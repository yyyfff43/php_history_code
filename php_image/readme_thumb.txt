图片处理类

范例：
<?php
require_once('lib/thumb.class.php');
 
$t = new ThumbHandler();
 
$t->setSrcImg("img/test.jpg");
$t->setDstImg("tmp/new_test.jpg");
$t->setMaskImg("img/test.gif");
$t->setMaskPosition(1);
$t->setMaskImgPct(80);
$t->setDstImgBorder(4,"#dddddd");
 
// 指定缩放比例
$t->createImg(300,200);
?>





<?php
require_once('lib/thumb.class.php');
 
$t = new ThumbHandler();
 
// 基本使用
$t->setSrcImg("img/test.jpg");
$t->setMaskWord("test");
$t->setDstImgBorder(10,"#dddddd");
 
// 指定缩放比例
$t->createImg(50);
?>



<?php
equire_once('lib/thumb.class.php');
 
$t = new ThumbHandler();
 
// 基本使用
$t->setSrcImg("img/test.jpg");
$t->setMaskWord("test");
 
// 指定固定宽高
$t->createImg(200,200);
?>



<?php
require_once('lib/thumb.class.php');
 
$t = new ThumbHandler();
 
$t->setSrcImg("img/test.jpg");
$t->setDstImg("tmp/new_test.jpg");
$t->setMaskWord("test");
 
// 指定固定宽高
$t->createImg(200,200);
?>



<?php
require_once('lib/thumb.class.php');
 
$t = new ThumbHandler();
 
$t->setSrcImg("img/test.jpg");
 
// 指定字体文件地址
$t->setMaskFont("c:/winnt/fonts/arial.ttf");
$t->setMaskFontSize(20);
$t->setMaskFontColor("#ffff00");
$t->setMaskWord("test3333333");
$t->setDstImgBorder(99,"#dddddd");
$t->createImg(50);
 
?>


<?php
require_once('lib/thumb.class.php');
 
$t = new ThumbHandler();
 
$t->setSrcImg("img/test.jpg");
$t->setMaskOffsetX(55);
$t->setMaskOffsetY(55);
$t->setMaskPosition(1);
//$t->setMaskPosition(2);
//$t->setMaskPosition(3);
//$t->setMaskPosition(4);
$t->setMaskFontColor("#ffff00");
$t->setMaskWord("test");
 
// 指定固定宽高
$t->createImg(50);
?>



<?php
require_once('lib/thumb.class.php');
 
$t = new ThumbHandler();
 
$t->setSrcImg("img/test.jpg");
$t->setMaskFont("c:/winnt/fonts/simyou.ttf");
$t->setMaskFontSize(20);
$t->setMaskFontColor("#ffffff");
$t->setMaskTxtPct(20);
$t->setDstImgBorder(10,"#dddddd");
$text = "中文";
$str = mb_convert_encoding($text, "UTF-8", "gb2312");
$t->setMaskWord($str);
$t->setMaskWord("test");
 
// 指定固定宽高
$t->createImg(50);
?>



<?php
//只压缩图片
require_once('thumb.class.php');
 
$t = new ThumbHandler();
 
$t->setSrcImg("img/testpic.jpg");
$t->setDstImg("tmp/new_test2.jpg");
 
//指定缩放比例,如果只留一个参数则为缩放比例，填写100为%100原大小,图片压缩质量参数见类中定义的变量img_create_quality和img_display_quality
$t->createImg(100);
?>

<?php
//只压缩图片
require_once('thumb.class.php');
 
$t = new ThumbHandler();
 
$t->setSrcImg("img/123.jpg");//输入图片路径
$t->setDstImg("tmp/new_test.jpg");//输出图片路径
 
$t->setDstImgBorder(10,"#ffffff");//边框补白
// 指定缩放比例
$t->createImg(200,200);
?>