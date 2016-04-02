<?php
  require("head.php");
  if(!isset($_GET['room'])){
    gotoindex();
  }
  $room=htmlspecialchars($_GET['room']);
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>五子棋<?php echo $room; ?>房间</title>
    <style>body{text-align:center}td{width:12;height:12}</style>
    <script src="jquery.js"></script>
    <script src="query.js"></script>
    <script src="game.js"></script>
  </head>
  <body>
    <h1>五子棋</h1>
    <p>
      当前房间：<?php echo $room; ?>
    </p>
    <div id="gameview" style="cursor:default"></div>
    <p>
      <a href="index.php">退出房间</a>
    </p>
    <?php require("copyright.php"); ?>
  </body>
</html>
<?php
  require("foot.php");
?>