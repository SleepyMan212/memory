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
          console.log(data.msg);
        },
        error:function(jqXHR){
          alert("發生錯誤"+jqXHR.status);
        },
        complete:()=>{
          $(e.target).parents('.data').hide(1000);
        }
      })
    }
  });
});
