<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Produksi;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Simulate authentication - let's find a dosen and mahasiswa
$dosen = Dosen::first();
$mahasiswa = Mahasiswa::first();

if (!$dosen || !$mahasiswa) {
    echo "No dosen or mahasiswa found in database\n";
    exit(1);
}

echo "Testing with:\n";
echo "- Dosen: {$dosen->nama} (NIDN: {$dosen->nidn})\n";
echo "- Mahasiswa: {$mahasiswa->nama} (NIM: {$mahasiswa->nim})\n";
echo "- Mahasiswa dosen_pembimbing_id: {$mahasiswa->dosen_pembimbing_id}\n\n";

// Find produksi for this mahasiswa
$produksi = Produksi::where('mahasiswa_id', $mahasiswa->user_id)->first();

if (!$produksi) {
    echo "No produksi found for this mahasiswa\n";
    exit(1);
}

echo "Found produksi ID: {$produksi->id}\n";
echo "Current status_pra_produksi: {$produksi->status_pra_produksi}\n";
echo "Produksi dosen_id: {$produksi->dosen_id}\n\n";

// Simulate authentication
Auth::loginUsingId($dosen->user_id);
echo "Simulated login as dosen user_id: {$dosen->user_id}\n\n";

// Create a mock request
$request = new Request();
$request->merge([
    'produksi_status' => 'disetujui',
    'produksi_feedback' => 'Approved via test script'
]);

// Create controller instance
$controller = new \App\Http\Controllers\Dospem\MahasiswaProduksiController();

// Call the approval method
echo "Calling approvePraProduksi...\n";
try {
    $response = $controller->approvePraProduksi($request, $produksi->id);

    echo "Response status: " . $response->getStatusCode() . "\n";

    if ($response->getStatusCode() == 200) {
        $data = json_decode($response->getContent(), true);
        echo "Response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n";
    } else {
        echo "Response content: " . $response->getContent() . "\n";
    }

    // Check if the update actually happened
    $produksi->refresh();
    echo "\nAfter approval:\n";
    echo "status_pra_produksi: {$produksi->status_pra_produksi}\n";
    echo "feedback_pra_produksi: {$produksi->feedback_pra_produksi}\n";
    echo "tanggal_review_pra: {$produksi->tanggal_review_pra}\n";

} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
