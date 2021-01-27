-- MySQL dump 10.13  Distrib 5.7.19, for Linux (x86_64)
--
-- Host: localhost    Database: sociomanager
-- ------------------------------------------------------
-- Server version	5.7.19-0ubuntu0.16.04.1

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
-- Table structure for table `dm_logs`
--

DROP TABLE IF EXISTS `dm_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dm_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('ig','tw') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dm_logs`
--

LOCK TABLES `dm_logs` WRITE;
/*!40000 ALTER TABLE `dm_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `dm_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `drafts`
--

DROP TABLE IF EXISTS `drafts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `drafts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `msg_body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `instagram_ids` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook_ids` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_ids` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fb_group_ids` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fb_page_ids` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `schedule_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `image_file` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_file` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `drafts`
--

LOCK TABLES `drafts` WRITE;
/*!40000 ALTER TABLE `drafts` DISABLE KEYS */;
/*!40000 ALTER TABLE `drafts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `facebook_accounts`
--

DROP TABLE IF EXISTS `facebook_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `facebook_accounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `credentials` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_picture` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `facebook_accounts`
--

LOCK TABLES `facebook_accounts` WRITE;
/*!40000 ALTER TABLE `facebook_accounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `facebook_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fb_groups`
--

DROP TABLE IF EXISTS `fb_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fb_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `facebook_id` int(10) unsigned NOT NULL,
  `group_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fb_groups_facebook_id_foreign` (`facebook_id`),
  CONSTRAINT `fb_groups_facebook_id_foreign` FOREIGN KEY (`facebook_id`) REFERENCES `facebook_accounts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fb_groups`
--

LOCK TABLES `fb_groups` WRITE;
/*!40000 ALTER TABLE `fb_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `fb_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fb_pages`
--

DROP TABLE IF EXISTS `fb_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fb_pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `facebook_id` int(10) unsigned NOT NULL,
  `page_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `page_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `page_credentials` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fb_pages_facebook_id_foreign` (`facebook_id`),
  CONSTRAINT `fb_pages_facebook_id_foreign` FOREIGN KEY (`facebook_id`) REFERENCES `facebook_accounts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fb_pages`
--

LOCK TABLES `fb_pages` WRITE;
/*!40000 ALTER TABLE `fb_pages` DISABLE KEYS */;
/*!40000 ALTER TABLE `fb_pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `follow_back_logs`
--

DROP TABLE IF EXISTS `follow_back_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `follow_back_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('ig','tw') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `follow_back_logs`
--

LOCK TABLES `follow_back_logs` WRITE;
/*!40000 ALTER TABLE `follow_back_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `follow_back_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ig_dms`
--

DROP TABLE IF EXISTS `ig_dms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ig_dms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `instagram_id` int(10) unsigned NOT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `end_date` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive','completed','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ig_dms_instagram_id_foreign` (`instagram_id`),
  CONSTRAINT `ig_dms_instagram_id_foreign` FOREIGN KEY (`instagram_id`) REFERENCES `instagram_accounts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ig_dms`
--

LOCK TABLES `ig_dms` WRITE;
/*!40000 ALTER TABLE `ig_dms` DISABLE KEYS */;
/*!40000 ALTER TABLE `ig_dms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ig_follow_backs`
--

DROP TABLE IF EXISTS `ig_follow_backs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ig_follow_backs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `instagram_id` int(10) unsigned NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `end_date` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive','completed','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `exception` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exclude_verified` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `exclude_non_verified` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `ig_follow_backs_instagram_id_foreign` (`instagram_id`),
  CONSTRAINT `ig_follow_backs_instagram_id_foreign` FOREIGN KEY (`instagram_id`) REFERENCES `instagram_accounts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ig_follow_backs`
--

LOCK TABLES `ig_follow_backs` WRITE;
/*!40000 ALTER TABLE `ig_follow_backs` DISABLE KEYS */;
/*!40000 ALTER TABLE `ig_follow_backs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ig_unfollows`
--

DROP TABLE IF EXISTS `ig_unfollows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ig_unfollows` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `instagram_id` int(10) unsigned NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `end_date` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive','completed','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `exception` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exclude_verified` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `exclude_non_verified` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `ig_unfollows_instagram_id_foreign` (`instagram_id`),
  CONSTRAINT `ig_unfollows_instagram_id_foreign` FOREIGN KEY (`instagram_id`) REFERENCES `instagram_accounts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ig_unfollows`
--

LOCK TABLES `ig_unfollows` WRITE;
/*!40000 ALTER TABLE `ig_unfollows` DISABLE KEYS */;
/*!40000 ALTER TABLE `ig_unfollows` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `instagram_accounts`
--

DROP TABLE IF EXISTS `instagram_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `instagram_accounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `credentials` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_picture` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `followers` int(11) DEFAULT NULL,
  `following` int(11) DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `instagram_accounts`
--

LOCK TABLES `instagram_accounts` WRITE;
/*!40000 ALTER TABLE `instagram_accounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `instagram_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `state` enum('danger','warning','info','success') COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('fb','tw','ig') COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs`
--

LOCK TABLES `logs` WRITE;
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2017_12_16_124910_create_instagram_accounts_table',1),(4,'2017_12_16_125323_create_twitter_accounts_table',1),(5,'2017_12_16_125733_create_facebook_accounts_table',1),(6,'2017_12_16_130208_create_ig_follow_backs_table',1),(7,'2017_12_16_130348_create_ig_dms_table',1),(8,'2017_12_16_130546_create_ig_unfollows_table',1),(9,'2017_12_16_130835_create_tw_follow_backs_table',1),(10,'2017_12_16_131039_create_tw_dms_table',1),(11,'2017_12_16_131228_create_tw_unfollows_table',1),(12,'2017_12_16_131512_create_fb_groups_table',1),(13,'2017_12_16_131831_create_fb_pages_table',1),(14,'2017_12_16_132054_create_post_queue_table',1),(15,'2017_12_16_132306_create_drafts_table',1),(16,'2017_12_16_143231_create_picture_queue_table',1),(17,'2017_12_16_151455_create_post_logs_table',1),(18,'2017_12_16_152100_create_follow_back_logs_table',1),(19,'2017_12_16_152345_create_dm_logs_table',1),(20,'2017_12_16_152746_create_unfollow_logs_table',1),(21,'2017_12_27_125359_create_logs_table',1),(22,'2018_01_08_175155_update_post_queue_table',1),(23,'2018_01_10_172442_update_ig_follow_backs_table',1),(24,'2018_01_10_173051_update_ig_unfollows_table',1),(25,'2018_01_10_173434_update_tw_follow_backs_table',1),(26,'2018_01_10_174009_update_tw_unfollows_table',1),(27,'2018_01_11_200242_create_sessions_table',1),(28,'2018_01_12_151836_create_permission_tables',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` int(10) unsigned NOT NULL,
  `model_id` int(10) unsigned NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_roles` (
  `role_id` int(10) unsigned NOT NULL,
  `model_id` int(10) unsigned NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,1,'App\\User');
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `picture_queue`
--

DROP TABLE IF EXISTS `picture_queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `picture_queue` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `image_file` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `instagram_ids` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook_ids` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_ids` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `schedule_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('active','inactive','completed','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `picture_queue`
--

LOCK TABLES `picture_queue` WRITE;
/*!40000 ALTER TABLE `picture_queue` DISABLE KEYS */;
/*!40000 ALTER TABLE `picture_queue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_logs`
--

DROP TABLE IF EXISTS `post_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('fb','tw','ig') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_logs`
--

LOCK TABLES `post_logs` WRITE;
/*!40000 ALTER TABLE `post_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `post_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_queue`
--

DROP TABLE IF EXISTS `post_queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post_queue` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `msg_body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `instagram_ids` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook_ids` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_ids` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fb_group_ids` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fb_page_ids` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `schedule_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `image_file` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_file` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive','completed','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ig_single` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `ig_story` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes',
  `tw_status` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes',
  `tw_media` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes',
  `fb_status` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes',
  `fb_media` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes',
  `fb_link` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `video_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_queue`
--

LOCK TABLES `post_queue` WRITE;
/*!40000 ALTER TABLE `post_queue` DISABLE KEYS */;
/*!40000 ALTER TABLE `post_queue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin','web','2018-01-14 22:19:51','2018-01-14 22:19:51'),(2,'user','web','2018-01-14 22:19:51','2018-01-14 22:19:51');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  UNIQUE KEY `sessions_id_unique` (`id`)
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
-- Table structure for table `tw_dms`
--

DROP TABLE IF EXISTS `tw_dms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tw_dms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `twitter_id` int(10) unsigned NOT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `end_date` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive','completed','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tw_dms_twitter_id_foreign` (`twitter_id`),
  CONSTRAINT `tw_dms_twitter_id_foreign` FOREIGN KEY (`twitter_id`) REFERENCES `twitter_accounts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tw_dms`
--

LOCK TABLES `tw_dms` WRITE;
/*!40000 ALTER TABLE `tw_dms` DISABLE KEYS */;
/*!40000 ALTER TABLE `tw_dms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tw_follow_backs`
--

DROP TABLE IF EXISTS `tw_follow_backs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tw_follow_backs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `twitter_id` int(10) unsigned NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `end_date` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive','completed','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `exception` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exclude_verified` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `exclude_non_verified` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `tw_follow_backs_twitter_id_foreign` (`twitter_id`),
  CONSTRAINT `tw_follow_backs_twitter_id_foreign` FOREIGN KEY (`twitter_id`) REFERENCES `twitter_accounts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tw_follow_backs`
--

LOCK TABLES `tw_follow_backs` WRITE;
/*!40000 ALTER TABLE `tw_follow_backs` DISABLE KEYS */;
/*!40000 ALTER TABLE `tw_follow_backs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tw_unfollows`
--

DROP TABLE IF EXISTS `tw_unfollows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tw_unfollows` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `twitter_id` int(10) unsigned NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `end_date` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive','completed','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `exception` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exclude_verified` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `exclude_non_verified` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `tw_unfollows_twitter_id_foreign` (`twitter_id`),
  CONSTRAINT `tw_unfollows_twitter_id_foreign` FOREIGN KEY (`twitter_id`) REFERENCES `twitter_accounts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tw_unfollows`
--

LOCK TABLES `tw_unfollows` WRITE;
/*!40000 ALTER TABLE `tw_unfollows` DISABLE KEYS */;
/*!40000 ALTER TABLE `tw_unfollows` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `twitter_accounts`
--

DROP TABLE IF EXISTS `twitter_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `twitter_accounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `credentials` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_picture` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `followers` int(11) DEFAULT NULL,
  `following` int(11) DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `twitter_accounts`
--

LOCK TABLES `twitter_accounts` WRITE;
/*!40000 ALTER TABLE `twitter_accounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `twitter_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unfollow_logs`
--

DROP TABLE IF EXISTS `unfollow_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unfollow_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('ig','tw') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unfollow_logs`
--

LOCK TABLES `unfollow_logs` WRITE;
/*!40000 ALTER TABLE `unfollow_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `unfollow_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator','admin@mail.com','$2y$10$vxULbKg46RHl4LhCr7wZ0eq2t5JaMRsZXGhsmIIhpzjLQUK1fEMwy',NULL,'2018-01-14 22:19:51','2018-01-14 22:19:51');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-01-14 21:21:36
