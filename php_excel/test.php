<?php
header("Content-type: text/html; charset=utf-8");
require "excel_class.php";

Read_Excel_File("cc.xls",$return);

for ($i=0;$i<count($return[Sheet1]);$i++)
{
    for ($j=0;$j<count($return[Sheet1][$i]);$j++)
     {
        echo $return[Sheet1][$i][$j]."|";
     }
    echo "<br>";
}



?>