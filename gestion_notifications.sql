-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           5.7.36 - MySQL Community Server (GPL)
-- SE du serveur:                Win32
-- HeidiSQL Version:             11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Listage de la structure de la base pour gestion_notifications
CREATE DATABASE IF NOT EXISTS `gestion_notifications` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `gestion_notifications`;

-- Listage de la structure de la table gestion_notifications. mailjet_invalid
CREATE TABLE IF NOT EXISTS `mailjet_invalid` (
  `pk` int(11) NOT NULL AUTO_INCREMENT,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(50) NOT NULL DEFAULT '',
  `payload` longtext NOT NULL,
  `source` varchar(50) NOT NULL DEFAULT '',
  `extra` longtext,
  `message_exception` longtext CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`pk`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de la table gestion_notifications. mailjet_processed
CREATE TABLE IF NOT EXISTS `mailjet_processed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` varchar(50) NOT NULL DEFAULT '0',
  `messageId` varchar(50) NOT NULL DEFAULT '',
  `type` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `payload` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de la table gestion_notifications. received
CREATE TABLE IF NOT EXISTS `received` (
  `pk` int(11) NOT NULL AUTO_INCREMENT,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(50) NOT NULL,
  `payload` longtext NOT NULL,
  `source` varchar(50) NOT NULL,
  `extra` longtext,
  PRIMARY KEY (`pk`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4;

-- Les données exportées n'étaient pas sélectionnées.

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
