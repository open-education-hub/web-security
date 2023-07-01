CREATE DATABASE IF NOT EXISTS `blacklist`;
USE `blacklist`;

DROP TABLE IF EXISTS `search_engine`;
CREATE TABLE `search_engine` (
  `title` varchar(50) DEFAULT NULL,
  `description` varchar(70) DEFAULT NULL,
  `link` varchar(70) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `users` WRITE;
INSERT INTO `users` VALUES ('Administrator','__TEMPLATE__');
UNLOCK TABLES;
