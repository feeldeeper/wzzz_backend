<?php
header("Content-type: text/html; charset=utf-8");
include("inc/conn.php");
session_set_cookie_params(SESSION_LIFE_TIME);
session_start();
$uid = $_SESSION['uid'];
$data=$_POST["data"];
if(isset($uid) && $uid!="" && $uid!="0" && isset($data))
{
}
else{ exit();}
$data = str_replace("'","''",$data);
$sql="update user set memo='$data' where id=".$uid;
mysql_query($sql,$conn);
?>