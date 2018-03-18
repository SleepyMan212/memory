setInterval(function() {
  time = new Date();
  $(".show_time").text(time.getFullYear()+"/"+ (time.getMonth()+1)+"/"+time.getDate());
  $(".show_time").append(" " +time.getHours()+":"+ time.getMinutes()+":"+time.getSeconds());
},1000);
function AJAX(e) {
  console.log($(e.target).parents('.data')[0].dataset.type); //取得完成事件的編號
  if($(e.target.checked)[0]){
    $.ajax({
      type:'get',
      url:`finish.php?finish=1&num=${$(e.target).parents('.data')[0].dataset.type}`,
      dataType:'json',
      success:(data)=>{
        console.log(data);
        $(e.target).parents('.data').hide(1000);
        console.log(data.num+" "+data.finish_num);
        $("#total_unfinish").text("總共還有: "+(data.num-data.finish_num)+" 筆事項");
        $("#total_finish").text("總共完成: "+(data.finish_num)+" 筆事項")
        if(!data.nodata){

          content="\
          <div class=data data-type="+data.num+">\
            <span style='width:15px; text-align:right;display:inline-block;'>"+data.num+"</span>\
            <span>"+data.date+"</span>\
            <span style=text-align:right>"+data.importance+"</span>\
            <span style='width:30%;display:inline-block;margin-left:45px;'>"+data.txt+"</span>\
            <span>\
              <input type=checkbox id=unfinish name=unfinish class=unfinish>\
              <label for=finish>完成</label>\
            </span>\
          </div>\
          ";
          $(".data:last").after(content);

        }
      },

      error:function(jqXHR){
        alert("發生錯誤"+jqXHR.status);
      },
      complete:()=>{

      }
    })
  }
}
$(document).on('change',".unfinish",AJAX);
