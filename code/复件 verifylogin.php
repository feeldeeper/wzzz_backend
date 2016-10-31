<?php 
include("../user/inc/conn.php");
$sql="select count(1) as c from user where username='".$_GET['u']."' and password='".$_GET['p']."'";
$result = mysql_query($sql,$conn);
$row=mysql_fetch_array($result);
echo "data=".$row['c'];
?>