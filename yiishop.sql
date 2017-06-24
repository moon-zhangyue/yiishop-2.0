/*
Navicat MySQL Data Transfer

Source Server         : lnmp
Source Server Version : 50716
Source Host           : localhost:3306
Source Database       : yiishop

Target Server Type    : MYSQL
Target Server Version : 50716
File Encoding         : 65001

Date: 2017-06-24 17:51:57
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for shop_address
-- ----------------------------
DROP TABLE IF EXISTS `shop_address`;
CREATE TABLE `shop_address` (
  `addressid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `firstname` varchar(32) NOT NULL DEFAULT '' COMMENT '名',
  `lastname` varchar(32) NOT NULL DEFAULT '' COMMENT '姓',
  `company` varchar(100) NOT NULL DEFAULT '' COMMENT '公司',
  `address` text COMMENT '收货地址',
  `postcode` char(6) NOT NULL DEFAULT '' COMMENT '邮编地址',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `telephone` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号',
  `userid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`addressid`),
  KEY `shop_address_userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='买家信息表';

-- ----------------------------
-- Records of shop_address
-- ----------------------------
INSERT INTO `shop_address` VALUES ('1', '张', '哈哈', '', '海淀区大马路', '10010', '5758774@qq.com', '12345678921', '2', '0');
INSERT INTO `shop_address` VALUES ('3', '小', '对对对', '', '达瓦达瓦多哇达瓦达瓦大王', '123456', '654789@qq.com', '13899775544', '2', '0');

-- ----------------------------
-- Table structure for shop_admin
-- ----------------------------
DROP TABLE IF EXISTS `shop_admin`;
CREATE TABLE `shop_admin` (
  `adminid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `adminuser` varchar(32) NOT NULL DEFAULT '' COMMENT '管理员账号',
  `adminpass` char(32) NOT NULL DEFAULT '' COMMENT '管理员密码',
  `adminemail` varchar(50) NOT NULL DEFAULT '' COMMENT '管理员电子邮箱',
  `logintime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录时间',
  `loginip` bigint(20) NOT NULL DEFAULT '0' COMMENT '登录IP',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`adminid`),
  UNIQUE KEY `shop_admin_adminuser_adminpass` (`adminuser`,`adminpass`),
  UNIQUE KEY `shop_admin_adminuser_adminemail` (`adminuser`,`adminemail`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_admin
-- ----------------------------
INSERT INTO `shop_admin` VALUES ('1', 'admin', '698d51a19d8a121ce581499d7b701668', '18500773033@163.com', '1498099356', '2130706433', '1496543922');
INSERT INTO `shop_admin` VALUES ('2', 'moon', 'e10adc3949ba59abbe56e057f20f883e', '575865770@qq.com', '1497271153', '2130706433', '0');
INSERT INTO `shop_admin` VALUES ('3', 'haha', '698d51a19d8a121ce581499d7b701668', '575865770@163.com', '0', '0', '0');
INSERT INTO `shop_admin` VALUES ('4', 'dudu', '698d51a19d8a121ce581499d7b701668', '123@qq.com', '0', '0', '0');
INSERT INTO `shop_admin` VALUES ('5', 'lala', '698d51a19d8a121ce581499d7b701668', '1233@qq.com', '0', '0', '0');

-- ----------------------------
-- Table structure for shop_cart
-- ----------------------------
DROP TABLE IF EXISTS `shop_cart`;
CREATE TABLE `shop_cart` (
  `cartid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `productid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `productnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品数量',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品单价',
  `userid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '买家id',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`cartid`),
  KEY `shop_cart_productid` (`productid`),
  KEY `shop_cart_userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_cart
-- ----------------------------

-- ----------------------------
-- Table structure for shop_category
-- ----------------------------
DROP TABLE IF EXISTS `shop_category`;
CREATE TABLE `shop_category` (
  `cateid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '名称',
  `parentid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '父级id',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`cateid`),
  KEY `shop_category_parentid` (`parentid`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='商品分类表';

-- ----------------------------
-- Records of shop_category
-- ----------------------------
INSERT INTO `shop_category` VALUES ('1', '服装', '0', '1497169241');
INSERT INTO `shop_category` VALUES ('2', '电子', '0', '1497169251');
INSERT INTO `shop_category` VALUES ('3', '食物', '0', '1497169264');
INSERT INTO `shop_category` VALUES ('4', '家具', '0', '1497169281');
INSERT INTO `shop_category` VALUES ('6', '上衣', '1', '1497169281');
INSERT INTO `shop_category` VALUES ('7', '裤子', '1', '1497169281');
INSERT INTO `shop_category` VALUES ('8', '手机', '2', '1497169281');
INSERT INTO `shop_category` VALUES ('9', '电脑', '2', '1497169281');
INSERT INTO `shop_category` VALUES ('10', '水果', '3', '1497169281');
INSERT INTO `shop_category` VALUES ('14', '电视', '2', '1497172413');
INSERT INTO `shop_category` VALUES ('17', '李宁', '6', '1497172608');

-- ----------------------------
-- Table structure for shop_order
-- ----------------------------
DROP TABLE IF EXISTS `shop_order`;
CREATE TABLE `shop_order` (
  `orderid` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单id',
  `userid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '下单人id',
  `addressid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '地址id',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单总价',
  `status` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单状态',
  `expressid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '快递方式',
  `expressno` varchar(50) NOT NULL DEFAULT '' COMMENT '快递号',
  `tradeno` varchar(100) NOT NULL DEFAULT '' COMMENT '支付号',
  `tradetext` text COMMENT '订单详细信息',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`orderid`),
  KEY `shop_order_userid` (`userid`),
  KEY `shop_order_addressid` (`addressid`),
  KEY `shop_order_expressid` (`expressid`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8 COMMENT='订单表';

-- ----------------------------
-- Records of shop_order
-- ----------------------------
INSERT INTO `shop_order` VALUES ('22', '2', '0', '0.00', '100', '0', '', '', null, '1497601944', '2017-06-20 14:05:19');
INSERT INTO `shop_order` VALUES ('24', '2', '0', '0.00', '0', '0', '', '', null, '1497602121', '2017-06-16 16:35:21');
INSERT INTO `shop_order` VALUES ('25', '2', '0', '0.00', '220', '0', '1234567890', '', null, '1497602175', '2017-06-20 14:25:41');
INSERT INTO `shop_order` VALUES ('27', '2', '0', '0.00', '100', '0', '', '', null, '1497602443', '2017-06-20 11:43:15');
INSERT INTO `shop_order` VALUES ('28', '2', '0', '0.00', '220', '0', '', '', null, '1497602485', '2017-06-20 11:43:21');
INSERT INTO `shop_order` VALUES ('39', '2', '0', '0.00', '202', '0', '', '', null, '1497603926', '2017-06-20 14:07:17');
INSERT INTO `shop_order` VALUES ('44', '2', '0', '0.00', '201', '0', '', '', null, '1497605252', '2017-06-20 11:43:33');
INSERT INTO `shop_order` VALUES ('48', '2', '0', '0.00', '260', '0', '', '', null, '1497605474', '2017-06-20 11:43:38');
INSERT INTO `shop_order` VALUES ('49', '2', '0', '0.00', '0', '0', '', '', null, '1497765725', '2017-06-18 14:02:05');
INSERT INTO `shop_order` VALUES ('50', '2', '0', '0.00', '100', '0', '', '', null, '1497766727', '2017-06-20 14:04:31');
INSERT INTO `shop_order` VALUES ('51', '2', '0', '0.00', '100', '0', '', '', null, '1497766738', '2017-06-20 14:04:34');
INSERT INTO `shop_order` VALUES ('52', '2', '0', '0.00', '220', '0', '', '', null, '1497767950', '2017-06-20 14:06:44');
INSERT INTO `shop_order` VALUES ('53', '2', '3', '90.00', '202', '3', '', '', null, '1497773726', '2017-06-20 14:07:14');
INSERT INTO `shop_order` VALUES ('54', '2', '0', '0.00', '201', '0', '', '', null, '1497774780', '2017-06-20 11:42:42');
INSERT INTO `shop_order` VALUES ('55', '2', '3', '0.10', '100', '3', '', '', null, '1497788317', '2017-06-18 21:56:24');
INSERT INTO `shop_order` VALUES ('56', '2', '3', '0.10', '100', '3', '', '', null, '1497837022', '2017-06-19 09:50:25');
INSERT INTO `shop_order` VALUES ('57', '2', '0', '0.00', '260', '0', '', '', null, '1497837141', '2017-06-20 17:10:29');
INSERT INTO `shop_order` VALUES ('58', '2', '3', '0.10', '100', '3', '', '', null, '1497839751', '2017-06-19 10:36:42');
INSERT INTO `shop_order` VALUES ('59', '2', '3', '2020.00', '100', '2', '', '', null, '1497947476', '2017-06-20 16:31:21');
INSERT INTO `shop_order` VALUES ('60', '2', '3', '2020.00', '100', '2', '', '', null, '1498104696', '2017-06-22 12:11:39');

-- ----------------------------
-- Table structure for shop_order_detail
-- ----------------------------
DROP TABLE IF EXISTS `shop_order_detail`;
CREATE TABLE `shop_order_detail` (
  `detailid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `productid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '单价',
  `productnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单商品数量',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单id',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`detailid`),
  KEY `shop_order_detail_productid` (`productid`),
  KEY `shop_order_detail_orderid` (`orderid`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COMMENT='订单详情表';

-- ----------------------------
-- Records of shop_order_detail
-- ----------------------------
INSERT INTO `shop_order_detail` VALUES ('1', '1', '90.00', '2', '22', '1497601944');
INSERT INTO `shop_order_detail` VALUES ('2', '1', '90.00', '2', '24', '1497602121');
INSERT INTO `shop_order_detail` VALUES ('3', '1', '90.00', '1', '25', '1497602175');
INSERT INTO `shop_order_detail` VALUES ('4', '1', '90.00', '3', '27', '1497602443');
INSERT INTO `shop_order_detail` VALUES ('5', '3', '2000.00', '1', '28', '1497602485');
INSERT INTO `shop_order_detail` VALUES ('12', '5', '11.00', '2', '39', '1497603926');
INSERT INTO `shop_order_detail` VALUES ('17', '5', '11.00', '1', '44', '1497605252');
INSERT INTO `shop_order_detail` VALUES ('18', '5', '11.00', '1', '48', '1497605474');
INSERT INTO `shop_order_detail` VALUES ('19', '1', '90.00', '1', '49', '1497765725');
INSERT INTO `shop_order_detail` VALUES ('20', '1', '90.00', '1', '50', '1497766727');
INSERT INTO `shop_order_detail` VALUES ('21', '3', '2000.00', '1', '51', '1497766738');
INSERT INTO `shop_order_detail` VALUES ('22', '3', '2000.00', '1', '52', '1497767950');
INSERT INTO `shop_order_detail` VALUES ('23', '1', '90.00', '1', '53', '1497773726');
INSERT INTO `shop_order_detail` VALUES ('24', '1', '90.00', '1', '54', '1497774780');
INSERT INTO `shop_order_detail` VALUES ('25', '5', '0.10', '1', '55', '1497788317');
INSERT INTO `shop_order_detail` VALUES ('26', '5', '0.10', '1', '56', '1497837022');
INSERT INTO `shop_order_detail` VALUES ('27', '5', '0.10', '1', '57', '1497837141');
INSERT INTO `shop_order_detail` VALUES ('28', '5', '0.10', '1', '58', '1497839751');
INSERT INTO `shop_order_detail` VALUES ('29', '3', '2000.00', '1', '59', '1497947476');
INSERT INTO `shop_order_detail` VALUES ('30', '3', '2000.00', '1', '60', '1498104696');

-- ----------------------------
-- Table structure for shop_product
-- ----------------------------
DROP TABLE IF EXISTS `shop_product`;
CREATE TABLE `shop_product` (
  `productid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cateid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `title` varchar(200) NOT NULL DEFAULT '' COMMENT '标题',
  `descr` text COMMENT '描述',
  `num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '库存',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '售价',
  `cover` varchar(200) NOT NULL DEFAULT '' COMMENT '封面图片',
  `pics` text COMMENT '内容图片',
  `issale` enum('0','1') NOT NULL DEFAULT '0' COMMENT '是否促销 0否 1促销',
  `ishot` enum('0','1') NOT NULL DEFAULT '0' COMMENT '是否热销  0否 1是',
  `istui` enum('0','1') NOT NULL DEFAULT '0',
  `saleprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '促销价格',
  `ison` enum('0','1') NOT NULL DEFAULT '1' COMMENT '是否上架  1 是',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`productid`),
  KEY `shop_product_cateid` (`cateid`),
  KEY `shop_product_ison` (`ison`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_product
-- ----------------------------
INSERT INTO `shop_product` VALUES ('1', '17', '运动裤', '挺好的裤子', '198', '100.00', 'o7zgluxwg.bkt.clouddn.com/5940cc227929c', '{\"5940cc2294823\":\"o7zgluxwg.bkt.clouddn.com\\/5940cc2294823\",\"5940cc22a5997\":\"o7zgluxwg.bkt.clouddn.com\\/5940cc22a5997\",\"5940cc22abf28\":\"o7zgluxwg.bkt.clouddn.com\\/5940cc22abf28\",\"5940cc22b5783\":\"o7zgluxwg.bkt.clouddn.com\\/5940cc22b5783\"}', '1', '0', '1', '90.00', '1', '0');
INSERT INTO `shop_product` VALUES ('3', '9', '神船', '必沉啊 哈哈哈', '126', '2000.00', 'o7zgluxwg.bkt.clouddn.com/5940ebbc65519', '{\"5940ebbcdb7d5\":\"o7zgluxwg.bkt.clouddn.com\\/5940ebbcdb7d5\",\"5940ebbced8e9\":\"o7zgluxwg.bkt.clouddn.com\\/5940ebbced8e9\"}', '0', '1', '1', '222.00', '1', '0');
INSERT INTO `shop_product` VALUES ('4', '14', '乐视', '立马解散', '22', '222.00', 'o7zgluxwg.bkt.clouddn.com/5941425ded0a9', '{\"5941426428def\":\"o7zgluxwg.bkt.clouddn.com\\/5941426428def\",\"59414269516fa\":\"o7zgluxwg.bkt.clouddn.com\\/59414269516fa\",\"5941426dedf17\":\"o7zgluxwg.bkt.clouddn.com\\/5941426dedf17\"}', '0', '0', '0', '111.00', '1', '0');
INSERT INTO `shop_product` VALUES ('5', '10', '苹果', '哈哈哈哈哈哈哈', '30', '0.10', 'o7zgluxwg.bkt.clouddn.com/5942aa59416ac', '{\"5942aa5992ef3\":\"o7zgluxwg.bkt.clouddn.com\\/5942aa5992ef3\",\"5942aa5aae679\":\"o7zgluxwg.bkt.clouddn.com\\/5942aa5aae679\",\"5942aa5b680cb\":\"o7zgluxwg.bkt.clouddn.com\\/5942aa5b680cb\"}', '1', '1', '1', '0.10', '1', '0');

-- ----------------------------
-- Table structure for shop_profile
-- ----------------------------
DROP TABLE IF EXISTS `shop_profile`;
CREATE TABLE `shop_profile` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `truename` varchar(32) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `age` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '年龄',
  `sex` enum('0','1','2') NOT NULL DEFAULT '0' COMMENT '性别',
  `birthday` date NOT NULL DEFAULT '2016-01-01' COMMENT '生日',
  `nickname` varchar(32) NOT NULL DEFAULT '' COMMENT '昵称',
  `company` varchar(100) NOT NULL DEFAULT '' COMMENT '公司',
  `userid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户的ID',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `shop_profile_userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_profile
-- ----------------------------

-- ----------------------------
-- Table structure for shop_test
-- ----------------------------
DROP TABLE IF EXISTS `shop_test`;
CREATE TABLE `shop_test` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_test
-- ----------------------------

-- ----------------------------
-- Table structure for shop_user
-- ----------------------------
DROP TABLE IF EXISTS `shop_user`;
CREATE TABLE `shop_user` (
  `userid` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `username` varchar(32) NOT NULL DEFAULT '',
  `userpass` char(32) NOT NULL DEFAULT '',
  `useremail` varchar(100) NOT NULL DEFAULT '',
  `openid` char(32) NOT NULL DEFAULT '0' COMMENT 'qq登录openid',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`userid`),
  UNIQUE KEY `shop_user_username_userpass` (`username`,`userpass`),
  UNIQUE KEY `shop_user_useremail_userpass` (`useremail`,`userpass`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_user
-- ----------------------------
INSERT INTO `shop_user` VALUES ('1', 'shop_593cb5d1e7462', '698d51a19d8a121ce581499d7b701668', '575865770@qq.com', '0', '1497150933');
INSERT INTO `shop_user` VALUES ('2', 'moon', '698d51a19d8a121ce581499d7b701668', '', 'awjdawkdawkldkwakl123', '1497152611');
