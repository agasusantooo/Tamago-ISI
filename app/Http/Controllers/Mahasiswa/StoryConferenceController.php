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
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;
        
        $statusBadges = [
            'menunggu_persetujuan' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Menunggu Persetujuan'],
            'disetujui' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Disetujui'],
            'ditolak' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Ditolak'],
            'selesai' => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'Selesai'],
        ];

        // Ambil story conference history dari database
        $history = collect();
        if ($mahasiswa) {
            $storyConferences = StoryConference::where('mahasiswa_nim', $mahasiswa->nim)
                ->orderBy('tanggal_daftar', 'desc')
                ->get();
            
            $history = $storyConferences->map(function($sc) use ($statusBadges) {
                $sc->statusBadge = $statusBadges[$sc->status] ?? $statusBadges['menunggu_persetujuan'];
                return $sc;
            });
        }

        $jadwalStoryConference = [
            [
                'jenis' => 'Story Conference',
                'tanggal_mulai' => Carbon::parse('2024-12-18')->format('d M Y'),
                'tanggal_akhir' => Carbon::parse('2024-12-20')->format('d M Y'),
                'tanggal' => Carbon::parse('2024-12-18')->format('d M Y'),
                'waktu' => '09:00 - 15:00 WIB',
                'tempat' => 'Ruang Diskusi Kreatif',
                'deskripsi' => 'Sesi diskusi dan review ide cerita, skenario, dan konsep visual untuk proyek tugas akhir. Setiap mahasiswa akan mempresentasikan draf awal karyanya.',
                'persyaratan' => [
                    'Proposal tugas akhir telah disetujui oleh pembimbing.',
                    'Menyiapkan draf skenario atau naskah awal.',
                    'Menyiapkan materi presentasi konsep (moodboard, referensi visual, dll).'
                ],
                'bg_color' => 'bg-purple-50',
                'border_color' => 'border-purple-500'
            ]
        ];
        
        return view('mahasiswa.story-conference.index', compact('history', 'jadwalStoryConference'));
    }

    /**
     * Show the form for creating a new registration.
     */
    public function create()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.proposal.index')
                ->with('error', 'Profil mahasiswa tidak ditemukan.');
        }

        $proposal = Proposal::where('mahasiswa_nim', $mahasiswa->nim)
            ->latest()
            ->first();

        if (!$proposal) {
            return redirect()->route('mahasiswa.proposal.index')
                ->with('error', 'Anda belum memiliki proposal. Silakan ajukan proposal terlebih dahulu.');
        }

        $storyConference = StoryConference::where('mahasiswa_nim', $mahasiswa->nim)
            ->where('proposal_id', $proposal->id)
            ->first();
        
        // Data jadwal story conference
        $jadwalStoryConference = [
            [
                'jenis' => 'Story Conference',
                'tanggal_mulai' => Carbon::parse('2024-12-18')->format('d M Y'),
                'tanggal_akhir' => Carbon::parse('2024-12-20')->format('d M Y'),
                'tanggal' => Carbon::parse('2024-12-18')->format('d M Y'),
                'waktu' => '09:00 - 15:00 WIB',
                'tempat' => 'Ruang Diskusi Kreatif',
                'deskripsi' => 'Sesi diskusi dan review ide cerita, skenario, dan konsep visual untuk proyek tugas akhir. Setiap mahasiswa akan mempresentasikan draf awal karyanya.',
                'persyaratan' => [
                    'Proposal tugas akhir telah disetujui oleh pembimbing.',
                    'Menyiapkan draf skenario atau naskah awal.',
                    'Menyiapkan materi presentasi konsep (moodboard, referensi visual, dll).'
                ],
                'bg_color' => 'bg-purple-50',
                'border_color' => 'border-purple-500'
            ]
        ];
        
        if ($proposal->status !== 'disetujui') {
            return view('mahasiswa.story-conference.create', compact('proposal', 'storyConference', 'jadwalStoryConference'))
                ->with('error', 'Proposal Anda belum disetujui. Silakan cek status proposal atau hubungi pembimbing.');
        }

        return view('mahasiswa.story-conference.create', compact('proposal', 'storyConference', 'jadwalStoryConference'));
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