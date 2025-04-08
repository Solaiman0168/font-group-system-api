-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 08, 2025 at 05:19 AM
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
(3, 'Poppins-Black', '67f12d0459895-Poppins-Black.ttf', '2025-04-05 13:15:48'),
(4, 'Poppins-Thin', '67f12d0f67106-Poppins-Thin.ttf', '2025-04-05 13:15:59'),
(8, 'NotoSansGunjalaGondi-VariableFont_wght', '67f3bb1f987f0-NotoSansGunjalaGondi-VariableFont_wght.ttf', '2025-04-07 11:46:39'),
(9, 'NotoSansGunjalaGondi-VariableFont_wght', '67f3bb4515bca-NotoSansGunjalaGondi-VariableFont_wght.ttf', '2025-04-07 11:47:17'),
(26, 'NotoSansGunjalaGondi-SemiBold', '67f3e5370b923-NotoSansGunjalaGondi-SemiBold.ttf', '2025-04-07 14:46:15'),
(27, 'NotoSansGunjalaGondi-Medium', '67f3e5d979320-NotoSansGunjalaGondi-Medium.ttf', '2025-04-07 14:48:57'),
(28, 'NotoSansGunjalaGondi-Bold', '67f3e63897beb-NotoSansGunjalaGondi-Bold.ttf', '2025-04-07 14:50:32'),
(29, 'NotoSansGunjalaGondi-Regular', '67f4aa3803cf1-NotoSansGunjalaGondi-Regular.ttf', '2025-04-08 04:46:48'),
(30, 'NotoSansGunjalaGondi-SemiBold', '67f4ab3a0a3a5-NotoSansGunjalaGondi-SemiBold.ttf', '2025-04-08 04:51:06'),
(31, 'NotoSansGunjalaGondi-VariableFont_wght', '67f4ab7df0952-NotoSansGunjalaGondi-VariableFont_wght.ttf', '2025-04-08 04:52:13'),
(32, 'Roboto-VariableFont_wdth,wght', '67f4af0b14e34-Roboto-VariableFont_wdth,wght.ttf', '2025-04-08 05:07:23'),
(33, 'Roboto_Condensed-Bold', '67f4af1acaf88-Roboto_Condensed-Bold.ttf', '2025-04-08 05:07:38');

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
(30, 'Gondi-Var', '2025-04-08 03:59:17', '2025-04-08 05:10:11'),
(31, 'Gondi-V', '2025-04-08 03:59:53', '2025-04-08 05:15:19'),
(32, 'GunjalaGondi', '2025-04-08 04:37:45', '2025-04-08 04:37:45'),
(33, 'GunjalaGo', '2025-04-08 05:07:59', '2025-04-08 05:08:13'),
(34, 'SansGunjalaGon', '2025-04-08 05:15:55', '2025-04-08 05:16:01');

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
(72, 32, 8, '2025-04-08 04:37:45'),
(73, 32, 8, '2025-04-08 04:37:45'),
(76, 33, 29, '2025-04-08 05:08:13'),
(77, 30, 28, '2025-04-08 05:10:11'),
(78, 31, 28, '2025-04-08 05:15:19'),
(81, 34, 28, '2025-04-08 05:16:01');

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `group_fonts`
--
ALTER TABLE `group_fonts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

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
