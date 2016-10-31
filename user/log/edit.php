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
$nid=1;if(isset($_GET['id']) && $_GET['id']!="") $nid = $_GET['id'];


$notice = $DB->Select("select * from notice where id = $nid");
$notice = $notice[0];

if(isset($_POST['dosubmit']) && $_POST['dosubmit']!="")
{
	$pid = $_POST['pid'];
	$title = stripslashes($_POST['title']);
	$content = stripslashes($_POST['content']);
	// $type = $_POST['notype'];
	// echo $type;exit();
	$state = $_POST['state'];
	$game = $_POST['game'];
	$stime = $_POST['stime'];
	$etime = $_POST['etime'];
	$DB->Query("update notice set title='$title',content='$content',game='$game',state='$state',stime='$stime',etime='$etime'  where id=$pid");
	echo "<script>alert('修改成功！');window.location='/user/log/notice.php'</script>";
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link href="/user/css/system.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/user/js/jquery.js"></script>
<script type="text/javaScript" src="/user/js/common.js"></script>

<script type="text/javascript" src="/user/js/validator.js"></script>
<script type="text/javascript" src="/user/js/member/default.js"></script>
<style type="text/css">
#password_notice{color:red}
.table_list th{ line-height:16px; height:16px}
#bigLiveMoney{color:red;font-weight:bold;}
.align_c td{ text-align:center}
</style>
</head>
<script type="text/javascript">
$(function() {
	$('form').checkForm(1);
	$("input[boxid='gamesLoginAll']").click(function(){
		var index = $("input[boxid='gamesLoginAll']").index(this);
		$("input[boxid='gamesPlayGameAll']").eq(index).attr("checked", false);
	});
	$("#liveMoney").keyup(function(){
		$("#bigLiveMoney").text(ChangeToBig($("#liveMoney").val()));
	});
	$("input[boxid='auto']").focus(function(){
		if ($(this).val() <= 0) {
			$(this).val("");
		}
	});
	$("input[boxid='auto']").blur(function(){
		if ($(this).val() <= 0) {
			$(this).val("0");
		}
	});
});
</script>
<link rel="stylesheet" type="text/css" href="/user/css/calendar-blue.css"/>
<script type="text/javascript" src="/user/js/calendar.js"></script>
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
<form name="myform" method="post" action="edit.php?id=<?=$nid?>">
<table cellpadding="0" cellspacing="1" class="table_form">
	<caption>修改 公告</caption>
    <tr>
  		<th width="20%"><strong>游戏：</strong></th>
        <td width="80%"><select name="game"><option value="所有游戏" selected="selected">所有游戏</option></select>
		</td>
    </tr>
	<tr>
  		<th width="20%"><strong>状态：</strong></th>
        <td width="80%"><select name="state"><option value="发布中" selected="selected">发布中</option></select>
		</td>
    </tr>
	<tr>
  		<th width="20%"><strong>类型：</strong></th>
        <td width="80%"><select name="notype"><option value="代理网公告" <?php if($notice['type']=="代理网公告") echo 'selected="selected"';?>>代理网公告</option>
		<option value="会员网公告" <?php if($notice['type']=="会员网公告") echo 'selected="selected"';?>>会员网公告</option>

		</select>
		</td>
    </tr>
	<tr>
  		<th width="20%"><strong>标题：</strong></th>
        <td width="80%"><input type="text" name="title" id="title" value="<?php echo $notice['title'];?>" size="170" class="">
		</td>
    </tr>
	<tr>
  		<th width="20%"><strong>内容：</strong></th>
        <td width="80%"><input type="text" name="content" id="content" value="<?php echo $notice['content'];?>" size="170" class="">
		</td>
    </tr>
	<tr>
  		<th width="20%"><strong>开始时间：</strong></th>
        <td width="80%"><input type="text" name="stime" id="stime" value="<?php echo $notice['stime'];?>" size="25" class="">
		</td>
    </tr>
	<tr>
  		<th width="20%"><strong>结束时间：</strong></th>
        <td width="80%"><input type="text" name="etime" id="etime" value="<?php echo $notice['etime'];?>" size="25" class="">
		</td>
    </tr>
	<script>        
	Calendar.setup({
		inputField     :    "stime",
		ifFormat       :    "%Y-%m-%d %H:%M:%S",
		showsTime      :    true,
		align          :    "B1",
		singleClick    :    true
	});
	Calendar.setup({
		inputField     :    "etime",
		ifFormat       :    "%Y-%m-%d %H:%M:%S",
		showsTime      :    true,
		align          :    "B1",
		singleClick    :    true
	});
	</script>        

        
        <tr>
		<th></th>
      	<td>
		<input type="hidden" name="pid" value="<?=$notice['id']?>" >
		<input type="submit" name="dosubmit" value=" 修改 ">
    	<input type="reset" name="reset" value=" 重置 "></td>
		</td>
      </tr>
</table>
</form>
<script type="text/javascript">
<!--
function checkall(fieldid)
{
	if(fieldid==null)
	{
		if($('#checkbox').attr('checked')==false)
		{
			$('input[name=bjlQuota\[\]]').attr('checked',true);
		}
		else
		{
			$('input[name=bjlQuota\[\]]').attr('checked',false);
		}
	}
	else
	{
		var fieldids = '#'+fieldid;
		var inputfieldids = 'input[boxid='+fieldid+']';
		$(inputfieldids).each(function(i, item){
			if ($(this).attr('disabled') == false){
				$(this).attr('checked', $(fieldids).attr('checked'));
			}
		});
	}
}
function inputLiveWash(obj){
	$("#liveWash").val($(obj).val().replace('%', ''));
}
//-->
</script>
</body>
</html>