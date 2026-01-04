<?php
$lines = file('app/Http/Controllers/Mahasiswa/UjianTAController.php');
$open = 0; $max = 0; $maxLine = 0;
foreach ($lines as $i => $l) {
    $open += substr_count($l, '{');
    $open -= substr_count($l, '}');
    if ($open > $max) { $max = $open; $maxLine = $i+1; }
}
echo "max open $max at line $maxLine\n";
$open = 0;
foreach ($lines as $i => $l) {
    $open += substr_count($l, '{');
    $open -= substr_count($l, '}');
    if ($open != 0) echo ($i+1) . ": $open\n";
}
