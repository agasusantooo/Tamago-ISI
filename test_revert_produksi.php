<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Produksi;

$targetId = $argv[1] ?? 12;

$produksi = Produksi::find($targetId);
if (! $produksi) {
    echo "Produksi ID {$targetId} not found\n";
    exit(1);
}

echo "Before revert:\n";
echo "  id: {$produksi->id}\n";
echo "  status_pra_produksi: {$produksi->status_pra_produksi}\n";
echo "  feedback_pra_produksi: " . ($produksi->feedback_pra_produksi ?? 'NULL') . "\n";
echo "  tanggal_review_pra: " . ($produksi->tanggal_review_pra ?? 'NULL') . "\n";
echo "  status_produksi: {$produksi->status_produksi}\n";
echo "  feedback_produksi: " . ($produksi->feedback_produksi ?? 'NULL') . "\n";
echo "  tanggal_review_produksi: " . ($produksi->tanggal_review_produksi ?? 'NULL') . "\n";
echo "  status_pasca_produksi: {$produksi->status_pasca_produksi}\n";
echo "  feedback_pasca_produksi: " . ($produksi->feedback_pasca_produksi ?? 'NULL') . "\n";
echo "  tanggal_review_pasca: " . ($produksi->tanggal_review_pasca ?? 'NULL') . "\n\n";

// Revert to original values
$produksi->update([
    'status_pra_produksi' => 'menunggu_review',
    'feedback_pra_produksi' => null,
    'tanggal_review_pra' => null,
    'status_produksi' => 'belum_upload',
    'feedback_produksi' => null,
    'tanggal_review_produksi' => null,
    'status_pasca_produksi' => 'belum_upload',
    'feedback_pasca_produksi' => null,
    'tanggal_review_pasca' => null,
]);

$produksi->refresh();

echo "After revert:\n";
echo "  status_pra_produksi: {$produksi->status_pra_produksi}\n";
echo "  feedback_pra_produksi: " . ($produksi->feedback_pra_produksi ?? 'NULL') . "\n";
echo "  tanggal_review_pra: " . ($produksi->tanggal_review_pra ?? 'NULL') . "\n";
echo "  status_produksi: {$produksi->status_produksi}\n";
echo "  feedback_produksi: " . ($produksi->feedback_produksi ?? 'NULL') . "\n";
echo "  tanggal_review_produksi: " . ($produksi->tanggal_review_produksi ?? 'NULL') . "\n";
echo "  status_pasca_produksi: {$produksi->status_pasca_produksi}\n";
echo "  feedback_pasca_produksi: " . ($produksi->feedback_pasca_produksi ?? 'NULL') . "\n";
echo "  tanggal_review_pasca: " . ($produksi->tanggal_review_pasca ?? 'NULL') . "\n";

echo "Revert completed for Produksi ID {$targetId}\n";
