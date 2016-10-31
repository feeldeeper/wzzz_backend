<?php  
	header("content-type:text/html; charset=utf-8");
	include '../user/inc/conn.php';
	session_set_cookie_params(SESSION_LIFE_TIME);
	session_start();
	$today = date("Y-m-d H:i:s",time()-1900);
	$query = "update user set lastlogintime='$today' where lastlogintime>'$today' and id=".$_SESSION['uid'];
	mysql_query($query,$conn);

	if(session_is_registered("isadmin") && session_is_registered("adminname"))
	{ 
		session_unregister("isadmin");

		session_unregister("adminname");

		unset($_SESSION['isadmin']);

		unset($_SESSION['adminname']); 

	}
	
	echo "<script>window.location='login.php';</script>";


?>