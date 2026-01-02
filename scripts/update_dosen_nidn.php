<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Dosen;

$targetNidn = '0012345672';

// Find a Dosen record that is attached to a user (likely the logged dospem used in debug)
$dosen = Dosen::whereNotNull('user_id')->first();
if (! $dosen) {
    echo "No Dosen record found with user_id. Aborting.\n";
    exit(1);
}

$old = $dosen->nidn;
$dosen->nidn = $targetNidn;
$dosen->save();

echo "✅ Updated Dosen (id={$dosen->id}, user_id={$dosen->user_id}) nidn: {$old} -> {$dosen->nidn}\n";
exit(0);
