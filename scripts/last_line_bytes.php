<?php
$lines = file('app/Http/Controllers/Mahasiswa/UjianTAController.php');
$l = end($lines);
printf('Last line bytes:');
for ($i=0;$i<strlen($l);$i++) printf(' %02X', ord($l[$i]));
printf("\n");
