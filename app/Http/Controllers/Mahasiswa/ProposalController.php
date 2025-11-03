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
     * Display the proposal submission form
     */
    public function index()
    {
        $mahasiswa = Auth::user();
        
        // Get latest proposal for this student
        $latestProposal = Proposal::where('mahasiswa_id', $mahasiswa->id)
            ->latest()
            ->first();
        
        // Get history of proposals
        $proposalHistory = Proposal::where('mahasiswa_id', $mahasiswa->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get available dosens
        $dosens = Dosen::where('status', 'aktif')
            ->orderBy('nama')
            ->get();
        
        return view('mahasiswa.proposal', compact('latestProposal', 'proposalHistory', 'dosens'));
    }

    /**
     * Handle proposal submission
     */
    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string|min:100',
            'dosen_id' => 'nullable|exists:dosens,id',
            'file_proposal' => 'required|file|mimes:pdf|max:10240', // 10MB
            'file_pitch_deck' => 'nullable|file|mimes:pdf,ppt,pptx|max:15360', // 15MB
        ], [
            'judul.required' => 'Judul tugas akhir wajib diisi',
            'deskripsi.required' => 'Deskripsi wajib diisi',
            'deskripsi.min' => 'Deskripsi minimal 100 karakter',
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
            $mahasiswa = Auth::user();
            
            // Calculate version number
            $lastProposal = Proposal::where('mahasiswa_id', $mahasiswa->id)
                ->latest('versi')
                ->first();
            $versi = $lastProposal ? $lastProposal->versi + 1 : 1;

            // Upload file proposal
            $fileProposalPath = null;
            if ($request->hasFile('file_proposal')) {
                $fileProposal = $request->file('file_proposal');
                $fileName = 'proposal_v' . $versi . '_' . time() . '.pdf';
                $fileProposalPath = $fileProposal->storeAs(
                    'proposals/' . $mahasiswa->id,
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
                    'pitch-decks/' . $mahasiswa->id,
                    $fileName,
                    'public'
                );
            }

            // Create proposal record
            $proposal = Proposal::create([
                'mahasiswa_id' => $mahasiswa->id,
                'dosen_id' => $request->dosen_id,
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'file_proposal' => $fileProposalPath,
                'file_pitch_deck' => $filePitchDeckPath,
                'versi' => $versi,
                'status' => 'diajukan',
                'tanggal_pengajuan' => now(),
            ]);

            return redirect()
                ->route('mahasiswa.proposal')
                ->with('success', 'Proposal berhasil diajukan! Menunggu review dari dosen pembimbing.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Save proposal as draft
     */
    public function saveDraft(Request $request)
    {
        try {
            $mahasiswa = Auth::user();
            
            // Check if there's existing draft
            $draft = Proposal::where('mahasiswa_id', $mahasiswa->id)
                ->where('status', 'draft')
                ->first();

            if ($draft) {
                // Update existing draft
                $draft->update([
                    'judul' => $request->judul,
                    'deskripsi' => $request->deskripsi,
                    'dosen_id' => $request->dosen_id,
                ]);
            } else {
                // Create new draft
                $draft = Proposal::create([
                    'mahasiswa_id' => $mahasiswa->id,
                    'dosen_id' => $request->dosen_id,
                    'judul' => $request->judul,
                    'deskripsi' => $request->deskripsi,
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
        if ($proposal->mahasiswa_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if (!Storage::disk('public')->exists($proposal->file_proposal)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::disk('public')->download($proposal->file_proposal);
    }
}