<?php
header("Content-type: text/html; charset=utf-8");

/*
SimpleXML �� PHP 5 �е������ԡ����˽� XML �ĵ� layout ������£�����һ��ȡ��Ԫ�����Ժ��ı��ı���;����

������Ҫ�������飺

   1. ���� XML �ļ�
   2. ȡ�õ�һ��Ԫ�ص�����
   3. ʹ�� children() ����������ÿ���ӽڵ��ϴ�����ѭ��
   4. ���ÿ���ӽڵ��Ԫ�����ƺ�����

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