<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\UjianTA;

try {
    echo "Checking ujian_tugas_akhir table columns:\n";
    $columns = DB::select("SHOW COLUMNS FROM ujian_tugas_akhir");
    foreach ($columns as $col) {
        echo "- {$col->Field}: {$col->Type}\n";
    }

    echo "\nChecking existing ujian records:\n";
    $ujians = UjianTA::all();
    foreach ($ujians as $u) {
        echo "ID: {$u->id_ujian}, status_pendaftaran: {$u->status_pendaftaran}, status_ujian: {$u->status_ujian}\n";
    }

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
