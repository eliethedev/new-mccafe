-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: cafecat_db
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
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `variant_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `variant_id` (`variant_id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_session` (`session_id`),
  KEY `idx_product` (`product_id`),
  CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_ibfk_3` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart`
--

LOCK TABLES `cart` WRITE;
/*!40000 ALTER TABLE `cart` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Coffee','Hot and cold coffee beveragess','69fe1edef1c24.png',1,1,'2026-05-08 06:07:59','2026-05-09 18:03:07'),(2,'Coffee Frappe','Blended coffee drinks','69ff76d7d80a3.png',2,1,'2026-05-08 06:07:59','2026-05-09 18:03:06'),(3,'Milk Tea','Tea-based milk beverages','69ff76e8a0354.png',3,1,'2026-05-08 06:07:59','2026-05-09 18:03:22'),(4,'Fruit Tea','Refreshing fruit-infused teas','69ff770abef5e.jpg',4,1,'2026-05-08 06:07:59','2026-05-09 18:03:59'),(5,'Non Coffee','Non-caffeinated hot and cold beverages','69ff771d15fd4.jpg',5,0,'2026-05-08 06:07:59','2026-05-09 18:04:13'),(6,'Non Coffee Frappe','Blended non-caffeinated drinks',NULL,6,1,'2026-05-08 06:07:59','2026-05-08 06:07:59'),(7,'Snacks','Light snacks and finger foods',NULL,7,1,'2026-05-08 06:07:59','2026-05-08 06:07:59'),(8,'Rice Bowl','Rice-based meal bowls',NULL,8,1,'2026-05-08 06:07:59','2026-05-08 06:07:59'),(9,'Pasta','Various pasta dishes',NULL,9,1,'2026-05-08 06:07:59','2026-05-08 06:07:59'),(10,'2PC Chicken','2-piece chicken meals with java rice',NULL,10,1,'2026-05-08 06:07:59','2026-05-08 06:07:59'),(12,'test','test','69ff774e82988.jpg',0,0,'2026-05-09 18:05:02','2026-05-09 18:05:02');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_verifications`
--

DROP TABLE IF EXISTS `email_verifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_verifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_token` (`token`),
  CONSTRAINT `email_verifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_verifications`
--

LOCK TABLES `email_verifications` WRITE;
/*!40000 ALTER TABLE `email_verifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `email_verifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory`
--

DROP TABLE IF EXISTS `inventory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `variant_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `reorder_level` int(11) DEFAULT 10,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `variant_id` (`variant_id`),
  KEY `idx_product` (`product_id`),
  KEY `idx_quantity` (`quantity`),
  CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_ibfk_2` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory`
--

LOCK TABLES `inventory` WRITE;
/*!40000 ALTER TABLE `inventory` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `login_attempts`
--

DROP TABLE IF EXISTS `login_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(150) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` text DEFAULT NULL,
  `success` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_email` (`email`),
  KEY `idx_ip` (`ip_address`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_attempts`
--

LOCK TABLES `login_attempts` WRITE;
/*!40000 ALTER TABLE `login_attempts` DISABLE KEYS */;
INSERT INTO `login_attempts` VALUES (1,'asfh@gmsl.c','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 OPR/130.0.0.0',0,'2026-05-08 08:26:30'),(2,'test@sasd.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 OPR/130.0.0.0',0,'2026-05-08 08:27:44'),(3,'test@sasd.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 OPR/130.0.0.0',0,'2026-05-08 08:27:48'),(4,'eliezersantillan648@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 OPR/130.0.0.0',1,'2026-05-08 08:28:01'),(5,'eliezersantillan648@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 OPR/130.0.0.0',1,'2026-05-08 08:30:45'),(6,'eliezersantillan648@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 OPR/130.0.0.0',1,'2026-05-08 08:32:28'),(7,'admin@maccafe.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36',0,'2026-05-08 09:07:55'),(8,'eliezersantillan648@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 OPR/130.0.0.0',0,'2026-05-08 14:20:32'),(9,'eliezersantillan648@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 OPR/130.0.0.0',1,'2026-05-08 14:20:50'),(10,'admin@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 OPR/130.0.0.0',0,'2026-05-08 16:13:27'),(11,'admin@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 OPR/130.0.0.0',0,'2026-05-08 16:16:54'),(12,'admin@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 OPR/130.0.0.0',0,'2026-05-08 16:17:01'),(13,'admin@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 OPR/130.0.0.0',1,'2026-05-08 16:20:32'),(14,'admin@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 OPR/130.0.0.0',1,'2026-05-08 16:30:45'),(15,'eliezersantillan648@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1,'2026-05-08 17:36:27'),(16,'eliezersantillan648@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 OPR/130.0.0.0',1,'2026-05-08 18:26:06'),(17,'tisuyongwapohon@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 OPR/130.0.0.0',1,'2026-05-09 05:17:27'),(18,'tisuyongwapohon@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 OPR/130.0.0.0',1,'2026-05-09 05:52:22'),(19,'admin@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1,'2026-05-09 12:33:15'),(20,'admin@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 OPR/130.0.0.0',1,'2026-05-09 16:04:03'),(21,'tisuyongwapohon@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 OPR/130.0.0.0',1,'2026-05-09 16:45:07'),(22,'admin@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1,'2026-05-09 16:46:25'),(23,'elie@g.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 OPR/130.0.0.0',0,'2026-05-10 04:04:02'),(24,'eliezersantilan648@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 OPR/130.0.0.0',0,'2026-05-10 04:05:02'),(25,'eliezersantillan648@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 OPR/130.0.0.0',1,'2026-05-10 04:05:21'),(26,'admin@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',0,'2026-05-10 04:07:01'),(27,'admin@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1,'2026-05-10 04:07:36'),(28,'admin@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1,'2026-05-10 04:07:58'),(29,'admin@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1,'2026-05-10 04:45:07'),(30,'eliezersantillan648@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0',1,'2026-05-11 16:31:59'),(31,'admin@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0',1,'2026-05-11 16:33:56'),(32,'eliezersantillan648@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0',1,'2026-05-11 16:36:32'),(33,'eliezersantillan648@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0',1,'2026-05-11 16:37:58'),(34,'admin@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0',1,'2026-05-11 16:38:48'),(35,'eliezersantillan648@gmail.co','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0',0,'2026-05-11 16:41:53'),(36,'eliezersantillan648@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0',1,'2026-05-11 16:42:05'),(37,'admin@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0',1,'2026-05-11 16:43:04'),(38,'eliezersantilla648@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',0,'2026-05-11 16:43:37'),(39,'eliezersantillan648@gmail.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1,'2026-05-11 16:43:52');
/*!40000 ALTER TABLE `login_attempts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `variant_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `variant_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `variant_id` (`variant_id`),
  KEY `idx_order` (`order_id`),
  KEY `idx_product` (`product_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `order_items_ibfk_3` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (24,24,62,NULL,2,2000.00,4000.00,'test product',NULL,'2026-05-11 16:37:16'),(25,25,61,NULL,1,200.00,200.00,'test product',NULL,'2026-05-11 16:42:23');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_status_history`
--

DROP TABLE IF EXISTS `order_status_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_status_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `status` enum('pending','confirmed','preparing','ready','completed','cancelled') NOT NULL,
  `changed_by` int(11) DEFAULT NULL COMMENT 'User who changed the status',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `changed_by` (`changed_by`),
  KEY `idx_order` (`order_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `order_status_history_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_status_history_ibfk_2` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_status_history`
--

LOCK TABLES `order_status_history` WRITE;
/*!40000 ALTER TABLE `order_status_history` DISABLE KEYS */;
INSERT INTO `order_status_history` VALUES (38,24,'pending',NULL,'Order placed, awaiting payment verification','2026-05-11 16:37:16'),(39,24,'cancelled',NULL,'GCash Order Cancellation: test cancel','2026-05-11 16:40:27'),(40,25,'pending',NULL,'Order placed, awaiting payment verification','2026-05-11 16:42:23'),(41,25,'confirmed',NULL,'','2026-05-11 16:44:05'),(42,25,'preparing',NULL,'','2026-05-11 16:44:17'),(43,25,'ready',NULL,'','2026-05-11 16:44:25'),(44,25,'completed',NULL,'','2026-05-11 16:44:31');
/*!40000 ALTER TABLE `order_status_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_number` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('pending','confirmed','preparing','ready','completed','cancelled') DEFAULT 'pending',
  `subtotal` decimal(10,2) NOT NULL,
  `tax_amount` decimal(10,2) DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','card','gcash','paymaya','bank_transfer') DEFAULT 'gcash',
  `payment_status` enum('pending','paid','refunded') DEFAULT 'pending',
  `customer_name` varchar(200) DEFAULT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `customer_email` varchar(150) DEFAULT NULL,
  `customer_address` varchar(255) DEFAULT NULL,
  `customer_city` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `prepared_by` int(11) DEFAULT NULL COMMENT 'Staff who prepared the order',
  `preparation_started_at` timestamp NULL DEFAULT NULL,
  `ready_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `prepared_by` (`prepared_by`),
  KEY `idx_user` (`user_id`),
  KEY `idx_status` (`status`),
  KEY `idx_order_number` (`order_number`),
  KEY `idx_created_at` (`created_at`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`prepared_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (24,'ORD202605119041',21,'cancelled',4000.00,480.00,4480.00,'gcash','pending','test  user','09780828108','eliezersantillan648@gmail.com','test addres',NULL,'GCash Order Cancellation: test cancel',NULL,NULL,NULL,NULL,'2026-05-11 16:37:16','2026-05-11 16:40:27'),(25,'ORD202605110018',21,'completed',200.00,24.00,224.00,'cash','pending','test  user','09780828108','eliezersantillan648@gmail.com','test addres',NULL,NULL,NULL,'2026-05-11 16:44:17','2026-05-11 16:44:25','2026-05-11 16:44:31','2026-05-11 16:42:23','2026-05-11 16:44:31');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(150) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_email` (`email`),
  KEY `idx_token` (`token`)
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
-- Table structure for table `payment_proofs`
--

DROP TABLE IF EXISTS `payment_proofs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_proofs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `payment_method` enum('gcash','paymaya','bank_transfer') NOT NULL,
  `reference_number` varchar(100) DEFAULT NULL,
  `proof_image` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','verified','rejected') DEFAULT 'pending',
  `verified_by` int(11) DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `verified_by` (`verified_by`),
  KEY `idx_order` (`order_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `payment_proofs_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payment_proofs_ibfk_2` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_proofs`
--

LOCK TABLES `payment_proofs` WRITE;
/*!40000 ALTER TABLE `payment_proofs` DISABLE KEYS */;
INSERT INTO `payment_proofs` VALUES (10,24,'gcash','345678912378','1778517436_samurai-spirit-in-japanese-kanji-backiee-HD.jpg',4480.00,'pending',NULL,NULL,NULL,'2026-05-11 16:37:16','2026-05-11 16:37:16');
/*!40000 ALTER TABLE `payment_proofs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_verifications`
--

DROP TABLE IF EXISTS `payment_verifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_verifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `proof_image` varchar(255) DEFAULT NULL,
  `verification_status` enum('pending','verified','rejected') NOT NULL DEFAULT 'pending',
  `rejection_reason` text DEFAULT NULL,
  `verified_by` int(11) DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `verified_by` (`verified_by`),
  KEY `idx_verification_status` (`verification_status`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_order_id` (`order_id`),
  CONSTRAINT `payment_verifications_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payment_verifications_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payment_verifications_ibfk_3` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_verifications`
--

LOCK TABLES `payment_verifications` WRITE;
/*!40000 ALTER TABLE `payment_verifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment_verifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_variants`
--

DROP TABLE IF EXISTS `product_variants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_variants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL COMMENT 'e.g., Small, Medium, Large',
  `price_adjustment` decimal(10,2) DEFAULT 0.00,
  `is_available` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_product` (`product_id`),
  CONSTRAINT `product_variants_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_variants`
--

LOCK TABLES `product_variants` WRITE;
/*!40000 ALTER TABLE `product_variants` DISABLE KEYS */;
INSERT INTO `product_variants` VALUES (24,30,'16 oz',10.00,1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(25,30,'22 oz',30.00,1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(26,31,'16 oz',5.00,1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(27,31,'22 oz',25.00,1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(28,32,'16 oz',10.00,1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(29,32,'22 oz',30.00,1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(30,33,'16 oz',10.00,1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(31,33,'22 oz',30.00,1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(32,34,'22 oz',20.00,1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(33,35,'22 oz',20.00,1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(34,36,'22 oz',20.00,1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(35,37,'22 oz',10.00,1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(36,38,'22 oz',10.00,1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(37,39,'22 oz',10.00,1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(38,40,'22 oz',10.00,1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(39,41,'22 oz',10.00,1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(40,42,'22 oz',10.00,1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(41,43,'22 oz',15.00,1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(42,44,'22 oz',15.00,1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(43,45,'22 oz',15.00,1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(44,46,'22 oz',20.00,1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(45,47,'22 oz',20.00,1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(46,48,'22 oz',20.00,1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21');
/*!40000 ALTER TABLE `product_variants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_available` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_category` (`category_id`),
  KEY `idx_available` (`is_available`),
  KEY `idx_name` (`name`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (30,1,'Americano','Classic Americano coffee available hot or iced',55.00,'americano.webp',1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(31,1,'Spanish Latte','Sweet Spanish latte with condensed milk',65.00,'spanish-latte.jpg',1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(32,1,'Matcha Espresso','Unique blend of matcha and espresso',60.00,'matcha-espresso.jpg',1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(33,1,'Caramel Macchiato','Espresso with vanilla syrup and caramel drizzle',65.00,'caramel-macchiato.jpg',1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(34,2,'Java Chip','Blended coffee with chocolate chips',89.00,'javaF.jpg',1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(35,2,'Butterscotch','Creamy butterscotch blended coffee',79.00,'bl.jpg',1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(36,2,'Almond Frappe','Nutty almond flavored blended coffee',79.00,'almondf.jpg',1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(37,3,'Okinawa','Traditional Okinawa milk tea',39.00,'okinawa.jpg',1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(38,3,'Wintermelon','Refreshing wintermelon milk tea',39.00,'wintermt.jpg',1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(39,3,'Salted Caramel','Sweet and salty caramel Milk tea',39.00,'saltedcaramel.png',1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(40,4,'Strawberry Tea','Fresh strawberry infused tea',45.00,'fruitteaa.jpg',1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(41,4,'Green Apple Tea','Crisp green apple flavored tea',45.00,'fruitteaa.jpg',1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(42,4,'Lychee Tea','Sweet lychee flavored tea',45.00,'fruitteaa.jpg',1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(43,5,'Strawberries and Cream','Creamy strawberry milk drink',65.00,'sc.jpg',1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(44,5,'Matcha Milk','Smooth matcha milk beverage',70.00,'noncoffe.jpg',1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(45,5,'Iced Chocolate','Rich chocolate cold drink',65.00,'ic.jpeg',1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(46,6,'Dark Choco','Intense dark chocolate blended drink',69.00,'dc.jpg',1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(47,6,'Caramel Frappe','Sweet caramel blended beverage',69.00,'cf.jpg',1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(48,6,'Strawberry Frappe','Fresh strawberry blended drink',69.00,'scc.jpg',1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(49,7,'Regular Fries','Classic golden french fries',50.00,'regularfries.jpg',1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(50,7,'Cheesy Fries','French fries topped with melted cheese',79.00,'cheesyfries.jpg',1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(51,7,'Nachos','Crispy nachos with toppings',89.00,'nachoos.jpg',1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(52,8,'Cheesy Hungarian','Hungarian sausage with cheesy rice bowl',89.00,'cheesyhungarian.jpg',1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(53,8,'Bacon & Cheese','Bacon and cheese rice bowl',89.00,'cheesyricebowl.jpg',1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(54,9,'Garlic Tuna Parmesan','Tuna pasta with garlic and parmesan',109.00,'garlicTparme.jpg',1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(55,9,'Meaty Spaghetti','Classic spaghetti with meat sauce',109.00,'meatyspag.jpg',1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(56,10,'Garlic Parmesan Chicken','2-piece chicken with garlic parmesan flavor and java rice',200.00,'garlicp.png',1,0,'2026-05-08 06:39:21','2026-05-08 16:46:54'),(57,10,'Teriyaki Chicken','2-piece chicken with teriyaki glaze and java rices\r\n',210.00,'product_69fe156cbf1388.38655816.jpg',1,0,'2026-05-08 06:39:21','2026-05-08 16:57:38'),(58,10,'Honey Butter Chicken','2-piece chicken with honey butter flavor and java rice',220.00,'honeybutter.jpg',1,0,'2026-05-08 06:39:21','2026-05-08 06:39:21'),(61,1,'test product','test',200.00,'product_69ff77bc8f8fe7.43110057.jpg',1,0,'2026-05-09 18:06:52','2026-05-09 18:10:49'),(62,4,'test product','test product only',2000.00,'product_6a02056a20a9f1.24282599.jpg',1,0,'2026-05-11 16:35:54','2026-05-11 16:36:02');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `refunds`
--

DROP TABLE IF EXISTS `refunds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `refunds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `refund_amount` decimal(10,2) NOT NULL,
  `refund_method` enum('gcash','cash','bank_transfer','store_credit') NOT NULL,
  `refund_notes` text DEFAULT NULL,
  `refund_status` enum('pending','processed','failed') DEFAULT 'pending',
  `processed_by` int(11) DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_order` (`order_id`),
  KEY `idx_status` (`refund_status`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `refunds`
--

LOCK TABLES `refunds` WRITE;
/*!40000 ALTER TABLE `refunds` DISABLE KEYS */;
INSERT INTO `refunds` VALUES (1,18,246.40,'gcash','Test refund - GCash cancellation','processed',1,'2026-05-09 17:22:53','2026-05-09 17:22:53','2026-05-09 17:22:53'),(2,19,67.20,'gcash','test','processed',1,'2026-05-09 17:23:59','2026-05-09 17:23:59','2026-05-09 17:23:59'),(3,24,4480.00,'gcash','','processed',1,'2026-05-11 16:39:53','2026-05-11 16:39:53','2026-05-11 16:39:53'),(4,24,4480.00,'gcash','test','processed',1,'2026-05-11 16:40:27','2026-05-11 16:40:27','2026-05-11 16:40:27');
/*!40000 ALTER TABLE `refunds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `role` enum('admin','staff','customer') DEFAULT 'customer',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_email` (`email`),
  KEY `idx_role` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin','User','admin@gmail.com','$2y$10$lTLb/Dgyyy35VRdw/mL7oul8sSZxaE3ldk/EMvAJAoua9Hs2Ek3MC',NULL,NULL,'admin','2026-05-09 02:24:22','2026-05-08 06:07:59','2026-05-09 02:24:22'),(3,'John','Doe','john.doe@example.com','$2y$10$l75DSflx6tJ6u2dUbE12j.t2yrKiiJd0vDvNgjOM9.V7dpcg.Sa3m','09123456789','123 Test Street','customer',NULL,'2026-05-09 02:47:52','2026-05-09 02:47:52'),(4,'Test','User','test@maccafe.com','$2y$10$xqwhY0fMScfWd5kBvvunzudQ8038ID.yikff7Gfs6TIp/ZKJHiU3K','09123456789','123 Test Street','customer','2026-05-09 02:56:12','2026-05-09 02:53:10','2026-05-09 02:56:12'),(17,'elie','santillan','tisuyongwapohon@gmail.com','$2y$10$VQ6PsgOVlMUvOQb2TnooPOl9PyN1wbkE9qfn4fUsRqJDFIARA/C6i','09708228108','test address','customer','2026-05-09 05:17:20','2026-05-09 05:17:08','2026-05-09 05:17:20'),(21,'test ','user','eliezersantillan648@gmail.com','$2y$10$LMx6RUfTvRdq/QWtEe0iJ.D9EwL1/Ka8IlPpTXTJS/32EdfmUSsRi','09780828108','test addres','customer','2026-05-11 16:31:48','2026-05-11 16:31:17','2026-05-11 16:31:48'),(22,'Admin','User','admin@cafecat.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',NULL,NULL,'admin',NULL,'2026-10-14 10:23:57','2026-10-14 10:23:57');
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

-- Dump completed on 2026-10-14 18:27:42
