<?php 
// 局  1-1
// 台号   8
// 日期  2015-08-23
header("Content-type: text/html; charset=utf-8");
include("../inc/conn.php");
$rid="0";
if(isset($_GET['c']) && $_GET['c']!="" && isset($_GET['t']) && $_GET['t']!=""  && isset($_GET['d']) && $_GET['d']!="")
{
	$cc=explode("-",post_check($_GET['c']));
	$chang = $cc[0];
	$ci = $cc[1];
	$t = intval(post_check($_GET['t']));
	$d = post_check($_GET['d']);
	
	$d1 = $d." 00:00:00";
	$d2 = $d." 23:59:59";
	$sql = "select * from `round` where createtime>='$d1' and createtime<='$d2' and tab_id='$t' and gameBoot='$chang' and roundNum='$ci' and result<>-1 limit 1";
	$result = mysql_query($sql,$conn);  
	if(mysql_num_rows($result))
	{
		if($row=mysql_fetch_array($result))
		{
			$rid = $row['rid'];
		}
	}
}
echo $rid;



?>