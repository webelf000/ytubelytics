-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 19, 2016 at 08:37 PM
-- Server version: 10.1.10-MariaDB
-- PHP Version: 7.0.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `xeroneit_youtube`
--

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(200) NOT NULL,
  `ip_address` varchar(200) NOT NULL,
  `user_agent` varchar(199) NOT NULL,
  `last_activity` varchar(199) NOT NULL,
  `user_data` longtext CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS `config`;
CREATE TABLE IF NOT EXISTS `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `google_safety_api` text,
  `moz_access_id` varchar(100) DEFAULT NULL,
  `moz_secret_key` varchar(100) DEFAULT NULL,
  `mobile_ready_api_key` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `config_proxy`;
CREATE TABLE IF NOT EXISTS `config_proxy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `proxy` varchar(100) DEFAULT NULL,
  `port` varchar(20) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `admin_permission` varchar(100) NOT NULL DEFAULT 'only me',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `is_google_valid` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `email_config`
--

DROP TABLE IF EXISTS `email_config`;
CREATE TABLE IF NOT EXISTS `email_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `email_address` varchar(100) NOT NULL,
  `smtp_host` varchar(100) NOT NULL,
  `smtp_port` varchar(100) NOT NULL,
  `smtp_user` varchar(100) NOT NULL,
  `smtp_password` varchar(100) NOT NULL,
  `status` enum('0','1') NOT NULL,
  `deleted` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;



-- --------------------------------------------------------

--
-- Table structure for table `forget_password`
--

DROP TABLE IF EXISTS `forget_password`;
CREATE TABLE IF NOT EXISTS `forget_password` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `confirmation_code` varchar(15) CHARACTER SET latin1 NOT NULL,
  `email` varchar(100) CHARACTER SET latin1 NOT NULL,
  `success` int(11) NOT NULL DEFAULT '0',
  `expiration` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `deleted` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `module_name`, `deleted`) VALUES
(26, 'Video Search Engine', '0'),
(27, 'Video Rank Tracking', '0'),
(33, 'Import Account', '0');

-- --------------------------------------------------------

--
-- Table structure for table `native_api`
--

DROP TABLE IF EXISTS `native_api`;
CREATE TABLE IF NOT EXISTS `native_api` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `api_key` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `package`
--

DROP TABLE IF EXISTS `package`;
CREATE TABLE IF NOT EXISTS `package` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_name` varchar(250) NOT NULL,
  `module_ids` varchar(250) NOT NULL,
  `monthly_limit` text,
  `bulk_limit` text,
  `price` varchar(20) NOT NULL DEFAULT '0',
  `validity` int(11) NOT NULL,
  `is_default` enum('0','1') NOT NULL,
  `deleted` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `package`
--

INSERT INTO `package` (`id`, `package_name`, `module_ids`, `monthly_limit`, `bulk_limit`, `price`, `validity`, `is_default`, `deleted`) VALUES
(1, 'Trial', '35,62,37,33,34,63,36,27,26,39', '{"35":"0","62":"0","37":"0","33":"0","34":"0","63":"0","36":"0","27":"0","26":"0","39":"0"}', '{"35":0,"62":0,"37":0,"33":0,"34":0,"63":0,"36":0,"27":0,"26":0,"39":0}', 'Trial', 7, '1', '0');


-- --------------------------------------------------------

--
-- Table structure for table `payment_config`
--

DROP TABLE IF EXISTS `payment_config`;
CREATE TABLE IF NOT EXISTS `payment_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paypal_email` varchar(250) NOT NULL,
  `stripe_secret_key` varchar(150) NOT NULL,
  `stripe_publishable_key` varchar(150) NOT NULL,
  `currency` enum('USD','AUD','CAD','EUR','ILS','NZD','RUB','SGD','SEK','BRL','VND') CHARACTER SET utf8 NOT NULL,
  `deleted` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment_config`
--

INSERT INTO `payment_config` (`id`, `paypal_email`, `stripe_secret_key`, `stripe_publishable_key`, `currency`, `deleted`) VALUES
(1, 'example@gmail.com', '', '', 'USD', '0');

-- --------------------------------------------------------

--
-- Table structure for table `usage_log`
--

DROP TABLE IF EXISTS `usage_log`;
CREATE TABLE IF NOT EXISTS `usage_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `usage_month` int(11) NOT NULL,
  `usage_year` year(4) NOT NULL,
  `usage_count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `usage_log`
--


--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(99) NOT NULL,
  `email` varchar(99) NOT NULL,
  `mobile` varchar(100) NOT NULL,
  `password` varchar(99) NOT NULL,
  `address` text CHARACTER SET utf8 NOT NULL,
  `user_type` enum('Member','Admin') NOT NULL,
  `status` enum('1','0') NOT NULL,
  `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `activation_code` varchar(20) DEFAULT NULL,
  `expired_date` datetime NOT NULL,
  `package_id` int(11) NOT NULL,
  `deleted` enum('0','1') NOT NULL,
  `brand_logo` text CHARACTER SET utf8,
  `brand_url` text CHARACTER SET utf8,
  `vat_no` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `currency` enum('USD','AUD','CAD','EUR','ILS','NZD','RUB','SGD','SEK','BRL') NOT NULL DEFAULT 'USD',
  `company_email` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `paypal_email` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

ALTER TABLE `users` ADD UNIQUE(`email`);
--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `mobile`, `password`, `address`, `user_type`, `status`, `add_date`, `activation_code`, `expired_date`, `package_id`, `deleted`, `brand_logo`, `brand_url`, `vat_no`, `currency`, `company_email`, `paypal_email`) VALUES
(1, 'Boss', 'admin@gmail.com', '+8801729853645', '259534db5d66c3effb7aa2dbbee67ab0', '127, gonokpara, \nrajshahi-6100', 'Admin', '1', '2015-12-31 18:00:00', NULL, '0000-00-00 00:00:00', 0, '0', '', '', NULL, 'USD', NULL, '');


-- --------------------------------------------------------

--
-- Table structure for table `video_position_report`
--

DROP TABLE IF EXISTS `video_position_report`;
CREATE TABLE IF NOT EXISTS `video_position_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `youtube_position` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;


--
-- Table structure for table `video_position_set`
--

DROP TABLE IF EXISTS `video_position_set`;
CREATE TABLE IF NOT EXISTS `video_position_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(250) NOT NULL,
  `youtube_video_id` varchar(250) NOT NULL,
  `user_id` int(11) NOT NULL,
  `add_date` datetime NOT NULL,
  `deleted` int(11) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Table structure for table `youtube_channel_info`
--

DROP TABLE IF EXISTS `youtube_channel_info`;
CREATE TABLE IF NOT EXISTS `youtube_channel_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `access_token` text,
  `refresh_token` text,
  `last_update` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;


--
-- Table structure for table `youtube_channel_list`
--

DROP TABLE IF EXISTS `youtube_channel_list`;
CREATE TABLE IF NOT EXISTS `youtube_channel_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `channel_info_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `channel_id` varchar(200) NOT NULL,
  `title` text,
  `description` text,
  `profile_image` text,
  `cover_image` text,
  `view_count` varchar(250) DEFAULT NULL,
  `video_count` varchar(250) DEFAULT NULL,
  `subscriber_count` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


--
-- Table structure for table `youtube_config`
--

DROP TABLE IF EXISTS `youtube_config`;
CREATE TABLE IF NOT EXISTS `youtube_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `google_client_id` varchar(250) NOT NULL,
  `google_secret` varchar(250) NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `status` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Table structure for table `youtube_video_list`
--

DROP TABLE IF EXISTS `youtube_video_list`;
CREATE TABLE IF NOT EXISTS `youtube_video_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `channel_id` varchar(200) DEFAULT NULL,
  `video_id` varchar(200) DEFAULT NULL,
  `title` text,
  `image_link` text,
  `publish_time` varchar(200) DEFAULT NULL,
  `tags` text,
  `categoryId` int(11) DEFAULT NULL,
  `liveBroadcastContent` varchar(250) DEFAULT NULL,
  `duration` varchar(250) DEFAULT NULL,
  `dimension` varchar(200) DEFAULT NULL,
  `definition` varchar(200) DEFAULT NULL,
  `caption` text,
  `licensedContent` text,
  `projection` varchar(250) DEFAULT NULL,
  `viewCount` int(11) DEFAULT NULL,
  `likeCount` int(11) DEFAULT NULL,
  `dislikeCount` int(11) DEFAULT NULL,
  `favoriteCount` int(11) DEFAULT NULL,
  `commentCount` int(11) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;


--
-- Table structure for table `youtube_video_upload`
--

DROP TABLE IF EXISTS `youtube_video_upload`;
CREATE TABLE IF NOT EXISTS `youtube_video_upload` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `channel_id` varchar(200) DEFAULT NULL,
  `video_id` varchar(150) DEFAULT NULL,
  `title` text,
  `description` text,
  `tags` text,
  `category` varchar(100) DEFAULT NULL,
  `privacy_type` varchar(100) DEFAULT NULL,
  `link` varchar(150) DEFAULT NULL,
  `time_zone` varchar(100) DEFAULT NULL,
  `upload_time` varchar(100) DEFAULT NULL,
  `upload_status` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;



CREATE
 ALGORITHM = UNDEFINED
 VIEW `view_usage_log`
 (id,module_id,user_id,usage_month,usage_year,usage_count)
 AS select * from usage_log where `usage_month`=MONTH(curdate()) and `usage_year`= YEAR(curdate()) ;




INSERT INTO `modules` (`id`, `module_name`, `deleted`) VALUES (NULL, 'Youtube Keyword Scraper', '0');
INSERT INTO `modules` (`id`, `module_name`, `deleted`) VALUES (NULL, 'Youtube Auto Keyword Suggestion', '0');
INSERT INTO `modules` (`id`, `module_name`, `deleted`) VALUES (NULL, 'Youtube Subscribe Button Plugin', '0');
INSERT INTO `modules` (`id`, `module_name`, `deleted`) VALUES (NULL, 'Youtube Custom Video Downloader', '0');
INSERT INTO `modules` (`id`, `module_name`, `deleted`) VALUES (NULL, 'Youtube Subtitle Downloader', '0');
INSERT INTO `modules` (`id`, `module_name`, `deleted`) VALUES (NULL, 'Youtube Video Uploader & Live Event', '0');
INSERT INTO `modules` (`id`, `module_name`, `deleted`) VALUES ('62', 'Channel Search engine', '0'), ('63', 'Playlist Search engine', '0');


CREATE TABLE IF NOT EXISTS `youtube_live_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `channel_id` varchar(100) NOT NULL,
  `Broadcast_id` varchar(100) DEFAULT NULL,
  `Stream_id` varchar(100) DEFAULT NULL,
  `boundBroadcast_id` varchar(100) DEFAULT NULL,
  `boundStream_id` varchar(100) DEFAULT NULL,
  `title` text,
  `description` text,
  `tags` text,
  `privacy_type` varchar(100) DEFAULT NULL,
  `time_zone` varchar(100) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `check_status` enum('0','1') NOT NULL,
  `tags_position` text NOT NULL,
  `last_updated` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `transaction_history` (
  `id` int(11) NOT NULL,
  `verify_status` varchar(200) NOT NULL,
  `first_name` varchar(250) CHARACTER SET utf8 NOT NULL,
  `last_name` varchar(250) CHARACTER SET utf8 NOT NULL,
  `paypal_email` varchar(200) NOT NULL,
  `receiver_email` varchar(200) NOT NULL,
  `country` varchar(100) NOT NULL,
  `payment_date` varchar(250) NOT NULL,
  `payment_type` varchar(100) NOT NULL,
  `transaction_id` varchar(150) NOT NULL,
  `paid_amount` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cycle_start_date` date NOT NULL,
  `cycle_expired_date` date NOT NULL,
  `package_id` int(11) NOT NULL,
  `stripe_card_source` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `transaction_history`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `transaction_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

  ALTER TABLE `modules` ADD `extra_text` VARCHAR(20) NOT NULL DEFAULT '/month' AFTER `deleted`;
  UPDATE `modules` SET `extra_text` = '' WHERE `modules`.`id` = 33;
  DELETE FROM `modules` WHERE`modules`.`id` = 38;



  
  ALTER TABLE `payment_config` CHANGE `currency` `currency` ENUM('AUD','BRL','CAD','CZK','DKK','EUR','HKD','HUF','ILS','JPY','MYR','MXN','TWD','NZD','NOK','PHP','PLN','GBP','RUB','SGD','SEK','CHF','USD','VND') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
