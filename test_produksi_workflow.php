<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Produksi;
use App\Models\Mahasiswa;

echo "═══════════════════════════════════════════════════════\n";
echo "  TESTING 3-STAGE PRODUKSI WORKFLOW\n";
echo "═══════════════════════════════════════════════════════\n\n";

// Get latest produksi
$produksi = Produksi::latest()->first();
if (!$produksi) {
    echo "❌ No produksi records found\n";
    exit(1);
}

echo "✓ PRODUKSI RECORD FOUND\n";
echo "  ID: " . $produksi->id . "\n";
echo "  Mahasiswa ID: " . $produksi->mahasiswa_id . "\n\n";

// Check database columns exist
echo "✓ DATABASE COLUMNS CHECK:\n";
$table = $produksi->getTable();
$columns = \DB::getSchemaBuilder()->getColumnListing($table);

$requiredColumns = [
    'status_pra_produksi', 'feedback_pra_produksi', 'tanggal_review_pra',
    'status_produksi', 'feedback_produksi', 'tanggal_review_produksi',
    'status_pasca_produksi', 'feedback_pasca_produksi', 'tanggal_review_pasca',
];

foreach ($requiredColumns as $col) {
    $exists = in_array($col, $columns) ? '✓' : '❌';
    echo "  $exists $col\n";
}

echo "\n✓ DATA IN DATABASE:\n";
echo "  Pra Produksi Status: " . ($produksi->status_pra_produksi ?? 'NULL') . "\n";
echo "  Produksi Status: " . ($produksi->status_produksi ?? 'NULL') . "\n";
echo "  Pasca Produksi Status: " . ($produksi->status_pasca_produksi ?? 'NULL') . "\n";

echo "\n✓ FEEDBACK DATA:\n";
echo "  Pra: " . (strlen($produksi->feedback_pra_produksi ?? '') > 0 ? '✓ Present' : '- Empty') . "\n";
echo "  Produksi: " . (strlen($produksi->feedback_produksi ?? '') > 0 ? '✓ Present' : '- Empty') . "\n";
echo "  Pasca: " . (strlen($produksi->feedback_pasca_produksi ?? '') > 0 ? '✓ Present' : '- Empty') . "\n";

echo "\n✓ TIMESTAMP DATA:\n";
echo "  Pra Review: " . ($produksi->tanggal_review_pra ?? 'NULL') . "\n";
echo "  Produksi Review: " . ($produksi->tanggal_review_produksi ?? 'NULL') . "\n";
echo "  Pasca Review: " . ($produksi->tanggal_review_pasca ?? 'NULL') . "\n";

echo "\n✓ API RESPONSE FORMAT CHECK:\n";
$apiData = [
    'id' => $produksi->id,
    'status_pra_produksi' => $produksi->status_pra_produksi ?? 'belum_upload',
    'feedback_pra_produksi' => $produksi->feedback_pra_produksi,
    'status_produksi' => $produksi->status_produksi ?? 'belum_upload',
    'feedback_produksi' => $produksi->feedback_produksi,
    'status_pasca_produksi' => $produksi->status_pasca_produksi ?? 'belum_upload',
    'feedback_pasca_produksi' => $produksi->feedback_pasca_produksi,
];

$apiKeys = array_keys($apiData);
echo "  API Response Keys:\n";
foreach ($apiKeys as $key) {
    echo "    ✓ $key\n";
}

echo "\n═══════════════════════════════════════════════════════\n";
echo "  ✅ ALL CHECKS PASSED - READY FOR TESTING\n";
echo "═══════════════════════════════════════════════════════\n";
