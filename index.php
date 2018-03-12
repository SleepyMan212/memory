<?php
require 'connectMysql.php';
$pageRow_records = 5; //每頁筆數
$num_pages = 1; //預設頁數
// 如果有參數就設定
if(isset($_GET['page'])) $num_pages = $_GET['page'];
$startRow_records=($num_pages-1)*$pageRow_records;
$query_num = "SELECT * FROM task ";
$query_all_data = "SELECT * FROM task Where finish = 0 ORDER BY num ASC";
$query_limit_data = "SELECT * FROM task Where finish = 0 ORDER BY num ASC LIMIT {$startRow_records},{$pageRow_records}";
$all_data = $db_link->query($query_all_data);
$limit_data = $db_link->query($query_limit_data);
$num_data= $db_link->query($query_num);
$num = $num_data->num_rows;
$all_record = $all_data->num_rows; //總共筆數
$limit_record = $limit_data->num_rows;
$total_pages = ceil($all_record/$pageRow_records);
$i=1;
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
        <li><a href="">專案</a></li>
      </ul>
    </div>
    <div class="add_data">
      <span class="input">待辦事項</span>
      <input type="text" name="task" id="task" class="input task" size="50">
      <select class="" name="importance" class="input" id="importance">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3" selected>3</option>
        <option value="4">4</option>
        <option value="5">5</option>
      </select>
        <button type="button" name="submit" class="input btn-primary btn" id="submit">送出</button>
    </div>
    
    <div class="list">
      <div class="field">
        <h2>待辦事項</h2>
        <p id="total_finish" style="float:right; margin-right:10px;" value="<?php echo $num-$all_record; ?>">總共完成: <?php echo $num-$all_record; ?> 筆事項</p>
        <p class="total_unfinish">總共還有: <?php echo $all_record; ?> 筆事項</p>
      </div>
      <hr>
      <div class="formname">
        <span>號碼</span>
        <span>發布日期</span>
        <span>重要性</span>
        <span style="width:30%; display:inline-block;">任務</span>

      </div>

      <?php

        while ($data = $limit_data->fetch_assoc()) {
          echo
          "
          <div class=data data-type=$data[num]>
            <span style='width:15px; text-align:right;display:inline-block;'>$data[num]</span>
            <span>".substr($data['created'],0,10)."</span>
            <span style=text-align:right>$data[importance]</span>
            <span style='width:30%;display:inline-block;margin-left:45px;'>$data[txt]</span>
            <span>
              <input type=checkbox id=unfinish name=unfinish class=unfinish>
              <label for=finish>完成</label>
            </span>
          </div>
          ";
        }
       ?>
       <div class="page">
         <ul>
           <?php
           if($num_pages!=1){
             echo
             "<li>
               <a href=?page=1>第一頁</a>
             </li>";
           }
           // if($num_pages>1){
             echo
             "<li>
               <a href=?page=". ($num_pages>1?$num_pages-1:1) .">上一頁</a>
             </li>";
           // }
           // if($num_pages<$total_pages){
             echo
             "<li>
               <a href=?page=". ($num_pages<$total_pages?$num_pages+1:$total_pages) .">下一頁</a>
             </li>";
           // }
           if($num_pages!=$total_pages){
             echo
             "<li>
               <a href=?page=".$total_pages.">最末頁</a>
             </li>";
           }
            ?>
         </ul>
       </div>
    </div>
      <script src="./src/js/main.js"></script>
      <script>
        $(document).ready(function(){
          $("#submit").click( () => {
            $(".total_unfinish").text("總共還有: <?php echo (++$all_record);  ?> 筆事項");
            alert(`showData.php?importance=${$("#importance").val()}&task=${$("#task").val()}`);
            $.ajax({
              type: "get",
              url: `./showData.php?importance=${$("#importance").val()}&task=${$("#task").val()}`,
              dataType: "json",
              success: function (data) {
                alert(data.msg);
                console.log($(".total_unfinish"));
                <?php
                  //如果目前筆數小於一頁筆數時顯示
                  if($num_pages==$total_pages&&$limit_record<$pageRow_records){
                 ?>
                  content="\
                  <div class=data data-type="+data.num+">\
                    <span style='width:15px; text-align:right;display:inline-block;'>"+data.num+"</span>\
                    <span>"+data.time+"</span>\
                    <span style=text-align:right>"+data.importance+"</span>\
                    <span style='width:30%;display:inline-block;margin-left:45px;'>"+data.txt+"</span>\
                    <span>\
                      <input type=checkbox id=unfinish name=unfinish class=unfinish>\
                      <label for=finish>完成</label>\
                    </span>\
                  </div>\
                  ";
                  $(".data:last").after(content);
                  // 把新加入的元素加入監聽事件
                  $(".data:last").change( (e)=>{
                    console.log($(e.target).parents('.data'));
                    if($(e.target.checked)[0]){
                      $(e.target).parents('.data').hide(1000);
                    }
                  });
                <?php }?>
              },
              error:function(jqXHR){
                alert("發生錯誤"+jqXHR.status);
              },
              complete:()=>{
                $("#task").val("");
              }
            })
          });

        });

      </script>
  </body>
</html>
