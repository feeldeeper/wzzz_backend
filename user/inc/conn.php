<?php
	ini_set('date.timezone','Asia/Shanghai'); //设置时区
	error_reporting(E_ALL ^ E_DEPRECATED);
	while(!isset($conn))
	{
		$conn = mysql_pconnect("localhost","root","admin");
	}
	mysql_select_db("goboes",$conn);
	mysql_query("set character set utf8");
	mysql_query("set names utf8");
	
	$session_time = 4 * 60 * 60;
	
	define('SITE_ROOT',"");
	define('SESSION_LIFE_TIME',$session_time);
	
	function post_check($post) // 对提交的编辑内容进行处理
	{
		
		$post = str_replace("，",",",$post);
		$post = str_replace("．",".",$post);
		$post = str_replace("：",":",$post);
		$post = str_replace("＇","'",$post);
		$post = str_replace("；",";",$post);
		$post = str_replace("？","?",$post);
		
		$post = str_replace("，",",",$post);
		$post = str_replace("。",".",$post);
		$post = str_replace("；",";",$post);
		$post = str_replace("：",":",$post);
		
		$post = str_replace("<p>","",$post);
		$post = str_replace("<P>","",$post);
		$post = str_replace("</p>","",$post);
		$post = str_replace("</P>","",$post);
			
		if (!get_magic_quotes_gpc())    // 判断magic_quotes_gpc是否为打开
		{
			$post = addslashes($post);    // 进行magic_quotes_gpc没有打开的情况对提交数据的过滤
		}

		$post= htmlspecialchars($post);    // html标记转换
	 
	   return $post;
	}
	
	function session_is_registered($key){ 
		return isset($_SESSION[$key]); 
	} 
	function session_unregister($key){ 
		unset($_SESSION[$key]); 
	} 
	function session_register(){ 
		$args = func_get_args(); 
		foreach ($args as $key){ 
			$_SESSION[$key]=$GLOBALS[$key]; 
		} 
	}
?>
