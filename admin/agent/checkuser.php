<?php
header("Content-type: text/html; charset=utf-8");
include("../../user/inc/function.php");
include("../../user/inc/conn.php");
$user = $database->query("select * from user where username='" . $_POST['value'] . "'")->fetchAll();
if ($user)
    echo "已经被别人注册了！";
else
    echo "success";
?>