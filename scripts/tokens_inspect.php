<?php
$code = file_get_contents('app/Http/Controllers/Mahasiswa/UjianTAController.php');
$tokens = token_get_all($code);
foreach ($tokens as $idx => $tok) {
    if (is_array($tok) && $tok[0] === T_PUBLIC) {
        echo "Found T_PUBLIC at token index $idx (line {$tok[2]})\n";
        $start = max(0, $idx-12);
        for ($i=$start; $i < $idx+3 && $i < count($tokens); $i++) {
            $t = $tokens[$i];
            if (is_array($t)) {
                printf("%4d: %s (line %d)\n", $i, token_name($t[0]), $t[2]);
            } else {
                printf("%4d: %s\n", $i, $t);
            }
        }
        break;
    }
}
