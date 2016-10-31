<?php 
header("Content-type: text/html; charset=utf-8");
include("../inc/conn.php");
if(isset($_GET['t']) && $_GET['t']!="")
{
	$table=$_GET['t'];
	
	$today = date("Y-m-d",time())." 00:00:00";
	$sql = "select SQL_CACHE * from `tablet` where tab_id='$table'";
	$result = mysql_query($sql,$conn);  
	if(mysql_num_rows($result))
	{
		if($row=mysql_fetch_array($result))
		{
			echo $row['telMin']."-".$row['telMax'];
		}
		else
		{echo "10-20000";}
	}else
	{
		echo "10-20000";
	}
}else{echo "10-20000";}
?>