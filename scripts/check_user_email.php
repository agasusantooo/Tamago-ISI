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
} else {
    echo "NOT FOUND\n";
}
