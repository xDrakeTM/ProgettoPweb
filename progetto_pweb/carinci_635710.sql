-- Progettazione Web 
DROP DATABASE if exists carinci_635710; 
CREATE DATABASE carinci_635710; 
USE carinci_635710; 
-- MySQL dump 10.13  Distrib 5.7.28, for Win64 (x86_64)
--
-- Host: localhost    Database: carinci_635710
-- ------------------------------------------------------
-- Server version	5.7.28

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
-- Table structure for table `accessi`
--

DROP TABLE IF EXISTS `accessi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accessi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `pt_id` int(11) DEFAULT NULL,
  `user_tipo` enum('utente','personal_trainer') NOT NULL,
  `timestamp_accesso` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `pt_id` (`pt_id`),
  CONSTRAINT `accessi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `utente` (`id`) ON DELETE CASCADE,
  CONSTRAINT `accessi_ibfk_2` FOREIGN KEY (`pt_id`) REFERENCES `personal_trainer` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accessi`
--

LOCK TABLES `accessi` WRITE;
/*!40000 ALTER TABLE `accessi` DISABLE KEYS */;
INSERT INTO `accessi` VALUES (19,NULL,6,'personal_trainer','2024-12-23 11:45:33'),(20,18,NULL,'utente','2024-12-23 11:46:16'),(21,18,NULL,'utente','2024-12-23 11:53:34'),(22,NULL,6,'personal_trainer','2024-12-23 11:58:13'),(23,18,NULL,'utente','2024-12-23 12:33:13'),(24,NULL,6,'personal_trainer','2024-12-23 12:34:28'),(25,18,NULL,'utente','2024-12-23 12:34:50'),(26,NULL,6,'personal_trainer','2024-12-23 12:35:15'),(27,18,NULL,'utente','2024-12-23 12:38:59'),(28,NULL,6,'personal_trainer','2024-12-23 12:40:54'),(29,NULL,6,'personal_trainer','2024-12-23 12:47:46'),(30,18,NULL,'utente','2024-12-23 12:48:44'),(31,NULL,6,'personal_trainer','2024-12-23 13:05:52'),(32,NULL,6,'personal_trainer','2024-12-23 13:09:05'),(33,18,NULL,'utente','2024-12-23 13:19:31'),(34,18,NULL,'utente','2024-12-23 19:27:45'),(35,NULL,6,'personal_trainer','2024-12-23 19:29:28'),(36,18,NULL,'utente','2024-12-23 19:30:58'),(37,NULL,6,'personal_trainer','2024-12-23 19:31:32'),(38,18,NULL,'utente','2024-12-23 19:32:09'),(39,18,NULL,'utente','2024-12-23 20:10:49');
/*!40000 ALTER TABLE `accessi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `timestamp_login` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'admin@pulsecoach.it','$2y$10$EdtBs5VaRh37Q/hGeWUtNuQlXpS7Jh1ex3.5fH4zBdjKaQLKhnqVW','Admin1','2024-12-23 20:10:59');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `appuntamento`
--

DROP TABLE IF EXISTS `appuntamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `appuntamento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utente_id` int(11) DEFAULT NULL,
  `personal_trainer_id` int(11) DEFAULT NULL,
  `data` date NOT NULL,
  `ora` time NOT NULL,
  `stato` enum('prenotato','confermato','cancellato') DEFAULT 'prenotato',
  PRIMARY KEY (`id`),
  KEY `utente_id` (`utente_id`),
  KEY `personal_trainer_id` (`personal_trainer_id`),
  CONSTRAINT `appuntamento_ibfk_1` FOREIGN KEY (`utente_id`) REFERENCES `utente` (`id`) ON DELETE CASCADE,
  CONSTRAINT `appuntamento_ibfk_2` FOREIGN KEY (`personal_trainer_id`) REFERENCES `personal_trainer` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appuntamento`
--

LOCK TABLES `appuntamento` WRITE;
/*!40000 ALTER TABLE `appuntamento` DISABLE KEYS */;
INSERT INTO `appuntamento` VALUES (11,18,6,'2024-12-23','14:00:00','confermato');
/*!40000 ALTER TABLE `appuntamento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `obiettivi`
--

DROP TABLE IF EXISTS `obiettivi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `obiettivi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `appuntamento_id` int(11) NOT NULL,
  `tipo_obiettivo` varchar(50) NOT NULL,
  `obiettivo` varchar(255) NOT NULL,
  `descrizione` varchar(255) NOT NULL,
  `ripetizioni` int(11) DEFAULT NULL,
  `serie` int(11) DEFAULT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `progresso1` int(11) DEFAULT NULL,
  `progresso2` int(11) DEFAULT NULL,
  `progresso3` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `appuntamento_id` (`appuntamento_id`),
  CONSTRAINT `obiettivi_ibfk_1` FOREIGN KEY (`appuntamento_id`) REFERENCES `appuntamento` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `obiettivi`
--

LOCK TABLES `obiettivi` WRITE;
/*!40000 ALTER TABLE `obiettivi` DISABLE KEYS */;
INSERT INTO `obiettivi` VALUES (31,11,'quantitativo','Panca Piana','Usare il bilanciere o in alternativa fare la Chest Press',8,3,35.00,6,2,30.00),(33,11,'continuativo','Bere 2 litri d\'acqua al giorno per 3 giorni','Segnare quanti giorni si è rispettato questo obiettivo giornaliero',NULL,NULL,NULL,3,3,-1.00);
/*!40000 ALTER TABLE `obiettivi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_trainer`
--

DROP TABLE IF EXISTS `personal_trainer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_trainer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `cognome` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `data_nascita` date NOT NULL,
  `genere` char(1) NOT NULL,
  `cellulare` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `curriculum` varchar(255) NOT NULL,
  `timestamp_login` datetime DEFAULT NULL,
  `timestamp_logout` datetime DEFAULT NULL,
  `timestamp_creazione` datetime DEFAULT CURRENT_TIMESTAMP,
  `timestamp_aggiornamento` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `risposta1` varchar(255) NOT NULL,
  `risposta2` varchar(255) NOT NULL,
  `attivo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_trainer`
--

LOCK TABLES `personal_trainer` WRITE;
/*!40000 ALTER TABLE `personal_trainer` DISABLE KEYS */;
INSERT INTO `personal_trainer` VALUES (6,'Luigi','Verdi','luigi.verdi@gmail.com','2000-12-10','M','1231231234','$2y$10$Dg/L2SNsbWKP9p5s8AFwyurympzyRJHR/84dld/0n34M6yYcs.aZC','676aa15c3edf7_1735041377.pdf','2024-12-23 19:31:32','2024-12-23 19:31:59','2024-12-23 11:44:27','2024-12-23 20:10:10','$2y$10$ungBO5NziEtNSx9UPyTBmO0EgMVNwIFBUNjtKQsKtu53MNz4OfePa','$2y$10$0/WLVAtGkMInCLDB8JAPL.TSVsLJfTwXA4fBMBTRfyudrZe/ppooi',1);
/*!40000 ALTER TABLE `personal_trainer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utente`
--

DROP TABLE IF EXISTS `utente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `utente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `cognome` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `data_nascita` date DEFAULT NULL,
  `genere` char(1) DEFAULT NULL,
  `altezza` int(11) DEFAULT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `informazioni_mediche` varchar(255) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `risposta1` varchar(255) NOT NULL,
  `risposta2` varchar(255) NOT NULL,
  `certificato` varchar(255) NOT NULL,
  `data_emissione_certificato` date NOT NULL,
  `timestamp_login` datetime DEFAULT NULL,
  `timestamp_logout` datetime DEFAULT NULL,
  `timestamp_creazione` datetime DEFAULT CURRENT_TIMESTAMP,
  `timestamp_aggiornamento` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utente`
--

LOCK TABLES `utente` WRITE;
/*!40000 ALTER TABLE `utente` DISABLE KEYS */;
INSERT INTO `utente` VALUES (18,'Mario','Rossi','mario.rossi@gmail.com','$2y$10$66.MVMKJz/LLeGPsHp89LOTzkVQD1MlSDzhZdUyZsEsHxVvMUwxpS','2002-10-20','M',183,75.00,'','','$2y$10$yeffto2JrSbMabF.aCtuIO6NGKti4S0wquZM9dSUO9q10nmEgQPi.','$2y$10$yeffto2JrSbMabF.aCtuIO6NGKti4S0wquZM9dSUO9q10nmEgQPi.','676aa15c3edf7_1735041372.pdf','2024-01-20','2024-12-23 20:10:49','2024-12-23 20:10:51','2024-12-23 11:41:04','2024-12-23 20:10:51');
/*!40000 ALTER TABLE `utente` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-12-23 20:12:35
