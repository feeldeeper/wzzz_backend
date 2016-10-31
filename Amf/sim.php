<?php 
// include("ExampleServices/Sim_Main.php");
include("ExampleServices/Sim_Bai.php");
$aa= new Sim_Bai();
// $aa= new Sim_Main();

// $arr=array("xiaozhi8888","3333","4444","5555","6666","7777","8888","9999","0000");
// $pp=$aa->seatList($arr,"3333");
// $aa->runfms($arr,"01","xiaozhi8888");
// $aa->updatemoney("xiaozhi8888");
// echo(json_encode($pp));
// $arr=array("gulu,0","gulu1,1");
echo json_encode($aa->gameInfo("11"))."<br>";

// $aa->abc();
// echo $aa->gameState("01");


// include("ExampleServices/Amf_User.php");
// $aa= new Amf_User();// $pp=$aa->gameState("1,0");

// $arr=array("xiaozhi8888","3333","4444","5555","6666","7777","8888","9999","0000");
// $pp=$aa->loginfms("xiaozhi8888","57599f0e0f2778077f78fc00f143e41a");
// echo(json_encode($pp));

?>