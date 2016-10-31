<?php 
header("Content-type: text/html; charset=utf-8");
include("inc/conn.php");
if(isset($_POST['username']) && $_POST['username']!="")
{
	$username=$_POST['username'];
	$money=intval($_POST['money']); 
	if($username!="")
	{  
			$sql="update user set username='$username',money=money+$money,currentmoney=currentmoney+$money where id=".$_POST['id'];
			//echo $sql;exit();
			mysql_query($sql,$conn);
			echo "<script>alert('已提交!');window.location='user.php'</script>"; 
	}
	else
		echo "<script>alert('用户名为空!');history.back(-1);</script>";
	
	 
	
} 

if(isset($_GET['id']) && $_GET['id']!="")
{
	$id=$_GET['id'];
	$sql="select * from user where id=".$id;
	$result=mysql_query($sql,$conn);
	if($row = mysql_fetch_array($result))
	{
		$name=$row['username'];
		$money=$row['money'];
		$cmoney=$row['currentmoney'];
	}
 
}


function existuser($username,$conn)
{
	$sql="select * from user where username='$username'";
	$query = mysql_query($sql,$conn);
	if($row = mysql_fetch_array($query))
	{
		return true;
	}
	
	 
	
	return false;
	
	
} 

 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0036)http://ag.88gobo.net/default/online/ -->
<html  xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title></title>

</head>
<body>

<div><h1 style="padding-left:250px;">修改充值</h1>
</div>
<div style="padding-left:150px;padding-top:10px;">
<form action="useredit.php" method="post">
<input type='hidden' value="<?=$id?>" name="id">
ID: <?=$id?><br/>
用户名: <?=$name?><br/>
累计充值金额: <?=$money?><br/>
剩余金额: <?=$cmoney?><br/>
新用户名:<input type="text" name="username" value="<?=$name?>" style="width:200px" /><br/> 
充值金额:<input type="text" name="money" value='0' style="width:200px" /><br/> 
<input type="submit" value="添加"><br/><br/>
</form>
 
</div>
</body></html>