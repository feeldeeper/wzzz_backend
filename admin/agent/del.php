<?php 
header("Content-type: text/html; charset=utf-8");
include("../../user/inc/function.php");
include("../../user/inc/conn.php");
include("../../user/inc/sql.class.php");
$DB = new MySql($conn);
session_set_cookie_params(SESSION_LIFE_TIME);
session_start();
$uid = $_SESSION['uid'];
if(isset($uid) && $uid!="" && $uid!="0")
{
}
else{ echo "<script>alert('请先登录!');window.location='../login.php';</script>";exit();}
$id=1;if(isset($_GET['id']) && $_GET['id']!="") $id = $_GET['id'];

$user = $DB->Select("select * from user where pid = $id");
if(count($user)>0)
{
	echo "<script>alert('删除此用户必须先删除此用户的下线代理和会员!');history.back(-1);</script>";exit();
}
$DB->Query("delete from user where id=$id");
	
echo "<script>window.location='/admin/agent/index.php?id=$uid';</script>";


?>