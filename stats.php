<?php
    $stats = json_decode(file_get_contents("scores.json"));
    $stats = array_values((array)$stats);
    echo json_encode($stats);
?>
