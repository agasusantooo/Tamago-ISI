<?php
$lines = file('app/Http/Controllers/Mahasiswa/UjianTAController.php');
$first = array_slice($lines,0,298);
file_put_contents('tmp_part1b.php', implode('', $first));
echo "wrote tmp_part1b.php\n";