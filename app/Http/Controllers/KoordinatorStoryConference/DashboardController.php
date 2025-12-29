<?php

namespace App\Http\Controllers\KoordinatorStoryConference;

use App\Http\Controllers\Controller;
use App\Models\JadwalAcara;
use App\Models\StoryConference;

class DashboardController extends Controller
{
    public function index()
    {
        // Story Conference specific stats
        $totalRegistrations = StoryConference::count();
        $accepted = StoryConference::accepted()->count();
        $pendingCount = StoryConference::where('status', 'menunggu_persetujuan')->count();

        // Pending Story Conference registrations
        $pending = [];
        $pending['story_conference'] = StoryConference::where('status', 'menunggu_persetujuan')
            ->with('mahasiswa')
            ->latest('tanggal_daftar')
            ->take(8)
            ->get();

        // Upcoming Story Conference events only
        $upcoming = JadwalAcara::where('type', 'Story Conference')->where('start', '>=', now())->orderBy('start')->take(3)->get();

        // Announcements specific to Story Conference (placeholder for now)
        $announcements = [
            [
                'title' => 'Pengumuman Story Conference',
                'body' => 'Pendaftaran Story Conference akan ditutup minggu depan. Pastikan slot presentasi dikonfirmasi.',
                'meta' => '01 April 2024 • Koordinator Story Conference',
            ],
        ];

        return view('koordinator_story_conference.dashboard', compact(
            'totalRegistrations', 'accepted', 'pendingCount', 'pending', 'upcoming', 'announcements'
        ));
    }
}
