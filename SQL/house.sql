# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.01 (MySQL 5.7.17)
# Database: house
# Generation Time: 2019-01-23 05:58:43 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table lianjia_area
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lianjia_area`;

CREATE TABLE `lianjia_area` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(32) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `all_count` int(11) DEFAULT '0',
  `all_count_xiaoqu` int(11) DEFAULT '0',
  `all_count_chengjiao` int(11) DEFAULT '0',
  `page_count` int(11) DEFAULT '0',
  `page_count_xiaoqu` int(11) DEFAULT '0',
  `page_count_chengjiao` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table lianjia_city
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lianjia_city`;

CREATE TABLE `lianjia_city` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(32) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `ershoufang` tinyint(4) DEFAULT '0',
  `chengjiao` tinyint(4) DEFAULT '0',
  `xiaoqu` tinyint(4) DEFAULT '0',
  `status` tinyint(4) DEFAULT '0',
  `other` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table lianjia_ershou_chengjiao
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lianjia_ershou_chengjiao`;

CREATE TABLE `lianjia_ershou_chengjiao` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `chengjiaoriqi` varchar(32) DEFAULT NULL COMMENT '副标题',
  `zongjia` varchar(16) DEFAULT NULL COMMENT '总价',
  `danjia` char(32) DEFAULT NULL COMMENT '单价',
  `guapaijia` char(16) DEFAULT NULL,
  `chengjiaozhouqi` char(16) DEFAULT NULL,
  `tiaojiacishu` char(16) DEFAULT NULL,
  `daikancishu` char(16) DEFAULT NULL,
  `guanzhushu` char(16) DEFAULT NULL,
  `liulanshu` char(16) DEFAULT NULL,
  `jingjiren_lianjie` varchar(255) DEFAULT NULL,
  `jibenshuxing` text,
  `jiaoyijilu` text,
  `fangyuantese` text,
  `parent_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table lianjia_ershou_xiaoqu
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lianjia_ershou_xiaoqu`;

CREATE TABLE `lianjia_ershou_xiaoqu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `sub` varchar(255) DEFAULT NULL COMMENT '副标题',
  `danjia` varchar(16) DEFAULT NULL COMMENT '单价',
  `jianzhuniandai` varchar(32) DEFAULT NULL,
  `jianzhuleixing` varchar(32) DEFAULT NULL,
  `wuyefeiyong` varchar(32) DEFAULT NULL,
  `wuyegongsi` varchar(32) DEFAULT NULL,
  `kaifashang` varchar(32) DEFAULT NULL,
  `loudongzongshu` varchar(32) DEFAULT NULL,
  `fangwuzongshu` varchar(64) DEFAULT NULL,
  `fujinmeidian` varchar(255) DEFAULT NULL,
  `jingjiren` varchar(32) DEFAULT NULL,
  `jingjirenpingfen` varchar(255) DEFAULT NULL,
  `jingjirendianhua` varchar(64) DEFAULT NULL,
  `parent_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table lianjia_ershou_zaishou
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lianjia_ershou_zaishou`;

CREATE TABLE `lianjia_ershou_zaishou` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `sub` varchar(255) DEFAULT NULL COMMENT '副标题',
  `zongjia` varchar(16) DEFAULT NULL COMMENT '总价',
  `danjia` varchar(16) DEFAULT NULL COMMENT '单价',
  `huxing` varchar(32) DEFAULT NULL,
  `louceng` varchar(32) DEFAULT NULL,
  `chaoxiang` varchar(32) DEFAULT NULL,
  `zhuangxiu` varchar(32) DEFAULT NULL,
  `mianji` varchar(32) DEFAULT NULL,
  `leixing` varchar(32) DEFAULT NULL,
  `xiaoqumingcheng` varchar(64) DEFAULT NULL,
  `suozaiquyu` varchar(255) DEFAULT NULL,
  `kanfangshijian` varchar(64) DEFAULT NULL,
  `jingjiren` varchar(32) DEFAULT NULL,
  `jingjirenpingfen` varchar(255) DEFAULT NULL,
  `jingjirendianhua` varchar(64) DEFAULT NULL,
  `jibenshuxing` text,
  `jiaoyishuxing` text,
  `fangyuantese` text,
  `daikancishu7` int(11) DEFAULT NULL,
  `daikancishu30` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table lianjia_url
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lianjia_url`;

CREATE TABLE `lianjia_url` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table lianjia_url_chengjiao
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lianjia_url_chengjiao`;

CREATE TABLE `lianjia_url_chengjiao` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table lianjia_url_xiaoqu
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lianjia_url_xiaoqu`;

CREATE TABLE `lianjia_url_xiaoqu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
