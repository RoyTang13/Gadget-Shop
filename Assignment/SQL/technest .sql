-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 21, 2025 at 06:12 PM
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
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `adminID` int(10) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phoneNo` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reset_code` varchar(6) DEFAULT NULL,
  `reset_expiry` datetime DEFAULT NULL,
  `adminPhoto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`adminID`, `fname`, `lname`, `email`, `phoneNo`, `password`, `reset_code`, `reset_expiry`, `adminPhoto`) VALUES
(3, 'Admin', '1', 'admin@technest.com', '017-8778729', '$2y$10$HWISITAHj7Ao.s2pyhRgnuzuRFeYl1.dGflVKWzYD7dRDBv/ScidG', NULL, NULL, '694820444f7d9_jisoo-3.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `productID` char(5) NOT NULL,
  `quantity` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `userID`, `productID`, `quantity`, `added_at`) VALUES
(91, 19, '1', 3, '2025-12-15 07:07:48'),
(150, 10, '5', 1, '2025-12-21 17:08:30');

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
(35, 10, '2025-12-14 23:22:12', '27.00', 'Cancelled'),
(36, 10, '2025-12-14 23:31:17', '27.00', 'Complete'),
(37, 10, '2025-12-14 23:41:12', '27.00', 'Complete'),
(38, 10, '2025-12-14 23:46:28', '1099.00', 'Cancelled'),
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
(49, 10, '2025-12-15 14:43:05', '108.00', 'Complete'),
(50, 10, '2025-12-16 21:54:33', '54.00', 'Complete'),
(51, 7, '2025-12-16 22:24:16', '4607.30', 'Complete'),
(52, 7, '2025-12-16 23:13:00', '636.40', 'Cancelled'),
(53, 7, '2025-12-17 00:23:41', '1217.00', 'Complete'),
(54, 7, '2025-12-17 00:37:19', '5.00', 'Complete'),
(55, 7, '2025-12-17 02:31:23', '135.00', 'Complete'),
(56, 7, '2025-12-17 02:34:14', '216.00', 'Complete'),
(57, 7, '2025-12-17 02:37:23', '1995.00', 'Complete'),
(58, 7, '2025-12-17 02:39:05', '1197.00', 'Complete'),
(59, 7, '2025-12-17 02:41:34', '1596.00', 'Complete'),
(60, 7, '2025-12-17 02:42:24', '162.00', 'Complete'),
(61, 10, '2025-12-20 14:46:53', '1020.00', 'Pending'),
(62, 10, '2025-12-20 14:47:59', '1020.00', 'Complete'),
(63, 10, '2025-12-20 14:55:13', '54.00', 'Complete'),
(64, 10, '2025-12-20 21:26:18', '135.00', 'Complete'),
(65, 10, '2025-12-20 21:28:06', '27.00', 'Complete'),
(66, 10, '2025-12-20 21:30:41', '27.00', 'Pending'),
(67, 10, '2025-12-20 21:50:39', '1177.00', 'Pending'),
(68, 10, '2025-12-20 22:22:21', '2197.00', 'Complete'),
(69, 10, '2025-12-20 22:47:59', '27.00', 'Complete'),
(70, 10, '2025-12-20 22:52:57', '1099.00', 'Complete'),
(71, 10, '2025-12-20 22:59:20', '399.00', 'Complete'),
(72, 10, '2025-12-20 23:24:07', '108.00', 'Complete'),
(73, 10, '2025-12-20 23:28:45', '54.00', 'Complete'),
(74, 10, '2025-12-20 23:38:43', '54.00', 'Complete'),
(75, 10, '2025-12-20 23:44:10', '568.00', 'Complete'),
(76, 10, '2025-12-20 23:51:10', '27.00', 'Complete'),
(77, 10, '2025-12-20 23:56:43', '81.00', 'Pending'),
(78, 10, '2025-12-21 01:26:40', '30.20', 'Pending'),
(79, 10, '2025-12-21 01:52:49', '90.00', 'Complete'),
(80, 8, '2025-12-21 02:28:24', '1848.00', 'Pending'),
(81, 8, '2025-12-21 03:55:40', '2283.00', 'Complete'),
(82, 8, '2025-12-21 04:17:32', '967.00', 'Complete'),
(83, 8, '2025-12-21 04:25:50', '516.00', 'Pending'),
(84, 10, '2025-12-21 12:03:47', '1150.00', 'Complete'),
(85, 10, '2025-12-22 00:12:36', '805.80', 'Complete'),
(86, 10, '2025-12-22 01:06:11', '798.00', 'Pending'),
(87, 10, '2025-12-22 01:07:04', '798.00', 'Complete');

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
(61, 49, 1, 4, '27.00'),
(62, 50, 1, 2, '27.00'),
(63, 51, 1, 2, '27.00'),
(64, 51, 11, 1, '206.50'),
(65, 51, 12, 2, '330.40'),
(66, 51, 2, 2, '399.00'),
(67, 51, 3, 1, '90.00'),
(68, 51, 9, 1, '600.00'),
(69, 51, 7, 2, '1099.00'),
(70, 52, 1, 8, '27.00'),
(71, 52, 12, 1, '330.40'),
(72, 52, 3, 1, '90.00'),
(73, 53, 2, 1, '399.00'),
(74, 53, 6, 1, '818.00'),
(75, 54, 15, 1, '5.00'),
(76, 55, 1, 5, '27.00'),
(77, 56, 1, 8, '27.00'),
(78, 57, 2, 5, '399.00'),
(79, 58, 2, 3, '399.00'),
(80, 59, 2, 4, '399.00'),
(81, 60, 1, 6, '27.00'),
(82, 61, 5, 1, '1020.00'),
(83, 62, 5, 1, '1020.00'),
(84, 63, 1, 2, '27.00'),
(85, 64, 1, 5, '27.00'),
(86, 65, 1, 1, '27.00'),
(87, 66, 1, 1, '27.00'),
(88, 67, 1, 1, '27.00'),
(89, 67, 10, 1, '1150.00'),
(90, 68, 1, 1, '27.00'),
(91, 68, 10, 1, '1150.00'),
(92, 68, 5, 1, '1020.00'),
(93, 69, 1, 1, '27.00'),
(94, 70, 7, 1, '1099.00'),
(95, 71, 2, 1, '399.00'),
(96, 72, 1, 4, '27.00'),
(97, 73, 1, 2, '27.00'),
(98, 74, 1, 2, '27.00'),
(99, 75, 4, 1, '568.00'),
(100, 76, 1, 1, '27.00'),
(101, 77, 1, 3, '27.00'),
(102, 78, 15, 1, '30.20'),
(103, 79, 3, 1, '90.00'),
(104, 80, 3, 1, '90.00'),
(105, 80, 1, 2, '27.00'),
(106, 80, 4, 3, '568.00'),
(107, 81, 2, 1, '399.00'),
(108, 81, 3, 2, '90.00'),
(109, 81, 4, 3, '568.00'),
(110, 82, 2, 1, '399.00'),
(111, 82, 4, 1, '568.00'),
(112, 83, 1, 1, '27.00'),
(113, 83, 3, 1, '90.00'),
(114, 83, 2, 1, '399.00'),
(115, 84, 10, 1, '1150.00'),
(116, 85, 1, 5, '27.00'),
(117, 85, 2, 1, '399.00'),
(118, 85, 15, 9, '30.20'),
(119, 86, 2, 2, '399.00'),
(120, 87, 2, 2, '399.00');

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
(24, 49, 'Public Bank', 'Roy', '1111111111111111', '11/22', 'Roy', '38 Jln Tun Mohd Fuad Taman Tun Dr Ismail', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-15 14:43:15'),
(25, 50, 'Public Bank', 'Roy', '1111111111111111', '11/22', 'Roy', '38 Jln Tun Mohd Fuad Taman Tun Dr Ismail', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-16 21:54:36'),
(26, 51, 'CIMB', 'Roy', '1234566633332222', '11/22', 'Roy', '10, Jalan 51A/223', 'Petaling Jaya', '46100', 'Malaysia', '2025-12-16 22:25:38'),
(27, 52, 'CIMB', 'Roy', '1234566633332222', '11/22', 'Roy', '10, Jalan 51A/223', 'Petaling Jaya', '46100', 'Malaysia', '2025-12-16 23:13:09'),
(28, 53, 'CIMB', 'Roy', '1234566633332222', '11/22', 'Roy', '10, Jalan 51A/223', 'Petaling Jaya', '46100', 'Malaysia', '2025-12-17 00:23:45'),
(29, 54, 'CIMB', 'Roy', '1234566633332222', '11/22', 'Roy', '10, Jalan 51A/223', 'Petaling Jaya', '46100', 'Malaysia', '2025-12-17 00:37:24'),
(30, 55, 'CIMB', 'Roy', '1234566633332222', '11/22', 'Roy', '10, Jalan 51A/223', 'Petaling Jaya', '46100', 'Malaysia', '2025-12-17 02:31:27'),
(31, 56, 'CIMB', 'Roy', '1234566633332222', '11/22', 'Roy', '10, Jalan 51A/223', 'Petaling Jaya', '46100', 'Malaysia', '2025-12-17 02:34:17'),
(32, 57, 'CIMB', 'Roy', '1234566633332222', '11/22', 'Roy', '10, Jalan 51A/223', 'Petaling Jaya', '46100', 'Malaysia', '2025-12-17 02:37:26'),
(33, 58, 'CIMB', 'Roy', '1234566633332222', '11/22', 'Roy', '10, Jalan 51A/223', 'Petaling Jaya', '46100', 'Malaysia', '2025-12-17 02:39:07'),
(34, 59, 'CIMB', 'Roy', '1234566633332222', '11/22', 'Roy', '10, Jalan 51A/223', 'Petaling Jaya', '46100', 'Malaysia', '2025-12-17 02:41:37'),
(35, 60, 'CIMB', 'Roy', '1234566633332222', '11/22', 'Roy', '10, Jalan 51A/223', 'Petaling Jaya', '46100', 'Malaysia', '2025-12-17 02:42:27'),
(36, 62, 'Public Bank', 'Roy', '1111111111111111', '11/22', 'Roy', '38 Jln Tun Mohd Fuad Taman Tun Dr Ismail', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-20 14:48:06'),
(37, 63, 'Public Bank', 'Roy', '1111111111111111', '11/22', 'Roy', '38 Jln Tun Mohd Fuad Taman Tun Dr Ismail', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-20 14:55:20'),
(38, 64, 'Public Bank', 'Roy', '1111111111111111', '11/22', 'Roy', '38 Jln Tun Mohd Fuad Taman Tun Dr Ismail', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-20 21:26:22'),
(39, 65, 'Public Bank', 'Roy', '1111111111111111', '11/22', 'Roy', '38 Jln Tun Mohd Fuad Taman Tun Dr Ismail', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-20 21:28:09'),
(40, 68, 'Public Bank', 'Roy', '1111111111111111', '11/22', 'Roy', '38 Jln Tun Mohd Fuad Taman Tun Dr Ismail', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-20 22:22:24'),
(41, 69, 'Public Bank', 'Roy', '1111111111111111', '11/22', 'Roy', '38 Jln Tun Mohd Fuad Taman Tun Dr Ismail', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-20 22:48:07'),
(42, 70, 'Public Bank', 'Roy', '1111111111111111', '11/22', 'Roy', '38 Jln Tun Mohd Fuad Taman Tun Dr Ismail', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-20 22:53:00'),
(43, 71, 'Public Bank', 'Roy', '1111111111111111', '11/22', 'Roy', '38 Jln Tun Mohd Fuad Taman Tun Dr Ismail', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-20 22:59:28'),
(44, 72, 'Public Bank', 'Roy', '1111111111111111', '11/22', 'Roy', '38 Jln Tun Mohd Fuad Taman Tun Dr Ismail', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-20 23:24:11'),
(45, 73, 'Public Bank', 'Roy', '1111111111111111', '11/22', 'Roy', '38 Jln Tun Mohd Fuad Taman Tun Dr Ismail', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-20 23:28:48'),
(46, 74, 'Public Bank', 'Roy', '1111111111111111', '11/22', 'Roy', '38 Jln Tun Mohd Fuad Taman Tun Dr Ismail', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-20 23:38:47'),
(47, 75, 'Public Bank', 'Roy', '1111111111111111', '11/22', 'Roy', '38 Jln Tun Mohd Fuad Taman Tun Dr Ismail', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-20 23:44:14'),
(48, 76, 'Public Bank', 'Roy', '1111111111111111', '11/22', 'Roy', '38 Jln Tun Mohd Fuad Taman Tun Dr Ismail', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-20 23:51:15'),
(49, 77, 'Public Bank', 'Roy', '1111111111111111', '11/22', 'Roy', '38 Jln Tun Mohd Fuad Taman Tun Dr Ismail', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-20 23:56:45'),
(50, 78, 'Public Bank', 'Roy', '1111111111111111', '11/22', 'Roy', '38 Jln Tun Mohd Fuad Taman Tun Dr Ismail', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-21 01:26:44'),
(51, 79, 'Public Bank', 'Roy', '1111111111111111', '11/22', 'Roy', '38 Jln Tun Mohd Fuad Taman Tun Dr Ismail', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-21 01:52:53'),
(52, 80, 'CIMB', 'Roy', '1235666644443333', '11/22', 'Roy', '5-4-14,Jln Tun Tasik', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-21 02:29:15'),
(53, 81, 'CIMB', 'Roy', '1235666644443333', '11/22', 'Roy', '5-4-14,Jln Tun Tasik', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-21 03:55:45'),
(54, 82, 'CIMB', 'Roy', '1235666644443333', '11/22', 'Roy', '5-4-14,Jln Tun Tasik', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-21 04:17:51'),
(55, 84, 'Public Bank', 'Roy', '1111111111111111', '11/22', 'Roy', '38 Jln Tun Mohd Fuad Taman Tun Dr Ismail', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-21 12:03:50'),
(56, 85, 'Public Bank', 'Roy', '1111111111111111', '11/22', 'Roy', '38 Jln Tun Mohd Fuad Taman Tun Dr Ismail', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-22 00:12:43'),
(57, 87, 'Public Bank', 'Roy', '1111111111111111', '11/22', 'Roy', '38 Jln Tun Mohd Fuad Taman Tun Dr Ismail', 'Cheras', '60000', 'Wilayah Persekutuan', '2025-12-22 01:07:09');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `productID` int(11) NOT NULL,
  `productName` varchar(150) NOT NULL,
  `productPrice` decimal(6,2) NOT NULL,
  `productDesc` varchar(1000) NOT NULL,
  `productQty` int(3) NOT NULL,
  `productCat1` enum('Wired','Wireless') NOT NULL,
  `productCat2` enum('In-Ear','Over-Ear') NOT NULL,
  `productCat3` enum('Noise-Canceled','Balanced','Clear Vocals') NOT NULL,
  `productPhoto` varchar(100) NOT NULL,
  `productDisp` enum('no','yes') NOT NULL,
  `productStatus` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`productID`, `productName`, `productPrice`, `productDesc`, `productQty`, `productCat1`, `productCat2`, `productCat3`, `productPhoto`, `productDisp`, `productStatus`) VALUES
(1, 'Backwin Brand Wired Earbuds', '27.00', '- Wired earbuds with dynamic bass boost provide you with an immersive music experience. The high notes of music will not break, while the low notes are strong and powerful\r\n\r\n- 3.5mm in ear headphones with high-definition microphone on one side of the line\r\n\r\n- CNC metal processing shell and curved handle give design\r\n\r\n- Equipped with interchangeable large, medium, and small L/M/S earplugs.\r\n\r\n- Durable cables increase the lifespan of wired earphones', 81, 'Wired', 'In-Ear', 'Noise-Canceled', '692bb9e0d7537.jpg', 'yes', 1),
(2, 'Lin Soul TIN HIFI T4', '399.00', 'Crafted in natural aluminum, the TINHIFI T4 delivers a sleek and durable design. With a comfortable in‑ear form factor and 32 Ohm impedance, it offers balanced audio performance suitable for everyday listening. Featuring sound isolation for an immersive experience, these earphones connect via a 3.5 mm jack for reliable wired use.', 186, 'Wired', 'In-Ear', 'Balanced', '692bff976e92f.jpg', 'no', 1),
(3, 'Sony IER‑EX15C USB‑C Wired In‑Ear Earphones (Black)', '90.00', 'Enjoy seamless connectivity across phones, laptops, and consoles with a simple USB‑C wired design—no charging or drop‑outs. Featuring a 5mm driver for rich bass and crystal‑clear vocals, these lightweight earphones deliver comfort with contoured housing and multiple ear tip sizes. The in‑line remote offers easy control for music, calls, and voice assistants, while the textured cable and slider reduce tangles. Designed with sustainability in mind, the packaging is plastic‑free, reflecting Sony’s commitment to eco‑friendly practices.', 295, 'Wired', 'In-Ear', 'Clear Vocals', '692c01a438b69.jpg', 'no', 1),
(4, 'Audio‑Technica ATH‑M50x', '568.00', 'Renowned by audio engineers and reviewers, the ATH‑M50x delivers exceptional clarity and deep bass with proprietary 45 mm drivers and neodymium magnets. Its closed‑back circumoral design ensures excellent sound isolation, while 90° swiveling earcups allow easy one‑ear monitoring. Built with professional‑grade pads and headband, these headphones are durable, comfortable, and collapsible for portability. Supplied with detachable cables (coiled and straight), a protective pouch, and a ¼\" adapter, the ATH‑M50x is a top choice for studio tracking, DJ monitoring, and personal listening.', 270, 'Wired', 'Over-Ear', 'Noise-Canceled', '692c04851e13c.jpg', 'no', 1),
(5, 'Beyerdynamic DT 880 Pro', '1020.00', 'Made in Germany for professional studio use, the DT 880 Pro delivers transparent, spacious, and natural sound across an extended 5–35,000 Hz frequency range. With 250-ohm impedance, it’s ideal for mixing, editing, and mastering. The semi‑open, circumneutral design ensures comfort and sound accuracy, supported by soft replaceable velour ear pads and a robust spring steel headband. Lightweight at 295 g, it includes a coiled cable with 3.5 mm jack and 6.35 mm adapter, offering durability and reliability for long sessions.', 198, 'Wired', 'Over-Ear', 'Balanced', '692c0914cfe7c.jpg', 'no', 1),
(6, 'Beyerdynamic DT 770 Pro', '818.00', 'Made in Germany, the DT 770 Pro delivers professional studio sound with an innovative bass reflex system and a wide 5–35,000 Hz frequency response. Its closed‑back circumneutral design ensures excellent isolation, while soft replaceable velour ear pads and a rugged padded headband provide long‑lasting comfort. With 250-ohm impedance, firm fit, and durable build, these headphones are ideal for mixing, monitoring, and mobile use. Lightweight at 270 g, they combine reliability with high‑quality audio performance.', 198, 'Wired', 'Over-Ear', 'Clear Vocals', '692c0b5ae25cc.jpg', 'no', 1),
(7, 'Sony WF‑1000XM5 Wireless Noise‑Cancelling Earbuds', '1099.00', 'Experience Sony’s best‑ever noise cancelling with dual processors, Dynamic Driver X, and innovative earbud tips for a stable fit. Delivering astonishing sound quality with deep bass, clear vocals, and fine detail, these earbuds also feature AI‑powered call clarity with bone conduction sensors and wind noise reduction. Compact and lightweight, they’re 25% smaller and 20% lighter than the previous model, with a sleek charging case for portability. Enjoy multipoint Bluetooth pairing, adaptive sound control, intuitive touch operation, and IPX4 water resistance—perfect for travel, work, and everyday listening.', 123, 'Wireless', 'In-Ear', 'Noise-Canceled', '692c129f0734b.jpg', 'no', 1),
(8, 'Samsung Galaxy Buds 2 Pro', '480.00', 'Step into 24‑bit Hi‑Fi audio with immersive 360° sound for a true cinematic experience. Enjoy crisp call quality with advanced noise reduction, even in busy environments. Featuring Intelligent Active Noise Cancellation (ANC) that’s 40% more powerful; you can block distractions or let in ambient sound as needed. Ergonomically designed for a secure, comfortable fit, the Buds2 Pro combine premium sound, clear conversations, and all‑day comfort in a sleek wireless package.', 80, 'Wireless', 'In-Ear', 'Balanced', '692c156dc99a9.jpg', 'no', 1),
(9, 'OPPO Enco Air 2 True Wireless Earbuds', '600.00', 'Powered by 13.4 mm composite tetanized drivers, the Enco Air2 delivers thumping bass, crystal‑clear treble, and rich Mids. Enhanced with unique bass boosters and Enco Live sound effects (Bass Boost & Clear Vocals), it offers a professional listening experience tuned by OPPO acoustics experts. Ultra‑light at 3.5 g per earbud, with an ergonomic fit and a translucent jelly case lid, these earbuds combine comfort and style. Enjoy up to 24 hours of playback with the charging case, Bluetooth® 5.2 low‑latency transmission, and AI noise cancellation for calls. With IPX4 water resistance, they’re perfect for music, gaming, and workouts.', 20, 'Wireless', 'In-Ear', 'Clear Vocals', '692c1a8953157.jpg', 'no', 1),
(10, 'Bose QuietComfort Ultra Earbuds - Black', '1150.00', 'Experience immersive spatialized audio and world‑class noise cancellation with Bose’s ultimate true wireless earbuds. Featuring Bose Immersive Audio for lifelike sound, these earbuds are designed for comfort with multiple ear tip and stability band options. Compact and lightweight at just 7 g each, they come with a sleek charging case, USB‑C cable, and Fit Kit. Perfect for music, calls, and everyday listening, the QuietComfort Ultra Earbuds deliver premium sound tailored to you.', 173, 'Wireless', 'Over-Ear', 'Noise-Canceled', '692c1e700f968.jpg', 'no', 1),
(11, 'JBL Tune 510BT', '206.50', 'Enjoy the signature JBL Pure Bass audio with wireless Bluetooth 5.0 streaming for high‑quality sound without cords. Offering up to 40 hours of battery life and speed charge (5 minutes = 2 hours playback), these lightweight 160 g on‑ear headphones are foldable and built for everyday use. Equipped with a 32 mm driver, built‑in microphone, and multi‑point connection, they support hands‑free calls, voice assistant integration, and intuitive ear‑cup controls. Perfect for music lovers seeking powerful bass, long playtime, and portable comfort.', 200, 'Wireless', 'Over-Ear', 'Balanced', '692c3c1a1dd26.jpg', 'no', 1),
(12, 'Soundcore Life Q30', '330.40', 'Enjoy hybrid active noise cancellation with three modes (Transport, Outdoor, Indoor) that block up to 95% of low‑frequency ambient sound. Powered by 40 mm silk drivers, the Life Q30 delivers hi‑res audio with thumping bass and crisp treble up to 40 kHz. With up to 40 hours of playtime in ANC mode (60 hours in standard mode) and fast charging (5 minutes = 4 hours), these headphones are built for all‑day listening. Ultra‑soft protein leather earcups with memory foam ensure pressure‑free comfort, while features like multipoint connection, dual mics for calls, and customizable EQ make them versatile for work, travel, and leisure. Lightweight at 260 g and TCO certified for sustainability, they combine performance, comfort, and eco‑friendly design.', 260, 'Wireless', 'Over-Ear', 'Clear Vocals', '692c3da179601.jpg', 'no', 1),
(13, 'Pandoru', '5.00', 'HASHIRE SORI YO\r\nKAZE NO YOU NI\r\nTSUKIMIHARA WO\r\nPADORU PADORU', 100, 'Wired', 'Over-Ear', 'Balanced', '694187d97588e.jpg', 'no', 1),
(14, 'Pandoru 2', '5.50', 'HASHIRE SORI YO\r\nKAZE NO YOU NI\r\nTSUKIMIHARA WO\r\nPADORU PADORU', 100, 'Wired', 'Over-Ear', 'Balanced', '69418a9b48c87.jpg', 'no', 1),
(15, 'Pandoru 3', '30.20', 'HASHIRE SORI YO\r\nKAZE NO YOU NI\r\nTSUKIMIHARA WO\r\nPADORU PADORU', 20, 'Wired', 'In-Ear', 'Noise-Canceled', '69418e275552c.jpg', 'no', 1),
(16, 'Pandoru4', '200.00', 'HASHIRE SORI YO\r\nKAZE NO YOU NI\r\nTSUKIMIHARA WO\r\nPADORU PADORU', 70, 'Wired', 'In-Ear', 'Noise-Canceled', '69481e2d82875.jpg', 'no', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userID` int(10) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phoneNo` varchar(50) NOT NULL,
  `userPhoto` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reset_code` varchar(6) DEFAULT NULL,
  `reset_expiry` datetime DEFAULT NULL,
  `status` enum('active','banned') NOT NULL DEFAULT 'active',
  `lastLogin` datetime DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `fname`, `lname`, `email`, `phoneNo`, `userPhoto`, `password`, `reset_code`, `reset_expiry`, `status`, `lastLogin`, `remember_token`) VALUES
(1, 'JOHN', 'TANG', 'john124@gmail.com', '012-3332223', '', '$2y$10$3cdDtxVe4SF14KkCDRXqz.PbxaA.fb.PpXkGkKdZ4.uRu.Gvh6sAy', NULL, NULL, 'active', NULL, NULL),
(2, 'JOHN', 'TANG', 'john123@gmail.com', '012-3332222', '', '$2y$10$3cdDtxVe4SF14KkCDRXqz.PbxaA.fb.PpXkGkKdZ4.uRu.Gvh6sAy', NULL, NULL, 'active', NULL, NULL),
(3, 'ROY', 'TANG', 'roy1@gmail.com', '011-1222222', '', '$2y$10$X02OthXz4JWMailohkZ9YOGoLYow3IM89OTAX3nWGy5awOh7v7QL.', NULL, NULL, 'active', NULL, NULL),
(4, 'ROY', 'TANG', 'roy2@gmail.com', '012-3456789', '', '$2y$10$DrSr9rhvOuI43.9DFhifwOCIVH7tWlYAW2iLA2q0PZXzTAV/pYu8u', NULL, NULL, 'active', NULL, NULL),
(5, 'ROY', 'TANG', 'roy3@gmail.com', '012-3456787', '', '$2y$10$J2Vjy9W/Bw.yB7e57MkojeuSYeCAnetBdeTp6Gc9FDIjPx7pQCP9a', NULL, NULL, 'active', NULL, NULL),
(6, 'ROY', 'TANG3', 'roy4@gmail.com', '012-3456786', '', '$2y$10$23VXhUOPMel8Npnqel/7CuvjQqpQNMbmNaPvViXvGcXGK7jDVfeZe', NULL, NULL, 'active', NULL, NULL),
(7, 'ROY5', 'TANG', 'roy5@gmail.com', '012-7772566', '6941ab8292772_iu-3.jpg', '$2y$10$CqXfHO3CWoUloKJdpr2j0uFEnYF14FtKzzT/Lpbmk16cxZkEmQoZy', NULL, NULL, 'active', '2025-12-16 22:22:58', NULL),
(8, 'TANG', 'LE YI', 'leyitang031013@gmail.com', '017-8778729', '694671c07fe7c_FB_IMG_1638342710671.jpg', '$2y$10$tubLgmyiCMYxLFdR9l.T5uVdUJkWM/v4xl.ac38jdj6zF1PuKsMcC', '596822', '2025-12-20 18:07:18', 'active', '2025-12-21 14:50:09', 'f06f4efca1c39b60ac04d6db6f3a24b34acfb62d6c256fc7f5032d95b86c9038'),
(9, 'ROY', 'TANG STUDENT', 'tangly-wm24@student.tarc.edu.my', '016-7779988', '', '$2y$10$h8uhhJERMBStUyJSWIqMhOr0SwKRhKGY5LFGzqAxX9sRN2mguRUiu', '924606', '2025-11-24 01:37:26', 'active', NULL, NULL),
(10, 'TANG', 'LE YI', 'roy6@gmail.com', '012-9999898', '692e97b55ead6.jpg', '$2y$10$m9ZeGmqomlPDmqCvfymvTeqIfWj5cKMh.a0hfarhaPmgq2CBTK/Gm', NULL, NULL, 'active', '2025-12-22 00:40:24', '67c883880bc48273cf7356f488fed67f96380f6a07d2b6ea9f27cad8b31857d5'),
(11, 'ROY', 'TANG', 'roy7@gmail.com', '017-2229999', '', '$2y$10$1LSrHJoXD9J1l2d2FmtYQO5fCx0ENwuDHQeeVwSwJgcVMw5eRlG0m', NULL, NULL, 'active', NULL, NULL),
(12, 'ROY', 'TANG', 'roy8@gmail.com', '017-8882222', '', '$2y$10$/X4uZWsNNzf.gnHTa.WMk.BK/b.VXEiTaR5yZs1pDIe9NBlM6Dn9q', NULL, NULL, 'active', NULL, NULL),
(13, 'ROY9', '91', 'roy91@gmail.com', '012-7894888', '', '$2y$10$PAAn2cSUu5.9/dJ7GiqO2utwflwTTIi/1sxL44y6dtbXmbqOMgCBa', NULL, NULL, 'active', NULL, NULL),
(14, 'ROY', '10', 'roy10@gmail.com', '017-8889988', '692d8c3176af8.jpg', '$2y$10$wHcw6UjhdBNk6H406/Jut.PQC21jhVjjg3fMQriNu./9eCP13tYqu', NULL, NULL, 'active', NULL, NULL),
(15, 'ROY', '11', 'roy11@gmail.com', '017-7778888', '692ea9b294d57.jpg', '$2y$10$wkX5zSIiEjqAh00JTna/au66VfpaUq6.aohacmuTmIRkzf2/WPmFK', NULL, NULL, 'active', NULL, NULL),
(16, 'ROY', '12', 'roy12@gmail.com', '017-8889999', '', '$2y$10$yO9ZT1bm/kf5mA6quwk9d.FGnllAXEg4/TJv3MIy7nzLtOcrS595.', NULL, NULL, 'active', NULL, NULL),
(17, 'USER', '1', 'user1@gmail.com', '017-8889992', '692dbe3707264.jpg', '$2y$10$TQmnN4uQUUjvCzRBb0Wwx.Ta2r/P4jZSu5.WEwicyRU0BDNWASdMi', NULL, NULL, 'active', NULL, NULL),
(18, 'TANG', 'LEYI', 'roy88@gmail.com', '017-8778726', '693f73c2e2a53.jpg', '$2y$10$CB4SWV8ObMj.Q6mGsyp37Ofhn8Tx29T2PL3AFN9ayk4b4Fotx8yCa', NULL, NULL, 'banned', '2025-12-16 22:14:31', NULL),
(19, 'ROY', 'TANG', 'test1@gmail.com', '017-8772655', '693fafbd2321e.jpg', '$2y$10$Ie/QBpp3e5LsT7Q7lV9RteNG/DH19Fl7c8BLeJDMF1DFf0WxM3tqG', NULL, NULL, 'banned', '2025-12-16 22:05:51', NULL),
(20, 'TANG', 'LE YI', 'roy13@gmail.com', '017-8778725', '69463a4f0e322_iu-4.jpg', '$2y$10$fW/MPTMcqa1gFqfAqmHcrujFEeX6Vbb.4zEX33n3yTsaRL5YePlpa', NULL, NULL, 'active', NULL, NULL),
(21, 'TANG', 'LE YI', 'roy14@gmail.com', '017-8778889', '69463af0b2aab_iu-4.jpg', '$2y$10$qaAK662iA3tnNFJUuUA1MeRXsPpcAxSXvpjBZ5Ei0sESNsZGoYBLi', NULL, NULL, 'active', '2025-12-20 13:58:33', NULL),
(22, 'ROY', '16', 'roy16@gmail.com', '017-6668888', '6947994c04e99_iu-3.jpg', '$2y$10$Uk2rHdB3UE5dMz0Sx4qUce56efIWd2x6KNUscHgIdSipiWTYsB/f6', NULL, NULL, 'active', '2025-12-21 14:53:28', 'fbd438da6b81a754482f12b1757cb0c6238f2ab13871f05e775e7329402652c0'),
(23, 'D', 'D', 'D@gmail.com', '017-8885555', '694820ae67dea_iu-5.jpg', '$2y$10$KhLKwIuasv/SvDsSba.NB.9yGoMClba/fcMqQoi1M0jueQCxsoTYe', NULL, NULL, 'active', '2025-12-22 00:30:06', 'f1d1b5ffce583ce544c778ca8fbabfd5eab60c3dbc5ea2c279fdb4f483408c5d'),
(24, 'ROY', '15', 'roy15@gmail.com', '017-8888888', '694829d59e67d_iu-3.jpg', '$2y$10$t9u1QmtIBz6qr340QUoSh.pme0sNNfySwe/xIYensgLlxoPgw.Y8i', NULL, NULL, 'active', '2025-12-22 01:09:57', 'bd942cf2d75c678cc04370defbc4fe44fa9517defbacbd6fba3492100cbdb95c');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`adminID`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userID` (`userID`),
  ADD KEY `productID` (`productID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`orderItemID`),
  ADD KEY `productID` (`productID`),
  ADD KEY `orderID` (`orderID`),
  ADD KEY `orderID_2` (`orderID`);

--
-- Indexes for table `payment_info`
--
ALTER TABLE `payment_info`
  ADD PRIMARY KEY (`paymentID`),
  ADD KEY `fk_payment_order` (`orderID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`productID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `adminID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `orderItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `payment_info`
--
ALTER TABLE `payment_info`
  MODIFY `paymentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `productID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`productID`) REFERENCES `product` (`productID`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`orderID`) REFERENCES `orders` (`orderID`);

--
-- Constraints for table `payment_info`
--
ALTER TABLE `payment_info`
  ADD CONSTRAINT `fk_payment_order` FOREIGN KEY (`orderID`) REFERENCES `orders` (`orderID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
