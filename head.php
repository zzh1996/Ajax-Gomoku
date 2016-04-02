<?php
  function gotophp($url){
    header("HTTP/1.1 303 See Other");
    header("Location: $url");
    exit();
  }
  function gotoindex(){
    gotophp("index.php");
  }
  function getname($db,$id){
    $s0=$db->prepare("SELECT name FROM user WHERE id=?");
    $s0->bind_param('s',$id);
    $s0->execute();
    $s0->store_result();
    if($s0->num_rows>0){
      $s0->bind_result($name);
      $s0->fetch();
      $name=htmlspecialchars($name);
      return $name;
    }else{
      return "";
    }
  }
  require("pw.php");
  $db=new mysqli("localhost","root",$PW,"wzq");
  mysqli_query($db,"set NAMES UTF8");
  mysqli_query($db,"set character set 'utf8'");
  require("login.php");
?>