<?php
header("Content-type: text/html; charset=utf-8");
require "excel.php";

$page=$_REQUEST["Page"];
if($page==""){
   $page = 1;
}
$search="";
$start=30*($page-1);
$sql="select * from flash ".$search." order by id desc limit $start,30";
$rows=mysql_query_result($sql);


$doc = array(
        0 => array ('id','游戏名','总点击','昨日点击','周点击','月点击')
);



foreach($rows as $rs){
   $doc[] = array($rs['id'],$rs['flashname'],$rs['hits_all'],$rs['hits_yd'],$rs['hits_w'],$rs['hits_m']);
}


   $xls = new Excel;
   $xls->addArray ($doc);
   $xls->generateXML ("hits".date("Y-m-d"));
   exit();
}



?>