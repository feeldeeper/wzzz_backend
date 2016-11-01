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

        .table_list {
            width: 300px;
            float: left;
            margin: 5px 0 0 5px
        }
    </style>
    <script type="text/javascript">
        function lock(id, type, obj) {
            redirect('/user/manage/lock/id/' + id + '/type/' + type + '/username/' + $(obj).parents("tr").find("td").eq(1).text());
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
            <input type='button' class="button_style" onclick='location.reload();' value='刷新本页'>
        </td>
    </tr>
</table>

<table cellpadding="0" cellspacing="1" class="table_list align_c">
    <form method="post" name="myform">
        <caption><?= $user['username'] ?>权限设置</caption>
        <tr align="center">
            <th width="50"><strong>选中</strong></th>
            <th><strong><strong>权限</strong></th>
        </tr>
        <tr>
            <td><input type="checkbox" name="id[]" id="checkbox" value="1"></td>
            <td>后台访问</td>
        </tr>
        <tr>
            <td><input type="checkbox" name="id[]" id="checkbox" value="2"></td>
            <td>日志查看</td>
        </tr>
        <tr>
            <td><input type="checkbox" name="id[]" id="checkbox" value="3"></td>
            <td>会员查看</td>
        </tr>
        <tr>
            <td><input type="checkbox" name="id[]" id="checkbox" value="4"></td>
            <td>会员修改</td>
        </tr>
        <tr>
            <td><input type="checkbox" name="id[]" id="checkbox" value="5"></td>
            <td>会员添加</td>
        </tr>
        <tr>
            <td><input type="checkbox" name="id[]" id="checkbox" value="11"></td>
            <td>会员权限设置</td>
        </tr>
        <tr>
            <td><input type="checkbox" name="id[]" id="checkbox" value="12"></td>
            <td>会员加减点</td>
        </tr>
        <tr>
            <td><input type="checkbox" name="id[]" id="checkbox" value="13"></td>
            <td>游戏记录</td>
        </tr>
        <tr>
            <td><input type="checkbox" name="id[]" id="checkbox" value="14"></td>
            <td>真人百家乐报表查看</td>
        </tr>
        <tr>
            <td><input type="checkbox" name="id[]" id="checkbox" value="15"></td>
            <td>子账号管理</td>
        </tr>
        <tr>
            <td><input type="checkbox" name="id[]" id="checkbox" value="16"></td>
            <td>在线会员</td>
        </tr>
        <tr>
            <td><input type="checkbox" name="id[]" id="checkbox" value="17"></td>
            <td>真人总报表查看</td>
        </tr>
        <tr>
            <td><input type="checkbox" name="id[]" id="checkbox" value="18"></td>
            <td>刷新系统</td>
        </tr>
        <tr>
            <td><input type="checkbox" name="id[]" id="checkbox" value="19"></td>
            <td>台面管理</td>
        </tr>
        <tr>
            <td><input type="checkbox" name="id[]" id="checkbox" value="20"></td>
            <td>公告管理</td>
        </tr>
</table>
<div class="button_box" style="clear:both">
    <input name='button2' type='button' class="button_style" id='chkall' onclick='checkall()' value='全选'>
</div>

<div class="button_box" style="text-align:center; width:200px; float:left; margin:5px 0">
    <input type="submit" name="dosubmit" value=" 提交 ">&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" name="reset"
                                                                                     value=" 重置 ">
</div>

</form>

</body>
</html>