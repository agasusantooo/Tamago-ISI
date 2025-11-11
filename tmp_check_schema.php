<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$schema = $app->make('db')->getSchemaBuilder();

echo 'catatan_mahasiswa: ' . ($schema->hasColumn('bimbingans','catatan_mahasiswa') ? 'yes' : 'no') . PHP_EOL;
echo 'nim: ' . ($schema->hasColumn('bimbingans','nim') ? 'yes' : 'no') . PHP_EOL;
