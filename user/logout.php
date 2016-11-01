<?php  
	header("content-type:text/html; charset=utf-8");
	include 'inc/conn.php';
	session_set_cookie_params(SESSION_LIFE_TIME);
	session_start();
	$today = date("Y-m-d H:i:s",time()-1900);
	$query = "update user set lastlogintime='$today' where lastlogintime>'$today' and id=".$_SESSION['uid'];
	$database->query($query);

	if(session_is_registered("isadmin") && session_is_registered("adminname"))
	{
		session_unregister("isadmin");
		session_unregister("adminname");
		session_unregister("admintab");
		session_unregister("uid");

	}
	
	echo "<script>window.location='login.php';</script>";


?>