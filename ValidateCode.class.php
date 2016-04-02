<?php
//验证码类
class ValidateCode {
 private $charset = 'abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789';//随机因子
 private $code;//验证码
 private $codelen = 4;//验证码长度
 private $width = 180;//宽度
 private $height = 60;//高度
 private $img;//图形资源句柄
 private $font;//指定的字体
 private $fontsize = 30;//指定字体大小
 private $fontcolor;//指定字体颜色
 //构造方法初始化
 public function __construct() {
  $this->font = dirname(__FILE__).'/font.ttf';//注意字体路径要写对，否则显示不了图片
  $this->codelen=mt_rand(4,6);
 }
 //生成随机码
 private function createCode() {
  $_len = strlen($this->charset)-1;
  for ($i=0;$i<$this->codelen;$i++) {
   $this->code .= $this->charset[mt_rand(0,$_len)];
  }
 }
 //生成背景
 private function createBg() {
  $this->img = imagecreatetruecolor($this->width, $this->height);
  //imageantialias($this->img, true);
  for($i=0;$i<10;$i++){
   $color = imagecolorallocate($this->img, mt_rand(157,255), mt_rand(157,255), mt_rand(157,255));
   imagefilledrectangle($this->img,$this->width*$i/10.0,0,$this->width*($i+1)/10.0,$this->height,$color);
  }
 }
 //生成文字
 private function createFont() {
  $_x = $this->width / $this->codelen;
  for ($i=0;$i<$this->codelen;$i++) {
   $_c[0]=mt_rand(0,150);$_c[1]=mt_rand(0,150);$_c[2]=mt_rand(0,150);
   $_c[mt_rand(0,2)]=mt_rand(0,50);
   $this->fontcolor = imagecolorallocate($this->img,$_c[0],$_c[1],$_c[2]);
   imagettftext($this->img,$this->fontsize,mt_rand(-30,30),$_x*$i+mt_rand(1,5),$this->height / 1.4,$this->fontcolor,$this->font,$this->code[$i]);
  }
 }
 //生成线条、雪花
 private function createLine() {
  //线条
  for ($i=0;$i<24;$i++) {
   $color = imagecolorallocate($this->img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
   imageline($this->img,mt_rand(0,$this->width),mt_rand(0,$this->height),mt_rand(0,$this->width),mt_rand(0,$this->height),$color);
  }
  //雪花
  /*for ($i=0;$i<100;$i++) {
   $color = imagecolorallocate($this->img,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
   imagestring($this->img,mt_rand(1,5),mt_rand(0,$this->width),mt_rand(0,$this->height),'*',$color);
  }*/
 }
 //fuck!
 private function fuck() {
  $dif=0;
  for ($x=0;$x<$this->width;$x++) {
   $linediff=mt_rand(-50,50);
   if(mt_rand(1,3)==3)$dif+=mt_rand(-1,1);
   if($dif>5)$dif=5;
   if($dif<-5)$dif=-5;
   for ($y=0;$y<$this->height;$y++) {
     $rgb = imagecolorat($this->img,$x,($y+$dif+$this->height)%$this->height);
     $r=($rgb >>16) & 0xFF;
     $g=($rgb >>8) & 0xFF;
     $b=$rgb & 0xFF;
     $r+=mt_rand(-30,30)+$linediff;
     if($r>255)$r=255;
     if($r<0)$r=0;
     $g+=mt_rand(-30,30)+$linediff;
     if($g>255)$g=255;
     if($g<0)$g=0;
     $b+=mt_rand(-30,30)+$linediff;
     if($b>255)$b=255;
     if($b<0)$b=0;
     $colorl[$y]=($r<<16)|($g<<8)|$b;
   }
   for ($y=0;$y<$this->height;$y++)
    imagesetpixel($this->img,$x,$y,$colorl[$y]);
  }
 }
 //输出
 private function outPut() {
  header('Content-type:image/png');
  imagepng($this->img);
  imagedestroy($this->img);
 }
 //对外生成
 public function doimg() {
  $this->createBg();
  $this->createCode();
  $this->createFont();
  $this->createLine();
  $this->fuck();
  $this->outPut();
 }
 //获取验证码
 public function getCode() {
  return strtolower($this->code);
 }
}
?>