<?php 
header("Content-type: text/html; charset=utf-8");
include("inc/conn.php");
if(!empty($_POST['username']))
{
	$username=$_POST['username'];
	$psw=$_POST['psw'];
	if($psw!="")
	{
		$psw=md5($psw);
		$money=intval($_POST['money']); 
		if(!existuser($username,$database))
		{
			$sql="insert into user(username,money,password,currentmoney) values('$username',$money,'$psw',$money)";
			$database->query($sql);
			echo "<script>alert('已提交!');</script>";
		}
		else
		{
			echo "<script>alert('用户名已存在!');history.back(-1);</script>";
		}
	}
	else
		echo "<script>alert('密码为空!');history.back(-1);</script>";
	
	 
	
} 

if(isset($_GET['action']) && $_GET['action']=="del")
{
	$id=$_GET['id'];
	$sql="delete from user where id=".$id;
	$database->query($sql);
	echo "<script>alert('已删除!');</script>";
}


function existuser($username,$database)
{
	$sql="select * from user where username='$username'";
	$row = $database->query($sql)->fetch();
	if($row)
	{
		return true;
	}
	
	 
	
	return false;
	
	
} 

function getalllast($database)
{
	$text="";
	$sql="select * from `user`";
	$result = $database->query($sql)->fetchAll();
	foreach($result as $row)
	{ 
		if($row['tableid']=="0")
			$table="离台";
		else
		{
			$table=$row['tableid']."号台";
		}
		$text.="<tr style='color:red;'><td>".$row['id']."</td><td>".$row['username']."</td><td>".$row['money']."</td><td>".$row['currentmoney']."</td><td>".$table."</td><td><a href='?action=del&id=".$row['id']."'>删除</a></td><td><a href='useredit.php?id=".$row['id']."'>修改充值</a></td></td></tr>"; 
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
<script>

var obj; 
    $(document).ready(function() {  
		setInterval(duoduo,3000);
	});
	
	function duoduo(){
        $.ajax({
            type: 'post', //可选get
            url: 'tablecon.php', //这里是接收数据的程序
            data: '', //传给PHP的数据，多个参数用&连接
            dataType: 'html', //服务器返回的数据类型 可选XML ,Json jsonp script html text等
            success: function(msg) {
                //这里是ajax提交成功后，程序返回的数据处理函数。msg是返回的数据，数据类型在dataType参数里定义！
               // obj = eval(msg);
				//var p = obj["0"];
				
				
				document.getElementById("q").innerHTML = msg;
				
                //$("#duoduo").innerHTML = msg;
            },
            error: function() {
                //alert('对不起失败了');
            }
        })
    }
	
	</script>
<div><h1 style="padding-left:250px;">添加用户</h1>
</div>
<div style="padding-left:150px;padding-top:10px;">
<form action="user.php?action=add" method="post">
用户名: <input type="text" name="username" style="width:200px" /><br/>
密  &nbsp;码: <input type="text" name="psw" style="width:200px" /><br/>
金  &nbsp;钱: <input type="text" name="money" style="width:200px" value="10000" /><br/><br/>
<input type="submit" value="添加"><br/><br/>
</form>
<div id="q">
 <table border="1" cellpadding="0" cellspacing="0">
 <tr><th>ID</th><th>用户名</th><th>充值金额</th><th>剩余金额</th><th>状态</th><th></th><th>操作</th></tr>
<?php echo getalllast($database);?>
 </table>
 </div>
</div>
</body></html>