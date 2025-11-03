<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Mahasiswa\ProposalController;

// Redirect ke login

Route::get('/', function () {
    return redirect('/login');
});

// Routes untuk guest (belum login)
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');

    // Register
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
});

// Routes untuk user yang sudah login
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard umum
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// ROLE: MAHASISWA
Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    // Menu Proposal
    Route::get('/proposal', [ProposalController::class, 'index'])->name('proposal');
    Route::post('/proposal/submit', [ProposalController::class, 'submit'])->name('proposal.submit');
    Route::post('/proposal/draft', [ProposalController::class, 'saveDraft'])->name('proposal.draft');
    Route::get('/proposal/{id}', [ProposalController::class, 'show'])->name('proposal.show');
    Route::get('/proposal/{id}/download', [ProposalController::class, 'download'])->name('proposal.download');

    // Menu tambahan
    Route::view('/bimbingan', 'mahasiswa.bimbingan')->name('bimbingan');
    Route::view('/story-conference', 'mahasiswa.story-conference')->name('story-conference');
    Route::view('/produksi', 'mahasiswa.produksi')->name('produksi');
    Route::view('/ujian-ta', 'mahasiswa.ujian-ta')->name('ujian-ta');
    Route::view('/naskah-karya', 'mahasiswa.naskah-karya')->name('naskah-karya');
    Route::view('/akun', 'mahasiswa.akun')->name('akun');
});

// ROLE: DOSEN PEMBIMBING
Route::middleware(['auth', 'role:dospem'])->prefix('dospem')->name('dospem.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dospemDashboard'])->name('dashboard');


    // Daftar mahasiswa bimbingan
    Route::view('/mahasiswa-bimbingan', 'dospem.mahasiswa-bimbingan')->name('mahasiswa-bimbingan');

    // Review tugas mahasiswa
    Route::view('/review-tugas', 'dospem.review-tugas')->name('review-tugas');

    // Jadwal bimbingan
    Route::view('/jadwal-bimbingan', 'dospem.jadwal-bimbingan')->name('jadwal-bimbingan');

    // Riwayat bimbingan
    Route::view('/riwayat-bimbingan', 'dospem.riwayat-bimbingan')->name('riwayat-bimbingan');
});

// ROLE: KAPRODI
Route::middleware(['auth', 'role:kaprodi'])->prefix('kaprodi')->name('kaprodi.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'kaprodi'])->name('dashboard');
    Route::view('/verifikasi', 'kaprodi.verifikasi')->name('verifikasi');
    Route::view('/laporan', 'kaprodi.laporan')->name('laporan');
});

// ROLE: KOORDINATOR TA
Route::middleware(['auth', 'role:koordinator_ta'])->prefix('koordinator-ta')->name('koordinator_ta.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'koordinatorTa'])->name('dashboard');
    Route::view('/monitoring', 'koordinator_ta.monitoring')->name('monitoring');
    Route::view('/dospem', 'koordinator_ta.dospem')->name('dospem');
});

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard Admin
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Halaman manajemen user dan role (opsional)
        Route::view('/users', 'admin.users')->name('users');
        Route::view('/roles', 'admin.roles')->name('roles');

        // Logs dan Settings
        Route::get('/logs', [AdminLogController::class, 'index'])->name('logs');
        Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings');
    });



// ROLE: DOSEN PENGUJI
Route::middleware(['auth', 'role:dosen_penguji'])->prefix('dosen-penguji')->name('dosen_penguji.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dosenPenguji'])->name('dashboard');
    Route::view('/jadwal', 'dosen_penguji.jadwal')->name('jadwal');
    Route::view('/penilaian', 'dosen_penguji.penilaian')->name('penilaian');
});

// DASHBOARD DEFAULT (Redirect per Role)
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

// PROFILE USER (SEMUA ROLE)
Route::get('/profile', function () {
    return view('profile.edit');
})->middleware('auth')->name('profile.edit');



// Mahasiswa Routes - Protected by auth middleware
Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    
    // Proposal Routes
    Route::get('/proposal', [ProposalController::class, 'index'])->name('proposal');
    Route::post('/proposal/submit', [ProposalController::class, 'submit'])->name('proposal.submit');
    Route::post('/proposal/draft', [ProposalController::class, 'saveDraft'])->name('proposal.draft');
    Route::get('/proposal/download/{id}', [ProposalController::class, 'download'])->name('proposal.download');
    
});
