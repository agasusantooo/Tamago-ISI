<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Service\ProgressService;
use App\Models\User;

$user = User::find(118); // use a sample mahasiswa user who has TEFA + Story pending
$svc = new ProgressService;
$data = $svc->getDashboardData($user->id);
print_r($data);

// Also show the stages and names clearly
foreach ($data['details'] as $d) {
    echo "{$d['code']} - {$d['name']} : fraction={$d['fraction']} contribution={$d['contribution']}\n";
}
