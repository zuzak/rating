<pre><?php
    function elo($playerRank, $opponentRank, $result) {
        $k = 20;
        $winProbability = 1/(10^(($opponentRank-$playerRank)/400)+1);
        $rankChange = $k*($result-$winProbability);
        $newRank = $rankChange + $playerRank;
        return intval($newRank);
    }
    
    if ((isset($_POST["winner"]) && isset($_POST["loser"])) ==false) {
        header("HTTP/1.1 400 Bad Request"); // lol fuck you go away
        die();
    }

    $win  = $_POST["winner"]; 
    $loss = $_POST["loser"]; 
    $scores = json_decode(file_get_contents("scores.json"));
    $winner = $scores->$win;
    $loser = $scores->$loss;
    $winner->count++;   
    $loser->count++;
    $winscore = $winner->score;
    $winner->score = elo($winscore, $loser->score, 1);
    $loser->score = elo($loser->score, $winscore, 0);

    $scores->$win=$winner;
    $scores->$loss=$loser;

    echo json_encode($scores);  
    file_put_contents("scores.json",json_encode($scores));
?>
