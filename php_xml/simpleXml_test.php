<?php
header("Content-type: text/html; charset=utf-8");

/*
SimpleXML 是 PHP 5 中的新特性。在了解 XML 文档 layout 的情况下，它是一种取得元素属性和文本的便利途径。

这是需要做的事情：

   1. 加载 XML 文件
   2. 取得第一个元素的名称
   3. 使用 children() 函数创建在每个子节点上触发的循环
   4. 输出每个子节点的元素名称和数据

*/
$xml = simplexml_load_file("test2.xml");

echo $xml->getName() . "<br />";

foreach($xml->children() as $child){

	if($child->children()){
        echo $child->getName() . ": " . "<br />";
		foreach($child->children() as $subchild){
               echo "&nbsp;&nbsp;".$subchild->getName() . ": " . $subchild . "<br />";
		}
	}else{
        echo $child->getName() . ": " . $child . "<br />";
	}
}

?>