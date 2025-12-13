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
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;
        
        $produksiBadges = [
            'belum_dimulai' => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Belum Dimulai'],
            'menunggu_review_pra' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Review Pra-Produksi'],
            'revisi_pra' => ['class' => 'bg-orange-100 text-orange-800', 'text' => 'Revisi Pra-Produksi'],
            'menunggu_review_produksi' => ['class' => 'bg-yellow-200 text-yellow-900', 'text' => 'Review Produksi'],
            'revisi_produksi' => ['class' => 'bg-orange-200 text-orange-900', 'text' => 'Revisi Produksi'],
            'menunggu_review_pasca' => ['class' => 'bg-yellow-300 text-yellow-900', 'text' => 'Review Pasca-Produksi'],
            'revisi_pasca' => ['class' => 'bg-orange-300 text-orange-900', 'text' => 'Revisi Pasca-Produksi'],
            'selesai' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Selesai'],
        ];

        // Ambil data produksi dari database
        $produksis = collect();
        if ($mahasiswa && $user) {
            // Perbaikan: mahasiswa_id di tim_produksi adalah users.id
            $produksiList = Produksi::where('mahasiswa_id', $user->id)
                ->with('proposal')
                ->orderBy('created_at', 'desc')
                ->get();

            $produksis = $produksiList->map(function ($produksi) use ($produksiBadges) {
                // Determine overall status based on production phases
                if (
                    ($produksi->status_pra_produksi === 'disetujui' || $produksi->status_pra_produksi === 'selesai') &&
                    ($produksi->status_produksi === 'disetujui' || $produksi->status_produksi === 'selesai') &&
                    ($produksi->status_pasca_produksi === 'disetujui' || $produksi->status_pasca_produksi === 'selesai')
                ) {
                    $produksi->overallStatus = 'selesai';
                } elseif ($produksi->status_pasca_produksi === 'revisi') {
                    $produksi->overallStatus = 'revisi_pasca';
                } elseif ($produksi->status_pasca_produksi === 'menunggu_review') {
                    $produksi->overallStatus = 'menunggu_review_pasca';
                } elseif ($produksi->status_produksi === 'revisi') {
                    $produksi->overallStatus = 'revisi_produksi';
                } elseif ($produksi->status_produksi === 'menunggu_review') {
                    $produksi->overallStatus = 'menunggu_review_produksi';
                } elseif ($produksi->status_pra_produksi === 'revisi') {
                    $produksi->overallStatus = 'revisi_pra';
                } elseif ($produksi->status_pra_produksi === 'menunggu_review') {
                    $produksi->overallStatus = 'menunggu_review_pra';
                } else {
                    $produksi->overallStatus = 'belum_dimulai';
                }
                $produksi->overallStatusBadge = $produksiBadges[$produksi->overallStatus] ?? $produksiBadges['belum_dimulai'];
                return $produksi;
            });
        }

        return view('mahasiswa.produksi.index', compact('produksis'));
    }

    /**
     * Display produksi management page with upload forms
     */
    public function manage()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.produksi.index')
                ->with('error', 'Profil mahasiswa tidak ditemukan.');
        }
        
        $proposal = Proposal::where('mahasiswa_nim', $mahasiswa->nim)
            ->where('status', 'disetujui')
            ->latest()
            ->first();
        
        if (!$proposal) {
            return redirect()->route('mahasiswa.produksi.index')
                ->with('error', 'Anda belum memiliki proposal yang disetujui.');
        }
        
        $produksi = Produksi::where('mahasiswa_id', $user->id)
            ->where('proposal_id', $proposal->id)
            ->first();
        
        return view('mahasiswa.produksi.manage', compact('proposal', 'produksi'));
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
            $user = Auth::user();
            $mahasiswa = $user->mahasiswa;
            if (!$mahasiswa) {
                // explicit log to help debugging when a user doesn't have a mahasiswa row
                \Illuminate\Support\Facades\Log::warning('storePraProduksi: authenticated user has no mahasiswa relation', ['user_id' => $user->id]);
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
            // produksi.mahasiswa_id stores users.id (not mahasiswa.id), use $user->id
            $produksi = Produksi::where('mahasiswa_id', $user->id)
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
                // store files under the authenticated user's id (consistent with DB relation)
                $fileSkenario = $file->storeAs('produksi/' . $user->id, $fileName, 'public');
            }

            if ($request->hasFile('file_storyboard')) {
                $file = $request->file('file_storyboard');
                $fileName = 'storyboard_' . time() . '.' . $file->getClientOriginalExtension();
                $fileStoryboard = $file->storeAs('produksi/' . $user->id, $fileName, 'public');
            }

            if ($request->hasFile('file_dokumen_pendukung')) {
                $file = $request->file('file_dokumen_pendukung');
                $fileName = 'dokumen_' . time() . '.' . $file->getClientOriginalExtension();
                $fileDokumenPendukung = $file->storeAs('produksi/' . $user->id, $fileName, 'public');
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
                    // mahasiswa_id references users.id in the migration
                    'mahasiswa_id' => $user->id,
                    'proposal_id' => $proposal->id,
                    'dosen_id' => $proposal->dosen_id,
                    'file_skenario' => $fileSkenario,
                    'file_storyboard' => $fileStoryboard,
                    'file_dokumen_pendukung' => $fileDokumenPendukung,
                    'status_pra_produksi' => 'menunggu_review',
                    'status_produksi' => 'belum_upload',
                    'status_pasca_produksi' => 'belum_upload',
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
     * Store or update produksi
     */
    public function storeProduksi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_produksi' => 'required|file|mimes:mp4,mov,avi,mkv|max:512000', // 500MB
            'catatan_produksi' => 'nullable|string|max:1000',
        ], [
            'file_produksi.required' => 'File produksi wajib diunggah',
            'file_produksi.mimes' => 'File produksi harus berformat MP4, MOV, AVI, atau MKV',
            'file_produksi.max' => 'File produksi maksimal 500 MB',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $user = Auth::user();
            $mahasiswa = $user->mahasiswa;
            if (!$mahasiswa) {
                return back()->with('error', 'Profil mahasiswa tidak ditemukan.')->withInput();
            }
            
            $proposal = Proposal::where('mahasiswa_nim', $mahasiswa->nim)
                ->where('status', 'disetujui')
                ->latest()
                ->first();

            if (!$proposal) {
                return back()->with('error', 'Proposal belum disetujui')->withInput();
            }

            $produksi = Produksi::where('mahasiswa_id', $user->id)
                ->where('proposal_id', $proposal->id)
                ->first();
            
            if (!$produksi) {
                return back()->with('error', 'Mohon upload pra produksi terlebih dahulu');
            }

            if ($produksi->status_pra_produksi !== 'disetujui') {
                return back()->with('error', 'Pra produksi harus disetujui terlebih dahulu sebelum upload file produksi');
            }

            $fileProduksi = null;
            if ($request->hasFile('file_produksi')) {
                $file = $request->file('file_produksi');
                $fileName = 'produksi_' . time() . '.' . $file->getClientOriginalExtension();
                $fileProduksi = $file->storeAs('produksi/' . $user->id, $fileName, 'public');
            }

            $produksi->update([
                'file_produksi' => $fileProduksi,
                'catatan_produksi' => $request->catatan_produksi,
                'status_produksi' => 'menunggu_review',
                'tanggal_upload_produksi' => now(),
            ]);

            return redirect()
                ->route('mahasiswa.produksi.index')
                ->with('success', 'File produksi berhasil diunggah! Menunggu review dari dosen pembimbing.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Store or update pasca produksi
     */
    public function storePascaProduksi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_pasca_produksi' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $user = Auth::user();
            $mahasiswa = $user->mahasiswa;
            $proposal = Proposal::where('mahasiswa_nim', $mahasiswa->nim)
                ->where('status', 'disetujui')
                ->latest()
                ->first();
            
            if (!$proposal) {
                return back()->with('error', 'Proposal belum disetujui')->withInput();
            }

            $produksi = Produksi::where('mahasiswa_id', $user->id)
                ->where('proposal_id', $proposal->id)
                ->first();

            if (!$produksi || $produksi->status_produksi !== 'disetujui') {
                return back()->with('error', 'Tahap produksi harus disetujui terlebih dahulu.');
            }

            $filePath = null;
            if ($request->hasFile('file_pasca_produksi')) {
                $file = $request->file('file_pasca_produksi');
                $fileName = 'pasca_produksi_' . time() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('produksi/' . $user->id, $fileName, 'public');
            }

            $produksi->update([
                'file_pasca_produksi' => $filePath,
                'status_pasca_produksi' => 'menunggu_review',
                'tanggal_upload_pasca' => now(),
            ]);

            return redirect()
                ->route('mahasiswa.produksi.index')
                ->with('success', 'File pasca produksi berhasil diunggah!');

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
            $user = Auth::user();
            $mahasiswa = $user->mahasiswa;
            if (!$mahasiswa) {
                \Illuminate\Support\Facades\Log::warning('storeLuaranTambahan: authenticated user has no mahasiswa relation', ['user_id' => $user->id]);
                return back()->with('error', 'Profil mahasiswa tidak ditemukan.')->withInput();
            }

            // Get approved proposal for this mahasiswa
            $proposal = Proposal::where('mahasiswa_nim', $mahasiswa->nim)
                ->where('status', 'disetujui')
                ->latest()
                ->first();

            if (!$proposal) {
                return back()->with('error', 'Proposal belum disetujui')->withInput();
            }

            // Get produksi scoped to this proposal and mahasiswa (users.id)
            $produksi = Produksi::where('mahasiswa_id', $user->id)
                ->where('proposal_id', $proposal->id)
                ->first();
            
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
                $fileLuaranTambahan = $file->storeAs('produksi/' . $user->id, $fileName, 'public');
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
        $user = Auth::user();
        // Ensure mahasiswa relation exists for better error message
        $mahasiswa = $user->mahasiswa;
        if ($produksi->mahasiswa_id !== $user->id) {
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