<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (App\Models\User::all() as $u) {
    if ($u->mahasiswa) {
        echo 'User '.$u->id." has mahasiswa nim: ".$u->mahasiswa->nim."\n";
        exit(0);
    }
}
echo "No mahasiswa user found\n";
