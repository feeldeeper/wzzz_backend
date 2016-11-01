<?php
	ini_set('date.timezone','Asia/Shanghai'); //设置时区
	error_reporting(E_ALL ^ E_DEPRECATED);

	include_once("sql.class.php");

    $database = new MySql([
    'database_type' => 'mysql',
    'database_name' => 'goboes',
    'server' => 'localhost',
    'username' => 'root',
    'password' => 'admin',
    'charset' => 'utf8'
    ]);
	
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

		$post = addslashes($post);    // 进行magic_quotes_gpc没有打开的情况对提交数据的过滤

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

