<?php 
header("Content-type: text/html; charset=utf-8");
include("inc/function.php");
include("inc/conn.php");
echo json_encode($database->RelativeID('3'));

?>