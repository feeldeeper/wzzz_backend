<?php 
	header("content-type:text/html; charset=utf-8");
	include 'inc/conn.php';
	session_set_cookie_params(SESSION_LIFE_TIME);
	session_start();
	$name=$_SESSION['adminname'];
	if(!isset($name) || $name=="")
	{
		echo "<script>alert('请先登录!');window.location='login.php';</script>";
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>果博东方管理网</title>
<link rel="stylesheet" type="text/css" href="css/system.css"/>
<link rel="stylesheet" type="text/css" href="css/tree.css"/>
<link rel="stylesheet" type="text/css" href="css/thickbox.css"/>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javaScript" src="js/common.js"></script>
<script type="text/javascript" src="js/menu.js"></script>
<script type="text/javascript" src="js/jquery.marquee.js"></script>
<script type="text/javascript" src="js/thickbox-compressed.js"></script>
<script type="text/javascript" src="js/jquery.liu.select.js"></script>
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
</script>
<style> 
body {
	width:100%;
	background:#ECF7FE url(images/bg_body.jpg) repeat-y top left;
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
#position {background: url(images/position.gif) no-repeat 5px -32px; text-align:left; color:#069;padding:0 0 0 24px;height:30px; line-height:30px; width:500px; float:left;}
#position strong {color:#069;}
.pos{margin-left:10px;margin-top:10px; text-align:left;color:#2e6ab1;}
#position a:link, #position a:visited,.pos a:link, .pos a:visited {color:#069; background:url(images/position.gif) no-repeat right -70px; padding-right:16px; text-decoration:none}
#position a:hover, #position a:active,.pos a:hover, .pos a:active {color:#069; background:url(images/position.gif) no-repeat right -70px; padding-right:16px;}
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
  <div class="logo"><a href="#" target="_blank"><img src="images/logo.jpg" width="220" height="58" border="0"/></a></div>
  <p id="info_bar"> 用户名称：<strong class="font_arial white">
    <?php echo $_SESSION["adminname"];;?>    </strong>，角色：
    <?php if($name=="admin") echo "超级管理员";else echo "管理员";?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="logout.php" class="white">退出登入</a>&nbsp;&nbsp;|&nbsp;&nbsp;选择语言：
  <select name="select" id="lang">
    <option value="zh-CN">简体中文</option>
  </select></p>
  <div id="menu">
    <ul>
	<?php if($name=="admin"){?> <?php } ?>
      <li><a href="admin.php" onclick="get_menu(1, 'tree', 0)" id="menu_1" class="menu" target="right" alt="游戏记录"><span>管理员管理</span></li><li><a href="baobiao.php" onclick="get_menu(1, 'tree', 0)" id="menu_1" class="menu" target="right" alt="游戏记录"><span>用户报表</span></li><li><a href="zhezhao.php" onclick="get_menu(1, 'tree', 0)" id="menu_1" class="menu" target="right" alt="游戏记录"><span>遮罩控制</span></li><li><a href="ptouzhu.php" onclick="get_menu(1, 'tree', 0)" id="menu_1" class="menu" target="right" alt="游戏记录"><span>批量添加记录</span></a></li><li><a href="touzhu.php" onclick="get_menu(2, 'tree', 0)" id="menu_1" class="menu" alt="游戏报表" target="right"><span>实时投注</span></a></li><li><a href="user.php" onclick="get_menu(0, 'tree', 0, 1)" id="menu_6" class="menu" target="right" alt="用户管理"><span>用户管理</span></a></li>
	  <li><a href="ntouzhu.php" onclick="get_menu(1, 'tree', 0)" id="menu_2" class="menu" target="right" alt="游戏记录"><span>新投注</span></a></li>
	  </ul>
  </div>
</div>

<div id="admin_right" style="width:100%;left:0px;position:relative;">
  <div id="inner" class="inner" style="">
  
 
 
 

<div id="position" style="width:100%;"><marquee scrollamount="1" id="marquee" scrollDelay="5" onmouseover="this.stop()" onmouseout="this.start()">
<a target='right'>[公告]&nbsp;为了尊重用户隐私权，系统更新了“昵称”功能，您可以在游戏大厅[用户信息一栏]设置一个喜欢的昵称，下注时座位号即显示您的昵称。新增加视频线路选择功能，如遇视频卡，请切换至一个合适的线路。新增加龙虎抽水，系统增加了对会员抽水功能，如需开启此项功能，请联系总公司操作。 2013-10-20 17:10:33</a>&nbsp;&nbsp;</marquee></div>
    <div>
      <iframe name="right" id="right" frameborder="0" src="ntouzhu.php" style="height:100%;width:100%;z-index:111;background-color:#ffffff"></iframe>
    </div>
  
  </div>
</div>
 
 
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
    $("#right").height(heights);
    //$("#admin_left").height((heights+28));
	//$('.window_1').css('left', (widths + 380 - $('.window_1').width())+'px');
}
 
window.onresize();
  

function showIframe(url){
	var height = (document.body.scrollHeight > document.body.offsetHeight ? document.body.scrollHeight : document.body.offsetHeight) - 52;
	var width = (document.body.scrollWidth > document.body.offsetWidth ? document.body.scrollWidth : document.body.offsetWidth) - 80;
	$(".thickbox").attr("href", url + "?keepThis=true&modal=true&TB_iframe=true&height=" + height + "");
	$(".thickbox").trigger("click");
}
</script>
</body>
</html>