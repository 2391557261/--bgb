<?php
ini_set('date.timezone','Asia/Shanghai');
$conn = @mysql_connect("localhost","root","root");
if (!$conn){die(mysql_error());}
mysql_select_db("wsdc",$conn);
mysql_set_charset("utf8");
error_reporting(E_ALL & ~E_NOTICE);
?>