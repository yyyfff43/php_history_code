<?php
/**
 * 测试smarty
 */

include 'smarty_config.php';

$smarty->assign('title','smarty小测试');

$smarty->assign('content','smarty学习第一课');

$smarty->display('test.tpl');

?>