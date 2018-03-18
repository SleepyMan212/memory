<?php
  $db_host = "localhost";
  $db_username = "root";
  $db_password = "herogoder";
  $db_name = "memory";

  // $db_link = @new mysqli($db_host,$db_username,$db_password,$db_name);
  try {
    $db_link = new PDO("mysql:host=$db_host;dbname=$db_name;",$db_username,$db_password);
    $db_link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db_link->query('SET NAMES "utf8"');
  } catch (PDOException $e) {
    print "Error".$e->getMessage();
  }
  ?>
