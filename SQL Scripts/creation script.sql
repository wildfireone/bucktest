-- phpMyAdmin SQL Dump
-- version 4.0.9
-- https://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 02, 2014 at 09:28 PM
-- Server version: 5.5.34
-- PHP Version: 5.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `basc_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `club_records`
--

CREATE TABLE IF NOT EXISTS `club_records` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`strokeOld` char(2),
	`StrokeID` int(11) NOT NULL default -1,
	`lengthOld` int(4),
	`lengthID` int(2),
	`ageLower` int(3) DEFAULT 1,
	`ageUpper` int(3) DEFAULT 100,
	`femaleMember` varchar(8),
	`femaleTime` varchar(8),
	`femaleDate` date, 
	`maleMember` varchar(8),
	`maleTime` varchar(8),
	`maleDate` date,
	/*`member` varchar(8) NOT NULL DEFAULT "default",*/
	`eventTypeOld` enum('C','A'),
	`eventtype` int(4),
	/*`swimEventID` varchar(16) NOT NULL DEFAULT 'NOT SPECIFIED',
	`date` date NOT NULL DEFAULT 0,
	`time` time NOT NULL DEFAULT 0,*/
	PRIMARY KEY (`id`, `strokeID`,`lengthID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

--
-- Table structure for table `days`
--

CREATE TABLE IF NOT EXISTS `days`(
	`id` int(1) NOT NULL,
	`day` varchar(9) NOT NULL,
	PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `gala_events`
--

CREATE TABLE IF NOT EXISTS `gala_events` (
  `id` varchar(16) NOT NULL,
  `galaID` varchar(8) NOT NULL,
  `strokeold` char(2) NOT NULL,
  `strokeID` int(2) NOT NULL DEFAULT 1,
  `lengthold` int(4) NOT NULL,
  `lengthID` int(2) NOT NULL DEFAULT 1,
  `gender` char(1) DEFAULT 'B',
  `ageLower` int(11) DEFAULT NULL,
  `ageUpper` int(11) DEFAULT NULL,
  `subGroup` varchar(100),
  PRIMARY KEY (`id`, `galaid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `galas`(
  `id` varchar(20) NOT NULL,
  `title` varchar(250) NOT NULL DEFAULT "TITLE",
  `description` longtext DEFAULT NULL,
  `date` date,
  `venueold` varchar(500),
  `venueID` int(11) NOT NULL DEFAULT 1,
  `warmUpTime` varchar(13) NOT NULL DEFAULT 'Not Specified',
  `organiser` varchar(50) NOT NULL DEFAULT "organiser",
  `fees` decimal(10,0) DEFAULT NULL,
  `confirmationDate` date,
  `cutOffDate` date,
  `eventTypeOld` CHAR(1),
  `isLongCourse` boolean,
  `isAccredited` boolean,
  PRIMARY KEY(`id`)
)  ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE IF NOT EXISTS `members` (
  `sasaNumber` varchar(14) NULL, 
  `sasaCategory` int(1) DEFAULT NULL,
  `sasaCategoryOld` char(1) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `statusold` CHAR(1) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `middleName` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) NOT NULL,
  `parentTitle` varchar(10) DEFAULT NULL,
  `parentName` varchar(50) DEFAULT NULL,
  `gender` char(1) NOT NULL,
  `dob` date NOT NULL,
  `address1` varchar(50) NOT NULL,
  `address2` varchar(50) DEFAULT NULL,
  `city` varchar(50) NOT NULL,
  `county` varchar(50) DEFAULT NULL,
  `postcode` varchar(12) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `emergencyContactName` varchar(50) DEFAULT NULL,
  `emergencyContactRelationship` int(11) DEFAULT NULL,
  `emergencyContactTelephone` varchar(14) DEFAULT NULL,
  `emergencyContactMobile` varchar(14) DEFAULT NULL,
  `squadID` int(11) DEFAULT NULL,
  `registerDate` date NOT NULL,
  `lastLoginDate` datetime DEFAULT NULL,
  `notes` varchar(2500) DEFAULT NULL,
  `swimmingHours` TINYINT(4) NOT NULL,
  `monthlyFee` DECIMAL(5, 2) NOT NULL,
  `feeAdjustment` DECIMAL(5, 2) DEFAULT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ;

-- --------------------------------------------------------

--
-- Table structure for table `member_role`
--

CREATE TABLE IF NOT EXISTS `members_roles` (
  `member` varchar(50) NOT NULL,
  `roleID` int(11) NOT NULL,
  PRIMARY KEY (`member`,`roleID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `news_privacy`
--

CREATE TABLE IF NOT EXISTS `news_privacy` (
  `newsID` varchar(50) NOT NULL,
  `roleID` int(11) NOT NULL,
  PRIMARY KEY (`newsID`,`roleID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL,
  `subTitle` varchar(250) DEFAULT NULL,
  `author` varchar(50) NOT NULL,
  `date` datetime NOT NULL,
  `mainBody` varchar(6500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(50) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `shop_items`
--

CREATE TABLE IF NOT EXISTS `shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  `price` decimal(10,0) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `squads`
--

CREATE TABLE IF NOT EXISTS `squads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `squad` varchar(50) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  `coach` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE IF NOT EXISTS `status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(50) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `strokes`
--

CREATE TABLE IF NOT EXISTS `strokes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stroke` varchar(50) NOT NULL,
  `description` int(250) DEFAULT NULL,
  `distance` int(11) DEFAULT NULL,
  `ageRangeLower` int(11) DEFAULT NULL,
  `ageRangeUpper` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `swim_events`
--

CREATE TABLE IF NOT EXISTS `swim_events` (
  `galaID` varchar(8) NOT NULL,
  `strokeID` int(11) NOT NULL,
  PRIMARY KEY (`galaID`, `strokeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `swim_events_type`
--

CREATE TABLE IF NOT EXISTS `swim_events_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `swim_times`
--

CREATE TABLE IF NOT EXISTS `swim_times` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `strokeID` int(11) NOT NULL DEFAULT -1,
  `member` varchar(50) NOT NULL,
  `galaid` varchar(8) NOT NULL,
  `swimEventID` varchar(16) NOT NULL,
  `typeID` int(11) NOT NULL DEFAULT 1,
  `date` date NOT NULL DEFAULT 0,
  `time` time NOT NULL DEFAULT 0,
  `rank` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`, `strokeID`,`member`,`swimEventID`,`typeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `username` varchar(50) NOT NULL,
  `password` varchar(250) NOT NULL,
  PRIMARY KEY (`Username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `venues`
--

CREATE TABLE IF NOT EXISTS `venues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `venue` varchar(250) NOT NULL,
  `address1` varchar(50) NOT NULL,
  `address2` varchar(50) DEFAULT NULL,
  `city` varchar(50) NOT NULL,
  `county` varchar(50) DEFAULT NULL,
  `postcode` varchar(8) NOT NULL,
  `telephone` varchar(11) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `website` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

--
-- Table structure for table `lengths`
--

CREATE TABLE IF NOT EXISTS `lengths`(
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`length` int(4) NOT NULL,
	PRIMARY KEY (`ID`)
)ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Table structure for table `gala_response`
--

CREATE TABLE IF NOT EXISTS `gala_response`(
	`galaid` varchar(8) NOT NULL,
	`member` varchar(8) NOT NULL,
	`responseold` tinyint(4),
	`response` int(2),
	PRIMARY KEY (`galaid`,`member`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `timetable`(
	`dayID` int(1) NOT NULL,
	`squadOld` varchar(10),
	`squadID` int(11),
	`venueOld` varchar(10),
	`venueID` int(11),
	`time` varchar(20)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

