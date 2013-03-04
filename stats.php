<?php
    $stats = json_decode(file_get_contents("scores.json"));
    $stats = (array)$stats;

    if (isset($_GET["raw"])){
        die(json_encode(array_values($stats)));
    }

    arsort($stats);
    echo "<ol>";
    foreach(array_keys($stats) as $stat) {
        echo "<li>$stat</li>";
    }
    echo "</ol>";
?>
