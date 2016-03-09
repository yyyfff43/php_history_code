<?php
/**
 * ╠ьсе╡╘©м www.biuuu.com
 * */
$page = intval ( isset ( $_GET ['page'] ) ? $_GET ['page'] : 1 );
$url = 'http://' . $_SERVER ['HTTP_HOST'] . $_SERVER ['PHP_SELF'] . '?page=';
$totalNum = 18;
include 'PagerDemo.html';
?>