<?php
header("Content-type: text/html; charset=utf-8");
include("inc/function.php");
include("inc/conn.php");
session_set_cookie_params(SESSION_LIFE_TIME);
session_start();
$uid = $_SESSION['uid'];
if(isset($uid) && $uid!="" && $uid!="0")
{
}
else{ echo "<script>alert('请先登录!');window.location='login.php';</script>";exit();}
$sql="select * from user where id=".$uid;
$query=mysql_query($sql,$conn);
if($row=mysql_fetch_array($query))
{
	$username=$row['username'];
	$nickname=$row['nickName'];
	$tel=$row['tel'];
	$ip=$row['ip'];
	$lastlogintime=$row['lastlogintime'];
	$logintimes=$row['logintimes'];
	$currentmoney=$row['currentmoney'];
	$memo=$row['memo'];
}
else
{
	echo "<script>alert('请先登录!');window.location='login.php';</script>";
	exit();
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link href="/user/css/system.css" rel="stylesheet" type="text/css">
<script type="text/javaScript" src="/user/js/jquery.js"></script>
<script type="text/javaScript" src="/user/js/common.js"></script>
<script>
$(function() {
	/*$.get('/myself/updatefinance', function(data) {
	  $('#finance').html(data);
	});*/
});
</script>
</head>
<style type="text/css"> 
<!--
* { padding:0; margin:0;}
body { padding:0; font-size:12px; color: black;	line-height: 150%; background-color: white;}
a:link, a:visited { text-decoration:none; color:#5097D8;}
a:hover, a:active { text-decoration:underline;}
a.orange:link, a.orange:visited { text-decoration:none;	color:orange;}
a.orange:hover, a.orange:active { text-decoration:underline;}
ul li {	list-style:none; margin:5px 0;}
img { border-width:0;}
.font_arial { font-family:Arial, Helvetica;}
.bdr{border:1px solid #b4d3ef; clear:both;}
#admin_main{ padding:0 10px;}
caption{*margin-top:10px; line-height:25px; height:25px; text-align:left; padding-left:14px;}
caption,#admin_main h3 { border:1px solid #99d3fb; border-bottom-width:0; color:#077ac7; background:url(/skin/images/bg_table.jpg) repeat-x 0 0; height:27px; line-height:27px; margin:10px auto 0; font-size:12px; font-family:"宋体"}
caption{border-bottom:1px solid #99d3fb !important; border:1px solid #99d3fb; border-bottom-width:0; font-weight:bold; }
caption span{float:right; padding-right:10px;}
table{background:#99d3fb; margin-top:-5px !important; margin-top:10px; width:100%;}
td{background:#fff;}
th,td{line-height:24px; text-align:center; color:#5097D8;}
th{ font-size:12px; background: url(/skin/images/bg_table.jpg) repeat-x 0 -26px; line-height:22px; height:24px !important; height:22px; font-weight:bold;}
#admin_main_2_1 {width:48%; float:left;}
#admin_main_2_1 p { border-bottom:1px dotted #b4d3ef; margin:10px auto;	text-align:left; padding:0 10px 10px; color:#5097D8;	line-height:22px;}
#admin_main_2_2 { float:left; margin-left:1.5%; width:48%;}
#admin_main_2_2 li,#admin_main_2_1 li { background:#fff url(/skin/images/list_bg.gif) no-repeat 5px 8px;}
#admin_main_2_2 { float:left; margin-left:1.5%; width:48%;}
.ad {text-align:center; margin:10px auto;}
.c_orange {color:orange;}
.sfe-section,.hdg{margin:0;padding:0}
-->
</style>
<body>
<div id="admin_main">
  <div id="admin_main_2_1">
    <h3>我的个人信息</h3>
    <div class="bdr">
		<!--管理员基本信息-->
		<p>您好，<strong><span class="font_arial" style="color:#690"><?php echo $username;?></span></strong><br />
		  真人点数：<?php echo $currentmoney;?><br />
		  登入时间：<?php echo $lastlogintime;?><br />
		  登 入 IP：<?php echo $ip." - ".getIPLoc_QQ($ip);?> <br />
		  登入次数：<?php echo $logintimes;?> 次		</p>
    </div>
 
	
  </div>
  <div id="admin_main_2_2">
    <h3><span id="memo_mtime" style="float:right; padding-right:10px;"></span>我的备忘录</h3>
    <div class="bdr"><textarea name="data" id="memo_data" class="inputtext" style="height:173px;width:99%;margin:5px; padding:5px" onblur='$.post("/user/notepad.php", { data: this.value }, function(data){$("#memo_mtime").html(data);});'><?php echo $memo;?></textarea></div>

	


  </div>
</div>
<script type="text/javascript">
function go(){
	var url = '/user/smstipsadd.php';
	redirect(url);
}
function checkPost(){
	if($("#mobile").val() == ""){
		alert("手机号码不能为空！");  
		return false;
	} else {
		reg=/^(13|15|18)\d{9}$/gi;
		if(!reg.test($("#mobile").val())){
			alert("非法的手机号码！");
			return false;
		}
	}
	if($("#userName").val() == ""){
		alert("用户名不能为空！");  
		return false;
	}
	if($("#passWord").val() == ""){
		alert("密码不能为空！");  
		return false;
	}
}
</script>
</body>
</html>