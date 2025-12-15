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
-- Table structure for table `payment_info`
--

CREATE TABLE `payment_info` (
  `paymentID` int(11) NOT NULL,
  `orderID` int(11) NOT NULL,
  `bankName` varchar(50) NOT NULL,
  `cardHolder` varchar(100) NOT NULL,
  `cardNumber` varchar(25) NOT NULL,
  `expiryDate` char(5) NOT NULL,
  `billingName` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(50) NOT NULL,
  `postcode` varchar(10) NOT NULL,
  `state` varchar(50) NOT NULL,
  `createdAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_info`
--

INSERT INTO `payment_info` (`paymentID`, `orderID`, `bankName`, `cardHolder`, `cardNumber`, `expiryDate`, `billingName`, `address`, `city`, `postcode`, `state`, `createdAt`) VALUES
(3, 23, 'CIMB', 'Roy', '**** **** **** 2222', '12/22', 'Roy', '38 Jln Tun Mohd Fuad Taman Tun Dr Ismail', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-14 21:45:32'),
(4, 24, 'CIMB', 'asdadd', '**** **** **** 1112', '11/22', 'Roy', 'weqweq', 'qweqw', '213213', 'adasd', '2025-12-14 21:57:33'),
(5, 25, 'Maybank', 'sdads', '**** **** **** 1111', '11/22', 'wda', 'adsas', 'asdas', 'asda', 'asdads', '2025-12-14 22:30:51'),
(6, 26, 'CIMB', 'sd', '**** **** **** 2222', '11/11', 'dasd', 'sdads', 'asdasd', 'asdasd', 'asd', '2025-12-14 22:36:26'),
(7, 28, 'Maybank', 'asdasd', '**** **** **** 1111', '11/11', 'ww', 'sdas', 'asda', 'asda', 'ads', '2025-12-14 22:58:15'),
(8, 29, 'Public Bank', '22222', '**** **** **** 1111', '11/21', 'ws', 'sdsd', 'sds', '2313', 'sda', '2025-12-14 23:00:20'),
(9, 30, 'CIMB', '1232321', '**** **** **** 2222', '11/11', 'Roy', 'sadad', 'sdad', 'sadsad', 'sadad', '2025-12-14 23:10:25'),
(10, 31, 'Maybank', 'asdasd', '**** **** **** 1111', '11/22', 'Roy', 'sdada', 'asdsad', 'asdsad', 'sadasd', '2025-12-14 23:17:52'),
(11, 33, 'Public Bank', 'sadasd', '**** **** **** 1111', '11/22', 'wwww', 'sczd', 'sdad', 'adssad', 'asdsad', '2025-12-14 23:20:26'),
(12, 36, 'Public Bank', 'Roy', '1122222221111222', '11/22', 'Roy', '38 Jln Tun Mohd Fuad Taman Tun Dr Ismail', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-14 23:40:54'),
(13, 37, 'Public Bank', 'Roy', '1111111111111111', '11/22', 'Roy', '38 Jln Tun Mohd Fuad Taman Tun Dr Ismail', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-14 23:46:16'),
(14, 39, 'Public Bank', 'Roy', '1111111111111111', '11/22', 'Roy', '38 Jln Tun Mohd Fuad Taman Tun Dr Ismail', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-14 23:51:07'),
(15, 40, 'Public Bank', 'Roy', '1111111111111111', '11/22', 'Roy', '38 Jln Tun Mohd Fuad Taman Tun Dr Ismail', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-14 23:58:35'),
(16, 41, 'Public Bank', 'Roy', '1111111111111111', '11/22', 'Roy', '38 Jln Tun Mohd Fuad Taman Tun Dr Ismail', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-15 00:13:00'),
(17, 42, 'Public Bank', 'Roy', '1111111111111111', '11/22', 'Roy', '38 Jln Tun Mohd Fuad Taman Tun Dr Ismail', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-15 01:18:58'),
(18, 43, 'Public Bank', 'Roy', '1111111111111111', '11/22', 'Roy', '38 Jln Tun Mohd Fuad Taman Tun Dr Ismail', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-15 01:22:09'),
(19, 44, 'Public Bank', 'Roy', '1111111111111111', '11/22', 'Roy', '38 Jln Tun Mohd Fuad Taman Tun Dr Ismail', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-15 02:50:25'),
(20, 45, 'Public Bank', 'Roy', '1111111111111111', '11/22', 'Roy', '38 Jln Tun Mohd Fuad Taman Tun Dr Ismail', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-15 10:15:51'),
(21, 46, 'Public Bank', 'Roy', '1111111111111111', '11/22', 'Roy', '38 Jln Tun Mohd Fuad Taman Tun Dr Ismail', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-15 10:38:40'),
(22, 47, 'Public Bank', 'Roy', '1111111111111111', '11/22', 'Roy', '38 Jln Tun Mohd Fuad Taman Tun Dr Ismail', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-15 13:44:56'),
(23, 48, 'Public Bank', 'Roy', '1111111111111111', '11/22', 'Roy', '38 Jln Tun Mohd Fuad Taman Tun Dr Ismail', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-15 13:46:02'),
(24, 49, 'Public Bank', 'Roy', '1111111111111111', '11/22', 'Roy', '38 Jln Tun Mohd Fuad Taman Tun Dr Ismail', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-15 14:43:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `payment_info`
--
ALTER TABLE `payment_info`
  ADD PRIMARY KEY (`paymentID`),
  ADD KEY `fk_payment_order` (`orderID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `payment_info`
--
ALTER TABLE `payment_info`
  MODIFY `paymentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payment_info`
--
ALTER TABLE `payment_info`
  ADD CONSTRAINT `fk_payment_order` FOREIGN KEY (`orderID`) REFERENCES `orders` (`orderID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
