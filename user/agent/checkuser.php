<?php 
header("Content-type: text/html; charset=utf-8");
include("../inc/function.php");
include("../inc/conn.php");
include("../inc/sql.class.php");
$DB = new MySql($conn);
$user = $DB->Select("select * from user where username='".$_POST['value']."'");
if(count($user)>0)
	echo "已经被别人注册了！";
else
	echo "success";
?>