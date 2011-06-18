-- MySQL dump 10.13  Distrib 5.1.54, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: omf_assets
-- ------------------------------------------------------
-- Server version	5.1.54-1ubuntu4

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

drop database if exists omf_assets;
create database if not exists omf_assets;
use omf_assets;

--
-- Table structure for table `Attributes`
--

DROP TABLE IF EXISTS `Attributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Attributes` (
  `attrId` int(11) NOT NULL AUTO_INCREMENT,
  `attrName` varchar(32) NOT NULL,
  PRIMARY KEY (`attrId`),
  UNIQUE KEY `attrName` (`attrName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ci`
--

DROP TABLE IF EXISTS `ci`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ci` (
  `ciid` int(11) NOT NULL AUTO_INCREMENT,
  `ciName` varchar(64) NOT NULL,
  `ciDesc` text,
  `ownerType` enum('USER','GROUP') not null default 'USER',
  `ownerId` int(11) NOT NULL,
  `projectId` int(11) NOT NULL,
  `statusId` int(11) NOT NULL,
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mtime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `phyParentId` int(11) DEFAULT NULL,
  `netParentId` int(11) DEFAULT NULL,
  `ciTypeId` int(11) NOT NULL,
  `isRetired` tinyint(4) NOT NULL DEFAULT '0',
  `ciSerialNum` varchar(64) NOT NULL,
  `locId` int(11) NOT NULL,
  `acquiredDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `displosalDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ciid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ciAttributes`
--

DROP TABLE IF EXISTS `ciAttributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ciAttributes` (
  `ciid` int(11) NOT NULL,
  `attrId` int(11) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`ciid`,`attrId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ciContracts`
--

DROP TABLE IF EXISTS `ciContracts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ciContracts` (
  `ciid` int(11) NOT NULL,
  `contractId` int(11) NOT NULL,
  PRIMARY KEY (`ciid`,`contractId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ciServices`
--

DROP TABLE IF EXISTS `ciServices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ciServices` (
  `ciid` int(11) NOT NULL,
  `serviceId` int(11) NOT NULL,
  PRIMARY KEY (`ciid`,`serviceId`),
  KEY `serviceId` (`serviceId`),
  KEY `ciid` (`ciid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ciStatus`
--

DROP TABLE IF EXISTS `ciStatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ciStatus` (
  `ciStatusId` int(11) NOT NULL AUTO_INCREMENT,
  `statusName` varchar(32) NOT NULL,
  `statusDesc` text,
  PRIMARY KEY (`ciStatusId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ciType`
--

DROP TABLE IF EXISTS `ciType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ciType` (
  `ciTypeId` int(11) NOT NULL AUTO_INCREMENT,
  `typeName` varchar(32) NOT NULL,
  `typeDesc` text,
  PRIMARY KEY (`ciTypeId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contracts`
--

DROP TABLE IF EXISTS `contracts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contracts` (
  `contractId` int(11) NOT NULL AUTO_INCREMENT,
  `contractCompId` int(11) NOT NULL,
  `contractType` text NOT NULL,
  `contractStart` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `contractEnd` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `details` text,
  PRIMARY KEY (`contractId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `extCompany`
--

DROP TABLE IF EXISTS `extCompany`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `extCompany` (
  `compId` int(11) NOT NULL AUTO_INCREMENT,
  `compType` enum('partner','vendor') NOT NULL DEFAULT 'vendor',
  `compName` varchar(64) NOT NULL,
  `compAddr` text,
  PRIMARY KEY (`compId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `location`
--

DROP TABLE IF EXISTS `location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `location` (
  `locId` int(11) NOT NULL AUTO_INCREMENT,
  `locName` varchar(128) NOT NULL,
  `locDesc` text,
  `locOwnerType` enum('USER','GROUP') not null default 'USER',
  `locOwner` int(11) NOT NULL,
  `locAddr` text,
  PRIMARY KEY (`locId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projects` (
  `projId` int(11) NOT NULL AUTO_INCREMENT,
  `projName` varchar(128) NOT NULL,
  `projDesc` text,
  `projOwner` int(11) NOT NULL,
  PRIMARY KEY (`projId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `services` (
  `serviceId` int(11) NOT NULL AUTO_INCREMENT,
  `serviceName` varchar(64) NOT NULL,
  `ownerId` int(11) NOT NULL,
  PRIMARY KEY (`serviceId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-06-13 10:43:30
