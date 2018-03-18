<?php
header('Content-Type: application/json; charset=UTF-8');
require_once '..\connectMysql2.php';
$update_finish = "UPDATE `pro_task` SET finish=$_POST[pro_task_finish] WHERE id=$_POST[id]";
$is_sucess=$db_link->query($update_finish);
if($is_sucess){
  echo json_encode(array('msg'=>'修改成功'));
}else{
  echo json_encode(array('msg'=>'修改失敗'));
}
 ?>
