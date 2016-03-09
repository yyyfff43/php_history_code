<?php
/**
 * 测试smarty
 */

include 'smarty_inc.php';

$smarty->assign('title','smarty变量小测试');

$arr = array('电脑类书籍','name'=>'PHP基础入门','unit_price'=>array('price'=>'$69','unit'=>'每本'));

$smarty->assign('content',$arr);

$list = array(
      array('name'=>'html语言入门','prcie'=>'35'),
	  array('name'=>'java语言入门','prcie'=>'56'),
	  array('name'=>'asp语言入门','prcie'=>'68'),
	  array('name'=>'javascript语言入门','prcie'=>'30'),
	  array('name'=>'C语言入门','prcie'=>'98'),
);

$smarty->assign('other_books_list',$list);


$paylist = array(
  array('way'=> '方法一','type'=>array('支付宝','快钱','财付通','银联')),
  array( 'way'=>'方法二','type'=>array('工行','农行','交行'))
);

$smarty->assign('pay_list',$paylist);

$smarty->display('test_array.html');

?>