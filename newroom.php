<?php
  require("head.php");
  session_start();
  session_destroy();
  if(!isset($_POST["validate"]))gotoindex();
  if(strtolower($_POST["validate"])!=$_SESSION["authnum_session"]){
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>五子棋</title>
    <style>body{text-align:center}</style>
  </head>
  <body>
    <h1>验证码有误！</h1>
    <a href="index.php">返回</a>
  </body>
</html>
<?php
  }else{
    do{
      $room=rand(100000,999999);
      $s0=$db->prepare("SELECT room FROM room WHERE room=?");
      $s0->bind_param('s',$room);
      $s0->execute();
      $s0->store_result();
    }while($s0->num_rows>0);
    $p20="";
    $game0=str_repeat("0 ",15*15);
    $s0=$db->prepare("INSERT INTO room (room,p1,p2,game) VALUES (?,?,?,?)");
    $s0->bind_param('ssss',$room,$user,$p20,$game0);
    $s0->execute();
    gotophp("game.php?room=".$room);
  }
  require("foot.php");
?>