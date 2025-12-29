<?php

namespace App\Http\Controllers\KoordinatorTefa;

use App\Http\Controllers\Controller;
use App\Models\JadwalAcara;
use App\Models\TefaFair;

class DashboardController extends Controller
{
    public function index()
    {
        // TEFA-specific stats
        $totalRegistrations = TefaFair::count();
        $accepted = TefaFair::where('status', 'disetujui')->count();
        $pendingCount = TefaFair::where('status', 'menunggu_review')->count();

        // TEFA-specific pending registrations
        $pending = [];
        $pending['tefa_registrations'] = TefaFair::where('status', 'menunggu_review')
            ->with('mahasiswa')
            ->latest('id_tefa')
            ->take(8)
            ->get();

        // Upcoming TEFA events only
        $upcoming = JadwalAcara::where('type', 'Tefa Fair')->where('start', '>=', now())->orderBy('start')->take(3)->get();

        // Announcements specific to TEFA (placeholder for now)
        $announcements = [
            [
                'title' => 'Perpanjangan Deadline Pendaftaran TEFA',
                'body' => 'Pendaftaran TEFA diperpanjang hingga 20 Maret 2024. Pastikan mahasiswa mengunggah proposal dan melengkapi deskripsi proyek.',
                'meta' => '12 Maret 2024 • Koordinator TEFA',
            ],
            [
                'title' => 'Panduan Penilaian TEFA 2024',
                'body' => 'Panduan dan rubrik penilaian TEFA telah diperbarui. Mohon tinjau panduan untuk memastikan proposal memenuhi syarat penilaian.',
                'meta' => '10 Maret 2024 • Koordinator TEFA',
            ],
        ];

        return view('koordinator_tefa.dashboard', compact(
            'totalRegistrations', 'accepted', 'pendingCount', 'pending', 'upcoming', 'announcements'
        ));
    }
}
