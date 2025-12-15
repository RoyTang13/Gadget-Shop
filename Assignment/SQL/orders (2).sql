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
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `orderDate` datetime NOT NULL,
  `totalAmount` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderID`, `userID`, `orderDate`, `totalAmount`, `status`) VALUES
(21, 10, '2025-12-14 21:33:28', '480.00', 'Pending'),
(22, 10, '2025-12-14 21:37:44', '1150.00', 'Pending'),
(23, 10, '2025-12-14 21:43:56', '54.00', 'Pending'),
(24, 10, '2025-12-14 21:56:52', '1150.00', 'Pending'),
(25, 10, '2025-12-14 22:15:54', '1155.00', 'Complete'),
(26, 10, '2025-12-14 22:34:36', '206.50', 'Complete'),
(27, 10, '2025-12-14 22:51:27', '206.50', 'Pending'),
(28, 10, '2025-12-14 22:57:14', '1853.00', 'Complete'),
(29, 10, '2025-12-14 22:59:57', '686.50', 'Complete'),
(30, 10, '2025-12-14 23:07:33', '330.40', 'Complete'),
(31, 10, '2025-12-14 23:16:49', '568.00', 'Complete'),
(32, 10, '2025-12-14 23:18:07', '90.00', 'Pending'),
(33, 10, '2025-12-14 23:20:12', '90.00', 'Complete'),
(34, 10, '2025-12-14 23:20:40', '27.00', 'Pending'),
(35, 10, '2025-12-14 23:22:12', '27.00', 'Pending'),
(36, 10, '2025-12-14 23:31:17', '27.00', 'Complete'),
(37, 10, '2025-12-14 23:41:12', '27.00', 'Complete'),
(38, 10, '2025-12-14 23:46:28', '1099.00', 'Pending'),
(39, 10, '2025-12-14 23:51:02', '1099.00', 'Complete'),
(40, 10, '2025-12-14 23:51:18', '1150.00', 'Complete'),
(41, 10, '2025-12-15 00:12:46', '1647.00', 'Complete'),
(42, 10, '2025-12-15 01:18:53', '189.00', 'Complete'),
(43, 10, '2025-12-15 01:22:05', '260.50', 'Complete'),
(44, 10, '2025-12-15 02:50:20', '3035.00', 'Complete'),
(45, 10, '2025-12-15 10:15:45', '135.00', 'Complete'),
(46, 10, '2025-12-15 10:38:31', '1480.40', 'Complete'),
(47, 10, '2025-12-15 13:44:49', '1150.00', 'Complete'),
(48, 10, '2025-12-15 13:45:57', '2249.00', 'Complete'),
(49, 10, '2025-12-15 14:43:05', '108.00', 'Complete');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderID`),
  ADD KEY `userID` (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
