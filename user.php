<!DOCTYPE html>
<?php
  require __DIR__ . '/vendor/autoload.php';
  require_once __DIR__ . '/prepare_db.php';
  require_once __DIR__ . '/points_calc.php';
?>
<html lang="pl-PL">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
  <body>
    <?php require_once __DIR__ . '/navbar.php'; ?>
    <h1>
      <?php
        $user_id = $_GET['id'];
        $user_points = $db->users->select()->one()->by('id', $user_id)->run()->points;
        echo "#".$db->users->count()->where('points >= :points', [':points' => $user_points])->run()." ";
        echo $db->users->select()->one()->by('id', $user_id)->run()->name;
        echo " <small>(";
        echo $user_points;
        echo ")</small>";
      ?>
    </h1>
    <div class="table-responsive">
      <table class="table table-hover">
        <thead>
          <tr>
              <th>Data</th>
              <th>Oponent</th>
              <th>Przed</th>
              <th>Wynik</th>
              <th>Po</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $matches = $db->matches
              ->select()
              ->where('loser_id = :loser_id or winner_id = :winner_id', [':loser_id' => $user_id, ':winner_id' => $user_id])
              ->orderBy('date ASC')
              ->run();
            foreach($matches as $match){
              $loser = $db->users->select()->one()->by('id', $match->winner_id)->run();
              $winner = $db->users->select()->one()->by('id', $match->loser_id)->run();
              $is_winner = $match->winner_id==$user_id;
              echo "<tr data-match-id=".$match->id.">";
              echo "<td>".$match->date->format('Y-m-d')."</td>";
              echo '<td onclick=location.href="user.php?id='.($is_winner ? $winner->id : $loser->id).'";>'.($is_winner ? $winner->name : $loser->name)."</td>";
              echo "<td>".($is_winner ? $match->winner_points : $match->loser_points)."</td>";
              echo "<td class=".($is_winner?"text-success":"text-danger").">".($is_winner ? "+". ((string)matchResult($match->winner_points,$match->loser_points)->winner-$match->winner_points) : matchResult($match->winner_points,$match->loser_points)->loser-$match->loser_points)."</td>";
              echo "<td>".($is_winner ? matchResult($match->winner_points,$match->loser_points)->winner : matchResult($match->winner_points,$match->loser_points)->loser)."</td>";
              echo "</tr>";
            }
          ?>
        </tbody>
      </table>
    </div>
    <?php require_once __DIR__ . '/load_js.php'; ?>
  </body>
</html>
