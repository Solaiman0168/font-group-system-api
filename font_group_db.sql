-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 07, 2025 at 05:08 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `font_group_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `fonts`
--

CREATE TABLE `fonts` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `fonts`
--

INSERT INTO `fonts` (`id`, `name`, `file_path`, `created_at`) VALUES
(1, 'SHREK___', 'D:\\laragon\\www\\font-group-system-api\\controllers/../uploads/fonts/67f0d533ca69f-SHREK___.TTF', '2025-04-05 07:01:07'),
(2, 'SHREK___', 'D:\\laragon\\www\\font-group-system-api\\controllers/../uploads/fonts/67f12cf236e70-SHREK___.TTF', '2025-04-05 13:15:30'),
(3, 'Poppins-Black', 'D:\\laragon\\www\\font-group-system-api\\controllers/../uploads/fonts/67f12d0459895-Poppins-Black.ttf', '2025-04-05 13:15:48'),
(4, 'Poppins-Thin', 'D:\\laragon\\www\\font-group-system-api\\controllers/../uploads/fonts/67f12d0f67106-Poppins-Thin.ttf', '2025-04-05 13:15:59');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `title`, `created_at`, `updated_at`) VALUES
(12, 'Broken', '2025-04-06 09:42:17', '2025-04-06 10:53:37');

-- --------------------------------------------------------

--
-- Table structure for table `group_fonts`
--

CREATE TABLE `group_fonts` (
  `id` int NOT NULL,
  `group_id` int NOT NULL,
  `font_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `group_fonts`
--

INSERT INTO `group_fonts` (`id`, `group_id`, `font_id`, `created_at`) VALUES
(21, 12, 3, '2025-04-06 10:53:37'),
(22, 12, 4, '2025-04-06 10:53:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fonts`
--
ALTER TABLE `fonts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_fonts`
--
ALTER TABLE `group_fonts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `font_id` (`font_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `fonts`
--
ALTER TABLE `fonts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `group_fonts`
--
ALTER TABLE `group_fonts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `group_fonts`
--
ALTER TABLE `group_fonts`
  ADD CONSTRAINT `group_fonts_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  ADD CONSTRAINT `group_fonts_ibfk_2` FOREIGN KEY (`font_id`) REFERENCES `fonts` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
