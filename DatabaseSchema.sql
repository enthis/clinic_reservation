/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.7.2-MariaDB, for osx10.20 (arm64)
--
-- Host: localhost    Database: clinic_reservation
-- ------------------------------------------------------
-- Server version	11.7.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES
('laravel-cache-livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3','i:1;',1753602686),
('laravel-cache-livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3:timer','i:1753602686;',1753602686),
('laravel-cache-spatie.permission.cache','a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:69:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:8:\"viewUser\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:10:\"createUser\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:8:\"editUser\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:10:\"deleteUser\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:11:\"viewAnyUser\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:8:\"viewRole\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:10:\"createRole\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:8:\"editRole\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:10:\"deleteRole\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:11:\"viewAnyRole\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:14:\"viewPermission\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:16:\"createPermission\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:14:\"editPermission\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:16:\"deletePermission\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:17:\"viewAnyPermission\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:11:\"viewService\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:13:\"createService\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:11:\"editService\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:13:\"deleteService\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:14:\"viewAnyService\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:10:\"viewDoctor\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:12:\"createDoctor\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:10:\"editDoctor\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:12:\"deleteDoctor\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:13:\"viewAnyDoctor\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:18:\"viewDoctorSchedule\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:20:\"createDoctorSchedule\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:18:\"editDoctorSchedule\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:20:\"deleteDoctorSchedule\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:21:\"viewAnyDoctorSchedule\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:30;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:15:\"viewReservation\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;}}i:31;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:17:\"createReservation\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:32;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:15:\"editReservation\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:33;a:4:{s:1:\"a\";i:34;s:1:\"b\";s:17:\"deleteReservation\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:34;a:4:{s:1:\"a\";i:35;s:1:\"b\";s:18:\"viewAnyReservation\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:4;}}i:35;a:4:{s:1:\"a\";i:36;s:1:\"b\";s:20:\"viewPrescriptionItem\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:36;a:4:{s:1:\"a\";i:37;s:1:\"b\";s:22:\"createPrescriptionItem\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:37;a:4:{s:1:\"a\";i:38;s:1:\"b\";s:20:\"editPrescriptionItem\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:38;a:4:{s:1:\"a\";i:39;s:1:\"b\";s:22:\"deletePrescriptionItem\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:39;a:4:{s:1:\"a\";i:40;s:1:\"b\";s:23:\"viewAnyPrescriptionItem\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:40;a:4:{s:1:\"a\";i:41;s:1:\"b\";s:10:\"viewRecipe\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:41;a:4:{s:1:\"a\";i:42;s:1:\"b\";s:12:\"createRecipe\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:42;a:4:{s:1:\"a\";i:43;s:1:\"b\";s:10:\"editRecipe\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:43;a:4:{s:1:\"a\";i:44;s:1:\"b\";s:12:\"deleteRecipe\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:44;a:4:{s:1:\"a\";i:45;s:1:\"b\";s:13:\"viewAnyRecipe\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:45;a:4:{s:1:\"a\";i:46;s:1:\"b\";s:14:\"viewDoctorNote\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:4;}}i:46;a:4:{s:1:\"a\";i:47;s:1:\"b\";s:16:\"createDoctorNote\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:47;a:4:{s:1:\"a\";i:48;s:1:\"b\";s:14:\"editDoctorNote\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:48;a:4:{s:1:\"a\";i:49;s:1:\"b\";s:16:\"deleteDoctorNote\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:49;a:4:{s:1:\"a\";i:50;s:1:\"b\";s:17:\"viewAnyDoctorNote\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:4;}}i:50;a:4:{s:1:\"a\";i:51;s:1:\"b\";s:11:\"viewPayment\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:51;a:4:{s:1:\"a\";i:52;s:1:\"b\";s:13:\"createPayment\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:52;a:4:{s:1:\"a\";i:53;s:1:\"b\";s:11:\"editPayment\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:53;a:4:{s:1:\"a\";i:54;s:1:\"b\";s:13:\"deletePayment\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:54;a:4:{s:1:\"a\";i:55;s:1:\"b\";s:14:\"viewAnyPayment\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:55;a:4:{s:1:\"a\";i:56;s:1:\"b\";s:24:\"viewPaymentGatewayConfig\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:56;a:4:{s:1:\"a\";i:57;s:1:\"b\";s:26:\"createPaymentGatewayConfig\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:57;a:4:{s:1:\"a\";i:58;s:1:\"b\";s:24:\"editPaymentGatewayConfig\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:58;a:4:{s:1:\"a\";i:59;s:1:\"b\";s:26:\"deletePaymentGatewayConfig\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:59;a:4:{s:1:\"a\";i:60;s:1:\"b\";s:27:\"viewAnyPaymentGatewayConfig\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:60;a:4:{s:1:\"a\";i:61;s:1:\"b\";s:19:\"approveReservations\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:61;a:4:{s:1:\"a\";i:62;s:1:\"b\";s:20:\"completeReservations\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:62;a:4:{s:1:\"a\";i:63;s:1:\"b\";s:17:\"payForReservation\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:63;a:4:{s:1:\"a\";i:64;s:1:\"b\";s:19:\"viewOwnReservations\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:4;}}i:64;a:4:{s:1:\"a\";i:65;s:1:\"b\";s:14:\"viewOwnRecipes\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:4;}}i:65;a:4:{s:1:\"a\";i:66;s:1:\"b\";s:18:\"viewOwnDoctorNotes\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:4;}}i:66;a:4:{s:1:\"a\";i:67;s:1:\"b\";s:15:\"viewAllPatients\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:67;a:4:{s:1:\"a\";i:68;s:1:\"b\";s:11:\"inputRecipe\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:68;a:4:{s:1:\"a\";i:69;s:1:\"b\";s:15:\"inputDoctorNote\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}}s:5:\"roles\";a:4:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:5:\"staff\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:6:\"doctor\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:4;s:1:\"b\";s:4:\"user\";s:1:\"c\";s:3:\"web\";}}}',1753688469);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
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
-- Table structure for table `doctor_notes`
--

DROP TABLE IF EXISTS `doctor_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctor_notes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reservation_id` bigint(20) unsigned NOT NULL,
  `doctor_id` bigint(20) unsigned NOT NULL,
  `note_content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `doctor_notes_reservation_id_foreign` (`reservation_id`),
  KEY `doctor_notes_doctor_id_foreign` (`doctor_id`),
  CONSTRAINT `doctor_notes_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `doctor_notes_reservation_id_foreign` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctor_notes`
--

LOCK TABLES `doctor_notes` WRITE;
/*!40000 ALTER TABLE `doctor_notes` DISABLE KEYS */;
INSERT INTO `doctor_notes` VALUES
(1,1,1,'notes','2025-07-27 00:48:49','2025-07-27 00:48:49',NULL),
(2,1,1,'test','2025-07-27 00:48:59','2025-07-27 00:48:59',NULL),
(3,2,2,'test','2025-07-27 01:03:27','2025-07-27 01:03:27',NULL);
/*!40000 ALTER TABLE `doctor_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctor_schedules`
--

DROP TABLE IF EXISTS `doctor_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctor_schedules` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `doctor_id` bigint(20) unsigned NOT NULL,
  `day_of_week` tinyint(4) NOT NULL COMMENT '0 for Sunday, 1 for Monday, ..., 6 for Saturday',
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `notes` text DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `doctor_schedules_doctor_id_foreign` (`doctor_id`),
  CONSTRAINT `doctor_schedules_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctor_schedules`
--

LOCK TABLES `doctor_schedules` WRITE;
/*!40000 ALTER TABLE `doctor_schedules` DISABLE KEYS */;
INSERT INTO `doctor_schedules` VALUES
(1,1,0,'09:00:00','12:00:00','Morning shift on Sunday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(2,1,0,'14:00:00','17:00:00','Afternoon shift on Sunday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(3,1,1,'09:00:00','12:00:00','Morning shift on Monday',0,'2025-07-27 00:23:48','2025-07-27 00:27:26',NULL),
(4,1,1,'14:00:00','17:00:00','Afternoon shift on Monday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(5,1,2,'09:00:00','12:00:00','Morning shift on Tuesday',0,'2025-07-27 00:23:48','2025-07-27 00:24:13',NULL),
(6,1,2,'14:00:00','17:00:00','Afternoon shift on Tuesday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(7,1,3,'09:00:00','12:00:00','Morning shift on Wednesday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(8,1,3,'14:00:00','17:00:00','Afternoon shift on Wednesday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(9,1,4,'09:00:00','12:00:00','Morning shift on Thursday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(10,1,4,'14:00:00','17:00:00','Afternoon shift on Thursday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(11,1,5,'09:00:00','12:00:00','Morning shift on Friday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(12,1,5,'14:00:00','17:00:00','Afternoon shift on Friday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(13,1,6,'09:00:00','12:00:00','Morning shift on Saturday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(14,1,6,'14:00:00','17:00:00','Afternoon shift on Saturday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(15,2,0,'09:00:00','12:00:00','Morning shift on Sunday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(16,2,0,'14:00:00','17:00:00','Afternoon shift on Sunday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(17,2,1,'09:00:00','12:00:00','Morning shift on Monday',0,'2025-07-27 00:23:48','2025-07-27 00:27:06',NULL),
(18,2,1,'14:00:00','17:00:00','Afternoon shift on Monday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(19,2,2,'09:00:00','12:00:00','Morning shift on Tuesday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(20,2,2,'14:00:00','17:00:00','Afternoon shift on Tuesday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(21,2,3,'09:00:00','12:00:00','Morning shift on Wednesday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(22,2,3,'14:00:00','17:00:00','Afternoon shift on Wednesday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(23,2,4,'09:00:00','12:00:00','Morning shift on Thursday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(24,2,4,'14:00:00','17:00:00','Afternoon shift on Thursday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(25,2,5,'09:00:00','12:00:00','Morning shift on Friday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(26,2,5,'14:00:00','17:00:00','Afternoon shift on Friday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(27,2,6,'09:00:00','12:00:00','Morning shift on Saturday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(28,2,6,'14:00:00','17:00:00','Afternoon shift on Saturday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(29,3,0,'09:00:00','12:00:00','Morning shift on Sunday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(30,3,0,'14:00:00','17:00:00','Afternoon shift on Sunday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(31,3,1,'09:00:00','12:00:00','Morning shift on Monday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(32,3,1,'14:00:00','17:00:00','Afternoon shift on Monday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(33,3,2,'09:00:00','12:00:00','Morning shift on Tuesday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(34,3,2,'14:00:00','17:00:00','Afternoon shift on Tuesday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(35,3,3,'09:00:00','12:00:00','Morning shift on Wednesday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(36,3,3,'14:00:00','17:00:00','Afternoon shift on Wednesday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(37,3,4,'09:00:00','12:00:00','Morning shift on Thursday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(38,3,4,'14:00:00','17:00:00','Afternoon shift on Thursday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(39,3,5,'09:00:00','12:00:00','Morning shift on Friday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(40,3,5,'14:00:00','17:00:00','Afternoon shift on Friday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(41,3,6,'09:00:00','12:00:00','Morning shift on Saturday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(42,3,6,'14:00:00','17:00:00','Afternoon shift on Saturday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(43,4,0,'09:00:00','12:00:00','Morning shift on Sunday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(44,4,0,'14:00:00','17:00:00','Afternoon shift on Sunday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(45,4,1,'09:00:00','12:00:00','Morning shift on Monday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(46,4,1,'14:00:00','17:00:00','Afternoon shift on Monday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(47,4,2,'09:00:00','12:00:00','Morning shift on Tuesday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(48,4,2,'14:00:00','17:00:00','Afternoon shift on Tuesday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(49,4,3,'09:00:00','12:00:00','Morning shift on Wednesday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(50,4,3,'14:00:00','17:00:00','Afternoon shift on Wednesday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(51,4,4,'09:00:00','12:00:00','Morning shift on Thursday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(52,4,4,'14:00:00','17:00:00','Afternoon shift on Thursday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(53,4,5,'09:00:00','12:00:00','Morning shift on Friday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(54,4,5,'14:00:00','17:00:00','Afternoon shift on Friday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(55,4,6,'09:00:00','12:00:00','Morning shift on Saturday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(56,4,6,'14:00:00','17:00:00','Afternoon shift on Saturday',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL);
/*!40000 ALTER TABLE `doctor_schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctors`
--

DROP TABLE IF EXISTS `doctors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `specialty` varchar(255) NOT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `doctors_user_id_foreign` (`user_id`),
  CONSTRAINT `doctors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctors`
--

LOCK TABLES `doctors` WRITE;
/*!40000 ALTER TABLE `doctors` DISABLE KEYS */;
INSERT INTO `doctors` VALUES
(1,4,'Dr. Alice Smith','General Practitioner','081234567890','2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(2,5,'Dr. Bob Johnson','Pediatrician','081298765432','2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(3,NULL,'Dr. Carol White','Dentist','081311223344','2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(4,NULL,'Dr. David Brown','Dermatologist','081555667788','2025-07-27 00:23:48','2025-07-27 00:23:48',NULL);
/*!40000 ALTER TABLE `doctors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
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
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
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
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
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
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2025_07_18_235937_add_google_id_role_and_soft_deletes_to_users_table',1),
(5,'2025_07_19_000027_create_services_table',1),
(6,'2025_07_19_000100_create_doctors_table',1),
(7,'2025_07_19_000128_create_doctor_schedules_table',1),
(8,'2025_07_19_000150_create_reservations_table',1),
(9,'2025_07_19_000209_create_prescription_items_table',1),
(10,'2025_07_19_000227_create_recipes_table',1),
(11,'2025_07_19_000243_create_doctor_notes_table',1),
(12,'2025_07_19_011156_create_payments_table',1),
(13,'2025_07_19_011254_remove_xendit_invoice_id_from_reservations_table',1),
(14,'2025_07_19_011524_create_payment_gateway_configs_table',1),
(15,'2025_07_19_013200_add_price_to_prescription_items_table',1),
(16,'2025_07_19_014247_create_permission_tables',1),
(17,'2025_07_19_022259_update_doctor_schedules_for_day_and_notes_table',1),
(18,'2025_07_19_025327_create_personal_access_tokens_table',1),
(19,'2025_07_27_055059_add_frequency_to_recipes_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
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
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
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
INSERT INTO `model_has_roles` VALUES
(1,'App\\Models\\User',1),
(1,'App\\Models\\User',2),
(2,'App\\Models\\User',3),
(3,'App\\Models\\User',4),
(3,'App\\Models\\User',5),
(4,'App\\Models\\User',6);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
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
-- Table structure for table `payment_gateway_configs`
--

DROP TABLE IF EXISTS `payment_gateway_configs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_gateway_configs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `gateway_name` varchar(255) NOT NULL COMMENT 'e.g., midtrans, qris_provider_a, xendit',
  `mode` varchar(255) NOT NULL DEFAULT 'sandbox' COMMENT 'e.g., sandbox, production',
  `config_key` varchar(255) NOT NULL COMMENT 'e.g., client_key, server_key, merchant_id, callback_url',
  `config_value` text NOT NULL COMMENT 'The actual configuration value (can be encrypted)',
  `is_encrypted` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Indicates if config_value is encrypted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_gateway_config` (`gateway_name`,`mode`,`config_key`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_gateway_configs`
--

LOCK TABLES `payment_gateway_configs` WRITE;
/*!40000 ALTER TABLE `payment_gateway_configs` DISABLE KEYS */;
INSERT INTO `payment_gateway_configs` VALUES
(1,'midtrans','sandbox','server_key','eyJpdiI6Ii8zOFpaMktVNERwdXJFUU44a040Unc9PSIsInZhbHVlIjoiMVh6S0xkRmo5cGFiQ2xqdUNXb054ZVV2NXVqa2d1QTUxWDZKR3ErZUpZbkZKWlQvdmtzWi85MHlqUUZzR2NQMSIsIm1hYyI6IjhmNDViN2Q5OWQyODg4ZmU0MDM1MDBjM2FmODhmZTZjMzNmYWU1MDExNmE1MmY3NzY2OWI1Y2ZlNmU0MjRlNzMiLCJ0YWciOiIifQ==',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(2,'midtrans','sandbox','client_key','SB-Mid-client-KEnuu62UKFDhFolJ',0,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(3,'midtrans','sandbox','merchant_id','G875131585',0,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(4,'midtrans','sandbox','callback_url','http://localhost/api/midtrans/callback',0,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(5,'midtrans','production','server_key','eyJpdiI6IjFWWjUzWmRvMzk4TS9Fb1Q4NzBqVXc9PSIsInZhbHVlIjoiMTRoZ2JFTmt5NlozVGREaVpjTzEzcmN6Ujh6K2pCYlM1eVZldnV2aUxqMU5uK25CZnY1K2lVclpTVWNUaW4yVyIsIm1hYyI6IjAyY2RiMzVkOWZhMTdiYmQ2MmY4ZGY1MGRmZDkxZDA4ODZmODE1NDVjNTVhZTc2ZTI0YWQzOTc1YzhjNTZmYzAiLCJ0YWciOiIifQ==',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(6,'midtrans','production','client_key','Mid-client-YOUR_PRODUCTION_CLIENT_KEY',0,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(7,'midtrans','production','merchant_id','YOUR_PRODUCTION_MERCHANT_ID',0,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(8,'midtrans','production','callback_url','http://localhost/api/midtrans/callback',0,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(9,'qris_provider_a','production','api_key','eyJpdiI6InRQSFZtMTRJTmFVcXJRQ3ZnSEQvbnc9PSIsInZhbHVlIjoidis5dWtkUnYyMWovOTJRSlRnRWgvc09GNG9YdUNiNGs0R1VRVEQ5TTE1QT0iLCJtYWMiOiIzOGMyNDVhMGRmOTAzZDU2ODcyNjg4YWNiZDcwZWM4ODFlMTBlZTk0ZjEyMjFkYmUwNzk2NDkzMDFlODFlZDBlIiwidGFnIjoiIn0=',1,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(10,'qris_provider_a','production','merchant_code','MERCHANT-QRS-A',0,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL);
/*!40000 ALTER TABLE `payment_gateway_configs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reservation_id` bigint(20) unsigned NOT NULL,
  `gateway_transaction_id` varchar(255) DEFAULT NULL COMMENT 'Transaction ID from the payment gateway (e.g., Midtrans ID, QRIS reference)',
  `order_id` varchar(255) NOT NULL COMMENT 'Your internal order ID (can be same as reservation_id or a unique string)',
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(255) NOT NULL DEFAULT 'IDR',
  `payment_gateway` varchar(255) NOT NULL COMMENT 'e.g., midtrans, cashier, qris_provider_name',
  `payment_method` varchar(255) DEFAULT NULL COMMENT 'e.g., credit_card, bank_transfer (Midtrans); cash, card (Cashier); gopay, ovo (QRIS)',
  `transaction_status` varchar(255) NOT NULL COMMENT 'Status of the transaction: pending, capture, settlement, deny, expire, cancel, refund, paid, failed',
  `transaction_time` timestamp NULL DEFAULT NULL,
  `raw_response` text DEFAULT NULL COMMENT 'Full JSON response from payment gateway for debugging/auditing',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_order_id_unique` (`order_id`),
  UNIQUE KEY `payments_gateway_transaction_id_unique` (`gateway_transaction_id`),
  KEY `payments_reservation_id_foreign` (`reservation_id`),
  CONSTRAINT `payments_reservation_id_foreign` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES
(1,1,'fef10142-b93d-40be-bc8d-0231ea60f0b4','RES-1-1753601077',150000.00,'IDR','midtrans','bank_transfer','settlement','2025-07-27 07:24:40','{\"status_code\":\"200\",\"transaction_id\":\"fef10142-b93d-40be-bc8d-0231ea60f0b4\",\"gross_amount\":\"150000.00\",\"currency\":\"IDR\",\"order_id\":\"RES-1-1753601077\",\"payment_type\":\"bank_transfer\",\"signature_key\":\"902af742a1dec3000b1482fe869f3898fa78a54f196bb211277c98f9fbaddf38a276292ad4dd7a5ad44626b781b58649d4e5b043d33d828ef419aba2f0ede08f\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G875131585\",\"va_numbers\":[{\"bank\":\"bca\",\"va_number\":\"31585398038353778092885\"}],\"payment_amounts\":[],\"transaction_time\":\"2025-07-27 14:24:40\",\"settlement_time\":\"2025-07-27 14:24:48\",\"expiry_time\":\"2025-07-28 14:24:40\"}','2025-07-27 00:24:37','2025-07-27 00:24:54',NULL),
(2,2,NULL,'RES-2-1753601226',200000.00,'IDR','midtrans',NULL,'pending',NULL,NULL,'2025-07-27 00:27:06','2025-07-27 00:27:06',NULL),
(3,3,NULL,'RES-3-1753601246',150000.00,'IDR','midtrans',NULL,'pending',NULL,NULL,'2025-07-27 00:27:27','2025-07-27 00:27:27',NULL);
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES
(1,'viewUser','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(2,'createUser','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(3,'editUser','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(4,'deleteUser','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(5,'viewAnyUser','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(6,'viewRole','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(7,'createRole','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(8,'editRole','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(9,'deleteRole','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(10,'viewAnyRole','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(11,'viewPermission','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(12,'createPermission','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(13,'editPermission','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(14,'deletePermission','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(15,'viewAnyPermission','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(16,'viewService','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(17,'createService','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(18,'editService','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(19,'deleteService','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(20,'viewAnyService','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(21,'viewDoctor','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(22,'createDoctor','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(23,'editDoctor','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(24,'deleteDoctor','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(25,'viewAnyDoctor','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(26,'viewDoctorSchedule','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(27,'createDoctorSchedule','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(28,'editDoctorSchedule','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(29,'deleteDoctorSchedule','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(30,'viewAnyDoctorSchedule','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(31,'viewReservation','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(32,'createReservation','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(33,'editReservation','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(34,'deleteReservation','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(35,'viewAnyReservation','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(36,'viewPrescriptionItem','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(37,'createPrescriptionItem','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(38,'editPrescriptionItem','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(39,'deletePrescriptionItem','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(40,'viewAnyPrescriptionItem','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(41,'viewRecipe','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(42,'createRecipe','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(43,'editRecipe','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(44,'deleteRecipe','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(45,'viewAnyRecipe','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(46,'viewDoctorNote','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(47,'createDoctorNote','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(48,'editDoctorNote','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(49,'deleteDoctorNote','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(50,'viewAnyDoctorNote','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(51,'viewPayment','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(52,'createPayment','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(53,'editPayment','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(54,'deletePayment','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(55,'viewAnyPayment','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(56,'viewPaymentGatewayConfig','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(57,'createPaymentGatewayConfig','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(58,'editPaymentGatewayConfig','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(59,'deletePaymentGatewayConfig','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(60,'viewAnyPaymentGatewayConfig','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(61,'approveReservations','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(62,'completeReservations','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(63,'payForReservation','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(64,'viewOwnReservations','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(65,'viewOwnRecipes','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(66,'viewOwnDoctorNotes','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(67,'viewAllPatients','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(68,'inputRecipe','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(69,'inputDoctorNote','web','2025-07-27 00:23:45','2025-07-27 00:23:45');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES
(1,'App\\Models\\User',2,'google-login-token','6c7adfc89ccf5bd78e838be66287ee97811974b4891cbc6af08863c241bdaf3c','[\"*\"]','2025-07-27 00:24:54',NULL,'2025-07-27 00:24:30','2025-07-27 00:24:54'),
(2,'App\\Models\\User',4,'impersonation-token','b6166f0fea99fa23afc57783aae351613471a54fc1c2bdd47e24014d58567c53','[\"*\"]','2025-07-27 00:27:26',NULL,'2025-07-27 00:25:13','2025-07-27 00:27:26'),
(3,'App\\Models\\User',2,'google-login-token','8b55cf7f29353e3b0f2a4f71030de98731f90c6294e34d5bf77c1fa2a5378708','[\"*\"]',NULL,NULL,'2025-07-27 00:50:43','2025-07-27 00:50:43'),
(4,'App\\Models\\User',2,'google-login-token','e8bb0c0be71aecb195c0115129388873f24edecccdda88d58ea1f87acf26774c','[\"*\"]',NULL,NULL,'2025-07-27 00:57:35','2025-07-27 00:57:35'),
(5,'App\\Models\\User',2,'google-login-token','995fd2240b5bc862588c7db8b10557cb3b51214ff33c17db97571c7dd5d559d0','[\"*\"]',NULL,NULL,'2025-07-27 00:58:15','2025-07-27 00:58:15');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prescription_items`
--

DROP TABLE IF EXISTS `prescription_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `prescription_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `prescription_items_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prescription_items`
--

LOCK TABLES `prescription_items` WRITE;
/*!40000 ALTER TABLE `prescription_items` DISABLE KEYS */;
INSERT INTO `prescription_items` VALUES
(1,'Paracetamol 500mg','Pain reliever and fever reducer.',15000.00,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(2,'Amoxicillin 500mg','Antibiotic.',25000.00,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(3,'Ibuprofen 400mg','NSAID for pain and inflammation.',18000.00,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(4,'Vitamin C 1000mg','Immune support.',30000.00,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(5,'Antacid','For heartburn and indigestion.',12000.00,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(6,'Cough Syrup','For cough relief.',22000.00,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL);
/*!40000 ALTER TABLE `prescription_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recipes`
--

DROP TABLE IF EXISTS `recipes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `recipes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reservation_id` bigint(20) unsigned NOT NULL,
  `prescription_item_id` bigint(20) unsigned NOT NULL,
  `dose` varchar(255) NOT NULL,
  `frequency_to_consume` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `recipes_reservation_id_foreign` (`reservation_id`),
  KEY `recipes_prescription_item_id_foreign` (`prescription_item_id`),
  CONSTRAINT `recipes_prescription_item_id_foreign` FOREIGN KEY (`prescription_item_id`) REFERENCES `prescription_items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `recipes_reservation_id_foreign` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recipes`
--

LOCK TABLES `recipes` WRITE;
/*!40000 ALTER TABLE `recipes` DISABLE KEYS */;
INSERT INTO `recipes` VALUES
(1,1,2,'1','1 time a day',NULL,'2025-07-27 00:41:20','2025-07-27 00:41:20',NULL),
(2,1,5,'1','2 times a day',NULL,'2025-07-27 00:49:10','2025-07-27 00:49:10',NULL);
/*!40000 ALTER TABLE `recipes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `reservations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `doctor_id` bigint(20) unsigned NOT NULL,
  `service_id` bigint(20) unsigned NOT NULL,
  `schedule_id` bigint(20) unsigned NOT NULL,
  `scheduled_date` date NOT NULL,
  `scheduled_time` time NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `payment_status` varchar(255) NOT NULL DEFAULT 'pending',
  `payment_amount` decimal(8,2) NOT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `completed_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reservations_user_id_foreign` (`user_id`),
  KEY `reservations_doctor_id_foreign` (`doctor_id`),
  KEY `reservations_service_id_foreign` (`service_id`),
  KEY `reservations_schedule_id_foreign` (`schedule_id`),
  KEY `reservations_approved_by_foreign` (`approved_by`),
  KEY `reservations_completed_by_foreign` (`completed_by`),
  CONSTRAINT `reservations_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `reservations_completed_by_foreign` FOREIGN KEY (`completed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `reservations_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reservations_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `doctor_schedules` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reservations_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reservations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservations`
--

LOCK TABLES `reservations` WRITE;
/*!40000 ALTER TABLE `reservations` DISABLE KEYS */;
INSERT INTO `reservations` VALUES
(1,2,1,4,5,'2025-07-29','09:00:00','pending','paid',150000.00,NULL,NULL,'2025-07-27 00:24:13','2025-07-27 00:33:28',NULL),
(2,4,2,2,17,'2025-07-28','09:00:00','pending','pending',200000.00,NULL,NULL,'2025-07-27 00:27:06','2025-07-27 00:27:06',NULL),
(3,4,1,1,3,'2025-07-28','09:00:00','pending','pending',150000.00,NULL,NULL,'2025-07-27 00:27:26','2025-07-27 00:27:26',NULL);
/*!40000 ALTER TABLE `reservations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
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
INSERT INTO `role_has_permissions` VALUES
(1,1),
(2,1),
(3,1),
(4,1),
(5,1),
(6,1),
(7,1),
(8,1),
(9,1),
(10,1),
(11,1),
(12,1),
(13,1),
(14,1),
(15,1),
(16,1),
(17,1),
(18,1),
(19,1),
(20,1),
(21,1),
(22,1),
(23,1),
(24,1),
(25,1),
(26,1),
(27,1),
(28,1),
(29,1),
(30,1),
(31,1),
(32,1),
(33,1),
(34,1),
(35,1),
(36,1),
(37,1),
(38,1),
(39,1),
(40,1),
(41,1),
(42,1),
(43,1),
(44,1),
(45,1),
(46,1),
(47,1),
(48,1),
(49,1),
(50,1),
(51,1),
(52,1),
(53,1),
(54,1),
(55,1),
(56,1),
(57,1),
(58,1),
(59,1),
(60,1),
(61,1),
(62,1),
(63,1),
(64,1),
(65,1),
(66,1),
(67,1),
(68,1),
(69,1),
(16,2),
(17,2),
(18,2),
(19,2),
(20,2),
(21,2),
(22,2),
(23,2),
(24,2),
(25,2),
(26,2),
(27,2),
(28,2),
(29,2),
(30,2),
(31,2),
(33,2),
(35,2),
(46,2),
(50,2),
(51,2),
(55,2),
(61,2),
(62,2),
(31,3),
(42,3),
(47,3),
(64,3),
(65,3),
(66,3),
(68,3),
(69,3),
(31,4),
(32,4),
(35,4),
(41,4),
(45,4),
(46,4),
(50,4),
(63,4),
(64,4),
(65,4),
(66,4);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES
(1,'admin','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(2,'staff','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(3,'doctor','web','2025-07-27 00:23:45','2025-07-27 00:23:45'),
(4,'user','web','2025-07-27 00:23:45','2025-07-27 00:23:45');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `services` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `services_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES
(1,'General Consultation','A basic check-up and consultation with a general practitioner.',150000.00,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(2,'Dental Check-up','Comprehensive dental examination and cleaning.',200000.00,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(3,'Pediatric Consultation','Consultation for children with a pediatrician.',180000.00,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(4,'Dermatology Consultation','Consultation for skin conditions.',250000.00,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL),
(5,'Nutrition Counseling','Personalized advice on diet and nutrition.',120000.00,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL);
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
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
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_google_id_unique` (`google_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'Admin User','user','admin@example.com','2025-07-27 00:23:46','$2y$12$q5VmcLwoPi/0fW1TTGHmCOD.C1dy2tpKWDnaFf1z.iSUOBsTtVeI6',NULL,NULL,'2025-07-27 00:23:46','2025-07-27 00:23:46',NULL),
(2,'Nharits Admin','user','nharits74@gmail.com','2025-07-27 00:23:46','$2y$12$M2LgH.uAcwwirp4wrpsOV.we0QiIdeCNyWnRX89SUkVta3rPeCGKu','117018621703663551770',NULL,'2025-07-27 00:23:46','2025-07-27 00:24:30',NULL),
(3,'Staff User','user','staff@example.com','2025-07-27 00:23:47','$2y$12$dK72hUQFqPTH1Jtmie0.6ONf4xaaNah39kgrVzeEEeF3M07sNOapC',NULL,NULL,'2025-07-27 00:23:47','2025-07-27 00:23:47',NULL),
(4,'Dr. Alice Smith','user','doctor1@example.com','2025-07-27 00:23:47','$2y$12$IWpbxyYFnASJZYYZ6oMLSOlnkem1v1lwWpkL/i/ptLy37J97N7vR6',NULL,NULL,'2025-07-27 00:23:47','2025-07-27 00:50:11',NULL),
(5,'Dr. Bob Johnson','user','doctor2@example.com','2025-07-27 00:23:47','$2y$12$o1fan0FH0Y/dwuzt6tQ8FufVIoc6QJvVENDG0aHMU6lpa0crb9XmC',NULL,NULL,'2025-07-27 00:23:47','2025-07-27 00:23:47',NULL),
(6,'Regular User','user','user@example.com','2025-07-27 00:23:48','$2y$12$ey2pPot3e72RUjzrRgjUAuM6iqQ3HX1w.nwgErEIKB0ok6/T/oG1O',NULL,NULL,'2025-07-27 00:23:48','2025-07-27 00:23:48',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'clinic_reservation'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2025-07-27 15:14:03
