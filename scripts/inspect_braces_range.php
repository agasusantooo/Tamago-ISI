<?php
$lines = file('app/Http/Controllers/Mahasiswa/UjianTAController.php');
$open = 0;
for ($i = 259; $i < 320; $i++) {
    $line = isset($lines[$i]) ? rtrim($lines[$i]) : '';
    $open += substr_count($line, '{');
    $open -= substr_count($line, '}');
    printf("%3d: %3d %s\n", $i + 1, $open, $line);
}
