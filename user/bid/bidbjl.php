<?php 
header("Content-type: text/html; charset=utf-8");
include("../inc/conn.php");
include("../inc/sql.class.php");
$DB=new MySql($conn);
if(isset($_GET['t']) && $_GET['t']!="")
{
	$table=$_GET['t'];
	$status=$_GET['s'];

	
	
	if($status=='1')
	{
		$cc=explode('-',$_GET['cc']);
		$chang=$cc[0];
		$ci=$cc[1];
		$ctime = date('Y-m-d H:i:s',time());
		$nt = date('mdHis',time());
		$num=sprintf("%02d",$table).$ci."15".$nt;
		$sql="insert into `round`(tab_id,gameNumber,gameState,gameBoot,roundNum,startTime,createtime,result) values('$table','$num','1','$chang','$ci','".getmicro()."','$ctime','-1')";	 
		mysql_query($sql,$conn);
		$sql="update status set txt='".$table.",1"."' where id=1";
		mysql_query($sql,$conn);
	}
	elseif($status=='3')
	{
		$rid=getlast($table,$conn);
		if($rid!="")
		{
			$sql="update `round` set gameState=3 where rid=".$rid;
			mysql_query($sql,$conn);
			$sql="update status set txt='".$table.",3' where id=1";
			mysql_query($sql,$conn);
		}		
	}
	elseif($status=='4')
	{
		$rid=getlast($table,$conn);
		if($rid!="")
		{
			$sql="update `round` set gameState=2 where rid=".$rid;
			mysql_query($sql,$conn);
		}		
	}
	elseif($status=='2')
	{
		$result=$_GET['re'];
		$rid=getlast($table,$conn);
		if($rid!="")
		{
			$sql="update `round` set gameState=0,result='$result' where rid=$rid";
			mysql_query($sql,$conn);
			$sql="update status set txt='".$table.",".$rid."' where id=2";
			mysql_query($sql,$conn);
			$sql="update status set txt='".$table.",0' where id=1";
			mysql_query($sql,$conn);
			$sql="select * from injectresult where rid=$rid";
			$query=mysql_query($sql,$conn);
			if(mysql_num_rows($query))
			{
				while($row=mysql_fetch_array($query))
				{
					$injecttype=$row['injecttype'];
					$injectmoney=$row['injectmoney'];
					$injectid=$row['id'];
					$uid=$row['uid'];
					$syh=getsyh($injecttype,$result);
					$winmoney=getwinmoney($injecttype,$syh,$injectmoney);
					$currentmoney=getcurrentmoney($uid,$conn);
					$restmoney=floatval($currentmoney)+floatval($winmoney)+floatval($injectmoney);
					$xima=getxima($uid,$injectmoney,$injecttype,$syh,$conn);
					$xima=explode(",",$xima);
					$sql2="update injectresult set syh='$syh',winmoney='$winmoney',restmoney='$restmoney',ximaliang='$xima[0]',ximayongjin='$xima[1]' where id=".$injectid;
					mysql_query($sql2,$conn);
					$sql3="update user set currentmoney ='$restmoney' where id=$uid";
					mysql_query($sql3,$conn);
					//updatemoney($uid,$conn);
				}
			}
		}
	}
	
	echo "success";
}

function getxima($uid,$money,$type,$syh,$conn)
{
	if($type=="3" || $type=="4" || $type=="5" || $syh=="2" || $money=="0")
		return "0,0";
	$tsql = "SELECT SQL_CACHE * from user where id=".$uid; 
	$query=mysql_query($tsql,$conn);
	$ximaliang=0;
	$yongjin=0;
	if(mysql_num_rows($query))
	{
		
		if($row=mysql_fetch_array($query))
		{
			$xima=floatval($row['xima']);
			$danshuangbian=intval($row['danshuangbian']);
			$m = floatval($money);
			if($syh=="0")
				$ximaliang+=$m;
			elseif($danshuangbian=="0" && $syh=="1")
			{
				$ximaliang+=$m;
			}
			$yongjin=$ximaliang*$xima*0.01;
		}
	}
	return $ximaliang.",".$yongjin;
}

function getcurrentmoney($uid,$conn)
{
	$tsql = "SELECT SQL_CACHE currentmoney from user where id=".$uid; 
	$query=mysql_query($tsql,$conn);
	$money=0;
	if(mysql_num_rows($query))
	{
		
		if($row=mysql_fetch_array($query))
		{
			$money=floatval($row['currentmoney']);
		}
	}
	return $money;
}

function updatemoney($uid,$conn)
{
	$tsql = "SELECT SQL_CACHE winmoney,money from injectresult i,user u WHERE i.uid=$uid and i.uid=u.id"; 
	$query=mysql_query($tsql,$conn);
	$tmoney=0;
	if(mysql_num_rows($query))
	{
		
		while($row=mysql_fetch_array($query))
		{
			$money=floatval($row['money']);
			$tmoney=$tmoney+floatval($row['winmoney']);
		}
		$tmoney+=$money;
	}
	$sql="update user set currentmoney='$tmoney' where id=$uid";
	mysql_query($sql,$conn);
}

function getwinmoney($i,$s,$m)
{
	if($s=='2')
	{
		return '0';
	}
	elseif($s=='0')
	{
		return intval($m)*(-1);
	}
	else
	{
		if($i=='1')
		{
			// if($m=="10" || $m=="30" || $m=="50" || $m=="70" || $m=="90")
				// return (intval($m))-((intval($m)+10)*0.05);
			// else
			return intval($m)*0.95;
		}
		elseif($i=='2')
			return $m;
		elseif($i=='3')
			return intval($m)*8;
		else
			return intval($m)*11;		
	}
	return '0';
	
}

function getsyh($i,$r)
{
	if($i=='1')
	{
		if($r=='0' || $r=='1' || $r=='2' || $r=='3')
			return '1';
		elseif($r=='8' || $r=='9' || $r=='10' || $r=='11')
			return '2';
		else
			return '0';
	}
	else if($i=='2')
	{
		if($r=='0' || $r=='1' || $r=='2' || $r=='3')
			return '0';
		elseif($r=='8' || $r=='9' || $r=='10' || $r=='11')
			return '2';
		else
			return '1';
	}
	else if($i=='3')
	{
		if($r=='8' || $r=='9' || $r=='10' || $r=='11')
			return '1';
		else
			return '0';
	}
	else if($i=='4')
	{
		if($r=='1' || $r=='3' || $r=='5' || $r=='7' || $r=='9' || $r=='11')
			return '1';
		else
			return '0';
	}
	else if($i=='5')
	{
		if($r=='2' || $r=='3' || $r=='6' || $r=='7' || $r=='10' || $r=='11')
			return '1';
		else
			return '0';
	}
	return '2';
	
}

function getmicro()
{
	$time = explode ( " ", microtime () );  
	$time3 = ($time [0] * 1000);  
	$time2 = explode ( ".", $time3 );  
	$time = $time[1].sprintf("%03d",$time2 [0]);  
	
	return floatval($time);
}

function getlast($id,$conn)
{
	$sql="select SQL_CACHE * from `round` where tab_id=$id order by rid desc";
	$query = mysql_query($sql,$conn);
	if($row = mysql_fetch_array($query))
	{
		return $row['rid'];
	}
	
	return "";
}

function getalllast($conn)
{
	$text="";
	for($i=1;$i<20;$i++){
		$sql="select SQL_CACHE * from `round` where tab_id=$i order by rid desc";
		$query = mysql_query($sql,$conn);
		if($row = mysql_fetch_array($query))
		{
			if($row['gameState']=="1")
				$s="投注中";
			else if($row['gameState']=='2')
				$s="投注结束";
			else if($row['gameState']=='0')
			{
				$s="已有结果 ".getjieguo($row['result'],$conn);
			}
			else
				$s="洗牌中";
			$text.="台号:".sprintf('%02d',$i)." 第".$row['gameBoot']."场 第".$row['roundNum']."次 ".$s." ".$row['createtime']."<br/>";
			//$text.="台号:".sprintf('%02d',$i)." 第".$row['gameBoot']."场 第".$row['roundNum']."次 ".row['createtime'];
		}
	}
	
	return $text;
	
	
}

function getjieguo($id,$conn)
{
	$sql="select SQL_CACHE * from result where rid=".$id;
	$query = mysql_query($sql,$conn);
	if($row=mysql_fetch_array($query))
	{
		return $row['result'];
	}
	return "和";
}
?>