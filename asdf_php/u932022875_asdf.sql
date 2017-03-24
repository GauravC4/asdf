
-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 24, 2017 at 09:31 PM
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
  PRIMARY KEY (`prim`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `exam`
--

CREATE TABLE IF NOT EXISTS `exam` (
  `prim` mediumint(9) NOT NULL AUTO_INCREMENT,
  `scode` varchar(50) NOT NULL,
  `sname` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  PRIMARY KEY (`prim`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE IF NOT EXISTS `notifications` (
  `prim` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `message` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`prim`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`prim`, `date`, `message`) VALUES
(1, '2017-03-24 20:58:18', 'this is first test message'),
(2, '2017-03-24 20:58:18', 'this is second test message'),
(3, '2017-03-24 20:59:22', 'this is third test message');

-- --------------------------------------------------------

--
-- Table structure for table `prof_det`
--

CREATE TABLE IF NOT EXISTS `prof_det` (
  `prim` mediumint(9) NOT NULL AUTO_INCREMENT,
  `prof_id` mediumint(9) NOT NULL,
  `deptartment` varchar(50) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `year` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`prim`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `prof_preferences`
--

CREATE TABLE IF NOT EXISTS `prof_preferences` (
  `prim` mediumint(9) NOT NULL AUTO_INCREMENT,
  `prof_id` mediumint(9) NOT NULL,
  `ptime` time NOT NULL,
  `pdate` date NOT NULL,
  `pref_yn` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`prim`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
  `duty_amount` smallint(6) NOT NULL,
  `type` varchar(10) NOT NULL DEFAULT 'user',
  PRIMARY KEY (`prim`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `prof_profile`
--

INSERT INTO `prof_profile` (`prim`, `name`, `rank`, `username`, `password`, `duty_amount`, `type`) VALUES
(1, 'prof1', NULL, 'prof1', 'profone', 0, 'user'),
(2, 'a', 'ss', 'aa', 'a1', 3, 'user'),
(3, 'comps1', 'ss', 'comps1', 'comps1', 3, 'user'),
(5, 'comps2', 'js', 'comps2', 'comps2', 6, 'user'),
(6, 'it1', 'ss', 'it1', 'it1', 3, 'user'),
(7, 'it2', 'js', 'it2', 'it2', 6, 'user'),
(8, 'extc1', 'ss', 'extc1', 'extc1', 3, 'user'),
(9, 'extc2', 'js', 'extc2', 'extc2', 6, 'user'),
(10, 'comps', 'ss', 'comps', 'comps', 3, 'comps'),
(11, 'it', 'ss', 'it', 'it', 3, 'it'),
(12, 'extc', 'ss', 'extc', 'extc', 3, 'extc'),
(13, 'admin', 'ss', 'admin', 'admin', 0, 'admin');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
