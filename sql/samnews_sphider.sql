SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `categories`
-- ----------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL auto_increment,
  `category` text,
  `parent_num` int(11) default NULL,
  PRIMARY KEY  (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of categories
-- ----------------------------

-- ----------------------------
-- Table structure for `domains`
-- ----------------------------
DROP TABLE IF EXISTS `domains`;
CREATE TABLE `domains` (
  `domain_id` int(11) NOT NULL auto_increment,
  `domain` varchar(255) default NULL,
  PRIMARY KEY  (`domain_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of domains
-- ----------------------------

-- ----------------------------
-- Table structure for `keywords`
-- ----------------------------
DROP TABLE IF EXISTS `keywords`;
CREATE TABLE `keywords` (
  `keyword_id` int(11) NOT NULL auto_increment,
  `keyword` varchar(30) NOT NULL,
  PRIMARY KEY  (`keyword_id`),
  UNIQUE KEY `kw` (`keyword`),
  KEY `keyword` (`keyword`(10))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of keywords
-- ----------------------------

-- ----------------------------
-- Table structure for `link_keyword0`
-- ----------------------------
DROP TABLE IF EXISTS `link_keyword0`;
CREATE TABLE `link_keyword0` (
  `link_id` int(11) NOT NULL,
  `keyword_id` int(11) NOT NULL,
  `weight` int(3) default NULL,
  `domain` int(4) default NULL,
  KEY `linkid` (`link_id`),
  KEY `keyid` (`keyword_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of link_keyword0
-- ----------------------------

-- ----------------------------
-- Table structure for `link_keyword1`
-- ----------------------------
DROP TABLE IF EXISTS `link_keyword1`;
CREATE TABLE `link_keyword1` (
  `link_id` int(11) NOT NULL,
  `keyword_id` int(11) NOT NULL,
  `weight` int(3) default NULL,
  `domain` int(4) default NULL,
  KEY `linkid` (`link_id`),
  KEY `keyid` (`keyword_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of link_keyword1
-- ----------------------------

-- ----------------------------
-- Table structure for `link_keyword2`
-- ----------------------------
DROP TABLE IF EXISTS `link_keyword2`;
CREATE TABLE `link_keyword2` (
  `link_id` int(11) NOT NULL,
  `keyword_id` int(11) NOT NULL,
  `weight` int(3) default NULL,
  `domain` int(4) default NULL,
  KEY `linkid` (`link_id`),
  KEY `keyid` (`keyword_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of link_keyword2
-- ----------------------------

-- ----------------------------
-- Table structure for `link_keyword3`
-- ----------------------------
DROP TABLE IF EXISTS `link_keyword3`;
CREATE TABLE `link_keyword3` (
  `link_id` int(11) NOT NULL,
  `keyword_id` int(11) NOT NULL,
  `weight` int(3) default NULL,
  `domain` int(4) default NULL,
  KEY `linkid` (`link_id`),
  KEY `keyid` (`keyword_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of link_keyword3
-- ----------------------------

-- ----------------------------
-- Table structure for `link_keyword4`
-- ----------------------------
DROP TABLE IF EXISTS `link_keyword4`;
CREATE TABLE `link_keyword4` (
  `link_id` int(11) NOT NULL,
  `keyword_id` int(11) NOT NULL,
  `weight` int(3) default NULL,
  `domain` int(4) default NULL,
  KEY `linkid` (`link_id`),
  KEY `keyid` (`keyword_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of link_keyword4
-- ----------------------------

-- ----------------------------
-- Table structure for `link_keyword5`
-- ----------------------------
DROP TABLE IF EXISTS `link_keyword5`;
CREATE TABLE `link_keyword5` (
  `link_id` int(11) NOT NULL,
  `keyword_id` int(11) NOT NULL,
  `weight` int(3) default NULL,
  `domain` int(4) default NULL,
  KEY `linkid` (`link_id`),
  KEY `keyid` (`keyword_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of link_keyword5
-- ----------------------------

-- ----------------------------
-- Table structure for `link_keyword6`
-- ----------------------------
DROP TABLE IF EXISTS `link_keyword6`;
CREATE TABLE `link_keyword6` (
  `link_id` int(11) NOT NULL,
  `keyword_id` int(11) NOT NULL,
  `weight` int(3) default NULL,
  `domain` int(4) default NULL,
  KEY `linkid` (`link_id`),
  KEY `keyid` (`keyword_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of link_keyword6
-- ----------------------------

-- ----------------------------
-- Table structure for `link_keyword7`
-- ----------------------------
DROP TABLE IF EXISTS `link_keyword7`;
CREATE TABLE `link_keyword7` (
  `link_id` int(11) NOT NULL,
  `keyword_id` int(11) NOT NULL,
  `weight` int(3) default NULL,
  `domain` int(4) default NULL,
  KEY `linkid` (`link_id`),
  KEY `keyid` (`keyword_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of link_keyword7
-- ----------------------------

-- ----------------------------
-- Table structure for `link_keyword8`
-- ----------------------------
DROP TABLE IF EXISTS `link_keyword8`;
CREATE TABLE `link_keyword8` (
  `link_id` int(11) NOT NULL,
  `keyword_id` int(11) NOT NULL,
  `weight` int(3) default NULL,
  `domain` int(4) default NULL,
  KEY `linkid` (`link_id`),
  KEY `keyid` (`keyword_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of link_keyword8
-- ----------------------------

-- ----------------------------
-- Table structure for `link_keyword9`
-- ----------------------------
DROP TABLE IF EXISTS `link_keyword9`;
CREATE TABLE `link_keyword9` (
  `link_id` int(11) NOT NULL,
  `keyword_id` int(11) NOT NULL,
  `weight` int(3) default NULL,
  `domain` int(4) default NULL,
  KEY `linkid` (`link_id`),
  KEY `keyid` (`keyword_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of link_keyword9
-- ----------------------------

-- ----------------------------
-- Table structure for `link_keyworda`
-- ----------------------------
DROP TABLE IF EXISTS `link_keyworda`;
CREATE TABLE `link_keyworda` (
  `link_id` int(11) NOT NULL,
  `keyword_id` int(11) NOT NULL,
  `weight` int(3) default NULL,
  `domain` int(4) default NULL,
  KEY `linkid` (`link_id`),
  KEY `keyid` (`keyword_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of link_keyworda
-- ----------------------------

-- ----------------------------
-- Table structure for `link_keywordb`
-- ----------------------------
DROP TABLE IF EXISTS `link_keywordb`;
CREATE TABLE `link_keywordb` (
  `link_id` int(11) NOT NULL,
  `keyword_id` int(11) NOT NULL,
  `weight` int(3) default NULL,
  `domain` int(4) default NULL,
  KEY `linkid` (`link_id`),
  KEY `keyid` (`keyword_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of link_keywordb
-- ----------------------------

-- ----------------------------
-- Table structure for `link_keywordc`
-- ----------------------------
DROP TABLE IF EXISTS `link_keywordc`;
CREATE TABLE `link_keywordc` (
  `link_id` int(11) NOT NULL,
  `keyword_id` int(11) NOT NULL,
  `weight` int(3) default NULL,
  `domain` int(4) default NULL,
  KEY `linkid` (`link_id`),
  KEY `keyid` (`keyword_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of link_keywordc
-- ----------------------------

-- ----------------------------
-- Table structure for `link_keywordd`
-- ----------------------------
DROP TABLE IF EXISTS `link_keywordd`;
CREATE TABLE `link_keywordd` (
  `link_id` int(11) NOT NULL,
  `keyword_id` int(11) NOT NULL,
  `weight` int(3) default NULL,
  `domain` int(4) default NULL,
  KEY `linkid` (`link_id`),
  KEY `keyid` (`keyword_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of link_keywordd
-- ----------------------------

-- ----------------------------
-- Table structure for `link_keyworde`
-- ----------------------------
DROP TABLE IF EXISTS `link_keyworde`;
CREATE TABLE `link_keyworde` (
  `link_id` int(11) NOT NULL,
  `keyword_id` int(11) NOT NULL,
  `weight` int(3) default NULL,
  `domain` int(4) default NULL,
  KEY `linkid` (`link_id`),
  KEY `keyid` (`keyword_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of link_keyworde
-- ----------------------------

-- ----------------------------
-- Table structure for `link_keywordf`
-- ----------------------------
DROP TABLE IF EXISTS `link_keywordf`;
CREATE TABLE `link_keywordf` (
  `link_id` int(11) NOT NULL,
  `keyword_id` int(11) NOT NULL,
  `weight` int(3) default NULL,
  `domain` int(4) default NULL,
  KEY `linkid` (`link_id`),
  KEY `keyid` (`keyword_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of link_keywordf
-- ----------------------------

-- ----------------------------
-- Table structure for `links`
-- ----------------------------
DROP TABLE IF EXISTS `links`;
CREATE TABLE `links` (
  `link_id` int(11) NOT NULL auto_increment,
  `site_id` int(11) default NULL,
  `url` varchar(255) NOT NULL,
  `title` varchar(200) default NULL,
  `description` varchar(255) default NULL,
  `fulltxt` mediumtext,
  `indexdate` date default NULL,
  `size` float default NULL,
  `md5sum` varchar(32) default NULL,
  `visible` int(11) default '0',
  `level` int(11) default NULL,
  PRIMARY KEY  (`link_id`),
  KEY `url` (`url`),
  KEY `md5key` (`md5sum`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of links
-- ----------------------------

-- ----------------------------
-- Table structure for `pending`
-- ----------------------------
DROP TABLE IF EXISTS `pending`;
CREATE TABLE `pending` (
  `site_id` int(11) default NULL,
  `temp_id` varchar(32) default NULL,
  `level` int(11) default NULL,
  `count` int(11) default NULL,
  `num` int(11) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pending
-- ----------------------------

-- ----------------------------
-- Table structure for `query_log`
-- ----------------------------
DROP TABLE IF EXISTS `query_log`;
CREATE TABLE `query_log` (
  `query` varchar(255) default NULL,
  `time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `elapsed` float default NULL,
  `results` int(11) default NULL,
  KEY `query_key` (`query`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of query_log
-- ----------------------------

-- ----------------------------
-- Table structure for `site_category`
-- ----------------------------
DROP TABLE IF EXISTS `site_category`;
CREATE TABLE `site_category` (
  `site_id` int(11) default NULL,
  `category_id` int(11) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of site_category
-- ----------------------------

-- ----------------------------
-- Table structure for `sites`
-- ----------------------------
DROP TABLE IF EXISTS `sites`;
CREATE TABLE `sites` (
  `site_id` int(11) NOT NULL auto_increment,
  `url` varchar(255) default NULL,
  `title` varchar(255) default NULL,
  `short_desc` text,
  `indexdate` date default NULL,
  `spider_depth` int(11) default '2',
  `required` text,
  `disallowed` text,
  `can_leave_domain` tinyint(1) default NULL,
  PRIMARY KEY  (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sites
-- ----------------------------

-- ----------------------------
-- Table structure for `temp`
-- ----------------------------
DROP TABLE IF EXISTS `temp`;
CREATE TABLE `temp` (
  `link` varchar(255) default NULL,
  `level` int(11) default NULL,
  `id` varchar(32) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of temp
-- ----------------------------
