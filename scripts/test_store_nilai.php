<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;

$uid = $argv[1] ?? 5; // user id to test
$ujianId = $argv[2] ?? 3; // ujian id to grade
$nilai = $argv[3] ?? 87;

Auth::loginUsingId($uid);
$controller = new DashboardController(new \App\Service\ProgressService());

// Build request
$request = \Illuminate\Http\Request::create('/', 'POST', ['ujian_id' => $ujianId, 'nilai' => $nilai]);
try {
    $response = $controller->storeNilaiUjian($request);
    if ($response instanceof \Illuminate\Http\JsonResponse) {
        echo $response->getContent()."\n";
    } else {
        echo "Unexpected response type: ".get_class($response)."\n";
    }
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
