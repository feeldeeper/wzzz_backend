<?php 
header("Content-type: text/html; charset=utf-8");
include("../inc/conn.php");
$record="";
if(isset($_GET['c']) && $_GET['c']!="" && isset($_GET['t']) && $_GET['t']!="")
{
	$cc=explode("-",$_GET['c']);
	$chang = $cc[0];
	$t = intval($_GET['t']);
	
	$today = date("Y-m-d",time())." 00:00:00";
	$sql = "select SQL_CACHE * from `round` where createtime>='$today' and tab_id='$t' and gameBoot='$chang' and result<>-1 order by rid asc";
	$result = $database->query($sql)->fetchAll();
	if($result)
	{
		foreach($result as $row)
		{
			$letter = getletter(intval($row['result']));
			$record.=$letter;
		}
	}
}
echo $record;
function getletter($num)
{
	$array=array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
	return $array[$num];
}

?>