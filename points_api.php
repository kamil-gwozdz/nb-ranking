<?php
  require_once __DIR__ . '/points_calc.php';

  header('Content-Type: application/json');

  $winner = $_GET['winner'];
  $loser = $_GET['loser'];

  echo json_encode(matchResult($winner, $loser));
?>
