<?php 
header("Content-type: text/html; charset=utf-8");
include("../../user/inc/function.php");
include("../../user/inc/conn.php");
include("../../user/inc/sql.class.php");
$DB = new MySql($conn);
session_set_cookie_params(SESSION_LIFE_TIME);
session_start();
$uid = $_SESSION['uid'];
if(isset($uid) && $uid!="" && $uid!="0")
{
}
else{ echo "<script>alert('请先登录!');window.location='../login.php';</script>";exit();}
$id=1;if(isset($_GET['id']) && $_GET['id']!="") $id = $_GET['id'];

$tb = $DB->Select("select * from tablet where tab_id = $id");
$tb = $tb[0];


if(isset($_POST['dosubmit']) && $_POST['dosubmit']!="")
{
	$tid = $_GET['id'];
	$djs = $_POST['djs'];
	$telMax = $_POST['telMax'];
	$telMin = $_POST['telMin'];
	$pairMax = $_POST['pairMax'];
	$tieMax = $_POST['tieMax'];
	$diffMax = $_POST['diffMax'];
	$chip = $_POST['chip'];
	$DB->Query("update tablet set injecttime='$djs',telMax='$telMax',telMin='$telMin',pairMax='$pairMax',tieMax='$tieMax',chip='$chip',diffMax='$diffMax' where tab_id=$tid");
	echo "<script>alert('修改成功！');window.location='/admin/table/index.php?id=$tid';</script>";
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
<form name="myform" method="post" action="edit.php?id=<?=$id?>">
<input type="hidden" name="token" value="63e3bc2b0e7756920c2b361b6db59c04">
<table cellpadding="0" cellspacing="1" class="table_form">
	<caption>修改 百家乐 台号 <?=$tb['gameid']?></caption>
    <tr>
      <th><strong>台号：</strong></th>
      <td><?=$tb['gameid']?>  </td>
    </tr>
    <tr>
  		<th width="20%"><strong>类型：</strong></th>
        <td width="80%"><?=$tb['gameType']?>
		</td>
    </tr>
	        <tr>
	          <th><strong>倒计时：</strong></th>
	          <td>
              <input type="text" name="djs" id="djs" value="<?=$tb['injecttime']?>" size="12" class=""></td>
    </tr>
	<tr>
	          <th><strong>上限：</strong></th>
	          <td>
              <input type="text" name="telMax" id="telMax" value="<?=$tb['telMax']?>" size="12" class=""></td>
    </tr>
	<tr>
	          <th><strong>下限：</strong></th>
	          <td>
              <input type="text" name="telMin" id="telMin" value="<?=$tb['telMin']?>" size="12" class=""></td>
    </tr>
	<tr>
	          <th><strong>和上限：</strong></th>
	          <td>
              <input type="text" name="tieMax" id="tieMax" value="<?=$tb['tieMax']?>" size="12" class=""></td>
    </tr>
	<tr>
	          <th><strong>对子上限：</strong></th>
	          <td>
              <input type="text" name="pairMax" id="pairMax" value="<?=$tb['pairMax']?>" size="12" class=""></td>
    </tr>
	<tr>
	          <th><strong>庄闲差上限：</strong></th>
	          <td>
              <input type="text" name="diffMax" id="diffMax" value="<?=$tb['diffMax']?>" size="12" class=""></td>
    </tr>
    <tr>
	          <th><strong>筹码：</strong></th>
	          <td>
              <input type="text" name="chip" id="chip" value="<?=$tb['chip']?>" size="40" class=""></td>
    </tr>
         
        <tr>
		<th></th>
      	<td>
		<input type="hidden" name="forward" value="" >
		<input type="hidden" name="tid" value="<?=$tb['id']?>" >
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