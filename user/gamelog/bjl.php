<?php
header("Content-type: text/html; charset=utf-8");
include("../inc/function.php");
include("../inc/conn.php");
include("../inc/sql.class.php");
$DB = new MySql($conn);
session_set_cookie_params(SESSION_LIFE_TIME);
session_start();
$uid = $_SESSION['uid'];
$username = $_SESSION['adminname'];
if (isset($uid) && $uid != "" && $uid != "0") {
} else {
    echo "<script>alert('请先登录!');window.location='../login.php';</script>";
    exit();
}
$key = "";
if (isset($_GET['key'])) {
    $key = $_GET['key'];
}
$stime = date('Y-m-d 08:00:00', strtotime('-1 days'));
if (isset($_GET['stime']) && $_GET['stime'] != "") {
    $stime = $_GET['stime'];
}
$etime = date('Y-m-d 08:00:00', time());
if (isset($_GET['etime']) && $_GET['etime'] != "") {
    $etime = $_GET['etime'];
}
$page = "1";
if (isset($_GET['page']) && $_GET['page'] != "" && $_GET['page'] != "0") {
    $page = $_GET['page'];
}
$mod = "0";
if (isset($_GET['mod']) && $_GET['mod'] != "") {
    $mod = $_GET['mod'];
}
$mod2 = "0";
if (isset($_GET['mod2']) && $_GET['mod2'] != "") {
    $mod2 = $_GET['mod2'];
}
if ($key == "" || $mod2 == "1")
    $uids = $DB->RelativeID($uid);
else
    $uids = $DB->RelativeID2($uid, $key, $mod);
if (count($uids) != 0) {
    $uids = implode(',', $uids);
    $arr = $DB->GameRecord($uids, $page, $stime, $etime, $key, $mod, $mod2);
} else {
    $arr = array();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"/>
    <link href="/user/css/system.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="/user/css/blue/style.css" type="text/css" media="print, projection, screen"/>
    <link rel="stylesheet" href="/user/css/print.css" type="text/css" media="print" charset="utf-8">
    <script type="text/javaScript" src="/user/js/jquery.js"></script>
    <title></title>
    <style type="text/css">
        body {
            min-width: 1300px;
            width: 100%
        }

        .poker {
            line-height: 29px;
            display: none
        }

        .poker img {
            vertical-align: middle
        }

        .line {
            cursor: pointer
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
        window.onerror = function () {
            //return true;
        }
        var poker = []</script>
    <script type="text/javaScript" src="/user/js/common.js"></script>
    <script type="text/javaScript" src="/user/js/jquery.tablesorter.min.js"></script>
    <script type="text/javaScript" src="/user/js/bjl.js?v=1"></script>
</head>
<body>
<!--<div id='loading'>正在加载...</div> -->
<table cellpadding="0" cellspacing="1" class="table_info">
    <caption>快捷操作</caption>
    <tr>
        <td>
            <input type='button' class="button_style" onclick='history.go(-1);' value='返回至上一页'>
            <input type='button' class="button_style" onclick='history.go(1);' value='前进至下一页'>
            <input type='button' class="button_style" onclick='location.reload();' value='刷新本页'>
            <input type='button' class="button_style" id="fullScreen" value='全屏显示'>
            <input type='button' class="button_style" onclick="window.print()" value="打印">
        </td>
    </tr>
    <tr>
        <td>
            <select name="s_mod" id="s_mod">
                <option value="1" <?php if ($mod == "1") echo 'selected="selected"'; ?>>精准</option>
                <option value="0" <?php if ($mod == "0") echo 'selected="selected"'; ?>>模糊</option>
            </select>
            条件：
            <select name="s_mod2" id="s_mod2">
                <option value="1" <?php if ($mod2 == "1") echo 'selected="selected"'; ?>>局编号</option>
                <option value="0" <?php if ($mod2 == "0") echo 'selected="selected"'; ?>>用户名</option>
            </select>
            按关键字查询：
            <input type="text" id="keyWord" size="20" value="<?= $key ?>" onKeyDown="if(event.keyCode==13) {go();}"/>
            <link rel="stylesheet" type="text/css" href="/user/css/calendar-blue.css"/>
            <script type="text/javascript" src="/user/js/calendar.js"></script>
            <script type="text/javaScript" src="/user/js/jquery.liu.select.js"></script>
            开始时间：<input type="text" id="sTime" value="<?= $stime ?>" size="18"/>
            结束时间：
            <input id="eTime" type="text" value="<?= $etime ?>" size="18"/>
            <input type='button' class="button_style" onclick="go()" value="确定搜索">
            <a href="javascript:settime('<?php echo date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - date("w") + 1 - 7, date("Y"))); ?> 08:00:00','<?php echo date("Y-m-d", mktime(23, 59, 59, date("m"), date("d") - date("w") + 7 - 7, date("Y"))); ?> 08:00:00')">上周</a>
            <a href="javascript:settime('<?php echo date('Y-m-d', strtotime('-2 days')); ?> 08:00:00','<?php echo date('Y-m-d', strtotime('-1 days')); ?> 08:00:00')">昨天</a>
            <a href="javascript:settime('<?php echo date('Y-m-d', strtotime('-1 days')); ?> 08:00:00','<?php echo date('Y-m-d', time()); ?> 08:00:00')">当日</a>
            <a href="javascript:settime('<?php echo date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d") - date("w") + 1, date("Y"))); ?> 08:00:00','<?php echo date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d") - date("w") + 7, date("Y"))); ?> 08:00:00')">本周</a>
            <a href="javascript:settime('<?php echo date("Y-m-d", mktime(0, 0, 0, date("m"), 1, date("Y"))); ?> 08:00:00','<?php echo date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("t"), date("Y"))); ?> 08:00:00')">本月</a>
        </td>
    </tr>
</table>

<table cellpadding="0" cellspacing="1" class="table_list" id="listTable">
    <caption>
        百家乐游戏记录
    </caption>
    <thead>
    <tr align="center">
        <th width="35">序号</th>
        <th>帐号</th>
        <th>局编号</th>
        <th width="120">局数</th>
        <th width="120">下注日期</th>
        <th width="120">结束日期</th>
        <th>下注类型</th>
        <th width="10%">开奖结果</th>
        <th>结果</th>
        <th>倍率</th>
        <th>下注金额</th>
        <th>洗码量</th>
        <th>抽水</th>
        <th>利润</th>
        <th>余额</th>
        <th>IP</th>
        <th>备注</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($arr as $key) { ?>
        <tr>
            <td class="align_c"><?= $key['rid'] ?></td>
            <td class="align_c"><a onclick="setKeyWord(this, 0)" href="#"><?= $key['username'] ?></a></td>
            <td class="align_c"><a onclick="setKeyWord(this, 1)" href="#"><?= $key['gamenumber'] ?></a></td>
            <td class="align_c"><?= $key['round'] ?></td>
            <td class="align_c"><?= $key['bidtime'] ?></td>
            <td class="align_c"><?= $key['endtime'] ?></td>
            <td class="align_c"><?= $key['bidtype'] ?></td>
            <td class="align_c line">
                <?= $key['result'] ?></td>
            <td class="align_c"><span class='g'><?= $key['winorlose'] ?></span></td>
            <td class="align_c"><?= $key['bidrate'] ?></td>
            <td class="align_c"><?= $key['injectmoney'] ?></td>
            <td class="align_c"><?= $key['xima'] ?></td>
            <td class="align_c"><?= $key['choushui'] ?></td>
            <td class="align_c"><span class="g"><?= $key['profit'] ?></span></td>
            <td class="align_c"><?= $key['restmoney'] ?></td>
            <td class="align_c"><a href="http://www.ip138.com/ips.asp?ip=<?= $key['ip'] ?>" title="点击查询详细IP归属地"
                                   target="_blank"><?= $key['ip'] ?> - <?= $key['iploc'] ?></a></td>
            <td class="align_c"><?= $key['memo'] ?></td>
        </tr>
        <tr class="poker">
            <td colspan="21" class="align_c"></td>
        </tr>
    <?php } ?>
    </tbody>
    <tfoot>
    <tr>
        <td colspan="21" class="align_r">
            <span class="r bo">本页利润合计：<span id="pTotal"></span>&nbsp;&nbsp;总利润合计：<?php $pa = $arr[0];
                echo $pa['totalprofit']; ?></span></td>
    </tr>
    </tfoot>
</table>

<div id="pages">总数：<b>0</b>&nbsp;
    每页：<b>100</b>
    <a href="/user/gamelog/bjl/page/1">首页</a><a href="/user/gamelog/bjl">上一页</a><a href="/user/gamelog/bjl">下一页</a><a
        href="/user/gamelog/bjl/page/0">尾页</a>
    页次：<b><font color="red">1</font>/0</b>
    <input type="text" name="page" id="page" size="4"
           onKeyDown="if(event.keyCode==13) {redirect('/user/gamelog/bjl.php?page='+this.value); return false;}">
    <input type="button" value="转到" class="button_style"
           onclick="redirect('/user/gamelog/bjl.php?page='+$('#page').val())"></div>
<script type="text/javascript">
    function checkOrder(thisObj, id, type) {
        var $td = $(thisObj).parents('tr').children('td');
        var key = $td.eq(2).text() + '_' + $td.eq(1).text() + '_' + id;
        var money = $td.eq(8).text();
        var time = $td.eq(4).text();
        $.post('/user/gamelog/checkorder1/', {key: key, type: type, money: money, time: time}, function (data) {
            if (data.type == false) {
                if (data.orderType == null) {
                    alert('数据效验失败，原始效验文件不存在！');
                } else {
                    alert('数据效验失败，原始数据：' + data.orderType + ' 金额：' + data.money);
                }
            } else {
                alert('数据效验成功！');
            }
        }, 'json');
    }
</script>
0.08608389
</body>
</html>