<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Produksi;
use App\Models\Proposal;

class ProduksiController extends Controller
{
    /**
     * Display produksi page
     */
    public function index()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.proposal')
                ->with('error', 'Profil mahasiswa tidak ditemukan.');
        }
        
        // Get approved proposal
        $proposal = Proposal::where('mahasiswa_nim', $mahasiswa->nim)
            ->where('status', 'disetujui')
            ->latest()
            ->first();
        
        if (!$proposal) {
            return redirect()->route('mahasiswa.proposal')
                ->with('error', 'Anda belum memiliki proposal yang disetujui.');
        }
        
        // Get produksi data (pra produksi dan produksi akhir)
        $produksi = Produksi::where('mahasiswa_id', $mahasiswa->id)
            ->where('proposal_id', $proposal->id)
            ->first();
        
        return view('mahasiswa.produksi', compact('proposal', 'produksi'));
    }

    /**
     * Store or update pra produksi
     */
    public function storePraProduksi(Request $request)
    {
        // Debug logging to inspect incoming files when upload fails
        \Illuminate\Support\Facades\Log::info('storePraProduksi called', [
            'has_file_skenario' => $request->hasFile('file_skenario'),
            'has_file_storyboard' => $request->hasFile('file_storyboard'),
            'has_file_dokumen' => $request->hasFile('file_dokumen_pendukung'),
            'files' => array_keys($request->files->all()),
        ]);

        $validator = Validator::make($request->all(), [
            'file_skenario' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB
            'file_storyboard' => 'required|file|mimes:pdf,jpg,jpeg,png|max:20480', // 20MB
            'file_dokumen_pendukung' => 'nullable|file|mimes:pdf,doc,docx,zip|max:20480', // 20MB
        ], [
            'file_skenario.required' => 'File skenario wajib diunggah',
            'file_skenario.mimes' => 'File skenario harus berformat PDF atau DOC',
            'file_skenario.max' => 'File skenario maksimal 10 MB',
            'file_storyboard.required' => 'File storyboard wajib diunggah',
            'file_storyboard.mimes' => 'File storyboard harus berformat PDF, JPG, atau PNG',
            'file_storyboard.max' => 'File storyboard maksimal 20 MB',
            'file_dokumen_pendukung.mimes' => 'File dokumen pendukung harus berformat PDF, DOC, atau ZIP',
            'file_dokumen_pendukung.max' => 'File dokumen pendukung maksimal 20 MB',
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

            // Check if produksi exists
            $produksi = Produksi::where('mahasiswa_id', $mahasiswa->id)
                ->where('proposal_id', $proposal->id)
                ->first();

            // Upload files
            $fileSkenario = null;
            $fileStoryboard = null;
            $fileDokumenPendukung = null;

            // if browser didn't send files for some reason, return clear error
            if (! $request->hasFile('file_skenario') && ! $request->hasFile('file_storyboard') && ! $request->hasFile('file_dokumen_pendukung')) {
                return back()->with('error', 'Tidak ada file yang terdeteksi. Pastikan Anda memilih file sebelum submit.')->withInput();
            }
            if ($request->hasFile('file_skenario')) {
                $file = $request->file('file_skenario');
                $fileName = 'skenario_' . time() . '.' . $file->getClientOriginalExtension();
                $fileSkenario = $file->storeAs('produksi/' . $mahasiswa->id, $fileName, 'public');
            }

            if ($request->hasFile('file_storyboard')) {
                $file = $request->file('file_storyboard');
                $fileName = 'storyboard_' . time() . '.' . $file->getClientOriginalExtension();
                $fileStoryboard = $file->storeAs('produksi/' . $mahasiswa->id, $fileName, 'public');
            }

            if ($request->hasFile('file_dokumen_pendukung')) {
                $file = $request->file('file_dokumen_pendukung');
                $fileName = 'dokumen_' . time() . '.' . $file->getClientOriginalExtension();
                $fileDokumenPendukung = $file->storeAs('produksi/' . $mahasiswa->id, $fileName, 'public');
            }

            if ($produksi) {
                // Update existing
                $produksi->update([
                    'file_skenario' => $fileSkenario ?? $produksi->file_skenario,
                    'file_storyboard' => $fileStoryboard ?? $produksi->file_storyboard,
                    'file_dokumen_pendukung' => $fileDokumenPendukung ?? $produksi->file_dokumen_pendukung,
                    'status_pra_produksi' => 'menunggu_review',
                    'tanggal_upload_pra' => now(),
                ]);
            } else {
                // Create new
                $produksi = Produksi::create([
                    'mahasiswa_id' => $mahasiswa->id,
                    'proposal_id' => $proposal->id,
                    'dosen_id' => $proposal->dosen_id,
                    'file_skenario' => $fileSkenario,
                    'file_storyboard' => $fileStoryboard,
                    'file_dokumen_pendukung' => $fileDokumenPendukung,
                    'status_pra_produksi' => 'menunggu_review',
                    'status_produksi_akhir' => 'belum_upload',
                    'tanggal_upload_pra' => now(),
                ]);
            }

            return redirect()
                ->route('mahasiswa.produksi.index')
                ->with('success', 'File pra produksi berhasil diunggah! Menunggu review dari dosen pembimbing.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Store or update produksi akhir
     */
    public function storeProduksiAkhir(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_produksi_akhir' => 'required|file|mimes:mp4,mov,avi,mkv|max:512000', // 500MB
            'catatan_produksi' => 'nullable|string|max:1000',
        ], [
            'file_produksi_akhir.required' => 'File produksi akhir wajib diunggah',
            'file_produksi_akhir.mimes' => 'File produksi akhir harus berformat MP4, MOV, AVI, atau MKV',
            'file_produksi_akhir.max' => 'File produksi akhir maksimal 500 MB',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $mahasiswa = Auth::user()->mahasiswa;
            if (!$mahasiswa) {
                return back()->with('error', 'Profil mahasiswa tidak ditemukan.')->withInput();
            }
            
            // Get produksi
            $produksi = Produksi::where('mahasiswa_id', $mahasiswa->id)->first();
            
            if (!$produksi) {
                return back()->with('error', 'Mohon upload pra produksi terlebih dahulu');
            }

            // Check if pra produksi approved
            if ($produksi->status_pra_produksi !== 'disetujui') {
                return back()->with('error', 'Pra produksi harus disetujui terlebih dahulu sebelum upload produksi akhir');
            }

            // Upload file produksi akhir
            $fileProduksiAkhir = null;
            if ($request->hasFile('file_produksi_akhir')) {
                $file = $request->file('file_produksi_akhir');
                $fileName = 'produksi_akhir_' . time() . '.' . $file->getClientOriginalExtension();
                $fileProduksiAkhir = $file->storeAs('produksi/' . $mahasiswa->id, $fileName, 'public');
            }

            $produksi->update([
                'file_produksi_akhir' => $fileProduksiAkhir,
                'catatan_produksi' => $request->catatan_produksi,
                'status_produksi_akhir' => 'menunggu_review',
                'tanggal_upload_akhir' => now(),
            ]);

            return redirect()
                ->route('mahasiswa.produksi.index')
                ->with('success', 'File produksi akhir berhasil diunggah! Menunggu review dari dosen pembimbing.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Store or update luaran tambahan
     */
    public function storeLuaranTambahan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_luaran_tambahan' => 'required|file|mimes:pdf,jpg,jpeg,png,zip|max:51200', // 50MB
        ], [
            'file_luaran_tambahan.required' => 'File luaran tambahan wajib diunggah',
            'file_luaran_tambahan.mimes' => 'File harus berformat PDF, JPG, PNG, atau ZIP',
            'file_luaran_tambahan.max' => 'File maksimal 50 MB',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $mahasiswa = Auth::user()->mahasiswa;
            if (!$mahasiswa) {
                return back()->with('error', 'Profil mahasiswa tidak ditemukan.')->withInput();
            }
            
            // Get produksi
            $produksi = Produksi::where('mahasiswa_id', $mahasiswa->id)->first();
            
            if (!$produksi) {
                return back()->with('error', 'Mohon upload pra produksi terlebih dahulu');
            }

            // Check if pra produksi approved
            if ($produksi->status_pra_produksi !== 'disetujui') {
                return back()->with('error', 'Pra produksi harus disetujui terlebih dahulu');
            }

            // Upload file luaran tambahan
            $fileLuaranTambahan = null;
            if ($request->hasFile('file_luaran_tambahan')) {
                $file = $request->file('file_luaran_tambahan');
                $fileName = 'luaran_tambahan_' . time() . '.' . $file->getClientOriginalExtension();
                $fileLuaranTambahan = $file->storeAs('produksi/' . $mahasiswa->id, $fileName, 'public');
            }

            $produksi->update([
                'file_luaran_tambahan' => $fileLuaranTambahan,
            ]);

            return redirect()
                ->route('mahasiswa.produksi.index')
                ->with('success', 'File luaran tambahan berhasil diunggah!');

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
        $produksi = Produksi::findOrFail($id);
        
        // Check authorization
        if ($produksi->mahasiswa_id !== Auth::user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $filePath = null;
        switch ($type) {
            case 'skenario':
                $filePath = $produksi->file_skenario;
                break;
            case 'storyboard':
                $filePath = $produksi->file_storyboard;
                break;
            case 'dokumen':
                $filePath = $produksi->file_dokumen_pendukung;
                break;
            case 'akhir':
                $filePath = $produksi->file_produksi_akhir;
                break;
            case 'luaran':
                $filePath = $produksi->file_luaran_tambahan;
                break;
        }

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::disk('public')->download($filePath);
    }
}