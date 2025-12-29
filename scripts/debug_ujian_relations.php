<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\UjianTA;
use App\Models\ProjekAkhir;

$ujians = UjianTA::all();
foreach ($ujians as $u) {
    $projek = ProjekAkhir::where('id_proyek_akhir', $u->id_proyek_akhir)->first();
    echo "Ujian ID: {$u->id_ujian}, id_proyek_akhir: {$u->id_proyek_akhir}, projek_nim: " . ($projek?->nim ?? 'NULL') . ", status_pendaftaran: {$u->status_pendaftaran}\n";
}

$projeks = ProjekAkhir::where('nim', 'like', '%')->take(10)->get();
foreach ($projeks as $p) {
    echo "Projek ID: {$p->id_proyek_akhir}, nim: {$p->nim}, created_at: {$p->created_at}\n";
}
