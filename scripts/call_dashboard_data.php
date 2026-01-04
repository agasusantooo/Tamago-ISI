<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;

$uid = $argv[1] ?? 5;
Auth::loginUsingId($uid);
$controller = new DashboardController(new \App\Service\ProgressService());
$response = $controller->dosenPengujiDashboardData();
// Response is a JsonResponse; get content
echo $response->getContent()."\n";