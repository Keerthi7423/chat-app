-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2025 at 08:00 AM
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
-- Database: `chat_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `type` enum('text','image') DEFAULT 'text'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message`, `created_at`, `type`) VALUES
(14, 13, 14, 'hi', '2025-10-28 17:44:49', 'text'),
(15, 14, 13, 'hello', '2025-10-28 17:45:10', 'text'),
(16, 14, 13, 'hi', '2025-10-29 09:07:58', 'text'),
(17, 14, 13, 'hi', '2025-10-29 13:34:09', 'text'),
(18, 13, 14, 'hi', '2025-10-29 13:34:32', 'text'),
(19, 13, 14, 'hi', '2025-10-29 13:51:29', 'text'),
(20, 14, 13, 'hi', '2025-10-29 13:51:38', 'text'),
(21, 14, 13, 'hi', '2025-10-29 13:51:38', 'text'),
(22, 14, 13, 'keerthi', '2025-10-29 13:51:47', 'text'),
(23, 14, 13, 'keerthi', '2025-10-29 13:51:47', 'text'),
(24, 13, 14, 'hi', '2025-10-29 13:53:42', 'text'),
(25, 14, 13, 'hi', '2025-10-29 13:53:50', 'text'),
(26, 13, 14, 'hi', '2025-10-29 16:17:36', 'text'),
(27, 13, 14, 'keerthi', '2025-10-29 16:17:41', 'text'),
(28, 13, 14, 'a', '2025-10-29 16:20:35', 'text');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `bio` text DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_pic` varchar(255) DEFAULT 'default.png',
  `status` enum('online','offline') DEFAULT 'offline',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `otp` varchar(6) DEFAULT NULL,
  `otp_expires_at` datetime DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `bio`, `email`, `password`, `profile_pic`, `status`, `created_at`, `otp`, `otp_expires_at`, `is_verified`) VALUES
(13, 'Keerthi Kumar V', NULL, 'kumarkeerthi442@gmail.com', '$2y$10$F6w5CFabqBJjSfirtTTJYOnffKUzS/.ZxhnZVUEEk2Xr1kcT1Sy7e', '1761666956_Keerthi1.jpg', 'offline', '2025-10-28 15:56:48', NULL, NULL, 1),
(14, 'Kiran Kumar V', NULL, 'kumarkiran760790@gmail.com', '$2y$10$mQICJjDPwDmI/ypFtsBy4.WDm.rDikm15IlOGddIh6DFFKoJENtHC', '1761667077_photo.jpg', 'online', '2025-10-28 15:58:39', NULL, NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
