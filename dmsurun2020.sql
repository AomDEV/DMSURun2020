-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 25, 2019 at 03:35 AM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dmsurun2020`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `uid` int(11) NOT NULL,
  `idcard` varchar(13) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `gender` int(1) NOT NULL,
  `birthday` int(15) NOT NULL,
  `email` varchar(120) NOT NULL,
  `admin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`uid`, `idcard`, `phone`, `first_name`, `last_name`, `gender`, `birthday`, `email`, `admin`) VALUES
(1, '1350101601625', '0956091008', 'ศิริวัฒน์', 'จันทร์เก', 0, 979686000, 'siriwat576@gmail.com', 1);

-- --------------------------------------------------------

--
-- Table structure for table `payment_report`
--

CREATE TABLE `payment_report` (
  `rid` int(100) NOT NULL,
  `file_name` varchar(60) NOT NULL,
  `date` int(16) NOT NULL,
  `uid` int(100) NOT NULL,
  `status` int(1) NOT NULL,
  `amount` double(64,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `run`
--

CREATE TABLE `run` (
  `runid` int(100) NOT NULL,
  `isVIP` tinyint(1) NOT NULL,
  `runType` int(1) NOT NULL,
  `subType` int(1) NOT NULL,
  `size` int(1) NOT NULL,
  `uid` int(100) NOT NULL,
  `registerTime` int(16) NOT NULL,
  `status` int(1) NOT NULL,
  `follower` tinyint(1) NOT NULL,
  `follow_name` varchar(200) NOT NULL DEFAULT 'none',
  `phone` varchar(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `payment_report`
--
ALTER TABLE `payment_report`
  ADD PRIMARY KEY (`rid`);

--
-- Indexes for table `run`
--
ALTER TABLE `run`
  ADD PRIMARY KEY (`runid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payment_report`
--
ALTER TABLE `payment_report`
  MODIFY `rid` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `run`
--
ALTER TABLE `run`
  MODIFY `runid` int(100) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
