<?php
header("content-type:text/html; charset=utf-8");
include '../user/inc/conn.php';
include '../user/inc/function.php';
session_set_cookie_params(SESSION_LIFE_TIME);
session_start();

if (session_is_registered("isadmin") && session_is_registered("adminname")) {
    unset($_SESSION['isadmin']);
    unset($_SESSION['adminname']);
    unset($_SESSION['uid']);
    unset($_SESSION['admintab']);
}

if (isset($_GET['action']) && $_GET['action'] == "login") {
    $name = post_check(trim($_POST['userName']));
    $checkCode = post_check(trim($_POST['checkCode']));

    $pwd = md5(post_check($_POST['password']));

    if ($checkCode != $_SESSION['check_auth']) {
        echo "<script>alert('验证码错误!');history.go(-1);</script>";
        exit();
    }
    $today = date("Y-m-d H:i:s", time() - 1800);
    $query = "select * from user where username='" . $name . "' and password='" . $pwd . "' and type=0";

    // echo $query;exit();

    $row = $database->query($query)->fetch();
    if ($row) {
        if (!session_is_registered("isadmin")) {

            $_SESSION['isadmin'] = 1;
            $_SESSION['uid'] = $row["id"];
            $_SESSION['adminname'] = $name;
            $_SESSION['admintab'] = $row["type"];

            $date = date("Y-m-d H:i:s", time());
            $ip = get_ip();
            $query = "update user set ip='$ip',lastlogintime='$date',logintimes=logintimes+1 where id=" . $row["id"];
            $database->query($query);

        }


        echo "<script>window.location='main.php';</script>";

    } else {
        echo "<script>alert('用户名密码错误或已被其他人登录!');history.go(-1);</script>";


    }

    exit();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head id="Head1"><title>王者至尊管理网</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="../user/css/text.css" rel="Stylesheet" type="text/css"/>
    <script type="text/javascript" src="../user/js/jquery.js"></script>
    <style type="text/css">
        BODY {
            background-color: #191919;
            background-repeat: no-repeat;
            margin-top: 0px;
            text-align: center;
        }

        .logintext {
            color: #fff;
            background-color: #0F0F0F;
            border: 0px;
            font-family: Tahoma;
            font-size: 13px;
            text-decoration: none;
            margin-left: 5px;
        }

        .message {
            color: #fff;
            border: 0px;
            font-family: Tahoma;
            font-size: 13px;
            text-decoration: none;
            margin-left: 5px;
        }
    </style>

    <script language="javascript" type="text/javascript">
        <!--
        function doClickKeypad(pTargetID, pKeyValue) {
            var pwdObj = document.getElementById(pTargetID);
            pwdObj.value = pwdObj.value + pKeyValue.toLowerCase();
        }
        function doBackSpace(pTargetID) {
            var pwdObj = document.getElementById(pTargetID);
            pwdObj.value = pwdObj.value.substr(0, pwdObj.value.length - 1);
        }
        function doResetPasswd(pTargetID) {
            var pwdObj = document.getElementById(pTargetID);
            pwdObj.value = "";
        }
        -->

    </script>
    <script language="javascript">
        function setfocus() {
            if (document.form.userName.value == "") {
                document.form.userName.focus();
            }
            else {
                document.form.passWord.select();
            }
        }
        function chkinput(form) {
            if (form.userName.value == "") {
                alert("请输入用户名!");
                form.userName.select();
                return (false);
            }
            if (form.passWord.value == "") {
                alert("请输入密码!");
                form.passWord.select();
                return (false);
            }
            if (form.checkCode.value == "") {
                alert("请输入验证码!");
                form.checkCode.select();
                return (false);
            }

            return (true);
        }
    </script>

</head>
<body>
<div align="center">
    <form name="form1" method="post" onsubmit="return chkinput(this)" action="login.php?action=login" id="form1">

        <table width="1000" height="700" border="0" cellpadding="0" cellspacing="0"
               background="../user/images/loginbg.jpg">
            <tr>
                <td colspan="2" width="1000" height="338">
                </td>
            </tr>
            <tr>
                <td height="362" width="434">
                </td>
                <td height="362" width="564">
                    <table height="362" width="564" border="0" cellpadding="0" cellspacing="0"
                           style="text-align: left;">
                        <tr>
                            <td height="39">
                                <input name="userName" type="text" id="userName" class="logintext"
                                       style="width:178px;"/>
                            </td>
                        </tr>
                        <tr>
                            <td height="36">
                                <input name="password" type="password" id="passWord" class="logintext"
                                       style="width: 178px"/>
                            </td>
                        </tr>
                        <tr>
                            <td height="35">
                                <input name="checkCode" type="text" id="checkCode" class="logintext"
                                       style="width:87px;"/>
                                &nbsp;<img align="absmiddle" style="width:84px; height:26px" src="../user/vcode.php"/>
                            </td>
                        </tr>
                        <tr>
                            <td height="23">
                                <span id="error_msg" class="message" style="color:White;"></span>
                            </td>
                        </tr>
                        <tr>
                            <td height="37">
                                <input type="image" name="Button1" id="Button1"
                                       onclick="return chkinput(document.form1)"
                                       onmouseover="this.src='../user/images/button2.jpg'"
                                       onmouseout="this.src='../user/images/button1.jpg'"
                                       src="../user/images/button1.jpg" style="border-width:0px;"/>
                            </td>
                        </tr>
                        <tr>
                            <td height="192">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div>
        </div>
    </form>
</div>

<map name="Map" id="Map">
    <area shape="rect" coords="1,1,32,22" href="#"/>
</map>

<map name="Map2" id="Map2">
    <area shape="rect" coords="1,1,32,22" href="/login.html"/>
</map>
</body>
<script type="text/javascript">
    // reCheckCode();
    function reCheckCode() {
        //jQuery("#checkCodeImg").attr('src', '/index/checkcode/r/'+Math.random()*5);
        //jQuery("#checkCodeImg").attr('src', 'images/3.png');
    }
</script>
</html>