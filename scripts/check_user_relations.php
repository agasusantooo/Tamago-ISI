<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$email = $argv[1] ?? 'suho@student.isi.ac.id';
$user = DB::table('users')->where('email', $email)->first();
if ($user) {
    echo "FOUND: user id={$user->id} name={$user->name} email={$user->email}\n";
    $m = DB::table('mahasiswa')->where('user_id', $user->id)->orWhere('nim', $user->name)->first();
    if ($m) {
        echo "Mahasiswa record found: ";
        print_r($m);
    } else {
        echo "No mahasiswa record linked to this user.\n";
    }
} else {
    echo "NOT FOUND\n";
}
