<?php
  header('Content-Type: application/json; charset=UTF-8');
  require_once 'connectMysql2.php';
  if(!isset($_POST['name'])||empty($_POST['name'])||!isset($_POST['due_date'])||empty($_POST['due_date'])){
    echo json_encode(array('msg'=>' 未填寫事項'));
    return;
  }
  $add_project = "INSERT INTO `project`(name,due_date) VALUES(?,?)";
  $is_sucess = $db_link->prepare($add_project)->execute(array($_POST['name'],$_POST['due_date']));
  if($is_sucess){
    $query_last_project ="SELECT name, id, due_date FROM `project` order by created desc limit 1";
    $query_num = "SELECT * from `project` WHERE finish=0";
    $num=count($db_link->query($query_num)->fetchAll());
    $last_project = $db_link->query($query_last_project)->fetch(PDO::FETCH_ASSOC);
    echo json_encode(array('msg' => '新增成功','name'=>$last_project['name'],
      'due_date'=>$last_project['due_date'],'id'=>$last_project['id'],'pro_num'=>$num ));
  }else{
    echo json_encode(array('msg'=>'新增失敗'));
  }


 ?>
