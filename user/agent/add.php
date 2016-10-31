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
$id=1;if(isset($_GET['id']) && $_GET['id']!="") $id = $_GET['id'];
$type='member';if(isset($_GET['type']) && $_GET['type']!="") $type = $_GET['type'];
$user = $DB->Select("select * from user where id = $id");
$user = $user[0];
$muser = $DB->Select("select * from user where id = $uid");
$muser = $muser[0];

$ximalimit = 1.8;
if($user['type']=="2" && $user['danshuangbian']=="0")
	$ximalimit = 0.9;

if(isset($_POST['dosubmit']) && $_POST['dosubmit']!="")
{
	$pid = $_POST['pid'];
	$ptype = $_POST['ptype'];
	$t = $_POST['type'];
	$uname = $_POST['userName'];
	$nname = $_POST['nickName'];
	$pwd = md5($_POST['passWord']);
	$lmoney = $_POST['liveMoney'];
	$lwash = $_POST['liveWash'];
	$cdate = date("Y-m-d H:i:s");
	$ximatype = $_POST['ximatype'];
	$DB->Query("insert into user(nickName,money,currentmoney,password,username,ip,type,pid,logintimes,createdate,xima,danshuangbian) values('$nname','$lmoney','$lmoney','$pwd','$uname','152.124.124.132','$t','$pid','0','$cdate','$lwash','$ximatype')");
	if($ptype!="0" && $lmoney!="0")
	{
		$DB->Query("update user set currentmoney=currentmoney-$lmoney where id = $pid");		
	}
	
	echo "<script>alert('添加成功！');</script>";
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
<form name="myform" method="post" action="add.php?type=<?=$type?>&id=<?=$id?>">
<input type="hidden" name="token" value="63e3bc2b0e7756920c2b361b6db59c04">
<table cellpadding="0" cellspacing="1" class="table_form">
	<caption>添加 <?php if($type=="member") echo "会员";else echo "代理";?> 账号</caption>
    <tr>
      <th><strong>上线：</strong></th>
      <td>
<?php if($user['type']=="1") echo "总";echo "代理：".$user['username'];?>	  </td>
    </tr>
    <tr>
  		<th width="20%"><strong>用户名：</strong></th>
        <td width="80%">
<input type="text" style="ime-mode:disabled" name="userName" id="userName" size="16" class=""  maxlength="20" require="true" datatype="limit|ajax" url="/user/agent/checkuser.php" min="2" max="20" msg="用户名不得少于2个字符超过20个字符|"/>		</td>
    </tr>
	        <tr>
	          <th><strong>别名：</strong></th>
	          <td>
              <input type="text" name="nickName" id="nickName" value="" size="12" class=""></td>
    </tr>
      <tr>
        <th><strong>密码：</strong><br />6到16个字符</th>
        <td>
        <input type="password" name="passWord" id="password" value="" size="16" class=""  maxlength="16" require="true" datatype="limit" min="6" max="16" msg="密码不得少于6个字符或超过16个字符！"/>         </td>
      </tr>
  	  <tr>
        <th><strong>确认密码：</strong></th>
        <td>
			<input type="password" name="rePassWord" id="pwdconfirm" value="" size="16" class=""  require="true" datatype="repeat" to="passWord" msg="两次输入的密码不一致"/>		</td>
      </tr>
	  	  <!--tr>
        <th><strong>电话：</strong></th>
        <td>
		<input type="text" name="tel" id="tel" size="16" maxlength="16" value="">        </td>
      </tr-->
          <tr>
            <th><strong>货币类型：</strong></th>
            <td>
人民币&nbsp;<span class="r">*货币类型一旦设置无法修改，而且所有下级也按此货币类型结算。</span>
</td>
          </tr>
      <tr>
        <th><strong>真人点数：</strong></th>
        <td>
<input boxid="auto" name="liveMoney" type="text" id="liveMoney" value="0" size="12" maxlength="12" require="true" datatype="double|range" min="0" max="<?php if($muser['type']=='0') echo "10000000000";
else echo $user['currentmoney'];?>" msg="真人点数限额最小为0|<?php if($muser['type']=='0') echo "真人点数无上限";
else echo "真人点数限额最大为".$user['currentmoney'];?>" msgid="liveMoney1"/>
&nbsp;<font style="color:red"> <?php if($muser['type']!='0'){ ?>/ <?=$user['currentmoney']?><?php } ?></font><span id="liveMoney1"></span>
&nbsp;&nbsp;<span id="bigLiveMoney">		</td>
    </tr>
      <tr>
		<th><strong>洗码类型：</strong></th>
		<td>
		<?php if($user['type']=="1"){?>
		<input type="radio" onclick="$('#liveWash2').html('1.8');$('#liveWash').val('1.8');$('#liveWash').attr('max','1.8');" name="ximatype" checked value="1">单边
		<input type="radio" onclick="$('#liveWash2').html('0.9');$('#liveWash').val('0.9');$('#liveWash').attr('max','0.9');" name="ximatype" value="0">双边
		<?php }elseif($user['type']=="2"){ ?>
		<?php if($user['danshuangbian']=="1"){?><input type="radio" name="ximatype" checked value="1">单边<?php } ?>
		<?php if($user['danshuangbian']=="0"){?><input type="radio" name="ximatype" checked value="0">双边<?php } ?>
		<?php } ?>
		</td>
	  </tr>
       <tr>
		<th><strong>真人洗码：</strong></th>
		<td>
<input boxid="auto" name="liveWash" id="liveWash" type="text" value="1.8" size="5" maxlength="5" require="true" datatype="double|range" min="0" max="<?=$ximalimit?>" msg="洗码比最小为0%|洗码比最大为双边0.9%，单边1.8%" msgid="liveWash1"/>
&nbsp;<font style="color:red">% / <span id="liveWash2"><?=$ximalimit?></span>%</font>
<!--<input type="button" onclick="washUp(1)" value="+" />
<input type="button" onclick="washUp(-1)" value="-" />-->
<input type="button" onclick="$('#liveWash').val($('#liveWash2').text())" value="复制" />
<span id="liveWash1"></span></td>
	  </tr>
        <tr>
          <th><strong>游戏权限设置：</strong></th>
          <td>
          <div style="width:350px">
          <table cellpadding="0" cellspacing="1" class="table_list align_c" id="gamesP">
          <caption>游戏权限设置</caption>
            <tr>
              <th>项目</th>
              <th>登录</th>
              <th>下注</th>
            </tr>
            <tr>
              <th>百家乐</th>
              <td><input type="checkbox" boxid="gamesLoginAll" checked name="gamesLogin[]" value="0"/></td>
              <td><input type="checkbox" checked boxid="gamesPlayGameAll" name="gamesPlayGame[]" value="0"/></td>
            </tr>
            <tr>
              <th>龙虎</th>
              <td><input type="checkbox" checked boxid="gamesLoginAll" name="gamesLogin[]" value="1"/></td>
              <td><input type="checkbox" checked boxid="gamesPlayGameAll" name="gamesPlayGame[]" value="1"/></td>
            </tr>
			<!--
            <tr>
              <th>星级百家乐</th>
              <td><input type="checkbox" boxid="gamesLoginAll" name="gamesLogin[]" value="2"/></td>
              <td><input type="checkbox" boxid="gamesPlayGameAll" name="gamesPlayGame[]" value="2"/></td>
            </tr>
            <tr>
              <th>保险百家乐</th>
              <td><input type="checkbox" boxid="gamesLoginAll" name="gamesLogin[]" value="3" disabled="disabled"/></td>
              <td><input type="checkbox" boxid="gamesPlayGameAll" name="gamesPlayGame[]" value="3" disabled="disabled"/></td>
            </tr>
            <tr>
              <th>急速龙虎</th>
              <td><input type="checkbox" boxid="gamesLoginAll" name="gamesLogin[]" value="4" disabled="disabled"/></td>
              <td><input type="checkbox" boxid="gamesPlayGameAll" name="gamesPlayGame[]" value="4" disabled="disabled"/></td>
            </tr>
            <tr>
              <th>轮盘</th>
              <td><input type="checkbox" boxid="gamesLoginAll" name="gamesLogin[]" value="5" disabled="disabled"/></td>
              <td><input type="checkbox" boxid="gamesPlayGameAll" name="gamesPlayGame[]" value="5" disabled="disabled"/></td>
            </tr>
            <tr>
              <th>骰宝盅</th>
              <td><input type="checkbox" boxid="gamesLoginAll" name="gamesLogin[]" value="6" disabled="disabled"/></td>
              <td><input type="checkbox" boxid="gamesPlayGameAll" name="gamesPlayGame[]" value="6" disabled="disabled"/></td>
            </tr>
            <tr>
              <th>斗牛</th>
              <td><input type="checkbox" boxid="gamesLoginAll" name="gamesLogin[]" value="7" disabled="disabled"/></td>
              <td><input type="checkbox" boxid="gamesPlayGameAll" name="gamesPlayGame[]" value="7" disabled="disabled"/></td>
            </tr>
            <tr>
              <th>排九</th>
              <td><input type="checkbox" boxid="gamesLoginAll" name="gamesLogin[]" value="8" disabled="disabled"/></td>
              <td><input type="checkbox" boxid="gamesPlayGameAll" name="gamesPlayGame[]" value="8" disabled="disabled"/></td>
            </tr>
            <tr>
              <th>三公</th>
              <td><input type="checkbox" boxid="gamesLoginAll" name="gamesLogin[]" value="9" disabled="disabled"/></td>
              <td><input type="checkbox" boxid="gamesPlayGameAll" name="gamesPlayGame[]" value="9" disabled="disabled"/></td>
            </tr>
            <tr>
              <th>加勒比海</th>
              <td><input type="checkbox" boxid="gamesLoginAll" name="gamesLogin[]" value="10" disabled="disabled"/></td>
              <td><input type="checkbox" boxid="gamesPlayGameAll" name="gamesPlayGame[]" value="10" disabled="disabled"/></td>
            </tr>-->
            <tr>
              <th>全选</th>
              <td><input type="checkbox" id="gamesLoginAll" onclick="checkall('gamesLoginAll')"/></td>
              <td><input type="checkbox" id="gamesPlayGameAll" onclick="checkall('gamesPlayGameAll')"/></td>
            </tr>
          </table>
    </div>    </tr>
        <tr>
          <th><strong>抽水设置：</strong></th>
          <td>
<input type="checkbox" boxid="customRate" name="customRate[]" value="1" disabled="disabled"/>庄抽0.05<br />
<input type="checkbox" boxid="customRate" name="customRate[]" value="2" disabled="disabled"/>庄对抽1<br />
<input type="checkbox" boxid="customRate" name="customRate[]" value="3" disabled="disabled"/>闲对抽1<br />
<input type="checkbox" boxid="customRate" name="customRate[]" value="4" disabled="disabled"/>和局抽1<br />
<input type="checkbox" boxid="customRate" name="customRate[]" value="5" disabled="disabled"/>龙抽0.05<br />
<input type="checkbox" boxid="customRate" name="customRate[]" value="6" disabled="disabled"/>虎抽0.05<br />
&nbsp;<span class="r">*如需修改抽水设置，请联系总公司。</span>
          </td>
          </tr>
        <!--<tr>
    	<th><strong>筹码：</strong></th>
    	<td>
<table cellpadding="0" cellspacing="1" class="table_list">
  <caption>
  筹码设置  </caption>
    <tr align="center">
        <th width="20"></th>
        <th>上限</th>
        <th>下限</th>
        <th><strong>筹码</strong></th>
    </tr>
<tr>
	<td colspan="4">
	  <input type="button" value="500 - 1000" onclick="autoSel('1,2,3')" />
	  <input type="button" value="1500 - 3000" onclick="autoSel('4,5,6')" />
	  <input type="button" value="5千 - 1万" onclick="autoSel('7,8,9')" />
	  <input type="button" value="2万,下限100 - 2万,下限500" onclick="autoSel('10,11,12')" />
	  <input type="button" value="2万 - 15万" onclick="autoSel('13,14,15')" />
	  <input type="button" value="20万" onclick="autoSel('16')" />	</td>
	</tr>
<tr>
<td><input type="checkbox" name="bjlQuota[]" id="checkbox" value="1"></td>
<td>500</td>
<td>10</td>
<td>10,30,50,300,500</td>
</tr>
<tr>
<td><input type="checkbox" name="bjlQuota[]" id="checkbox" value="3"></td>
<td>1000</td>
<td>30</td>
<td>20,50,100,200,500</td>
</tr>
<tr>
<td><input type="checkbox" name="bjlQuota[]" id="checkbox" value="5"></td>
<td>3000</td>
<td>50</td>
<td>10,50,300,500,3000</td>
</tr>
<tr>
<td><input type="checkbox" name="bjlQuota[]" id="checkbox" value="9"></td>
<td>10000</td>
<td>100</td>
<td>20,100,500,1000,10000</td>
</tr>
<tr>
<td><input type="checkbox" name="bjlQuota[]" id="checkbox" value="13"></td>
<td>20000</td>
<td>2000</td>
<td>100,500,1000,5000,10000</td>
</tr>
</table>
<script type="text/javascript">
function autoSel(a){
	$('input[name=bjlQuota\[\]]').attr('checked',false);
	items = a.split(",");
	for (i = 0; i < items.length; i++) {
		$('input[name=bjlQuota\[\]][value='+items[i]+']').attr('checked',true);
	}
}
$('input:checkbox').click(function() {
	return doit();
});
function doit(){
	if ($("input[name='bjlQuota[]']:checked").length > 3) {
		alert('会员最多只可以选择三种限红！');
		return false;
	}
}
function washUp(val){
	var newWash = parseFloat($("#liveWash").val()) + (0.1 * val);
	if (newWash < parseFloat($("#liveWash2").text()) && newWash >= 0) {
		$("#liveWash").val(round(newWash, 2));
	}
}
function round(v,e)   
{   
var   t=1;   
for(;e>0;t*=10,e--);   
for(;e<0;t/=10,e++);   
return   Math.round(v*t)/t;   
} 
</script></td>
    </tr>-->
        <tr>
		<th></th>
      	<td>
		<input type="hidden" name="forward" value="" >
		<input type="hidden" name="pid" value="<?=$user['id']?>" >
		<input type="hidden" name="ptype" value="<?=$user['type']?>" >
		<input type="hidden" name="type" value="<?php if($type=="member") echo "3";else echo "2";?>" >
		<input type="submit" name="dosubmit" value=" 添加 ">
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