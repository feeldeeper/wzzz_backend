<?php 
header("Content-type: text/html; charset=utf-8");
include("../../user/inc/function.php");
include("../../user/inc/conn.php");
include("../../user/inc/sql.class.php");
$DB = new MySql($conn);
session_set_cookie_params(SESSION_LIFE_TIME);
session_start();
$suid = $_SESSION['uid'];
if(isset($suid) && $suid!="" && $suid!="0")
{
}
else{ echo "<script>alert('请先登录!');window.location='../login.php';</script>";exit();}

$notice = $DB->Select("select * from notice");


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link href="/user/css/system.css" rel="stylesheet" type="text/css">
<script type="text/javaScript" src="/user/js/jquery.js"></script>
<script type="text/javaScript" src="/user/js/common.js"></script>
<script type="text/javascript" src="/user/js/jquery.liu.select.js"></script>
<style type="text/css">
.align_c td{text-align:center}
</style>
</head>
<body>
<script type="text/javascript">
$(function(){
});
</script>
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
<table cellpadding="0" cellspacing="1" class="table_list align_c">
  <caption>系统公告管理</caption>
  </tr>
    <tr>
        <th width="5%"><strong>ID</strong></th>
        <th width="10%"><strong>类型</strong></th>
        <th>游戏</th>
	<th>状态</th>
        <th><strong>标题</strong></th>
        <th><strong>内容</strong></th>
        <th><strong>开始时间</strong></th>
        <th><strong>结束时间</strong></th>
        <th>操作</th>
    </tr>
	<?php foreach($notice as $no){?>
		<tr>
		  <td><?=$no['id']?></td>
		  <td><?=$no['type']?></td>
		  <td><?=$no['game']?></td>
		  <td><?=$no['state']?></td>
		  <td><?=$no['title']?></td>
		  <td><?=$no['content']?></td>
		  <td><?=$no['stime']?></td>
		  <td><?=$no['etime']?></td>
		  <td><a href="/admin/log/edit.php?id=<?=$no['id']?>">修改</a></td>
	    </tr>
	<?php } ?>
	</table>
<div id="pages">
<div id="pages">总数：<b><?=count($notice)?></b>&nbsp;
每页：<b>20</b>
<a href="notice.php?page=1">首页</a><a href="notice.php?page=1">上一页</a><a href="notice.php?page=1">下一页</a><a href="notice.php?page=1">尾页</a>
页次：<b><font color="red">1</font>/1</b>
<input type="text" name="page" id="page" size="4" onKeyDown="if(event.keyCode==13) {redirect('/admin/log/notice.php?page='+this.value); return false;}"> 
<input type="button" value="转到" class="button_style" onclick="redirect('/admin/log/notice.php?page='+$('#page').val())"></div></div>
</body>
</html>