<?php
// require 'connectMysql.php';
require '../connectMysql2.php';
$pageRow_records = 4; //每頁筆數
$num_pages = 1; //預設頁數
// 如果有參數就設定
if(isset($_GET['page'])) $num_pages = $_GET['page'];
  try {
    $startRow_records=($num_pages-1)*$pageRow_records;
    $query_all_data = "SELECT * FROM `project`";
    $query_limit_data = "SELECT * FROM `project` WHERE finish = 1
                          ORDER BY id ASC LIMIT $startRow_records , $pageRow_records";
    $query_num = "SELECT * FROM `project` ";
    $data_num = $db_link->query($query_num)->rowCount();
    $all_data = $db_link->query($query_all_data);
    $limit_data = $db_link->query($query_limit_data);
    $all_record = $all_data->rowCount();
    $limit_record = $limit_data->rowCount();
    $total_pages = ceil($all_record/$pageRow_records);
  } catch (PDOException $e) {
    echo $e->getMessage();
  }



 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>備忘錄</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../src/css/main.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  </head>
  <body>
    <div class="banner"></div>
    <div class="nav">
      <ul>
        <li><a href="../index.php">首頁</a></li>
        <li><a href="../project.php">專案</a></li>
        <li><a href="./show_complete_pro.php">完成</a></li>
      </ul>
    </div>
  <div class="left_panel">
    <!-- <button type="button" name="button" id='new_project' class="btn btn-primary">新增專案</button>
    <div class="list" id="add_project">
      <label for="project_name">專案名: </label>
      <input type="text" name=""  id="project_name" placeholder="專案名" class="input">
      <label for="project_date">目標完成時間: </label>
      <input type="date" name=""  id="project_date" class="input">
      <button type="button" name="button" class="btn btn-outline-success input" id="submit">送出</button>
    </div> -->
    <div class="list" id="list">
      <div class="field">
        <h2>完成</h2>
        <p id="total_unfinish">總共有: <?php echo $all_record; ?> 筆事項</p>
      </div>
      <hr>
      <div class="formname">
        <span>號碼</span>
        <span>完成日期</span>
        <span style="width:30%; margin-left:50px;display:inline-block;">名稱</span>
      </div>
      <?php while ($data = $limit_data->fetch(PDO::FETCH_ASSOC)) {
        echo "
        <div class=data data-id=$data[id]>
          <span style='width:5%;'>$data[id]</span>
          <span>$data[finish_date]</span>
          <span style=margin-left:40px>$data[name]</span>
        </div>
        ";
      } ?>
      <div class="page">

        <ul>
          <?php
          // if($num_pages!=1){
            echo
            "<li>
              <a href=?page=1>第一頁</a>
            </li>";
          // }
            echo
            "<li>
              <a href=?page=". ($num_pages>1?$num_pages-1:1) .">上一頁</a>
            </li>";

            echo
            "<li>
              <a href=?page=". ($num_pages<$total_pages-1?$num_pages+1:$total_pages-1 ) .">下一頁</a>
            </li>";

          // if($num_pages!=$total_pages){
            echo
            "<li>
              <a href=?page=".($total_pages==0?1:$total_pages-1).">最末頁</a>
            </li>";
          // }
           ?>
        </ul>
      </div>
    </div>
  </div>
  <div class="right_panel border">
    <div>
      <div style="display: inline-block; width:50%;">
        <div class="information">
          <span class="input">編號:</span>
          <span class="text-left input" id='pro_num'></span>
        </div>

        <div class="information">
          <span class="input">專案名:</span>
          <span class="text-left input" id='pro_name'></span>
        </div>
        <div class="information">
          <span class="input">開始時間:</span>
          <span class="text-left input" id="start_day"></span>
        </div>
        <div class="information">
          <span class="input">預計時間:</span>
          <span class="text-left input" id="due_day"></span>
        </div>
        <div class="information">
          <span class="input">完成時間:</span>
          <span class="text-left input" id="finish_day"></span>
        </div>
      </div>
      <div class="information" style="float:right;">
        <button type="button" class="btn btn-outline-dark" onclick='print()'>列印</button>
      </div>
      <div style="display: inline-block;">
        <div class="information">
          <span class="input">完成任務:</span>
          <span class="text-left input" id="complete_task"></span>
        </div>
        <div class="information">
          <span class="input">未完成任務:</span>
          <span class="text-left input" id="uncomplete_task"></span>
        </div>
      </div>
    </div>
    <hr>
    <div class="">
      <div class="text-left input" style="font-size:24px" id="target">任務:<br /></div>
      <div class="list_pro_task"  style="overflow:auto;overflow-X:hidden; height:150px">
    </div>

    </div>
  </div>
  <script>
  $(document).on('click',".data",(e)=>{
    $(".pro_task").remove();
    // console.log(e.target.dataset.id);
    if(e.target.dataset.id){
      console.log(e.target.dataset.id);
      $.ajax({
        type:'post',
        url:'../query_pro.php',
        dataType:'json',
        data:{
          id: e.target.dataset.id
        },
        success:(data)=>{
          console.log(data);
          $("#due_day").text(data[0].due_date);
          $("#start_day").text(data[0].created);
          $("#finish_day").text(data[0].finish_date);
          $("#pro_name").text(data[0].name);
          $("#pro_num").text(data[0].id);
          $("#complete_task").text(data[2].complete);
          $("#uncomplete_task").text((data[2].all-data[2].complete));

          console.log(data[1]);
          if(data[1]){
            i=1;
            data[1].forEach((d)=>{
              let content= "<div class='information pro_task "+(d.finish==1?'checked':'')+"'>\
                <span class='input' style='display:inline-block;text-align:right; width:45px; '>"+(i++)+"</span>\
                <span class='text-left input task'>"+d.txt+"</span>";
              content+=(d.finish=='1')?("<span class='input checked'>完成 </span>"):("<span class='input'>未完成 </span>")+"</div>";
              if($(".pro_task").length>0){
                $(".pro_task:last").after(content);
              }else{
                $(".list_pro_task").append(content);
              }
            });

          }
        },
        error:(jqXHR)=>{
          alert("發生錯誤: "+jqXHR.status);
        }
      })
    }
  });
  function print(){
    if(!$("#pro_num").text()) return;
    const id = $("#pro_num").text();
    open("../pdf.php?id="+id);
  }
  </script>
</body>
</html>
