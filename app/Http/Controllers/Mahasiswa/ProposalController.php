<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Proposal;
use App\Models\Dosen;

class ProposalController extends Controller
{
    /**
     * Display a listing of the proposals.
     */
    public function index()
    {
        $badges = [
            'draft' => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Draft'],
            'diajukan' => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'Diajukan'],
            'review' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Dalam Review'],
            'revisi' => ['class' => 'bg-orange-100 text-orange-800', 'text' => 'Perlu Revisi'],
            'disetujui' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Disetujui'],
            'ditolak' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Ditolak'],
        ];

        $dummy1 = (object)[
            'id' => 1, 'versi' => 3, 'judul' => 'Analisis Semiotika pada Film Penyalin Cahaya',
            'tanggal_pengajuan' => \Illuminate\Support\Carbon::parse('2024-03-15'),
            'status' => 'disetujui',
        ];
        $dummy1->statusBadge = $badges[$dummy1->status];

        $dummy2 = (object)[
            'id' => 2, 'versi' => 2, 'judul' => 'Perancangan Tata Artistik Film Pendek "Senja"',
            'tanggal_pengajuan' => \Illuminate\Support\Carbon::parse('2024-02-20'),
            'status' => 'revisi',
        ];
        $dummy2->statusBadge = $badges[$dummy2->status];

        $dummy3 = (object)[
            'id' => 3, 'versi' => 1, 'judul' => 'Studi Kasus Penyutradaraan dalam Film Dokumenter',
            'tanggal_pengajuan' => \Illuminate\Support\Carbon::parse('2024-01-10'),
            'status' => 'ditolak',
        ];
        $dummy3->statusBadge = $badges[$dummy3->status];

        $proposalHistory = collect([$dummy1, $dummy2, $dummy3]);

        return view('mahasiswa.proposal.index', [
            'proposalHistory' => $proposalHistory,
        ]);
    }

    /**
     * Show the form for creating a new proposal.
     */
    public function create()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $mahasiswaNim = $mahasiswa ? $mahasiswa->nim : null;

        $latestProposal = $mahasiswaNim ? Proposal::where('mahasiswa_nim', $mahasiswaNim)
            ->latest()
            ->first() : null;

        $dosens = Dosen::where('status', 'aktif')
            ->orderBy('nama')
            ->get();
        
        return view('mahasiswa.proposal.create', [
            'proposal' => null, // Set to null for create form
            'dosens' => $dosens,
        ]);
    }

    /**
     * Display the specified proposal.
     */
    public function show($id)
    {
        // This is a temporary implementation using dummy data to match the index method.
        $badges = [
            'draft' => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Draft'],
            'diajukan' => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'Diajukan'],
            'review' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Dalam Review'],
            'revisi' => ['class' => 'bg-orange-100 text-orange-800', 'text' => 'Perlu Revisi'],
            'disetujui' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Disetujui'],
            'ditolak' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Ditolak'],
        ];

        $dummyData = [
            1 => (object)[
                'id' => 1, 'versi' => 3, 'judul' => 'Analisis Semiotika pada Film Penyalin Cahaya',
                'deskripsi' => 'Penelitian ini berfokus pada analisis semiotika mendalam terhadap elemen-elemen visual dan naratif dalam film "Penyalin Cahaya" untuk mengungkap makna-makna tersembunyi dan kritik sosial yang disampaikan.',
                'rumpun_ilmu' => 'Pengkajian Seni',
                'tanggal_pengajuan' => \Illuminate\Support\Carbon::parse('2024-03-15'),
                'status' => 'disetujui',
                'feedback' => 'Proposal sangat baik dan relevan. Silakan dilanjutkan ke tahap berikutnya.',
                'dosen' => (object)['nama' => 'Dr. Anisa Lestari', 'gelar' => 'M.Sn.'],
                'file_proposal' => '#',
            ],
            2 => (object)[
                'id' => 2, 'versi' => 2, 'judul' => 'Perancangan Tata Artistik Film Pendek "Senja"',
                'deskripsi' => 'Proposal ini menguraikan konsep perancangan tata artistik untuk sebuah film pendek berjudul "Senja", yang berfokus pada penciptaan atmosfer melankolis melalui warna, set, dan properti.',
                'rumpun_ilmu' => 'Penciptaan Seni',
                'tanggal_pengajuan' => \Illuminate\Support\Carbon::parse('2024-02-20'),
                'status' => 'revisi',
                'feedback' => 'Konsep sudah bagus, namun perlu detail lebih lanjut pada breakdown budget dan timeline produksi. Mohon direvisi.',
                'dosen' => (object)['nama' => 'Budi Dharmawan', 'gelar' => 'S.Sn., M.A.'],
                'file_proposal' => '#',
            ],
            3 => (object)[
                'id' => 3, 'versi' => 1, 'judul' => 'Studi Kasus Penyutradaraan dalam Film Dokumenter',
                'deskripsi' => 'Mengajukan studi kasus mengenai pendekatan penyutradaraan dalam film dokumenter "The Act of Killing", menganalisis bagaimana sutradara membangun narasi dan memanipulasi emosi penonton.',
                'rumpun_ilmu' => 'Media Rekam',
                'tanggal_pengajuan' => \Illuminate\Support\Carbon::parse('2024-01-10'),
                'status' => 'ditolak',
                'feedback' => 'Topik yang diajukan sudah terlalu sering dibahas dan kurang memiliki kebaruan. Silakan cari topik lain yang lebih orisinal.',
                'dosen' => (object)['nama' => 'Dr. Anisa Lestari', 'gelar' => 'M.Sn.'],
                'file_proposal' => '#',
            ],
        ];

        $proposal = $dummyData[$id] ?? null;

        if (!$proposal) {
            abort(404, 'Proposal tidak ditemukan');
        }
        $proposal->statusBadge = $badges[$proposal->status];

        return view('mahasiswa.proposal.show', [
            'proposal' => $proposal,
        ]);
    }

    /**
     * Store a newly created proposal in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string|min:100',
            'rumpun_ilmu' => 'required|string|in:"Penciptaan Seni","Pengkajian Seni","Media Rekam"',
            'dosen_id' => 'nullable|exists:dosens,id',
            'file_proposal' => 'required|file|mimes:pdf|max:10240', // 10MB
            'file_pitch_deck' => 'nullable|file|mimes:pdf,ppt,pptx|max:15360', // 15MB
        ], [
            'judul.required' => 'Judul tugas akhir wajib diisi',
            'deskripsi.required' => 'Deskripsi wajib diisi',
            'deskripsi.min' => 'Deskripsi minimal 100 karakter',
            'rumpun_ilmu.required' => 'Rumpun ilmu wajib dipilih',
            'file_proposal.required' => 'File proposal wajib diunggah',
            'file_proposal.mimes' => 'File proposal harus berformat PDF',
            'file_proposal.max' => 'File proposal maksimal 10 MB',
            'file_pitch_deck.mimes' => 'File pitch deck harus berformat PDF/PPT/PPTX',
            'file_pitch_deck.max' => 'File pitch deck maksimal 15 MB',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $mahasiswa = Auth::user()->mahasiswa;
            if (!$mahasiswa) {
                return back()->with('error', 'Profil mahasiswa tidak ditemukan. Silakan lengkapi profil mahasiswa Anda.')->withInput();
            }
            
            // Calculate version number
            $lastProposal = Proposal::where('mahasiswa_nim', $mahasiswa->nim)
                ->latest('versi')
                ->first();
            $versi = $lastProposal ? $lastProposal->versi + 1 : 1;

            // Upload file proposal
            $fileProposalPath = null;
            if ($request->hasFile('file_proposal')) {
                $fileProposal = $request->file('file_proposal');
                $fileName = 'proposal_v' . $versi . '_' . time() . '.pdf';
                $fileProposalPath = $fileProposal->storeAs(
                    'proposals/' . $mahasiswa->nim,
                    $fileName,
                    'public'
                );
            }

            // Upload file pitch deck (optional)
            $filePitchDeckPath = null;
            if ($request->hasFile('file_pitch_deck')) {
                $filePitchDeck = $request->file('file_pitch_deck');
                $extension = $filePitchDeck->getClientOriginalExtension();
                $fileName = 'pitchdeck_v' . $versi . '_' . time() . '.' . $extension;
                $filePitchDeckPath = $filePitchDeck->storeAs(
                    'pitch-decks/' . $mahasiswa->nim,
                    $fileName,
                    'public'
                );
            }

            // Create proposal record
            $proposal = Proposal::create([
                'mahasiswa_nim' => $mahasiswa->nim,
                'dosen_id' => $request->dosen_id,
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'rumpun_ilmu' => $request->rumpun_ilmu,
                'file_proposal' => $fileProposalPath,
                'file_pitch_deck' => $filePitchDeckPath,
                'versi' => $versi,
                'status' => 'diajukan',
                'tanggal_pengajuan' => now(),
            ]);

            return redirect()
                ->route('mahasiswa.proposal.index')
                ->with('success', 'Proposal berhasil diajukan! Menunggu review dari dosen pembimbing.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified proposal.
     */
    public function edit(Proposal $proposal_real) // Route model binding still happens, but we ignore it for the dummy data
    {
        // This is a temporary implementation using dummy data.
        $id = request()->route('proposal'); // Get the ID from the route

        $dummyData = [
            1 => (object)['id' => 1, 'judul' => 'Analisis Semiotika...', 'status' => 'disetujui'],
            2 => (object)[
                'id' => 2, 'judul' => 'Perancangan Tata Artistik Film Pendek "Senja"', 
                'deskripsi' => 'Proposal ini menguraikan konsep perancangan...',
                'rumpun_ilmu' => 'Penciptaan Seni',
                'dosen_id' => 1, // Assuming a dummy dosen ID
                'status' => 'revisi',
                'file_proposal' => 'proposals/dummy/proposal_v2.pdf',
                'file_pitch_deck' => 'pitch-decks/dummy/pitch_v2.pptx',
            ],
            3 => (object)['id' => 3, 'judul' => 'Studi Kasus Penyutradaraan...', 'status' => 'ditolak'],
        ];

        $proposal = $dummyData[$id] ?? null;

        if (!$proposal || $proposal->status !== 'revisi') {
            return redirect()
                ->route('mahasiswa.proposal.index')
                ->with('error', 'Proposal ini tidak dapat diedit.');
        }

        $dosens = Dosen::where('status', 'aktif')
            ->orderBy('nama')
            ->get();
        
        return view('mahasiswa.proposal.create', [
            'proposal' => $proposal,
            'dosens' => $dosens,
        ]);
    }

    /**
     * Update the specified proposal in storage.
     */
    public function update(Request $request, Proposal $proposal)
    {
        // Authorization check
        $mahasiswa = Auth::user()->mahasiswa;
        if (!$mahasiswa || $proposal->mahasiswa_nim !== $mahasiswa->nim) {
            abort(403, 'Anda tidak diizinkan untuk mengupdate proposal ini.');
        }

        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string|min:100',
            'rumpun_ilmu' => 'required|string|in:"Penciptaan Seni","Pengkajian Seni","Media Rekam"',
            'dosen_id' => 'nullable|exists:dosens,id',
            'file_proposal' => 'nullable|file|mimes:pdf|max:10240', // Optional on update
            'file_pitch_deck' => 'nullable|file|mimes:pdf,ppt,pptx|max:15360', // Optional on update
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $proposalData = $request->only(['judul', 'deskripsi', 'rumpun_ilmu', 'dosen_id']);
            
            // Increment version and set status to 'diajukan'
            $proposalData['versi'] = $proposal->versi + 1;
            $proposalData['status'] = 'diajukan';
            $proposalData['tanggal_pengajuan'] = now(); // Update submission date
            $proposalData['feedback'] = null; // Clear previous feedback

            // Handle file uploads if new ones are provided
            if ($request->hasFile('file_proposal')) {
                // Optionally, delete the old file
                if ($proposal->file_proposal) {
                    Storage::disk('public')->delete($proposal->file_proposal);
                }
                $fileProposal = $request->file('file_proposal');
                $fileName = 'proposal_v' . $proposalData['versi'] . '_' . time() . '.pdf';
                $proposalData['file_proposal'] = $fileProposal->storeAs('proposals/' . $mahasiswa->nim, $fileName, 'public');
            }

            if ($request->hasFile('file_pitch_deck')) {
                if ($proposal->file_pitch_deck) {
                    Storage::disk('public')->delete($proposal->file_pitch_deck);
                }
                $filePitchDeck = $request->file('file_pitch_deck');
                $extension = $filePitchDeck->getClientOriginalExtension();
                $fileName = 'pitchdeck_v' . $proposalData['versi'] . '_' . time() . '.' . $extension;
                $proposalData['file_pitch_deck'] = $filePitchDeck->storeAs('pitch-decks/' . $mahasiswa->nim, $fileName, 'public');
            }

            $proposal->update($proposalData);

            return redirect()
                ->route('mahasiswa.proposal.index')
                ->with('success', 'Revisi proposal berhasil diajukan kembali!');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
    
    /**
     * Save proposal as draft
     */
    public function saveDraft(Request $request)
    {
        try {
            $mahasiswa = Auth::user()->mahasiswa;
            if (!$mahasiswa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profil mahasiswa tidak ditemukan. Silakan lengkapi profil mahasiswa Anda.'
                ], 400);
            }

            // Check if there's existing draft
            $draft = Proposal::where('mahasiswa_nim', $mahasiswa->nim)
                ->where('status', 'draft')
                ->first();

            if ($draft) {
                // Update existing draft
                $draft->update([
                    'judul' => $request->judul,
                    'deskripsi' => $request->deskripsi,
                    'rumpun_ilmu' => $request->rumpun_ilmu,
                    'dosen_id' => $request->dosen_id,
                ]);
            } else {
                // Create new draft
                $draft = Proposal::create([
                    'mahasiswa_nim' => $mahasiswa->nim,
                    'dosen_id' => $request->dosen_id,
                    'judul' => $request->judul,
                    'deskripsi' => $request->deskripsi,
                    'rumpun_ilmu' => $request->rumpun_ilmu,
                    'status' => 'draft',
                    'versi' => 0,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Draft berhasil disimpan!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan draft: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download proposal file
     */
    public function download($id)
    {
        $proposal = Proposal::findOrFail($id);
        
        // Check if user owns this proposal
        $mahasiswa = Auth::user()->mahasiswa;
        if (!$mahasiswa || $proposal->mahasiswa_nim !== $mahasiswa->nim) {
            abort(403, 'Unauthorized action.');
        }

        if (!Storage::disk('public')->exists($proposal->file_proposal)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::disk('public')->download($proposal->file_proposal);
    }
}