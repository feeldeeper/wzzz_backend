<?php 
header("Content-type: text/html; charset=utf-8");
include("../inc/function.php");
include("../inc/conn.php");
include("../inc/sql.class.php");
$DB = new MySql($conn);
session_set_cookie_params(SESSION_LIFE_TIME);
session_start();
$uid = $_SESSION['uid'];
if(isset($uid) && $uid!="" && $uid!="0")
{
}
else{ echo "<script>alert('请先登录!');window.location='../login.php';</script>";exit();}

$username = $_SESSION['adminname'];

$key = "";if(isset($_GET['key'])){$key=$_GET['key'];}
$stime = date('Y-m-d 08:00:00',strtotime('-1 days'));if(isset($_GET['stime']) && $_GET['stime']!=""){$stime=$_GET['stime'];}
$etime = date('Y-m-d 08:00:00',time());if(isset($_GET['etime']) && $_GET['etime']!=""){$etime=$_GET['etime'];}


$logs = array();

$pid = " ('$username')";
if($key=="")
{
	$pid = getpid($username,$conn);
}

$logs = ximalog($key,$pid,$stime,$etime,$conn);

function ximalog($key,$pid,$stime,$etime,$conn)
{
	$arr = array();
	$query = "select * from ximalog where username='$key' and createdate>='$stime' and createdate<='$etime'";	
	if($key=="")
	{
		$query = "select * from ximalog where shangji in $pid and createdate>='$stime' and createdate<='$etime'";
	}
	$result=mysql_query($query,$conn);
	if(mysql_num_rows($result))
	{
		while($row = mysql_fetch_array($result))
		{
			$member->id = $row['id'];
			$member->username = $row['username'];
			$member->nickname = $row['nickname'];
			$member->shangji = $row['shangji'];
			$member->createdate = $row['createdate'];
			$member->settledxm = $row['settledxm'];
			$member->settledyj = $row['settledyj'];
			$member->unsettledxm = $row['unsettledxm'];
			$member->unsettledyj = $row['unsettledyj'];
			array_push($arr,$member);
			$member = null;
		}
	}
	return $arr;
}

function getid($username,$conn)
{
	$query = "select * from user where username='$username' ";	
	$result=mysql_query($query,$conn);
	if(mysql_num_rows($result))
	{
		if($row = mysql_fetch_array($result))
		{
			return $row['id'];
		}
	}
	
	return "0";
}

function getpid($username,$conn)
{
	$id = getid($username,$conn);
	$query = "select * from user where pid='$id' and type='2'";	
	$result=mysql_query($query,$conn);
	if(mysql_num_rows($result))
	{
		$pid=" ('$username'";
		while($row = mysql_fetch_array($result))
		{
			$pid .= ",'".$row['username']."'";
		}
		$pid.=")";
		return $pid;
	}
	
	return " ('$username')";
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link href="/user/css/system.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/user/css/blue/style.css" type="text/css" media="print, projection, screen" />
<script type="text/javaScript" src="/user/js/jquery1.9.js"></script>
<script type="text/javaScript" src="/user/js/common.js"></script>
<script type="text/javascript" src="/user/js/default.js"></script>
<script type="text/javascript" src="/user/js/livexima.js?v=1.0"></script>
<script type="text/javaScript" src="/user/js/jquery.tablesorter.min.js?v=0.5"></script>
<script type="text/javascript" src="/user/js/jquery.edtableheader.js"></script>
<script language="javascript" src="/user/js/LodopFuncs.js"></script>
<title></title>
<style type="text/css">
body{min-width:1300px;width:100%}
.table_list td{ text-align:center}
#loading{z-index:1;padding:5px 10px;background:red;color:#fff;position:absolute;top:0px;left:0px}
.f14{font-size:14px}
</style>
<script type="text/javascript">
window.onerror = function(){
    return true;
}
var LODOP;
var sTime = '<?php echo date('Y年m月d日',time());?>';
var eTime = '<?php echo date('Y年m月d日',strtotime('+1'));?>';
var thisYears = 2015;

function printAll() {
	var i = 0;
	$("input[pid='print']").each(function(){
		i++;
		var obj = this;
		setTimeout(function(){delayPrint(obj);}, 3000 * i);
	});
	//$("input[pid='print']").trigger("click");
}

function delayPrint(obj) {
	$(obj).trigger("click");
}
</script>
</head>
<body>
<div id='loading'>正在加载...</div>
<DIV id=position2><STRONG>当前位置：</STRONG>
<a href=''>洗码佣金结算报表</a>
</DIV>

<table cellpadding="0" cellspacing="1" class="table_info">
<caption>快捷操作</caption>
    <tr>
	  <td>
<input type='button' class="button_style" onclick='history.go(-1);' value='返回至上一页'>
<input type='button' class="button_style" onclick='history.go(1);' value='前进至下一页'>
<input type='button' class="button_style" onclick='location.reload();' value='刷新本页'>
<input type='button' class="button_style" id="fullScreen" value='全屏显示'>
<input type='button' class="button_style" onclick='printAll()' value='全部打印'>
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

<table cellpadding="0" cellspacing="1" class="table_list" id="listTable">
<caption>
洗码佣金结算报表</caption>
<thead>
<tr align="center">

  <th>编号</th>
	<th>用户名</th>
	<th>别名</th>
	<th>上级代理</th>
	<th>清算洗码量</th>
	<th>清算洗码佣金</th>
	<th>未清算洗码量</th>
	<th>未清算洗码佣金</th>
	<th>清算时间</th>
    </tr>
</thead>
<tbody>
<?php foreach($logs as $ag){ ?>
<tr align="center">
  <td><span class='b'><?=$ag->id?></span></td>
  <td><?=$ag->username?></td>
  <td><?=$ag->nickname?></td>
  <td><?=$ag->shangji?></td>
  <td><?=$ag->settledxm?></td>
  <td><?=$ag->settledyj?></td>
  <td><?=$ag->unsettledxm?></td>
  <td><?=$ag->unsettledyj?></td>
  <td><?=$ag->createdate?></td>
</tr>
 <?php } ?>
</tbody>
<tfoot>
<tr align="center" id="total_listTable" class="total">
  <th>总计</th>
  <th>&nbsp;</th>
  <th>&nbsp;</th>
  <th>&nbsp;</th>
  <th>&nbsp;</th>
  <th>&nbsp;</th>
  <th>&nbsp;</th>
  <th>&nbsp;</th>
  <th></th>
  <!--th>&nbsp;</th-->
</tr>
</tfoot>
</table>


<div style="margin:5px; float:left"><input type='button' class="button_style" onclick="window.print()" value="打印"></div>
<script type="text/javascript">
$(function() {
	printTotal('mlistTable', 5);
	printTotal('mlistTable', 7);
	printTotal('mlistTable', 8);
	printTotal('mlistTable', 10);
	printTotal('mlistTable', 11);
	printTotal('mlistTable', 12);
	printTotal('mlistTable', 13);
	$("#listTable").find("tbody").find("tr").each(function(){
		//formatMoney(this, 6);
		formatMoney(this, 12);
		formatMoney(this, 13);
		//formatMoney(this, 13);
	});
	$("#mlistTable").find("tbody").find("tr").each(function(){
		//formatMoney(this, 6);
		formatMoney(this, 10);
	});
	//setAllTotal(1, 4, 4);
	$("#mlistTable").freezeHeader();
});
function formatMoney(a, b){
	var c = $(a).find("td").eq(b).find("span");
	c.text(outputMoney(c.text()));
}
function setAllTotal(a, b, c){
	$("#alistTable tbody").find("td").eq(a - 1).html(allTotalSum('total_listTable', b) + allTotalSum('total_mlistTable', c));
}
function allTotalSum(obj, num){
	return parseFloat($("#" + obj).find("th").eq(num - 1).text());
}
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
	$('#loading').show();
	var userName = $("#userName").val();
	if (userName != '') {
		var url = "/user/agent/ximareport.php?key="+$("#userName").val()+"&stime="+$("#sTime").val()+"&etime="+$("#eTime").val();
	} else {
		var url = "/user/agent/ximareport.php?stime="+$("#sTime").val()+"&etime="+$("#eTime").val();
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
	var val2 = outputMoney(val + "");
	if (val > 0) {
		val = '<span class="r bo f14">' + val2 + '</span>';
	} else {
		val = '<span class="g f14">' + val2 + '</span>';
	}
	$('#total_' + obj).find('th').eq(num - 1).html(val);
}
//$("#pTotal").html(getResult('listTable', 11));
function getArgs(strParame) {
var args = new Object( );
var query = location.search.substring(1); // Get query string
var pairs = query.split("&"); // Break at ampersand
for(var i = 0; i < pairs.length; i++) {
var pos = pairs[i].indexOf('='); // Look for "name=value"
if (pos == -1) continue; // If not found, skip
var argname = pairs[i].substring(0,pos); // Extract the name
var value = pairs[i].substring(pos+1); // Extract the value
value = decodeURIComponent(value); // Decode it, if needed
args[argname] = value; // Store as a property
}
return args[strParame]; // Return the object
}


function getuserPrintData(obj, sTime, eTime) {
	var td = $(obj).parent("td").parent("tr").find("td");
	$("#pUserName").html(td.eq(2).text());
	$("#pNickName").html(td.eq(3).text());
	$("#mpReNum").html(td.eq(1).text());
	$("#pUserProfit").html('￥' + parseInt(td.eq(6).text().replace(/,/g, '')));
	$("#pWashTotal").html('￥' + td.eq(7).text());
	$("#pDrawbackProfit").html('￥' + td.eq(10).text());
	$("#pLiveWash").html(td.eq(8).text());
	var washTotalProfit = parseInt(td.eq(9).text());
	$("#pWashTotalProfit").html('￥' + washTotalProfit);
	var userAllProfit = washTotalProfit + parseInt(td.eq(10).text());
	$("#pUserAllProfit").html('￥' + userAllProfit);
	$("#pbigMoneyStr").html(ChangeToBig(userAllProfit));
	$("#pStime").html(this.sTime);
	$("#pEtime").html(this.eTime);
	$("#thisYears").html(this.thisYears);
	createUserPrintPage(1760);
}

function getAgPrintData(obj, sTime, eTime) {
	var td = $(obj).parent("td").parent("tr").find("td");
	var userMoneyProfit = parseInt(td.eq(6).text().replace(/,/g, ''));
	var apDrawbackProfit = parseInt(td.eq(11).text());
	$("#apUserName").html(td.eq(2).text());
	$("#apNickName").html(td.eq(3).text());
	$("#apReNum").html(td.eq(1).text());
	$("#apUserProfit").html('￥' + userMoneyProfit);
	$("#apRealUserProfit").html('￥' + (userMoneyProfit + apDrawbackProfit));
	$("#apWashTotal").html('￥' + td.eq(8).text());
	if (td.eq(10).text() != '') {
		$("#apWashProfit").text('￥' + td.eq(10).text());
	} else {
		alert('洗码佣金错误！');
		return;
	}
	$("#apDrawbackProfit").text('￥' + td.eq(11).text());
	$("#apLiveRatio").text(td.eq(12).text());
	var agRatio = parseInt(td.eq(12).text()) / 100;
	var ratio = 1 - agRatio;
	var realUserMoneyProfit = userMoneyProfit + apDrawbackProfit;
	$("#apWashTotalProfit").text(parseInt(td.eq(10).text() * ratio) + parseInt(td.eq(11).text()));
	if (agRatio > 0) {
		/*if (realUserMoneyProfit < 0) {
			$("#apUserAllProfit").text(Math.abs(realUserMoneyProfit * agRatio));
		} else {
			$("#apUserAllProfit").text(realUserMoneyProfit * ratio);
		}*/
		$("#apTips").text('应收占成金额');
		$("#apUserAllProfit").text(parseInt(Math.abs(realUserMoneyProfit * agRatio)));
	} else {
		$("#apTips").text('应付占成金额');
		$("#apUserAllProfit").text('0');
	}
	if (realUserMoneyProfit > 0) {
		$("#apAllTotalProfit").text(parseInt($("#apWashTotalProfit").text()) - parseInt($("#apUserAllProfit").text()));
	} else {
		$("#apAllTotalProfit").text(parseInt($("#apWashTotalProfit").text()) + parseInt($("#apUserAllProfit").text()));
	}
	$("#apbigMoneyStr").text(ChangeToBig(Math.abs($("#apAllTotalProfit").text())));
	$("#apStime").html(this.sTime);
	$("#apEtime").html(this.eTime);
	$("#apthisYears").html(this.thisYears);
	createAgPrintPage(1956);
}

function createUserPrintPage(width) {
	LODOP.PRINT_INITA(0,0,613,302,"娱乐中心王者至尊三合一会员周报表");
	LODOP.SET_PRINT_MODE("POS_BASEON_PAPER",true);
	LODOP.SET_PRINT_PAGESIZE(2,770,width,'');
	LODOP.ADD_PRINT_HTM(-5,-10,"100%","100%", $("#userPrintTemplates").html());
	LODOP.PRINT();
	//LODOP.PREVIEW();
};

function createAgPrintPage(width) {
	LODOP.PRINT_INITA(0,0,613,302,"娱乐中心王者至尊三合一代理周报表");
	LODOP.SET_PRINT_MODE("POS_BASEON_PAPER",true);
	LODOP.SET_PRINT_PAGESIZE(2,770,width,'');
	LODOP.ADD_PRINT_HTM(-5,-10,"100%","100%", $("#agPrintTemplates").html());
	LODOP.PRINT();
	//LODOP.PREVIEW();
};
</script>

<script>
function monitorMtbody() {
	var i;
	i = 1;
	$("#listTable").find("tbody").find("tr").each(function(){
		$(this).find("td").eq(1).text(i);
		i++;
	});
	i = 1;
	$("#mlistTable").find("tbody").find("tr").each(function(){
		$(this).find("td").eq(1).text(i);
		i++;
	});
}
try {
LODOP = getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));
LODOP.SET_LICENSES("四川第五元素OA管理系统","E27623582E4029F9A7DED1F854086A89","","");
}catch(e){}
</script>
</body>
</html>hit