-- MySQL dump 10.13  Distrib 5.1.54, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: omf_incident
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

--
-- Table structure for table `bugSystems`
--

DROP TABLE IF EXISTS `bugSystems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bugSystems` (
  `sysId` int(11) NOT NULL AUTO_INCREMENT,
  `systemName` varchar(32) NOT NULL,
  `systemDesc` text,
  `systemLink` text,
  PRIMARY KEY (`sysId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `impacted`
--

DROP TABLE IF EXISTS `impacted`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `impacted` (
  `impactId` int(11) NOT NULL AUTO_INCREMENT,
  `incidentId` int(11) NOT NULL,
  `impactType` enum('asset','project','cmr') NOT NULL,
  `impactValue` int(11) NOT NULL,
  `impactDesc` text,
  `impactSeverity` int(11) DEFAULT NULL,
  PRIMARY KEY (`impactId`),
  KEY `incidentId` (`incidentId`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `incident`
--

DROP TABLE IF EXISTS `incident`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `incident` (
  `incidentId` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  `statusId` int(11) NOT NULL,
  `start_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `createdBy` int(11) NOT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `severity` int(11) NOT NULL,
  `impact` text,
  `revImpact` text,
  `description` text,
  `resolveTime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `resolveSteps` text,
  `respProject` int(11) DEFAULT NULL,
  `detect_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mtime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`incidentId`),
  KEY `severity` (`severity`),
  KEY `statusId` (`statusId`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `incidentHistory`
--

DROP TABLE IF EXISTS `incidentHistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `incidentHistory` (
  `histId` int(11) NOT NULL AUTO_INCREMENT,
  `incidentId` int(11) NOT NULL,
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mUser` int(11) NOT NULL,
  `changes` text NOT NULL,
  PRIMARY KEY (`histId`),
  KEY `incidentId` (`incidentId`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `remTasks`
--

DROP TABLE IF EXISTS `remTasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `remTasks` (
  `taskId` int(11) NOT NULL AUTO_INCREMENT,
  `remId` int(11) NOT NULL,
  `ownerId` int(11) NOT NULL,
  `systemId` int(11) NOT NULL,
  `tktNum` varchar(64) NOT NULL,
  `dueDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isComplete` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`taskId`),
  KEY `remId` (`remId`),
  KEY `ownerId` (`ownerId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `remediation`
--

DROP TABLE IF EXISTS `remediation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `remediation` (
  `remId` int(11) NOT NULL AUTO_INCREMENT,
  `incidentId` int(11) NOT NULL,
  `ownerId` int(11) NOT NULL,
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mtime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `notes` text,
  `rootCauseCat` int(11) DEFAULT NULL,
  `rootCauseId` int(11) DEFAULT NULL,
  `rootCauseDesc` text,
  PRIMARY KEY (`remId`),
  KEY `incidentId` (`incidentId`),
  KEY `ownerId` (`ownerId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rootCauses`
--

DROP TABLE IF EXISTS `rootCauses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rootCauses` (
  `causeId` int(11) NOT NULL AUTO_INCREMENT,
  `causeType` enum('category','rootcause') NOT NULL,
  `name` varchar(32) NOT NULL,
  `description` text,
  PRIMARY KEY (`causeId`),
  KEY `causeType` (`causeType`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `severity`
--

DROP TABLE IF EXISTS `severity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `severity` (
  `sevId` int(11) NOT NULL AUTO_INCREMENT,
  `sevName` varchar(32) NOT NULL,
  `sevDesc` text,
  PRIMARY KEY (`sevId`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `status`
--

DROP TABLE IF EXISTS `status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `status` (
  `statusId` int(11) NOT NULL AUTO_INCREMENT,
  `statusName` varchar(32) NOT NULL,
  `statusDesc` text,
  `orderNum` int(11) DEFAULT NULL,
  PRIMARY KEY (`statusId`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `worklog`
--

DROP TABLE IF EXISTS `worklog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `worklog` (
  `workLogId` int(11) NOT NULL AUTO_INCREMENT,
  `incidentId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `wlType` enum('WORKLOG','STATUS','SOCIAL') NOT NULL,
  `data` text,
  PRIMARY KEY (`workLogId`),
  KEY `incidentId` (`incidentId`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-07-13 13:22:20
