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

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `service_type` varchar(100) NOT NULL,
  `date` DATE NOT NULL,
  `property_id` int(10) NOT NULL,
  `property_name` varchar(255) NOT NULL,
  `cost_per_hour` decimal(10,2) NOT NULL,
  `total_hours` int(10),
  `status` ENUM('Done', 'Pending', 'Ongoing') NOT NULL,
  `service_provider_id` int,
  `total_cost` decimal(10,2) AS (cost_per_hour * total_hours) VIRTUAL,
  `service_description` TEXT,
  FOREIGN KEY (`service_provider_id`) REFERENCES `person`(`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Sample data for services table
INSERT INTO `services` (`service_type`, `date`, `property_id`, `property_name`, `cost_per_hour`, `total_hours`, `status`, `service_provider_id`, `service_description`) VALUES
('Plumbing Repair', '2023-10-15', 1, 'Seaside Villa', 75.00, 3, 'Done', 61, 'Fixed leaking pipe in master bathroom'),
('Electrical Maintenance', '2023-10-16', 2, 'Mountain View Apartment', 85.00, 4, 'Ongoing', 61, 'Rewiring living room and kitchen'),
('Gardening', '2023-10-17', 3, 'Sunset Manor', 45.00, 5, 'Pending', 76, 'Monthly garden maintenance and lawn mowing'),
('Door lock Repair', '2023-10-18', 4, 'Downtown Condo', 95.00, 2, 'Done', 76, 'AC unit repair and maintenance'),
('Pool Maintenance', '2023-10-19', 5, 'Lakeside House', 65.00, 3, 'Ongoing', 61, 'Weekly pool cleaning and chemical balance check');


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
