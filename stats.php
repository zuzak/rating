<?php
    $stats = json_decode(file_get_contents("scores.json"));
    $stats = (array)$stats;

    if (isset($_GET["raw"])){
        foreach ($stats as $name => $stat) {
//            $raw[$name] = $stat -> score;
            $raw[] = $stat -> score;
        }
        die(json_encode($raw));
    }

    arsort($stats);
    echo "<ol>";
    foreach(array_keys($stats) as $stat) {
        echo "<li>$stat</li>";
    }
    echo "</ol>";
?>
