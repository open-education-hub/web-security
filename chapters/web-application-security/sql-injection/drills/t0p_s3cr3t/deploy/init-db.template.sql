DROP DATABASE IF EXISTS `secrets`;
CREATE DATABASE `secrets`; 

USE `secrets`;

DROP TABLE IF EXISTS `secrets`;
CREATE TABLE `secrets` (
  `session_id` varchar(50) DEFAULT NULL,
  `secret` varchar(200) DEFAULT NULL
);

LOCK TABLES `secrets` WRITE;
INSERT INTO `secrets` VALUES ('king_of_the_web_security','__TEMPLATE__');
UNLOCK TABLES;
