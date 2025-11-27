<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\User;
use App\Models\Mahasiswa;

echo "=== Checking Mahasiswa-User Linking ===\n\n";

// Get all users with mahasiswa role
$mahasiswaUsers = User::whereHas('role', function($q) {
    $q->where('name', 'mahasiswa');
})->get();

echo "Found " . $mahasiswaUsers->count() . " mahasiswa users\n\n";

foreach ($mahasiswaUsers as $user) {
    echo "User: {$user->name} (ID: {$user->id}, Email: {$user->email})\n";
    
    // Check if mahasiswa record exists
    $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();
    
    if ($mahasiswa) {
        echo "  ✓ Linked to Mahasiswa NIM: {$mahasiswa->nim}\n";
    } else {
        echo "  ✗ NOT LINKED - Looking for matching NIM...\n";
        
        // Try to find mahasiswa by matching name or other criteria
        $possibleMatch = Mahasiswa::whereNull('user_id')
            ->where('nama', 'LIKE', '%' . $user->name . '%')
            ->first();
        
        if ($possibleMatch) {
            echo "    Found possible match: NIM {$possibleMatch->nim} - {$possibleMatch->nama}\n";
            echo "    Linking now...\n";
            $possibleMatch->update(['user_id' => $user->id]);
            echo "    ✓ Linked!\n";
        } else {
            echo "    No matching mahasiswa record found\n";
        }
    }
    echo "\n";
}

echo "\n=== Summary ===\n";
$linkedCount = Mahasiswa::whereNotNull('user_id')->count();
$totalMahasiswa = Mahasiswa::count();
echo "Linked: {$linkedCount}/{$totalMahasiswa}\n";
