<?php
  require("head.php");
  if(isset($_POST['newname'])){
    $newname=$_POST['newname'];
    $s0=$db->prepare("UPDATE user SET name=? WHERE id=?");
    $s0->bind_param('ss',$newname,$user);
    $s0->execute();
    gotoindex();
  }
  require("foot.php");
?>