<?php
$content = file_get_contents('resources/views/dospem/detail-mahasiswa.blade.php');

// Count opening and closing div tags
$lines = explode("\n", $content);

$divCount = 0;
$inMonitoring = false;
foreach ($lines as $num => $line) {
    $lineNum = $num + 1;
    
    if (strpos($line, 'id="monitoring-tab"') !== false) {
        echo "Line $lineNum: OPEN monitoring-tab\n";
        $inMonitoring = true;
        $divCount = 1;
    }
    
    if ($inMonitoring) {
        // Count div opens
        preg_match_all('/<div/', $line, $matches);
        $divCount += count($matches[0]);
        
        // Count div closes
        preg_match_all('/<\/div>/', $line, $matches);
        $divCount -= count($matches[0]);
        
        if ($divCount == 0) {
            echo "Line $lineNum: CLOSE monitoring-tab\n";
            $inMonitoring = false;
        }
        
        // Show structure around monitoring
        if ($inMonitoring) {
            echo "Line $lineNum (div balance: $divCount): " . trim($line) . "\n";
        }
    }
}
