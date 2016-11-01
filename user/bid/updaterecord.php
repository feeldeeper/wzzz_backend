<?php
header("Content-type: text/html; charset=utf-8");
include("../inc/conn.php");

$arr = array();
if (isset($_GET['id']) && $_GET['id'] != "") {
    $id = intval(post_check($_GET['id']));
    $re = intval(post_check($_GET['re']));
    $querycount = $database->query("update `round` set result='" . $re . "' where rid=" . $id);

    $sql = "select * from `injectresult` where syh<>-1 and rid=" . $id;
    $result = $database->query($sql)->fetchAll();
    if ($result) {
        foreach ($result as $row) {
            $id = $row['id'];
            $uid = $row['uid'];
            $money = $row['injectmoney'];
            $type = $row['injecttype'];
            $profit = $row['winmoney'];
            $rest = $row['restmoney'];
            $syh = getsyh($type, $re);
            $winmoney = getwinmoney($type, $syh, $money);
            $bucha = floatval($winmoney) - floatval($profit);
            $restmoney = floatval($rest) + $bucha;
            $ximaliang = getxima($uid, $money, $type, $syh, $database);
            $ximaliang = explode(',', $ximaliang);
            $ximabucha = floatval($ximaliang[0]) - floatval($row['ximaliang']);
            $restxima = floatval($row['restxima']) + $ximabucha;
            $sql = "update injectresult set syh='$syh',winmoney='$winmoney',restmoney='$restmoney',ximaliang='$ximaliang[0]',ximayongjin='$ximaliang[1]',haschanged='1',restxima='$restxima' where id=$id";
            $database->query($sql);
            if ($bucha >= 0)
                $sql = "update user set currentmoney = currentmoney + $bucha where id = $uid";
            else {
                $bucha = (-1) * $bucha;
                $sql = "update user set currentmoney = currentmoney - $bucha where id = $uid";
            }
            $database->query($sql);
            if ($ximabucha >= 0)
                $sql = "update user set currentxima = currentxima + $ximabucha where id = $uid";
            else {
                $ximabucha = (-1) * $ximabucha;
                $sql = "update user set currentxima = currentxima - $ximabucha where id = $uid";
            }
            $database->query($sql);
        }
    }
}

if (true)
    echo "success";
else
    echo "failed";


function getxima($uid, $money, $type, $syh, $database)
{
    if ($type == "3" || $type == "4" || $type == "5" || $syh == "2" || $money == "0")
        return "0,0";
    $tsql = "SELECT SQL_CACHE * from user where id=" . $uid;
    $row = $database->query($tsql)->fetch();
    $ximaliang = 0;
    $yongjin = 0;

    if ($row) {
        $xima = floatval($row['xima']);
        $danshuangbian = intval($row['danshuangbian']);
        $m = floatval($money);
        if ($syh == "0")
            $ximaliang += $m;
        elseif ($danshuangbian == "0" && $syh == "1") {
            $ximaliang += $m;
        }
        $yongjin = $ximaliang * $xima * 0.01;
    }

    return $ximaliang . "," . $yongjin;
}

function getcurrentmoney($uid, $database)
{
    $tsql = "SELECT SQL_CACHE currentmoney from user where id=" . $uid;
    $row = $database->query($tsql)->fetch();
    $money = 0;

    if ($row) {
        $money = floatval($row['currentmoney']);
    }

    return $money;
}

function updatemoney($uid, $database)
{
    $tsql = "SELECT SQL_CACHE winmoney,money from injectresult i,user u WHERE i.uid=$uid and i.uid=u.id";
    $result = $database->query($tsql)->fetchAll();
    $tmoney = 0;
    if ($result) {

        foreach ($result as $row) {
            $money = floatval($row['money']);
            $tmoney = $tmoney + floatval($row['winmoney']);
        }
        $tmoney += $money;
    }
    $sql = "update user set currentmoney='$tmoney' where id=$uid";
    $database->query($sql);
}

function getwinmoney($i, $s, $m)
{
    if ($s == '2') {
        return '0';
    } elseif ($s == '0') {
        return intval($m) * (-1);
    } else {
        if ($i == '1') {
            // if($m=="10" || $m=="30" || $m=="50" || $m=="70" || $m=="90")
            // return (intval($m))-((intval($m)+10)*0.05);
            // else
            return intval($m) * 0.95;
        } elseif ($i == '2')
            return $m;
        elseif ($i == '3')
            return intval($m) * 8;
        else
            return intval($m) * 11;
    }
    return '0';

}

function getsyh($i, $r)
{
    if ($i == '1') {
        if ($r == '0' || $r == '1' || $r == '2' || $r == '3')
            return '1';
        elseif ($r == '8' || $r == '9' || $r == '10' || $r == '11')
            return '2';
        else
            return '0';
    } else if ($i == '2') {
        if ($r == '0' || $r == '1' || $r == '2' || $r == '3')
            return '0';
        elseif ($r == '8' || $r == '9' || $r == '10' || $r == '11')
            return '2';
        else
            return '1';
    } else if ($i == '3') {
        if ($r == '8' || $r == '9' || $r == '10' || $r == '11')
            return '1';
        else
            return '0';
    } else if ($i == '4') {
        if ($r == '1' || $r == '3' || $r == '5' || $r == '7' || $r == '9' || $r == '11')
            return '1';
        else
            return '0';
    } else if ($i == '5') {
        if ($r == '2' || $r == '3' || $r == '6' || $r == '7' || $r == '10' || $r == '11')
            return '1';
        else
            return '0';
    }
    return '2';

}

?>