
SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `category`
-- ----------------------------
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
`id`  int(11) NOT NULL ,
`name`  varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci

;

-- ----------------------------
-- Table structure for `comment`
-- ----------------------------
DROP TABLE IF EXISTS `comment`;
CREATE TABLE `comment` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`post`  int(11) NOT NULL ,
`thread`  int(11) NULL DEFAULT NULL ,
`text`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`author`  int(11) NOT NULL ,
`score`  int(11) NOT NULL ,
`ip`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`created`  datetime NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=35

;

-- ----------------------------
-- Table structure for `post`
-- ----------------------------
DROP TABLE IF EXISTS `post`;
CREATE TABLE `post` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`title`  varchar(160) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`slug`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`url`  varchar(600) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`domain`  varchar(90) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`author`  int(11) NOT NULL ,
`description`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`category`  int(11) NULL DEFAULT NULL ,
`score`  int(11) NOT NULL ,
`ip`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`created`  datetime NOT NULL ,
`comment_count`  int(11) NOT NULL DEFAULT 0 ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=1020

;

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`login`  varchar(12) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`password`  varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`email`  varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`about`  varchar(300) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`last_visit`  datetime NULL DEFAULT NULL ,
`ip`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`created`  datetime NOT NULL ,
`perm_mod`  int(11) NULL DEFAULT NULL ,
`perm_admin`  int(11) NULL DEFAULT NULL ,
`post_count`  int(11) NOT NULL DEFAULT 0 ,
`comment_count`  int(11) NOT NULL DEFAULT 0 ,
`vote_count`  int(11) NOT NULL DEFAULT 0 ,
`voted_count`  int(11) NOT NULL DEFAULT 0 ,
`forgot_key`  varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`cookie_key`  varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=17

;

-- ----------------------------
-- Table structure for `version`
-- ----------------------------
DROP TABLE IF EXISTS `version`;
CREATE TABLE `version` (
`version`  varchar(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
PRIMARY KEY (`version`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci

;

-- ----------------------------
-- Table structure for `vote_comment`
-- ----------------------------
DROP TABLE IF EXISTS `vote_comment`;
CREATE TABLE `vote_comment` (
`comment`  int(11) NOT NULL ,
`userid`  int(11) NOT NULL ,
`created`  datetime NOT NULL ,
PRIMARY KEY (`comment`, `userid`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci

;

-- ----------------------------
-- Table structure for `vote_post`
-- ----------------------------
DROP TABLE IF EXISTS `vote_post`;
CREATE TABLE `vote_post` (
`post`  int(11) NOT NULL ,
`userid`  int(11) NOT NULL ,
`created`  datetime NOT NULL ,
PRIMARY KEY (`post`, `userid`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci

;

-- ----------------------------
-- Auto increment value for `comment`
-- ----------------------------
ALTER TABLE `comment` AUTO_INCREMENT=35;

-- ----------------------------
-- Indexes structure for table post
-- ----------------------------
CREATE UNIQUE INDEX `uc_slug` USING BTREE ON `post`(`slug`) ;

-- ----------------------------
-- Auto increment value for `post`
-- ----------------------------
ALTER TABLE `post` AUTO_INCREMENT=1020;

-- ----------------------------
-- Auto increment value for `users`
-- ----------------------------
ALTER TABLE `users` AUTO_INCREMENT=17;
