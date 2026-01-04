<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Mahasiswa;

$rows = Mahasiswa::whereNotNull('dosen_pembimbing_id')->get();
if ($rows->isEmpty()) {
    echo "No mahasiswa with dosen_pembimbing_id set\n";
} else {
    foreach ($rows as $m) {
        echo "user_id={$m->user_id}, nim={$m->nim}, dosen_pembimbing_id={$m->dosen_pembimbing_id}\n";
    }
}
