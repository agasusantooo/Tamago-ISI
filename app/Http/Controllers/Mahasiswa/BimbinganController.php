<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Bimbingan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class BimbinganController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.proposal')->with('error', 'Profil mahasiswa tidak ditemukan.');
        }

        // Ambil semua riwayat bimbingan mahasiswa (gunakan nim sebagai kunci)
        $bimbinganList = Bimbingan::where('nim', $mahasiswa->nim)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                // Tambahkan warna dan teks status untuk tampilan
                $statusMap = [
                    'pending' => ['text' => 'Menunggu', 'color' => 'bg-yellow-100 text-yellow-800'],
                    'disetujui' => ['text' => 'Disetujui', 'color' => 'bg-green-100 text-green-800'],
                    'ditolak' => ['text' => 'Ditolak', 'color' => 'bg-red-100 text-red-800'],
                ];

                $status = $statusMap[$item->status] ?? ['text' => 'Tidak Diketahui', 'color' => 'bg-gray-100 text-gray-800'];

                $item->statusBadge = ['text' => $status['text']];
                $item->statusColor = $status['color'];
                $item->tanggal = $item->tanggal ?? now();

                return $item;
            });

        // Jadwal bimbingan yang sudah dijadwalkan
        $jadwalTerjadwal = $bimbinganList->where('status', 'disetujui');

        return view('mahasiswa.bimbingan', compact('bimbinganList', 'jadwalTerjadwal'));
    }

    public function store(Request $request)
    {
        // Log the incoming payload to help debug missing/renamed fields
        Log::info('Bimbingan submit payload (before merge)', $request->all());

        // Accept old field name 'catatan' for backward compatibility.
        $catatan = $request->input('catatan_mahasiswa') ?? $request->input('catatan');
        if ($catatan !== null) {
            $request->merge(['catatan_mahasiswa' => $catatan]);
        }

        Log::info('Bimbingan submit payload (after merge)', $request->only(['topik', 'catatan_mahasiswa']));

        // Validasi input
        $request->validate([
            'topik' => 'required|string|max:255',
            'catatan_mahasiswa' => 'required|string|min:5',
            'file_pendukung' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

    $user = Auth::user();

    // Cek relasi mahasiswa
    $mahasiswa = $user->mahasiswa;
    if (!$mahasiswa) {
        return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
    }

    // Proyek akhir opsional - bimbingan bisa dilakukan kapan saja
    $projekAkhir = $mahasiswa->projek_akhir ?? null;

    // Handle file pendukung
    $filePath = null;
    if ($request->hasFile('file_pendukung')) {
        $filePath = $request->file('file_pendukung')->store('bimbingan_files', 'public');
    }

        // Insert data bimbingan
        Bimbingan::create([
            'id_proyek_akhir' => $projekAkhir?->id_proyek_akhir,
            'nim' => $mahasiswa->nim,
            'topik' => $request->topik,
            'catatan_mahasiswa' => $request->catatan_mahasiswa,
            'file_pendukung' => $filePath,
            'status' => 'pending',
            'tanggal' => now(),
        ]);

    return redirect()
        ->route('mahasiswa.bimbingan.index')
        ->with('success', 'Pengajuan bimbingan berhasil dikirim!');
}


    public function download($id)
    {
        $bimbingan = Bimbingan::findOrFail($id);

        if (!$bimbingan->file_pendukung) {
            return back()->with('error', 'Tidak ada file pendukung untuk diunduh.');
        }

        return Storage::disk('public')->download($bimbingan->file_pendukung);
    }

    public function show($id)
    {
        $bimbingan = Bimbingan::findOrFail($id);
        return view('mahasiswa.bimbingan-detail', compact('bimbingan'));
    }
}
