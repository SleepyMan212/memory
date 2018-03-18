<?php
  header('Content-Type: application/json; charset=UTF-8');
  require_once 'connectMysql2.php';
  // echo json_encode(array('msg'=>'新增失敗'));
  if(!isset($_POST['pro_task'])||empty($_POST['pro_task'])){
    echo json_encode(array('msg' => '欄位為空白'));
    return;
  }
  $add_pro_task = "INSERT INTO `pro_task` (txt,num) VALUES (?,?) ";
  $is_sucess = $db_link->prepare($add_pro_task)->execute(array($_POST['pro_task'],$_POST['num']));
  $id=$db_link->lastInsertId();
  if($is_sucess){
    // $query_pro_task_num="SELECT count(*) FROM `pro_task` WHERE num = ?";
    $query_pro_task_num="SELECT count(*) FROM `pro_task` WHERE num = $_POST[num]";
    // $pro_task_num = $db_link->prepare($query_pro_task_num)->execute($_POST['num'])->fetchColumn();;
    $pro_task_num = $db_link->query($query_pro_task_num)->fetchColumn();
    echo json_encode(array('msg'=>'新增成功','task'=>$_POST['pro_task'],'num'=>$pro_task_num,'id'=>$id));
  }else{
    echo json_encode(array('msg'=>'新增失敗'));
  }

 ?>
