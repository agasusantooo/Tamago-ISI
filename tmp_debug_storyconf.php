<?php
// temporary debug script — prints summary of mahasiswa and approved proposals
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Mahasiswa;
use App\Models\Proposal;
use Illuminate\Support\Facades\DB;

echo "== Mahasiswa sample (first 20) ==\n";
$mahasiswas = Mahasiswa::select('nim','nama','user_id')->limit(20)->get();
foreach ($mahasiswas as $m) {
    echo "nim={$m->nim} | nama={$m->nama} | user_id=" . ($m->user_id ?? 'NULL') . "\n";
}

echo "\n== Approved proposals (first 30) ==\n";
$proposals = Proposal::where('status','disetujui')->orderBy('created_at','desc')->limit(30)->get();
foreach ($proposals as $p) {
    echo "id={$p->id} | nim={$p->mahasiswa_nim} | judul=" . substr($p->judul,0,60) . " | created_at={$p->created_at}\n";
}

echo "\n== Proposals with mahasiswa_nim not matching any mahasiswa row (first 30) ==\n";
$allNims = Proposal::select('mahasiswa_nim')->distinct()->pluck('mahasiswa_nim')->toArray();
foreach ($allNims as $nim) {
    $exists = Mahasiswa::where('nim',$nim)->exists();
    if (!$exists) {
        echo "nim={$nim} (no mahasiswa row)\n";
    }
}

echo "\nDone.\n";
