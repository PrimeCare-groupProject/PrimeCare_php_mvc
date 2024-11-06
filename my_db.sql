-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 04, 2024 at 06:19 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `my_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `person`
--

CREATE TABLE `person` (
  `fname` varchar(20) NOT NULL,
  `lname` varchar(20) NOT NULL,
  `username` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `contact` varchar(10) NOT NULL,
  `password` varchar(256) NOT NULL,
  `image_url` varchar(255) DEFAULT 'user.png',
  `user_lvl` int(2) NOT NULL DEFAULT 1,
  `pid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `person`
--

INSERT INTO `person` (`fname`, `lname`, `username`, `email`, `contact`, `password`, `image_url`, `user_lvl`, `pid`) VALUES
('Wendt', 'Edmund', 'Wendt5WV97', 'serviceprovider@gmail.com', '0762213874', '$2y$10$dD38iRpTqFYLK154ahuKS.fp/dTMnhqj/xrkA0R/DQeKGhrONcubO', 'user.png', 2, 61),
('Wendt', 'Edmund', 'WendtR2EQ0', 'agent@gmail.com', '0762213874', '$2y$10$Gt6.mbORgBzZn6cyLS8KMOGTq4/e8WXsDvq7rD4g5kJ4vGo7YLr1.', 'user.png', 3, 62),
('Wendt', 'Edmund', 'WendtGCSN3', 'manager@gmail.com', '0762213874', '$2y$10$hJU6Sph7fBxjIfEWFF/FveOKgMtm4MFnya98mlHVgn0.2IF/gdyGi', 'user.png', 4, 63),
('Wendt', 'Edmund', 'Wendt8E6MU', 'wvedmund@gmail.com', '0762213874', '$2y$10$DhI8z4c6aM0suA2cX3.FI.jm4TKFOr95yzqM2vUEq/hi4gZER55VO', 'user.png', 1, 66),
('Wendt', 'Edmund', 'Wendt32215', 'user0@gmail.com', '0762213874', '$2y$10$GI0f3spkCuWU5AbVQmq9A.U66Ei/jX6fbIvnQs1/XyM5RJD6ytiCu', '672688e478161__user0@gmail.com.jpg', 1, 75),
('SP', 'User', 'SPUser123', 'sp@gmail.com', '0712345678', '$2y$10$dD38iRpTqFYLK154ahuKS.fp/dTMnhqj/xrkA0R/DQeKGhrONcubO', 'user.png', 2, 76);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`pid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `person`
--
ALTER TABLE `person`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
