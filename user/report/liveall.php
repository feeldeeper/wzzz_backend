<?php
header("Content-type: text/html; charset=utf-8");
include("../inc/function.php");
include("../inc/conn.php");
session_set_cookie_params(SESSION_LIFE_TIME);
session_start();
$uid = $_SESSION['uid'];
$admintab = $_SESSION['admintab'];
if (isset($uid) && $uid != "" && $uid != "0") {
} else {
    echo "<script>alert('请先登录!');window.location='../login.php';</script>";
    exit();
}

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

$members = array();
$agents = array();
$ssuid = $uid;
$uid = getid($key, $database);
if (!$database->VerifyUserReport($admintab, $ssuid, $uid)) {
    echo "没有权限！";
    exit();
}
$mems = allmem($uid, "3", $database);
$ages = allmem($uid, "2", $database);

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
    $query = "select * from user where (pid=$id or id=$id) and type=$type";
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
    $result = $database->query($query)->fetch();
    if ($result) {
        return $result['id'];
    }

    return "0";
}


function agentresult($id, $stime, $etime, $database)
{
    $query = "select * from user where id=$id";
    $result = $database->query($query)->fetch();
    if ($result) {
        $member = new stdClass();
        $member->username = $result['username'];
        $member->nickname = $result['nickName'];
        $member->xima = $result['xima'];
        $member->zhancheng = $result['zhancheng'];
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
    $member = new stdClass();
    $query = "select * from user where id=$id";
    $row = $database->query($query)->fetch();
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
    $ijtimes = mysql_num_rows($result);
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
    <script type="text/javaScript" src="/user/js/jquery1.9.js"></script>
    <script type="text/javaScript" src="/user/js/common.js"></script>
    <script type="text/javascript" src="/user/js/default.js"></script>
    <script type="text/javascript" src="/user/js/liveall.js?v=1.0"></script>
    <script type="text/javaScript" src="/user/js/jquery.tablesorter.min.js?v=0.5"></script>
    <script type="text/javascript" src="/user/js/jquery.edtableheader.js"></script>
    <script language="javascript" src="/user/js/LodopFuncs.js"></script>
    <title></title>
    <style type="text/css">
        body {
            min-width: 1300px;
            width: 100%
        }

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
        var LODOP;
        var sTime = '<?php echo date('Y年m月d日',time());?>';
        var eTime = '<?php echo date('Y年m月d日',strtotime('+1'));?>';
        var thisYears = 2015;
        function getWashDetailed(id, sTime, eTime) {
            $.getJSON('/report/api/getwash/id/' + id + '/stime/' + sTime + '/etime/' + eTime, function (data) {
                $("#w_" + data.userID).html(data.washTotal);
                var obj = $("#wa_" + data.userID).parent("td").parent("tr").find("td");
                //用户输赢
                var money = parseInt(obj.eq(6).text().replace(/,/g, ''));
                //抽水
                var drawbackProfit = parseInt(obj.eq(11).text().replace(/,/g, ''));
                //用户占成
                var agRatio = parseInt(obj.eq(12).text().replace(/%/g, '')) / 100;
                //公司占成
                var ratio = 1 - agRatio;
                //应付占成金额
                var agOccupyProfit;
                if (agRatio > 0) {
                    if (money < 0) {
                        agOccupyProfit = Math.abs(parseInt((money + drawbackProfit) * agRatio));
                    } else {
                        agOccupyProfit = parseInt((money + drawbackProfit) * ratio);
                    }
                } else {
                    agOccupyProfit = 0;
                }
                //应付占成洗码
                var agWashProfit = parseInt(data.washTotal) * ratio + drawbackProfit;
                $("#wa_" + data.userID).html(parseInt(agOccupyProfit + agWashProfit));
                printTotal('listTable', 11);
            });
        }

        function printAll() {
            var i = 0;
            $("input[pid='print']").each(function () {
                i++;
                var obj = this;
                setTimeout(function () {
                    delayPrint(obj);
                }, 3000 * i);
            });
            //$("input[pid='print']").trigger("click");
        }

        function delayPrint(obj) {
            $(obj).trigger("click");
        }
    </script>
</head>
<body>
<div id='loading'>正在加载...</div>
<DIV id=position2><STRONG>当前位置：</STRONG>
    <a href='/report/liveall/index/'>总报表</a>
    <A href='/user/manage/agent/id/'></A><a href='#'><?= $username ?></a>
</DIV>

<table cellpadding="0" cellspacing="1" class="table_info">
    <caption>快捷操作</caption>
    <tr>
        <td>
            <input type='button' class="button_style" onclick='history.go(-1);' value='返回至上一页'>
            <input type='button' class="button_style" onclick='history.go(1);' value='前进至下一页'>
            <input type='button' class="button_style" onclick='location.reload();' value='刷新本页'>
            <input type='button' class="button_style" id="fullScreen" value='全屏显示'>
            <input type='button' class="button_style" onclick='printAll()' value='全部打印'>
        </td>
    </tr>
    <tr>
        <td>
            按用户名查询：
            <input name="userName" type="text" id="userName" onKeyDown="if(event.keyCode==13) {go();}"
                   value="<?= $key ?>" size="20"/>
            <link rel="stylesheet" type="text/css" href="/user/css/calendar-blue.css"/>
            <script type="text/javascript" src="/user/js/calendar.js"></script>
            <link rel="stylesheet" type="text/css" href="/js/calendar/calendar-blue.css"/>
            <script type="text/javascript" src="/js/calendar/calendar.js"></script>
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
        真人游戏总报表
    </caption>
    <thead>
    <tr align="center">
        <th>&nbsp;</th>
        <th>编号</th>
        <th>用户名</th>
        <th>别名</th>
        <th>投注次数</th>
        <th>下注金额</th>
        <th>输赢金额</th>
        <th>实际输赢</th>
        <th>洗码量</th>
        <th>真人洗码(%)</th>
        <th>洗码佣金</th>
        <th>抽成佣金</th>
        <th>真人占成(%)</th>
        <th>下家利润</th>
        <th>总金额</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($agents as $ag){ ?>
    <tr align="center">
        <td><input type='button' class="button_style"
                   onclick="redirect('/user/report/livebjl.php?username=<?php echo $ag->username; ?>&stime=<?= $stime ?>&etime=<?= $etime ?>');"
                   value='明细'></td>
        <td></td>
        <td><span class='b'><?= $ag->username ?></span></td>
        <td><?= $ag->nickname ?></td>
        <td><?= $ag->ijtimes ?></td>
        <td><?= $ag->ijmoney ?></td>
        <td><?= $ag->gainmoney ?></td>
        <td><?= $ag->shijimoney ?></td>
        <td><?= $ag->ximaliang ?></td>
        <td><?= $ag->xima ?>%</td>
        <td><?= $ag->yongjin ?></td>
        <td><?= $ag->choucheng ?></td>
        <td><?= $ag->zhancheng ?>%</td>
        <td>0</td>
        <td><?= $ag->zongjine ?></td>

        <?php } ?>
    </tbody>
    <tfoot>
    <tr align="center" id="total_listTable" class="total">
        <th>总计</th>
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
        <th>&nbsp;</th>
        <th></th>
        <th></th>
        <!--th>&nbsp;</th-->
    </tr>
    </tfoot>
</table>

<table cellpadding="0" cellspacing="1" class="table_list" id="mlistTable">
    <caption>
        会员真人游戏总报表
    </caption>
    <thead>
    <tr align="center">
        <th width="10%">明细</th>
        <th>编号</th>
        <th>用户名</th>
        <th>别名</th>
        <th>投注次数</th>
        <th>下注金额</th>
        <th>输赢金额</th>
        <th>洗码量</th>
        <th>真人洗码(%)</th>
        <th>洗码佣金</th>
        <th>抽成佣金</th>
        <!--th>缴上家利润</th-->
        <th>总金额</th>
    </tr>

    </thead>
    <tbody>
    <?php $key = 0;
    foreach ($members as $mem) {
        $key++; ?>
        <tr>
            <td><input type='button' class="button_style"
                       onclick="redirect('/user/report/livebjl.php?username=<?php echo $ag->username; ?>&stime=<?= $stime ?>&etime=<?= $etime ?>');"
                       value='明细'></td>
            <td><?= $key ?></td>
            <td><?php echo $mem->username; ?></td>
            <td><?php echo $mem->nickname; ?></td>
            <td><?php echo $mem->ijtimes; ?></td>
            <td><?php echo $mem->ijmoney; ?></td>
            <td><?php echo $mem->gainmoney; ?></td>
            <td><?php echo $mem->ximaliang; ?></td>
            <td><?php echo $mem->xima;
                echo "%"; ?></td>
            <td><?php echo $mem->yongjin; ?></td>
            <td><?php echo $mem->choucheng; ?></td>
            <td><?php echo $mem->zongjine; ?></td>
        </tr>
    <?php } ?>
    </tbody>
    <tfoot>
    <tr align="center" id="total_mlistTable" class="total">
        <th>总计</th>
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
        <!--th>&nbsp;</th-->
    </tr>
    </tfoot>
</table>

<div style="margin:5px; float:left"><input type='button' class="button_style" onclick="window.print()" value="打印"></div>
<script type="text/javascript">
    $(function () {
        printTotal('mlistTable', 5);
        printTotalk('listTable', 5, 5);
        printTotalk('listTable', 7, 7);
        printTotalk('listTable', 8, 9);
        printTotalk('listTable', 10, 11);
        printTotalk('listTable', 11, 12);
        printTotalk('listTable', 12, 15);


        printTotal('mlistTable', 7);
        printTotal('mlistTable', 8);
        printTotal('mlistTable', 10);
        printTotal('mlistTable', 11);
        printTotal('mlistTable', 12);
        printTotal('mlistTable', 13);
        $("#listTable").find("tbody").find("tr").each(function () {
            //formatMoney(this, 6);
            formatMoney(this, 12);
            formatMoney(this, 13);
            //formatMoney(this, 13);
        });
        $("#mlistTable").find("tbody").find("tr").each(function () {
            //formatMoney(this, 6);
            formatMoney(this, 10);
        });
        //setAllTotal(1, 4, 4);
        $("#mlistTable").freezeHeader();
    });
    function formatMoney(a, b) {
        var c = $(a).find("td").eq(b).find("span");
        c.text(outputMoney(c.text()));
    }
    function setAllTotal(a, b, c) {
        $("#alistTable tbody").find("td").eq(a - 1).html(allTotalSum('total_listTable', b) + allTotalSum('total_mlistTable', c));
    }
    function allTotalSum(obj, num) {
        return parseFloat($("#" + obj).find("th").eq(num - 1).text());
    }
    date = new Date();
    Calendar.setup({
        inputField: "sTime",
        ifFormat: "%Y-%m-%d %H:%M:%S",
        showsTime: true,
        align: "B1",
        singleClick: true
    });
    Calendar.setup({
        inputField: "eTime",
        ifFormat: "%Y-%m-%d %H:%M:%S",
        showsTime: true,
        align: "B1",

        singleClick: true
    });
    function settime(time1, time2) {
        $("#sTime").val(time1);
        $("#eTime").val(time2);
        go(0);
    }
    function setKeyWord(obj) {
        $("#keyWord").val(obj.innerHTML);
        go(0);
    }

    function go(page) {
        $('#loading').show();
        var userName = $("#userName").val();
        if (userName != '') {
            var url = "/user/report/liveall.php?key=" + $("#userName").val() + "&stime=" + $("#sTime").val() + "&etime=" + $("#eTime").val();
        } else {
            var url = "/user/report/liveall.php?stime=" + $("#sTime").val() + "&etime=" + $("#eTime").val();
        }
        redirect(url);
    }
    function getResult(id, num) {
        var result = 0;
        var list = $('#' + id + ' tbody td:nth-child(' + num + ')');
        $.each(list, function (i, n) {
            result += parseFloat(n.innerText);
        });
        return result;
    }

    function printTotal(obj, num) {
        var val = getResult(obj, num);
        var val2 = outputMoney(val + "");
        if (val > 0) {
            val = '<span class="r bo f14">' + val2 + '</span>';
        } else {
            val = '<span class="g f14">' + val2 + '</span>';
        }
        $('#total_' + obj).find('th').eq(num - 1).html(val);
    }
    function printTotalk(obj, num, pnum) {
        var val = getResult("mlistTable", num);
        var val2 = outputMoney(val + "");
        if (val > 0) {
            val = '<span class="r bo f14">' + val2 + '</span>';
        } else {
            val = '<span class="g f14">' + val2 + '</span>';
        }
        $('#total_' + obj).find('th').eq(pnum - 1).html(val);
    }
    //$("#pTotal").html(getResult('listTable', 11));
    function getArgs(strParame) {
        var args = new Object();
        var query = location.search.substring(1); // Get query string
        var pairs = query.split("&"); // Break at ampersand
        for (var i = 0; i < pairs.length; i++) {
            var pos = pairs[i].indexOf('='); // Look for "name=value"
            if (pos == -1) continue; // If not found, skip
            var argname = pairs[i].substring(0, pos); // Extract the name
            var value = pairs[i].substring(pos + 1); // Extract the value
            value = decodeURIComponent(value); // Decode it, if needed
            args[argname] = value; // Store as a property
        }
        return args[strParame]; // Return the object
    }


    function getuserPrintData(obj, sTime, eTime) {
        var td = $(obj).parent("td").parent("tr").find("td");
        $("#pUserName").html(td.eq(2).text());
        $("#pNickName").html(td.eq(3).text());
        $("#mpReNum").html(td.eq(1).text());
        $("#pUserProfit").html('￥' + parseInt(td.eq(6).text().replace(/,/g, '')));
        $("#pWashTotal").html('￥' + td.eq(7).text());
        $("#pDrawbackProfit").html('￥' + td.eq(10).text());
        $("#pLiveWash").html(td.eq(8).text());
        var washTotalProfit = parseInt(td.eq(9).text());
        $("#pWashTotalProfit").html('￥' + washTotalProfit);
        var userAllProfit = washTotalProfit + parseInt(td.eq(10).text());
        $("#pUserAllProfit").html('￥' + userAllProfit);
        $("#pbigMoneyStr").html(ChangeToBig(userAllProfit));
        $("#pStime").html(this.sTime);
        $("#pEtime").html(this.eTime);
        $("#thisYears").html(this.thisYears);
        createUserPrintPage(1760);
    }

    function getAgPrintData(obj, sTime, eTime) {
        var td = $(obj).parent("td").parent("tr").find("td");
        var userMoneyProfit = parseInt(td.eq(6).text().replace(/,/g, ''));
        var apDrawbackProfit = parseInt(td.eq(11).text());
        $("#apUserName").html(td.eq(2).text());
        $("#apNickName").html(td.eq(3).text());
        $("#apReNum").html(td.eq(1).text());
        $("#apUserProfit").html('￥' + userMoneyProfit);
        $("#apRealUserProfit").html('￥' + (userMoneyProfit + apDrawbackProfit));
        $("#apWashTotal").html('￥' + td.eq(8).text());
        if (td.eq(10).text() != '') {
            $("#apWashProfit").text('￥' + td.eq(10).text());
        } else {
            alert('洗码佣金错误！');
            return;
        }
        $("#apDrawbackProfit").text('￥' + td.eq(11).text());
        $("#apLiveRatio").text(td.eq(12).text());
        var agRatio = parseInt(td.eq(12).text()) / 100;
        var ratio = 1 - agRatio;
        var realUserMoneyProfit = userMoneyProfit + apDrawbackProfit;
        $("#apWashTotalProfit").text(parseInt(td.eq(10).text() * ratio) + parseInt(td.eq(11).text()));
        if (agRatio > 0) {
            /*if (realUserMoneyProfit < 0) {
             $("#apUserAllProfit").text(Math.abs(realUserMoneyProfit * agRatio));
             } else {
             $("#apUserAllProfit").text(realUserMoneyProfit * ratio);
             }*/
            $("#apTips").text('应收占成金额');
            $("#apUserAllProfit").text(parseInt(Math.abs(realUserMoneyProfit * agRatio)));
        } else {
            $("#apTips").text('应付占成金额');
            $("#apUserAllProfit").text('0');
        }
        if (realUserMoneyProfit > 0) {
            $("#apAllTotalProfit").text(parseInt($("#apWashTotalProfit").text()) - parseInt($("#apUserAllProfit").text()));
        } else {
            $("#apAllTotalProfit").text(parseInt($("#apWashTotalProfit").text()) + parseInt($("#apUserAllProfit").text()));
        }
        $("#apbigMoneyStr").text(ChangeToBig(Math.abs($("#apAllTotalProfit").text())));
        $("#apStime").html(this.sTime);
        $("#apEtime").html(this.eTime);
        $("#apthisYears").html(this.thisYears);
        createAgPrintPage(1956);
    }

    function createUserPrintPage(width) {
        LODOP.PRINT_INITA(0, 0, 613, 302, "娱乐中心王者至尊三合一会员周报表");
        LODOP.SET_PRINT_MODE("POS_BASEON_PAPER", true);
        LODOP.SET_PRINT_PAGESIZE(2, 770, width, '');
        LODOP.ADD_PRINT_HTM(-5, -10, "100%", "100%", $("#userPrintTemplates").html());
        LODOP.PRINT();
        //LODOP.PREVIEW();
    }
    ;

    function createAgPrintPage(width) {
        LODOP.PRINT_INITA(0, 0, 613, 302, "娱乐中心王者至尊三合一代理周报表");
        LODOP.SET_PRINT_MODE("POS_BASEON_PAPER", true);
        LODOP.SET_PRINT_PAGESIZE(2, 770, width, '');
        LODOP.ADD_PRINT_HTM(-5, -10, "100%", "100%", $("#agPrintTemplates").html());
        LODOP.PRINT();
        //LODOP.PREVIEW();
    }
    ;
</script>
<table cellpadding="0" cellspacing="1" class="table_info">
    <caption>报表开始、结束时间提示信息</caption>
    <tr>
        <td id="help"></td>
    </tr>
</table>
0.07221389
<object id="LODOP_OB" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0>
    <embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0
           pluginspage="/js/install_lodop32.exe"></embed>
</object>
<div style="text-align:center; width:660px; margin:0; padding:0; display:none;position:relative"
" id="userPrintTemplates">
<h3 style="margin:0 0 4px 0;text-align:center">娱乐中心王者至尊三合一会员周报表</h3>

<h1 style="position: absolute; right: 0px; top: 0px;" id="mpReNum"></h1>
<h4 style="margin:4px;text-align:center"><span id="pStime"></span>&nbsp;&nbsp;至&nbsp;&nbsp;<span id="pEtime"></span>
</h4>
<table width="100%" border="1" cellpadding="4" cellspacing="0" bordercolor="#000000"
       style="border-collapse:collapse;border:solid 1px">
    <tr>
        <td width="130" height="40" align="center">会员名</td>
        <td width="90" align="center">别名</td>
        <td width="125" align="center">输赢金额</td>
        <td width="125" align="center">洗码量</td>
        <td width="48" align="center">洗码比</td>
        <td width="84" align="center">抽水佣金</td>
    </tr>
    <tr>
        <td align="center" height="40" id="pUserName"></td>
        <td align="center" id="pNickName"></td>
        <td align="center" id="pUserProfit"></td>
        <td align="center" id="pWashTotal"></td>
        <td align="center" id="pLiveWash"></td>
        <td align="center" id="pDrawbackProfit"></td>
    </tr>
    <tr>
        <td align="center" height="40">洗码佣金</td>
        <td colspan="2" align="center" id="pWashTotalProfit"></td>
        <td align="center">应付洗码佣金</td>
        <td colspan="2" align="center" id="pUserAllProfit"></td>
    </tr>
    <tr>
        <td height="40" align="center">人民币大写</td>
        <td colspan="5" align="left" id="pbigMoneyStr"></td>
    </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin:6px 0 0 0">
    <tr>
        <td>结账人：</td>
        <td>主管审批：</td>
        <td>领款人：</td>
        <td width="160" align="right"><span id="thisYears"></span>年&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;月&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;日
        </td>
    </tr>
</table>
</div>

<div style="text-align:center; width:786px; margin:0; padding:0;display:none;position:relative" id="agPrintTemplates">
    <h3 style="margin:0 0 4px 0;text-align:center">娱乐中心王者至尊三合一代理周报表</h3>

    <h1 style="position: absolute; right: 0px; top: 0px;" id="apReNum"></h1>
    <h4 style="margin:4px;text-align:center"><span id="apStime"></span>&nbsp;&nbsp;至&nbsp;&nbsp;<span
            id="apEtime"></span></h4>
    <table width="100%" border="1" cellpadding="4" cellspacing="0" bordercolor="#000000"
           style="border-collapse:collapse;border:solid 1px">
        <tr>
            <td width="140" height="40" align="center">代理名</td>
            <td width="90" align="center">别名</td>
            <td width="125" align="center">输赢金额</td>
            <td width="125" align="center">实际输赢</td>
            <td width="125" align="center">洗码量</td>
            <td width="100" align="center">洗码佣金</td>
            <td width="84" align="center">抽成佣金</td>
            <td width="84" align="center">占成(%)</td>
        </tr>
        <tr>
            <td align="center" height="40" id="apUserName"></td>
            <td align="center" id="apNickName"></td>
            <td align="center" id="apUserProfit"></td>
            <td align="center" id="apRealUserProfit"></td>
            <td align="center" id="apWashTotal"></td>
            <td align="center" id="apWashProfit"></td>
            <td align="center" id="apDrawbackProfit"></td>
            <td align="center" id="apLiveRatio"></td>
        </tr>
        <tr>
            <td align="center" height="40">应付洗码金额</td>
            <td colspan="3" align="center">￥<span id="apWashTotalProfit"></span></td>
            <td align="center" id="apTips"></td>
            <td colspan="3" align="center">￥<span id="apUserAllProfit"></span></td>
        </tr>
        <tr>
            <td height="40" align="center">合计</td>
            <td height="40" colspan="2" align="center">￥<span id="apAllTotalProfit"></span></td>
            <td align="center">&nbsp;</td>
            <td height="40" align="center">人民币大写</td>
            <td colspan="3" align="left" id="apbigMoneyStr"></td>
        </tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin:6px 0 0 0">
        <tr>
            <td>结账人：</td>
            <td>主管审批：</td>
            <td>领款人：</td>
            <td width="160" align="right"><span id="apthisYears"></span>年&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;月&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;日
            </td>
        </tr>
    </table>
</div>
<script>
    function monitorMtbody() {
        var i;
        i = 1;
        $("#listTable").find("tbody").find("tr").each(function () {
            $(this).find("td").eq(1).text(i);
            i++;
        });
        i = 1;
        $("#mlistTable").find("tbody").find("tr").each(function () {
            $(this).find("td").eq(1).text(i);
            i++;
        });
    }
    try {
        LODOP = getLodop(document.getElementById('LODOP_OB'), document.getElementById('LODOP_EM'));
        LODOP.SET_LICENSES("管理系统", "E27623582E4029F9A7DED1F854086A89", "", "");
    } catch (e) {
    }
</script>
</body>
</html>