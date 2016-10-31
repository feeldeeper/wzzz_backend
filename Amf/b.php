<?php 
include("ExampleServices/Conn_Amf_User_Main.php");
$aa= new Conn_Amf_User_Main();// $pp=$aa->gameState("1,0");

// $arr=array("xiaozhi8888","3333","4444","5555","6666","7777","8888","9999","0000");
// $pp=$aa->seatList($arr,"3333");
// $aa->runfms($arr,"01","xiaozhi8888");
// $aa->updatemoney("xiaozhi8888");
// echo(json_encode($pp));
$arr=array("gulu,0","xiaozhi8888,1");
$obj->fun="userInjectMoney";
$inject->z=100;
$data->inject=$inject;
$obj->data=$data;
echo json_encode($aa->seatList($arr));
 


// include("ExampleServices/Conn_Amf_User.php");
// $aa= new Conn_Amf_User();// $pp=$aa->gameState("1,0");

// $arr=array("xiaozhi8888","3333","4444","5555","6666","7777","8888","9999","0000");
// $pp=$aa->historyavi();
// echo(json_encode($aa->gameHistory("1,0")));

?>