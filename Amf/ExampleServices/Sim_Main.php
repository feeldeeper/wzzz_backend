<?php

require_once dirname(__FILE__) . '/../Includes/constants.php';
require_once dirname(__FILE__) . '/../Includes/MySQLUtil.php';
date_default_timezone_set('Asia/Shanghai');

class Sim_Main
{

    public static $protectedMethods = array();

    function __construct()
    {
        if (!defined('PDO::ATTR_DRIVER_NAME')) {
            throw new Exception('PDO unavailable');
        }
    }

    public function login($obj)
    {
        $pdo = MySQLUtil::getConnection();
        $res = new stdClass();
        $data = new stdClass();

        $tsql = "SELECT * from user WHERE username = :username AND password = :pass AND type=3";

        $stmt = $pdo->prepare($tsql);
        $stmt->bindParam(':username', $obj->userName, PDO::PARAM_STR);
        $stmt->bindParam(':pass', $obj->passWord, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_OBJ);
        $stmt = null;
        $pdo = null;
        if ($result) {
            $res->type = true;
        } else {
            $res->type = false;
        }

        return $res;

    }

    public function changeNickName($obj)
    {
        $res = new stdClass();
        $fp = fopen("log.txt", "a");
        fwrite($fp, json_encode($obj) . "\r\n");
        fclose($fp);
        if ($obj->userName != "") {
            $nickName = str_replace("'", "''", $obj->nickName);
            $userName = str_replace("'", "''", $obj->userName);
            $pdo = MySQLUtil::getConnection();
            $tsql = "update user set nickName='$nickName' where username = '$userName'";

            $stmt = $pdo->prepare($tsql);
            $stmt->execute();
            $stmt = null;
            $pdo = null;

            $res->type = true;
            return $res;
        }
        $res->type = false;
        return $res;
    }

    public function abc()
    {

        $pdo = MySQLUtil::getConnection();
        $tsql = "select * from user where username = 'gulufdfd'";

        $stmt = $pdo->prepare($tsql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        if ($result) echo "aaaaaaaaaa";
        $stmt = null;
        $pdo = null;

    }

    public function changePwd($obj)
    {
        $res = new stdClass();
        if ($obj->userName != "") {
            $userName = str_replace("'", "''", $obj->userName);
            $opwd = md5($obj->pwd);
            $pwd = md5($obj->newPwd);
            $pdo = MySQLUtil::getConnection();
            $tsql = "select * from user where username = '$userName' and password='$opwd'";


            $stmt = $pdo->prepare($tsql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            if ($result) {
                $tsql = "update user set password='$pwd' where username = '$userName' and password='$opwd'";
                $stmt = $pdo->prepare($tsql);
                $stmt->execute();
                $stmt = null;
                $pdo = null;

                $res->type = true;
                return $res;
            }
            $stmt = null;
            $pdo = null;

            $res->type = false;
            return $res;
        }
    }


    public function getLiveMoney($obj)
    {
        $res = new stdClass();
        $pdo = MySQLUtil::getConnection();

        $tsql = "SELECT * from user WHERE username = :username";

        $stmt = $pdo->prepare($tsql);
        $stmt->bindParam(':username', $obj->iv, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_OBJ);
        $stmt = null;
        $pdo = null;
        if ($result) {
            $res->type = true;
            $res->money = $result->currentmoney;
        } else {
            $res->type = false;
            $res->money = 0;
        }

        return $res;

    }

    public function getReport($obj)
    {
        $res = new stdClass();
        $username = $obj->userName;
        $time = $obj->time;
        $pwd = $obj->pwd;
        $stime = $time->sTime;
        $etime = $time->eTime;

        $pdo = MySQLUtil::getConnection();

        $tsql = "SELECT * from user WHERE username = '$username'";

        $stmt = $pdo->prepare($tsql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);

        if ($result) {
            $uid = $result->id;
            $sql = "select * from `injectresult` where uid='$uid' and injecttime>='$stime' and injecttime<='$etime'";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $result2 = $stmt->fetchAll(PDO::FETCH_OBJ);
            $res->type = true;
            $res->totalMoney = 0;
            $res->profitMoney = 0;
            $res->washProfit = 0;
            if ($result2) {
                foreach ($result2 as $re) {
                    $res->totalMoney += floatval($re->injectmoney);
                    $res->profitMoney += floatval($re->winmoney);
                    $res->washProfit += floatval($re->ximayongjin);
                }
            }
        } else {
            $res->type = false;
        }
        $stmt = null;
        $pdo = null;
        return $res;

    }

    public function gameStateList()
    {
        $res = new stdClass();
        $data = new stdClass();
        $res->type = "gameStateList";
        $res->data = array();
        $today = date("Y-m-d", time()) . " 00:00:00";
        $pdo = MySQLUtil::getConnection();
        $tsql = "SELECT tab_id,count(1) as c from round WHERE createtime>'$today' group by tab_id";
        $stmt = $pdo->prepare($tsql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);

        if ($result) {
            foreach ($result as $re) {
                if ($re->c == "0") {
                } else {
                    $tsql = "select * from round,tablet where tablet.tab_id=round.tab_id and round.tab_id=" . $re->tab_id . " order by rid desc limit 1";
                    $stmt = $pdo->prepare($tsql);
                    $stmt->execute();
                    $result2 = $stmt->fetch(PDO::FETCH_OBJ);
                    if ($result2) {
                        $data->injectTime = $result2->injecttime;    //倒计时秒数
                        $data->gameBoot = $result2->gameBoot;    //场次
                        $data->roundNum = $result2->roundNum;        // 次
                        $data->nowTime = self::getmicro();        //当前时间   毫秒
                        $data->startTime = floatval($result2->startTime);        //投注开始 时间	 毫秒
                        $data->time = intval((($data->nowTime) - ($data->startTime)) / 1000);            //
                        $data->gameid = $result2->gameid;
                        $data->telMax = intval($result2->telMax);
                        $data->telMin = intval($result2->telMin);
                        $data->gameNumber = $result2->gameNumber;
                        $data->gameType = $result2->gameType;
                        $data->gameTableName = $result2->gameTableName;
                        $data->gameState = $result2->gameState;        // 1 开始投注  time 0-40  2 停止投注	3洗牌   0 出结果后
                        array_push($res->data, $data);
                        $data = null;
                    }
                }
            }
        }

        $app = array("08", "09", "10", "11", "12", "14", "15", "16", "17", "18", "19", "20");
        $pdata = $res->data;
        for ($i = 0; $i < count($app); $i++) {
            $m = 0;
            for ($j = 0; $j < count($pdata); $j++) {
                if ($pdata[$j]->gameid == $app[$i]) {
                    $m++;
                    break;
                }
            }
            if ($m == 0) {
                $tsql = "select SQL_CACHE * from tablet where gameid=" . $app[$i] . "";
                $stmt = $pdo->prepare($tsql);
                $stmt->execute();
                $result2 = $stmt->fetch(PDO::FETCH_OBJ);
                if ($result2) {
                    $data->injectTime = $result2->injecttime;    //倒计时秒数
                    $data->gameBoot = 0;    //场次
                    $data->roundNum = 0;        // 次
                    $data->nowTime = self::getmicro();        //当前时间   毫秒
                    $data->startTime = 0;        //投注开始 时间	 毫秒
                    $data->time = 0;            //
                    $data->gameid = $result2->gameid;
                    $data->telMax = intval($result2->telMax);
                    $data->telMin = intval($result2->telMin);
                    $data->gameNumber = 0;
                    $data->gameType = $result2->gameType;
                    $data->gameTableName = $result2->gameTableName;
                    $data->gameState = 0;        // 1 开始投注  time 0-40  2 停止投注	3洗牌   0 出结果后
                    array_push($res->data, $data);
                    $data = null;
                }
            }
        }
        $stmt = null;
        $pdo = null;
        return $res;
    }

    public function getmicro()
    {
        $time = explode(" ", microtime());
        $time3 = ($time [0] * 1000);
        $time2 = explode(".", $time3);
        $time = $time[1] . sprintf("%03d", $time2 [0]);

        return floatval($time);
    }

    public function gameHistoryList()
    {
        $res = new stdClass();
        $data = new stdClass();
        $pdo = MySQLUtil::getConnection();
        $today = date("Y-m-d", time()) . " 00:00:00";
        $res->type = "gameHistoryList";
        $res->data = array();
        $rdata = self::historyavi();
        if ($rdata) {
            foreach ($rdata as $rd) {
                $tsql = "select gameid,gameType,gameTableName,result from round,tablet where tablet.tab_id=round.tab_id and round.tab_id=" . $rd->tab_id . " and round.gameBoot=" . $rd->gameBoot . " and createtime>'$today' and round.result>=0 order by rid asc";
                $stmt = $pdo->prepare($tsql);
                $stmt->execute();
                $result2 = $stmt->fetchAll(PDO::FETCH_OBJ);
                if ($result2) {
                    $data->gameHistory = array();
                    foreach ($result2 as $re2) {
                        array_push($data->gameHistory, intval($re2->result));
                    }
                    $data->gameid = $result2[0]->gameid;
                    $data->gameType = $result2[0]->gameType;
                    $data->gameTableName = $result2[0]->gameTableName;
                    array_push($res->data, $data);
                    $data = null;
                } else {
                    $tsql = "select gameid,gameType,gameTableName from tablet where tab_id=" . $rd->tab_id;
                    $stmt = $pdo->prepare($tsql);
                    $stmt->execute();
                    $result2 = $stmt->fetch(PDO::FETCH_OBJ);
                    if ($result2) {
                        $data->gameHistory = array();
                        $data->gameid = $result2->gameid;
                        $data->gameType = $result2->gameType;
                        $data->gameTableName = $result2->gameTableName;
                        array_push($res->data, $data);
                        $data = null;
                    }
                }
            }
        }

        $stmt = null;
        $pdo = null;
        return $res;
    }

    public function historyavi()
    {
        $data = new stdClass();
        $rdata = array();
        $pdo = MySQLUtil::getConnection();
        $today = date("Y-m-d", time()) . " 00:00:00";
        $boot = '';
        $psql = "select count(1) as c from `round` where gameState=3 and gameBoot={$boot} and createtime>'$today'";
        $stmt = $pdo->prepare($psql);
        $stmt->execute();
        $result1 = $stmt->fetchAll(PDO::FETCH_OBJ);
        $xipai = 0;
        if ($result1) {
            $xipai = $result1[0]->c;
        }
        if ($xipai == "0") {

            $tsql = "SELECT tab_id,count(1) as c from round WHERE createtime>'{$today}' and gameState in (0,3) group by tab_id";
            $stmt = $pdo->prepare($tsql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);

            if ($result) {
                foreach ($result as $re) {
                    if ($re->c == "0") {
                        $data->gameBoot = 2000;    //场次
                        $data->tab_id = $re->tab_id;
                        array_push($rdata, $data);
                        $data = null;
                    } else {
                        $tsql = "select * from round,tablet where gameState in (0,3) and tablet.tab_id=round.tab_id and round.tab_id=" . $re->tab_id . " order by rid desc limit 1";
                        $stmt = $pdo->prepare($tsql);
                        $stmt->execute();
                        $result2 = $stmt->fetch(PDO::FETCH_OBJ);
                        if ($result2) {
                            if ($result2->gameState == 3) {
                                $data->gameBoot = "2222";
                                $data->tab_id = $result2->tab_id;
                                array_push($rdata, $data);
                                $data = null;
                            } else {
                                $data->gameBoot = $result2->gameBoot;    //场次
                                $data->tab_id = $result2->tab_id;
                                array_push($rdata, $data);
                                $data = null;
                            }
                        }
                    }
                }
            }
        }
        $app = array("8", "9", "10", "11", "12", "14", "15", "16", "17", "18", "19", "20");
        $pdata = $rdata;
        for ($i = 0; $i < count($app); $i++) {
            $m = 0;
            for ($j = 0; $j < count($pdata); $j++) {
                if ($pdata[$j]->tab_id == $app[$i]) {
                    $m++;
                    break;
                }
            }
            if ($m == 0) {
                $data->gameBoot = "2222";
                $data->tab_id = $app[$i];
                array_push($rdata, $data);
                $data = null;
            }
        }

        $stmt = null;
        $pdo = null;
        return $rdata;

    }

    public function broadcastNotice()
    {
        $res = new stdClass();
        $pdo = MySQLUtil::getConnection();
        $psql = "select SQL_CACHE * from notice where id=2";
        $stmt = $pdo->prepare($psql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);

        $res->type = "broadcastNotice";
        $res->data = $result->content;
        return $res;
    }

    public function gameState($state)
    {
        $res = new stdClass();
        $data = new stdClass();
        $s = self::getstatus("1", $state);
        if ($s == "0") {
            $res->type = "s";
        } else {
            $ss = explode(',', $s);
            $pdo = MySQLUtil::getConnection();
            $tsql = "select * from round,tablet where tablet.tab_id=round.tab_id and round.tab_id=" . $ss[0] . " order by rid desc limit 1";
            $stmt = $pdo->prepare($tsql);
            $stmt->execute();
            $result2 = $stmt->fetch(PDO::FETCH_OBJ);
            $stmt = null;
            $pdo = null;
            if ($result2) {
                $data->gameState = intval($result2->gameState);        // 1 开始投注  time 0-40  2 停止投注	3洗牌   0 出结果后
                $data->injectTime = intval($result2->injecttime);    //倒计时秒数
                $data->gameBoot = intval($result2->gameBoot);    //场次
                $data->roundNum = intval($result2->roundNum);        // 次
                $data->nowTime = self::getmicro();        //当前时间   毫秒
                $data->startTime = floatval($result2->startTime);        //投注开始 时间	 毫秒
                if ($data->gameState == 1) {
                    $data->time = intval((($data->nowTime) - ($data->startTime)) / 1000);            //
                    //$data->time=($data->injectTime)-($data->time);
                    if ($data->time >= $data->injectTime) {
                        $data->time = 0;
                        $data->gameState = 2;
                    }
                }
                $data->gameid = $result2->gameid;
                $data->telMax = intval($result2->telMax);
                $data->telMin = intval($result2->telMin);
                $data->gameNumber = $result2->gameNumber;
                $data->gameType = $result2->gameType;
                $data->gameTableName = $result2->gameTableName;
                $res->data = $data;
                $res->type = "gameState";
                $res->state = $s;
            }
        }

        return $res;
    }

    public function getstatus($node, $s)
    {
        $pdo = MySQLUtil::getConnection();
        $tsql = "SELECT SQL_CACHE * from status where id=" . $node;
        $stmt = $pdo->prepare($tsql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        $stmt = null;
        $pdo = null;
        if ($result) {
            $r = $result->txt;
            if ($s == $r)
                return "0";
            else
                return $r;
        }
        return "0";
    }

    public function gameHistory($history)
    {
        $res = new stdClass();
        $data = new stdClass();
        $s = self::getstatus("2", $history);
        if ($s == "0") {

            $res->type = "s";
        } else {
            $ss = explode(',', $s);
            $today = date("Y-m-d", time()) . " 00:00:00";
            $pdo = MySQLUtil::getConnection();
            $tsql = "select gameid,gameType,gameTableName,result from round,tablet where tablet.tab_id=round.tab_id and round.tab_id=" . $ss[0] . " and round.gameBoot=" . self::getboot($ss[1]) . " and createtime>'$today' and round.gameState in (0,3) and round.result>=0 order by rid asc";
            $stmt = $pdo->prepare($tsql);
            $stmt->execute();
            $result2 = $stmt->fetchAll(PDO::FETCH_OBJ);
            if ($result2) {

                $data->gameHistory = array();
                $ccc = count($result2) - 1;
                if ($result2[$ccc]->gameState != "3") {
                    foreach ($result2 as $re2) {
                        array_push($data->gameHistory, intval($re2->result));
                    }
                }
                $data->gameid = $result2[0]->gameid;
                $data->gameType = $result2[0]->gameType;
                $data->gameTableName = $result2[0]->gameTableName;
                $res->type = "gameHistory";
                $res->history = $s;
                $res->data = $data;
            } else
                $res->type = "s";
        }

        if ($res->type == "s" || true) {
            $p = self::getstatus("1", "111111");
            $pp = explode(',', $p);
            if ($pp[1] == "3") {

                $pdo = MySQLUtil::getConnection();
                $tsql = "select SQL_CACHE gameid,gameType,gameTableName from tablet where tab_id=" . $pp[0];
                $stmt = $pdo->prepare($tsql);
                $stmt->execute();
                $result2 = $stmt->fetch(PDO::FETCH_OBJ);
                if ($result2) {

                    $data->gameHistory = array();
                    $data->gameid = $result2->gameid;
                    $data->gameType = $result2->gameType;
                    $data->gameTableName = $result2->gameTableName;
                    $res->type = "gameHistory";
                    $res->history = $s;
                    $res->data = $data;
                } else
                    $res->type = "s";
            }
        }
        $stmt = null;
        $pdo = null;
        return $res;
    }

    public function getboot($rid)
    {
        $pdo = MySQLUtil::getConnection();
        $tsql = "select SQL_CACHE * from `round` where rid=" . $rid;
        $stmt = $pdo->prepare($tsql);
        $stmt->execute();
        $result2 = $stmt->fetch(PDO::FETCH_OBJ);
        $stmt = null;
        $pdo = null;
        if ($result2) {
            return $result2->gameBoot;
        }

        return '-1';
    }


    public function loginfms($userid, $userpsw)
    {
        $res = new stdClass();
        $data = new stdClass();
        $pdo = MySQLUtil::getConnection();
        $tsql = "SELECT * from user WHERE username = :username AND password = :pass AND type=3";

        $stmt = $pdo->prepare($tsql);
        $stmt->bindParam(':username', $userid, PDO::PARAM_STR);
        $stmt->bindParam(':pass', $userpsw, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_OBJ);
        $stmt = null;
        $pdo = null;
        if ($result) {
            $data->type = true;
            $data->wins = "0"; //共赢了几场
            $data->customRate = array();
            $data->userName = $userid;
            $data->hideWash = "1";
            $data->passWord = $userpsw;
            $data->userid = $userid;
            $data->userMoney = $result->currentmoney;
            $data->chipModel = "custom";
            $data->parentTreeStr = "admin/xiaozhi";
            $data->realType = "real";
            $data->nickName = $result->nickName;
            $data->hourWins = "0";
            $data->gameArr = array("baijiale");
            $data->maxMin = array();
            $data->maxMin[0] = array('type' => 'baijiale', 'c10_500' => '10,30,50,300,500', 'maxMin' => '10_500,30_1000,50_3000', 'c30_1000' => '20,50,100,200,500', 'c50_3000' => '10,50,300,500,3000');//最大小码 有很多种
            $res->type = "loginOk";
            $res->data = $data;

        } else {
            $res->type = "loginFail";
            $res->data = "";
        }

        return $res;

    }


    public function signIn($username, $password)
    {
        $pdo = MySQLUtil::getConnection();

        $tsql = "SELECT ur.name AS user_role, u.* FROM users AS u";
        $tsql .= " INNER JOIN user_roles AS ur ON (ur.id = u.user_role_id)";
        $tsql .= " WHERE u.username = :username AND u.password = :pass";

        $stmt = $pdo->prepare($tsql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':pass', $password, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_OBJ);
        $stmt = null;
        $pdo = null;
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
    public function signOut()
    {
        AmfphpAuthentication::clearSessionInfo();
    }

    /**
     * function the authentication plugin uses to get accepted roles for each function
     * Here login and logout are not protected, however
     * @param String $methodName
     * @return array
     */
    public function _getMethodRoles($methodName)
    {
        if (in_array($methodName, self::$protectedMethods)) {
            return array('admin');
        } else {
            return null;
        }
    }

}

?>