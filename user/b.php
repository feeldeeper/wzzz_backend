<?php 
header("Content-type: text/html; charset=utf-8");
include("inc/function.php");
include("inc/conn.php");
include("inc/sql.class.php");
$DB = new MySql($conn);
echo json_encode($DB->RelativeID('3'));

?>