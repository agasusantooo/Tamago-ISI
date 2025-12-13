<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Produksi;
use Illuminate\Support\Facades\DB;

echo "=== Checking Database ===\n";
$produksi = Produksi::latest()->first();

if ($produksi) {
    echo "Latest Produksi ID: " . $produksi->id . "\n";
    echo "Status Pra Produksi: " . $produksi->status_pra_produksi . "\n";
    echo "Feedback Pra Produksi: " . ($produksi->feedback_pra_produksi ?? 'NULL') . "\n";
    echo "Tanggal Review Pra: " . ($produksi->tanggal_review_pra ?? 'NULL') . "\n";
} else {
    echo "Tidak ada data produksi\n";
}

// Check raw database
echo "\n=== Raw Database Query ===\n";
$data = DB::table('tim_produksi')->latest()->first();
if ($data) {
    echo "ID: " . $data->id . "\n";
    echo "status_pra_produksi: " . $data->status_pra_produksi . "\n";
    echo "feedback_pra_produksi: " . ($data->feedback_pra_produksi ?? 'NULL') . "\n";
    echo "tanggal_review_pra: " . ($data->tanggal_review_pra ?? 'NULL') . "\n";
}
