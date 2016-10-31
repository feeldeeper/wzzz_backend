
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link href="/user/css/system.css" rel="stylesheet" type="text/css">
<script type="text/javaScript" src="/user/js/jquery2.js"></script>
<script type="text/javaScript" src="/user/js/common.js"></script>
<script type="text/javaScript" src="/user/js/chromatable.js"></script>
<script type="text/javaScript" src="/user/js/jquery.tips.compressed.js"></script>
<style type="text/css">
body{min-width:1400px;width:100%}
.align_c td{ text-align:center}
.align_l td{ text-align:left}
.highlight th{}
</style>
<script type="text/javascript">
$(function() {
	//$("#searchUser").chromatable({width: "100%",height: "100%", scrolling: "yes"});
});
function lock(id, type, obj){
	redirect('/user/manage/lock/id/' + id + '/type/' + type + '/username/' + $(obj).parents("tr").find("td").eq(1).text());
}
function liveLock(id, type, obj){
	redirect('/user/manage/lock/model/1/id/' + id + '/type/' + type + '/username/' + $(obj).parents("tr").find("td").eq(1).text());
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
<!--input type='button' class="button_style" onclick='refreshStart()' id="startBtn" value='暂停自动刷新'-->
	  </td>
	</tr>
</table>


<table cellpadding="0" cellspacing="1" class="table_list align_c">
  <caption>
       在线会员账号列表  </caption>
  <th><td style="text-align:left">
  活动时间：此时间为服务器上的UTC+08:00时区标准时间，如和您的电脑时间有较大出入，请校正您的电脑时区为：UTC+08:00时区  </td></th>
</table>

<div id="searchUser">
<table cellpadding="0" cellspacing="1" class="table_list align_c">
<thead>
  <caption>
只看这些会员
  </caption>
  <th><td style="text-align:left">
会员帐号：
    <input type="text" name="userName" id="userName" /> <input type="button" value="只看这个用户" onclick="jsonRefresh()"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="显示所有" onclick="clearUserName()"/>*会员帐号支持模糊查询</td></th>
    </thead>
</table>
</div>

<table cellpadding="0" cellspacing="1" class="table_list align_c" id="template1" style="display:none;">

    <tr align="center">
        <th width="80"><strong>用户名</a></strong></th>
        <th width="80"><strong>别名</strong></th>
        <th width="80"><strong>台号</strong></th>
        <th width="10%"><strong>代理关系</strong></th>
        <th width="10%"><strong>真人点数</strong></th>
        <th width="40"><strong>连赢</strong></th>
        <th style="min-width:130" width="15%">活动时间</th>
        <th width="30%"><strong>IP</strong></th>
    </tr>
	<tr id="template" style="display:none;">
	  <td></td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	</tr>
</table>

<div id='userList'>
	
</div>

<script type="text/javascript">
function clearUserName() {
	$('#userName').val('');
	jsonRefresh();
}
//window.onerror = function(){
//	return true;
//}
//var userNameList = new Array();
var userNum=0;
var gameStr = ['百家乐', '龙虎'];
var orderStr = [['', '庄', '闲', '和', '庄对', '闲对', '庄双', '闲双', '庄单', '闲单', '大', '小'], ['', '龙', '虎', '和', '龙单', '虎单', '龙双', '虎双', '龙黑', '虎黑', '龙红', '虎红']];
var stockInterval;
jsonRefresh();
stockInterval = setInterval(jsonRefresh, 10000);
function refreshStart(){
	if ($("#startBtn").val() == "暂停自动刷新") {
		$("#startBtn").val("开始自动刷新");
		clearInterval(stockInterval);
	} else {
		$("#startBtn").val("暂停自动刷新");
		stockInterval = setInterval(jsonRefresh, 10000);
	}
}
function jsonRefresh(){
$.get("/user/onlinejson.php", {userName:$("#userName").val()},
   function(data){
	  $("#userList").html("");
	  //for (i = 0; i < userNameList.length; i++) {
		//alert(userNameList[i]);
	  //}
	  //userNameList.length = 0;
	  var template1 = $("#template1").clone(true);
	  template1.removeAttr("id");
	  template1.show();
	  $("#userList").append(template1);
	  if (userNum < data.length) {
		alert('有会员上线');
	  }
	  userNum = data.length;
	  $.each(data, function(i, item){
		var template = $("#template").clone(true);
		// var orderCount = item.order.length;
		var orderMoney = 0;
		template.removeAttr("id");

		template.show();
		
		// template.find("caption").html(item.userName + "【" + item.nickName + "】");
		//if (item.userName) {
		//	userNameList.push(item.userName);
		//}
		template.find("td").eq(0).html(item.userName);
		template.find("td").eq(1).html(item.nickName);
		template.find("td").eq(2).html(item.tablet);
		template.find("td").eq(3).html(item.parentTreeStr);
		template.find("td").eq(4).html(item.money);
		template.find("td").eq(5).html(item.wins);
		template.find("td").eq(6).html(item.activityTime);
		template.find("td").eq(7).html(item.ip);
		template1.append(template);
		
		
		// if (orderCount > 0) {
			// template.find("tr").eq(2).show();
		// }
		// $.each(item.order, function(i2, item2){
			// var template3 = $("#template3").clone(true);
			// template3.removeAttr("id");
			// template3.show();
			// template3.find("td").eq(0).html(gameStr[item2.gameId]);
			// template3.find("td").eq(1).html(item2.gameNum);
			// template3.find("td").eq(2).html(item2.totalMoney);
			// template3.find("td").eq(3).html(orderStr[item2.gameId][item2.orderType]);
			// template3.find("td").eq(4).html(item2.eTime);
			// template.find("tr").eq(2).find("table").append(template3);
			// orderMoney += parseInt(item2.totalMoney);
		// });
		// template.find("td").eq(4).html(orderMoney);
		//$("#userList").append(template);
		// $("#userList").append("<hr />");
	  });
   }, 'json');
}

function unix2human(val) {
    var unixTimeValue = new Date(val * 1000);
    beijingTimeValue = unixTimeValue.toLocaleString();
	return beijingTimeValue;
}
</script>
</body>
</html>
