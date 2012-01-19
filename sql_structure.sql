-- phpMyAdmin SQL Dump
-- version 3.4.5deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 19, 2012 at 05:05 PM
-- Server version: 5.1.58
-- PHP Version: 5.3.6-13ubuntu3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `media_felix`
--

-- --------------------------------------------------------

--
-- Table structure for table `api_keys`
--

CREATE TABLE IF NOT EXISTS `api_keys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `api_key` varchar(40) DEFAULT NULL,
  `user` varchar(16) DEFAULT NULL,
  `description` mediumtext,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `api_log`
--

CREATE TABLE IF NOT EXISTS `api_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `what` longtext NOT NULL,
  `request` longtext,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `article`
--

CREATE TABLE IF NOT EXISTS `article` (
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
  KEY `hidden` (`hidden`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1464 ;

-- --------------------------------------------------------

--
-- Table structure for table `article_author`
--

CREATE TABLE IF NOT EXISTS `article_author` (
  `article` int(11) DEFAULT NULL,
  `author` varchar(16) DEFAULT NULL,
  KEY `article` (`article`),
  KEY `author` (`author`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `article_topic`
--

CREATE TABLE IF NOT EXISTS `article_topic` (
  `article_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  PRIMARY KEY (`article_id`,`topic_id`),
  KEY `article_id` (`article_id`),
  KEY `topic_id` (`topic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `article_visit`
--

CREATE TABLE IF NOT EXISTS `article_visit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user` varchar(16) DEFAULT NULL,
  `IP` varchar(15) DEFAULT NULL,
  `referrer` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `article` (`article`,`timestamp`,`user`),
  KEY `timestamp` (`timestamp`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
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
  KEY `cat` (`cat`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Table structure for table `category_author`
--

CREATE TABLE IF NOT EXISTS `category_author` (
  `category` int(11) NOT NULL,
  `user` varchar(16) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'section editor',
  KEY `category` (`category`,`user`),
  KEY `user` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
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
  KEY `article` (`article`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=358 ;

-- --------------------------------------------------------

--
-- Table structure for table `comment_ext`
--

CREATE TABLE IF NOT EXISTS `comment_ext` (
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
  KEY `pending` (`pending`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=80008155 ;

-- --------------------------------------------------------

--
-- Table structure for table `comment_like`
--

CREATE TABLE IF NOT EXISTS `comment_like` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(16) NOT NULL,
  `comment` int(11) NOT NULL,
  `binlike` tinyint(1) NOT NULL COMMENT '0 dislike, 1 like',
  PRIMARY KEY (`id`),
  UNIQUE KEY `comment_like_check` (`user`,`comment`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=508 ;

-- --------------------------------------------------------

--
-- Table structure for table `comment_spam`
--

CREATE TABLE IF NOT EXISTS `comment_spam` (
  `IP` varchar(15) DEFAULT NULL,
  `date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `engine_page`
--

CREATE TABLE IF NOT EXISTS `engine_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(32) NOT NULL,
  `uri` varchar(255) NOT NULL,
  `inc` varchar(255) DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `active` (`active`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `frontpage`
--

CREATE TABLE IF NOT EXISTS `frontpage` (
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

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

CREATE TABLE IF NOT EXISTS `image` (
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
  KEY `user` (`user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1379 ;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE IF NOT EXISTS `login` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Table structure for table `media_photo_albums`
--

CREATE TABLE IF NOT EXISTS `media_photo_albums` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- Table structure for table `media_photo_images`
--

CREATE TABLE IF NOT EXISTS `media_photo_images` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=509 ;

-- --------------------------------------------------------

--
-- Table structure for table `media_video`
--

CREATE TABLE IF NOT EXISTS `media_video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `description` text,
  `author` varchar(1000) NOT NULL,
  `video_id` varchar(30) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `hidden` int(11) DEFAULT NULL,
  `hits` int(11) DEFAULT NULL,
  `site` varchar(30) NOT NULL COMMENT 'Name of site the video is on',
  `thumbnail` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `optimise`
--

CREATE TABLE IF NOT EXISTS `optimise` (
  `article` int(10) NOT NULL,
  `optimised` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `page`
--

CREATE TABLE IF NOT EXISTS `page` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(15) NOT NULL DEFAULT '',
  `uri` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `poll`
--

CREATE TABLE IF NOT EXISTS `poll` (
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
  KEY `chart_type_id` (`chart_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `poll_chart_type`
--

CREATE TABLE IF NOT EXISTS `poll_chart_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `poll_option`
--

CREATE TABLE IF NOT EXISTS `poll_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poll_id` int(11) NOT NULL,
  `option` int(11) NOT NULL,
  `label` varchar(140) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `poll_id` (`poll_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

-- --------------------------------------------------------

--
-- Table structure for table `poll_vote`
--

CREATE TABLE IF NOT EXISTS `poll_vote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poll_id` int(11) NOT NULL,
  `user` varchar(16) NOT NULL,
  `answer` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `poll_id` (`poll_id`,`user`),
  KEY `user` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `preview_email`
--

CREATE TABLE IF NOT EXISTS `preview_email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL COMMENT 'Numerical order of power',
  `role` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='id: numerical order of power';

-- --------------------------------------------------------

--
-- Table structure for table `sport_table`
--

CREATE TABLE IF NOT EXISTS `sport_table` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=52 ;

-- --------------------------------------------------------

--
-- Table structure for table `text_global`
--

CREATE TABLE IF NOT EXISTS `text_global` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(50) NOT NULL,
  `value` varchar(5000) DEFAULT NULL,
  `style` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `text_story`
--

CREATE TABLE IF NOT EXISTS `text_story` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(16) NOT NULL,
  `content` mediumtext,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user` (`user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1505 ;

-- --------------------------------------------------------

--
-- Table structure for table `text_story_bkp`
--

CREATE TABLE IF NOT EXISTS `text_story_bkp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(16) NOT NULL,
  `content` mediumtext,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user` (`user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1184 ;

-- --------------------------------------------------------

--
-- Table structure for table `thephig_albums`
--

CREATE TABLE IF NOT EXISTS `thephig_albums` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `thephig_images`
--

CREATE TABLE IF NOT EXISTS `thephig_images` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=263 ;

-- --------------------------------------------------------

--
-- Table structure for table `thephig_info`
--

CREATE TABLE IF NOT EXISTS `thephig_info` (
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

-- --------------------------------------------------------

--
-- Table structure for table `thephig_users`
--

CREATE TABLE IF NOT EXISTS `thephig_users` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `permissions` varchar(20) NOT NULL,
  `description` text NOT NULL,
  `lastlogin` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `topic`
--

CREATE TABLE IF NOT EXISTS `topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1213 ;

-- --------------------------------------------------------

--
-- Table structure for table `top_2col`
--

CREATE TABLE IF NOT EXISTS `top_2col` (
  `1` int(11) NOT NULL,
  `2` int(11) NOT NULL,
  `3` int(11) NOT NULL,
  `4` int(11) NOT NULL,
  KEY `1` (`1`),
  KEY `2` (`2`),
  KEY `3` (`3`),
  KEY `4` (`4`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `top_extrapage_cat`
--

CREATE TABLE IF NOT EXISTS `top_extrapage_cat` (
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
  KEY `5` (`cat5`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
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

--
-- Constraints for dumped tables
--

--
-- Constraints for table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `article_ibfk_44` FOREIGN KEY (`author`) REFERENCES `user` (`user`),
  ADD CONSTRAINT `article_ibfk_45` FOREIGN KEY (`category`) REFERENCES `category` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `article_ibfk_46` FOREIGN KEY (`approvedby`) REFERENCES `user` (`user`),
  ADD CONSTRAINT `article_ibfk_47` FOREIGN KEY (`text1`) REFERENCES `text_story` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `article_author`
--
ALTER TABLE `article_author`
  ADD CONSTRAINT `article_author_ibfk_1` FOREIGN KEY (`article`) REFERENCES `article` (`id`),
  ADD CONSTRAINT `article_author_ibfk_2` FOREIGN KEY (`author`) REFERENCES `user` (`user`);

--
-- Constraints for table `article_topic`
--
ALTER TABLE `article_topic`
  ADD CONSTRAINT `article_topic_ibfk_3` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `article_topic_ibfk_4` FOREIGN KEY (`topic_id`) REFERENCES `topic` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `article_visit`
--
ALTER TABLE `article_visit`
  ADD CONSTRAINT `article_visit_ibfk_1` FOREIGN KEY (`article`) REFERENCES `article` (`id`);

--
-- Constraints for table `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `category_ibfk_10` FOREIGN KEY (`top_sidebar_2`) REFERENCES `article` (`id`),
  ADD CONSTRAINT `category_ibfk_11` FOREIGN KEY (`top_sidebar_3`) REFERENCES `article` (`id`),
  ADD CONSTRAINT `category_ibfk_12` FOREIGN KEY (`top_sidebar_4`) REFERENCES `article` (`id`),
  ADD CONSTRAINT `category_ibfk_13` FOREIGN KEY (`top_sidebar_5`) REFERENCES `article` (`id`),
  ADD CONSTRAINT `category_ibfk_5` FOREIGN KEY (`top_slider_1`) REFERENCES `article` (`id`),
  ADD CONSTRAINT `category_ibfk_6` FOREIGN KEY (`top_slider_2`) REFERENCES `article` (`id`),
  ADD CONSTRAINT `category_ibfk_7` FOREIGN KEY (`top_slider_3`) REFERENCES `article` (`id`),
  ADD CONSTRAINT `category_ibfk_8` FOREIGN KEY (`top_slider_4`) REFERENCES `article` (`id`),
  ADD CONSTRAINT `category_ibfk_9` FOREIGN KEY (`top_sidebar_1`) REFERENCES `article` (`id`);

--
-- Constraints for table `category_author`
--
ALTER TABLE `category_author`
  ADD CONSTRAINT `category_author_ibfk_2` FOREIGN KEY (`user`) REFERENCES `user` (`user`),
  ADD CONSTRAINT `category_author_ibfk_3` FOREIGN KEY (`category`) REFERENCES `category` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`article`) REFERENCES `article` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_ibfk_3` FOREIGN KEY (`user`) REFERENCES `user` (`user`);

--
-- Constraints for table `comment_ext`
--
ALTER TABLE `comment_ext`
  ADD CONSTRAINT `comment_ext_ibfk_1` FOREIGN KEY (`article`) REFERENCES `article` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `comment_like`
--
ALTER TABLE `comment_like`
  ADD CONSTRAINT `comment_like_ibfk_1` FOREIGN KEY (`user`) REFERENCES `user` (`user`);

--
-- Constraints for table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `image_ibfk_1` FOREIGN KEY (`user`) REFERENCES `user` (`user`);

--
-- Constraints for table `poll`
--
ALTER TABLE `poll`
  ADD CONSTRAINT `poll_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`),
  ADD CONSTRAINT `poll_ibfk_2` FOREIGN KEY (`author`) REFERENCES `user` (`user`),
  ADD CONSTRAINT `poll_ibfk_3` FOREIGN KEY (`chart_type_id`) REFERENCES `poll_chart_type` (`id`);

--
-- Constraints for table `poll_option`
--
ALTER TABLE `poll_option`
  ADD CONSTRAINT `poll_option_ibfk_1` FOREIGN KEY (`poll_id`) REFERENCES `poll` (`id`);

--
-- Constraints for table `poll_vote`
--
ALTER TABLE `poll_vote`
  ADD CONSTRAINT `poll_vote_ibfk_1` FOREIGN KEY (`poll_id`) REFERENCES `poll` (`id`),
  ADD CONSTRAINT `poll_vote_ibfk_2` FOREIGN KEY (`user`) REFERENCES `user` (`user`);

--
-- Constraints for table `text_story`
--
ALTER TABLE `text_story`
  ADD CONSTRAINT `text_story_ibfk_1` FOREIGN KEY (`user`) REFERENCES `user` (`user`);

--
-- Constraints for table `top_2col`
--
ALTER TABLE `top_2col`
  ADD CONSTRAINT `top_2col_ibfk_1` FOREIGN KEY (`1`) REFERENCES `article` (`id`),
  ADD CONSTRAINT `top_2col_ibfk_2` FOREIGN KEY (`2`) REFERENCES `article` (`id`),
  ADD CONSTRAINT `top_2col_ibfk_3` FOREIGN KEY (`3`) REFERENCES `article` (`id`),
  ADD CONSTRAINT `top_2col_ibfk_4` FOREIGN KEY (`4`) REFERENCES `article` (`id`);

--
-- Constraints for table `top_extrapage_cat`
--
ALTER TABLE `top_extrapage_cat`
  ADD CONSTRAINT `top_extrapage_cat_ibfk_1` FOREIGN KEY (`cat1`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `top_extrapage_cat_ibfk_2` FOREIGN KEY (`cat2`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `top_extrapage_cat_ibfk_3` FOREIGN KEY (`cat3`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `top_extrapage_cat_ibfk_4` FOREIGN KEY (`cat4`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `top_extrapage_cat_ibfk_5` FOREIGN KEY (`cat5`) REFERENCES `category` (`id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`role`) REFERENCES `role` (`id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
