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
class Conn_Amf_User {

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
		mysql_free_result($stmt);
		mysql_close($conn);	
		return $res;

    }
	
	public function getLiveMoney($obj) {
            $conn = mysql_pconnect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);mysql_select_db(MYSQL_DB,$conn); 

            $tsql = "SELECT * from user WHERE username = :username";

            $stmt = mysql_query($tsql,$conn); 
			
            if ($result=mysql_fetch_array($stmt)) { 
                $res->type = true;
                $res->money = $result["currentmoney"];
            } else {
                $res->type = false;
                $res->money = 0;
            }
		mysql_free_result($stmt);
		mysql_close($conn);	
		return $res;

    } 
	
	public function gameStateList()
	{   
		$res->type="gameStateList";
		$res->data=array();
		
		$conn = mysql_pconnect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);mysql_select_db(MYSQL_DB,$conn); 
		$tsql = "SELECT tab_id,count(1) as c from round WHERE DATEDIFF(NOW(),createtime)<=1 group by tab_id";
		$stmt = mysql_query($tsql,$conn); 
		
		if (mysql_num_rows($stmt)) {  
			while($result=mysql_fetch_array($stmt))
			{
				if($result["c"] == "0")
				{
				}else{
					$tsql="select * from round,tablet where tablet.tab_id=round.tab_id and round.tab_id=".$result["tab_id"]." order by rid desc limit 1";
					$stmt1 = mysql_query($tsql,$conn); 
					if($result2=mysql_fetch_array($stmt1))
					{
						$data->injectTime=$result2["injecttime"];	//倒计时秒数		
						$data->gameBoot=$result2["gameBoot"];	//场次		
						$data->roundNum=$result2["roundNum"];		// 次	
						$data->nowTime= self::getmicro();		//当前时间   毫秒
						$data->startTime=floatval($result2["startTime"]);		//投注开始 时间	 毫秒	
						$data->time=intval((($data->nowTime)-($data->startTime))/1000);			// 
						$data->gameid=$result2["gameid"];			
						$data->telMax=intval($result2["telMax"]);			
						$data->telMin=intval($result2["telMin"]);			
						$data->gameNumber=$result2["gameNumber"];			
						$data->gameType=$result2["gameType"];			
						$data->gameTableName=$result2["gameTableName"];			
						$data->gameState=$result2["gameState"];		// 1 开始投注  time 0-40  2 停止投注	3洗牌   0 出结果后
						array_push($res->data,$data);
						$data=null;
					}
					mysql_free_result($stmt1);
				}
			}
		}   
		
		$app=array("01","02","05","06","07","08","10","11","12","15");
		$pdata=$res->data;
		for($i=0;$i<10;$i++)
		{
			$m=0;
			for($j=0;$j<count($pdata);$j++)
			{
				if($pdata[$j]->gameid==$app[$i])
				{	$m++;break;}
			}
			if($m==0)
			{
				$tsql="select * from tablet where gameid=".$app[$i]."";
				$stmt1 = mysql_query($tsql,$conn); 
				if($result2=mysql_fetch_array($stmt1))
				{
					$data->injectTime=$result2["injecttime"];	//倒计时秒数		
					$data->gameBoot=0;	//场次		
					$data->roundNum=0;		// 次	
					$data->nowTime= self::getmicro();		//当前时间   毫秒
					$data->startTime=0;		//投注开始 时间	 毫秒	
					$data->time=0;			// 
					$data->gameid=$result2["gameid"];			
					$data->telMax=intval($result2["telMax"]);			
					$data->telMin=intval($result2["telMin"]);			
					$data->gameNumber=0;			
					$data->gameType=$result2["gameType"];			
					$data->gameTableName=$result2["gameTableName"];			
					$data->gameState=0;		// 1 开始投注  time 0-40  2 停止投注	3洗牌   0 出结果后
					array_push($res->data,$data);
					$data=null;
				}
				mysql_free_result($stmt1);
			}
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
	
	public function gameHistoryList()
	{  
		$rdata = self::historyavi();
		$conn = mysql_pconnect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);mysql_select_db(MYSQL_DB,$conn); 
		
		$res->type="gameHistoryList";
		$res->data=array();
		
		if($rdata)
		{
			foreach($rdata as $rd)
			{
				$tsql="select gameid,gameType,gameTableName,result from round,tablet where tablet.tab_id=round.tab_id and round.tab_id=".$rd->tab_id." and round.gameBoot=".$rd->gameBoot." and DATEDIFF(NOW(),createtime)=0 and round.result>=0 order by rid asc"; 
				$stmt=mysql_query($tsql,$conn);  
				if(mysql_num_rows($stmt))
				{
					$data->gameHistory=array();				
					while($result2=mysql_fetch_array($stmt))
					{ 	 	 
						array_push($data->gameHistory,intval($result2["result"])); 
					}
					$data->gameid=sprintf("%02d",$rd->tab_id);		 	
					$data->gameType="baijiale";			
					$data->gameTableName="baijiale".$data->gameid;	 
					array_push($res->data,$data);
					$data=null;
				}
				else
				{
					$tsql="select gameid,gameType,gameTableName from tablet where tab_id=".$rd->tab_id;
					$stmt = mysql_query($tsql,$conn); 
					if($result2=mysql_fetch_array($stmt))
					{
						$data->gameHistory=array();		 
						$data->gameid=$result2["gameid"];		 	
						$data->gameType=$result2["gameType"];			
						$data->gameTableName=$result2["gameTableName"];	 
						array_push($res->data,$data);
						$data=null;
					}
				}
			}
		}
		mysql_free_result($stmt);mysql_close($conn);
 
		return $res;
	}
	
	public function historyavi()
	{    
		$rdata=array(); 
		$conn = mysql_pconnect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);mysql_select_db(MYSQL_DB,$conn); 

		$tsql = "SELECT tab_id,count(1) as c from round WHERE DATEDIFF(NOW(),createtime)=0 and gameState in (0,3) group by tab_id";
		$stmt = mysql_query($tsql,$conn); 
		
		if (mysql_num_rows($stmt)) {  
			while($re=mysql_fetch_array($stmt))
			{
				if($re["c"] == "0")
				{
					$data->gameBoot=2000;	//场次	  
					$data->tab_id=$re["tab_id"];			 
					array_push($rdata,$data);
					$data=null;
				}else{
					$tsql="select * from round,tablet where gameState in (0,3) and tablet.tab_id=round.tab_id and round.tab_id=".$re["tab_id"]." order by rid desc limit 1";
					$stmt1 =  mysql_query($tsql,$conn); 
					if($result2=mysql_fetch_array($stmt1))
					{ 	 
						if($result2["gameState"]==3)
						{
							$data->gameBoot="2222";
							$data->tab_id=$result2["tab_id"];			 
							array_push($rdata,$data);
							$data=null;
						}
						else{
							$data->gameBoot=$result2["gameBoot"];	//场次	  
							$data->tab_id=$result2["tab_id"];			 
							array_push($rdata,$data);
							$data=null;
						}
					}
					mysql_free_result($stmt1);
				}
			}
		}   
		$app=array("1","2","5","6","7","8","10","11","12","15");
		$pdata=$rdata;
		for($i=0;$i<10;$i++)
		{
			$m=0;
			for($j=0;$j<count($pdata);$j++)
			{
				if($pdata[$j]->tab_id==$app[$i])
				{	$m++;break;}
			}
			if($m==0)
			{
				$data->gameBoot="2222";
				$data->tab_id=$app[$i];			 
				array_push($rdata,$data);
				$data=null;
			}
		}
		mysql_free_result($stmt);mysql_close($conn);
		return $rdata;
	}
	
	public function broadcastNotice()
	{ 
		$res->type="broadcastNotice";
		$res->data="在同一局游戏里，同时下注庄和闲等实现无风险投注的对打行为被各娱乐公司列为违规行为，如若发现，一律禁封帐号，不结算洗码佣金处理！1，2号台面镜头显示方式略作调整，给您带来不便敬请谅解。 - "; 
		return $res;
	}
	
	public function gameState($state)
	{ 
		$s = self::getstatus("1",$state);
		if($s=="0")
		{
			$res->type="s"; 
		}
		else
		{
			$ss=explode(',',$s);
			$conn = mysql_pconnect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);mysql_select_db(MYSQL_DB,$conn); 

			$tsql="select * from round,tablet where tablet.tab_id=round.tab_id and round.tab_id=".$ss[0]." order by rid desc limit 1";
			$stmt = mysql_query($tsql,$conn); 
			if($result2=mysql_fetch_array($stmt))
			{ 
				$data->gameState=intval($result2["gameState"]);		// 1 开始投注  time 0-40  2 停止投注	3洗牌   0 出结果后
				$data->injectTime=intval($result2["injecttime"]);	//倒计时秒数		
				$data->gameBoot=intval($result2["gameBoot"]);	//场次		
				$data->roundNum=intval($result2["roundNum"]);		// 次	
				$data->nowTime= self::getmicro();		//当前时间   毫秒
				$data->startTime=floatval($result2["startTime"]);		//投注开始 时间	 毫秒	
				if($data->gameState==1)
				{
					$data->time=intval((($data->nowTime)-($data->startTime))/1000);			// 
					//$data->time=($data->injectTime)-($data->time);
					if($data->time>=$data->injectTime)
					{
						$data->time=0;
						$data->gameState=2;
					}
				}
				$data->gameid=$result2["gameid"];			
				$data->telMax=intval($result2["telMax"]);			
				$data->telMin=intval($result2["telMin"]);			
				$data->gameNumber=$result2["gameNumber"];			
				$data->gameType=$result2["gameType"];			
				$data->gameTableName=$result2["gameTableName"];			
				$res->data=$data;
				$res->type="gameState"; 
				$res->state=$s;  
			} 
			mysql_free_result($stmt);mysql_close($conn);
		}
		
		return $res;
	}
	
	public function getstatus($node,$s)
	{	 
		$conn = mysql_pconnect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);mysql_select_db(MYSQL_DB,$conn); 

		$tsql = "SELECT * from status where id=".$node;
		$stmt = mysql_query($tsql,$conn); 
		if ($result=mysql_fetch_array($stmt)) {  
			$r = $result["txt"];
			if($s==$r)
				return "0";
			else
				return $r;
		}  
		
		mysql_free_result($stmt);mysql_close($conn);
		return "0";
	}
	
	public function gameHistory($history)
	{    
		$s = self::getstatus("2",$history);  
		
		if($s=="0")
		{
			
			$res->type="s"; 
		}
		else
		{
			$ss=explode(',',$s);
			$boot = self::getboot($ss[1]);
			$conn = mysql_pconnect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);mysql_select_db(MYSQL_DB,$conn); 
			
			
			$tsql="select gameid,gameType,gameTableName,result from round,tablet where tablet.tab_id=round.tab_id and round.tab_id=".$ss[0]." and round.gameBoot=".$boot." and DATEDIFF(NOW(),createtime)=0 and round.gameState in (0,3) and round.result>=0 order by rid desc";
			 
			$stmt = mysql_query($tsql,$conn); 
			$ccc=mysql_num_rows($stmt);
			if($ccc)
			{
				$i=0;
				$data->gameHistory=array();
				while($re2=mysql_fetch_array($stmt))
				{ 	 	 
					array_push($data->gameHistory,intval($re2->result)); 
					if($i==0)
					{
						$data->gameid=$re2["gameid"];		 	
						$data->gameType=$re2["gameType"];			
						$data->gameTableName=$re2["gameTableName"];
					}
					$i++;
					if($i==$ccc)
					{
						if($re2["gameState"]=="3")
						{ 
							$data->gameHistory=array();
						}
					}
				}
				 
				$res->type="gameHistory"; 
				$res->history=$s;  
				$res->data=$data;  
			}
			else
				$res->type="s"; 
		}
		mysql_free_result($stmt);
		if($res->type=="s" || true)
		{
			$p=self::getstatus("1","111111");
			$pp=explode(',',$p);
			if($pp[1]=="3")
			{  
				$tsql="select gameid,gameType,gameTableName from tablet where tab_id=".$pp[0];
				$stmt = mysql_query($tsql,$conn); 
				if($result2=mysql_fetch_array($stmt))
				{
				
					$data->gameHistory=array();  
					$data->gameid=$result2["gameid"];		 	
					$data->gameType=$result2["gameType"];			
					$data->gameTableName=$result2["gameTableName"];
					$res->type="gameHistory"; 
					$res->history=$s;  
					$res->data=$data;  
				}
				else
					$res->type="s"; 
				
				mysql_free_result($stmt);
			}			
		}
		mysql_close($conn);
		return $res;
	}
	
	public function getboot($rid)
	{
		$conn = mysql_pconnect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);mysql_select_db(MYSQL_DB,$conn); 

		$tsql="select * from `round` where rid=".$rid;
		$stmt = mysql_query($tsql,$conn); 
		if($result2=mysql_fetch_array($stmt))
		{
			mysql_free_result($stmt);mysql_close($conn);
			return $result2["gameBoot"];
		}
		mysql_free_result($stmt);mysql_close($conn);
		return '-1';
	}
	 
	
	public function loginfms($userid,$userpsw) {
            $conn = mysql_pconnect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);mysql_select_db(MYSQL_DB,$conn); 
            $tsql = "SELECT * from user WHERE username ='$userid' AND password = '$userpsw'";
            $stmt = mysql_query($tsql,$conn);  
            if ($result=mysql_fetch_array($stmt)) { 
				$data->type = true;
				$data->wins = "0"; //共赢了几场
				$data->customRate = array();
				$data->userName = $userid;
				$data->hideWash = "1";
				$data->passWord = $userpsw;
				$data->userid = $userid;
				$data->userMoney = $result["currentmoney"];
				$data->chipModel = "custom";
				$data->parentTreeStr = "admin/xiaozhi";
				$data->realType = "real";
				$data->nickName = $result["nickName"];
				$data->hourWins = "0";
				$data->gameArr = array("baijiale");
				$data->maxMin = array(); 
				$data->maxMin[0]=array('type'=>'baijiale','c10_500'=>'10,30,50,300,500','maxMin'=>'10_500,30_1000,50_3000','c30_1000'=>'20,50,100,200,500','c50_3000'=>'10,50,300,500,3000');//最大小码 有很多种
                $res->type = "loginOk";
				$res->data = $data;
				
            } else {
                $res->type = "loginFail";
				$res->data = "";
            }
			mysql_free_result($stmt);
			mysql_close($conn);
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