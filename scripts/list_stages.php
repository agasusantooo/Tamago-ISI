<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TAProgressStage;

$stages = TAProgressStage::orderBy('sequence')->get();
foreach ($stages as $s) {
    echo ($s->is_active ? '[active] ' : '[inactive] ') . $s->stage_code . ' - ' . $s->stage_name . ' (weight: ' . $s->weight . ")\n";
}
