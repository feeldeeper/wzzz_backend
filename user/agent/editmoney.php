<?php 
header("Content-type: text/html; charset=utf-8");
include("../inc/function.php");
include("../inc/conn.php");
include("../inc/sql.class.php");
$DB = new MySql($conn);
session_set_cookie_params(SESSION_LIFE_TIME);
session_start();
$suid = $_SESSION['uid'];
if(isset($suid) && $suid!="" && $suid!="0")
{
}
else{ echo "<script>alert('请先登录!');window.location='../login.php';</script>";exit();}
$username = $_SESSION['adminname'];
echo $_POST['dosubmit'];
if(isset($_POST['uid']) && $_POST['uid']!=""){
	$im=intval($_POST['payInMoney']);
	$om=intval($_POST['payOutMoney']);
	$mid=$_POST['uid'];
	$type=$_POST['type'];
	$ptype=$_POST['ptype'];
	$pid=$_POST['pid'];
	$today = date("Y-m-d H:i:s",time());
	if($type=="1")
	{
		$DB->Query("update user set currentmoney=currentmoney+$im where id = $mid");		
		$DB->Query("insert into moneylog(uid,createdate,money,type,tid) values('$mid','$today','$im','$type','$suid')");
		if($ptype!="0"){$DB->Query("update user set currentmoney = currentmoney - $im where id = $pid");}
	}
	else{
		$DB->Query("update user set currentmoney=currentmoney-$om where id = $mid");		
		$DB->Query("insert into moneylog(uid,createdate,money,type,tid) values('$mid','$today','$om','$type','$suid')");
		if($ptype!="0"){$DB->Query("update user set currentmoney = currentmoney + $om where id = $pid");}
	}
	echo "<script>window.location='/user/agent/index.php?id=$suid';</script>";
	exit();
}
if(isset($_GET['id']) && $_GET['id']!="")
{
	$uid = $_GET['id'];
	$user = $DB->Select("select * from user where id = $uid");
	$user = $user[0];

	$suser = $DB->Select("select * from user where id = $suid");
	$suser = $suser[0];
}
else{
	echo "<script>history.back(-1);</script>";
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
<script type="text/javascript" src="/user/js/paymoney.js"></script>
<script language="javascript" src="/user/js/LodopFuncs.js"></script>
<style type="text/css">
#sx{color:#00C;text-decoration:underline;cursor:pointer;}
.table_list td{ text-align:center}
</style>
<script type="text/javascript">
var agLiveMoney = <?=$suser['currentmoney']?>;
var liveMoney = <?=$user['currentmoney']?>;
var LODOP;
$(function() {
	//LODOP = getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));
	//LODOP.SET_LICENSES("四川第五元素OA管理系统","E27623582E4029F9A7DED1F854086A89","","");
});

function printReco() {
}
</script>
</head>
<body>
<table cellpadding="0" cellspacing="1" class="table_info">
<caption>快捷操作</caption>
    <tr>
		<td>
<input type='button' class="button_style" onclick='history.go(-1);' value='返回至上一页'>
<input type='button' class="button_style" onclick='history.go(1);' value='前进至下一页'>
<input type='button' class="button_style" onclick='location.reload();' value='刷新本页'>
	  </td>
	</tr>
</table>
<!--标签页-->

<form method="post" action="editmoney.php" name="f" id="postForm">
<input type="hidden" name="uid" value="<?=$uid?>">
<table cellpadding="0" cellspacing="1" class="table_form">
<caption><b class="r"><?=$user['username']?></b> 加减点</caption>
  <tr>
    <th width="25%"><strong>加点或减点</strong></th>
    <td width="75%">
		<input type="radio" onclick="selectPayType(0)" value="1" name="type" class="radio_style"/>&nbsp;加点(+)&nbsp;&nbsp;
	  <input type="radio" onclick="selectPayType(1)" value="0" name="type" class="radio_style"/>&nbsp;减点(-)&nbsp;	</td>
  </tr>
  <tr>
    <th><strong>类型</strong></th>
    <td>
        <input name="moneyType" type="radio" class="radio_style" onclick="javascript:change();" value="0" checked="checked"/>真人点数&nbsp;&nbsp;	</td>
  </tr>
  
  <tr>
    <th><strong>真人点数</strong></th>
    <td id="liveMoney"><?=$user['currentmoney']?><span id="nowLiveMoney"></span></td>
  </tr>

<tr id="payIn">
    <th><strong>加点数量</strong></th>
    <td>
<input type="text" onclick="if(this.value == 0){this.value = ''}" id="payInMoney" onkeyup="pay()" name="payInMoney" value="0" size="12" require="true" datatype="currency|range" min="0" max="<?php echo floor($suser['currentmoney']);?>" msg="真人点数限额最小为0|真人点数限额最大为<?php if($suser['type']=="0") echo "无限"; else echo $suser['currentmoney']; ?>" msgid="code1"/>
&nbsp;<font id = "numberid" name="numberid" style="color:red">点</font><span id="code1"></span><?php if($suser['type']!="0"){ ?>，上级<b class="r"><?=$suser['username']?></b>还有<b class="r" id="balance1"></b>可加<b class="r" id="balance2"><?=$suser['currentmoney']?></b><?php } ?>
</td>
</tr>

<tr id="payOut" style="display:none">
    <th><strong>减点数量</strong></th>
    <td>
<input type="text" id="payOutMoney" onkeyup="pay()" name="payOutMoney" value="0" size="12" require="true" datatype="currency|range" min="0" max="<?=$user['currentmoney']?>" msg="真人点数限额最小为0|真人点数限额最大为<?=$user['currentmoney']?>" msgid="code2"/>
&nbsp;<font id="numberid" name="numberid" style="color:red">点</font><span id="code2"></span>，下级<b class="r"><?=$user['username']?></b>还有<b class="r" id="balance3"></b>可减<b class="r" id="balance4"></b>
</td>
</tr>

  <tr>
    <th>&nbsp;</th>
    <td><input type="button" name="postBtn" value="打印上下分记录单" id="postBtn2" onclick="printReco();" /></td>
  </tr>
  <tr>
    <th><strong>操作事由</strong></th>
    <td><textarea name="note" cols="60" rows="8" id="kw"></textarea><span id="su"></span>&nbsp;<font style="color:red"></font>
<br /><span id="sx">打开手写功能</span></td>
  </tr>
  <tr>
    <th>&nbsp;</th>
    <td>
	<input type="hidden" name="forward" value="/user/money/index/id/37514">
	<input type="hidden" name="pid" value="<?=$suser['id']?>">
	<input type="hidden" name="ptype" value="<?=$suser['type']?>">
	    <input type="button" name="dosubmit" value=" 确定" id="postBtn" onclick="checkForm();">&nbsp;&nbsp;&nbsp;&nbsp;
	    <input type="reset" name="reset" value=" 重置 ">	</td>
  </tr>
</table>
</form>
<object id="LODOP_OB" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0> 
	<embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0 pluginspage="/js/install_lodop32.exe"></embed>
</object>


</body>
</html>