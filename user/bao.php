<?php 
header("Content-type: text/html; charset=utf-8");
include("inc/conn.php");
 

function getinjecttype($id,$conn)
{
	$sql="select * from `type` where tid=$id";
	$query = mysql_query($sql,$conn);
	if($row = mysql_fetch_array($query))
	{ 
		$arr=array(str_replace(".00","",$row['rate']),$row['type']);
	}
	else
		$arr=array(0,"错误");
		
	return $arr;
}

function getresult($id,$conn)
{
	$sql="select * from `result` where rid=$id";
	$query = mysql_query($sql,$conn);
	if($row = mysql_fetch_array($query))
	{ 
		$arr=$row['result'];
	}
	else
		$arr="";
		
	return $arr;
}

function getusername($id,$conn)
{
	$sql="select * from `user` where id=$id";
	$query = mysql_query($sql,$conn);
	if($row = mysql_fetch_array($query))
	{ 
		$arr=$row['username'];
	}
	else
		$arr="";
		
	return $arr;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0036)http://ag.88gobo.net/default/online/ -->
<html  xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title></title>
<script src="http://libs.baidu.com/jquery/1.9.1/jquery.min.js"></script>
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
</head>
<body>

<div><h1 style="padding-left:300px;">用户报表</h1>
</div>

<div id="q" style="padding-left:150px;">
 <table class="altrowstable">
 <tr style="background:#BFD5FD"><th>用户名</th><th>下注金额</th><th>局编号</th><th>下注日期</th><th>下注</th><th>开奖结果</th><th>结果</th><th>倍率</th><th>利润</th></tr>
<?php 
$id=$_GET['id'];
if(isset($id) && $id!="")
{
	$sql="select * from `injectresult` as i,`round` as r where uid=$id and syh<>-1 and r.rid=i.rid order by injecttime desc";
	$query = mysql_query($sql,$conn);
	$i=0;
	$wint=0;
	$winm=0;
	$loset=0;
	$losem=0;
	$he=0;
	while($row = mysql_fetch_array($query))
	{ 	 
		$i++;
		if($i%2==1)
			$color="oddrowcolor";
		else
			$color="evenrowcolor";
		$type=getinjecttype($row['injecttype'],$conn);
		$result=getresult($row['result'],$conn);
		$username=getusername($id,$conn);
		$syh=$row['syh'];
		$winmoney=floatval($row['winmoney']);
		if($syh=='0')
		{
			$loset++;
			$losem-=$winmoney;
			$r="<span style='color:red'>输</span>";
		}
		elseif($syh=='1')
		{
			$wint++;
			$winm+=$winmoney;
			
			$r="<span style='color:blue'>赢</span>";
		}
		else
		{
			$he++;
			$r="<span style='color:green'>和</span>";
		}
		
		echo "<tr class='$color'><td>".$username."</td><td>".$row['injectmoney']."</td><td>".$row['gameBoot']."-".$row['roundNum']."</td><td>".$row['injecttime']."</td><td>".$type[1]."</td><td style='text-align:center;'>".$result."</td><td>".$r."</td><td>".$type[0]."</td><td>".$winmoney."</td></tr>"; 
	}
	$cha=$winm-$losem;
	
	echo "</table><br/><table  class='altrowstable'><tr style='background:#BFD5FD'><th>赢次数</th><th>输次数</th><th>和次数</th><th>赢金额</th><th>输金额</th><th>输赢合计</th></tr><tr><td>$wint</td><td>$loset</td><td>$he</td><td>$winm</td><td>$losem</td><td>$cha</td></tr></table><br/><br/><br/>";
	
}
?>
 
 
 </div>
</body></html> 