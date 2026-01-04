<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ProjekAkhir;
use App\Models\Mahasiswa;
$nims = ['71240099','71220099','220026045'];
foreach ($nims as $nim) {
    $projek = ProjekAkhir::where('nim', $nim)->latest()->first();
    if (! $projek) {
        echo "No projek for nim $nim\n";
        continue;
    }
    $mahasiswa = Mahasiswa::where('nim', $nim)->first();
    echo "nim=$nim, projek_id={$projek->id_proyek_akhir}, mahasiswa_user_id=".($mahasiswa?->user_id ?? 'NULL').", pembimbing=".($mahasiswa?->dosen_pembimbing_id ?? 'NULL')."\n";
}
