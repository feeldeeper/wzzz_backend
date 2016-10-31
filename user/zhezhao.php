<?php 
header("Content-type: text/html; charset=utf-8");
include("inc/conn.php");
if(isset($_POST['sta']) && $_POST['sta']!="")
{
	if($_POST['sta']=="1")
		upsta('0',$conn);
	else
		upsta('1',$conn);
}

$staa=getalllast($conn);

function upsta($txt,$conn)
{
	$sql="update `status` set txt='$txt' where id=3";
	mysql_query($sql,$conn); 
}

function getalllast($conn)
{
	$text="";
	for($i=1;$i<20;$i++){
		$sql="select * from `status` where id=3";
		$query = mysql_query($sql,$conn);
		if($row = mysql_fetch_array($query))
		{
			if($row['txt']=="1")
				 return "1";
		}
	}
	
	return "0";
	
	
} 
?>
<script src="http://libs.baidu.com/jquery/1.9.1/jquery.min.js"></script>
 
<div><h1 style="text-align:center;">遮罩控制</h1>
</div>
<div style="padding-top:10px;text-align:center;">
<form name="form1" id="form1" action="zhezhao.php" method="post">
 
<input type="hidden" value="<?=$staa?>" name="sta" /> 


<input type="submit" style="font-size:30px;color:blue;" value="<?php
if($staa=="1")
	echo "取消遮罩";
else
	echo "加上遮罩";
?>">

</form>
 
</div>