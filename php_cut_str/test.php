<?php
header("Content-type: text/html; charset=utf-8");
/*
cut_str(字符串, 开始长度,截取长度);
编码默认为 utf-8
*/
 
function cut_str($string, $beginIndex, $length){
    if(strlen($string) < $length){
        return substr($string, $beginIndex);
    }
 
    $char = ord($string[$beginIndex + $length - 1]);
    if($char >= 224 && $char <= 239){
        $str = substr($string, $beginIndex, $length - 1);
        return $str;
    }
 
    $char = ord($string[$beginIndex + $length - 2]);
    if($char >= 224 && $char <= 239){
        $str = substr($string, $beginIndex, $length - 2);
        return $str;
    }
 
    return substr($string, $beginIndex, $length);
}
 
$str = "abcd需要截取的字符串";
echo cut_str($str, 0,12); 
?>