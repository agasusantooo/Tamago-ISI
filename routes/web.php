<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DebugController;
use App\Http\Controllers\Mahasiswa\ProposalController;
use App\Http\Controllers\Mahasiswa\BimbinganController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminLogController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Mahasiswa\StoryConferenceController;
use App\Http\Controllers\Mahasiswa\ProduksiController;
use App\Http\Controllers\Mahasiswa\NaskahKaryaController;
use App\Http\Controllers\Mahasiswa\UjianTAController;
use App\Http\Controllers\Mahasiswa\TefaFairController;

use App\Http\Controllers\DosenPembimbingController;
use App\Http\Controllers\KoordinatorTA\KoordinatorTaskController;
use App\Http\Controllers\Kaprodi\SetupController;
use App\Http\Controllers\Kaprodi\PengelolaanController;
use App\Http\Controllers\Kaprodi\MonitoringController;

/*
|--------------------------------------------------------------------------
| Default Redirect
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect('/login');
});

/*
|--------------------------------------------------------------------------
| Guest Routes (Belum Login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');

    // Register
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
});


// API Dosen Pembimbing
Route::prefix('dosen-pembimbing')->group(function () {
    Route::get('/', [DosenPembimbingController::class, 'index']); // List all
    Route::get('/{nidn}', [DosenPembimbingController::class, 'show']); // Show single
    Route::post('/', [DosenPembimbingController::class, 'store']); // Create
    Route::put('/{nidn}', [DosenPembimbingController::class, 'update']); // Update
    Route::delete('/{nidn}', [DosenPembimbingController::class, 'destroy']); // Delete
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Sudah Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard umum
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| ROLE: MAHASISWA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:mahasiswa'])
    ->prefix('mahasiswa')
    ->name('mahasiswa.')
    ->group(function () {
        // Dashboard Mahasiswa
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // ------------------------
        // PROPOSAL ROUTES
        // ------------------------
        Route::get('/proposal', [ProposalController::class, 'index'])->name('proposal.index');
        Route::get('/proposal/create', [ProposalController::class, 'create'])->name('proposal.create');
        Route::post('/proposal', [ProposalController::class, 'store'])->name('proposal.store');
        Route::get('/proposal/{proposal}/edit', [ProposalController::class, 'edit'])->name('proposal.edit');
        Route::put('/proposal/{proposal}', [ProposalController::class, 'update'])->name('proposal.update');
        Route::post('/proposal/draft', [ProposalController::class, 'saveDraft'])->name('proposal.draft');
        Route::get('/proposal/{id}', [ProposalController::class, 'show'])->name('proposal.show');
        Route::get('/proposal/{id}/download', [ProposalController::class, 'download'])->name('proposal.download');

        // ------------------------
        // BIMBINGAN ROUTES
        // ------------------------
        Route::controller(BimbinganController::class)
            ->prefix('bimbingan')
            ->name('bimbingan.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::get('/{id}', 'show')->name('show');
                Route::get('/{id}/download', 'download')->name('download');
                Route::get('/check/updates', 'checkUpdates')->name('check-updates');
            });

        // ------------------------
        // STORY CONFERENCE ROUTES
        // ------------------------
        Route::controller(StoryConferenceController::class)
            ->prefix('story-conference')
            ->name('story-conference.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/{id}/download', 'download')->name('download');
                Route::delete('/{id}/cancel', 'cancel')->name('cancel');
            });

        // ------------------------
        // PRODUKSI ROUTES
        // ------------------------
        Route::controller(ProduksiController::class)
            ->prefix('produksi')
            ->name('produksi.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/manage', [ProduksiController::class, 'manage'])->name('manage');
                Route::post('/store-pra', 'storePraProduksi')->name('store.pra');
                Route::post('/store-produksi', 'storeProduksi')->name('store.produksi');
                Route::post('/store-pasca', 'storePascaProduksi')->name('store.pasca');
                Route::post('/luaran-tambahan', 'storeLuaranTambahan')->name('luaran-tambahan');
                Route::get('/{id}/{type}', 'download')->name('download');
            });

        // ------------------------
        // UJIAN TA ROUTES
        // ------------------------
        Route::controller(UjianTAController::class)
            ->prefix('ujian-ta')
            ->name('ujian-ta.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::get('/hasil', 'hasil')->name('hasil');
                Route::post('/submit-revisi', 'submitRevisi')->name('submit-revisi');
                Route::get('/{id}/{type}', 'download')->name('download');
            });

        // ------------------------
        // TEFA FAIR ROUTES
        // ------------------------
        Route::controller(TefaFairController::class)
            ->prefix('tefa-fair')
            ->name('tefa-fair.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
            });


    // ------------------------
    // NASKAH & KARYA
    // ------------------------
    Route::get('/naskah-karya', [NaskahKaryaController::class, 'index'])->name('naskah-karya');
    Route::post('/naskah-karya/upload-naskah', [NaskahKaryaController::class, 'uploadNaskah'])->name('naskah-karya.upload');
    Route::get('/naskah-karya/{id}/{type}', [NaskahKaryaController::class, 'download'])->name('naskah-karya.download');
        Route::view('/akun', 'mahasiswa.akun')->name('akun');

    });


/*
|--------------------------------------------------------------------------
| ROLE: DOSEN PEMBIMBING
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:dospem'])
    ->prefix('dospem')
    ->name('dospem.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'dospemDashboard'])->name('dashboard');
        Route::get('/dashboard/data', [DashboardController::class, 'dospemDashboardData'])->name('dashboard.data');

        Route::controller(\App\Http\Controllers\Dospem\DospemController::class)
            ->group(function () {
                Route::get('/review-tugas', 'reviewTugas')->name('review-tugas');
                Route::get('/review-tugas/data', 'reviewTugasData')->name('review-tugas.data');
                Route::get('/jadwal-bimbingan', 'jadwalBimbingan')->name('jadwal-bimbingan');
                Route::get('/jadwal-bimbingan/data', 'jadwalBimbinganData')->name('jadwal-bimbingan.data');
                Route::get('/riwayat-bimbingan', 'riwayatBimbingan')->name('riwayat-bimbingan');
                Route::get('/riwayat-bimbingan/data', 'riwayatBimbinganData')->name('riwayat-bimbingan.data');
            });

        // Mahasiswa Bimbingan routes with real-time data
        Route::controller(\App\Http\Controllers\Dospem\MahasiswaBimbinganController::class)
            ->group(function () {
                Route::get('/mahasiswa-bimbingan', 'index')->name('mahasiswa-bimbingan');
                Route::get('/mahasiswa-bimbingan/data', 'getMahasiswaBimbinganDataJson')->name('mahasiswa-bimbingan.data');
            });

        // Jadwal Approval Routes
        Route::controller(\App\Http\Controllers\Dospem\JadwalApprovalController::class)
            ->prefix('jadwal')
            ->name('jadwal.')
            ->group(function () {
                Route::get('/{id}', 'getJadwal')->name('show');
                Route::post('/{id}/approve', 'approve')->name('approve');
                Route::post('/{id}/reject', 'reject')->name('reject');
            });

        // Jadwal Bimbingan API Routes
        Route::controller(\App\Http\Controllers\Dospem\JadwalBimbinganController::class)
            ->prefix('jadwal-bimbingan')
            ->name('jadwal-bimbingan.')
            ->group(function () {
                Route::get('/events', 'index')->name('events');
                Route::post('/store', 'store')->name('store');
                Route::put('/events/{id}', 'update')->name('update');
                Route::delete('/events/{id}', 'destroy')->name('destroy');
            });

        Route::get('/profile', function () {
            return view('dospem.profile');
        })->name('profile');

        // Routes for Mahasiswa Bimbingan detail / feedback
        Route::match(['GET', 'POST'], '/mahasiswa-bimbingan/{id}', [\App\Http\Controllers\Dospem\MahasiswaBimbinganController::class, 'show'])->name('mahasiswa-bimbingan.show');
        Route::post('/mahasiswa-bimbingan/{id}/feedback', [\App\Http\Controllers\Dospem\MahasiswaBimbinganController::class, 'submitFeedback'])->name('mahasiswa.feedback.submit');

        // Routes for Proposal approval/rejection
        Route::post('/proposal/{id}/update-status', [\App\Http\Controllers\Dospem\MahasiswaBimbinganController::class, 'updateProposalStatus'])->name('proposal.update-status');
        Route::post('/proposal/{id}/approve', [\App\Http\Controllers\Dospem\MahasiswaBimbinganController::class, 'approveProposal'])->name('proposal.approve');
        Route::post('/proposal/{id}/reject', [\App\Http\Controllers\Dospem\MahasiswaBimbinganController::class, 'rejectProposal'])->name('proposal.reject');

        // Routes for Bimbingan approval/rejection
        Route::post('/bimbingan/{id}/approve', [\App\Http\Controllers\Dospem\MahasiswaBimbinganController::class, 'approveBimbingan'])->name('bimbingan.approve');
        Route::post('/bimbingan/{id}/reject', [\App\Http\Controllers\Dospem\MahasiswaBimbinganController::class, 'rejectBimbingan'])->name('bimbingan.reject');

        // Routes for Produksi approval/feedback
        Route::controller(\App\Http\Controllers\Dospem\MahasiswaProduksiController::class)
            ->prefix('produksi')
            ->name('produksi.')
            ->group(function () {
                Route::post('/{id}/pra-produksi', 'approvePraProduksi')->name('pra-produksi');
                Route::post('/{id}/pra-produksi/reject', 'rejectPraProduksi')->name('pra-produksi.reject');
                Route::post('/{id}/produksi', 'approveProduksi')->name('produksi');
                Route::post('/{id}/produksi/reject', 'rejectProduksi')->name('produksi.reject');
                Route::post('/{id}/pasca-produksi', 'approvePascaProduksi')->name('pasca-produksi');
                Route::post('/{id}/pasca-produksi/reject', 'rejectPascaProduksi')->name('pasca-produksi.reject');
                // Real-time API endpoints
                Route::get('/{id}/data', 'getProduksiData')->name('data');
                Route::get('/mahasiswa/{mahasiswaId}/data', 'getMahasiswaProduksiData')->name('mahasiswa-data');
            });
    });

/*
|--------------------------------------------------------------------------
| ROLE: KAPRODI
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:kaprodi'])
    ->prefix('kaprodi')
    ->name('kaprodi.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'kaprodiDashboard'])->name('dashboard');
        Route::get('/dashboard/data', [DashboardController::class, 'kaprodiDashboardData'])->name('dashboard.data');
        // Dashboard khusus Kaprodi untuk Tugas Akhir
        Route::get('/dashboard-ta', [DashboardController::class, 'kaprodiTADashboard'])->name('dashboard.ta');
        Route::view('/verifikasi', 'kaprodi.verifikasi')->name('verifikasi');
        Route::view('/laporan', 'kaprodi.laporan')->name('laporan');
        Route::view('/statistik', 'kaprodi.statistik')->name('statistik');
        Route::view('/manajemen-data', 'kaprodi.manajemen_data')->name('manajemen-data');
        Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring');
        Route::get('/monitoring/{mahasiswa:nim}', [MonitoringController::class, 'show'])->name('monitoring.show');
        
        // Setup and Timeline routes
        Route::get('/setup', [SetupController::class, 'index'])->name('setup');
        Route::post('/setup/semester', [SetupController::class, 'storeSemester'])->name('semester.store');
        Route::get('/timeline', [SetupController::class, 'configureActivitySemesters'])->name('timeline');
        Route::post('/timeline', [SetupController::class, 'storeActivitySemesters'])->name('timeline.store');

        Route::get('/pengelolaan', [PengelolaanController::class, 'index'])->name('pengelolaan');
        Route::post('/pengelolaan/dosen/{dosen}', [PengelolaanController::class, 'updateDosen'])->name('pengelolaan.dosen.update');
        Route::get('/rumpun-ilmu', [PengelolaanController::class, 'rumpunIlmuIndex'])->name('rumpun-ilmu');
        Route::post('/rumpun-ilmu', [PengelolaanController::class, 'rumpunIlmuStore'])->name('rumpun-ilmu.store');
        Route::post('/rumpun-ilmu/assign', [PengelolaanController::class, 'assignDosenToRumpun'])->name('rumpun-ilmu.assign');
        Route::get('/dosen-seminar', [PengelolaanController::class, 'dosenSeminarIndex'])->name('dosen-seminar');
        Route::post('/dosen-seminar', [PengelolaanController::class, 'dosenSeminarUpdate'])->name('dosen-seminar.update');
        Route::get('/koordinator-tefa', [PengelolaanController::class, 'koordinatorTefaIndex'])->name('koordinator-tefa');
        Route::post('/koordinator-tefa', [PengelolaanController::class, 'koordinatorTefaUpdate'])->name('koordinator-tefa.update');

        // Kaprodi task actions (approve / reject) for TA proposals
        Route::post('/tasks/{id}/approve', [\App\Http\Controllers\Kaprodi\KaprodiTaskController::class, 'approve'])->name('tasks.approve');
        Route::post('/tasks/{id}/reject', [\App\Http\Controllers\Kaprodi\KaprodiTaskController::class, 'reject'])->name('tasks.reject');
    });

/*
|--------------------------------------------------------------------------
| ROLE: KOORDINATOR TEFA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:koordinator_tefa'])
    ->prefix('koordinator-tefa')
    ->name('koordinator_tefa.')
    ->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\KoordinatorTefa\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/monitoring', [\App\Http\Controllers\KoordinatorTefa\MonitoringController::class, 'index'])->name('monitoring');
        Route::post('/monitoring/{id}/approve', [\App\Http\Controllers\KoordinatorTefa\MonitoringController::class, 'approve'])->name('monitoring.approve');
        Route::post('/monitoring/{id}/reject', [\App\Http\Controllers\KoordinatorTefa\MonitoringController::class, 'reject'])->name('monitoring.reject');
        
        Route::controller(\App\Http\Controllers\KoordinatorTefa\JadwalController::class)
            ->prefix('jadwal')
            ->name('jadwal.')
            ->group(function() {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
            });
    });

/*
|--------------------------------------------------------------------------
| ROLE: KOORDINATOR STORY CONFERENCE
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:koordinator_story_conference'])
    ->prefix('koordinator-story-conference')
    ->name('koordinator_story_conference.')
    ->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\KoordinatorStoryConference\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/monitoring', [\App\Http\Controllers\KoordinatorStoryConference\MonitoringController::class, 'index'])->name('monitoring');
        Route::post('/monitoring/{id}/approve', [\App\Http\Controllers\KoordinatorStoryConference\MonitoringController::class, 'approve'])->name('monitoring.approve');
        Route::post('/monitoring/{id}/reject', [\App\Http\Controllers\KoordinatorStoryConference\MonitoringController::class, 'reject'])->name('monitoring.reject');

        Route::controller(\App\Http\Controllers\KoordinatorStoryConference\JadwalController::class)
            ->prefix('jadwal')
            ->name('jadwal.')
            ->group(function() {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
            });
    });

/*
|--------------------------------------------------------------------------
| ROLE: KOORDINATOR TA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:koordinator_ta'])
    ->prefix('koordinator-ta')
    ->name('koordinator_ta.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'koordinatorTADashboard'])->name('dashboard');
        Route::get('/jadwal', [\App\Http\Controllers\KoordinatorTA\JadwalController::class, 'showJadwalPage'])->name('jadwal');
        Route::get('/monitoring', [\App\Http\Controllers\KoordinatorTA\MonitoringController::class, 'index'])->name('monitoring');
        Route::post('/monitoring/{id}/approve', [\App\Http\Controllers\KoordinatorTA\MonitoringController::class, 'approve'])->name('monitoring.approve');
        Route::post('/monitoring/{id}/reject', [\App\Http\Controllers\KoordinatorTA\MonitoringController::class, 'reject'])->name('monitoring.reject');
        Route::view('/matakuliah', 'koordinator_ta.matakuliah')->name('matakuliah');
        Route::view('/profile', 'koordinator_ta.profile')->name('profile');

        // Jadwal Kegiatan API Routes
        Route::controller(\App\Http\Controllers\KoordinatorTA\JadwalController::class)
            ->prefix('jadwal')
            ->name('jadwal.')
            ->group(function () {
                Route::get('/events', 'index')->name('events');
                Route::post('/store', 'store')->name('store');
                Route::put('/events/{id}', 'update')->name('update');
                Route::delete('/events/{id}', 'destroy')->name('destroy');
            });

        // Koordinator task actions (approve / reject)
        Route::post('/tasks/{id}/approve', [KoordinatorTaskController::class, 'approve'])->name('tasks.approve');
        Route::post('/tasks/{id}/reject', [KoordinatorTaskController::class, 'reject'])->name('tasks.reject');
    });

/*
|--------------------------------------------------------------------------
| ROLE: ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Optional management pages
        Route::view('/users', 'admin.users')->name('users');
        Route::view('/roles', 'admin.roles')->name('roles');

        // Logs dan Settings
        Route::get('/logs', [AdminLogController::class, 'index'])->name('logs');
        Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings');
    });

/*
|--------------------------------------------------------------------------
| ROLE: DOSEN PENGUJI
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:dosen_penguji'])
    ->prefix('dosen-penguji')
    ->name('dosen_penguji.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'dosenPengujiDashboard'])->name('dashboard');
        Route::get('/dashboard/data', [DashboardController::class, 'dosenPengujiDashboardData'])->name('dashboard.data');
        Route::get('/penilaian', [DashboardController::class, 'dosenPengujiPenilaian'])->name('penilaian');
        Route::get('/penilaian/data', [DashboardController::class, 'dosenPengujiPenilaianData'])->name('penilaian.data');
        Route::post('/penilaian/store', [DashboardController::class, 'storeNilaiUjian'])->name('penilaian.store');
        Route::view('/profile', 'dosen_penguji.profile')->name('profile');
    });

/*
|--------------------------------------------------------------------------
| DASHBOARD DEFAULT (Redirect per Role)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard/default', function () {
    $user = Auth::user();

    switch ($user->role->name) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'mahasiswa':
            return redirect()->route('mahasiswa.dashboard');
        case 'dospem':
            return redirect()->route('dospem.dashboard');
        case 'kaprodi':
            return redirect()->route('kaprodi.dashboard');
        case 'koordinator_ta':
            return redirect()->route('koordinator_ta.dashboard');
        case 'koordinator_tefa':
            return redirect()->route('koordinator_tefa.dashboard');
        case 'koordinator_story_conference':
            return redirect()->route('koordinator_story_conference.dashboard');
        case 'dosen_penguji':
            return redirect()->route('dosen_penguji.dashboard');
        default:
            Auth::logout();
            return redirect()->route('login');
    }
})->middleware('auth')->name('dashboard.default');

/*
|--------------------------------------------------------------------------
| PROFILE ROUTE (Untuk Semua Role)
|--------------------------------------------------------------------------
*/
Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])
    ->middleware('auth')
    ->name('profile.edit');

// Handle profile update (name, email, nim, optional password)
Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])
    ->middleware('auth')
    ->name('profile.update');

// Upload avatar
Route::post('/profile/photo', [App\Http\Controllers\ProfileController::class, 'uploadPhoto'])
    ->middleware('auth')
    ->name('profile.photo');

// Save theme preference server-side
Route::post('/profile/theme', [App\Http\Controllers\ProfileController::class, 'saveTheme'])
    ->middleware('auth')
    ->name('profile.theme');

// Debug routes
Route::get('/debug/bimbingan', [DebugController::class, 'bimbingan'])->middleware('auth');
Route::get('/debug/produksi', [DebugController::class, 'produksi'])->middleware('auth');
    ->name('profile.theme');
