-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 14, 2025 at 07:37 AM
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
  `reset_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`adminID`, `fname`, `lname`, `email`, `phoneNo`, `password`, `reset_code`, `reset_expiry`) VALUES
(3, 'Admin', '1', 'admin@technest.com', '1234567890', '$2y$10$HWISITAHj7Ao.s2pyhRgnuzuRFeYl1.dGflVKWzYD7dRDBv/ScidG', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `productID` int(4) NOT NULL,
  `quantity` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `userID`, `productID`, `quantity`, `added_at`) VALUES
(1, 1, '1', 2, '2025-12-13 06:03:30'),
(20, 10, '2', 1, '2025-12-14 06:30:20');

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
(2, 10, '2025-12-14 00:37:04', '2073.80', 'Pending'),
(3, 10, '2025-12-14 14:00:13', '960.00', 'Pending'),
(4, 10, '2025-12-14 14:02:51', '206.50', 'Pending'),
(5, 10, '2025-12-14 14:22:57', '2236.00', 'Pending'),
(6, 10, '2025-12-14 14:27:47', '90.00', 'Pending'),
(7, 10, '2025-12-14 14:28:42', '27.00', 'Pending'),
(8, 10, '2025-12-14 14:30:27', '1099.00', 'Pending'),
(9, 10, '2025-12-14 14:34:58', '27.00', 'Pending');

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
(1, 2, 0, 2, '330.40'),
(2, 2, 0, 3, '399.00'),
(3, 2, 0, 8, '27.00'),
(4, 3, 0, 2, '480.00'),
(5, 4, 0, 1, '206.50'),
(6, 5, 0, 2, '818.00'),
(7, 5, 0, 1, '600.00'),
(8, 6, 0, 1, '90.00'),
(9, 7, 0, 1, '27.00'),
(10, 8, 0, 1, '1099.00'),
(11, 9, 0, 1, '27.00');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `productID` int(4) NOT NULL,
  `productName` varchar(150) NOT NULL,
  `productPrice` decimal(6,2) NOT NULL,
  `productDesc` varchar(1000) NOT NULL,
  `productQty` int(3) NOT NULL,
  `productCat1` enum('Wired','Wireless') NOT NULL,
  `productCat2` enum('In-Ear','Over-Ear') NOT NULL,
  `productCat3` enum('Noise-Canceled','Balanced','Clear Vocals') NOT NULL,
  `productPhoto` varchar(100) NOT NULL,
  `productDisp` enum('no','yes') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`productID`, `productName`, `productPrice`, `productDesc`, `productQty`, `productCat1`, `productCat2`, `productCat3`, `productPhoto`, `productDisp`) VALUES
('1', 'Backwin Brand Wired Earbuds', '27.00', '- Wired earbuds with dynamic bass boost provide you with an immersive music experience. The high notes of music will not break, while the low notes are strong and powerful\r\n\r\n- 3.5mm in ear headphones with high-definition microphone on one side of the line\r\n\r\n- CNC metal processing shell and curved handle give design\r\n\r\n- Equipped with interchangeable large, medium, and small L/M/S earplugs.\r\n\r\n- Durable cables increase the lifespan of wired earphones', 150, 'Wired', 'In-Ear', 'Noise-Canceled', '692bb9e0d7537.jpg', 'yes'),
('2', 'Lin Soul TIN HIFI T4', '399.00', 'Crafted in natural aluminum, the TINHIFI T4 delivers a sleek and durable design. With a comfortable in‑ear form factor and 32 Ohm impedance, it offers balanced audio performance suitable for everyday listening. Featuring sound isolation for an immersive experience, these earphones connect via a 3.5 mm jack for reliable wired use.', 200, 'Wired', 'In-Ear', 'Balanced', '692bff976e92f.jpg', 'no'),
('3', 'Sony IER‑EX15C USB‑C Wired In‑Ear Earphones (Black)', '90.00', 'Enjoy seamless connectivity across phones, laptops, and consoles with a simple USB‑C wired design—no charging or drop‑outs. Featuring a 5mm driver for rich bass and crystal‑clear vocals, these lightweight earphones deliver comfort with contoured housing and multiple ear tip sizes. The in‑line remote offers easy control for music, calls, and voice assistants, while the textured cable and slider reduce tangles. Designed with sustainability in mind, the packaging is plastic‑free, reflecting Sony’s commitment to eco‑friendly practices.', 300, 'Wired', 'In-Ear', 'Clear Vocals', '692c01a438b69.jpg', 'no'),
('4', 'Audio‑Technica ATH‑M50x', '568.00', 'Renowned by audio engineers and reviewers, the ATH‑M50x delivers exceptional clarity and deep bass with proprietary 45 mm drivers and neodymium magnets. Its closed‑back circumoral design ensures excellent sound isolation, while 90° swiveling earcups allow easy one‑ear monitoring. Built with professional‑grade pads and headband, these headphones are durable, comfortable, and collapsible for portability. Supplied with detachable cables (coiled and straight), a protective pouch, and a ¼\" adapter, the ATH‑M50x is a top choice for studio tracking, DJ monitoring, and personal listening.', 280, 'Wired', 'Over-Ear', 'Noise-Canceled', '692c04851e13c.jpg', 'no'),
('5', 'Beyerdynamic DT 880 Pro', '1020.00', 'Made in Germany for professional studio use, the DT 880 Pro delivers transparent, spacious, and natural sound across an extended 5–35,000 Hz frequency range. With 250-ohm impedance, it’s ideal for mixing, editing, and mastering. The semi‑open, circumneutral design ensures comfort and sound accuracy, supported by soft replaceable velour ear pads and a robust spring steel headband. Lightweight at 295 g, it includes a coiled cable with 3.5 mm jack and 6.35 mm adapter, offering durability and reliability for long sessions.', 200, 'Wired', 'Over-Ear', 'Balanced', '692c0914cfe7c.jpg', 'no'),
('6', 'Beyerdynamic DT 770 Pro', '818.00', 'Made in Germany, the DT 770 Pro delivers professional studio sound with an innovative bass reflex system and a wide 5–35,000 Hz frequency response. Its closed‑back circumneutral design ensures excellent isolation, while soft replaceable velour ear pads and a rugged padded headband provide long‑lasting comfort. With 250-ohm impedance, firm fit, and durable build, these headphones are ideal for mixing, monitoring, and mobile use. Lightweight at 270 g, they combine reliability with high‑quality audio performance.', 198, 'Wired', 'Over-Ear', 'Clear Vocals', '692c0b5ae25cc.jpg', 'no'),
('7', 'Sony WF‑1000XM5 Wireless Noise‑Cancelling Earbuds', '1099.00', 'Experience Sony’s best‑ever noise cancelling with dual processors, Dynamic Driver X, and innovative earbud tips for a stable fit. Delivering astonishing sound quality with deep bass, clear vocals, and fine detail, these earbuds also feature AI‑powered call clarity with bone conduction sensors and wind noise reduction. Compact and lightweight, they’re 25% smaller and 20% lighter than the previous model, with a sleek charging case for portability. Enjoy multipoint Bluetooth pairing, adaptive sound control, intuitive touch operation, and IPX4 water resistance—perfect for travel, work, and everyday listening.', 130, 'Wireless', 'In-Ear', 'Noise-Canceled', '692c129f0734b.jpg', 'no'),
('8', 'Samsung Galaxy Buds 2 Pro', '480.00', 'Step into 24‑bit Hi‑Fi audio with immersive 360° sound for a true cinematic experience. Enjoy crisp call quality with advanced noise reduction, even in busy environments. Featuring Intelligent Active Noise Cancellation (ANC) that’s 40% more powerful; you can block distractions or let in ambient sound as needed. Ergonomically designed for a secure, comfortable fit, the Buds2 Pro combine premium sound, clear conversations, and all‑day comfort in a sleek wireless package.', 80, 'Wireless', 'In-Ear', 'Balanced', '692c156dc99a9.jpg', 'no'),
('9', 'OPPO Enco Air 2 True Wireless Earbuds', '600.00', 'Powered by 13.4 mm composite tetanized drivers, the Enco Air2 delivers thumping bass, crystal‑clear treble, and rich Mids. Enhanced with unique bass boosters and Enco Live sound effects (Bass Boost & Clear Vocals), it offers a professional listening experience tuned by OPPO acoustics experts. Ultra‑light at 3.5 g per earbud, with an ergonomic fit and a translucent jelly case lid, these earbuds combine comfort and style. Enjoy up to 24 hours of playback with the charging case, Bluetooth® 5.2 low‑latency transmission, and AI noise cancellation for calls. With IPX4 water resistance, they’re perfect for music, gaming, and workouts.', 20, 'Wireless', 'In-Ear', 'Clear Vocals', '692c1a8953157.jpg', 'no'),
('10', 'Bose QuietComfort Ultra Earbuds - Black', '1150.00', 'Experience immersive spatialized audio and world‑class noise cancellation with Bose’s ultimate true wireless earbuds. Featuring Bose Immersive Audio for lifelike sound, these earbuds are designed for comfort with multiple ear tip and stability band options. Compact and lightweight at just 7 g each, they come with a sleek charging case, USB‑C cable, and Fit Kit. Perfect for music, calls, and everyday listening, the QuietComfort Ultra Earbuds deliver premium sound tailored to you.', 175, 'Wireless', 'Over-Ear', 'Noise-Canceled', '692c1e700f968.jpg', 'no'),
('11', 'JBL Tune 510BT', '206.50', 'Enjoy the signature JBL Pure Bass audio with wireless Bluetooth 5.0 streaming for high‑quality sound without cords. Offering up to 40 hours of battery life and speed charge (5 minutes = 2 hours playback), these lightweight 160 g on‑ear headphones are foldable and built for everyday use. Equipped with a 32 mm driver, built‑in microphone, and multi‑point connection, they support hands‑free calls, voice assistant integration, and intuitive ear‑cup controls. Perfect for music lovers seeking powerful bass, long playtime, and portable comfort.', 200, 'Wireless', 'Over-Ear', 'Balanced', '692c3c1a1dd26.jpg', 'no'),
('12', 'Soundcore Life Q30', '330.40', 'Enjoy hybrid active noise cancellation with three modes (Transport, Outdoor, Indoor) that block up to 95% of low‑frequency ambient sound. Powered by 40 mm silk drivers, the Life Q30 delivers hi‑res audio with thumping bass and crisp treble up to 40 kHz. With up to 40 hours of playtime in ANC mode (60 hours in standard mode) and fast charging (5 minutes = 4 hours), these headphones are built for all‑day listening. Ultra‑soft protein leather earcups with memory foam ensure pressure‑free comfort, while features like multipoint connection, dual mics for calls, and customizable EQ make them versatile for work, travel, and leisure. Lightweight at 260 g and TCO certified for sustainability, they combine performance, comfort, and eco‑friendly design.', 260, 'Wireless', 'Over-Ear', 'Clear Vocals', '692c3da179601.jpg', 'no');

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
  `reset_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `fname`, `lname`, `email`, `phoneNo`, `userPhoto`, `password`, `reset_code`, `reset_expiry`) VALUES
(1, 'JOHN', 'TANG', 'john124@gmail.com', '012-3332223', '', '$2y$10$3cdDtxVe4SF14KkCDRXqz.PbxaA.fb.PpXkGkKdZ4.uRu.Gvh6sAy', NULL, NULL),
(2, 'JOHN', 'TANG', 'john123@gmail.com', '012-3332222', '', '$2y$10$3cdDtxVe4SF14KkCDRXqz.PbxaA.fb.PpXkGkKdZ4.uRu.Gvh6sAy', NULL, NULL),
(3, 'ROY', 'TANG', 'roy1@gmail.com', '011-1222222', '', '$2y$10$X02OthXz4JWMailohkZ9YOGoLYow3IM89OTAX3nWGy5awOh7v7QL.', NULL, NULL),
(4, 'ROY', 'TANG', 'roy2@gmail.com', '012-3456789', '', '$2y$10$DrSr9rhvOuI43.9DFhifwOCIVH7tWlYAW2iLA2q0PZXzTAV/pYu8u', NULL, NULL),
(5, 'ROY', 'TANG', 'roy3@gmail.com', '012-3456787', '', '$2y$10$J2Vjy9W/Bw.yB7e57MkojeuSYeCAnetBdeTp6Gc9FDIjPx7pQCP9a', NULL, NULL),
(6, 'ROY', 'TANG3', 'roy4@gmail.com', '012-3456786', '', '$2y$10$23VXhUOPMel8Npnqel/7CuvjQqpQNMbmNaPvViXvGcXGK7jDVfeZe', NULL, NULL),
(7, 'ROY5', 'TANG', 'roy5@gmail.com', '012-7772566', '', '$2y$10$KY7OdU8wlLSkNuL/KyJUBOGcH8O44Ugk19ir41/aLi/q4BVBlo76G', NULL, NULL),
(8, 'TANG', 'LE YI', 'leyitang031013@gmail.com', '017-8778729', '', '$2y$10$tubLgmyiCMYxLFdR9l.T5uVdUJkWM/v4xl.ac38jdj6zF1PuKsMcC', '696467', '2025-12-02 00:57:03'),
(9, 'ROY', 'TANG STUDENT', 'tangly-wm24@student.tarc.edu.my', '016-7779988', '', '$2y$10$h8uhhJERMBStUyJSWIqMhOr0SwKRhKGY5LFGzqAxX9sRN2mguRUiu', '924606', '2025-11-24 01:37:26'),
(10, 'TANG', 'LE YI', 'roy6@gmail.com', '012-9999898', '692e97b55ead6.jpg', '$2y$10$m9ZeGmqomlPDmqCvfymvTeqIfWj5cKMh.a0hfarhaPmgq2CBTK/Gm', NULL, NULL),
(11, 'ROY', 'TANG', 'roy7@gmail.com', '017-2229999', '', '$2y$10$1LSrHJoXD9J1l2d2FmtYQO5fCx0ENwuDHQeeVwSwJgcVMw5eRlG0m', NULL, NULL),
(12, 'ROY', 'TANG', 'roy8@gmail.com', '017-8882222', '', '$2y$10$/X4uZWsNNzf.gnHTa.WMk.BK/b.VXEiTaR5yZs1pDIe9NBlM6Dn9q', NULL, NULL),
(13, 'ROY9', '91', 'roy91@gmail.com', '012-7894888', '', '$2y$10$PAAn2cSUu5.9/dJ7GiqO2utwflwTTIi/1sxL44y6dtbXmbqOMgCBa', NULL, NULL),
(14, 'ROY', '10', 'roy10@gmail.com', '017-8889988', '692d8c3176af8.jpg', '$2y$10$wHcw6UjhdBNk6H406/Jut.PQC21jhVjjg3fMQriNu./9eCP13tYqu', NULL, NULL),
(15, 'ROY', '11', 'roy11@gmail.com', '017-7778888', '692ea9b294d57.jpg', '$2y$10$wkX5zSIiEjqAh00JTna/au66VfpaUq6.aohacmuTmIRkzf2/WPmFK', NULL, NULL),
(16, 'ROY', '12', 'roy12@gmail.com', '017-8889999', '', '$2y$10$yO9ZT1bm/kf5mA6quwk9d.FGnllAXEg4/TJv3MIy7nzLtOcrS595.', NULL, NULL),
(17, 'USER', '1', 'user1@gmail.com', '017-8889992', '692dbe3707264.jpg', '$2y$10$TQmnN4uQUUjvCzRBb0Wwx.Ta2r/P4jZSu5.WEwicyRU0BDNWASdMi', NULL, NULL);

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
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `productID` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `orderItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`productID`) REFERENCES `product` (`productID`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
