<?php
  $user="";
  if(isset($_COOKIE['user'])&&strlen($_COOKIE['user'])>0){
    $user=$_COOKIE['user'];
    $s0=$db->prepare("SELECT name FROM user WHERE id=?");
    $s0->bind_param('s',$user);
    $s0->execute();
    $s0->store_result();
    if($s0->num_rows==0){
      setcookie("user","",time()-1);
      gotoindex();
    }else{
      $s0->bind_result($name);
      $s0->fetch();
      $name=htmlspecialchars($name);
    }
  }else{
    $name0="player";
    $user=md5(uniqid(rand(),true));
    $s0=$db->prepare("INSERT INTO user (id,name) VALUES (?,?)");
    $s0->bind_param('ss',$user,$name0);
    $s0->execute();
    setcookie("user",$user,time()+3600*24*7);
    gotoindex();
  }
?>