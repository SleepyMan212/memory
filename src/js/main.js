$(document).ready(function(){
  // 在資料庫裡標記已完成
  $(".unfinish").change( (e)=>{
    console.log($(e.target).parents('.data')[0].dataset.type); //取得完成事件的編號
    if($(e.target.checked)[0]){
      $.ajax({
        type:'get',
        url:`finish.php?finish=1&num=${$(e.target).parents('.data')[0].dataset.type}`,
        dataType:'json',
        success:(data)=>{
          $(e.target).parents('.data').hide(1000);
          // console.log(data.msg);
          // console.log(data.num);
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
            // 把新加入的元素加入監聽事件
            $(".data:last").change( (e)=>{
              console.log($(e.target).parents('.data'));
              if($(e.target.checked)[0]){
                $(e.target).parents('.data').hide(1000);
              }
            });
          }
        },

        error:function(jqXHR){
          alert("發生錯誤"+jqXHR.status);
        },
        complete:()=>{

        }
      })
    }
  });
});
