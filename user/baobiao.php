<?php 
header("Content-type: text/html; charset=utf-8");
include("inc/conn.php");

function getalllast($conn)
{
	$text="";
	$sql="select * from `user`";
	$query = mysql_query($sql,$conn);
	while($row = mysql_fetch_array($query))
	{ 
		$text.="<tr style='color:red;'><td>".$row['id']."</td><td>".$row['username']."</td><td>".$row['money']."</td><td>".$row['currentmoney']."</td><td><a href='bao.php?id=".$row['id']."'>浏览报表</a></td></tr>"; 
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

<div><h1 style="padding-left:300px;">用户报表</h1>
</div>

<div id="q" style="padding-left:150px;">
 <table border="1" cellpadding="0" cellspacing="0">
 <tr><th>ID</th><th>用户名</th><th>充值金额</th><th>剩余金额</th><th>操作</th></tr>
<?php echo getalllast($conn);?>
 </table>
 </div>
</body></html>