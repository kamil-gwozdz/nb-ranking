<?php
  if($_SERVER["HTTPS"]!="on" && $_SERVER["HTTP_HOST"]!='localhost' && $_SERVER["HTTP_HOST"]!='127.0.0.1'){
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
  }
  require __DIR__ . '/vendor/autoload.php';
  require_once __DIR__ . '/prepare_db.php';
  require_once __DIR__ . '/points_calc.php';

  $admin_users = array_keys($admin_passwords);

  $user = $_SERVER['PHP_AUTH_USER'];
  $pass = $_SERVER['PHP_AUTH_PW'];

  $validated = (in_array($user, $admin_users)) && ($pass == $admin_passwords[$user]);

  if(!$validated) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    die('<iframe width="420" height="345" src="http://www.youtube.com/embed/oHg5SJYRHA0?autoplay=1" frameborder="0" allowfullscreen></iframe>');
  }

  if(isset($_POST["name"])){
    $newUser = $db->users->create(['name' => $_POST["name"], 'points' => 1200]);
    $newUser->save();

    header("Location: https://nota-bene.org.pl/ranking");
    exit();
  }
  if(isset($_POST["winner"]) && isset($_POST["loser"])){
    if($_POST["winner"]!=$_POST["loser"] && $_POST["winner"]!=0 && $_POST["loser"]!=0){
      $matchResult = matchResult($db->users[$_POST["winner"]]->points,$db->users[$_POST["loser"]]->points);

      $newMatch = $db->matches->create(['winner_id' => $_POST["winner"],
                                      'loser_id' => $_POST["loser"],
                                      'loser_points' => $db->users[$_POST["loser"]]->points,
                                      'winner_points' => $db->users[$_POST["winner"]]->points,
                                      'date' => date('Y-m-d h:i:s', time())
                                    ]);
      $newMatch->save();

      $db->users[$_POST["winner"]]= [
        'points' => $matchResult->winner
      ];
      $db->users[$_POST["loser"]]= [
        'points' => $matchResult->loser
      ];

      header("Location: https://nota-bene.org.pl/ranking");
      exit();
    }
  }
?>

<!DOCTYPE html>
<html lang="pl-PL">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Bene</title>
  </head>
  <body>
    <?php require_once __DIR__ . '/navbar.php'; ?>
    <form action="#" method="post">
      <div class="form-group">
        <label for="name">Nowy Gracz:</label>
        <input type="text" name="name" class="form-control" id="name" placeholder="imię i nazwisko">
      </div>
      <button type="submit" class="btn btn-default">Submit</button>
    </form>
    </br>
    <form action="#" method="post">
      <div class="form-group">
        <select name="winner" class="form-control">
          <option value=0 selected>Wygrany</option>
          <?php
            $users = $db->users
              ->select()
              ->all()
              ->orderBy('name ASC')
              ->run();
            foreach($users as $user){
              echo '<option value="'.$user->id.'">'.$user->name.' ('.$user->points.')</option>';
            }
          ?>
        </select>
        <select name="loser" class="form-control">
          <option value=0 selected>Przegrany</option>
          <?php
            $users = $db->users
              ->select()
              ->all()
              ->orderBy('name ASC')
              ->run();
            foreach($users as $user){
              echo '<option value="'.$user->id.'">'.$user->name.' ('.$user->points.')</option>';
            }
          ?>
          </select>
      </div>
      <button type="submit" class="btn btn-default">Submit</button>
    </form>

    <?php require_once __DIR__ . '/load_js.php'; ?>
  </body>
</html>
