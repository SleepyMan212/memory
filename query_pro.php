<?php
header('Content-Type: application/json; charset=UTF-8');
require_once 'connectMysql2.php';

$query_pro = "SELECT name, due_date FROM `project` WHERE id=$_POST[id]";
$query_pro_task = "SELECT * FROM `pro_task` WHERE num= $_POST[id] ";
$all_pro_task = $db_link->query($query_pro_task);
$pro = $db_link->query($query_pro)->fetch(PDO::FETCH_ASSOC);
// echo json_encode(array('name'=>$pro['name'],'due_date'=>$pro['due_date'],'num'=>$_POST['id']));
if($all_pro_task!==false){
  $result = $all_pro_task->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode(array(array('pro_name'=>$pro['name'],'due_date'=>$pro['due_date'],'pro_num'=>$_POST['id']),$result));

}
// $result =

 ?>
