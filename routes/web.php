<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Mahasiswa\ProposalController;
use App\Http\Controllers\Mahasiswa\BimbinganController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminLogController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Mahasiswa\StoryConferenceController;
use App\Http\Controllers\Mahasiswa\ProduksiController;
use App\Http\Controllers\Mahasiswa\NaskahKaryaController;
use App\Http\Controllers\Mahasiswa\UjianTAController;

use App\Http\Controllers\DosenPembimbingController;

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
        // Alias route mahasiswa.bimbingan ke mahasiswa.bimbingan.index
        Route::get('/bimbingan', [\App\Http\Controllers\Mahasiswa\BimbinganController::class, 'index'])->name('bimbingan');

        // Dashboard Mahasiswa
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // ------------------------
        // PROPOSAL ROUTES
        // ------------------------
        Route::get('/proposal', [ProposalController::class, 'index'])->name('proposal');
        Route::post('/proposal/submit', [ProposalController::class, 'submit'])->name('proposal.submit');
        Route::post('/proposal/draft', [ProposalController::class, 'saveDraft'])->name('proposal.draft');
        Route::get('/proposal/{id}', [ProposalController::class, 'show'])->name('proposal.show');
        Route::get('/proposal/{id}/download', [ProposalController::class, 'download'])->name('proposal.download');

        // ------------------------
        // BIMBINGAN ROUTE (shortcut)
        // ------------------------
        Route::get('/bimbingan', [\App\Http\Controllers\Mahasiswa\BimbinganController::class, 'index'])->name('bimbingan');

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
            });

        // ------------------------
        // STORY CONFERENCE ROUTES
        // ------------------------
        Route::controller(StoryConferenceController::class)
            ->prefix('story-conference')
            ->name('story-conference.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
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
                Route::post('/store-pra', 'storePraProduksi')->name('store.pra');
                Route::post('/produksi-akhir', 'storeProduksiAkhir')->name('produksi-akhir');
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

        Route::controller(\App\Http\Controllers\Dospem\DospemController::class)
            ->group(function () {
                Route::get('/mahasiswa-bimbingan', 'mahasiswaBimbingan')->name('mahasiswa-bimbingan');
                Route::get('/review-tugas', 'reviewTugas')->name('review-tugas');
                Route::get('/jadwal-bimbingan', 'jadwalBimbingan')->name('jadwal-bimbingan');
                Route::get('/riwayat-bimbingan', 'riwayatBimbingan')->name('riwayat-bimbingan');
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
            return view('mahasiswa.akun');
        })->name('profile');
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
        Route::get('/dashboard', [DashboardController::class, 'kaprodi'])->name('dashboard');
        Route::view('/verifikasi', 'kaprodi.verifikasi')->name('verifikasi');
        Route::view('/laporan', 'kaprodi.laporan')->name('laporan');
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
        Route::get('/dashboard', [DashboardController::class, 'koordinatorTa'])->name('dashboard');
        Route::view('/monitoring', 'koordinator_ta.monitoring')->name('monitoring');
        Route::view('/dospem', 'koordinator_ta.dospem')->name('dospem');
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
        Route::get('/dashboard', [DashboardController::class, 'dosenPenguji'])->name('dashboard');
        Route::view('/jadwal', 'dosen_penguji.jadwal')->name('jadwal');
        Route::view('/penilaian', 'dosen_penguji.penilaian')->name('penilaian');
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
Route::get('/profile', function () {
    return view('mahasiswa.akun');
})->middleware('auth')->name('profile.edit');

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
