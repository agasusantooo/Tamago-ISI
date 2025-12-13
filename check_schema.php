<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$table = \Illuminate\Support\Facades\Schema::getColumns('tim_produksi');
echo "Kolom di tabel tim_produksi:\n";
foreach ($table as $col) {
    echo "- " . $col['name'] . " (" . $col['type'] . ")\n";
}
