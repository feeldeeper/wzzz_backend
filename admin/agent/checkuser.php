<?php 
header("Content-type: text/html; charset=utf-8");
include("../../user/inc/function.php");
include("../../user/inc/conn.php");
include("../../user/inc/sql.class.php");
$DB = new MySql($conn);
$user = $database->query("select * from user where username='".$_POST['value']."'");
if(count($user)>0)
	echo "已经被别人注册了！";
else
	echo "success";
?>