<?php
    function pullTwo($array){
        while($a == $b) {
            $a = array_rand($array);
            $b = array_rand($array);
        }
        return array($a,$b);
    }
    function elo($playerRank, $opponentRank, $result) {
        $k = 20;
        $winProbability = 1/(10^(($opponentRank-$playerRank)/400)+1);
        $rankChange = $k*($result-$winProbability);
        $newRank = $rankChange + $playerRank;
        return intval($newRank);
    }

    // filter out bad attempts
    if ((isset($_POST["winner"]) && isset($_POST["loser"])) == false){
        $scores = json_decode(file_get_contents("scores.json"));
        $scores = (array)$scores;
        echo json_encode(pullTwo($scores));
        die();
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
    $return = (object) array('next' => pullTwo($scores), 'accepted' => array('last' => $_POST["winner"], 'loser' => $_POST['loser']));
    echo json_encode($return);
?>
