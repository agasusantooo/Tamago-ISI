<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TefaFair;
use App\Models\StoryConference;
use App\Models\Mahasiswa;

// Print a few TEFA records
$tefa = TefaFair::orderBy('id_tefa','desc')->take(5)->get();
if ($tefa->isEmpty()) {
    echo "No TefaFair records found\n";
} else {
    echo "TefaFair sample records:\n";
    foreach ($tefa as $t) {
        $m = Mahasiswa::where('nim', $t->mahasiswa_nim)->first();
        $userId = $m ? $m->user_id : 'unknown';
        echo "id_tefa={$t->id_tefa} nim={$t->mahasiswa_nim} status={$t->status} user_id={$userId}\n";
    }
}

// Print a few StoryConference records
$sc = StoryConference::orderBy('id_conference','desc')->take(5)->get();
if ($sc->isEmpty()) {
    echo "No StoryConference records found\n";
} else {
    echo "StoryConference sample records:\n";
    foreach ($sc as $s) {
        $m = $s->mahasiswa();
        $nim = $s->mahasiswa_nim ?? ($s->mahasiswa?->nim ?? 'unknown');
        echo "id_conf={$s->id_conference} nim={$nim} status={$s->status}\n";
    }
}
