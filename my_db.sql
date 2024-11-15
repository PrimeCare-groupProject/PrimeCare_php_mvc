-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2024 at 06:51 PM
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
-- Database: `my_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_id` int(11) NOT NULL,
  `property_id` int(11) DEFAULT NULL,
  `tenant_id` int(11) DEFAULT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `booked_date` datetime DEFAULT current_timestamp(),
  `renting_period` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `user_lvl` int(2) NOT NULL DEFAULT 0,
  `pid` int(11) NOT NULL,
  `reset_code` varchar(20) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `person`
--

INSERT INTO `person` (`fname`, `lname`, `username`, `email`, `contact`, `password`, `image_url`, `user_lvl`, `pid`, `reset_code`) VALUES
('Wendt', 'Edmund', 'Wendt5WV97', 'serviceprovider@gmail.com', '0762213874', '$2y$10$dD38iRpTqFYLK154ahuKS.fp/dTMnhqj/xrkA0R/DQeKGhrONcubO', 'user.png', 2, 61, NULL),
('Wendt', 'Edmund', 'WendtR2EQ0', 'agent@gmail.com', '0762213874', '$2y$10$Gt6.mbORgBzZn6cyLS8KMOGTq4/e8WXsDvq7rD4g5kJ4vGo7YLr1.', 'user.png', 3, 62, NULL),
('Wendt', 'Edmund', 'WendtGCSN3', 'manager@gmail.com', '0762213874', '$2y$10$hJU6Sph7fBxjIfEWFF/FveOKgMtm4MFnya98mlHVgn0.2IF/gdyGi', 'user.png', 4, 63, NULL),
('Wendt', 'Edmund', 'Wendt8E6MU', 'wvedmund@gmail.com', '0762213874', '$2y$10$DhI8z4c6aM0suA2cX3.FI.jm4TKFOr95yzqM2vUEq/hi4gZER55VO', 'user.png', 1, 66, NULL),
('Wendt', 'Edmund', 'Wendt32215', 'user0@gmail.com', '0762213874', '$2y$10$GI0f3spkCuWU5AbVQmq9A.U66Ei/jX6fbIvnQs1/XyM5RJD6ytiCu', '672688e478161__user0@gmail.com.jpg', 1, 75, NULL),
('SP', 'User', 'SPUser123', 'sp@gmail.com', '0712345678', '$2y$10$dD38iRpTqFYLK154ahuKS.fp/dTMnhqj/xrkA0R/DQeKGhrONcubO', 'user.png', 2, 76, NULL),
('Nimna', 'Pathum', 'Nimna2603S', 'nimna@gmail.com', '0703954041', '$2y$10$FiASLnzEf.OayXV4gTsyYOdCaoLbxdu/MYwbOWz53lOKvEm4kaDyC', '673051d1f4182__nimna@gmail.com.jpg', 1, 77, NULL),
('customer', 'customer', 'customerAA', 'customer@gmail.com', '0703954041', '$2y$10$KqSBpyTz66zpuGizPLZnGu36wWIUYrX.sw5m/qs4Bm8m0m/1d1dKy', 'user.png', 5, 78, NULL),
('owner', 'owner', 'ownerPU57Z', 'owner@gmail.com', '0703954031', '$2y$10$q47/xA7KQisQH5XwrMRLAOL5fAgEWy5iKu1eD1Rcoi09yTkDnXaCO', '673637be5492e__owner@gmail.com.jpg', 1, 79, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `property`
--

CREATE TABLE `property` (
  `property_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('shortTerm','monthly','serviceOnly') NOT NULL,
  `description` text NOT NULL,
  `address` varchar(255) NOT NULL,
  `zipcode` varchar(20) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state_province` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `year_built` int(4) NOT NULL,
  `rent_on_basis` decimal(10,2) NOT NULL,
  `units` int(11) NOT NULL,
  `size_sqr_ft` int(11) NOT NULL,
  `bedrooms` int(2) NOT NULL,
  `bathrooms` int(2) NOT NULL,
  `parking` varchar(3) NOT NULL,
  `furnished` varchar(3) NOT NULL,
  `floor_plan` text NOT NULL,
  `status` enum('active','inactive','under maintenance','sold','pending') NOT NULL DEFAULT 'active',
  `person_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `property`
--

INSERT INTO `property` (`property_id`, `name`, `type`, `description`, `address`, `zipcode`, `city`, `state_province`, `country`, `year_built`, `rent_on_basis`, `units`, `size_sqr_ft`, `bedrooms`, `bathrooms`, `parking`, `furnished`, `floor_plan`, `status`, `person_id`) VALUES
(24, 'test', 'shortTerm', 'test', 'test', '23356', 'test', 'test', 'india', 2022, 0.00, 1, 1, 1, 1, 'yes', 'yes', 'test', 'pending', 79);

-- --------------------------------------------------------

--
-- Table structure for table `property_deed_image`
--

CREATE TABLE `property_deed_image` (
  `image_url` varchar(255) NOT NULL,
  `property_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `property_deed_image`
--

INSERT INTO `property_deed_image` (`image_url`, `property_id`) VALUES
('6735f3a42d5cb_doc_24.pdf', 24);

-- --------------------------------------------------------

--
-- Table structure for table `property_image`
--

CREATE TABLE `property_image` (
  `image_url` varchar(255) NOT NULL,
  `property_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `property_image`
--

INSERT INTO `property_image` (`image_url`, `property_id`) VALUES
('6735f3a42b7e8_property_24.png', 24);

-- --------------------------------------------------------

--
-- Indexes for table `person`
--
    ALTER TABLE `person`
    ADD PRIMARY KEY (`pid`);
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL,
  `service_type` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `property_id` int(10) NOT NULL,
  `property_name` varchar(255) NOT NULL,
  `cost_per_hour` decimal(10,2) NOT NULL,
  `total_hours` int(10) DEFAULT NULL,
  `status` enum('Done','Pending','Ongoing') NOT NULL,
  `service_provider_id` int(11) DEFAULT NULL,
  `total_cost` decimal(10,2) GENERATED ALWAYS AS (`cost_per_hour` * `total_hours`) VIRTUAL,
  `service_description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `service_type`, `date`, `property_id`, `property_name`, `cost_per_hour`, `total_hours`, `status`, `service_provider_id`, `service_description`) VALUES
(1, 'Plumbing Repair', '2023-10-15', 1, 'Seaside Villa', 75.00, 3, 'Done', 61, 'Fixed leaking pipe in master bathroom'),
(2, 'Electrical Maintenance', '2023-10-16', 2, 'Mountain View Apartment', 85.00, 4, 'Ongoing', 61, 'Rewiring living room and kitchen'),
(3, 'Gardening', '2023-10-17', 3, 'Sunset Manor', 45.00, 5, 'Pending', 76, 'Monthly garden maintenance and lawn mowing'),
(4, 'Door lock Repair', '2023-10-18', 4, 'Downtown Condo', 95.00, 2, 'Done', 76, 'AC unit repair and maintenance'),
(5, 'Pool Maintenance', '2023-10-19', 5, 'Lakeside House', 65.00, 3, 'Ongoing', 61, 'Weekly pool cleaning and chemical balance check');


--
-- Indexes for dumped tables
--

--
<<<<<<< agent-crud

=======
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `property_id` (`property_id`),
  ADD KEY `tenant_id` (`tenant_id`),
  ADD KEY `agent_id` (`agent_id`);

--
-- Indexes for table `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`pid`);
>>>>>>> dev

--
-- Indexes for table `property`
--
ALTER TABLE `property`
  ADD PRIMARY KEY (`property_id`),
  ADD KEY `person_id` (`person_id`);

--
-- Indexes for table `property_deed_image`
--
ALTER TABLE `property_deed_image`
  ADD PRIMARY KEY (`image_url`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `property_image`
--
ALTER TABLE `property_image`
  ADD PRIMARY KEY (`image_url`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`),
  ADD KEY `service_provider_id` (`service_provider_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `person`
--
ALTER TABLE `person`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `property`
--
ALTER TABLE `property`
  MODIFY `property_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `property` (`property_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`tenant_id`) REFERENCES `person` (`pid`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_ibfk_3` FOREIGN KEY (`agent_id`) REFERENCES `person` (`pid`) ON DELETE CASCADE;

--
-- Constraints for table `property`
--
ALTER TABLE `property`
  ADD CONSTRAINT `property_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`pid`) ON DELETE CASCADE;

--
-- Constraints for table `property_deed_image`
--
ALTER TABLE `property_deed_image`
  ADD CONSTRAINT `property_deed_image_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `property` (`property_id`) ON DELETE CASCADE;

--
-- Constraints for table `property_image`
--
ALTER TABLE `property_image`
  ADD CONSTRAINT `property_image_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `property` (`property_id`) ON DELETE CASCADE;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`service_provider_id`) REFERENCES `person` (`pid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- Table structure for table `serpro`
--

CREATE TABLE `serpro` (
  `serpro_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `date_of_birth` DATE NOT NULL,
  `gender` varchar(255) NOT NULL,
  `contact_no1` int(10) NOT NULL,
  `contact_no2` int(10) NOT NULL,
  `email` varchar(255) NOT NULL,
  `bank_account_no` int(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `marital_status` varchar(255) NOT NULL,
  `NIC-no` int(12),
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Sample data for services table
INSERT INTO `services` (`service_type`, `date`, `property_id`, `property_name`, `cost_per_hour`, `total_hours`, `status`, `service_provider_id`, `service_description`) VALUES
('Plumbing Repair', '2023-10-15', 1, 'Seaside Villa', 75.00, 3, 'Done', 61, 'Fixed leaking pipe in master bathroom'),
('Electrical Maintenance', '2023-10-16', 2, 'Mountain View Apartment', 85.00, 4, 'Ongoing', 61, 'Rewiring living room and kitchen'),
('Gardening', '2023-10-17', 3, 'Sunset Manor', 45.00, 5, 'Pending', 76, 'Monthly garden maintenance and lawn mowing'),
('Door lock Repair', '2023-10-18', 4, 'Downtown Condo', 95.00, 2, 'Done', 76, 'AC unit repair and maintenance'),
('Pool Maintenance', '2023-10-19', 5, 'Lakeside House', 65.00, 3, 'Ongoing', 61, 'Weekly pool cleaning and chemical balance check');

'serpro_id',
        'first_name',
        'last_name', 
        'date_of_birth',
        'gender',
        'contact_number1',
        'contact_number2',
        'email',
        'bank_account_No',
        'address',
        'marital_status',
        'NIC_no.'
