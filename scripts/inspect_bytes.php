<?php
$lines = file('app/Http/Controllers/Mahasiswa/UjianTAController.php');
$start = 270; $end = 280;
for ($i=$start-1;$i<$end;$i++) {
    $line = $lines[$i] ?? '';
    echo ($i+1) . ": ";
    for ($j=0;$j<strlen($line);$j++) {
        $c = ord($line[$j]);
        if ($c < 32 || $c > 126) {
            printf("[%02X]", $c);
        } else {
            echo $line[$j];
        }
    }
}
