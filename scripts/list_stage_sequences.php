<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TAProgressStage;

foreach (TAProgressStage::orderBy('sequence')->get() as $s) {
    echo $s->sequence . ' - ' . $s->stage_code . ' - ' . $s->stage_name . "\n";
}
