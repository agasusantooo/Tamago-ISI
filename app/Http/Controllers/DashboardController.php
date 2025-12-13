<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;
use App\Service\ProgressService;
use App\Models\Proposal;
use App\Models\Mahasiswa;
use App\Models\Bimbingan;
use App\Models\UjianTA;
use App\Models\ProjekAkhir;
use App\Models\ActivitySemester;
use App\Models\Timeline;
use App\Models\File;
use App\Models\TAProgressStage;

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

    /**
     * DASHBOARD MAHASISWA
     */
    public function mahasiswaDashboard()
    {
        $user = Auth::user();
        $userId = $user->id;

        // Ambil data progress dari service
        $progressData = $this->progressService->getDashboardData($userId);

        // Ambil data real dari database
        $mahasiswa = $user->mahasiswa;
        
        // Total bimbingan dari database (cari berdasarkan nim atau mahasiswa_id)
        $totalBimbingan = 0;
        if ($mahasiswa && $mahasiswa->nim) {
            $totalBimbingan = Bimbingan::where(function($q) use ($mahasiswa, $user) {
                    $q->where('nim', $mahasiswa->nim)
                      ->orWhere('mahasiswa_id', $user->id);
                })->count();
        }

        // Hitung berapa yang sudah disetujui dan yang masih menunggu
        $approvedBimbinganCount = 0;
        $pendingBimbinganCount = 0;
        if ($mahasiswa && $mahasiswa->nim) {
            $approvedBimbinganCount = Bimbingan::where(function($q) use ($mahasiswa, $user) {
                $q->where('nim', $mahasiswa->nim)
                  ->orWhere('mahasiswa_id', $user->id);
            })->where('status', 'disetujui')->count();

            $pendingBimbinganCount = Bimbingan::where(function($q) use ($mahasiswa, $user) {
                $q->where('nim', $mahasiswa->nim)
                  ->orWhere('mahasiswa_id', $user->id);
            })->where('status', 'pending')->count();
        }
        
        // File yang terupload dari database
        $fileTerupload = 0;
        if ($user) {
            $fileTerupload = File::where('uploaded_by', $user->id)->count();
        }

        // Ambil dosen pembimbing
        $dosenPembimbing = null;
        if ($mahasiswa && $mahasiswa->dosen_pembimbing_id) {
            $dosenPembimbing = \App\Models\Dosen::where('nidn', $mahasiswa->dosen_pembimbing_id)->first();
        }

        // Ambil bimbingan terbaru (cari berdasarkan nim atau mahasiswa_id)
        $bimbinganTerbaru = [];
        if ($mahasiswa && $mahasiswa->nim) {
            $bimbinganTerbaru = Bimbingan::where(function($q) use ($mahasiswa, $user) {
                $q->where('nim', $mahasiswa->nim)
                  ->orWhere('mahasiswa_id', $user->id);
            })
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get();
        }

        // Ambil upcoming deadlines dari timeline dengan relasi
        $upcomingDeadlines = Timeline::with(['taProgressStage', 'semester'])
            ->where('due_date', '>=', Carbon::now())
            ->orderBy('due_date')
            ->take(5)
            ->get();

        // Jika tidak ada timeline yang terdefinisi, fallback: tampilkan tahap-tahap progress aktif sebagai placeholder
        if ($upcomingDeadlines->isEmpty()) {
            $stages = TAProgressStage::getActiveStages()->take(5);
            $upcomingDeadlines = $stages->map(function($s){
                return (object)[
                    'taProgressStage' => $s,
                    'due_date' => null,
                    'semester' => null,
                ];
            })->values();
        }

        $data = [
            'progress' => $progressData['percentage'],
            'progressDetails' => $progressData['details'],
            'completedStages' => $progressData['completed_count'],
            'totalStages' => $progressData['total_stages'],
            'totalBimbingan' => $totalBimbingan,
            'approvedBimbinganCount' => $approvedBimbinganCount,
            'pendingBimbinganCount' => $pendingBimbinganCount,
            'fileTerupload' => $fileTerupload,
            'dosenPembimbing' => $dosenPembimbing,
            'bimbinganTerbaru' => $bimbinganTerbaru,
            'upcomingDeadlines' => $upcomingDeadlines,
            'hideProgressBar' => true,
        ];

        // Ambil proposal terakhir mahasiswa
        $latestProposal = null;
        if ($mahasiswa && $mahasiswa->nim) {
            $latestProposal = Proposal::where('mahasiswa_nim', $mahasiswa->nim)
                ->orderBy('created_at', 'desc')
                ->first();
        }
        // Gabungkan data untuk dikirim ke view
        return view('mahasiswa.dashboard', array_merge($data, compact('latestProposal')));
    }
    /**
     * DASHBOARD DOSEN PEMBIMBING
     */
    public function dospemDashboard()
    {
        $data = $this->buildDospemData();
        return view('dospem.dashboard', $data);
    }

    /**
     * Return Dosen Pembimbing dashboard data as JSON for real-time polling.
     */
    public function dospemDashboardData()
    {
        $data = $this->buildDospemData();
        return response()->json($data);
    }

    /**
     * Build Dosen Pembimbing dashboard data array used by view and API.
     */
    private function buildDospemData()
    {
        $user = Auth::user();
        $nidn = $user->nidn ?? null;

        // Mahasiswa yang dibimbing oleh dosen ini
        $mahasiswaAktifCount = Mahasiswa::where('dosen_pembimbing_id', $nidn)->count();

        // Tugas review (proposal yang perlu direview)
        $tugasReview = Proposal::where('dosen_id', $nidn)
            ->whereIn('status', ['diajukan', 'review'])
            ->count();

        // Bimbingan minggu ini
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $bimbinganMingguIni = Bimbingan::where('dosen_nidn', $nidn)
            ->whereBetween('tanggal', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
            ->count();

        // TA yang sudah selesai (completed projects)
        $taSelesai = ProjekAkhir::where(function($query) use ($nidn) {
                $query->where('nidn1', $nidn)
                      ->orWhere('nidn2', $nidn);
            })
            ->where('status', 'selesai')
            ->count();

        // Mahasiswa bimbingan aktif dengan progress real dari DB
        $mahasiswaBimbingan = Mahasiswa::where('dosen_pembimbing_id', $nidn)
            ->with('user')
            ->get()
            ->map(function($m) use ($nidn){
                // Get project to find judul_ta
                $projek = ProjekAkhir::where('nim', $m->nim)->first();
                $judul_ta = $projek ? ($projek->judul_ta ?? 'Belum ada judul') : 'Belum ada judul';
                
                // Compute real progress using ProgressService
                $progress = 0;
                if ($m->user_id) {
                    $progressData = $this->progressService->getDashboardData($m->user_id);
                    $progress = $progressData['percentage'] ?? 0;
                }
                
                return (object)[
                    'nim' => $m->nim,
                    'name' => $m->nama ?? optional($m->user)->name,
                    'email' => $m->email ?? optional($m->user)->email,
                    'judul_ta' => $judul_ta,
                    'progress' => $progress,
                ];
            })->values()->toArray();

        // Jadwal bimbingan mendatang
        $jadwalBimbingan = Bimbingan::where('dosen_nidn', $nidn)
            ->whereDate('tanggal', '>=', Carbon::now()->toDateString())
            ->orderBy('tanggal')
            ->take(5)
            ->get()
            ->map(function($b){
                $mahasiswaName = 'Mahasiswa';
                if ($b->nim) {
                    $m = Mahasiswa::where('nim', $b->nim)->first();
                    $mahasiswaName = $m ? ($m->nama ?? optional($m->user)->name) : $b->nim;
                }
                return (object)[
                    'mahasiswa_nim' => $b->nim,
                    'mahasiswa_name' => $mahasiswaName,
                    'tanggal' => isset($b->tanggal) ? (is_string($b->tanggal) ? $b->tanggal : $b->tanggal->format('d M Y')) : null,
                    'topik' => $b->topik ?? ($b->catatan_bimbingan ?? 'Bimbingan'),
                ];
            })->values()->toArray();

        // Tugas menunggu review (proposal yang diajukan)
        $tugasMenungguReview = Proposal::where('dosen_id', $nidn)
            ->where('status', 'diajukan')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($p){
                $mahasiswaName = 'Mahasiswa';
                if ($p->mahasiswa_nim) {
                    $m = Mahasiswa::where('nim', $p->mahasiswa_nim)->first();
                    $mahasiswaName = $m ? ($m->nama ?? optional($m->user)->name) : $p->mahasiswa_nim;
                }
                return (object)[
                    'judul' => $p->judul,
                    'mahasiswa_nim' => $p->mahasiswa_nim,
                    'mahasiswa_name' => $mahasiswaName,
                    'created_at' => isset($p->created_at) ? $p->created_at->format('d M Y H:i') : now()->format('d M Y H:i'),
                ];
            })->values()->toArray();

        $jumlahMahasiswaAktif = $mahasiswaAktifCount;
        $jumlahTugasReview = $tugasReview;

        return compact('mahasiswaAktifCount', 'tugasReview', 'bimbinganMingguIni', 'taSelesai', 'mahasiswaBimbingan', 'jadwalBimbingan', 'tugasMenungguReview', 'jumlahMahasiswaAktif', 'jumlahTugasReview');
    }

    /**
     * DASHBOARD KAPRODI
     */
    public function kaprodiDashboard()
    {
        // Build data and render view
        $data = $this->buildKaprodiData();

        // existing view file is in resources/views/dashboards/kaprodi.blade.php
        return view('dashboards.kaprodi', $data);
    }

    /**
     * Return Kaprodi dashboard data as JSON for real-time polling.
     */
    public function kaprodiDashboardData()
    {
        $data = $this->buildKaprodiData();
        return response()->json($data);
    }

    /**
     * Build Kaprodi dashboard data array used by view and API.
     */
    private function buildKaprodiData()
    {
        // Real data from DB
        $totalMahasiswa = Mahasiswa::count();

        // Use ProjekAkhir status 'selesai' as lulus proxy
        $mahasiswaLulus = ProjekAkhir::where('status', 'selesai')->count();
        $belumLulus = max(0, $totalMahasiswa - $mahasiswaLulus);

        // Recent activities: combine recent proposals and bimbingan requests
        $recentProposals = Proposal::orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($p){
                return (object)[
                    'description' => 'Pengajuan proposal: ' . ($p->judul ?? '-'),
                    'created_at' => $p->created_at,
                ];
            });

        $recentBimbingan = Bimbingan::orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($b){
                return (object)[
                    'description' => 'Permintaan bimbingan dari ' . ($b->nim ?? '-'),
                    'created_at' => $b->created_at ?? now(),
                ];
            });

        $aktivitasTerakhir = $recentProposals->merge($recentBimbingan)->sortByDesc('created_at')->values();

        // Chart data: basic counts per semester placeholder computed from ProjekAkhir by year-month (last 6 months)
        $labels = [];
        $lulusCounts = [];
        $belumCounts = [];
        for ($i = 5; $i >= 0; $i--) {
            $dt = Carbon::now()->subMonths($i);
            $label = $dt->format('M Y');
            $labels[] = $label;

            $start = $dt->copy()->startOfMonth()->toDateString();
            $end = $dt->copy()->endOfMonth()->toDateString();

            $lulusCounts[] = ProjekAkhir::whereBetween('created_at', [$start, $end])->where('status', 'selesai')->count();
            $belumCounts[] = ProjekAkhir::whereBetween('created_at', [$start, $end])->where('status', '!=', 'selesai')->count();
        }

        // Rata-rata durasi TA: keep static small sample if DB lacks data for robust calculation
        $rataDurasiTA = [
            ['semester' => Carbon::now()->subMonths(2)->format('Y') . ' Genap', 'durasi' => 8.5],
            ['semester' => Carbon::now()->subMonths(8)->format('Y') . ' Ganjil', 'durasi' => 9.1],
        ];

        // Pengumuman: no model, keep empty collection for now
        $pengumumanPenting = collect([]);

        $chartData = [
            'labels' => $labels,
            'lulus' => $lulusCounts,
            'belum_lulus' => $belumCounts,
        ];

        // Header counts for Kaprodi
        $mahasiswaAktifCount = $totalMahasiswa;
        $tugasReview = Proposal::whereIn('status', ['diajukan', 'review'])->count();

        return compact('totalMahasiswa', 'mahasiswaLulus', 'belumLulus', 'aktivitasTerakhir', 'rataDurasiTA', 'pengumumanPenting', 'chartData', 'mahasiswaAktifCount', 'tugasReview');
    }

    /**
     * DASHBOARD KOORDINATOR TA
     */
    public function koordinatorTADashboard()
    {
        // DB-driven stats for Koordinator TA
        $totalMahasiswa = Mahasiswa::count();

        // Students who already have an UjianTA record with result
        $sudahUjian = UjianTA::whereNotNull('hasil_akhir')->count();

        // Lulus: use ProjekAkhir status 'selesai' as proxy
        $lulus = ProjekAkhir::where('status', 'selesai')->count();

        // Recent activities: recent proposals and bimbingan requests
        $recentProposals = Proposal::orderBy('created_at', 'desc')->take(5)->get()->map(function($p){
            return (object)[
                'description' => 'Pengajuan proposal: ' . ($p->judul ?? '-'),
                'created_at' => $p->created_at,
            ];
        });

        $recentBimbingan = Bimbingan::orderBy('created_at', 'desc')->take(5)->get()->map(function($b){
            return (object)[
                'description' => 'Permintaan bimbingan dari ' . ($b->nim ?? '-'),
                'created_at' => $b->created_at ?? now(),
            ];
        });

        $aktivitasTerakhir = $recentProposals->merge($recentBimbingan)->sortByDesc('created_at')->values();

        // Tugas menunggu persetujuan: proposals with status 'diajukan' and ujian pending
        $pendingProposals = Proposal::where('status', 'diajukan')->orderBy('created_at', 'desc')->take(5)->get()->map(function($p){
            $m = Mahasiswa::where('nim', $p->mahasiswa_nim)->first();
            return (object)[
                'type' => 'Proposal',
                'title' => $p->judul ?? 'Pengajuan Proposal',
                'student' => $m ? $m->nama : ($p->mahasiswa_nim ?? '-'),
                'created_at' => $p->created_at,
            ];
        });

        $pendingUjian = UjianTA::where('status_kelayakan', 'belum')->orderBy('created_at', 'desc')->take(5)->get()->map(function($u){
            // find projek_akhir -> mahasiswa name
            $projek = ProjekAkhir::where('id_proyek_akhir', $u->id_proyek_akhir)->first();
            $mname = $projek ? optional($projek->mahasiswa)->nama ?? $projek->nim : ($u->id_proyek_akhir ?? '-');
            return (object)[
                'type' => 'Ujian',
                'title' => 'Persetujuan Pengajuan Ujian',
                'student' => $mname,
                'created_at' => $u->created_at ?? now(),
            ];
        });

        // Convert to base Support collections before merging to avoid Eloquent collection
        // attempting to call model methods on stdClass items (getKey()).
        $tugasMenungguPersetujuan = $pendingProposals->toBase()->merge($pendingUjian->toBase())->take(5);

        // Jadwal mendatang: Ujian TA and upcoming bimbingan
        $jadwalUjian = UjianTA::whereDate('tanggal_ujian', '>=', now()->toDateString())->orderBy('tanggal_ujian')->take(3)->get()->map(function($u){
            $projek = ProjekAkhir::where('id_proyek_akhir', $u->id_proyek_akhir)->first();
            $mname = $projek ? optional($projek->mahasiswa)->nama ?? $projek->nim : '-';
            $tanggal = null;
            if (isset($u->tanggal_ujian) && $u->tanggal_ujian) {
                if (is_string($u->tanggal_ujian)) {
                    try {
                        $tanggal = \Illuminate\Support\Carbon::parse($u->tanggal_ujian)->format('d M');
                    } catch (\Exception $e) {
                        $tanggal = $u->tanggal_ujian;
                    }
                } else {
                    $tanggal = $u->tanggal_ujian->format('d M');
                }
            }
            return (object)[
                'title' => 'Ujian TA',
                'tanggal' => $tanggal,
                'subtitle' => $mname,
            ];
        });

        $jadwalBimbingan = Bimbingan::whereDate('tanggal', '>=', now()->toDateString())->orderBy('tanggal')->take(3)->get()->map(function($b){
            $m = Mahasiswa::where('nim', $b->nim)->first();
            $tanggal = null;
            if (isset($b->tanggal) && $b->tanggal) {
                if (is_string($b->tanggal)) {
                    try {
                        $tanggal = \Illuminate\Support\Carbon::parse($b->tanggal)->format('d M');
                    } catch (\Exception $e) {
                        $tanggal = $b->tanggal;
                    }
                } else {
                    $tanggal = $b->tanggal->format('d M');
                }
            }
            return (object)[
                'title' => 'Bimbingan',
                'tanggal' => $tanggal,
                'subtitle' => $m ? $m->nama : $b->nim,
            ];
        });

        $jadwalMendatang = $jadwalUjian->toBase()->merge($jadwalBimbingan->toBase())->take(3);

        // Pengumuman: keep empty collection for now (no model)
        $pengumumanPenting = collect([]);

        $data = compact('totalMahasiswa', 'sudahUjian', 'lulus', 'aktivitasTerakhir', 'tugasMenungguPersetujuan', 'jadwalMendatang', 'pengumumanPenting');

        // Sample mahasiswa progress card (real-time from DB) - pick one mahasiswa to preview
        $sampleMahasiswa = Mahasiswa::whereNotNull('user_id')->first();
        $sampleStudentName = null;
        $sampleProgress = null;
        if ($sampleMahasiswa) {
            $sampleStudentName = $sampleMahasiswa->nama ?? optional($sampleMahasiswa->user)->name ?? $sampleMahasiswa->nim;
            $sampleProgress = $this->progressService->getDashboardData($sampleMahasiswa->user_id);
            // Log debug of sample progress
            \Log::debug('SampleProgress', ['user_id' => $sampleMahasiswa->user_id, 'progress' => $sampleProgress]);
        }

        $data['sampleStudentName'] = $sampleStudentName;
        $data['sampleProgress'] = $sampleProgress;

        return view('koordinator_ta.dashboard', $data);
    }

    /**
     * DASHBOARD KAPRODI TA
     *
     * A separate dashboard for Kaprodi when viewing TA-specific overview.
     */
    public function kaprodiTADashboard()
    {
        // reuse the same data logic as koordinatorTADashboard
        $totalMahasiswa = Mahasiswa::count();

        $sudahUjian = UjianTA::whereNotNull('hasil_akhir')->count();
        $lulus = ProjekAkhir::where('status', 'selesai')->count();

        $recentProposals = Proposal::orderBy('created_at', 'desc')->take(5)->get()->map(function($p){
            return (object)[
                'description' => 'Pengajuan proposal: ' . ($p->judul ?? '-'),
                'created_at' => $p->created_at,
            ];
        });

        $recentBimbingan = Bimbingan::orderBy('created_at', 'desc')->take(5)->get()->map(function($b){
            return (object)[
                'description' => 'Permintaan bimbingan dari ' . ($b->nim ?? '-'),
                'created_at' => $b->created_at ?? now(),
            ];
        });

        $aktivitasTerakhir = $recentProposals->merge($recentBimbingan)->sortByDesc('created_at')->values();

        $pendingProposals = Proposal::where('status', 'diajukan')->orderBy('created_at', 'desc')->take(5)->get()->map(function($p){
            $m = Mahasiswa::where('nim', $p->mahasiswa_nim)->first();
            return (object)[
                'type' => 'Proposal',
                'title' => $p->judul ?? 'Pengajuan Proposal',
                'student' => $m ? $m->nama : ($p->mahasiswa_nim ?? '-'),
                'created_at' => $p->created_at,
            ];
        });

        $pendingUjian = UjianTA::where('status_kelayakan', 'belum')->orderBy('created_at', 'desc')->take(5)->get()->map(function($u){
            $projek = ProjekAkhir::where('id_proyek_akhir', $u->id_proyek_akhir)->first();
            $mname = $projek ? optional($projek->mahasiswa)->nama ?? $projek->nim : ($u->id_proyek_akhir ?? '-');
            return (object)[
                'type' => 'Ujian',
                'title' => 'Persetujuan Pengajuan Ujian',
                'student' => $mname,
                'created_at' => $u->created_at ?? now(),
            ];
        });

        $tugasMenungguPersetujuan = $pendingProposals->toBase()->merge($pendingUjian->toBase())->take(5);

        $jadwalUjian = UjianTA::whereDate('tanggal_ujian', '>=', now()->toDateString())->orderBy('tanggal_ujian')->take(3)->get()->map(function($u){
            $projek = ProjekAkhir::where('id_proyek_akhir', $u->id_proyek_akhir)->first();
            $mname = $projek ? optional($projek->mahasiswa)->nama ?? $projek->nim : '-';
            $tanggal = null;
            if (isset($u->tanggal_ujian) && $u->tanggal_ujian) {
                if (is_string($u->tanggal_ujian)) {
                    try {
                        $tanggal = \Illuminate\Support\Carbon::parse($u->tanggal_ujian)->format('d M');
                    } catch (\Exception $e) {
                        $tanggal = $u->tanggal_ujian;
                    }
                } else {
                    $tanggal = $u->tanggal_ujian->format('d M');
                }
            }
            return (object)[
                'title' => 'Ujian TA',
                'tanggal' => $tanggal,
                'subtitle' => $mname,
            ];
        });

        $jadwalBimbingan = Bimbingan::whereDate('tanggal', '>=', now()->toDateString())->orderBy('tanggal')->take(3)->get()->map(function($b){
            $m = Mahasiswa::where('nim', $b->nim)->first();
            $tanggal = null;
            if (isset($b->tanggal) && $b->tanggal) {
                if (is_string($b->tanggal)) {
                    try {
                        $tanggal = \Illuminate\Support\Carbon::parse($b->tanggal)->format('d M');
                    } catch (\Exception $e) {
                        $tanggal = $b->tanggal;
                    }
                } else {
                    $tanggal = $b->tanggal->format('d M');
                }
            }
            return (object)[
                'title' => 'Bimbingan',
                'tanggal' => $tanggal,
                'subtitle' => $m ? $m->nama : $b->nim,
            ];
        });

        $jadwalMendatang = $jadwalUjian->toBase()->merge($jadwalBimbingan->toBase())->take(3);

        $pengumumanPenting = collect([]);

        $data = compact('totalMahasiswa', 'sudahUjian', 'lulus', 'aktivitasTerakhir', 'tugasMenungguPersetujuan', 'jadwalMendatang', 'pengumumanPenting');

        return view('kaprodi.ta_dashboard', $data);
    }

    /**
     * DASHBOARD DOSEN PENGUJI
     */
    public function dosenPengujiDashboard()
    {
        $user = Auth::user();
        $data = $this->buildDosenPengujiData($user);
        return view('dosen_penguji.dashboard', $data);
    }

    /**
     * Build dosen penguji dashboard data
     */
    private function buildDosenPengujiData($user)
    {
        // Ambil ujian TA yang dosen ini menjadi penguji
        $ujianTA = UjianTA::where('ketua_penguji_id', $user->id)
            ->orWhere('penguji_ahli_id', $user->id)
            ->orderBy('tanggal_ujian', 'asc')
            ->get();

        // Hitung ujian berdasarkan status
        $ujianSelesai = $ujianTA->where('status_ujian', 'selesai_ujian')->count();
        $ujianMenungguNilai = $ujianTA->where('status_ujian', 'belum_ujian')->count();
        $ujianMendatang = $ujianTA->where('tanggal_ujian', '>', now())->count();

        // Hitung rata-rata nilai
        $rataRataNilai = $ujianTA->avg('nilai_akhir') ?? 0;

        return [
            'ujianTotal' => $ujianTA->count(),
            'ujianMenungguNilai' => $ujianMenungguNilai,
            'ujianMendatang' => $ujianMendatang,
            'ujianSelesai' => $ujianSelesai,
            'rataRataNilai' => round($rataRataNilai, 2),
            'ujianList' => $ujianTA->map(function($u) {
                return [
                    'id' => $u->id_ujian,
                    'mahasiswa' => $u->mahasiswa?->name ?? 'Unknown',
                    'nim' => $u->mahasiswa?->nim ?? '-',
                    'judul' => $u->judul_ta ?? '-',
                    'status' => $u->status_ujian,
                    'jadwal' => $u->tanggal_ujian?->format('Y-m-d H:i') ?? '-',
                    'nilai' => $u->nilai_akhir,
                ];
            })->toArray(),
            'chartData' => [
                'labels' => ['Menunggu Nilai', 'Selesai', 'Mendatang'],
                'data' => [$ujianMenungguNilai, $ujianSelesai, $ujianMendatang],
            ],
        ];
    }

    /**
     * Get dosen penguji dashboard data as JSON (for real-time updates)
     */
    public function dosenPengujiDashboardData()
    {
        $user = Auth::user();
        $data = $this->buildDosenPengujiData($user);
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    /**
     * PENILAIAN PAGE (Dosen Penguji)
     */
    public function dosenPengujiPenilaian()
    {
        $user = Auth::user();
        $ujianTA = UjianTA::where('ketua_penguji_id', $user->id)
            ->orWhere('penguji_ahli_id', $user->id)
            ->orderBy('tanggal_ujian', 'asc')
            ->get()
            ->map(function($u) {
                return [
                    'id' => $u->id_ujian,
                    'nim' => $u->mahasiswa?->nim ?? '-',
                    'nama' => $u->mahasiswa?->name ?? 'Unknown',
                    'judul' => $u->judul_ta ?? '-',
                    'tanggal' => $u->tanggal_ujian?->format('Y-m-d H:i') ?? '-',
                    'status' => $u->nilai_akhir ? 'Sudah Dinilai' : 'Belum Dinilai',
                    'nilai' => $u->nilai_akhir,
                ];
            });

        return view('dosen_penguji.penilaian', ['ujianList' => $ujianTA]);
    }

    /**
     * Get penilaian data as JSON (for real-time updates)
     */
    public function dosenPengujiPenilaianData()
    {
        $user = Auth::user();
        $ujianTA = UjianTA::where('ketua_penguji_id', $user->id)
            ->orWhere('penguji_ahli_id', $user->id)
            ->orderBy('tanggal_ujian', 'asc')
            ->get()
            ->map(function($u) {
                return [
                    'id' => $u->id_ujian,
                    'nim' => $u->mahasiswa?->nim ?? '-',
                    'nama' => $u->mahasiswa?->name ?? 'Unknown',
                    'judul' => $u->judul_ta ?? '-',
                    'tanggal' => $u->tanggal_ujian?->format('Y-m-d H:i') ?? '-',
                    'status' => $u->nilai_akhir ? 'Sudah Dinilai' : 'Belum Dinilai',
                    'nilai' => $u->nilai_akhir,
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => ['ujianList' => $ujianTA],
        ]);
    }

    /**
     * Store nilai (grade) for ujian
     */
    public function storeNilaiUjian(Request $request)
    {
        $request->validate([
            'ujian_id' => 'required|exists:ujian_tugas_akhir,id_ujian',
            'nilai' => 'required|numeric|min:0|max:100',
        ]);

        $ujian = UjianTA::findOrFail($request->ujian_id);

        // Authorization check: user must be ketua_penguji or penguji_ahli
        $user = Auth::user();
        if ($ujian->ketua_penguji_id != $user->id && $ujian->penguji_ahli_id != $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized: Anda bukan penguji ujian ini',
            ], 403);
        }

        // Update ujian with nilai
        $ujian->update([
            'nilai_akhir' => $request->nilai,
            'status_ujian' => 'selesai_ujian',
            'updated_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Nilai berhasil disimpan',
            'data' => [
                'id' => $ujian->id_ujian,
                'nilai' => $ujian->nilai_akhir,
                'status' => 'Sudah Dinilai',
            ],
        ]);
    }

    /**
     * DASHBOARD ADMIN
     */
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
            'notifikasiPenting' => collect(),
        ];

        return view('admin.dashboard', $data);
    }
}
