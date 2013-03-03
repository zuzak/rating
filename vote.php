<?php
    $constant = 0;
    if ((isset($_POST["winner"]) && isset($_POST["loser"])) == false){
        header("HTTP/1.1 400 Bad Request");
        die("Error 01: Incorrect or insufficient parameters.");
    }
    $win["name"] = $_POST["winner"];
    $loss["name"] = $_POST["loser"];

    $counts = json_decode(file_get_contents("counts.json"));
    $counts->$win["name"] = ($counts->$win["name"] + 1);
    $counts->$loss["name"] = ($counts->$loss["name"] + 1);
    file_put_contents("counts.json",json_encode($counts));
    $scores = json_decode(file_get_contents("scores.json"));

    $win["old"] = $scores->$win["name"];

    $loss["old"] = $scores->$loss["name"];

    $win["new"]  = $win["old"] + 1; 
    $loss["new"] = $loss["old"] - 1;

    $win["diff"] = $win["new"] - $win["old"];
    $loss["diff"] = $loss["new"] - $loss["old"];

    $scores->$win["name"] = $win["new"];
    $scores->$loss["name"] = $loss["new"];
    
    file_put_contents("scores.json",json_encode($scores));

    $scores = (array)$scores;
    
    $return = (object) array('next' => array(array_rand($scores), array_rand($scores)), 'accepted' => array('last' => $_POST["winner"], 'loser' => $_POST['loser']));
    echo json_encode($return);
?>
