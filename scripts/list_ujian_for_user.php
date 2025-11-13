<?php
// scripts/list_ujian_for_user.php
// Usage: php scripts/list_ujian_for_user.php [user_id]
$uid = $argv[1] ?? 5;
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\ProjekAkhir;
use App\Models\UjianTA;

$user = User::find($uid);
if (!$user) {
    echo "User $uid not found\n";
    exit(1);
}
$mahasiswa = $user->mahasiswa;
if (!$mahasiswa) {
    echo "Mahasiswa relation not found for user $uid\n";
    exit(1);
}

echo "User id={$user->id}, nim={$mahasiswa->nim}\n";

$projek = ProjekAkhir::where('nim', $mahasiswa->nim)->latest()->first();
if (!$projek) {
    echo "No ProjekAkhir found for nim {$mahasiswa->nim}\n";
} else {
    echo "ProjekAkhir id_proyek_akhir={$projek->id_proyek_akhir}, judul={$projek->judul}, status={$projek->status}\n";

    $ujians = UjianTA::where('id_proyek_akhir', $projek->id_proyek_akhir)->get();
    if ($ujians->isEmpty()) {
        echo "No UjianTA rows for projek id {$projek->id_proyek_akhir}\n";
    } else {
        echo "UjianTA rows:\n";
        foreach ($ujians as $u) {
            echo "- id_ujian=" . ($u->id_ujian ?? $u->getKey()) . ", status_pendaftaran={$u->status_pendaftaran}, status_ujian={$u->status_ujian}, tanggal_daftar={$u->tanggal_daftar}, file_surat_pengantar={$u->file_surat_pengantar}, file_transkrip_nilai={$u->file_transkrip_nilai}\n";
        }
    }
}

echo "Done\n";
