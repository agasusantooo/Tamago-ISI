<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

$connection = DB::connection();
$config = $connection->getConfig();
$dbName = $connection->getDatabaseName();

echo "DB connection info:\n";
echo "driver: " . ($config['driver'] ?? 'n/a') . "\n";
echo "host: " . ($config['host'] ?? 'n/a') . "\n";
echo "database: " . ($dbName ?? 'n/a') . "\n";
echo "username: " . ($config['username'] ?? 'n/a') . "\n";
echo "\nRecent users (last 10):\n";
$users = DB::table('users')->orderByDesc('id')->limit(10)->get();
foreach ($users as $u) {
    echo "id={$u->id} email={$u->email} name={$u->name} created_at={$u->created_at}\n";
}

echo "\nRecent mahasiswa (last 10):\n";
if (Schema::hasTable('mahasiswa')) {
    $mahas = DB::table('mahasiswa')->orderByDesc('created_at')->limit(10)->get();
    foreach ($mahas as $m) {
        // print as json-ish
        echo json_encode((array)$m) . "\n";
    }
} else {
    echo "mahasiswa table does not exist\n";
}
