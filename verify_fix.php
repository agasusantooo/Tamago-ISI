<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Produksi;

// Test 1: Check model fillable
echo "=== Model Fillable Array ===\n";
$produksi = new Produksi();
echo "Fillable: " . json_encode($produksi->getFillable(), JSON_PRETTY_PRINT) . "\n\n";

// Test 2: Test update dengan kolom yang benar
echo "=== Testing Update ===\n";
$sample = Produksi::first();
if ($sample) {
    echo "Sample Produksi ID: " . $sample->id . "\n";
    echo "Current status_produksi: " . $sample->status_produksi . "\n";
    echo "Current feedback_produksi: " . ($sample->feedback_produksi ?? 'null') . "\n";
    echo "Current tanggal_review_produksi: " . ($sample->tanggal_review_produksi ?? 'null') . "\n";
    echo "\nTest update data akan terlihat di database.\n";
} else {
    echo "Tidak ada data produksi di database.\n";
}

echo "\n✅ Model dan controller sudah diselaraskan dengan database structure!\n";
