<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Dosen;
use App\Models\Produksi;

echo "=== Finding Test User ===\n\n";

// Get any dosen with user
$dosen = Dosen::with('user')->first();
if ($dosen) {
    echo "Found Dosen:\n";
    echo "  NIDN: " . $dosen->nidn . "\n";
    echo "  Nama: " . $dosen->nama . "\n";
    if ($dosen->user) {
        echo "  User ID: " . $dosen->user->id . "\n";
        echo "  Email: " . $dosen->user->email . "\n";
    } else {
        echo "  (No user linked)\n";
    }
    echo "\n";
}

// Get any produksi with status menunggu_review
$produksi = Produksi::where('status_pra_produksi', 'menunggu_review')->first();
if ($produksi) {
    echo "Found Produksi waiting review:\n";
    echo "  ID: " . $produksi->id . "\n";
    echo "  Mahasiswa ID: " . $produksi->mahasiswa_id . "\n";
    echo "  Dosen ID (stored): " . var_export($produksi->dosen_id, true) . "\n";
    echo "  Status: " . $produksi->status_pra_produksi . "\n";
} else {
    echo "No produksi found in menunggu_review status.\n";
    // Show any produksi
    $any = Produksi::first();
    if ($any) {
        echo "\nFirst produksi:\n";
        echo "  ID: " . $any->id . "\n";
        echo "  Status: " . $any->status_pra_produksi . "\n";
        echo "  Dosen ID: " . var_export($any->dosen_id, true) . "\n";
    }
}
