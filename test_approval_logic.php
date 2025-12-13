<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Produksi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

echo "=== Test Approval Logic ===\n\n";

// Get sample data
$produksi = Produksi::first();
if (!$produksi) {
    echo "❌ Tidak ada data produksi\n";
    exit;
}

echo "Sample Produksi:\n";
echo "- ID: " . $produksi->id . "\n";
echo "- Mahasiswa ID: " . $produksi->mahasiswa_id . "\n";
echo "- Status Pra: " . $produksi->status_pra_produksi . "\n";
echo "- Status Produksi (akhir): " . $produksi->status_produksi . "\n";
echo "- Feedback Produksi: " . ($produksi->feedback_produksi ?? 'NULL') . "\n";
echo "- Tanggal Review Produksi: " . ($produksi->tanggal_review_produksi ?? 'NULL') . "\n";

echo "\n=== Simulating Approval ===\n";

// Simulasi approval
$updateData = [
    'status_produksi' => 'disetujui',
    'tanggal_review_produksi' => now(),
    'feedback_produksi' => 'Bagus! Sudah sesuai standar.'
];

$produksi->update($updateData);
$produksi->refresh();

echo "\nSetelah update:\n";
echo "- Status Produksi: " . $produksi->status_produksi . "\n";
echo "- Feedback Produksi: " . $produksi->feedback_produksi . "\n";
echo "- Tanggal Review Produksi: " . $produksi->tanggal_review_produksi . "\n";

echo "\n✅ Test approval logic berhasil!\n";
