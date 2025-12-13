<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Mahasiswa;
use App\Http\Controllers\Dospem\MahasiswaProduksiController;
use Illuminate\Http\Request;

echo "=== Testing getMahasiswaProduksiData API ===\n\n";

// Get first mahasiswa
$mahasiswa = Mahasiswa::first();

if (!$mahasiswa) {
    echo "❌ Tidak ada mahasiswa\n";
    exit;
}

echo "Test dengan Mahasiswa NIM: " . $mahasiswa->nim . " (User ID: " . $mahasiswa->user_id . ")\n\n";

// Use nim as identifier since id is NULL (nim is the primary key)
$identifier = $mahasiswa->nim ?? $mahasiswa->user_id;

echo "Using identifier: $identifier\n\n";

// Call controller method directly
$controller = new MahasiswaProduksiController();

try {
    $response = $controller->getMahasiswaProduksiData($identifier);
    
    echo "Response Status Code: " . $response->status() . "\n";
    $data = json_decode($response->getContent(), true);
    echo "Response Data:\n";
    print_r($data);
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
