-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: dmssoftware_crm_v2
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `attendances`
--

DROP TABLE IF EXISTS `attendances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attendances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `date` date NOT NULL,
  `status` enum('present','absent','late','half_day','leave') NOT NULL DEFAULT 'present',
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attendances_user_id_date_unique` (`user_id`,`date`),
  CONSTRAINT `attendances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendances`
--

LOCK TABLES `attendances` WRITE;
/*!40000 ALTER TABLE `attendances` DISABLE KEYS */;
INSERT INTO `attendances` VALUES (1,1,'2026-10-01','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(2,1,'2026-10-02','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(3,1,'2026-10-03','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(4,1,'2026-10-04','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(5,1,'2026-10-06','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(6,1,'2026-10-07','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(7,1,'2026-10-08','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(8,1,'2026-10-09','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(9,1,'2026-10-10','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(10,1,'2026-10-11','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(11,1,'2026-10-12','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(12,1,'2026-10-13','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(13,2,'2026-10-01','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(14,2,'2026-10-02','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(15,2,'2026-10-03','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(16,2,'2026-10-04','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(17,2,'2026-10-06','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(18,2,'2026-10-07','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(19,2,'2026-10-08','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(20,2,'2026-10-09','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(21,2,'2026-10-10','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(22,2,'2026-10-11','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(23,2,'2026-10-12','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(24,2,'2026-10-13','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(25,3,'2026-10-01','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(26,3,'2026-10-02','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(27,3,'2026-10-03','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(28,3,'2026-10-04','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(29,3,'2026-10-06','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(30,3,'2026-10-07','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(31,3,'2026-10-08','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(32,3,'2026-10-09','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(33,3,'2026-10-10','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(34,3,'2026-10-11','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(35,3,'2026-10-12','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(36,3,'2026-10-13','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(37,4,'2026-10-01','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(38,4,'2026-10-02','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(39,4,'2026-10-03','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(40,4,'2026-10-04','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(41,4,'2026-10-06','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(42,4,'2026-10-07','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(43,4,'2026-10-08','absent',NULL,NULL,'Unscheduled absence','2026-10-13 17:03:35','2026-10-13 17:03:35'),(44,4,'2026-10-09','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(45,4,'2026-10-10','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(46,4,'2026-10-11','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(47,4,'2026-10-12','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(48,4,'2026-10-13','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(49,5,'2026-10-01','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(50,5,'2026-10-02','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(51,5,'2026-10-03','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(52,5,'2026-10-04','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(53,5,'2026-10-06','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(54,5,'2026-10-07','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(55,5,'2026-10-08','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(56,5,'2026-10-09','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(57,5,'2026-10-10','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(58,5,'2026-10-11','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(59,5,'2026-10-12','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(60,5,'2026-10-13','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(61,6,'2026-10-01','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(62,6,'2026-10-02','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(63,6,'2026-10-03','late','10:15:00','18:30:00','Traffic congestion at Banani bridge','2026-10-13 17:03:35','2026-10-13 17:03:35'),(64,6,'2026-10-04','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(65,6,'2026-10-06','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(66,6,'2026-10-07','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(67,6,'2026-10-08','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(68,6,'2026-10-09','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(69,6,'2026-10-10','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(70,6,'2026-10-11','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(71,6,'2026-10-12','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(72,6,'2026-10-13','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(73,7,'2026-10-01','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(74,7,'2026-10-02','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(75,7,'2026-10-03','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(76,7,'2026-10-04','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(77,7,'2026-10-06','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(78,7,'2026-10-07','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(79,7,'2026-10-08','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(80,7,'2026-10-09','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(81,7,'2026-10-10','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(82,7,'2026-10-11','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(83,7,'2026-10-12','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(84,7,'2026-10-13','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(85,8,'2026-10-01','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(86,8,'2026-10-02','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(87,8,'2026-10-03','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(88,8,'2026-10-04','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(89,8,'2026-10-06','leave',NULL,NULL,'Approved personal leave day','2026-10-13 17:03:36','2026-10-13 17:03:36'),(90,8,'2026-10-07','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(91,8,'2026-10-08','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(92,8,'2026-10-09','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(93,8,'2026-10-10','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(94,8,'2026-10-11','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(95,8,'2026-10-12','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(96,8,'2026-10-13','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(97,9,'2026-10-01','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(98,9,'2026-10-02','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(99,9,'2026-10-03','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(100,9,'2026-10-04','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(101,9,'2026-10-06','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(102,9,'2026-10-07','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(103,9,'2026-10-08','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(104,9,'2026-10-09','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(105,9,'2026-10-10','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(106,9,'2026-10-11','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(107,9,'2026-10-12','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(108,9,'2026-10-13','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(109,10,'2026-10-01','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(110,10,'2026-10-02','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(111,10,'2026-10-03','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(112,10,'2026-10-04','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(113,10,'2026-10-06','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(114,10,'2026-10-07','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(115,10,'2026-10-08','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(116,10,'2026-10-09','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(117,10,'2026-10-10','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(118,10,'2026-10-11','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(119,10,'2026-10-12','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(120,10,'2026-10-13','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(121,11,'2026-10-01','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(122,11,'2026-10-02','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(123,11,'2026-10-03','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(124,11,'2026-10-04','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(125,11,'2026-10-06','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(126,11,'2026-10-07','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(127,11,'2026-10-08','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(128,11,'2026-10-09','half_day','09:30:00','14:00:00','Half day for medical consultation','2026-10-13 17:03:36','2026-10-13 17:03:36'),(129,11,'2026-10-10','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(130,11,'2026-10-11','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(131,11,'2026-10-12','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36'),(132,11,'2026-10-13','present','09:30:00','18:30:00',NULL,'2026-10-13 17:03:36','2026-10-13 17:03:36');
/*!40000 ALTER TABLE `attendances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `campaigns`
--

DROP TABLE IF EXISTS `campaigns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campaigns` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `platform` varchar(50) NOT NULL DEFAULT 'meta',
  `objective` varchar(50) NOT NULL DEFAULT 'leads',
  `status` varchar(50) NOT NULL DEFAULT 'active',
  `budget` decimal(12,2) NOT NULL DEFAULT 0.00,
  `spend` decimal(12,2) NOT NULL DEFAULT 0.00,
  `revenue` decimal(12,2) NOT NULL DEFAULT 0.00,
  `impressions` bigint(20) unsigned NOT NULL DEFAULT 0,
  `clicks` int(10) unsigned NOT NULL DEFAULT 0,
  `leads_count` int(10) unsigned NOT NULL DEFAULT 0,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `target_audience` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `ad_account_id` varchar(100) DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `assigned_to` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `campaigns_client_id_foreign` (`client_id`),
  KEY `campaigns_created_by_foreign` (`created_by`),
  KEY `campaigns_assigned_to_foreign` (`assigned_to`),
  CONSTRAINT `campaigns_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `campaigns_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `campaigns_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campaigns`
--

LOCK TABLES `campaigns` WRITE;
/*!40000 ALTER TABLE `campaigns` DISABLE KEYS */;
INSERT INTO `campaigns` VALUES (1,'Aarong - Festive Autumn Collection Advantage+ Catalog',1,'meta','sales','active',150000.00,84500.00,388700.00,420000,14280,0,'2026-09-29','2026-10-29','Men & Women 22-50, Urban BD (Dhaka, Chittagong, Sylhet), Fashion & Apparel interest','High-converting Advantage+ dynamic catalog feed. ROAS trending at 4.6x.','act_98234710293841',1,10,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(2,'Chaldal - Fresh Morning Grocery Daily Delivery Lead & App Install',2,'meta','traffic','active',90000.00,52400.00,183400.00,310000,18600,420,'2026-10-03','2026-11-02','Housewives, working professionals 25-55 in Dhaka Metropolitan radius','App installs and direct web orders for fresh groceries. CPL: 124.76 BDT.','act_48102948201948',2,10,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(3,'Pathao Food - Midnight Cravings Dynamic Promo Boost',5,'meta','engagement','active',110000.00,78000.00,265200.00,580000,26100,310,'2026-10-01','2026-10-31','Youth 18-35 in Dhaka, Chittagong. Active hours 20:00 - 02:00','Engagement & food delivery promo conversions. ROAS: 3.4x.','act_71829304918234',2,10,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(4,'Apex - Venturini Sneakers Gen-Z TikTok & Reels Video Push',3,'tiktok','awareness','active',60000.00,28500.00,85500.00,650000,19500,95,'2026-10-06','2026-10-27','Ages 16-28 interested in street sneakers, hip-hop culture, lifestyle fashion','Video view optimization and youth awareness.','tt_918204918204',1,10,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(5,'BrainStation 23 - Enterprise FinTech Cloud High Intent Search',4,'google','leads','active',140000.00,96200.00,481000.00,88000,7480,84,'2026-09-23','2026-10-23','Corporate IT decision makers, CTOs, CIOs searching for enterprise cloud migration','High value B2B enterprise leads. ROAS: 5.0x.','goog_381-920-1928',1,10,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(6,'Bengal Meat - Gourmet Marinated BBQ Weekend Flash Sales',6,'meta','sales','active',45000.00,21300.00,78800.00,165000,5775,110,'2026-10-08','2026-10-28','Foodies, home cooks, BBQ enthusiasts in Dhanmondi, Gulshan, Banani, Uttara','Direct WhatsApp orders and e-commerce cart checkouts.','act_59102938401928',2,10,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(7,'Square Health - 24/7 Specialist Doctor Online Consultation',8,'google','leads','paused',80000.00,44000.00,132000.00,120000,8400,160,'2026-08-13','2026-10-03','Healthcare searchers, online prescription inquiries','Temporarily paused per client IT server migration request.','goog_192-384-9102',1,10,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(8,'Yellow Lifestyle - Autumn Minimalist Season Teaser YouTube Video Views',7,'youtube','awareness','draft',50000.00,0.00,0.00,0,0,0,'2026-10-18','2026-11-07','Fashion video viewers on YouTube 18-35 in BD','Draft setup ready for client asset approval.','goog_772-102-9481',1,10,'2026-10-13 17:03:35','2026-10-13 17:03:35');
/*!40000 ALTER TABLE `campaigns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_services`
--

DROP TABLE IF EXISTS `client_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_services` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) unsigned NOT NULL,
  `service_id` bigint(20) unsigned NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `qty` int(11) NOT NULL DEFAULT 1,
  `custom_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_services_client_id_foreign` (`client_id`),
  KEY `client_services_service_id_foreign` (`service_id`),
  CONSTRAINT `client_services_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `client_services_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_services`
--

LOCK TABLES `client_services` WRITE;
/*!40000 ALTER TABLE `client_services` DISABLE KEYS */;
INSERT INTO `client_services` VALUES (1,1,1,45000.00,1,NULL,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(2,1,2,25000.00,1,NULL,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(3,1,4,800.00,25,NULL,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(4,1,6,3500.00,8,NULL,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(5,2,2,30000.00,1,NULL,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(6,2,3,25000.00,1,NULL,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(7,2,4,750.00,30,NULL,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(8,3,1,40000.00,1,NULL,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(9,3,5,4500.00,6,NULL,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(10,3,6,3500.00,10,NULL,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(11,4,7,35000.00,1,NULL,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(12,4,3,28000.00,1,NULL,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(13,4,9,800.00,8,NULL,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(14,5,1,50000.00,1,NULL,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(15,5,2,35000.00,1,NULL,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(16,5,6,3500.00,12,NULL,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(17,6,10,18000.00,1,NULL,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(18,6,1,30000.00,1,NULL,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(19,6,4,800.00,16,NULL,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(20,7,8,75000.00,1,NULL,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(21,7,1,45000.00,1,NULL,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(22,7,6,3500.00,10,NULL,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(23,8,7,28000.00,1,NULL,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(24,8,3,20000.00,1,NULL,'2026-10-13 17:03:34','2026-10-13 17:03:34');
/*!40000 ALTER TABLE `client_services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `company` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `assigned_smm` bigint(20) unsigned DEFAULT NULL,
  `assigned_sales` bigint(20) unsigned DEFAULT NULL,
  `status` enum('active','inactive','onboarding','paused') NOT NULL DEFAULT 'active',
  `billing_cycle` enum('monthly','quarterly','one-time') NOT NULL DEFAULT 'monthly',
  `advance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `satisfaction_score` tinyint(4) DEFAULT NULL,
  `onboarded_at` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `clients_assigned_smm_foreign` (`assigned_smm`),
  KEY `clients_assigned_sales_foreign` (`assigned_sales`),
  CONSTRAINT `clients_assigned_sales_foreign` FOREIGN KEY (`assigned_sales`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `clients_assigned_smm_foreign` FOREIGN KEY (`assigned_smm`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` VALUES (1,'Aarong Lifestyle & Crafts','BRAC-Aarong Limited','+880 1711-203491','marketing@aarong.com','Aarong Centre, 346 Tejgaon I/A, Dhaka',5,3,'active','monthly',0.00,'Top lifestyle brand in BD. Full-funnel SMM, festive autumn campaigns, and Advantage+ Meta catalog sales.',95,'2026-06-13','2026-10-13 17:03:34','2026-10-20 14:38:56',NULL),(2,'Chaldal Fresh & Grocery','Chaldal Limited','+880 1819-482015','growth@chaldal.com','House 12, Road 1, Block A, Banani DOHS, Dhaka',5,3,'active','monthly',0.00,'Online grocery pioneer. Daily flash deal creative banners, rapid app installs, and Google Search campaigns.',90,'2026-04-13','2026-10-13 17:03:34','2026-10-20 14:38:56',NULL),(3,'Apex Footwear Ltd','Apex Footwear Holdings','+880 1912-349812','sabrina.y@apexfootwearltd.com','Apex Bhaban, Gulshan 1, Dhaka',5,3,'active','monthly',0.00,'Focus on Gen-Z Venturini sneakers launch and formal office comfort wear. High motion graphics and 4K reels demand.',88,'2026-07-13','2026-10-13 17:03:34','2026-10-20 14:38:56',NULL),(4,'BrainStation 23 FinTech','Brain Station 23 PLC','+880 1722-901234','growth@brainstation-23.com','8th Floor, Mirpur 12 / Mohakhali Wireless, Dhaka',5,4,'active','monthly',0.00,'Enterprise cloud & FinTech solutions. B2B LinkedIn thought leadership, technical SEO, and Google Search PPC.',98,'2026-05-13','2026-10-13 17:03:34','2026-10-20 14:38:56',NULL),(5,'Pathao Food & Courier','Pathao Limited','+880 1844-551122','marketing@pathao.com','Shanta Western Tower, Bir Uttam Mir Shawkat Sarak, Tejgaon, Dhaka',5,3,'active','monthly',0.00,'Super-app food delivery & courier. Viral weekend reels, late night food promo boosts, and influencer UGC cuts.',92,'2026-07-13','2026-10-13 17:03:34','2026-10-20 14:38:56',NULL),(6,'Bengal Meat Gourmet Cuts','Bengal Meat Processing Industries Ltd','+880 1713-998877','retail@bengalmeat.com','House 41, Road 27 (Old), Dhanmondi, Dhaka',5,4,'active','monthly',0.00,'Premium organic beef, marinated steaks and cold cuts. Commercial food photography shoots and BBQ reels.',85,'2026-08-13','2026-10-13 17:03:34','2026-10-20 14:38:56',NULL),(7,'Yellow Lifestyle (Beximco)','Beximco Apparels Ltd','+880 1715-443322','nadia@yellowlifestyle.net','Sector 3, Uttara Model Town, Dhaka',5,3,'onboarding','monthly',45000.00,'Modern youth apparel line. Complete seasonal brand identity revamp and headless Shopify optimization.',82,'2026-09-29','2026-10-13 17:03:34','2026-10-13 17:03:34',NULL),(8,'Square Health Telemedicine','Square Hospitals / Square Pharma','+880 1730-123456','online@squarehealth.com.bd','18/F Bir Uttam Qazi Nuruzzaman Sarak, Panthapath, Dhaka',5,4,'paused','monthly',15000.00,'Temporarily paused during Hospital ERP server migration. Resuming in November 2026.',74,'2026-03-13','2026-10-13 17:03:34','2026-10-13 17:03:34',NULL);
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `content_posts`
--

DROP TABLE IF EXISTS `content_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `content_posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `assigned_to` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `platform` enum('facebook','instagram','linkedin','twitter','tiktok','youtube','other') NOT NULL DEFAULT 'facebook',
  `post_type` varchar(50) DEFAULT 'design',
  `date` date NOT NULL,
  `publish_time` varchar(20) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'draft',
  `notes` text DEFAULT NULL,
  `caption` text DEFAULT NULL,
  `creative_url` varchar(1000) DEFAULT NULL,
  `internal_feedback` text DEFAULT NULL,
  `client_feedback` text DEFAULT NULL,
  `brief` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `content_posts_client_id_foreign` (`client_id`),
  KEY `content_posts_assigned_to_foreign` (`assigned_to`),
  KEY `content_posts_created_by_foreign` (`created_by`),
  CONSTRAINT `content_posts_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `content_posts_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `content_posts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `content_posts`
--

LOCK TABLES `content_posts` WRITE;
/*!40000 ALTER TABLE `content_posts` DISABLE KEYS */;
INSERT INTO `content_posts` VALUES (1,'Aarong শরৎ সম্ভার ২০২৬ — ঐতিহ্যের সূতা ও আধুনিক কারুকাজ',1,6,5,'instagram','carousel','2026-10-12','18:00','published',NULL,'প্রকৃতির রঙের সাথে ঐতিহ্যের মেলবন্ধন।\nআড়ং শরৎ সম্ভার ২০২৬ নিয়ে এলো খাঁটি সুতি ও তন্তুর আরামদায়ক পোশাকের সমাহার।\n\n🛍️ নিকটস্থ আড়ং আউটলেটে ঘুরে আসুন অথবা অনলাইনে অর্ডার করুন: aarong.com\n#Aarong #AutumnCollection #BengaliFashion #HandloomHeritage','https://www.figma.com/file/aarong-autumn-published','Approved by Sara without edits.','Loved the warmth in the models skin tone.',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(2,'Aarong Men’s Khadi Hand-Spun Kurti Showcase',1,6,5,'facebook','design','2026-10-13','20:00','approved',NULL,'পরিমিত আভিজাত্য, নিখুঁত সেলাই।\nপুরুষদের নতুন খাদি কুর্তি কালেকশন এখন আপনার পছন্দের রঙে।\n\n#AarongMen #KhadiCollection #SophisticatedElegance','https://assets.dmsagency.com/creatives/aarong_kurti.png','Colors verified.','Approved for scheduled broadcast.',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(3,'Behind the Loom: Tangail Weavers Artisan Story (60s Reel)',1,8,5,'instagram','reel','2026-10-14','19:30','client_approval',NULL,'প্রতিটি সুতোয় জড়ানো এক একটি ঐতিহ্যবাহী গল্প। টাঙ্গাইলের তাঁত শিল্পীদের নিপুণ হাতের জাদু দেখুন এই বিশেষ ভিডিওতে। 🧵✨\n\n#WeaversOfBengal #AarongCrafts #ArtisanPride','https://assets.dmsagency.com/video/aarong_loom_preview.mp4','Sara reviewed audio levels: Sound is clean and soothing.','Awaiting review from Tanzim Rahman.',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(4,'Chaldal ১ ঘণ্টায় তাজা শাকসবজি আপনার ঘরে',2,6,5,'facebook','design','2026-10-11','09:00','published',NULL,'সকালের তাজা বাজার এখন চটজলদি আপনার দ্বারে! 🥦🥕\nচালডাল অ্যাপে অর্ডার করুন আর বুঝে নিন মাত্র ১ ঘণ্টায়।\n\n#Chaldal #FreshVegetables #DhakaGroceries #FastDelivery','https://assets.dmsagency.com/creatives/chaldal_veggies.png',NULL,NULL,NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(5,'Chaldal Flash Friday Deals Carousel',2,6,5,'facebook','carousel','2026-10-15','10:00','internal_review',NULL,'শুক্রবার মানেই বাজারের বিশেষ ছাড়! তেল, ডাল আর মসলায় ফ্ল্যাট ১৫% পর্যন্ত সেভিংস। 🛒✨','https://www.figma.com/file/chaldal-flash-friday','Sara: Double check discount badges match product SKUs.',NULL,NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(6,'Apex Venturini — Move Beyond City Limits (Reel)',3,8,5,'instagram','reel','2026-10-16','21:00','client_approval',NULL,'Engineered for pure street traction and cloud-like cushioning. Step into the all-new Venturini Active series. 👟⚡\n\n#ApexFootwear #VenturiniSneakers #UrbanMovement','https://assets.dmsagency.com/video/apex_sneakers_v1.mp4',NULL,NULL,NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(7,'Apex Formal Executive Leather Comfort Collection',3,6,5,'linkedin','design','2026-10-17','11:00','revision',NULL,'Command the boardroom with effortless comfort. 100% genuine calf leather footwear crafted for leaders.','https://assets.dmsagency.com/creatives/apex_executive.png',NULL,'Sabrina: Please brighten the leather brown highlights and make the price badge font bolder.',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(8,'Pathao বৃষ্টির দিনে গরম ধোঁয়া ওঠা খিচুড়ি? ফুড আছে তো!',5,8,5,'tiktok','reel','2026-10-10','13:00','published',NULL,'বাইরে বৃষ্টি আর ঘরে গরম খিচুড়ি + ইলিশ মাছ ভাজা! পাঠাও ফুডে অর্ডার দিন ৩০ মিনিটের মধ্যে। 🌧️🍛🛵\n#PathaoFood #KhichuriWeather #FoodPornDhaka','https://assets.dmsagency.com/video/pathao_rain_khichuri.mp4',NULL,NULL,NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(9,'Pathao Midnight Cravings 25% Off Campaign Post',5,6,5,'facebook','design','2026-10-18','23:00','draft',NULL,'রাত ১২টায় যখন হঠাৎ পিৎজা কিংবা বার্গারের টান! পাঠাও ফুড মিডনাইট ক্র্যাভিংস। 🍔🍕',NULL,NULL,NULL,NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(10,'Bengal Meat Angus Ribeye Steak Grilling ASMR Masterclass',6,8,5,'instagram','reel','2026-10-15','18:30','approved',NULL,'Listen to the sizzle. 🥩🔥 100% grass-fed organic beef with perfect marbling. Available at Bengal Meat Gourmet stores.\n#BengalMeat #RibeyeSteak #SteakASMR #GourmetFood','https://assets.dmsagency.com/video/bengalmeat_asmr.mp4',NULL,NULL,NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(11,'BrainStation 23 Cloud Microservices in Modern Banking',4,5,5,'linkedin','copy','2026-10-09','14:00','published',NULL,'How can tier-1 financial institutions process 50,000 TPS with zero downtime? Discover how BrainStation 23 engineers architected fault-tolerant Kubernetes microservices for regional banks.\n\nRead our engineering whitepaper: brainstation-23.com/cloud\n#FinTech #Microservices #CloudArchitecture #Kubernetes',NULL,NULL,NULL,NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(12,'BrainStation 23 AI Fraud Detection Framework Infographic',4,6,5,'linkedin','carousel','2026-10-19','15:00','draft',NULL,'Real-time payment fraud prevention powered by graph neural networks. A 5-slide visual technical breakdown.',NULL,NULL,NULL,NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(13,'Yellow Lifestyle Autumn Minimalist Essentials Lookbook',7,8,5,'instagram','video','2026-10-16','19:00','internal_review',NULL,'Simplicity redefined. Yellow Autumn drop features earthy tones and relaxed silhouettes for every day.','https://assets.dmsagency.com/video/yellow_teaser.mp4','Sara: Add subtle sound riser before typography reveal.',NULL,NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(14,'Square Health অভিজ্ঞ চিকিৎসকের ভিডিও পরামর্শ ঘরে বসেই',8,6,5,'facebook','design','2026-10-20','11:00','revision',NULL,'২৪/৭ অভিজ্ঞ বিশেষজ্ঞ ডাক্তারের পরামর্শ এখন হাতের মুঠোয়। স্কয়ার হেলথ অ্যাপ ডাউনলোড করুন। 🩺📱',NULL,NULL,'Dr. Kamrul: Hospital helpline phone number needs to be placed on top right header.',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(15,'Aarong Earth: ১০০% ভেষজ প্রাকৃতিক হেয়ার কেয়ার অয়েল',1,6,5,'facebook','design','2026-10-15','17:00','client_approval',NULL,'ভেষজ উপাদানের সমৃদ্ধ যত্নে আপনার চুলের যত্ন নিন প্রাকৃতিকভাবে। আড়ং আর্থ হারবাল হেয়ার অয়েল। 🌿✨','https://assets.dmsagency.com/creatives/aarong_earth.png',NULL,NULL,NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35');
/*!40000 ALTER TABLE `content_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `custom_statuses`
--

DROP TABLE IF EXISTS `custom_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custom_statuses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `color` varchar(20) NOT NULL DEFAULT '#64748b',
  `order` int(11) NOT NULL DEFAULT 0,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `core_status` enum('pending','in_progress','done_pending_review','done') NOT NULL DEFAULT 'in_progress',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `custom_statuses_client_id_foreign` (`client_id`),
  CONSTRAINT `custom_statuses_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `custom_statuses`
--

LOCK TABLES `custom_statuses` WRITE;
/*!40000 ALTER TABLE `custom_statuses` DISABLE KEYS */;
INSERT INTO `custom_statuses` VALUES (1,NULL,'Backlog','#94a3b8',1,1,'pending','2026-10-13 17:03:34','2026-10-13 17:03:34'),(2,NULL,'In Progress','#3b82f6',2,1,'in_progress','2026-10-13 17:03:34','2026-10-13 17:03:34'),(3,NULL,'Quality Review','#8b5cf6',3,1,'done_pending_review','2026-10-13 17:03:34','2026-10-13 17:03:34'),(4,NULL,'Approved & Done','#10b981',4,1,'done','2026-10-13 17:03:34','2026-10-13 17:03:34');
/*!40000 ALTER TABLE `custom_statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_salaries`
--

DROP TABLE IF EXISTS `employee_salaries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_salaries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `month` varchar(7) NOT NULL,
  `base_salary` decimal(10,2) NOT NULL DEFAULT 0.00,
  `bonus` decimal(10,2) NOT NULL DEFAULT 0.00,
  `deductions` decimal(10,2) NOT NULL DEFAULT 0.00,
  `net_salary` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('paid','pending') NOT NULL DEFAULT 'pending',
  `paid_at` date DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_salaries_user_id_month_unique` (`user_id`,`month`),
  CONSTRAINT `employee_salaries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_salaries`
--

LOCK TABLES `employee_salaries` WRITE;
/*!40000 ALTER TABLE `employee_salaries` DISABLE KEYS */;
INSERT INTO `employee_salaries` VALUES (1,1,'2026-10',120000.00,20000.00,0.00,140000.00,'paid','2026-10-10','bank_transfer','Executive founder drawing','2026-10-13 17:03:36','2026-10-13 17:03:36'),(2,2,'2026-10',85000.00,15000.00,0.00,100000.00,'paid','2026-10-10','bank_transfer','Q4 agency operations bonus','2026-10-13 17:03:36','2026-10-13 17:03:36'),(3,3,'2026-10',60000.00,18000.00,0.00,78000.00,'paid','2026-10-10','bank_transfer','Commission on Shwapno closed sale','2026-10-13 17:03:36','2026-10-13 17:03:36'),(4,5,'2026-10',52000.00,10000.00,0.00,62000.00,'paid','2026-10-10','bank_transfer','Aarong festive sprint incentive','2026-10-13 17:03:36','2026-10-13 17:03:36'),(5,6,'2026-10',48000.00,8000.00,0.00,56000.00,'pending',NULL,'bank_transfer','Creative quality milestone','2026-10-13 17:03:36','2026-10-13 17:03:36'),(6,7,'2026-10',50000.00,7500.00,0.00,57500.00,'pending',NULL,'bank_transfer','Apex 3D animation milestone','2026-10-13 17:03:36','2026-10-13 17:03:36'),(7,8,'2026-10',45000.00,6000.00,0.00,51000.00,'pending',NULL,'bank_transfer','4K Reel cuts delivery bonus','2026-10-13 17:03:36','2026-10-13 17:03:36'),(8,9,'2026-10',65000.00,10000.00,0.00,75000.00,'pending',NULL,'bank_transfer','Core Web Vitals speed reward','2026-10-13 17:03:36','2026-10-13 17:03:36'),(9,10,'2026-10',58000.00,12000.00,0.00,70000.00,'pending',NULL,'bank_transfer','4.6x ROAS performance incentive','2026-10-13 17:03:36','2026-10-13 17:03:36'),(10,11,'2026-10',32000.00,3000.00,0.00,35000.00,'pending',NULL,'bkash','Competitor intelligence reward','2026-10-13 17:03:36','2026-10-13 17:03:36'),(11,4,'2026-10',55000.00,8000.00,0.00,63000.00,'pending',NULL,'bank_transfer','BrainStation deal renewal bonus','2026-10-13 17:03:36','2026-10-13 17:03:36');
/*!40000 ALTER TABLE `employee_salaries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expense_categories`
--

DROP TABLE IF EXISTS `expense_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expense_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `color` varchar(20) NOT NULL DEFAULT '#64748b',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expense_categories`
--

LOCK TABLES `expense_categories` WRITE;
/*!40000 ALTER TABLE `expense_categories` DISABLE KEYS */;
INSERT INTO `expense_categories` VALUES (1,'Banani Office Rent','#3b82f6','2026-10-13 17:03:34','2026-10-13 17:03:34'),(2,'Utilities & Fiber Net','#8b5cf6','2026-10-13 17:03:34','2026-10-13 17:03:34'),(3,'SaaS Software & AI Tools','#ec4899','2026-10-13 17:03:34','2026-10-13 17:03:34'),(4,'Meta & Google Ads Credit','#f59e0b','2026-10-13 17:03:34','2026-10-13 17:03:34'),(5,'Employee Salaries & KPI','#10b981','2026-10-13 17:03:34','2026-10-13 17:03:34'),(6,'Camera & Studio Gear','#f97316','2026-10-13 17:03:34','2026-10-13 17:03:34'),(7,'Office Refreshments & Misc','#64748b','2026-10-13 17:03:34','2026-10-13 17:03:34');
/*!40000 ALTER TABLE `expense_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expenses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `payment_method` varchar(255) NOT NULL DEFAULT 'cash',
  `notes` text DEFAULT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expenses_category_id_foreign` (`category_id`),
  KEY `expenses_created_by_foreign` (`created_by`),
  CONSTRAINT `expenses_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `expense_categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `expenses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expenses`
--

LOCK TABLES `expenses` WRITE;
/*!40000 ALTER TABLE `expenses` DISABLE KEYS */;
INSERT INTO `expenses` VALUES (1,'Banani HQ Office Lease (Oct 2026)',85000.00,1,1,'bank_transfer',NULL,'2026-10-01','2026-10-13 17:03:34','2026-10-13 17:03:34'),(2,'Dedicated 1Gbps Fiber Internet Line',7500.00,2,1,'bkash',NULL,'2026-10-03','2026-10-13 17:03:34','2026-10-13 17:03:34'),(3,'Adobe Creative Cloud Team License (10 Seats)',24000.00,3,1,'card',NULL,'2026-10-05','2026-10-13 17:03:34','2026-10-13 17:03:34'),(4,'Figma Organization Annual Subscription',16500.00,3,1,'card',NULL,'2026-10-07','2026-10-13 17:03:34','2026-10-13 17:03:34'),(5,'Meta Ad Account Threshold Credit Topup',120000.00,4,1,'card',NULL,'2026-10-09','2026-10-13 17:03:34','2026-10-13 17:03:34'),(6,'Studio LED Softbox Lighting & Stands',12500.00,6,1,'cash',NULL,'2026-10-01','2026-10-13 17:03:34','2026-10-13 17:03:34'),(7,'Nespresso Coffee Beans & Team Snacks',6800.00,7,1,'cash',NULL,'2026-10-11','2026-10-13 17:03:34','2026-10-13 17:03:34'),(8,'Generator Backup Fuel & Electrical',9200.00,2,1,'cash',NULL,'2026-09-28','2026-10-13 17:03:34','2026-10-13 17:03:34');
/*!40000 ALTER TABLE `expenses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `followups`
--

DROP TABLE IF EXISTS `followups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `followups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('call','email','whatsapp','meeting','visit','other') NOT NULL DEFAULT 'call',
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `scheduled_at` datetime NOT NULL,
  `status` enum('pending','completed','cancelled') NOT NULL DEFAULT 'pending',
  `outcome` text DEFAULT NULL,
  `lead_id` bigint(20) unsigned DEFAULT NULL,
  `client_id` bigint(20) unsigned DEFAULT NULL,
  `meeting_id` bigint(20) unsigned DEFAULT NULL,
  `assigned_to` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `followups_lead_id_foreign` (`lead_id`),
  KEY `followups_client_id_foreign` (`client_id`),
  KEY `followups_meeting_id_foreign` (`meeting_id`),
  KEY `followups_assigned_to_foreign` (`assigned_to`),
  KEY `followups_created_by_foreign` (`created_by`),
  CONSTRAINT `followups_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `followups_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  CONSTRAINT `followups_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `followups_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE SET NULL,
  CONSTRAINT `followups_meeting_id_foreign` FOREIGN KEY (`meeting_id`) REFERENCES `meetings` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `followups`
--

LOCK TABLES `followups` WRITE;
/*!40000 ALTER TABLE `followups` DISABLE KEYS */;
INSERT INTO `followups` VALUES (1,'Call Tanvir Ahmed regarding Walton quotation review','Confirm whether procurement committee approved the 125,000 BDT SLA.','call','urgent','2026-10-14 02:03:34','pending',NULL,2,NULL,NULL,3,3,NULL,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(2,'WhatsApp message to Kacchi Bhai regarding shooting date','Send draft storyboard link and confirm branch availability.','whatsapp','high','2026-10-14 23:03:34','pending',NULL,3,NULL,NULL,3,3,NULL,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(3,'In-person gym visit at Krav Maga Banani','Inspect ambient gym lighting and meet with head fitness coach.','visit','medium','2026-10-15 23:03:34','pending',NULL,4,NULL,NULL,4,4,NULL,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(4,'Payment follow-up with Square Health Accounts','Inquired on overdue invoice INV-2026-010 (48,000 BDT).','call','urgent','2026-10-12 23:03:34','completed','Accounts manager stated check is in sign-off stage, promised payment within 48 hours.',NULL,8,NULL,4,4,'2026-10-12 23:03:34','2026-10-13 17:03:34','2026-10-13 17:03:34'),(5,'WhatsApp follow-up with Aarong on Autumn Reel captions','Sent copywriter brief for approval.','whatsapp','medium','2026-10-11 23:03:34','completed','Tanzim approved Bengali captions with minor word switch.',NULL,1,NULL,5,5,'2026-10-11 23:03:34','2026-10-13 17:03:34','2026-10-13 17:03:34');
/*!40000 ALTER TABLE `followups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice_items`
--

DROP TABLE IF EXISTS `invoice_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoice_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint(20) unsigned NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_items_invoice_id_foreign` (`invoice_id`),
  CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice_items`
--

LOCK TABLES `invoice_items` WRITE;
/*!40000 ALTER TABLE `invoice_items` DISABLE KEYS */;
INSERT INTO `invoice_items` VALUES (1,1,'Social Media Management (SMM 360)',1,45000.00,45000.00,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(2,1,'Meta Ads Performance Marketing',1,25000.00,25000.00,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(3,1,'Reels & Short-form Video Production',8,3500.00,28000.00,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(4,1,'Creative Design Post Suite',10,700.00,7000.00,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(5,2,'Meta Ads Performance Marketing',1,30000.00,30000.00,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(6,2,'Google Ads PPC Search Optimization',1,25000.00,25000.00,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(7,2,'Creative Design Posts (30 Banners)',30,750.00,22500.00,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(8,3,'Social Media Management (SMM 360)',1,40000.00,40000.00,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(9,3,'Motion Graphics & 2D Animations',6,4500.00,27000.00,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(10,3,'Reels & Short-form Video Production',10,3500.00,35000.00,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(11,4,'Social Media Management (SMM 360)',1,45000.00,45000.00,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(12,4,'Meta Ads Performance Marketing',1,25000.00,25000.00,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(13,4,'Reels & Short-form Video Production',8,3500.00,28000.00,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(14,4,'Creative Design Post Suite',10,700.00,7000.00,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(15,5,'Meta Ads Performance Marketing',1,30000.00,30000.00,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(16,5,'Google Ads PPC Search Optimization',1,25000.00,25000.00,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(17,5,'Creative Design Posts (30 Banners)',30,750.00,22500.00,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(18,6,'SEO & Technical Organic Growth',1,35000.00,35000.00,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(19,6,'Google Ads PPC & Search Optimization',1,28000.00,28000.00,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(20,6,'Full-Funnel Content Writing & Copy',8,800.00,6400.00,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(21,7,'Commercial Product Photography Shoot',1,18000.00,18000.00,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(22,7,'Social Media Management (SMM 360)',1,30000.00,30000.00,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(23,7,'Creative Design & Branding Suite',16,800.00,12800.00,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(24,8,'E-Commerce Web Design & Development',1,75000.00,75000.00,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(25,8,'Social Media Management (SMM 360)',1,45000.00,45000.00,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(26,8,'Reels & Short-form Video Production',10,3500.00,35000.00,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(27,9,'SEO & Technical Organic Growth',1,28000.00,28000.00,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(28,9,'Google Ads PPC & Search Optimization',1,20000.00,20000.00,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(29,10,'Social Media Management (SMM 360)',1,45000.00,45000.00,'2026-10-20 14:38:56','2026-10-20 14:38:56'),(30,10,'Meta Ads Performance Marketing',1,25000.00,25000.00,'2026-10-20 14:38:56','2026-10-20 14:38:56'),(31,10,'Creative Design & Branding Suite',25,800.00,20000.00,'2026-10-20 14:38:56','2026-10-20 14:38:56'),(32,10,'Reels & Short-form Video Production',8,3500.00,28000.00,'2026-10-20 14:38:56','2026-10-20 14:38:56'),(33,11,'Meta Ads Performance Marketing',1,30000.00,30000.00,'2026-10-20 14:38:56','2026-10-20 14:38:56'),(34,11,'Google Ads PPC & Search Optimization',1,25000.00,25000.00,'2026-10-20 14:38:56','2026-10-20 14:38:56'),(35,11,'Creative Design & Branding Suite',30,750.00,22500.00,'2026-10-20 14:38:56','2026-10-20 14:38:56'),(36,12,'Social Media Management (SMM 360)',1,40000.00,40000.00,'2026-10-20 14:38:56','2026-10-20 14:38:56'),(37,12,'Motion Graphics & 2D Animations',6,4500.00,27000.00,'2026-10-20 14:38:56','2026-10-20 14:38:56'),(38,12,'Reels & Short-form Video Production',10,3500.00,35000.00,'2026-10-20 14:38:56','2026-10-20 14:38:56'),(39,13,'Google Ads PPC & Search Optimization',1,28000.00,28000.00,'2026-10-20 14:38:56','2026-10-20 14:38:56'),(40,13,'SEO & Technical Organic Growth',1,35000.00,35000.00,'2026-10-20 14:38:56','2026-10-20 14:38:56'),(41,13,'Full-Funnel Content Writing & Copy',8,800.00,6400.00,'2026-10-20 14:38:56','2026-10-20 14:38:56'),(42,14,'Social Media Management (SMM 360)',1,50000.00,50000.00,'2026-10-20 14:38:56','2026-10-20 14:38:56'),(43,14,'Meta Ads Performance Marketing',1,35000.00,35000.00,'2026-10-20 14:38:56','2026-10-20 14:38:56'),(44,14,'Reels & Short-form Video Production',12,3500.00,42000.00,'2026-10-20 14:38:56','2026-10-20 14:38:56'),(45,15,'Social Media Management (SMM 360)',1,30000.00,30000.00,'2026-10-20 14:38:56','2026-10-20 14:38:56'),(46,15,'Creative Design & Branding Suite',16,800.00,12800.00,'2026-10-20 14:38:56','2026-10-20 14:38:56'),(47,15,'Commercial Product Photography Shoot',1,18000.00,18000.00,'2026-10-20 14:38:56','2026-10-20 14:38:56');
/*!40000 ALTER TABLE `invoice_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(255) NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `advance_paid` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('paid','partial','unpaid','cancelled') NOT NULL DEFAULT 'unpaid',
  `notes` text DEFAULT NULL,
  `issued_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  KEY `invoices_client_id_foreign` (`client_id`),
  KEY `invoices_created_by_foreign` (`created_by`),
  CONSTRAINT `invoices_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES (1,'INV-2026-001',1,1,105000.00,50000.00,105000.00,0.00,'paid','September 2026 full retainer cleared via Standard Chartered Bank transfer.','2026-09-01','2026-09-16','2026-10-13 17:03:35','2026-10-13 17:03:35',NULL),(2,'INV-2026-002',2,1,77500.00,35000.00,77500.00,0.00,'paid','Paid via City Bank BEFTN.','2026-09-13','2026-09-27','2026-10-13 17:03:35','2026-10-13 17:03:35',NULL),(3,'INV-2026-003',3,1,102000.00,40000.00,102000.00,0.00,'paid','Full settlement received.','2026-09-23','2026-10-08','2026-10-13 17:03:35','2026-10-13 17:03:35',NULL),(4,'INV-2026-004',1,1,105000.00,0.00,105000.00,105000.00,'unpaid','October 2026 retainer invoice.','2026-10-01','2026-10-18','2026-10-13 17:03:35','2026-10-13 17:03:35',NULL),(5,'INV-2026-005',2,1,77500.00,30000.00,77500.00,47500.00,'partial','30,000 BDT advance received on 5th Oct. Balance 47,500 due on 18th Oct.','2026-10-08','2026-10-16','2026-10-13 17:03:35','2026-10-13 17:03:35',NULL),(6,'INV-2026-006',4,1,69400.00,0.00,69400.00,69400.00,'unpaid','Monthly technical SEO and Google Ads management.','2026-10-11','2026-10-21','2026-10-13 17:03:35','2026-10-13 17:03:35',NULL),(7,'INV-2026-007',6,1,60800.00,25000.00,60800.00,35800.00,'partial','Advance 25,000 paid. Remainder due upon delivery of final processed photos.','2026-10-07','2026-10-15','2026-10-13 17:03:35','2026-10-13 17:03:35',NULL),(8,'INV-2026-008',7,1,155000.00,45000.00,155000.00,110000.00,'partial','Web development and SMM onboarding agreement.','2026-10-05','2026-10-25','2026-10-13 17:03:35','2026-10-13 17:03:35',NULL),(9,'INV-2026-009',8,1,48000.00,0.00,48000.00,48000.00,'unpaid','⚠️ OVERDUE by 12 days. Reminder sent via WhatsApp and Phone.','2026-09-16','2026-10-01','2026-10-13 17:03:35','2026-10-13 17:03:35',NULL),(10,'INV-202611-001',1,1,118000.00,50000.00,118000.00,68000.00,'partial','Automated Monthly Retainer Billing for November 2026','2026-11-01','2026-11-11','2026-10-20 14:38:56','2026-10-20 14:38:56',NULL),(11,'INV-202611-002',2,1,77500.00,35000.00,77500.00,42500.00,'partial','Automated Monthly Retainer Billing for November 2026','2026-11-01','2026-11-11','2026-10-20 14:38:56','2026-10-20 14:38:56',NULL),(12,'INV-202611-003',3,1,102000.00,40000.00,102000.00,62000.00,'partial','Automated Monthly Retainer Billing for November 2026','2026-11-01','2026-11-11','2026-10-20 14:38:56','2026-10-20 14:38:56',NULL),(13,'INV-202611-004',4,1,69400.00,60000.00,69400.00,9400.00,'partial','Automated Monthly Retainer Billing for November 2026','2026-11-01','2026-11-11','2026-10-20 14:38:56','2026-10-20 14:38:56',NULL),(14,'INV-202611-005',5,1,127000.00,55000.00,127000.00,72000.00,'partial','Automated Monthly Retainer Billing for November 2026','2026-11-01','2026-11-11','2026-10-20 14:38:56','2026-10-20 14:38:56',NULL),(15,'INV-202611-006',6,1,60800.00,25000.00,60800.00,35800.00,'partial','Automated Monthly Retainer Billing for November 2026','2026-11-01','2026-11-11','2026-10-20 14:38:56','2026-10-20 14:38:56',NULL);
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lead_requirements`
--

DROP TABLE IF EXISTS `lead_requirements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lead_requirements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` bigint(20) unsigned NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lead_requirements_lead_id_foreign` (`lead_id`),
  CONSTRAINT `lead_requirements_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lead_requirements`
--

LOCK TABLES `lead_requirements` WRITE;
/*!40000 ALTER TABLE `lead_requirements` DISABLE KEYS */;
INSERT INTO `lead_requirements` VALUES (1,1,'Social Media Management (SMM 360)',1,45000.00,'Full account handling','2026-10-13 17:03:34','2026-10-13 17:03:34'),(2,1,'Meta Ads Performance Marketing',1,30000.00,'Advantage+ Catalog','2026-10-13 17:03:34','2026-10-13 17:03:34'),(3,1,'Creative Design & Branding Suite',25,800.00,'Festive post set','2026-10-13 17:03:34','2026-10-13 17:03:34'),(4,2,'Google Ads PPC & Search Optimization',1,35000.00,'Electronics high intent search','2026-10-13 17:03:34','2026-10-13 17:03:34'),(5,2,'SEO & Technical Organic Growth',1,30000.00,'Product page SEO','2026-10-13 17:03:34','2026-10-13 17:03:34'),(6,2,'Motion Graphics & 2D Animations',4,6000.00,'3D product spins','2026-10-13 17:03:34','2026-10-13 17:03:34'),(7,2,'E-Commerce Web Design & Development',1,40000.00,'Promo landing page','2026-10-13 17:03:34','2026-10-13 17:03:34'),(8,3,'Reels & Short-form Video Production',12,3500.00,'Viral food tasting reels','2026-10-13 17:03:34','2026-10-13 17:03:34'),(9,3,'Meta Ads Performance Marketing',1,25000.00,'Local Dhanmondi & Banani radius','2026-10-13 17:03:34','2026-10-13 17:03:34');
/*!40000 ALTER TABLE `lead_requirements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lead_stages`
--

DROP TABLE IF EXISTS `lead_stages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lead_stages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `color` varchar(20) NOT NULL DEFAULT '#64748b',
  `order` int(11) NOT NULL DEFAULT 0,
  `is_won` tinyint(1) NOT NULL DEFAULT 0,
  `is_lost` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lead_stages`
--

LOCK TABLES `lead_stages` WRITE;
/*!40000 ALTER TABLE `lead_stages` DISABLE KEYS */;
INSERT INTO `lead_stages` VALUES (1,'New Lead','#94a3b8',1,0,0,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(2,'Contacted','#3b82f6',2,0,0,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(3,'Meeting Done','#8b5cf6',3,0,0,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(4,'Proposal Sent','#f59e0b',4,0,0,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(5,'Negotiation','#f97316',5,0,0,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(6,'Won','#10b981',6,1,0,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(7,'Lost','#ef4444',7,0,1,'2026-10-13 17:03:34','2026-10-13 17:03:34');
/*!40000 ALTER TABLE `lead_stages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lead_timelines`
--

DROP TABLE IF EXISTS `lead_timelines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lead_timelines` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'note',
  `text` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lead_timelines_lead_id_foreign` (`lead_id`),
  KEY `lead_timelines_user_id_foreign` (`user_id`),
  CONSTRAINT `lead_timelines_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lead_timelines_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lead_timelines`
--

LOCK TABLES `lead_timelines` WRITE;
/*!40000 ALTER TABLE `lead_timelines` DISABLE KEYS */;
INSERT INTO `lead_timelines` VALUES (1,1,3,'call','Initial pitch call with Mahbubur Rahman. Discussed Q4 campaign requirements.','2026-10-13 17:03:34','2026-10-13 17:03:34'),(2,1,3,'meeting','In-person presentation at ACI Centre. Proposal warmly received.','2026-10-13 17:03:34','2026-10-13 17:03:34'),(3,1,1,'status_change','Proposal approved and deal marked as WON! Requisition created.','2026-10-13 17:03:34','2026-10-13 17:03:34'),(4,2,3,'meeting','Virtual sync with Walton Digital team. Reviewed technical scope with Tariq (SEO).','2026-10-13 17:03:34','2026-10-13 17:03:34'),(5,2,3,'proposal','Official quotation PDF generated and sent via portal.','2026-10-13 17:03:34','2026-10-13 17:03:34');
/*!40000 ALTER TABLE `lead_timelines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leads`
--

DROP TABLE IF EXISTS `leads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leads` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `source` varchar(255) NOT NULL DEFAULT 'Website',
  `stage_id` bigint(20) unsigned NOT NULL,
  `assigned_to` bigint(20) unsigned NOT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `budget` decimal(10,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `next_followup` date DEFAULT NULL,
  `quotation_status` varchar(30) NOT NULL DEFAULT 'draft',
  `quotation_notes` text DEFAULT NULL,
  `quotation_valid_until` date DEFAULT NULL,
  `quotation_discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `quotation_tax_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `quotation_approved_by` bigint(20) unsigned DEFAULT NULL,
  `quotation_approved_at` timestamp NULL DEFAULT NULL,
  `converted_client_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `leads_stage_id_foreign` (`stage_id`),
  KEY `leads_assigned_to_foreign` (`assigned_to`),
  KEY `leads_created_by_foreign` (`created_by`),
  KEY `leads_converted_client_id_foreign` (`converted_client_id`),
  KEY `leads_quotation_approved_by_foreign` (`quotation_approved_by`),
  CONSTRAINT `leads_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `leads_converted_client_id_foreign` FOREIGN KEY (`converted_client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  CONSTRAINT `leads_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `leads_quotation_approved_by_foreign` FOREIGN KEY (`quotation_approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `leads_stage_id_foreign` FOREIGN KEY (`stage_id`) REFERENCES `lead_stages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leads`
--

LOCK TABLES `leads` WRITE;
/*!40000 ALTER TABLE `leads` DISABLE KEYS */;
INSERT INTO `leads` VALUES (1,'Shwapno Super Shop (ACI)','+880 1711-554433','marketing@shwapno.net','ACI Logistics Ltd','Gulshan Avenue, Dhaka','Referral',6,3,3,95000.00,'Major retail chain. Deal closed successfully for monthly SMM + Meta Ads Retainer.','2026-10-20','approved','Approved with 5% annual corporate discount.','2026-11-12',5000.00,5.00,1,'2026-10-10 17:03:34',2,'2026-10-13 17:03:34','2026-10-13 17:03:34',NULL),(2,'Walton Digi-Tech Industries','+880 1811-223344','procurement@waltonbd.com','Walton Hi-Tech PLC','Chandra, Gazipur & Motijheel HQ, Dhaka','Website',5,3,3,125000.00,'Negotiating laptop & smartphone lineup digital campaign. Quotation submitted, waiting for approval.','2026-10-15','pending_approval','Special corporate pilot package submitted to Tanvir Ahmed.','2026-10-28',8000.00,0.00,NULL,NULL,NULL,'2026-10-13 17:03:34','2026-10-13 17:03:34',NULL),(3,'Kacchi Bhai Food Chains','+880 1911-778899','sohel@kacchibhai.bd','Kacchi Bhai Restaurants','Road 2, Dhanmondi, Dhaka','Facebook Ad',4,3,3,65000.00,'Proposal sent for 12 monthly viral food reels and geo-targeted Instagram food delivery ads.','2026-10-16','draft','Draft proposal with 12 reels and 1 Meta ad campaign.','2026-11-02',2000.00,0.00,NULL,NULL,NULL,'2026-10-13 17:03:34','2026-10-13 17:03:34',NULL),(4,'Krav Maga Fitness BD','+880 1722-112233','coach@kravmagabd.com','Elite Krav Maga Gym','Road 11, Banani, Dhaka','Instagram Ad',3,4,4,40000.00,'Met with Coach Saimum. Interested in membership lead generation ads for female self-defense batches.','2026-10-17','draft',NULL,NULL,0.00,0.00,NULL,NULL,NULL,'2026-10-13 17:03:34','2026-10-13 17:03:34',NULL),(5,'GadgetHub BD (Daraz Top Seller)','+880 1833-445566','sales@gadgethubbd.com','GadgetHub Limited','Elephant Road, Dhaka','Google Search',2,3,3,35000.00,'First discovery call completed. Requires product video unboxings and TikTok viral setup.','2026-10-18','draft',NULL,NULL,0.00,0.00,NULL,NULL,NULL,'2026-10-13 17:03:34','2026-10-13 17:03:34',NULL),(6,'Star Tech & Engineering','+880 1714-889900','asif@startechbd.com','Star Tech Ltd','Multiplan Center, New Elephant Road, Dhaka','Cold Outreach',1,4,4,85000.00,'Fresh inbound lead via agency WhatsApp line. Looking for SEO keyword ranking dominance over Ryans.','2026-10-14','draft',NULL,NULL,0.00,0.00,NULL,NULL,NULL,'2026-10-13 17:03:34','2026-10-13 17:03:34',NULL),(7,'PandaMart QuickCommerce','+880 1799-665544','vendor.ops@foodpanda.bd','Delivery Hero Bangladesh','Mirpur DOHS, Dhaka','Referral',6,3,3,75000.00,'Flash discount dynamic creatives campaign won for Q4 festival season.','2026-10-23','approved','Approved signed agreement.','2026-11-27',0.00,5.00,1,'2026-10-08 17:03:34',5,'2026-10-13 17:03:34','2026-10-13 17:03:34',NULL),(8,'PriyoShop B2B Commerce','+880 1755-332211','growth@priyoshop.com','PriyoShop Limited','Kawran Bazar C/A, Dhaka','LinkedIn',7,4,4,50000.00,'Lost to in-house marketing hire. Keep in quarterly nurture email cadence.','2027-01-13','rejected','Client opted for in-house team.',NULL,0.00,0.00,NULL,NULL,NULL,'2026-10-13 17:03:34','2026-10-13 17:03:34',NULL);
/*!40000 ALTER TABLE `leads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `meeting_attendees`
--

DROP TABLE IF EXISTS `meeting_attendees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `meeting_attendees` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `meeting_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `meeting_attendees_meeting_id_foreign` (`meeting_id`),
  KEY `meeting_attendees_user_id_foreign` (`user_id`),
  CONSTRAINT `meeting_attendees_meeting_id_foreign` FOREIGN KEY (`meeting_id`) REFERENCES `meetings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `meeting_attendees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `meeting_attendees`
--

LOCK TABLES `meeting_attendees` WRITE;
/*!40000 ALTER TABLE `meeting_attendees` DISABLE KEYS */;
INSERT INTO `meeting_attendees` VALUES (1,1,1),(2,1,3),(3,1,5),(4,2,1),(5,2,3),(6,2,5),(7,3,1),(8,3,3),(9,3,5),(10,4,1),(11,4,3),(12,4,5),(13,5,1),(14,5,3),(15,5,5);
/*!40000 ALTER TABLE `meeting_attendees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `meetings`
--

DROP TABLE IF EXISTS `meetings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `meetings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) unsigned DEFAULT NULL,
  `lead_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `client_name` varchar(255) DEFAULT NULL,
  `agenda` varchar(255) DEFAULT NULL,
  `date` date NOT NULL,
  `time` time DEFAULT NULL,
  `duration` int(11) NOT NULL DEFAULT 60,
  `location` varchar(255) DEFAULT NULL,
  `status` enum('scheduled','completed','cancelled') NOT NULL DEFAULT 'scheduled',
  `outcome` text DEFAULT NULL,
  `next_action` text DEFAULT NULL,
  `next_followup` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `meetings_client_id_foreign` (`client_id`),
  KEY `meetings_lead_id_foreign` (`lead_id`),
  KEY `meetings_created_by_foreign` (`created_by`),
  CONSTRAINT `meetings_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  CONSTRAINT `meetings_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `meetings_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `meetings`
--

LOCK TABLES `meetings` WRITE;
/*!40000 ALTER TABLE `meetings` DISABLE KEYS */;
INSERT INTO `meetings` VALUES (1,1,NULL,3,'Aarong Lifestyle','Autumn Festive Campaign Creative Storyboards & Shoot Schedule','2026-10-11','11:00:00',60,'Aarong Centre Boardroom, Tejgaon','completed','Client approved 6 moodboards. Photography shoot scheduled for Thursday.','Designer Rafi to finalize carousel layouts.','2026-10-16','2026-10-13 17:03:34','2026-10-13 17:03:34'),(2,3,NULL,5,'Apex Footwear Ltd','Venturini Sneaker Video Cut Review & TikTok Ads Strategy','2026-10-12','15:30:00',45,'Google Meet (Virtual)','completed','Sabrina approved 1st cut. Requested font size enlargement on final 3 seconds CTA.','Tanvir to render 4K version with revised audio master.','2026-10-15','2026-10-13 17:03:34','2026-10-13 17:03:34'),(3,NULL,2,3,'Walton Digi-Tech','Commercial Proposal Negotiation & Contract Scope Finalization','2026-10-14','14:00:00',60,'DMS Agency Conference Room, Banani','scheduled',NULL,'Prepare revised corporate SLA contract draft.','2026-10-15','2026-10-13 17:03:34','2026-10-13 17:03:34'),(4,4,NULL,9,'BrainStation 23 FinTech','Technical Core Web Vitals & B2B International Keyword Strategy','2026-10-16','16:00:00',45,'Zoom Meeting','scheduled',NULL,'Share SEMrush keyword comparison spreadsheet before call.','2026-10-17','2026-10-13 17:03:34','2026-10-13 17:03:34'),(5,NULL,3,3,'Kacchi Bhai Food Chains','Food Reels Pilot Presentation & Tasting Shoot Concept','2026-10-17','12:30:00',60,'Kacchi Bhai Dhanmondi Branch','scheduled',NULL,'Tanvir & Rafi to bring gimbal and lighting kit.','2026-10-18','2026-10-13 17:03:34','2026-10-13 17:03:34');
/*!40000 ALTER TABLE `meetings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2024_01_01_000001_create_users_table',1),(2,'2024_01_01_000002_create_services_table',1),(3,'2024_01_01_000003_create_clients_table',1),(4,'2024_01_01_000004_create_client_services_table',1),(5,'2024_01_01_000005_create_lead_stages_table',1),(6,'2024_01_01_000006_create_leads_table',1),(7,'2024_01_01_000007_create_lead_requirements_table',1),(8,'2024_01_01_000008_create_lead_timelines_table',1),(9,'2024_01_01_000009_create_requisitions_table',1),(10,'2024_01_01_000010_create_requisition_items_table',1),(11,'2024_01_01_000011_create_requisition_team_table',1),(12,'2024_01_01_000012_create_custom_statuses_table',1),(13,'2024_01_01_000013_create_tasks_table',1),(14,'2024_01_01_000014_create_task_progresses_table',1),(15,'2024_01_01_000015_create_task_approval_comments_table',1),(16,'2024_01_01_000016_create_content_posts_table',1),(17,'2024_01_01_000017_create_meetings_table',1),(18,'2024_01_01_000018_create_meeting_attendees_table',1),(19,'2024_01_01_000019_create_invoices_table',1),(20,'2024_01_01_000020_create_invoice_items_table',1),(21,'2024_01_01_000021_create_reminder_templates_table',1),(22,'2024_01_01_000022_create_reminders_table',1),(23,'2024_01_01_000023_create_expense_categories_table',1),(24,'2024_01_01_000024_create_expenses_table',1),(25,'2024_01_01_000025_create_targets_table',1),(26,'2024_01_01_000026_create_target_items_table',1),(27,'2024_01_01_000027_create_target_deals_table',1),(28,'2024_01_01_000028_create_worklogs_table',1),(29,'2024_01_01_000029_create_notifications_table',1),(30,'2024_01_01_000030_create_my_work_items_table',1),(31,'2024_01_01_000031_create_sessions_table',1),(32,'2026_09_14_000001_add_attachment_and_checklist_to_tasks_table',2),(33,'2026_09_23_000001_add_title_to_reminders_and_templates_table',2),(34,'2026_09_24_000001_create_followups_table',2),(35,'2026_10_13_210324_add_quotation_fields_to_leads_table',3),(36,'2026_10_13_214500_enhance_content_posts_workflow',4),(37,'2026_10_13_215200_modify_users_role_to_varchar',5),(38,'2026_10_13_221500_create_campaigns_table',6),(39,'2026_10_13_230000_add_employee_info_to_users_table',7),(40,'2026_10_13_230100_create_attendances_table',7),(41,'2026_10_13_230200_create_employee_salaries_table',7);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_work_items`
--

DROP TABLE IF EXISTS `my_work_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_work_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `priority` enum('high','medium','low') NOT NULL DEFAULT 'medium',
  `status` enum('pending','in_progress','done') NOT NULL DEFAULT 'pending',
  `due_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `my_work_items_user_id_foreign` (`user_id`),
  CONSTRAINT `my_work_items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_work_items`
--

LOCK TABLES `my_work_items` WRITE;
/*!40000 ALTER TABLE `my_work_items` DISABLE KEYS */;
INSERT INTO `my_work_items` VALUES (1,3,'Send Walton final SLA contract draft before 2 PM meeting','Sales Ops','high','in_progress','2026-10-14','Coordinate with Farhan Manager on corporate warranty clause.',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(2,5,'Review and approve draft reels submitted by Tanvir','Review','high','pending','2026-10-13','Aarong handloom documentary cut.',NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(3,6,'Export final high-res PNGs for Aarong Autumn Carousels','Creative','medium','in_progress','2026-10-14',NULL,NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(4,10,'Check Meta CAPI deduplication match rates in Event Manager','Ad Tech','high','done','2026-10-12',NULL,'2026-10-12 17:03:35','2026-10-13 17:03:35','2026-10-13 17:03:35'),(5,1,'Review SMM monthly calendar approvals for Aarong & Chaldal','Review','high','in_progress','2026-10-20','Confirm post copy and visual assets align with festive guidelines.',NULL,'2026-10-20 13:56:26','2026-10-20 13:56:26'),(6,1,'Audit Q4 Meta & Google Ads spend and ROAS performance','Ad Tech','high','pending','2026-10-20','Check CPL thresholds across active campaigns in Ads Manager.',NULL,'2026-10-20 13:56:26','2026-10-20 13:56:26'),(7,1,'Follow up on Walton monthly retainer invoice settlement','Finance','medium','done','2026-10-20','Reminded accounts team; receipt acknowledgment received.','2026-10-20 13:56:26','2026-10-20 13:56:26','2026-10-20 13:56:26'),(8,1,'Conduct team weekly standup and creative workload balancing','Operations','low','pending','2026-10-20','Check task queues for designers Rafi and Nafi.',NULL,'2026-10-20 13:56:26','2026-10-20 13:56:26'),(9,2,'Approve pending daily worklogs submitted by creative team','Review','high','in_progress','2026-10-20','Rate quality and provide constructive remarks.',NULL,'2026-10-20 13:56:26','2026-10-20 13:56:26'),(10,2,'Schedule client strategy check-in with Apex Footwear brand team','Meetings','medium','pending','2026-10-20','Send Google Meet invite for Thursday 11 AM.',NULL,'2026-10-20 13:56:26','2026-10-20 13:56:26');
/*!40000 ALTER TABLE `my_work_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `icon` varchar(20) NOT NULL DEFAULT 'pin',
  `bg_color` varchar(20) NOT NULL DEFAULT '#dbeafe',
  `message` text NOT NULL,
  `is_admin_only` tinyint(1) NOT NULL DEFAULT 0,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_foreign` (`user_id`),
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (1,NULL,'triangle-exclamation','#fee2e2','🚨 Urgent Alert: Invoice #INV-2026-009 for Square Health (48,000 BDT) is 12 days overdue!',1,0,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(2,NULL,'trophy','#dcfce7','🎉 High-Value Sale Closed: Shwapno Super Shop (95,000 BDT) approved by Admin!',0,0,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(3,5,'circle-check','#dbeafe','📋 New Team Requisition: Pathao Campaign Retainer approved and allocated to your SMM desk.',0,0,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(4,10,'chart-line','#fef3c7','📈 Performance Milestone: Aarong Advantage+ Campaign reached 4.6x ROAS with 388k BDT generated revenue.',0,0,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(5,8,'thumbs-up','#dcfce7','🎬 Client Approved Video: Apex Venturini Reel approved without revision by Sabrina.',0,1,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(6,3,'calendar','#e0e7ff','📅 Upcoming Meeting: Walton Digi-Tech contract finalization in Conference Room tomorrow at 2:00 PM.',0,0,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(7,6,'pen-to-square','#ffedd5','✍️ Revision Requested: Yellow Lifestyle requested font highlight adjustment on feed banner.',0,0,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(8,NULL,'triangle-exclamation','bg-rose-500','?? Overdue Invoice: INV-2026-004 for Aarong Lifestyle & Crafts (105,000 BDT) is 3 days overdue!',1,0,'2026-10-20 14:05:22','2026-10-20 14:05:22'),(9,NULL,'triangle-exclamation','bg-rose-500','?? Overdue Invoice: INV-2026-005 for Chaldal Fresh & Grocery (47,500 BDT) is 5 days overdue!',1,0,'2026-10-20 14:05:22','2026-10-20 14:05:22'),(10,NULL,'triangle-exclamation','bg-rose-500','?? Overdue Invoice: INV-2026-007 for Bengal Meat Gourmet Cuts (35,800 BDT) is 6 days overdue!',1,0,'2026-10-20 14:05:22','2026-10-20 14:05:22'),(11,NULL,'triangle-exclamation','bg-rose-500','?? Overdue Invoice: INV-2026-009 for Square Health Telemedicine (48,000 BDT) is 20 days overdue!',1,0,'2026-10-20 14:05:22','2026-10-20 14:05:22'),(12,6,'clock','bg-amber-500','⏰ Overdue Task: \'Design 6 High-Res Carousels for Aarong Autumn Festival\' for Aarong Lifestyle & Crafts passed its deadline. Please deliver or update status.',0,0,'2026-10-20 14:05:22','2026-10-20 14:05:22'),(13,6,'clock','bg-amber-500','⏰ Overdue Task: \'Apex Venturini Sneaker Editorial Campaign Post\' for Apex Footwear Ltd passed its deadline. Please deliver or update status.',0,0,'2026-10-20 14:05:22','2026-10-20 14:05:22'),(14,7,'clock','bg-amber-500','⏰ Overdue Task: \'Apex Venturini 15s Dynamic Kinetic 3D Logo Reveal\' for Apex Footwear Ltd passed its deadline. Please deliver or update status.',0,0,'2026-10-20 14:05:22','2026-10-20 14:05:22'),(15,8,'clock','bg-amber-500','⏰ Overdue Task: \'Aarong Handloom Artisan Documentary Reel (60s)\' for Aarong Lifestyle & Crafts passed its deadline. Please deliver or update status.',0,0,'2026-10-20 14:05:22','2026-10-20 14:05:22'),(16,8,'clock','bg-amber-500','⏰ Overdue Task: \'Bengal Meat Ribeye Grilling ASMR Reel (30s)\' for Bengal Meat Gourmet Cuts passed its deadline. Please deliver or update status.',0,0,'2026-10-20 14:05:22','2026-10-20 14:05:22'),(17,9,'clock','bg-amber-500','⏰ Overdue Task: \'BrainStation 23 Core Web Vitals & Technical Schema Fix\' for BrainStation 23 FinTech passed its deadline. Please deliver or update status.',0,0,'2026-10-20 14:05:22','2026-10-20 14:05:22'),(18,10,'clock','bg-amber-500','⏰ Overdue Task: \'Google Search PPC Campaign A/B Test for Chaldal Fresh\' for Chaldal Fresh & Grocery passed its deadline. Please deliver or update status.',0,0,'2026-10-20 14:05:22','2026-10-20 14:05:22'),(19,5,'clock','bg-amber-500','⏰ Overdue Task: \'Finalize Aarong November 30-Day Content Matrix\' for Aarong Lifestyle & Crafts passed its deadline. Please deliver or update status.',0,0,'2026-10-20 14:05:22','2026-10-20 14:05:22'),(20,11,'clock','bg-amber-500','⏰ Overdue Task: \'Weekly Retail Competitor Creative Matrix Benchmarking\' for Aarong Lifestyle & Crafts passed its deadline. Please deliver or update status.',0,0,'2026-10-20 14:05:22','2026-10-20 14:05:22'),(21,NULL,'clipboard-check','bg-indigo-500','📋 QA Review Queue: 2 daily creative deliverables awaiting SMM review & quality ratings.',1,0,'2026-10-20 14:05:22','2026-10-20 14:05:22'),(22,NULL,'file-contract','bg-blue-500','📑 Monthly Retainer Review: 6 active client retainers due for deliverable quota and invoice audits.',1,0,'2026-10-20 14:05:22','2026-10-20 14:05:22'),(23,NULL,'file-invoice-dollar','bg-emerald-500','📋 Monthly Retainer Generated: INV-202611-001 for Aarong Lifestyle & Crafts (৳118,000).',1,0,'2026-10-20 14:38:56','2026-10-20 14:38:56'),(24,NULL,'file-invoice-dollar','bg-emerald-500','📋 Monthly Retainer Generated: INV-202611-002 for Chaldal Fresh & Grocery (৳77,500).',1,0,'2026-10-20 14:38:56','2026-10-20 14:38:56'),(25,NULL,'file-invoice-dollar','bg-emerald-500','📋 Monthly Retainer Generated: INV-202611-003 for Apex Footwear Ltd (৳102,000).',1,0,'2026-10-20 14:38:56','2026-10-20 14:38:56'),(26,NULL,'file-invoice-dollar','bg-emerald-500','📋 Monthly Retainer Generated: INV-202611-004 for BrainStation 23 FinTech (৳69,400).',1,0,'2026-10-20 14:38:56','2026-10-20 14:38:56'),(27,NULL,'file-invoice-dollar','bg-emerald-500','📋 Monthly Retainer Generated: INV-202611-005 for Pathao Food & Courier (৳127,000).',1,0,'2026-10-20 14:38:56','2026-10-20 14:38:56'),(28,NULL,'file-invoice-dollar','bg-emerald-500','📋 Monthly Retainer Generated: INV-202611-006 for Bengal Meat Gourmet Cuts (৳60,800).',1,0,'2026-10-20 14:38:56','2026-10-20 14:38:56'),(29,NULL,'triangle-exclamation','bg-rose-500','🚨 Overdue Invoice: INV-2026-004 for Aarong Lifestyle & Crafts (105,000 BDT) is 20 days overdue!',1,0,'2026-11-06 10:20:11','2026-11-06 10:20:11'),(30,NULL,'triangle-exclamation','bg-rose-500','🚨 Overdue Invoice: INV-2026-005 for Chaldal Fresh & Grocery (47,500 BDT) is 22 days overdue!',1,0,'2026-11-06 10:20:11','2026-11-06 10:20:11'),(31,NULL,'triangle-exclamation','bg-rose-500','🚨 Overdue Invoice: INV-2026-006 for BrainStation 23 FinTech (69,400 BDT) is 17 days overdue!',1,0,'2026-11-06 10:20:11','2026-11-06 10:20:11'),(32,NULL,'triangle-exclamation','bg-rose-500','🚨 Overdue Invoice: INV-2026-007 for Bengal Meat Gourmet Cuts (35,800 BDT) is 23 days overdue!',1,0,'2026-11-06 10:20:11','2026-11-06 10:20:11'),(33,NULL,'triangle-exclamation','bg-rose-500','🚨 Overdue Invoice: INV-2026-008 for Yellow Lifestyle (Beximco) (110,000 BDT) is 13 days overdue!',1,0,'2026-11-06 10:20:11','2026-11-06 10:20:11'),(34,NULL,'triangle-exclamation','bg-rose-500','🚨 Overdue Invoice: INV-2026-009 for Square Health Telemedicine (48,000 BDT) is 37 days overdue!',1,0,'2026-11-06 10:20:11','2026-11-06 10:20:11'),(35,6,'clock','bg-amber-500','⏰ Overdue Task: \'Design 6 High-Res Carousels for Aarong Autumn Festival\' for Aarong Lifestyle & Crafts passed its deadline. Please deliver or update status.',0,0,'2026-11-06 10:20:11','2026-11-06 10:20:11'),(36,6,'clock','bg-amber-500','⏰ Overdue Task: \'Apex Venturini Sneaker Editorial Campaign Post\' for Apex Footwear Ltd passed its deadline. Please deliver or update status.',0,0,'2026-11-06 10:20:11','2026-11-06 10:20:11'),(37,7,'clock','bg-amber-500','⏰ Overdue Task: \'Apex Venturini 15s Dynamic Kinetic 3D Logo Reveal\' for Apex Footwear Ltd passed its deadline. Please deliver or update status.',0,0,'2026-11-06 10:20:11','2026-11-06 10:20:11'),(38,8,'clock','bg-amber-500','⏰ Overdue Task: \'Aarong Handloom Artisan Documentary Reel (60s)\' for Aarong Lifestyle & Crafts passed its deadline. Please deliver or update status.',0,0,'2026-11-06 10:20:11','2026-11-06 10:20:11'),(39,8,'clock','bg-amber-500','⏰ Overdue Task: \'Bengal Meat Ribeye Grilling ASMR Reel (30s)\' for Bengal Meat Gourmet Cuts passed its deadline. Please deliver or update status.',0,0,'2026-11-06 10:20:11','2026-11-06 10:20:11'),(40,9,'clock','bg-amber-500','⏰ Overdue Task: \'BrainStation 23 Core Web Vitals & Technical Schema Fix\' for BrainStation 23 FinTech passed its deadline. Please deliver or update status.',0,0,'2026-11-06 10:20:11','2026-11-06 10:20:11'),(41,9,'clock','bg-amber-500','⏰ Overdue Task: \'Yellow Lifestyle Headless Shopify Performance Optimization\' for Yellow Lifestyle (Beximco) passed its deadline. Please deliver or update status.',0,0,'2026-11-06 10:20:11','2026-11-06 10:20:11'),(42,10,'clock','bg-amber-500','⏰ Overdue Task: \'Google Search PPC Campaign A/B Test for Chaldal Fresh\' for Chaldal Fresh & Grocery passed its deadline. Please deliver or update status.',0,0,'2026-11-06 10:20:11','2026-11-06 10:20:11'),(43,5,'clock','bg-amber-500','⏰ Overdue Task: \'Finalize Aarong November 30-Day Content Matrix\' for Aarong Lifestyle & Crafts passed its deadline. Please deliver or update status.',0,0,'2026-11-06 10:20:11','2026-11-06 10:20:11'),(44,11,'clock','bg-amber-500','⏰ Overdue Task: \'Weekly Retail Competitor Creative Matrix Benchmarking\' for Aarong Lifestyle & Crafts passed its deadline. Please deliver or update status.',0,0,'2026-11-06 10:20:11','2026-11-06 10:20:11'),(45,NULL,'clipboard-check','bg-indigo-500','📋 QA Review Queue: 2 daily creative deliverables awaiting SMM review & quality ratings.',1,0,'2026-11-06 10:20:11','2026-11-06 10:20:11'),(46,NULL,'file-contract','bg-blue-500','📑 Monthly Retainer Review: 6 active client retainers due for deliverable quota and invoice audits.',1,0,'2026-11-06 10:20:11','2026-11-06 10:20:11');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reminder_templates`
--

DROP TABLE IF EXISTS `reminder_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reminder_templates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `type` enum('email','whatsapp','sms') NOT NULL DEFAULT 'whatsapp',
  `body` text NOT NULL,
  `days_before` int(11) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reminder_templates`
--

LOCK TABLES `reminder_templates` WRITE;
/*!40000 ALTER TABLE `reminder_templates` DISABLE KEYS */;
INSERT INTO `reminder_templates` VALUES (1,'Friendly Pre-due Reminder','Friendly Payment Notice','whatsapp','Assalamu Alaikum {client_name},\n\nThis is a friendly reminder from DMS Agency that invoice {invoice_number} for {balance_due} BDT is due on {due_date}.\n\nPlease let us know once transferred.\n\nBest regards,\nDMS Agency Finance',3,1,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(2,'Invoice Due Today Notice','Invoice Due Today Alert','whatsapp','Dear {client_name},\n\nYour retainer invoice {invoice_number} of {balance_due} BDT is due today ({due_date}).\n\nKindly clear payment today to ensure uninterrupted ad campaign delivery.\n\nDMS Agency',0,1,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(3,'Urgent Overdue Notice','Payment Overdue Notification','email','Dear {client_name},\n\nInvoice {invoice_number} ({balance_due} BDT) was due on {due_date} and is now overdue.\n\nPlease arrange remittance today or reply with payment confirmation.\n\nAccounts Department\nDMS Agency',5,1,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(4,'Final Warning & Service Pause Alert','Service Pause Warning','sms','URGENT: Invoice {invoice_number} for {client_name} is 10 days overdue. Active campaigns may be paused within 24h if {balance_due} BDT remains unpaid. Contact 01711203491.',10,1,'2026-10-13 17:03:34','2026-10-13 17:03:34');
/*!40000 ALTER TABLE `reminder_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reminders`
--

DROP TABLE IF EXISTS `reminders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reminders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint(20) unsigned NOT NULL,
  `template_id` bigint(20) unsigned DEFAULT NULL,
  `sent_by` bigint(20) unsigned NOT NULL,
  `channel` enum('email','whatsapp','sms','manual') NOT NULL DEFAULT 'whatsapp',
  `title` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reminders_invoice_id_foreign` (`invoice_id`),
  KEY `reminders_template_id_foreign` (`template_id`),
  KEY `reminders_sent_by_foreign` (`sent_by`),
  CONSTRAINT `reminders_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reminders_sent_by_foreign` FOREIGN KEY (`sent_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reminders_template_id_foreign` FOREIGN KEY (`template_id`) REFERENCES `reminder_templates` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reminders`
--

LOCK TABLES `reminders` WRITE;
/*!40000 ALTER TABLE `reminders` DISABLE KEYS */;
INSERT INTO `reminders` VALUES (1,9,3,1,'whatsapp','Urgent Payment Notice — INV-2026-009','Dear Dr. Kamrul Hasan,\n\nYour invoice INV-2026-009 of 48,000.00 BDT was due on 01 Oct 2026 and is now 12 days overdue.\n\nKindly clear payment today to avoid service interruption.\n\nDMS Agency','2026-10-12 17:03:35','2026-10-13 17:03:35','2026-10-13 17:03:35'),(2,5,1,2,'whatsapp','Reminder — Balance due for INV-2026-005','Assalamu Alaikum Wasif Chowdhury,\n\nThis is a friendly reminder that invoice INV-2026-005 for 47,500.00 BDT is due on 16 Oct 2026.\n\nPlease arrange payment at your convenience.\n\nThank you,\nDMS Agency','2026-10-13 11:03:35','2026-10-13 17:03:35','2026-10-13 17:03:35'),(3,6,1,2,'email','Upcoming Retainer Payment — INV-2026-006','Dear Anisul Huq,\n\nWe have generated invoice INV-2026-006 for 69,400.00 BDT due on 21 Oct 2026.\n\nAccounts Department\nDMS Agency','2026-10-12 23:03:35','2026-10-13 17:03:35','2026-10-13 17:03:35');
/*!40000 ALTER TABLE `reminders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `requisition_items`
--

DROP TABLE IF EXISTS `requisition_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `requisition_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `requisition_id` bigint(20) unsigned NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `requisition_items_requisition_id_foreign` (`requisition_id`),
  CONSTRAINT `requisition_items_requisition_id_foreign` FOREIGN KEY (`requisition_id`) REFERENCES `requisitions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `requisition_items`
--

LOCK TABLES `requisition_items` WRITE;
/*!40000 ALTER TABLE `requisition_items` DISABLE KEYS */;
INSERT INTO `requisition_items` VALUES (1,1,'SMM 360 Full Retainer',1,45000.00,45000.00,NULL,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(2,1,'Meta Advantage+ Ad Mgmt',1,25000.00,25000.00,NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(3,1,'Short-form Reels (8 cuts)',8,3500.00,28000.00,NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(4,1,'Ad Post Graphic Designs',10,700.00,7000.00,NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(5,2,'Google Ads Performance Search',1,35000.00,35000.00,NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(6,2,'SEO Technical Retainer',1,30000.00,30000.00,NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(7,2,'Motion 3D Animations',4,6000.00,24000.00,NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(8,2,'Campaign Landing Page',1,36000.00,36000.00,NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35');
/*!40000 ALTER TABLE `requisition_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `requisition_team`
--

DROP TABLE IF EXISTS `requisition_team`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `requisition_team` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `requisition_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `requisition_team_requisition_id_foreign` (`requisition_id`),
  KEY `requisition_team_user_id_foreign` (`user_id`),
  CONSTRAINT `requisition_team_requisition_id_foreign` FOREIGN KEY (`requisition_id`) REFERENCES `requisitions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `requisition_team_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `requisition_team`
--

LOCK TABLES `requisition_team` WRITE;
/*!40000 ALTER TABLE `requisition_team` DISABLE KEYS */;
INSERT INTO `requisition_team` VALUES (1,1,5),(2,1,6),(3,1,8),(4,1,10);
/*!40000 ALTER TABLE `requisition_team` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `requisitions`
--

DROP TABLE IF EXISTS `requisitions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `requisitions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` bigint(20) unsigned DEFAULT NULL,
  `submitted_by` bigint(20) unsigned NOT NULL,
  `reviewed_by` bigint(20) unsigned DEFAULT NULL,
  `assigned_smm` bigint(20) unsigned DEFAULT NULL,
  `converted_client_id` bigint(20) unsigned DEFAULT NULL,
  `client_name` varchar(255) NOT NULL,
  `company` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `total_value` decimal(10,2) NOT NULL DEFAULT 0.00,
  `advance_paid` decimal(10,2) NOT NULL DEFAULT 0.00,
  `billing_cycle` enum('monthly','quarterly','one-time') NOT NULL DEFAULT 'monthly',
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `special_notes` text DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `requisitions_lead_id_foreign` (`lead_id`),
  KEY `requisitions_submitted_by_foreign` (`submitted_by`),
  KEY `requisitions_reviewed_by_foreign` (`reviewed_by`),
  KEY `requisitions_assigned_smm_foreign` (`assigned_smm`),
  KEY `requisitions_converted_client_id_foreign` (`converted_client_id`),
  CONSTRAINT `requisitions_assigned_smm_foreign` FOREIGN KEY (`assigned_smm`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `requisitions_converted_client_id_foreign` FOREIGN KEY (`converted_client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  CONSTRAINT `requisitions_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE SET NULL,
  CONSTRAINT `requisitions_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `requisitions_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `requisitions`
--

LOCK TABLES `requisitions` WRITE;
/*!40000 ALTER TABLE `requisitions` DISABLE KEYS */;
INSERT INTO `requisitions` VALUES (1,1,3,1,5,1,'Aarong Lifestyle & Crafts','BRAC-Aarong Limited','+880 1711-203491','marketing@aarong.com','Tejgaon I/A, Dhaka',105000.00,50000.00,'monthly','approved','Full agency onboarding. Superadmin approved team assignment.','Approved. Assigned Rafi (Design), Tanvir (Video), and Sabbir (Media Buyer).','2026-10-09 17:03:34','2026-10-13 17:03:34','2026-10-13 17:03:34'),(2,2,3,NULL,NULL,NULL,'Walton Digi-Tech Industries','Walton Hi-Tech PLC','+880 1811-223344','procurement@waltonbd.com','Motijheel HQ, Dhaka',125000.00,35000.00,'monthly','pending','Sales closed pending Admin review. Need Tariq for SEO and Nafi for 3D Motion.',NULL,NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35');
/*!40000 ALTER TABLE `requisitions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `services` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `base_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit` varchar(255) NOT NULL DEFAULT 'month',
  `description` text DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (1,'Social Media Management (SMM 360)',35000.00,'month',NULL,1,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(2,'Meta Ads Performance Marketing',25000.00,'month',NULL,1,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(3,'Google Ads PPC & Search Optimization',22000.00,'month',NULL,1,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(4,'Creative Design & Branding Suite',800.00,'post',NULL,1,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(5,'Motion Graphics & 2D Animations',4500.00,'video',NULL,1,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(6,'Reels & Short-form Video Production',3500.00,'video',NULL,1,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(7,'SEO & Technical Organic Growth',28000.00,'month',NULL,1,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(8,'E-Commerce Web Design & Development',65000.00,'project',NULL,1,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(9,'Full-Funnel Content Writing & Copy',500.00,'article',NULL,1,'2026-10-13 17:03:34','2026-10-13 17:03:34'),(10,'Commercial Product Photography Shoot',18000.00,'session',NULL,1,'2026-10-13 17:03:34','2026-10-13 17:03:34');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `target_deals`
--

DROP TABLE IF EXISTS `target_deals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `target_deals` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `target_id` bigint(20) unsigned NOT NULL,
  `target_item_id` bigint(20) unsigned DEFAULT NULL,
  `client_id` bigint(20) unsigned DEFAULT NULL,
  `service_name` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `target_deals_target_id_foreign` (`target_id`),
  KEY `target_deals_target_item_id_foreign` (`target_item_id`),
  KEY `target_deals_client_id_foreign` (`client_id`),
  CONSTRAINT `target_deals_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  CONSTRAINT `target_deals_target_id_foreign` FOREIGN KEY (`target_id`) REFERENCES `targets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `target_deals_target_item_id_foreign` FOREIGN KEY (`target_item_id`) REFERENCES `target_items` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `target_deals`
--

LOCK TABLES `target_deals` WRITE;
/*!40000 ALTER TABLE `target_deals` DISABLE KEYS */;
INSERT INTO `target_deals` VALUES (1,1,1,1,'Aarong SMM 360 Contract Renewal',1,45000.00,'2026-10-08','2026-10-13 17:03:35','2026-10-13 17:03:35'),(2,1,2,2,'Chaldal Meta Ads Q4 Boost',1,30000.00,'2026-10-05','2026-10-13 17:03:35','2026-10-13 17:03:35'),(3,2,4,4,'BrainStation 23 SEO Retainer',1,35000.00,'2026-10-09','2026-10-13 17:03:35','2026-10-13 17:03:35');
/*!40000 ALTER TABLE `target_deals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `target_items`
--

DROP TABLE IF EXISTS `target_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `target_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `target_id` bigint(20) unsigned NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `target_items_target_id_foreign` (`target_id`),
  CONSTRAINT `target_items_target_id_foreign` FOREIGN KEY (`target_id`) REFERENCES `targets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `target_items`
--

LOCK TABLES `target_items` WRITE;
/*!40000 ALTER TABLE `target_items` DISABLE KEYS */;
INSERT INTO `target_items` VALUES (1,1,'SMM 360 Full Retainer Packages',3,45000.00,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(2,1,'Performance Ad Management',4,25000.00,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(3,1,'Brand Creative Retainers',2,35000.00,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(4,2,'Enterprise SEO Packages',2,35000.00,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(5,2,'Corporate Web Development',1,75000.00,'2026-10-13 17:03:35','2026-10-13 17:03:35');
/*!40000 ALTER TABLE `target_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `targets`
--

DROP TABLE IF EXISTS `targets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `targets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `month` varchar(255) NOT NULL,
  `label` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `targets_user_id_foreign` (`user_id`),
  KEY `targets_created_by_foreign` (`created_by`),
  CONSTRAINT `targets_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `targets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `targets`
--

LOCK TABLES `targets` WRITE;
/*!40000 ALTER TABLE `targets` DISABLE KEYS */;
INSERT INTO `targets` VALUES (1,3,1,'2026-10','Q4 Agency Expansion Target','2026-10-13 17:03:35','2026-10-13 17:03:35'),(2,4,1,'2026-10','B2B & Enterprise Accounts Quota','2026-10-13 17:03:35','2026-10-13 17:03:35');
/*!40000 ALTER TABLE `targets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `task_approval_comments`
--

DROP TABLE IF EXISTS `task_approval_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `task_approval_comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `text` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `task_approval_comments_task_id_foreign` (`task_id`),
  KEY `task_approval_comments_user_id_foreign` (`user_id`),
  CONSTRAINT `task_approval_comments_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `task_approval_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `task_approval_comments`
--

LOCK TABLES `task_approval_comments` WRITE;
/*!40000 ALTER TABLE `task_approval_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `task_approval_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `task_progresses`
--

DROP TABLE IF EXISTS `task_progresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `task_progresses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `note` text NOT NULL,
  `done` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `task_progresses_task_id_foreign` (`task_id`),
  KEY `task_progresses_user_id_foreign` (`user_id`),
  CONSTRAINT `task_progresses_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `task_progresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `task_progresses`
--

LOCK TABLES `task_progresses` WRITE;
/*!40000 ALTER TABLE `task_progresses` DISABLE KEYS */;
INSERT INTO `task_progresses` VALUES (1,1,6,'4 out of 6 carousel layouts completed in Figma.',4,6,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(2,4,7,'C4D render pass complete. Moving to After Effects.',1,2,'2026-10-13 17:03:35','2026-10-13 17:03:35');
/*!40000 ALTER TABLE `task_progresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tasks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `client_id` bigint(20) unsigned DEFAULT NULL,
  `assigned_to` bigint(20) unsigned NOT NULL,
  `assigned_by` bigint(20) unsigned NOT NULL,
  `service_id` bigint(20) unsigned DEFAULT NULL,
  `parent_task_id` bigint(20) unsigned DEFAULT NULL,
  `custom_status_id` bigint(20) unsigned DEFAULT NULL,
  `status` enum('pending','in_progress','done_pending_review','done') NOT NULL DEFAULT 'pending',
  `priority` enum('high','medium','low') NOT NULL DEFAULT 'medium',
  `approval_status` enum('pending','approved','revision') DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `checklist` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`checklist`)),
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachments`)),
  `deadline` date DEFAULT NULL,
  `scheduled_date` date DEFAULT NULL,
  `estimated_hours` decimal(5,2) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `recurring_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `recurring_type` enum('daily','weekly','monthly') DEFAULT NULL,
  `recurring_interval` int(11) NOT NULL DEFAULT 1,
  `recurring_end_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tasks_client_id_foreign` (`client_id`),
  KEY `tasks_assigned_to_foreign` (`assigned_to`),
  KEY `tasks_assigned_by_foreign` (`assigned_by`),
  KEY `tasks_service_id_foreign` (`service_id`),
  KEY `tasks_parent_task_id_foreign` (`parent_task_id`),
  KEY `tasks_custom_status_id_foreign` (`custom_status_id`),
  CONSTRAINT `tasks_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tasks_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tasks_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  CONSTRAINT `tasks_custom_status_id_foreign` FOREIGN KEY (`custom_status_id`) REFERENCES `custom_statuses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `tasks_parent_task_id_foreign` FOREIGN KEY (`parent_task_id`) REFERENCES `tasks` (`id`) ON DELETE SET NULL,
  CONSTRAINT `tasks_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tasks`
--

LOCK TABLES `tasks` WRITE;
/*!40000 ALTER TABLE `tasks` DISABLE KEYS */;
INSERT INTO `tasks` VALUES (1,'Design 6 High-Res Carousels for Aarong Autumn Festival',1,6,5,4,NULL,NULL,'in_progress','high','pending','Match ethnic earthy color palette with BRAC brand guidelines. 1080x1350 vertical carousel format.','[{\"id\":1,\"text\":\"Product cutouts & tone correction\",\"done\":true},{\"id\":2,\"text\":\"Bengali typographic headline alignment\",\"done\":true},{\"id\":3,\"text\":\"Client feedback & export 4x PNGs\",\"done\":false}]','[{\"name\":\"Aarong_Autumn_Moodboard.pdf\",\"url\":\"https:\\/\\/assets.dmsagency.com\\/briefs\\/aarong_autumn.pdf\",\"size\":\"4.2MB\"},{\"name\":\"Figma_Source_File.fig\",\"url\":\"https:\\/\\/www.figma.com\\/file\\/aarong-autumn-carousels\",\"size\":\"18.4MB\"}]','2026-10-15','2026-10-13',6.50,6,0,NULL,1,NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35',NULL),(2,'Chaldal Flash Sale Banner Suite (Facebook & Mobile App)',2,6,5,4,NULL,NULL,'done','high','approved','Promote fresh seasonal vegetables flat 20% off.','[{\"id\":1,\"text\":\"Square 1080x1080 Feed banner\",\"done\":true},{\"id\":2,\"text\":\"Story 1080x1920 format\",\"done\":true},{\"id\":3,\"text\":\"App Push Notification banner\",\"done\":true}]','[{\"name\":\"Chaldal_Approved_Assets.zip\",\"url\":\"https:\\/\\/assets.dmsagency.com\\/delivery\\/chaldal_flash.zip\",\"size\":\"8.6MB\"}]','2026-10-12','2026-10-11',4.00,3,0,NULL,1,NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35',NULL),(3,'Apex Venturini Sneaker Editorial Campaign Post',3,6,5,4,NULL,NULL,'done_pending_review','medium','pending','Edgy urban aesthetic targeting university students.','[{\"id\":1,\"text\":\"Sneaker lighting contrast tweak\",\"done\":true},{\"id\":2,\"text\":\"Incorporate Venturini badge logo\",\"done\":true}]',NULL,'2026-10-14',NULL,3.50,1,0,NULL,1,NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35',NULL),(4,'Apex Venturini 15s Dynamic Kinetic 3D Logo Reveal',3,7,2,5,NULL,NULL,'in_progress','high','pending','High energy 120fps motion graphics with bass-heavy sound effect sync.','[{\"id\":1,\"text\":\"Cinema4D render passes\",\"done\":true},{\"id\":2,\"text\":\"After Effects compositing\",\"done\":false},{\"id\":3,\"text\":\"Sound FX audio master\",\"done\":false}]','[{\"name\":\"Apex_Audio_WAV.wav\",\"url\":\"https:\\/\\/assets.dmsagency.com\\/audio\\/apex_bass.wav\",\"size\":\"12.1MB\"}]','2026-10-16',NULL,8.00,1,0,NULL,1,NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35',NULL),(5,'Pathao Courier Kinetic Typography Promo Reel',5,7,5,5,NULL,NULL,'done','high','approved','Deliver in 1080x1920 vertical 9:16 format for Instagram & TikTok.',NULL,NULL,'2026-10-11',NULL,5.00,1,0,NULL,1,NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35',NULL),(6,'Aarong Handloom Artisan Documentary Reel (60s)',1,8,5,6,NULL,NULL,'done_pending_review','high','pending','Footage from Tangail loom village. Warm organic color grade with acoustic flute soundtrack.','[{\"id\":1,\"text\":\"Rough cut & audio synch\",\"done\":true},{\"id\":2,\"text\":\"DaVinci Resolve color grade\",\"done\":true},{\"id\":3,\"text\":\"English subtitles styling\",\"done\":true}]','[{\"name\":\"Google_Drive_Raw_Footage.txt\",\"url\":\"https:\\/\\/drive.google.com\\/drive\\/folders\\/aarong-raw-video\",\"size\":\"1KB\"}]','2026-10-14',NULL,7.50,1,0,NULL,1,NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35',NULL),(7,'Bengal Meat Ribeye Grilling ASMR Reel (30s)',6,8,5,6,NULL,NULL,'in_progress','medium','pending','Sizzling steak audio amplification and butter basting closeups.',NULL,NULL,'2026-10-17',NULL,4.50,1,0,NULL,1,NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35',NULL),(8,'BrainStation 23 Core Web Vitals & Technical Schema Fix',4,9,1,7,NULL,NULL,'in_progress','high',NULL,'Resolve LCP delays and configure FAQ Schema markup for FinTech solution pages.','[{\"id\":1,\"text\":\"WebP image compression pipeline\",\"done\":true},{\"id\":2,\"text\":\"Defer unused JavaScript bundles\",\"done\":true},{\"id\":3,\"text\":\"JSON-LD schema testing in Google Validator\",\"done\":false}]',NULL,'2026-10-18',NULL,8.00,NULL,0,NULL,1,NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35',NULL),(9,'Yellow Lifestyle Headless Shopify Performance Optimization',7,9,2,8,NULL,NULL,'pending','high',NULL,'Audit mobile checkout load time and bundle splitting.',NULL,NULL,'2026-10-21',NULL,12.00,NULL,0,NULL,1,NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35',NULL),(10,'Configure Meta Advantage+ Shopping Campaign for Aarong',1,10,2,2,NULL,NULL,'done','high','approved','Pixel verified, CAPI server event deductions tested.',NULL,NULL,'2026-10-10',NULL,4.00,NULL,0,NULL,1,NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35',NULL),(11,'Google Search PPC Campaign A/B Test for Chaldal Fresh',2,10,2,3,NULL,NULL,'in_progress','high',NULL,'Optimize negative keyword list for grocery organic vs commercial intent.',NULL,NULL,'2026-10-15',NULL,5.00,NULL,0,NULL,1,NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35',NULL),(12,'Finalize Aarong November 30-Day Content Matrix',1,5,1,1,NULL,NULL,'in_progress','high',NULL,'Schedule 24 posts and 8 reels in Content Ops calendar.',NULL,NULL,'2026-10-16',NULL,6.00,NULL,0,NULL,1,NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35',NULL),(13,'Weekly Retail Competitor Creative Matrix Benchmarking',1,11,5,NULL,NULL,NULL,'in_progress','medium',NULL,'Capture ad copies and visual hooks of Yellow, Sailor, and Taaga.',NULL,NULL,'2026-10-14',NULL,3.00,NULL,0,NULL,1,NULL,'2026-10-13 17:03:35','2026-10-13 17:03:35',NULL);
/*!40000 ALTER TABLE `tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT 'sales',
  `designation` varchar(100) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `base_salary` decimal(10,2) NOT NULL DEFAULT 0.00,
  `emergency_contact` varchar(100) DEFAULT NULL,
  `color` varchar(20) NOT NULL DEFAULT '#64748b',
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Mehedi Hasan','admin','admin@dmsagency.com','+880 1711-100200','$2y$12$qxL173dJ1Y28TITMDLZ9De8QLMmOK9rVm10NTshfTtO.P9pi5XGJC','owner','CEO & Agency Managing Director','Executive Leadership','2024-01-01',120000.00,'Dr. Hasan (+880 1711-998811)','#f59e0b',1,NULL,'2026-10-13 17:03:28','2026-10-13 17:03:28'),(2,'Farhan Manager','manager','manager@dmsagency.com','+880 1712-200300','$2y$12$D2SNsyUBSRrj1NhpTLPySOFhTpAYwAfOD4tsJVMZ.x6RvWu6W.v9u','admin','Chief Operating Officer (COO)','Agency Operations & Finance','2024-03-01',85000.00,'Fahim Manager (+880 1712-887766)','#6366f1',1,NULL,'2026-10-13 17:03:29','2026-10-13 17:03:29'),(3,'Rahim Uddin','rahim','rahim@dmsagency.com','+880 1713-300400','$2y$12$rw41yw4.lT1RULxcJ11Fe.1AcSOz57gG4VBHAX/S1Q4noG1x8slCu','sales','Senior Business Development Lead','Sales & Partnerships','2024-06-15',60000.00,'Mrs. Rahim (+880 1713-776655)','#3b82f6',1,NULL,'2026-10-13 17:03:29','2026-10-13 17:03:29'),(4,'Karim Sheikh','karim','karim@dmsagency.com','+880 1714-400500','$2y$12$BCztUCcSmEY76ubtfJXaCORYkoVH2E7FTGbI8qBlqfY9nsETeARo2','sales','B2B Enterprise Account Executive','Sales & Partnerships','2024-08-01',55000.00,'Abul Sheikh (+880 1714-665544)','#8b5cf6',1,NULL,'2026-10-13 17:03:30','2026-10-13 17:03:30'),(5,'Sara Begum','sara','sara@dmsagency.com','+880 1715-500600','$2y$12$0t3nRz9QEcFrrcoCmPFkRea0irA7EYua8ENCemBE282DJT/sqsqse','smm','Head of Social Media & Content Strategy','SMM & Editorial Operations','2024-04-10',52000.00,'Salma Begum (+880 1715-554433)','#f97316',1,NULL,'2026-10-13 17:03:31','2026-10-13 17:03:31'),(6,'Rafi Ahmed','rafi','rafi@dmsagency.com','+880 1716-600700','$2y$12$5a3MdZXtDRAApSmgsU7q9ech8kGganZsgWJcikuHnAusmyzJJUtwe','designer','Lead Visual & Brand Identity Designer','Creative Production','2024-05-01',48000.00,'Rina Ahmed (+880 1716-443322)','#ec4899',1,NULL,'2026-10-13 17:03:31','2026-10-13 17:03:31'),(7,'Nafi Islam','nafi','nafi@dmsagency.com','+880 1717-700800','$2y$12$vhQvW0RggEcOr.sdRFGlcOxYLCH./dq6zxOTqd77IMENa/ZrL4X3O','motion','Senior 3D & Kinetic Motion Artist','Motion & VFX','2024-07-20',50000.00,'Nur Islam (+880 1717-332211)','#14b8a6',1,NULL,'2026-10-13 17:03:32','2026-10-13 17:03:32'),(8,'Tanvir Editor','tanvir','tanvir@dmsagency.com','+880 1718-800900','$2y$12$A3s0xtZazCsuqZ.tVTHfU.RoNX/MJdguU4tx.8urZXuXeqrqxxU32','video','Lead Video Editor & Colorist','Video Production','2024-09-01',45000.00,'Tareq Editor (+880 1718-221100)','#f43f5e',1,NULL,'2026-10-13 17:03:32','2026-10-13 17:03:32'),(9,'Tariq Hassan','tariq','tariq@dmsagency.com','+880 1719-900100','$2y$12$ezaWvtR0W6rVOxwtwVTrUeG68Gd7FswYETxglgy4UyBQvyjCgVjVG','seo','Lead Technical SEO & Full-Stack Engineer','Engineering & Organic Growth','2024-02-15',65000.00,'Nusrat Hassan (+880 1719-110099)','#10b981',1,NULL,'2026-10-13 17:03:33','2026-10-13 17:03:33'),(10,'Sabbir Ads','sabbir','sabbir@dmsagency.com','+880 1720-001122','$2y$12$zZ0Y1hQQQeHn29ig7q80xu3fIMYKlPu1rCJ155Yv.FcjDSzsqpyey','mediabuyer','Senior Performance Marketer & Media Buyer','Paid Performance Media','2024-05-15',58000.00,'Saif Ads (+880 1720-998877)','#0ea5e9',1,NULL,'2026-10-13 17:03:33','2026-10-13 17:03:33'),(11,'Joy Team','joy','joy@dmsagency.com','+880 1721-112233','$2y$12$/iwUlasrBSzvSp91fAoSIuLjwzBkXYDrwKc0iwhIEMxPEHDKYAcBS','employee','Operations & Account Support Associate','General Operations','2025-01-10',32000.00,'Fatema (+880 1721-887766)','#64748b',1,NULL,'2026-10-13 17:03:34','2026-10-13 17:03:34');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `worklogs`
--

DROP TABLE IF EXISTS `worklogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `worklogs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned DEFAULT NULL,
  `task_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `qty_done` int(11) NOT NULL DEFAULT 0,
  `qty_total` int(11) NOT NULL DEFAULT 0,
  `quality_rating` tinyint(4) DEFAULT NULL,
  `smm_feedback` text DEFAULT NULL,
  `status` enum('pending','approved','revision') NOT NULL DEFAULT 'pending',
  `date` date NOT NULL,
  `reviewed_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `worklogs_user_id_foreign` (`user_id`),
  KEY `worklogs_client_id_foreign` (`client_id`),
  KEY `worklogs_task_id_foreign` (`task_id`),
  KEY `worklogs_reviewed_by_foreign` (`reviewed_by`),
  CONSTRAINT `worklogs_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  CONSTRAINT `worklogs_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `worklogs_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE SET NULL,
  CONSTRAINT `worklogs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `worklogs`
--

LOCK TABLES `worklogs` WRITE;
/*!40000 ALTER TABLE `worklogs` DISABLE KEYS */;
INSERT INTO `worklogs` VALUES (1,6,1,1,'Designed 4 Vertical Carousels for Aarong Autumn Festival','Extracted 12 high-resolution sarees and kurtis, color graded backgrounds to match warm festive mood, formatted typography.',4,6,5,'Flawless execution! Typography alignment and brand consistency are top notch.','approved','2026-10-12',5,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(2,6,2,2,'Completed 3 Chaldal Fresh Sale Banners','Designed square 1080x1080, story 1080x1920, and mobile push banner.',3,3,5,'Delivered well before scheduled deadline.','approved','2026-10-11',5,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(3,7,3,4,'Cinema4D Render Passes for Apex Venturini 3D Logo Reveal','Exported ambient occlusion, depth passes, and specular reflections at 4K 60fps.',1,2,5,'Render quality is cinematic. Ready for AE composite.','approved','2026-10-12',2,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(4,7,5,5,'Pathao Courier Kinetic Typography Promo Animation','Completed motion graphics in After Effects, timed to upbeat electronic music track.',1,1,4,'Great energy! Minor easing adjustment done on outro logo.','approved','2026-10-11',5,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(5,8,1,6,'Rough & Color Graded Cut for Tangail Loom Documentary','Synchronized 4K Sony FX3 footage, applied custom organic film LUT, balanced flute background audio.',1,1,5,'Heart-touching storytelling. Client will love this.','approved','2026-10-12',5,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(6,9,4,8,'Resolved LCP Delays & Configured FAQ JSON-LD Schema','LCP improved from 3.8s to 1.4s on mobile. Verified rich snippets in Google Search Console.',2,3,5,'Impressive speed score improvement on PageSpeed Insights.','approved','2026-10-11',1,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(7,10,1,10,'Configured Advantage+ Catalog Shopping Campaign & Conversions API','Mapped 800+ product catalog items, setup deduplicated server events via Stape Cloud GTM.',1,1,5,'Event match quality reached 9.4/10. Outstanding.','approved','2026-10-10',2,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(8,11,1,13,'Audited Top 5 Apparel Competitor Facebook Ad Creative Hooks','Compiled 25 ad screenshots, headline copies, and CTA comparisons in Notion matrix.',1,1,4,'Very useful competitive insight for our upcoming sprint.','approved','2026-10-12',5,'2026-10-13 17:03:35','2026-10-13 17:03:35'),(9,6,1,NULL,'Winter Campaign Social Media Banners (Instagram & Facebook Square)','Designed 3 visual layouts with brand color palettes and typography for upcoming winter collection.',3,5,NULL,NULL,'pending','2026-10-19',NULL,'2026-10-20 13:43:21','2026-10-20 13:43:21'),(10,7,2,NULL,'15-sec Chaldal Quick Delivery Promo Reel Motion Graphics','Created kinetic text animations and product transitions for the 30-minute flash grocery delivery promo.',1,1,NULL,NULL,'pending','2026-10-20',NULL,'2026-10-20 13:43:21','2026-10-20 13:43:21'),(11,8,3,NULL,'Apex Footwear Lifestyle Video Edit - First Cut','Synced multi-cam footage, added background beats and transitions. Pending color grade adjustments.',1,2,3,'Pacing feels slightly fast in scene 2. Please slow down transition and adjust color warmth.','revision','2026-10-18',5,'2026-10-20 13:43:21','2026-10-20 13:43:21');
/*!40000 ALTER TABLE `worklogs` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-06 17:00:05
