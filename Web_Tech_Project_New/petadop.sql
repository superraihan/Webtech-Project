-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 19, 2026 at 08:17 PM
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
-- Database: `petadop`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Raihan', 'admin@petadopt.com', 'admin123', '2026-01-11 17:02:58'),
(2, 'Sadia', 'sadia@gmail.com', 'admin123', '2026-01-19 07:17:12'),
(3, 'Badhon', 'badhon@gmail.com', 'admin123', '2026-01-19 07:17:56'),
(4, 'esha', 'esha@gmail.com', 'esha123', '2026-01-19 08:57:52'),
(5, 'Nibir', 'nibir@gmail.com', 'admin123', '2026-01-19 18:27:40'),
(6, 'Test Admin', 'admin@test.com', 'admin123', '2026-01-19 19:00:52');

-- --------------------------------------------------------

--
-- Table structure for table `adoption`
--

CREATE TABLE `adoption` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pet_id` int(11) NOT NULL,
  `adopted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adoption`
--

INSERT INTO `adoption` (`id`, `user_id`, `pet_id`, `adopted_at`) VALUES
(1, 4, 6, '2026-01-16 09:44:51'),
(2, 1, 3, '2026-01-19 08:13:58'),
(3, 4, 5, '2026-01-19 08:13:58'),
(4, 1, 12, '2026-01-19 08:49:49'),
(5, 4, 11, '2026-01-19 09:00:31'),
(7, 6, 10, '2026-01-19 15:33:24'),
(8, 4, 17, '2026-01-19 18:32:00');

-- --------------------------------------------------------

--
-- Table structure for table `adoption_request`
--

CREATE TABLE `adoption_request` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pet_id` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `request_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adoption_request`
--

INSERT INTO `adoption_request` (`id`, `user_id`, `pet_id`, `status`, `request_at`) VALUES
(1, 1, 3, 'approved', '2026-01-15 16:02:32'),
(2, 4, 3, 'rejected', '2026-01-15 19:40:08'),
(3, 4, 5, 'approved', '2026-01-16 09:32:33'),
(4, 4, 6, 'approved', '2026-01-16 09:44:09'),
(5, 1, 12, 'approved', '2026-01-19 07:41:26'),
(6, 4, 11, 'approved', '2026-01-19 08:59:09'),
(7, 1, 11, 'rejected', '2026-01-19 08:59:35'),
(9, 6, 10, 'approved', '2026-01-19 15:31:50'),
(10, 4, 17, 'approved', '2026-01-19 18:31:24');

-- --------------------------------------------------------

--
-- Table structure for table `pets`
--

CREATE TABLE `pets` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  `age` int(11) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` enum('available','adopted') NOT NULL DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pets`
--

INSERT INTO `pets` (`id`, `name`, `type`, `age`, `description`, `image`, `status`) VALUES
(3, 'piku bain', 'Cat', 1, 'cute, friendly', 'pexels-pixabay-45201.jpg', 'adopted'),
(4, 'Buddy', 'Dog', 1, 'active, playful', 'puppy-1903313_1280.jpg', 'adopted'),
(5, 'bunny', 'Rabit', 2, 'clam, clingy', 'b825e1484a21bb183466a3890df21c39.jpg', 'adopted'),
(6, 'bunny', 'Rabbit', 2, 'clam, clingy', '', 'adopted'),
(7, 'lilo', 'cat', 3, '', 'Cute Fluffy Cat With Bow.jpg', 'available'),
(8, 'mimi', 'cat', 4, '', 'alexander-london-mJaD10XeD7w-unsplash.jpg', 'adopted'),
(9, 'jarvis', 'dog', 10, '', 'pexels-chevanon-1108099.jpg', 'available'),
(10, 'Lio', 'dog', 8, '', 'richard-brutyo-Sg3XwuEpybU-unsplash.jpg', 'adopted'),
(11, 'Rubi', 'Rabbit', 4, '', 'sandy-millar-kKAaCeGf5wY-unsplash.jpg', 'adopted'),
(12, 'Pablo', 'Rabbit', 9, '', 'frank-otoole-agvPJ3uYDro-unsplash.jpg', 'adopted'),
(13, 'RB', 'Rabbit', 1, 'cute, humble, hungry', '48cd634afbce7a5118e39f76d9a235e8.jpg', 'available'),
(14, 'lia', 'Cat', 2, '', 'lia.webp', 'available'),
(15, 'cathy', 'Cat', 3, '', 'photo-1495360010541-f48722b34f7d.avif', 'available'),
(17, 'labu', 'cat', 24, '', 'alexander-london-mJaD10XeD7w-unsplash.jpg', 'adopted');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `address`, `phone`, `created_at`) VALUES
(1, 'Sadia afrin', 'sadiaafrinsadia2017@gmail.com', 'afrin123', 'kuratuli', '09876543211', '2026-01-09 15:08:52'),
(2, 'Raihan Nibir', 'raihan@gmail.com', 'nibir123', 'kuril', '12345678909', '2026-01-09 15:29:17'),
(4, 'Oishee', 'oishe@gmail.com', 'oishe123', '3/2', '01717487865', '2026-01-12 05:15:05'),
(6, 'muntahina', 'mun@gmail.com', '123mun', 'dhaka', '123456789098', '2026-01-19 15:31:24'),
(7, 'Test User', 'user@test.com', 'password123', '123 Test St', '01711223344', '2026-01-19 19:02:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `adoption`
--
ALTER TABLE `adoption`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `pet_id` (`pet_id`);

--
-- Indexes for table `adoption_request`
--
ALTER TABLE `adoption_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `pet_id` (`pet_id`);

--
-- Indexes for table `pets`
--
ALTER TABLE `pets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `adoption`
--
ALTER TABLE `adoption`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `adoption_request`
--
ALTER TABLE `adoption_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pets`
--
ALTER TABLE `pets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `adoption`
--
ALTER TABLE `adoption`
  ADD CONSTRAINT `adoption_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `adoption_ibfk_2` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`);

--
-- Constraints for table `adoption_request`
--
ALTER TABLE `adoption_request`
  ADD CONSTRAINT `adoption_request_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `adoption_request_ibfk_2` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
