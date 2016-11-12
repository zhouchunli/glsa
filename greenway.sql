/*
Navicat MySQL Data Transfer

Source Server         : Charlienew
Source Server Version : 50627
Source Host           : 123.59.95.67:3306
Source Database       : greenway

Target Server Type    : MYSQL
Target Server Version : 50627
File Encoding         : 65001

Date: 2016-11-08 15:34:34
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `user_id` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `power` varchar(255) DEFAULT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=88888889 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES ('1', '后台管理员1', 'admin1', '14e1b600b1fd579f47433b88e8d85291', '1,2,3,4,5,6,7', '2016-11-04 05:16:14', '2016-10-11 13:19:48');
INSERT INTO `admin` VALUES ('2', '后台管理员2', 'admin2', '14e1b600b1fd579f47433b88e8d85291', '1,2,3,4,5', '2016-10-11 13:19:45', '2016-10-11 13:19:48');
INSERT INTO `admin` VALUES ('3', '后台管理员3', 'admin3', '14e1b600b1fd579f47433b88e8d85291', '2,3,4,5,7', '2016-10-17 08:48:21', '2016-10-11 13:19:48');
INSERT INTO `admin` VALUES ('4', '后台管理员4', 'admin4', '14e1b600b1fd579f47433b88e8d85291', '', '2016-10-17 08:32:30', '2016-10-11 13:19:48');
INSERT INTO `admin` VALUES ('5', '后台管理员5', 'admin5', '14e1b600b1fd579f47433b88e8d85291', '1,2,3,4,5', '2016-10-11 13:19:48', '2016-10-11 13:19:48');
INSERT INTO `admin` VALUES ('6', '后台管理员6', 'admin6', '14e1b600b1fd579f47433b88e8d85291', '1,2,3,4,5', '2016-10-11 13:19:48', '2016-10-11 13:19:48');
INSERT INTO `admin` VALUES ('88888888', '超级管理员', 'adminsp', '14e1b600b1fd579f47433b88e8d85291', '1,2,3,4,5,6,7', '2016-10-28 08:50:25', '2016-10-11 13:19:48');

-- ----------------------------
-- Table structure for camp
-- ----------------------------
DROP TABLE IF EXISTS `camp`;
CREATE TABLE `camp` (
  `c_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '营地表',
  `country_id` int(10) DEFAULT NULL COMMENT '所属国家ID',
  `c_name` varchar(255) DEFAULT NULL COMMENT '营地名称',
  `opentime` varchar(255) DEFAULT NULL COMMENT '开放时间',
  `subtitle` varchar(255) DEFAULT NULL COMMENT '列表页副标题',
  `describe` text COMMENT '列表页描述',
  `remark` text COMMENT '营地简介',
  `climate` text COMMENT '气候',
  `flight` text COMMENT '航班',
  `food` text COMMENT '餐饮',
  `periphery` text COMMENT '周边',
  `bannerurl` varchar(255) DEFAULT NULL COMMENT '头部图片地址',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`c_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of camp
-- ----------------------------

-- ----------------------------
-- Table structure for camp_project
-- ----------------------------
DROP TABLE IF EXISTS `camp_project`;
CREATE TABLE `camp_project` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '营地所属项目表',
  `c_id` int(10) DEFAULT NULL COMMENT '营地ID',
  `p_name` varchar(255) DEFAULT NULL COMMENT '项目名称',
  `P_remarkvt` text COMMENT '项目简介',
  `img_pic` int(10) DEFAULT NULL COMMENT 'banner图文件号',
  `Price` double DEFAULT NULL COMMENT '单价',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of camp_project
-- ----------------------------

-- ----------------------------
-- Table structure for competence_screen
-- ----------------------------
DROP TABLE IF EXISTS `competence_screen`;
CREATE TABLE `competence_screen` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '资格筛查表',
  `uid` int(10) DEFAULT NULL COMMENT '用户关联表',
  `english` tinyint(2) DEFAULT NULL COMMENT '英语水平',
  `food` tinyint(1) DEFAULT NULL COMMENT '饮食要求',
  `disease` tinyint(1) DEFAULT NULL COMMENT '疾病有无',
  `oneabroad` tinyint(1) DEFAULT NULL COMMENT '第一次出国否',
  `remark` text COMMENT '备注',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of competence_screen
-- ----------------------------

-- ----------------------------
-- Table structure for continent
-- ----------------------------
DROP TABLE IF EXISTS `continent`;
CREATE TABLE `continent` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '洲分类表',
  `calss_name` varchar(255) DEFAULT NULL COMMENT '洲名称',
  `class_state` tinyint(1) DEFAULT NULL COMMENT '洲状态',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of continent
-- ----------------------------

-- ----------------------------
-- Table structure for country
-- ----------------------------
DROP TABLE IF EXISTS `country`;
CREATE TABLE `country` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '国家表',
  `continent_id` int(10) DEFAULT NULL COMMENT '所属洲ID',
  `name` varchar(255) DEFAULT NULL COMMENT '国家名',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of country
-- ----------------------------

-- ----------------------------
-- Table structure for expenses
-- ----------------------------
DROP TABLE IF EXISTS `expenses`;
CREATE TABLE `expenses` (
  `e_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '包含与不包含费用表',
  `belong` int(10) DEFAULT NULL COMMENT '所属项目',
  `nexus` tinyint(1) DEFAULT '1' COMMENT '1为费用包含，2为费用不包含',
  `content` text COMMENT '说明内容',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`e_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of expenses
-- ----------------------------

-- ----------------------------
-- Table structure for material
-- ----------------------------
DROP TABLE IF EXISTS `material`;
CREATE TABLE `material` (
  `m_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '物资说明表',
  `belong` int(10) DEFAULT NULL COMMENT '所属项目',
  `title` varchar(255) DEFAULT NULL COMMENT '物资栏目标题',
  `content` text COMMENT '内容',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`m_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of material
-- ----------------------------

-- ----------------------------
-- Table structure for menu
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `lv` int(2) DEFAULT NULL,
  `group` int(11) DEFAULT '0',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of menu
-- ----------------------------
INSERT INTO `menu` VALUES ('1', '用户管理', 'users', '1', '0', null, null);
INSERT INTO `menu` VALUES ('2', '达人列表', 'master', '1', '0', null, null);
INSERT INTO `menu` VALUES ('3', '举报管理', 'accusation', '1', '0', null, null);
INSERT INTO `menu` VALUES ('4', '问答管理', 'qanda', '1', '0', null, null);
INSERT INTO `menu` VALUES ('5', '运营数据统计', 'operate', '1', '0', null, null);
INSERT INTO `menu` VALUES ('6', '标签管理', 'labels', '1', '0', null, null);
INSERT INTO `menu` VALUES ('7', '广告位', 'advert', '1', '0', null, null);

-- ----------------------------
-- Table structure for other
-- ----------------------------
DROP TABLE IF EXISTS `other`;
CREATE TABLE `other` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '其他表，包含其他说明类文字',
  `security` text COMMENT '安全须知',
  `flight` text COMMENT '航班信息',
  `visa` text COMMENT '签证说明',
  `currency` text COMMENT '货币说明',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of other
-- ----------------------------

-- ----------------------------
-- Table structure for trip
-- ----------------------------
DROP TABLE IF EXISTS `trip`;
CREATE TABLE `trip` (
  `t_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '行程管理表',
  `t_name` varchar(255) DEFAULT NULL COMMENT '行程名称',
  `belong` int(10) DEFAULT NULL COMMENT '所属项目',
  `introduction` text COMMENT '简介',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`t_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of trip
-- ----------------------------

-- ----------------------------
-- Table structure for trip_time
-- ----------------------------
DROP TABLE IF EXISTS `trip_time`;
CREATE TABLE `trip_time` (
  `tt_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '行程当日时间事务表',
  `belong` int(10) DEFAULT NULL COMMENT '所属行程编号',
  `time` varchar(255) DEFAULT NULL COMMENT '时间段',
  `text` varchar(255) DEFAULT NULL COMMENT '活动内容',
  `uodated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`tt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of trip_time
-- ----------------------------

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '用户表',
  `open_id` varchar(255) DEFAULT NULL COMMENT '微信',
  `phone` int(11) DEFAULT NULL COMMENT '手机号',
  `weixinid` varchar(255) DEFAULT NULL COMMENT '微信号',
  `loginname` varchar(255) DEFAULT NULL COMMENT '登陆账号',
  `password` varchar(255) DEFAULT NULL COMMENT '密码',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------

-- ----------------------------
-- Table structure for user_applicant
-- ----------------------------
DROP TABLE IF EXISTS `user_applicant`;
CREATE TABLE `user_applicant` (
  `id` int(10) DEFAULT NULL COMMENT '常用联系人表',
  `uid` int(10) DEFAULT NULL COMMENT '关联用户名',
  `ua_name` varchar(255) DEFAULT NULL COMMENT '常用申请人姓名',
  `ua_phone` int(11) DEFAULT NULL COMMENT '手机号',
  `ua_idcard` varchar(100) DEFAULT NULL COMMENT '身份证',
  `credentials` varchar(255) DEFAULT NULL COMMENT '证件号',
  `weixinid` varchar(255) DEFAULT NULL COMMENT '微信号',
  `ua_email` varchar(255) DEFAULT NULL COMMENT '联系人邮箱',
  `passport` varchar(255) DEFAULT NULL COMMENT '护照号码',
  `ua_address` varchar(255) DEFAULT NULL COMMENT '联系人地址',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_applicant
-- ----------------------------

-- ----------------------------
-- Table structure for user_emergency
-- ----------------------------
DROP TABLE IF EXISTS `user_emergency`;
CREATE TABLE `user_emergency` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '紧急联络人表',
  `uid` int(10) DEFAULT NULL COMMENT '关联用户id',
  `ue_name` varchar(255) DEFAULT NULL COMMENT '紧急联络人姓名',
  `ue_phone` int(11) DEFAULT NULL COMMENT '手机',
  `ue_address` varchar(255) DEFAULT NULL COMMENT '详细地址',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_emergency
-- ----------------------------
