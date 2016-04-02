<?php
  require("head.php");
  //验证身份
  if(!isset($_POST['room']))exit();
  $room=$_POST['room'];
  $s0=$db->prepare("SELECT p1,p2,game FROM room WHERE room=?");
  $s0->bind_param('s',$room);
  $s0->execute();
  $s0->store_result();
  if($s0->num_rows==0){
    echo '房间号不存在！';
    exit();
  }
  $s0->bind_result($p1,$p2,$game);
  $s0->fetch();
  if($p1==$user){
    $me=1;
  }else if($p2==$user){
    $me=2;
  }else if($p2==""){ //加入
    $s0=$db->prepare("UPDATE room SET p2=? WHERE room=?");
    $s0->bind_param('ss',$user,$room);
    $s0->execute();
    $p2=$user;
    $me=2;
  }else{
    echo '此房间已满！';
    exit();
  }
  //处理请求
  $s=explode(" ",$game);
  $maxn=0;
  $turn=1;
  for($i=0;$i<15;$i++){
    for($j=0;$j<15;$j++){
      $n[$i][$j]=intval($s[$i*15+$j]);
      if($maxn<$n[$i][$j]){
        $maxn=$n[$i][$j];
        $turn=$n[$i][$j]%2+1;
      }
    }
  }
  //judge win
  $win=0;
  $wintext="";
  for($i=0;$i<11;$i++){
    for($j=0;$j<11;$j++){
      //right down
      $f=$n[$i][$j]%2+1;
      for($k=0;$k<5&&$f>0;$k++){
        if($n[$i+$k][$j+$k]==0||$n[$i+$k][$j+$k]%2+1!=$f)$f=0;
      }
      if($f>0)$win=$f;
      //row
      $f=$n[$i][$j]%2+1;
      for($k=0;$k<5&&$f>0;$k++){
        if($n[$i][$j+$k]==0||$n[$i][$j+$k]%2+1!=$f)$f=0;
      }
      if($f>0)$win=$f;
      //line
      $f=$n[$i][$j]%2+1;
      for($k=0;$k<5&&$f>0;$k++){
        if($n[$i+$k][$j]==0||$n[$i+$k][$j]%2+1!=$f)$f=0;
      }
      if($f>0)$win=$f;
      //right up
      $f=$n[$i+4][$j]%2+1;
      for($k=0;$k<5&&$f>0;$k++){
        if($n[$i+4-$k][$j+$k]==0||$n[$i+4-$k][$j+$k]%2+1!=$f)$f=0;
      }
      if($f>0)$win=$f;
    }
  }
  $f=1;
  for($i=0;$i<15;$i++){
    for($j=0;$j<15;$j++){
      if($n[$i][$j]==0)$f=0;
    }
  }
  if($f==1){
    $win=1;
    $wintext="平局";
  }else if($win==2){
    $wintext="玩家1：".getname($db,$p1)." 获胜";
  }else if($win==1){
    $wintext="玩家2：".getname($db,$p2)." 获胜";
  }
  if($wintext!=""){
    $wintext="<br><font color='red' size='5em'>".$wintext."</font><br>";
  }

  //go?
  if($win==0&&$turn==$me&&isset($_POST['pos'])){
    $pos=$_POST['pos'];
    if($pos>=0&&$pos<15*15){
      if($n[floor($pos/15)][$pos%15]==0){
        $n[floor($pos/15)][$pos%15]=$maxn+1;
        $newt="";
        for($i=0;$i<15;$i++){
          for($j=0;$j<15;$j++){
            $newt=$newt.$n[$i][$j]." ";
          }
        }
        $s0=$db->prepare("UPDATE room SET game=? WHERE room=?");
        $s0->bind_param('ss',$newt,$room);
        $s0->execute();
        gotophp("data.php?room=".$room);
      }
    }
  }
  //输出页面
  if($p2!=""&&$turn==1)echo '→';
  echo '玩家1 ● ：'.getname($db,$p1);
  if($me==1)echo ' (当前玩家)';
  echo '<br>';
  if($turn==2)echo '→';
  echo '玩家2 ○ ：'.($p2==""?'<b>未加入</b>':getname($db,$p2));
  if($me==2)echo ' (当前玩家)';
  echo '<br>';
  echo $wintext;
  if($p2!=""){
    //draw map
    echo '<table border=0 align="center">';
    for($i=0;$i<15;$i++){
      echo '<tr>';
      for($j=0;$j<15;$j++){
        echo '<td>';
        if($n[$i][$j]>0){
          if($n[$i][$j]==$maxn)echo '<font color="red">';
          if($n[$i][$j]%2==1){//p1
            echo '●';
          }else{//p2
            echo '○';
          }
          if($n[$i][$j]==$maxn)echo '</font>';
        }else{//empty
          echo '<span id="'.($i*15+$j).'">';
          if($i==0&&$j==0)echo '┌';
          else if($i==0&&$j==14)echo '┐';
          else if($i==14&&$j==0)echo '└';
          else if($i==14&&$j==14)echo '┘';
          else if($i==0)echo '┬';
          else if($i==14)echo '┴';
          else if($j==0)echo '├';
          else if($j==14)echo '┤';
          else echo '┼';
          echo '</span>';
        }
        echo '</td>';
      }
      echo '</tr>';
    }
    echo '</table>';
    //button
    echo '<button id="go" type="button">确定</button>';
  }
  //refresh
  if($p2==""||$turn!=$me){
    $ref=1;
  }else{
    $ref=0;
  }
  echo '<div id="refresh" name="'.$ref.'"></div>';
  require("foot.php");
?>