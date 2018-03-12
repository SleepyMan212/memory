<?php
  header('Content-Type: application/json; charset=UTF-8');
  require_once 'connectMysql.php';
  if(!isset($_GET['finish'])||empty($_GET['finish'])||!isset($_GET['num'])||empty($_GET['num'])) return;

  $update_data = "UPDATE task SET finish = '".$_GET['finish']."' WHERE num='".$_GET['num']."'" ;

  $is_sucess=$db_link->query($update_data);
  if($is_sucess){
    $query_next_data = "SELECT * FROM `task` WHERE finish=0 AND num>{$_GET['num']} LIMIT 1";
    $next_data_record=$db_link->query($query_next_data);
    $next_data = $next_data_record->fetch_assoc();
    if($next_data->num_rows){
      echo json_encode(array('msg'=>'任務已完成','txt'=>$next_data['txt'],'num'=>$next_data['num'],
                          'date'=>substr($next_data['created'],0,10),'importance'=>$next_data['importance']));
    }else{
        echo json_encode(array('msg'=>'任務已完成','nodata'=>'1'));
    }
  }else
  echo json_encode(array('msg'=>'發生錯誤'));

 ?>
