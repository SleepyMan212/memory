<?php
  header('Content-Type: application/json; charset=UTF-8');
  require_once 'connectMysql.php';
  // echo js;
  if(!isset($_GET['task'])||empty($_GET['task'])||!isset($_GET['importance'])||empty($_GET['importance'])){
    echo json_encode(array('msg'=>' 未填寫事項'));
    return;
  }
  $add_task = "INSERT INTO `task`(txt,importance) VALUES ('".$_GET['task']."','".$_GET['importance']."')";
  $is_sucess=$db_link->query($add_task);
  if($is_sucess){
    $query_last_task ="SELECT created,num FROM task order by created desc limit 1";
    $last_task_record = $db_link->query($query_last_task);
    $last_task = $last_task_record->fetch_assoc();
    // $num = $last_task['num'];
    echo json_encode(array('msg'=>'新增成功','txt'=>$_GET['task'],'importance'=>$_GET['importance'],'num' => $last_task['num'],'time'=>substr($data['created'],0,10)));

    // echo json_encode(array('num' => $num));

  }else{
    echo json_encode(array('msg'=>'新增失敗'));
  }
  // $db_link->close();
 ?>
