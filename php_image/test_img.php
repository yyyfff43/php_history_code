<?php
header("Content-type: text/html; charset=utf-8");
require_once('img.class.php');
 
$t = new Img();

//$rPic为源图片，$sPic为要生成的图片 
$rPic = "img/pic.jpg";
$sPic = "img/new_pic.jpg";

echo $t->Img_BigToSamll($rPic,142,189,$sPic,1);

?>