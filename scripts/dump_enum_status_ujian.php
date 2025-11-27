<?php
// scripts/dump_enum_status_ujian.php
// Usage: php scripts/dump_enum_status_ujian.php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $col = DB::select("SHOW COLUMNS FROM ujian_tugas_akhir LIKE 'status_ujian'");
    if (empty($col)) {
        echo "Column not found\n";
        exit(1);
    }
    $type = $col[0]->Type ?? ($col[0]['Type'] ?? null);
    echo "Raw Type: ". $type . "\n";

    // extract values from enum('a','b',...)
    if (preg_match_all("/'([^']+)'/", $type, $m)) {
        $values = $m[1];
        echo "Allowed values:\n";
        foreach ($values as $v) {
            echo " - $v\n";
        }
    } else {
        echo "No enum values parsed\n";
    }
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
