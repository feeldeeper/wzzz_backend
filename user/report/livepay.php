<?php 
header("Content-type: text/html; charset=utf-8");
include("../inc/function.php");
include("../inc/conn.php");
include("../inc/sql.class.php");
$DB = new MySql($conn);
session_set_cookie_params(SESSION_LIFE_TIME);
session_start();
$uid = $_SESSION['uid'];
$username = $_SESSION['adminname'];
$admintab = $_SESSION['admintab'];
$key = $username;if(isset($_GET['userName'])){$key=$_GET['userName'];}
$stime = date('Y-m-d 08:00:00',strtotime('-1 days'));if(isset($_GET['stime']) && $_GET['stime']!=""){$stime=$_GET['stime'];}
$etime = date('Y-m-d 08:00:00',time());if(isset($_GET['etime']) && $_GET['etime']!=""){$etime=$_GET['etime'];}
$user = $DB->Select("select * from user where username = '$key'");
$user = $user[0];
$puid = $user['id']; 
if(!$DB->VerifyUserReport($admintab,$uid,$puid))
{
	echo "没有权限！";exit();
}
$result = $DB->Select("select * from moneylog where createdate>='$stime' and createdate<='$etime' and uid=$puid ");
$upmoney = 0;
$downmoney = 0; 
foreach($result as $re)
{
	if($re['type']=="0")
	{
		$downmoney +=floatval($re['money']);
	}else{
		$upmoney +=floatval($re['money']);
	}
}
$total = $upmoney-$downmoney;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link href="/user/css/system.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/user/css/blue/style.css" type="text/css" media="print, projection, screen" />
<script type="text/javaScript" src="/user/js/jquery.js"></script>
<script type="text/javaScript" src="/user/js/common.js"></script>
<script type="text/javaScript" src="/user/js/jquery.tablesorter.min.js"></script>
<title></title>
<style type="text/css">
.table_list td{ text-align:center}
.table_list{width:500px;margin:9px；text-align:left; float:left}
</style>
<script type="text/javascript">
window.onerror = function(){   
   return true;   
}
$(function() {
	$("#listTable").tablesorter({sortList:[[5,1]]});
});
function act(url, flag){
	switch (flag) {
		default:
			str = "确定要这样做吗？"
			break;
		case 2:
			str = "如果删除上级的话，那他下级的所有会员和代理都会被删除！\n但是报表和游戏数据不会删除！";
			break;
		case 3:
			str = "如果踢出的是会员的话，需等下一局游戏开始才自动生效！\n确定要踢出吗？！";
			break;
	}
	if(flag > 0){
		var bln = window.confirm(str);
		if(bln != false){
			document.myform.action = url;
			$("#myform").submit();
		}
	}
}
function checkPost(){
	
}
</script>
</head>
<body>
<DIV id=position2><STRONG>当前位置：</STRONG>
<a href='/user/report/livepay.php'>上下分报表</a>
<A href='/user/report/livepay.php'></A><A href='/user/report/livepay.php'></A></DIV>
<table cellpadding="0" cellspacing="1" class="table_info">
<caption>快捷操作</caption>
    <tr>
		<td>
<input type='button' class="button_style" onclick='history.go(-1);' value='返回至上一页'>
<input type='button' class="button_style" onclick='history.go(1);' value='前进至下一页'>
<input type='button' class="button_style" onclick='location.reload();' value='刷新本页'>
	  </td>
	</tr>
    <tr>
    	<td>
按用户名查询：
  <input name="userName" type="text" id="userName" onKeyDown="if(event.keyCode==13) {go();}" value="<?=$key?>" size="20"/>
<link rel="stylesheet" type="text/css" href="/user/css/calendar-blue.css"/>
<script type="text/javascript" src="/user/js/calendar.js"></script>
开始时间：<input type="text" id="sTime" value="<?=$stime?>" size="18" />
  结束时间：
  <input id="eTime" type="text" value="<?=$etime?>" size="18" />
  <input type='button' class="button_style" onclick="go()" value="确定搜索">
<a href="javascript:settime('<?php echo date("Y-m-d",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1-7,date("Y")));?> 08:00:00','<?php echo date("Y-m-d",mktime(23,59,59,date("m"),date("d")-date("w")+7-7,date("Y")));?> 08:00:00')">上周</a> <a href="javascript:settime('<?php echo date('Y-m-d',strtotime('-2 days'));?> 08:00:00','<?php echo date('Y-m-d',strtotime('-1 days'));?> 08:00:00')">昨天</a> <a href="javascript:settime('<?php echo date('Y-m-d',strtotime('-1 days'));?> 08:00:00','<?php echo date('Y-m-d',time());?> 08:00:00')">当日</a> <a href="javascript:settime('<?php echo date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y")));?> 08:00:00','<?php echo date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y")));?> 08:00:00')">本周</a> <a href="javascript:settime('<?php echo date("Y-m-d",mktime(0, 0 , 0,date("m"),1,date("Y")));?> 08:00:00','<?php echo date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("t"),date("Y")));?> 08:00:00')">本月</a>
      </td>
    </tr>
</table>
<div style="clear:both"></div>
<div style="margin:5px; float:left">
<table cellpadding="0" cellspacing="1" class="table_list" id="listTable">
<caption>
上下分报表
</caption>
<thead>
<tr align="center">
  <!--th>&nbsp;</th-->
	<th>用户名</th>
	<th>别名</th>
    <th>上分</th>
	<th>下分</th>
	</tr>
</thead>
<tbody>

<tr align="center">
  <!--td><input type='button' class="button_style" value='明细'></td-->
  <td><?=$user['username']?></td>
  <td><?=$user['nickName']?></td>
  <td><?=$upmoney?></td>
  <td><?=$downmoney?></td>
  </tr>
</tbody>
<tfoot>
<tr align="center" id="total_listTable" class="total">
  <th>合计</th>
  <th><?=$total?></th>
  <!--th>&nbsp;</th-->
  <th>&nbsp;</th>
  <th>&nbsp;</th>
  </tr>
</tfoot>
</table>
</div>
<div style="clear:both"></div>
<div style="margin:5px; float:left"><input type='button' class="button_style" onclick="window.print()" value="打印"></div>
<script type="text/javascript">
date = new Date();
Calendar.setup({
	inputField     :    "sTime",
	ifFormat       :    "%Y-%m-%d %H:%M:%S",
	showsTime      :    true,
	align          :    "B1",
	singleClick    :    true
});
Calendar.setup({
	inputField     :    "eTime",
	ifFormat       :    "%Y-%m-%d %H:%M:%S",
	showsTime      :    true,
	align          :    "B1",
	singleClick    :    true
});
function settime(time1,time2){
	$("#sTime").val(time1);
	$("#eTime").val(time2);
	go(0);
}
function setKeyWord(obj){
	$("#keyWord").val(obj.innerHTML);
	go(0);
}
function go(page){
	var userName = $("#userName").val();
	var userId = getUrlQuery('id', 3);
	if (userName != '') {
		var url = "/user/report/livepay.php?userName="+$("#userName").val()+"&stime="+$("#sTime").val()+"&etime="+$("#eTime").val()+"&type=0";
	} else {
		var url = "/user/report/livepay.php?userName="+$("#userName").val()+"&stime="+$("#sTime").val()+"&etime="+$("#eTime").val()+"&type=0";
	}
	redirect(url);
}
function getResult(id, num)
  {
   var result = 0;
   var list =  $('#'+id+' tbody td:nth-child('+num+')');
    $.each(list,function(i,n)
	{
		result +=  parseFloat(n.innerText);
	});
	return result;
  }

function printTotal(obj, num){
	var val = getResult(obj, num);
	$('#total_' + obj).find('th').eq(num - 1).text(val);
}
//$("#pTotal").html(getResult('listTable', 11));
</script>
<table cellpadding="0" cellspacing="1" class="table_info">
  <caption>报表开始、结束时间提示信息</caption>
  <tr>
    <td>1、每天：开始时间是从中午12点整至第二天中午11点59分59秒，其他时间段也是按此格式。<br />
      2、每周：从当前时间所在周的周一至周日。<br />
    3、每月：从当前时间所在月的第一周的星期一往后推算4个星期的星期一。<br /></td>
  </tr>
</table>
</body>
</html>