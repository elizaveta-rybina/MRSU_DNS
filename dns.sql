-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Jul 22, 2023 at 12:24 AM
-- Server version: 8.0.33
-- PHP Version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dns`
--

-- --------------------------------------------------------

--
-- Table structure for table `arecord`
--

CREATE TABLE `arecord` (
  `id` int NOT NULL,
  `domainid` int NOT NULL,
  `ip` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `arecord`
--

INSERT INTO `arecord` (`id`, `domainid`, `ip`) VALUES
(1, 20, '0.0.0.0'),
(2, 21, '0.0.0.1'),
(3, 20, '0.0.0.1'),
(4, 20, '0.0.0.2'),
(5, 20, '0.0.0.3'),
(6, 20, '0.0.0.4');

-- --------------------------------------------------------

--
-- Table structure for table `domains`
--

CREATE TABLE `domains` (
  `id` int NOT NULL,
  `name` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `domains`
--

INSERT INTO `domains` (`id`, `name`) VALUES
(1, 'test.org'),
(2, 'test2.org'),
(3, 'test3.org'),
(4, 'test4.org'),
(5, 'test5.org'),
(6, 'test6.org'),
(7, 'test7.org'),
(8, 'test8.org'),
(9, 'test9.org'),
(10, 'test10.org'),
(11, 'test11.org'),
(12, 'test12.org'),
(13, 'test13.org'),
(14, 'test14.org'),
(15, 'test15.org'),
(16, 'test16.org'),
(17, 'test17.org'),
(18, 'test18.org'),
(19, 'test19.org'),
(20, 'test20.org'),
(21, 'test21.org');

-- --------------------------------------------------------

--
-- Table structure for table `mxrecord`
--

CREATE TABLE `mxrecord` (
  `id` int NOT NULL,
  `domainid` int NOT NULL,
  `priority` int NOT NULL,
  `ip` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `mxrecord`
--

INSERT INTO `mxrecord` (`id`, `domainid`, `priority`, `ip`) VALUES
(1, 20, 1, '0.0.0.0');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `arecord`
--
ALTER TABLE `arecord`
  ADD PRIMARY KEY (`id`),
  ADD KEY `foreign key` (`domainid`);

--
-- Indexes for table `domains`
--
ALTER TABLE `domains`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mxrecord`
--
ALTER TABLE `mxrecord`
  ADD PRIMARY KEY (`id`),
  ADD KEY `domainid` (`domainid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `arecord`
--
ALTER TABLE `arecord`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `domains`
--
ALTER TABLE `domains`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `mxrecord`
--
ALTER TABLE `mxrecord`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `arecord`
--
ALTER TABLE `arecord`
  ADD CONSTRAINT `foreign key` FOREIGN KEY (`domainid`) REFERENCES `domains` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mxrecord`
--
ALTER TABLE `mxrecord`
  ADD CONSTRAINT `mxrecord_ibfk_1` FOREIGN KEY (`domainid`) REFERENCES `domains` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
