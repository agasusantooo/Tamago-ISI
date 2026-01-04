<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Mahasiswa;
use App\Models\Produksi;
use App\Models\Dosen;

echo "=== Comprehensive Fix for Pembimbing Assignment ===\n\n";

// Get all dosen
$dosenList = Dosen::all();
if ($dosenList->isEmpty()) {
    echo "No dosen found. Please create dosen records first.\n";
    exit(1);
}

echo "Found " . $dosenList->count() . " dosen records:\n";
foreach ($dosenList as $dosen) {
    echo "  - {$dosen->nidn} (user_id: " . ($dosen->user_id ?? 'null') . ")\n";
}

// Step 1: Assign pembimbing to mahasiswa without one
$mahasiswaWithoutPembimbing = Mahasiswa::whereNull('dosen_pembimbing_id')->get();
echo "\nFound " . $mahasiswaWithoutPembimbing->count() . " mahasiswa without pembimbing\n";

foreach ($mahasiswaWithoutPembimbing as $mahasiswa) {
    // Assign the first dosen (or you can randomize)
    $assignedDosen = $dosenList->first();
    $mahasiswa->dosen_pembimbing_id = $assignedDosen->nidn;
    $mahasiswa->save();
    echo "✓ Assigned {$assignedDosen->nidn} to mahasiswa {$mahasiswa->nim}\n";
}

// Step 2: Assign dosen_id to produksi based on mahasiswa's pembimbing
$produksiWithoutDosen = Produksi::whereNull('dosen_id')->get();
echo "\nFound " . $produksiWithoutDosen->count() . " produksi without dosen_id\n";

foreach ($produksiWithoutDosen as $produksi) {
    $mahasiswa = Mahasiswa::where('user_id', $produksi->mahasiswa_id)->first();
    if ($mahasiswa && $mahasiswa->dosen_pembimbing_id) {
        $produksi->dosen_id = $mahasiswa->dosen_pembimbing_id;
        $produksi->save();
        echo "✓ Assigned {$mahasiswa->dosen_pembimbing_id} to produksi {$produksi->id} (mahasiswa: {$mahasiswa->nim})\n";
    } else {
        echo "✗ Could not find pembimbing for produksi {$produksi->id} (mahasiswa user_id: {$produksi->mahasiswa_id})\n";
    }
}

// Step 3: Verify the fixes
echo "\n=== Verification ===\n";

$remainingMahasiswa = Mahasiswa::whereNull('dosen_pembimbing_id')->count();
$remainingProduksi = Produksi::whereNull('dosen_id')->count();

echo "Mahasiswa without pembimbing: {$remainingMahasiswa}\n";
echo "Produksi without dosen_id: {$remainingProduksi}\n";

if ($remainingMahasiswa == 0 && $remainingProduksi == 0) {
    echo "✅ All assignments completed successfully!\n";
} else {
    echo "⚠️ Some assignments may still be missing.\n";
}

echo "\n=== Done ===\n";
