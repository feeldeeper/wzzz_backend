<?php
class MySql1{
	
	private $link;
	public $meta;
	public $folder;
	function __construct($link){
		$this->link = $link;
	}
	
	
	function Query($sql,$line=''){
		$k=0;
		while(!isset($result))
		{
			$k++;
			if($k>50)
				exit();
			$result = @mysql_query($sql,$this->link);
			if(!$result){
				// $errors = debug_backtrace();
				// $errors = $errors[count($errors)-1];
				// print_r($errors);
				// echo mysql_error();exit;
			}else{
				return $result;
			}
		}
	}
	
	function Select($sql,$line=''){
		$result = $this->Query($sql,$line);
		$i=0;
		$temp = array();
		while($rs = mysql_fetch_array($result,1)){
			foreach($rs as $key=>$value){
				$temp[$i][$key]=htmlspecialchars_decode($value);
			}
			$i++;
		}
		return $temp;
	}
	
	function DelSiteMap($type){
		$this->Query("DELETE FROM ww_sitemap WHERE type='$type'");
	}
	
	function RelativeID($uid,$line=''){
		$result1=$this->Select("select id from user where pid='$uid' and type='2'",$line);
		$temp1 = array();
		foreach($result1 as $key1){
			$temp1[] = $key1['id'];
		}
		$temp1[] = $uid;
		$tt = implode(',',$temp1);
		
		
		
		$result=$this->Select("select id from user where pid in ($tt) and type=3",$line);
		$temp = array();
		foreach($result as $key){
			$temp[] = $key['id'];
		}
		return $temp;
	}
	
	function RelativeID2($id,$u,$m,$line=''){
		if($m == "0")
			$sql = "select id,pid from user where username like '%$u%'";
		else
			$sql = "select id,pid from user where username='$u'";
		$result=$this->Select($sql,$line);
		$temp = array();
		foreach($result as $key){
			if($this->VerifyUser($id,$key['pid']))
				$temp[] = $key['id'];
		}
		return $temp;
	}
	
	function VerifyUser($uid,$pid)
	{
		if($uid == $pid)
			return true;
		$sql = "select id from user where id='$pid' and pid='$uid'";
		$result=$this->Select($sql,$line);
		if(count($result)!=0)
			return true;
		return false;
	}
	
	function VerifyUserReport($type,$uid,$id)
	{
		if($uid==$id)
			return true;
		$sql = "select id from user where id='$id'and pid='$uid'";
		$result=$this->Select($sql,$line);
		if(count($result)!=0)
			return true;
		if($type=="1")
		{
			$sql = "select pid from user where id='$id' and type='3'";
			$result=$this->Select($sql,$line);
			if(count($result)>0)
			{
				$pid = $result[0]['pid'];
				$sql = "select id from user where id='$pid'and pid='$uid'";
				$result=$this->Select($sql,$line);
				if(count($result)!=0)
					return true;
			}
		}
		return false;
	}
	
	function GetUserName($uid,$line=''){
		$result=$this->Select("select * from user where id=$uid",$line);
		
		if(count($result)>0)
		{
			$temp = $result[0];
		
			return $temp['username'];
		}
		else{
			return "";
		}
	}
	
	function GetInjectType($tid,$line='')
	{
		$result=$this->Select("select * from `type` where tid=$tid",$line);
		
		if(count($result)>0)
		{
			$temp = $result[0];
			$arr=array(str_replace(".00","",$temp['rate']),$temp['type']);
		}
		else{
			$arr=array(0,"错误");
		}
		return $arr;
	}
	
	function GetResult($id,$line='')
	{
		$result=$this->Select("select * from `result` where rid=$id",$line);
		
		if(count($result)>0)
		{
			$temp = $result[0];
			$arr=$temp['result'];
		}
		else{
			$arr="";
		}
		return $arr;
	}
	
	function GetIPLoc($queryIP){ 
		$url = 'http://wap.ip138.com/ip.asp?ip='.$queryIP; 
		$ch = curl_init($url); 
		curl_setopt($ch,CURLOPT_ENCODING ,'utf-8'); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 10); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回 
		$result = curl_exec($ch); 
		curl_close($ch); 
		preg_match("@<br/><b>(.*)</b>@iU",$result,$ipArray); 
		$loc = $ipArray[1]; 
		$loc = mb_ereg_replace("查询结果：","",$loc);
		return $loc; 
	}
	
	function GetIpLocData($ip)
	{
		$result = $this->Select("select * from ip where ip='$ip'");
		if(count($result)==0)
		{
			$loc = $this->GetIPLoc($ip);
			$this->Query("insert into ip(ip,loc) values('$ip','$loc')");
			return $loc;
		}
		else
		{
			$temp = $result[0];
			return $temp['loc'];
		}
	}
	
	function TotalProfit($sql)
	{
		$result = $this->Select($sql);
		$pmoney = 0;
		foreach($result as $key)
		{
			$pmoney += intval($key['winmoney']);
		}
		return $pmoney;
	}

	
	function GameRecord($uids,$page,$stime,$etime,$key,$mod,$mod2){
		$node = "";
		if($key!="" && $mod2 == "1")
		{
			if($mod == "0")
				$node = "and gameNumber like '%$key%'";
			else
				$node = "and gameNumber='$key'";				
		}
		$limit = " limit 0,100";
		if($page != "1")
		{
			$limit = " limit ".((intval($page)-1)*100).",".(intval($page)*100);
		}
		$sql="select * from `injectresult` as i,`round` as r where uid in ($uids) and syh<>-1 and r.rid=i.rid and injecttime<='$etime' and injecttime>='$stime' $node order by injecttime desc";
		$result = $this->Select($sql.$limit);
		$temp = array();
		$c=0;
		$pmoney = $this->TotalProfit($sql);
		foreach($result as $key)
		{
			$c++;
			$uid = $key['uid'];
			$tid = $key['injecttype'];
			$reid = $key['result'];
			$username= $this->GetUserName($uid);
			$type= $this->GetInjectType($tid);
			$res= $this->GetResult($reid);
			$syh=$key['syh'];
			$winmoney=floatval($key['winmoney']);
			$r="";
			if($syh=='0')
			{
				$loset++;
				$losem-=$winmoney;
				$r="<span style='color:green'>输</span>";
				$winmoney = "<span style='color:green'>$winmoney</span>";
				$xima = $key['injectmoney'];
			}
			elseif($syh=='1')
			{
				$wint++;
				$winm+=$winmoney;
				
				$r="<span style='color:red;font-weight:bold;'>赢</span>";
				$winmoney = "<span style='color:red;font-weight:bold;'>$winmoney</span>";
				$xima = "0";
			}
			else
			{
				$he++;
				$r="<span style='color:blue'>和局</span>";
				$winmoney = "<span style='color:green'>$winmoney</span>";
				$xima = "0";
			}
			$temp[] = array(
					'id'=>$c,
					'uid'=>$uid,
					'username'=>$username,
					'gamenumber'=>$key['gameNumber'],
					'round'=>$key['gameBoot']."-".$key['roundNum'],
					'bidtime'=>$key['injecttime'],
					'endtime'=>date("Y-m-d H:i:s",strtotime($key['createtime'],"+30 second")),
					'bidtype'=>$type[1],
					'result'=>$res,
					'winorlose'=>$r,
					'bidrate'=>$type[0],
					'injectmoney'=>$key['injectmoney'],
					'xima'=>$key['ximaliang'],
					'choushui'=>"0",
					'profit'=>$winmoney,
					'restmoney'=>$key['restmoney'],
					'ip'=>$key['ip'],
					'iploc'=>$this->GetIpLocData($key['ip']),
					'memo'=>"",
					'totalprofit'=>$pmoney
				);
		}
		
		return $temp;
	}
}
	
?>