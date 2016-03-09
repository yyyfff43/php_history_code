<?php
header("Content-type: text/html; charset=utf-8");
//解决代码转换unicode to utf8
require "excel_class.php";

Read_Excel_File("",$return);

for ($i=0;$i<count($return[Sheet1]);$i++)
{
	for ($j=0;$j<count($return[Sheet1][$i]);$j++)
	{
		echo mb_convert_encoding($return[Sheet1][$i][1],'UTF-8', 'UTF-16LE').",";

//		if($return[Sheet1][$i][1]==utf8ToUnicode("收款人1")){
//			echo "sdsadsdasdasda";
//		}
	}
	echo "<br>";
}



?>