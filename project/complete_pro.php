<?php
header('Content-Type: application/json; charset=UTF-8');
require '..\connectMysql2.php';
$update_pro_finish="UPDATE `project` SET finish_date = NOW() ,finish=1 WHERE  id = $_POST[pro_id]";
$is_sucess=$db_link->query($update_pro_finish);
$query_next = "SELECT * FROM `project` WHERE finish=0 AND id>$_POST[pro_id]  LIMIT 3 , 4";
$fin_num = count($db_link->query("SELECT * FROM `project` WHERE finish=1")->fetchAll());
$unfin_num = count($db_link->query("SELECT * FROM `project` WHERE finish=0")->fetchAll());
$next=$db_link->query($query_next)->fetchAll(PDO::FETCH_ASSOC);

if($is_sucess){
  echo json_encode(array(array('msg'=>'更新成功','fin_num'=>$fin_num,'unfin_num'=>$unfin_num),$next,));
}else
  echo json_encode(array('msg'=>'更新失敗'));
?>
