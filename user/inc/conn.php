<?php
	ini_set('date.timezone','Asia/Shanghai'); //����ʱ��
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
	
	function post_check($post) // ���ύ�ı༭���ݽ��д���
	{
		
		$post = str_replace("��",",",$post);
		$post = str_replace("��",".",$post);
		$post = str_replace("��",":",$post);
		$post = str_replace("��","'",$post);
		$post = str_replace("��",";",$post);
		$post = str_replace("��","?",$post);
		
		$post = str_replace("��",",",$post);
		$post = str_replace("��",".",$post);
		$post = str_replace("��",";",$post);
		$post = str_replace("��",":",$post);
		
		$post = str_replace("<p>","",$post);
		$post = str_replace("<P>","",$post);
		$post = str_replace("</p>","",$post);
		$post = str_replace("</P>","",$post);
			
		if (!get_magic_quotes_gpc())    // �ж�magic_quotes_gpc�Ƿ�Ϊ��
		{
			$post = addslashes($post);    // ����magic_quotes_gpcû�д򿪵�������ύ���ݵĹ���
		}

		$post= htmlspecialchars($post);    // html���ת��
	 
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
