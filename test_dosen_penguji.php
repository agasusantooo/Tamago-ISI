<?php
/**
 * Test script for Dosen Penguji functionality
 */

// Start PHP artisan
$env = file_get_contents('.env');
if (!str_contains($env, 'APP_KEY')) {
    die("APP_KEY not found in .env\n");
}

// Load Laravel
require_once __DIR__ . '/bootstrap/app.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

// Boot the application
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Test UjianTA model
echo "Testing UjianTA model...\n";
try {
    $ujian = \App\Models\UjianTA::first();
    if ($ujian) {
        echo "✓ UjianTA table exists\n";
        echo "  - Total records: " . \App\Models\UjianTA::count() . "\n";
        echo "  - Sample record: ID=" . $ujian->id_ujian . "\n";
    } else {
        echo "✗ No ujian records found\n";
    }
} catch (\Exception $e) {
    echo "✗ Error accessing UjianTA: " . $e->getMessage() . "\n";
}

// Test User model
echo "\nTesting User model...\n";
try {
    $user = \App\Models\User::where('role', 'dosen_penguji')->first();
    if ($user) {
        echo "✓ Dosen Penguji user found\n";
        echo "  - Name: " . $user->name . "\n";
        echo "  - Email: " . $user->email . "\n";
    } else {
        echo "✗ No dosen_penguji user found\n";
    }
} catch (\Exception $e) {
    echo "✗ Error accessing User: " . $e->getMessage() . "\n";
}

// Test DashboardController
echo "\nTesting DashboardController methods...\n";
try {
    $controller = new \App\Http\Controllers\DashboardController(new \App\Service\ProgressService());
    echo "✓ DashboardController instantiated\n";
    
    // Check if methods exist
    $methods = ['dosenPengujiDashboard', 'dosenPengujiPenilaian', 'storeNilaiUjian'];
    foreach ($methods as $method) {
        if (method_exists($controller, $method)) {
            echo "  ✓ Method $method exists\n";
        } else {
            echo "  ✗ Method $method not found\n";
        }
    }
} catch (\Exception $e) {
    echo "✗ Error with DashboardController: " . $e->getMessage() . "\n";
}

echo "\nDone!\n";
