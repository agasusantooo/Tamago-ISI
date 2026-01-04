<?php
$c = file_get_contents('app/Http/Controllers/Mahasiswa/UjianTAController.php');
echo 'open:'.substr_count($c,'{')." close:".substr_count($c,'}')."\n";
