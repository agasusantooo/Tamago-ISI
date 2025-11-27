<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\User;
use App\Models\Mahasiswa;

echo "=== Cleanup User tanpa Mahasiswa Link ===\n\n";

// Get all mahasiswa users
$mahasiswaUsers = User::whereHas('role', function($q) {
    $q->where('name', 'mahasiswa');
})->get();

$unlinkedUsers = [];
$linkedCount = 0;

foreach ($mahasiswaUsers as $user) {
    if (!$user->mahasiswa) {
        $unlinkedUsers[] = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email
        ];
    } else {
        $linkedCount++;
    }
}

echo "Summary:\n";
echo "- Total mahasiswa users: " . $mahasiswaUsers->count() . "\n";
echo "- Linked: $linkedCount\n";
echo "- Unlinked: " . count($unlinkedUsers) . "\n\n";

if (count($unlinkedUsers) > 0) {
    echo "Unlinked Users (AKAN DIHAPUS):\n";
    foreach ($unlinkedUsers as $user) {
        echo "  ID: {$user['id']}, Name: {$user['name']}, Email: {$user['email']}\n";
    }
    echo "\nDeleting unlinked users...\n";
    
    foreach ($unlinkedUsers as $userData) {
        $user = User::find($userData['id']);
        $user->delete();
        echo "✓ Deleted: {$userData['name']}\n";
    }
    
    echo "\n✓ Cleanup complete!\n";
} else {
    echo "✓ Semua mahasiswa users sudah ter-link!\n";
}
