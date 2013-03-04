<?php
    function elo($playerRank, $opponentRank, $result) {
        $playerRank = $playerRank;
        $opponentRank = $opponentRank;
        $k = 20;
        $winProbability = 1/(10^(($opponentRank-$playerRank)/400)+1);
        $rankChange = $k*($result-$winProbability);
        $newRank = $rankChange + $playerRank;
        return intval($newRank);
    }

    // filter out bad attempts
    if ((isset($_POST["winner"]) && isset($_POST["loser"])) == false){
        header("HTTP/1.1 400 Bad Request");
        die("Error 01: Incorrect or insufficient parameters.");
    }

    $win["name"] = $_POST["winner"];  // locate names
    $loss["name"] = $_POST["loser"]; //  of players

    $scores = json_decode(file_get_contents("scores.json")); // pull json

    $win["old"] = $scores->$win["name"];    // extract current
    $loss["old"] = $scores->$loss["name"]; //  scores from json

    $win["new"]  = elo($win["old"],$loss["old"],1);  // compute new
    $loss["new"] = elo($loss["old"],$win["old"],0); // scores magically

    $scores->$win["name"] = $win["new"];    // store new score
    $scores->$loss["name"] = $loss["new"]; // in the json
    
    file_put_contents("scores.json",json_encode($scores)); // save json

    // create json to parse back to end user
    $scores = (array)$scores;
    $return = (object) array('next' => array(array_rand($scores), array_rand($scores)), 'accepted' => array('last' => $_POST["winner"], 'loser' => $_POST['loser']));
    echo json_encode($return);
?>
