<?php
$lines = file('app/Http/Controllers/Mahasiswa/UjianTAController.php');
$open=0;
foreach ($lines as $i=>$line) {
    $open += substr_count($line, '{');
    $open -= substr_count($line, '}');
    printf('%4d: %3d %s', $i+1, $open, rtrim($line));
    echo PHP_EOL;
}
