<?php 
header("Content-type: text/html; charset=utf-8");
include("inc/conn.php");
if(isset($_POST['table']) && $_POST['table']!="")
{
	$table=$_POST['table'];
	$status=$_POST['status'];
 
	
	
	if($status=='1')
	{
		$chang=$_POST['chang'];
		$ci=$_POST['ci'];
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
	elseif($status=='2')
	{
		$result=$_POST['result'];
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
					$sql2="update injectresult set syh='$syh',winmoney='$winmoney' where id=".$injectid;
					mysql_query($sql2,$conn);
					updatemoney($uid,$conn);
				}
			}
		}
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
<script>
$(document).ready(function() {

	$("#status").change(function(e){

		select_car_model(this);
    });
	function select_car_model(){
		var status = $("#status").val();
		
		if(status=='1')
		{	$('#ok1')[0].style.display="block"; $('#ok2')[0].style.display="none"; }
		else if(status=='2')
		{	   $('#ok1')[0].style.display="none"; $('#ok2')[0].style.display="block"; }
		else if(status=='3')
		{	$('#ok1')[0].style.display="none"; $('#ok2')[0].style.display="none"; }
	}
});


</script>
<div><h1 style="padding-left:350px;">投注控制</h1>
</div>
<div style="padding-left:150px;padding-top:10px;">
<form action="touzhu.php" method="post">
选择台号:
<select name="table" style="width:120px;" >
<?php 
$arr=array(1,2,5,6,7,8,10,11,12,15);
for($i=0;$i<count($arr);$i++)
{
	if($arr[$i]==$_POST['table'])
		echo '<option value="'.$arr[$i].'" selected="selected">'.sprintf('%02d',$arr[$i]).'</option>';
	else
		echo '<option value="'.$arr[$i].'">'.sprintf('%02d',$arr[$i]).'</option>';
}
?> 
</select><br /><br />
选择状态:
<select name="status" id="status" style="width:120px;" >
<option value="1">开始投注</option>
<option value="2">投注结果</option>
<option value="3">洗牌中</option>
</select><br /><br />
<div id="ok1" style="display:block;">
场:<input name='chang' type="text" style="width:65px;" value="<?=$_POST['chang']?>"/> 次：<input name="ci" style="width:68px;" value="<?=$_POST['ci']?>"/><br/><br />
</div>
<div id="ok2" style="display:none;">
输入结果:
<select name="result" style="width:120px;">
<option value="0">庄赢</option>
<option value="4">闲赢</option>
<option value="8">和</option>
<option value="1">庄赢 庄对</option>
<option value="2">庄赢 闲对</option>
<option value="5">闲赢 庄对</option>
<option value="6">闲赢 闲对</option>
<option value="9">和 庄对</option>
<option value="10">和 闲对</option>
<option value="3">庄赢 庄对 闲对</option>
<option value="7">闲赢 庄对 闲对</option>
<option value="11">和 庄对 闲对</option>
</select><br /><br />
</div>
<input type="submit" value="提交"/><br /><br />

</form>
<span style="color:red;">
<?php echo getalllast($conn);?>
</span>
</div>