<?php
header("Content-type: text/html; charset=utf-8");
include("../inc/function.php");
include("../inc/conn.php");
session_set_cookie_params(SESSION_LIFE_TIME);
session_start();
$uid = $_SESSION['uid'];
$admintab = $_SESSION['admintab'];
$username = $_SESSION['adminname'];
$key = $username;
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
$user = $database->query("select * from user where username='$key'")->fetch();

$members = array();
$agents = array();

$ssuid = $uid;
$uid = getid($key, $database);
if (!$database->VerifyUserReport($admintab, $ssuid, $uid)) {
    echo "没有权限！";
    exit();
}
$mems = allmem($uid, "3", $database);
$ages = allmem($uid, "1,2", $database);

foreach ($mems as $m) {
    $member = memberresult($m, $stime, $etime, $database);
    array_push($members, $member);
    $member = null;
}

foreach ($ages as $m) {
    $agent = agentresult($m, $stime, $etime, $database);
    array_push($agents, $agent);
    $agent = null;
}

function allmem($id, $type, $database)
{
    $ags = array();
    $query = "select * from user where (pid=$id or id=$id) and type in ($type)";
    $result = $database->query($query)->fetchAll();
    if ($result) {
        foreach ($result as $row) {
            array_push($ags, $row['id']);
        }
    }
    return $ags;
}

function getid($username, $database)
{
    $query = "select * from user where username='$username'";
    $row = $database->query($query, $database)->fetch();

    if ($row) {
        return $row['id'];
    }


    return "0";
}


function agentresult($id, $stime, $etime, $database)
{
    $query = "select * from user where id=$id";
    $member = new stdClass();
    $row = $database->query($query, $database)->fetch();
    if ($row) {
        $member->username = $row['username'];
        $member->nickname = $row['nickName'];
        $member->xima = $row['xima'];
        $member->zhancheng = $row['zhancheng'];
    }


    $today = date("Y-m-d", time() - 360000) . " 00:00:00";
    $query = "select * from `injectresult` where uid=$id and injecttime>='$stime' and injecttime<='$etime'";

    $result = $database->query($query, $database)->fetchAll();
    $u = "";
    $ijtimes = count($result);
    $ijmoney = 0;
    $gainmoney = 0;
    $ximaliang = 0;
    if ($result) {
        foreach ($result as $row) {
            $syh = $row['syh'];
            $type = $row['injecttype'];


            $injectmoney = floatval($row['injectmoney']);
            $ijmoney += $injectmoney;

            $ximaliang += floatval($row['ximaliang']);

            $winmoney = floatval($row['winmoney']);
            $gainmoney += $winmoney;


        }
    }
    $yongjin = $ximaliang * floatval($member->xima) * 0.01;
    $zongjine = $yongjin + $gainmoney;
    $jiaoshangjia = $zongjine * floatval($member->zhancheng) * 0.01;

    $member->ijtimes = $ijtimes;
    $member->ijmoney = $ijmoney;
    $member->gainmoney = $gainmoney;
    $member->ximaliang = $ximaliang;
    $member->yongjin = $yongjin;
    $member->choucheng = $jiaoshangjia;
    $member->zongjine = $zongjine;
    $member->shijimoney = shijimoney($id, $stime, $etime, $database);

    return $member;
}

function shijimoney($id, $stime, $etime, $database)
{
    $query = "select * from `injectresult` a,user where user.pid=$id and user.id= a.uid and a.injecttime>='$stime' and a.injecttime<='$etime'";
    $result = $database->query($query)->fetchAll();

    $ijtimes = count($result);

    $gainmoney = 0;

    if ($result) {
        foreach ($result as $row) {
            $winmoney = floatval($row['winmoney']);
            $gainmoney += $winmoney;
        }
    }
    return $gainmoney;
}

function memberresult($id, $stime, $etime, $database)
{
    $query = "select * from user where id=$id";
    $member = new stdClass();
    $row = $database->query($query, $database)->fetch();

    if ($row) {
        $member->username = $row['username'];
        $member->nickname = $row['nickName'];
        $member->xima = $row['xima'];
        $member->zhancheng = $row['zhancheng'];
    }


    $today = date("Y-m-d", time() - 360000) . " 00:00:00";
    $query = "select * from `injectresult` where uid=$id and injecttime>='$stime' and injecttime<='$etime'";

    $result = $database->query($query)->fetchAll();
    $u = "";
    $ijtimes = count($result);
    $ijmoney = 0;
    $gainmoney = 0;
    $ximaliang = 0;
    if ($result) {
        foreach ($result as $row) {
            $syh = $row['syh'];
            $type = $row['injecttype'];


            $injectmoney = floatval($row['injectmoney']);
            $ijmoney += $injectmoney;

            $ximaliang += floatval($row['ximaliang']);

            $winmoney = floatval($row['winmoney']);
            $gainmoney += $winmoney;


        }
    }
    $yongjin = $ximaliang * floatval($member->xima) * 0.01;
    $zongjine = $yongjin + $gainmoney;
    $jiaoshangjia = $zongjine * floatval($member->zhancheng) * 0.01;

    $member->ijtimes = $ijtimes;
    $member->ijmoney = $ijmoney;
    $member->gainmoney = $gainmoney;
    $member->ximaliang = $ximaliang;
    $member->yongjin = $yongjin;
    $member->choucheng = $jiaoshangjia;
    $member->zongjine = $zongjine;

    return $member;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"/>
    <link href="/user/css/system.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="/user/css/blue/style.css" type="text/css" media="print, projection, screen"/>
    <script type="text/javascript" src="/user/js/jquery.js"></script>
    <script type="text/javaScript" src="/user/js/common.js"></script>
    <script type="text/javaScript" src="/user/js/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="/user/js/default.js"></script>
    <script type="text/javascript" src="/user/js/report/bjl.js"></script>
    <script type="text/javascript" src="/user/js/FusionCharts.js"></script>
    <title></title>
    <style type="text/css">
        .table_list td {
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

        .f14 {
            font-size: 14px
        }
    </style>
    <script type="text/javascript">
        window.onerror = function () {
            return true;
        }
        $(function () {
            $('#loading').fadeOut('slow');
        });
    </script>
</head>
<body>
<div id='loading'>正在加载...</div>
<DIV id=position2><STRONG>当前位置：</STRONG>
    <a href='/user/report/livebjl.php'>百家乐报表</a>
    <a href='/user/report/livebjl.php'></a></DIV>

<table cellpadding="0" cellspacing="1" class="table_info">
    <caption>快捷操作</caption>
    <tr>
        <td>
            <input type='button' class="button_style" onclick='history.go(-1);' value='返回至上一页'>
            <input type='button' class="button_style" onclick='history.go(1);' value='前进至下一页'>
            <input type='button' class="button_style" onclick='location.reload();' value='刷新本页'>
            <input type='button' class="button_style" id="fullScreen" value='全屏显示'>
        </td>
    </tr>
    <tr>
        <td>
            按用户名查询：
            <input name="userName" type="text" id="userName" onKeyDown="if(event.keyCode==13) {go(0);}"
                   value="<?= $key ?>" size="20"/>
            <link rel="stylesheet" type="text/css" href="/user/css/calendar-blue.css"/>
            <script type="text/javascript" src="/user/js/calendar.js"></script>
            开始时间：<input type="text" id="sTime" value="<?= $stime ?>" size="18"/>
            结束时间：
            <input id="eTime" type="text" value="<?= $etime ?>" size="18"/>
            <input type='button' class="button_style" onclick="go(0)" value="确定搜索">
            <a href="javascript:settime('<?php echo date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - date("w") + 1 - 7, date("Y"))); ?> 08:00:00','<?php echo date("Y-m-d", mktime(23, 59, 59, date("m"), date("d") - date("w") + 7 - 7, date("Y"))); ?> 08:00:00')">上周</a>
            <a href="javascript:settime('<?php echo date('Y-m-d', strtotime('-2 days')); ?> 08:00:00','<?php echo date('Y-m-d', strtotime('-1 days')); ?> 08:00:00')">昨天</a>
            <a href="javascript:settime('<?php echo date('Y-m-d', strtotime('-1 days')); ?> 08:00:00','<?php echo date('Y-m-d', time()); ?> 08:00:00')">当日</a>
            <a href="javascript:settime('<?php echo date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d") - date("w") + 1, date("Y"))); ?> 08:00:00','<?php echo date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d") - date("w") + 7, date("Y"))); ?> 08:00:00')">本周</a>
            <a href="javascript:settime('<?php echo date("Y-m-d", mktime(0, 0, 0, date("m"), 1, date("Y"))); ?> 08:00:00','<?php echo date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("t"), date("Y"))); ?> 08:00:00')">本月</a>
        </td>
    </tr>
</table>

<table cellpadding="0" cellspacing="1" class="table_list" id="myTable">
    <caption>
        用户：<?= $user['username'] ?>
        别名：<?= $user['nickName'] ?> 直属会员报表
    </caption>
    <thead>
    <tr align="center">
        <th>&nbsp;</th>
        <th>投注次数</th>
        <th>投注金额</th>
        <th>输赢金额</th>
        <th>洗码量</th>
        <th>洗码比(%)</th>
        <th>洗码佣金</th>
        <th>总金额</th>
        <th>占成比(%)</th>
        <th>缴上家利润</th>
    </tr>
    <?php $key = 0;
    foreach ($members as $mem) {
        $key++; ?>
        <tr align="center">
            <td><input type='button' class="button_style"
                       onclick="redirect('/user/report/bjlmember.php?id=<?php echo $mem->id; ?>&stime=<?= $stime ?>&etime=<?= $etime ?>');"
                       value='明细'></td>
            <td><?php echo $mem->ijtimes; ?></td>
            <td><span class='b'><?php echo $mem->ijmoney; ?></span></td>
            <td><?php echo $mem->gainmoney; ?></td>
            <td><?php echo $mem->ximaliang; ?></td>
            <td><?php echo $mem->xima;
                echo "%"; ?></td>
            <td><?php echo $mem->yongjin; ?></td>
            <td><?php echo $mem->zongjine; ?></td>
            <td><?php echo $mem->zhancheng; ?>%</td>
            <td><?php echo $mem->choucheng; ?></td>
        </tr>
    <?php } ?>
    </thead>
    <tbody>
    </tbody>
</table>

<table cellpadding="0" cellspacing="1" class="table_list" id="listTable">
    <caption>
        下级代理报表
    </caption>
    <thead>
    <tr align="center">
        <th>&nbsp;</th>
        <th>用户名</th>
        <th>别名</th>
        <th>投注次数</th>
        <th>投注金额</th>
        <th>输赢金额</th>
        <th>洗码量</th>
        <th>洗码比(%)</th>
        <th>洗码佣金</th>
        <th>占成比(%)</th>
        <th>总金额</th>
        <th>缴上家纯利</th>
    </tr>
    </thead>
    <tbody>
    <?php $key = 0;
    foreach ($agents as $ag) {
        $key++; ?>
        <tr align="center" id="total_listTable" class="total">
            <td><?= $key ?></td>
            <td><?= $ag->username ?></td>
            <td><?= $ag->nickName ?></td>
            <td><?= $ag->ijtimes ?></td>
            <td><?= $ag->ijmoney ?></td>
            <td><?= $ag->gainmoney ?></td>
            <td><?= $ag->ximaliang ?></td>
            <td><?= $ag->xima ?>%</td>
            <td><?= $ag->yongjin ?></td>
            <td><?= $ag->zhancheng ?>%</td>
            <td><?= $ag->zongjine ?></td>
            <td><?= $ag->zongjine ?></td>
        </tr>
    <?php } ?>
    </tbody>
    <tfoot>

    <tr align="center" id="total_listTable" class="total" style="display:none;">
        <th>合计</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
    </tr>
    </tfoot>
</table>
<div style="margin:5px; float:left"><input type='button' class="button_style" onclick="window.print()" value="打印"></div>
<!--div id="chart1" style="clear:both">FusionCharts will load here!</div-->
<div id="chartContainer" style="clear:both">FusionCharts will load here!</div>
<script type="text/javascript">
    <!--
    var myChart = new FusionCharts("/images/Charts/Pie3D.swf", "myChartId", "100%", "300", {
        debugMode: false,
        registerWithJS: true,
        animation: false
    });
    myChart.setJSONData({
        "chart": {
            "caption": "自身盈利饼状图",
            "formatnumberscale": "0",
            "animation": "0",
            "numberPrefix": "￥",
            "baseFontSize": 12
        },
        "data": [],
        "styles": [{
            "definition": [{
                "style": [{
                    "name": "MyFirstFontStyle",
                    "type": "font",
                    "font": "Verdana",
                    "size": "12",
                    "color": "FF0000",
                    "bold": "1",
                    "bgcolor": "FFFFDD"
                }, {
                    "name": "MyFirstAnimationStyle",
                    "type": "animation",
                    "param": "_xScale",
                    "start": "0",
                    "duration": "2"
                }, {"name": "MyFirstShadow", "type": "Shadow", "color": "CCCCCC"}]
            }],
            "application": [{
                "apply": [{
                    "toobject": "Caption",
                    "styles": "MyFirstFontStyle,MyFirstShadow"
                }, {"toobject": "Canvas", "styles": "MyFirstAnimationStyle"}, {
                    "toobject": "DataPlot",
                    "styles": "MyFirstShadow"
                }]
            }]
        }]
    });
    myChart.render("chartContainer");
    // -->
</script>
<table cellpadding="0" cellspacing="1" class="table_info">
    <caption>报表开始、结束时间提示信息</caption>
    <tr>
        <td id="help"></td>
    </tr>
</table>
</body>
</html>
