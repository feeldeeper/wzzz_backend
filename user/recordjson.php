<?php 
header("Content-type: text/html; charset=utf-8");
include("inc/function.php");
include("inc/conn.php");

if(!isset($_POST['username']))
{
	$username="";
}
else
	$username = post_check($_POST['username']);
	
if(!isset($_POST['pwd']))
{
	$pwd="";
}
else
	$pwd = post_check($_POST['pwd']);
	
if(!isset($_POST['date']))
{
	$date="2015-08-22";
}
else
	$date = post_check($_POST['date']);


$json=array();
$query ="select * from user where username='$username'";


$row = $database->query($query)->fetch();

if($row)
{
	$json =  getorders($row["id"],$date,$database);
}

echo json_encode($json);


function getorders($id,$date,$database)
{
	$orders = array();
	$stime=$date." 00:00:00";
	$etime=$date." 23:59:59";
	$query = "select * from `injectresult` a,`round` t where a.uid=$id and a.injecttime>='$stime' and a.injecttime<='$etime' and a.syh<>-1 and a.rid=t.rid order by id desc";
	
	$result = $database->query($query)->fetchAll();
	$u = "";
	if($result)
	{
		foreach($result as $row)
		{
			$order = new stdClass();
			$order->gameNum=$row['gameNumber'];
			$time=explode(" ",$row['injecttime']);
			$order->date=$time[0];
			$order->time=$time[1];
			$order->game="百家乐";
			$order->tablet=sprintf("%02d",$row['tab_id']);
			$order->round=$row['gameBoot']."-".$row['roundNum'];
			switch($row['injecttype'])
			{
				case '1':$order->ma="庒";break;
				case '2':$order->ma="閑";break;
				case '3':$order->ma="和";break;
				case '4':$order->ma="庒對";break;
				case '5':$order->ma="閑對";break;
			}
			switch($row['result'])
			{
				case '0':$order->result="庒";break;
				case '1':$order->result="庒 庒對";break;
				case '2':$order->result="庒 閑對";break;
				case '3':$order->result="庒 庒對 閑對";break;
				case '4':$order->result="閑";break;
				case '5':$order->result="閑 庒對";break;
				case '6':$order->result="閑 閑對";break;
				case '7':$order->result="閑 庒對 閑對";break;
				case '8':$order->result="和";break;
				case '9':$order->result="和 庒對";break;
				case '10':$order->result="和 閑對";break;
				case '11':$order->result="和 庒對 閑對";break;
			}
			$order->money=$row['injectmoney'];
			if($row['syh']=='0')
				$order->syh="輸";
			elseif($row['syh']=='1')
				$order->syh="贏";
			elseif($row['syh']=='2')
				$order->syh="和";
			
			$order->gameNum=$row['gameNumber'];
			$order->totalMoney=$row['injectmoney'];
			$order->orderType=$row['injecttype'];
			$order->status="已結束";
			array_push($orders,$order);
		}
	}
	
	
	
	return $orders;
}


?>