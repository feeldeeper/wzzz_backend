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
$key = "";if(isset($_GET['userName'])){$key=$_GET['userName'];}
$stime = date('Y-m-d 08:00:00',strtotime('-1 days'));if(isset($_GET['stime']) && $_GET['stime']!=""){$stime=$_GET['stime'];}
$etime = date('Y-m-d 08:00:00',time());if(isset($_GET['etime']) && $_GET['etime']!=""){$etime=$_GET['etime'];}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link href="/user/css/system.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/user/css/blue/style.css" type="text/css" media="print, projection, screen" />
<script type="text/javascript" src="/user/js/jquery.js"></script>
<script type="text/javaScript" src="/user/js/common.js"></script>
<script type="text/javaScript" src="/user/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="/user/js/default.js"></script>
<script type="text/javascript" src="/user/js/report/lh.js"></script>
<script type="text/javascript" src="/images/Charts/FusionCharts.js"></script>
<title></title>
<style type="text/css">
.table_list td{ text-align:center}
#loading{z-index:1;padding:5px 10px;background:red;color:#fff;position:absolute;top:0px;left:0px}
.f14{font-size:14px}
</style>
<script type="text/javascript">
window.onerror = function(){
    return true;
}
$(function() {
    $('#loading').fadeOut('slow');
});
</script>
</head>
<body>
<div id='loading'>正在加载...</div>
<DIV id=position2><STRONG>当前位置：</STRONG>
<a href='/user/report/livelh.php'>龙虎报表</a>
<a href='/user/report/livelh.php'></a></DIV>

<table cellpadding="0" cellspacing="1" class="table_info">
<caption>快捷操作</caption>
    <tr>
	  <td>
<input type='button' class="button_style" onclick='history.go(-1);' value='返回至上一页'>
<input type='button' class="button_style" onclick='history.go(1);' value='前进至下一页'>
<input type='button' class="button_style" onclick='location.reload();' value='刷新本页'>
<input type='button' class="button_style" id="fullScreen" value='全屏显示'>
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

<table cellpadding="0" cellspacing="1" class="table_list" id="myTable">
<caption>
用户： 
别名：直属会员报表
</caption>
<thead>
<tr align="center">
  <th>&nbsp;</th>
    <th>投注次数</th>
	<th>投注金额</th>
	<th>输赢金额</th>
	<th>洗码量</th>
	<th>洗码比(%)</th>
	<th>洗码佣金</th>
	<th>总金额</th>
	<th>占成比(%)</th>
    <th>缴上家利润</th>
    </tr>

<tr align="center">
  <td><input type='button' class="button_style" onclick="redirect('/report/live/lhmember/id//stime/2015-07-21 08:00:00/etime/2015-07-22 08:00:00');" value='明细'></td>
  <td></td>
  <td><span class='b'></span></td>
  <td></td>
  <td></td>
  <td>0%</td>
  <td>0</td>
  <td>0</td>
  <td>0%</td>
  <td>0</td>
  </tr>
</thead>
<tbody>
</tbody>
</table>

<table cellpadding="0" cellspacing="1" class="table_list" id="listTable">
<caption>
下级代理报表
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
	<th>占成比(%)</th>
	<th>总金额</th>
	<th>缴上家纯利</th>
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
  <th>&nbsp;</th>
  <th>&nbsp;</th>
  </tr>
</tfoot>
</table>
<div style="margin:5px; float:left"><input type='button' class="button_style" onclick="window.print()" value="打印"></div>
<!--div id="chart1" style="clear:both">FusionCharts will load here!</div-->
<div id="chartContainer" style="clear:both">FusionCharts will load here!</div>
<script type="text/javascript">
<!--
var myChart = new FusionCharts( "/images/Charts/Pie3D.swf", "myChartId", "100%", "300", {debugMode : false , registerWithJS : true, animation : false} );
myChart.setJSONData({ "chart": { "caption": "自身盈利饼状图", "formatnumberscale": "0", "animation" : "0" , "numberPrefix" : "￥", "baseFontSize":12},
"data": [], 
  "styles":[{      "definition":[{          "style":[{ "name":"MyFirstFontStyle", "type":"font", "font":"Verdana", "size":"12", "color":"FF0000", "bold":"1", "bgcolor":"FFFFDD" },                   { "name":"MyFirstAnimationStyle", "type":"animation", "param":"_xScale", "start":"0", "duration":"2" },                   { "name":"MyFirstShadow", "type":"Shadow", "color":"CCCCCC" }]        }],      "application":[{          "apply":[{ "toobject":"Caption", "styles":"MyFirstFontStyle,MyFirstShadow" },                   { "toobject":"Canvas", "styles":"MyFirstAnimationStyle" },                   { "toobject":"DataPlot", "styles":"MyFirstShadow" }]        }]    }]
});
myChart.render("chartContainer");
// -->
</script>    
<table cellpadding="0" cellspacing="1" class="table_info">
  <caption>报表开始、结束时间提示信息</caption>
  <tr>
    <td id="help"></td>
  </tr>
</table>
</body>
</html>
hit miss