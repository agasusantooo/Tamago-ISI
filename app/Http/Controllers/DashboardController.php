<?php

namespace App\Http\Controllers;

use App\Models\Bimbingan;
use App\Models\File;
use App\Models\Mahasiswa;
use App\Models\Produksi;
use App\Models\ProjekAkhir;
use App\Models\Proposal;
use App\Models\StoryConference;
use App\Models\TAProgressStage;
use App\Models\Timeline;
use App\Models\UjianTA;
use App\Service\ProgressService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

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

        // Ambil proposal terakhir mahasiswa (define early so it can be used below)
        $latestProposal = null;
        if ($mahasiswa && $mahasiswa->nim) {
            $latestProposal = Proposal::where('mahasiswa_nim', $mahasiswa->nim)
                ->orderBy('created_at', 'desc')
                ->first();
        }

        // Total bimbingan dari database (cari berdasarkan nim atau mahasiswa_id)
        $totalBimbingan = 0;
        if ($mahasiswa && $mahasiswa->nim) {
            $totalBimbingan = Bimbingan::where(function ($q) use ($mahasiswa, $user) {
                $q->where('nim', $mahasiswa->nim)
                    ->orWhere('mahasiswa_id', $user->id);
            })->count();
        }

        // Hitung berapa yang sudah disetujui dan yang masih menunggu
        $approvedBimbinganCount = 0;
        $pendingBimbinganCount = 0;
        if ($mahasiswa && $mahasiswa->nim) {
            $approvedBimbinganCount = Bimbingan::where(function ($q) use ($mahasiswa, $user) {
                $q->where('nim', $mahasiswa->nim)
                    ->orWhere('mahasiswa_id', $user->id);
            })->where('status', 'disetujui')->count();

            $pendingBimbinganCount = Bimbingan::where(function ($q) use ($mahasiswa, $user) {
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
            $bimbinganTerbaru = Bimbingan::where(function ($q) use ($mahasiswa, $user) {
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
            ->get();

        // Personal upcoming items per mahasiswa
        $personalDeadlines = collect();

        // helper: get timeline due_date by stage_code
        $getTimelineDueDate = function ($stageCode) {
            $t = Timeline::whereHas('taProgressStage', function ($q) use ($stageCode) {
                $q->where('stage_code', $stageCode);
            })->whereNotNull('due_date')->orderBy('due_date')->first();

            return $t ? Carbon::parse($t->due_date) : null;
        };

        // Next Bimbingan
        $nextBimbingan = null;
        if ($mahasiswa && $mahasiswa->nim) {
            $nextBimbingan = Bimbingan::where(function ($q) use ($mahasiswa, $user) {
                $q->where('nim', $mahasiswa->nim)
                    ->orWhere('mahasiswa_id', $user->id);
            })
                ->where('tanggal', '>=', Carbon::now())
                ->orderBy('tanggal')
                ->first();
        }
        if ($nextBimbingan) {
            $personalDeadlines->push((object) [
                'taProgressStage' => (object) ['name' => 'Jadwal Bimbingan', 'stage_code' => 'bimbingan_progress'],
                'due_date' => Carbon::parse($nextBimbingan->tanggal) ?: $getTimelineDueDate('bimbingan_progress'),
                'semester' => null,
            ]);
        }

        // Story Conference (pendaftaran/presentasi)
        $nextStory = StoryConference::where('mahasiswa_id', $user->id)
            ->whereNotIn('status', ['selesai'])
            ->orderByRaw('COALESCE(tanggal, tanggal_daftar) ASC')
            ->first();
        if ($nextStory) {
            $dueDate = $nextStory->tanggal ?? $nextStory->tanggal_daftar ?? null;
            $personalDeadlines->push((object) [
                'taProgressStage' => (object) ['name' => 'Story Conference', 'stage_code' => 'story_conference'],
                'due_date' => $dueDate ? Carbon::parse($dueDate) : $getTimelineDueDate('story_conference'),
                'semester' => null,
            ]);
        }

        // Upcoming Ujian TA
        $nextUjian = null;
        if (Schema::hasColumn('ujian_tugas_akhir', 'mahasiswa_id')) {
            $nextUjian = UjianTA::where('mahasiswa_id', $user->id)
                ->whereNotNull('tanggal_ujian')
                ->where('tanggal_ujian', '>=', Carbon::now())
                ->orderBy('tanggal_ujian')
                ->first();
        } elseif ($mahasiswa && $mahasiswa->nim) {
            $projek = ProjekAkhir::where('nim', $mahasiswa->nim)->first();
            if ($projek) {
                $nextUjian = UjianTA::where('id_proyek_akhir', $projek->id_proyek_akhir)
                    ->whereNotNull('tanggal_ujian')
                    ->where('tanggal_ujian', '>=', Carbon::now())
                    ->orderBy('tanggal_ujian')
                    ->first();
            }
        }
        if ($nextUjian) {
            $personalDeadlines->push((object) [
                'taProgressStage' => (object) ['name' => 'Ujian Tugas Akhir', 'stage_code' => 'exam_registration'],
                'due_date' => Carbon::parse($nextUjian->tanggal_ujian) ?: $getTimelineDueDate('exam_registration'),
                'semester' => null,
            ]);
        }

        // Produksi - if not yet completed
        $produksi = Produksi::where('mahasiswa_id', $user->id)
            ->orderBy('id', 'desc')
            ->first();
        if ($produksi && ($produksi->status_pra_produksi !== 'disetujui' || $produksi->status_produksi !== 'disetujui')) {
            $personalDeadlines->push((object) [
                'taProgressStage' => (object) ['name' => 'Upload Produksi / Pra Produksi', 'stage_code' => 'production_upload'],
                'due_date' => $produksi->tanggal_upload_pra ?? $produksi->tanggal_upload_produksi ?? $getTimelineDueDate('production_upload'),
                'semester' => null,
            ]);
        }

        // Pengumpulan Proposal
        if (! $latestProposal) {
            // if there is a timeline for proposal submission, use its due_date
            $proposalTimeline = Timeline::whereHas('taProgressStage', function ($q) {
                $q->where('stage_code', 'proposal_submission');
            })->whereNotNull('due_date')->orderBy('due_date')->first();
            $personalDeadlines->push((object) [
                'taProgressStage' => (object) ['name' => 'Pengumpulan Proposal', 'stage_code' => 'proposal_submission'],
                'due_date' => $proposalTimeline ? Carbon::parse($proposalTimeline->due_date) : $getTimelineDueDate('proposal_submission'),
                'semester' => null,
            ]);
        }

        // If timeline entries exist but do not fill the list, merge personal ones to make up to 5
        $combined = $upcomingDeadlines->map(function ($t) {
            return (object) ['taProgressStage' => $t->taProgressStage, 'due_date' => $t->due_date ? Carbon::parse($t->due_date) : null, 'semester' => $t->semester ?? null];
        });
        // Avoid duplication by stage_code
        $all = $combined->concat($personalDeadlines)
            ->unique(function ($item) {
                $code = optional($item->taProgressStage)->stage_code ?? optional($item->taProgressStage)->name ?? null;

                return $code;
            })
            ->sortBy(function ($item) {
                return $item->due_date ? $item->due_date->timestamp : PHP_INT_MAX;
            })
            ->values()
            ->take(5);

        // jika $all kosong (rare), fallback active stages
        if ($all->isEmpty()) {
            $stages = TAProgressStage::getActiveStages()->take(5);
            $all = $stages->map(function ($s) {
                return (object) [
                    'taProgressStage' => (object) ['name' => $s->stage_name, 'stage_code' => $s->stage_code],
                    'due_date' => null,
                    'semester' => null,
                ];
            })->values();
        }

        $upcomingDeadlines = $all;

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
        $taSelesai = ProjekAkhir::where(function ($query) use ($nidn) {
            $query->where('nidn1', $nidn)
                ->orWhere('nidn2', $nidn);
        })
            ->where('status', 'selesai')
            ->count();

        // Mahasiswa bimbingan aktif dengan progress real dari DB
        $mahasiswaBimbingan = Mahasiswa::where('dosen_pembimbing_id', $nidn)
            ->with('user')
            ->get()
            ->map(function ($m) {
                // Prefer the latest Proposal title (pengumpulan proposal). Fall back to ProjekAkhir if none.
                $projek = ProjekAkhir::where('nim', $m->nim)->first();
                $proposal = \App\Models\Proposal::where('mahasiswa_nim', $m->nim)->latest()->first();
                $judul_ta = optional($proposal)->judul ?? ($projek ? ($projek->judul_ta ?? 'Belum ada judul') : 'Belum ada judul');

                // Compute real progress using ProgressService
                $progress = 0;
                if ($m->user_id) {
                    $progressData = $this->progressService->getDashboardData($m->user_id);
                    $progress = $progressData['percentage'] ?? 0;
                }

                return (object) [
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
            ->map(function ($b) {
                $mahasiswaName = 'Mahasiswa';
                if ($b->nim) {
                    $m = Mahasiswa::where('nim', $b->nim)->first();
                    $mahasiswaName = $m ? ($m->nama ?? optional($m->user)->name) : $b->nim;
                }

                return (object) [
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
            ->map(function ($p) {
                $mahasiswaName = 'Mahasiswa';
                if ($p->mahasiswa_nim) {
                    $m = Mahasiswa::where('nim', $p->mahasiswa_nim)->first();
                    $mahasiswaName = $m ? ($m->nama ?? optional($m->user)->name) : $p->mahasiswa_nim;
                }

                return (object) [
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
            ->map(function ($p) {
                return (object) [
                    'description' => 'Pengajuan proposal: '.($p->judul ?? '-'),
                    'created_at' => $p->created_at,
                ];
            });

        $recentBimbingan = Bimbingan::orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($b) {
                return (object) [
                    'description' => 'Permintaan bimbingan dari '.($b->nim ?? '-'),
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
            ['semester' => Carbon::now()->subMonths(2)->format('Y').' Genap', 'durasi' => 8.5],
            ['semester' => Carbon::now()->subMonths(8)->format('Y').' Ganjil', 'durasi' => 9.1],
        ];

        // Pengumuman: no model, keep empty collection for now
        $pengumumanPenting = collect([]);

        // Ujian TA summary (connect to DB table `ujian_tugas_akhir`)
        $pendingUjian = UjianTA::where('status_pendaftaran', 'pengajuan_ujian')->count();
        $jadwalDitetapkan = UjianTA::where('status_pendaftaran', 'jadwal_ditetapkan')->count();
        $berlangsung = UjianTA::where('status_pendaftaran', 'ujian_berlangsung')->count();
        $selesaiUjian = UjianTA::where('status_ujian', 'selesai_ujian')->count();

        // small list of pending ujian for quick actions
        $pendingUjianList = UjianTA::where('status_pendaftaran', 'pengajuan_ujian')
            ->with(['projekAkhir'])
            ->orderBy('tanggal_daftar', 'asc')
            ->take(5)
            ->get()
            ->map(function ($u) {
                return (object) [
                    'nim' => optional($u->projekAkhir)->nim ?? null,
                    'name' => optional(optional($u->projekAkhir)->mahasiswa)->nama ?? null,
                    'tanggal_daftar' => isset($u->tanggal_daftar) ? $u->tanggal_daftar->format('d M Y') : null,
                ];
            })->values();

        $chartData = [
            'labels' => $labels,
            'lulus' => $lulusCounts,
            'belum_lulus' => $belumCounts,
        ];

        // Header counts for Kaprodi
        $mahasiswaAktifCount = $totalMahasiswa;
        $tugasReview = Proposal::whereIn('status', ['diajukan', 'review'])->count();

        return compact('totalMahasiswa', 'mahasiswaLulus', 'belumLulus', 'aktivitasTerakhir', 'rataDurasiTA', 'pengumumanPenting', 'chartData', 'mahasiswaAktifCount', 'tugasReview', 'pendingUjian', 'jadwalDitetapkan', 'berlangsung', 'selesaiUjian', 'pendingUjianList');
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
        $recentProposals = Proposal::orderBy('created_at', 'desc')->take(5)->get()->map(function ($p) {
            return (object) [
                'description' => 'Pengajuan proposal: '.($p->judul ?? '-'),
                'created_at' => $p->created_at,
            ];
        });

        $recentBimbingan = Bimbingan::orderBy('created_at', 'desc')->take(5)->get()->map(function ($b) {
            return (object) [
                'description' => 'Permintaan bimbingan dari '.($b->nim ?? '-'),
                'created_at' => $b->created_at ?? now(),
            ];
        });

        $aktivitasTerakhir = $recentProposals->merge($recentBimbingan)->sortByDesc('created_at')->values();

        // Tugas menunggu persetujuan: proposals with status 'diajukan' and ujian pending
        $pendingProposals = Proposal::where('status', 'diajukan')->orderBy('created_at', 'desc')->take(5)->get()->map(function ($p) {
            $m = Mahasiswa::where('nim', $p->mahasiswa_nim)->first();

            return (object) [
                'type' => 'Proposal',
                'title' => $p->judul ?? 'Pengajuan Proposal',
                'student' => $m ? $m->nama : ($p->mahasiswa_nim ?? '-'),
                'created_at' => $p->created_at,
            ];
        });

        $pendingUjian = UjianTA::where('status_kelayakan', 'belum')->orderBy('created_at', 'desc')->take(5)->get()->map(function ($u) {
            // find projek_akhir -> mahasiswa name
            $projek = ProjekAkhir::where('id_proyek_akhir', $u->id_proyek_akhir)->first();
            $mname = $projek ? optional($projek->mahasiswa)->nama ?? $projek->nim : ($u->id_proyek_akhir ?? '-');

            return (object) [
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
        $jadwalUjian = UjianTA::whereDate('tanggal_ujian', '>=', now()->toDateString())->orderBy('tanggal_ujian')->take(3)->get()->map(function ($u) {
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

            return (object) [
                'title' => 'Ujian TA',
                'tanggal' => $tanggal,
                'subtitle' => $mname,
            ];
        });

        $jadwalBimbingan = Bimbingan::whereDate('tanggal', '>=', now()->toDateString())->orderBy('tanggal')->take(3)->get()->map(function ($b) {
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

            return (object) [
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

        $recentProposals = Proposal::orderBy('created_at', 'desc')->take(5)->get()->map(function ($p) {
            return (object) [
                'description' => 'Pengajuan proposal: '.($p->judul ?? '-'),
                'created_at' => $p->created_at,
            ];
        });

        $recentBimbingan = Bimbingan::orderBy('created_at', 'desc')->take(5)->get()->map(function ($b) {
            return (object) [
                'description' => 'Permintaan bimbingan dari '.($b->nim ?? '-'),
                'created_at' => $b->created_at ?? now(),
            ];
        });

        $aktivitasTerakhir = $recentProposals->merge($recentBimbingan)->sortByDesc('created_at')->values();

        $pendingProposals = Proposal::where('status', 'diajukan')->orderBy('created_at', 'desc')->take(5)->get()->map(function ($p) {
            $m = Mahasiswa::where('nim', $p->mahasiswa_nim)->first();

            return (object) [
                'type' => 'Proposal',
                'title' => $p->judul ?? 'Pengajuan Proposal',
                'student' => $m ? $m->nama : ($p->mahasiswa_nim ?? '-'),
                'created_at' => $p->created_at,
            ];
        });

        $pendingUjian = UjianTA::where('status_kelayakan', 'belum')->orderBy('created_at', 'desc')->take(5)->get()->map(function ($u) {
            $projek = ProjekAkhir::where('id_proyek_akhir', $u->id_proyek_akhir)->first();
            $mname = $projek ? optional($projek->mahasiswa)->nama ?? $projek->nim : ($u->id_proyek_akhir ?? '-');

            return (object) [
                'type' => 'Ujian',
                'title' => 'Persetujuan Pengajuan Ujian',
                'student' => $mname,
                'created_at' => $u->created_at ?? now(),
            ];
        });

        $tugasMenungguPersetujuan = $pendingProposals->toBase()->merge($pendingUjian->toBase())->take(5);

        $jadwalUjian = UjianTA::whereDate('tanggal_ujian', '>=', now()->toDateString())->orderBy('tanggal_ujian')->take(3)->get()->map(function ($u) {
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

            return (object) [
                'title' => 'Ujian TA',
                'tanggal' => $tanggal,
                'subtitle' => $mname,
            ];
        });

        $jadwalBimbingan = Bimbingan::whereDate('tanggal', '>=', now()->toDateString())->orderBy('tanggal')->take(3)->get()->map(function ($b) {
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

            return (object) [
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
        // Check schema to avoid SQL errors if migrations haven't been run yet
        $schema = \Illuminate\Support\Facades\Schema::getConnection()->getSchemaBuilder();
        $hasKetuaColumn = $schema->hasColumn('ujian_tugas_akhir', 'ketua_penguji_id');
        $hasPengujiColumn = $schema->hasColumn('ujian_tugas_akhir', 'penguji_ahli_id');

        $loggedUserId = $user->id;
        // Ambil ujian TA yang dosen ini menjadi penguji atau pembimbing
        $dosen = \App\Models\Dosen::where('user_id', $user->id)->first();
        $dosenNidn = $dosen?->nidn ?? null;

        if (! $hasKetuaColumn || ! $hasPengujiColumn) {
            // Migrations not applied yet; return empty set and flag (handled by view)
            $ujianTA = collect();
        } else {
            $ujianTA = UjianTA::where(function ($q) use ($loggedUserId, $dosenNidn) {
                $q->where('ketua_penguji_id', $loggedUserId)
                    ->orWhere('penguji_ahli_id', $loggedUserId)
                    ->orWhere('dosen_pembimbing_id', $dosenNidn)
                    ->orWhere('dosen_pembimbing_id', $loggedUserId)
                    // Also consider cases where the ujian row doesn't have dosen_pembimbing_id filled
                    ->orWhereHas('mahasiswa', function ($qq) use ($dosenNidn, $loggedUserId) {
                        $qq->where('dosen_pembimbing_id', $dosenNidn)
                           ->orWhere('dosen_pembimbing_id', $loggedUserId);
                    })
                    ->orWhereHas('projekAkhir', function ($qq) use ($dosenNidn, $loggedUserId) {
                        $qq->whereHas('mahasiswa', function ($qqq) use ($dosenNidn, $loggedUserId) {
                            $qqq->where('dosen_pembimbing_id', $dosenNidn)
                                ->orWhere('dosen_pembimbing_id', $loggedUserId);
                        });
                    });
            })
                ->orderBy('tanggal_ujian', 'asc')
                ->with(['mahasiswa.user','projekAkhir.mahasiswa.user'])
                ->get();

            // Also include ujian where mahasiswa/projek's mahasiswa is this dosen's pembimbing
            $extra = UjianTA::where(function ($q) use ($dosenNidn, $loggedUserId) {
                $q->whereHas('mahasiswa', function ($qq) use ($dosenNidn, $loggedUserId) {
                    $qq->where('dosen_pembimbing_id', $dosenNidn)
                       ->orWhere('dosen_pembimbing_id', $loggedUserId);
                })
                ->orWhereHas('projekAkhir', function ($qq) use ($dosenNidn, $loggedUserId) {
                    $qq->whereHas('mahasiswa', function ($qqq) use ($dosenNidn, $loggedUserId) {
                        $qqq->where('dosen_pembimbing_id', $dosenNidn)
                            ->orWhere('dosen_pembimbing_id', $loggedUserId);
                    });
                });
            })->with(['mahasiswa.user','projekAkhir.mahasiswa.user'])->get();

            $ujianTA = $ujianTA->merge($extra)->unique('id_ujian')->sortBy('tanggal_ujian')->values();

            // Include any pending "pengajuan_ujian" so dosen penguji can see new registrations in real time
            $pending = UjianTA::where('status_pendaftaran', 'pengajuan_ujian')
                ->orderBy('tanggal_ujian', 'asc')
                ->with(['mahasiswa.user','projekAkhir.mahasiswa.user'])
                ->get();

            $ujianTA = $ujianTA->merge($pending)->unique('id_ujian')->sortBy('tanggal_ujian')->values();
        }

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
            'ujianList' => $ujianTA->map(function ($u) {
                // Normalize status keys for the front-end to use concise mapping
                // status_key: 'selesai', 'berlangsung', 'belum' (short keys)
                $statusKey = 'belum';
                if ($u->status_ujian === 'selesai_ujian') {
                    $statusKey = 'selesai';
                } elseif ($u->status_ujian === 'ujian_berlangsung' || $u->status_ujian === 'ujian_berlangsung') {
                    $statusKey = 'berlangsung';
                }

                // Friendly label for display (use badge text from model, fallback to formatted key)
                $statusLabel = $u->getStatusUjianBadgeAttribute()['text'] ?? ucfirst(str_replace('_', ' ', $u->status_ujian));

                return [
                    'id' => $u->id_ujian,
                    'mahasiswa' => ($u->mahasiswa?->name ?: $u->mahasiswa?->user?->name ?: $u->projekAkhir?->mahasiswa?->user?->name) ?? 'Unknown',
                    'nim' => $u->mahasiswa?->nim ?? ($u->projekAkhir?->nim ?? '-'),
                    'judul' => $u->judul_ta ?: ($u->projekAkhir?->judul ?: '-'),
                    'status' => $u->status_ujian,
                    'status_key' => $statusKey,
                    'status_label' => $statusLabel,
                    'jadwal' => $u->tanggal_ujian?->format('Y-m-d H:i') ?? '-',
                    'nilai' => $u->nilai_akhir,
                ];
            })->toArray(),
            'needsMigration' => (! $hasKetuaColumn || ! $hasPengujiColumn),
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
        $schema = \Illuminate\Support\Facades\Schema::getConnection()->getSchemaBuilder();
        $hasKetuaColumn = $schema->hasColumn('ujian_tugas_akhir', 'ketua_penguji_id');
        $hasPengujiColumn = $schema->hasColumn('ujian_tugas_akhir', 'penguji_ahli_id');
        $dosen = \App\Models\Dosen::where('user_id', $user->id)->first();
        $dosenNidn = $dosen?->nidn ?? null;

        if (! $hasKetuaColumn || ! $hasPengujiColumn) {
            $ujianTA = collect();
        } else {
            $ujianTA = UjianTA::where(function ($q) use ($user, $dosenNidn) {
                $q->where('ketua_penguji_id', $user->id)
                    ->orWhere('penguji_ahli_id', $user->id)
                    ->orWhere('dosen_pembimbing_id', $dosenNidn)
                    ->orWhere('dosen_pembimbing_id', $user->id);
            })
                ->orderBy('tanggal_ujian', 'asc')
                ->with(['mahasiswa.user','projekAkhir.mahasiswa.user'])
                ->get()
                ->map(function ($u) {
                    return [
                        'id' => $u->id_ujian,
                        'nim' => $u->mahasiswa?->nim ?? ($u->projekAkhir?->nim ?? '-'),
                        'nama' => ($u->mahasiswa?->name ?: $u->mahasiswa?->user?->name ?: $u->projekAkhir?->mahasiswa?->user?->name) ?? 'Unknown',
                        'judul' => $u->judul_ta ?: ($u->projekAkhir?->judul ?: '-'),
                        'tanggal' => $u->tanggal_ujian?->format('Y-m-d H:i') ?? '-',
                        'status' => $u->nilai_akhir ? 'Sudah Dinilai' : 'Belum Dinilai',
                        'nilai' => $u->nilai_akhir,
                    ];
                });
        }

        // Also include ujian that relate to mahasiswa/projek pembimbing (in case dosen_pembimbing_id on ujian row is empty)
        $extra = UjianTA::where(function ($q) use ($dosenNidn, $user) {
            $q->whereHas('mahasiswa', function ($qq) use ($dosenNidn, $user) {
                $qq->where('dosen_pembimbing_id', $dosenNidn)
                   ->orWhere('dosen_pembimbing_id', $user->id);
            })->orWhereHas('projekAkhir', function ($qq) use ($dosenNidn, $user) {
                $qq->whereHas('mahasiswa', function ($qqq) use ($dosenNidn, $user) {
                    $qqq->where('dosen_pembimbing_id', $dosenNidn)
                        ->orWhere('dosen_pembimbing_id', $user->id);
                });
            });
        })->with(['mahasiswa.user','projekAkhir.mahasiswa.user'])->get()->map(function ($u) {
            return [
                'id' => $u->id_ujian,
                'nim' => $u->mahasiswa?->nim ?? ($u->projekAkhir?->nim ?? '-'),
                'nama' => ($u->mahasiswa?->name ?: $u->mahasiswa?->user?->name ?: $u->projekAkhir?->mahasiswa?->user?->name) ?? 'Unknown',
                'judul' => $u->judul_ta ?: ($u->projekAkhir?->judul ?: '-'),
                'tanggal' => $u->tanggal_ujian?->format('Y-m-d H:i') ?? '-',
                'status' => $u->nilai_akhir ? 'Sudah Dinilai' : 'Belum Dinilai',
                'nilai' => $u->nilai_akhir,
            ];
        });

        $ujianTA = collect($ujianTA)->merge($extra)->unique('id')->values();

        // Also include pending registrations for visibility
        $pending = UjianTA::where('status_pendaftaran', 'pengajuan_ujian')->orderBy('tanggal_ujian', 'asc')->with(['mahasiswa.user','projekAkhir.mahasiswa.user'])->get()->map(function ($u) {
            return [
                'id' => $u->id_ujian,
                'nim' => $u->mahasiswa?->nim ?? ($u->projekAkhir?->nim ?? '-'),
                'nama' => ($u->mahasiswa?->name ?: $u->mahasiswa?->user?->name ?: $u->projekAkhir?->mahasiswa?->user?->name) ?? 'Unknown',
                'judul' => $u->judul_ta ?: ($u->projekAkhir?->judul ?: '-'),
                'tanggal' => $u->tanggal_ujian?->format('Y-m-d H:i') ?? '-',
                'status' => $u->nilai_akhir ? 'Sudah Dinilai' : 'Belum Dinilai',
                'nilai' => $u->nilai_akhir,
            ];
        });

        $ujianTA = collect($ujianTA)->merge($pending)->unique('id')->values();

        return view('dosen_penguji.penilaian', ['ujianList' => $ujianTA]);
    }

    /**
     * Get penilaian data as JSON (for real-time updates)
     */
    public function dosenPengujiPenilaianData()
    {
        $user = Auth::user();
        $schema = \Illuminate\Support\Facades\Schema::getConnection()->getSchemaBuilder();
        $hasKetuaColumn = $schema->hasColumn('ujian_tugas_akhir', 'ketua_penguji_id');
        $hasPengujiColumn = $schema->hasColumn('ujian_tugas_akhir', 'penguji_ahli_id');
        $dosen = \App\Models\Dosen::where('user_id', $user->id)->first();
        $dosenNidn = $dosen?->nidn ?? null;

        if (! $hasKetuaColumn || ! $hasPengujiColumn) {
            $ujianTA = collect();
        } else {
            $ujianTA = UjianTA::where(function ($q) use ($user, $dosenNidn) {
                $q->where('ketua_penguji_id', $user->id)
                    ->orWhere('penguji_ahli_id', $user->id)
                    ->orWhere('dosen_pembimbing_id', $dosenNidn)
                    ->orWhere('dosen_pembimbing_id', $user->id);
            })
                ->orderBy('tanggal_ujian', 'asc')
                ->with(['mahasiswa.user','projekAkhir.mahasiswa.user'])
                ->get()
                ->map(function ($u) {
                    return [
                        'id' => $u->id_ujian,
                        'nim' => $u->mahasiswa?->nim ?? ($u->projekAkhir?->nim ?? '-'),
                        'nama' => ($u->mahasiswa?->name ?: $u->mahasiswa?->user?->name ?: $u->projekAkhir?->mahasiswa?->user?->name) ?? 'Unknown',
                        'judul' => $u->judul_ta ?: ($u->projekAkhir?->judul ?: '-'),
                        'tanggal' => $u->tanggal_ujian?->format('Y-m-d H:i') ?? '-',
                        'status' => $u->nilai_akhir ? 'Sudah Dinilai' : 'Belum Dinilai',
                        'nilai' => $u->nilai_akhir,
                    ];
                });
        }

        // Also include ujian that relate to mahasiswa/projek pembimbing (in case dosen_pembimbing_id on ujian row is empty)
        $extra = UjianTA::where(function ($q) use ($dosenNidn, $user) {
            $q->whereHas('mahasiswa', function ($qq) use ($dosenNidn, $user) {
                $qq->where('dosen_pembimbing_id', $dosenNidn)
                   ->orWhere('dosen_pembimbing_id', $user->id);
            })->orWhereHas('projekAkhir', function ($qq) use ($dosenNidn, $user) {
                $qq->whereHas('mahasiswa', function ($qqq) use ($dosenNidn, $user) {
                    $qqq->where('dosen_pembimbing_id', $dosenNidn)
                        ->orWhere('dosen_pembimbing_id', $user->id);
                });
            });
        })->with(['mahasiswa.user','projekAkhir.mahasiswa.user'])->get()->map(function ($u) {
            return [
                'id' => $u->id_ujian,
                'nim' => $u->mahasiswa?->nim ?? ($u->projekAkhir?->nim ?? '-'),
                'nama' => ($u->mahasiswa?->name ?: $u->mahasiswa?->user?->name ?: $u->projekAkhir?->mahasiswa?->user?->name) ?? 'Unknown',
                'judul' => $u->judul_ta ?: ($u->projekAkhir?->judul ?: '-'),
                'tanggal' => $u->tanggal_ujian?->format('Y-m-d H:i') ?? '-',
                'status' => $u->nilai_akhir ? 'Sudah Dinilai' : 'Belum Dinilai',
                'nilai' => $u->nilai_akhir,
            ];
        });

        $ujianTA = collect($ujianTA)->merge($extra)->unique('id')->values();

        // Include pending registrations as well
        $pending = UjianTA::where('status_pendaftaran', 'pengajuan_ujian')->orderBy('tanggal_ujian', 'asc')->with(['mahasiswa.user','projekAkhir.mahasiswa.user'])->get()->map(function ($u) {
            return [
                'id' => $u->id_ujian,
                'nim' => $u->mahasiswa?->nim ?? ($u->projekAkhir?->nim ?? '-'),
                'nama' => ($u->mahasiswa?->name ?: $u->mahasiswa?->user?->name ?: $u->projekAkhir?->mahasiswa?->user?->name) ?? 'Unknown',
                'judul' => $u->judul_ta ?: ($u->projekAkhir?->judul ?: '-'),
                'tanggal' => $u->tanggal_ujian?->format('Y-m-d H:i') ?? '-',
                'status' => $u->nilai_akhir ? 'Sudah Dinilai' : 'Belum Dinilai',
                'nilai' => $u->nilai_akhir,
            ];
        });

        $ujianTA = collect($ujianTA)->merge($pending)->unique('id')->values();

        return response()->json([
            'status' => 'success',
            'data' => ['ujianList' => $ujianTA, 'needsMigration' => (! $hasKetuaColumn || ! $hasPengujiColumn)],
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

        // Load ujian with relations to check pembimbing-based authorization
        $ujian = UjianTA::with(['mahasiswa','projekAkhir','mahasiswa.user','projekAkhir.mahasiswa'])->findOrFail($request->ujian_id);

        // Authorization: allow ketua/penguji OR dosen pembimbing (either stored on ujian row or on related mahasiswa/projek)
        $user = Auth::user();
        $dosen = \App\Models\Dosen::where('user_id', $user->id)->first();
        $dosenNidn = $dosen?->nidn ?? null;

        $isAuthorized = false;
        // direct penguji check
        if ($ujian->ketua_penguji_id == $user->id || $ujian->penguji_ahli_id == $user->id) {
            $isAuthorized = true;
        }

        // dosen pembimbing stored on ujian row (could be user id or nidn)
        if ($ujian->dosen_pembimbing_id == $user->id || ($dosenNidn && $ujian->dosen_pembimbing_id == $dosenNidn)) {
            $isAuthorized = true;
        }

        // pembimbing set on related mahasiswa
        if ($ujian->mahasiswa && ($ujian->mahasiswa->dosen_pembimbing_id == $user->id || ($dosenNidn && $ujian->mahasiswa->dosen_pembimbing_id == $dosenNidn))) {
            $isAuthorized = true;
        }

        // pembimbing set via projekAkhir->mahasiswa
        if ($ujian->projekAkhir && $ujian->projekAkhir->mahasiswa && (
            $ujian->projekAkhir->mahasiswa->dosen_pembimbing_id == $user->id || ($dosenNidn && $ujian->projekAkhir->mahasiswa->dosen_pembimbing_id == $dosenNidn)
        )) {
            $isAuthorized = true;
        }

        if (! $isAuthorized) {
            // Allow users with the 'dosen_penguji' role to grade entries visible in the penilaian dashboard
            $role = method_exists($user, 'getRoleName') ? $user->getRoleName() : null;
            if ($role !== 'dosen_penguji') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized: Anda bukan penguji atau pembimbing ujian ini',
                ], 403);
            }
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
                (object) ['description' => 'Menambahkan user baru ke sistem', 'created_at' => now()->subMinutes(30)],
                (object) ['description' => 'Melihat log aktivitas sistem', 'created_at' => now()->subHours(2)],
            ]),
            'notifikasiPenting' => collect(),
        ];

        return view('admin.dashboard', $data);
    }
}
