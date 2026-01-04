<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\UjianTA;

$ujians = UjianTA::all();
foreach ($ujians as $u) {
    echo "Ujian ID: {$u->id_ujian}, ketua_penguji_id: {$u->ketua_penguji_id}, penguji_ahli_id: {$u->penguji_ahli_id}, dosen_pembimbing_id: {$u->dosen_pembimbing_id}\n";
}
