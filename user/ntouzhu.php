<?php 
header("Content-type: text/html; charset=utf-8");
include("inc/conn.php");
session_set_cookie_params(SESSION_LIFE_TIME);
session_start();
$arr=array(1,2,5,6,7,8,11,12,15,16,17,18,19,22,25,26,27);
$tab = $_SESSION['admintab'];
empty($tab) && exit();

$arr=explode(',',$tab);
for($i=0;$i<count($arr);$i++)
{
	$arr[$i]=intval($arr[$i]);
}

if(isset($_POST['ok']) && $_POST['ok']=="1")
{
	
	for($i=0;$i<count($arr);$i++)
	{
		$changname="chang".$arr[$i];
		$ciname="ci".$arr[$i];
		$$changname = $_POST[$changname];  
		$$ciname = $_POST[$ciname];
	}
	
	
	
	if(isset($_GET['id']) && $_GET['id']!="")
	{
		$id=$_GET['id'];
	}
	else
		$id="1";

	if(isset($_GET['action']) && $_GET['action']=="wash")
	{   
		$changnames='chang'.$id;
		$cinames='ci'.$id;
		
		$rid=getlast($id,$database);
		if($rid!="")
		{
			$sql="update `round` set gameState=3 where rid=".$rid;
			$database->query($sql);
			$sql="update status set txt='".$id.",3' where id=1";
			$database->query($sql);
		}	
		
		$$changnames=intval($$changnames)+1; 
		$$cinames="1";
	}
	elseif(isset($_GET['action']) && $_GET['action']=="touzhu")
	{
		$changnames='chang'.$id;
		$cinames='ci'.$id;
		$chang=$$changnames;
		$ci=$$cinames;
		
		$ctime = date('Y-m-d H:i:s',time());
		$nt = date('mdHis',time());
		$num=sprintf("%02d",$id).$ci."15".$nt;
		$sql="insert into `round`(tab_id,gameNumber,gameState,gameBoot,roundNum,startTime,createtime,result) values('$id','$num','1','$chang','$ci','".getmicro()."','$ctime','-1')";
		$database->query($sql);
		$sql="update status set txt='".$id.",1"."' where id=1";
		$database->query($sql);
		
	}
	elseif(isset($_GET['action']) && $_GET['action']=="submit")
	{ 
		$changnames='chang'.$id;
		$cinames='ci'.$id;
		$chang=$$changnames;
		$ci=$$cinames;
		
		$zxhname='zxh'.$id;
		$zxdname='zxd'.$id;
		 
		$zxh = intval($_POST[$zxhname]);
		$zxd = $_POST[$zxdname];
		for($i=0;$i<count($zxd);$i++)
		{
			$zxh+=intval($zxd[$i]);
		} 
		
		if(!isresult($id,$database))
		{ 
			$ctime = date('Y-m-d H:i:s',time());
			$nt = date('mdHis',time());
			$num=sprintf("%02d",$id).$ci."15".$nt;
			$sql="insert into `round`(tab_id,gameNumber,gameState,gameBoot,roundNum,startTime,createtime,result) values('$id','$num','0','$chang','$ci','".getmicro()."','$ctime','$zxh')";
			$database->query($sql);
			
			$rid=getlast($id,$database);
			if($rid!="")
			{
				$sql="update status set txt='".$id.",".$rid."' where id=2";
				$database->query($sql);
				$sql="update status set txt='".$id.",0' where id=1";
				$database->query($sql);
			} 
		}else
		{
			$rid=getlast($id,$database);
			if($rid!="")
			{
				$sql="update `round` set gameState=0,result='$zxh' where rid=$rid";
				$database->query($sql);
				$sql="update status set txt='".$id.",".$rid."' where id=2";
				$database->query($sql);
				$sql="update status set txt='".$id.",0' where id=1";
				$database->query($sql);
				$sql="select * from injectresult where rid=$rid";
				$result=$database->query($sql)->fetchAll();
				if($result)
				{
					foreach($result as $row)
					{
						$injecttype=$row['injecttype'];
						$injectmoney=$row['injectmoney'];
						$injectid=$row['id'];
						$uid=$row['uid'];
						$syh=getsyh($injecttype,$zxh);
						$winmoney=getwinmoney($injecttype,$syh,$injectmoney);
						$sql2="update injectresult set syh='$syh',winmoney='$winmoney' where id=".$injectid;
						mysql_query($sql2,$database);
						updatemoney($uid,$database);
					}
				}
			}
		} 
		
		$$cinames=intval($$cinames)+1;  		
	}
	
}
else
{
	for($i=0;$i<count($arr);$i++)
	{
		$tid=$arr[$i];
		$sql="select * from `round` where tab_id=$tid order by rid desc";
		$row = $database->query($sql)->fetch();
		if($row)
		{
			$boot = intval($row['gameBoot']);
			$round = intval($row['roundNum'])+1;
			if($row["gameState"]=="3")
			{
				$boot = $boot+1;;
				$round = 1;
			}
			elseif($row["gameState"]=="2" || $row["gameState"]=="1")
			{
				$round--;
			}
			
		}else
		{
			$boot=1;
			$round=1;
		}
		
		$changname="chang".$tid;
		$ciname="ci".$tid;
		$$changname = $boot;
		$$ciname = $round;
	}
}



function updatemoney($uid,$database)
{
	$tsql = "SELECT winmoney,money from injectresult i,user u WHERE i.uid=$uid and i.uid=u.id";
	$result = $database->query($tsql)->fetchAll();
	$tmoney=0;
	if($result)
	{
		
		foreach($result as $row)
		{
			$money=floatval($row['money']);
			$tmoney=$tmoney+floatval($row['winmoney']);
		}
		$tmoney+=$money;
	}
	$sql="update user set currentmoney='$tmoney' where id=$uid";
	$database->query($sql);
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

function getlast($id,$database)
{
	$sql="select * from `round` where tab_id=$id order by rid desc";
	$row = $database->query($sql)->fetch();
	if($row)
	{
		return $row['rid'];
	}
	
	return "";
}

function isresult($id,$database)
{
	$sql="select * from `round` where tab_id=$id order by rid desc";
	$row = $database->query($sql)->fetch();
	if($row)
	{
		if($row['result']=="-1")
			return true;
	}
	
	
	return false;
}

function getalllast($database)
{
	$text='<table class="altrowstable"><tr style="background:#BFD5FD;margin:auto;"><th>台号</th><th>场</th><th>次</th><th>状态</th><th>时间</th></tr>';
	for($i=1;$i<28;$i++){
		$sql="select * from `round` where tab_id=$i order by rid desc";
		$row = $database->query($sql)->fetch();
		if($row)
		{
			if($row['gameState']=="1")
				$s="投注中";
			else if($row['gameState']=='2')
				$s="投注结束";
			else if($row['gameState']=='0')
			{
				$s="已有结果 ".getjieguo($row['result'],$database);
			}
			else
				$s="洗牌中";
			$text.="<tr class='oddrowcolor'><td>".sprintf('%02d',$i)."</td><td>第".$row['gameBoot']."场</td><td>第".$row['roundNum']."次</td><td>".$s."</td><td>".$row['createtime']."</td></tr>";
			//$text.="台号:".sprintf('%02d',$i)." 第".$row['gameBoot']."场 第".$row['roundNum']."次 ".row['createtime'];
		}
	}
	
	return $text;
	
	
}

function getjieguo($id,$database)
{
	$sql="select * from result where rid=".$id;
	$row = $database->query($sql)->fetch();
	if($row)
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
<style>
table.altrowstable {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
	color:#333333;
	border-width: 1px;
	border-color: #a9c6c9;
	border-collapse: collapse;
}
table.altrowstable th {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #a9c6c9;
}
table.altrowstable td {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #a9c6c9;
}
.oddrowcolor{
	background-color:#d4e3e5;
}
.evenrowcolor{
	background-color:#c3dde0;
}
</style>
<div  style="text-align:center;"><h1>新版投注控制</h1>
</div>
<div style="padding-top:10px;text-align:center;">
<form name="form1" id="form1" action="ntouzhu.php" method="post">
<input type="hidden" value="1" name="ok" />
<table border="1" cellpadding="0" cellspacing="0" style="margin:auto;">
<tr style="text-align:center;"><td>台号</td><td>场次</td><td>投注结果</td><td colspan="2">状态</td></tr>
<?php 


for($i=0;$i<count($arr);$i++){ 
	$tid=$arr[$i];
	$tdid=sprintf("%02d",$tid);
	$chang='chang'.$tid;
	$ci='ci'.$tid;
	
?>
<tr>
<td><?=$tdid?></td>
<td>
	场: <input type="text" name="chang<?=$tid?>" value="<?=$$chang?>" style="width:30px"> 
	次:<input name="ci<?=$tid?>" type="text" readOnly="true" value="<?=$$ci?>"  style="text-align:center; border-style: none; width:20px"> 
</td>
<td>
	<input type="radio" checked value="0" name="zxh<?=$tid?>">庄赢
	<input type="radio" value="4" name="zxh<?=$tid?>">闲赢
	<input type="radio" value="8" name="zxh<?=$tid?>">和 
	<input type="checkbox" name="zxd<?=$tid?>[]" value="1">庄对 
	<input type="checkbox" name="zxd<?=$tid?>[]" value="2">闲对 
	<input type="button" onclick="form1.action='ntouzhu.php?action=submit&id=<?=$tid?>';form1.submit();" style="background-color:#529214; border:1px solid #529214;color:#fff;" name="" value="提交">&nbsp;&nbsp;
</td>
<td>
	
	&nbsp;&nbsp;<input type="button" onclick="form1.action='ntouzhu.php?action=touzhu&id=<?=$tid?>';form1.submit();" style="background-color:#6299c5; border:1px solid #6299c5;color:#fff;" name="touzhu" value="开始投注">&nbsp;&nbsp;
</td>
<td>
	<input type="button" onclick="form1.action='ntouzhu.php?action=wash&id=<?=$tid?>';form1.submit();" value="洗牌" style="background-color:#dff4ff; border:1px solid #c2e1ef;color:#336699;" name="wash">
</td>
</tr>
<?php } ?>
</table>

 

</form>
<div style="text-align:center;">
<center>
<?php echo getalllast($database);?>
</center>
</div>
</div>