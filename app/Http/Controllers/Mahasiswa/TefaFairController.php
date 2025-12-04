<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TefaFair;
use App\Models\ProjekAkhir;

class TefaFairController extends Controller
{
    public function index()
    {
        $statusBadges = [
            'menunggu_review' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Menunggu Review'],
            'disetujui' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Disetujui'],
            'ditolak' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Ditolak'],
        ];

        $dummyHistory = collect([
            (object)['id_tefa' => 1, 'semester' => 'Genap 2023/2024', 'status' => 'disetujui', 'statusBadge' => $statusBadges['disetujui']],
            (object)['id_tefa' => 2, 'semester' => 'Gasal 2024/2025', 'status' => 'menunggu_review', 'statusBadge' => $statusBadges['menunggu_review']],
            (object)['id_tefa' => 3, 'semester' => 'Gasal 2023/2024', 'status' => 'ditolak', 'statusBadge' => $statusBadges['ditolak']],
        ]);
        $history = $dummyHistory;

        $jadwalTefaFair = [
            [
                'jenis' => 'Tefa Fair Exhibition',
                'tanggal' => '28 - 30 Desember 2024',
                'waktu' => '10:00 - 21:00 WIB',
                'tempat' => 'Gedung Pameran Utama ISI',
                'deskripsi' => 'Pameran karya akhir mahasiswa dari berbagai program studi. Terbuka untuk umum.',
                'persyaratan' => [
                    'Karya akhir telah disetujui untuk dipamerkan.',
                    'Menyiapkan materi pameran (display, poster, produk, dll).',
                    'Mengisi form pendaftaran dan daftar kebutuhan pameran.'
                ],
                'bg_color' => 'bg-green-50',
                'border_color' => 'border-green-500'
            ]
        ];

        return view('mahasiswa.tefa-fair.index', compact('jadwalTefaFair', 'history'));
    }

    public function create()
    {
        // Using dummy data for demonstration
        $projekAkhir = (object)['id_proyek_akhir' => 1]; // Dummy object to pass the check in the view
        $tefaFair = null; // Assume no current registration for the form

        return view('mahasiswa.tefa-fair.create', compact('projekAkhir', 'tefaFair'));
    }

    public function store(Request $request)
    {
        // Logic to store Tefa Fair data will be implemented here later.
        return redirect()->route('mahasiswa.tefa-fair.index')->with('success', 'Data Tefa Fair berhasil disimpan!');
    }
}