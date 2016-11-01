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
    <script type="text/javascript" src="/user/js/validator.js"></script>
    <style type="text/css">
        #password_notice {
            color: red
        }

        .table_list th {
            line-height: 16px;
            height: 16px
        }
    </style>
</head>
<body>
<table cellpadding="0" cellspacing="1" class="table_info">
    <caption>
        功能导航
    </caption>
    <tr>
        <td>
            <input type='button' class="button_style" onclick='history.go(-1);' value='返回至上一页'>
            <input type='button' class="button_style" onclick='history.go(1);' value='前进至下一页'>
            <input type='button' class="button_style" onclick='location.reload();' value='刷新本页'>
        </td>
    </tr>
</table>

<form name="myform" method="post" action="">
    <table cellpadding="0" cellspacing="1" class="table_form">
        <caption>
            添加<?= $user['username'] ?>的子账号信息
        </caption>

        <tr>
            <th width="20%"><strong>用户名：</strong></th>
            <td width="80%"><input type="text" style="ime-mode:disabled" name="userName" id="userName" size="16"
                                   class="" maxlength="20" require="true" datatype="limit|ajax"
                                   url="/user/agent/checkuser.php" min="2" max="20" msg="用户名不得少于2个字符超过20个字符|"/></td>
        </tr>
        <tr>
            <th><strong>用户别名：</strong></th>
            <td>
                <input type="text" name="nickName" id="nickName" size="12" class=""></td>
        </tr>
        <tr>
            <th><strong>密码：</strong><br></th>
            <td>
                <input type="password" name="passWord" id="password" value="" size="16" class="" maxlength="16"
                       require="true" datatype="limit" min="6" max="16" msg="密码不得少于6个字符或超过16个字符！"/></td>
        </tr>

        <tr>
            <th><strong>确认密码：</strong></th>
            <td>
                <input type="password" name="rePassWord" id="pwdconfirm" value="" size="16" class="" require="true"
                       datatype="repeat" to="passWord" msg="两次输入的密码不一致"/></td>
        </tr>
        <tr>
            <th><strong>电话：</strong></th>
            <td>
                <input type="text" name="tel" id="tel" size="30" maxlength="50"></td>
        </tr>

        <tr>
            <th></th>
            <td>
                <input type="submit" value=" 添加 ">
                <input type="reset" value=" 重置 ">
            </td>
            </td>
        </tr>
    </table>
</form>

<table cellpadding="0" cellspacing="1" class="table_info">
    <caption>提示信息</caption>
    <tr>
        <td>
            1、如果筹码前面打钩小框显“灰色”不可编辑状态，那么就代表此账号下有用户正在使用此筹码，无法修改，如需修改，请先去除下级用户的相应筹码。<br/>
        </td>
    </tr>
</table>

<script LANGUAGE="javascript">
    <!--
    $('form').checkForm(1);
    function checkall(fieldid) {
        if (fieldid == null) {
            if ($('#checkbox').attr('checked') == false) {
                $('input[name=bjlQuota\[\]]').attr('checked', true);
            }
            else {
                $('input[name=bjlQuota\[\]]').attr('checked', false);
            }
        }
        else {
            var fieldids = '#' + fieldid;
            var inputfieldids = 'input[boxid=' + fieldid + ']';
            if ($(fieldids).attr('checked') == false) {
                $(inputfieldids).attr('checked', true);
            }
            else {
                $(inputfieldids).attr('checked', false);
            }
        }
    }
    function inputLiveWash(obj) {
        $("#liveWash").val($(obj).val().replace('%', ''));
    }
    //-->
</script>
</body>
</html>