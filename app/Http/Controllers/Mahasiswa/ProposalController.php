<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Proposal;
use App\Models\Dosen;
use App\Models\Bimbingan;

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

        $mahasiswa = Auth::user()->mahasiswa;
        $proposalHistory = collect();
        
        // Ambil semua proposal dari database untuk mahasiswa yang login
        if ($mahasiswa && $mahasiswa->nim) {
            $proposals = Proposal::where('mahasiswa_nim', $mahasiswa->nim)
                ->orderBy('created_at', 'desc')
                ->get();
            
            $proposalHistory = $proposals->map(function($proposal) use ($badges) {
                $proposal->statusBadge = $badges[$proposal->status] ?? $badges['draft'];
                return $proposal;
            });
        }

        return view('mahasiswa.proposal.index', [
            'proposalHistory' => $proposalHistory,
        ]);
    }

    /**
     * Show the form for creating a new proposal.
     */
    public function create()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;
        $mahasiswaNim = $mahasiswa ? $mahasiswa->nim : null;

        // Validasi: mahasiswa harus melakukan bimbingan minimal 6x sebelum mengajukan proposal
        $bimbinganCount = 0;
        if ($mahasiswa) {
            $bimbinganCount = Bimbingan::where(function($q) use ($user, $mahasiswa) {
                    $q->where('mahasiswa_id', $user->id)
                      ->orWhere('nim', $mahasiswa->nim);
                })
                ->where('status', 'disetujui')
                ->count();
        }

        if ($bimbinganCount < 6) {
            return redirect()->route('mahasiswa.bimbingan.index')
                ->with('warning', 'Anda harus melakukan bimbingan minimal 6 kali sebelum mengajukan proposal. Saat ini Anda baru melakukan ' . $bimbinganCount . ' bimbingan yang disetujui.');
        }

        $latestProposal = $mahasiswaNim ? Proposal::where('mahasiswa_nim', $mahasiswaNim)
            ->latest()
            ->first() : null;

        $dosens = Dosen::where('status', 'aktif')
            ->orderBy('nama')
            ->get();
        
        return view('mahasiswa.proposal.create', [
            'proposal' => null, // Set to null for create form
            'dosens' => $dosens,
            'bimbinganCount' => $bimbinganCount,
        ]);
    }

    /**
     * Display the specified proposal.
     */
    public function show($id)
    {
        $badges = [
            'draft' => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Draft'],
            'diajukan' => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'Diajukan'],
            'review' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Dalam Review'],
            'revisi' => ['class' => 'bg-orange-100 text-orange-800', 'text' => 'Perlu Revisi'],
            'disetujui' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Disetujui'],
            'ditolak' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Ditolak'],
        ];

        // Ambil proposal dari database
        $proposal = Proposal::where('id', $id)
            ->with('dosen')
            ->first();

        if (!$proposal) {
            abort(404, 'Proposal tidak ditemukan');
        }

        // Cek authorization - user hanya bisa melihat proposal milik mereka sendiri
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;
        if (!$mahasiswa || $proposal->mahasiswa_nim !== $mahasiswa->nim) {
            abort(403, 'Unauthorized');
        }

        $proposal->statusBadge = $badges[$proposal->status] ?? $badges['draft'];

        return view('mahasiswa.proposal.show', [
            'proposal' => $proposal,
        ]);
    }

    /**
     * Store a newly created proposal in storage.
     */
    public function store(Request $request)
    {
        // Validasi bimbingan minimal 6x
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;
        $bimbinganCount = Bimbingan::where(function($q) use ($user, $mahasiswa) {
                $q->where('mahasiswa_id', $user->id)
                  ->orWhere('nim', $mahasiswa->nim);
            })
            ->where('status', 'disetujui')
            ->count();

        if ($bimbinganCount < 6) {
            return back()->with('error', 'Anda harus melakukan bimbingan minimal 6 kali sebelum mengajukan proposal. Saat ini Anda baru melakukan ' . $bimbinganCount . ' bimbingan yang disetujui.');
        }

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
    public function edit(Proposal $proposal)
    {
        $mahasiswa = Auth::user()->mahasiswa;
        
        // Cek authorization
        if (!$mahasiswa || $proposal->mahasiswa_nim !== $mahasiswa->nim) {
            return redirect()->route('mahasiswa.proposal.index')
                ->with('error', 'Unauthorized');
        }

        // Hanya proposal dengan status 'revisi' yang bisa diedit
        if ($proposal->status !== 'revisi') {
            return redirect()->route('mahasiswa.proposal.index')
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