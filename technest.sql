-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 24, 2025 at 11:27 AM
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
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userID` int(10) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phoneNo` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reset_code` varchar(6) DEFAULT NULL,
  `reset_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `fname`, `lname`, `email`, `phoneNo`, `password`, `reset_code`, `reset_expiry`) VALUES
(1, 'JOHN', 'TANG', 'john124@gmail.com', '012-3332223', '$2y$10$3cdDtxVe4SF14KkCDRXqz.PbxaA.fb.PpXkGkKdZ4.uRu.Gvh6sAy', NULL, NULL),
(2, 'JOHN', 'TANG', 'john123@gmail.com', '012-3332222', '$2y$10$3cdDtxVe4SF14KkCDRXqz.PbxaA.fb.PpXkGkKdZ4.uRu.Gvh6sAy', NULL, NULL),
(3, 'ROY', 'TANG', 'roy1@gmail.com', '011-1222222', '$2y$10$X02OthXz4JWMailohkZ9YOGoLYow3IM89OTAX3nWGy5awOh7v7QL.', NULL, NULL),
(4, 'ROY', 'TANG', 'roy2@gmail.com', '012-3456789', '$2y$10$DrSr9rhvOuI43.9DFhifwOCIVH7tWlYAW2iLA2q0PZXzTAV/pYu8u', NULL, NULL),
(5, 'ROY', 'TANG', 'roy3@gmail.com', '012-3456787', '$2y$10$J2Vjy9W/Bw.yB7e57MkojeuSYeCAnetBdeTp6Gc9FDIjPx7pQCP9a', NULL, NULL),
(6, 'ROY', 'TANG3', 'roy4@gmail.com', '012-3456786', '$2y$10$23VXhUOPMel8Npnqel/7CuvjQqpQNMbmNaPvViXvGcXGK7jDVfeZe', NULL, NULL),
(7, 'ROY5', 'TANG', 'roy5@gmail.com', '012-7772566', '$2y$10$KY7OdU8wlLSkNuL/KyJUBOGcH8O44Ugk19ir41/aLi/q4BVBlo76G', NULL, NULL),
(8, 'TANG', 'LE YI', 'leyitang031013@gmail.com', '017-8778729', '$2y$10$8SZVVUuIg9sKicWvwSy0PuTAJWS/gQmngobdP0.ft7FkdxaQzGrnC', NULL, NULL),
(9, 'ROY', 'TANG STUDENT', 'tangly-wm24@student.tarc.edu.my', '016-7779988', '$2y$10$h8uhhJERMBStUyJSWIqMhOr0SwKRhKGY5LFGzqAxX9sRN2mguRUiu', '924606', '2025-11-24 01:37:26'),
(10, 'TANG', 'LE YI', 'roy6@gmail.com', '012-7894564', '$2y$10$ES7LIWtozuSs5o6qEniFRuQbkwUc6GCVpqHTK89i1QsRBT4G3F6Z.', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
