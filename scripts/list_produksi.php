<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Produksi;

$rows = Produksi::orderBy('mahasiswa_id')->get(['id','mahasiswa_id','proposal_id','status_pra_produksi','status_produksi','file_produksi']);
if ($rows->isEmpty()) {
    echo "No produksi rows\n";
    exit;
}
foreach ($rows as $r) {
    echo "ID: $r->id | mahasiswa_id: $r->mahasiswa_id | proposal_id: $r->proposal_id | pra: $r->status_pra_produksi | produksi: $r->status_produksi | file_produksi: " . ($r->file_produksi ?? 'NULL') . "\n";
}
