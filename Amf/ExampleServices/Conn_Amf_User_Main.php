<?php
/**
 *  This file is part of amfPHP
 *
 * LICENSE
 *
 * This source file is subject to the license that is bundled
 * with this package in the file license.txt.
 * @package Amfphp_Examples_Authentication
 */

/**
 * includes
 */
require_once dirname(__FILE__) . '/../Includes/constants.php';

/**
 * Authentication and user administration service
 *
 * @package Amfphp_Examples_Authentication
 * @author Sven Dens
 */
class Conn_Amf_User_Main {


    /**
     * protected methods
     * @var array 
     */
	 
    public static $protectedMethods = array();
    
    /**
     * constructor
     * @throws Exception
     */
    function __construct() {
        if (!defined('PDO::ATTR_DRIVER_NAME')) {
            throw new Exception('PDO unavailable');
        }
    }

    /**
     * sign in
     * @param string $username
     * @param string $password
     * @return boolean
     */
	public function login($obj) {
            $conn = mysql_pconnect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);mysql_select_db(MYSQL_DB,$conn); 

            $tsql = "SELECT * from user WHERE username = :username AND password = :pass";

            $stmt = mysql_query($tsql,$conn); 
            if ($result=mysql_fetch_array($stmt)) { 
                $res->type = true;
            } else {
                $res->type = false;
            }
			mysql_free_result($stmt);mysql_close($conn);
		return $res;

    }
	
	public function userSurplusMoney($userid) {
		self::updatemoney($userid);

		$conn = mysql_pconnect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);mysql_select_db(MYSQL_DB,$conn); 

		
		$tsql = "SELECT * from user WHERE username = '$userid'";
		
		$stmt  = mysql_query($tsql,$conn); 
		if ($result=mysql_fetch_array($stmt)) { 
			$res->type = "userSurplusMoney";
			$data->userMoney = $result["currentmoney"];
			$data->userid=$userid;
			$res->data=$data;
		} else {
			$res->type = "userSurplusMoney";
			$data->userMoney = "0";
			$data->userid=$userid;
			$res->data=$data;
		}
		mysql_free_result($stmt);mysql_close($conn);
		return $res;

    }
	
	public function getusermoney($userid) {
		//self::updatemoney($userid);

		$conn = mysql_pconnect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);mysql_select_db(MYSQL_DB,$conn); 

		$tsql = "SELECT * from user WHERE username = '$userid'";
		
		$stmt = mysql_query($tsql,$conn); 
		
		if ($result=mysql_fetch_array($stmt)) { 
			mysql_free_result($stmt);mysql_close($conn);
			return $result["currentmoney"]; 
		} else { 
			mysql_free_result($stmt);mysql_close($conn);
			return "0";
		}
		
		return "0";

    }
	
	public function updatemoney($userid)
	{
		$conn = mysql_pconnect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);mysql_select_db(MYSQL_DB,$conn); 
		$tsql = "SELECT winmoney,money from injectresult i,user u WHERE u.username ='$userid' and i.uid=u.id"; 
		$stmt = mysql_query($tsql,$conn); 
		if (mysql_num_rows($stmt)) { 
			
			$i=0;
			while($r=mysql_fetch_array($stmt))
			{
				if($i==0)
					$money=floatval($r["money"]);
				$i++;
				$money=$money+floatval($r["winmoney"]);
			}
		}
		
		if($money!=null)
		{
			$tsql = "update user set currentmoney=".$money." where username='".$userid."'";
		 
			mysql_query($tsql,$conn);
		} 
		mysql_free_result($stmt);mysql_close($conn);
	}
	 
	public function broadcastNotice()
	{ 
		$res->type="broadcastNotice";
		$res->data="在同一局游戏里，同时下注庄和闲等实现无风险投注的对打行为被各娱乐公司列为违规行为，如若发现，一律禁封帐号，不结算洗码佣金处理！1，2号台面镜头显示方式略作调整，给您带来不便敬请谅解。 - "; 
		return $res;
	}
	
	public function getstatus($node,$s)
	{	 
		$conn = mysql_pconnect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);mysql_select_db(MYSQL_DB,$conn); 

		$tsql = "SELECT * from status where id=".$node;
		$stmt = mysql_query($tsql,$conn);  
		if ($result=mysql_fetch_array($stmt)) {  
			$r = $result["txt"];
			mysql_free_result($stmt);mysql_close($conn);
			if($s==$r)
				return "0";
			else
				return $r;
		}  
		mysql_free_result($stmt);mysql_close($conn);
		return "0";
	}
	
	public function runfms($obj,$id,$name)
	{ 
		if($obj->fun=="userInjectMoney")
		{
			$uid=self::getuid($name);
			$rid=self::getrid($id);
			$data=$obj->data;
			$data=$data->inject;
			if($rid!="0" && $uid!="0")
			{
				if($data->z!=0)
				{ 
					self::insertinject("1",$data->z,$rid,$uid); 
				}
				if($data->x!=0)
				{ 
					self::insertinject("2",$data->x,$rid,$uid); 
				}
				if($data->h!=0)
				{ 
					self::insertinject("3",$data->h,$rid,$uid); 
				}
				if($data->zd!=0)
				{ 
					self::insertinject("4",$data->zd,$rid,$uid); 
				}
				if($data->xd!=0)
				{ 
					self::insertinject("5",$data->xd,$rid,$uid); 
				} 
			}
		} 
		return null;
	}
	
	public function getrid($id)
	{ 
		$conn = mysql_pconnect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);mysql_select_db(MYSQL_DB,$conn); 

		$tsql="select * from `round` where tab_id=".intval($id)." order by rid desc";
		 
		$stmt = mysql_query($tsql,$conn); 
		if($result2=mysql_fetch_array($stmt))
		{	
			$rid = $result2["rid"];
			mysql_free_result($stmt);mysql_close($conn);
			return $rid;
		
		}
		mysql_free_result($stmt);mysql_close($conn);
		return "0";
	}
	
	public function getrid1($id)
	{ 
		$conn = mysql_pconnect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);mysql_select_db(MYSQL_DB,$conn); 

		$tsql="select * from `round` where tab_id=".intval($id)." order by rid desc";
		 
		$stmt = mysql_query($tsql,$conn); 
		if($result2=mysql_fetch_array($stmt))
		{	
			if($result2["gameState"]=="1" || $result2["gameState"]=="2")
			{
				$rid = $result2["rid"];
				mysql_free_result($stmt);mysql_close($conn);
				return $rid;
			}
		
		}
		mysql_free_result($stmt);mysql_close($conn);
		return "0";
	}
	
	public function getuid($name)
	{
		$conn = mysql_pconnect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);mysql_select_db(MYSQL_DB,$conn); 

		$tsql="select * from `user` where username='".$name."'";
		 
		$stmt = mysql_query($tsql,$conn); 
		if($result2=mysql_fetch_array($stmt))
		{	
			$rid = $result2["id"];
			mysql_free_result($stmt);mysql_close($conn);
			return $rid;
		
		}
		mysql_free_result($stmt);mysql_close($conn);
		return "0";
	}
	
	public function insertinject($type,$money,$rid,$uid)
	{
		$winmoney = (-1) * floatval($money);
		$itime= date('Y-m-d H:i:s',time());
		$conn = mysql_pconnect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);mysql_select_db(MYSQL_DB,$conn); 

		$tsql="insert into injectresult(uid,rid,injecttime,injecttype,ip,syh,injectmoney,winmoney) values('$uid','$rid','$itime','$type','127.0.0.1','-1','$money','$winmoney')";
		 
		mysql_query($tsql,$conn); mysql_close($conn);
	}
	
	public function upstate($rid,$s)
	{ 
		$conn = mysql_pconnect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);mysql_select_db(MYSQL_DB,$conn); 

		$tsql="update `round` set gameState=".$s." where rid=".$rid;
		mysql_query($tsql,$conn); mysql_close($conn);
	}
	public function gameState($id)
	{ 
		
		$conn = mysql_pconnect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);mysql_select_db(MYSQL_DB,$conn); 

		$tsql="select * from `round`,tablet where tablet.tab_id=`round`.tab_id and tablet.gameid=".$id." order by rid desc limit 1";
		$stmt = mysql_query($tsql,$conn); 
		if($result2=mysql_fetch_array($stmt))
		{
			$data->injectTime=intval($result2["injecttime"]);	//倒计时秒数		
			$data->gameBoot=intval($result2["gameBoot"]);	//场次		
			$data->roundNum=intval($result2["roundNum"]);		// 次	
			$data->nowTime= self::getmicro();		//当前时间   毫秒
			$data->startTime=floatval($result2["startTime"]);		//投注开始 时间	 毫秒	
			$data->gameState=intval($result2["gameState"]);
			if($data->gameState==1)
			{
				$data->time=intval((($data->nowTime)-($data->startTime))/1000);
				$data->time=intval(($data->injectTime)-($data->time));
				if(($data->time)<0)
				{
					self::upstate($result2["rid"],"2");
					$data->time=0;
					$data->gameState="2";
				}
			}		
			else
				$data->time=0;
			$data->gameid=$result2["gameid"];			
			$data->telMax=intval($result2["telMax"]);		
			if($data->telMax<200)
				$data->telMax=50000;
			$data->telMin=intval($result2["telMin"]);		
			if($data->telMin<200 || $data->telMin>10000)
				$data->telMin=200;			
			$data->gameNumber=$result2["gameNumber"];			
			$data->gameType=$result2["gameType"];			
			$data->gameTableName=$result2["gameTableName"];			
					// 1 开始投注  time 0-40  2 停止投注	3洗牌   0 出结果后
			$data->isCheckOut=false;		
			$data->farmerBool=false;	
		
			$res->data=$data;
			$res->type="gameState"; 
			//$res->state=$s;  
		} 
		mysql_free_result($stmt);mysql_close($conn);
		
		return $res; 
	}
	
	public function gameStateStart($id)
	{ 
		
		$conn = mysql_pconnect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);mysql_select_db(MYSQL_DB,$conn); 

		$tsql="select * from `round`,tablet where tablet.tab_id=`round`.tab_id and tablet.gameid=".$id." order by rid desc limit 1";
		$stmt = mysql_query($tsql,$conn); 

		if($result2=mysql_fetch_array($stmt))
		{
			$data->injectTime=intval($result2["injecttime"]);	//倒计时秒数		
			$data->gameBoot=intval($result2["gameBoot"]);	//场次		
			$data->roundNum=intval($result2["roundNum"]);		// 次	
			$data->nowTime= self::getmicro();		//当前时间   毫秒
			$data->startTime=floatval($result2["startTime"]);		//投注开始 时间	 毫秒	
			$data->gameState=intval($result2["gameState"]);
			if($data->gameState==1)
			{
				$data->time=intval((($data->nowTime)-($data->startTime))/1000);
				$data->time=intval(($data->injectTime)-($data->time));
				if(($data->time)<0)
				{
					self::upstate($result2["rid"],"2");
					$data->time=0;
					$data->gameState="2";
				}
			}		
			else
				$data->time=0;
			$data->gameid=$result2["gameid"];			
			$data->telMax=intval($result2["telMax"]);
			if($data->telMax<200)
				$data->telMax=50000;			
			$data->telMin=intval($result2["telMin"]);			
			if($data->telMin<200 || $data->telMin>10000)
				$data->telMin=200;
			$data->gameNumber=$result2["gameNumber"];			
			$data->gameType=$result2["gameType"];			
			$data->gameTableName=$result2["gameTableName"];			
					// 1 开始投注  time 0-40  2 停止投注	3洗牌   0 出结果后
			$data->isCheckOut=false;		
			$data->farmerBool=false;	
		
			$res->data=$data;
			$res->type="gameState"; 
			//$res->state=$s;  
		} 
		
		mysql_free_result($stmt);mysql_close($conn);
		return $res; 
	}
	
	public function getmicro()
	{
		$time = explode ( " ", microtime () );  
		$time3 = ($time [0] * 1000);  
		$time2 = explode ( ".", $time3 );  
		$time = $time[1].sprintf("%03d",$time2 [0]);  
		
		return floatval($time);
	}
	
	public function gethistoryBoot($id)
	{
		$conn = mysql_pconnect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);mysql_select_db(MYSQL_DB,$conn); 

		$tsql="select gameid,gameBoot from `round`,tablet where tablet.tab_id=`round`.tab_id and tablet.gameid=".$id." and DATEDIFF(NOW(),createtime)=0 order by rid desc";
		$stmt = mysql_query($tsql,$conn); 
		
		if($result2=mysql_fetch_array($stmt))
		{
			mysql_free_result($stmt);mysql_close($conn);
			return $result2["gameBoot"];
		}
		mysql_free_result($stmt);mysql_close($conn);
		return "100";
	}
	
	public function gameHistory($id)
	{   
		$boot = self::gethistoryBoot($id);
		$conn = mysql_pconnect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);mysql_select_db(MYSQL_DB,$conn); 

		$tsql="select gameid,gameType,gameTableName,result,gameState from `round`,tablet where `round`.gameState in (0,3) and tablet.tab_id=`round`.tab_id and tablet.gameid=".$id." and `round`.gameBoot=".$boot." and DATEDIFF(NOW(),createtime)=0 and `round`.result>=0 order by rid asc";
		$stmt = mysql_query($tsql,$conn); 
		$ccc=mysql_num_rows($stmt);
		if($ccc)
		{		
			$data->gameHistory=array();
			$i=0;
			while($re2=mysql_fetch_array($stmt))
			{  
				array_push($data->gameHistory,intval($re2["result"]));
				
				if($i==0)
				{
					$data->gameid=$re2["gameid"];		 	
					$data->gameType=$re2["gameType"];			
					$data->gameTableName=$re2["gameTableName"];
				}
				$i++;
				if($i==$ccc)
				{
					if($re2["gameState"]==3)
					{
						$data->gameHistory=null;
						$data->gameHistory=array();
					}
				}
				
			}
		 
			
			$res->type="gameHistory";  
			$res->data=$data;  
			 
		}
		else
		{
			$data->gameHistory=array();
			$data->gameid=$id;		 	
			$data->gameType="baijiale";			
			$data->gameTableName="baijiale".$id;
			
			$res->type="gameHistory";  
			$res->data=$data;  
			
		}
		mysql_free_result($stmt);mysql_close($conn);
		return $res;
 
	}
	
	public function gameInfo($id)
	{ 
		$conn = mysql_pconnect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);mysql_select_db(MYSQL_DB,$conn); 

		$tsql="select * from tablet where gameid='$id'";
		$stmt = mysql_query($tsql,$conn); 
		if($result2=mysql_fetch_array($stmt))
		{
			$data->telMin=intval($result2["telMin"]);
			if($data->telMin<200 || $data->telMin>10000)
				$data->telMin=200;
			$data->telMax=intval($result2["telMax"]);
			if($data->telMax<200)
				$data->telMax=50000;
			$data->injectTime=intval($result2["injecttime"]);
		}
		else
		{
			$data->telMin=1000;
			$data->telMax=100000;
			$data->injectTime=50;
		}
		
		$res->type="gameInfo"; 
		if($id=="01" || $id=="06" || $id=="08" || $id=="21")
			$data->customChipArr=array(100,1000,5000,10000,20000,50000); 
		else
			$data->customChipArr=array(100,500,1000,5000,10000,50000); 
		$data->gameid=$id;		 	
		$data->gameType="baijiale";			
		$data->gameTableName="baijiale".($data->gameid);	 
		
		$data->farmerBool=false;
		$data->telTieMax=10000;
		$data->telPairMax=10000;
		$data->videoUrll="rtmp://live.fbjoy.com:1989/goboes/";
		$data->videoName="b0".$id;
		
		$data->tableMax=1200000;
		$data->tableMin=0;
		
		$res->data=$data;
		mysql_free_result($stmt);mysql_close($conn);
		return $res;
	}
	
	public function refreshAllUserMoney($obj)
	{ 
		foreach($obj as $ob)
		{
			$name = $ob;
			$money=self::getusermoney($name);
			$data->userName=$ob;		 	
			$data->money=$money;		
			$res->data[]=$data;
			$data=null;
		} 
		$res->type="refreshAllUserMoney";  
	 
		return $res;
	}
	
	public function groupInjectMoney($tid,$allseat)
	{
		//return null;
		$len = count($allseat);
		$rid=self::getrid1($tid);
		if($len>0)
		{
			$conn = mysql_pconnect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);mysql_select_db(MYSQL_DB,$conn); 

			for($i=0;$i<$len;$i++)
			{ 
				$ss=explode(',',$allseat[$i]);
				//$ss=explode(',',"gulu,0");
				$tsql = "SELECT * from user where username='".$ss[0]."'";
				$stmt = mysql_query($tsql,$conn); 
				if($result=mysql_fetch_array($stmt))
				{
					$data->nickName=$result["nickName"]; 
					$data->userid=$result["username"];		 	
					$data->userMoney=$result["currentmoney"];			 
					$data->seat=intval($ss[1]);
					$uid=$result["id"];
				}
				
				$data->big=0;
				$data->small=0;
				$data->z=0;
				$data->x=0;
				$data->h=0;
				$data->zd=0;
				$data->xd=0;
				$data->z1=0;
				$data->x1=0;
				$data->x2=0;
				$data->z2=0;
				
				$tsql="select * from `injectresult` where uid=".$uid." and rid=$rid order by rid desc";
				$stmt1 = mysql_query($tsql,$conn); 
				if(mysql_num_rows($stmt1))
				{
					while($re=mysql_fetch_array($stmt1))
					{
						$t=$re["injecttype"];
						$m=$re["injectmoney"];
						if($t=="1") $data->z+=floatval($m);
						elseif($t=="2") $data->x+=floatval($m);
						elseif($t=="3") $data->h+=floatval($m);
						elseif($t=="4") $data->zd+=floatval($m);
						elseif($t=="5") $data->xd+=floatval($m);
					}
				}
			 
			 
				$res->data[]=$data;
				$data=null;
			}
			
			mysql_free_result($stmt1);mysql_free_result($stmt);mysql_close($conn);
		
		}
		
		$data->seatGroupNum=intval($len);
		$data->allLength=20*intval($len);		
		$res->type="groupInjectMoney";  
	 
		return $res;
	}
	
	public function injectMoneyOk($tabid,$name)
	{ 
		$uid=self::getuid($name);
		$rid=self::getrid($tabid);
		
		$data->big=0;
		$data->small=0;
		$data->z=0;
		$data->x=0;
		$data->h=0;
		$data->zd=0;
		$data->xd=0;
		$data->z1=0;
		$data->x1=0;
		$data->x2=0;
		$data->z2=0; 
		
	 
		$conn = mysql_pconnect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);mysql_select_db(MYSQL_DB,$conn); 

		$tsql="select * from `injectresult` where uid=".$uid." and rid=$rid order by rid desc";
		$stmt = mysql_query($tsql,$conn); 
		if(mysql_num_rows($stmt))
		{
			while($re=mysql_fetch_array($stmt))
			{
				$t=$re["injecttype"];
				$m=$re["injectmoney"];
				if($t=="1") $data->z+=floatval($m);
				elseif($t=="2") $data->x+=floatval($m);
				elseif($t=="3") $data->h+=floatval($m);
				elseif($t=="4") $data->zd+=floatval($m);
				elseif($t=="5") $data->xd+=floatval($m);
			}
		}
		
		
		$res->data=$data; 
		$res->type="injectMoneyOk";  
		mysql_free_result($stmt);mysql_close($conn);
		return $res;
	}
	
	public function gameAllInjects($tabid)
	{ 
		//$rid=self::getrid($tabid);
		$rid=self::getrid($tabid);
		$data->big=0;
		$data->small=0;
		$data->z=0;
		$data->x=0;
		$data->h=0;
		$data->zd=0;
		$data->xd=0;
		$data->z1=0;
		$data->x1=0;
		$data->x2=0;
		$data->z2=0;
		// $boot = self::gethistoryBoot($rid);
		$conn = mysql_pconnect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);mysql_select_db(MYSQL_DB,$conn); 

		$tsql="select * from `injectresult` where rid=$rid order by rid desc";
		$stmt = mysql_query($tsql,$conn); 
		if(mysql_num_rows($stmt))
		{
			while($re=mysql_fetch_array($stmt))
			{
				$t=$re["injecttype"];
				$m=$re["injectmoney"];
				if($t=="1") $data->z+=floatval($m);
				elseif($t=="2") $data->x+=floatval($m);
				elseif($t=="3") $data->h+=floatval($m);
				elseif($t=="4") $data->zd+=floatval($m);
				elseif($t=="5") $data->xd+=floatval($m);
			}
		}
		
	 
		$res->data=$data; 
		$res->type="gameAllInject";  
		mysql_free_result($stmt);mysql_close($conn);
		return $res;
	}
	
	public function gameAllInject($name,$tabid,$seat)
	{ 
		$uid=self::getuid($name);
		$rid=self::getrid($tabid);
		
		$data->big=0;
		$data->small=0;
		$data->z=0;
		$data->x=0;
		$data->h=0;
		$data->zd=0;
		$data->xd=0;
		$data->z1=0;
		$data->x1=0;
		$data->x2=0;
		$data->z2=0; 
		
		//$data->nickName=null;
		$data->userid=$name;
		$data->userMoney=self::getusermoney($name);
		$data->seat=$seat;		
		$data->ip="125.245.142.25"; 
		
		// $boot = self::gethistoryBoot($rid);
		$conn = mysql_pconnect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);mysql_select_db(MYSQL_DB,$conn); 

		$tsql="select * from `injectresult` where uid=".$uid." and rid=$rid order by rid desc";
		$stmt = mysql_query($tsql,$conn); 
		if(mysql_num_rows($stmt))
		{
			while($re=mysql_fetch_array($stmt))
			{
				$t=$re["injecttype"];
				$m=$re["injectmoney"];
				if($t=="1") $data->z+=floatval($m);
				elseif($t=="2") $data->x+=floatval($m);
				elseif($t=="3") $data->h+=floatval($m);
				elseif($t=="4") $data->zd+=floatval($m);
				elseif($t=="5") $data->xd+=floatval($m);
			}
		}
		
	 
		$res->data=$data; 
		$res->type="userInjectMoney";  
		mysql_free_result($stmt);mysql_close($conn);
		return $res;
	}
	
 
	
	public function userInjectMoney($tabid,$name,$money,$seat)
	{ 
		$data->big=0;
		$data->small=0;
		$data->z=0;
		$data->x=0;
		$data->h=0;
		$data->zd=0;
		$data->xd=0;
		$data->z1=0;
		$data->x1=0;
		$data->x2=0;
		$data->z2=0; 
		
		$rid=self::getrid($tabid);
		$uid=self::getuid($name);
		$conn = mysql_pconnect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);mysql_select_db(MYSQL_DB,$conn); 

		$tsql="select * from `injectresult` where rid=$rid and uid=$uid order by rid desc";
		$stmt = mysql_query($tsql,$conn); 
		if(mysql_num_rows($stmt))
		{
			while($re=mysql_fetch_array($stmt))
			{
				$t=$re["injecttype"];
				$m=$re["injectmoney"];
				if($t=="1") $data->z+=floatval($m);
				elseif($t=="2") $data->x+=floatval($m);
				elseif($t=="3") $data->h+=floatval($m);
				elseif($t=="4") $data->zd+=floatval($m);
				elseif($t=="5") $data->xd+=floatval($m);
			}
		}
		
		$data->nickName=null;
		$data->userid=$name;
		$data->userMoney=$money;
		$data->seat=$seat;		
		$data->ip="125.245.142.25"; 
	 
		$res->data=$data; 
		$res->type="userInjectMoney";  
		mysql_free_result($stmt);mysql_close($conn);
		return $res;
	}
	
	public function gameResults($tabid,$s)
	{ 
		// $boot = self::gethistoryBoot($tabid);
		$conn = mysql_pconnect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);mysql_select_db(MYSQL_DB,$conn); 

		$tsql="select * from `round` where tab_id=".intval($tabid)." order by rid desc";
		$stmt = mysql_query($tsql,$conn); 
		if($result2=mysql_fetch_array($stmt))
		{
			$chang = $result2["gameBoot"];
			$ci = $result2["roundNum"];
			$cc=$chang."-".$ci;
			
			$gameNumber = $result2["gameNumber"];
 
			$nowGameNum=(string)$gameNumber;
			$nowGameNum=$tabid.substr($nowGameNum,-10);
			
			$state = $result2["gameState"];
			$result = $result2["result"];
			if($state=="0" && $result!="-1" && $state!=$s)
			{
				$data->nowGameNum=$nowGameNum;
				$data->gameNumber=$gameNumber;
				$data->model="syn";//固定
				$data->zxh=self::getzxh($result);//0庄 1闲 2和
				$data->pu=0;//???
				
				
				$data->gameLogArr=array();//历史记录
				$data->userArr=null;//????
				$data->pauseReCheckout=true;//????
				$data->ju=$cc;
				$data->resutls=intval($result);//0-11 结果 
				
				if($data->resutls==1 || $data->resutls==5 || $data->resutls==9)
					$data->zbool=1;
				if($data->resutls==2 || $data->resutls==6 || $data->resutls==10)
					$data->xbool=1;
				if($data->resutls==3 || $data->resutls==7 || $data->resutls==11)
				{	$data->zbool=1;$data->xbool=1;}
				$res->data=$data; 
				$res->type="gameResults";  
				return $res;
			}
		}
		
		mysql_free_result($stmt);mysql_close($conn);
		$res->type="false";
		return $res;
	}
	
	function getzxh($r)
	{
		if($r=="0" || $r=="1" || $r=="2" || $r=="3")
			return 0;
		else if($r=="4" || $r=="5" || $r=="6" || $r=="7")
			return 1;
		else
			return 2;		
	}
	
	public function seatList($allseat)
	{
		$len = count($allseat); 
 
		// if($len>7)
		// {
			// $index = array_keys($allseat,$name);
			// $index = intval($index[0]); 		
			
			// if($index>6)
			// {
				// $seat=rand(0,6);
				// $mid = $allseat[$seat];
				// $allseat[$seat]= $allseat[$index];
				// $allseat[$index]= $mid;
			// }			
		// }
		
		
		
		if($len>0)
		{
			$conn = mysql_pconnect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);mysql_select_db(MYSQL_DB,$conn); 

			for($i=0;$i<$len;$i++)
			{ 
				$ss=explode(',',$allseat[$i]);
				
				$tsql = "SELECT * from user where username='".$ss[0]."'";
				$stmt = mysql_query($tsql,$conn); 
				if($result=mysql_fetch_array($stmt))
				{
					$arr->nickName=$result["nickName"]; 
					$arr->userid=$result["username"];		 	
					$arr->userMoney=$result["currentmoney"];			 
					$arr->seat=$ss[1]; 
				 
					$data->arr[]=$arr;
					$arr=null;
				}
				 
			}
			if($len>7)
			{
				$data->seatGroupNum=7;
				$data->allLength=140;
			}
			else
			{
				$data->seatGroupNum=intval($len);
				$data->allLength=intval($len)*20;
			}
			$res->type="seatList"; 
			$res->data=$data;
			mysql_free_result($stmt);mysql_close($conn);
			return $res;
		}
		return res;
	}
	
	
	
	public function seatList_remove()
	{ 		
		$data->nickName=null;
		$data->userid="ddad";
		$data->userMoney="845555";
		$data->seat=6;		
		
		$res->type="seatList_remove"; 
		$res->data=$data;	 
		return $res;
	}
	
	public function seatList_add()
	{ 		
		$data->nickName=null;
		$data->userid="ddad";
		$data->userMoney="845555";
		$data->seat=6;		
		$data->ip="125.245.142.25";
		
		$data->big=0;
		$data->small=0;
		$data->z=0;
		$data->x=0;
		$data->h=0;
		$data->zd=0;
		$data->xd=0;
		$data->z1=0;
		$data->x1=0;
		$data->x2=0;
		$data->z2=0;
		
		
		$res->type="seatList_add"; 
		$res->data=$data;	 
		return $res;
	}
	
	public function loginfms($userid,$userpsw) {
            $conn = mysql_pconnect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);mysql_select_db(MYSQL_DB,$conn); 
 

            $tsql = "SELECT * from user WHERE username ='$userid' AND password ='$userpsw'";

            $stmt = mysql_query($tsql,$conn); 
            if ($result=mysql_fetch_array($stmt)) {  
				$data->customRate = array();
				$data->userMoney = $result["currentmoney"];
				$data->parentTreeStr = "admin/xiaozhi";
				$data->userName = $userid;
				$data->id = $userid;				
				$data->userid = $userid;				
				$data->gameArr = array("baijiale"); 
				$data->seat=1;
				$data->big=0;
				$data->small=0;
				$data->x=0;
				$data->z=0;
				$data->h=0;
				$data->xd=0;
				$data->zd=0;
				$data->x1=0;
				$data->x2=0;
				$data->z1=0;
				$data->z2=0;				
				$data->wins=0;				
				$data->realType="real";
				$data->type="user";
				$data->ip="14.18.29.38";  
                $res->type = "loginOk";
				$res->data = $data;
				
            } else {
                $res->type = "loginFail";
				$res->data = "";
            }
			mysql_free_result($stmt);mysql_close($conn);
		return $res;

    }
	 
	 
    public function signIn($username, $password) {
            $pdo = MySQLUtil::getConnection();

            // hash the password
            $password = DBUtils::hashPassword($password);

            $tsql = "SELECT ur.name AS user_role, u.* FROM users AS u";
            $tsql .= " INNER JOIN user_roles AS ur ON (ur.id = u.user_role_id)";
            $tsql .= " WHERE u.username = :username AND u.password = :pass";

            $stmt = $pdo->prepare($tsql);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':pass', $password, PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_OBJ);
            if ($result) {
                AmfphpAuthentication::addRole($result->user_role);
                unset($result->password);

                return $result;
            } else {
                return false;
            }

    }

    /**
     * sign out function
     */
    public function signOut() {
        AmfphpAuthentication::clearSessionInfo();
    }

    /**
     * function the authentication plugin uses to get accepted roles for each function
     * Here login and logout are not protected, however
     * @param String $methodName
     * @return array
     */
    public function _getMethodRoles($methodName) {
        if (in_array($methodName, self::$protectedMethods)) {
            return array('admin');
        } else {
            return null;
        }
    }

}

?>