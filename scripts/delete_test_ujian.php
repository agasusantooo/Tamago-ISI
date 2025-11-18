<?php
// scripts/delete_test_ujian.php
// Usage: php scripts/delete_test_ujian.php [nim|user_id]
$arg = $argv[1] ?? null;
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\ProjekAkhir;
use App\Models\UjianTA;

try {
    if (!$arg) {
        echo "Provide user id or nim as argument\n";
        exit(1);
    }

    // try as user id
    $user = is_numeric($arg) ? User::find((int)$arg) : null;
    $nim = null;
    if ($user && $user->mahasiswa) {
        $nim = $user->mahasiswa->nim;
    } elseif (!is_numeric($arg)) {
        $nim = $arg;
    }

    if (!$nim) {
        echo "Could not determine nim for argument: $arg\n";
        exit(1);
    }

    $projek = ProjekAkhir::where('nim', $nim)->get();
    foreach ($projek as $p) {
        echo "Deleting UjianTA for projek id={$p->id_proyek_akhir}\n";
        UjianTA::where('id_proyek_akhir', $p->id_proyek_akhir)->delete();
        echo "Deleting ProjekAkhir id={$p->id_proyek_akhir}\n";
        $p->delete();
    }

    echo "Done. Removed projek & ujian entries for nim=$nim\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
