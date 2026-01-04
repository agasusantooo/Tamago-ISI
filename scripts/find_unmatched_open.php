<?php
$lines = file('app/Http/Controllers/Mahasiswa/UjianTAController.php');
$stack = [];
foreach ($lines as $i=>$line) {
    for ($j=0;$j<strlen($line);$j++) {
        $c = $line[$j];
        if ($c === '{') $stack[] = [$i+1, $j+1, $line];
        if ($c === '}') array_pop($stack);
    }
}
if (count($stack) === 0) {
    echo "All braces matched\n";
} else {
    echo "Unmatched opens: " . count($stack) . "\n";
    foreach ($stack as $s) {
        echo "Line {$s[0]} Col {$s[1]}: " . trim($s[2]) . "\n";
    }
}
