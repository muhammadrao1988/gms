/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 10.1.21-MariaDB : Database - gms
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`gms` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `gms`;

/*Table structure for table `acc_types` */

DROP TABLE IF EXISTS `acc_types`;

CREATE TABLE `acc_types` (
  `acc_type_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) DEFAULT NULL,
  `Description` text,
  `service_period` int(11) DEFAULT NULL,
  `service_charges` int(11) DEFAULT NULL,
  `service_offered` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`acc_type_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*Data for the table `acc_types` */

insert  into `acc_types`(`acc_type_ID`,`Name`,`Description`,`service_period`,`service_charges`,`service_offered`) values (1,'WHITE CARD MEMBERSHIP','WHITE CARD MEMBERSHIP',30,2800,'Gym Monthly'),(2,'BLACK CARD MEMBERSHIP','BLACK CARD MEMBERSHIP',30,3300,'Treadmill Monthly'),(3,'GREY CARD MEMBERSHIP','GREY CARD MEMBERSHIP',30,5500,'Gym & Treadmill or Elliptical Monthly'),(4,'RED CARD MEMERSHIP','RED CARD MEMERSHIP',30,9500,'Gym Trainer & Treadmill or Elliptical'),(8,'RED CARD 2 MEMBERSHIP','RED CARD 2 MEMBERSHIP',30,6800,'32'),(9,'New test memeber type 1','balah balalsd aaa',60,21000,'gyem fitness sdf');

/*Table structure for table `account_status` */

DROP TABLE IF EXISTS `account_status`;

CREATE TABLE `account_status` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `reason` varchar(255) DEFAULT NULL,
  `short_desc` varchar(255) DEFAULT NULL,
  `long_desc` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `account_status` */

insert  into `account_status`(`id`,`reason`,`short_desc`,`long_desc`) values (0,'Disabled','DIS','Active'),(1,'Active','ACT','Your account has been suspended, please contact your account manager.'),(2,'Contact Customer Services','C_CS','There is a problem on your account, please contact customer services.'),(3,'Contact Accounts','C_ACC','This is a problem on your account, please contact the accounts department.'),(4,'Close Account','CLOSED','Your account has been closed.');

/*Table structure for table `accounts` */

DROP TABLE IF EXISTS `accounts`;

CREATE TABLE `accounts` (
  `acc_id` int(11) NOT NULL AUTO_INCREMENT,
  `acc_manager` int(11) DEFAULT NULL,
  `acc_types` tinyint(3) DEFAULT NULL,
  `status` tinyint(3) DEFAULT '1',
  `acc_name` varchar(255) DEFAULT NULL,
  `acc_description` text,
  `acc_tel` bigint(15) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `account_image` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `county` varchar(50) DEFAULT NULL,
  `postcode` varchar(20) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `reg_num` bigint(15) DEFAULT NULL,
  `acc_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `branch_id` int(11) DEFAULT NULL,
  `subscriptin_id` int(11) DEFAULT NULL,
  `serial_number` varchar(255) DEFAULT NULL,
  `machine_member_id` int(11) DEFAULT NULL COMMENT 'badge id',
  `machine_user_id` int(11) DEFAULT NULL COMMENT 'user id',
  PRIMARY KEY (`acc_id`),
  KEY `index2` (`acc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=50007 DEFAULT CHARSET=latin1;

/*Data for the table `accounts` */

insert  into `accounts`(`acc_id`,`acc_manager`,`acc_types`,`status`,`acc_name`,`acc_description`,`acc_tel`,`date_of_birth`,`gender`,`email`,`account_image`,`address`,`city`,`county`,`postcode`,`country`,`reg_num`,`acc_date`,`branch_id`,`subscriptin_id`,`serial_number`,`machine_member_id`,`machine_user_id`) values (50000,100069,1,1,'Sample Project Master Account','master account',123,'1989-10-05','male','rameez1254@hotmail.cm',NULL,'Tes123ting','123','West Yorkshire','123','United Kingdom',0,'2013-03-18 22:31:40',1,2,'6113161200173',NULL,NULL),(50001,100069,1,1,'Rameez',NULL,3349352145,'1989-02-10','male','rameez@gmail.com',NULL,'abc street','Karachi',NULL,NULL,'Pakistan',NULL,'2017-05-05 12:06:21',1,2,'6113161200173',4,1),(50004,100069,2,1,'Dayan',NULL,123456789,'1989-10-05','male','dayan@gmail.com',NULL,'test','karachi',NULL,NULL,'paksitan',NULL,'2017-05-05 17:11:26',1,1,'6113161200173',2,85),(50005,100069,1,1,'New Test User',NULL,123456789,'0000-00-00','male','examp@gmail.com',NULL,'asdf','asdf',NULL,NULL,'pakistan',NULL,'2017-05-08 14:29:24',1,1,'6113161200173',NULL,NULL),(50006,100069,8,1,'Test Memner',NULL,3349352145,'1988-05-01','female','memeber@hotmail.com',NULL,'asdf','a sdf',NULL,NULL,'1',NULL,'2017-05-12 12:13:04',1,1,'6113161200173',1,2);

/*Table structure for table `attendance` */

DROP TABLE IF EXISTS `attendance`;

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL COMMENT 'machine user id in account table',
  `status` int(1) DEFAULT NULL COMMENT '1 = valid',
  `datetime` datetime DEFAULT NULL,
  `check_type` varchar(100) DEFAULT NULL COMMENT 'I= in, O= out',
  `machine_serial` varchar(255) DEFAULT NULL COMMENT 'machine serial number get by user table',
  `sensored_id` int(5) DEFAULT NULL COMMENT 'machine sensored type',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

/*Data for the table `attendance` */

insert  into `attendance`(`id`,`account_id`,`status`,`datetime`,`check_type`,`machine_serial`,`sensored_id`) values (3,50001,1,'2017-05-05 15:30:00',NULL,NULL,NULL),(4,50004,1,'2017-05-05 17:14:00',NULL,NULL,NULL),(5,1,1,'2017-05-11 10:58:16','I','6113161200173',1),(6,85,0,'2017-05-11 15:44:05','I','6113161200173',1),(7,1,0,'2017-05-11 10:59:56','O','6113161200173',1),(8,85,1,'2017-05-11 15:44:05','I','6113161200173',1),(9,1,1,'2017-05-11 10:59:56','O','6113161200173',1),(10,1,0,'2017-05-12 11:28:38','I','6113161200173',1),(11,1,0,'2017-05-12 11:33:46','O','6113161200173',1),(16,1,1,'2017-05-12 11:33:46','O','6113161200173',1),(17,1,1,'2017-05-12 11:28:38','I','6113161200173',1),(18,85,1,'2017-05-12 16:26:00','I',NULL,1),(19,85,1,'2017-05-12 17:00:00','O',NULL,1);

/*Table structure for table `audit_log` */

DROP TABLE IF EXISTS `audit_log`;

CREATE TABLE `audit_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `user_id` int(8) DEFAULT NULL COMMENT 'The users.id value that made the change if it is not an admin account. If it is an admin account then store the user id in the admin_id field.',
  `owner` int(8) NOT NULL COMMENT 'accounts.id value',
  `type_id` int(6) NOT NULL COMMENT 'refers to the audit_log_type.id table\n',
  `display_state` int(1) NOT NULL COMMENT '0 = Display to all users, 1 = display to resellers only, 2 = display to admin users only.',
  `note` text COMMENT 'the information relating to the entry which will be displayed on the portal',
  `numbers_id` int(15) DEFAULT NULL COMMENT 'The numbers table id that the record refers to',
  `transaction_id` int(15) DEFAULT NULL COMMENT 'The transactions.id that the comment refers to',
  `cdryymm` varchar(45) DEFAULT NULL COMMENT 'The cdryymm table that the record refers to',
  `cdryymm_id` varchar(45) DEFAULT NULL COMMENT 'The cdryymm.id that the record refers to',
  `admin_id` int(8) DEFAULT NULL,
  `ip_addr` varchar(20) DEFAULT NULL COMMENT 'The IP address which made the change',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;

/*Data for the table `audit_log` */

insert  into `audit_log`(`id`,`date`,`user_id`,`owner`,`type_id`,`display_state`,`note`,`numbers_id`,`transaction_id`,`cdryymm`,`cdryymm_id`,`admin_id`,`ip_addr`) values (1,'2017-05-03 09:39:57',100069,50000,8,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(2,'2017-05-03 09:42:34',100069,50000,8,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(3,'2017-05-03 12:10:42',100069,50000,8,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(4,'2017-05-03 12:12:56',100069,50000,8,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(5,'2017-05-03 12:18:26',NULL,50000,9,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(6,'2017-05-04 05:06:30',100069,50000,8,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(7,'2017-05-04 05:08:45',NULL,50000,9,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(8,'2017-05-04 05:10:34',NULL,50000,9,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(9,'2017-05-04 05:10:36',NULL,50000,9,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(10,'2017-05-04 05:11:19',NULL,50000,9,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(11,'2017-05-04 05:11:54',NULL,50000,9,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(12,'2017-05-04 05:15:11',NULL,50000,9,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(13,'2017-05-04 05:16:14',NULL,50000,9,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(14,'2017-05-04 05:20:13',100069,50000,8,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(15,'2017-05-04 05:51:04',100069,50000,8,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(16,'2017-05-04 08:58:09',100069,50000,8,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(17,'2017-05-04 11:45:29',100069,50000,8,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(18,'2017-05-04 14:53:45',100069,0,15,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(19,'2017-05-04 14:54:53',100069,0,15,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(20,'2017-05-04 14:56:17',100069,0,15,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(21,'2017-05-04 14:58:26',100069,50000,8,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(22,'2017-05-04 14:59:08',100069,0,15,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(23,'2017-05-04 14:59:21',100069,0,15,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(24,'2017-05-05 07:06:30',100069,50000,8,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(25,'2017-05-05 09:06:01',100069,0,15,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(26,'2017-05-05 09:06:20',100069,0,15,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(27,'2017-05-05 09:13:16',100069,0,15,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(28,'2017-05-05 09:13:28',100069,0,15,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(29,'2017-05-05 14:11:26',100069,0,15,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(30,'2017-05-08 07:14:40',100069,50000,8,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(31,'2017-05-08 09:45:57',100069,50000,8,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(32,'2017-05-08 09:48:12',100069,50000,8,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(33,'2017-05-08 11:29:24',100069,0,15,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(34,'2017-05-08 13:37:59',100069,50000,8,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(35,'2017-05-09 07:37:01',100069,50000,8,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(36,'2017-05-09 12:48:11',100069,50000,8,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(37,'2017-05-10 07:36:12',100069,50000,8,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(38,'2017-05-10 09:49:20',100069,50000,8,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(39,'2017-05-10 12:39:12',100069,50000,8,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(40,'2017-05-11 07:19:25',100069,50000,8,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(41,'2017-05-11 08:07:44',100069,50000,8,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(42,'2017-05-11 12:53:26',100069,50000,8,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(43,'2017-05-12 07:05:02',100069,50000,8,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(44,'2017-05-12 07:12:18',100069,50000,8,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(45,'2017-05-12 09:13:04',100069,0,15,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1'),(46,'2017-05-15 07:17:00',100069,50000,8,2,NULL,NULL,NULL,NULL,NULL,NULL,'::1');

/*Table structure for table `audit_log_type` */

DROP TABLE IF EXISTS `audit_log_type`;

CREATE TABLE `audit_log_type` (
  `id` int(11) NOT NULL,
  `action` varchar(45) DEFAULT NULL COMMENT 'This is what the audit_log item refers to - it is internal. ',
  `display_title` varchar(40) DEFAULT NULL COMMENT 'This is the text which will be displayed as the title item on the portal',
  `display_status` int(1) DEFAULT NULL COMMENT '0 = Display to all users, 1 = display to resellers only, 2 = display to admin users only',
  `pre_post_type` int(1) DEFAULT NULL COMMENT 'This will define whether the audit_log.comment_pub value should be displayed before or after the display_text',
  `display_text` varchar(45) DEFAULT NULL COMMENT 'This is the text that will be displayed before or after the information stored in the audit_log.comment_pub value',
  `table_ref` varchar(20) DEFAULT NULL COMMENT 'Which table the information is from',
  `function_ref` varchar(20) DEFAULT NULL COMMENT 'The function which created the entry',
  `display_image_name` varchar(20) DEFAULT NULL COMMENT 'This is to identify which image to display on the portal',
  `note` varchar(45) DEFAULT NULL COMMENT 'internal note for the account',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `audit_log_type` */

insert  into `audit_log_type`(`id`,`action`,`display_title`,`display_status`,`pre_post_type`,`display_text`,`table_ref`,`function_ref`,`display_image_name`,`note`) values (0,NULL,'Error',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(8,NULL,'User Logged In',2,0,NULL,'users',NULL,NULL,NULL),(9,NULL,'Password Changed',2,0,NULL,'users',NULL,NULL,NULL),(10,NULL,'New Customer Created',2,0,NULL,'accounts',NULL,NULL,NULL),(11,NULL,'User Deleted',1,0,NULL,'users',NULL,NULL,NULL),(12,NULL,'User Account Auto Locked',1,0,NULL,'users',NULL,NULL,NULL),(13,NULL,'User Forgotten Password',1,0,NULL,'users',NULL,NULL,NULL),(14,NULL,'User Forgotten Username',1,0,NULL,'users',NULL,NULL,NULL),(15,NULL,'New User Created',1,0,NULL,'users',NULL,NULL,NULL),(44,'','Unlock Attempt - Incorrect Details',1,0,NULL,'users',NULL,NULL,NULL),(46,NULL,'Failed use of unlock code detected',1,0,NULL,'users',NULL,NULL,NULL),(47,NULL,'User account unlocked',1,0,NULL,'users',NULL,NULL,NULL),(48,NULL,'User account locked via CRM',1,0,NULL,'users',NULL,NULL,NULL),(53,'','Disabled Account Attempted Login',1,0,NULL,'audit_log',NULL,NULL,NULL);

/*Table structure for table `branches` */

DROP TABLE IF EXISTS `branches`;

CREATE TABLE `branches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_name` varchar(255) DEFAULT NULL,
  `branch_user` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `branches` */

insert  into `branches`(`id`,`branch_name`,`branch_user`,`status`,`datetime`) values (1,'Defence',100069,1,'2017-05-08 15:15:36'),(2,'Gulshan',100069,1,'2017-05-08 16:46:23');

/*Table structure for table `ci_sessions` */

DROP TABLE IF EXISTS `ci_sessions`;

CREATE TABLE `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `ci_sessions` */

insert  into `ci_sessions`(`id`,`ip_address`,`timestamp`,`data`) values ('b0qmtlprib3hfjdmb66tbhg1cgu17q82','::1',1494851463,'__ci_last_regenerate|i:1494851463;tgm_user_id|s:6:\"100069\";login_status|s:1:\"1\";password_attempts|s:1:\"0\";user_id|s:6:\"100069\";username|s:5:\"m.rao\";email|s:24:\"m.rao@topgearmedia.co.uk\";user_type|s:1:\"1\";u_type|s:1:\"1\";user_template_id|s:1:\"1\";parent_id|s:5:\"50000\";user_info|O:8:\"stdClass\":31:{s:7:\"user_id\";s:6:\"100069\";s:6:\"parent\";s:5:\"50000\";s:12:\"parent_child\";s:5:\"50000\";s:9:\"user_type\";s:1:\"1\";s:6:\"u_type\";s:1:\"1\";s:16:\"user_template_id\";s:1:\"1\";s:10:\"first_name\";s:2:\"Mr\";s:7:\"surname\";s:3:\"Rao\";s:8:\"username\";s:5:\"m.rao\";s:5:\"email\";s:24:\"m.rao@topgearmedia.co.uk\";s:8:\"position\";s:3:\"ttt\";s:12:\"office_phone\";s:11:\"03433049856\";s:9:\"mob_phone\";s:11:\"03433049856\";s:3:\"fax\";s:1:\"0\";s:8:\"password\";s:32:\"0192023a7bbd73250516f069df18b500\";s:11:\"security_id\";s:1:\"1\";s:12:\"security_ans\";s:8:\"pakistan\";s:13:\"creation_date\";s:19:\"2013-04-12 02:54:10\";s:13:\"modified_date\";s:19:\"2016-01-01 07:20:47\";s:12:\"login_status\";s:1:\"1\";s:11:\"permission1\";N;s:9:\"reset_key\";s:32:\"fd4d18ad80c789efd1f1d8b734237a81\";s:6:\"status\";s:1:\"1\";s:11:\"acc_manager\";s:1:\"1\";s:10:\"reset_code\";s:0:\"\";s:11:\"auto_logout\";s:3:\"480\";s:11:\"disabled_by\";N;s:15:\"default_page_id\";s:3:\"108\";s:14:\"machine_serial\";s:13:\"6113161200173\";s:9:\"branch_id\";s:1:\"1\";s:8:\"acc_name\";s:29:\"Sample Project Master Account\";}logged_in|i:1;'),('5ehufv2p31heg76809g5fkda6gd4ak3j','::1',1494853224,'__ci_last_regenerate|i:1494853224;tgm_user_id|s:6:\"100069\";login_status|s:1:\"1\";password_attempts|s:1:\"0\";user_id|s:6:\"100069\";username|s:5:\"m.rao\";email|s:24:\"m.rao@topgearmedia.co.uk\";user_type|s:1:\"1\";u_type|s:1:\"1\";user_template_id|s:1:\"1\";parent_id|s:5:\"50000\";user_info|O:8:\"stdClass\":31:{s:7:\"user_id\";s:6:\"100069\";s:6:\"parent\";s:5:\"50000\";s:12:\"parent_child\";s:5:\"50000\";s:9:\"user_type\";s:1:\"1\";s:6:\"u_type\";s:1:\"1\";s:16:\"user_template_id\";s:1:\"1\";s:10:\"first_name\";s:2:\"Mr\";s:7:\"surname\";s:3:\"Rao\";s:8:\"username\";s:5:\"m.rao\";s:5:\"email\";s:24:\"m.rao@topgearmedia.co.uk\";s:8:\"position\";s:3:\"ttt\";s:12:\"office_phone\";s:11:\"03433049856\";s:9:\"mob_phone\";s:11:\"03433049856\";s:3:\"fax\";s:1:\"0\";s:8:\"password\";s:32:\"0192023a7bbd73250516f069df18b500\";s:11:\"security_id\";s:1:\"1\";s:12:\"security_ans\";s:8:\"pakistan\";s:13:\"creation_date\";s:19:\"2013-04-12 02:54:10\";s:13:\"modified_date\";s:19:\"2016-01-01 07:20:47\";s:12:\"login_status\";s:1:\"1\";s:11:\"permission1\";N;s:9:\"reset_key\";s:32:\"fd4d18ad80c789efd1f1d8b734237a81\";s:6:\"status\";s:1:\"1\";s:11:\"acc_manager\";s:1:\"1\";s:10:\"reset_code\";s:0:\"\";s:11:\"auto_logout\";s:3:\"480\";s:11:\"disabled_by\";N;s:15:\"default_page_id\";s:3:\"108\";s:14:\"machine_serial\";s:13:\"6113161200173\";s:9:\"branch_id\";s:1:\"1\";s:8:\"acc_name\";s:29:\"Sample Project Master Account\";}logged_in|i:1;'),('vaverf7057b7nd5e0273g513cc4gnsse','::1',1494854009,'__ci_last_regenerate|i:1494854009;tgm_user_id|s:6:\"100069\";login_status|s:1:\"1\";password_attempts|s:1:\"0\";user_id|s:6:\"100069\";username|s:5:\"m.rao\";email|s:24:\"m.rao@topgearmedia.co.uk\";user_type|s:1:\"1\";u_type|s:1:\"1\";user_template_id|s:1:\"1\";parent_id|s:5:\"50000\";user_info|O:8:\"stdClass\":31:{s:7:\"user_id\";s:6:\"100069\";s:6:\"parent\";s:5:\"50000\";s:12:\"parent_child\";s:5:\"50000\";s:9:\"user_type\";s:1:\"1\";s:6:\"u_type\";s:1:\"1\";s:16:\"user_template_id\";s:1:\"1\";s:10:\"first_name\";s:2:\"Mr\";s:7:\"surname\";s:3:\"Rao\";s:8:\"username\";s:5:\"m.rao\";s:5:\"email\";s:24:\"m.rao@topgearmedia.co.uk\";s:8:\"position\";s:3:\"ttt\";s:12:\"office_phone\";s:11:\"03433049856\";s:9:\"mob_phone\";s:11:\"03433049856\";s:3:\"fax\";s:1:\"0\";s:8:\"password\";s:32:\"0192023a7bbd73250516f069df18b500\";s:11:\"security_id\";s:1:\"1\";s:12:\"security_ans\";s:8:\"pakistan\";s:13:\"creation_date\";s:19:\"2013-04-12 02:54:10\";s:13:\"modified_date\";s:19:\"2016-01-01 07:20:47\";s:12:\"login_status\";s:1:\"1\";s:11:\"permission1\";N;s:9:\"reset_key\";s:32:\"fd4d18ad80c789efd1f1d8b734237a81\";s:6:\"status\";s:1:\"1\";s:11:\"acc_manager\";s:1:\"1\";s:10:\"reset_code\";s:0:\"\";s:11:\"auto_logout\";s:3:\"480\";s:11:\"disabled_by\";N;s:15:\"default_page_id\";s:3:\"108\";s:14:\"machine_serial\";s:13:\"6113161200173\";s:9:\"branch_id\";s:1:\"1\";s:8:\"acc_name\";s:29:\"Sample Project Master Account\";}logged_in|i:1;'),('0pudv9ujrjbd7uiep72romk2o59q0okq','::1',1494850268,'__ci_last_regenerate|i:1494850268;tgm_user_id|s:6:\"100069\";login_status|s:1:\"1\";password_attempts|s:1:\"0\";user_id|s:6:\"100069\";username|s:5:\"m.rao\";email|s:24:\"m.rao@topgearmedia.co.uk\";user_type|s:1:\"1\";u_type|s:1:\"1\";user_template_id|s:1:\"1\";parent_id|s:5:\"50000\";user_info|O:8:\"stdClass\":31:{s:7:\"user_id\";s:6:\"100069\";s:6:\"parent\";s:5:\"50000\";s:12:\"parent_child\";s:5:\"50000\";s:9:\"user_type\";s:1:\"1\";s:6:\"u_type\";s:1:\"1\";s:16:\"user_template_id\";s:1:\"1\";s:10:\"first_name\";s:2:\"Mr\";s:7:\"surname\";s:3:\"Rao\";s:8:\"username\";s:5:\"m.rao\";s:5:\"email\";s:24:\"m.rao@topgearmedia.co.uk\";s:8:\"position\";s:3:\"ttt\";s:12:\"office_phone\";s:11:\"03433049856\";s:9:\"mob_phone\";s:11:\"03433049856\";s:3:\"fax\";s:1:\"0\";s:8:\"password\";s:32:\"0192023a7bbd73250516f069df18b500\";s:11:\"security_id\";s:1:\"1\";s:12:\"security_ans\";s:8:\"pakistan\";s:13:\"creation_date\";s:19:\"2013-04-12 02:54:10\";s:13:\"modified_date\";s:19:\"2016-01-01 07:20:47\";s:12:\"login_status\";s:1:\"1\";s:11:\"permission1\";N;s:9:\"reset_key\";s:32:\"fd4d18ad80c789efd1f1d8b734237a81\";s:6:\"status\";s:1:\"1\";s:11:\"acc_manager\";s:1:\"1\";s:10:\"reset_code\";s:0:\"\";s:11:\"auto_logout\";s:3:\"480\";s:11:\"disabled_by\";N;s:15:\"default_page_id\";s:3:\"108\";s:14:\"machine_serial\";s:13:\"6113161200173\";s:9:\"branch_id\";s:1:\"1\";s:8:\"acc_name\";s:29:\"Sample Project Master Account\";}logged_in|i:1;'),('1bamtfh5lu26s5dafnm8vt8ndl6lpbkv','::1',1494854465,'__ci_last_regenerate|i:1494854465;tgm_user_id|s:6:\"100069\";login_status|s:1:\"1\";password_attempts|s:1:\"0\";user_id|s:6:\"100069\";username|s:5:\"m.rao\";email|s:24:\"m.rao@topgearmedia.co.uk\";user_type|s:1:\"1\";u_type|s:1:\"1\";user_template_id|s:1:\"1\";parent_id|s:5:\"50000\";user_info|O:8:\"stdClass\":31:{s:7:\"user_id\";s:6:\"100069\";s:6:\"parent\";s:5:\"50000\";s:12:\"parent_child\";s:5:\"50000\";s:9:\"user_type\";s:1:\"1\";s:6:\"u_type\";s:1:\"1\";s:16:\"user_template_id\";s:1:\"1\";s:10:\"first_name\";s:2:\"Mr\";s:7:\"surname\";s:3:\"Rao\";s:8:\"username\";s:5:\"m.rao\";s:5:\"email\";s:24:\"m.rao@topgearmedia.co.uk\";s:8:\"position\";s:3:\"ttt\";s:12:\"office_phone\";s:11:\"03433049856\";s:9:\"mob_phone\";s:11:\"03433049856\";s:3:\"fax\";s:1:\"0\";s:8:\"password\";s:32:\"0192023a7bbd73250516f069df18b500\";s:11:\"security_id\";s:1:\"1\";s:12:\"security_ans\";s:8:\"pakistan\";s:13:\"creation_date\";s:19:\"2013-04-12 02:54:10\";s:13:\"modified_date\";s:19:\"2016-01-01 07:20:47\";s:12:\"login_status\";s:1:\"1\";s:11:\"permission1\";N;s:9:\"reset_key\";s:32:\"fd4d18ad80c789efd1f1d8b734237a81\";s:6:\"status\";s:1:\"1\";s:11:\"acc_manager\";s:1:\"1\";s:10:\"reset_code\";s:0:\"\";s:11:\"auto_logout\";s:3:\"480\";s:11:\"disabled_by\";N;s:15:\"default_page_id\";s:3:\"108\";s:14:\"machine_serial\";s:13:\"6113161200173\";s:9:\"branch_id\";s:1:\"1\";s:8:\"acc_name\";s:29:\"Sample Project Master Account\";}logged_in|i:1;'),('t7nl0i3smvr3h890glc258ju6b9kfa02','::1',1494855089,'__ci_last_regenerate|i:1494854465;tgm_user_id|s:6:\"100069\";login_status|s:1:\"1\";password_attempts|s:1:\"0\";user_id|s:6:\"100069\";username|s:5:\"m.rao\";email|s:24:\"m.rao@topgearmedia.co.uk\";user_type|s:1:\"1\";u_type|s:1:\"1\";user_template_id|s:1:\"1\";parent_id|s:5:\"50000\";user_info|O:8:\"stdClass\":31:{s:7:\"user_id\";s:6:\"100069\";s:6:\"parent\";s:5:\"50000\";s:12:\"parent_child\";s:5:\"50000\";s:9:\"user_type\";s:1:\"1\";s:6:\"u_type\";s:1:\"1\";s:16:\"user_template_id\";s:1:\"1\";s:10:\"first_name\";s:2:\"Mr\";s:7:\"surname\";s:3:\"Rao\";s:8:\"username\";s:5:\"m.rao\";s:5:\"email\";s:24:\"m.rao@topgearmedia.co.uk\";s:8:\"position\";s:3:\"ttt\";s:12:\"office_phone\";s:11:\"03433049856\";s:9:\"mob_phone\";s:11:\"03433049856\";s:3:\"fax\";s:1:\"0\";s:8:\"password\";s:32:\"0192023a7bbd73250516f069df18b500\";s:11:\"security_id\";s:1:\"1\";s:12:\"security_ans\";s:8:\"pakistan\";s:13:\"creation_date\";s:19:\"2013-04-12 02:54:10\";s:13:\"modified_date\";s:19:\"2016-01-01 07:20:47\";s:12:\"login_status\";s:1:\"1\";s:11:\"permission1\";N;s:9:\"reset_key\";s:32:\"fd4d18ad80c789efd1f1d8b734237a81\";s:6:\"status\";s:1:\"1\";s:11:\"acc_manager\";s:1:\"1\";s:10:\"reset_code\";s:0:\"\";s:11:\"auto_logout\";s:3:\"480\";s:11:\"disabled_by\";N;s:15:\"default_page_id\";s:3:\"108\";s:14:\"machine_serial\";s:13:\"6113161200173\";s:9:\"branch_id\";s:1:\"1\";s:8:\"acc_name\";s:29:\"Sample Project Master Account\";}logged_in|i:1;'),('1bvupb0t09r0nf12c91a6jkmvd29ghn2','::1',1494847898,'__ci_last_regenerate|i:1494847898;tgm_user_id|s:6:\"100069\";login_status|s:1:\"1\";password_attempts|s:1:\"0\";user_id|s:6:\"100069\";username|s:5:\"m.rao\";email|s:24:\"m.rao@topgearmedia.co.uk\";user_type|s:1:\"1\";u_type|s:1:\"1\";user_template_id|s:1:\"1\";parent_id|s:5:\"50000\";user_info|O:8:\"stdClass\":31:{s:7:\"user_id\";s:6:\"100069\";s:6:\"parent\";s:5:\"50000\";s:12:\"parent_child\";s:5:\"50000\";s:9:\"user_type\";s:1:\"1\";s:6:\"u_type\";s:1:\"1\";s:16:\"user_template_id\";s:1:\"1\";s:10:\"first_name\";s:2:\"Mr\";s:7:\"surname\";s:3:\"Rao\";s:8:\"username\";s:5:\"m.rao\";s:5:\"email\";s:24:\"m.rao@topgearmedia.co.uk\";s:8:\"position\";s:3:\"ttt\";s:12:\"office_phone\";s:11:\"03433049856\";s:9:\"mob_phone\";s:11:\"03433049856\";s:3:\"fax\";s:1:\"0\";s:8:\"password\";s:32:\"0192023a7bbd73250516f069df18b500\";s:11:\"security_id\";s:1:\"1\";s:12:\"security_ans\";s:8:\"pakistan\";s:13:\"creation_date\";s:19:\"2013-04-12 02:54:10\";s:13:\"modified_date\";s:19:\"2016-01-01 07:20:47\";s:12:\"login_status\";s:1:\"1\";s:11:\"permission1\";N;s:9:\"reset_key\";s:32:\"fd4d18ad80c789efd1f1d8b734237a81\";s:6:\"status\";s:1:\"1\";s:11:\"acc_manager\";s:1:\"1\";s:10:\"reset_code\";s:0:\"\";s:11:\"auto_logout\";s:3:\"480\";s:11:\"disabled_by\";N;s:15:\"default_page_id\";s:3:\"108\";s:14:\"machine_serial\";s:13:\"6113161200173\";s:9:\"branch_id\";s:1:\"1\";s:8:\"acc_name\";s:29:\"Sample Project Master Account\";}logged_in|i:1;'),('5m2phgpb56bluc91l8chks7bhg405p84','::1',1494848222,'__ci_last_regenerate|i:1494848222;tgm_user_id|s:6:\"100069\";login_status|s:1:\"1\";password_attempts|s:1:\"0\";user_id|s:6:\"100069\";username|s:5:\"m.rao\";email|s:24:\"m.rao@topgearmedia.co.uk\";user_type|s:1:\"1\";u_type|s:1:\"1\";user_template_id|s:1:\"1\";parent_id|s:5:\"50000\";user_info|O:8:\"stdClass\":31:{s:7:\"user_id\";s:6:\"100069\";s:6:\"parent\";s:5:\"50000\";s:12:\"parent_child\";s:5:\"50000\";s:9:\"user_type\";s:1:\"1\";s:6:\"u_type\";s:1:\"1\";s:16:\"user_template_id\";s:1:\"1\";s:10:\"first_name\";s:2:\"Mr\";s:7:\"surname\";s:3:\"Rao\";s:8:\"username\";s:5:\"m.rao\";s:5:\"email\";s:24:\"m.rao@topgearmedia.co.uk\";s:8:\"position\";s:3:\"ttt\";s:12:\"office_phone\";s:11:\"03433049856\";s:9:\"mob_phone\";s:11:\"03433049856\";s:3:\"fax\";s:1:\"0\";s:8:\"password\";s:32:\"0192023a7bbd73250516f069df18b500\";s:11:\"security_id\";s:1:\"1\";s:12:\"security_ans\";s:8:\"pakistan\";s:13:\"creation_date\";s:19:\"2013-04-12 02:54:10\";s:13:\"modified_date\";s:19:\"2016-01-01 07:20:47\";s:12:\"login_status\";s:1:\"1\";s:11:\"permission1\";N;s:9:\"reset_key\";s:32:\"fd4d18ad80c789efd1f1d8b734237a81\";s:6:\"status\";s:1:\"1\";s:11:\"acc_manager\";s:1:\"1\";s:10:\"reset_code\";s:0:\"\";s:11:\"auto_logout\";s:3:\"480\";s:11:\"disabled_by\";N;s:15:\"default_page_id\";s:3:\"108\";s:14:\"machine_serial\";s:13:\"6113161200173\";s:9:\"branch_id\";s:1:\"1\";s:8:\"acc_name\";s:29:\"Sample Project Master Account\";}logged_in|i:1;'),('bfrardmb193etmgt5mh4mt8gbmnva5s2','::1',1494849264,'__ci_last_regenerate|i:1494849264;tgm_user_id|s:6:\"100069\";login_status|s:1:\"1\";password_attempts|s:1:\"0\";user_id|s:6:\"100069\";username|s:5:\"m.rao\";email|s:24:\"m.rao@topgearmedia.co.uk\";user_type|s:1:\"1\";u_type|s:1:\"1\";user_template_id|s:1:\"1\";parent_id|s:5:\"50000\";user_info|O:8:\"stdClass\":31:{s:7:\"user_id\";s:6:\"100069\";s:6:\"parent\";s:5:\"50000\";s:12:\"parent_child\";s:5:\"50000\";s:9:\"user_type\";s:1:\"1\";s:6:\"u_type\";s:1:\"1\";s:16:\"user_template_id\";s:1:\"1\";s:10:\"first_name\";s:2:\"Mr\";s:7:\"surname\";s:3:\"Rao\";s:8:\"username\";s:5:\"m.rao\";s:5:\"email\";s:24:\"m.rao@topgearmedia.co.uk\";s:8:\"position\";s:3:\"ttt\";s:12:\"office_phone\";s:11:\"03433049856\";s:9:\"mob_phone\";s:11:\"03433049856\";s:3:\"fax\";s:1:\"0\";s:8:\"password\";s:32:\"0192023a7bbd73250516f069df18b500\";s:11:\"security_id\";s:1:\"1\";s:12:\"security_ans\";s:8:\"pakistan\";s:13:\"creation_date\";s:19:\"2013-04-12 02:54:10\";s:13:\"modified_date\";s:19:\"2016-01-01 07:20:47\";s:12:\"login_status\";s:1:\"1\";s:11:\"permission1\";N;s:9:\"reset_key\";s:32:\"fd4d18ad80c789efd1f1d8b734237a81\";s:6:\"status\";s:1:\"1\";s:11:\"acc_manager\";s:1:\"1\";s:10:\"reset_code\";s:0:\"\";s:11:\"auto_logout\";s:3:\"480\";s:11:\"disabled_by\";N;s:15:\"default_page_id\";s:3:\"108\";s:14:\"machine_serial\";s:13:\"6113161200173\";s:9:\"branch_id\";s:1:\"1\";s:8:\"acc_name\";s:29:\"Sample Project Master Account\";}logged_in|i:1;'),('tb093obpcsmc4nd0u5i11bbcp6giov2b','::1',1494848866,'__ci_last_regenerate|i:1494848866;tgm_user_id|s:6:\"100069\";login_status|s:1:\"1\";password_attempts|s:1:\"0\";user_id|s:6:\"100069\";username|s:5:\"m.rao\";email|s:24:\"m.rao@topgearmedia.co.uk\";user_type|s:1:\"1\";u_type|s:1:\"1\";user_template_id|s:1:\"1\";parent_id|s:5:\"50000\";user_info|O:8:\"stdClass\":31:{s:7:\"user_id\";s:6:\"100069\";s:6:\"parent\";s:5:\"50000\";s:12:\"parent_child\";s:5:\"50000\";s:9:\"user_type\";s:1:\"1\";s:6:\"u_type\";s:1:\"1\";s:16:\"user_template_id\";s:1:\"1\";s:10:\"first_name\";s:2:\"Mr\";s:7:\"surname\";s:3:\"Rao\";s:8:\"username\";s:5:\"m.rao\";s:5:\"email\";s:24:\"m.rao@topgearmedia.co.uk\";s:8:\"position\";s:3:\"ttt\";s:12:\"office_phone\";s:11:\"03433049856\";s:9:\"mob_phone\";s:11:\"03433049856\";s:3:\"fax\";s:1:\"0\";s:8:\"password\";s:32:\"0192023a7bbd73250516f069df18b500\";s:11:\"security_id\";s:1:\"1\";s:12:\"security_ans\";s:8:\"pakistan\";s:13:\"creation_date\";s:19:\"2013-04-12 02:54:10\";s:13:\"modified_date\";s:19:\"2016-01-01 07:20:47\";s:12:\"login_status\";s:1:\"1\";s:11:\"permission1\";N;s:9:\"reset_key\";s:32:\"fd4d18ad80c789efd1f1d8b734237a81\";s:6:\"status\";s:1:\"1\";s:11:\"acc_manager\";s:1:\"1\";s:10:\"reset_code\";s:0:\"\";s:11:\"auto_logout\";s:3:\"480\";s:11:\"disabled_by\";N;s:15:\"default_page_id\";s:3:\"108\";s:14:\"machine_serial\";s:13:\"6113161200173\";s:9:\"branch_id\";s:1:\"1\";s:8:\"acc_name\";s:29:\"Sample Project Master Account\";}logged_in|i:1;'),('au01fholskdtdblj0u1k2clsqo0vu1mh','::1',1494848543,'__ci_last_regenerate|i:1494848543;tgm_user_id|s:6:\"100069\";login_status|s:1:\"1\";password_attempts|s:1:\"0\";user_id|s:6:\"100069\";username|s:5:\"m.rao\";email|s:24:\"m.rao@topgearmedia.co.uk\";user_type|s:1:\"1\";u_type|s:1:\"1\";user_template_id|s:1:\"1\";parent_id|s:5:\"50000\";user_info|O:8:\"stdClass\":31:{s:7:\"user_id\";s:6:\"100069\";s:6:\"parent\";s:5:\"50000\";s:12:\"parent_child\";s:5:\"50000\";s:9:\"user_type\";s:1:\"1\";s:6:\"u_type\";s:1:\"1\";s:16:\"user_template_id\";s:1:\"1\";s:10:\"first_name\";s:2:\"Mr\";s:7:\"surname\";s:3:\"Rao\";s:8:\"username\";s:5:\"m.rao\";s:5:\"email\";s:24:\"m.rao@topgearmedia.co.uk\";s:8:\"position\";s:3:\"ttt\";s:12:\"office_phone\";s:11:\"03433049856\";s:9:\"mob_phone\";s:11:\"03433049856\";s:3:\"fax\";s:1:\"0\";s:8:\"password\";s:32:\"0192023a7bbd73250516f069df18b500\";s:11:\"security_id\";s:1:\"1\";s:12:\"security_ans\";s:8:\"pakistan\";s:13:\"creation_date\";s:19:\"2013-04-12 02:54:10\";s:13:\"modified_date\";s:19:\"2016-01-01 07:20:47\";s:12:\"login_status\";s:1:\"1\";s:11:\"permission1\";N;s:9:\"reset_key\";s:32:\"fd4d18ad80c789efd1f1d8b734237a81\";s:6:\"status\";s:1:\"1\";s:11:\"acc_manager\";s:1:\"1\";s:10:\"reset_code\";s:0:\"\";s:11:\"auto_logout\";s:3:\"480\";s:11:\"disabled_by\";N;s:15:\"default_page_id\";s:3:\"108\";s:14:\"machine_serial\";s:13:\"6113161200173\";s:9:\"branch_id\";s:1:\"1\";s:8:\"acc_name\";s:29:\"Sample Project Master Account\";}logged_in|i:1;'),('93f1ls057hd2d84kgob9k2dostkd2j2t','::1',1494849622,'__ci_last_regenerate|i:1494849622;tgm_user_id|s:6:\"100069\";login_status|s:1:\"1\";password_attempts|s:1:\"0\";user_id|s:6:\"100069\";username|s:5:\"m.rao\";email|s:24:\"m.rao@topgearmedia.co.uk\";user_type|s:1:\"1\";u_type|s:1:\"1\";user_template_id|s:1:\"1\";parent_id|s:5:\"50000\";user_info|O:8:\"stdClass\":31:{s:7:\"user_id\";s:6:\"100069\";s:6:\"parent\";s:5:\"50000\";s:12:\"parent_child\";s:5:\"50000\";s:9:\"user_type\";s:1:\"1\";s:6:\"u_type\";s:1:\"1\";s:16:\"user_template_id\";s:1:\"1\";s:10:\"first_name\";s:2:\"Mr\";s:7:\"surname\";s:3:\"Rao\";s:8:\"username\";s:5:\"m.rao\";s:5:\"email\";s:24:\"m.rao@topgearmedia.co.uk\";s:8:\"position\";s:3:\"ttt\";s:12:\"office_phone\";s:11:\"03433049856\";s:9:\"mob_phone\";s:11:\"03433049856\";s:3:\"fax\";s:1:\"0\";s:8:\"password\";s:32:\"0192023a7bbd73250516f069df18b500\";s:11:\"security_id\";s:1:\"1\";s:12:\"security_ans\";s:8:\"pakistan\";s:13:\"creation_date\";s:19:\"2013-04-12 02:54:10\";s:13:\"modified_date\";s:19:\"2016-01-01 07:20:47\";s:12:\"login_status\";s:1:\"1\";s:11:\"permission1\";N;s:9:\"reset_key\";s:32:\"fd4d18ad80c789efd1f1d8b734237a81\";s:6:\"status\";s:1:\"1\";s:11:\"acc_manager\";s:1:\"1\";s:10:\"reset_code\";s:0:\"\";s:11:\"auto_logout\";s:3:\"480\";s:11:\"disabled_by\";N;s:15:\"default_page_id\";s:3:\"108\";s:14:\"machine_serial\";s:13:\"6113161200173\";s:9:\"branch_id\";s:1:\"1\";s:8:\"acc_name\";s:29:\"Sample Project Master Account\";}logged_in|i:1;'),('upf5h66unh8p8qa19pm3osqti8houg6f','::1',1494850770,'__ci_last_regenerate|i:1494850770;tgm_user_id|s:6:\"100069\";login_status|s:1:\"1\";password_attempts|s:1:\"0\";user_id|s:6:\"100069\";username|s:5:\"m.rao\";email|s:24:\"m.rao@topgearmedia.co.uk\";user_type|s:1:\"1\";u_type|s:1:\"1\";user_template_id|s:1:\"1\";parent_id|s:5:\"50000\";user_info|O:8:\"stdClass\":31:{s:7:\"user_id\";s:6:\"100069\";s:6:\"parent\";s:5:\"50000\";s:12:\"parent_child\";s:5:\"50000\";s:9:\"user_type\";s:1:\"1\";s:6:\"u_type\";s:1:\"1\";s:16:\"user_template_id\";s:1:\"1\";s:10:\"first_name\";s:2:\"Mr\";s:7:\"surname\";s:3:\"Rao\";s:8:\"username\";s:5:\"m.rao\";s:5:\"email\";s:24:\"m.rao@topgearmedia.co.uk\";s:8:\"position\";s:3:\"ttt\";s:12:\"office_phone\";s:11:\"03433049856\";s:9:\"mob_phone\";s:11:\"03433049856\";s:3:\"fax\";s:1:\"0\";s:8:\"password\";s:32:\"0192023a7bbd73250516f069df18b500\";s:11:\"security_id\";s:1:\"1\";s:12:\"security_ans\";s:8:\"pakistan\";s:13:\"creation_date\";s:19:\"2013-04-12 02:54:10\";s:13:\"modified_date\";s:19:\"2016-01-01 07:20:47\";s:12:\"login_status\";s:1:\"1\";s:11:\"permission1\";N;s:9:\"reset_key\";s:32:\"fd4d18ad80c789efd1f1d8b734237a81\";s:6:\"status\";s:1:\"1\";s:11:\"acc_manager\";s:1:\"1\";s:10:\"reset_code\";s:0:\"\";s:11:\"auto_logout\";s:3:\"480\";s:11:\"disabled_by\";N;s:15:\"default_page_id\";s:3:\"108\";s:14:\"machine_serial\";s:13:\"6113161200173\";s:9:\"branch_id\";s:1:\"1\";s:8:\"acc_name\";s:29:\"Sample Project Master Account\";}logged_in|i:1;');

/*Table structure for table `email_templates` */

DROP TABLE IF EXISTS `email_templates`;

CREATE TABLE `email_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `email_content` text,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

/*Data for the table `email_templates` */

insert  into `email_templates`(`id`,`name`,`subject`,`email_content`,`status`,`created`) values (1,'New User Created','New Telebox Account Created','<html>\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\r\n<title>New User Created</title>\r\n</head>\r\n<body style=\"background: #fff; margin: 0px auto; font-family: Calibri;\">\r\n<div style=\"width: 620px;  background: #ffffff; margin:0 auto;\">\r\n  <div style=\"background:#275d90; height:65px;\"><img src=\"http://telebox.co.uk/assets/images/small_logo.png\" style=\"padding-left:15px; padding-top:15px;\" /></div>\r\n  <div style=\"padding:15px; \">\r\n    <p style=\"font-size: 21px; font-weight:bold;\">Hi {first_name},</p>\r\n    <p style=\"font-size: 16px;\">A new account has been created on Telebox.co.uk for you.</p>\r\n    <p style=\"font-size: 16px; padding-top:5px; font-weight:bold\"> Username: {username}<br>\r\n      Password: {password} </p>\r\n    <p style=\"font-size: 16px; padding-top:5px;\"> To access your account please visit <br/>\r\n      <a href=\"http://www.telebox.co.uk/portal\" style=\"color:#ee7b2a; font-weight:bold;\">http://www.telebox.co.uk/portal</a> <br>\r\n      <br>\r\n      You will be prompted to setup a security question and enter a new password when you first login. </p>\r\n    <p style=\"font-size: 16px;padding-top:5px;\"> Thanks,<br>\r\n      The Telebox Team </p>\r\n  </div>\r\n  <div style=\"background:#393939;\">\r\n    <div style=\"font-size: 15px; color:#949494; padding:15px;\">This email was sent to you as a registered user of <a href=\"www.telebox.co.uk/portal\" style=\"color:#949494\">www.telebox.co.uk</a><br />\r\n      If you need any assistance please contact your account manager, or email <a href=\"mailto:help@telebox.co.uk\" style=\"color:#949494\">help@telebox.co.uk</a></div>\r\n  </div>\r\n  <div style=\"background:#393939; border-top:1px solid #949494\">\r\n    <div style=\"font-size: 15px; color:#fff; padding:15px;\">Copyright &copy; {current_year} <a href=\"www.telebox.co.uk/portal\" style=\"color:#fff; text-decoration:none;\">telebox.co.uk</a> All rights reserved</div>\r\n  </div>\r\n</div>\r\n</div>\r\n</body>\r\n</html>',1,NULL),(2,'New Reseller User Created','New Telebox Account Created','<html>\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\r\n<title>New Reseller User Created</title>\r\n</head>\r\n<body style=\"background: #fff; margin: 0px auto; font-family: Calibri;\">\r\n<div style=\"width: 620px;  background: #ffffff; margin:0 auto;\">\r\n  <div style=\"background:#275d90; height:65px;\"><img src=\"http://telebox.co.uk/assets/images/small_logo.png\" style=\"padding-left:15px; padding-top:15px;\" /></div>\r\n  <div style=\"padding:15px; \">\r\n    <p style=\"font-size: 21px; font-weight:bold;\">Hi {first_name},</p>\r\n    <p style=\"font-size: 16px;\">A new account has been created on Telebox.co.uk for you.</p>\r\n    <p style=\"font-size: 16px; padding-top:5px; font-weight:bold\"> Username: {username}<br>\r\n      Password: {password} </p>\r\n    <p style=\"font-size: 16px; padding-top:5px;\"> To access your account please visit <br/>\r\n      <a href=\"http://www.telebox.co.uk/portal\" style=\"color:#ee7b2a; font-weight:bold;\">http://www.telebox.co.uk/portal</a> <br>\r\n      <br>\r\n      You will be prompted to setup a security question and enter a new password when you first login. </p>\r\n    <p style=\"font-size: 16px;padding-top:5px;\"> Thanks,<br>\r\n      The Telebox Team </p>\r\n  </div>\r\n  <div style=\"background:#393939;\">\r\n    <div style=\"font-size: 15px; color:#949494; padding:15px;\">This email was sent to you as a registered user of <a href=\"www.telebox.co.uk/portal\" style=\"color:#949494\">www.telebox.co.uk</a><br />\r\n      If you need any assistance please contact your account manager, or email <a href=\"mailto:help@telebox.co.uk\" style=\"color:#949494\">help@telebox.co.uk</a></div>\r\n  </div>\r\n  <div style=\"background:#393939; border-top:1px solid #949494\">\r\n    <div style=\"font-size: 15px; color:#fff; padding:15px;\">Copyright &copy; {current_year} <a href=\"www.telebox.co.uk/portal\" style=\"color:#fff; text-decoration:none;\">telebox.co.uk</a> All rights reserved</div>\r\n  </div>\r\n</div>\r\n</div>\r\n</body>\r\n</html>',1,NULL),(3,'New Admin User Created','New Telebox Account Created','<html>\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\r\n<title>New Admin User Created</title>\r\n</head>\r\n<body style=\"background: #fff; margin: 0px auto; font-family: Calibri;\">\r\n<div style=\"width: 620px;  background: #ffffff; margin:0 auto;\">\r\n  <div style=\"background:#275d90; height:65px;\"><img src=\"http://telebox.co.uk/assets/images/small_logo.png\" style=\"padding-left:15px; padding-top:15px;\" /></div>\r\n  <div style=\"padding:15px; \">\r\n    <p style=\"font-size: 21px; font-weight:bold;\">Hi {first_name},</p>\r\n    <p style=\"font-size: 16px;\">A new account has been created on Telebox.co.uk for you.</p>\r\n    <p style=\"font-size: 16px; padding-top:5px; font-weight:bold\"> Username: {username}<br>\r\n      Password: {password} </p>\r\n    <p style=\"font-size: 16px; padding-top:5px;\"> To access your account please visit <br/>\r\n      <a href=\"http://www.telebox.co.uk/portal\" style=\"color:#ee7b2a; font-weight:bold;\">http://www.telebox.co.uk/portal</a> <br>\r\n      <br>\r\n      You will be prompted to setup a security question and enter a new password when you first login. </p>\r\n    <p style=\"font-size: 16px;padding-top:5px;\"> Thanks,<br>\r\n      The Telebox Team </p>\r\n  </div>\r\n  <div style=\"background:#393939;\">\r\n    <div style=\"font-size: 15px; color:#949494; padding:15px;\">This email was sent to you as a registered user of <a href=\"www.telebox.co.uk/portal\" style=\"color:#949494\">www.telebox.co.uk</a><br />\r\n      If you need any assistance please contact your account manager, or email <a href=\"mailto:help@telebox.co.uk\" style=\"color:#949494\">help@telebox.co.uk</a></div>\r\n  </div>\r\n  <div style=\"background:#393939; border-top:1px solid #949494\">\r\n    <div style=\"font-size: 15px; color:#fff; padding:15px;\">Copyright &copy; {current_year} <a href=\"www.telebox.co.uk/portal\" style=\"color:#fff; text-decoration:none;\">telebox.co.uk</a> All rights reserved</div>\r\n  </div>\r\n</div>\r\n</div>\r\n</body>\r\n</html>',1,NULL),(5,'Forget password','Telebox Reset Password','<html>\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\r\n<title>Forget password</title>\r\n</head>\r\n<body style=\"background: #fff; margin: 0px auto; font-family: Calibri;\">\r\n<div style=\"width: 620px;  background: #ffffff; margin:0 auto;\">\r\n  <div style=\"background:#275d90; height:65px;\"><img src=\"http://telebox.co.uk/assets/images/small_logo.png\" style=\"padding-left:15px; padding-top:15px;\" /></div>\r\n  <div style=\"padding:15px; \">\r\n    <p style=\"font-size: 21px; font-weight:bold;\">Hi {first_name},</p>\r\n    <p style=\"font-size: 16px;\">A request to change your password has been received. Please click on the following link to change your password.</p>\r\n    <p style=\"font-size: 16px; padding-top:5px;\"> <a href=\"{site_url}admin/login/account_unlock/?key={reset_key}&acc_lock=0\" style=\"color:#ee7b2a; font-weight:bold;\">{site_url}admin/login/account_unlock/?key={reset_key}</a> </p>\r\n    <p style=\"font-size: 16px;\"> <strong>Note -</strong> this link is only active for 60 minutes from receipt of request.</p>\r\n    <p style=\"font-size: 16px;\"> If you did not request this, please contact your account manager immediately.</p>\r\n    <p style=\"font-size: 16px;padding-top:5px;\"> Thanks,<br>\r\n      The Telebox Team </p>\r\n  </div>\r\n  <div style=\"background:#393939;\">\r\n    <div style=\"font-size: 15px; color:#949494; padding:15px;\">This email was sent to you as a registered user of <a href=\"www.telebox.co.uk/portal\" style=\"color:#949494\">www.telebox.co.uk</a><br />\r\n      If you need any assistance please contact your account manager, or email <a href=\"mailto:help@telebox.co.uk\" style=\"color:#949494\">help@telebox.co.uk</a></div>\r\n  </div>\r\n  <div style=\"background:#393939; border-top:1px solid #949494\">\r\n    <div style=\"font-size: 15px; color:#fff; padding:15px;\">Copyright &copy; {current_year} <a href=\"www.telebox.co.uk/portal\" style=\"color:#fff; text-decoration:none;\">telebox.co.uk</a> All rights reserved</div>\r\n  </div>\r\n</div>\r\n</div>\r\n</body>\r\n</html>',1,NULL),(6,'Account Locked Out','Telebox Account Locked','<html>\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\r\n<title>Account Locked Out</title>\r\n</head>\r\n<body style=\"background: #fff; margin: 0px auto; font-family: Calibri;\">\r\n<div style=\"width: 620px;  background: #ffffff; margin:0 auto;\">\r\n  <div style=\"background:#275d90; height:65px;\"><img src=\"http://telebox.co.uk/assets/images/small_logo.png\" style=\"padding-left:15px; padding-top:15px;\" /></div>\r\n  <div style=\"padding:15px; \">\r\n    <p style=\"font-size: 21px; font-weight:bold;\">Hi {first_name},</p>\r\n    <p style=\"font-size: 16px;\">Your Telebox account was locked at {timedate} locked because someone has entered the password incorrectly 3 times. </p>\r\n    \r\n    <p style=\"font-size: 16px; padding-top:5px;\"> If this was you, please reset your password by following this link - <a href=\"{site_url}portal/login/forget_pass\">www.telebox.co.uk/beta/portal/login/forget_pass</a></p>\r\n    <p style=\"font-size: 16px;\"> If you have not logged into your account recently, please contact your account manager immediately.</p>\r\n   \r\n   \r\n    <p style=\"font-size: 16px;padding-top:5px;\"> Thanks,<br>\r\n      The Telebox Team </p>\r\n  </div>\r\n  <div style=\"background:#393939;\">\r\n    <div style=\"font-size: 15px; color:#949494; padding:15px;\">This email was sent to you as a registered user of <a href=\"www.telebox.co.uk/portal\" style=\"color:#949494\">www.telebox.co.uk</a><br />\r\n      If you need any assistance please contact your account manager, or email <a href=\"mailto:help@telebox.co.uk\" style=\"color:#949494\">help@telebox.co.uk</a></div>\r\n  </div>\r\n  <div style=\"background:#393939; border-top:1px solid #949494\">\r\n    <div style=\"font-size: 15px; color:#fff; padding:15px;\">Copyright &copy; {current_year} <a href=\"www.telebox.co.uk/portal\" style=\"color:#fff; text-decoration:none;\">telebox.co.uk</a> All rights reserved</div>\r\n  </div>\r\n</div>\r\n</div>\r\n</body>\r\n</html>',1,NULL),(7,'New Password Send','Telebox Password Reset','<html>\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\r\n<title>TeleBox Account Updated</title>\r\n</head>\r\n<body style=\"background: #fff; margin: 0px auto; font-family: Calibri;\">\r\n<div style=\"width: 620px;  background: #ffffff; margin:0 auto;\">\r\n  <div style=\"background:#275d90; height:65px;\"><img src=\"http://telebox.co.uk/assets/images/small_logo.png\" style=\"padding-left:15px; padding-top:15px;\" /></div>\r\n  <div style=\"padding:15px; \">\r\n    <p style=\"font-size: 21px; font-weight:bold;\">Hi {first_name},</p>\r\n    <p style=\"font-size: 16px;\">Your password has been changed:</p>\r\n    <p style=\"font-size: 16px; padding-top:5px; font-weight:bold\"> Username: {username}<br>\r\n      Password: {password} </p>\r\n    <p style=\"font-size: 16px; padding-top:5px;\"> You can access your account at <a href=\"http://www.telebox.co.uk/portal\" style=\"color:#ee7b2a; font-weight:bold;\">www.telebox.co.uk/portal</a> <br>\r\n      <br>\r\n   If you did not request your password to be reset, please contact you account manager immediately.</p>\r\n    <p style=\"font-size: 16px;padding-top:5px;\"> Thanks,<br>\r\n      The Telebox Team </p>\r\n  </div>\r\n  <div style=\"background:#393939;\">\r\n    <div style=\"font-size: 15px; color:#949494; padding:15px;\">This email was sent to you as a registered user of <a href=\"www.telebox.co.uk/portal\" style=\"color:#949494\">www.telebox.co.uk</a><br />\r\n      If you need any assistance please contact your account manager, or email <a href=\"mailto:help@telebox.co.uk\" style=\"color:#949494\">help@telebox.co.uk</a></div>\r\n  </div>\r\n  <div style=\"background:#393939; border-top:1px solid #949494\">\r\n    <div style=\"font-size: 15px; color:#fff; padding:15px;\">Copyright &copy; {current_year} <a href=\"www.telebox.co.uk/portal\" style=\"color:#fff; text-decoration:none;\">telebox.co.uk</a> All rights reserved</div>\r\n  </div>\r\n</div>\r\n</div>\r\n</body>\r\n</html>',1,NULL),(9,'Forget user-old','Misplaced Numbers Telecom Username','<html>\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\r\n<title>Forget user</title>\r\n</head>\r\n<body style=\"background: #fff; margin: 0px auto; font-family: Calibri;\">\r\n<div style=\"width: 620px;  background: #ffffff; margin:0 auto;\">\r\n  <div style=\"background:#275d90; height:65px;\"><img src=\"http://telebox.co.uk/assets/images/small_logo.png\" style=\"padding-left:15px; padding-top:15px;\" /></div>\r\n  <div style=\"padding:15px; \">\r\n    <p style=\"font-size: 21px; font-weight:bold;\">Hi {first_name},</p>\r\n    <p style=\"font-size: 16px;\">You have asked us to look up the username associated with the email address {email}</p>\r\n    <p style=\"font-size: 16px; padding-top:5px; font-weight:bold\"> It is : {username} </p>\r\n    <p style=\"font-size: 16px; padding-top:5px;\"> If you have forgotten your password please use the reset password link here <a href=\"{current_url}\" style=\"color:#ee7b2a; font-weight:bold;\">Click Here</a> </p>\r\n    <p style=\"font-size: 16px;padding-top:5px;\"> Thanks,<br>\r\n      The Telebox Team </p>\r\n  </div>\r\n  <div style=\"background:#393939;\">\r\n    <div style=\"font-size: 15px; color:#949494; padding:15px;\">This email was sent to you as a registered user of <a href=\"www.telebox.co.uk/portal\" style=\"color:#949494\">www.telebox.co.uk</a><br />\r\n      If you need any assistance please contact your account manager, or email <a href=\"mailto:help@telebox.co.uk\" style=\"color:#949494\">help@telebox.co.uk</a></div>\r\n  </div>\r\n  <div style=\"background:#393939; border-top:1px solid #949494\">\r\n    <div style=\"font-size: 15px; color:#fff; padding:15px;\">Copyright &copy; {current_year} <a href=\"www.telebox.co.uk/portal\" style=\"color:#fff; text-decoration:none;\">telebox.co.uk</a> All rights reserved</div>\r\n  </div>\r\n</div>\r\n</div>\r\n</body>\r\n</html>',1,NULL),(11,'Invalid Reset Code','User entered password reset code incorrectly','<html>\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\r\n<title>Invalid Reset Code</title>\r\n</head>\r\n<body style=\"background: #fff; margin: 0px auto; font-family: Calibri;\">\r\n<div style=\"width: 620px;  background: #ffffff; margin:0 auto;\">\r\n  <div style=\"background:#275d90; height:65px;\"><img src=\"http://telebox.co.uk/assets/images/small_logo.png\" style=\"padding-left:15px; padding-top:15px;\" /></div>\r\n  <div style=\"padding:15px; \">\r\n    <p style=\"font-size: 21px; font-weight:bold;\">Hi ,</p>\r\n    <p style=\"font-size: 16px;\">The following user has attempted to reset their password but incorrectly entered the reset\r\ncode 3 times: </p>\r\n    \r\n    <p style=\"font-size: 16px; padding-top:5px;\"> \r\n     <table border=\"1\" cellpadding=\"6\" cellspacing=\"0\" style=\"width:100%\">\r\n                <tbody><tr>\r\n                    <td style=\"width:30%\"> Name:</td>\r\n                    <td>{first_name} {surname}</td>\r\n                </tr>\r\n                <tr>\r\n                    <td style=\"width:30%\">Company:</td>\r\n                    <td style=\"width:70%\">{acc_name}</td>\r\n                </tr>\r\n                <tr>\r\n                    <td style=\"width:30%\">IP:</td>\r\n                   <td style=\"width:70%\">{ip_address}</td>\r\n                </tr>\r\n            </tbody></table>\r\n       </p>\r\n   \r\n    <p style=\"font-size: 16px;padding-top:5px;\"> Thanks,<br>\r\n      The Telebox Team </p>\r\n  </div>\r\n  <div style=\"background:#393939;\">\r\n    <div style=\"font-size: 15px; color:#949494; padding:15px;\">This email was sent to you as a registered user of <a href=\"www.telebox.co.uk/portal\" style=\"color:#949494\">www.telebox.co.uk</a><br />\r\n      If you need any assistance please contact your account manager, or email <a href=\"mailto:help@telebox.co.uk\" style=\"color:#949494\">help@telebox.co.uk</a></div>\r\n  </div>\r\n  <div style=\"background:#393939; border-top:1px solid #949494\">\r\n    <div style=\"font-size: 15px; color:#fff; padding:15px;\">Copyright &copy; {current_year} <a href=\"www.telebox.co.uk/portal\" style=\"color:#fff; text-decoration:none;\">telebox.co.uk</a> All rights reserved</div>\r\n  </div>\r\n</div>\r\n</div>\r\n</body>\r\n</html>',1,NULL),(13,'New Password Reset','Telebox Password Updated','<html>\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\r\n<title>TeleBox Account Updated</title>\r\n</head>\r\n<body style=\"background: #fff; margin: 0px auto; font-family: Calibri;\">\r\n<div style=\"width: 620px;  background: #ffffff; margin:0 auto;\">\r\n  <div style=\"background:#275d90; height:65px;\"><img src=\"http://telebox.co.uk/assets/images/small_logo.png\" style=\"padding-left:15px; padding-top:15px;\" /></div>\r\n  <div style=\"padding:15px; \">\r\n    <p style=\"font-size: 21px; font-weight:bold;\">Hi {first_name},</p>\r\n    <p style=\"font-size: 16px;\">Your Telebox login details have recently been updated :</p>\r\n    <p style=\"font-size: 16px; padding-top:5px; font-weight:bold\"> Username: {username}<br>\r\n      Password: {password} </p>\r\n    <p style=\"font-size: 16px; padding-top:5px;\"> You can access your account at <a href=\"http://www.telebox.co.uk/portal\" style=\"color:#ee7b2a; font-weight:bold;\">www.telebox.co.uk/portal</a> <br>\r\n      <br>\r\n    If the password change was not requested, please contact your account manager immediately.</p>\r\n    <p style=\"font-size: 16px;padding-top:5px;\"> Thanks,<br>\r\n      The Telebox Team </p>\r\n  </div>\r\n  <div style=\"background:#393939;\">\r\n    <div style=\"font-size: 15px; color:#949494; padding:15px;\">This email was sent to you as a registered user of <a href=\"www.telebox.co.uk/portal\" style=\"color:#949494\">www.telebox.co.uk</a><br />\r\n      If you need any assistance please contact your account manager, or email <a href=\"mailto:help@telebox.co.uk\" style=\"color:#949494\">help@telebox.co.uk</a></div>\r\n  </div>\r\n  <div style=\"background:#393939; border-top:1px solid #949494\">\r\n    <div style=\"font-size: 15px; color:#fff; padding:15px;\">Copyright &copy; {current_year} <a href=\"www.telebox.co.uk/portal\" style=\"color:#fff; text-decoration:none;\">telebox.co.uk</a> All rights reserved</div>\r\n  </div>\r\n</div>\r\n</div>\r\n</body>\r\n</html>',1,NULL),(15,'Locked Account Attempted to Login','Locked Account Attempted to Login','<html>\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\r\n<title>TeleBox Account Updated</title>\r\n</head>\r\n<body style=\"background: #fff; margin: 0px auto; font-family: Calibri;\">\r\n<div style=\"width: 620px;  background: #ffffff; margin:0 auto;\">\r\n  <div style=\"background:#275d90; height:65px;\"><img src=\"http://telebox.co.uk/assets/images/small_logo.png\" style=\"padding-left:15px; padding-top:15px;\" /></div>\r\n  <div style=\"padding:15px; \">\r\n    <p style=\"font-size: 21px; font-weight:bold;\">Hi,</p>\r\n    <p style=\"font-size: 16px;\">The following disabled user attempted to login to Telebox:</p>\r\n    <p style=\"font-size: 16px; padding-top:5px; font-weight:bold\"> Account: {acc_name}<br>\r\n   Name: {first_name} {surname} </p>\r\n    <p style=\"font-size: 16px;padding-top:5px;\"> Thanks,<br>\r\n      The Telebox Team </p>\r\n  </div>\r\n  <div style=\"background:#393939;\">\r\n  <div style=\"background:#393939; border-top:1px solid #949494\">\r\n    <div style=\"font-size: 15px; color:#fff; padding:15px;\">Copyright &copy; {current_year} <a href=\"www.telebox.co.uk/portal\" style=\"color:#fff; text-decoration:none;\">telebox.co.uk</a> All rights reserved</div>\r\n  </div>\r\n</div>\r\n</div>\r\n</body>\r\n</html>',1,NULL),(19,'Forget user','Telebox Username Reminder','<html>\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\r\n<title>Forget user</title>\r\n</head>\r\n<body style=\"background: #fff; margin: 0px auto; font-family: Calibri;\">\r\n<div style=\"width: 620px;  background: #ffffff; margin:0 auto;\">\r\n  <div style=\"background:#275d90; height:65px;\"><img src=\"http://telebox.co.uk/assets/images/small_logo.png\" style=\"padding-left:15px; padding-top:15px;\" /></div>\r\n  <div style=\"padding:15px; \">\r\n    <p style=\"font-size: 21px; font-weight:bold;\">Hi {first_name},</p>\r\n    <p style=\"font-size: 16px;\">You have recently requested a reminder of your Telebox username, it is : <span style=\"color: #ee7b2a;font-weight: bold;\">{username}</span></p>    \r\n    <p style=\"font-size: 16px; padding-top:5px;\"> If you have forgotten your password please use the reset password link - <br/> <a href=\"{forget_password}\" style=\"color:#ee7b2a; font-weight:bold;\">{forget_password}</a> </p>\r\n    <p style=\"font-size: 16px; padding-top:5px;\"> Should you have not requested a reminder of your username, please contact your account manager immediately.</p>\r\n    <p style=\"font-size: 16px;padding-top:5px;\"> Thanks,<br>\r\n      The Telebox Team </p>\r\n  </div>\r\n  <div style=\"background:#393939;\">\r\n    <div style=\"font-size: 15px; color:#949494; padding:15px;\">This email was sent to you as a registered user of <a href=\"www.telebox.co.uk/portal\" style=\"color:#949494\">www.telebox.co.uk</a><br />\r\n      If you need any assistance please contact your account manager, or email <a href=\"mailto:help@telebox.co.uk\" style=\"color:#949494\">help@telebox.co.uk</a></div>\r\n  </div>\r\n  <div style=\"background:#393939; border-top:1px solid #949494\">\r\n    <div style=\"font-size: 15px; color:#fff; padding:15px;\">Copyright &copy; {current_year} <a href=\"www.telebox.co.uk/portal\" style=\"color:#fff; text-decoration:none;\">telebox.co.uk</a> All rights reserved</div>\r\n  </div>\r\n</div>\r\n</div>\r\n</body>\r\n</html>',1,NULL);

/*Table structure for table `invoices` */

DROP TABLE IF EXISTS `invoices`;

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acc_id` int(11) DEFAULT NULL COMMENT 'acc_id from account',
  `amount` varchar(255) DEFAULT NULL COMMENT 'fees amount',
  `fees_dateitime` datetime DEFAULT NULL,
  `fees_month` date DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL COMMENT '1=monthly fees,2=member type fees,3= subscription,4= special days fees',
  `status` tinyint(1) DEFAULT NULL COMMENT '1= generated,2=paid',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `invoices` */

insert  into `invoices`(`id`,`acc_id`,`amount`,`fees_dateitime`,`fees_month`,`type`,`status`) values (1,50001,'300','2017-05-15 12:20:33','2017-05-01',1,1);

/*Table structure for table `modules` */

DROP TABLE IF EXISTS `modules`;

CREATE TABLE `modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(255) DEFAULT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `module_title` varchar(255) DEFAULT NULL,
  `ordering` int(4) NOT NULL DEFAULT '0',
  `show_on_menu` tinyint(1) NOT NULL DEFAULT '1',
  `actions` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT 'tb-arrow',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('active','inactive','deleted') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=latin1;

/*Data for the table `modules` */

insert  into `modules`(`id`,`module`,`parent_id`,`module_title`,`ordering`,`show_on_menu`,`actions`,`icon`,`created`,`status`) values (3,'user_types',22,'Master Permissions',1,1,'add|edit|delete','tb-arrow','2015-07-24 11:37:51','active'),(22,'#',0,'Admin',7,1,NULL,'tb-admin','2014-04-29 19:27:50','active'),(24,'logout',0,'Logout',9,1,NULL,'tb-logout','2014-04-29 19:28:17','active'),(40,'users_admin',22,'Users',1,1,'add|edit|delete|send_new_password|acc_status','tb-arrow','2014-07-04 08:36:10','active'),(41,'companies_admin',22,'Companies',2,1,'add|edit|delete|status','tb-arrow','2017-05-08 16:16:51','inactive'),(42,'reseller_admin',22,'Reseller',3,1,'add|edit|delete','tb-arrow','2017-05-08 16:17:01','inactive'),(108,'dashboard',0,'Dashboard',0,1,'','tb-dashboard','2016-02-23 08:16:38','active'),(116,'modules',0,'Modules',4,1,'add|edit|delete','tb-module','2017-05-03 18:14:39','active'),(117,'accounts',0,'Members',2,1,'','tb-accounts','2017-05-04 15:46:59','active'),(118,'members',117,'All Members',1,1,'add|edit|view|delete|view_attendance','tb-arrow','2017-05-05 16:46:51','active'),(119,'members_types',117,'Members Types',2,1,'add|edit|view|delete','tb-arrow','2017-05-04 16:03:43','active'),(120,'attendance',117,'Attendance',1,1,'add|edit|view|delete','tb-arrow','2017-05-05 15:48:55','active'),(121,'branches',22,'Branches',3,1,'add|edit|view|delete','tb-arrow','2017-05-08 16:25:08','active'),(122,'fees_management',0,'Fees Management',2,1,'','tb-statistics','2017-05-15 10:34:15','active'),(123,'Invoices',122,'invoices',1,1,'add|edit|view|delete','tb-arrow','2017-05-15 10:34:01','active');

/*Table structure for table `options` */

DROP TABLE IF EXISTS `options`;

CREATE TABLE `options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `option_name` varchar(255) DEFAULT NULL,
  `option_value` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*Data for the table `options` */

insert  into `options`(`id`,`option_name`,`option_value`) values (1,'header_nav','1'),(2,'client_panel_nav','5'),(3,'footer_nav','2'),(4,'admin_user_type','1'),(5,'iic_user_type','3'),(6,'unallocated_acc_id','500020'),(7,'email_admin_from','Sample Project'),(8,'email_admin','muhammadrao1988@gmail.com'),(9,'email_admin_noreply','muhammadrao1988@gmail.com');

/*Table structure for table `subscriptions` */

DROP TABLE IF EXISTS `subscriptions`;

CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `period` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `period_duration` varchar(255) DEFAULT NULL,
  `charges` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `subscriptions` */

insert  into `subscriptions`(`id`,`period`,`name`,`branch_id`,`datetime`,`period_duration`,`charges`,`status`) values (1,3,'Regular',1,'2017-05-12 16:47:51','3 months',1500,1),(2,1,'Annual',1,'2017-05-12 16:47:49','1 year',2500,1);

/*Table structure for table `user_template_methods` */

DROP TABLE IF EXISTS `user_template_methods`;

CREATE TABLE `user_template_methods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_type_id` int(11) DEFAULT NULL COMMENT 'users:user_template_id',
  `acc_id` int(11) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  `actions` varchar(255) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(4) DEFAULT '1',
  `override_feature` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `GROUP_LIST_INDEX` (`id`,`user_type_id`,`acc_id`,`module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5788 DEFAULT CHARSET=latin1;

/*Data for the table `user_template_methods` */

insert  into `user_template_methods`(`id`,`user_type_id`,`acc_id`,`module_id`,`actions`,`datetime`,`status`,`override_feature`) values (5122,1,50000,108,NULL,'2016-11-11 10:45:37',1,0),(5159,1,50000,22,NULL,'2016-11-11 10:45:38',1,0),(5160,1,50000,40,'add|edit|delete|send_new_password|acc_status','2016-11-11 10:45:38',1,0),(5161,1,50000,3,'add|edit|delete','2016-11-11 10:45:38',1,0),(5179,1,50000,24,NULL,'2016-11-11 10:45:38',1,0),(5780,1,50000,116,'add|edit|delete','2017-05-03 13:13:34',1,0),(5781,1,50000,117,'add|edit|view|delete','2017-05-04 12:10:29',1,0),(5782,1,50000,118,'add|edit|view|delete','2017-05-04 12:48:59',1,0),(5783,1,50000,119,'add|edit|view|delete','2017-05-04 12:58:28',1,0),(5784,1,50000,120,'add|view|delete','2017-05-05 08:56:02',1,0),(5785,1,50000,121,'add|edit|view|delete','2017-05-08 13:25:25',1,0),(5786,1,50000,122,NULL,'2017-05-12 14:09:00',1,0),(5787,1,50000,123,'add|edit|view|delete','2017-05-15 07:34:34',1,0);

/*Table structure for table `user_type_module_rel` */

DROP TABLE IF EXISTS `user_type_module_rel`;

CREATE TABLE `user_type_module_rel` (
  `user_type_id` int(11) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  `actions` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `user_type_module_rel` */

insert  into `user_type_module_rel`(`user_type_id`,`module_id`,`actions`) values (5,17,NULL),(5,30,'edit|view|checkboxes'),(5,25,'add|edit|view'),(5,27,NULL),(5,11,NULL),(5,31,'add|edit|delete|view|print'),(5,33,'add|edit|delete|view'),(5,19,NULL),(5,36,'add|edit|delete'),(5,37,'view'),(5,20,NULL),(5,21,NULL),(5,24,NULL),(7,11,NULL),(7,31,NULL),(7,33,NULL),(7,50,NULL),(7,34,NULL),(7,35,NULL),(7,51,NULL),(7,19,NULL),(7,36,NULL),(7,37,NULL),(7,21,NULL),(7,24,NULL),(7,5,NULL),(7,2,'edit'),(9,17,NULL),(9,25,'add|edit|delete|view'),(9,11,NULL),(9,31,'add|edit|delete|view'),(9,19,NULL),(9,36,'add|edit|delete'),(9,37,'view'),(9,24,NULL),(11,17,NULL),(11,25,'add|edit|delete|view'),(11,26,NULL),(11,11,NULL),(11,31,'add|edit|delete|view'),(11,33,'add|edit|delete|view'),(11,50,NULL),(11,51,'view'),(11,19,NULL),(11,36,'add|edit|delete'),(11,37,'view'),(11,38,NULL),(11,20,NULL),(11,65,NULL),(11,21,NULL),(11,24,NULL),(12,17,NULL),(12,25,'add|edit|delete|view|report_number'),(12,11,NULL),(12,31,'add|edit|delete|view'),(12,24,NULL),(3,108,NULL),(3,17,NULL),(3,25,'edit|view'),(3,11,NULL),(3,31,'add|edit|delete|view'),(3,33,'add|edit|delete|view'),(3,19,NULL),(3,36,'add|edit|delete'),(3,37,'view'),(3,38,'view'),(3,65,NULL),(3,67,'add|edit|delete|view'),(3,21,NULL),(3,24,NULL),(4,108,NULL),(4,17,NULL),(4,25,'edit|view'),(4,11,NULL),(4,31,NULL),(4,19,NULL),(4,36,'add|edit|delete'),(4,37,'view'),(4,39,NULL),(4,24,NULL),(2,108,NULL),(2,17,NULL),(2,30,NULL),(2,25,'add|edit|delete'),(2,26,NULL),(2,27,NULL),(2,28,NULL),(2,29,NULL),(2,11,NULL),(2,31,NULL),(2,33,NULL),(2,103,'add|edit|delete'),(2,50,'reseller_range_view|reseller_customer_breakdown|reseller_manage_account'),(2,34,NULL),(2,95,NULL),(2,19,NULL),(2,36,'add|edit|delete'),(2,37,NULL),(2,38,NULL),(2,39,NULL),(2,20,NULL),(2,21,NULL),(2,22,NULL),(2,40,'add'),(2,3,'add|edit|delete'),(2,41,'add'),(2,42,'add'),(2,23,NULL),(2,43,NULL),(2,44,'add'),(2,49,NULL),(2,45,NULL),(2,46,'add|edit|delete'),(2,93,'edit|add|delete'),(2,47,NULL),(2,48,NULL),(2,24,NULL),(2,1,'add|edit|delete|status'),(13,108,NULL),(13,11,NULL),(13,31,'view'),(13,36,'add|edit|delete'),(13,37,'view'),(13,105,NULL),(13,24,NULL),(13,114,NULL),(14,108,NULL),(1,108,NULL),(1,122,NULL),(1,123,'add|edit|view|delete'),(1,117,NULL),(1,120,'add|edit|view|delete'),(1,118,'add|edit|view|delete|view_attendance'),(1,119,'add|edit|view|delete'),(1,116,'add|edit|delete'),(1,22,NULL),(1,3,'add|edit|delete'),(1,40,'add|edit|delete|send_new_password|acc_status'),(1,121,'add|edit|view|delete'),(1,24,NULL);

/*Table structure for table `user_types` */

DROP TABLE IF EXISTS `user_types`;

CREATE TABLE `user_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_type` varchar(255) DEFAULT NULL,
  `user_status` int(1) DEFAULT '1',
  `last_edited` datetime DEFAULT NULL,
  `edited_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `user_types` */

insert  into `user_types`(`id`,`user_type`,`user_status`,`last_edited`,`edited_by`) values (1,'Super Admin',1,'2014-07-23 08:48:08',100069),(2,'Administrator',1,NULL,NULL),(3,'Reseller',1,'2014-07-14 09:42:43',100001),(4,'Customer',1,'2014-07-11 03:10:37',100001);

/*Table structure for table `user_types_template` */

DROP TABLE IF EXISTS `user_types_template`;

CREATE TABLE `user_types_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_type` varchar(255) DEFAULT NULL,
  `user_status` int(1) DEFAULT '1',
  `last_edited` datetime DEFAULT NULL,
  `edited_by` int(11) DEFAULT NULL,
  `user_type_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

/*Data for the table `user_types_template` */

insert  into `user_types_template`(`id`,`user_type`,`user_status`,`last_edited`,`edited_by`,`user_type_id`) values (1,'Super Admin',1,'2017-05-15 07:34:24',100069,1),(12,'Customer - Reports And Numbers Only',1,'2016-02-22 12:37:15',100001,4),(14,'Finance',1,'2017-05-03 01:09:11',100069,4);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent` int(11) NOT NULL COMMENT 'main reseller',
  `parent_child` int(11) DEFAULT NULL COMMENT 'reseller company id',
  `user_type` int(11) DEFAULT NULL COMMENT 'related to acc_type table',
  `u_type` int(11) DEFAULT NULL COMMENT 'related to uset_type table',
  `user_template_id` int(11) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `position` varchar(255) DEFAULT NULL,
  `office_phone` varchar(25) DEFAULT NULL,
  `mob_phone` varchar(25) DEFAULT NULL,
  `fax` double DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `security_id` int(11) DEFAULT NULL,
  `security_ans` varchar(255) DEFAULT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` datetime DEFAULT NULL,
  `login_status` int(11) NOT NULL DEFAULT '0' COMMENT '0=New User but did not login 1=Active 2=Account was locked. Admin reset account but user did not login',
  `permission1` varchar(255) DEFAULT NULL,
  `reset_key` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '0 mean disable, 1 mean active, 2 mean lock by admin 3 mean lock by invalid password',
  `acc_manager` tinyint(1) DEFAULT '0',
  `reset_code` varchar(4) DEFAULT NULL,
  `auto_logout` int(11) DEFAULT '60' COMMENT 'value min. by default 1 hour',
  `disabled_by` int(11) DEFAULT NULL COMMENT 'admin user_id who disabled the user...',
  `default_page_id` int(11) DEFAULT NULL COMMENT 'when user login it will redirect to particular module',
  `machine_serial` varchar(255) DEFAULT NULL COMMENT 'machine serial number',
  `branch_id` int(11) DEFAULT NULL COMMENT 'branch id in branch table',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `index2` (`username`,`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=100070 DEFAULT CHARSET=latin1;

/*Data for the table `users` */

insert  into `users`(`user_id`,`parent`,`parent_child`,`user_type`,`u_type`,`user_template_id`,`first_name`,`surname`,`username`,`email`,`position`,`office_phone`,`mob_phone`,`fax`,`password`,`security_id`,`security_ans`,`creation_date`,`modified_date`,`login_status`,`permission1`,`reset_key`,`status`,`acc_manager`,`reset_code`,`auto_logout`,`disabled_by`,`default_page_id`,`machine_serial`,`branch_id`) values (100069,50000,50000,1,1,1,'Mr','Rao','m.rao','m.rao@topgearmedia.co.uk','ttt','03433049856','03433049856',0,'0192023a7bbd73250516f069df18b500',1,'pakistan','2013-04-12 02:54:10','2016-01-01 07:20:47',1,NULL,'fd4d18ad80c789efd1f1d8b734237a81',1,1,'',480,NULL,108,'6113161200173',1);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
