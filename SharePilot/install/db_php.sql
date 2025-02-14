-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: sharepilot
-- ------------------------------------------------------
-- Server version	8.0.32

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `channel_media_keys`
--

DROP TABLE IF EXISTS `channel_media_keys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `channel_media_keys` (
  `id` int NOT NULL AUTO_INCREMENT,
  `channel_id` int DEFAULT NULL,
  `media_id` int DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `value` varchar(500) DEFAULT NULL,
  `regdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `channels`
--

DROP TABLE IF EXISTS `channels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `channels` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(150) DEFAULT NULL,
  `regdate` datetime DEFAULT NULL,
  `active` tinyint DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lists`
--

DROP TABLE IF EXISTS `lists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lists` (
  `id` int NOT NULL AUTO_INCREMENT,
  `channel_id` int DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `scheduled_posts`
--

DROP TABLE IF EXISTS `scheduled_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `scheduled_posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `url_id` int DEFAULT NULL,
  `list_id` int DEFAULT NULL,
  `channel_id` int DEFAULT NULL,
  `post_time` datetime DEFAULT NULL,
  `posted_time` datetime DEFAULT NULL,
  `is_posted` tinyint DEFAULT NULL,
  `regdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_scheduled_posts_url_id` (`url_id`),
  CONSTRAINT `scheduled_posts_urls` FOREIGN KEY (`url_id`) REFERENCES `urls` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `timezone` varchar(45) DEFAULT NULL,
  `crontoken` varchar(250) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;
insert into `settings` (timezone, email) values('UTC', 'default@default.gr');


--
-- Table structure for table `media_categories`
--

DROP TABLE IF EXISTS `media_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media_categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL, 
  `regdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb3;
insert into media_categories (name, regdate) values('All', now());
insert into media_categories (name, regdate) values('Add new media category', now());

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `regdate` datetime DEFAULT NULL,
  `active` tinyint DEFAULT NULL,
  `media_category_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`media_category_id`) REFERENCES `media_categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;
insert into media (name, regdate, active) values('facebook', now(), 1);
insert into media (name, regdate, active) values('twitter', now(), 1);
insert into media (name, regdate, active) values('instagram', now(), 1);
insert into media (name, regdate, active) values('linkedin', now(), 1);

--
-- Table structure for table `sources`
--

DROP TABLE IF EXISTS `sources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sources` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `types`
--

DROP TABLE IF EXISTS `types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `urls`
--

DROP TABLE IF EXISTS `urls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `urls` (
  `id` int NOT NULL AUTO_INCREMENT,
  `channel_id` int DEFAULT NULL,
  `list_id` int DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `source` int NOT NULL,
  `type` int NOT NULL,
  `thumbnailUrl` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `regdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_urls_id` (`id`),
  KEY `fk_urls_channels_idx` (`channel_id`),
  CONSTRAINT `fk_urls_channels` FOREIGN KEY (`channel_id`) REFERENCES `channels` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `urlsarchived`
--

DROP TABLE IF EXISTS `urlsarchived`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `urlsarchived` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `dateInserted` datetime NOT NULL,
  `datePosted` datetime DEFAULT NULL,
  `source` int NOT NULL,
  `type` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `source` (`source`),
  KEY `type` (`type`),
  CONSTRAINT `urlsarchived_ibfk_1` FOREIGN KEY (`source`) REFERENCES `sources` (`id`),
  CONSTRAINT `urlsarchived_ibfk_2` FOREIGN KEY (`type`) REFERENCES `types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` int NOT NULL,
  `mobilephone` varchar(20) DEFAULT NULL,
  `regdate` date DEFAULT NULL,
  `token` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb3;


-- Dropping existing tables if they exist
DROP TABLE IF EXISTS `subscriptions`;
DROP TABLE IF EXISTS `topics`;
DROP TABLE IF EXISTS `subscription_types`;
DROP TABLE IF EXISTS `subscribers`;
DROP TABLE IF EXISTS `subscription_tokens`;
DROP TABLE IF EXISTS `device_types`;


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;

-- Creating a table for storing subscriber details
CREATE TABLE `subscribers` (
    `id` int NOT NULL AUTO_INCREMENT,
    `firstName` varchar(100) DEFAULT NULL,
    `lastName` varchar(100) DEFAULT NULL,
    `email` varchar(100) DEFAULT NULL,
    `mobileNumber` varchar(15) DEFAULT NULL,
    `userAgent_details` varchar(2048) DEFAULT NULL,
    `regdate` datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Creating a table for different types of subscriptions
CREATE TABLE `subscription_types` (
    `id` int NOT NULL AUTO_INCREMENT,
    `typeName` varchar(50) NOT NULL,
    `description` text,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
-- Inserting messaging subscription types into the subscription_types table
INSERT INTO `subscription_types` (`typeName`, `description`) 
VALUES
('Email Subscription', 'Periodic emails including newsletters, promotions, and personalized messages.'),
('SMS Subscription', 'Short message service notifications sent directly to the mobile phone.'),
('Push Notification', 'Notifications sent through mobile or web app directly to the device.'),
('Instant Messaging Subscription', 'Updates sent through platforms like WhatsApp, Telegram, or Messenger.'),
('Social Media Updates', 'Notifications sent through social media platforms like Twitter, Facebook, or Instagram.'),
('RSS Feeds', 'Automatically updated feeds for news and updates, subscribable via RSS reader.');

-- Creating a table for topics
CREATE TABLE `topics` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Creating a table to link subscribers, subscription types, and topics
CREATE TABLE `subscriptions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `subscriber_id` int NOT NULL,
  `subscriptionType_id` int NOT NULL,
  `topic_id` int NOT NULL,
  `isActive` boolean DEFAULT TRUE,
  `subscribedOn` datetime DEFAULT CURRENT_TIMESTAMP,
  `unsubscribedOn` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subscriber_id_idx` (`subscriber_id`),
  KEY `subscription_type_id_idx` (`subscriptionType_id`),
  KEY `topic_id_idx` (`topic_id`),
  CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`subscriber_id`) REFERENCES `subscribers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `subscriptions_ibfk_2` FOREIGN KEY (`subscriptionType_id`) REFERENCES `subscription_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `subscriptions_ibfk_3` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- Creating a table for storing tokens related to subscriptions
CREATE TABLE `device_types` (
    `id` int NOT NULL AUTO_INCREMENT,
    `type_name` varchar(50) NOT NULL,    
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `device_types` (type_name)
VALUES 
('Mobile'),
('Tablet'),
('PC');



CREATE TABLE `subscription_tokens` (
    `id` int NOT NULL AUTO_INCREMENT,
    `subscription_id` int NOT NULL,
    `token` varchar(255) NOT NULL,
    `device_type_id` int DEFAULT NULL,  -- Now referencing the device_types table
    `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`),
    FOREIGN KEY (`device_type_id`) REFERENCES `device_types` (`id`)  -- New foreign key
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- Stored Procedures
--
DROP procedure IF EXISTS `schedule_posts`;
CREATE PROCEDURE `schedule_posts`(start_datetime DATETIME, hourInterval int, channel_id int, avoid_start_hour INT, avoid_end_hour INT, OUT procedure_success BOOLEAN)
BEGIN
  DECLARE current_id INT;
  DECLARE done INT DEFAULT FALSE;
  DECLARE cur CURSOR FOR SELECT * FROM final_ids;
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

  DROP TEMPORARY TABLE IF EXISTS final_ids;
  
  IF(channel_id IS NULL OR channel_id = 0) THEN
    CREATE TEMPORARY TABLE final_ids AS 
      SELECT urls.id 
      FROM urls
      WHERE urls.id NOT IN (SELECT url_id FROM scheduled_posts)
      ORDER BY urls.id;
  ELSE
    CREATE TEMPORARY TABLE final_ids AS
      SELECT urls.id 
      FROM urls
      WHERE urls.id NOT IN (SELECT url_id FROM scheduled_posts)
      AND urls.channel_id = channel_id 
      ORDER BY urls.id;
  END IF;

  SET @row_num := 0;
  SET @next_post_time := start_datetime;

  OPEN cur;

  read_loop: LOOP
    FETCH cur INTO current_id;
    IF done THEN
      LEAVE read_loop;
    END IF;

    WHILE HOUR(@next_post_time) BETWEEN avoid_start_hour AND avoid_end_hour DO
      SET @next_post_time := DATE_ADD(@next_post_time, INTERVAL hourInterval HOUR);
    END WHILE;

    INSERT INTO scheduled_posts (url_id, post_time)
    VALUES (current_id, @next_post_time);

    SET @next_post_time := DATE_ADD(@next_post_time, INTERVAL hourInterval HOUR);
    
    SET procedure_success := TRUE;

  END LOOP;

  CLOSE cur;

  DROP TEMPORARY TABLE IF EXISTS final_ids;
  
  IF procedure_success IS NULL THEN
    SET procedure_success := FALSE;
  END IF;
END;


DROP procedure IF EXISTS `restateschedule_posts`;
CREATE PROCEDURE `restateschedule_posts`(start_datetime DATETIME, hourInterval int, channel_id int, avoid_start_hour INT, avoid_end_hour INT, OUT procedure_success BOOLEAN)
BEGIN
  DECLARE current_id INT;
  DECLARE done INT DEFAULT FALSE;
  DECLARE cur CURSOR FOR SELECT * FROM final_ids;
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

  DROP TEMPORARY TABLE IF EXISTS final_ids;

  IF(channel_id IS NULL OR channel_id = 0) THEN
    CREATE TEMPORARY TABLE final_ids AS
      SELECT id
      FROM scheduled_posts
      ORDER BY id;
  ELSE
    CREATE TEMPORARY TABLE final_ids AS
      SELECT id
      FROM scheduled_posts
      WHERE channel_id = channel_id
      ORDER BY id;
  END IF;

  SET @next_post_time := start_datetime;

  OPEN cur;

  read_loop: LOOP
    FETCH cur INTO current_id;
    IF done THEN
      LEAVE read_loop;
    END IF;

    WHILE HOUR(@next_post_time) BETWEEN avoid_start_hour AND avoid_end_hour DO
      SET @next_post_time := DATE_ADD(@next_post_time, INTERVAL hourInterval HOUR);
    END WHILE;

    UPDATE scheduled_posts 
    SET post_time = @next_post_time 
    WHERE id = current_id;

    SET @next_post_time := DATE_ADD(@next_post_time, INTERVAL hourInterval HOUR);
    
    SET procedure_success := TRUE;
  END LOOP;

  CLOSE cur;

  DROP TEMPORARY TABLE IF EXISTS final_ids;
  
  IF procedure_success IS NULL THEN
    SET procedure_success := FALSE;
  END IF;
END;