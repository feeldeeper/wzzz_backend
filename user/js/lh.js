window.onerror = function(){   
    return true;
}
$(function() {
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
	if ($("#listTable tbody").find("tr").length > 0) {
		$("#listTable").tablesorter({sortList:[[5,1]]});
	}
	var modal = getArgs("modal");
	if (modal == "true") {
		$("#fullScreen").click(function() {
			var url = parent.$("#TB_iframeContent").attr("src").replace("&modal=true", "");
			parent.$("#right").attr("src", url);
			parent.tb_remove();
		});
		$("#fullScreen").val("退出全屏");
	} else {
		$("#fullScreen").click(function() {
			window.parent.showIframe(window.location.href);
		});
	}
printTotal('listTable', 4);
printTotal('listTable', 5);
printTotal('listTable', 6);
printTotal('listTable', 7);
printTotal('listTable', 9);
printTotal('listTable', 11);
printTotal('listTable', 12);
/*var totalMoney = parseFloat($("#total_listTable").find("th").eq(10).text());
var totalSelfMoney = parseFloat($("#total_listTable").find("th").eq(11).text());
var myChart1 = new FusionCharts("/images/Charts/Pyramid.swf", "chart1Id", "400", "280", "0", "0");
myChart1.setJSONData({ "chart": { "caption": "总金额占成图", "formatnumberscale": "0", "animation" : "0" , "numberPrefix" : "￥", "baseFontSize":12}, 
"data": [{ "label" : "自身盈利", "value" : Math.abs(totalSelfMoney) }, { "label" : "总金额", "value" : Math.abs(totalMoney)}]
});
myChart1.render("chart1");*/
$("#help").html("1、每天：开始时间是从中午12点整至第二天中午11点59分59秒，其他时间段也是按此格式。<br />2、每周：从当前时间所在周的周一至周日。<br />3、每月：从当前时间所在月的第一周的星期一往后推算4个星期的星期一。<br />4、洗码佣金：洗码量 “乘” 洗码占成比 = 洗码佣金 （注：如中途有修改洗码比，报表会按生成注单时洗码比来计算！）<br />5、总金额：会员输赢金额 “加” 洗码佣金 = 总金额<br />6、缴上家利润：总金额 “乘” 当前账号占成比 = 缴上家利润<br />7、洗码量：只有当用户下注以下几类“庄对”、“闲对”、“和”之外的下注类型发生输赢时方才计算。<br />8、占成比：（注：如中途有修改占成比，报表会按生成注单时占成比来计算！）");
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


function redirect(url)
{
	if(url.lastIndexOf('/.') > 0)
	{
		url = url.replace(/\/(\.[a-zA-Z]+)([0-9]+)$/g, "/$2$1");
	}
	else if(url.match(/\/([a-z]+).html([0-9]+)$/)) {
		url = url.replace(/\/([a-z]+).html([0-9]+)$/, "/$1-$2.html");
	}
	else if(url.match(/-.html([0-9]+)$/)) {
		url = url.replace(/-.html([0-9]+)$/, "-$1.html");
	}

	if(url.indexOf('://') == -1 && url.substr(0, 1) != '/' && url.substr(0, 1) != '?') url = $('base').attr('href')+url;
	var url = url + "/?keepThis="+getArgs("keepThis")+"&modal="+getArgs("modal");
	if (getArgs("modal") == "true") {
		parent.$("#TB_iframeContent").attr("src", url);
	} else {
		location.href = url;
	}
}

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
		var url = "/report/live/search/key/"+$("#userName").val()+"/stime/"+$("#sTime").val()+"/etime/"+$("#eTime").val()+"/type/1";
	} else {
		var url = "/report/live/lh/stime/"+$("#sTime").val()+"/etime/"+$("#eTime").val()+"/type/1";
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
	return round(result, 2);
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
var args = new Object();
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