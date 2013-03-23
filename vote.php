<?php
    function elo($playerRank, $opponentRank, $result) {
        $k = 20;
        $winProbability = 1/(10^(($opponentRank-$playerRank)/400)+1);
        $rankChange = $k*($result-$winProbability);
        $newRank = $rankChange + $playerRank;
        return intval($newRank);
    }

    $scores = json_decode(file_get_contents("scores.json"));
    if ((isset($_POST["winner"]) && isset($_POST["loser"])) == true) {
        // this is a match submission, so record the data

        $win  = $_POST["winner"];  // grab data from submission
        $loss = $_POST["loser"]; 

        $winner = $scores->$win; // pull out current scores
        $loser = $scores->$loss;

        $winner->count++; // increment match count
        $loser->count++;

        // compute new scores
        $winscore = $winner->score;
        $winner->score = elo($winscore, $loser->score, 1);
        $loser->score = elo($loser->score, $winscore, 0);

        $scores->$win=$winner; // push new scores to json
        $scores->$loss=$loser;
        file_put_contents("scores.json",json_encode($scores));
    }

    // display new pair to match

    $last["c"] = INF; 
    foreach($scores as $name => $data) {
        if ($data->count <= $last["c"]) {
            $penu = $last;
            $last["c"] = $data->count;
            $last["n"] = $name;
        }
    }
    $score = array($last["n"], $penu["n"]);
    echo json_encode($score);
   
?>
