  <?php
    $db_host = "localhost";
    $db_username = "root";
    $db_password = "herogoder";
    $db_name = "memory";

    $db_link = @new mysqli($db_host,$db_username,$db_password,$db_name);

    if($db_link->connect_error!=""){
      echo "資料庫連結失敗";
    }else{
      $db_link->query("SET NAMES　utf8");
    }
   ?>
