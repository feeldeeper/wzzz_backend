<?php 
header("Content-type: text/html; charset=utf-8");
include("../inc/conn.php");
$res = "";
if(isset($_GET['t']) && $_GET['t']!="" && isset($_GET['cc']) && $_GET['cc']!="")
{
	$table=$_GET['t'];
	$cc=explode("-",$_GET['cc']);
	$date=date("Y-m-d",time())." 00:00:00";
	
	$sql="select SQL_CACHE * from `round` where gameBoot='$cc[0]' and roundNum='$cc[1]' and createtime>'$date'";
	$result = mysql_query($sql,$conn);
	$rid="";
	if(mysql_num_rows($result))
	{
		if($row=mysql_fetch_array($result))
		{
			$rid=$row['rid'];
		}
	}
	if($rid!="")
	{
		$sql = "select SQL_CACHE *  from `injectresult` where rid='$rid'";
		$result = mysql_query($sql,$conn);  
		if(mysql_num_rows($result))
		{
			$z=0;$x=0;$h=0;$zd=0;$xd=0;$t=0;
			while($row=mysql_fetch_array($result))
			{
				$i = $row['injecttype'];
				$m = $row['injectmoney'];
				
				$t+=intval($m);
				
				if($i=="1")
				{
					$z+=intval($m);
				}elseif($i=="2")
				{
					$x+=intval($m);
				}elseif($i=="3")
				{
					$h+=intval($m);
				}elseif($i=="4")
				{
					$zd+=intval($m);
				}elseif($i=="5")
				{
					$xd+=intval($m);
				}
			}
			$res = $z.",".$x.",".$h.",".$zd.",".$xd.",".$t;
		}
		
	}
	
	
}
if($res!="")
	echo $res;
else
	echo "0,0,0,0,0,0";

?>