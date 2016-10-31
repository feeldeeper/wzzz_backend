<?php 
	header("content-type:text/html; charset=utf-8");
	include '../user/inc/conn.php';
	include("../user/inc/function.php");
	include("../user/inc/sql.class.php");
	$DB = new MySql($conn);
	session_set_cookie_params(SESSION_LIFE_TIME);
	session_start();
	$uid=$_SESSION['uid'];
	$name=$_SESSION['adminname'];
	if(!isset($name) || $name=="")
	{
		echo "<script>alert('请先登录!');window.location='login.php';</script>";exit();
	}
	$notice = $DB->Select("select * from notice where id=2"); $notice = $notice[0];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>王者至尊管理网</title>
<link rel="stylesheet" type="text/css" href="../user/css/system.css"/>
<link rel="stylesheet" type="text/css" href="../user/css/tree.css"/>
<link rel="stylesheet" type="text/css" href="../user/css/thickbox.css"/>
<script type="text/javascript" src="../user/js/jquery.js"></script>
<script type="text/javaScript" src="../user/js/common.js"></script>
<script type="text/javascript" src="../user/js/adminmenu.js"></script>
<script type="text/javascript" src="../user/js/jquery.marquee.js"></script>
<script type="text/javascript" src="../user/js/thickbox-compressed.js"></script>
<script type="text/javascript" src="../user/js/jquery.liu.select.js"></script>
<script type="text/javascript">
/*window.onerror = function(){
   return true;
}*/
jQuery(function(){
	jQuery("#lang").change(function(){
		window.location = '/index/setlang/lang/' + jQuery("#lang").val() + '/location/main/';
	});
	var lang = getcookie('lang')?getcookie('lang'):'zh-CN';
	if (lang) {
		jQuery("#lang").setSelectedValue(lang);
	}
});
get_menu(7, 'tree', 0);
</script>
<style> 
body {
	width:100%;
	background:#ECF7FE url(../user/images/bg_body.jpg) repeat-y top left;
		scrollbar-face-color: #E7F5FE; 
	scrollbar-highlight-color: #006699; 
	scrollbar-shadow-color: #006699; 
	scrollbar-3dlight-color: #E7F5FE; 
	scrollbar-arrow-color: #006699; 
	scrollbar-track-color: #E7F5FE; 
	scrollbar-darkshadow-color: #E7F5FE; 
	scrollbar-base-color: #E7F5FE;
	}
	
/*後臺位置導航*/
#position { text-align:left; color:#069;padding:0 0 0 24px;height:30px; line-height:30px; width:500px; float:left;}
#position strong {color:#069;}
.pos{margin-left:10px;margin-top:10px; text-align:left;color:#2e6ab1;}
#position a:link, #position a:visited,.pos a:link, .pos a:visited {color:#069;  padding-right:16px; text-decoration:none}
#position a:hover, #position a:active,.pos a:hover, .pos a:active {color:#069;  padding-right:16px;}
#header select{vertical-align:middle}
</style>
 
</head>
<body scroll="no">
<div id="dvLockScreen" class="ScreenLock" style="display:none">
    <div id="dvLockScreenWin" class="inputpwd">
    <h5><b class="ico ico-info"></b><span id="lock_tips">锁屏状态，请输入密码解锁</span></h5>
    <div class="input">
    	<label class="lb">密码：</label><input type="password" id="lock_password" class="input-text" size="24">
        <input type="submit" class="submit" value="&nbsp;" name="dosubmit" onclick="check_screenlock();return false;">
    </div></div>
</div>
<!--head-->
<div id="header">
  <div class="logo"><a href="#" target="_blank"><img src="../user/images/logo.jpg" width="220" height="58" border="0"/></a></div>
  <p id="info_bar"> 用户名称：<strong class="font_arial white">
    <?php echo $_SESSION["adminname"];;?>    </strong>，角色：
    超级管理员&nbsp;&nbsp;|&nbsp;&nbsp;<a href="logout.php" class="white">退出登入</a>&nbsp;&nbsp;|&nbsp;&nbsp;选择语言：
  <select name="select" id="lang">
    <option value="zh-CN">简体中文</option>
  </select></p>
  <div id="menu">
    <ul>
	  <li><a href="table/index.php" onclick="get_menu(4, 'tree', 0)" id="menu_4" class="menu" target="right" alt="台面管理"><span>台面管理</span></a></li>
	  <li><a href="agent/admin.php" onclick="get_menu(0, 'tree', 0)" id="menu_6" class="menu" target="right" alt="用户管理"><span>用户管理</span></a></li>
	  <li><a href="myself.php" onclick="get_menu(7, 'tree', 0)" id="menu_7" class="menu selected" alt="我的面板" target="right"><span>我的面板</span></a></li>
	  <li><a href="log/notice.php" onclick="get_menu(12, 'tree', 0)" id="menu_3" class="menu" alt="系统日志" target="right"><span>系统日志</span></a></li>
	  </ul>
  </div>
</div>
<div id="admin_left">
  <div id="inner" class="inner">
    <h4><em style="float:right"><img src="../user/images/refresh.gif" onClick="javascript:menu_refresh()" width="16" height="25" id="menurefresh" alt="刷新" title="刷新" />
		</em><span id="menu_name">我的面板</span></h4>
	    <div id="tree_box" class="p_r" style="top:10px; left:6px;" >
      <div id="tree" class="tree p_a" style="top:34px;overflow:auto;overflow-x:hidden;"></div>
    	  <div id="tree_bg" class="p_a"></div>
      <div id="tree_click"  class="p_a"></div>
    </div>
  </div>
</div>
<div id="admin_right">
  <div id="inner" class="inner" style="">
	<!--導航-->
				<a href="javascript:show_map();show_div(this)" target="right"><img id="msg_img" src="../user/images/icon_4.gif" title="短消息" height="22" width="22" /></a>
    <span class="btn_menu">
		        <a href="javascript:show_map();show_div(this)" id="show_map" title="後臺管理地圖"><img src="../user/images/icon_9.gif" title="後臺管理地圖" height="22" width="22" /></a>
        <a href="javascript:add_menu()" title="添加常用操作"><img src="../user/images/icon_1.gif" title="添加常用操作" height="22" width="22" /></a>
		<a href="javascript:search_menu()"><img src="../user/images/icon_2.gif" title="菜單搜索" height="22" width="22"  /></a>
		<a href="?mod=phpcms&file=safe&action=start" target="right" onclick="show_div(this)"><img src="../user/images/icon_3.gif" title="掃描木馬" height="22" width="22"  /></a>
		<a href="javascript:get_memo()" onclick="show_div('memo')"><img src="../user/images/icon_7.gif" title="備忘錄" height="22" width="22" /></a>
		<a href="javascript:help_url();show_div(this)"><img src="../user/images/icon_6.gif" title="使用幫助，按F2也可以打開幫助" height="22" width="22"  /></a>
	</span>
	<input type="hidden" name="ismsgopen" id="ismsgopen" value="0" /> 
    <div id="new_msg"><img src="../user/images/s.gif" alt="查看新消息" style="width:49px;height:20px;margin-right:-2px;" onclick="go_right('message/inbox.php?userid=1');" /><img src="../user/images/close_1.gif" alt="關閉" onclick="$('#new_msg').hide();$('#ismsgopen').val('1');" style="margin:5px 15px;" /></div>
    <div class="div" id="add_menu">
      <form action="" method="post" onsubmit="return add_mymenu()">
        <div class="menu_line">菜單名稱：
          <input type="text" name="menu_name" id="my_menu_name" size="40">
        </div>
        <div class="menu_line">菜單位址：
          <input type="text" name="menu_url" id="my_menu_url" size="40">
        </div>
        <div class="menu_line">
          <input type="submit" value="添加到常用操作" style="margin-left:60px;">
        </div>
      </form>
    </div>
	<div id="msg_div" class="div">
	<div class="content_i">
		<ul id="adminlist">
		</ul>
	<div class="footer">
		<div class="footer_left"></div>
		<div class="footer_right"></div>
		<div>
			<div class="btn"><a href="message/outbox.php?userid=1" target="right"><img src="/skin/images/btn_sjx.gif" width="54" height="24" /></a></div>
			<div class="btn"><a href="message/inbox.php?userid=1"  target="right"><img src="/skin/images/btn_fjx.gif" width="54" height="24" /></a></div>
		</div>
	</div>
	</div>
	</div>
    <div id="search_menu" class="div">
      <input type="text" name="menu_key" id="menu_key" value="請輸入功能表名稱" onblur="if($(this).val()=='')$(this).val('請輸入功能表名稱')" onfocus="if($(this).val()=='請輸入功能表名稱')$(this).val('')" size="30" />
      <div id="floor"></div>
    </div>
    <div id="memo" class="div">
	<div id="memo_mtime" style="text-align:right;padding-right:10px;"></div>
    <textarea id="memo_data" name="memo_data" rows="10" cols="50" style="padding:5px" onblur="set_memo(this.value)">
    </textarea>
    </div>
 
 
 

<div id="position"><marquee scrollamount="1" id="marquee" scrollDelay="5" onmouseover="this.stop()" onmouseout="this.start()">
<a target='right'>[公告]&nbsp;<?=$notice['content']?> <?=$notice['stime']?></a>&nbsp;&nbsp;</marquee></div>
    <div>
      <iframe name="right" id="right" frameborder="0" src="myself.php" style="height:100%;width:100%;z-index:111;background-color:#ffffff"></iframe>
    </div>
	<div class="help_line_top" onclick="help_url()" title="點擊查看幫助"><img  id="help_line" src="/skin/images/top.gif" width="13" height="5" border="0" alt="線上說明"/></div>
    <iframe name="help" id="help" frameborder="0" src="" style="height:0px;width:100%;z-index:111"></iframe>
  </div>
</div>
<div class="window_1" id="window_1">
  <div class="window_title"><img src="/skin/images/close.gif" alt="" height="16px" width="16px" onclick="$('.window_1').hide();$('.btn_menu').children('a').attr('class','');" class="jqmClose" style="cursor:pointer;float:right;"/>Phpcms網站後臺導航地圖
  </div>
<div style="clear:both;" class="window_2">

</div>
</div>
<a href="#" title="add a caption to title attribute / or leave blank" class="thickbox">Example 1</a>
 
<script language="JavaScript">
function request(paras){ 
var url = location.href;   
var paraString = url.substring(url.indexOf("?")+1,url.length).split("&");
var paraObj = {}   
for (i=0; j=paraString[i]; i++){   
paraObj[j.substring(0,j.indexOf("=")).toLowerCase()] = j.substring(j.indexOf 
("=")+1,j.length);   
}   
var returnValue = paraObj[paras.toLowerCase()];   
if(typeof(returnValue)=="undefined"){   
return "";   
}else{   
return returnValue;   
}   
} 
var cut_nums = 0;
if(!$.browser.msie) cut_nums = 10;
var screen_h = parseInt(document.documentElement.clientHeight)-95-parseInt(cut_nums);
$("#tree").css("height",screen_h);
var site_url = '/skin/';
menuId = parseInt(request("id"));
get_menu(menuId, 'tree', 0);
$("#menu_" + menuId).addClass("selected");
window.onresize=function()
{
    var widths = document.body.scrollWidth-220;
    var heights = document.documentElement.clientHeight-98;
    $("#right").height(heights).width(widths);
    $("#admin_left").height((heights+28));
	$('.window_1').css('left', (widths + 380 - $('.window_1').width())+'px');
}
 
window.onresize();
 
var speed = 1;
var px = 5;
/*$('#down').mouseover(function(){tree_down();MyMar=setInterval(tree_down,speed);});
$('#up').mouseover(function(){tree_up();MyMar=setInterval(tree_up,speed);});
$('#down').mouseout(function(){clearInterval(MyMar)});
$('#up').mouseout(function(){clearInterval(MyMar)});*/
function tree_up()
{
	var inner_height = $("#admin_left").height()-50;
	var height = $("#tree").height();
	var top = (height-inner_height)+inner_height*0.5;
	var nowtop = parseInt($("#tree_box").css('top').replace('px',''));
	if(-top < nowtop)
	{
		if(height > inner_height)
		{
			$("#tree_box").css('top',(parseInt($("#tree_box").css('top').replace('px',''))-px)+'px');
		}
	}
}
function tree_down()
{
	var nowtop = parseInt($("#tree_box").css('top').replace('px',''));
	if(nowtop<0)
	{
		$("#tree_box").css('top',(parseInt($("#tree_box").css('top').replace('px',''))+px)+'px');
	}
}
function resetf5(event) {
		event = event ? event : window.event;
		keycode = event.keyCode ? event.keyCode : event.charCode;
		if(keycode == 116 || (event.ctrlKey && keycode==82)) {
			parent.right.location.reload();
			if(document.all) {
				event.keyCode = 0;
				event.returnValue = false;
			} else {
					event.cancelBubble = true;
					event.preventDefault();
			}
		}
		if(keycode==113)
		{
			help_url();
			if(document.all) {
					event.keyCode = 0;
					event.returnValue = false;
			} else {
					event.cancelBubble = true;
					event.preventDefault();
			}
		}
}

$('#marquee').marquee('pointer').mouseover(function () {
	$(this).trigger('stop');
}).mouseout(function () {
	$(this).trigger('start');
}).mousemove(function (event) {
	if ($(this).data('drag') == true) {
		this.scrollLeft = $(this).data('scrollX') + ($(this).data('x') - event.clientX);
	}
}).mousedown(function (event) {
	$(this).data('drag', true).data('x', event.clientX).data('scrollX', this.scrollLeft);
}).mouseup(function () {
	$(this).data('drag', false);
});

function showIframe(url){
	var height = (document.body.scrollHeight > document.body.offsetHeight ? document.body.scrollHeight : document.body.offsetHeight) - 52;
	var width = (document.body.scrollWidth > document.body.offsetWidth ? document.body.scrollWidth : document.body.offsetWidth) - 80;
	$(".thickbox").attr("href", url + "?keepThis=true&modal=true&TB_iframe=true&height=" + height + "&width=" + width);
	$(".thickbox").trigger("click");
}
</script>
</body>
</html>