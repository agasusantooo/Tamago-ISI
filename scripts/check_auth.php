<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Produksi;
use Illuminate\Support\Facades\Auth;

$produksi = Produksi::where('status_pra_produksi', 'menunggu_review')->latest()->first();
if (!$produksi) {
    echo "No produksi found in menunggu_review\n";
    exit(0);
}

echo "Produksi ID: " . $produksi->id . "\n";
echo "Produksi dosen_id (DB value): " . var_export($produksi->dosen_id, true) . "\n";

// Try logging in user id 1
Auth::loginUsingId(1);
$user = Auth::user();
if ($user) {
    echo "Auth user id: " . $user->id . "\n";
    echo "Auth user nidn: " . var_export($user->nidn ?? null, true) . "\n";
} else {
    echo "No auth user after loginUsingId(1)\n";
}

// also print a list of users who have a nidn matching produksi->dosen_id
use App\Models\User;
$match = User::where('nidn', $produksi->dosen_id)->first();
echo "User with nidn == produksi->dosen_id: " . ($match ? "found (id={" . $match->id . "})" : "not found") . "\n";

// show any user whose id equals produksi->dosen_id (in case dosen_id stores user id)
$match2 = User::where('id', $produksi->dosen_id)->first();
echo "User with id == produksi->dosen_id: " . ($match2 ? "found (nidn={" . ($match2->nidn ?? 'NULL') . "})" : "not found") . "\n";

