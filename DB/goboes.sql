/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50711
Source Host           : localhost:3306
Source Database       : goboes

Target Server Type    : MYSQL
Target Server Version : 50711
File Encoding         : 65001

Date: 2016-11-02 14:44:35
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES ('1', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '0');
INSERT INTO `admin` VALUES ('2', 'sb', 'd41d8cd98f00b204e9800998ecf8427e', '01,02');
INSERT INTO `admin` VALUES ('3', 'g', '202cb962ac59075b964b07152d234b70', '03,04');
INSERT INTO `admin` VALUES ('6', 'a', 'c4ca4238a0b923820dcc509a6f75849b', '0');
INSERT INTO `admin` VALUES ('8', 'dd', '202cb962ac59075b964b07152d234b70', '5');

-- ----------------------------
-- Table structure for datas
-- ----------------------------
DROP TABLE IF EXISTS `datas`;
CREATE TABLE `datas` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `datas` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of datas
-- ----------------------------
INSERT INTO `datas` VALUES ('1', '111xiaozhi8888');

-- ----------------------------
-- Table structure for injectresult
-- ----------------------------
DROP TABLE IF EXISTS `injectresult`;
CREATE TABLE `injectresult` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) DEFAULT NULL,
  `rid` int(10) DEFAULT NULL,
  `injecttime` datetime DEFAULT NULL,
  `injecttype` int(10) DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `syh` int(10) DEFAULT NULL,
  `injectmoney` double(50,2) DEFAULT NULL,
  `winmoney` double(50,2) DEFAULT NULL,
  `restmoney` double(50,2) DEFAULT NULL,
  `ximaliang` double(50,2) NOT NULL DEFAULT '0.00',
  `ximayongjin` double(50,2) NOT NULL DEFAULT '0.00',
  `jiaoshangjia` double(50,2) NOT NULL DEFAULT '0.00',
  `restxima` double(50,2) NOT NULL DEFAULT '0.00',
  `haschanged` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of injectresult
-- ----------------------------
INSERT INTO `injectresult` VALUES ('1', null, null, null, null, null, null, null, null, null, '0.00', '0.00', '0.00', '0.00', '0');
INSERT INTO `injectresult` VALUES ('2', '4', '27', '2015-12-28 21:34:07', '1', '127.0.0.1', '1', '500.00', '475.00', '139670.00', '500.00', '9.00', '0.00', '0.00', '0');
INSERT INTO `injectresult` VALUES ('3', '4', '28', '2015-12-28 21:34:50', '1', '127.0.0.1', '1', '1000.00', '950.00', '140620.00', '1000.00', '18.00', '0.00', '0.00', '0');

-- ----------------------------
-- Table structure for ip
-- ----------------------------
DROP TABLE IF EXISTS `ip`;
CREATE TABLE `ip` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `ip` varchar(100) NOT NULL,
  `loc` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ip
-- ----------------------------
INSERT INTO `ip` VALUES ('1', '219.138.225.221', '湖北省鄂州市鄂城区 电信ADSL');
INSERT INTO `ip` VALUES ('2', '152.124.124.132', '美国');
INSERT INTO `ip` VALUES ('3', '182.152.142', '无效的IP地址<br/>');
INSERT INTO `ip` VALUES ('4', '127.0.0.1', '本机地址');

-- ----------------------------
-- Table structure for moneylog
-- ----------------------------
DROP TABLE IF EXISTS `moneylog`;
CREATE TABLE `moneylog` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `uid` int(30) NOT NULL,
  `createdate` datetime DEFAULT NULL,
  `money` double(30,2) DEFAULT NULL,
  `type` int(2) DEFAULT NULL,
  `tid` int(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of moneylog
-- ----------------------------
INSERT INTO `moneylog` VALUES ('1', '3', '2015-08-14 00:05:14', '100.00', '1', '2');
INSERT INTO `moneylog` VALUES ('2', '2', '2015-08-14 00:05:33', '1000.00', '1', '2');
INSERT INTO `moneylog` VALUES ('3', '2', '2015-08-14 00:05:46', '0.00', '0', '2');
INSERT INTO `moneylog` VALUES ('4', '2', '2015-08-14 00:06:35', '10000.00', '0', '2');
INSERT INTO `moneylog` VALUES ('5', '2', '2015-08-14 00:07:06', '100.00', '1', '2');
INSERT INTO `moneylog` VALUES ('6', '3', '2015-08-14 00:07:18', '0.00', '0', '2');
INSERT INTO `moneylog` VALUES ('7', '2', '2015-08-14 00:08:43', '1500.00', '0', '2');
INSERT INTO `moneylog` VALUES ('8', '3', '2015-08-22 17:18:15', '10000.00', '1', '2');
INSERT INTO `moneylog` VALUES ('9', '3', '2015-08-22 17:18:27', '1000.00', '1', '2');
INSERT INTO `moneylog` VALUES ('10', '4', '2015-08-22 17:18:43', '10000.00', '1', '2');
INSERT INTO `moneylog` VALUES ('11', '35', '2015-08-22 17:19:35', '100000.00', '1', '2');
INSERT INTO `moneylog` VALUES ('12', '35', '2015-08-22 17:20:06', '1000000.00', '1', '2');
INSERT INTO `moneylog` VALUES ('13', '35', '2015-08-22 17:21:00', '100000.00', '0', '3');
INSERT INTO `moneylog` VALUES ('14', '35', '2015-08-22 17:21:23', '5000.00', '1', '3');
INSERT INTO `moneylog` VALUES ('15', '2', '2015-09-06 23:16:18', '2147483647.00', '1', '1');
INSERT INTO `moneylog` VALUES ('16', '2', '2015-09-06 23:17:09', '1000000000.00', '0', '1');
INSERT INTO `moneylog` VALUES ('17', '3', '2015-09-22 10:31:13', '1000000.00', '1', '2');
INSERT INTO `moneylog` VALUES ('18', '3', '2015-09-22 10:31:40', '6730.00', '1', '2');
INSERT INTO `moneylog` VALUES ('19', '3', '2015-09-22 16:01:59', '10000.00', '1', '2');
INSERT INTO `moneylog` VALUES ('20', '4', '2015-09-22 16:02:51', '1000.00', '1', '2');
INSERT INTO `moneylog` VALUES ('21', '39', '2016-11-01 20:40:28', '10000.00', '1', '1');
INSERT INTO `moneylog` VALUES ('22', '39', '2016-11-01 20:40:42', '20.00', '1', '1');
INSERT INTO `moneylog` VALUES ('23', '39', '2016-11-01 20:41:00', '30.00', '0', '1');

-- ----------------------------
-- Table structure for notice
-- ----------------------------
DROP TABLE IF EXISTS `notice`;
CREATE TABLE `notice` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` text,
  `content` text,
  `type` varchar(20) DEFAULT NULL,
  `game` varchar(20) DEFAULT NULL,
  `state` varchar(20) DEFAULT NULL,
  `stime` datetime DEFAULT NULL,
  `etime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of notice
-- ----------------------------
INSERT INTO `notice` VALUES ('1', '因国际网络线路故障，1号至15号台视频暂停开放，为确保客户汇款的安全与及时，请在每次汇款前，与公司财务联系，否则后果自负！', '因国际网络线路故障，1号至15号台视频暂停开放，为确保客户汇款的安全与及时，请在每次汇款前，与公司财务联系，否则后果自负！', '会员网公告', '所有游戏', '发布中', '2014-07-01 16:50:00', '2014-07-02 16:50:00');
INSERT INTO `notice` VALUES ('2', '为了尊重用户隐私权，系统更新了“昵称”功能，您可以在游戏大厅[用户信息一栏]设置一个喜欢的昵称，下注时座位号即显示您的昵称。新增加视频线路选择功能，如遇视频卡，请切换至一个合适的线路。新增加龙虎抽水，系统增加了对会员抽水功能，如需开启此项功能，请联系总公司操作。', '为了尊重用户隐私权，系统更新了“昵称”功能，您可以在游戏大厅[用户信息一栏]设置一个喜欢的昵称，下注时座位号即显示您的昵称。新增加视频线路选择功能，如遇视频卡，请切换至一个', '代理网公告', '所有游戏', '发布中', '2013-10-07 17:10:00', '2014-10-27 17:10:00');
INSERT INTO `notice` VALUES ('3', '近期一些不法分子利用非法手段对公司客户进行诈骗，公司财务电话不会主动联系客户，若有异常情况，请第一时间与公司核实情况！在同一局游戏里，同时下注庄和闲、龙和虎、等实现无风险投注的对打行为被各娱乐公司列为违规行为，如若发现，一律禁封帐号，不结算洗码佣金处理！', '近期一些不法分子利用非法手段对公司客户进行诈骗，公司财务电话不会主动联系客户，若有异常情况，请第一时间与公司核实情况！在同一局游戏里，同时下注庄和闲、龙和虎、等实现无风险', '会员网公告', '所有游戏', '发布中', '2013-10-20 17:10:33', '2015-08-23 02:06:00');

-- ----------------------------
-- Table structure for result
-- ----------------------------
DROP TABLE IF EXISTS `result`;
CREATE TABLE `result` (
  `rid` int(10) NOT NULL,
  `result` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of result
-- ----------------------------
INSERT INTO `result` VALUES ('0', '庄');
INSERT INTO `result` VALUES ('1', '庄 庄对');
INSERT INTO `result` VALUES ('2', '庄 闲对');
INSERT INTO `result` VALUES ('3', '庄 庄对 闲对');
INSERT INTO `result` VALUES ('4', '闲');
INSERT INTO `result` VALUES ('5', '闲 庄对');
INSERT INTO `result` VALUES ('6', '闲 闲对');
INSERT INTO `result` VALUES ('7', '闲 庄对 闲对');
INSERT INTO `result` VALUES ('8', '和');
INSERT INTO `result` VALUES ('9', '和 庄对');
INSERT INTO `result` VALUES ('10', '和 闲对');
INSERT INTO `result` VALUES ('11', '和 庄对 闲对');

-- ----------------------------
-- Table structure for round
-- ----------------------------
DROP TABLE IF EXISTS `round`;
CREATE TABLE `round` (
  `rid` int(10) NOT NULL AUTO_INCREMENT,
  `tab_id` int(10) DEFAULT NULL,
  `gameNumber` varchar(60) DEFAULT NULL,
  `gameState` int(10) DEFAULT NULL,
  `gameBoot` int(10) DEFAULT NULL,
  `roundNum` int(10) DEFAULT NULL,
  `startTime` bigint(50) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `result` int(3) DEFAULT NULL,
  PRIMARY KEY (`rid`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of round
-- ----------------------------
INSERT INTO `round` VALUES ('1', '8', '082150824210235', '0', '0', '2', '1440421355640', '2015-08-24 21:02:35', '1');
INSERT INTO `round` VALUES ('2', '8', '080150826101239', '0', '0', '0', '1440555159440', '2015-08-26 10:12:39', '0');
INSERT INTO `round` VALUES ('3', '11', '110150902151157', '0', '0', '0', '1441177917690', '2015-09-02 15:11:57', '0');
INSERT INTO `round` VALUES ('4', '11', '111150902151239', '0', '0', '1', '1441177959250', '2015-09-02 15:12:39', '4');
INSERT INTO `round` VALUES ('5', '11', '112150902151343', '0', '0', '2', '1441178023780', '2015-09-02 15:13:43', '4');
INSERT INTO `round` VALUES ('6', '11', '110150911142213', '0', '0', '0', '1441952533880', '2015-09-11 14:22:13', '0');
INSERT INTO `round` VALUES ('7', '11', '111150911142219', '0', '0', '1', '1441952539360', '2015-09-11 14:22:19', '0');
INSERT INTO `round` VALUES ('8', '11', '110150919112811', '0', '0', '0', '1442633291760', '2015-09-19 11:28:11', '0');
INSERT INTO `round` VALUES ('9', '11', '111150919112819', '0', '0', '1', '1442633299300', '2015-09-19 11:28:19', '2');
INSERT INTO `round` VALUES ('10', '11', '112150919112843', '0', '0', '2', '1442633323360', '2015-09-19 11:28:43', '8');
INSERT INTO `round` VALUES ('11', '11', '113150919112849', '0', '0', '3', '1442633329280', '2015-09-19 11:28:49', '4');
INSERT INTO `round` VALUES ('12', '11', '114150919113159', '0', '0', '4', '1442633519450', '2015-09-19 11:31:59', '3');
INSERT INTO `round` VALUES ('13', '11', '115150919113235', '2', '0', '5', '1442633555420', '2015-09-19 11:32:35', '-1');
INSERT INTO `round` VALUES ('14', '11', '116150919113853', '0', '0', '6', '1442633933700', '2015-09-19 11:38:53', '7');
INSERT INTO `round` VALUES ('15', '11', '117150919114008', '0', '0', '7', '1442634008450', '2015-09-19 11:40:08', '11');
INSERT INTO `round` VALUES ('16', '11', '118150919114019', '1', '0', '8', '1442634019700', '2015-09-19 11:40:19', '-1');
INSERT INTO `round` VALUES ('17', '11', '119150919114352', '2', '0', '9', '1442634232340', '2015-09-19 11:43:52', '-1');
INSERT INTO `round` VALUES ('18', '11', '1110150919114806', '0', '0', '10', '1442634486310', '2015-09-19 11:48:06', '3');
INSERT INTO `round` VALUES ('19', '11', '1111150919115103', '0', '0', '11', '1442634663330', '2015-09-19 11:51:03', '0');
INSERT INTO `round` VALUES ('20', '11', '1112150919115109', '0', '0', '12', '1442634669380', '2015-09-19 11:51:09', '8');
INSERT INTO `round` VALUES ('21', '11', '1113150919115228', '2', '0', '13', '1442634748520', '2015-09-19 11:52:28', '-1');
INSERT INTO `round` VALUES ('22', '11', '110150922160344', '0', '0', '0', '1442909024530', '2015-09-22 16:03:44', '0');
INSERT INTO `round` VALUES ('23', '11', '111150922162302', '0', '0', '1', '1442910182810', '2015-09-22 16:23:02', '0');
INSERT INTO `round` VALUES ('24', '11', '112150922162425', '0', '0', '2', '1442910265060', '2015-09-22 16:24:25', '0');
INSERT INTO `round` VALUES ('25', '11', '113150922162454', '2', '0', '3', '1442910294160', '2015-09-22 16:24:54', '-1');
INSERT INTO `round` VALUES ('26', '8', '080151228211737', '0', '0', '0', '1451308657550', '2015-12-28 21:17:37', '0');
INSERT INTO `round` VALUES ('27', '8', '081151228213359', '0', '0', '1', '1451309639440', '2015-12-28 21:33:59', '0');
INSERT INTO `round` VALUES ('28', '8', '082151228213445', '0', '0', '2', '1451309685600', '2015-12-28 21:34:45', '0');
INSERT INTO `round` VALUES ('29', '8', '083151228222838', '2', '0', '3', '1451312918540', '2015-12-28 22:28:38', '-1');
INSERT INTO `round` VALUES ('30', '1', '012151101163842', '1', '1', '2', '1477989522968', '2016-11-01 16:38:42', '-1');
INSERT INTO `round` VALUES ('31', '1', '013151101163848', '1', '1', '3', '1477989528087', '2016-11-01 16:38:48', '-1');
INSERT INTO `round` VALUES ('32', '1', '014151101163850', '1', '1', '4', '1477989530831', '2016-11-01 16:38:50', '-1');

-- ----------------------------
-- Table structure for status
-- ----------------------------
DROP TABLE IF EXISTS `status`;
CREATE TABLE `status` (
  `id` int(5) NOT NULL DEFAULT '0',
  `txt` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of status
-- ----------------------------
INSERT INTO `status` VALUES ('1', '1,1');
INSERT INTO `status` VALUES ('2', '8,28');
INSERT INTO `status` VALUES ('3', '0');

-- ----------------------------
-- Table structure for tablet
-- ----------------------------
DROP TABLE IF EXISTS `tablet`;
CREATE TABLE `tablet` (
  `tab_id` int(10) NOT NULL AUTO_INCREMENT,
  `injecttime` int(10) DEFAULT NULL,
  `chip` varchar(100) DEFAULT NULL,
  `gameid` varchar(10) DEFAULT NULL,
  `telMax` int(10) DEFAULT NULL,
  `telMin` int(10) DEFAULT NULL,
  `gameType` varchar(50) DEFAULT NULL,
  `gameTableName` varchar(255) DEFAULT NULL,
  `pairMax` int(10) DEFAULT NULL,
  `tieMax` int(10) DEFAULT NULL,
  `status` int(10) DEFAULT NULL,
  `diffMax` int(10) DEFAULT NULL,
  PRIMARY KEY (`tab_id`)
) ENGINE=MyISAM AUTO_INCREMENT=86 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tablet
-- ----------------------------
INSERT INTO `tablet` VALUES ('1', '40', '10,100,1000,5000,10000,20000,50000', '01', '100000', '1000', 'baijiale', 'baijiale01', '1700', '1500', '0', '3000');
INSERT INTO `tablet` VALUES ('2', '40', '10,100,500,1000,5000,10000,50000', '02', '300000', '3000', 'baijiale', 'baijiale02', '1700', '1500', '0', '3000');
INSERT INTO `tablet` VALUES ('5', '50', '10,100,500,1000,5000,10000,50000', '05', '50000', '200', 'baijiale', 'baijiale05', '1700', '1500', '0', '3000');
INSERT INTO `tablet` VALUES ('6', '40', '10,100,1000,5000,10000,20000,50000', '06', '100000', '1000', 'baijiale', 'baijiale06', '1700', '1500', '0', '3000');
INSERT INTO `tablet` VALUES ('7', '50', '10,100,500,1000,5000,10000,50000', '07', '50000', '200', 'baijiale', 'baijiale07', '1700', '1500', '0', '3000');
INSERT INTO `tablet` VALUES ('8', '40', '10,50,100,500,1000,5000', '08', '66000', '10', 'baijiale', 'baijiale08', '1700', '1500', '1', '3000');
INSERT INTO `tablet` VALUES ('9', '50', '10,50,100,500,1000,5000', '09', '66000', '10', 'baijiale', 'baijiale09', '1700', '1500', '1', '3000');
INSERT INTO `tablet` VALUES ('10', '50', '10,50,100,500,1000,5000,10000', '10', '66000', '10', 'baijiale', 'baijiale10', '1700', '1500', '1', '3000');
INSERT INTO `tablet` VALUES ('11', '50', '10,50,100,500,1000,5000', '11', '66000', '10', 'baijiale', 'baijiale11', '1700', '1500', '1', '3000');
INSERT INTO `tablet` VALUES ('12', '50', '10,50,100,500,1000,5000', '12', '66000', '10', 'baijiale', 'baijiale12', '1700', '1500', '1', '3000');
INSERT INTO `tablet` VALUES ('14', '50', '10,50,100,500,1000,5000', '14', '66000', '10', 'baijiale', 'baijiale14', '1700', '1500', '1', '3000');
INSERT INTO `tablet` VALUES ('15', '50', '10,50,100,500,1000,5000', '15', '66000', '10', 'baijiale', 'baijiale15', '1700', '1500', '1', '3000');
INSERT INTO `tablet` VALUES ('16', '50', '10,50,100,500,1000,5000', '16', '66000', '10', 'baijiale', 'baijiale16', '1700', '1500', '1', '3000');
INSERT INTO `tablet` VALUES ('17', '50', '10,50,100,500,1000,5000', '17', '66000', '10', 'baijiale', 'baijiale17', '1700', '1500', '1', '3000');
INSERT INTO `tablet` VALUES ('19', '50', '10,50,100,500,1000,5000', '19', '66000', '10', 'baijiale', 'baijiale19', '1700', '1500', '1', '3000');
INSERT INTO `tablet` VALUES ('20', '50', '10,50,100,500,1000,5000', '20', '66000', '10', 'baijiale', 'baijiale20', '1700', '1500', '1', '3000');
INSERT INTO `tablet` VALUES ('18', '50', '10,50,100,500,1000,5000', '18', '66000', '10', 'baijiale', 'baijiale18', '17000', '1500', '1', '30000');
INSERT INTO `tablet` VALUES ('22', '50', '10,100,500,1000,5000,10000,20000', '22', '30000', '100', 'baijiale', 'baijiale22', '1700', '1500', '0', '3000');
INSERT INTO `tablet` VALUES ('25', '50', '10,100,500,1000,5000,10000,20000', '25', '30000', '100', 'baijiale', 'baijiale25', '1700', '1500', '0', '3000');
INSERT INTO `tablet` VALUES ('26', '50', '10,100,500,1000,5000,10000,50000', '26', '50000', '200', 'baijiale', 'baijiale26', '1700', '1500', '0', '3000');
INSERT INTO `tablet` VALUES ('27', '50', '10,100,500,1000,5000,10000,50000', '27', '50000', '200', 'baijiale', 'baijiale27', '1700', '1500', '0', '3000');
INSERT INTO `tablet` VALUES ('28', '50', '10,100,500,1000,5000,10000,50000', '28', '50000', '200', 'baijiale', 'baijiale28', '1700', '1500', '0', '3000');
INSERT INTO `tablet` VALUES ('29', '50', '10,100,500,1000,5000,10000,50000', '29', '50000', '200', 'baijiale', 'baijiale29', '1700', '1500', '0', '3000');
INSERT INTO `tablet` VALUES ('30', '50', '10,100,500,1000,5000,10000,50000', '30', '50000', '200', 'baijiale', 'baijiale30', '1700', '1500', '0', '3000');
INSERT INTO `tablet` VALUES ('31', '50', '10,100,500,1000,5000,10000,50000', '31', '50000', '200', 'baijiale', 'baijiale31', '1700', '1500', '0', '3000');
INSERT INTO `tablet` VALUES ('32', '50', '10,100,500,1000,5000,10000,50000', '32', '50000', '200', 'baijiale', 'baijiale32', '1700', '1500', '0', '3000');
INSERT INTO `tablet` VALUES ('35', '50', '10,100,500,1000,5000,10000,50000', '35', '50000', '200', 'baijiale', 'baijiale35', '1700', '1500', '0', '3000');
INSERT INTO `tablet` VALUES ('36', '50', '10,100,500,1000,5000,10000,50000', '36', '50000', '200', 'baijiale', 'baijiale36', '1700', '1500', '0', '3000');
INSERT INTO `tablet` VALUES ('37', '50', '10,100,500,1000,5000,10000,50000', '37', '50000', '200', 'baijiale', 'baijiale37', '1700', '1500', '0', '3000');
INSERT INTO `tablet` VALUES ('38', '50', '10,100,500,1000,5000,10000,50000', '38', '50000', '200', 'baijiale', 'baijiale38', '1700', '1500', '0', '3000');
INSERT INTO `tablet` VALUES ('39', '50', '10,100,500,1000,5000,10000,50000', '39', '50000', '200', 'baijiale', 'baijiale39', '1700', '1500', '0', '3000');
INSERT INTO `tablet` VALUES ('40', '50', '10,100,500,1000,5000,10000,50000', '40', '50000', '200', 'baijiale', 'baijiale40', '1700', '1500', '0', '3000');
INSERT INTO `tablet` VALUES ('41', '50', '10,100,500,1000,5000,10000,50000', '41', '50000', '200', 'baijiale', 'baijiale41', '1700', '1500', '0', '3000');
INSERT INTO `tablet` VALUES ('65', '50', '10,100,500,1000,5000,10000,50000', '65', '50000', '200', 'baijiale', 'baijiale65', '1700', '1500', '0', '3000');
INSERT INTO `tablet` VALUES ('66', '50', '10,100,500,1000,5000,10000,50000', '66', '50000', '200', 'baijiale', 'baijiale66', '1700', '1500', '0', '3000');
INSERT INTO `tablet` VALUES ('67', '50', '10,100,500,1000,5000,10000,50000', '67', '50000', '200', 'baijiale', 'baijiale67', '1700', '1500', '0', '3000');
INSERT INTO `tablet` VALUES ('68', '50', '10,100,500,1000,5000,10000,50000', '68', '50000', '200', 'baijiale', 'baijiale68', '1700', '1500', '0', '3000');
INSERT INTO `tablet` VALUES ('69', '50', '10,100,500,1000,5000,10000,50000', '69', '50000', '200', 'baijiale', 'baijiale69', '1700', '1500', '0', '3000');
INSERT INTO `tablet` VALUES ('70', '50', '10,100,500,1000,5000,10000,50000', '70', '50000', '200', 'baijiale', 'baijiale70', '1700', '1500', '0', '3000');
INSERT INTO `tablet` VALUES ('71', '50', '10,100,500,1000,5000,10000,50000', '71', '50000', '200', 'baijiale', 'baijiale71', '1700', '1500', '0', '3000');
INSERT INTO `tablet` VALUES ('72', '50', '10,100,500,1000,5000,10000,50000', '72', '50000', '200', 'baijiale', 'baijiale72', '1700', '1500', '0', '3000');
INSERT INTO `tablet` VALUES ('75', '50', '10,100,500,1000,5000,10000,50000', '75', '50000', '200', 'baijiale', 'baijiale75', '1700', '1500', '0', '3000');
INSERT INTO `tablet` VALUES ('76', '50', '10,100,500,1000,5000,10000,50000', '76', '50000', '200', 'baijiale', 'baijiale76', '1700', '1500', '0', '3000');
INSERT INTO `tablet` VALUES ('77', '50', '10,100,500,1000,5000,10000,50000', '77', '50000', '200', 'baijiale', 'baijiale77', '1700', '1500', '0', '3000');

-- ----------------------------
-- Table structure for type
-- ----------------------------
DROP TABLE IF EXISTS `type`;
CREATE TABLE `type` (
  `tid` int(10) NOT NULL,
  `rate` decimal(20,2) DEFAULT NULL,
  `chou` decimal(20,2) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of type
-- ----------------------------
INSERT INTO `type` VALUES ('1', '0.95', '0.05', '庄');
INSERT INTO `type` VALUES ('2', '1.00', '0.00', '闲');
INSERT INTO `type` VALUES ('3', '8.00', '0.00', '和');
INSERT INTO `type` VALUES ('4', '11.00', '0.00', '庄对');
INSERT INTO `type` VALUES ('5', '11.00', '0.00', '闲对');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `nickName` varchar(30) DEFAULT NULL,
  `tel` varchar(20) DEFAULT NULL,
  `money` double(30,2) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `currentmoney` double(30,2) DEFAULT NULL,
  `tableid` int(20) DEFAULT '0',
  `ip` varchar(100) DEFAULT NULL,
  `type` int(4) DEFAULT NULL,
  `pid` int(20) DEFAULT NULL,
  `logintimes` int(50) DEFAULT '0',
  `lastlogintime` datetime DEFAULT NULL,
  `memo` text,
  `createdate` datetime DEFAULT NULL,
  `xima` double(100,2) DEFAULT '0.00',
  `zhancheng` double(100,2) DEFAULT '0.00',
  `danshuangbian` int(2) NOT NULL DEFAULT '1',
  `currentxima` double(30,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', '超级管理员', '13579468241', '10000.00', 'e10adc3949ba59abbe56e057f20f883e', 'admin', '10000.00', '0', '127.0.0.1', '0', '0', '552', '2016-11-01 19:50:22', '111122333', '2015-01-05 15:02:04', '0.08', '0.00', '1', '0.00');
INSERT INTO `user` VALUES ('2', '王八蛋', '13735482433', '20000.00', '96e79218965eb72c92a549dd5a330112', 'gulu', '1136360917.00', '0', '127.0.0.1', '1', '1', '112', '2016-11-01 20:50:08', '晚上7点，湘妃楼王总酒局111333', '2015-01-05 15:02:04', '0.80', '0.00', '2', '-1000.00');
INSERT INTO `user` VALUES ('3', '关羽', '13456324258', '19927.00', 'e10adc3949ba59abbe56e057f20f883e', 'gulumu', '1016734.50', '0', '127.0.0.1', '2', '2', '454', '2015-11-30 15:36:05', null, '2015-01-05 15:02:04', '0.08', '0.00', '1', '0.00');
INSERT INTO `user` VALUES ('4', '小宝', null, '6000.00', 'e10adc3949ba59abbe56e057f20f883e', 'gulugu', '140620.00', '0', '152.124.124.132', '3', '2', '0', '2015-12-28 22:36:07', null, '2015-08-01 11:50:57', '1.80', '0.00', '0', '40000.00');
INSERT INTO `user` VALUES ('5', 'gggggu', null, '4000.00', 'e10adc3949ba59abbe56e057f20f883e', 'gulugulu', '4000.00', '8', '152.124.124.132', '2', '2', '0', '2015-11-30 15:44:54', null, '2015-08-06 15:34:10', '0.01', '0.00', '1', '0.00');
INSERT INTO `user` VALUES ('6', 'pkkk', null, '4000.00', 'e10adc3949ba59abbe56e057f20f883e', 'gulu1', '1015850.00', '8', '152.124.124.132', '3', '2', '0', '2015-11-30 15:44:54', null, '2015-08-11 21:27:52', '0.00', '0.00', '1', '0.00');
INSERT INTO `user` VALUES ('7', '', null, '5000.00', 'e10adc3949ba59abbe56e057f20f883e', 'gulu2', '954500.00', '8', '152.124.124.132', '3', '2', '0', '2015-11-30 15:44:54', null, '2015-08-11 21:30:24', '0.00', '0.00', '1', '0.00');
INSERT INTO `user` VALUES ('8', '12', null, '10000.00', 'e10adc3949ba59abbe56e057f20f883e', 'gulu3', '1015000.00', '0', '152.124.124.132', '3', '2', '0', '2015-11-30 15:44:54', null, '2015-08-12 14:44:44', '0.00', '0.00', '1', '0.00');
INSERT INTO `user` VALUES ('9', '', null, '10000.00', 'e10adc3949ba59abbe56e057f20f883e', 'gulu4', '1036000.00', '0', '152.124.124.132', '3', '2', '0', '2015-11-30 15:44:54', null, '2015-08-12 14:44:56', '0.00', '0.00', '1', '0.00');
INSERT INTO `user` VALUES ('10', '', null, '10000.00', 'e10adc3949ba59abbe56e057f20f883e', 'gulu5', '1017000.00', '0', '152.124.124.132', '3', '2', '0', '2015-11-30 15:44:54', null, '2015-08-12 14:45:10', '0.00', '0.00', '1', '0.00');
INSERT INTO `user` VALUES ('11', '', null, '10000.00', 'e10adc3949ba59abbe56e057f20f883e', 'gulu6', '1000000.00', '0', '152.124.124.132', '3', '2', '0', '2015-11-30 15:44:54', null, '2015-08-12 14:45:21', '0.00', '0.00', '1', '0.00');
INSERT INTO `user` VALUES ('12', '', null, '1000.00', 'e10adc3949ba59abbe56e057f20f883e', 'gulu7', '1000000.00', '0', '152.124.124.132', '3', '2', '0', '2015-11-30 15:44:54', null, '2015-08-12 14:45:46', '0.10', '0.00', '1', '0.00');
INSERT INTO `user` VALUES ('13', '', '', '1000.00', 'e10adc3949ba59abbe56e057f20f883e', 'gulu8', '1000000.00', '0', '152.124.124.132', '3', '2', '0', '2015-11-30 15:44:54', '', '2015-08-12 14:45:46', '0.10', '0.00', '1', '0.00');
INSERT INTO `user` VALUES ('14', '', '', '1000.00', 'e10adc3949ba59abbe56e057f20f883e', 'gulu9', '1000000.00', '0', '152.124.124.132', '3', '2', '0', '2015-11-30 15:44:54', '', '2015-08-12 14:45:46', '0.10', '0.00', '1', '0.00');
INSERT INTO `user` VALUES ('15', '', '', '1000.00', 'e10adc3949ba59abbe56e057f20f883e', 'gulu10', '1000000.00', '0', '152.124.124.132', '3', '2', '0', '2015-11-30 15:44:54', '', '2015-08-12 14:45:46', '0.10', '0.00', '1', '0.00');
INSERT INTO `user` VALUES ('16', '', '', '1000.00', 'e10adc3949ba59abbe56e057f20f883e', 'gulu11', '1000000.00', '0', '152.124.124.132', '3', '2', '0', '2015-11-30 15:44:54', '', '2015-08-12 14:45:46', '0.10', '0.00', '1', '0.00');
INSERT INTO `user` VALUES ('17', '', '', '1000.00', 'e10adc3949ba59abbe56e057f20f883e', 'gulu12', '1000000.00', '0', '152.124.124.132', '3', '2', '0', '2015-11-30 15:44:54', '', '2015-08-12 14:45:46', '0.10', '0.00', '1', '0.00');
INSERT INTO `user` VALUES ('18', '', '', '1000.00', 'e10adc3949ba59abbe56e057f20f883e', 'gulu13', '1000000.00', '0', '152.124.124.132', '3', '2', '0', '2015-11-30 15:44:54', '', '2015-08-12 14:45:46', '0.10', '0.00', '1', '0.00');
INSERT INTO `user` VALUES ('19', '', '', '1000.00', 'e10adc3949ba59abbe56e057f20f883e', 'gulu14', '1000000.00', '10', '152.124.124.132', '3', '2', '0', '2015-09-23 16:17:00', '', '2015-08-12 14:45:46', '0.10', '0.00', '1', '0.00');
INSERT INTO `user` VALUES ('20', '', '', '1000.00', 'e10adc3949ba59abbe56e057f20f883e', 'gulu15', '1000000.00', '0', '152.124.124.132', '3', '2', '0', '2015-11-30 15:44:54', '', '2015-08-12 14:45:46', '0.10', '0.00', '1', '0.00');
INSERT INTO `user` VALUES ('21', '', '', '1000.00', 'e10adc3949ba59abbe56e057f20f883e', 'gulu16', '1000000.00', '0', '152.124.124.132', '3', '2', '0', '2015-11-30 15:44:54', '', '2015-08-12 14:45:46', '0.10', '0.00', '1', '0.00');
INSERT INTO `user` VALUES ('22', '', '', '1000.00', 'e10adc3949ba59abbe56e057f20f883e', 'gulu17', '1000000.00', '0', '152.124.124.132', '3', '2', '0', '2015-11-30 15:44:54', '', '2015-08-12 14:45:46', '0.10', '0.00', '1', '0.00');
INSERT INTO `user` VALUES ('23', '', '', '1000.00', 'e10adc3949ba59abbe56e057f20f883e', 'gulu18', '1000000.00', '0', '152.124.124.132', '3', '2', '0', '2015-11-30 15:44:54', '', '2015-08-12 14:45:46', '0.10', '0.00', '1', '0.00');
INSERT INTO `user` VALUES ('24', '', '', '1000.00', 'e10adc3949ba59abbe56e057f20f883e', 'gulu19', '1000000.00', '0', '152.124.124.132', '3', '2', '0', '2015-11-30 15:44:54', '', '2015-08-12 14:45:46', '0.10', '0.00', '1', '0.00');
INSERT INTO `user` VALUES ('25', '', '', '1000.00', 'e10adc3949ba59abbe56e057f20f883e', 'gulu20', '1000000.00', '0', '152.124.124.132', '3', '2', '0', '2015-11-30 15:44:54', '', '2015-08-12 14:45:46', '0.10', '0.00', '1', '0.00');
INSERT INTO `user` VALUES ('26', '', '', '1000.00', 'e10adc3949ba59abbe56e057f20f883e', 'gulu21', '1000000.00', '0', '152.124.124.132', '3', '2', '0', '2015-11-30 15:44:54', '', '2015-08-12 14:45:46', '0.10', '0.00', '1', '0.00');
INSERT INTO `user` VALUES ('37', '', null, '1000000.00', 'e10adc3949ba59abbe56e057f20f883e', 'gugugudd', '1000000.00', '0', '152.124.124.132', '1', '1', '0', null, null, '2015-09-06 23:10:17', '1.80', '0.00', '0', '0.00');
INSERT INTO `user` VALUES ('34', '', null, '500.00', 'e10adc3949ba59abbe56e057f20f883e', 'wangba', '500.00', '0', '152.124.124.132', '2', '2', '0', null, null, '2015-08-22 17:15:03', '1.50', '0.00', '1', '0.00');
INSERT INTO `user` VALUES ('35', 'gulu444', null, '5000.00', 'e10adc3949ba59abbe56e057f20f883e', 'jilu', '1010000.00', '0', '152.124.124.132', '3', '3', '0', null, null, '2015-08-22 17:16:43', '0.90', '0.00', '1', '0.00');
INSERT INTO `user` VALUES ('36', '王八蛋', '13735482411', '20000.00', 'e10adc3949ba59abbe56e057f20f883e', 'ggu', '1002700.00', '0', '127.0.0.1', '1', '1', '90', '2015-09-22 09:37:45', '晚上7点，湘妃楼王总酒局', '2015-01-05 15:02:04', '0.80', '0.00', '2', '0.00');
INSERT INTO `user` VALUES ('38', null, null, null, null, null, null, '0', null, null, null, '0', null, null, null, '0.00', '0.00', '1', '0.00');
INSERT INTO `user` VALUES ('39', '123456', null, '0.00', 'e10adc3949ba59abbe56e057f20f883e', 'du', '9990.00', '8', '152.124.124.132', '2', '36', '0', '2015-11-30 15:44:54', null, '2015-09-22 10:04:13', '1.80', '0.00', '1', '0.00');
INSERT INTO `user` VALUES ('40', '', null, '1111117.00', 'e10adc3949ba59abbe56e057f20f883e', 'abcdddd', '1111117.00', '0', '152.124.124.132', '3', '2', '0', '2015-09-22 10:44:54', null, '2015-09-22 10:18:14', '1.80', '0.00', '1', '0.00');
INSERT INTO `user` VALUES ('43', '', null, '10000000.00', 'e10adc3949ba59abbe56e057f20f883e', 'gulupp', '10000000.00', '0', '152.124.124.132', '3', '2', '0', null, null, '2015-11-20 13:30:36', '0.95', '0.00', '0', '0.00');
INSERT INTO `user` VALUES ('41', '', null, '10000.00', 'd41d8cd98f00b204e9800998ecf8427e', 'fsdafasdkf', '10000.00', '8', '152.124.124.132', '0', '4', '0', '2015-11-30 15:44:54', null, '2015-09-22 14:06:14', '0.00', '0.00', '0', '0.00');
INSERT INTO `user` VALUES ('45', '大大', null, '33.00', '96e79218965eb72c92a549dd5a330112', 'dddd', '33.00', '0', '152.124.124.132', '2', '2', '0', null, null, '2016-11-01 21:35:15', '1.80', '0.00', '1', '0.00');

-- ----------------------------
-- Table structure for useraddition
-- ----------------------------
DROP TABLE IF EXISTS `useraddition`;
CREATE TABLE `useraddition` (
  `uid` int(20) NOT NULL,
  `currentmoney` float(20,2) NOT NULL,
  `tableid` int(20) DEFAULT NULL,
  `ip` varchar(100) DEFAULT NULL,
  `logintimes` int(50) DEFAULT NULL,
  `lastlogintime` datetime DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of useraddition
-- ----------------------------

-- ----------------------------
-- Table structure for ximalog
-- ----------------------------
DROP TABLE IF EXISTS `ximalog`;
CREATE TABLE `ximalog` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) DEFAULT NULL,
  `createdate` datetime DEFAULT NULL,
  `settledxm` float(30,0) DEFAULT NULL,
  `settledyj` float(30,0) DEFAULT NULL,
  `unsettledxm` float(30,0) DEFAULT NULL,
  `unsettledyj` float(30,0) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `shangji` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ximalog
-- ----------------------------
