<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use App\Models\StoryConference;
use App\Models\Proposal;
use Carbon\Carbon;

class StoryConferenceController extends Controller
{
    /**
     * Display story conference page
     */
    public function index()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.proposal')
                ->with('error', 'Profil mahasiswa tidak ditemukan.');
        }
        // Get the latest proposal for this mahasiswa (might not be approved yet)
        $proposal = Proposal::where('mahasiswa_nim', $mahasiswa->nim)
            ->latest()
            ->first();

        // If the student has no proposal at all, redirect to proposal page
        if (!$proposal) {
            return redirect()->route('mahasiswa.proposal')
                ->with('error', 'Anda belum memiliki proposal. Silakan ajukan proposal terlebih dahulu.');
        }

        // Find existing story conference registration defensively based on available columns
        $storyConference = null;
        $table = 'story_conference';

        // Determine which mahasiswa column exists (nim-based or id-based)
        if (Schema::hasTable($table)) {
            if (Schema::hasColumn($table, 'mahasiswa_nim')) {
                $query = StoryConference::where('mahasiswa_nim', $mahasiswa->nim);
            } elseif (Schema::hasColumn($table, 'mahasiswa_id')) {
                // If mahasiswa_id exists but mahasiswa table uses nim as PK, attempt to use user->mahasiswa->id if present
                $mahasiswaId = property_exists($mahasiswa, 'id') ? $mahasiswa->id : null;
                $query = StoryConference::where('mahasiswa_id', $mahasiswaId);
            } else {
                $query = StoryConference::query();
            }

            // Determine proposal column name
            if (Schema::hasColumn($table, 'proposal_id')) {
                $query->where('proposal_id', $proposal->id);
            } elseif (Schema::hasColumn($table, 'proposals_id')) {
                $query->where('proposals_id', $proposal->id);
            }

            $storyConference = $query->first();
        }
        
    // Get jadwal story conference (hardcoded untuk demo, bisa dari database)
        $jadwalStoryConference = [
            [
                'jenis' => 'Presentasi Tugas Akhir',
                'tanggal' => '15 Desember 2024',
                'waktu' => '08:00 - 17:00 WIB',
                'tempat' => 'Auditorium Utama Informatika',
                'deskripsi' => 'Presentasi hasil penelitian tugas akhir mahasiswa dengan durasi 15 menit per mahasiswa',
                'persyaratan' => [
                    'Mahasiswa tingkat akhir yang telah menyelesaikan minimal 120 SKS',
                    'Memiliki proposal tugas akhir yang telah disetujui pembimbing',
                    'Menyiapkan materi presentasi dalam format PDF atau PPT'
                ],
                'bg_color' => 'bg-blue-50',
                'border_color' => 'border-blue-500'
            ],
            [
                'jenis' => 'Presentasi Tugas Akhir',
                'tanggal' => '20-22 Desember 2024',
                'waktu' => '09:00 - 16:00 WIB',
                'tempat' => 'Gedung Exhibition',
                'deskripsi' => 'Pameran produk dan inovasi teknologi hasil karya mahasiswa beserta presentasi',
                'persyaratan' => [
                    'Mahasiswa tingkat akhir dengan proposal yang disetujui',
                    'Membawa produk atau prototype untuk dipamerkan',
                    'Menyiapkan poster atau standing banner'
                ],
                'bg_color' => 'bg-yellow-50',
                'border_color' => 'border-yellow-500'
            ]
        ];
        
        // If proposal exists but is not approved, show the page with a clear message rather than redirecting
        if ($proposal->status !== 'disetujui') {
            // pass a flag the view can use to show registration disabled state and the current proposal status/feedback
            return view('mahasiswa.story-conference', compact('proposal', 'storyConference', 'jadwalStoryConference'))
                ->with('error', 'Proposal Anda belum disetujui. Silakan cek status proposal atau hubungi pembimbing.');
        }

        return view('mahasiswa.story-conference', compact('proposal', 'storyConference', 'jadwalStoryConference'));
    }

    /**
     * Store story conference registration
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul_karya' => 'required|string|max:255',
            'slot_waktu' => 'required|string',
            'file_presentasi' => 'required|file|mimes:pdf,ppt,pptx|max:10240', // 10MB
        ], [
            'judul_karya.required' => 'Judul karya wajib diisi',
            'slot_waktu.required' => 'Pilih slot waktu wajib dipilih',
            'file_presentasi.required' => 'File presentasi wajib diunggah',
            'file_presentasi.mimes' => 'File presentasi harus berformat PDF, PPT, atau PPTX',
            'file_presentasi.max' => 'File presentasi maksimal 10 MB',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $mahasiswa = Auth::user()->mahasiswa;
            if (!$mahasiswa) {
                return back()->with('error', 'Profil mahasiswa tidak ditemukan.')->withInput();
            }
            
            // Get proposal
            $proposal = Proposal::where('mahasiswa_nim', $mahasiswa->nim)
                ->where('status', 'disetujui')
                ->latest()
                ->first();
            
            if (!$proposal) {
                return back()->with('error', 'Proposal belum disetujui');
            }

            // Check if already registered
            $existing = StoryConference::where('mahasiswa_nim', $mahasiswa->nim)
                ->where('proposal_id', $proposal->id)
                ->first();

            if ($existing) {
                return back()->with('error', 'Anda sudah terdaftar di Story Conference');
            }

            // Upload file presentasi
            $filePath = null;
            if ($request->hasFile('file_presentasi')) {
                $file = $request->file('file_presentasi');
                $fileName = 'storyconf_' . time() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs(
                    'story-conference/' . $mahasiswa->nim,
                    $fileName,
                    'public'
                );
            }

            // Create registration
            // Build data array - ensure mahasiswa_nim (FK to mahasiswa.nim) is always provided
            $data = [
                'mahasiswa_nim' => $mahasiswa->nim,
                'mahasiswa_id' => $mahasiswa->id,
                'proposal_id' => $proposal->id,
                'proposals_id' => $proposal->id,  // duplicate for backward compatibility
                'dosen_id' => $proposal->dosen_id,
                'judul_karya' => $request->judul_karya,
                'slot_waktu' => $request->slot_waktu,
                'file_presentasi' => $filePath,
                'status' => 'menunggu_persetujuan',
                'tanggal_daftar' => now(),
            ];

            $storyConference = StoryConference::create($data);

            return redirect()
                ->route('mahasiswa.story-conference.index')
                ->with('success', 'Pendaftaran Story Conference berhasil! Menunggu persetujuan panitia.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Download file presentasi
     */
    public function download($id)
    {
        $storyConference = StoryConference::findOrFail($id);
        
        // Check authorization
        $mahasiswa = Auth::user()->mahasiswa;
        if (!$mahasiswa || $storyConference->mahasiswa_nim !== $mahasiswa->nim) {
            abort(403, 'Unauthorized action.');
        }

        if (!$storyConference->file_presentasi || !Storage::disk('public')->exists($storyConference->file_presentasi)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::disk('public')->download($storyConference->file_presentasi);
    }

    /**
     * Cancel registration
     */
    public function cancel($id)
    {
        $storyConference = StoryConference::findOrFail($id);
        
        // Check authorization
        $mahasiswa = Auth::user()->mahasiswa;
        if (!$mahasiswa || $storyConference->mahasiswa_nim !== $mahasiswa->nim) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow cancel if still pending
        if ($storyConference->status !== 'menunggu_persetujuan') {
            return back()->with('error', 'Pendaftaran tidak dapat dibatalkan karena sudah diproses.');
        }

        $storyConference->delete();

        return redirect()
            ->route('mahasiswa.story-conference.index')
            ->with('success', 'Pendaftaran Story Conference berhasil dibatalkan.');
    }
}