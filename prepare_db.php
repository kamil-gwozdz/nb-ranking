<?php

  $db_server = "mysql:host=HOST;dbname=DBNAME";
  $db_user = "USER";
  $db_password = "PASSWORD";
  $admin_passwords = array ('admin' => 'admin');

  use SimpleCrud\SimpleCrud;

  date_default_timezone_set('Europe/Warsaw');

  $pdo = new PDO($db_server, $db_user, $db_password);
  $pdo -> query('SET NAMES utf8');
  $pdo -> query('SET CHARACTER_SET utf8_unicode_ci');

  $db = new SimpleCrud($pdo);
?>
