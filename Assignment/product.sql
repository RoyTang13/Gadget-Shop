-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 16, 2025 at 02:20 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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
  `productStatus` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`productID`, `productName`, `productPrice`, `productDesc`, `productQty`, `productCat1`, `productCat2`, `productCat3`, `productPhoto`, `productStatus`) VALUES
(1, 'Backwin Brand Wired Earbuds', 27.00, '- Wired earbuds with dynamic bass boost provide you with an immersive music experience. The high notes of music will not break, while the low notes are strong and powerful. Great for listening to music!\r\n\r\n- 3.5mm in ear headphones with high-definition microphone on one side of the line\r\n\r\n- CNC metal processing shell and curved handle give design\r\n\r\n- Equipped with interchangeable large, medium, and small L/M/S earplugs.\r\n\r\n- Durable cables increase the lifespan of wired earphones', 150, 'Wired', 'In-Ear', 'Noise-Canceled', '692bb9e0d7537.jpg', 1),
(2, 'Lin Soul TIN HIFI T4', 399.00, 'Crafted in natural aluminum, the TINHIFI T4 delivers a sleek and durable design. With a comfortable in‑ear form factor and 32 Ohm impedance, it offers balanced audio performance suitable for everyday listening. Featuring sound isolation for an immersive experience, these earphones connect via a 3.5 mm jack for reliable wired use.', 200, 'Wired', 'In-Ear', 'Balanced', '692bff976e92f.jpg', 1),
(3, 'Sony IER‑EX15C USB‑C Wired In‑Ear Earphones (Black)', 90.00, 'Enjoy seamless connectivity across phones, laptops, and consoles with a simple USB‑C wired design—no charging or drop‑outs. Featuring a 5mm driver for rich bass and crystal‑clear vocals, these lightweight earphones deliver comfort with contoured housing and multiple ear tip sizes. The in‑line remote offers easy control for music, calls, and voice assistants, while the textured cable and slider reduce tangles. Designed with sustainability in mind, the packaging is plastic‑free, reflecting Sony’s commitment to eco‑friendly practices.', 300, 'Wired', 'In-Ear', 'Clear Vocals', '692c01a438b69.jpg', 0),
(4, 'Audio‑Technica ATH‑M50x', 568.00, 'Renowned by audio engineers and reviewers, the ATH‑M50x delivers exceptional clarity and deep bass with proprietary 45 mm drivers and neodymium magnets. Its closed‑back circumoral design ensures excellent sound isolation, while 90° swiveling earcups allow easy one‑ear monitoring. Built with professional‑grade pads and headband, these headphones are durable, comfortable, and collapsible for portability. Supplied with detachable cables (coiled and straight), a protective pouch, and a ¼\" adapter, the ATH‑M50x is a top choice for studio tracking, DJ monitoring, and personal listening.', 280, 'Wired', 'Over-Ear', 'Noise-Canceled', '692c04851e13c.jpg', 0),
(5, 'Beyerdynamic DT 880 Pro', 1020.00, 'Made in Germany for professional studio use, the DT 880 Pro delivers transparent, spacious, and natural sound across an extended 5–35,000 Hz frequency range. With 250-ohm impedance, it’s ideal for mixing, editing, and mastering. The semi‑open, circumneutral design ensures comfort and sound accuracy, supported by soft replaceable velour ear pads and a robust spring steel headband. Lightweight at 295 g, it includes a coiled cable with 3.5 mm jack and 6.35 mm adapter, offering durability and reliability for long sessions.', 200, 'Wired', 'Over-Ear', 'Balanced', '692c0914cfe7c.jpg', 0),
(6, 'Beyerdynamic DT 770 Pro', 818.00, 'Made in Germany, the DT 770 Pro delivers professional studio sound with an innovative bass reflex system and a wide 5–35,000 Hz frequency response. Its closed‑back circumneutral design ensures excellent isolation, while soft replaceable velour ear pads and a rugged padded headband provide long‑lasting comfort. With 250-ohm impedance, firm fit, and durable build, these headphones are ideal for mixing, monitoring, and mobile use. Lightweight at 270 g, they combine reliability with high‑quality audio performance.', 198, 'Wired', 'Over-Ear', 'Clear Vocals', '692c0b5ae25cc.jpg', 1),
(7, 'Sony WF‑1000XM5 Wireless Noise‑Cancelling Earbuds', 1099.00, 'Experience Sony’s best‑ever noise cancelling with dual processors, Dynamic Driver X, and innovative earbud tips for a stable fit. Delivering astonishing sound quality with deep bass, clear vocals, and fine detail, these earbuds also feature AI‑powered call clarity with bone conduction sensors and wind noise reduction. Compact and lightweight, they’re 25% smaller and 20% lighter than the previous model, with a sleek charging case for portability. Enjoy multipoint Bluetooth pairing, adaptive sound control, intuitive touch operation, and IPX4 water resistance—perfect for travel, work, and everyday listening.', 130, 'Wireless', 'In-Ear', 'Noise-Canceled', '692c129f0734b.jpg', 0),
(8, 'Samsung Galaxy Buds 2 Pro', 480.00, 'Step into 24‑bit Hi‑Fi audio with immersive 360° sound for a true cinematic experience. Enjoy crisp call quality with advanced noise reduction, even in busy environments. Featuring Intelligent Active Noise Cancellation (ANC) that’s 40% more powerful; you can block distractions or let in ambient sound as needed. Ergonomically designed for a secure, comfortable fit, the Buds2 Pro combine premium sound, clear conversations, and all‑day comfort in a sleek wireless package.', 80, 'Wireless', 'In-Ear', 'Balanced', '692c156dc99a9.jpg', 0),
(9, 'OPPO Enco Air 2 True Wireless Earbuds', 600.00, 'Powered by 13.4 mm composite tetanized drivers, the Enco Air2 delivers thumping bass, crystal‑clear treble, and rich Mids. Enhanced with unique bass boosters and Enco Live sound effects (Bass Boost & Clear Vocals), it offers a professional listening experience tuned by OPPO acoustics experts. Ultra‑light at 3.5 g per earbud, with an ergonomic fit and a translucent jelly case lid, these earbuds combine comfort and style. Enjoy up to 24 hours of playback with the charging case, Bluetooth® 5.2 low‑latency transmission, and AI noise cancellation for calls. With IPX4 water resistance, they’re perfect for music, gaming, and workouts.', 20, 'Wireless', 'In-Ear', 'Clear Vocals', '692c1a8953157.jpg', 1),
(10, 'Bose QuietComfort Ultra Earbuds - Black', 1150.00, 'Experience immersive spatialized audio and world‑class noise cancellation with Bose’s ultimate true wireless earbuds. Featuring Bose Immersive Audio for lifelike sound, these earbuds are designed for comfort with multiple ear tip and stability band options. Compact and lightweight at just 7 g each, they come with a sleek charging case, USB‑C cable, and Fit Kit. Perfect for music, calls, and everyday listening, the QuietComfort Ultra Earbuds deliver premium sound tailored to you.', 175, 'Wireless', 'Over-Ear', 'Noise-Canceled', '692c1e700f968.jpg', 1),
(11, 'JBL Tune 510BT', 206.50, 'Enjoy the signature JBL Pure Bass audio with wireless Bluetooth 5.0 streaming for high‑quality sound without cords. Offering up to 40 hours of battery life and speed charge (5 minutes = 2 hours playback), these lightweight 160 g on‑ear headphones are foldable and built for everyday use. Equipped with a 32 mm driver, built‑in microphone, and multi‑point connection, they support hands‑free calls, voice assistant integration, and intuitive ear‑cup controls. Perfect for music lovers seeking powerful bass, long playtime, and portable comfort.', 200, 'Wireless', 'Over-Ear', 'Balanced', '692c3c1a1dd26.jpg', 1),
(12, 'Soundcore Life Q30', 330.40, 'Enjoy hybrid active noise cancellation with three modes (Transport, Outdoor, Indoor) that block up to 95% of low‑frequency ambient sound. Powered by 40 mm silk drivers, the Life Q30 delivers hi‑res audio with thumping bass and crisp treble up to 40 kHz. With up to 40 hours of playtime in ANC mode (60 hours in standard mode) and fast charging (5 minutes = 4 hours), these headphones are built for all‑day listening. Ultra‑soft protein leather earcups with memory foam ensure pressure‑free comfort, while features like multipoint connection, dual mics for calls, and customizable EQ make them versatile for work, travel, and leisure. Lightweight at 260 g and TCO certified for sustainability, they combine performance, comfort, and eco‑friendly design.', 260, 'Wireless', 'Over-Ear', 'Clear Vocals', '692c3da179601.jpg', 1),
(15, '', 0.00, '', 0, '', '', '', '', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`productID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `productID` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
