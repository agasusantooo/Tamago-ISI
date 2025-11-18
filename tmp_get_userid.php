<?php
if ($argc < 2) {
    echo "Usage: php tmp_get_userid.php you@your.email\n";
    exit(1);
}

$email = $argv[1];

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('email', $email)->first();
if (! $user) {
    echo "NOT FOUND\n";
    exit(0);
}
echo $user->id . PHP_EOL;
