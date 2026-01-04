<?php
$s = file_get_contents('app/Http/Controllers/Mahasiswa/UjianTAController.php');
echo "single: ".substr_count($s, "'")."\n";
echo "double: ".substr_count($s, '"')."\n";
