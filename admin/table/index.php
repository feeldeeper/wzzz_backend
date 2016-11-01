<?php
header("Content-type: text/html; charset=utf-8");
include("../../user/inc/function.php");
include("../../user/inc/conn.php");
session_set_cookie_params(SESSION_LIFE_TIME);
session_start();
$suid = $_SESSION['uid'];
if (isset($suid) && $suid != "" && $suid != "0") {
} else {
    echo "<script>alert('请先登录!');window.location='../login.php';</script>";
    exit();
}
$username = $_SESSION['adminname'];
$tb = $database->query("select * from tablet where status=1")->fetchAll();


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
    <script type="text/javaScript" src="/user/js/jquery.tips.compressed.js"></script>
    <script type="text/javascript" src="/user/js/default.js"></script>
    <style type="text/css">
        body {
            min-width: 1400px;
            width: 100%
        }

        .align_c td {
            text-align: center
        }

        #loading {
            z-index: 1;
            padding: 5px 10px;
            background: red;
            color: #fff;
            position: absolute;
            top: 0px;
            left: 0px
        }
    </style>
    <script type="text/javascript">
        $(function () {
            $('#loading').fadeOut('slow');
            $("a", "#userList").tips();
            $("#userList").find("tr").each(function () {
                var tmp = $(this).find("td").eq(4);
                tmp.text(outputMoney(tmp.text()));
            });
        });
        window.onerror = function () {
            return true;
        }
    </script>
</head>
<body>
<div id='loading'>正在加载...</div>
<DIV id=position2><STRONG>当前位置：</STRONG>
    <A href='#'><?= $username ?></A></DIV>

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
        <caption>
            百家乐台号
        </caption>
        <tr align="center">
            <th width="5%"><strong>选中</strong></th>
            <th width="5%"><strong>台号</strong></th>
            <th width="10%"><strong>倒计时</strong></th>
            <th width="20%"><strong>筹码</th>
            <th width="10%"><strong>下限</strong></th>
            <th width="10%">上限</th>
            <th width="10%">
                <strong>
                    类型 </strong></th>
            <th width="10%">
                <strong>
                    桌名 </strong></th>
            <th width="10%"><strong>管理操作</strong></th>
        </tr>
        <tbody id="userList">
        <?php foreach ($tb as $t) { ?>
            <tr>
                <td><input type="checkbox" name="userid[]" id="checkbox" value="<?= $t['tab_id'] ?>"></td>
                <td><?= $t['gameid'] ?></a></td>
                <td><?= $t['injecttime'] ?>秒</td>
                <td><?= $t['chip'] ?></td>
                <td><?= $t['telMin'] ?></td>
                <td><?= $t['telMax'] ?></td>
                <td><?= $t['gameType'] ?></td>
                <td><?= $t['gameTableName'] ?></td>
                <td>
                    <a href="/admin/table/edit.php?id=<?= $t['tab_id'] ?>">修改</a>
                </td>
            </tr>
        <?php } ?>

        </tbody>
</table>
<div class="button_box">
    <input name='button2' type='button' class="button_style" id='chkall' onclick='checkall()' value='全选'>
</div>


</form>

<script type="text/javascript">
    var username = getUrlQuery('username', 3);
    var groupid = parseInt(getUrlQuery('groupid', 3));
    var status = parseInt(getUrlQuery('status', 3));
    var livestatus = parseInt(getUrlQuery('livestatus', 3));
    var nametype = parseInt(getUrlQuery('nametype', 3));
    if (username != null) {
        $("#username").val(username);
    } else {
        username = '不限';
    }
    if (groupid > -1) {
        $("#groupid").setSelectedValue(groupid);
    }
    if (status > -1) {
        $("#status").setSelectedValue(status);
    }
    if (livestatus > -1) {
        $("#livestatus").setSelectedValue(livestatus);
    }
    $("#nametype").setSelectedValue(nametype);
    function go() {
        $("#loading").show();
        var url = '/user/search/index/groupid/' + $("#groupid").val() + '/status/' + $("#status").val() + '/livestatus/' + $("#livestatus").val() + '/username/' + $("#username").val() + '/nametype/' + $("#nametype").val();
        url = "/admin/agent/index.php?id=<?=$uid?>&page=1";
        redirect(url);
    }
</script>
</body>
</html>