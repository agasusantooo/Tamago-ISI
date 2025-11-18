<?php
// scripts/debug_ujian.php
// Usage: php scripts/debug_ujian.php [user_id]
$uid = $argv[1] ?? 5;
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Use models
use App\Models\User;
use App\Models\ProjekAkhir;
use App\Models\UjianTA;
use App\Models\Produksi;
use App\Models\Proposal;

$out = ['user_id' => (int)$uid, 'found' => false];
try {
    $user = User::find($uid);
    if (!$user) {
        echo json_encode(['error' => "User id $uid not found"], JSON_PRETTY_PRINT);
        exit(0);
    }
    $out['found'] = true;
    $out['user'] = $user->toArray();

    $mahasiswa = $user->mahasiswa;
    $out['mahasiswa'] = $mahasiswa ? $mahasiswa->toArray() : null;

    $projek = null;
    if ($mahasiswa && $mahasiswa->nim) {
        $projek = ProjekAkhir::where('nim', $mahasiswa->nim)->latest()->first();
    }
    $out['projek'] = $projek ? $projek->toArray() : null;

    $ujian = null;
    if ($projek) {
        $ujian = UjianTA::where('id_proyek_akhir', $projek->id_proyek_akhir)->latest()->first();
    }
    $out['ujian'] = $ujian ? $ujian->toArray() : null;

    // Approved proposal check (controller expects a proposal with status 'disetujui')
    $proposal = null;
    if ($mahasiswa && $mahasiswa->nim) {
        $proposal = Proposal::where('mahasiswa_nim', $mahasiswa->nim)
            ->where('status', 'disetujui')
            ->latest()
            ->first();
    }
    $out['proposal'] = $proposal ? $proposal->toArray() : null;

    // produksi lookup uses mahasiswa_id = user->id in controller
    $produksi = Produksi::where('mahasiswa_id', $user->id)->latest()->first();
    $out['produksi'] = $produksi ? $produksi->toArray() : null;

    echo json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
} catch (\Exception $e) {
    echo json_encode(['error' => $e->getMessage()], JSON_PRETTY_PRINT);
}

