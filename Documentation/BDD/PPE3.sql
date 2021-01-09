-- MySQL dump 10.17  Distrib 10.3.25-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: PPE3
-- ------------------------------------------------------
-- Server version	10.3.25-MariaDB-0ubuntu0.20.04.1

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
-- Current Database: `PPE3`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `PPE3` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `PPE3`;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nomCategorie` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descriptionCategorie` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Utilisateur','Toute personne ayant uniquement accès à la base des fournitures pour établir sa liste.'),(2,'Valideur','La personne en charge de confirmer la demande auprès des SG.'),(3,'Administrateur','Accès à toute la base et paramètre de l’outil et gestion des stocks');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commandes`
--

DROP TABLE IF EXISTS `commandes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commandes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idEtat` int(11) NOT NULL,
  `idFournitures` int(11) NOT NULL,
  `idPersonnel` int(11) NOT NULL,
  `nomCommandes` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantiteDemande` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commandes`
--

LOCK TABLES `commandes` WRITE;
/*!40000 ALTER TABLE `commandes` DISABLE KEYS */;
INSERT INTO `commandes` VALUES (1,3,5,2,'Lot de 4 stylo bic cristal',1,'2020-12-21 12:04:04','2020-12-21 12:04:04'),(3,1,8,3,'Papier d\'imprimante Clairefontaine',3,'2020-12-28 12:56:32','2020-12-30 16:00:00');
/*!40000 ALTER TABLE `commandes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `demandes_specifiques`
--

DROP TABLE IF EXISTS `demandes_specifiques`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `demandes_specifiques` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idEtat` int(11) NOT NULL,
  `idPersonnel` int(11) NOT NULL,
  `nomDemande` varchar(255) NOT NULL,
  `quantiteDemande` int(11) NOT NULL,
  `lienProduit` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demandes_specifiques`
--

LOCK TABLES `demandes_specifiques` WRITE;
/*!40000 ALTER TABLE `demandes_specifiques` DISABLE KEYS */;
INSERT INTO `demandes_specifiques` VALUES (1,1,2,'Ciseau de bureau',1,'https://dactylbureau-calipage.fournituredebureau.com/Detail.aspx?ProductId=4315838','2020-12-09 07:33:41','2020-12-09 07:33:41'),(2,3,2,'Stick de colle UHU',3,'https://dactylbureau-calipage.fournituredebureau.com/Detail.aspx?ProductId=59108','2020-12-22 11:04:37','2020-12-22 13:27:17'),(3,1,2,'Vidéo projecteur',1,'Aucun lien fourni','2020-12-24 16:41:17','2020-12-24 16:41:17');
/*!40000 ALTER TABLE `demandes_specifiques` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `etats`
--

DROP TABLE IF EXISTS `etats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `etats` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nomEtat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descriptionEtat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `etats`
--

LOCK TABLES `etats` WRITE;
/*!40000 ALTER TABLE `etats` DISABLE KEYS */;
INSERT INTO `etats` VALUES (1,'Prise en compte','La commande à été prise en compte.'),(2,'Validé','La commande à été validé.'),(3,'En cours','La commande est en cours.'),(4,'Livré','La commande est livré.'),(5,'Annulé','La commande à été annulé.');
/*!40000 ALTER TABLE `etats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `familles_fournitures`
--

DROP TABLE IF EXISTS `familles_fournitures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `familles_fournitures` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nomFamille` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descriptionFamille` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `familles_fournitures`
--

LOCK TABLES `familles_fournitures` WRITE;
/*!40000 ALTER TABLE `familles_fournitures` DISABLE KEYS */;
INSERT INTO `familles_fournitures` VALUES (1,'Aucune','L\'article n\'appartient à aucune familles','2021-01-03 13:58:14','2021-01-03 13:58:14'),(2,'Stylos bille','Tous les stylos à bille.','2021-01-03 14:00:34','2021-01-03 14:00:34'),(3,'Feuilles d\'imprimante','Toutes les feuilles d\'imprimante','2021-01-03 14:01:23','2021-01-03 14:01:23'),(4,'Feuilles à dessin','Toutes les feuilles pour le dessin',NULL,NULL);
/*!40000 ALTER TABLE `familles_fournitures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fournitures`
--

DROP TABLE IF EXISTS `fournitures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fournitures` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idFamille` int(11) NOT NULL,
  `nomFournitures` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomPhoto` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descriptionFournitures` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantiteDisponible` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fournitures`
--

LOCK TABLES `fournitures` WRITE;
/*!40000 ALTER TABLE `fournitures` DISABLE KEYS */;
INSERT INTO `fournitures` VALUES (1,2,'Stylo bic cristal bleu','stylo-bic-cristal-bleu','Stylo bic cristal bleu',7,'2020-12-10 12:22:28','2021-01-04 17:40:37'),(2,2,'Stylo bic cristal vert','stylo-bic-cristal-vert','Stylo bic cristal vert',10,'2020-12-10 12:22:28','2021-01-04 10:07:55'),(3,2,'Stylo bic cristal rouge','stylo-bic-cristal-rouge','Stylo bic cristal rouge',8,'2020-12-10 12:22:28','2020-12-27 09:01:47'),(4,1,'Stylo bic cristal noir','stylo-bic-cristal-noir','Stylo bic cristal noir',3,'2020-12-10 12:22:28','2020-12-10 12:22:28'),(5,2,'Lot de 4 stylo bic cristal','lot-stylo-bic-cristal','Lot de 4 stylo bic cristal',7,'2020-12-10 12:22:28','2020-12-10 12:22:28'),(6,2,'Stylo bic quatre couleurs','stylo-bic-quatre-couleurs','Stylo bic quatre couleurs',16,'2020-12-10 12:22:28','2020-12-10 12:22:28'),(7,1,'Ciseaux Maped','ciseaux-maped','Ciseaux de la marque Maped',11,'2020-12-27 11:47:04','2020-12-27 11:47:04'),(8,3,'Papier d\'imprimante Clairefontaine','papier-d\'imprimante-clairefontaine','Papier d\'imprimante Clairefontaine',21,'2020-12-27 12:00:27','2021-01-04 17:40:12'),(9,1,'Papier canson','papier-canson','Papier de la marque canson',10,'2020-12-29 15:37:47','2021-01-03 15:36:28');
/*!40000 ALTER TABLE `fournitures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2020_10_28_144756_create_commandes_table',1),(2,'2020_10_28_144825_create_fournitures_table',1),(3,'2020_10_28_144851_create_personnel_table',1),(4,'2020_11_14_092922_create_service_table',1),(5,'2020_11_14_092948_create_categorie_table',1),(6,'2020_11_14_093006_create_etat_table',1),(7,'2020_12_21_163451_create_demandes_specifiques_table',1),(8,'2021_01_03_202625_create_familles_fournitures_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personnels`
--

DROP TABLE IF EXISTS `personnels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personnels` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idCategorie` int(11) NOT NULL,
  `idService` int(11) NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mail` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pass` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` varchar(1500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personnels`
--

LOCK TABLES `personnels` WRITE;
/*!40000 ALTER TABLE `personnels` DISABLE KEYS */;
INSERT INTO `personnels` VALUES (1,3,3,'Admin','Admin','admin@admin.fr','$2y$10$ULxajwbfdCsNz5I/XNQkzerWXwozwXj09rNQKMQyCODEsYuO9Hpb.','Bienvenue à la CCI d\'Alsace Eurométropole !','2020-12-05 12:51:03','2021-01-03 09:23:01'),(2,1,2,'Utilisateur','Comptabilité','utilisateur@comptabilite.fr','$2y$10$OKkrnaG3CGtZuLK/euxxGOLJXRRm3/WZ9x1PSxJmp2oijIFDVrH0K','Bienvenue à la CCI d\'Alsace Eurométropole !','2020-12-06 09:32:17','2021-01-03 09:23:01'),(3,2,2,'Valideur','Comptabilité','valideur@comptabilite.fr','$2y$10$z7dEVd9DzXJ1sfvjC9hvquFScRbdhbqCBy01sqBs9Q8W5lxCp58oe','Bienvenue à la CCI d\'Alsace Eurométropole !','2021-01-02 12:00:25','2021-01-03 09:23:01'),(4,1,1,'Utilisateur','Accueil','utilisateur@accueil.fr','$2y$10$5wPoJNoImanY/pJq0KZ1Q.O1.QwY8UUY43mSYpwFXV5aRUI/r0Jvu','Bienvenue à la CCI d\'Alsace Eurométropole !','2021-01-02 11:58:44','2021-01-03 09:23:01'),(5,2,1,'Valideur','Accueil','valideur@accueil.fr','$2y$10$/JC5vIKOKlxzKdaR0u7IKuuzmtlv6sRhFDnpGct1iLuO4rFzFgYim','Bienvenue à la CCI d\'Alsace Eurométropole !','2020-12-21 08:26:58','2021-01-03 09:23:01'),(6,1,3,'Utilisateur','Administration','utilisateur@administration.fr','$2y$10$N/ESfqZ2AWoIZGrbALCnzehUZYA06dUFvEw8OPivWJXISVwGW/CyO','Bienvenue à la CCI d\'Alsace Eurométropole !','2021-01-02 12:01:58','2021-01-03 09:23:01'),(7,2,3,'Valideur','Administration','valideur@administration.fr','$2y$10$3dm.vFEouv5JE5tSdpJuWObjd.c/B7t64UyvvAgOTbB7vuXpES3om','Bienvenue à la CCI d\'Alsace Eurométropole !','2021-01-02 12:03:00','2021-01-03 09:23:01');
/*!40000 ALTER TABLE `personnels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `services` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nomService` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descriptionService` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (1,'Accueil','Le service d\'accueil de la CCI.','2020-12-09 08:45:22','2020-12-09 08:45:22'),(2,'Comptabilité','Le service de comptabilité de la CCI.','2020-12-09 08:45:22','2020-12-09 08:45:22'),(3,'Administration','Le service d\'administration de la CCI.','2020-12-09 07:45:22','2020-12-09 07:45:22'),(4,'Apprentissage','Le service d\'apprentissage de la CCI.','2021-01-02 13:30:09','2021-01-02 13:30:09');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-01-06 10:54:58
