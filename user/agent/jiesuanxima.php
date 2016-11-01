<?php
header("Content-type: text/html; charset=utf-8");
include("../inc/function.php");
include("../inc/conn.php");


session_set_cookie_params(SESSION_LIFE_TIME);
session_start();
$uid = $_SESSION['uid'];
if (!empty($uid)) {
} else {
    echo "<script>alert('请先登录!');window.location='../login.php';</script>";
    exit();
}

if (isset($_POST['dosubmit']) && $_POST['dosubmit'] != "") {
    $pid = $_POST['pid'];
    $puser = $database->query("select * from user where id = $pid")->fetch();
    $ppid = $puser['pid'];
    $auser = $database->query("select * from user where id = $ppid")->fetch();
    $ausername = $auser['username'];

    $xima = $puser['xima'];
    $currentxima = $puser['currentxima'];


    $uname = $puser['username'];
    $nname = $puser['nickName'];

    $lmoney = $_POST['liveMoney'];

    $restxima = floatval($currentxima) - floatval($lmoney);

    $restyongjin = number_format(($restxima * floatval($xima) * 0.01), 2);
    $lyongjin = number_format((floatval($lmoney) * floatval($xima) * 0.01), 2);

    $cdate = date("Y-m-d H:i:s");
    $sql = "insert into ximalog(uid,username,nickname,shangji,createdate,settledxm,settledyj,unsettledxm,unsettledyj) values('$pid','$uname','$nname','$ausername','$cdate','$lmoney','$lyongjin','$restxima','$restyongjin')";
    $database->query($sql);
    // echo $sql;exit();
    if ($lmoney != "0") {
        $database->query("update user set currentxima=currentxima-$lmoney where id = $pid");
    }

    echo "<script>alert('结算成功！');window.location='/user/agent/ximareport.php?key=$uname';</script>";
    exit();
}

$id = 1;
if (isset($_GET['id']) && $_GET['id'] != "") $id = $_GET['id'];
$user = $database->query("select * from user where id = $id")->fetch();
$muser = $database->query("select * from user where id = $uid")->fetch();
$xima = $user['xima'];


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"/>
    <link href="/user/css/system.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="/user/js/jquery.js"></script>
    <script type="text/javaScript" src="/user/js/common.js"></script>
    <script type="text/javascript" src="/user/js/validator.js"></script>
    <script type="text/javascript" src="/user/js/member/default.js"></script>
    <style type="text/css">
        #password_notice {
            color: red
        }

        .table_list th {
            line-height: 16px;
            height: 16px
        }

        #bigLiveMoney {
            color: red;
            font-weight: bold;
        }

        .align_c td {
            text-align: center
        }
    </style>
</head>
<script type="text/javascript">
    $(function () {
        $('form').checkForm(1);
        $("input[boxid='gamesLoginAll']").click(function () {
            var index = $("input[boxid='gamesLoginAll']").index(this);
            $("input[boxid='gamesPlayGameAll']").eq(index).attr("checked", false);
        });
        $("#liveMoney").keyup(function () {
            $("#bigLiveMoney").text(ChangeToBig($("#liveMoney").val()));
        });
        $("input[boxid='auto']").focus(function () {
            if ($(this).val() <= 0) {
                $(this).val("");
            }
        });
        $("input[boxid='auto']").blur(function () {
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
<form name="myform" method="post" action="jiesuanxima.php">
    <input type="hidden" name="token" value="63e3bc2b0e7756920c2b361b6db59c04">
    <table cellpadding="0" cellspacing="1" class="table_form">
        <caption>结算洗码佣金</caption>
        <tr>
            <th width="40%"><strong>用户名：</strong></th>
            <td width="60%"><?= $user['username'] ?></td>
        </tr>
        <tr>
            <th><strong>真人洗码：</strong></th>
            <td><?= $xima ?>%</td>
        </tr>
        <tr>
            <th><strong>洗码佣金：</strong></th>
            <td><span id="nowLiveMoney">0</span></td>
        </tr>
        <tr>
            <th><strong>结算洗码量：</strong></th>
            <td>
                <input boxid="auto" onkeyup="pay()" name="liveMoney" type="text" id="liveMoney" value="0" size="12"
                       maxlength="12" require="true" datatype="double|range" min="0" max="<?= $user['currentxima'] ?>"
                       msg="洗码量限额最小为0|洗码量限额最大为<?= $user['currentxima'] ?>" msgid="liveMoney1"/>
                &nbsp;<font style="color:red"> / <?= $user['currentxima'] ?></font><span id="liveMoney1"></span>
                &nbsp;&nbsp;<span id="bigLiveMoney"></td>
        </tr>

        <tr>
            <th></th>
            <td>
                <input type="hidden" name="forward" value="">
                <input type="hidden" name="pid" value="<?= $user['id'] ?>">
                <input type="submit" name="dosubmit" value=" 添加 ">
                <input type="reset" name="reset" value=" 重置 "></td>
            </td>
        </tr>
    </table>
</form>
<script type="text/javascript">
    function pay() {
        var nowmoney = parseFloat(parseFloat($("#liveMoney").val()) * 0.01 * <?=$xima?>);

        $("#nowLiveMoney").html(nowmoney.toFixed(2));

    }
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
            $(inputfieldids).each(function (i, item) {
                if ($(this).attr('disabled') == false) {
                    $(this).attr('checked', $(fieldids).attr('checked'));
                }
            });
        }
    }
    function inputLiveWash(obj) {
        $("#liveWash").val($(obj).val().replace('%', ''));
    }

</script>
</body>
</html>