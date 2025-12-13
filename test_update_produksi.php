<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Produksi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

echo "=== Testing Approval Process ===\n\n";

// Get sample produksi
$produksi = Produksi::where('status_pra_produksi', 'menunggu_review')->latest()->first();

if (!$produksi) {
    echo "❌ Tidak ada produksi dengan status menunggu_review\n";
    exit;
}

echo "Target Produksi ID: " . $produksi->id . "\n";
echo "Current Status: " . $produksi->status_pra_produksi . "\n";
echo "Current Feedback: " . ($produksi->feedback_pra_produksi ?? 'NULL') . "\n\n";

// Simulate approval like the controller would do
echo "Simulating approval...\n";

$updateData = [
    'status_pra_produksi' => 'disetujui',
    'tanggal_review_pra' => now(),
];

// Add feedback only if provided
$feedback = 'Baik! Sesuai standar.';
if (!empty($feedback)) {
    $updateData['feedback_pra_produksi'] = $feedback;
}

echo "Update data:\n";
print_r($updateData);

$result = $produksi->update($updateData);

if ($result) {
    echo "\n✅ Update berhasil!\n";
    $produksi->refresh();
    echo "New Status: " . $produksi->status_pra_produksi . "\n";
    echo "New Feedback: " . ($produksi->feedback_pra_produksi ?? 'NULL') . "\n";
    echo "New Tanggal Review: " . ($produksi->tanggal_review_pra ?? 'NULL') . "\n";
} else {
    echo "\n❌ Update gagal!\n";
}
