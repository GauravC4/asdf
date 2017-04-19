
-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 19, 2017 at 03:11 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `u932022875_asdf`
--

-- --------------------------------------------------------

--
-- Table structure for table `allotment`
--

CREATE TABLE IF NOT EXISTS `allotment` (
  `prim` mediumint(9) NOT NULL AUTO_INCREMENT,
  `exam_id` mediumint(9) NOT NULL,
  `prof_id` mediumint(9) NOT NULL,
  `position` varchar(10) NOT NULL,
  `room` varchar(10) NOT NULL,
  PRIMARY KEY (`prim`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=37 ;

--
-- Dumping data for table `allotment`
--

INSERT INTO `allotment` (`prim`, `exam_id`, `prof_id`, `position`, `room`) VALUES
(17, 3, 12, 'JS', '210'),
(16, 3, 7, 'JS', '312'),
(15, 3, 5, 'JS', '405'),
(14, 2, 9, 'JS', '404B'),
(13, 1, 5, 'JS', '403'),
(12, 1, 12, 'JS', '405'),
(11, 1, 3, 'JS', '410'),
(10, 1, 7, 'JS', '404B'),
(18, 4, 3, 'JS', '409'),
(19, 5, 7, 'JS', '303'),
(20, 5, 5, 'JS', '312'),
(21, 6, 12, 'JS', '302'),
(22, 1, 6, 'SS', '404B'),
(23, 1, 6, 'SS', '410'),
(24, 1, 6, 'SS', '405'),
(25, 1, 6, 'SS', '403'),
(26, 3, 10, 'SS', '405'),
(27, 3, 10, 'SS', '312'),
(28, 3, 10, 'SS', '210'),
(29, 5, 8, 'SS', '303'),
(30, 5, 8, 'SS', '312'),
(31, 3, 9, 'RELIEVER', '405'),
(32, 3, 9, 'RELIEVER', '312'),
(33, 3, 9, 'RELIEVER', '210'),
(34, 5, 9, 'STANDBY', '303'),
(35, 5, 9, 'STANDBY', '312'),
(36, 6, 3, 'STANDBY', '302');

-- --------------------------------------------------------

--
-- Table structure for table `exam`
--

CREATE TABLE IF NOT EXISTS `exam` (
  `prim` mediumint(9) NOT NULL AUTO_INCREMENT,
  `scode` varchar(10) NOT NULL,
  `edate` date NOT NULL,
  `etime` time NOT NULL,
  `eslot` smallint(6) NOT NULL DEFAULT '1',
  `room` varchar(200) NOT NULL,
  `etimediff` smallint(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`prim`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `exam`
--

INSERT INTO `exam` (`prim`, `scode`, `edate`, `etime`, `eslot`, `room`, `etimediff`) VALUES
(1, 'FEMECH', '2017-04-04', '09:30:00', 1, '404B,410,405,403', 3),
(2, 'SECG', '2017-04-04', '15:00:00', 2, '404B', 3),
(3, 'TEOS', '2017-04-05', '09:30:00', 1, '405,312,210', 3),
(4, 'TEMCC', '2017-04-05', '15:00:00', 2, '409', 3),
(5, 'TESE', '2017-04-03', '15:00:00', 2, '303,312', 2),
(6, 'BEML', '2017-04-03', '09:00:00', 1, '302', 3);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE IF NOT EXISTS `notifications` (
  `prim` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `message` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`prim`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`prim`, `date`, `message`) VALUES
(1, '2017-03-24 20:58:18', 'this is first test message'),
(2, '2017-03-24 20:58:18', 'this is second test message'),
(3, '2017-03-24 20:59:22', 'this is third test message'),
(4, '2017-03-25 05:25:16', 'hey there everyone'),
(5, '2017-03-25 14:59:46', 'test notification'),
(6, '2017-03-26 09:44:52', 'test '),
(7, '2017-03-26 13:39:21', 'hello'),
(9, '2017-03-26 19:01:21', 'testing '),
(14, '2017-04-05 03:57:58', 'Happy Birthday Gaurav!');

-- --------------------------------------------------------

--
-- Table structure for table `prof_preferences`
--

CREATE TABLE IF NOT EXISTS `prof_preferences` (
  `prim` mediumint(9) NOT NULL AUTO_INCREMENT,
  `prof_id` mediumint(9) NOT NULL,
  `ptime` time NOT NULL,
  `pdate` date NOT NULL,
  `ptype` tinyint(4) NOT NULL DEFAULT '1',
  `pslot` int(10) NOT NULL,
  PRIMARY KEY (`prim`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `prof_preferences`
--

INSERT INTO `prof_preferences` (`prim`, `prof_id`, `ptime`, `pdate`, `ptype`, `pslot`) VALUES
(1, 1, '00:00:00', '2017-04-20', 1, 1),
(2, 1, '00:00:00', '2017-04-20', 1, 1),
(3, 2, '00:00:00', '2017-04-20', 2, 1),
(4, 8, '00:00:00', '2017-04-20', 3, 1),
(5, 8, '00:00:00', '2017-04-20', 1, 2),
(6, 6, '00:00:00', '2017-04-04', 3, 2),
(7, 6, '00:00:00', '2017-04-04', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `prof_profile`
--

CREATE TABLE IF NOT EXISTS `prof_profile` (
  `prim` mediumint(9) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `rank` varchar(10) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_of_duty` smallint(6) NOT NULL,
  `type` varchar(10) NOT NULL DEFAULT 'user',
  `dept` varchar(10) NOT NULL DEFAULT 'none',
  PRIMARY KEY (`prim`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `prof_profile`
--

INSERT INTO `prof_profile` (`prim`, `name`, `rank`, `username`, `password`, `no_of_duty`, `type`, `dept`) VALUES
(1, 'prof1', 'SS', 'prof1', 'profone', 19, 'user', 'comps'),
(2, 'a', 'SS', 'aa', 'a1', 19, 'user', 'it'),
(3, 'comps1', 'JS', 'comps1', 'comps1', 14, 'user', 'comps'),
(5, 'comps2', 'JS', 'comps2', 'comps2', 11, 'user', 'comps'),
(6, 'it1', 'SS', 'it1', 'it1', 17, 'user', 'it'),
(7, 'it2', 'JS', 'it2', 'it2', 11, 'user', 'it'),
(8, 'extc1', 'SS', 'extc1', 'extc1', 19, 'user', 'extc'),
(9, 'extc2', 'JS', 'extc2', 'extc2', 11, 'user', 'extc'),
(10, 'comps', 'SS', 'comps', 'comps', 17, 'comps', 'comps'),
(11, 'it', 'SS', 'it', 'it', 20, 'it', 'it'),
(12, 'extc', 'JS', 'extc', 'extc', 11, 'extc', 'extc'),
(13, 'admin', 'A', 'admin', 'admin', 20, 'admin', 'admin');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
