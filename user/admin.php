<?php 
header("Content-type: text/html; charset=utf-8");
include("inc/conn.php");
if(isset($_GET['action']) && $_GET['action']!="")
{
	$action=$_GET['action'];
	if($action=="del")
	{
		$id = $_GET["id"];
		$sql="delete from admin where id = $id";
		mysql_query($sql,$conn);
		echo "<script>alert('已删除!');</script>";
	}
	elseif($action=="add")
	{
		$u = $_POST["username"];
		$t = $_POST["tab"];
		$p = md5($_POST["psw"]);
		if(!existuser($u,$conn))
		{
			$sql="insert into admin(username,password,phone) values('$u','$p','$t')";
			mysql_query($sql,$conn);
		}else{
			echo "<script>alert('用户名已存在!');history.back(-1);</script>";
		}
	} 
	
	 
	
}  

function existuser($username,$conn)
{
	$sql="select * from admin where username='$username'";
	$query = mysql_query($sql,$conn);
	if($row = mysql_fetch_array($query))
	{
		return true;
	}
	
	 
	
	return false;
	
	
} 

function getalllast($conn)
{
	$text="";
	$sql="select * from `admin`";
	$query = mysql_query($sql,$conn);
	while($row = mysql_fetch_array($query))
	{ 
		$phone=$row["phone"];
		if($phone=="0")
		{
			$phone="全部台号";
		}
		if($row["id"]=="1")
			$text.="<tr style='color:red;'><td>".$row['id']."</td><td>".$row['username']."</td><td>".$phone."</td><td><a href='adminedit.php?id=".$row['id']."'>编辑</a> &nbsp;&nbsp;&nbsp;</td></td></tr>";
		else
			$text.="<tr style='color:red;'><td>".$row['id']."</td><td>".$row['username']."</td><td>".$phone."</td><td><a href='adminedit.php?id=".$row['id']."'>编辑</a> &nbsp;&nbsp;&nbsp;<a href='?action=del&id=".$row['id']."'>删除</a></td></td></tr>"; 
	}
	 
	
	return $text;
	
	
} 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0036)http://ag.88gobo.net/default/online/ -->
<html  xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title></title>
<script src="http://libs.baidu.com/jquery/1.9.1/jquery.min.js"></script>
</head>
<body> 
<div><h1 style="padding-left:250px;">添加管理员</h1>
</div>
<div style="padding-left:150px;padding-top:10px;">
<form action="admin.php?action=add" method="post">
用户名: <input type="text" name="username" style="width:200px" /><br/>
密  &nbsp;码: <input type="text" name="psw" style="width:200px" /><br/>
控制台号: <input type="text" name="tab" value="0" style="width:200px" />(0代表所有台号,多个台号用英文逗号,隔开)<br/>
<input type="submit" value="添加"><br/><br/>
</form>
<div id="q">
 <table border="1" cellpadding="0" cellspacing="0">
 <tr><th>ID</th><th>用户名</th><th>控制台号</th><th>操作</th></tr>
<?php echo getalllast($conn);?>
 </table>
 </div>
</div>
</body></html>