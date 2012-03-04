-- MySQL dump 10.13  Distrib 5.1.58, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: media_felix
-- ------------------------------------------------------
-- Server version	5.1.58-1ubuntu1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `api_keys`
--

DROP TABLE IF EXISTS `api_keys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `api_keys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `api_key` varchar(40) DEFAULT NULL,
  `user` varchar(16) DEFAULT NULL,
  `description` mediumtext,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `api_log`
--

DROP TABLE IF EXISTS `api_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `api_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `what` longtext NOT NULL,
  `request` longtext,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `article`
--

DROP TABLE IF EXISTS `article`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `short_title` varchar(20) DEFAULT NULL,
  `teaser` varchar(300) NOT NULL,
  `author` varchar(16) NOT NULL,
  `category` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `approvedby` varchar(16) DEFAULT NULL,
  `published` timestamp NULL DEFAULT NULL,
  `hidden` tinyint(4) NOT NULL COMMENT 'from engine',
  `text1` int(11) NOT NULL,
  `img1` int(11) DEFAULT NULL,
  `text2` int(11) DEFAULT NULL,
  `img2` int(11) DEFAULT NULL,
  `img2lr` tinyint(1) DEFAULT NULL COMMENT '0 left, 1 right (default)',
  `hits` int(11) NOT NULL DEFAULT '0',
  `short_desc` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `text1` (`text1`),
  KEY `img1` (`img1`),
  KEY `text2` (`text2`),
  KEY `img2` (`img2`),
  KEY `category` (`category`),
  KEY `author` (`author`),
  KEY `approvedby` (`approvedby`),
  KEY `hidden` (`hidden`),
  CONSTRAINT `article_ibfk_44` FOREIGN KEY (`author`) REFERENCES `user` (`user`),
  CONSTRAINT `article_ibfk_45` FOREIGN KEY (`category`) REFERENCES `category` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `article_ibfk_46` FOREIGN KEY (`approvedby`) REFERENCES `user` (`user`),
  CONSTRAINT `article_ibfk_47` FOREIGN KEY (`text1`) REFERENCES `text_story` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1467 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `article_author`
--

DROP TABLE IF EXISTS `article_author`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `article_author` (
  `article` int(11) DEFAULT NULL,
  `author` varchar(16) DEFAULT NULL,
  KEY `article` (`article`),
  KEY `author` (`author`),
  CONSTRAINT `article_author_ibfk_1` FOREIGN KEY (`article`) REFERENCES `article` (`id`),
  CONSTRAINT `article_author_ibfk_2` FOREIGN KEY (`author`) REFERENCES `user` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `article_topic`
--

DROP TABLE IF EXISTS `article_topic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `article_topic` (
  `article_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  PRIMARY KEY (`article_id`,`topic_id`),
  KEY `article_id` (`article_id`),
  KEY `topic_id` (`topic_id`),
  CONSTRAINT `article_topic_ibfk_3` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`) ON DELETE CASCADE,
  CONSTRAINT `article_topic_ibfk_4` FOREIGN KEY (`topic_id`) REFERENCES `topic` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `article_visit`
--

DROP TABLE IF EXISTS `article_visit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `article_visit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user` varchar(16) DEFAULT NULL,
  `IP` varchar(15) DEFAULT NULL,
  `referrer` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `article` (`article`,`timestamp`,`user`),
  KEY `timestamp` (`timestamp`),
  CONSTRAINT `article_visit_ibfk_1` FOREIGN KEY (`article`) REFERENCES `article` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(30) NOT NULL,
  `cat` varchar(16) NOT NULL,
  `uri` varchar(500) NOT NULL,
  `colourclass` varchar(32) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `top_slider_1` int(11) NOT NULL,
  `top_slider_2` int(11) NOT NULL,
  `top_slider_3` int(11) NOT NULL,
  `top_slider_4` int(11) NOT NULL,
  `top_sidebar_1` int(11) NOT NULL,
  `top_sidebar_2` int(11) NOT NULL,
  `top_sidebar_3` int(11) NOT NULL,
  `top_sidebar_4` int(11) NOT NULL,
  `top_sidebar_5` int(11) NOT NULL,
  `email` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `twitter` varchar(20) DEFAULT NULL,
  `description` text,
  `hidden` tinyint(1) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `top_slider_1` (`top_slider_1`),
  KEY `top_slider_2` (`top_slider_2`),
  KEY `top_slider_3` (`top_slider_3`),
  KEY `top_slider_4` (`top_slider_4`),
  KEY `top_sidebar_1` (`top_sidebar_1`),
  KEY `top_sidebar_2` (`top_sidebar_2`),
  KEY `top_sidebar_3` (`top_sidebar_3`),
  KEY `top_sidebar_4` (`top_sidebar_4`),
  KEY `top_sidebar_5` (`top_sidebar_5`),
  KEY `cat` (`cat`),
  CONSTRAINT `category_ibfk_10` FOREIGN KEY (`top_sidebar_2`) REFERENCES `article` (`id`),
  CONSTRAINT `category_ibfk_11` FOREIGN KEY (`top_sidebar_3`) REFERENCES `article` (`id`),
  CONSTRAINT `category_ibfk_12` FOREIGN KEY (`top_sidebar_4`) REFERENCES `article` (`id`),
  CONSTRAINT `category_ibfk_13` FOREIGN KEY (`top_sidebar_5`) REFERENCES `article` (`id`),
  CONSTRAINT `category_ibfk_5` FOREIGN KEY (`top_slider_1`) REFERENCES `article` (`id`),
  CONSTRAINT `category_ibfk_6` FOREIGN KEY (`top_slider_2`) REFERENCES `article` (`id`),
  CONSTRAINT `category_ibfk_7` FOREIGN KEY (`top_slider_3`) REFERENCES `article` (`id`),
  CONSTRAINT `category_ibfk_8` FOREIGN KEY (`top_slider_4`) REFERENCES `article` (`id`),
  CONSTRAINT `category_ibfk_9` FOREIGN KEY (`top_sidebar_1`) REFERENCES `article` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `category_author`
--

DROP TABLE IF EXISTS `category_author`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category_author` (
  `category` int(11) NOT NULL,
  `user` varchar(16) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'section editor',
  KEY `category` (`category`,`user`),
  KEY `user` (`user`),
  CONSTRAINT `category_author_ibfk_2` FOREIGN KEY (`user`) REFERENCES `user` (`user`),
  CONSTRAINT `category_author_ibfk_3` FOREIGN KEY (`category`) REFERENCES `category` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article` int(11) NOT NULL,
  `user` varchar(16) NOT NULL,
  `comment` varchar(1024) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `reply` int(11) DEFAULT NULL,
  `likes` int(11) NOT NULL DEFAULT '0',
  `dislikes` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  KEY `article` (`article`),
  CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`article`) REFERENCES `article` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `comment_ibfk_3` FOREIGN KEY (`user`) REFERENCES `user` (`user`)
) ENGINE=InnoDB AUTO_INCREMENT=324 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `comment_ext`
--

DROP TABLE IF EXISTS `comment_ext`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment_ext` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `comment` varchar(1024) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `IP` varchar(15) NOT NULL,
  `pending` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Pending approval',
  `reply` int(11) DEFAULT NULL,
  `spam` tinyint(1) NOT NULL,
  `likes` int(11) NOT NULL DEFAULT '0',
  `dislikes` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user` (`name`),
  KEY `article` (`article`),
  KEY `pending` (`pending`),
  CONSTRAINT `comment_ext_ibfk_1` FOREIGN KEY (`article`) REFERENCES `article` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=80008134 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `comment_like`
--

DROP TABLE IF EXISTS `comment_like`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment_like` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(16) NOT NULL,
  `comment` int(11) NOT NULL,
  `binlike` tinyint(1) NOT NULL COMMENT '0 dislike, 1 like',
  PRIMARY KEY (`id`),
  UNIQUE KEY `comment_like_check` (`user`,`comment`),
  CONSTRAINT `comment_like_ibfk_1` FOREIGN KEY (`user`) REFERENCES `user` (`user`)
) ENGINE=InnoDB AUTO_INCREMENT=508 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `comment_spam`
--

DROP TABLE IF EXISTS `comment_spam`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment_spam` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `IP` varchar(15) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cookies`
--

DROP TABLE IF EXISTS `cookies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cookies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` varchar(64) NOT NULL,
  `user` varchar(64) NOT NULL,
  `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `hash` (`hash`),
  KEY `user` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `engine_page`
--

DROP TABLE IF EXISTS `engine_page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `engine_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(32) NOT NULL,
  `uri` varchar(255) NOT NULL,
  `inc` varchar(255) DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `active` (`active`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `frontpage`
--

DROP TABLE IF EXISTS `frontpage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `frontpage` (
  `layout` int(11) NOT NULL,
  `section` varchar(20) NOT NULL,
  `1` int(11) NOT NULL,
  `2` int(11) NOT NULL,
  `3` int(11) NOT NULL,
  `4` int(11) NOT NULL,
  `5` int(11) NOT NULL,
  `6` int(11) NOT NULL,
  `7` int(11) NOT NULL,
  `8` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `image`
--

DROP TABLE IF EXISTS `image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `uri` varchar(500) NOT NULL,
  `user` varchar(16) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `v_offset` int(11) NOT NULL DEFAULT '0',
  `h_offset` int(11) NOT NULL DEFAULT '0',
  `caption` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `attribution` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `attr_link` varchar(100) DEFAULT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  CONSTRAINT `image_ibfk_1` FOREIGN KEY (`user`) REFERENCES `user` (`user`)
) ENGINE=InnoDB AUTO_INCREMENT=1379 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `login`
--

DROP TABLE IF EXISTS `login`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(32) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `browser` text NOT NULL,
  `user` varchar(16) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `valid` tinyint(1) NOT NULL DEFAULT '1',
  `logged_in` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `media_photo`
--

DROP TABLE IF EXISTS `media_photo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media_photo` (
  `id` int(4) NOT NULL DEFAULT '0',
  `folder` text NOT NULL,
  `title` varchar(64) NOT NULL,
  `author` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `description` text NOT NULL,
  `order` int(6) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `thumbnail` int(10) NOT NULL,
  `hits` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `media_photo_albums`
--

DROP TABLE IF EXISTS `media_photo_albums`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media_photo_albums` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `albumFolder` text NOT NULL,
  `albumName` varchar(64) NOT NULL,
  `albumAuthor` varchar(100) NOT NULL,
  `albumDate` date NOT NULL,
  `albumDesc` text NOT NULL,
  `albumOrder` int(6) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `albumThumb` int(10) NOT NULL,
  `hits` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `media_photo_images`
--

DROP TABLE IF EXISTS `media_photo_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media_photo_images` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `albumID` int(11) NOT NULL,
  `imageName` text NOT NULL,
  `imageDate` datetime NOT NULL,
  `imageTitle` text NOT NULL,
  `imageCaption` text NOT NULL,
  `camera` text NOT NULL,
  `iso` int(6) NOT NULL,
  `fstop` varchar(5) NOT NULL,
  `orientation` int(2) NOT NULL,
  `tags` varchar(100) NOT NULL,
  `geoCoords` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=509 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `media_video`
--

DROP TABLE IF EXISTS `media_video`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media_video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `description` text,
  `author` varchar(1000) NOT NULL,
  `video_id` varchar(30) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `visible` int(11) DEFAULT NULL,
  `hits` int(11) DEFAULT NULL,
  `site` varchar(30) NOT NULL COMMENT 'Name of site the video is on',
  `thumbnail` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `optimise`
--

DROP TABLE IF EXISTS `optimise`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `optimise` (
  `article` int(10) NOT NULL,
  `optimised` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `page`
--

DROP TABLE IF EXISTS `page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(15) NOT NULL DEFAULT '',
  `uri` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(30) DEFAULT NULL,
  `title` varchar(30) DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `poll`
--

DROP TABLE IF EXISTS `poll`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(140) NOT NULL,
  `article_id` int(11) DEFAULT NULL,
  `author` varchar(16) NOT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `options` int(11) NOT NULL DEFAULT '1' COMMENT '1 radio, 2+ checkbox',
  `open` timestamp NULL DEFAULT NULL,
  `close` timestamp NULL DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `limit` int(11) DEFAULT NULL,
  `chart_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `article_id` (`article_id`),
  KEY `author` (`author`),
  KEY `chart_type_id` (`chart_type_id`),
  CONSTRAINT `poll_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`),
  CONSTRAINT `poll_ibfk_2` FOREIGN KEY (`author`) REFERENCES `user` (`user`),
  CONSTRAINT `poll_ibfk_3` FOREIGN KEY (`chart_type_id`) REFERENCES `poll_chart_type` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `poll_chart_type`
--

DROP TABLE IF EXISTS `poll_chart_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll_chart_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `poll_option`
--

DROP TABLE IF EXISTS `poll_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poll_id` int(11) NOT NULL,
  `option` int(11) NOT NULL,
  `label` varchar(140) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `poll_id` (`poll_id`),
  CONSTRAINT `poll_option_ibfk_1` FOREIGN KEY (`poll_id`) REFERENCES `poll` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `poll_vote`
--

DROP TABLE IF EXISTS `poll_vote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll_vote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poll_id` int(11) NOT NULL,
  `user` varchar(16) NOT NULL,
  `answer` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `poll_id` (`poll_id`,`user`),
  KEY `user` (`user`),
  CONSTRAINT `poll_vote_ibfk_1` FOREIGN KEY (`poll_id`) REFERENCES `poll` (`id`),
  CONSTRAINT `poll_vote_ibfk_2` FOREIGN KEY (`user`) REFERENCES `user` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `preview_email`
--

DROP TABLE IF EXISTS `preview_email`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `preview_email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role` (
  `id` int(11) NOT NULL COMMENT 'Numerical order of power',
  `role` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='id: numerical order of power';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sexsurvey_completers`
--

DROP TABLE IF EXISTS `sexsurvey_completers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sexsurvey_completers` (
  `uname` varchar(45) NOT NULL,
  UNIQUE KEY `uname_UNIQUE` (`uname`),
  KEY `uname_key` (`uname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sexsurvey_responses`
--

DROP TABLE IF EXISTS `sexsurvey_responses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sexsurvey_responses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` longtext,
  `deptcheck` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `site`
--

DROP TABLE IF EXISTS `site`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(30) DEFAULT NULL,
  `title` varchar(30) DEFAULT NULL,
  `active` int(2) DEFAULT '0',
  `theme` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sport_table`
--

DROP TABLE IF EXISTS `sport_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sport_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `team` varchar(30) DEFAULT NULL,
  `played` int(11) NOT NULL,
  `won` int(11) NOT NULL,
  `drawn` int(11) NOT NULL,
  `lost` int(11) NOT NULL,
  `f` int(11) NOT NULL,
  `a` int(11) NOT NULL,
  `difference` float NOT NULL,
  `index` float NOT NULL,
  `win_percent` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `text_global`
--

DROP TABLE IF EXISTS `text_global`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `text_global` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(50) NOT NULL,
  `value` varchar(5000) DEFAULT NULL,
  `style` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `text_story`
--

DROP TABLE IF EXISTS `text_story`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `text_story` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(16) NOT NULL,
  `content` mediumtext,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  CONSTRAINT `text_story_ibfk_1` FOREIGN KEY (`user`) REFERENCES `user` (`user`)
) ENGINE=InnoDB AUTO_INCREMENT=1508 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `text_story_bkp`
--

DROP TABLE IF EXISTS `text_story_bkp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `text_story_bkp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(16) NOT NULL,
  `content` mediumtext,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user` (`user`)
) ENGINE=InnoDB AUTO_INCREMENT=1184 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `thephig_albums`
--

DROP TABLE IF EXISTS `thephig_albums`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `thephig_albums` (
  `albumID` int(4) NOT NULL AUTO_INCREMENT,
  `albumFolder` text NOT NULL,
  `albumName` varchar(64) NOT NULL,
  `albumDate` text NOT NULL,
  `albumDesc` text NOT NULL,
  `albumOrder` int(6) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `protected` int(1) NOT NULL DEFAULT '0',
  `gmap` int(1) NOT NULL DEFAULT '0',
  `password` text NOT NULL,
  PRIMARY KEY (`albumID`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `thephig_images`
--

DROP TABLE IF EXISTS `thephig_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `thephig_images` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `albumID` int(11) NOT NULL,
  `imageName` text NOT NULL,
  `thumbnailName` text NOT NULL,
  `imageDate` datetime NOT NULL,
  `imageTitle` text NOT NULL,
  `camera` text NOT NULL,
  `iso` int(6) NOT NULL,
  `fstop` varchar(5) NOT NULL,
  `orientation` int(2) NOT NULL,
  `tags` varchar(100) NOT NULL,
  `geoCoords` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=263 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `thephig_info`
--

DROP TABLE IF EXISTS `thephig_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `thephig_info` (
  `galleryTitle` varchar(64) NOT NULL DEFAULT 'reedGal',
  `galleryPath` varchar(32) NOT NULL DEFAULT '/gallery/',
  `copyrightOwner` varchar(24) NOT NULL,
  `copyrightLink` varchar(48) NOT NULL,
  `jsOverlay` varchar(30) NOT NULL DEFAULT 'shutterset',
  `imagesPath` varchar(32) NOT NULL DEFAULT 'gallery_images',
  `albumsPerPage` int(3) NOT NULL DEFAULT '10',
  `imagesPerPage` int(3) NOT NULL DEFAULT '20',
  `maxHeight` int(3) NOT NULL DEFAULT '150',
  `maxWidth` int(3) NOT NULL DEFAULT '200',
  `thumbExtension` varchar(6) NOT NULL DEFAULT '_tn',
  `googleAPIkey` varchar(90) NOT NULL,
  `theme` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `thephig_users`
--

DROP TABLE IF EXISTS `thephig_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `thephig_users` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `permissions` varchar(20) NOT NULL,
  `description` text NOT NULL,
  `lastlogin` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `top_2col`
--

DROP TABLE IF EXISTS `top_2col`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `top_2col` (
  `1` int(11) NOT NULL,
  `2` int(11) NOT NULL,
  `3` int(11) NOT NULL,
  `4` int(11) NOT NULL,
  KEY `1` (`1`),
  KEY `2` (`2`),
  KEY `3` (`3`),
  KEY `4` (`4`),
  CONSTRAINT `top_2col_ibfk_1` FOREIGN KEY (`1`) REFERENCES `article` (`id`),
  CONSTRAINT `top_2col_ibfk_2` FOREIGN KEY (`2`) REFERENCES `article` (`id`),
  CONSTRAINT `top_2col_ibfk_3` FOREIGN KEY (`3`) REFERENCES `article` (`id`),
  CONSTRAINT `top_2col_ibfk_4` FOREIGN KEY (`4`) REFERENCES `article` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `top_extrapage_cat`
--

DROP TABLE IF EXISTS `top_extrapage_cat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `top_extrapage_cat` (
  `cat1` int(11) NOT NULL,
  `cat2` int(11) NOT NULL,
  `cat3` int(11) NOT NULL,
  `cat4` int(11) NOT NULL,
  `cat5` int(11) NOT NULL,
  `loc` varchar(16) NOT NULL,
  UNIQUE KEY `loc` (`loc`),
  KEY `1` (`cat1`),
  KEY `2` (`cat2`),
  KEY `3` (`cat3`),
  KEY `4` (`cat4`),
  KEY `5` (`cat5`),
  CONSTRAINT `top_extrapage_cat_ibfk_1` FOREIGN KEY (`cat1`) REFERENCES `category` (`id`),
  CONSTRAINT `top_extrapage_cat_ibfk_2` FOREIGN KEY (`cat2`) REFERENCES `category` (`id`),
  CONSTRAINT `top_extrapage_cat_ibfk_3` FOREIGN KEY (`cat3`) REFERENCES `category` (`id`),
  CONSTRAINT `top_extrapage_cat_ibfk_4` FOREIGN KEY (`cat4`) REFERENCES `category` (`id`),
  CONSTRAINT `top_extrapage_cat_ibfk_5` FOREIGN KEY (`cat5`) REFERENCES `category` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `topic`
--

DROP TABLE IF EXISTS `topic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=1213 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `user` varchar(16) NOT NULL,
  `name` varchar(80) NOT NULL,
  `visits` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(15) NOT NULL DEFAULT '0.0.0.0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `role` int(11) NOT NULL DEFAULT '0' COMMENT '>0 can log into /engine',
  `description` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `facebook` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `twitter` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `websitename` varchar(50) DEFAULT NULL,
  `websiteurl` varchar(50) DEFAULT NULL,
  `img` int(11) NOT NULL DEFAULT '676',
  UNIQUE KEY `user` (`user`),
  KEY `role` (`role`),
  KEY `timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-03-04 19:42:09
