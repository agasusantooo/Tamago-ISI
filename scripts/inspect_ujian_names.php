<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\UjianTA;
use App\Models\ProjekAkhir;
use App\Models\Mahasiswa;
use App\Models\User;

$ujians = UjianTA::whereIn('id_ujian', [1,2,3])->get();
foreach ($ujians as $u) {
    echo "Ujian id={$u->id_ujian}\n";
    echo " - ujian.judul_ta: ".($u->judul_ta ?? 'NULL')."\n";
    $projek = $u->projekAkhir;
    echo " - id_proyek_akhir: ".($u->id_proyek_akhir ?? 'NULL')."\n";
    if ($projek) {
        echo "   projek.id_proyek_akhir={$projek->id_proyek_akhir}, nim={$projek->nim}, judul={$projek->judul}\n";
        $m = \App\Models\Mahasiswa::where('nim', $projek->nim)->first();
        if ($m) {
            echo "   mahasiswa user_id={$m->user_id}, name=".($m->user?->name ?? 'NULL').", pembimbing={$m->dosen_pembimbing_id}\n";
        } else {
            echo "   mahasiswa for nim {$projek->nim} NOT FOUND\n";
        }
    } else {
        echo "   projek not found\n";
    }

    $mahasiswa = $u->mahasiswa;
    echo " - ujian.mahasiswa relation: ".($mahasiswa ? ($mahasiswa->user_id.' / '.$mahasiswa->name) : 'NULL')."\n";

    echo "\n";
}
