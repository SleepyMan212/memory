<?php
  header('Content-Type: application/json; charset=UTF-8');
  require_once 'connectMysql2.php';
  // require_once 'connectMysql.php';
  if(!isset($_GET['finish'])||empty($_GET['finish'])||!isset($_GET['num'])||empty($_GET['num'])) return;

  // $update_data = "UPDATE task SET finish = '".$_GET['finish']."' WHERE num='".$_GET['num']."'" ;
  $update_data = "UPDATE task SET finish = ? WHERE num= ? " ;
  $data=$db_link->prepare($update_data);
  $is_sucess=$data->execute(array($_GET['finish'],$_GET['num']));
  if($is_sucess){
    $query_next_data = "SELECT * FROM `task` WHERE finish=0 AND num>{$_GET['num']} LIMIT 3,4";
    $next_data_record=$db_link->query($query_next_data);
    $next_data = $next_data_record->fetch(PDO::FETCH_ASSOC);
    if($next_data_record->rowCount()){
      echo json_encode(array('msg'=>'任務已完成','txt'=>$next_data['txt'],'num'=>$next_data['num'],
                          'date'=>substr($next_data['created'],0,10),'importance'=>$next_data['importance']));
    }else{
        echo json_encode(array('msg'=>'任務已完成','nodata'=>'1'));
    }
  }else
  echo json_encode(array('msg'=>'發生錯誤'));

 ?>
