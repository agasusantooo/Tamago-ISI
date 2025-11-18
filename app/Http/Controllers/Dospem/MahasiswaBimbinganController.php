<?php

namespace App\Http\Controllers\Dospem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // Asumsi mahasiswa adalah User

class MahasiswaBimbinganController extends Controller
{
    /**
     * Menampilkan daftar mahasiswa bimbingan.
     */
    public function index()
    {
        // Data dummy untuk daftar mahasiswa
        $mahasiswaBimbingan = [
            (object)['id' => 1, 'name' => 'Budi Santoso', 'email' => 'budi@example.com', 'judul_ta' => 'Sistem Rekomendasi Film', 'progress' => 75, 'bimbingan_terakhir' => '2025-11-01'],
            (object)['id' => 2, 'name' => 'Siti Lestari', 'email' => 'siti@example.com', 'judul_ta' => 'Analisis Sentimen Media Sosial', 'progress' => 60, 'bimbingan_terakhir' => '2025-10-28'],
            (object)['id' => 3, 'name' => 'Ahmad Fauzi', 'email' => 'ahmad@example.com', 'judul_ta' => 'Aplikasi Mobile untuk Petani', 'progress' => 90, 'bimbingan_terakhir' => '2025-11-05'],
        ];

        return view('dospem.mahasiswa-bimbingan', compact('mahasiswaBimbingan'));
    }

    /**
     * Menampilkan halaman detail mahasiswa bimbingan.
     */
    public function show($id)
    {
        // Data dummy untuk detail mahasiswa
        // Di aplikasi nyata, Anda akan mencari mahasiswa dari database: User::findOrFail($id);
        $mahasiswa = (object)[
            'id' => $id, 
            'name' => 'Budi Santoso', 
            'nim' => '12345678',
            'email' => 'budi@example.com', 
            'judul_ta' => 'Sistem Rekomendasi Film', 
            'progress' => 75,
            'bimbingan_terakhir' => '2025-11-01',
            'riwayat_bimbingan' => [
                ['tanggal' => '2025-11-01', 'catatan' => 'Revisi Bab 3, perbaiki metodologi.'],
                ['tanggal' => '2025-10-25', 'catatan' => 'ACC Bab 2, lanjut Bab 3.'],
                ['tanggal' => '2025-10-18', 'catatan' => 'Diskusi awal Bab 2.'],
            ]
        ];

        return view('dospem.detail-mahasiswa', compact('mahasiswa'));
    }
}