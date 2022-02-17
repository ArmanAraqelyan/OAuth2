-- MySQL dump 10.13  Distrib 8.0.28, for Linux (x86_64)
--
-- Host: localhost    Database: oauth2
-- ------------------------------------------------------
-- Server version	8.0.28

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
-- Table structure for table `auth_code`
--

DROP TABLE IF EXISTS `auth_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_code` (
  `id` int NOT NULL AUTO_INCREMENT,
  `identifier` varchar(255) NOT NULL,
  `user_id` int NOT NULL,
  `client_id` int NOT NULL,
  `scope` text NOT NULL,
  `expiry_time` int NOT NULL,
  `revoked` tinyint(1) NOT NULL DEFAULT '0',
  `revoke_date` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `identifier` (`identifier`),
  KEY `user_id` (`user_id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `auth_code_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `auth_code_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_code`
--

LOCK TABLES `auth_code` WRITE;
/*!40000 ALTER TABLE `auth_code` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_code` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `uri` text NOT NULL,
  `secret` varchar(255) NOT NULL,
  `confidential` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client`
--

LOCK TABLES `client` WRITE;
/*!40000 ALTER TABLE `client` DISABLE KEYS */;
INSERT INTO `client` VALUES (1,'VueApp','["http://localhost:9090/oauth"]','$2y$10$WHGn6cEcHgSgocOUldd3AOIiotg.TIcWp0MNP7ckYJqvYSVguuATG',1);
/*!40000 ALTER TABLE `client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `refresh_token`
--

DROP TABLE IF EXISTS `refresh_token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `refresh_token` (
  `id` int NOT NULL AUTO_INCREMENT,
  `identifier` varchar(255) NOT NULL,
  `access_token` varchar(255) NOT NULL,
  `expiry_time` int NOT NULL,
  `revoked` tinyint(1) NOT NULL DEFAULT '0',
  `revoke_date` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `identifier` (`identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `refresh_token`
--

LOCK TABLES `refresh_token` WRITE;
/*!40000 ALTER TABLE `refresh_token` DISABLE KEYS */;
/*!40000 ALTER TABLE `refresh_token` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scope`
--

DROP TABLE IF EXISTS `scope`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `scope` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scope`
--

LOCK TABLES `scope` WRITE;
/*!40000 ALTER TABLE `scope` DISABLE KEYS */;
INSERT INTO `scope` VALUES (1,'all','Access to all the data');
/*!40000 ALTER TABLE `scope` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `token`
--

DROP TABLE IF EXISTS `token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `token` (
  `id` int NOT NULL AUTO_INCREMENT,
  `identifier` varchar(255) NOT NULL,
  `user_id` int NOT NULL,
  `client_id` int NOT NULL,
  `scope` text NOT NULL,
  `expiry_time` int NOT NULL,
  `revoked` tinyint(1) NOT NULL DEFAULT '0',
  `revoke_date` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `identifier` (`identifier`),
  KEY `user_id` (`user_id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `token_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `token_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `token`
--

LOCK TABLES `token` WRITE;
/*!40000 ALTER TABLE `token` DISABLE KEYS */;
/*!40000 ALTER TABLE `token` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `reg_date` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `mail` (`mail`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-02-17 15:02:52
