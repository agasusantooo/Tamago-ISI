<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Mahasiswa;
use App\Models\Produksi;
use App\Models\Dosen;

echo "=== Fix Pembimbing Assignment ===\n\n";

// Get all dosen
$dosenList = Dosen::all();
if ($dosenList->isEmpty()) {
    echo "No dosen found. Please create dosen records first.\n";
    exit(1);
}

echo "Found " . $dosenList->count() . " dosen records\n";

// Get mahasiswa without pembimbing
$mahasiswaWithoutPembimbing = Mahasiswa::whereNull('dosen_pembimbing_id')->get();
echo "Found " . $mahasiswaWithoutPembimbing->count() . " mahasiswa without pembimbing\n";

// Assign pembimbing randomly for now
foreach ($mahasiswaWithoutPembimbing as $mahasiswa) {
    $randomDosen = $dosenList->random();
    $mahasiswa->update(['dosen_pembimbing_id' => $randomDosen->nidn]);
    echo "Assigned {$randomDosen->nidn} to mahasiswa {$mahasiswa->nim}\n";
}

// Now fix produksi records
$produksiWithoutDosen = Produksi::whereNull('dosen_id')->get();
echo "\nFound " . $produksiWithoutDosen->count() . " produksi without dosen_id\n";

foreach ($produksiWithoutDosen as $produksi) {
    $mahasiswa = Mahasiswa::where('user_id', $produksi->mahasiswa_id)->first();
    if ($mahasiswa && $mahasiswa->dosen_pembimbing_id) {
        $produksi->update(['dosen_id' => $mahasiswa->dosen_pembimbing_id]);
        echo "Assigned {$mahasiswa->dosen_pembimbing_id} to produksi {$produksi->id}\n";
    } else {
        echo "Could not find pembimbing for produksi {$produksi->id}\n";
    }
}

echo "\n=== Done ===\n";
