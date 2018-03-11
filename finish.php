<?php
  header('Content-Type: application/json; charset=UTF-8');
  require_once 'connectMysql.php';
  if(!isset($_GET['finish'])||empty($_GET['finish'])||!isset($_GET['num'])||empty($_GET['num'])) return;

  $update_data = "UPDATE task SET finish = '".$_GET['finish']."' WHERE num='".$_GET['num']."'" ;
  $is_sucess=$db_link->query($update_data);
  if($is_sucess){
    echo json_encode(array('msg'=>'任務已完成'));
  }else
  echo json_encode(array('msg'=>'發生錯誤'));

 ?>
