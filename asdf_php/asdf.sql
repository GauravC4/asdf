-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 21, 2017 at 10:46 AM
-- Server version: 10.1.20-MariaDB
-- PHP Version: 7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id1119811_asdf`
--

-- --------------------------------------------------------

--
-- Table structure for table `prof_profile`
--

CREATE TABLE `prof_profile` (
  `prim` mediumint(9) NOT NULL,
  `name` varchar(200) NOT NULL,
  `rank` varchar(10) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `duty_amount` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `prof_profile`
--

INSERT INTO `prof_profile` (`prim`, `name`, `rank`, `username`, `password`, `duty_amount`) VALUES
(1, 'prof1', NULL, 'prof1', 'profone', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `prof_profile`
--
ALTER TABLE `prof_profile`
  ADD PRIMARY KEY (`prim`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `prof_profile`
--
ALTER TABLE `prof_profile`
  MODIFY `prim` mediumint(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
