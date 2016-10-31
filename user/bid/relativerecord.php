<?php 
// 局  1-1
// 台号   8
// 日期  2015-08-23
header("Content-type: text/html; charset=utf-8");
include("../inc/conn.php");

$arr = array();
if(isset($_GET['id']) && $_GET['id']!="")
{
	$id=intval(post_check($_GET['id']));
	$c=post_check($_GET['c']);
	$sql = "select * from `injectresult` where syh<>-1 and rid=".$id;
	$result = mysql_query($sql,$conn);  
	$rr = getresult($id,$conn);
	if(mysql_num_rows($result))
	{
		while($row=mysql_fetch_array($result))
		{
			$temp->username=getusername($row['uid'],$conn);
			$temp->cc=$c;
			$temp->itime=$row['injecttime'];
			$temp->result=$rr;
			$temp->type=getinjecttype($row['injecttype'],$conn);
			if($row['haschanged']=="1")
				$temp->result.=" 修正";
			$temp->money=$row['injectmoney'];			
			$temp->profit=$row['winmoney'];
			$temp->peicai=floatval($temp->money)+floatval($temp->profit);
			$temp->rest=$row['restmoney'];
		
			array_push($arr,$temp);
			$temp=null;
		}
	}
}

echo json_encode($arr);

function getinjecttype($id,$conn)
{
	switch($id)
	{
		case "1": return "庄";
		case "2": return "闲";
		case "3": return "和";
		case "4": return "庄对";
		case "5": return "闲对";
	}
	return "无";
}

function getresult($rid,$conn)
{
	$u="";
	$sql="select s.result as rr from `round` r,`result` s where r.result=s.rid and r.rid=".$rid;
	$result = mysql_query($sql,$conn);
	if(mysql_num_rows($result))
	{
		if($row=mysql_fetch_array($result))
		{
			$u = $row['rr'];
		}
	}
	
	return $u;

}

function getusername($id,$conn)
{
	$u="";
	$sql="select * from user where id=".$id;
	$result = mysql_query($sql,$conn);
	if(mysql_num_rows($result))
	{
		if($row=mysql_fetch_array($result))
		{
			$u = $row['username'];
		}
	}
	return $u;

}
//编号 用户名 日期 时间 场次 台号 押注金额 投注类型 结果 利润



?>