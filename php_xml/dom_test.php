<?php
header("Content-type: text/html; charset=utf-8");

/*
DOM 解析器是基于树的解析器。
请看下面的 XML 文档片段：
<from>John</from>

    XML DOM 把 XML 视为一个树形结构：

     Level 1: XML 文档
     Level 2: 根元素: <from>
     Level 3: 文本元素: "John"
*/
//
$xmlDoc = new DOMDocument();
$xmlDoc->load("test.xml");

print $xmlDoc->saveXML();
/*
上面的例子创建了一个 DOMDocument-Object，并把 "note.xml" 中的 XML 载入这个文档对象中。

saveXML() 函数把内部 XML 文档放入一个字符串，这样我们就可以输出它
*/
echo "<HR>";


$xmlDoc = new DOMDocument();
$xmlDoc->load("test.xml");

$x = $xmlDoc->documentElement;
foreach ($x->childNodes AS $item)
  {
  print $item->nodeName . " = " . $item->nodeValue . "<br />";
  }

/*
在上面的例子中，您看到了每个元素之间存在空的文本节点。

当 XML 生成时，它通常会在节点之间包含空白。XML DOM 解析器把它们当作普通的元素，如果您不注意它们，有时会产生问题。
*/

?>