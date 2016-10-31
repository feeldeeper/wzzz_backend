<?php 
header("Content-type: text/html; charset=utf-8");
include("inc/conn.php");
if(isset($_POST['alltxt']) && $_POST['alltxt']!="")
{
	$alltxt=$_POST['alltxt']; 
	$alltxt=explode("\n",$alltxt);
	//echo ','.trim($alltxt[1]).',';exit();
	$cc= count($alltxt);
	// echo ','.$cc.',';exit();
	for($i=0;$i<$cc;$i++)
	{
		$content=trim($alltxt[$i]);
		if($content=="")
			continue;
		$content=explode("-",$content);
		
		$table=$content[0];
		$chang=$content[1];
		$ci=$content[2];
		$re=$content[3];
		$ctime = date('Y-m-d H:i:s',time());
		$nt = date('mdHis',time());
		$num=sprintf("%02d",$table).$ci."15".$nt;
		$sql="insert into `round`(tab_id,gameNumber,gameState,gameBoot,roundNum,startTime,createtime,result) values('$table','$num','0','$chang','$ci','".getmicro()."','$ctime','$re')";	 
		mysql_query($sql,$conn);
		$sql="update status set txt='".$table.",1"."' where id=1";
		mysql_query($sql,$conn);
	} 
	
	echo "<script>alert('已提交!');</script>";
}

function updatemoney($uid,$conn)
{
	$tsql = "SELECT winmoney,money from injectresult i,user u WHERE i.uid=$uid and i.uid=u.id"; 
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
			return intval($m)*0.95;
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
	$sql="select * from `round` where tab_id=$id order by rid desc";
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
		$sql="select * from `round` where tab_id=$i order by rid desc";
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
	$sql="select * from result where rid=".$id;
	$query = mysql_query($sql,$conn);
	if($row=mysql_fetch_array($query))
	{
		return $row['result'];
	}
	return "和";
}
?>
<script src="http://libs.baidu.com/jquery/1.9.1/jquery.min.js"></script>
 
<div><h1 style="padding-left:350px;">批量添加投注记录</h1>
</div>
<div style="padding-left:150px;padding-top:10px;">
<form action="ptouzhu.php" method="post">
  
结果代码: 0=庄赢，4=闲赢，8=和，1=庄赢 庄对，2=庄赢 闲对，5=闲赢 庄对，6=闲赢 闲对<br/>9=和 庄对，10=和 闲对，3=庄赢 庄对 闲对，7=闲赢 庄对 闲对，11=和 庄对 闲对
 <br /><br />
说明：一行一个，前面的行先入库，每行规则是，台号-场-次-结果代码<br/>例如1-8-10-0  代表 1号台8场10次庄赢
<textarea name="alltxt" rows="20" cols="100">
</textarea><br/>
<input type="submit" value="提交"/><br /><br />

</form>
<span style="color:red;">
<?php echo getalllast($conn);?>
</span>
</div>