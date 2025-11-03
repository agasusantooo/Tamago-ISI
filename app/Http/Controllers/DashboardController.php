<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Service\ProgressService;

class DashboardController extends Controller
{
    protected $progressService;

    public function __construct(ProgressService $progressService)
    {
        $this->progressService = $progressService;
    }

    public function index()
    {
        $user = Auth::user();
        $roleName = $user->getRoleName();

        switch ($roleName) {
            case 'mahasiswa':
                return $this->mahasiswaDashboard();
            case 'dospem':
                return $this->dospemDashboard();
            case 'kaprodi':
                return $this->kaprodiDashboard();
            case 'koordinator_ta':
                return $this->koordinatorTADashboard();
            case 'dosen_penguji':
                return $this->dosenPengujiDashboard();
            case 'admin':
                return $this->adminDashboard();
            default:
                abort(403, 'ROLE TIDAK VALID');
        }
    }

    public function mahasiswaDashboard()
    {
        $userId = Auth::id();
        $progressData = $this->progressService->getDashboardData($userId);

        $totalBimbingan = 12; // contoh placeholder

        $data = [
            'progress' => $progressData['percentage'],
            'progressDetails' => $progressData['details'],
            'completedStages' => $progressData['completed_count'],
            'totalStages' => $progressData['total_stages'],
            'totalBimbingan' => $totalBimbingan,
            'tugasSelesai' => 8,
            'totalTugas' => 10,
            'fileTerupload' => 24,
            'aktivitasTerakhir' => collect([
                (object)['description' => 'Mengunggah proposal tugas akhir', 'created_at' => now()->subDays(1)],
                (object)['description' => 'Mendapat revisi dari dosen pembimbing', 'created_at' => now()->subDays(2)],
            ]),
        ];

        return view('dashboards.mahasiswa', $data);
    }

    public function dospemDashboard()
    {
        $data = [
            'mahasiswaAktifCount' => 15,
            'tugasReview' => 5,
            'bimbinganMingguIni' => 8,
            'taSelesai' => 23,
            'mahasiswaBimbingan' => [],
            'tugasMenungguReview' => [],
            'jadwalBimbingan' => [],
            'aktivitasTerakhir' => collect([
                (object)['description' => 'Memberi catatan revisi kepada mahasiswa A', 'created_at' => now()->subHours(3)],
                (object)['description' => 'Meninjau draft proposal mahasiswa B', 'created_at' => now()->subDay()],
            ]),
        ];

        return view('dashboards.pembimbing', $data);
    }

    public function kaprodiDashboard()
    {
        $data = [
            'totalMahasiswa' => 150,
            'mahasiswaLulus' => 128,
            'belumLulus' => 22,
            'aktivitasTerakhir' => collect([
                (object)['description' => 'Menyetujui proposal mahasiswa angkatan 2022', 'created_at' => now()->subDays(1)],
            ]),
        ];

        return view('dashboards.dashboard.kaprodi', $data);
    }

    public function koordinatorTADashboard()
    {
        $data = [
            'totalMahasiswa' => 150,
            'sudahUjian' => 94,
            'lulus' => 85,
            'aktivitasTerakhir' => collect([
                (object)['description' => 'Menjadwalkan ujian TA gelombang 2', 'created_at' => now()->subHours(10)],
                (object)['description' => 'Menyetujui dosen penguji baru', 'created_at' => now()->subDays(1)],
            ]),
        ];

        return view('dashboards.koordinator_ta', $data);
    }

    public function dosenPengujiDashboard()
    {
        $data = [
            'jadwalUjian' => [
                (object)['mahasiswa' => 'Budi Santoso', 'tanggal' => now()->addDays(2)],
                (object)['mahasiswa' => 'Siti Lestari', 'tanggal' => now()->addDays(4)],
            ],
            'aktivitasTerakhir' => collect([
                (object)['description' => 'Menilai ujian TA mahasiswa A', 'created_at' => now()->subHours(5)],
            ]),
        ];

        return view('dashboards.dospenguji', $data);
    }

    public function adminDashboard()
    {
        $data = [
            'totalMahasiswa' => 150,
            'totalDosen' => 45,
            'totalKorprodi' => 3,
            'totalAdmin' => 2,
            'aktivitasTerakhir' => collect([
                (object)['description' => 'Menambahkan user baru ke sistem', 'created_at' => now()->subMinutes(30)],
                (object)['description' => 'Melihat log aktivitas sistem', 'created_at' => now()->subHours(2)],
            ]),
        ];

        return view('dashboards.admin', $data);
    }
}
