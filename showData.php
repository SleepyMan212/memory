<?php
  header('Content-Type: application/json; charset=UTF-8');
  require_once 'connectMysql2.php';
  // echo js;
  if(!isset($_GET['task'])||empty($_GET['task'])||!isset($_GET['importance'])||empty($_GET['importance'])){
    echo json_encode(array('msg'=>' 未填寫事項'));
    return;
  }
  // $add_task = "INSERT INTO `task`(txt,importance) VALUES ('".$_GET['task']."','".$_GET['importance']."')";
  $add_task = "INSERT INTO `task`(txt,importance) VALUES (?,?)";
  // $data = $db_link->prepare($add_task);
  // $is_sucess=$data->execute(array($_GET['task'],$_GET['importance']));
  $is_sucess=$db_link->prepare($add_task)->execute(array($_GET['task'],$_GET['importance']));
  if($is_sucess){
    $query_last_task ="SELECT created,num FROM task order by created desc limit 1";
    $last_task_record = $db_link->query($query_last_task);
    $last_task = $last_task_record->fetch(PDO::FETCH_ASSOC);
      echo json_encode(array('msg'=>'新增成功','txt'=>$_GET['task'],'importance'=>$_GET['importance'],'num' => $last_task['num'],'date'=>substr($last_task['created'],0,10)));


  }else{
    echo json_encode(array('msg'=>'新增失敗'));
  }
  // $db_link->close();
 ?>
