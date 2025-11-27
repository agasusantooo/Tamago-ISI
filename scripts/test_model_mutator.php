<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\UjianTA;

$ujian = UjianTA::find(3);
if (!$ujian) {
    echo "No ujian\n";
    exit;
}

// call mutator directly
use Illuminate\Support\Facades\DB;

$ujian->setStatusUjianAttribute('selesai_ujian');

echo "status_ujian property after direct mutator call: "; var_export($ujian->status_ujian); echo "\n";
echo "raw attributes after direct mutator call: "; var_export($ujian->getAttributes()); echo "\n";

// show SHOW COLUMNS result
$col = DB::select("SHOW COLUMNS FROM ujian_tugas_akhir LIKE 'status_ujian'");
var_export($col);

echo "\n";

// replicate mapping logic to see what would be chosen
$allowed = [];
if (!empty($col) && isset($col[0]->Type)) {
    preg_match_all("/'([^']+)'/", $col[0]->Type, $m);
    $allowed = $m[1] ?? [];
}

echo "Allowed values parsed:\n"; var_export($allowed); echo "\n";

$desired = 'selesai_ujian';
$desiredNorm = strtolower(preg_replace('/[^a-z0-9]/','',str_replace([' ', '_', '-'], '', $desired)));

echo "desiredNorm: $desiredNorm\n";

$normMap = [];
foreach ($allowed as $val) {
    $norm = strtolower(preg_replace('/[^a-z0-9]/','',str_replace([' ', '_', '-'], '', $val)));
    $normMap[$norm] = $val;
}

echo "normMap keys: "; var_export(array_keys($normMap)); echo "\n";

if (isset($normMap[$desiredNorm])) {
    echo "Direct match: " . $normMap[$desiredNorm] . "\n";
} else {
    $alt = str_replace(['_', '-'], ' ', $desired);
    $altNorm = strtolower(preg_replace('/[^a-z0-9]/','',str_replace([' ', '_', '-'], '', $alt)));
    if (isset($normMap[$altNorm])) {
        echo "Alt match: " . $normMap[$altNorm] . "\n";
    } else {
        $synonyms = [
            'belumujian' => 'Menunggu_hasil',
            'ujianberlangsung' => 'Berlangsung',
            'selesaiujian' => 'Selesai',
            'lulus' => 'Lulus',
            'tidaklulus' => 'Tidak Lulus',
            'menungguhasil' => 'Menunggu_hasil',
        ];
        if (isset($synonyms[$desiredNorm]) && in_array($synonyms[$desiredNorm], $allowed)) {
            echo "Synonym match: " . $synonyms[$desiredNorm] . "\n";
        } else {
            echo "No mapping found, fallback to first: " . ($allowed[0] ?? 'none') . "\n";
        }
    }
}

// test mass assign (fill)
$ujian->fill(['status_ujian' => 'selesai_ujian']);
echo "after fill status_ujian property: "; var_export($ujian->status_ujian); echo "\n";
echo "raw attributes after fill: "; var_export($ujian->getAttributes()); echo "\n";
