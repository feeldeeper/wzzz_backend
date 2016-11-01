<?php
header("Content-type: text/html; charset=utf-8");
include("../inc/function.php");
include("../inc/conn.php");


session_set_cookie_params(SESSION_LIFE_TIME);
session_start();
$suid = $_SESSION['uid'];
if (isset($suid) && $suid != "" && $suid != "0") {
} else {
    echo "<script>alert('请先登录!');window.location='../login.php';</script>";
    exit();
}
$username = $_SESSION['adminname'];
$uid = $_GET['id'];
$user = $database->query("select * from user where id = $uid")->fetch();


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"/>
    <link href="/user/css/system.css" rel="stylesheet" type="text/css">
    <script type="text/javaScript" src="/user/js/jquery.js"></script>
    <script type="text/javaScript" src="/user/js/common.js"></script>
    <style type="text/css">
        .align_c td {
            text-align: center
        }
    </style>
    <script type="text/javascript">
        function lock(id, type, obj) {
            redirect('/user/submanage/lock/id/' + id + '/type/' + type + '/username/' + $(obj).parents("tr").find("td").eq(1).text());
        }
        function liveLock(id, type, obj) {
            redirect('/user/submanage/lock/model/1/id/' + id + '/type/' + type + '/username/' + $(obj).parents("tr").find("td").eq(1).text());
        }
    </script>
</head>
<body>
<table cellpadding="0" cellspacing="1" class="table_list">
    <caption>
        <?= $user['username'] ?>代理信息
    </caption>
    <tr>
        <td>总代：<?= $user['username'] ?></td>
        <td>名称：<?= $user['nickName'] ?></td>
        <td>状态：开启</td>
        <td>投注：开启</td>
    </tr>
    <tr>
        <td>真人点数：<?= $user['currentmoney'] ?></td>
        <td>真人占成：<?= $user['zhancheng'] ?>%</td>
        <td>真人洗码：<?= $user['xima'] ?>%</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td colspan="4">扩展功能：<span class="align_c">
          <input class="button_style" onclick="redirect('/user/agent/submanageadd.php?id=<?= $user['id'] ?>')"
                 type='button' value='添加子账号'/>
    	</span></td>
    </tr>
</table>
<table cellpadding="0" cellspacing="1" class="table_list align_c">
    <form method="post" name="myform">
        <caption>
            <?= $user['username'] ?>的子账号列表
        </caption>
        <tr align="center">
            <th width="5%"><strong>选中</strong></th>
            <th width="10%">
                <strong>
                    <a href="http://admin.usemm.com/phpcms/admin.php?mod=member&file=member&action=manage&orderby=m.username ASC"
                       title="按用户名排序">用户名</a> </strong></th>
            <th width="20%"><strong>名称</strong></th>
            <th width="10%"><strong>父用户名</strong></th>
            <th width="20%">父名称</th>
            <th width="60">
                <strong>
                    状态 </strong></th>
            <th width="80">新建时间</th>
            <th><strong>管理操作</strong></th>
        </tr>
</table>
<div class="button_box">
    <input name='button2' type='button' class="button_style" id='chkall' onclick='checkall()' value='全选'>
</div>

<div id="pages">
    <div id="pages">总数：<b>0</b>&nbsp;
        每页：<b>20</b>
        <a href="/user/agent/submanage.php?id=<?= $user['id'] ?>&page=1">首页</a><a
            href="/user/agent/submanage.php?id=<?= $user['id'] ?>&page=1">上一页</a><a
            href="/user/agent/submanage.php?id=<?= $user['id'] ?>&page=1">下一页</a><a
            href="/user/agent/submanage.php?id=<?= $user['id'] ?>&page=1">尾页</a>
        页次：<b><font color="red">1</font>/0</b>
        <input type="text" name="page" id="page" size="4"
               onKeyDown="if(event.keyCode==13) {redirect('/user/agent/submanage.php?id=<?= $user['id'] ?>&page='+this.value); return false;}">
        <input type="button" value="转到" class="button_style"
               onclick="redirect('/user/agent/submanage.php?id=<?= $user['id'] ?>&page='+$('#page').val())"></div>
</div>
</form>

<script type="text/javascript">
</script>
</body>
</html>