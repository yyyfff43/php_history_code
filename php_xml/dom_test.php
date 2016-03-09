<?php
header("Content-type: text/html; charset=utf-8");

/*
DOM �������ǻ������Ľ�������
�뿴����� XML �ĵ�Ƭ�Σ�
<from>John</from>

    XML DOM �� XML ��Ϊһ�����νṹ��

     Level 1: XML �ĵ�
     Level 2: ��Ԫ��: <from>
     Level 3: �ı�Ԫ��: "John"
*/
//
$xmlDoc = new DOMDocument();
$xmlDoc->load("test.xml");

print $xmlDoc->saveXML();
/*
��������Ӵ�����һ�� DOMDocument-Object������ "note.xml" �е� XML ��������ĵ������С�

saveXML() �������ڲ� XML �ĵ�����һ���ַ������������ǾͿ��������
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
������������У���������ÿ��Ԫ��֮����ڿյ��ı��ڵ㡣

�� XML ����ʱ����ͨ�����ڽڵ�֮������հס�XML DOM �����������ǵ�����ͨ��Ԫ�أ��������ע�����ǣ���ʱ��������⡣
*/

?>