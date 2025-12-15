-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 15, 2025 at 09:15 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `technest`
--

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `orderItemID` int(11) NOT NULL,
  `orderID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`orderItemID`, `orderID`, `productID`, `quantity`, `price`) VALUES
(23, 21, 8, 1, '480.00'),
(24, 22, 10, 1, '1150.00'),
(25, 23, 1, 2, '27.00'),
(26, 24, 10, 1, '1150.00'),
(27, 25, 1, 5, '27.00'),
(28, 25, 5, 1, '1020.00'),
(29, 26, 11, 1, '206.50'),
(30, 27, 11, 1, '206.50'),
(31, 28, 11, 2, '206.50'),
(32, 28, 8, 3, '480.00'),
(33, 29, 11, 1, '206.50'),
(34, 29, 8, 1, '480.00'),
(35, 30, 12, 1, '330.40'),
(36, 31, 4, 1, '568.00'),
(37, 32, 3, 1, '90.00'),
(38, 33, 3, 1, '90.00'),
(39, 34, 1, 1, '27.00'),
(40, 35, 1, 1, '27.00'),
(41, 36, 1, 1, '27.00'),
(42, 37, 1, 1, '27.00'),
(43, 38, 7, 1, '1099.00'),
(44, 39, 7, 1, '1099.00'),
(45, 40, 10, 1, '1150.00'),
(46, 41, 1, 1, '27.00'),
(47, 41, 3, 2, '90.00'),
(48, 41, 8, 3, '480.00'),
(49, 42, 1, 7, '27.00'),
(50, 43, 1, 2, '27.00'),
(51, 43, 11, 1, '206.50'),
(52, 44, 10, 2, '1150.00'),
(53, 44, 9, 1, '600.00'),
(54, 44, 1, 5, '27.00'),
(55, 45, 1, 5, '27.00'),
(56, 46, 10, 1, '1150.00'),
(57, 46, 12, 1, '330.40'),
(58, 47, 10, 1, '1150.00'),
(59, 48, 7, 1, '1099.00'),
(60, 48, 10, 1, '1150.00'),
(61, 49, 1, 4, '27.00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`orderItemID`),
  ADD KEY `productID` (`productID`),
  ADD KEY `orderID` (`orderID`),
  ADD KEY `orderID_2` (`orderID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `orderItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
