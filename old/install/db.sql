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
-- Table structure for table `channel_social_keys`
--

DROP TABLE IF EXISTS `channel_social_keys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `channel_social_keys` (
  `id` int NOT NULL AUTO_INCREMENT,
  `channel_id` int DEFAULT NULL,
  `social_id` int DEFAULT NULL,
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
  `timezone` varchar(45) DEFAULT NULL  
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;
insert into `settings` (timezone) values('UTC');

--
-- Table structure for table `socials`
--

DROP TABLE IF EXISTS `socials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `socials` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `regdate` datetime DEFAULT NULL,
  `active` tinyint DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;
insert into socials (name, regdate, active) values('facebook', now(), 1);
insert into socials (name, regdate, active) values('twitter', now(), 1);
insert into socials (name, regdate, active) values('instagram', now(), 1);
insert into socials (name, regdate, active) values('linkedin', now(), 1);

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


-- Stored Procedures
--
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

/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-08-02 19:00:22
