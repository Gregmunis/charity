-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 09, 2021 at 07:50 AM
-- Server version: 10.3.28-MariaDB-log-cll-lve
-- PHP Version: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `muthcwxm_charity`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` text DEFAULT NULL,
  `joined_date` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `organisation_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `is_received` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`id`, `user_id`, `organisation_id`, `item_id`, `quantity`, `is_received`, `created_at`) VALUES
(1, NULL, 6, 1, 3000, NULL, '2021-06-26 23:22:30'),
(2, 1, 6, 2, 1000, 1, '2021-06-26 23:24:13'),
(3, 1, 3, 2, 6000, 1, '2021-06-28 18:43:57');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `item_name`, `description`, `created_at`) VALUES
(1, 'Clothes', 'Clothes include beddings.', '2021-06-20 22:16:48'),
(2, 'Money', 'This include any amount of money and currency but we highly recommand Kenyan Shillings and US Dollar.', '2021-06-21 00:45:14');

-- --------------------------------------------------------

--
-- Table structure for table `organisations`
--

CREATE TABLE `organisations` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone_number` varchar(255) NOT NULL,
  `logo` text DEFAULT NULL,
  `description` text NOT NULL,
  `evidence` text NOT NULL,
  `paybill_no` int(11) NOT NULL,
  `account_no` varchar(255) NOT NULL,
  `password` text DEFAULT NULL,
  `is_active` int(11) DEFAULT NULL,
  `joined_date` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `organisations`
--

INSERT INTO `organisations` (`id`, `name`, `email`, `phone_number`, `logo`, `description`, `evidence`, `paybill_no`, `account_no`, `password`, `is_active`, `joined_date`) VALUES
(6, 'KASEREKA', 'muthaka@gmail.com', '0721964291', 'cuea.png', '', 'cuea.jpeg', 0, '', '17e89c359872f5659b5d7892ba8e52821c93eef3', 1, '2021-06-25 23:00:08'),
(7, 'Beacon of Hope', 'beacon@gmail.com', '07329742087', 'boh.png', 'Our Vision -\r\nTo be a model of excellence in wholesome community transformation globally\r\n\r\nOur Core Business\r\nWe design and implement initiatives that transform and empower people’s spiritual, physical, economic and social and well being\r\n\r\nOur Mission\r\nTo bring hope and catalyze sustainable transformation by uplifting the spiritual, physical, economic and social well-being of vulnerable individuals, families and communities.', 'boh.png', 0, '', '17e89c359872f5659b5d7892ba8e52821c93eef3', 1, '2021-07-07 21:06:02'),
(8, 'Tumaini', 'tumaini@gmail.com', '0742864229', NULL, 'This site provides archived versions of various MySQL products. We provide these as a courtesy to our users, who may need to duplicate an existing installation based on older versions of our software.\r\n\r\n', 'boh.png', 320320, 'Tumaini', '17e89c359872f5659b5d7892ba8e52821c93eef3', 1, '2021-07-07 23:17:28');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone_number` varchar(255) NOT NULL,
  `password` text DEFAULT NULL,
  `is_admin` int(1) DEFAULT NULL,
  `joined_date` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `phone_number`, `password`, `is_admin`, `joined_date`) VALUES
(1, 'Maurice Muthaka', 'muthakacom@gmail.com', '0721964297', '17e89c359872f5659b5d7892ba8e52821c93eef3', NULL, '2021-06-20 16:30:04'),
(2, 'Maurice', 'maurice@gmail.com', '0721964287', '17e89c359872f5659b5d7892ba8e52821c93eef3', 1, '2021-06-20 21:15:00');

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` int(11) NOT NULL,
  `organisation_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `wishlists`
--

INSERT INTO `wishlists` (`id`, `organisation_id`, `title`, `description`, `quantity`, `created_at`) VALUES
(3, 6, 'New Car', 'asgDDAH', '54634573', '2021-07-07 22:48:00'),
(5, 7, 'Need Medical Support', 'We need medical support for our members involved in a road accident, any support will be appreciated.', '32534', '2021-07-07 22:54:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `organisation_id` (`organisation_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organisations`
--
ALTER TABLE `organisations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organisation_id` (`organisation_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `organisations`
--
ALTER TABLE `organisations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `donations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `donations_ibfk_2` FOREIGN KEY (`organisation_id`) REFERENCES `organisations` (`id`),
  ADD CONSTRAINT `donations_ibfk_3` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
