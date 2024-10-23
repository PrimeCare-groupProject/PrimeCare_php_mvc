-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 17, 2024 at 06:40 AM
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
  `image_url` varchar(255) NOT NULL DEFAULT 'https://nohat.cc/f/male-face-icon-default-profile-image/c3f2c592f9-202301261009.html',
  `user_lvl` int(2) NOT NULL DEFAULT 1,
  `pid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `person`
--

INSERT INTO `person` (`fname`, `lname`, `username`, `email`, `contact`, `password`, `image_url`, `user_lvl`, `pid`) VALUES
('user1', 'user1', 'user', 'user@gmail.com', '0762213874', '123', 'https://nohat.cc/f/male-face-icon-default-profile-image/c3f2c592f9-202301261009.html', 1, 1),
('agent1', 'agent1', 'agent', 'agent@gmail.com', '0762213874', '123', 'https://nohat.cc/f/male-face-icon-default-profile-image/c3f2c592f9-202301261009.html', 3, 50),
('manager1', 'manager1', 'manager', 'manager@gmail.com', '0762213874', '123', 'https://nohat.cc/f/male-face-icon-default-profile-image/c3f2c592f9-202301261009.html', 4, 51),
('serviceprovider1', 'serviceprovider1', 'serviceprovider', 'serviceprovider@gmail.com', '0762213874', '123', 'https://nohat.cc/f/male-face-icon-default-profile-image/c3f2c592f9-202301261009.html', 2, 52);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fname` int(11) NOT NULL,
  `lname` int(11) NOT NULL,
  `age` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
