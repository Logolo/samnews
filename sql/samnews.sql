SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `comment`
-- ----------------------------
DROP TABLE IF EXISTS `comment`;
CREATE TABLE `comment` (
  `id` int(11) NOT NULL auto_increment,
  `post` int(11) NOT NULL,
  `thread` int(11) default NULL,
  `text` text NOT NULL,
  `author` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `ip` varchar(255) default NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of comment
-- ----------------------------

-- ----------------------------
-- Table structure for `post`
-- ----------------------------
DROP TABLE IF EXISTS `post`;
CREATE TABLE `post` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(160) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `url` varchar(600) NOT NULL,
  `domain` varchar(90) NOT NULL,
  `author` int(11) NOT NULL,
  `description` text,
  `score` int(11) NOT NULL,
  `ip` varchar(255) default NULL,
  `created` datetime NOT NULL,
  `comment_count` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uc_slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of post
-- ----------------------------
INSERT INTO `post` VALUES ('1', 'First link', 'first_link', 'http://google.com/', 'google.com', '1', 'Hello World!', '1', '192.168.1.100', '2010-12-17 02:15:41', '0');

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL auto_increment,
  `login` varchar(12) NOT NULL,
  `password` varchar(45) NOT NULL,
  `email` varchar(150) NOT NULL,
  `about` varchar(300) default NULL,
  `last_visit` datetime default NULL,
  `ip` varchar(255) default NULL,
  `created` datetime NOT NULL,
  `perm_mod` int(11) default NULL,
  `perm_admin` int(11) default NULL,
  `post_count` int(11) NOT NULL default '0',
  `comment_count` int(11) NOT NULL default '0',
  `vote_count` int(11) NOT NULL default '0',
  `voted_count` int(11) NOT NULL default '0',
  `forgot_key` varchar(150) default NULL,
  `cookie_key` varchar(40) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'admin', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'youremail', null, '2010-12-17 03:19:48', '192.168.1.100', '2010-12-17 01:26:24', null, '1', '1', '0', '0', '0', null, 'changeme');

-- ----------------------------
-- Table structure for `vote_comment`
-- ----------------------------
DROP TABLE IF EXISTS `vote_comment`;
CREATE TABLE `vote_comment` (
  `comment` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`comment`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of vote_comment
-- ----------------------------

-- ----------------------------
-- Table structure for `vote_post`
-- ----------------------------
DROP TABLE IF EXISTS `vote_post`;
CREATE TABLE `vote_post` (
  `post` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`post`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of vote_post
-- ----------------------------
