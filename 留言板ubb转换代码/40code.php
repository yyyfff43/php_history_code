<?php

/*
 * www.php100.com 视频教程
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

function get_ubb($str) {

	$str = preg_replace("/(\[)em(.*?)(\])/i", "<img src=\"emot/em\\2.gif\" />", $str);
	//链接UBB
	$str = preg_replace("/(\[url\])(.*)(\[\/url\])/i", "<a href=\\2 target=\"new\">\\2</a>", $str);
	//QQ号码UBB
	$str = preg_replace("/\[qq\]([0-9]*)\[\/qq\]/i", "<a target=\"_blank\" href=\"tencent://message/?uin=\${1}&amp;site=www.php100.com&amp;menu=yes\"><img src=\"http://wpa.qq.com/pa?p=1:\${1}:8\" alt=\"QQ\${1}\" height=\"16\" border=\"0\" align=\"top\" /></a>", $str);

	return $str;
}

if($_POST['sub']){
	echo get_ubb($_POST[message]);
}


?>
<script>
function inserttag(topen,tclose){
var themess = document.getElementById('con');//编辑对象
themess.focus();
if (document.selection) {//如果是ie浏览器
   var theSelection = document.selection.createRange().text;//获取选区文字
   //alert(theSelection);
   if(theSelection){
    document.selection.createRange().text = theSelection = topen+theSelection+tclose;//替换
   }else{
    document.selection.createRange().text = topen+tclose;
   }
   theSelection='';

}else{//其他浏览器

   var scrollPos = themess.scrollTop;
   var selLength = themess.textLength;
   var selStart = themess.selectionStart;//选区起始点索引，未选择为0
   var selEnd = themess.selectionEnd;//选区终点点索引
   if (selEnd <= 2)
   selEnd = selLength;

   var s1 = (themess.value).substring(0,selStart);//截取起始点前部分字符
   var s2 = (themess.value).substring(selStart, selEnd)//截取选择部分字符
   var s3 = (themess.value).substring(selEnd, selLength);//截取终点后部分字符

   themess.value = s1 + topen + s2 + tclose + s3;//替换

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
<a href="javascript:void(0);" onclick='inserttag("[b]","[/b]");'>加粗</a>
<a href="javascript:void(0);" onclick='inserttag("[qq]","[/qq]");'>QQ号</a>
<a href="javascript:void(0);" onclick='inserttag("[url]","[/url]");'>超链接</a>
<br>

  <form action="" method="post">
  <textarea name="message" id="con" cols="70%" rows="10"></textarea>

  <input type="submit" name="sub" value="提交"/>


  </form>




