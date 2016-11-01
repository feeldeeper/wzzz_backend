<?php
header("Content-type: text/html; charset=utf-8");
include("inc/function.php");
include("inc/conn.php");
session_set_cookie_params(SESSION_LIFE_TIME);
session_start();
$uid = $_SESSION['uid'];
if (!$uid)
    exit('请先登录');
$query = "select * from user where pid = '$uid' and type=2";
$result = $database->query($query)->fetchAll();
$inpp = $uid;
if ($result) {
    foreach ($result as $row) {
        $inpp .= "," . $row['id'];
    }
}

if (!isset($_GET['userName']))
    $username = "";
else
    $username = $_GET['userName'];
$order = new stdClass();
$order->gameId = "0";
$order->gameNum = "15453512131";
$order->totalMoney = "1000";
$order->orderType = "2";
$order->eTime = "2015-2-2 12:10:20";
$json = array();
$restdate = date("Y-m-d H:i:s", time() - 36000);
$query = "select * from user where tableid<>0 and lastlogintime>'$restdate' and pid in ($inpp)";
if ($username != "")
    $query .= " and username='$username'";


$result = $database->query($query)->fetchAll();
if ($result) {
    foreach ($result as $row) {
        $item = null;
        $item->userName = $row["username"];
        $item->nickName = $row["nickName"];
        if ($row['type'] == "1")
            $parent = "总代理";
        else
            $parent = "上线:" . getusername($row['pid'], $database);
        $item->parentTreeStr = $parent;
        $item->money = $row["currentmoney"];
        $item->wins = getwins($row["id"], $database);
        $item->activityTime = $row['lastlogintime'];
        $item->ip = $row["ip"];
        $item->tablet = $row["tableid"];
        // $item->order = getorders($row["id"],$database);
        // array_push($item->order,$order);
        array_push($json, $item);
    }
}
echo json_encode($json);

function getusername($id, $database)
{
    $query = "select * from user where id=$id";
    $row = $database->query($query)->fetch();
    $u = "";
    if ($row) {
        $u = $row['username'];
    }
    return $u;
}

function getwins($id, $database)
{

    $today = date("Y-m-d", time()) . " 00:00:00";
    $query = "select * from `injectresult` a,`round` t where a.uid=$id and a.injecttime>='$today' and a.rid=t.rid";

    $result = $database->query($query)->fetchAll();
    $u = "";
    $win = 0;
    $pwin = 0;
    if ($result) {
        foreach ($result as $row) {
            $syh = $row['syh'];
            if ($syh == "1") {
                $pwin++;
                if ($pwin > $win)
                    $win = $pwin;
            } else {
                $pwin = 0;

            }
        }
    }

    return (string)$win;

}

function getorders($id, $database)
{
    $orders = array();
    $today = date("Y-m-d", time() - 36000) . " 00:00:00";
    $query = "select * from `injectresult` a,`round` t where a.uid=$id and a.injecttime>='$today' and a.rid=t.rid order by id desc";

    $result = $database->query($query)->fetchAll();
    $u = "";
    if ($result) {
        foreach ($result as $row) {
            $norder = new stdClass();
            $norder->gameId = "0";
            $norder->gameNum = $row['gameNumber'];
            $norder->totalMoney = $row['injectmoney'];
            $norder->orderType = $row['injecttype'];
            $norder->eTime = $row['injecttime'];
            array_push($orders, $norder);
        }
    }


    return $orders;
}

?>