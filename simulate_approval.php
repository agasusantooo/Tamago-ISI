<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Produksi;
use App\Http\Controllers\Dospem\MahasiswaProduksiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

echo "=== Simulating Approval Request ===\n\n";

// Get test produksi
$produksi = Produksi::where('status_pra_produksi', 'menunggu_review')->latest()->first();

if (!$produksi) {
    echo "❌ Tidak ada produksi menunggu review\n";
    exit;
}

echo "Before Approval:\n";
echo "- Status: " . $produksi->status_pra_produksi . "\n";
echo "- Feedback: " . ($produksi->feedback_pra_produksi ?? 'NULL') . "\n\n";

// Create mock request like from browser
$requestData = [
    'produksi_status' => 'disetujui',
    'produksi_feedback' => 'Sangat bagus! Semua file lengkap dan sesuai standar.'
];

$request = Request::create(
    route('dospem.produksi.pra-produksi', $produksi->id),
    'POST',
    $requestData,
    [],
    [],
    ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json'],
    json_encode($requestData)
);

// Fake auth
Auth::loginUsingId(1); // Assuming dosen ID 1 exists

// Call controller method
$controller = new MahasiswaProduksiController();

try {
    $response = $controller->approvePraProduksi($request, $produksi->id);
    
    echo "Response Status Code: " . $response->status() . "\n";
    $data = json_decode($response->getContent(), true);
    echo "Response Data:\n";
    print_r($data);
    
    // Check database after
    $produksi->refresh();
    echo "\n\nAfter Approval:\n";
    echo "- Status: " . $produksi->status_pra_produksi . "\n";
    echo "- Feedback: " . ($produksi->feedback_pra_produksi ?? 'NULL') . "\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
