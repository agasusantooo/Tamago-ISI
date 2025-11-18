<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\ProjekAkhir;
use App\Models\Proposal;
use App\Models\Produksi;

class NaskahKaryaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        // find latest approved proposal / projek akhir
        $projek = null;
        if ($mahasiswa) {
            $projek = ProjekAkhir::where('nim', $mahasiswa->nim)->latest()->first();
        }

        // find produksi (for karya final & luaran) by user id and approved proposal
        $proposal = $mahasiswa ? Proposal::where('mahasiswa_nim', $mahasiswa->nim)->where('status', 'disetujui')->latest()->first() : null;
        $produksi = null;
        if ($proposal) {
            $produksi = Produksi::where('mahasiswa_id', $user->id)->where('proposal_id', $proposal->id)->first();
        }

        return view('mahasiswa.naskah-karya', compact('projek', 'produksi', 'proposal'));
    }

    /**
     * Handle naskah publikasi upload
     */
    public function uploadNaskah(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_naskah' => 'required|file|mimes:pdf,doc,docx,zip|max:51200', // 50MB
            'link_jurnal' => 'nullable|url|max:255',
        ], [
            'file_naskah.required' => 'File naskah wajib diunggah',
            'file_naskah.mimes' => 'Format file naskah tidak didukung',
            'file_naskah.max' => 'File naskah maksimal 50 MB',
            'link_jurnal.url' => 'Link jurnal harus berupa URL yang valid',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;
        if (!$mahasiswa) {
            return back()->with('error', 'Profil mahasiswa tidak ditemukan.');
        }

        // find or create projek_akhir
        $projek = ProjekAkhir::firstOrCreate([
            'nim' => $mahasiswa->nim,
        ], [
            'judul' => optional($request)->judul ?? null,
        ]);

        if ($request->hasFile('file_naskah')) {
            $file = $request->file('file_naskah');
            $fileName = 'naskah_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('naskah/' . $mahasiswa->nim, $fileName, 'public');

            $projek->update([
                'file_naskah_publikasi' => $path,
                'link_jurnal' => $request->link_jurnal,
                'tanggal_upload_naskah' => now(),
            ]);
        }

        return redirect()->route('mahasiswa.naskah-karya')->with('success', 'Naskah publikasi berhasil diunggah.');
    }

    /**
     * Download stored files (naskah or produksi files if requested)
     */
    public function download($id, $type)
    {
        $user = Auth::user();

        // if type == 'naskah' -> $id refers to projek_akhir id
        if ($type === 'naskah') {
            $projek = ProjekAkhir::findOrFail($id);
            if ($projek->nim !== optional($user->mahasiswa)->nim) {
                abort(403);
            }
            $filePath = $projek->file_naskah_publikasi;
            if (!$filePath || !Storage::disk('public')->exists($filePath)) {
                abort(404, 'File tidak ditemukan');
            }
            return Storage::disk('public')->download($filePath);
        }

        // otherwise forward to Produksi download for karya/luaran
        $produksi = Produksi::findOrFail($id);
        if ($produksi->mahasiswa_id !== $user->id) abort(403);

        $filePath = null;
        if ($type === 'akhir') $filePath = $produksi->file_produksi_akhir;
        if ($type === 'luaran') $filePath = $produksi->file_luaran_tambahan;

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::disk('public')->download($filePath);
    }
}
