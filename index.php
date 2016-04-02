<?php
  require("head.php");
  session_start();
  session_destroy();
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>五子棋</title>
    <style>body{text-align:center}</style>
  </head>
  <body>
    <h1>五子棋</h1>
    <p>
      您好，<?php echo $name; ?>
    </p>
    <p>
      <form action="changename.php" method="POST">
        <input name="newname" type="text" placeholder="新的名字" value="<?php echo $name; ?>" />
        <input type="submit" value="改名" />
      </form>
    </p>
    <p>
      <form action="game.php" method="GET">
        <input name="room" type="text" placeholder="房间号" value="" />
        <input type="submit" value="加入" />
      </form>
    </p>
    <p>
      <font size="0.5em">
        <br>为了防止某两人恶意大量开房，
        <br>新建房间请输入下图中的验证码：
        <br>
      </font>
      <img title="点击刷新" src="captcha.php" onclick="this.src='captcha.php?'+Math.random();"></img>
      <form action="newroom.php" method="POST">
      <input name="validate" type="text" value="" /> 
      <input type="submit" value="新建房间" />
    </p>
    <?php require("copyright.php"); ?>
  </body>
</html>
<?php
  require("foot.php");
?>