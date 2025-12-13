<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Produksi;

// Get latest produksi record
$produksi = Produksi::latest()->first();
if ($produksi) {
    echo "✓ Latest Produksi Record Found:\n";
    echo "  ID: " . $produksi->id . "\n";
    echo "  Mahasiswa ID: " . $produksi->mahasiswa_id . "\n";
    echo "  Dosen ID: " . $produksi->dosen_id . "\n";
    echo "\n[PRA PRODUKSI]\n";
    echo "  Status: " . ($produksi->status_pra_produksi ?? 'NULL') . "\n";
    echo "  Feedback: " . ($produksi->feedback_pra_produksi ? substr($produksi->feedback_pra_produksi, 0, 50) . '...' : 'NULL') . "\n";
    echo "  Upload: " . ($produksi->tanggal_upload_pra ?? 'NULL') . "\n";
    echo "  Review: " . ($produksi->tanggal_review_pra ?? 'NULL') . "\n";
    
    echo "\n[PRODUKSI]\n";
    echo "  Status: " . ($produksi->status_produksi ?? 'NULL') . "\n";
    echo "  Feedback: " . ($produksi->feedback_produksi ? substr($produksi->feedback_produksi, 0, 50) . '...' : 'NULL') . "\n";
    echo "  Upload: " . ($produksi->tanggal_upload_produksi ?? 'NULL') . "\n";
    echo "  Review: " . ($produksi->tanggal_review_produksi ?? 'NULL') . "\n";
    
    echo "\n[PASCA PRODUKSI]\n";
    echo "  Status: " . ($produksi->status_pasca_produksi ?? 'NULL') . "\n";
    echo "  Feedback: " . ($produksi->feedback_pasca_produksi ? substr($produksi->feedback_pasca_produksi, 0, 50) . '...' : 'NULL') . "\n";
    echo "  Upload: " . ($produksi->tanggal_upload_pasca ?? 'NULL') . "\n";
    echo "  Review: " . ($produksi->tanggal_review_pasca ?? 'NULL') . "\n";
    
    echo "\n[DATABASE RAW DATA]\n";
    $raw = \DB::table('tim_produksi')->where('id', $produksi->id)->first();
    echo json_encode($raw, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
} else {
    echo "✗ No produksi records found\n";
}
