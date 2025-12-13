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

echo "Test dengan Mahasiswa ID: " . $mahasiswa->id . " (NIM: " . $mahasiswa->nim . ")\n\n";

// Create proper route request through Laravel's router
$request = Request::create(
    '/dospem/produksi/mahasiswa/' . $mahasiswa->id . '/data',
    'GET',
    [],
    [],
    [],
    ['HTTP_ACCEPT' => 'application/json']
);

// Call controller method with just the route parameter (no extra $request param!)
$controller = new MahasiswaProduksiController();

try {
    $response = $controller->getMahasiswaProduksiData($mahasiswa->id);
    
    echo "Response Status Code: " . $response->status() . "\n";
    $data = json_decode($response->getContent(), true);
    echo "Response Data:\n";
    print_r($data);
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
