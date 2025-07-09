-- MySQL dump 10.13  Distrib 8.0.38, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: pq-res-lvl-live
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `branch_configs`
--

DROP TABLE IF EXISTS `branch_configs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `branch_configs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint unsigned NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'United States',
  `tax` decimal(8,2) NOT NULL DEFAULT '0.00',
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `dial_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '+1',
  `currency_symbol` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_charges` decimal(8,2) NOT NULL DEFAULT '0.00',
  `tips` decimal(8,2) NOT NULL DEFAULT '0.00',
  `enableTax` tinyint(1) NOT NULL DEFAULT '1',
  `enableDeliveryCharges` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `branch_configs_branch_id_unique` (`branch_id`),
  CONSTRAINT `branch_configs_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `branch_configs`
--

LOCK TABLES `branch_configs` WRITE;
/*!40000 ALTER TABLE `branch_configs` DISABLE KEYS */;
INSERT INTO `branch_configs` VALUES (1,1,'Philippines',0.00,'PHP','+63','₱',0.00,0.00,1,1,'2025-07-09 02:42:19','2025-07-09 02:49:55');
/*!40000 ALTER TABLE `branch_configs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint unsigned DEFAULT NULL,
  `restaurant_id` int DEFAULT NULL,
  `identifier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (4,'Snacks',NULL,2,'CAT-004','Crispy fries, nuggets, and other quick bites.','images/category/67826c429e75f.png','active','2025-06-08 01:16:17','2025-06-08 01:16:17'),(5,'Desserts',NULL,2,'CAT-005','Sweet treats including ice creams, cakes, and pastries.','images/category/67826c0795588.png','active','2025-06-08 01:16:17','2025-06-08 01:16:17'),(10,'Chicken Wings',NULL,1,'CAT-010','Taste just right, taste like your on wings.','images/category/6860ef27a19a4.png','active','2025-06-29 02:40:32','2025-06-29 02:45:43'),(11,'For Sharing',NULL,1,'CAT-011','Share your stories with this group meals.','images/category/6860f29f2bcb4.png','active','2025-06-29 03:00:31','2025-06-29 03:00:31'),(12,'Pasta',NULL,1,'CAT-012','Traditional Filipino Taste Pasta','images/category/68610b45349c3.png','active','2025-06-29 04:45:41','2025-06-29 04:45:41'),(13,'Salads',NULL,1,'CAT-013','Local Produce Fresh salads.','images/category/68610faf291d6.png','active','2025-06-29 05:04:31','2025-06-29 05:04:31'),(14,'Dessert',NULL,1,'CAT-014','Sweet tooth will enjoy','images/category/68611b64b3896.png','active','2025-06-29 05:53:18','2025-06-29 05:54:28'),(15,'Biryani',NULL,1,'CAT-015','Authentic Middle East delicacies','images/category/6861b32335c1b.png','active','2025-06-29 16:41:53','2025-06-29 16:41:55'),(16,'Rice Meals',NULL,1,'CAT-016','Rice meals big deals','images/category/6863ec637cc36.png','active','2025-07-01 09:10:42','2025-07-01 09:10:43'),(17,'Snacks',NULL,1,'CAT-017','Snacks for everyone.','images/category/6863eda37cfdd.png','active','2025-07-01 09:13:13','2025-07-01 09:16:06'),(19,'Drinks',NULL,1,'CAT-019','Match and magic drinks for you.','images/category/6863f0b143a23.png','active','2025-07-01 09:29:04','2025-07-01 09:29:05'),(20,'Coffee',NULL,1,'CAT-020','Fresh aroma from local produce coffee','images/category/6869340b8fd87.png','active','2025-07-05 09:17:46','2025-07-05 09:22:28');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `countries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dial_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `countries_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=243 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `countries`
--

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;
INSERT INTO `countries` VALUES (1,'Afghanistan','+93','AF','2025-06-08 01:16:18','2025-06-08 01:16:18'),(2,'Aland Islands','+358','AX','2025-06-08 01:16:18','2025-06-08 01:16:18'),(3,'Albania','+355','AL','2025-06-08 01:16:18','2025-06-08 01:16:18'),(4,'Algeria','+213','DZ','2025-06-08 01:16:18','2025-06-08 01:16:18'),(5,'AmericanSamoa','+1684','AS','2025-06-08 01:16:18','2025-06-08 01:16:18'),(6,'Andorra','+376','AD','2025-06-08 01:16:18','2025-06-08 01:16:18'),(7,'Angola','+244','AO','2025-06-08 01:16:18','2025-06-08 01:16:18'),(8,'Anguilla','+1264','AI','2025-06-08 01:16:18','2025-06-08 01:16:18'),(9,'Antarctica','+672','AQ','2025-06-08 01:16:18','2025-06-08 01:16:18'),(10,'Antigua and Barbuda','+1268','AG','2025-06-08 01:16:18','2025-06-08 01:16:18'),(11,'Argentina','+54','AR','2025-06-08 01:16:18','2025-06-08 01:16:18'),(12,'Armenia','+374','AM','2025-06-08 01:16:18','2025-06-08 01:16:18'),(13,'Aruba','+297','AW','2025-06-08 01:16:18','2025-06-08 01:16:18'),(14,'Australia','+61','AU','2025-06-08 01:16:18','2025-06-08 01:16:18'),(15,'Austria','+43','AT','2025-06-08 01:16:18','2025-06-08 01:16:18'),(16,'Azerbaijan','+994','AZ','2025-06-08 01:16:18','2025-06-08 01:16:18'),(17,'Bahamas','+1242','BS','2025-06-08 01:16:18','2025-06-08 01:16:18'),(18,'Bahrain','+973','BH','2025-06-08 01:16:18','2025-06-08 01:16:18'),(19,'Bangladesh','+880','BD','2025-06-08 01:16:18','2025-06-08 01:16:18'),(20,'Barbados','+1246','BB','2025-06-08 01:16:18','2025-06-08 01:16:18'),(21,'Belarus','+375','BY','2025-06-08 01:16:18','2025-06-08 01:16:18'),(22,'Belgium','+32','BE','2025-06-08 01:16:18','2025-06-08 01:16:18'),(23,'Belize','+501','BZ','2025-06-08 01:16:18','2025-06-08 01:16:18'),(24,'Benin','+229','BJ','2025-06-08 01:16:18','2025-06-08 01:16:18'),(25,'Bermuda','+1441','BM','2025-06-08 01:16:18','2025-06-08 01:16:18'),(26,'Bhutan','+975','BT','2025-06-08 01:16:18','2025-06-08 01:16:18'),(27,'Bolivia, Plurinational State of','+591','BO','2025-06-08 01:16:18','2025-06-08 01:16:18'),(28,'Bosnia and Herzegovina','+387','BA','2025-06-08 01:16:18','2025-06-08 01:16:18'),(29,'Botswana','+267','BW','2025-06-08 01:16:18','2025-06-08 01:16:18'),(30,'Brazil','+55','BR','2025-06-08 01:16:18','2025-06-08 01:16:18'),(31,'British Indian Ocean Territory','+246','IO','2025-06-08 01:16:18','2025-06-08 01:16:18'),(32,'Brunei Darussalam','+673','BN','2025-06-08 01:16:18','2025-06-08 01:16:18'),(33,'Bulgaria','+359','BG','2025-06-08 01:16:18','2025-06-08 01:16:18'),(34,'Burkina Faso','+226','BF','2025-06-08 01:16:18','2025-06-08 01:16:18'),(35,'Burundi','+257','BI','2025-06-08 01:16:18','2025-06-08 01:16:18'),(36,'Cambodia','+855','KH','2025-06-08 01:16:18','2025-06-08 01:16:18'),(37,'Cameroon','+237','CM','2025-06-08 01:16:18','2025-06-08 01:16:18'),(38,'Canada','+1','CA','2025-06-08 01:16:18','2025-06-08 01:16:18'),(39,'Cape Verde','+238','CV','2025-06-08 01:16:18','2025-06-08 01:16:18'),(40,'Cayman Islands','+ 345','KY','2025-06-08 01:16:18','2025-06-08 01:16:18'),(41,'Central African Republic','+236','CF','2025-06-08 01:16:18','2025-06-08 01:16:18'),(42,'Chad','+235','TD','2025-06-08 01:16:18','2025-06-08 01:16:18'),(43,'Chile','+56','CL','2025-06-08 01:16:18','2025-06-08 01:16:18'),(44,'China','+86','CN','2025-06-08 01:16:18','2025-06-08 01:16:18'),(45,'Christmas Island','+61','CX','2025-06-08 01:16:18','2025-06-08 01:16:18'),(46,'Cocos (Keeling) Islands','+61','CC','2025-06-08 01:16:18','2025-06-08 01:16:18'),(47,'Colombia','+57','CO','2025-06-08 01:16:18','2025-06-08 01:16:18'),(48,'Comoros','+269','KM','2025-06-08 01:16:18','2025-06-08 01:16:18'),(49,'Congo','+242','CG','2025-06-08 01:16:18','2025-06-08 01:16:18'),(50,'Congo, The Democratic Republic of the Congo','+243','CD','2025-06-08 01:16:18','2025-06-08 01:16:18'),(51,'Cook Islands','+682','CK','2025-06-08 01:16:18','2025-06-08 01:16:18'),(52,'Costa Rica','+506','CR','2025-06-08 01:16:18','2025-06-08 01:16:18'),(53,'Cote d\'Ivoire','+225','CI','2025-06-08 01:16:18','2025-06-08 01:16:18'),(54,'Croatia','+385','HR','2025-06-08 01:16:18','2025-06-08 01:16:18'),(55,'Cuba','+53','CU','2025-06-08 01:16:18','2025-06-08 01:16:18'),(56,'Cyprus','+357','CY','2025-06-08 01:16:18','2025-06-08 01:16:18'),(57,'Czech Republic','+420','CZ','2025-06-08 01:16:18','2025-06-08 01:16:18'),(58,'Denmark','+45','DK','2025-06-08 01:16:18','2025-06-08 01:16:18'),(59,'Djibouti','+253','DJ','2025-06-08 01:16:18','2025-06-08 01:16:18'),(60,'Dominica','+1767','DM','2025-06-08 01:16:18','2025-06-08 01:16:18'),(61,'Dominican Republic','+1849','DO','2025-06-08 01:16:18','2025-06-08 01:16:18'),(62,'Ecuador','+593','EC','2025-06-08 01:16:18','2025-06-08 01:16:18'),(63,'Egypt','+20','EG','2025-06-08 01:16:18','2025-06-08 01:16:18'),(64,'El Salvador','+503','SV','2025-06-08 01:16:18','2025-06-08 01:16:18'),(65,'Equatorial Guinea','+240','GQ','2025-06-08 01:16:18','2025-06-08 01:16:18'),(66,'Eritrea','+291','ER','2025-06-08 01:16:18','2025-06-08 01:16:18'),(67,'Estonia','+372','EE','2025-06-08 01:16:18','2025-06-08 01:16:18'),(68,'Ethiopia','+251','ET','2025-06-08 01:16:18','2025-06-08 01:16:18'),(69,'Falkland Islands (Malvinas)','+500','FK','2025-06-08 01:16:18','2025-06-08 01:16:18'),(70,'Faroe Islands','+298','FO','2025-06-08 01:16:18','2025-06-08 01:16:18'),(71,'Fiji','+679','FJ','2025-06-08 01:16:18','2025-06-08 01:16:18'),(72,'Finland','+358','FI','2025-06-08 01:16:18','2025-06-08 01:16:18'),(73,'France','+33','FR','2025-06-08 01:16:18','2025-06-08 01:16:18'),(74,'French Guiana','+594','GF','2025-06-08 01:16:18','2025-06-08 01:16:18'),(75,'French Polynesia','+689','PF','2025-06-08 01:16:18','2025-06-08 01:16:18'),(76,'Gabon','+241','GA','2025-06-08 01:16:18','2025-06-08 01:16:18'),(77,'Gambia','+220','GM','2025-06-08 01:16:18','2025-06-08 01:16:18'),(78,'Georgia','+995','GE','2025-06-08 01:16:18','2025-06-08 01:16:18'),(79,'Germany','+49','DE','2025-06-08 01:16:18','2025-06-08 01:16:18'),(80,'Ghana','+233','GH','2025-06-08 01:16:18','2025-06-08 01:16:18'),(81,'Gibraltar','+350','GI','2025-06-08 01:16:18','2025-06-08 01:16:18'),(82,'Greece','+30','GR','2025-06-08 01:16:18','2025-06-08 01:16:18'),(83,'Greenland','+299','GL','2025-06-08 01:16:18','2025-06-08 01:16:18'),(84,'Grenada','+1473','GD','2025-06-08 01:16:18','2025-06-08 01:16:18'),(85,'Guadeloupe','+590','GP','2025-06-08 01:16:18','2025-06-08 01:16:18'),(86,'Guam','+1671','GU','2025-06-08 01:16:18','2025-06-08 01:16:18'),(87,'Guatemala','+502','GT','2025-06-08 01:16:18','2025-06-08 01:16:18'),(88,'Guernsey','+44','GG','2025-06-08 01:16:18','2025-06-08 01:16:18'),(89,'Guinea','+224','GN','2025-06-08 01:16:18','2025-06-08 01:16:18'),(90,'Guinea-Bissau','+245','GW','2025-06-08 01:16:18','2025-06-08 01:16:18'),(91,'Guyana','+595','GY','2025-06-08 01:16:18','2025-06-08 01:16:18'),(92,'Haiti','+509','HT','2025-06-08 01:16:18','2025-06-08 01:16:18'),(93,'Holy See (Vatican City State)','+379','VA','2025-06-08 01:16:18','2025-06-08 01:16:18'),(94,'Honduras','+504','HN','2025-06-08 01:16:18','2025-06-08 01:16:18'),(95,'Hong Kong','+852','HK','2025-06-08 01:16:18','2025-06-08 01:16:18'),(96,'Hungary','+36','HU','2025-06-08 01:16:18','2025-06-08 01:16:18'),(97,'Iceland','+354','IS','2025-06-08 01:16:18','2025-06-08 01:16:18'),(98,'India','+91','IN','2025-06-08 01:16:18','2025-06-08 01:16:18'),(99,'Indonesia','+62','ID','2025-06-08 01:16:18','2025-06-08 01:16:18'),(100,'Iran, Islamic Republic of Persian Gulf','+98','IR','2025-06-08 01:16:18','2025-06-08 01:16:18'),(101,'Iraq','+964','IQ','2025-06-08 01:16:18','2025-06-08 01:16:18'),(102,'Ireland','+353','IE','2025-06-08 01:16:18','2025-06-08 01:16:18'),(103,'Isle of Man','+44','IM','2025-06-08 01:16:18','2025-06-08 01:16:18'),(104,'Israel','+972','IL','2025-06-08 01:16:18','2025-06-08 01:16:18'),(105,'Italy','+39','IT','2025-06-08 01:16:18','2025-06-08 01:16:18'),(106,'Jamaica','+1876','JM','2025-06-08 01:16:18','2025-06-08 01:16:18'),(107,'Japan','+81','JP','2025-06-08 01:16:18','2025-06-08 01:16:18'),(108,'Jersey','+44','JE','2025-06-08 01:16:18','2025-06-08 01:16:18'),(109,'Jordan','+962','JO','2025-06-08 01:16:18','2025-06-08 01:16:18'),(110,'Kazakhstan','+77','KZ','2025-06-08 01:16:18','2025-06-08 01:16:18'),(111,'Kenya','+254','KE','2025-06-08 01:16:18','2025-06-08 01:16:18'),(112,'Kiribati','+686','KI','2025-06-08 01:16:18','2025-06-08 01:16:18'),(113,'Korea, Democratic People\'s Republic of Korea','+850','KP','2025-06-08 01:16:18','2025-06-08 01:16:18'),(114,'Korea, Republic of South Korea','+82','KR','2025-06-08 01:16:18','2025-06-08 01:16:18'),(115,'Kuwait','+965','KW','2025-06-08 01:16:18','2025-06-08 01:16:18'),(116,'Kyrgyzstan','+996','KG','2025-06-08 01:16:18','2025-06-08 01:16:18'),(117,'Laos','+856','LA','2025-06-08 01:16:18','2025-06-08 01:16:18'),(118,'Latvia','+371','LV','2025-06-08 01:16:18','2025-06-08 01:16:18'),(119,'Lebanon','+961','LB','2025-06-08 01:16:18','2025-06-08 01:16:18'),(120,'Lesotho','+266','LS','2025-06-08 01:16:18','2025-06-08 01:16:18'),(121,'Liberia','+231','LR','2025-06-08 01:16:18','2025-06-08 01:16:18'),(122,'Libyan Arab Jamahiriya','+218','LY','2025-06-08 01:16:18','2025-06-08 01:16:18'),(123,'Liechtenstein','+423','LI','2025-06-08 01:16:18','2025-06-08 01:16:18'),(124,'Lithuania','+370','LT','2025-06-08 01:16:18','2025-06-08 01:16:18'),(125,'Luxembourg','+352','LU','2025-06-08 01:16:18','2025-06-08 01:16:18'),(126,'Macao','+853','MO','2025-06-08 01:16:18','2025-06-08 01:16:18'),(127,'Macedonia','+389','MK','2025-06-08 01:16:18','2025-06-08 01:16:18'),(128,'Madagascar','+261','MG','2025-06-08 01:16:18','2025-06-08 01:16:18'),(129,'Malawi','+265','MW','2025-06-08 01:16:18','2025-06-08 01:16:18'),(130,'Malaysia','+60','MY','2025-06-08 01:16:19','2025-06-08 01:16:19'),(131,'Maldives','+960','MV','2025-06-08 01:16:19','2025-06-08 01:16:19'),(132,'Mali','+223','ML','2025-06-08 01:16:19','2025-06-08 01:16:19'),(133,'Malta','+356','MT','2025-06-08 01:16:19','2025-06-08 01:16:19'),(134,'Marshall Islands','+692','MH','2025-06-08 01:16:19','2025-06-08 01:16:19'),(135,'Martinique','+596','MQ','2025-06-08 01:16:19','2025-06-08 01:16:19'),(136,'Mauritania','+222','MR','2025-06-08 01:16:19','2025-06-08 01:16:19'),(137,'Mauritius','+230','MU','2025-06-08 01:16:19','2025-06-08 01:16:19'),(138,'Mayotte','+262','YT','2025-06-08 01:16:19','2025-06-08 01:16:19'),(139,'Mexico','+52','MX','2025-06-08 01:16:19','2025-06-08 01:16:19'),(140,'Micronesia, Federated States of Micronesia','+691','FM','2025-06-08 01:16:19','2025-06-08 01:16:19'),(141,'Moldova','+373','MD','2025-06-08 01:16:19','2025-06-08 01:16:19'),(142,'Monaco','+377','MC','2025-06-08 01:16:19','2025-06-08 01:16:19'),(143,'Mongolia','+976','MN','2025-06-08 01:16:19','2025-06-08 01:16:19'),(144,'Montenegro','+382','ME','2025-06-08 01:16:19','2025-06-08 01:16:19'),(145,'Montserrat','+1664','MS','2025-06-08 01:16:19','2025-06-08 01:16:19'),(146,'Morocco','+212','MA','2025-06-08 01:16:19','2025-06-08 01:16:19'),(147,'Mozambique','+258','MZ','2025-06-08 01:16:19','2025-06-08 01:16:19'),(148,'Myanmar','+95','MM','2025-06-08 01:16:19','2025-06-08 01:16:19'),(149,'Namibia','+264','NA','2025-06-08 01:16:19','2025-06-08 01:16:19'),(150,'Nauru','+674','NR','2025-06-08 01:16:19','2025-06-08 01:16:19'),(151,'Nepal','+977','NP','2025-06-08 01:16:19','2025-06-08 01:16:19'),(152,'Netherlands','+31','NL','2025-06-08 01:16:19','2025-06-08 01:16:19'),(153,'Netherlands Antilles','+599','AN','2025-06-08 01:16:19','2025-06-08 01:16:19'),(154,'New Caledonia','+687','NC','2025-06-08 01:16:19','2025-06-08 01:16:19'),(155,'New Zealand','+64','NZ','2025-06-08 01:16:19','2025-06-08 01:16:19'),(156,'Nicaragua','+505','NI','2025-06-08 01:16:19','2025-06-08 01:16:19'),(157,'Niger','+227','NE','2025-06-08 01:16:19','2025-06-08 01:16:19'),(158,'Nigeria','+234','NG','2025-06-08 01:16:19','2025-06-08 01:16:19'),(159,'Niue','+683','NU','2025-06-08 01:16:19','2025-06-08 01:16:19'),(160,'Norfolk Island','+672','NF','2025-06-08 01:16:19','2025-06-08 01:16:19'),(161,'Northern Mariana Islands','+1670','MP','2025-06-08 01:16:19','2025-06-08 01:16:19'),(162,'Norway','+47','NO','2025-06-08 01:16:19','2025-06-08 01:16:19'),(163,'Oman','+968','OM','2025-06-08 01:16:19','2025-06-08 01:16:19'),(164,'Pakistan','+92','PK','2025-06-08 01:16:19','2025-06-08 01:16:19'),(165,'Palau','+680','PW','2025-06-08 01:16:19','2025-06-08 01:16:19'),(166,'Palestinian Territory, Occupied','+970','PS','2025-06-08 01:16:19','2025-06-08 01:16:19'),(167,'Panama','+507','PA','2025-06-08 01:16:19','2025-06-08 01:16:19'),(168,'Papua New Guinea','+675','PG','2025-06-08 01:16:19','2025-06-08 01:16:19'),(169,'Paraguay','+595','PY','2025-06-08 01:16:19','2025-06-08 01:16:19'),(170,'Peru','+51','PE','2025-06-08 01:16:19','2025-06-08 01:16:19'),(171,'Philippines','+63','PH','2025-06-08 01:16:19','2025-06-08 01:16:19'),(172,'Pitcairn','+872','PN','2025-06-08 01:16:19','2025-06-08 01:16:19'),(173,'Poland','+48','PL','2025-06-08 01:16:19','2025-06-08 01:16:19'),(174,'Portugal','+351','PT','2025-06-08 01:16:19','2025-06-08 01:16:19'),(175,'Puerto Rico','+1939','PR','2025-06-08 01:16:19','2025-06-08 01:16:19'),(176,'Qatar','+974','QA','2025-06-08 01:16:19','2025-06-08 01:16:19'),(177,'Romania','+40','RO','2025-06-08 01:16:19','2025-06-08 01:16:19'),(178,'Russia','+7','RU','2025-06-08 01:16:19','2025-06-08 01:16:19'),(179,'Rwanda','+250','RW','2025-06-08 01:16:19','2025-06-08 01:16:19'),(180,'Reunion','+262','RE','2025-06-08 01:16:19','2025-06-08 01:16:19'),(181,'Saint Barthelemy','+590','BL','2025-06-08 01:16:19','2025-06-08 01:16:19'),(182,'Saint Helena, Ascension and Tristan Da Cunha','+290','SH','2025-06-08 01:16:19','2025-06-08 01:16:19'),(183,'Saint Kitts and Nevis','+1869','KN','2025-06-08 01:16:19','2025-06-08 01:16:19'),(184,'Saint Lucia','+1758','LC','2025-06-08 01:16:19','2025-06-08 01:16:19'),(185,'Saint Martin','+590','MF','2025-06-08 01:16:19','2025-06-08 01:16:19'),(186,'Saint Pierre and Miquelon','+508','PM','2025-06-08 01:16:19','2025-06-08 01:16:19'),(187,'Saint Vincent and the Grenadines','+1784','VC','2025-06-08 01:16:19','2025-06-08 01:16:19'),(188,'Samoa','+685','WS','2025-06-08 01:16:19','2025-06-08 01:16:19'),(189,'San Marino','+378','SM','2025-06-08 01:16:19','2025-06-08 01:16:19'),(190,'Sao Tome and Principe','+239','ST','2025-06-08 01:16:19','2025-06-08 01:16:19'),(191,'Saudi Arabia','+966','SA','2025-06-08 01:16:19','2025-06-08 01:16:19'),(192,'Senegal','+221','SN','2025-06-08 01:16:19','2025-06-08 01:16:19'),(193,'Serbia','+381','RS','2025-06-08 01:16:19','2025-06-08 01:16:19'),(194,'Seychelles','+248','SC','2025-06-08 01:16:19','2025-06-08 01:16:19'),(195,'Sierra Leone','+232','SL','2025-06-08 01:16:19','2025-06-08 01:16:19'),(196,'Singapore','+65','SG','2025-06-08 01:16:19','2025-06-08 01:16:19'),(197,'Slovakia','+421','SK','2025-06-08 01:16:19','2025-06-08 01:16:19'),(198,'Slovenia','+386','SI','2025-06-08 01:16:19','2025-06-08 01:16:19'),(199,'Solomon Islands','+677','SB','2025-06-08 01:16:19','2025-06-08 01:16:19'),(200,'Somalia','+252','SO','2025-06-08 01:16:19','2025-06-08 01:16:19'),(201,'South Africa','+27','ZA','2025-06-08 01:16:19','2025-06-08 01:16:19'),(202,'South Sudan','+211','SS','2025-06-08 01:16:19','2025-06-08 01:16:19'),(203,'South Georgia and the South Sandwich Islands','+500','GS','2025-06-08 01:16:19','2025-06-08 01:16:19'),(204,'Spain','+34','ES','2025-06-08 01:16:19','2025-06-08 01:16:19'),(205,'Sri Lanka','+94','LK','2025-06-08 01:16:19','2025-06-08 01:16:19'),(206,'Sudan','+249','SD','2025-06-08 01:16:19','2025-06-08 01:16:19'),(207,'Suriname','+597','SR','2025-06-08 01:16:19','2025-06-08 01:16:19'),(208,'Svalbard and Jan Mayen','+47','SJ','2025-06-08 01:16:19','2025-06-08 01:16:19'),(209,'Swaziland','+268','SZ','2025-06-08 01:16:19','2025-06-08 01:16:19'),(210,'Sweden','+46','SE','2025-06-08 01:16:19','2025-06-08 01:16:19'),(211,'Switzerland','+41','CH','2025-06-08 01:16:19','2025-06-08 01:16:19'),(212,'Syrian Arab Republic','+963','SY','2025-06-08 01:16:19','2025-06-08 01:16:19'),(213,'Taiwan','+886','TW','2025-06-08 01:16:19','2025-06-08 01:16:19'),(214,'Tajikistan','+992','TJ','2025-06-08 01:16:19','2025-06-08 01:16:19'),(215,'Tanzania, United Republic of Tanzania','+255','TZ','2025-06-08 01:16:19','2025-06-08 01:16:19'),(216,'Thailand','+66','TH','2025-06-08 01:16:19','2025-06-08 01:16:19'),(217,'Timor-Leste','+670','TL','2025-06-08 01:16:19','2025-06-08 01:16:19'),(218,'Togo','+228','TG','2025-06-08 01:16:19','2025-06-08 01:16:19'),(219,'Tokelau','+690','TK','2025-06-08 01:16:19','2025-06-08 01:16:19'),(220,'Tonga','+676','TO','2025-06-08 01:16:19','2025-06-08 01:16:19'),(221,'Trinidad and Tobago','+1868','TT','2025-06-08 01:16:19','2025-06-08 01:16:19'),(222,'Tunisia','+216','TN','2025-06-08 01:16:19','2025-06-08 01:16:19'),(223,'Turkey','+90','TR','2025-06-08 01:16:19','2025-06-08 01:16:19'),(224,'Turkmenistan','+993','TM','2025-06-08 01:16:19','2025-06-08 01:16:19'),(225,'Turks and Caicos Islands','+1649','TC','2025-06-08 01:16:19','2025-06-08 01:16:19'),(226,'Tuvalu','+688','TV','2025-06-08 01:16:19','2025-06-08 01:16:19'),(227,'Uganda','+256','UG','2025-06-08 01:16:19','2025-06-08 01:16:19'),(228,'Ukraine','+380','UA','2025-06-08 01:16:19','2025-06-08 01:16:19'),(229,'United Arab Emirates','+971','AE','2025-06-08 01:16:19','2025-06-08 01:16:19'),(230,'United Kingdom','+44','GB','2025-06-08 01:16:19','2025-06-08 01:16:19'),(231,'United States','+1','US','2025-06-08 01:16:19','2025-06-08 01:16:19'),(232,'Uruguay','+598','UY','2025-06-08 01:16:19','2025-06-08 01:16:19'),(233,'Uzbekistan','+998','UZ','2025-06-08 01:16:19','2025-06-08 01:16:19'),(234,'Vanuatu','+678','VU','2025-06-08 01:16:19','2025-06-08 01:16:19'),(235,'Venezuela, Bolivarian Republic of Venezuela','+58','VE','2025-06-08 01:16:19','2025-06-08 01:16:19'),(236,'Vietnam','+84','VN','2025-06-08 01:16:19','2025-06-08 01:16:19'),(237,'Virgin Islands, British','+1284','VG','2025-06-08 01:16:19','2025-06-08 01:16:19'),(238,'Virgin Islands, U.S.','+1340','VI','2025-06-08 01:16:19','2025-06-08 01:16:19'),(239,'Wallis and Futuna','+681','WF','2025-06-08 01:16:19','2025-06-08 01:16:19'),(240,'Yemen','+967','YE','2025-06-08 01:16:19','2025-06-08 01:16:19'),(241,'Zambia','+260','ZM','2025-06-08 01:16:19','2025-06-08 01:16:19'),(242,'Zimbabwe','+263','ZW','2025-06-08 01:16:19','2025-06-08 01:16:19');
/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coupons` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `discount_type` enum('percentage','fixed') COLLATE utf8mb4_unicode_ci NOT NULL,
  `usage_limit` int DEFAULT NULL,
  `used_count` int NOT NULL DEFAULT '0',
  `expires_at` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupons`
--

LOCK TABLES `coupons` WRITE;
/*!40000 ALTER TABLE `coupons` DISABLE KEYS */;
INSERT INTO `coupons` VALUES (1,'WELCOME10',10.00,'percentage',100,0,'2025-12-31 23:59:59',1,'2025-06-08 01:16:20','2025-06-08 01:16:20'),(2,'SUMMER20',20.00,'percentage',50,0,'2025-08-31 23:59:59',1,'2025-06-08 01:16:20','2025-06-08 01:16:20'),(3,'FALL30',30.00,'percentage',30,0,'2025-10-31 23:59:59',1,'2025-06-08 01:16:20','2025-06-08 01:16:20'),(4,'FLASH14',14.00,'fixed',20,0,'2025-06-30 23:59:59',1,'2025-06-08 01:16:20','2025-06-08 01:16:20'),(5,'LUCKY17',17.00,'fixed',10,0,'2025-07-31 23:59:59',1,'2025-06-08 01:16:20','2025-06-08 01:16:20');
/*!40000 ALTER TABLE `coupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currencies`
--

DROP TABLE IF EXISTS `currencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `currencies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_code` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dial_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `flag` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currencies`
--

LOCK TABLES `currencies` WRITE;
/*!40000 ALTER TABLE `currencies` DISABLE KEYS */;
INSERT INTO `currencies` VALUES (1,'United Arab Emirates','AED','UAE Dirham','+971','??','د.إ','2025-06-08 01:16:19','2025-06-08 01:16:19'),(2,'Saudi Arabia','SAR','Saudi Riyal','+966','??','﷼','2025-06-08 01:16:19','2025-06-08 01:16:19'),(3,'Kuwait','KWD','Kuwaiti Dinar','+965','??','د.ك','2025-06-08 01:16:19','2025-06-08 01:16:19'),(4,'Qatar','QAR','Qatari Riyal','+974','??','﷼','2025-06-08 01:16:19','2025-06-08 01:16:19'),(5,'Bahrain','BHD','Bahraini Dinar','+973','??','.د.ب','2025-06-08 01:16:19','2025-06-08 01:16:19'),(6,'Oman','OMR','Omani Rial','+968','??','ر.ع.','2025-06-08 01:16:19','2025-06-08 01:16:19'),(7,'Jordan','JOD','Jordanian Dinar','+962','??','د.ا','2025-06-08 01:16:19','2025-06-08 01:16:19'),(8,'Lebanon','LBP','Lebanese Pound','+961','??','ل.ل','2025-06-08 01:16:19','2025-06-08 01:16:19'),(9,'Egypt','EGP','Egyptian Pound','+20','??','ج.م','2025-06-08 01:16:19','2025-06-08 01:16:19'),(10,'Turkey','TRY','Turkish Lira','+90','??','₺','2025-06-08 01:16:19','2025-06-08 01:16:19'),(11,'India','INR','Indian Rupee','+91','??','₹','2025-06-08 01:16:19','2025-06-08 01:16:19'),(12,'Pakistan','PKR','Pakistani Rupee','+92','??','₨','2025-06-08 01:16:19','2025-06-08 01:16:19'),(13,'Bangladesh','BDT','Bangladeshi Taka','+880','??','৳','2025-06-08 01:16:19','2025-06-08 01:16:19'),(14,'Sri Lanka','LKR','Sri Lankan Rupee','+94','??','රු','2025-06-08 01:16:19','2025-06-08 01:16:19'),(15,'Nepal','NPR','Nepalese Rupee','+977','??','रू','2025-06-08 01:16:19','2025-06-08 01:16:19'),(16,'China','CNY','Chinese Yuan','+86','??','¥','2025-06-08 01:16:19','2025-06-08 01:16:19'),(17,'Japan','JPY','Japanese Yen','+81','??','¥','2025-06-08 01:16:19','2025-06-08 01:16:19'),(18,'Singapore','SGD','Singapore Dollar','+65','??','$','2025-06-08 01:16:19','2025-06-08 01:16:19'),(19,'Malaysia','MYR','Malaysian Ringgit','+60','??','RM','2025-06-08 01:16:19','2025-06-08 01:16:19'),(20,'Indonesia','IDR','Indonesian Rupiah','+62','??','Rp','2025-06-08 01:16:19','2025-06-08 01:16:19'),(21,'Philippines','PHP','Philippine Peso','+63','??','₱','2025-06-08 01:16:19','2025-06-08 01:16:19'),(22,'Thailand','THB','Thai Baht','+66','??','฿','2025-06-08 01:16:19','2025-06-08 01:16:19'),(23,'United Kingdom','GBP','British Pound','+44','??','£','2025-06-08 01:16:19','2025-06-08 01:16:19'),(24,'Germany','EUR','Euro','+49','??','€','2025-06-08 01:16:19','2025-06-08 01:16:19'),(25,'France','EUR','Euro','+33','??','€','2025-06-08 01:16:19','2025-06-08 01:16:19'),(26,'Italy','EUR','Euro','+39','??','€','2025-06-08 01:16:20','2025-06-08 01:16:20'),(27,'Spain','EUR','Euro','+34','??','€','2025-06-08 01:16:20','2025-06-08 01:16:20'),(28,'Netherlands','EUR','Euro','+31','??','€','2025-06-08 01:16:20','2025-06-08 01:16:20'),(29,'Switzerland','CHF','Swiss Franc','+41','??','Fr.','2025-06-08 01:16:20','2025-06-08 01:16:20'),(30,'Sweden','SEK','Swedish Krona','+46','??','kr','2025-06-08 01:16:20','2025-06-08 01:16:20'),(31,'Norway','NOK','Norwegian Krone','+47','??','kr','2025-06-08 01:16:20','2025-06-08 01:16:20'),(32,'Denmark','DKK','Danish Krone','+45','??','kr.','2025-06-08 01:16:20','2025-06-08 01:16:20'),(33,'Russia','RUB','Russian Ruble','+7','??','₽','2025-06-08 01:16:20','2025-06-08 01:16:20'),(34,'United States','USD','US Dollar','+1','??','$','2025-06-08 01:16:20','2025-06-08 01:16:20'),(35,'Canada','CAD','Canadian Dollar','+1','??','$','2025-06-08 01:16:20','2025-06-08 01:16:20');
/*!40000 ALTER TABLE `currencies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `discounts`
--

DROP TABLE IF EXISTS `discounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `discounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `identifier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_id` bigint unsigned NOT NULL,
  `actual_price` decimal(10,2) NOT NULL,
  `discount_price` decimal(10,2) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `discounts`
--

LOCK TABLES `discounts` WRITE;
/*!40000 ALTER TABLE `discounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `discounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expense_categories`
--

DROP TABLE IF EXISTS `expense_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `expense_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `restaurant_id` bigint unsigned NOT NULL,
  `category_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `daily_estimate` decimal(10,2) DEFAULT NULL,
  `weekly_estimate` decimal(10,2) DEFAULT NULL,
  `monthly_estimate` decimal(10,2) DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expense_categories_restaurant_id_foreign` (`restaurant_id`),
  CONSTRAINT `expense_categories_restaurant_id_foreign` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expense_categories`
--

LOCK TABLES `expense_categories` WRITE;
/*!40000 ALTER TABLE `expense_categories` DISABLE KEYS */;
INSERT INTO `expense_categories` VALUES (1,1,'Rent',166.67,1166.69,5000.00,'Property rental costs','images/expense-category/expense.png','active','2025-06-08 01:16:20','2025-06-08 01:16:20'),(2,1,'Utilities',33.33,233.31,1000.00,'Electricity, water, and gas services','images/expense-category/expense.png','active','2025-06-08 01:16:20','2025-06-08 01:16:20'),(3,1,'Salaries',400.00,2800.00,12000.00,'Staff wages and benefits','images/expense-category/expense.png','active','2025-06-08 01:16:20','2025-06-08 01:16:20'),(4,1,'Food Supplies',266.67,1866.69,8000.00,'Raw ingredients and food items','images/expense-category/expense.png','active','2025-06-08 01:16:20','2025-06-08 01:16:20'),(5,1,'Marketing',16.67,116.69,500.00,'Advertising and promotions','images/expense-category/expense.png','active','2025-06-08 01:16:20','2025-06-08 01:16:20'),(6,1,'Maintenance',33.33,233.31,1000.00,'Equipment and facility repairs','images/expense-category/expense.png','active','2025-06-08 01:16:20','2025-06-08 01:16:20'),(7,1,'Insurance',8.33,58.31,250.00,'Business insurance policies','images/expense-category/expense.png','active','2025-06-08 01:16:20','2025-06-08 01:16:20'),(8,1,'Taxes',66.67,466.69,2000.00,'Government taxes and fees','images/expense-category/expense.png','active','2025-06-08 01:16:20','2025-06-08 01:16:20'),(9,1,'Office Supplies',6.67,46.69,200.00,'Stationery and office materials','images/expense-category/expense.png','active','2025-06-08 01:16:20','2025-06-08 01:16:20');
/*!40000 ALTER TABLE `expense_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `expenses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `expense_category_id` bigint unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` enum('recurring','one-time') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('paid','unpaid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `date` date NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expenses_expense_category_id_foreign` (`expense_category_id`),
  CONSTRAINT `expenses_expense_category_id_foreign` FOREIGN KEY (`expense_category_id`) REFERENCES `expense_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expenses`
--

LOCK TABLES `expenses` WRITE;
/*!40000 ALTER TABLE `expenses` DISABLE KEYS */;
/*!40000 ALTER TABLE `expenses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_date` date DEFAULT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoices_invoice_no_unique` (`invoice_no`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES (1,1,'INV-001','2025-04-24','cash',NULL,0.00,'received','Random invoice generated with NO: INV-001','2025-04-23 23:42:00',NULL),(2,2,'INV-002','2025-05-09','transfer',NULL,55.35,'received','Random invoice generated with NO: INV-002','2025-05-09 07:46:00',NULL),(3,3,'INV-003','2025-06-08','card',NULL,45.17,'received','Random invoice generated with NO: INV-003','2025-06-07 21:44:00',NULL),(4,4,'INV-004','2025-05-26','card',NULL,65.90,'received','Random invoice generated with NO: INV-004','2025-05-26 15:23:00',NULL),(5,5,'INV-005','2025-04-02','transfer',NULL,63.43,'received','Random invoice generated with NO: INV-005','2025-04-02 12:50:00',NULL),(6,6,'INV-006','2025-06-17','card',NULL,32.50,'received','Random invoice generated with NO: INV-006','2025-06-17 06:39:00',NULL),(7,7,'INV-007','2025-06-28','cash',NULL,11.84,'received','Random invoice generated with NO: INV-007','2025-06-27 22:47:00',NULL),(8,8,'INV-008','2025-06-23','card',NULL,55.66,'received','Random invoice generated with NO: INV-008','2025-06-23 12:28:00',NULL),(9,9,'INV-009','2025-06-23','transfer',NULL,17.17,'pending','Random invoice generated with NO: INV-009','2025-06-23 08:15:00',NULL),(10,10,'INV-010','2025-06-26','cash',NULL,36.74,'pending','Random invoice generated with NO: INV-010','2025-06-25 20:08:00',NULL);
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` text COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','sent','delivered','read','failed','archived') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `restaurant_id` bigint unsigned DEFAULT NULL,
  `reply_by_user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2024_11_20_111631_create_restaurants_table',1),(5,'2024_11_28_124511_create_categories_table',1),(6,'2024_11_28_141441_create_products_table',1),(7,'2024_11_28_141452_create_discounts_table',1),(8,'2024_11_28_141459_create_product_props_table',1),(9,'2024_11_29_141048_create_rtables_table',1),(10,'2024_12_02_180301_create_roles_table',1),(11,'2024_12_02_184958_create_oauth_auth_codes_table',1),(12,'2024_12_02_184959_create_oauth_access_tokens_table',1),(13,'2024_12_02_185000_create_oauth_refresh_tokens_table',1),(14,'2024_12_02_185001_create_oauth_clients_table',1),(15,'2024_12_02_185002_create_oauth_personal_access_clients_table',1),(16,'2024_12_09_090609_create_orders_table',1),(17,'2024_12_09_090634_create_order_products_table',1),(18,'2024_12_12_105711_create_payments_table',1),(19,'2024_12_12_114701_create_profiles_table',1),(20,'2024_12_12_114743_create_user_addresses_table',1),(21,'2024_12_13_104224_create_rtable_bookings_table',1),(22,'2024_12_17_130755_create_restaurant_timings_table',1),(23,'2024_12_19_124042_create_rtableBooking_rtables_table',1),(24,'2024_12_21_151732_create_rtable_booking_payments_table',1),(25,'2024_12_21_212421_create_messages_table',1),(26,'2024_12_31_114709_create_invoice_table',1),(27,'2025_01_08_133823_create_variations_table',1),(28,'2025_01_20_152855_create_restaurant_settings_table',1),(29,'2025_01_24_131835_create_notifications_table',1),(30,'2025_02_08_101613_create_replies_table',1),(31,'2025_02_11_140619_create_user_codes_table',1),(32,'2025_02_19_181659_add_columns_to_orders',1),(33,'2025_02_21_114136_add_dial_code_to_users_table',1),(34,'2025_02_26_221023_alter_status_column_in_rtable',1),(35,'2025_03_07_103629_create_countries_table',1),(36,'2025_03_08_085538_add_dial_code_and_phone_to_orders_table',1),(37,'2025_03_09_121254_create_coupons_table',1),(38,'2025_03_10_080113_add_coupon_fields_to_orders_table',1),(39,'2025_05_07_171705_create_branch_configs_table',1),(40,'2025_05_07_171747_create_currencies_table',1),(41,'2025_05_16_051934_create_expense_categories_table',1),(42,'2025_05_16_111621_create_expenses_table',1),(43,'2025_06_02_194844_create_permissions_table',2),(52,'2025_06_27_214824_create_restaurant_meta_table',3),(53,'2025_06_27_214825_create_restaurant_timings_table',3),(54,'2025_07_03_000420_create_branch_configs_table',3),(55,'2025_07_03_000544_add_missing_fields_to_restaurants_table',3),(56,'2025_07_09_001055_add_columns_to_branch_configs',3),(57,'2025_07_09_003931_add_tips_to_restaurants_table',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`),
  CONSTRAINT `notifications_chk_1` CHECK (json_valid(`data`))
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (1,'App\\Notifications\\NewOrderNotification','11','App\\Models\\User',1,'{\"title\":\"New Order ORD-20250608-MAM5JI\",\"message\":\"You have a new order\",\"order_id\":11}',NULL,'2025-06-08 01:23:44','2025-06-08 01:23:44'),(2,'App\\Notifications\\NewOrderNotification','1','App\\Models\\User',1,'{\"title\":\"New Order ORD-20250709-1PUIES\",\"message\":\"You have a new order\",\"order_id\":1}',NULL,'2025-07-09 02:49:08','2025-07-09 02:49:08'),(3,'App\\Notifications\\NewOrderNotification','2','App\\Models\\User',1,'{\"title\":\"New Order ORD-20250709-JL77PP\",\"message\":\"You have a new order\",\"order_id\":2}',NULL,'2025-07-09 02:50:23','2025-07-09 02:50:23'),(4,'App\\Notifications\\NewOrderNotification','3','App\\Models\\User',1,'{\"title\":\"New Order ORD-20250709-S6WOG1\",\"message\":\"You have a new order\",\"order_id\":3}',NULL,'2025-07-09 02:51:39','2025-07-09 02:51:39');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_access_tokens`
--

DROP TABLE IF EXISTS `oauth_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `client_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_access_tokens`
--

LOCK TABLES `oauth_access_tokens` WRITE;
/*!40000 ALTER TABLE `oauth_access_tokens` DISABLE KEYS */;
INSERT INTO `oauth_access_tokens` VALUES ('04ea67f77406ce1cd0a3f4d24048204f817cfa3cb55846ca387065ea29b7ea010cc02902de54e168',1,'9f1a8a3b-f42f-4e1e-949d-33e20dab1a68','AuthToken','[]',0,'2025-07-05 21:06:41','2025-07-05 21:06:41','2026-07-06 02:06:41'),('176109aa972f764443d2d86e5a67470a9b2f7e4d6279f5fe89a291c9e51e24c51c1c2678509c21e4',1,'9f1a8a3b-f42f-4e1e-949d-33e20dab1a68','AuthToken','[]',0,'2025-07-06 02:29:09','2025-07-06 02:29:09','2026-07-06 07:29:09'),('189e2710ef03dc2f23cfacf23b6af797e6acab159bc5ce66d9f168216850429e82f040c3f346cd72',1,'9f1a8a3b-f42f-4e1e-949d-33e20dab1a68','AuthToken','[]',0,'2025-06-25 14:42:41','2025-06-25 14:42:41','2026-06-25 19:42:41'),('273c56fb0759caa44d08099250b85c16f8d410734152f984d486459ad1513b4f21ccb73a858700eb',1,'9f1a8a3b-f42f-4e1e-949d-33e20dab1a68','AuthToken','[]',0,'2025-06-20 15:50:49','2025-06-20 15:50:49','2026-06-20 20:50:49'),('37d7771a79264dc9516b11c7339579a6dfecd70763da4d74b8080db96b887e23642cc024f340f4d0',1,'9f1a8a3b-f42f-4e1e-949d-33e20dab1a68','AuthToken','[]',0,'2025-06-19 15:03:48','2025-06-19 15:03:48','2026-06-19 20:03:48'),('4066415c23db87cf73b944a6fae13ac8c0a1ad1f07443e0d36f250d56eca1e1e797092bd3517e4a2',30,'9f1a8a3b-f42f-4e1e-949d-33e20dab1a68','auth_token','[]',0,'2025-07-09 02:51:20','2025-07-09 02:51:20','2026-07-09 07:51:20'),('4bd53d7f0d620d9d06f7784a8e9b5bf483b9de24fc564da2e76d73f4d0d6b95ee944ff1f1376c591',28,'9f1a8a3b-f42f-4e1e-949d-33e20dab1a68','AuthToken','[]',0,'2025-06-19 14:58:38','2025-06-19 14:58:38','2026-06-19 19:58:38'),('563a20c0de1d2bd84a5b222fcdb539da6501d5e5dd20301e23bdb86b027d7d8aa9e6505479ed3d6e',27,'9f1a8a3b-f42f-4e1e-949d-33e20dab1a68','auth_token','[]',0,'2025-06-08 01:23:41','2025-06-08 01:23:41','2026-06-08 06:23:41'),('7bbb473b4e92552c4c649c3c565b667683275017eb50e9fa59072e3f2c902723cd56b6e15c44e191',1,'9f1a8a3b-f42f-4e1e-949d-33e20dab1a68','AuthToken','[]',0,'2025-06-29 02:45:15','2025-06-29 02:45:15','2026-06-29 07:45:15'),('832f4a68ecc530f49904811bf0b6d47ef988a14926a4cf426ce26582e07a17a3bc11adbdc8e01ee5',30,'9f1a8a3b-f42f-4e1e-949d-33e20dab1a68','auth_token','[]',0,'2025-07-09 02:21:00','2025-07-09 02:21:00','2026-07-09 07:21:00'),('8416d2a094ec65332fb3c6469028a1c5aad4d8b241f51cb3d178b5ca78ec8ced92b979b71b184895',1,'9f1a8a3b-f42f-4e1e-949d-33e20dab1a68','AuthToken','[]',0,'2025-06-25 14:39:38','2025-06-25 14:39:38','2026-06-25 19:39:38'),('8e81f20e637e9f5a9766d917f34e81897c0854ece0954ef7a8f7cf76d0f7aae949768d818d89358d',1,'9f1a8a3b-f42f-4e1e-949d-33e20dab1a68','AuthToken','[]',0,'2025-07-01 00:29:28','2025-07-01 00:29:28','2026-07-01 05:29:28'),('911477b6052b21811129c0607934ef03607034ac5e16d30fd07bf04b8f8e8a14349fe999e62e222b',28,'9f1a8a3b-f42f-4e1e-949d-33e20dab1a68','AuthToken','[]',0,'2025-06-19 14:59:34','2025-06-19 14:59:34','2026-06-19 19:59:34'),('977768fee9be44154bf94756c2b6448decdff916e779229c715fd81c4b2d630a20f60fb37ff5780d',1,'9f1a8a3b-f42f-4e1e-949d-33e20dab1a68','AuthToken','[]',0,'2025-06-29 09:35:06','2025-06-29 09:35:06','2026-06-29 14:35:06'),('a11cfe9f8d94a9541b9c6c1b47eeef937bee3fdb4ca5fa8e9bd95db929daed6c3d55d6620df1b40f',1,'9f1a8a3b-f42f-4e1e-949d-33e20dab1a68','AuthToken','[]',0,'2025-06-08 01:38:03','2025-06-08 01:38:03','2026-06-08 06:38:03'),('abb6db66bc7e44cf2a12a671d73b3c7f555c780fdf73447b727ca18f89684a9cbc0a40565cdcc4a5',29,'9f1a8a3b-f42f-4e1e-949d-33e20dab1a68','AuthToken','[]',0,'2025-06-25 14:41:11','2025-06-25 14:41:11','2026-06-25 19:41:11'),('b49019062c1014e38556bbe1d51687b2e16ceb1296de0fbe4bfd985fe827a0a6f5fc0f6733b8e645',1,'9f1a8a3b-f42f-4e1e-949d-33e20dab1a68','AuthToken','[]',0,'2025-06-08 01:18:25','2025-06-08 01:18:25','2026-06-08 06:18:25'),('e0776a486a199da8edc16ae1adefa42e3825009147baca7ca6e1b08691a54ab3e492a9cf1005bc04',30,'9f1a8a3b-f42f-4e1e-949d-33e20dab1a68','auth_token','[]',0,'2025-07-09 02:50:20','2025-07-09 02:50:20','2026-07-09 07:50:20'),('e885bedb42ecf016c7c2b044af2f7e0ea4f8cadfdceace9e891c4e5ff45b35fd4c481d199eba11d8',1,'9f1a8a3b-f42f-4e1e-949d-33e20dab1a68','AuthToken','[]',0,'2025-06-29 02:54:29','2025-06-29 02:54:29','2026-06-29 07:54:29'),('f1ea7dedd2572725876cc53357f77036c5deb5938f0f742e37dc1b823d182e7f0ea5a9f2f41c8e5a',1,'9f1a8a3b-f42f-4e1e-949d-33e20dab1a68','AuthToken','[]',0,'2025-06-24 07:10:26','2025-06-24 07:10:27','2026-06-24 12:10:26');
/*!40000 ALTER TABLE `oauth_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_auth_codes`
--

DROP TABLE IF EXISTS `oauth_auth_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_auth_codes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `client_id` bigint unsigned NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_auth_codes_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_auth_codes`
--

LOCK TABLES `oauth_auth_codes` WRITE;
/*!40000 ALTER TABLE `oauth_auth_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_auth_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_clients`
--

DROP TABLE IF EXISTS `oauth_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_clients` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_clients`
--

LOCK TABLES `oauth_clients` WRITE;
/*!40000 ALTER TABLE `oauth_clients` DISABLE KEYS */;
INSERT INTO `oauth_clients` VALUES ('9f1a8a3b-f42f-4e1e-949d-33e20dab1a68',NULL,'local','UW6mZDneD2N2xlHu7ab0yOckY4n90GlBgiDthGD6',NULL,'http://localhost',1,0,0,'2025-06-08 01:16:11','2025-06-08 01:16:11');
/*!40000 ALTER TABLE `oauth_clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_personal_access_clients`
--

DROP TABLE IF EXISTS `oauth_personal_access_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_personal_access_clients`
--

LOCK TABLES `oauth_personal_access_clients` WRITE;
/*!40000 ALTER TABLE `oauth_personal_access_clients` DISABLE KEYS */;
INSERT INTO `oauth_personal_access_clients` VALUES (1,'9f1a8a3b-f42f-4e1e-949d-33e20dab1a68','2025-06-08 01:16:11','2025-06-08 01:16:11');
/*!40000 ALTER TABLE `oauth_personal_access_clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_refresh_tokens`
--

DROP TABLE IF EXISTS `oauth_refresh_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_refresh_tokens`
--

LOCK TABLES `oauth_refresh_tokens` WRITE;
/*!40000 ALTER TABLE `oauth_refresh_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_refresh_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_products`
--

DROP TABLE IF EXISTS `order_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `variation` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_products`
--

LOCK TABLES `order_products` WRITE;
/*!40000 ALTER TABLE `order_products` DISABLE KEYS */;
INSERT INTO `order_products` VALUES (6,'4','16','5',NULL,2.99,'Random order note','2025-05-26 15:23:00',NULL),(7,'4','19','4',NULL,4.49,'Random order note','2025-05-26 15:23:00',NULL),(8,'4','20','2',NULL,3.99,'Random order note','2025-05-26 15:23:00',NULL),(9,'4','25','5',NULL,5.99,'Random order note','2025-05-26 15:23:00',NULL),(14,'6','18','3',NULL,3.49,'Random order note','2025-06-17 06:39:00',NULL),(15,'6','24','5',NULL,3.75,'Random order note','2025-06-17 06:39:00',NULL),(16,'7','22','4',NULL,2.99,'Random order note','2025-06-27 22:47:00',NULL),(17,'8','16','2',NULL,2.99,'Random order note','2025-06-23 12:28:00',NULL),(18,'8','21','3',NULL,3.99,'Random order note','2025-06-23 12:28:00',NULL),(19,'8','23','4',NULL,4.49,'Random order note','2025-06-23 12:28:00',NULL),(20,'8','25','3',NULL,5.99,'Random order note','2025-06-23 12:28:00',NULL),(21,'9','16','6',NULL,2.99,'Random order note','2025-06-23 08:15:00',NULL),(31,'3','31','1','\"[[]]\"',210.00,NULL,'2025-07-09 02:51:39',NULL),(32,'3','32','1','\"[[]]\"',185.00,NULL,'2025-07-09 02:51:39',NULL);
/*!40000 ALTER TABLE `order_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `coupon_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_value` decimal(10,2) NOT NULL DEFAULT '0.00',
  `final_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `identifier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('dine-in','take-away','delivery','drive-thru','curbside-pickup','catering','reservation') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','confirmed','preparing','ready_for_pickup','out_for_delivery','delivered','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `customer_id` bigint unsigned DEFAULT NULL,
  `dial_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `table_no` bigint unsigned DEFAULT NULL,
  `is_paid` tinyint(1) NOT NULL DEFAULT '0',
  `total_price` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `order_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `delivery_address` text COLLATE utf8mb4_unicode_ci,
  `discount` decimal(10,2) DEFAULT NULL,
  `restaurant_id` bigint unsigned DEFAULT NULL,
  `tax_percentage` decimal(5,2) DEFAULT NULL,
  `tax_amount` decimal(10,2) DEFAULT NULL,
  `tips_amount` decimal(5,2) DEFAULT NULL,
  `tips` decimal(10,2) DEFAULT NULL,
  `delivery_charges` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (3,NULL,0.00,395.00,'ORD-003','ORD-20250709-S6WOG1','delivery','pending',NULL,30,'+92','3215553556','INV-686e1f8b9d6df',NULL,0,395.00,'applePay','takeaway',NULL,NULL,1,0.00,0.00,0.00,0.00,0.00,'2025-07-09 02:51:39','2025-07-09 02:51:39');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `customer_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_status` enum('pending','received','canceled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_mode` enum('none','cash','card','transfer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `payment_portal` enum('none','cash','stripe','paypal') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (1,1,0.00,'18','pending','transfer','none','2025-04-23 23:42:00',NULL),(2,2,55.35,'19','pending','transfer','none','2025-05-09 07:46:00',NULL),(3,3,45.17,'20','pending','transfer','none','2025-06-07 21:44:00',NULL),(4,4,65.90,'21','received','transfer','none','2025-05-26 15:23:00',NULL),(5,5,63.43,'22','pending','card','none','2025-04-02 12:50:00',NULL),(6,6,32.50,'23','received','cash','none','2025-06-17 06:39:00',NULL),(7,7,11.84,'24','pending','transfer','none','2025-06-27 22:47:00',NULL),(8,8,55.66,'25','received','card','none','2025-06-23 12:28:00',NULL),(9,9,17.17,'26','received','cash','none','2025-06-23 08:15:00',NULL),(10,10,36.74,'21','received','card','none','2025-06-25 20:08:00',NULL);
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint unsigned DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_role_id_slug_unique` (`role_id`,`slug`),
  CONSTRAINT `permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=418 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,1,'dashboard.view',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(2,1,'role.add',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(3,1,'role.view',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(4,1,'role.edit',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(5,1,'role.update',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(6,1,'role.delete',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(7,1,'role.list',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(8,1,'role.filter',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(9,1,'user.add',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(10,1,'user.view',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(11,1,'user.edit',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(12,1,'user.update',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(13,1,'user.delete',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(14,1,'user.list',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(15,1,'user.filter',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(16,1,'product.add',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(17,1,'product.view',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(18,1,'product.edit',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(19,1,'product.update',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(20,1,'product.delete',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(21,1,'product.list',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(22,1,'product.filter',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(23,1,'category.add',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(24,1,'category.view',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(25,1,'category.edit',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(26,1,'category.update',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(27,1,'category.delete',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(28,1,'category.list',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(29,1,'category.filter',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(30,1,'variation.add',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(31,1,'variation.view',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(32,1,'variation.edit',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(33,1,'variation.update',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(34,1,'variation.delete',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(35,1,'variation.list',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(36,1,'variation.filter',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(37,1,'table.add',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(38,1,'table.view',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(39,1,'table.edit',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(40,1,'table.update',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(41,1,'table.delete',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(42,1,'table.list',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(43,1,'table.filter',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(44,1,'table_booking.add',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(45,1,'table_booking.view',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(46,1,'table_booking.edit',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(47,1,'table_booking.update',2,'2025-06-19 14:51:58','2025-06-19 14:51:58'),(48,1,'table_booking.delete',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(49,1,'table_booking.list',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(50,1,'table_booking.filter',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(51,1,'expense_category.add',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(52,1,'expense_category.view',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(53,1,'expense_category.edit',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(54,1,'expense_category.update',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(55,1,'expense_category.delete',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(56,1,'expense_category.list',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(57,1,'expense_category.filter',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(58,1,'expense.add',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(59,1,'expense.view',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(60,1,'expense.edit',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(61,1,'expense.update',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(62,1,'expense.delete',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(63,1,'expense.list',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(64,1,'expense.filter',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(65,1,'expense.status',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(66,1,'expense.payment_status_update',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(67,1,'coupon.add',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(68,1,'coupon.view',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(69,1,'coupon.edit',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(70,1,'coupon.update',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(71,1,'coupon.delete',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(72,1,'coupon.list',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(73,1,'coupon.filter',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(74,1,'message.add',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(75,1,'message.view',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(76,1,'message.edit',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(77,1,'message.update',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(78,1,'message.delete',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(79,1,'message.list',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(80,1,'message.filter',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(81,1,'order.add',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(82,1,'order.view',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(83,1,'order.edit',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(84,1,'order.update',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(85,1,'order.delete',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(86,1,'order.list',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(87,1,'order.filter',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(88,1,'order.payment_status',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(89,1,'order.order_status',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(90,1,'order.menu',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(91,1,'branch.add',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(92,1,'branch.view',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(93,1,'branch.edit',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(94,1,'branch.update',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(95,1,'branch.delete',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(96,1,'branch.list',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(97,1,'branch.filter',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(98,1,'branch.set_default',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(99,1,'branch.config_button',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(100,2,'user.add',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(101,2,'user.view',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(102,2,'user.edit',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(103,2,'user.update',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(104,2,'user.delete',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(105,2,'user.list',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(106,2,'user.filter',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(107,2,'product.add',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(108,2,'product.view',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(109,2,'product.edit',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(110,2,'product.update',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(111,2,'product.delete',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(112,2,'product.list',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(113,2,'product.filter',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(114,2,'category.add',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(115,2,'category.view',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(116,2,'category.edit',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(117,2,'category.update',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(118,2,'category.delete',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(119,2,'category.list',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(120,2,'category.filter',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(121,2,'variation.add',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(122,2,'variation.view',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(123,2,'variation.edit',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(124,2,'variation.update',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(125,2,'variation.delete',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(126,2,'variation.list',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(127,2,'variation.filter',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(128,2,'table.add',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(129,2,'table.view',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(130,2,'table.edit',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(131,2,'table.update',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(132,2,'table.delete',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(133,2,'table.list',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(134,2,'table.filter',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(135,2,'table_booking.add',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(136,2,'table_booking.view',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(137,2,'table_booking.edit',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(138,2,'table_booking.update',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(139,2,'table_booking.delete',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(140,2,'table_booking.list',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(141,2,'table_booking.filter',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(142,2,'expense_category.add',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(143,2,'expense_category.view',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(144,2,'expense_category.edit',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(145,2,'expense_category.update',2,'2025-06-19 14:51:59','2025-06-19 14:51:59'),(146,2,'expense_category.delete',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(147,2,'expense_category.list',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(148,2,'expense_category.filter',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(149,2,'expense.add',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(150,2,'expense.view',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(151,2,'expense.edit',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(152,2,'expense.update',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(153,2,'expense.delete',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(154,2,'expense.list',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(155,2,'expense.filter',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(156,2,'expense.status',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(157,2,'expense.payment_status_update',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(158,2,'coupon.add',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(159,2,'coupon.view',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(160,2,'coupon.edit',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(161,2,'coupon.update',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(162,2,'coupon.delete',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(163,2,'coupon.list',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(164,2,'coupon.filter',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(165,2,'message.add',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(166,2,'message.view',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(167,2,'message.edit',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(168,2,'message.update',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(169,2,'message.delete',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(170,2,'message.list',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(171,2,'message.filter',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(172,2,'order.add',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(173,2,'order.view',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(174,2,'order.edit',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(175,2,'order.update',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(176,2,'order.delete',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(177,2,'order.list',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(178,2,'order.filter',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(179,2,'order.payment_status',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(180,2,'order.order_status',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(181,2,'order.menu',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(182,2,'branch.add',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(183,2,'branch.view',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(184,2,'branch.edit',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(185,2,'branch.update',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(186,2,'branch.delete',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(187,2,'branch.list',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(188,2,'branch.filter',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(189,2,'branch.set_default',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(190,2,'branch.config_button',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(191,3,'user.add',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(192,3,'user.view',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(193,3,'user.edit',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(194,3,'user.update',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(195,3,'user.delete',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(196,3,'user.list',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(197,3,'user.filter',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(198,3,'product.add',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(199,3,'product.view',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(200,3,'product.edit',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(201,3,'product.update',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(202,3,'product.delete',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(203,3,'product.list',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(204,3,'product.filter',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(205,3,'category.add',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(206,3,'category.view',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(207,3,'category.edit',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(208,3,'category.update',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(209,3,'category.delete',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(210,3,'category.list',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(211,3,'category.filter',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(212,3,'variation.add',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(213,3,'variation.view',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(214,3,'variation.edit',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(215,3,'variation.update',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(216,3,'variation.delete',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(217,3,'variation.list',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(218,3,'variation.filter',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(219,3,'table.add',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(220,3,'table.view',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(221,3,'table.edit',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(222,3,'table.update',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(223,3,'table.delete',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(224,3,'table.list',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(225,3,'table.filter',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(226,3,'table_booking.add',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(227,3,'table_booking.view',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(228,3,'table_booking.edit',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(229,3,'table_booking.update',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(230,3,'table_booking.delete',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(231,3,'table_booking.list',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(232,3,'table_booking.filter',2,'2025-06-19 14:52:00','2025-06-19 14:52:00'),(233,3,'expense_category.add',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(234,3,'expense_category.view',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(235,3,'expense_category.edit',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(236,3,'expense_category.update',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(237,3,'expense_category.delete',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(238,3,'expense_category.list',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(239,3,'expense_category.filter',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(240,3,'expense.add',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(241,3,'expense.view',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(242,3,'expense.edit',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(243,3,'expense.update',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(244,3,'expense.delete',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(245,3,'expense.list',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(246,3,'expense.filter',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(247,3,'expense.status',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(248,3,'expense.payment_status_update',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(249,3,'coupon.add',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(250,3,'coupon.view',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(251,3,'coupon.edit',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(252,3,'coupon.update',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(253,3,'coupon.delete',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(254,3,'coupon.list',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(255,3,'coupon.filter',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(256,3,'order.add',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(257,3,'order.view',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(258,3,'order.edit',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(259,3,'order.update',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(260,3,'order.delete',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(261,3,'order.list',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(262,3,'order.filter',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(263,3,'order.payment_status',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(264,3,'order.order_status',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(265,3,'order.menu',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(266,3,'branch.add',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(267,3,'branch.view',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(268,3,'branch.edit',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(269,3,'branch.update',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(270,3,'branch.delete',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(271,3,'branch.list',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(272,3,'branch.filter',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(273,3,'branch.set_default',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(274,3,'branch.config_button',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(275,4,'product.add',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(276,4,'product.view',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(277,4,'product.edit',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(278,4,'product.update',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(279,4,'product.delete',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(280,4,'product.list',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(281,4,'product.filter',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(282,4,'category.add',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(283,4,'category.view',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(284,4,'category.edit',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(285,4,'category.update',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(286,4,'category.delete',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(287,4,'category.list',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(288,4,'category.filter',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(289,4,'variation.add',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(290,4,'variation.view',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(291,4,'variation.edit',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(292,4,'variation.update',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(293,4,'variation.delete',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(294,4,'variation.list',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(295,4,'variation.filter',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(296,4,'order.add',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(297,4,'order.view',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(298,4,'order.edit',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(299,4,'order.update',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(300,4,'order.delete',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(301,4,'order.list',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(302,4,'order.filter',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(303,4,'order.payment_status',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(304,4,'order.order_status',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(305,4,'order.menu',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(306,5,'table.add',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(307,5,'table.view',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(308,5,'table.edit',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(309,5,'table.update',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(310,5,'table.delete',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(311,5,'table.list',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(312,5,'table.filter',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(313,5,'table_booking.add',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(314,5,'table_booking.view',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(315,5,'table_booking.edit',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(316,5,'table_booking.update',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(317,5,'table_booking.delete',2,'2025-06-19 14:52:01','2025-06-19 14:52:01'),(318,5,'table_booking.list',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(319,5,'table_booking.filter',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(320,5,'order.add',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(321,5,'order.view',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(322,5,'order.edit',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(323,5,'order.update',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(324,5,'order.delete',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(325,5,'order.list',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(326,5,'order.filter',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(327,5,'order.payment_status',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(328,5,'order.order_status',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(329,5,'order.menu',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(330,6,'expense.add',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(331,6,'expense.view',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(332,6,'expense.edit',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(333,6,'expense.update',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(334,6,'expense.delete',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(335,6,'expense.list',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(336,6,'expense.filter',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(337,6,'expense.status',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(338,6,'expense.payment_status_update',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(339,6,'coupon.add',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(340,6,'coupon.view',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(341,6,'coupon.edit',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(342,6,'coupon.update',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(343,6,'coupon.delete',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(344,6,'coupon.list',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(345,6,'coupon.filter',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(346,6,'order.add',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(347,6,'order.view',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(348,6,'order.edit',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(349,6,'order.update',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(350,6,'order.delete',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(351,6,'order.list',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(352,6,'order.filter',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(353,6,'order.payment_status',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(354,6,'order.order_status',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(355,6,'order.menu',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(356,7,'order.add',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(357,7,'order.view',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(358,7,'order.edit',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(359,7,'order.update',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(360,7,'order.delete',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(361,7,'order.list',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(362,7,'order.filter',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(363,7,'order.payment_status',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(364,7,'order.order_status',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(365,7,'order.menu',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(366,8,'table_booking.add',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(367,8,'table_booking.view',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(368,8,'table_booking.edit',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(369,8,'table_booking.update',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(370,8,'table_booking.delete',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(371,8,'table_booking.list',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(372,8,'table_booking.filter',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(373,8,'order.add',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(374,8,'order.view',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(375,8,'order.edit',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(376,8,'order.update',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(377,8,'order.delete',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(378,8,'order.list',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(379,8,'order.filter',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(380,8,'order.payment_status',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(381,8,'order.order_status',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(382,8,'order.menu',2,'2025-06-19 14:52:02','2025-06-19 14:52:02'),(383,11,'order.add',2,'2025-06-19 14:57:35','2025-06-19 14:57:35'),(384,11,'order.edit',2,'2025-06-19 14:57:35','2025-06-19 14:57:35'),(385,11,'order.delete',2,'2025-06-19 14:57:35','2025-06-19 14:57:35'),(386,11,'order.list',2,'2025-06-19 14:57:35','2025-06-19 14:57:35'),(387,11,'order.filter',2,'2025-06-19 14:57:35','2025-06-19 14:57:35'),(388,11,'order.payment_status',2,'2025-06-19 14:57:35','2025-06-19 14:57:35'),(389,11,'order.order_status',2,'2025-06-19 14:57:35','2025-06-19 14:57:35'),(390,11,'order.menu',2,'2025-06-19 14:57:35','2025-06-19 14:57:35'),(391,11,'product.add',2,'2025-06-19 14:57:35','2025-06-19 14:57:35'),(392,11,'product.edit',2,'2025-06-19 14:57:35','2025-06-19 14:57:35'),(393,11,'product.delete',2,'2025-06-19 14:57:35','2025-06-19 14:57:35'),(394,11,'product.list',2,'2025-06-19 14:57:35','2025-06-19 14:57:35'),(395,11,'product.filter',2,'2025-06-19 14:57:35','2025-06-19 14:57:35'),(396,11,'category.add',2,'2025-06-19 14:57:35','2025-06-19 14:57:35'),(397,11,'category.edit',2,'2025-06-19 14:57:35','2025-06-19 14:57:35'),(398,11,'category.delete',2,'2025-06-19 14:57:35','2025-06-19 14:57:35'),(399,11,'category.filter',2,'2025-06-19 14:57:35','2025-06-19 14:57:35'),(400,11,'category.list',2,'2025-06-19 14:57:35','2025-06-19 14:57:35'),(401,11,'variation.add',2,'2025-06-19 14:57:35','2025-06-19 14:57:35'),(402,11,'variation.edit',2,'2025-06-19 14:57:35','2025-06-19 14:57:35'),(403,11,'variation.delete',2,'2025-06-19 14:57:35','2025-06-19 14:57:35'),(404,11,'variation.list',2,'2025-06-19 14:57:35','2025-06-19 14:57:35'),(405,11,'variation.filter',2,'2025-06-19 14:57:35','2025-06-19 14:57:35'),(406,11,'table.add',2,'2025-06-19 14:57:35','2025-06-19 14:57:35'),(407,11,'table.edit',2,'2025-06-19 14:57:35','2025-06-19 14:57:35'),(408,11,'table.delete',2,'2025-06-19 14:57:35','2025-06-19 14:57:35'),(409,11,'table.list',2,'2025-06-19 14:57:35','2025-06-19 14:57:35'),(410,11,'table.filter',2,'2025-06-19 14:57:35','2025-06-19 14:57:35'),(411,11,'table_booking.add',2,'2025-06-19 14:57:35','2025-06-19 14:57:35'),(412,11,'table_booking.edit',2,'2025-06-19 14:57:35','2025-06-19 14:57:35'),(413,11,'table_booking.delete',2,'2025-06-19 14:57:35','2025-06-19 14:57:35'),(414,11,'table_booking.list',2,'2025-06-19 14:57:35','2025-06-19 14:57:35'),(415,11,'table_booking.filter',2,'2025-06-19 14:57:35','2025-06-19 14:57:35'),(416,11,'coupon.filter',2,'2025-06-19 14:57:35','2025-06-19 14:57:35'),(417,11,'coupon.list',2,'2025-06-19 14:57:35','2025-06-19 14:57:35');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_props`
--

DROP TABLE IF EXISTS `product_props`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_props` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` text COLLATE utf8mb4_unicode_ci,
  `meta_key_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=155 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_props`
--

LOCK TABLES `product_props` WRITE;
/*!40000 ALTER TABLE `product_props` DISABLE KEYS */;
INSERT INTO `product_props` VALUES (16,16,'variation','\"[{\\\"type\\\": \\\"seasoning\\\", \\\"selected\\\": false, \\\"options\\\": [{\\\"name\\\": \\\"extra salt\\\", \\\"description\\\": \\\"Add more salt\\\", \\\"price\\\": 2.0}]}]\"','array','2025-06-08 01:16:17','2025-06-08 01:16:17'),(17,17,'variation','\"[{\\\"type\\\": \\\"dipping sauce\\\", \\\"selected\\\": false, \\\"options\\\": [{\\\"name\\\": \\\"BBQ\\\", \\\"description\\\": \\\"Barbecue sauce\\\", \\\"price\\\": 2.5}]}]\"','array','2025-06-08 01:16:17','2025-06-08 01:16:17'),(18,18,'variation','\"[{\\\"type\\\": \\\"flavor\\\", \\\"selected\\\": false, \\\"options\\\": [{\\\"name\\\": \\\"spicy\\\", \\\"description\\\": \\\"Spicy nuggets\\\", \\\"price\\\": 3.0}]}]\"','array','2025-06-08 01:16:17','2025-06-08 01:16:17'),(19,19,'variation','\"[{\\\"type\\\": \\\"dip\\\", \\\"selected\\\": false, \\\"options\\\": [{\\\"name\\\": \\\"marinara\\\", \\\"description\\\": \\\"Marinara dip\\\", \\\"price\\\": 2.5}]}]\"','array','2025-06-08 01:16:17','2025-06-08 01:16:17'),(20,20,'variation','\"[{\\\"type\\\": \\\"seasoning\\\", \\\"selected\\\": false, \\\"options\\\": [{\\\"name\\\": \\\"spicy\\\", \\\"description\\\": \\\"Spicy popcorn chicken\\\", \\\"price\\\": 3.0}]}]\"','array','2025-06-08 01:16:17','2025-06-08 01:16:17'),(21,21,'variation','\"[{\\\"type\\\": \\\"topping\\\", \\\"selected\\\": false, \\\"options\\\": [{\\\"name\\\": \\\"extra chocolate\\\", \\\"description\\\": \\\"Add more chocolate\\\", \\\"price\\\": 2.5}]}]\"','array','2025-06-08 01:16:17','2025-06-08 01:16:17'),(22,22,'variation','\"[{\\\"type\\\": \\\"scoop\\\", \\\"selected\\\": false, \\\"options\\\": [{\\\"name\\\": \\\"extra scoop\\\", \\\"description\\\": \\\"Add an extra scoop\\\", \\\"price\\\": 3.0}]}]\"','array','2025-06-08 01:16:17','2025-06-08 01:16:17'),(23,23,'variation','\"[{\\\"type\\\": \\\"topping\\\", \\\"selected\\\": false, \\\"options\\\": [{\\\"name\\\": \\\"chocolate drizzle\\\", \\\"description\\\": \\\"Add chocolate drizzle\\\", \\\"price\\\": 2.5}]}]\"','array','2025-06-08 01:16:17','2025-06-08 01:16:17'),(24,24,'variation','\"[{\\\"type\\\": \\\"add-on\\\", \\\"selected\\\": false, \\\"options\\\": [{\\\"name\\\": \\\"extra cream\\\", \\\"description\\\": \\\"Add extra whipped cream\\\", \\\"price\\\": 2.5}]}]\"','array','2025-06-08 01:16:17','2025-06-08 01:16:17'),(25,25,'variation','\"[{\\\"type\\\": \\\"lava\\\", \\\"selected\\\": false, \\\"options\\\": [{\\\"name\\\": \\\"extra chocolate\\\", \\\"description\\\": \\\"Extra molten chocolate\\\", \\\"price\\\": 3.5}]}]\"','array','2025-06-08 01:16:17','2025-06-08 01:16:17'),(38,31,'variation','\"[]\"','array','2025-07-01 00:36:28','2025-07-01 00:36:28'),(39,32,'variation','\"[]\"','array','2025-07-01 00:38:23','2025-07-01 00:38:23'),(42,34,'variation','\"[]\"','array','2025-07-01 02:43:37','2025-07-01 02:43:37'),(44,33,'variation','\"[]\"','array','2025-07-01 02:46:41','2025-07-01 02:46:41'),(45,35,'variation','\"[]\"','array','2025-07-01 03:02:15','2025-07-01 03:02:15'),(47,36,'variation','\"[]\"','array','2025-07-01 03:12:26','2025-07-01 03:12:26'),(48,37,'variation','\"[]\"','array','2025-07-01 09:33:28','2025-07-01 09:33:28'),(51,39,'variation','\"[]\"','array','2025-07-01 09:40:23','2025-07-01 09:40:23'),(52,38,'variation','\"[]\"','array','2025-07-01 09:41:00','2025-07-01 09:41:00'),(53,40,'variation','\"[]\"','array','2025-07-01 09:42:59','2025-07-01 09:42:59'),(54,41,'variation','\"[]\"','array','2025-07-05 09:20:00','2025-07-05 09:20:00'),(55,42,'variation','\"[]\"','array','2025-07-05 09:21:47','2025-07-05 09:21:47'),(56,43,'variation','\"[]\"','array','2025-07-05 09:24:34','2025-07-05 09:24:34'),(57,44,'variation','\"[]\"','array','2025-07-05 22:26:38','2025-07-05 22:26:38'),(58,45,'variation','\"[]\"','array','2025-07-06 03:37:54','2025-07-06 03:37:54'),(59,46,'variation','\"[]\"','array','2025-07-06 03:39:27','2025-07-06 03:39:27'),(60,47,'variation','\"[]\"','array','2025-07-06 03:40:43','2025-07-06 03:40:43'),(61,48,'variation','\"[]\"','array','2025-07-06 03:42:03','2025-07-06 03:42:03'),(62,49,'variation','\"[]\"','array','2025-07-06 03:43:10','2025-07-06 03:43:10'),(63,50,'variation','\"[]\"','array','2025-07-06 03:45:00','2025-07-06 03:45:00'),(64,51,'variation','\"[]\"','array','2025-07-06 03:46:31','2025-07-06 03:46:31'),(65,52,'variation','\"[]\"','array','2025-07-06 03:48:24','2025-07-06 03:48:24'),(66,53,'variation','\"[]\"','array','2025-07-06 03:50:13','2025-07-06 03:50:13'),(67,54,'variation','\"[]\"','array','2025-07-06 03:52:43','2025-07-06 03:52:43'),(68,55,'variation','\"[]\"','array','2025-07-06 03:54:02','2025-07-06 03:54:02'),(69,56,'variation','\"[]\"','array','2025-07-06 03:55:41','2025-07-06 03:55:41'),(70,57,'variation','\"[]\"','array','2025-07-06 03:57:03','2025-07-06 03:57:03'),(71,58,'variation','\"[]\"','array','2025-07-06 04:03:35','2025-07-06 04:03:35'),(72,59,'variation','\"[]\"','array','2025-07-07 21:20:47','2025-07-07 21:20:47'),(73,60,'variation','\"[]\"','array','2025-07-07 21:22:24','2025-07-07 21:22:24'),(74,61,'variation','\"[]\"','array','2025-07-07 21:23:26','2025-07-07 21:23:26'),(75,62,'variation','\"[]\"','array','2025-07-07 21:24:16','2025-07-07 21:24:16'),(76,63,'variation','\"[]\"','array','2025-07-07 21:25:37','2025-07-07 21:25:37'),(77,64,'variation','\"[]\"','array','2025-07-07 21:26:32','2025-07-07 21:26:32'),(78,65,'variation','\"[]\"','array','2025-07-07 21:27:18','2025-07-07 21:27:18'),(79,66,'variation','\"[]\"','array','2025-07-07 21:28:26','2025-07-07 21:28:26'),(80,67,'variation','\"[]\"','array','2025-07-07 21:29:20','2025-07-07 21:29:20'),(81,68,'variation','\"[]\"','array','2025-07-07 21:30:22','2025-07-07 21:30:22'),(82,69,'variation','\"[]\"','array','2025-07-07 21:31:03','2025-07-07 21:31:03'),(83,70,'variation','\"[]\"','array','2025-07-07 21:32:22','2025-07-07 21:32:22'),(84,71,'variation','\"[]\"','array','2025-07-07 21:33:18','2025-07-07 21:33:18'),(87,74,'variation','\"[]\"','array','2025-07-07 21:41:46','2025-07-07 21:41:46'),(88,75,'variation','\"[]\"','array','2025-07-07 21:42:47','2025-07-07 21:42:47'),(89,76,'variation','\"[]\"','array','2025-07-07 21:43:31','2025-07-07 21:43:31'),(91,78,'variation','\"[]\"','array','2025-07-07 21:46:58','2025-07-07 21:46:58'),(92,79,'variation','\"[]\"','array','2025-07-07 21:47:53','2025-07-07 21:47:53'),(93,80,'variation','\"[]\"','array','2025-07-07 21:48:36','2025-07-07 21:48:36'),(94,81,'variation','\"[]\"','array','2025-07-07 21:49:29','2025-07-07 21:49:29'),(95,82,'variation','\"[]\"','array','2025-07-07 21:50:05','2025-07-07 21:50:05'),(118,105,'variation','\"[]\"','array','2025-07-07 22:12:43','2025-07-07 22:12:43'),(119,106,'variation','\"[]\"','array','2025-07-07 22:13:27','2025-07-07 22:13:27'),(121,108,'variation','\"[]\"','array','2025-07-07 23:17:33','2025-07-07 23:17:33'),(122,109,'variation','\"[]\"','array','2025-07-07 23:18:31','2025-07-07 23:18:31'),(123,110,'variation','\"[]\"','array','2025-07-07 23:19:51','2025-07-07 23:19:51'),(124,111,'variation','\"[]\"','array','2025-07-07 23:20:53','2025-07-07 23:20:53'),(125,112,'variation','\"[]\"','array','2025-07-07 23:21:35','2025-07-07 23:21:35'),(126,113,'variation','\"[]\"','array','2025-07-07 23:25:26','2025-07-07 23:25:26'),(127,114,'variation','\"[]\"','array','2025-07-07 23:26:38','2025-07-07 23:26:38'),(128,115,'variation','\"[]\"','array','2025-07-07 23:27:27','2025-07-07 23:27:27'),(129,116,'variation','\"[]\"','array','2025-07-07 23:29:15','2025-07-07 23:29:15'),(130,117,'variation','\"[]\"','array','2025-07-07 23:30:20','2025-07-07 23:30:20'),(131,118,'variation','\"[]\"','array','2025-07-07 23:31:17','2025-07-07 23:31:17'),(132,119,'variation','\"[]\"','array','2025-07-07 23:32:06','2025-07-07 23:32:06'),(133,120,'variation','\"[]\"','array','2025-07-07 23:33:13','2025-07-07 23:33:13'),(134,121,'variation','\"[]\"','array','2025-07-07 23:34:43','2025-07-07 23:34:43'),(135,122,'variation','\"[]\"','array','2025-07-07 23:36:21','2025-07-07 23:36:21'),(136,123,'variation','\"[]\"','array','2025-07-07 23:39:51','2025-07-07 23:39:51'),(137,124,'variation','\"[]\"','array','2025-07-07 23:40:35','2025-07-07 23:40:35'),(138,125,'variation','\"[]\"','array','2025-07-07 23:41:29','2025-07-07 23:41:29'),(139,126,'variation','\"[]\"','array','2025-07-07 23:42:03','2025-07-07 23:42:03'),(140,127,'variation','\"[]\"','array','2025-07-07 23:55:39','2025-07-07 23:55:39'),(142,129,'variation','\"[]\"','array','2025-07-07 23:57:45','2025-07-07 23:57:45'),(143,130,'variation','\"[]\"','array','2025-07-07 23:58:45','2025-07-07 23:58:45'),(144,131,'variation','\"[]\"','array','2025-07-07 23:59:54','2025-07-07 23:59:54'),(145,132,'variation','\"[]\"','array','2025-07-08 00:01:01','2025-07-08 00:01:01'),(146,133,'variation','\"[]\"','array','2025-07-08 00:02:53','2025-07-08 00:02:53'),(147,134,'variation','\"[]\"','array','2025-07-08 00:04:41','2025-07-08 00:04:41'),(148,135,'variation','\"[]\"','array','2025-07-08 00:05:55','2025-07-08 00:05:55'),(149,136,'variation','\"[]\"','array','2025-07-08 00:06:58','2025-07-08 00:06:58'),(150,137,'variation','\"[]\"','array','2025-07-08 00:07:57','2025-07-08 00:07:57'),(151,138,'variation','\"[]\"','array','2025-07-08 00:09:01','2025-07-08 00:09:01'),(152,139,'variation','\"[]\"','array','2025-07-08 00:09:45','2025-07-08 00:09:45'),(153,140,'variation','\"[]\"','array','2025-07-09 02:50:43','2025-07-09 02:50:43'),(154,141,'variation','\"[]\"','array','2025-07-09 02:52:13','2025-07-09 02:52:13');
/*!40000 ALTER TABLE `product_props` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `restaurant_id` int DEFAULT NULL,
  `identifier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `image` text COLLATE utf8mb4_unicode_ci,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_identifier_unique` (`identifier`)
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (16,4,2,'PROD-016','French Fries','Golden crispy french fries, perfectly seasoned.',2.99,'USD',0.10,'images/product/678272662593c.png','active','2025-06-08 01:16:17','2025-06-08 01:16:17'),(17,4,2,'PROD-017','Chicken Nuggets','Juicy and tender chicken nuggets with dipping sauce.',3.99,'USD',0.15,'images/product/678272f0a7a5a.png','active','2025-06-08 01:16:17','2025-06-08 01:16:17'),(18,4,2,'PROD-018','Onion Rings','Crispy fried onion rings, perfect for snacking.',3.49,'USD',0.20,'images/product/67827298c927a.png','active','2025-06-08 01:16:17','2025-06-08 01:16:17'),(19,4,2,'PROD-019','Mozzarella Sticks','Cheesy mozzarella sticks with marinara dip.',4.49,'USD',0.25,'images/product/6782731ed0914.png','active','2025-06-08 01:16:17','2025-06-08 01:16:17'),(20,4,2,'PROD-020','Popcorn Chicken','Crispy bite-sized chicken pieces with flavorful seasoning.',3.99,'USD',0.30,'images/product/6782735875477.png','active','2025-06-08 01:16:17','2025-06-08 01:16:17'),(21,5,2,'PROD-021','Chocolate Brownie','Rich and fudgy chocolate brownie topped with chocolate sauce.',3.99,'USD',0.25,'images/product/67827014368c3.png','active','2025-06-08 01:16:17','2025-06-08 01:16:17'),(22,5,2,'PROD-022','Vanilla Ice Cream','Creamy vanilla ice cream made with natural vanilla beans.',2.99,'USD',0.15,'images/product/6782705c0228d.png','active','2025-06-08 01:16:17','2025-06-08 01:16:17'),(23,5,2,'PROD-023','Cheesecake Slice','Classic cheesecake slice with a graham cracker crust.',4.49,'USD',0.30,'images/product/678273a300afb.png','active','2025-06-08 01:16:17','2025-06-08 01:16:17'),(24,5,2,'PROD-024','Strawberry Sundae','A delightful sundae with fresh strawberries and whipped cream.',3.75,'USD',0.20,'images/product/678273fca393e.png','active','2025-06-08 01:16:17','2025-06-08 01:16:17'),(25,5,2,'PROD-025','Chocolate Lava Cake','Warm and gooey lava cake with molten chocolate inside.',5.99,'USD',0.10,'images/product/6782742719d43.png','active','2025-06-08 01:16:17','2025-06-08 01:16:17'),(31,15,1,'PROD-031','Beef Biryani','Beefy and spicy lovee dovee.',210.00,'USD',0.00,'images/product/686373d0096fe.png','active','2025-07-01 00:36:12','2025-07-01 00:36:28'),(32,15,1,'PROD-032','Chicken Biryani','Tender and juicy meaty chicken',185.00,'USD',0.00,'images/product/6863745329f90.png','active','2025-07-01 00:38:23','2025-07-01 00:38:27'),(33,15,1,'PROD-033','Party Tray (Medium) - Beef Biryani','Party trays good for 5 Pax',1050.00,'USD',0.00,'images/product/686391e487063.png','active','2025-07-01 01:48:38','2025-07-01 02:46:41'),(34,15,1,'PROD-034','Party Tray (Large) - Beef Biryani','Large Tray Good for 8 Pax',1700.00,'USD',0.00,'images/product/6863923e9f1ea.png','active','2025-07-01 02:43:37','2025-07-01 02:46:06'),(35,15,1,'PROD-035','Party Tray (Medium) - Chicken Biryani','Party Tray good for 5 pax',950.00,'USD',0.00,'images/product/686396084e934.png','active','2025-07-01 03:02:15','2025-07-01 03:02:16'),(36,15,1,'PROD-036','Party Tray (Large) - Chicken Biryani','Party Tray good for 8 pax',1500.00,'USD',0.00,'images/product/6863986724627.png','active','2025-07-01 03:11:28','2025-07-01 03:12:26'),(37,10,1,'PROD-037','Plain','Plain goodness',230.00,'USD',0.00,'images/product/6863f1b963043.png','active','2025-07-01 09:33:28','2025-07-01 09:33:29'),(38,10,1,'PROD-038','Honey Glazed','Glass Glazed Goodness',250.00,'USD',0.00,'images/product/6863f2c29807a.png','active','2025-07-01 09:37:06','2025-07-01 09:41:00'),(39,10,1,'PROD-039','Sweet Chilli','Chillisweetness goodness.',250.00,'USD',0.00,'images/product/6863f358c10c3.png','active','2025-07-01 09:40:23','2025-07-01 09:40:24'),(40,10,1,'PROD-040','Teriyaki','Teriyaki taste like japan',250.00,'USD',0.00,'images/product/6863f3f5d15d4.png','active','2025-07-01 09:42:59','2025-07-01 09:43:01'),(41,20,1,'PROD-041','Americano','Americano',90.00,'USD',0.00,'images/product/68693491c9a9b.png','active','2025-07-05 09:20:00','2025-07-05 09:20:01'),(42,20,1,'PROD-042','Cappuccino','Cappuccino Delight',100.00,'USD',0.00,'images/product/686934fc88e43.png','active','2025-07-05 09:21:47','2025-07-05 09:21:48'),(43,20,1,'PROD-043','Cafe Latte','Cafe Latte goodness',100.00,'USD',0.00,'images/product/686935a3dac2e.png','active','2025-07-05 09:24:34','2025-07-05 09:24:35'),(44,20,1,'PROD-044','Hot Caramel','Hot Caramel',115.00,'USD',0.00,'images/product/686a20d719c27.png','active','2025-07-05 22:26:38','2025-07-06 02:08:07'),(45,19,1,'PROD-045','Hot Matcha','Drinks',100.00,'USD',0.00,'images/product/686a35e4bed4f.png','active','2025-07-06 03:37:54','2025-07-06 03:37:56'),(46,19,1,'PROD-046','Hot Choco','Drinks',100.00,'USD',0.00,'images/product/686a364150223.png','active','2025-07-06 03:39:27','2025-07-06 03:39:29'),(47,19,1,'PROD-047','Iced Taro Cream Cheese','Drinks',120.00,'USD',0.00,'images/product/686a368d159fb.png','active','2025-07-06 03:40:43','2025-07-06 03:40:45'),(48,19,1,'PROD-048','Iced Thai Cream Cheese','Drinks',120.00,'USD',0.00,'images/product/686a36dd02522.png','active','2025-07-06 03:42:03','2025-07-06 03:42:05'),(49,19,1,'PROD-049','Iced Matcha Latte','Drinks',120.00,'USD',0.00,'images/product/686a37204c42f.png','active','2025-07-06 03:43:10','2025-07-06 03:43:12'),(50,19,1,'PROD-050','Peach Mulberry','Drinks',120.00,'USD',0.00,'images/product/686a378e58753.png','active','2025-07-06 03:45:00','2025-07-06 03:45:02'),(51,19,1,'PROD-051','Blue Berry Mulberry','Drinks',120.00,'USD',0.00,'images/product/686a37e9cc78f.png','active','2025-07-06 03:46:31','2025-07-06 03:46:33'),(52,19,1,'PROD-052','Pomelo Cantaloupe Soda','Drinks',120.00,'USD',0.00,'images/product/686a385bae3b5.png','active','2025-07-06 03:48:24','2025-07-06 03:48:27'),(53,19,1,'PROD-053','Lychee Mulberry Soda','Drinks',120.00,'USD',0.00,'images/product/686a38c82a145.png','active','2025-07-06 03:50:13','2025-07-06 03:50:16'),(54,20,1,'PROD-054','Iced Tropicoffee','Iced coffee',150.00,'USD',0.00,'images/product/686a395e9630a.png','active','2025-07-06 03:52:43','2025-07-06 03:52:46'),(55,20,1,'PROD-055','Iced Tamarind Americano','Iced coffee',120.00,'USD',0.00,'images/product/686a39ac977ad.png','active','2025-07-06 03:54:02','2025-07-06 03:54:04'),(56,20,1,'PROD-056','Caramel Cream Coffee','Iced coffee',140.00,'USD',0.00,'images/product/686a3a10648de.png','active','2025-07-06 03:55:41','2025-07-06 03:55:44'),(57,20,1,'PROD-057','Caramel Macchiato','Iced coffee',140.00,'USD',0.00,'images/product/686a3a624cc8b.png','active','2025-07-06 03:57:03','2025-07-06 03:57:06'),(58,20,1,'PROD-058','Dirty Matcha','Iced coffee',150.00,'USD',0.00,'images/product/686a3be8ad38b.png','active','2025-07-06 04:03:35','2025-07-06 04:03:36'),(59,19,1,'PROD-059','Lemongrass Kiwi','Lemongrass Kiwi',110.00,'USD',0.00,'images/product/686c8080b8de5.png','active','2025-07-07 21:20:47','2025-07-07 21:20:48'),(60,19,1,'PROD-060','Grapefruit Cantaloupe','Grapefruit Cantaloupe',110.00,'USD',0.00,'images/product/686c80e146e5d.png','active','2025-07-07 21:22:24','2025-07-07 21:22:25'),(61,19,1,'PROD-061','Mango Strawberry','Mango strawberry',110.00,'USD',0.00,'images/product/686c8120381fd.png','active','2025-07-07 21:23:26','2025-07-07 21:23:28'),(62,19,1,'PROD-062','Strawberry Lychee','Strawberry lychee',110.00,'USD',0.00,'images/product/686c81511a0dc.png','active','2025-07-07 21:24:16','2025-07-07 21:24:17'),(63,19,1,'PROD-063','Santol Jasmine','Santol Jasmine',110.00,'USD',0.00,'images/product/686c81a2bd736.png','active','2025-07-07 21:25:37','2025-07-07 21:25:38'),(64,19,1,'PROD-064','Mulberry Smoothie','Mulberry smoothie',120.00,'USD',0.00,'images/product/686c81d9d650a.png','active','2025-07-07 21:26:32','2025-07-07 21:26:33'),(65,19,1,'PROD-065','Strawberry Smoothie','Strawberry smoothie',120.00,'USD',0.00,'images/product/686c820839ead.png','active','2025-07-07 21:27:18','2025-07-07 21:27:20'),(66,19,1,'PROD-066','Mango Santol Smoothie','Mango santol smoothie',120.00,'USD',0.00,'images/product/686c824b9820b.png','active','2025-07-07 21:28:26','2025-07-07 21:28:27'),(67,19,1,'PROD-067','Melon Smoothie','Melon Smoothie',120.00,'USD',0.00,'images/product/686c8281f2af0.png','active','2025-07-07 21:29:20','2025-07-07 21:29:21'),(68,19,1,'PROD-068','Tropical Mulberry','Tropical Mulberry',150.00,'USD',0.00,'images/product/686c82bf92ed0.png','active','2025-07-07 21:30:22','2025-07-07 21:30:23'),(69,19,1,'PROD-069','Strawberry Mulberry','Strawberry mulberry',150.00,'USD',0.00,'images/product/686c82e85ec05.png','active','2025-07-07 21:31:03','2025-07-07 21:31:04'),(70,19,1,'PROD-070','Santol Mulberry','Santol Mulberry',150.00,'USD',0.00,'images/product/686c8338ac4fe.png','active','2025-07-07 21:32:22','2025-07-07 21:32:24'),(71,19,1,'PROD-071','Caramel Macchiato Frappe','Caramel Macchiato Frappe',150.00,'USD',0.00,'images/product/686c837050da0.png','active','2025-07-07 21:33:18','2025-07-07 21:33:20'),(74,17,1,'PROD-074','Cheese Quesadilla','Cheese quesadilla',150.00,'USD',0.00,'images/product/686c856c094e7.png','active','2025-07-07 21:41:46','2025-07-07 21:41:48'),(75,17,1,'PROD-075','Cheese Quesadilla with Fries','Cheese quesadilla with fries',200.00,'USD',0.00,'images/product/686c85a976fad.png','active','2025-07-07 21:42:47','2025-07-07 21:42:49'),(76,17,1,'PROD-076','Beef Quesadilla','Beef Quesadilla',180.00,'USD',0.00,'images/product/686c85d53897c.png','active','2025-07-07 21:43:31','2025-07-07 21:43:33'),(78,17,1,'PROD-078','Beef Quesadilla with Fries','Beef Quesadilla with Fries',230.00,'USD',0.00,'images/product/686c86a387832.png','active','2025-07-07 21:46:58','2025-07-07 21:46:59'),(79,17,1,'PROD-079','Fries Plain','Fries',85.00,'USD',0.00,'images/product/686c86dad62b4.png','active','2025-07-07 21:47:53','2025-07-07 21:47:54'),(80,17,1,'PROD-080','Fries Cheese','Fries',85.00,'USD',0.00,'images/product/686c8705df5a9.png','active','2025-07-07 21:48:36','2025-07-07 21:48:37'),(81,17,1,'PROD-081','Fries Sour Cream','Fries',85.00,'USD',0.00,'images/product/686c873ace64f.png','active','2025-07-07 21:49:29','2025-07-07 21:49:30'),(82,17,1,'PROD-082','Fries BBQ','Fries',85.00,'USD',0.00,'images/product/686c875f3d5bb.png','active','2025-07-07 21:50:05','2025-07-07 21:50:07'),(105,17,1,'PROD-105','Cheese Quesadilla Tray','Cheese quesadilla',750.00,'USD',0.00,'images/product/686c8cac6f11f.png','active','2025-07-07 22:12:43','2025-07-07 22:12:44'),(106,17,1,'PROD-106','Beef Quesadilla Tray','Beef Quesadilla',900.00,'USD',0.00,'images/product/686c8cd90f1d9.png','active','2025-07-07 22:13:27','2025-07-07 22:13:29'),(108,16,1,'PROD-108','Berry Classic Fried Chicken','Classic Fried Chicken',170.00,'USD',0.00,'images/product/686c9bdfc7f52.png','active','2025-07-07 23:17:33','2025-07-07 23:17:35'),(109,16,1,'PROD-109','Garlic Pepper Chicken','Garlic Pepper Chicken',165.00,'USD',0.00,'images/product/686c9c18e12f2.png','active','2025-07-07 23:18:31','2025-07-07 23:18:32'),(110,16,1,'PROD-110','Chicken Sisig','Chicken Sisig',165.00,'USD',0.00,'images/product/686c9c69d5418.png','active','2025-07-07 23:19:51','2025-07-07 23:19:53'),(111,16,1,'PROD-111','Burger Steak','Burger steak',165.00,'USD',0.00,'images/product/686c9ca7a7676.png','active','2025-07-07 23:20:53','2025-07-07 23:20:55'),(112,16,1,'PROD-112','Beef Tapa','Beef tapa',165.00,'USD',0.00,'images/product/686c9cd184232.png','active','2025-07-07 23:21:35','2025-07-07 23:21:37'),(113,14,1,'PROD-113','Mango Float','Mango float',165.00,'USD',0.00,'images/product/686c9db9e9b80.png','active','2025-07-07 23:25:26','2025-07-07 23:25:29'),(114,14,1,'PROD-114','Leche Flan','Leche Flan',145.00,'USD',0.00,'images/product/686c9e00940ba.png','active','2025-07-07 23:26:38','2025-07-07 23:26:40'),(115,14,1,'PROD-115','Blueberry Cheesecake','Blueberry Cheesecake',175.00,'USD',0.00,'images/product/686c9e3626266.png','active','2025-07-07 23:27:27','2025-07-07 23:27:34'),(116,14,1,'PROD-116','Red Velvet','Red velvet',120.00,'USD',0.00,'images/product/686c9e9e637f2.png','active','2025-07-07 23:29:15','2025-07-07 23:29:18'),(117,14,1,'PROD-117','Chocolate Cake','Chocolate cake',120.00,'USD',0.00,'images/product/686c9ee3371a0.png','active','2025-07-07 23:30:20','2025-07-07 23:30:27'),(118,14,1,'PROD-118','Rainbow Cake','Rainbow Cake',140.00,'USD',0.00,'images/product/686c9f16e9f45.png','active','2025-07-07 23:31:17','2025-07-07 23:31:18'),(119,14,1,'PROD-119','Sans Rival','Sans Rival',130.00,'USD',0.00,'images/product/686c9f4928cbf.png','active','2025-07-07 23:32:06','2025-07-07 23:32:09'),(120,14,1,'PROD-120','Black Forest','Black Forest',130.00,'USD',0.00,'images/product/686c9f8bbc7e0.png','active','2025-07-07 23:33:13','2025-07-07 23:33:15'),(121,14,1,'PROD-121','Halo Halo','Halo Halo',120.00,'USD',0.00,'images/product/686c9fe46c5bb.png','active','2025-07-07 23:34:43','2025-07-07 23:34:44'),(122,13,1,'PROD-122','Caesar Salad','Caesar Salad',200.00,'USD',0.00,'images/product/686ca04854c54.png','active','2025-07-07 23:36:21','2025-07-07 23:36:24'),(123,12,1,'PROD-123','Bake Mac','Bake Mac',185.00,'USD',0.00,'images/product/686ca118e0bf3.png','active','2025-07-07 23:39:51','2025-07-07 23:39:52'),(124,12,1,'PROD-124','Aglio Olio','Aglio Olio',165.00,'USD',0.00,'images/product/686ca146806ed.png','active','2025-07-07 23:40:35','2025-07-07 23:40:38'),(125,12,1,'PROD-125','Classic Spaghetti','Classic spaghetti',155.00,'USD',0.00,'images/product/686ca17aba058.png','active','2025-07-07 23:41:29','2025-07-07 23:41:30'),(126,12,1,'PROD-126','Lomi','Lomi',200.00,'USD',0.00,'images/product/686ca19d9c08e.png','active','2025-07-07 23:42:03','2025-07-07 23:42:05'),(127,11,1,'PROD-127','Beef Kansi','Beef Kansi',380.00,'USD',0.00,'images/product/686ca4ce66562.png','active','2025-07-07 23:55:39','2025-07-07 23:55:42'),(129,11,1,'PROD-129','Berry Classic Fried Chicken Whole','Classic Fried Chicken',350.00,'USD',0.00,'images/product/686ca54ad2bac.png','active','2025-07-07 23:57:45','2025-07-07 23:57:46'),(130,11,1,'PROD-130','Berry Classic Fried Chicken Half','Classic Fried Chicken',190.00,'USD',0.00,'images/product/686ca5895cc87.png','active','2025-07-07 23:58:45','2025-07-07 23:58:49'),(131,11,1,'PROD-131','Native Chicken Tinola','Chicken Tinola',275.00,'USD',0.00,'images/product/686ca5cd30a4c.png','active','2025-07-07 23:59:54','2025-07-07 23:59:57'),(132,11,1,'PROD-132','Egg Soup','Egg Soup',155.00,'USD',0.00,'images/product/686ca60f65aff.png','active','2025-07-08 00:01:01','2025-07-08 00:01:03'),(133,11,1,'PROD-133','Sinigang (salmon belly/tuna','Sinigang',220.00,'USD',0.00,'images/product/686ca67fd836d.png','active','2025-07-08 00:02:53','2025-07-08 00:02:55'),(134,11,1,'PROD-134','Chicken (battered/garlic','Chicken battered/garlic',250.00,'USD',0.00,'images/product/686ca6ea71ecf.png','active','2025-07-08 00:04:41','2025-07-08 00:04:42'),(135,11,1,'PROD-135','Chopsuey','Chopsuey',210.00,'USD',0.00,'images/product/686ca7354646f.png','active','2025-07-08 00:05:55','2025-07-08 00:05:57'),(136,11,1,'PROD-136','Sotanghon Guisado','Sotanghon Guisado',200.00,'USD',0.00,'images/product/686ca7741fc44.png','active','2025-07-08 00:06:58','2025-07-08 00:07:00'),(137,11,1,'PROD-137','Chicken Sisig with Egg','Chicken Sisig',200.00,'USD',0.00,'images/product/686ca7af1481b.png','active','2025-07-08 00:07:57','2025-07-08 00:07:59'),(138,11,1,'PROD-138','Chicken Shanghai','Chicken Shanghai',150.00,'USD',0.00,'images/product/686ca7ee30d33.png','active','2025-07-08 00:09:01','2025-07-08 00:09:02'),(139,11,1,'PROD-139','Plain Rice','Rice',25.00,'USD',0.00,'images/product/686e146be5f4a.png','active','2025-07-08 00:09:45','2025-07-09 02:04:11'),(140,17,1,'PROD-140','Nachos','Nachos',230.00,'USD',0.00,'images/product/686e1f63b1dc7.jpeg','active','2025-07-09 02:50:43','2025-07-09 02:50:59'),(141,17,1,'PROD-141','Teobokki','Teobokki',175.00,'USD',0.00,'images/product/686e1fbca52ef.jpeg','active','2025-07-09 02:52:13','2025-07-09 02:52:28');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profiles`
--

DROP TABLE IF EXISTS `profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `profiles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `profiles_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profiles`
--

LOCK TABLES `profiles` WRITE;
/*!40000 ALTER TABLE `profiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `replies`
--

DROP TABLE IF EXISTS `replies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `replies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `content` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint unsigned DEFAULT NULL,
  `restaurant_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `replies_user_id_foreign` (`user_id`),
  KEY `replies_restaurant_id_foreign` (`restaurant_id`),
  CONSTRAINT `replies_restaurant_id_foreign` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `replies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `replies`
--

LOCK TABLES `replies` WRITE;
/*!40000 ALTER TABLE `replies` DISABLE KEYS */;
/*!40000 ALTER TABLE `replies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurant_meta`
--

DROP TABLE IF EXISTS `restaurant_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `restaurant_meta` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `restaurant_id` bigint unsigned NOT NULL,
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `restaurant_meta_restaurant_id_meta_key_unique` (`restaurant_id`,`meta_key`),
  KEY `restaurant_meta_restaurant_id_index` (`restaurant_id`),
  KEY `restaurant_meta_meta_key_index` (`meta_key`),
  CONSTRAINT `restaurant_meta_restaurant_id_foreign` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurant_meta`
--

LOCK TABLES `restaurant_meta` WRITE;
/*!40000 ALTER TABLE `restaurant_meta` DISABLE KEYS */;
/*!40000 ALTER TABLE `restaurant_meta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurant_settings`
--

DROP TABLE IF EXISTS `restaurant_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `restaurant_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `restaurant_id` bigint unsigned DEFAULT NULL,
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurant_settings`
--

LOCK TABLES `restaurant_settings` WRITE;
/*!40000 ALTER TABLE `restaurant_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `restaurant_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurant_timings`
--

DROP TABLE IF EXISTS `restaurant_timings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `restaurant_timings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `restaurant_id` bigint unsigned NOT NULL,
  `day` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `restaurant_timings_restaurant_id_foreign` (`restaurant_id`),
  CONSTRAINT `restaurant_timings_restaurant_id_foreign` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurant_timings`
--

LOCK TABLES `restaurant_timings` WRITE;
/*!40000 ALTER TABLE `restaurant_timings` DISABLE KEYS */;
INSERT INTO `restaurant_timings` VALUES (1,1,'Monday','09:00:00','22:00:00','active','2025-06-08 01:16:16','2025-06-08 01:16:16'),(2,1,'Tuesday','09:00:00','22:00:00','active','2025-06-08 01:16:16','2025-06-08 01:16:16'),(3,1,'Wednesday','09:00:00','22:00:00','active','2025-06-08 01:16:16','2025-06-08 01:16:16'),(4,1,'Thursday','09:00:00','22:00:00','active','2025-06-08 01:16:16','2025-06-08 01:16:16'),(5,1,'Friday','09:00:00','22:00:00','active','2025-06-08 01:16:16','2025-06-08 01:16:16'),(6,1,'Saturday','09:00:00','22:00:00','active','2025-06-08 01:16:16','2025-06-08 01:16:16'),(7,1,'Sunday','09:00:00','22:00:00','active','2025-06-08 01:16:16','2025-06-08 01:16:16');
/*!40000 ALTER TABLE `restaurant_timings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurant_timings_meta`
--

DROP TABLE IF EXISTS `restaurant_timings_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `restaurant_timings_meta` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `restaurant_id` bigint unsigned NOT NULL,
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `restaurant_timings_meta_restaurant_id_meta_key_unique` (`restaurant_id`,`meta_key`),
  CONSTRAINT `restaurant_timings_meta_restaurant_id_foreign` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurant_timings_meta`
--

LOCK TABLES `restaurant_timings_meta` WRITE;
/*!40000 ALTER TABLE `restaurant_timings_meta` DISABLE KEYS */;
/*!40000 ALTER TABLE `restaurant_timings_meta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurants`
--

DROP TABLE IF EXISTS `restaurants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `restaurants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` text COLLATE utf8mb4_unicode_ci,
  `favicon` text COLLATE utf8mb4_unicode_ci,
  `logo` text COLLATE utf8mb4_unicode_ci,
  `copyright_text` text COLLATE utf8mb4_unicode_ci,
  `rating` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `is_active` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `dial_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'United States',
  `enableTax` tinyint(1) NOT NULL DEFAULT '1',
  `enableDeliveryCharges` tinyint(1) NOT NULL DEFAULT '1',
  `tips` decimal(8,2) NOT NULL DEFAULT '0.00',
  `delivery_charges` decimal(8,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurants`
--

LOCK TABLES `restaurants` WRITE;
/*!40000 ALTER TABLE `restaurants` DISABLE KEYS */;
INSERT INTO `restaurants` VALUES (1,'The Berry Cafe','The Berry Cafe','09079810684','contact@theberry.com','https://www.theberry.com','A fine dining experience with globally inspired cuisine.','images/restaurant/REST01.png','images/restaurant/686e1ff3551cb.png','images/restaurant/686e1ff356d44.png','TheBerryCafe',4.80,'active','1','+63','0.00','PHP','2025-06-08 01:16:16','2025-07-09 02:53:23','Philippines',1,1,0.00,0.00);
/*!40000 ALTER TABLE `restaurants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`),
  UNIQUE KEY `roles_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Super Admin','super-admin','2025-06-08 01:16:11','2025-06-08 01:16:11'),(2,'Admin','admin','2025-06-08 01:16:11','2025-06-08 01:16:11'),(3,'Manager','manager','2025-06-08 01:16:11','2025-06-08 01:16:11'),(4,'Chef','chef','2025-06-08 01:16:11','2025-06-08 01:16:11'),(5,'Waiter','waiter','2025-06-08 01:16:11','2025-06-08 01:16:11'),(6,'Cashier','cashier','2025-06-08 01:16:11','2025-06-08 01:16:11'),(7,'Delivery Boy','delivery-boy','2025-06-08 01:16:11','2025-06-08 01:16:11'),(8,'Receptionist','receptionist','2025-06-08 01:16:11','2025-06-08 01:16:11'),(9,'Cleaner','cleaner','2025-06-08 01:16:11','2025-06-08 01:16:11'),(10,'Customer','customer','2025-06-08 01:16:11','2025-06-08 01:16:11'),(11,'saleman','saleman','2025-06-19 14:57:35','2025-06-19 14:57:35');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rtable_booking_payments`
--

DROP TABLE IF EXISTS `rtable_booking_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rtable_booking_payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `rtable_booking_id` bigint unsigned NOT NULL,
  `payment_method` bigint unsigned NOT NULL,
  `payment_gateway` bigint unsigned NOT NULL,
  `payment_col1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_col2` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rtable_booking_payments`
--

LOCK TABLES `rtable_booking_payments` WRITE;
/*!40000 ALTER TABLE `rtable_booking_payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `rtable_booking_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rtable_bookings`
--

DROP TABLE IF EXISTS `rtable_bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rtable_bookings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `rtable_id` bigint DEFAULT NULL,
  `customer_id` bigint DEFAULT NULL,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `restaurant_id` bigint DEFAULT NULL,
  `order_id` bigint DEFAULT NULL,
  `booking_start` datetime DEFAULT NULL,
  `booking_end` datetime DEFAULT NULL,
  `no_of_seats` bigint DEFAULT '0',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled','checked_in','checked_out','no_show','reserved','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rtable_bookings`
--

LOCK TABLES `rtable_bookings` WRITE;
/*!40000 ALTER TABLE `rtable_bookings` DISABLE KEYS */;
/*!40000 ALTER TABLE `rtable_bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rtablebooking_rtables`
--

DROP TABLE IF EXISTS `rtablebooking_rtables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rtablebooking_rtables` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `restaurant_id` bigint unsigned NOT NULL,
  `rtable_booking_id` bigint unsigned NOT NULL,
  `rtable_id` bigint unsigned NOT NULL,
  `booking_start` datetime NOT NULL,
  `booking_end` datetime NOT NULL,
  `no_of_seats` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rtablebooking_rtables`
--

LOCK TABLES `rtablebooking_rtables` WRITE;
/*!40000 ALTER TABLE `rtablebooking_rtables` DISABLE KEYS */;
/*!40000 ALTER TABLE `rtablebooking_rtables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rtables`
--

DROP TABLE IF EXISTS `rtables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rtables` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `restaurant_id` bigint unsigned DEFAULT NULL,
  `identifier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `floor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_of_seats` int DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rtables_identifier_unique` (`identifier`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rtables`
--

LOCK TABLES `rtables` WRITE;
/*!40000 ALTER TABLE `rtables` DISABLE KEYS */;
INSERT INTO `rtables` VALUES (1,'Table-04First',1,'TABL-001','First',4,'Random table description.','active','2025-06-08 01:16:17','2025-06-08 01:16:17'),(2,'Table-05First',1,'TABL-002','First',5,'Random table description.','active','2025-06-08 01:16:17','2025-06-08 01:16:17'),(3,'Table-08First',1,'TABL-003','First',8,'Random table description.','active','2025-06-08 01:16:17','2025-06-08 01:16:17'),(15,'Table-02Secon',2,'TABL-015','Second',2,'Random table description.','inactive','2025-06-08 01:16:17','2025-06-08 01:16:17'),(16,'Table-05Secon',2,'TABL-016','Second',5,'Random table description.','active','2025-06-08 01:16:17','2025-06-08 01:16:17'),(17,'Table-08Secon',2,'TABL-017','Second',8,'Random table description.','inactive','2025-06-08 01:16:17','2025-06-08 01:16:17'),(18,'Table-09Secon',2,'TABL-018','Second',9,'Random table description.','active','2025-06-08 01:16:17','2025-06-08 01:16:17'),(19,'Table-04Secon',2,'TABL-019','Second',4,'Random table description.','active','2025-06-08 01:16:17','2025-06-08 01:16:17'),(20,'Table-03Secon',2,'TABL-020','Second',3,'Random table description.','inactive','2025-06-08 01:16:17','2025-06-08 01:16:17'),(21,'Table-08Third',2,'TABL-021','Third',8,'Random table description.','active','2025-06-08 01:16:17','2025-06-08 01:16:17'),(22,'Table-05Third',2,'TABL-022','Third',5,'Random table description.','inactive','2025-06-08 01:16:17','2025-06-08 01:16:17'),(23,'Table-06Third',2,'TABL-023','Third',6,'Random table description.','active','2025-06-08 01:16:17','2025-06-08 01:16:17'),(24,'Table-07Third',2,'TABL-024','Third',7,'Random table description.','active','2025-06-08 01:16:17','2025-06-08 01:16:17'),(25,'Table-05Third',2,'TABL-025','Third',5,'Random table description.','inactive','2025-06-08 01:16:17','2025-06-08 01:16:17'),(26,'Table-04Third',2,'TABL-026','Third',4,'Random table description.','inactive','2025-06-08 01:16:17','2025-06-08 01:16:17'),(27,'Table-08Third',2,'TABL-027','Third',8,'Random table description.','active','2025-06-08 01:16:18','2025-06-08 01:16:18'),(28,'Table-09Third',2,'TABL-028','Third',9,'Random table description.','active','2025-06-08 01:16:18','2025-06-08 01:16:18'),(29,'Table-02Third',2,'TABL-029','Third',2,'Random table description.','inactive','2025-06-08 01:16:18','2025-06-08 01:16:18'),(30,'Table-07Third',2,'TABL-030','Third',7,'Random table description.','active','2025-06-08 01:16:18','2025-06-08 01:16:18'),(31,'Table-05First',2,'TABL-031','First',5,'Random table description.','inactive','2025-06-08 01:16:18','2025-06-08 01:16:18');
/*!40000 ALTER TABLE `rtables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
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
INSERT INTO `sessions` VALUES ('17P3yjk54Nud7xk21XlCqQ7GkSv3t5moPVOCKzoB',NULL,'47.91.125.252','Go-http-client/1.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTWRWSHIyeHNERnpvaU5Fek9oVEVyM2g0aUVtZk40T0hySkVDcXoxZCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NjY6Imh0dHBzOi8vMzUuMTU0LjcyLjEyNy8/ZG5zPTRSTUJBQUFCQUFBQUFBQUFCMlY0WVcxd2JHVURZMjl0QUFBQkFBRSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1752027477),('44jQVHeiS3we32TerRkGbLtecSSeOYRYObmhcQLr',NULL,'185.242.226.80','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.190 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRENhdXVKbjZSM3RYTlpQSnFtYXNBTXBVQ01rQnVJZEFkY1VUTEV1bSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8zNS4xNTQuNzIuMTI3Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1752037734),('6eaPeCsT5vHSaH3LNnn4vqAaGrxPsfA6aBJy9fTq',NULL,'87.236.176.14','Mozilla/5.0 (compatible; InternetMeasurement/1.0; +https://internet-measurement.com/)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMFhhZ1I5UHcybURBY2Y2RW5xa3hMcXZhN0dkbkNXS1ZxWG1lSGpuaiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMzUuMTU0LjcyLjEyNyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1752045291),('6QSTCDmUKke8qs1BCMe7eWZb85VxTXmAp8XfYcGc',NULL,'44.249.105.145','Mozilla/5.0 (Linux; Android 6.0.1; SM-N915T) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.143 Mobile Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTloxRXJ1SVZlcTZiVlhFQXg0SjdISHhSck5HUmNoS2NPYWwxamN4YSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMzUuMTU0LjcyLjEyNyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1752043121),('7CfMwfr8GUVqyRs68D7RYkcWxKGDfBxmaSwx4MqJ',NULL,'159.223.38.31','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTlpVUHNJVWlYcWJOaXZFMGExOFplV08zSG9TZ05nR2w0VUQ5OWpLRCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NzA6Imh0dHA6Ly9lYzItMzUtMTU0LTcyLTEyNy5hcC1zb3V0aC0xLmNvbXB1dGUuYW1hem9uYXdzLmNvbS8/eGRlYnVnaW5mbz0iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752046745),('9lYaJltIRyz8rlPgZsZObOdXroG2IXLnrwugcuXQ',NULL,'172.234.217.129','Mozilla/5.0 (Macintosh; Intel Mac OS X 13_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWjdxUHp5OTZ2cTZrWEFSelhlYmNHRWxHaHlwbExlaXNSaG5ueFlYZyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8zNS4xNTQuNzIuMTI3Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1752042743),('dZgNGzRPRNtcfSWtKPSIm9jbmptmTUCQJCxCMRcw',NULL,'64.62.156.142','Mozilla/5.0 (X11; Linux x86_64; rv:109.0) Gecko/20100101 Firefox/110.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNU0yR1l6RXljY3EzTGVCOVVHeHpscEFwOVREUnlOVTd6N3FRYlJkMCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8zNS4xNTQuNzIuMTI3Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1752031171),('FEHMDSkCAYeGTTU2OhhjF8kKXiYNRB8NM0iPgQQf',NULL,'47.237.115.100','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.95 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWGZXdEZFdlExcG15dlFLRXZJR2FzZFFMREc2eG9kNlNQYkptU003UCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMzUuMTU0LjcyLjEyNyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1752025212),('hAQs3SfKXGa9uem8LjPctaN88vmY2X1iL6ouVn05',NULL,'170.106.192.208','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWWtzZHBwZU1lZ3V0UlJDMFpwcjRiQTl0cGtUYkJQS25JTTVvVTVOcSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8zNS4xNTQuNzIuMTI3Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1752037039),('HDBNap7KqizI6UjmB2tXTLn6zI1BeJC39XRgejUE',NULL,'81.231.178.25','Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVUVLa3NqZUYwU2daQ3V3R2Y0NWlzTGlBMVQ5Q204cHRRT0dMN2xFQiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8zNS4xNTQuNzIuMTI3Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1752035858),('HqzMiaZ2Sj02NW4bSvSa2C8u6tv8EKq62qCdGyV1',NULL,'139.59.38.203','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSFFSdlBxTUQ5aXNza0xFUGhWcUprSzdYM2hidjd3TWt6ZGlQbTJDQiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8zNS4xNTQuNzIuMTI3Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1752027112),('if1P1igRkLvQMjsy1ePWufabr5dhS00uWVwNWmv0',NULL,'165.22.223.90','Mozilla/5.0 (Linux; Android 11; SM-G991B) AppleWebKit/537.36 Chrome/89.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUnlsNnhFZ3dGNTNOZ2xuTktIdE1Ka2d1VmNNM3h0ZGtWQ3dZT3daWSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDM6Imh0dHBzOi8vYXBpLnJlc3RhdXJhbnQuYXhldGVjaHNvbHV0aW9ucy5jb20iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752046253),('iFTMoS0IfBzn8MlSEeV3jewfL8KJ2qklcNMORQTh',NULL,'220.134.205.118','Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTlVJdk9NNlR6ZjJvWmpIQUllSzZBYm5SdXBLUjdZS09MM1Q4WEJqZCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8zNS4xNTQuNzIuMTI3Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1752036093),('jvgYgfy0w200Tpr5TFkufq15hBNgOVdtVjsIh0vH',NULL,'175.4.55.185','Custom-AsyncHttpClient','YTozOntzOjY6Il90b2tlbiI7czo0MDoia2tKTnN4dWdmWW1La0Y3WE1UTnZkQlpBY0pyTVhYUjBxTkZSWGdJZSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjAwOiJodHRwczovLzM1LjE1NC43Mi4xMjcvaW5kZXgucGhwPyUyRiUzQyUzRmVjaG8lMjhtZDUlMjglMjJoaSUyMiUyOSUyOSUzQiUzRiUzRSUyMCUyRnRtcCUyRmluZGV4MS5waHA9JmNvbmZpZy1jcmVhdGUlMjAlMkY9Jmxhbmc9Li4lMkYuLiUyRi4uJTJGLi4lMkYuLiUyRi4uJTJGLi4lMkYuLiUyRnVzciUyRmxvY2FsJTJGbGliJTJGcGhwJTJGcGVhcmNtZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1752042526),('Kti9are5kzPGgSfoPELwEHgltx8GH5bUKRNBvsUL',NULL,'159.89.88.69','','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRXJWZmRCRjZwRDEzOGlucXhYQlQ4NlZNdUNLVWVEeU9HNVVST3FNayI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8zNS4xNTQuNzIuMTI3Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1752028208),('mdd3DI54TjtfqqTEHrmLGke8ihqyVQIcZAsmME58',NULL,'165.22.223.90','python-requests/2.32.4','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRWZNbGNiUktXRzI0Y3FTd2dueGw3aE1kVU00Y1J3ZVNodEIxTGs3YSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDM6Imh0dHBzOi8vYXBpLnJlc3RhdXJhbnQuYXhldGVjaHNvbHV0aW9ucy5jb20iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752046253),('Mo6S7mSAQqpPdLPFrIO4XHkDjWmN7rCflg3eoo3A',NULL,'45.131.155.253','Mozilla/5.0 (X11; Linux x86_64; rv:109.0) Gecko/20100101 Firefox/113.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOTlURTJHckp3bTV1QzFxQVU4SGQwbjJFRjZsaDE3VHMySENIRUtmQyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMzUuMTU0LjcyLjEyNyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1752026248),('mQ5uKSsgFzWkN6QaLXEHUoUwT2DhfiJe2xgKEVEX',NULL,'185.242.226.117','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.190 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVWUxRlB0cWdFNFdhUjM2Y0FZaFVMelc5cU1ITWhpaWdQNVd0RmNaSSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMzUuMTU0LjcyLjEyNyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1752032441),('Nb4GcHaD6YGMqWl1g6IURs2jMtfYC5wTFRoVPygp',NULL,'47.91.125.252','Go-http-client/1.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiN2YxY2tweWVKb1lUSHlBdkpmdnRveVhqN2trcjYzU0xSNkFSYTFKSyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NjY6Imh0dHBzOi8vMzUuMTU0LjcyLjEyNy8/ZG5zPXgwMEJBQUFCQUFBQUFBQUFCMlY0WVcxd2JHVURZMjl0QUFBQkFBRSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1752027475),('nVu4zjHw3V6e9nLJ2yIo6gPuiKf5uJ7WwIcoNRbI',NULL,'185.218.84.47','curl/7.81.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOTk5ZVRPWmh2SGhSR2t5UmN3a3Zmc2o0a0YxWmhDZVJGSldWdkdYZSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8zNS4xNTQuNzIuMTI3Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1752038677),('pKd362kKkY151nI9Q8tq42TxY1k72UhUpmoJT5zt',NULL,'49.51.178.45','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQmw3Tjl3aTY1VklMMExZV0VQR2VKT3pzVk1FS3pDSFBPbU1kN2Z4eCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8zNS4xNTQuNzIuMTI3Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1752043267),('pNTwFxVwaiFUHyBPcfHjG2sY3DMbnf4XbUQ4wZlZ',NULL,'185.218.84.47','curl/7.81.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoibXQ3ZGUzNGhDb0JoWkxQOVM0VG10NGhrQXR6RlFXMk1uSmxMTEllcSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8zNS4xNTQuNzIuMTI3Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1752045273),('QeE20Kb76RkCEcH91TVYHUtmlYMaiOxczVnBTeLG',NULL,'185.12.59.118','-','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOXVGQnl6dFVZVDk4VlNQMDI0NFZvcFVwVGJwU1JkUmxXUzZvVnViRiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMzUuMTU0LjcyLjEyNyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1752047211),('rBq3fJEB0BaejR8ntWMF8DcJpGvj8dFnxpLNgzb8',NULL,'37.131.106.123','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVUg4d2t3STg2T0QxQkNXS3dUZ1lCellyQWh5SURieUdjYWlVdXl4eCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vd3d3LmFwaS50aGViZXJyeS5jYWZlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1752042428),('RwYlAoc8bvQiaFtL2LRns3gulRLzuyarVtQH7riU',NULL,'175.4.55.185','Custom-AsyncHttpClient','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVHdXam1PeDRDY1h5UzR6T2lZSUZMeHgwdExKeGtOTmhFSWpWQ0dQdiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTQ3OiJodHRwczovLzM1LjE1NC43Mi4xMjcvaW5kZXgucGhwP2Z1bmN0aW9uPWNhbGxfdXNlcl9mdW5jX2FycmF5JnM9JTJGaW5kZXglMkYlNUN0aGluayU1Q2FwcCUyRmludm9rZWZ1bmN0aW9uJnZhcnMlNUIwJTVEPW1kNSZ2YXJzJTVCMSU1RCU1QjAlNUQ9SGVsbG8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752042525),('T39AJ1sgkk3R3TMwSHyjXZU04MQDYR9TWliZClnR',NULL,'47.91.125.252','Go-http-client/1.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSmMzbGlTZ1k3cU9ZUEZSdFdxZU1VZW41c0xJcFM5ZTlDaHBOTnZUWSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDY6Imh0dHBzOi8vMzUuMTU0LjcyLjEyNy8/bmFtZT1leGFtcGxlLmNvbSZ0eXBlPUEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752027478),('ti43qD8YBJwVwptEN3spUoCGJ7CRoXH3Mmzf0kMP',NULL,'47.91.125.252','Go-http-client/1.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYXBEbjZiQzBmVXk0VVFOR0Nta1dqc083RWZWOE9wQnVWRWxZekVVbSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDY6Imh0dHBzOi8vMzUuMTU0LjcyLjEyNy8/bmFtZT1leGFtcGxlLmNvbSZ0eXBlPUEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752027477),('ULIZtd9bLp6DFmk3Iy0UvbWcbNkCh4TdfzmGZ2za',NULL,'3.134.148.59','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoicGNxRUx5UzRFaDdVaVk0dTFvSjNJZElIU09xY055MHpDck9DZmxFaCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMzUuMTU0LjcyLjEyNyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1752033908),('VgRs2sIAhYuAiw1cFR676KWvJ3VPydUzfrbeq1AL',NULL,'217.24.233.115','','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNUF2SXk5NktQYWNJaDhlZzB5dzN4bEplZDNsV0VORXVFYUFvRjgzSyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8zNS4xNTQuNzIuMTI3Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1752029712),('vZZRqhGYAvfl6JkUuY0AdkoIAON2RIg6ETy7xvDX',NULL,'175.4.55.185','Custom-AsyncHttpClient','YTozOntzOjY6Il90b2tlbiI7czo0MDoieG1ma21hVGdwMWNueTY4TjVZNTlUTko2bzhnem1sZUJGa1VHN1M2YyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODk6Imh0dHBzOi8vMzUuMTU0LjcyLjEyNy9pbmRleC5waHA/bGFuZz0uLiUyRi4uJTJGLi4lMkYuLiUyRi4uJTJGLi4lMkYuLiUyRi4uJTJGdG1wJTJGaW5kZXgxIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1752042527);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_addresses`
--

DROP TABLE IF EXISTS `user_addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_addresses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_addresses`
--

LOCK TABLES `user_addresses` WRITE;
/*!40000 ALTER TABLE `user_addresses` DISABLE KEYS */;
INSERT INTO `user_addresses` VALUES (1,1,'123 Main St, Apt 4','New York','NY','USA','2025-06-08 01:16:16','2025-06-08 01:16:16'),(5,5,'22 Maple Dr','San Francisco','CA','USA','2025-06-08 01:16:16','2025-06-08 01:16:16'),(6,6,'11 Cedar Ln','Seattle','WA','USA','2025-06-08 01:16:16','2025-06-08 01:16:16'),(7,7,'78 Willow Ct','Miami','FL','USA','2025-06-08 01:16:16','2025-06-08 01:16:16'),(8,8,'555 Birch Blvd','Boston','MA','USA','2025-06-08 01:16:16','2025-06-08 01:16:16'),(9,9,'321 Spruce St','Austin','TX','USA','2025-06-08 01:16:16','2025-06-08 01:16:16'),(10,10,'999 Redwood Way','Denver','CO','USA','2025-06-08 01:16:16','2025-06-08 01:16:16'),(12,29,'e',NULL,NULL,NULL,'2025-06-25 14:41:03','2025-06-25 14:41:03');
/*!40000 ALTER TABLE `user_addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_codes`
--

DROP TABLE IF EXISTS `user_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_codes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `code` int DEFAULT NULL,
  `expires_at` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_codes_user_id_foreign` (`user_id`),
  CONSTRAINT `user_codes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_codes`
--

LOCK TABLES `user_codes` WRITE;
/*!40000 ALTER TABLE `user_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_id` int DEFAULT NULL,
  `restaurant_id` int DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dial_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` text COLLATE utf8mb4_unicode_ci,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Super Admin','admin@theberry.cafe',NULL,'$2y$12$XwZtUHwx2xKJooabwoyZ.u3UezTpyJxtGN5chMMHjQkZM2AVeI3.K',1,0,'+921324124',NULL,'active','images/user/user-1.png','ibR5J714mK','2025-06-08 01:16:12','2025-06-08 01:16:12'),(18,'walk-in-customer','8402903@domain.com',NULL,'$2y$12$HtdXU5TTt12gYLtFc2g/oeGrlOL.TaIlS1Bj12gzl7IMkVy6uERqi',0,2,'+968-228402903',NULL,'active',NULL,NULL,'2025-04-23 23:42:00',NULL),(19,'walk-in-customer','2771017@domain.com',NULL,'$2y$12$9ngIta3dgSglc8IKvBrBceFbm289vehK5asUBr6kXqYXuv1Jndkri',0,2,'+968-212771017',NULL,'inactive',NULL,NULL,'2025-05-09 07:46:00',NULL),(20,'walk-in-customer','4347594@domain.com',NULL,'$2y$12$Uamy88ySi/rixDLXX2NCTOPEdmJfSn067Y.LDqWOhacOgujKglZSC',0,1,'+968-224347594',NULL,'active',NULL,NULL,'2025-06-07 21:44:00',NULL),(21,'walk-in-customer','4497724@domain.com',NULL,'$2y$12$ce1nIjTPfWj/7Yenb3iW2eMqs2fxATxw.Bm8EkOJeXafvaDX39lyG',0,2,'+968-224497724',NULL,'inactive',NULL,NULL,'2025-05-26 15:23:00',NULL),(22,'walk-in-customer','6593392@domain.com',NULL,'$2y$12$sRZbxSw.Zs/wohRMHfOHTeMnnFsJ5sxRXtpwF.vazFL0e6hoRpXHq',0,1,'+968-246593392',NULL,'active',NULL,NULL,'2025-04-02 12:50:00',NULL),(23,'walk-in-customer','2323113@domain.com',NULL,'$2y$12$vkFuMDVehvprO7urgQ3EEu9zKfIwBRGtPxsY8vMxnONbLAVUw1L2m',0,2,'+968-232323113',NULL,'active',NULL,NULL,'2025-06-17 06:39:00',NULL),(24,'walk-in-customer','9264213@domain.com',NULL,'$2y$12$h8rAUw0Ud2so007/MFkyVejAPDM4bk1RartRg2gxW4ZZFXfE2Ze7y',0,2,'+968-249264213',NULL,'active',NULL,NULL,'2025-06-27 22:47:00',NULL),(25,'walk-in-customer','3239131@domain.com',NULL,'$2y$12$OS8a3JGi.aBilESVfs/8dOgbP3ui1SNw5gTWWSoG5QTcCFq4g5hzC',0,2,'+968-263239131',NULL,'active',NULL,NULL,'2025-06-23 12:28:00',NULL),(26,'walk-in-customer','6006735@domain.com',NULL,'$2y$12$NE3GHBo1gaXC2CkY.wQV2uJHMHTe/4yT4aEP9SMA7uTdNpdi2tHQm',0,2,'+968-236006735',NULL,'inactive',NULL,NULL,'2025-06-23 08:15:00',NULL),(29,'saleman','saleman@theberry.cafe',NULL,'$2y$12$CxU.wc1VUmooQ/ryb1eO7e5AO0Br8d1egO2os.OlfFh7GPcXM6c5W',11,1,'37415433','+966','active',NULL,NULL,'2025-06-25 14:41:03',NULL),(30,'Khyifa','',NULL,NULL,11,NULL,'3215553556','+92','active',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `variations`
--

DROP TABLE IF EXISTS `variations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `variations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `variations_chk_1` CHECK (json_valid(`meta_value`))
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `variations`
--

LOCK TABLES `variations` WRITE;
/*!40000 ALTER TABLE `variations` DISABLE KEYS */;
/*!40000 ALTER TABLE `variations` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-07-09 18:38:26
