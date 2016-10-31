<?php 
header("Content-type: text/html; charset=utf-8");
include("inc/function.php");
include("inc/conn.php");
session_set_cookie_params(SESSION_LIFE_TIME);
session_start();
$uid = $_SESSION['uid'];
$query = "select * from user where pid = '$uid' and type=2";
$result=mysql_query($query,$conn);
$inpp = $uid;
if(mysql_num_rows($result))
{
	while($row = mysql_fetch_array($result))
	{
		$inpp.=",".$row['id'];
	}
}


if(!isset($_GET['userName']))
	$username="";
else
	$username = $_GET['userName'];

$order->gameId="0";
$order->gameNum="15453512131";
$order->totalMoney="1000";
$order->orderType="2";
$order->eTime="2015-2-2 12:10:20";
$json=array();
$restdate = date("Y-m-d H:i:s",time()-36000);
$query ="select * from user where tableid<>0 and lastlogintime>'$restdate' and pid in ($inpp)";
if($username!="")
	$query.=" and username='$username'";
	
	
$result=mysql_query($query,$conn);
if(mysql_num_rows($result))
{
	while($row = mysql_fetch_array($result))
	{
		$item=null;
		$item->userName = $row["username"];
		$item->nickName = $row["nickName"];
		if($row['type']=="1")
			$parent = "总代理";
		else
			$parent = "上线:".getusername($row['pid'],$conn);
		$item->parentTreeStr = $parent;
		$item->money = $row["currentmoney"];
		$item->wins = getwins($row["id"],$conn);
		$item->activityTime = $row['lastlogintime'];
		$item->ip =  $row["ip"];
		$item->tablet =  $row["tableid"];
		// $item->order = getorders($row["id"],$conn);
		// array_push($item->order,$order);
		array_push($json,$item);
	}
}
echo json_encode($json);

function getusername($id,$conn)
{
	$query = "select * from user where id=$id";
	$result=mysql_query($query,$conn);
	$u = "";
	if(mysql_num_rows($result))
	{
		if($row = mysql_fetch_array($result))
		{
			$u = $row['username'];
		}
	}
	return $u;
}

function getwins($id,$conn)
{

	$today=date("Y-m-d",time())." 00:00:00";
	$query = "select * from `injectresult` a,`round` t where a.uid=$id and a.injecttime>='$today' and a.rid=t.rid";
	
	$result=mysql_query($query,$conn);
	$u = "";
	$win = 0;
	$pwin = 0;
	if(mysql_num_rows($result))
	{
		while($row = mysql_fetch_array($result))
		{
			$syh = $row['syh'];
			if($syh == "1")
			{
				$pwin++;
				if($pwin>$win)
					$win = $pwin;
			}else{
				$pwin = 0;
				
			}
		}
	}
	
	return (string)$win;
	
}

function getorders($id,$conn)
{
	$orders = array();
	$today=date("Y-m-d",time()-36000)." 00:00:00";
	$query = "select * from `injectresult` a,`round` t where a.uid=$id and a.injecttime>='$today' and a.rid=t.rid order by id desc";
	
	$result=mysql_query($query,$conn);
	$u = "";
	if(mysql_num_rows($result))
	{
		while($row = mysql_fetch_array($result))
		{
			$order->gameId="0";
			$order->gameNum=$row['gameNumber'];
			$order->totalMoney=$row['injectmoney'];
			$order->orderType=$row['injecttype'];
			$order->eTime=$row['injecttime'];
			array_push($orders,$order);
			$order=null;
		}
	}
	
	
	
	return $orders;
}
?>