-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 01, 2026 at 05:14 AM
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
-- Database: `student_registration_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `email`, `phone`, `address`, `profile_picture`, `created_at`) VALUES
(1, 'sedra', 'sedra@gmail.com', '0965412548', 'دمشق-سوريا', '', '2026-11-01 03:55:48'),
(2, 'yamen', 'yamen@gmail.com', '096874521352', 'اللاذقية - سوريا', '', '2026-11-01 03:56:28'),
(3, 'ahmad', 'ahmad@gmail.com', '0978546215', 'دمشق-سوريا', '6ae6b8dd1d9cd.jpeg', '2026-11-01 03:58:21'),
(4, 'lara', 'lara@gmail.com', '0985462154', 'حلب - سوريا', '6ae6b91905326.jpeg', '2026-11-01 03:59:21'),
(5, 'mohamad', 'mohamad@gmail.com', '0985648752', 'حمص - سوريا', '', '2026-11-01 03:59:59'),
(6, 'yaser', 'yaser@gmail.com', '0987546251', 'حماه - سوريا', '', '2026-11-01 04:00:31'),
(7, 'mohanad', 'mohanad@gmail.com', '0966542154', 'دمشق - سوريا', '', '2026-11-01 04:01:06'),
(8, 'yara', 'yara@gmail.com', '0965442510', 'طرطوس - سوريا', '6ae6b9aaa72fd.jpeg', '2026-11-01 04:01:46'),
(9, 'waseem', 'waseem@gmail.com', '0965453251', 'اللاذقية - سوريا', '', '2026-11-01 04:02:35'),
(10, 'waseem', 'waseem2@gmail.com', '0965485721', 'دمشق - سوريا', '6ae6ba20b6651.jpeg', '2026-11-01 04:03:44'),
(11, 'hasan', 'hasan@gmail.com', '0965485752', 'دمشق - سوريا', '6ae6ba4661442.jpeg', '2026-11-01 04:11:48');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'teacher', 'teacher@gmail.com', '$2y$10$3NdmX.g0EScPFjEt8DSXLeTu.LlyyMiY1y1wyimsiwe87W7tAboOC', '2026-11-01 03:54:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
