<?php
  function getExpected($a, $b){
    return 1/(1+pow(10,(($b-$a)/400)));
  }
  function updateRating($expected, $actual, $current){
    $k = 32;
    return round($current+ $k*($actual-$expected));
  }
  function matchResult($winner, $loser){
    $expectedScoreWinner = getExpected($winner, $loser);
    $expectedScoreLoser = getExpected($loser, $winner);
    $newWinnerScore = updateRating($expectedScoreWinner, 1, $winner);
    $newLoserScore = updateRating($expectedScoreLoser, 0, $loser);

    $obj = (object) ['winner' => $newWinnerScore, 'loser'=>$newLoserScore];
    return  $obj;
  }
?>
