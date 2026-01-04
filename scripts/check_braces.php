<?php
$file = 'app/Http/Controllers/Mahasiswa/UjianTAController.php';
$lines = file($file);
$open = 0;
foreach ($lines as $i => $line) {
    $open += substr_count($line, '{');
    $open -= substr_count($line, '}');
    if ($open < 0) {
        echo "Negative braces at line " . ($i+1) . "\n";
        break;
    }
    if (($i+1) % 10 == 0) {
        echo "Line " . ($i+1) . " cumulative braces: " . $open . "\n";
    }
}
echo "Final cumulative braces: $open\n";
