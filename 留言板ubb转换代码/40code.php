<?php

/*
 * www.php100.com ��Ƶ�̳�
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

function get_ubb($str) {

	$str = preg_replace("/(\[)em(.*?)(\])/i", "<img src=\"emot/em\\2.gif\" />", $str);
	//����UBB
	$str = preg_replace("/(\[url\])(.*)(\[\/url\])/i", "<a href=\\2 target=\"new\">\\2</a>", $str);
	//QQ����UBB
	$str = preg_replace("/\[qq\]([0-9]*)\[\/qq\]/i", "<a target=\"_blank\" href=\"tencent://message/?uin=\${1}&amp;site=www.php100.com&amp;menu=yes\"><img src=\"http://wpa.qq.com/pa?p=1:\${1}:8\" alt=\"QQ\${1}\" height=\"16\" border=\"0\" align=\"top\" /></a>", $str);

	return $str;
}

if($_POST['sub']){
	echo get_ubb($_POST[message]);
}


?>
<script>
function inserttag(topen,tclose){
var themess = document.getElementById('con');//�༭����
themess.focus();
if (document.selection) {//�����ie�����
   var theSelection = document.selection.createRange().text;//��ȡѡ������
   //alert(theSelection);
   if(theSelection){
    document.selection.createRange().text = theSelection = topen+theSelection+tclose;//�滻
   }else{
    document.selection.createRange().text = topen+tclose;
   }
   theSelection='';

}else{//���������

   var scrollPos = themess.scrollTop;
   var selLength = themess.textLength;
   var selStart = themess.selectionStart;//ѡ����ʼ��������δѡ��Ϊ0
   var selEnd = themess.selectionEnd;//ѡ���յ������
   if (selEnd <= 2)
   selEnd = selLength;

   var s1 = (themess.value).substring(0,selStart);//��ȡ��ʼ��ǰ�����ַ�
   var s2 = (themess.value).substring(selStart, selEnd)//��ȡѡ�񲿷��ַ�
   var s3 = (themess.value).substring(selEnd, selLength);//��ȡ�յ�󲿷��ַ�

   themess.value = s1 + topen + s2 + tclose + s3;//�滻

   themess.focus();
   themess.selectionStart = newStart;
   themess.selectionEnd = newStart;
   themess.scrollTop = scrollPos;
   return;
}
}
</script>
<hr/>
<font size=2>
<img src="emot/em_01.gif" onclick='inserttag("[em_01","]");' />
<img src="emot/em_02.gif" onclick='inserttag("[em_02","]");' />
<img src="emot/em_03.gif" onclick='inserttag("[em_03","]");' />
<img src="emot/em_04.gif" onclick='inserttag("[em_04","]");' />
<img src="emot/em_05.gif" onclick='inserttag("[em_05","]");' />
<img src="emot/em_06.gif" onclick='inserttag("[em_06","]");' />
<img src="emot/em_07.gif" onclick='inserttag("[em_07","]");' />
<img src="emot/em_08.gif" onclick='inserttag("[em_08","]");' />
<a href="javascript:void(0);" onclick='inserttag("[b]","[/b]");'>�Ӵ�</a>
<a href="javascript:void(0);" onclick='inserttag("[qq]","[/qq]");'>QQ��</a>
<a href="javascript:void(0);" onclick='inserttag("[url]","[/url]");'>������</a>
<br>

  <form action="" method="post">
  <textarea name="message" id="con" cols="70%" rows="10"></textarea>

  <input type="submit" name="sub" value="�ύ"/>


  </form>




