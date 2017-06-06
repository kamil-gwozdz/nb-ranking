<!DOCTYPE html>
<?php
  require __DIR__ . '/vendor/autoload.php';
  require_once __DIR__ . '/prepare_db.php';
?>
<html lang="pl-PL">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
  <body>
    <?php require_once __DIR__ . '/navbar.php'; ?>
    <div class="table-responsive">
      <table class="table table-hover">
        <thead>
          <tr>
              <th>#</th>
              <th>Imię i nazwisko</th>
              <th>Punkty</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $users = $db->users
            ->select()
            ->all()
            ->orderBy('points DESC')
            ->run();
            $i=0;
            foreach($users as $user){
              echo '<tr onclick=location.href="user.php?id='.$user->id.'";>';
              echo "<td>".++$i."</td>";
              echo "<td>".$user->name."</td>";
              echo "<td>".$user->points."</td>";
              echo "</tr>";
            }
          ?>
        </tbody>
      </table>
    </div>
    <?php require_once __DIR__ . '/load_js.php'; ?>
  </body>
</html>
