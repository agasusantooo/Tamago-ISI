<?php
$lines = file('app/Http/Controllers/Mahasiswa/UjianTAController.php');
for ($i = 279; $i < 324; $i++) {
    echo str_pad($i+1, 4, ' ', STR_PAD_LEFT) . ': ' . ($lines[$i] ?? '') ;
}
