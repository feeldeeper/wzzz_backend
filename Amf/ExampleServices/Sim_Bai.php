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
require_once dirname(__FILE__) . '/../Includes/MySQLUtil.php';
date_default_timezone_set('Asia/Shanghai');

/**
 * Authentication and user administration service
 *
 * @package Amfphp_Examples_Authentication
 * @author Sven Dens
 */
class Sim_Bai
{


    /**
     * protected methods
     * @var array
     */

    public static $protectedMethods = array();

    /**
     * constructor
     * @throws Exception
     */
    function __construct()
    {
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
    public function login($obj)
    {
        $pdo = MySQLUtil::getConnection();

        $tsql = "SELECT * from user WHERE username = :username AND password = :pass";

        $stmt = $pdo->prepare($tsql);
        $stmt->bindParam(':username', $obj->userName, PDO::PARAM_STR);
        $stmt->bindParam(':pass', $obj->passWord, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_OBJ);
        $res = new stdClass();
        if ($result) {
            $res->type = true;
        } else {
            $res->type = false;
        }

        return $res;

    }

    public function userSurplusMoney($userid)
    {
        //self::updatemoney($userid);

        $pdo = MySQLUtil::getConnection();

        $tsql = "SELECT SQL_CACHE * from user WHERE username = :username";

        $stmt = $pdo->prepare($tsql);
        $stmt->bindParam(':username', $userid, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_OBJ);
        $stmt = null;
        $pdo = null;
        $res = new stdClass();
        $data = new stdClass();
        if ($result) {
            $res->type = "userSurplusMoney";
            $data->userMoney = floatval($result->currentmoney);
            $data->userid = $userid;
            $res->data = $data;
        } else {
            $res->type = "userSurplusMoney";
            $data->userMoney = 0;
            $data->userid = $userid;
            $res->data = $data;
        }

        return $res;

    }

    public function getusermoney($userid)
    {
        //self::updatemoney($userid);

        $pdo = MySQLUtil::getConnection();

        $tsql = "SELECT SQL_CACHE * from user WHERE username = :username";

        $stmt = $pdo->prepare($tsql);
        $stmt->bindParam(':username', $userid, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_OBJ);
        $stmt = null;
        $pdo = null;
        if ($result) {
            return floatval($result->currentmoney);
        } else {
            return 0;
        }

        return 0;

    }

    public function updatemoney($userid)
    {
        $pdo = MySQLUtil::getConnection();
        $tsql = "SELECT winmoney,money from injectresult i,user u WHERE u.username =:username and i.uid=u.id";

        $stmt = $pdo->prepare($tsql);
        $stmt->bindParam(':username', $userid, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);

        $money = -312541;
        if ($result) {
            $money = floatval($result[0]->money);

            foreach ($result as $r) {
                $money = $money + floatval($r->winmoney);
            }
        }

        if ($money != -312541) {
            $tsql = "update user set currentmoney=" . $money . " where username='" . $userid . "'";

            $stmt = $pdo->prepare($tsql);
            $stmt->bindParam(':username', $userid, PDO::PARAM_STR);
            $stmt->execute();
        }
        $stmt = null;
        $pdo = null;
    }

    public function broadcastNotice()
    {
        $pdo = MySQLUtil::getConnection();
        $psql = "select SQL_CACHE * from notice where id=2";
        $stmt = $pdo->prepare($psql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        $res = new stdClass();
        $res->type = "broadcastNotice";
        $res->data = $result->content;
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


    public function runfms($obj, $id, $name)
    {
        // $fp = fopen("log.txt","a");
        // fwrite($fp, json_encode($obj)."\r\n");
        // fclose($fp);


        if ($obj->fun == "userInjectMoney") {
            $uid = self::getuid($name);
            $rid = self::getrid($id);
            $data = $obj->data;
            $data = $data->inject;
            if ($rid != "0" && $uid != "0") {
                // if($data->zd>1000 || $data->xd>1000 || $data->h>2000)
                // {
                // $data->gameState=intval($result2->gameState);
                // $res->data=$data;
                // $res->type="injectMoneyError";
                // return $res;

                // }
                if ($data->z != 0) {
                    self::insertinject("1", $data->z, $rid, $uid);
                }
                if ($data->x != 0) {
                    self::insertinject("2", $data->x, $rid, $uid);
                }
                if ($data->h != 0) {
                    self::insertinject("3", $data->h, $rid, $uid);
                }
                if ($data->zd != 0) {
                    self::insertinject("4", $data->zd, $rid, $uid);
                }
                if ($data->xd != 0) {
                    self::insertinject("5", $data->xd, $rid, $uid);
                }
            }
        }

        return null;
    }

    public function getrid($id)
    {
        $pdo = MySQLUtil::getConnection();
        $tsql = "select * from `round` where tab_id=" . intval($id) . " order by rid desc";

        $stmt = $pdo->prepare($tsql);
        $stmt->execute();
        $result2 = $stmt->fetch(PDO::FETCH_OBJ);
        $stmt = null;
        $pdo = null;
        if ($result2) {
            $rid = $result2->rid;
            return $rid;

        }

        return "0";
    }

    public function getrid1($id)
    {
        $pdo = MySQLUtil::getConnection();
        $tsql = "select SQL_CACHE * from `round` where tab_id=" . intval($id) . " order by rid desc";

        $stmt = $pdo->prepare($tsql);
        $stmt->execute();
        $result2 = $stmt->fetch(PDO::FETCH_OBJ);
        $stmt = null;
        $pdo = null;
        if ($result2) {
            if ($result2->gameState == "1" || $result2->gameState == "2") {
                $rid = $result2->rid;
                return $rid;
            }

        }

        return "0";
    }

    public function getuid($name)
    {
        $pdo = MySQLUtil::getConnection();
        $tsql = "select SQL_CACHE * from `user` where username='" . $name . "'";

        $stmt = $pdo->prepare($tsql);
        $stmt->execute();
        $result2 = $stmt->fetch(PDO::FETCH_OBJ);
        $stmt = null;
        $pdo = null;
        if ($result2) {
            $rid = $result2->id;
            return $rid;

        }

        return "0";
    }

    public function insertinject($type, $money, $rid, $uid)
    {
        $winmoney = (-1) * floatval($money);
        $itime = date('Y-m-d H:i:s', time());
        $pdo = MySQLUtil::getConnection();
        $tsql = "insert into injectresult(uid,rid,injecttime,injecttype,ip,syh,injectmoney,winmoney) values('$uid','$rid','$itime','$type','127.0.0.1','-1','$money','$winmoney')";

        $stmt = $pdo->prepare($tsql);
        $stmt->execute();
        $psql = "update user set currentmoney=currentmoney-$money where id=$uid";
        $stmt = $pdo->prepare($psql);
        $stmt->execute();
    }

    public function upstate($rid, $s)
    {
        $pdo = MySQLUtil::getConnection();
        $tsql = "update `round` set gameState=" . $s . " where rid=" . $rid;
        $stmt = $pdo->prepare($tsql);
        $stmt->execute();
        $stmt = null;
        $pdo = null;
    }

    public function upusertable($uid, $tid)
    {
        $tid = intval($tid);
        $pdo = MySQLUtil::getConnection();
        $date = date("Y-m-d H:i:s", time());

        if ($tid != "0")
            $tsql = "update `user` set tableid=" . $tid . ",lastlogintime='$date' where username='" . $uid . "'";
        else
            $tsql = "update `user` set tableid=" . $tid . " where username='" . $uid . "'";
        $stmt = $pdo->prepare($tsql);
        $stmt->execute();
        $stmt = null;
        $pdo = null;
    }

    public function gameStateSim($id)
    {

        $pdo = MySQLUtil::getConnection();
        $tsql = "select SQL_CACHE * from `round` where tab_id=" . intval($id) . " order by rid desc limit 1";
        $stmt = $pdo->prepare($tsql);
        $stmt->execute();
        $result2 = $stmt->fetch(PDO::FETCH_OBJ);
        $psql = "select SQL_CACHE * from tablet where tab_id='" . intval($id) . "'";
        $stmt = $pdo->prepare($psql);
        $stmt->execute();
        $result3 = $stmt->fetch(PDO::FETCH_OBJ);
        $stmt = null;
        $pdo = null;
        $data = new stdClass();
        $res = new stdClass();
        if ($result2) {
            $data->injectTime = intval($result3->injecttime);    //倒计时秒数
            $data->gameBoot = intval($result2->gameBoot);    //场次
            $data->roundNum = intval($result2->roundNum);        // 次
            $data->nowTime = self::getmicro();        //当前时间   毫秒
            $data->startTime = floatval($result2->startTime);        //投注开始 时间	 毫秒
            $data->gameState = intval($result2->gameState);
            if ($data->gameState == 1) {
                $data->time = intval((($data->nowTime) - ($data->startTime)) / 1000);
                $data->time = intval(($data->injectTime) - ($data->time));
                if (($data->time) < 0) {
                    self::upstate($result2->rid, "2");
                    $data->time = 0;
                    $data->gameState = 2;
                }
            } else
                $data->time = 0;
            $data->gameid = $result3->gameid;
            $data->telMax = intval($result3->telMax);
            if ($data->telMax < 200)
                $data->telMax = 50000;
            $data->telMin = intval($result3->telMin);
            if ($data->telMin < 10 || $data->telMin > 10000)
                $data->telMin = 10;
            $data->gameNumber = $result2->gameNumber;
            $data->gameType = $result3->gameType;
            $data->gameTableName = $result3->gameTableName;
            // 1 开始投注  time 0-40  2 停止投注	3洗牌   0 出结果后
            $data->isCheckOut = false;
            $data->farmerBool = false;

            $res->data = $data;
            $res->type = "gameState";

        } else {
            $data->injectTime = intval($result3->injecttime);    //倒计时秒数
            $data->gameBoot = 0;    //场次
            $data->roundNum = 0;        // 次
            $data->nowTime = self::getmicro();        //当前时间   毫秒
            $data->startTime = self::getmicro();        //投注开始 时间	 毫秒
            $data->gameState = 0;
            $data->time = 0;
            $data->gameid = $result3->gameid;
            $data->telMax = intval($result3->telMax);
            if ($data->telMax < 200)
                $data->telMax = 50000;
            $data->telMin = intval($result3->telMin);
            if ($data->telMin < 10 || $data->telMin > 10000)
                $data->telMin = 10;
            $data->gameNumber = "0";
            $data->gameType = $result3->gameType;
            $data->gameTableName = $result3->gameTableName;
            // 1 开始投注  time 0-40  2 停止投注	3洗牌   0 出结果后
            $data->isCheckOut = false;
            $data->farmerBool = false;

            $res->data = $data;
            $res->type = "gameState";

        }
        return $res;

    }

    public function gameState($id)
    {

        $pdo = MySQLUtil::getConnection();
        $tsql = "select * from `round`,tablet where tablet.tab_id=`round`.tab_id and tablet.gameid=" . $id . " order by rid desc limit 1";
        $stmt = $pdo->prepare($tsql);
        $stmt->execute();
        $result2 = $stmt->fetch(PDO::FETCH_OBJ);
        $stmt = null;
        $pdo = null;
        $data = new stdClass();
        $res = new stdClass();
        if ($result2) {
            $data->injectTime = intval($result2->injecttime);    //倒计时秒数
            $data->gameBoot = intval($result2->gameBoot);    //场次
            $data->roundNum = intval($result2->roundNum);        // 次
            $data->nowTime = self::getmicro();        //当前时间   毫秒
            $data->startTime = floatval($result2->startTime);        //投注开始 时间	 毫秒
            $data->gameState = intval($result2->gameState);
            if ($data->gameState == 1) {
                $data->time = intval((($data->nowTime) - ($data->startTime)) / 1000);
                $data->time = intval(($data->injectTime) - ($data->time));
                if (($data->time) < 0) {
                    self::upstate($result2->rid, "2");
                    $data->time = 0;
                    $data->gameState = 2;
                }
            } else
                $data->time = 0;
            $data->gameid = $result2->gameid;
            $data->telMax = intval($result2->telMax);
            if ($data->telMax < 200)
                $data->telMax = 50000;
            $data->telMin = intval($result2->telMin);
            if ($data->telMin < 10 || $data->telMin > 10000)
                $data->telMin = 10;
            $data->gameNumber = $result2->gameNumber;
            $data->gameType = $result2->gameType;
            $data->gameTableName = $result2->gameTableName;
            // 1 开始投注  time 0-40  2 停止投注	3洗牌   0 出结果后
            $data->isCheckOut = false;
            $data->farmerBool = false;

            $res->data = $data;
            $res->type = "gameState";
            //$res->state=$s;
            return $res;
        }
        $res->type = "false";
        return $res;


    }

    public function gameStateStart($id)
    {

        $pdo = MySQLUtil::getConnection();
        $tsql = "select SQL_CACHE * from `round` where tab_id=" . intval($id) . " order by rid desc limit 1";
        $stmt = $pdo->prepare($tsql);
        $stmt->execute();
        $result2 = $stmt->fetch(PDO::FETCH_OBJ);
        $psql = "select SQL_CACHE * from tablet where tab_id='" . intval($id) . "'";
        $stmt = $pdo->prepare($psql);
        $stmt->execute();
        $result3 = $stmt->fetch(PDO::FETCH_OBJ);
        $stmt = null;
        $pdo = null;
        $data = new stdClass();
        $res = new stdClass();
        if ($result2) {
            $data->injectTime = intval($result3->injecttime);    //倒计时秒数
            $data->gameBoot = intval($result2->gameBoot);    //场次
            $data->roundNum = intval($result2->roundNum);        // 次
            $data->nowTime = self::getmicro();        //当前时间   毫秒
            $data->startTime = floatval($result2->startTime);        //投注开始 时间	 毫秒
            $data->gameState = intval($result2->gameState);
            if ($data->gameState == 1) {
                $data->time = intval((($data->nowTime) - ($data->startTime)) / 1000);
                $data->time = intval(($data->injectTime) - ($data->time));
                if (($data->time) < 0) {
                    self::upstate($result2->rid, "2");
                    $data->time = 0;
                    $data->gameState = "2";
                }
            } else
                $data->time = 0;
            $data->gameid = $result3->gameid;
            $data->telMax = intval($result3->telMax);
            if ($data->telMax < 200)
                $data->telMax = 50000;
            $data->telMin = intval($result3->telMin);
            if ($data->telMin < 10 || $data->telMin > 10000)
                $data->telMin = 10;
            $data->gameNumber = $result2->gameNumber;
            $data->gameType = $result3->gameType;
            $data->gameTableName = $result3->gameTableName;
            // 1 开始投注  time 0-40  2 停止投注	3洗牌   0 出结果后
            $data->isCheckOut = false;
            $data->farmerBool = false;

            $res->data = $data;
            $res->type = "gameState";
            //$res->state=$s;
        } else {
            $data->injectTime = intval($result3->injecttime);    //倒计时秒数
            $data->gameBoot = 0;    //场次
            $data->roundNum = 0;        // 次
            $data->nowTime = self::getmicro();        //当前时间   毫秒
            $data->startTime = self::getmicro();        //投注开始 时间	 毫秒
            $data->gameState = 0;
            $data->time = 0;
            $data->gameid = $result3->gameid;
            $data->telMax = intval($result3->telMax);
            if ($data->telMax < 200)
                $data->telMax = 50000;
            $data->telMin = intval($result3->telMin);
            if ($data->telMin < 10 || $data->telMin > 10000)
                $data->telMin = 10;
            $data->gameNumber = "0";
            $data->gameType = $result3->gameType;
            $data->gameTableName = $result3->gameTableName;
            // 1 开始投注  time 0-40  2 停止投注	3洗牌   0 出结果后
            $data->isCheckOut = false;
            $data->farmerBool = false;

            $res->data = $data;
            $res->type = "gameState";

        }


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

    public function gethistoryBoot($id)
    {
        $pdo = MySQLUtil::getConnection();
        $today = date("Y-m-d", time()) . " 00:00:00";
        $tsql = "select gameBoot from `round` where tab_id=" . intval($id) . " and createtime>'$today' order by rid desc";
        $stmt = $pdo->prepare($tsql);
        $stmt->execute();
        $result2 = $stmt->fetch(PDO::FETCH_OBJ);
        $stmt = null;
        $pdo = null;

        if ($result2) {
            return $result2->gameBoot;
        }
        return "1000";
    }

    public function gethistoryBootXipai($id)
    {
        $pdo = MySQLUtil::getConnection();
        $today = date("Y-m-d", time()) . " 00:00:00";
        $tsql = "select SQL_CACHE gameBoot,gameState from `round` where tab_id=" . intval($id) . " and createtime>'$today' order by rid desc";
        $stmt = $pdo->prepare($tsql);
        $stmt->execute();
        $result2 = $stmt->fetch(PDO::FETCH_OBJ);
        $stmt = null;
        $pdo = null;

        if ($result2) {
            if ($result2->gameState == "3")
                return "6000";
            return $result2->gameBoot;
        }
        return "6000";
    }

    public function gameHistory($id)
    {
        $boot = self::gethistoryBootXipai($id);
        $pdo = MySQLUtil::getConnection();
        $today = date("Y-m-d", time()) . " 00:00:00";
        $data = new stdClass();
        $res = new stdClass();
        $data->gameHistory = array();
        if ($boot != "6000") {
            $tsql = "select SQL_CACHE gameid,gameType,gameTableName,result,gameState from `round`,tablet where `round`.gameState in (0,3) and tablet.tab_id=`round`.tab_id and tablet.gameid=" . $id . " and `round`.gameBoot=" . $boot . " and createtime>'$today' and `round`.result>=0 order by rid asc";
            $stmt = $pdo->prepare($tsql);
            $stmt->execute();
            $result2 = $stmt->fetchAll(PDO::FETCH_OBJ);
            $stmt = null;
            $pdo = null;
            if (count($result2) > 0) {
                foreach ($result2 as $re2) {
                    array_push($data->gameHistory, intval($re2->result));
                }
            }
        }
        $data->gameid = $id;
        $data->gameType = "baijiale";
        $data->gameTableName = "baijiale" . $id;
        $data->historystr = implode(',', $data->gameHistory);

        $res->type = "gameHistory";
        $res->data = $data;
        return $res;

    }

    public function gameHistoryStart($id)
    {
        $boot = self::gethistoryBootXipai($id);
        $pdo = MySQLUtil::getConnection();
        $today = date("Y-m-d", time()) . " 00:00:00";
        $data = new stdClass();
        $res = new stdClass();
        $data->gameHistory = array();
        if ($boot != "6000") {
            $tsql = "select SQL_CACHE gameid,gameType,gameTableName,result,gameState from `round`,tablet where `round`.gameState in (0,3) and tablet.tab_id=`round`.tab_id and tablet.gameid=" . $id . " and `round`.gameBoot=" . $boot . " and createtime>'$today' and `round`.result>=0 order by rid asc";
            $stmt = $pdo->prepare($tsql);
            $stmt->execute();
            $result2 = $stmt->fetchAll(PDO::FETCH_OBJ);
            $stmt = null;
            $pdo = null;
            if (count($result2) > 0) {
                foreach ($result2 as $re2) {
                    array_push($data->gameHistory, intval($re2->result));
                }
            }
        }
        $data->gameid = $id;
        $data->gameType = "baijiale";
        $data->gameTableName = "baijiale" . $id;
        $data->historystr = implode(',', $data->gameHistory);

        $res->type = "gameHistory";
        $res->data = $data;
        return $res;

    }

    public function gameInfo($id)
    {
        $pdo = MySQLUtil::getConnection();
        $tsql = "select SQL_CACHE * from tablet where gameid='$id'";
        $stmt = $pdo->prepare($tsql);
        $stmt->execute();
        $result2 = $stmt->fetch(PDO::FETCH_OBJ);
        $stmt = null;
        $pdo = null;
        $data = new stdClass();
        if ($result2) {
            $data->telMin = intval($result2->telMin);
            if ($data->telMin < 10 || $data->telMin > 10000)
                $data->telMin = 10;
            $data->telMax = intval($result2->telMax);
            if ($data->telMax < 200)
                $data->telMax = 50000;
            $data->injectTime = intval($result2->injecttime);
        } else {
            $data->telMin = 1000;
            $data->telMax = 100000;
            $data->injectTime = 50;
        }
        $res = new stdClass();
        $res->type = "gameInfo";
        $chip = explode(',', $result2->chip);
        $data->customChipArr = array();
        foreach ($chip as $c) {
            array_push($data->customChipArr, intval($c));
        }
        $data->gameid = $id;
        $data->gameType = "baijiale";
        $data->gameTableName = "baijiale" . ($data->gameid);

        $data->farmerBool = false;
        $data->telTieMax = intval($result2->tieMax);
        $data->telPairMax = intval($result2->pairMax);
        $data->telDiffMax = intval($result2->diffMax);
        $data->videoUrll = "rtmp://live.fbjoy.com:1989/goboes/";
        $data->videoName = "b0" . $id;

        $data->tableMax = 12000000;
        $data->tableMin = 0;

        $res->data = $data;
        $fp = fopen("log.txt", "a");
        fwrite($fp, json_encode($res) . "\r\n");
        fclose($fp);
        return $res;
    }

    public function refreshAllUserMoney($obj, $n)
    {
        $res = new stdClass();
        $data = new stdClass();
        foreach ($obj as $ob) {
            $name = $ob;
            $money = self::getusermoney($name);
            $data->userName = $ob;
            $data->money = $money;
            $res->data[] = $data;
            $data = null;
        }
        $res->type = "refreshAllUserMoney";
        $res->name = $n;

        return $res;
    }

    public function groupInjectMoney($tid, $allseat)
    {
        //return null;
        $len = count($allseat);
        $res = new stdClass();
        $data = new stdClass();
        if ($len > 0) {
            $pdo = MySQLUtil::getConnection();
            for ($i = 0; $i < $len; $i++) {
                $ss = explode(',', $allseat[$i]);
                //$ss=explode(',',"gulu,0");
                $tsql = "SELECT SQL_CACHE * from user where username='" . $ss[0] . "'";
                $stmt = $pdo->prepare($tsql);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_OBJ);
                if ($result) {
                    $data->nickName = $result->nickName;
                    // $data->nickName="小小哥哥";
                    $data->userid = $result->username;
                    $data->userMoney = floatval($result->currentmoney);
                    $data->seat = intval($ss[1]);
                    $uid = $result->id;
                }
                $rid = self::getrid1($tid);
                $data->big = 0;
                $data->small = 0;
                $data->z = 0;
                $data->x = 0;
                $data->h = 0;
                $data->zd = 0;
                $data->xd = 0;
                $data->z1 = 0;
                $data->x1 = 0;
                $data->x2 = 0;
                $data->z2 = 0;

                $tsql = "select SQL_CACHE * from `injectresult` where uid=" . $uid . " and rid=$rid order by rid desc";
                $stmt = $pdo->prepare($tsql);
                $stmt->execute();
                $result3 = $stmt->fetchAll(PDO::FETCH_OBJ);
                if ($result3) {
                    foreach ($result3 as $re) {
                        $t = $re->injecttype;
                        $m = $re->injectmoney;
                        if ($t == "1") $data->z += floatval($m);
                        elseif ($t == "2") $data->x += floatval($m);
                        elseif ($t == "3") $data->h += floatval($m);
                        elseif ($t == "4") $data->zd += floatval($m);
                        elseif ($t == "5") $data->xd += floatval($m);
                    }
                }


                $res->data[] = $data;
                $data = null;
            }

        }

        $data->seatGroupNum = intval($len);
        $data->allLength = 20 * intval($len);
        $res->type = "groupInjectMoney";
        $stmt = null;
        $pdo = null;

        return $res;
    }

    public function injectMoneyOkBegin($tabid, $name)
    {
        $uid = self::getuid($name);
        $rid = self::getrid1($tabid);
        $data = new stdClass();
        $res = new stdClass();
        $data->big = 0;
        $data->small = 0;
        $data->z = 0;
        $data->x = 0;
        $data->h = 0;
        $data->zd = 0;
        $data->xd = 0;
        $data->userMoney = self::getusermoney($name);
        $data->z1 = 0;
        $data->x1 = 0;
        $data->x2 = 0;
        $data->z2 = 0;


        $pdo = MySQLUtil::getConnection();
        $tsql = "select SQL_CACHE * from `injectresult` where uid=" . $uid . " and rid=$rid order by rid desc";
        $stmt = $pdo->prepare($tsql);
        $stmt->execute();
        $result2 = $stmt->fetchAll(PDO::FETCH_OBJ);
        $stmt = null;
        $pdo = null;
        if ($result2) {
            foreach ($result2 as $re) {
                $t = $re->injecttype;
                $m = $re->injectmoney;
                if ($t == "1") $data->z += floatval($m);
                elseif ($t == "2") $data->x += floatval($m);
                elseif ($t == "3") $data->h += floatval($m);
                elseif ($t == "4") $data->zd += floatval($m);
                elseif ($t == "5") $data->xd += floatval($m);
            }
            $res->data = $data;
            $res->type = "injectMoneyOk";
            $res->name = $name;
        } else {
            $res->data = $data;
            $res->type = "fail";
            $res->name = $name;
        }


        return $res;
    }

    public function injectMoneyOk($tabid, $name)
    {
        $uid = self::getuid($name);
        $rid = self::getrid($tabid);
        $data = new stdClass();
        $res = new stdClass();
        $data->big = 0;
        $data->small = 0;
        $data->z = 0;
        $data->x = 0;
        $data->h = 0;
        $data->userMoney = self::getusermoney($name);
        $data->zd = 0;
        $data->xd = 0;
        $data->z1 = 0;
        $data->x1 = 0;
        $data->x2 = 0;
        $data->z2 = 0;


        $pdo = MySQLUtil::getConnection();
        $tsql = "select SQL_CACHE * from `injectresult` where uid=" . $uid . " and rid=$rid order by rid desc";
        $stmt = $pdo->prepare($tsql);
        $stmt->execute();
        $result2 = $stmt->fetchAll(PDO::FETCH_OBJ);
        $stmt = null;
        $pdo = null;
        if ($result2) {
            foreach ($result2 as $re) {
                $t = $re->injecttype;
                $m = $re->injectmoney;
                if ($t == "1") $data->z += floatval($m);
                elseif ($t == "2") $data->x += floatval($m);
                elseif ($t == "3") $data->h += floatval($m);
                elseif ($t == "4") $data->zd += floatval($m);
                elseif ($t == "5") $data->xd += floatval($m);
            }
        }


        $res->data = $data;
        $res->type = "injectMoneyOk";
        $res->name = $name;

        return $res;
    }

    public function gameAllInjects($tabid)
    {
        //$rid=self::getrid($tabid);
        $rid = self::getrid($tabid);
        $res = new stdClass();
        $data = new stdClass();
        $data->big = 0;
        $data->small = 0;
        $data->z = 0;
        $data->x = 0;
        $data->h = 0;
        $data->zd = 0;
        $data->xd = 0;
        $data->z1 = 0;
        $data->x1 = 0;
        $data->x2 = 0;
        $data->z2 = 0;
        // $boot = self::gethistoryBoot($id);
        $pdo = MySQLUtil::getConnection();
        $tsql = "select * from `injectresult` where rid=$rid and syh=-1 order by rid desc";
        $stmt = $pdo->prepare($tsql);
        $stmt->execute();
        $result2 = $stmt->fetchAll(PDO::FETCH_OBJ);
        $stmt = null;
        $pdo = null;
        if ($result2) {
            foreach ($result2 as $re) {
                $t = $re->injecttype;
                $m = $re->injectmoney;
                if ($t == "1") $data->z += floatval($m);
                elseif ($t == "2") $data->x += floatval($m);
                elseif ($t == "3") $data->h += floatval($m);
                elseif ($t == "4") $data->zd += floatval($m);
                elseif ($t == "5") $data->xd += floatval($m);
            }
        }


        $res->data = $data;
        $res->type = "gameAllInject";
        $res->tid = $tabid;

        return $res;
    }

    public function gameAllInject($name, $tabid, $seat)
    {
        $uid = self::getuid($name);
        $rid = self::getrid($tabid);
        $data = new stdClass();
        $res = new stdClass();
        $data->big = 0;
        $data->small = 0;
        $data->z = 0;
        $data->x = 0;
        $data->h = 0;
        $data->zd = 0;
        $data->xd = 0;
        $data->z1 = 0;
        $data->x1 = 0;
        $data->x2 = 0;
        $data->z2 = 0;

        //$data->nickName=null;
        $data->userid = $name;
        $data->userMoney = self::getusermoney($name);
        $data->seat = $seat;
        $data->ip = "125.245.142.25";

        // $boot = self::gethistoryBoot($id);
        $pdo = MySQLUtil::getConnection();
        $tsql = "select * from `injectresult` where uid=" . $uid . " and rid=$rid order by rid desc";
        $stmt = $pdo->prepare($tsql);
        $stmt->execute();
        $result2 = $stmt->fetchAll(PDO::FETCH_OBJ);
        $stmt = null;
        $pdo = null;
        if ($result2) {
            foreach ($result2 as $re) {
                $t = $re->injecttype;
                $m = $re->injectmoney;
                if ($t == "1") $data->z += floatval($m);
                elseif ($t == "2") $data->x += floatval($m);
                elseif ($t == "3") $data->h += floatval($m);
                elseif ($t == "4") $data->zd += floatval($m);
                elseif ($t == "5") $data->xd += floatval($m);
            }
        }


        $res->data = $data;
        $res->type = "userInjectMoney";

        return $res;
    }


    public function userInjectMoney($tabid, $name, $money, $seat)
    {
        $res = new stdClass();
        $data = new stdClass();
        $data->big = 0;
        $data->small = 0;
        $data->z = 0;
        $data->x = 0;
        $data->h = 0;
        $data->zd = 0;
        $data->xd = 0;
        $data->z1 = 0;
        $data->x1 = 0;
        $data->x2 = 0;
        $data->z2 = 0;

        $rid = self::getrid($tabid);
        $uid = self::getuid($name);
        $pdo = MySQLUtil::getConnection();
        $tsql = "select SQL_CACHE * from `injectresult` where rid=$rid and uid=$uid order by rid desc";
        $stmt = $pdo->prepare($tsql);
        $stmt->execute();
        $result2 = $stmt->fetchAll(PDO::FETCH_OBJ);
        $stmt = null;
        $pdo = null;
        if ($result2) {
            foreach ($result2 as $re) {
                $t = $re->injecttype;
                $m = $re->injectmoney;
                if ($t == "1") $data->z += floatval($m);
                elseif ($t == "2") $data->x += floatval($m);
                elseif ($t == "3") $data->h += floatval($m);
                elseif ($t == "4") $data->zd += floatval($m);
                elseif ($t == "5") $data->xd += floatval($m);
            }
        }

        $data->nickName = null;
        $data->userid = $name;
        $data->userMoney = $money;
        $data->seat = $seat;
        $data->ip = "125.245.142.25";

        $res->data = $data;
        $res->type = "userInjectMoney";
        $res->tid = $tabid;

        return $res;
    }

    public function gameResults($tabid)
    {
        // $boot = self::gethistoryBoot($id);
        // if(true)
        // {
        $pdo = MySQLUtil::getConnection();
        $tsql = "select SQL_CACHE * from `round` where tab_id=" . intval($tabid) . " order by rid desc";
        $stmt = $pdo->prepare($tsql);
        $stmt->execute();
        $result2 = $stmt->fetch(PDO::FETCH_OBJ);
        $stmt = null;
        $pdo = null;
        $res = new stdClass();
        $data = new stdClass();
        $n = '';
        if ($result2) {
            $chang = $result2->gameBoot;
            $ci = $result2->roundNum;
            $cc = $chang . "-" . $ci;

            $gameNumber = $result2->gameNumber;

            $nowGameNum = (string)$gameNumber;
            $nowGameNum = $tabid . substr($nowGameNum, -10);

            $state = $result2->gameState;
            $result = $result2->result;
            if ($state == "0" && $result != "-1") {
                $data->nowGameNum = $nowGameNum;
                $data->gameNumber = $gameNumber;
                $data->model = "syn";//固定
                $data->zxh = self::getzxh($result);//0庄 1闲 2和
                $data->pu = 0;//???


                $data->gameLogArr = array();//历史记录
                $data->userArr = null;//????
                $data->pauseReCheckout = true;//????
                $data->ju = $cc;
                $data->resutls = intval($result);//0-11 结果

                if ($data->resutls == 1 || $data->resutls == 5 || $data->resutls == 9)
                    $data->zbool = 1;
                if ($data->resutls == 2 || $data->resutls == 6 || $data->resutls == 10)
                    $data->xbool = 1;
                if ($data->resutls == 3 || $data->resutls == 7 || $data->resutls == 11) {
                    $data->zbool = 1;
                    $data->xbool = 1;
                }
                $res->data = $data;
                $res->type = "gameResults";
                $res->tabid = $tabid;
                return $res;
            }
        }
        // }


        $res->type = "false";
        $res->name = $n;

        return $res;
    }

    function getzxh($r)
    {
        if ($r == "0" || $r == "1" || $r == "2" || $r == "3")
            return 0;
        else if ($r == "4" || $r == "5" || $r == "6" || $r == "7")
            return 1;
        else
            return 2;
    }

    public function seatList($allseat)
    {

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

        $arr = new stdClass();
        $res = new stdClass();
        $data = new stdClass();
        $len = count($allseat);
        if ($len > 0) {
            $pdo = MySQLUtil::getConnection();
            for ($i = 0; $i < $len; $i++) {
                $ss = explode(',', $allseat[$i]);

                $tsql = "SELECT * from user where username='" . $ss[0] . "'";

                $stmt = $pdo->prepare($tsql);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_OBJ);
                if ($result) {
                    $arr->nickName = $result->nickName;
                    // $arr->nickName="小小哥哥";
                    $arr->userid = $result->username;
                    $arr->userMoney = floatval($result->currentmoney);
                    $arr->seat = $ss[1];

                    $data->arr[] = $arr;
                    $arr = null;
                }

            }
            $stmt = null;
            $pdo = null;
            if ($len > 7) {
                $data->seatGroupNum = 7;
                $data->allLength = 140;
            } else {
                $data->seatGroupNum = intval($len);
                $data->allLength = intval($len) * 20;
            }
            $res->type = "seatList";
            $res->data = $data;

            return $res;
        }
        return res;
    }


    public function seatList_remove()
    {
        $data = new stdClass();
        $res = new stdClass();
        $data->nickName = null;
        $data->userid = "ddad";
        $data->userMoney = "845555";
        $data->seat = 6;

        $res->type = "seatList_remove";
        $res->data = $data;
        return $res;
    }

    public function seatList_add()
    {
        $res = new stdClass();
        $data = new stdClass();
        $data->nickName = null;
        $data->userid = "ddad";
        $data->userMoney = "845555";
        $data->seat = 6;
        $data->ip = "125.245.142.25";

        $data->big = 0;
        $data->small = 0;
        $data->z = 0;
        $data->x = 0;
        $data->h = 0;
        $data->zd = 0;
        $data->xd = 0;
        $data->z1 = 0;
        $data->x1 = 0;
        $data->x2 = 0;
        $data->z2 = 0;


        $res->type = "seatList_add";
        $res->data = $data;
        return $res;
    }

    public function loginfms($userid, $userpsw)
    {
        $pdo = MySQLUtil::getConnection();

        $tsql = "SELECT * from user WHERE username = :username AND password = :pass";

        $stmt = $pdo->prepare($tsql);
        $stmt->bindParam(':username', $userid, PDO::PARAM_STR);
        $stmt->bindParam(':pass', $userpsw, PDO::PARAM_STR);
        $stmt->execute();
        $data = new stdClass();
        $res = new stdClass();

        $result = $stmt->fetch(PDO::FETCH_OBJ);
        $stmt = null;
        $pdo = null;
        if ($result) {
            $data->customRate = array();
            $data->userMoney = floatval($result->currentmoney);
            $data->parentTreeStr = "admin/xiaozhi";
            $data->userName = $userid;
            $data->id = $userid;
            $data->userid = $userid;
            $data->gameArr = array("baijiale");
            $data->seat = 0;
            $data->big = 0;
            $data->small = 0;
            $data->x = 0;
            $data->z = 0;
            $data->h = 0;
            $data->xd = 0;
            $data->zd = 0;
            $data->x1 = 0;
            $data->x2 = 0;
            $data->z1 = 0;
            $data->z2 = 0;
            $data->wins = self::getwins($userid);
            $data->realType = "real";
            $data->type = "user";
            $data->ip = "14.18.29.38";
            $res->type = "loginOk";
            $res->data = $data;

        } else {
            $res->type = "loginFail";
            $res->data = "";
        }
        $fp = fopen("log.txt", "a");
        fwrite($fp, json_encode($res) . "\r\n");
        fclose($fp);
        return $res;

    }

    public function getwins($userid)
    {
        return 3;
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