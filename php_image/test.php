<?php
header("Content-type: text/html; charset=utf-8");
require_once('thumb.class.php');
 
$t = new ThumbHandler();
 
$t->setSrcImg("img/123.jpg");//输入图片路径
$t->setDstImg("tmp/new_test.jpg");//输出图片路径
 
$t->setDstImgBorder(10,"#ffffff");//边框补白
// 指定缩放比例
var_dump($t->createImg(200,200));

?>