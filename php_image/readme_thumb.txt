ͼƬ������

������
<?php
require_once('lib/thumb.class.php');
 
$t = new ThumbHandler();
 
$t->setSrcImg("img/test.jpg");
$t->setDstImg("tmp/new_test.jpg");
$t->setMaskImg("img/test.gif");
$t->setMaskPosition(1);
$t->setMaskImgPct(80);
$t->setDstImgBorder(4,"#dddddd");
 
// ָ�����ű���
$t->createImg(300,200);
?>





<?php
require_once('lib/thumb.class.php');
 
$t = new ThumbHandler();
 
// ����ʹ��
$t->setSrcImg("img/test.jpg");
$t->setMaskWord("test");
$t->setDstImgBorder(10,"#dddddd");
 
// ָ�����ű���
$t->createImg(50);
?>



<?php
equire_once('lib/thumb.class.php');
 
$t = new ThumbHandler();
 
// ����ʹ��
$t->setSrcImg("img/test.jpg");
$t->setMaskWord("test");
 
// ָ���̶����
$t->createImg(200,200);
?>



<?php
require_once('lib/thumb.class.php');
 
$t = new ThumbHandler();
 
$t->setSrcImg("img/test.jpg");
$t->setDstImg("tmp/new_test.jpg");
$t->setMaskWord("test");
 
// ָ���̶����
$t->createImg(200,200);
?>



<?php
require_once('lib/thumb.class.php');
 
$t = new ThumbHandler();
 
$t->setSrcImg("img/test.jpg");
 
// ָ�������ļ���ַ
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
 
// ָ���̶����
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
$text = "����";
$str = mb_convert_encoding($text, "UTF-8", "gb2312");
$t->setMaskWord($str);
$t->setMaskWord("test");
 
// ָ���̶����
$t->createImg(50);
?>



<?php
//ֻѹ��ͼƬ
require_once('thumb.class.php');
 
$t = new ThumbHandler();
 
$t->setSrcImg("img/testpic.jpg");
$t->setDstImg("tmp/new_test2.jpg");
 
//ָ�����ű���,���ֻ��һ��������Ϊ���ű�������д100Ϊ%100ԭ��С,ͼƬѹ���������������ж���ı���img_create_quality��img_display_quality
$t->createImg(100);
?>

<?php
//ֻѹ��ͼƬ
require_once('thumb.class.php');
 
$t = new ThumbHandler();
 
$t->setSrcImg("img/123.jpg");//����ͼƬ·��
$t->setDstImg("tmp/new_test.jpg");//���ͼƬ·��
 
$t->setDstImgBorder(10,"#ffffff");//�߿򲹰�
// ָ�����ű���
$t->createImg(200,200);
?>