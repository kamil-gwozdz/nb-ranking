<!DOCTYPE html>
<?php
  require_once __DIR__ . '/points_calc.php';
?>
<html lang="pl-PL">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
  <body>
    <?php require_once __DIR__ . '/navbar.php'; ?>
    <div class="table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th></th>
            <th>Zwycięzca</th>
            <th>Przegrany</th>
        </tr>
        </thead>
        <tbody>
          <tr>
            <td>Przed: </td>
            <td><input type="number" class="form-control" id="winner-before" value="1200" onchange="updateResult()" pattern="\d*"/></td>
            <td><input type="number" class="form-control" id="loser-before" value="1200" onchange="updateResult()" pattern="\d*"/></td>
          </tr>
          <tr>
            <td>Po: </td>
            <td class="text-bold" id="winner-after"></td>
            <td class="text-bold" id="loser-after"></td>
          </tr>
        </tbody>
      </table>
    </div>
  <?php require_once __DIR__ . '/load_js.php'; ?>
  <script>
    function updateResult(){
      $result = $.getJSON("./points_api.php", {winner: $('#winner-before').val(), loser: $('#loser-before').val()},function(json){
        $('#winner-after').html(json.winner);
        $('#loser-after').html(json.loser);
      });
    }
    $(document).ready(function(){
      updateResult();
    });
  </script>
  </body>
</html>
