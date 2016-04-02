$(document).ready(function(){
  refresh();
});

function regclick(){
  $("span").click(function(){
    pos=parseInt($(this).attr("id"));
    var i;
    for(i=0;i<15*15;i++){
      if(i==pos)
        $("#"+i).css("background-color","#8080FF");
      else
        $("#"+i).css("background-color","#FFFFFF");
    }
  });
  $("#go").click(function(){
    $.post("data.php",{room:$.query.get('room'),pos:pos},function(data,status){
      refresh();
    });
  });
  if(parseInt($("#refresh").attr("name"))==1){//need to refresh
    setTimeout(function(){refresh();},1000);
  }
}

function refresh(){
  $.post("data.php",{room:$.query.get('room')},function(data,status){
    document.getElementById("gameview").innerHTML=data;
    pos=-1;
    regclick();
  });
}