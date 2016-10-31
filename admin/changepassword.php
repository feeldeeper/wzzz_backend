<?php 
header("Content-type: text/html; charset=utf-8");
include("../user/inc/function.php");
include("../user/inc/conn.php");
session_set_cookie_params(SESSION_LIFE_TIME);
session_start();
$uid = $_SESSION['uid'];
$username = $_SESSION['adminname'];
if(isset($uid) && $uid!="" && $uid!="0")
{
}
else{ echo "<script>alert('请先登录!');window.location='login.php';</script>";exit();}

if(isset($_POST['dosubmit']) && $_POST['dosubmit']!="")
{
	$oldPassword = md5($_POST['oldPassword']);
	$newPassword = md5($_POST['newPassword']);
	$query = "select * from user where password='$oldPassword' and id =$uid";
	$result = mysql_query($query,$conn);
	if(mysql_num_rows($result)>0)
	{
		$query = "update user set password='$newPassword' where id =$uid";
		mysql_query($query,$conn);
		echo "<script>alert('密码修改成功!');</script>";
	}else{
		echo "<script>alert('旧密码不匹配!');history.back(-1);</script>";exit();
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link href="/user/css/system.css" rel="stylesheet" type="text/css">
<script type="text/javaScript" src="/user/js/jquery.js"></script>
<script type="text/javaScript" src="/user/js/common.js"></script>
<script type="text/javascript" src="/user/js/validator.js"></script>
<style type="text/css">
#password_notice{color:red}
.table_list th{ line-height:16px; height:16px}
/*密码强度判定*/
.pw_check { width:150px; background:url(/skin/images/pw_check.gif)  no-repeat; height:20px;}
.pw_check span { width:50px; height:14px; line-height:14px; margin-bottom:6px; text-align:center; display:block; float:left;}
#pw_check_1{ background-position:0 bottom;}
#pw_check_2{ background-position:-150px bottom;}
#pw_check_3{ background-position:-300px bottom;}
.table_reg{ text-align:left; padding:6px 30px; font-size:14px; font-weight:bold; border-bottom:1px solid #BFF1FF; margin:10px auto; width:98%;}
.table_reg td{ padding:8px 5px;}
.table_reg caption{text-align:left;	padding:6px 30px; font-size:14px; font-weight:bold;	background:url(/skin/images/login_bg.gif) no-repeat -1px -77px; border-bottom:1px solid #8da7c4; margin:10px auto 0;}
</style>
</head>
<body>
<script language="javascript" type="text/javascript"> 

</script>

  <form action="changepassword.php" method="post" name="myform">
    <table cellpadding="0" cellspacing="1" class="table_form">
    <caption>修改密码</caption>
      <tr>
        <th width="20%">用户名：</th>
        <td width="80%"><strong><?php echo $username;?></strong></td>
      </tr>
      <tr>
        <th>原密码：</th>
        <td><input name="oldPassword" type="password" id="oldPassword" size="15" require="true" datatype="limit" min="6" max="16" msg="密码不得少于6个字符或超过16个字符！" /></td>
      </tr>
      <tr>
        <th>新密码：</th>
        <td><input name="newPassword" type="password" id="newPassword" size="20" require="true" datatype="limit" min="6" max="16" msg="密码不得少于6个字符或超过16个字符！" /></td>
      </tr>
      <tr>
        <th>密码强度：</th>
        <td><div id="pw_check_1" class="pw_check" style="display:;"><span><strong class="c_orange">弱</strong></span><span>中</span><span>强</span></div>
          <div id="pw_check_2" class="pw_check" style="display:none;"><span>弱</span><span><strong class="c_orange">中</strong></span><span>强</span></div>
          <div id="pw_check_3" class="pw_check" style="display:none;"><span>弱</span><span>中</span><span><strong class="c_orange">强</strong></span></div></td>
      </tr>
      <tr>
        <th>确认新密码：</th>
        <td><input name="chkPassword" type="password" id="chkPassword" size="20" require="true" datatype="limit|repeat"  min="6" max="16" to="newPassword" msg="密码不得少于6个字符或超过16个字符|两次输入的密码不一致" /></td>
      </tr>
      <tr>
<input type="hidden" name="forward" value="" />
        <input type="hidden" name="username" value="" /> 
        <th></th>
        <td colspan="2"><label>
          <input type="submit" name="dosubmit" value="确 定" id="button" />
          &nbsp;
          <input type="reset" name="reset" value="重 置" />
          </label></td>
      </tr>
    </table>
  </form>
<script LANGUAGE="javascript"> 
<!--
$().ready(function() {
	  $('form').checkForm(1);
});

function CharMode(iN)
{
if (iN>=65 && iN <= 90)
return 2;
if (iN>=97 && iN <= 122)
return 4;
else
return 1;
}
 
function bitTotal(num)
{
modes = 0;
for(i=0; i<3; i++)
{
if (num & 1) modes++;
num >>>= 1;
}
return modes;
}
 
function checkStrong(sPW){
Modes=0;
 
for (i=0;i<sPW.length;i++){
Modes|=CharMode(sPW.charCodeAt(i));
}
var btotal = bitTotal(Modes);
if (sPW.length >= 10) btotal++;
switch(btotal) {
case 1:
return "pw_check_1";
break;
case 2:
return "pw_check_2";
break;
case 3:
return "pw_check_3";
break;
default:
return "pw_check_1";
}
}
 
function ShowStrong(){
data = checkStrong($('#newPassword').val());
pw_id = '#' + data;
$(".pw_check").hide();
$(pw_id).show();
}
 
$('#newPassword').blur(function(){
 ShowStrong();
});
//-->
</script>
</body>
</html>