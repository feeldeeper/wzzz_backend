<?php 
include("ExampleServices/Amf_User_Main.php");
$aa= new Amf_User_Main();// $pp=$aa->gameState("1,0");

// $arr=array("xiaozhi8888","3333","4444","5555","6666","7777","8888","9999","0000");
// $pp=$aa->seatList($arr,"3333");
// $aa->runfms($arr,"01","xiaozhi8888");
// $aa->updatemoney("xiaozhi8888");
// echo(json_encode($pp));
// $arr=array("gulu,0","xiaozhi8888,1");
echo json_encode($aa->injectMoneyOk('01','gulu'));
// echo $aa->gameState("01");


// include("ExampleServices/Amf_User.php");
// $aa= new Amf_User();// $pp=$aa->gameState("1,0");

// $arr=array("xiaozhi8888","3333","4444","5555","6666","7777","8888","9999","0000");
// $pp=$aa->loginfms("xiaozhi8888","57599f0e0f2778077f78fc00f143e41a");
// echo(json_encode($pp));

?>