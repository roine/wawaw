-- MySQL dump 10.13  Distrib 5.5.28, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: fuel_dev
-- ------------------------------------------------------
-- Server version	5.5.28-0ubuntu0.12.10.2

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
-- Table structure for table `bo_forms`
--

DROP TABLE IF EXISTS `bo_forms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bo_forms` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cleanName` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `table` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bo_forms`
--

LOCK TABLES `bo_forms` WRITE;
/*!40000 ALTER TABLE `bo_forms` DISABLE KEYS */;
INSERT INTO `bo_forms` VALUES (3,'All','all','all'),(4,'Introducing Brokers','introducing_brokers','ib'),(5,'Franchise Scheme','franchise_scheme','franchisescheme'),(6,'White Label','white_label','whitelabel'),(7,'Senior Partners','senior_partners','seniorpartner'),(8,'Call Back','callback','callback'),(9,'Inquiry','inquiry','inquiry'),(10,'Small Registration','small_registration','small_registration'),(11,'Forex Blog','forex_blog','forexblog_ib_registration'),(12,'Promotions','promotions','promotions'),(13,'Video Conference','video_conference','videoconference'),(14,'Demo Account','demo_account','demoaccount'),(15,'Facebook','facebook','fb_home'),(16,'Pay Order','pay_order','pay_order_info'),(17,'CMG','cmg','cmginfo');
/*!40000 ALTER TABLE `bo_forms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sentry_groups`
--

DROP TABLE IF EXISTS `sentry_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sentry_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `permissions` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sentry_groups`
--

LOCK TABLES `sentry_groups` WRITE;
/*!40000 ALTER TABLE `sentry_groups` DISABLE KEYS */;
INSERT INTO `sentry_groups` VALUES (1,'jonathan','{\"superuser\":\"superuser\"}'),(2,'russian','{\"users_index\":1,\"customers_all_read\":1,\"customers_ru\":1,\"customers_index\":1,\"filters_lang_use\":1,\"filters_date_use\":1,\"filters_multi_use\":1,\"ajax_dashboard\":1}'),(3,'demo','{\"users_index\":1,\"customers_en\":1,\"customers_ru\":1,\"customers_tw\":1,\"customers_cn\":1,\"customers_demoaccount_read\":1,\"customers_index\":1,\"filters_lang_use\":1,\"filters_date_use\":1,\"filters_multi_use\":1,\"charts_index\":1,\"charts_monthly\":1,\"ajax_dashboard\":1}');
/*!40000 ALTER TABLE `sentry_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sentry_users`
--

DROP TABLE IF EXISTS `sentry_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sentry_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(81) NOT NULL,
  `password_reset_hash` varchar(81) NOT NULL,
  `temp_password` varchar(81) NOT NULL,
  `remember_me` varchar(81) NOT NULL,
  `activation_hash` varchar(81) NOT NULL,
  `last_login` int(11) NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `activated` tinyint(4) NOT NULL,
  `permissions` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sentry_users`
--

LOCK TABLES `sentry_users` WRITE;
/*!40000 ALTER TABLE `sentry_users` DISABLE KEYS */;
INSERT INTO `sentry_users` VALUES (1,'jonathan','jonathan@ikonfx.com','U3x818WPbPO8xt0e21e198ae26bb9aac378971c80759668315b53e1499d45a1cb81106fdc9406523','','','','y2Pj8m2rZ6Dq78sa6a970fc2d3a4bb3eab27baf07791d15a30b477964ef3b379e3e329d736c47841',1357524401,'127.0.0.1',1357524401,1353922051,1,1,'{\"superuser\":1}'),(5,'russian','russian@ikonfx.com','qEHqahu58Pq6Bn4Seff0c2b62f034660d3d19a590e6ead12be820a08797bd682494583dd7134d710','','','','',1357291836,'127.0.0.1',1357291836,1354001351,1,1,''),(6,'demo','demo@ikonfx.com','K4roswNjHj7XIoaQ4f719e336b26a0e6c896afa34908d7daa570bd1a0b046911cf211c327f9a5cf6','','','','',1355366718,'127.0.0.1',1355366718,1354002271,1,1,'');
/*!40000 ALTER TABLE `sentry_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sentry_users_groups`
--

DROP TABLE IF EXISTS `sentry_users_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sentry_users_groups` (
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sentry_users_groups`
--

LOCK TABLES `sentry_users_groups` WRITE;
/*!40000 ALTER TABLE `sentry_users_groups` DISABLE KEYS */;
INSERT INTO `sentry_users_groups` VALUES (4,1),(1,1),(5,2),(6,3);
/*!40000 ALTER TABLE `sentry_users_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sentry_users_metadata`
--

DROP TABLE IF EXISTS `sentry_users_metadata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sentry_users_metadata` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `department` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sentry_users_metadata`
--

LOCK TABLES `sentry_users_metadata` WRITE;
/*!40000 ALTER TABLE `sentry_users_metadata` DISABLE KEYS */;
INSERT INTO `sentry_users_metadata` VALUES (1,'jonathan','dem','web'),(5,'','',''),(6,'','','');
/*!40000 ALTER TABLE `sentry_users_metadata` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sentry_users_suspended`
--

DROP TABLE IF EXISTS `sentry_users_suspended`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sentry_users_suspended` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login_id` varchar(50) NOT NULL,
  `attempts` int(50) NOT NULL,
  `ip` varchar(25) NOT NULL,
  `last_attempt_at` int(11) NOT NULL,
  `suspended_at` int(11) NOT NULL,
  `unsuspend_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sentry_users_suspended`
--

LOCK TABLES `sentry_users_suspended` WRITE;
/*!40000 ALTER TABLE `sentry_users_suspended` DISABLE KEYS */;
/*!40000 ALTER TABLE `sentry_users_suspended` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'fuel_dev'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-01-07 10:12:04
