<?php
include 'smarty_inc.php';

$smarty->assign('test','aaaaaa');

// put this in your application
function protect_email($tpl_output, &$smarty)
{
 $tpl_output =
 preg_replace('!(\S+)@([a-zA-Z0-9\.\-]+\.([a-zA-Z]{2,3}|[0-9]{1,3}))!',
 '$1%40$2', $tpl_output);
 return $tpl_output;
}

// register the outputfilter
$smarty->register_outputfilter("protect_email");

$smarty->display('test.html');


?>