
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link href="/skin/system.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skin/blue/style.css" type="text/css" media="print, projection, screen" />
<script type="text/javaScript" src="/js/jquery.js"></script>
<script type="text/javaScript" src="/js/common.js"></script>
<script type="text/javaScript" src="/js/jquery.tablesorter.min.js"></script>
<title></title>
<style type="text/css">
.table_list td{ text-align:center}
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
<A href='/report/live/index/stime/2015-07-21+08%3A00%3A00/etime/2015-07-22+08%3A00%3A00'></A></DIV>
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
  <input name="userName" type="text" id="userName" onKeyDown="if(event.keyCode==13) {go();}" value="" size="20"/>
<link rel="stylesheet" type="text/css" href="/js/calendar/calendar-blue.css"/>
<script type="text/javascript" src="/js/calendar/calendar.js"></script>
开始时间：<input name="sTime" type="text" id="sTime" value="2015-07-21 08:00:00" size="18" />
  结束时间：
  <input name="eTime" type="text" id="eTime" value="2015-07-22 08:00:00" size="18" />
  <input type='button' class="button_style" onclick="go()" value="确定搜索">
<a href="javascript:settime('2015-08-03 08:00:00','2015-08-10 08:00:00')">上周</a> <a href="javascript:settime('2015-08-13 08:00:00','2015-08-14 08:00:00')">昨天</a> <a href="javascript:settime('2015-08-14 08:00:00','2015-08-15 08:00:00')">当日</a> <a href="javascript:settime('2015-08-10 08:00:00','2015-08-17 08:00:00')">本周</a> <a href="javascript:settime('2015-08-03 08:00:00','2015-09-07 08:00:00')">本月</a>
      </td>
    </tr>
</table>

<table cellpadding="0" cellspacing="1" class="table_list" id="listTable">
<caption>
百家乐会员报表
</caption>
<thead>
<tr align="center">
  <th>&nbsp;</th>
	<th>用户名</th>
	<th>别名</th>
    <th>投注次数</th>
	<th>投注金额</th>
	<th>输赢金额</th>
	<th>洗码量</th>
	<th>洗码比(%)</th>
	<th>洗码佣金</th>
	<th>总金额</th>
	</tr>
</thead>
<tbody>
</tbody>
<tfoot>
<tr align="center" id="total_listTable" class="total">
  <th>合计</th>
  <th>&nbsp;</th>
  <th>&nbsp;</th>
  <th>&nbsp;</th>
  <th>&nbsp;</th>
  <th>&nbsp;</th>
  <th>&nbsp;</th>
  <th>&nbsp;</th>
  <th>&nbsp;</th>
  <th>&nbsp;</th>
  </tr>
</tfoot>
</table>
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
		var url = "/report/live/search/key/"+$("#userName").val()+"/stime/"+$("#sTime").val()+"/etime/"+$("#eTime").val()+"/type/0";
	} else {
		var url = "/report/live/member/id/"+userId+"/stime/"+$("#sTime").val()+"/etime/"+$("#eTime").val()+"/type/0";
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
printTotal('listTable', 12);
printTotal('listTable', 4);
printTotal('listTable', 5);
printTotal('listTable', 6);
printTotal('listTable', 7);
printTotal('listTable', 9);
printTotal('listTable', 10);
function printTotal(obj, num){
	var val = getResult(obj, num);
	$('#total_' + obj).find('th').eq(num - 1).text(val);
}
//$("#pTotal").html(getResult('listTable', 11));
</script>
<table cellpadding="0" cellspacing="1" class="table_info">
  <caption>报表开始、结束时间提示信息</caption>
  <tr>
    <td>
1、每天：开始时间是从中午12点整至第二天中午11点59分59秒，其他时间段也是按此格式。<br />
2、每周：从当前时间所在周的周一至周日。<br />
3、每月：从当前时间所在月的第一周的星期一往后推算4个星期的星期一。
	</td>
  </tr>
</table>
</body>
</html>
<!--
http://bg.88gobo.net/report/live/bjlmember/id/5102584/stime/2015-05-21%2008:00:00/etime/2015-07-22%2008:00:00
62328
-->