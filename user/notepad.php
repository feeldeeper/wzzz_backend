<?php
header("Content-type: text/html; charset=utf-8");
include("inc/conn.php");
session_set_cookie_params(SESSION_LIFE_TIME);
session_start();
$uid = $_SESSION['uid'];
empty($uid) && exit();
empty($_POST["data"]) && exit();

$data = str_replace("'","''",$_POST["data"]);
$sql="update user set memo='$data' where id=".$uid;
$row = $database->query($sql);
?>