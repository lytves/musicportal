-- MySQL dump 10.13  Distrib 5.7.17, for Linux (x86_64)
--
-- Host: localhost    Database: domain
-- ------------------------------------------------------
-- Server version	5.5.52-MariaDB

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
-- Table structure for table `xhw7tid4_admin_sections`
--

DROP TABLE IF EXISTS `xhw7tid4_admin_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_admin_sections` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `descr` varchar(255) NOT NULL DEFAULT '',
  `icon` varchar(255) NOT NULL DEFAULT '',
  `allow_groups` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_banned`
--

DROP TABLE IF EXISTS `xhw7tid4_banned`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_banned` (
  `id` smallint(5) NOT NULL AUTO_INCREMENT,
  `users_id` mediumint(8) NOT NULL DEFAULT '0',
  `descr` text NOT NULL,
  `date` varchar(15) NOT NULL DEFAULT '',
  `days` smallint(4) NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `user_id` (`users_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_banners`
--

DROP TABLE IF EXISTS `xhw7tid4_banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_banners` (
  `id` smallint(5) NOT NULL AUTO_INCREMENT,
  `banner_tag` varchar(40) NOT NULL DEFAULT '',
  `descr` varchar(200) NOT NULL DEFAULT '',
  `code` text NOT NULL,
  `approve` tinyint(1) NOT NULL DEFAULT '0',
  `short_place` tinyint(1) NOT NULL DEFAULT '0',
  `bstick` tinyint(1) NOT NULL DEFAULT '0',
  `main` tinyint(1) NOT NULL DEFAULT '0',
  `category` varchar(255) NOT NULL DEFAULT '',
  `grouplevel` varchar(100) NOT NULL DEFAULT 'all',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_category`
--

DROP TABLE IF EXISTS `xhw7tid4_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_category` (
  `id` smallint(5) NOT NULL AUTO_INCREMENT,
  `parentid` smallint(5) NOT NULL DEFAULT '0',
  `posi` smallint(5) NOT NULL DEFAULT '1',
  `name` varchar(50) NOT NULL DEFAULT '',
  `alt_name` varchar(50) NOT NULL DEFAULT '',
  `icon` varchar(200) NOT NULL DEFAULT '',
  `skin` varchar(50) NOT NULL DEFAULT '',
  `descr` varchar(200) NOT NULL DEFAULT '',
  `keywords` text NOT NULL,
  `news_sort` varchar(10) NOT NULL DEFAULT '',
  `news_msort` varchar(4) NOT NULL DEFAULT '',
  `news_number` smallint(5) NOT NULL DEFAULT '0',
  `short_tpl` varchar(40) NOT NULL DEFAULT '',
  `full_tpl` varchar(40) NOT NULL DEFAULT '',
  `metatitle` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_comments`
--

DROP TABLE IF EXISTS `xhw7tid4_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL DEFAULT '0',
  `user_id` mediumint(8) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `autor` varchar(40) NOT NULL DEFAULT '',
  `email` varchar(40) NOT NULL DEFAULT '',
  `text` text NOT NULL,
  `ip` varchar(16) NOT NULL DEFAULT '',
  `is_register` tinyint(1) NOT NULL DEFAULT '0',
  `approve` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  FULLTEXT KEY `text` (`text`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_email`
--

DROP TABLE IF EXISTS `xhw7tid4_email`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_email` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(12) NOT NULL DEFAULT '',
  `template` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_files`
--

DROP TABLE IF EXISTS `xhw7tid4_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_files` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `news_id` int(10) NOT NULL DEFAULT '0',
  `name` varchar(250) NOT NULL DEFAULT '',
  `onserver` varchar(250) NOT NULL DEFAULT '',
  `author` varchar(40) NOT NULL DEFAULT '',
  `date` varchar(15) NOT NULL DEFAULT '',
  `dcount` smallint(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `news_id` (`news_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_flood`
--

DROP TABLE IF EXISTS `xhw7tid4_flood`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_flood` (
  `f_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(16) NOT NULL DEFAULT '',
  `id` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`f_id`),
  KEY `ip` (`ip`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_images`
--

DROP TABLE IF EXISTS `xhw7tid4_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `images` text NOT NULL,
  `news_id` int(10) NOT NULL DEFAULT '0',
  `author` varchar(40) NOT NULL DEFAULT '',
  `date` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `author` (`author`),
  KEY `news_id` (`news_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_logs`
--

DROP TABLE IF EXISTS `xhw7tid4_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `news_id` int(10) NOT NULL DEFAULT '0',
  `member` varchar(40) NOT NULL DEFAULT '',
  `ip` varchar(16) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `news_id` (`news_id`),
  KEY `member` (`member`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_lostdb`
--

DROP TABLE IF EXISTS `xhw7tid4_lostdb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_lostdb` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `lostname` mediumint(8) NOT NULL DEFAULT '0',
  `lostid` varchar(40) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `lostid` (`lostid`)
) ENGINE=MyISAM AUTO_INCREMENT=387 DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_mservice`
--

DROP TABLE IF EXISTS `xhw7tid4_mservice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_mservice` (
  `mid` int(10) NOT NULL AUTO_INCREMENT,
  `time` varchar(20) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `rating` int(10) NOT NULL DEFAULT '0',
  `approve` int(1) NOT NULL,
  `is_demo` int(1) NOT NULL DEFAULT '0',
  `vote_num` int(10) NOT NULL DEFAULT '0',
  `album` int(10) NOT NULL DEFAULT '0',
  `artist` varchar(255) NOT NULL,
  `download` int(10) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `filename` varchar(255) NOT NULL,
  `hdd` int(1) NOT NULL DEFAULT '0',
  `size` int(16) NOT NULL,
  `crc32` varchar(12) NOT NULL,
  `uploader` int(10) NOT NULL,
  `view_count` int(10) NOT NULL DEFAULT '0',
  `transartist` varchar(255) NOT NULL,
  `lenght` varchar(20) NOT NULL DEFAULT '0',
  `bitrate` varchar(20) NOT NULL DEFAULT '0',
  `clip_online` text NOT NULL,
  `clip_online_time` varchar(20) NOT NULL,
  `strip_base64_artist` text NOT NULL,
  `strip_base64_title` text NOT NULL,
  PRIMARY KEY (`mid`),
  UNIQUE KEY `vid` (`mid`),
  KEY `combined1` (`approve`,`uploader`),
  KEY `transartist` (`transartist`),
  KEY `artist_2` (`artist`),
  KEY `album` (`album`),
  KEY `title_2` (`title`),
  KEY `is_demo` (`is_demo`),
  KEY `clip_online_time` (`clip_online_time`),
  KEY `time` (`time`),
  KEY `art_transart` (`artist`,`transartist`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `artist` (`artist`),
  FULLTEXT KEY `artist-title` (`artist`,`title`)
) ENGINE=MyISAM AUTO_INCREMENT=130884 DEFAULT CHARSET=cp1251 PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_mservice_albums`
--

DROP TABLE IF EXISTS `xhw7tid4_mservice_albums`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_mservice_albums` (
  `aid` int(10) NOT NULL AUTO_INCREMENT,
  `time` varchar(20) NOT NULL DEFAULT '0',
  `year` year(4) NOT NULL,
  `album` varchar(255) NOT NULL,
  `artist` varchar(255) NOT NULL,
  `approve` int(1) NOT NULL,
  `image_cover` varchar(255) NOT NULL,
  `image_cover_crc32` varchar(12) NOT NULL,
  `uploader` int(10) NOT NULL,
  `genre` int(1) NOT NULL DEFAULT '0',
  `is_notfullalbum` int(1) NOT NULL DEFAULT '0',
  `is_collection` int(1) NOT NULL DEFAULT '0',
  `collection` text NOT NULL,
  `rating` int(10) NOT NULL DEFAULT '0',
  `download` int(10) NOT NULL DEFAULT '0',
  `vote_num` int(10) NOT NULL DEFAULT '0',
  `view_count` int(10) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `transartist` varchar(255) NOT NULL,
  `strip_base64_artist` text NOT NULL,
  `strip_base64_album` text NOT NULL,
  PRIMARY KEY (`aid`),
  UNIQUE KEY `aid` (`aid`),
  KEY `combined1` (`approve`,`uploader`),
  KEY `album` (`album`),
  KEY `artist` (`artist`),
  FULLTEXT KEY `album2` (`album`)
) ENGINE=MyISAM AUTO_INCREMENT=3753 DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_mservice_artists`
--

DROP TABLE IF EXISTS `xhw7tid4_mservice_artists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_mservice_artists` (
  `artid` int(10) NOT NULL AUTO_INCREMENT,
  `artist` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image_cover` varchar(255) NOT NULL,
  `image_cover150` varchar(255) NOT NULL,
  `genre` int(1) NOT NULL DEFAULT '0',
  `transartist` varchar(255) NOT NULL,
  `strip_base64_artist` text NOT NULL,
  PRIMARY KEY (`artid`),
  KEY `artist` (`artist`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_mservice_commentit`
--

DROP TABLE IF EXISTS `xhw7tid4_mservice_commentit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_mservice_commentit` (
  `num` int(10) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `name` text NOT NULL,
  `is_register` int(1) NOT NULL DEFAULT '0',
  `comm` text NOT NULL,
  `data` datetime NOT NULL,
  `moder` int(1) NOT NULL,
  `ip` text NOT NULL,
  `mail` text NOT NULL,
  `rootid` int(11) NOT NULL,
  `level` int(11) NOT NULL DEFAULT '0',
  `raitng` int(11) NOT NULL DEFAULT '0',
  `iprating` text NOT NULL,
  `http` text NOT NULL,
  `service` text NOT NULL,
  `avatar` text NOT NULL,
  `admincom` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`num`),
  KEY `url` (`url`),
  KEY `is_register` (`is_register`)
) ENGINE=MyISAM AUTO_INCREMENT=4728 DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_mservice_commentit_config`
--

DROP TABLE IF EXISTS `xhw7tid4_mservice_commentit_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_mservice_commentit_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `par` text NOT NULL,
  `val` text NOT NULL,
  `com` text NOT NULL,
  `tmp` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=67 DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_mservice_downloads`
--

DROP TABLE IF EXISTS `xhw7tid4_mservice_downloads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_mservice_downloads` (
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `time_php` varchar(20) NOT NULL,
  `mid` int(10) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `user_id` mediumint(8) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `referer` text NOT NULL,
  KEY `mid` (`mid`),
  KEY `user_id` (`user_id`),
  KEY `time_php` (`time_php`),
  KEY `ip` (`ip`),
  KEY `mid_time_php` (`mid`,`time_php`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_mservice_downloads_albums`
--

DROP TABLE IF EXISTS `xhw7tid4_mservice_downloads_albums`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_mservice_downloads_albums` (
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `time_php` varchar(20) NOT NULL,
  `aid` int(10) NOT NULL,
  `user_id` mediumint(8) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `referer` text NOT NULL,
  KEY `aid` (`aid`),
  KEY `user_id` (`user_id`),
  KEY `time_php` (`time_php`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_mservice_logs`
--

DROP TABLE IF EXISTS `xhw7tid4_mservice_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_mservice_logs` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `mid` int(10) NOT NULL DEFAULT '0',
  `member` varchar(40) NOT NULL,
  `ip` varchar(16) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12639 DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_mservice_logs_albums`
--

DROP TABLE IF EXISTS `xhw7tid4_mservice_logs_albums`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_mservice_logs_albums` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `aid` int(10) NOT NULL DEFAULT '0',
  `member` varchar(40) NOT NULL,
  `ip` varchar(16) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9979 DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_mservice_plays`
--

DROP TABLE IF EXISTS `xhw7tid4_mservice_plays`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_mservice_plays` (
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `time_php` varchar(20) NOT NULL,
  `mid` int(10) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `user_id` mediumint(8) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `referer` text NOT NULL,
  KEY `mid` (`mid`),
  KEY `user_id` (`user_id`),
  KEY `time_php` (`time_php`),
  KEY `ip` (`ip`),
  KEY `mid_time_php` (`mid`,`time_php`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_mservice_search`
--

DROP TABLE IF EXISTS `xhw7tid4_mservice_search`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_mservice_search` (
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `time_php` varchar(20) NOT NULL,
  `count` int(5) NOT NULL,
  `search_text` varchar(255) NOT NULL,
  `search_type` int(1) NOT NULL,
  `user_id` mediumint(8) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `referer` text NOT NULL,
  KEY `search_type` (`search_type`),
  KEY `search_text` (`search_text`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_notice`
--

DROP TABLE IF EXISTS `xhw7tid4_notice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_notice` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) NOT NULL DEFAULT '0',
  `notice` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_pm`
--

DROP TABLE IF EXISTS `xhw7tid4_pm`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_pm` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `subj` varchar(255) NOT NULL DEFAULT '',
  `text` text NOT NULL,
  `user` mediumint(8) NOT NULL DEFAULT '0',
  `user_from` varchar(50) NOT NULL DEFAULT '',
  `date` varchar(15) NOT NULL DEFAULT '',
  `pm_read` char(3) NOT NULL DEFAULT '',
  `folder` varchar(10) NOT NULL DEFAULT '',
  `reply` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `folder` (`folder`),
  KEY `user` (`user`),
  KEY `user_from` (`user_from`)
) ENGINE=MyISAM AUTO_INCREMENT=51080 DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_poll`
--

DROP TABLE IF EXISTS `xhw7tid4_poll`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_poll` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `news_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(200) NOT NULL DEFAULT '',
  `frage` varchar(200) NOT NULL DEFAULT '',
  `body` text NOT NULL,
  `votes` mediumint(8) NOT NULL DEFAULT '0',
  `multiple` tinyint(1) NOT NULL DEFAULT '0',
  `answer` varchar(150) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `news_id` (`news_id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_poll_log`
--

DROP TABLE IF EXISTS `xhw7tid4_poll_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_poll_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `news_id` int(10) unsigned NOT NULL DEFAULT '0',
  `member` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `news_id` (`news_id`,`member`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_post`
--

DROP TABLE IF EXISTS `xhw7tid4_post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `autor` varchar(40) NOT NULL DEFAULT '',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `short_story` text NOT NULL,
  `full_story` text NOT NULL,
  `xfields` text NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `descr` varchar(200) NOT NULL DEFAULT '',
  `keywords` text NOT NULL,
  `category` varchar(200) NOT NULL DEFAULT '0',
  `alt_name` varchar(200) NOT NULL DEFAULT '',
  `comm_num` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `allow_comm` tinyint(1) NOT NULL DEFAULT '1',
  `allow_main` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `allow_rate` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `approve` tinyint(1) NOT NULL DEFAULT '0',
  `fixed` tinyint(1) NOT NULL DEFAULT '0',
  `rating` smallint(5) NOT NULL DEFAULT '0',
  `allow_br` tinyint(1) NOT NULL DEFAULT '1',
  `vote_num` smallint(5) unsigned NOT NULL DEFAULT '0',
  `news_read` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `votes` tinyint(1) NOT NULL DEFAULT '0',
  `access` varchar(150) NOT NULL DEFAULT '',
  `symbol` varchar(3) NOT NULL DEFAULT '',
  `flag` tinyint(1) NOT NULL DEFAULT '1',
  `editdate` varchar(15) NOT NULL DEFAULT '',
  `editor` varchar(40) NOT NULL DEFAULT '',
  `reason` varchar(255) NOT NULL DEFAULT '',
  `view_edit` tinyint(1) NOT NULL DEFAULT '0',
  `tags` varchar(255) NOT NULL DEFAULT '',
  `metatitle` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `autor` (`autor`),
  KEY `alt_name` (`alt_name`),
  KEY `category` (`category`),
  KEY `approve` (`approve`),
  KEY `allow_main` (`allow_main`),
  KEY `date` (`date`),
  KEY `symbol` (`symbol`),
  KEY `comm_num` (`comm_num`),
  KEY `tags` (`tags`),
  FULLTEXT KEY `short_story` (`short_story`,`full_story`,`xfields`,`title`)
) ENGINE=MyISAM AUTO_INCREMENT=46 DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_post_log`
--

DROP TABLE IF EXISTS `xhw7tid4_post_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_post_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `news_id` int(11) NOT NULL DEFAULT '0',
  `expires` varchar(15) NOT NULL DEFAULT '',
  `action` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `news_id` (`news_id`),
  KEY `expires` (`expires`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_quest`
--

DROP TABLE IF EXISTS `xhw7tid4_quest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_quest` (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT 'айди',
  `quest` varchar(50) NOT NULL COMMENT 'вопрос',
  `answer` varchar(50) NOT NULL COMMENT 'атвет',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_rss`
--

DROP TABLE IF EXISTS `xhw7tid4_rss`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_rss` (
  `id` smallint(5) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `allow_main` tinyint(1) NOT NULL DEFAULT '0',
  `allow_rating` tinyint(1) NOT NULL DEFAULT '0',
  `allow_comm` tinyint(1) NOT NULL DEFAULT '0',
  `text_type` tinyint(1) NOT NULL DEFAULT '0',
  `date` tinyint(1) NOT NULL DEFAULT '0',
  `search` text NOT NULL,
  `max_news` tinyint(3) NOT NULL DEFAULT '0',
  `cookie` text NOT NULL,
  `category` smallint(5) NOT NULL DEFAULT '0',
  `lastdate` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_rssinform`
--

DROP TABLE IF EXISTS `xhw7tid4_rssinform`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_rssinform` (
  `id` smallint(5) NOT NULL AUTO_INCREMENT,
  `tag` varchar(40) NOT NULL DEFAULT '',
  `descr` varchar(255) NOT NULL DEFAULT '',
  `category` varchar(200) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `template` varchar(40) NOT NULL DEFAULT '',
  `news_max` smallint(5) NOT NULL DEFAULT '0',
  `tmax` smallint(5) NOT NULL DEFAULT '0',
  `dmax` smallint(5) NOT NULL DEFAULT '0',
  `approve` tinyint(1) NOT NULL DEFAULT '1',
  `rss_date_format` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_static`
--

DROP TABLE IF EXISTS `xhw7tid4_static`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_static` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `descr` varchar(255) NOT NULL DEFAULT '',
  `template` text NOT NULL,
  `allow_br` tinyint(1) NOT NULL DEFAULT '0',
  `allow_template` tinyint(1) NOT NULL DEFAULT '0',
  `grouplevel` varchar(100) NOT NULL DEFAULT 'all',
  `tpl` varchar(40) NOT NULL DEFAULT '',
  `metadescr` varchar(200) NOT NULL DEFAULT '',
  `metakeys` text NOT NULL,
  `views` mediumint(8) NOT NULL DEFAULT '0',
  `template_folder` varchar(50) NOT NULL DEFAULT '',
  `date` varchar(15) NOT NULL DEFAULT '',
  `metatitle` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  FULLTEXT KEY `template` (`template`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_static_files`
--

DROP TABLE IF EXISTS `xhw7tid4_static_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_static_files` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `static_id` mediumint(8) NOT NULL DEFAULT '0',
  `author` varchar(40) NOT NULL DEFAULT '',
  `date` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `onserver` varchar(255) NOT NULL DEFAULT '',
  `dcount` smallint(5) NOT NULL DEFAULT '0',
  `buy` double(9,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `static_id` (`static_id`),
  KEY `onserver` (`onserver`),
  KEY `author` (`author`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_subscribe`
--

DROP TABLE IF EXISTS `xhw7tid4_subscribe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_subscribe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(40) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `news_id` int(11) NOT NULL DEFAULT '0',
  `hash` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `news_id` (`news_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_tags`
--

DROP TABLE IF EXISTS `xhw7tid4_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `news_id` int(11) NOT NULL DEFAULT '0',
  `tag` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `news_id` (`news_id`),
  KEY `tag` (`tag`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_unitpay_payments`
--

DROP TABLE IF EXISTS `xhw7tid4_unitpay_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_unitpay_payments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `unitpayId` varchar(255) NOT NULL,
  `account` varchar(255) NOT NULL,
  `sum` float NOT NULL,
  `dateCreate` datetime NOT NULL,
  `dateComplete` datetime DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_usergroups`
--

DROP TABLE IF EXISTS `xhw7tid4_usergroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_usergroups` (
  `id` smallint(5) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(32) NOT NULL DEFAULT '',
  `allow_cats` text NOT NULL,
  `allow_adds` tinyint(1) NOT NULL DEFAULT '1',
  `cat_add` text NOT NULL,
  `allow_admin` tinyint(1) NOT NULL DEFAULT '0',
  `allow_addc` tinyint(1) NOT NULL DEFAULT '0',
  `allow_editc` tinyint(1) NOT NULL DEFAULT '0',
  `allow_delc` tinyint(1) NOT NULL DEFAULT '0',
  `edit_allc` tinyint(1) NOT NULL DEFAULT '0',
  `del_allc` tinyint(1) NOT NULL DEFAULT '0',
  `moderation` tinyint(1) NOT NULL DEFAULT '0',
  `allow_all_edit` tinyint(1) NOT NULL DEFAULT '0',
  `allow_edit` tinyint(1) NOT NULL DEFAULT '0',
  `allow_pm` tinyint(1) NOT NULL DEFAULT '0',
  `max_pm` smallint(5) NOT NULL DEFAULT '0',
  `max_foto` varchar(10) NOT NULL DEFAULT '',
  `allow_files` tinyint(1) NOT NULL DEFAULT '1',
  `allow_hide` tinyint(1) NOT NULL DEFAULT '1',
  `allow_short` tinyint(1) NOT NULL DEFAULT '0',
  `time_limit` tinyint(1) NOT NULL DEFAULT '0',
  `rid` smallint(5) NOT NULL DEFAULT '0',
  `allow_fixed` tinyint(1) NOT NULL DEFAULT '0',
  `allow_feed` tinyint(1) NOT NULL DEFAULT '1',
  `allow_search` tinyint(1) NOT NULL DEFAULT '1',
  `allow_poll` tinyint(1) NOT NULL DEFAULT '1',
  `allow_main` tinyint(1) NOT NULL DEFAULT '1',
  `captcha` tinyint(1) NOT NULL DEFAULT '0',
  `icon` varchar(200) NOT NULL DEFAULT '',
  `allow_modc` tinyint(1) NOT NULL DEFAULT '0',
  `allow_rating` tinyint(1) NOT NULL DEFAULT '1',
  `allow_offline` tinyint(1) NOT NULL DEFAULT '0',
  `allow_image_upload` tinyint(1) NOT NULL DEFAULT '0',
  `allow_file_upload` tinyint(1) NOT NULL DEFAULT '0',
  `allow_signature` tinyint(1) NOT NULL DEFAULT '0',
  `allow_url` tinyint(1) NOT NULL DEFAULT '1',
  `news_sec_code` tinyint(1) NOT NULL DEFAULT '1',
  `allow_image` tinyint(1) NOT NULL DEFAULT '0',
  `max_signature` smallint(6) NOT NULL DEFAULT '0',
  `max_info` smallint(6) NOT NULL DEFAULT '0',
  `admin_addnews` tinyint(1) NOT NULL DEFAULT '0',
  `admin_editnews` tinyint(1) NOT NULL DEFAULT '0',
  `admin_comments` tinyint(1) NOT NULL DEFAULT '0',
  `admin_categories` tinyint(1) NOT NULL DEFAULT '0',
  `admin_editusers` tinyint(1) NOT NULL DEFAULT '0',
  `admin_wordfilter` tinyint(1) NOT NULL DEFAULT '0',
  `admin_xfields` tinyint(1) NOT NULL DEFAULT '0',
  `admin_userfields` tinyint(1) NOT NULL DEFAULT '0',
  `admin_static` tinyint(1) NOT NULL DEFAULT '0',
  `admin_editvote` tinyint(1) NOT NULL DEFAULT '0',
  `admin_newsletter` tinyint(1) NOT NULL DEFAULT '0',
  `admin_blockip` tinyint(1) NOT NULL DEFAULT '0',
  `admin_banners` tinyint(1) NOT NULL DEFAULT '0',
  `admin_rss` tinyint(1) NOT NULL DEFAULT '0',
  `admin_iptools` tinyint(1) NOT NULL DEFAULT '0',
  `admin_rssinform` tinyint(1) NOT NULL DEFAULT '0',
  `admin_googlemap` tinyint(1) NOT NULL DEFAULT '0',
  `allow_html` tinyint(1) NOT NULL DEFAULT '1',
  `group_prefix` text NOT NULL,
  `group_suffix` text NOT NULL,
  `allow_subscribe` tinyint(1) NOT NULL DEFAULT '0',
  `mservice_allow_rating` tinyint(1) DEFAULT '0',
  `mservice_filedown` tinyint(1) DEFAULT '0',
  `mservice_filedown_captcha` tinyint(1) DEFAULT '0',
  `mservice_addfile` tinyint(1) DEFAULT '0',
  `mservice_captcha` tinyint(1) DEFAULT '0',
  `mservice_newtrack_approve` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_users`
--

DROP TABLE IF EXISTS `xhw7tid4_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_users` (
  `email` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `name` varchar(40) NOT NULL DEFAULT '',
  `user_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `news_num` smallint(6) NOT NULL DEFAULT '0',
  `comm_num` mediumint(8) NOT NULL DEFAULT '0',
  `user_group` smallint(5) NOT NULL DEFAULT '4',
  `lastdate` varchar(20) DEFAULT NULL,
  `reg_date` varchar(20) DEFAULT NULL,
  `banned` varchar(5) NOT NULL DEFAULT '',
  `allow_mail` tinyint(1) NOT NULL DEFAULT '1',
  `info` text NOT NULL,
  `signature` text NOT NULL,
  `foto` varchar(30) NOT NULL DEFAULT '',
  `fullname` varchar(100) NOT NULL DEFAULT '',
  `land` varchar(100) NOT NULL DEFAULT '',
  `icq` varchar(20) NOT NULL DEFAULT '',
  `favorites` text NOT NULL,
  `favorites_mservice` text NOT NULL,
  `favorites_artists_mservice` text NOT NULL,
  `favorites_albums_mservice` text NOT NULL,
  `pm_all` smallint(5) NOT NULL DEFAULT '0',
  `pm_unread` smallint(5) NOT NULL DEFAULT '0',
  `time_limit` varchar(20) NOT NULL DEFAULT '',
  `xfields` text NOT NULL,
  `allowed_ip` varchar(255) NOT NULL DEFAULT '',
  `hash` varchar(32) NOT NULL DEFAULT '',
  `logged_ip` varchar(16) NOT NULL DEFAULT '',
  `restricted` tinyint(1) NOT NULL DEFAULT '0',
  `restricted_days` smallint(4) NOT NULL DEFAULT '0',
  `restricted_date` varchar(15) NOT NULL DEFAULT '',
  `vk_user_id` varchar(30) NOT NULL,
  `vk_connected` int(1) NOT NULL DEFAULT '0',
  `vk_registered` int(1) NOT NULL DEFAULT '0',
  `vk_hash_auth` text NOT NULL,
  `vk_user_phone` varchar(30) NOT NULL,
  `vk_user_friends` text NOT NULL,
  `fb_user_id` varchar(30) NOT NULL,
  `fb_connected` int(1) NOT NULL DEFAULT '0',
  `fb_registered` int(1) NOT NULL DEFAULT '0',
  `fb_hash_auth` text NOT NULL,
  `fb_user_friends` text NOT NULL,
  `tw_user_id` varchar(40) NOT NULL,
  `tw_connected` int(1) NOT NULL DEFAULT '0',
  `tw_registered` int(1) NOT NULL DEFAULT '0',
  `tw_user_friends` text NOT NULL,
  `od_user_id` varchar(30) NOT NULL,
  `od_connected` int(1) NOT NULL DEFAULT '0',
  `od_registered` int(1) NOT NULL DEFAULT '0',
  `in_user_id` varchar(30) NOT NULL,
  `in_username` varchar(50) NOT NULL,
  `in_connected` int(1) NOT NULL DEFAULT '0',
  `in_registered` int(1) NOT NULL DEFAULT '0',
  `gh_user_id` varchar(30) NOT NULL,
  `gh_username` varchar(50) NOT NULL,
  `gh_connected` int(1) NOT NULL DEFAULT '0',
  `gh_registered` int(1) NOT NULL DEFAULT '0',
  `ms_user_id` varchar(40) NOT NULL,
  `ms_link` varchar(90) NOT NULL,
  `ms_connected` int(1) NOT NULL DEFAULT '0',
  `ms_registered` int(1) NOT NULL DEFAULT '0',
  `ma_user_id` varchar(40) NOT NULL,
  `ma_link` varchar(90) NOT NULL,
  `ma_connected` int(1) NOT NULL DEFAULT '0',
  `ma_registered` int(1) NOT NULL DEFAULT '0',
  `fs_user_id` varchar(40) NOT NULL,
  `fs_connected` int(1) NOT NULL DEFAULT '0',
  `fs_registered` int(1) NOT NULL DEFAULT '0',
  `fs_hash_auth` text NOT NULL,
  `fs_user_friends` text NOT NULL,
  `go_user_id` varchar(40) NOT NULL,
  `go_connected` int(1) NOT NULL DEFAULT '0',
  `go_registered` int(1) NOT NULL DEFAULT '0',
  `ya_user_id` varchar(30) NOT NULL,
  `ya_username` varchar(100) NOT NULL,
  `ya_registered` int(1) NOT NULL,
  `ya_connected` int(11) NOT NULL,
  `sex` varchar(30) NOT NULL,
  `bdate` varchar(30) NOT NULL,
  `updtime` varchar(30) NOT NULL,
  `userfriends` text NOT NULL,
  `userpassword_hash` text NOT NULL,
  `use_demo_vip_group` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=61740 DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_views`
--

DROP TABLE IF EXISTS `xhw7tid4_views`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_views` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `news_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_vote`
--

DROP TABLE IF EXISTS `xhw7tid4_vote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_vote` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `category` text NOT NULL,
  `vote_num` mediumint(8) NOT NULL DEFAULT '0',
  `date` varchar(25) NOT NULL DEFAULT '0',
  `title` varchar(200) NOT NULL DEFAULT '',
  `body` text NOT NULL,
  `approve` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `approve` (`approve`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xhw7tid4_vote_result`
--

DROP TABLE IF EXISTS `xhw7tid4_vote_result`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xhw7tid4_vote_result` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ip` varchar(16) NOT NULL DEFAULT '',
  `name` varchar(40) NOT NULL DEFAULT '',
  `vote_id` mediumint(8) NOT NULL DEFAULT '0',
  `answer` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `answer` (`answer`),
  KEY `vote_id` (`vote_id`),
  KEY `ip` (`ip`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=cp1251;
