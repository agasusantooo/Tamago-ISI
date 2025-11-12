<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\UjianTA;
use App\Models\Produksi;
use App\Models\Proposal;

class UjianTAController extends Controller
{
    /**
     * Display ujian TA page (Informasi & Pendaftaran)
     */
    public function index()
    {
        $mahasiswa = Auth::user();
        
        // Get approved proposal
        $proposal = Proposal::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'disetujui')
            ->latest()
            ->first();
        
        if (!$proposal) {
            return redirect()->route('mahasiswa.proposal.index')
                ->with('error', 'Anda belum memiliki proposal yang disetujui.');
        }

        // Check if produksi akhir approved
        $produksi = Produksi::where('mahasiswa_id', $mahasiswa->id)
            ->where('proposal_id', $proposal->id)
            ->first();

        if (!$produksi || $produksi->status_produksi_akhir !== 'disetujui') {
            return redirect()->route('mahasiswa.produksi')
                ->with('error', 'Produksi akhir harus disetujui terlebih dahulu sebelum daftar ujian.');
        }
        
        // Get ujian TA data
        $ujianTA = UjianTA::where('mahasiswa_id', $mahasiswa->id)
            ->where('proposal_id', $proposal->id)
            ->first();
        
        return view('mahasiswa.ujian-ta', compact('proposal', 'produksi', 'ujianTA'));
    }

    /**
     * Store ujian TA registration
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_surat_pengantar' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB
            'file_transkrip_nilai' => 'required|file|mimes:pdf|max:5120', // 5MB
        ], [
            'file_surat_pengantar.required' => 'Surat pengantar wajib diunggah',
            'file_surat_pengantar.mimes' => 'Surat pengantar harus berformat PDF atau DOC',
            'file_surat_pengantar.max' => 'Surat pengantar maksimal 5 MB',
            'file_transkrip_nilai.required' => 'Transkrip nilai wajib diunggah',
            'file_transkrip_nilai.mimes' => 'Transkrip nilai harus berformat PDF',
            'file_transkrip_nilai.max' => 'Transkrip nilai maksimal 5 MB',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $mahasiswa = Auth::user();
            
            // Get proposal
            $proposal = Proposal::where('mahasiswa_id', $mahasiswa->id)
                ->where('status', 'disetujui')
                ->latest()
                ->first();
            
            if (!$proposal) {
                return back()->with('error', 'Proposal belum disetujui');
            }

            // Check if already registered
            $existing = UjianTA::where('mahasiswa_id', $mahasiswa->id)
                ->where('proposal_id', $proposal->id)
                ->first();

            if ($existing) {
                return back()->with('error', 'Anda sudah terdaftar ujian TA');
            }

            // Upload files
            $fileSuratPengantar = null;
            $fileTranskrip = null;

            if ($request->hasFile('file_surat_pengantar')) {
                $file = $request->file('file_surat_pengantar');
                $fileName = 'surat_pengantar_' . time() . '.' . $file->getClientOriginalExtension();
                $fileSuratPengantar = $file->storeAs('ujian-ta/' . $mahasiswa->id, $fileName, 'public');
            }

            if ($request->hasFile('file_transkrip_nilai')) {
                $file = $request->file('file_transkrip_nilai');
                $fileName = 'transkrip_' . time() . '.pdf';
                $fileTranskrip = $file->storeAs('ujian-ta/' . $mahasiswa->id, $fileName, 'public');
            }

            // Create ujian TA
            $ujianTA = UjianTA::create([
                'mahasiswa_id' => $mahasiswa->id,
                'proposal_id' => $proposal->id,
                'dosen_pembimbing_id' => $proposal->dosen_id,
                'file_surat_pengantar' => $fileSuratPengantar,
                'file_transkrip_nilai' => $fileTranskrip,
                'status_pendaftaran' => 'pengajuan_ujian',
                'status_ujian' => 'belum_ujian',
                'tanggal_daftar' => now(),
            ]);

            return redirect()
                ->route('mahasiswa.ujian-ta')
                ->with('success', 'Pendaftaran ujian TA berhasil! Menunggu jadwal dari koordinator.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display hasil ujian page
     */
    public function hasil()
    {
        $mahasiswa = Auth::user();
        
        // Get ujian TA data
        $ujianTA = UjianTA::where('mahasiswa_id', $mahasiswa->id)
            ->latest()
            ->first();

        if (!$ujianTA) {
            return redirect()->route('mahasiswa.ujian-ta')
                ->with('error', 'Anda belum terdaftar ujian TA');
        }

        // Check if ujian already done
        if ($ujianTA->status_ujian !== 'selesai_ujian') {
            return redirect()->route('mahasiswa.ujian-ta')
                ->with('error', 'Ujian belum dilaksanakan');
        }
        
        return view('mahasiswa.ujian-ta-hasil', compact('ujianTA'));
    }

    /**
     * Submit revisi pasca ujian
     */
    public function submitRevisi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_revisi' => 'required|file|mimes:pdf,doc,docx|max:51200', // 50MB
            'deskripsi_revisi' => 'nullable|string|max:1000',
        ], [
            'file_revisi.required' => 'File revisi wajib diunggah',
            'file_revisi.mimes' => 'File revisi harus berformat PDF atau DOC',
            'file_revisi.max' => 'File revisi maksimal 50 MB',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $mahasiswa = Auth::user();
            
            // Get ujian TA
            $ujianTA = UjianTA::where('mahasiswa_id', $mahasiswa->id)->latest()->first();
            
            if (!$ujianTA) {
                return back()->with('error', 'Data ujian tidak ditemukan');
            }

            // Upload file revisi
            $fileRevisi = null;
            if ($request->hasFile('file_revisi')) {
                $file = $request->file('file_revisi');
                $fileName = 'revisi_' . time() . '.' . $file->getClientOriginalExtension();
                $fileRevisi = $file->storeAs('ujian-ta/' . $mahasiswa->id, $fileName, 'public');
            }

            $ujianTA->update([
                'file_revisi' => $fileRevisi,
                'deskripsi_revisi' => $request->deskripsi_revisi,
                'status_revisi' => 'menunggu_persetujuan',
                'tanggal_submit_revisi' => now(),
            ]);

            return redirect()
                ->route('mahasiswa.ujian-ta.hasil')
                ->with('success', 'Revisi berhasil disubmit! Menunggu persetujuan dosen.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Download file
     */
    public function download($id, $type)
    {
        $ujianTA = UjianTA::findOrFail($id);
        
        // Check authorization
        if ($ujianTA->mahasiswa_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $filePath = null;
        switch ($type) {
            case 'surat':
                $filePath = $ujianTA->file_surat_pengantar;
                break;
            case 'transkrip':
                $filePath = $ujianTA->file_transkrip_nilai;
                break;
            case 'revisi':
                $filePath = $ujianTA->file_revisi;
                break;
        }

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::disk('public')->download($filePath);
    }
}