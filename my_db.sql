-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 21, 2024 at 06:20 PM
-- Generation Time: Nov 21, 2024 at 06:20 PM
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
-- Database: `commen`
--
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

INSERT INTO `person` (`fname`, `lname`, `username`, `email`, `contact`, `password`, `image_url`, `user_lvl`, `pid`, `reset_code`, `created_date`) VALUES
('niki', 'gay', 'niki5IB36L', 'customer@gmail.com', '0464434353', '$2y$10$iz4D/kVuOTmD57wC80tPk.rg3sMOqbnUXFPc7wCEq3QqxNz3s8MAe', 'user.png', 0, 0, NULL, '2024-11-20 00:31:08'),
('Wendt', 'Edmund', 'Wendt5WV97', 'serviceprovider@gmail.com', '0762213874', '$2y$10$dD38iRpTqFYLK154ahuKS.fp/dTMnhqj/xrkA0R/DQeKGhrONcubO', 'user.png', 2, 61, NULL, '2024-11-19 18:32:12'),
('Wendt', 'Edmund', 'WendtR2EQ0', 'agent@gmail.com', '0762213874', '$2y$10$Gt6.mbORgBzZn6cyLS8KMOGTq4/e8WXsDvq7rD4g5kJ4vGo7YLr1.', 'user.png', 3, 62, NULL, '2024-11-19 18:32:12'),
('Wendt', 'Edmund', 'WendtGCSN3', 'manager@gmail.com', '0762213874', '$2y$10$hJU6Sph7fBxjIfEWFF/FveOKgMtm4MFnya98mlHVgn0.2IF/gdyGi', 'user.png', 4, 63, NULL, '2024-11-19 18:32:12'),
('Wendt', 'Edmund', 'Wendt8E6MU', 'wvedmund@gmail.com', '0762213874', '$2y$10$DhI8z4c6aM0suA2cX3.FI.jm4TKFOr95yzqM2vUEq/hi4gZER55VO', 'user.png', 1, 66, NULL, '2024-11-19 18:32:12'),
('Wendt', 'Edmund', 'Wendt32215', 'user0@gmail.com', '0762213874', '$2y$10$GI0f3spkCuWU5AbVQmq9A.U66Ei/jX6fbIvnQs1/XyM5RJD6ytiCu', '672688e478161__user0@gmail.com.jpg', 1, 75, NULL, '2024-11-19 18:32:12'),
('SP', 'User', 'SPUser123', 'sp@gmail.com', '0712345678', '$2y$10$dD38iRpTqFYLK154ahuKS.fp/dTMnhqj/xrkA0R/DQeKGhrONcubO', 'user.png', 2, 76, NULL, '2024-11-19 18:32:12'),
('Nimna', 'Pathum', 'Nimna2603S', 'nimna@gmail.com', '0703954041', '$2y$10$FiASLnzEf.OayXV4gTsyYOdCaoLbxdu/MYwbOWz53lOKvEm4kaDyC', '673051d1f4182__nimna@gmail.com.jpg', 1, 77, NULL, '2024-11-19 18:32:12'),
('owner', 'owner', 'ownerPU57Z', 'owner@gmail.com', '0703954031', '$2y$10$q47/xA7KQisQH5XwrMRLAOL5fAgEWy5iKu1eD1Rcoi09yTkDnXaCO', '673637be5492e__owner@gmail.com.jpg', 1, 79, NULL, '2024-11-19 18:32:12'),
('nimna', 'pathum', 'nimnaM4J27', 'pathum@gmail.com', '0715417980', '$2y$10$3IGS8q1/luZSsXBpZz3ReeIzNE9BEyripN1LIFh3wmiZ4PJcV6xwm', 'user.png', 2, 80, NULL, '2024-11-19 18:32:12'),
('customer', 'customer', 'customer92', 'forcustomer@gmail.com', '0715417980', '$2y$10$iKyFlX3r6YKchdoIkVec9.pq3Ej9cIsQXX/VD.H37p.yips/geK1G', 'user.png', 1, 81, NULL, '2024-11-19 18:32:12'),
('password', 'provider', 'password5Y', 'sprovider@gmail.com', '0715417980', '$2y$10$yKQzcal5reWf0FR48WO32eR4YpMU7cdYpN0advapp2rcpe4JOi2oS', 'user.png', 2, 82, NULL, '2024-11-19 18:32:12');

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
  `person_id` int(11) NOT NULL,
  `agent_id` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `property`
--

INSERT INTO `property` (`property_id`, `name`, `type`, `description`, `address`, `zipcode`, `city`, `state_province`, `country`, `year_built`, `rent_on_basis`, `units`, `size_sqr_ft`, `bedrooms`, `bathrooms`, `parking`, `furnished`, `floor_plan`, `status`, `person_id`, `agent_id`) VALUES
(25, 'test', 'shortTerm', 'test', 'test', '23356', 'test', 'test', 'india', 2022, 0.00, 1, 1, 1, 1, 'yes', 'yes', 'test', 'pending', 79, 0),
(26, 'Heaven resort', 'shortTerm', 'This is the best place to live without disturbing', 'No 99  Nugagahahena  Bandaramulla', '81740', 'Matara', 'Southern', 'Sri Lanka', 2002, 20000.00, 4, 1200, 2, 1, 'yes', 'yes', 'Living area and two bedrooms with one bathroom', 'pending', 79, 0),
(28, 'Meka delete krnne na ehem', 'shortTerm', 'test', 'test', '23356', 'test', 'test', 'india', 2022, 0.00, 1, 1, 1, 1, 'yes', 'yes', 'test for delete', 'pending', 79, 0);

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
('673744387aec7_doc_25.pdf', 25),
('67376473e20a4_doc_26.pdf', 26),
('67382b8e10e67_doc_28.pdf', 28);


--
-- Table structure for table `property_deed_image_temp`
--

CREATE TABLE `property_deed_image_temp` (
  `image_url` varchar(255) NOT NULL,
  `property_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
('673744387aec7_doc_25.pdf', 25),
('67376473e20a4_doc_26.pdf', 26),
('67382b8e10e67_doc_28.pdf', 28);

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
('67374438771d3_property_25.jpg', 25),
('673744387852a_property_25.png', 25),
('6737443879788_property_25.jpg', 25),
('67376473db170_property_26.jpg', 26),
('67376473dc125_property_26.jpg', 26),
('67376473e1223_property_26.jpg', 26),
('67382b8e0a73b_property_28.jpg', 28);

-- --------------------------------------------------------
--
-- Table structure for table `property_temp`
--

CREATE TABLE `property_temp` (
  `property_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` enum('short term','monthly','service only') DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `zipcode` varchar(20) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `state_province` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `year_built` int(4) DEFAULT NULL,
  `rent_on_basis` decimal(10,2) DEFAULT NULL,
  `units` int(11) DEFAULT NULL,
  `size_sqr_ft` int(11) DEFAULT NULL,
  `bedrooms` int(2) DEFAULT NULL,
  `bathrooms` int(2) DEFAULT NULL,
  `parking` varchar(3) DEFAULT NULL,
  `furnished` varchar(3) DEFAULT NULL,
  `floor_plan` text DEFAULT NULL,
  `status` enum('active','inactive','under maintenance','sold','pending') DEFAULT NULL,
  `person_id` int(11) DEFAULT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `request_type` enum('update','removal','acceptance','') NOT NULL DEFAULT 'acceptance'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Stand-in structure for view `property_with_images`
-- (See below for the actual view)
--
CREATE TABLE `property_with_images` (
`property_id` int(11)
,`name` varchar(255)
,`description` text
,`type` enum('shortTerm','monthly','serviceOnly')
,`address` varchar(255)
,`zipcode` varchar(20)
,`city` varchar(100)
,`state_province` varchar(100)
,`country` varchar(100)
,`year_built` int(4)
,`rent_on_basis` decimal(10,2)
,`units` int(11)
,`size_sqr_ft` int(11)
,`bedrooms` int(2)
,`bathrooms` int(2)
,`parking` varchar(3)
,`furnished` varchar(3)
,`floor_plan` text
,`status` enum('active','inactive','under maintenance','sold','pending')
,`person_id` int(11)
,`property_images` mediumtext
,`property_deed_images` mediumtext
);

--
-- Structure for view `property_with_images`
--
DROP TABLE IF EXISTS `property_with_images`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `property_with_images`  AS SELECT `p`.`property_id` AS `property_id`, `p`.`name` AS `name`, `p`.`description` AS `description`, `p`.`type` AS `type`, `p`.`address` AS `address`, `p`.`zipcode` AS `zipcode`, `p`.`city` AS `city`, `p`.`state_province` AS `state_province`, `p`.`country` AS `country`, `p`.`year_built` AS `year_built`, `p`.`rent_on_basis` AS `rent_on_basis`, `p`.`units` AS `units`, `p`.`size_sqr_ft` AS `size_sqr_ft`, `p`.`bedrooms` AS `bedrooms`, `p`.`bathrooms` AS `bathrooms`, `p`.`parking` AS `parking`, `p`.`furnished` AS `furnished`, `p`.`floor_plan` AS `floor_plan`, `p`.`status` AS `status`, `p`.`person_id` AS `person_id`, group_concat(distinct `pi`.`image_url` order by `pi`.`image_url` ASC separator ',') AS `property_images`, group_concat(distinct `pdi`.`image_url` order by `pdi`.`image_url` ASC separator ',') AS `property_deed_images` FROM ((`property` `p` left join `property_image` `pi` on(`p`.`property_id` = `pi`.`property_id`)) left join `property_deed_image` `pdi` on(`p`.`property_id` = `pdi`.`property_id`)) GROUP BY `p`.`property_id` ;

-- --------------------------------------------------------
-- Table structure for table `serviceLogs`
=========
-- Table structure for table `property_image_temp`
--


CREATE TABLE `property_image_temp` (
  `image_url` varchar(255) NOT NULL,
  `property_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `property_temp`
--

CREATE TABLE `property_temp` (
  `property_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` enum('short term','monthly','service only') DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `zipcode` varchar(20) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `state_province` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `year_built` int(4) DEFAULT NULL,
  `rent_on_basis` decimal(10,2) DEFAULT NULL,
  `units` int(11) DEFAULT NULL,
  `size_sqr_ft` int(11) DEFAULT NULL,
  `bedrooms` int(2) DEFAULT NULL,
  `bathrooms` int(2) DEFAULT NULL,
  `parking` varchar(3) DEFAULT NULL,
  `furnished` varchar(3) DEFAULT NULL,
  `floor_plan` text DEFAULT NULL,
  `status` enum('active','inactive','under maintenance','sold','pending') DEFAULT NULL,
  `person_id` int(11) DEFAULT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `request_type` enum('update','removal','acceptance','') NOT NULL DEFAULT 'acceptance'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `property_with_images`
-- (See below for the actual view)
--
CREATE TABLE `property_with_images` (
`property_id` int(11)
,`name` varchar(255)
,`description` text
,`type` enum('shortTerm','monthly','serviceOnly')
,`address` varchar(255)
,`zipcode` varchar(20)
,`city` varchar(100)
,`state_province` varchar(100)
,`country` varchar(100)
,`year_built` int(4)
,`rent_on_basis` decimal(10,2)
,`units` int(11)
,`size_sqr_ft` int(11)
,`bedrooms` int(2)
,`bathrooms` int(2)
,`parking` varchar(3)
,`furnished` varchar(3)
,`floor_plan` text
,`status` enum('active','inactive','under maintenance','sold','pending')
,`person_id` int(11)
,`property_images` mediumtext
,`property_deed_images` mediumtext
);

-- --------------------------------------------------------
-- Table structure for table `serviceLog`
--
-- Stand-in structure for view `property_with_images`
-- (See below for the actual view)
--
CREATE TABLE `property_with_images` (
`property_id` int(11)
,`name` varchar(255)
,`description` text
,`type` enum('shortTerm','monthly','serviceOnly')
,`address` varchar(255)
,`zipcode` varchar(20)
,`city` varchar(100)
,`state_province` varchar(100)
,`country` varchar(100)
,`year_built` int(4)
,`rent_on_basis` decimal(10,2)
,`units` int(11)
,`size_sqr_ft` int(11)
,`bedrooms` int(2)
,`bathrooms` int(2)
,`parking` varchar(3)
,`furnished` varchar(3)
,`floor_plan` text
,`status` enum('active','inactive','under maintenance','sold','pending')
,`person_id` int(11)
,`property_images` mediumtext
,`property_deed_images` mediumtext
);

--
-- Structure for view `property_with_images`
--
DROP TABLE IF EXISTS `property_with_images`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `property_with_images`  AS SELECT `p`.`property_id` AS `property_id`, `p`.`name` AS `name`, `p`.`description` AS `description`, `p`.`type` AS `type`, `p`.`address` AS `address`, `p`.`zipcode` AS `zipcode`, `p`.`city` AS `city`, `p`.`state_province` AS `state_province`, `p`.`country` AS `country`, `p`.`year_built` AS `year_built`, `p`.`rent_on_basis` AS `rent_on_basis`, `p`.`units` AS `units`, `p`.`size_sqr_ft` AS `size_sqr_ft`, `p`.`bedrooms` AS `bedrooms`, `p`.`bathrooms` AS `bathrooms`, `p`.`parking` AS `parking`, `p`.`furnished` AS `furnished`, `p`.`floor_plan` AS `floor_plan`, `p`.`status` AS `status`, `p`.`person_id` AS `person_id`, group_concat(distinct `pi`.`image_url` order by `pi`.`image_url` ASC separator ',') AS `property_images`, group_concat(distinct `pdi`.`image_url` order by `pdi`.`image_url` ASC separator ',') AS `property_deed_images` FROM ((`property` `p` left join `property_image` `pi` on(`p`.`property_id` = `pi`.`property_id`)) left join `property_deed_image` `pdi` on(`p`.`property_id` = `pdi`.`property_id`)) GROUP BY `p`.`property_id` ;

-- --------------------------------------------------------
-- Table structure for table `serviceLogs`
--

CREATE TABLE `serviceLog` (
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
-- Dumping data for table `serviceLog`
--

INSERT INTO `serviceLog` (`service_id`, `service_type`, `date`, `property_id`, `property_name`, `cost_per_hour`, `total_hours`, `status`, `service_provider_id`, `service_description`) VALUES
(1, 'Plumbing Repair', '2023-10-15', 1, 'Seaside Villa', 75.00, 3, 'Done', 61, 'Fixed leaking pipe in master bathroom'),
(2, 'Electrical Maintenance', '2023-10-16', 2, 'Mountain View Apartment', 85.00, 4, 'Ongoing', 61, 'Rewiring living room and kitchen'),
(3, 'Gardening', '2023-10-17', 3, 'Sunset Manor', 45.00, 5, 'Pending', 76, 'Monthly garden maintenance and lawn mowing'),
(4, 'Door lock Repair', '2023-10-18', 4, 'Downtown Condo', 95.00, 2, 'Done', 76, 'AC unit repair and maintenance'),
(5, 'Pool Maintenance', '2023-10-19', 5, 'Lakeside House', 65.00, 3, 'Ongoing', 61, 'Weekly pool cleaning and chemical balance check');


--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `cost_per_hour` DOUBLE NOT NULL,
  `description` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `name`, `cost_per_hour`, `description`) VALUES
(1, 'Door Repairing', 1000, 'This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. '),
(2, 'Deck Repairing', 1000, 'This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. '),
(3, 'Roof Repairing', 1000, 'This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. '),
(4, 'Plumbing', 1000, 'This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing.'),
(5, 'Concrete Repairing', 1000, 'This is concrete repairing');

-- --------------------------------------------------------

-- Structure for view `property_with_images`
--
DROP TABLE IF EXISTS `property_with_images`;
CREATE OR REPLACE VIEW property_with_images AS
SELECT 
    p.property_id AS property_id, 
    p.name AS name,
    p.description AS description,
    p.type AS type,
    p.address AS address,
    p.zipcode AS zipcode,
    p.city AS city,
    p.state_province AS state_province,
    p.country AS country,
    p.year_built AS year_built,
    p.rent_on_basis AS rent_on_basis,
    p.units AS units,
    p.size_sqr_ft AS size_sqr_ft,
    p.bedrooms AS bedrooms,
    p.bathrooms AS bathrooms,
    p.parking AS parking,
    p.furnished AS furnished,
    p.floor_plan AS floor_plan,
    p.status AS status,
    p.person_id AS person_id,
    GROUP_CONCAT(DISTINCT pi.image_url ORDER BY pi.image_url ASC SEPARATOR ',') AS property_images,
    GROUP_CONCAT(DISTINCT pdi.image_url ORDER BY pdi.image_url ASC SEPARATOR ',') AS property_deed_images
FROM 
    property p
LEFT JOIN 
    property_image pi ON p.property_id = pi.property_id
LEFT JOIN 
    property_deed_image pdi ON p.property_id = pdi.property_id
GROUP BY 
    p.property_id;

-- CREATE ALGORITHM=UNDEFINED DEFINER=`primecare`@`mysql-primecare.alwaysdata.net` SQL SECURITY DEFINER VIEW `property_with_images`  AS SELECT `p`.`property_id` AS `property_id`, `p`.`name` AS `name`, `p`.`description` AS `description`, `p`.`type` AS `type`, `p`.`address` AS `address`, `p`.`zipcode` AS `zipcode`, `p`.`city` AS `city`, `p`.`state_province` AS `state_province`, `p`.`country` AS `country`, `p`.`year_built` AS `year_built`, `p`.`rent_on_basis` AS `rent_on_basis`, `p`.`units` AS `units`, `p`.`size_sqr_ft` AS `size_sqr_ft`, `p`.`bedrooms` AS `bedrooms`, `p`.`bathrooms` AS `bathrooms`, `p`.`parking` AS `parking`, `p`.`furnished` AS `furnished`, `p`.`floor_plan` AS `floor_plan`, `p`.`status` AS `status`, `p`.`person_id` AS `person_id`, group_concat(distinct `pi`.`image_url` order by `pi`.`image_url` ASC separator ',') AS `property_images`, group_concat(distinct `pdi`.`image_url` order by `pdi`.`image_url` ASC separator ',') AS `property_deed_images` FROM ((`property` `p` left join `property_image` `pi` on(`p`.`property_id` = `pi`.`property_id`)) left join `property_deed_image` `pdi` on(`p`.`property_id` = `pdi`.`property_id`)) GROUP BY `p`.`property_id` ;
-- CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `property_with_images`  AS SELECT `p`.`property_id` AS `property_id`, `p`.`name` AS `name`, `p`.`description` AS `description`, `p`.`type` AS `type`, `p`.`address` AS `address`, `p`.`zipcode` AS `zipcode`, `p`.`city` AS `city`, `p`.`state_province` AS `state_province`, `p`.`country` AS `country`, `p`.`year_built` AS `year_built`, `p`.`rent_on_basis` AS `rent_on_basis`, `p`.`units` AS `units`, `p`.`size_sqr_ft` AS `size_sqr_ft`, `p`.`bedrooms` AS `bedrooms`, `p`.`bathrooms` AS `bathrooms`, `p`.`parking` AS `parking`, `p`.`furnished` AS `furnished`, `p`.`floor_plan` AS `floor_plan`, `p`.`status` AS `status`, `p`.`person_id` AS `person_id`, group_concat(distinct `pi`.`image_url` order by `pi`.`image_url` ASC separator ',') AS `property_images`, group_concat(distinct `pdi`.`image_url` order by `pdi`.`image_url` ASC separator ',') AS `property_deed_images` FROM ((`property` `p` left join `property_image` `pi` on(`p`.`property_id` = `pi`.`property_id`)) left join `property_deed_image` `pdi` on(`p`.`property_id` = `pdi`.`property_id`)) GROUP BY `p`.`property_id` ;


--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `cost_per_hour` DOUBLE NOT NULL,
  `description` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `name`, `cost_per_hour`, `description`) VALUES
(1, 'Door Repairing', 1000, 'This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. '),
(2, 'Deck Repairing', 1000, 'This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. '),
(3, 'Roof Repairing', 1000, 'This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. '),
(4, 'Plumbing', 1000, 'This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing.'),
(5, 'Concrete Repairing', 1000, 'This is concrete repairing');

-- --------------------------------------------------------

-- Structure for view `property_with_images`
--
DROP TABLE IF EXISTS `property_with_images`;
CREATE OR REPLACE VIEW property_with_images AS
SELECT 
    p.property_id AS property_id, 
    p.name AS name,
    p.description AS description,
    p.type AS type,
    p.address AS address,
    p.zipcode AS zipcode,
    p.city AS city,
    p.state_province AS state_province,
    p.country AS country,
    p.year_built AS year_built,
    p.rent_on_basis AS rent_on_basis,
    p.units AS units,
    p.size_sqr_ft AS size_sqr_ft,
    p.bedrooms AS bedrooms,
    p.bathrooms AS bathrooms,
    p.parking AS parking,
    p.furnished AS furnished,
    p.floor_plan AS floor_plan,
    p.status AS status,
    p.person_id AS person_id,
    GROUP_CONCAT(DISTINCT pi.image_url ORDER BY pi.image_url ASC SEPARATOR ',') AS property_images,
    GROUP_CONCAT(DISTINCT pdi.image_url ORDER BY pdi.image_url ASC SEPARATOR ',') AS property_deed_images
FROM 
    property p
LEFT JOIN 
    property_image pi ON p.property_id = pi.property_id
LEFT JOIN 
    property_deed_image pdi ON p.property_id = pdi.property_id
GROUP BY 
    p.property_id;

-- CREATE ALGORITHM=UNDEFINED DEFINER=`primecare`@`mysql-primecare.alwaysdata.net` SQL SECURITY DEFINER VIEW `property_with_images`  AS SELECT `p`.`property_id` AS `property_id`, `p`.`name` AS `name`, `p`.`description` AS `description`, `p`.`type` AS `type`, `p`.`address` AS `address`, `p`.`zipcode` AS `zipcode`, `p`.`city` AS `city`, `p`.`state_province` AS `state_province`, `p`.`country` AS `country`, `p`.`year_built` AS `year_built`, `p`.`rent_on_basis` AS `rent_on_basis`, `p`.`units` AS `units`, `p`.`size_sqr_ft` AS `size_sqr_ft`, `p`.`bedrooms` AS `bedrooms`, `p`.`bathrooms` AS `bathrooms`, `p`.`parking` AS `parking`, `p`.`furnished` AS `furnished`, `p`.`floor_plan` AS `floor_plan`, `p`.`status` AS `status`, `p`.`person_id` AS `person_id`, group_concat(distinct `pi`.`image_url` order by `pi`.`image_url` ASC separator ',') AS `property_images`, group_concat(distinct `pdi`.`image_url` order by `pdi`.`image_url` ASC separator ',') AS `property_deed_images` FROM ((`property` `p` left join `property_image` `pi` on(`p`.`property_id` = `pi`.`property_id`)) left join `property_deed_image` `pdi` on(`p`.`property_id` = `pdi`.`property_id`)) GROUP BY `p`.`property_id` ;
-- CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `property_with_images`  AS SELECT `p`.`property_id` AS `property_id`, `p`.`name` AS `name`, `p`.`description` AS `description`, `p`.`type` AS `type`, `p`.`address` AS `address`, `p`.`zipcode` AS `zipcode`, `p`.`city` AS `city`, `p`.`state_province` AS `state_province`, `p`.`country` AS `country`, `p`.`year_built` AS `year_built`, `p`.`rent_on_basis` AS `rent_on_basis`, `p`.`units` AS `units`, `p`.`size_sqr_ft` AS `size_sqr_ft`, `p`.`bedrooms` AS `bedrooms`, `p`.`bathrooms` AS `bathrooms`, `p`.`parking` AS `parking`, `p`.`furnished` AS `furnished`, `p`.`floor_plan` AS `floor_plan`, `p`.`status` AS `status`, `p`.`person_id` AS `person_id`, group_concat(distinct `pi`.`image_url` order by `pi`.`image_url` ASC separator ',') AS `property_images`, group_concat(distinct `pdi`.`image_url` order by `pdi`.`image_url` ASC separator ',') AS `property_deed_images` FROM ((`property` `p` left join `property_image` `pi` on(`p`.`property_id` = `pi`.`property_id`)) left join `property_deed_image` `pdi` on(`p`.`property_id` = `pdi`.`property_id`)) GROUP BY `p`.`property_id` ;


--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `cost_per_hour` DOUBLE NOT NULL,
  `description` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Sample data for services table
INSERT INTO `services` (`service_id`, `name`, `cost_per_hour`, `description`,`service_img`) VALUES
(1, 'Concrete Repairing', 1000, 'This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. This is concrete repairing. ','assets/images/repairimages/concreterepairing.png'),
(2, 'Deck Repairing', 1000, 'This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. This is deck repairing. ','assets/images/repairimages/deckrepairing.png'),
(3, 'Roof Repairing', 1000, 'This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. This is roof repairing. ','assets/images/repairimages/roofrepairing.png'),
(4, 'Plumbing', 1000, 'This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing. This is plumbing.','assets/images/repairimages/plumbing.png'),
(5, 'Electric Repairing', 1000, 'This is electric repairing','assets/images/repairimages/electricrepairing.png');

-- Table structure for table `property_image_temp`
--
CREATE TABLE property_image_temp (
  `image_url` VARCHAR(255) ,
  `property_id` INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `property_deed_image_temp`
--
ALTER TABLE `property`
  ADD PRIMARY KEY (`property_id`),
  ADD KEY `person_id` (`person_id`);
CREATE TABLE property_deed_image_temp (
  `image_url` VARCHAR(255) ,
  `property_id` INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for table `property_deed_image`
--
ALTER TABLE `property_deed_image`
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `property_deed_image_temp`
--
ALTER TABLE `property_deed_image_temp`
  ADD PRIMARY KEY (`image_url`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `property_image`
--
ALTER TABLE `property_image`
  ADD PRIMARY KEY (`image_url`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `property_image_temp`
--
ALTER TABLE `property_image_temp`
  ADD PRIMARY KEY (`image_url`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `property_temp`
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
----
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT;
>>>>>>>>> Temporary merge branch 2

--
-- AUTO_INCREMENT for table `person`
--
ALTER TABLE `person`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `property`
--
ALTER TABLE `property`
  MODIFY `property_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
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
  `bank_account_no` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `marital_status` varchar(255) NOT NULL,
  `NIC_no` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `serviceLog` (`service_type`, `date`, `property_id`, `property_name`, `cost_per_hour`, `total_hours`, `status`, `service_provider_id`, `service_description`) VALUES
('Plumbing Repair', '2023-10-15', 1, 'Seaside Villa', 75.00, 3, 'Done', 61, 'Fixed leaking pipe in master bathroom'),
('Electrical Maintenance', '2023-10-16', 2, 'Mountain View Apartment', 85.00, 4, 'Ongoing', 61, 'Rewiring living room and kitchen'),
('Gardening', '2023-10-17', 3, 'Sunset Manor', 45.00, 5, 'Pending', 76, 'Monthly garden maintenance and lawn mowing'),
('Door lock Repair', '2023-10-18', 4, 'Downtown Condo', 95.00, 2, 'Done', 76, 'AC unit repair and maintenance'),
('Pool Maintenance', '2023-10-19', 5, 'Lakeside House', 65.00, 3, 'Ongoing', 61, 'Weekly pool cleaning and chemical balance check');




--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `property` (`property_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`tenant_id`) REFERENCES `person` (`pid`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_ibfk_3` FOREIGN KEY (`agent_id`) REFERENCES `person` (`pid`) ON DELETE CASCADE;

<<<<<<<<< Temporary merge branch 1
=========
--
-- Constraints for table `property`
--
ALTER TABLE `property`
  ADD CONSTRAINT `property_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`pid`) ON DELETE CASCADE;
>>>>>>>>> Temporary merge branch 2

--
-- Constraints for table `property_deed_image`
--
ALTER TABLE `property_deed_image`
  ADD CONSTRAINT `property_deed_image_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `property` (`property_id`) ON DELETE CASCADE;

--
-- Constraints for table `property_deed_image_temp`
--
ALTER TABLE `property_deed_image_temp`
  ADD CONSTRAINT `property_deed_image_temp_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `property` (`property_id`) ON DELETE CASCADE;

--
-- Constraints for table `property_image`
--
ALTER TABLE `property_image`
  ADD CONSTRAINT `property_image_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `property` (`property_id`) ON DELETE CASCADE;

--
-- Constraints for table `property_image_temp`
--
ALTER TABLE `property_image_temp`
  ADD CONSTRAINT `property_image_temp_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `property` (`property_id`) ON DELETE CASCADE;




--
-- Constraints for table `property_image_temp`
--
ALTER TABLE `property_image_temp`
  ADD CONSTRAINT `property_image_temp_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `property` (`property_id`) ON DELETE CASCADE;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

CREATE TABLE payment_details (
    name_on_card VARCHAR(30) NOT NULL, -- Cardholder's name; cannot be null.
    card_no CHAR(12) PRIMARY KEY,      -- Card number; fixed length of 12; primary key ensures uniqueness.
    bank INT(20) NOT NULL,         -- Bank name; cannot be null.
    branch INT(2) NOT NULL,       -- Branch name; cannot be null.
    pid INT NOT NULL,                  -- Foreign key reference; cannot be null.
    FOREIGN KEY (pid) REFERENCES person(pid) 
        ON DELETE CASCADE              -- Ensures related entries are removed when a person is deleted.
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

