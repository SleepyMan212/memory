<?php
// require 'connectMysql.php';
require 'connectMysql2.php';
$pageRow_records = 4; //每頁筆數
$num_pages = 1; //預設頁數
// 如果有參數就設定
if(isset($_GET['page'])) $num_pages = $_GET['page'];
  try {
    $startRow_records=($num_pages-1)*$pageRow_records;
    $query_all_data = "SELECT name, id, due_date FROM `project`";
    $query_limit_data = "SELECT name, id, due_date FROM `project` WHERE finish = 0
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
    <link rel="stylesheet" href="src/css/main.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  </head>
  <body>
    <div class="banner"></div>
    <div class="nav">
      <ul>
        <li><a href="./index.php">首頁</a></li>
        <li><a href="./project.php">專案</a></li>
      </ul>
    </div>
  <div class="left_panel">
    <button type="button" name="button" id='new_project' class="btn btn-primary">新增專案</button>
    <div class="list" id="add_project">
      <label for="project_name">專案名: </label>
      <input type="text" name=""  id="project_name" placeholder="專案名" class="input">
      <label for="project_date">目標完成時間: </label>
      <input type="date" name=""  id="project_date" class="input">
      <button type="button" name="button" class="btn btn-outline-success input" id="submit">送出</button>
    </div>
    <div class="list" id="list">
      <div class="field">
        <h2>專案</h2>
        <p id="total_finish" style="float:right; margin-right:10px;" >總共完成: <?php echo $data_num-$all_record; ?> 筆事項</p>
        <p id="total_unfinish">總共還有: <?php echo $all_record; ?> 筆事項</p>
      </div>
      <hr>
      <div class="formname">
        <span>號碼</span>
        <span>截止日期</span>
        <span style="width:30%; margin-left:50px;display:inline-block;">名稱</span>
      </div>
      <?php while ($data = $limit_data->fetch(PDO::FETCH_ASSOC)) {
        echo "
        <div class=data data-id=$data[id]>
          <span style='width:5%;'>$data[id]</span>
          <span>$data[due_date]</span>
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
              <a href=?page=". ($num_pages<$total_pages?$num_pages+1:$total_pages ) .">下一頁</a>
            </li>";

          // if($num_pages!=$total_pages){
            echo
            "<li>
              <a href=?page=".($total_pages==0?1:$total_pages).">最末頁</a>
            </li>";
          // }
           ?>
        </ul>
      </div>
    </div>
  </div>
  <div class="right_panel border">
    <div class="">
      <div class="informaton">
        <span class="input">編號:</span>
        <span class="text-left input" id='pro_num'></span>
      </div>
      <div class="informaton">
        <span class="input">專案名:</span>
        <span class="text-left input" id='pro_name'></span>
      </div>
      <div class="informaton">
        <span class="input">預計時間:</span>
        <span class="text-left input" id="due_day"></span>
      </div>
      <hr>
      <div class="text-left input" style="font-size:24px" id="target">目標:<br /></div>
      <div class="list_pro_task"  style="overflow:auto;overflow-X:hidden; height:180px">

      </div>

    </div>
    <div class="function">
      <input type='text' size='60' style='margin-top:5px'calss='new' id="new_task">
      <button type="button" name="button" id="add" class="btn btn-dark">新增</button>
      <button type="button" name="button" id="solve" class="btn btn-success">完成</button>
    </div>

  </div>
  <script>
    $("#add_project").hide();
    let show=true;
    $("#new_project").click((e)=>{
      $("#add_project").toggle();
      $("#list").toggle();
      if(show){
          $("#new_project").text("查看專案");
          show=false;
      }else{
        $("#new_project").text("新增專案");
        show = true;
      }
      // $("#new_project").text("查看專案");

    });
    // let add_counter=
    $("#submit").click((e)=>{
      console.log($("#project_date").val());
      $.ajax({
        type:'post',
        url:'./add_project.php',
        dataType:'json',
        data:{
          name: $("#project_name").val(),
          due_date: $("#project_date").val()
        },
        success:(data)=>{
          console.log(data);
          content="\
          <div class=data data-id="+data.id+">\
            <span>"+data.id+"</span>\
            <span>"+data.due_date+"</span>\
            <span style=margin-left:40px>"+data.name+"</span>\
          </div>\
          ";
          console.log(data.msg);
          // alert(data);
        },
        error:(jqXHR)=>{
          alert("發生錯誤: "+jqXHR.status);
        },
        complete:()=>{
          name: $("#project_name").val("");
          finish_time: $("#project_date").val("");
        }
      })
    });
    $(document).on('click',".data",(e)=>{
      $(".pro_task").remove();
      // console.log(e.target.dataset.id);
      if(e.target.dataset.id){
        console.log(e.target.dataset.id);
        $.ajax({
          type:'post',
          url:'./query_pro.php',
          dataType:'json',
          data:{
            id: e.target.dataset.id
          },
          success:(data)=>{
            // console.log(data);
            $("#due_day").text(data[0].due_date);
            $("#pro_name").text(data[0].pro_name);
            $("#pro_num").text(data[0].pro_num);

            console.log(data[1]);
            if(data[1]){
              i=1;
              data[1].forEach((d)=>{
                let content= "<div class='informaton pro_task "+(d.finish==1?'checked':'')+"'>\
                  <span class='input' style='display:inline-block;text-align:right; width:45px; '>"+(i++)+"</span>\
                  <span class='text-left input task'>"+d.txt+"</span>\
                  <span class='input '>完成: </span>\
                  <input type='checkbox' id='finish' "+(d.finish=='1'?'checked':'')+ " data-id="+d.id+">\
                </div>";
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
    // if value exist
    // console.log($('#pro_num').val());
      $("#add").click((e)=>{
        // alert($('#pro_num').text());
      if($('#pro_num').text()){
        // console.log("cl");
        // alert("WW");
        $.ajax({
          type:'post',
          url:'./add_pro_task.php',
          dataType:'json',
          data:{
            pro_task: $('#new_task').val(),
            num: $('#pro_num').text()
          },
          success:(data)=>{

              // console.log(data.msg);
              // console.log(data);
              const content= "<div class='informaton pro_task'>\
                <span class='input'style='display:inline-block;text-align:right; width:45px; '>"+data.num+"</span>\
                <span class='text-left input task'>"+data.task+"</span>\
                <span class='input '>完成: </span>\
                <input type='checkbox'  id='finish'data-id="+data.id+">\
              </div>";
              if(data.num){
                if($(".pro_task").length>0){
                  $(".pro_task:last").after(content);
                }else{
                  $(".list_pro_task").append(content);
                }
              }

              // console.log(data);
              // alert(data.msg+"A");
          },
          error:(jqXHR)=>{
            console.log("發生錯誤"+jqXHR.status);
          },
          complete:()=>{
            $('#new_task').val("");
          }
        })
      }
    });
    $(document).on('change','#finish' ,(e)=>{
      console.log($(e.target)[0].dataset.id);
      console.log($(e.target).prop('checked'));
      if($(e.target).prop('checked')){
        // $($(e.target).parents()[0]).css({"text-decoration": "line-through","color":"red"});
        $($(e.target).parents()[0]).addClass("checked");
        $(e.target).prop('checked',true)
      }else{
        // $($(e.target).parents()[0]).css({"text-decoration": "none","color":"black"});
        $($(e.target).parents()[0]).removeClass("checked");
        $(e.target).prop('checked',false)
      }
      $.ajax({
        type:'post',
        url:'./project/finish_task.php',
        dataType:'json',
        data:{
          pro_task_finish: $(e.target).prop('checked'),
          id:$(e.target)[0].dataset.id
        },
        success:(data)=>{
          console.log(data.msg);
        },
        error:(jqXHR)=>{
          console.log("發生錯誤"+jqXHR.status);
        }
      });
    });
  </script>
  </body>
</html>
