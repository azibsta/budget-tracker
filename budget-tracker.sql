-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 12, 2025 at 11:36 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `budget-tracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `advisor_answers`
--

DROP TABLE IF EXISTS `advisor_answers`;
CREATE TABLE IF NOT EXISTS `advisor_answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `answer` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `username` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `advisor_answers`
--

INSERT INTO `advisor_answers` (`id`, `question_id`, `answer`, `created_at`, `username`) VALUES
(2, 5, 'One guideline you can use is the 50-20-30 budget: Half your income goes to essentials, 20% is saved and invested, and 30% is leftover for wants.', '2025-02-12 10:02:19', '');

-- --------------------------------------------------------

--
-- Table structure for table `advisor_questions`
--

DROP TABLE IF EXISTS `advisor_questions`;
CREATE TABLE IF NOT EXISTS `advisor_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `username` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `advisor_questions`
--

INSERT INTO `advisor_questions` (`id`, `user_id`, `question`, `created_at`, `username`) VALUES
(5, 5, 'What Should I Include in My Budget?', '2025-02-12 09:40:32', '');

-- --------------------------------------------------------

--
-- Table structure for table `budgets`
--

DROP TABLE IF EXISTS `budgets`;
CREATE TABLE IF NOT EXISTS `budgets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `limit_amount` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `budgets`
--

INSERT INTO `budgets` (`id`, `user_id`, `category`, `limit_amount`) VALUES
(3, 1, 'girlfriend', 2000.00),
(4, 4, 'onlyfans', 70000.00);

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
CREATE TABLE IF NOT EXISTS `expenses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `user_id`, `amount`, `category`, `date`, `created_at`) VALUES
(4, 1, 300.00, 'girlfriend', '2025-02-04', '2025-02-11 18:18:32'),
(5, 1, 500.00, 'girlfriend', '2025-02-04', '2025-02-11 18:18:32'),
(6, 1, 200.00, 'Mak', '2025-02-04', '2025-02-11 18:18:32');

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

DROP TABLE IF EXISTS `feedbacks`;
CREATE TABLE IF NOT EXISTS `feedbacks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedbacks`
--

INSERT INTO `feedbacks` (`id`, `user_id`, `message`, `created_at`) VALUES
(1, 1, 'hi hello hello', '2025-02-04 01:45:57'),
(2, 4, 'baik do zib\r\n', '2025-02-11 17:43:36');

-- --------------------------------------------------------

--
-- Table structure for table `goals`
--

DROP TABLE IF EXISTS `goals`;
CREATE TABLE IF NOT EXISTS `goals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `goal_date` date NOT NULL,
  `goal_description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `goals`
--

INSERT INTO `goals` (`id`, `user_id`, `goal_date`, `goal_description`, `created_at`) VALUES
(1, 5, '2025-02-28', 'i wan to save 1000 by then', '2025-02-12 09:12:49');

-- --------------------------------------------------------

--
-- Table structure for table `income`
--

DROP TABLE IF EXISTS `income`;
CREATE TABLE IF NOT EXISTS `income` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `income`
--

INSERT INTO `income` (`id`, `user_id`, `amount`, `source`, `date`, `created_at`) VALUES
(4, 1, 4000.00, 'Dad', '2025-02-03', '2025-02-11 18:18:32'),
(5, 1, 500.00, 'Mama', '2025-02-03', '2025-02-11 18:18:32'),
(6, 4, 1000000.00, 'money laundering', '2025-02-12', '2025-02-11 18:18:32'),
(7, 4, 30394642.40, 'squid game', '2025-02-11', '2025-02-11 18:18:32');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_broadcast` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `is_read`, `created_at`, `is_broadcast`) VALUES
(1, 4, '⚠️ Budget limit exceeded for \'onlyfans\'! Your limit: $70000.00, Spent: $163464.00', 1, '2025-02-11 18:38:43', 0),
(2, 4, '⚠️ Budget limit exceeded for \'onlyfans\'! Your limit: $70000.00, Spent: $238464.00', 1, '2025-02-11 18:43:29', 0),
(3, 4, '⚠️ Large expense detected in \'onlyfans\'! Amount: $75000.00 (Your limit: $500.00)', 1, '2025-02-11 18:43:29', 0);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `value`) VALUES
(1, 'theme', 'light'),
(2, 'notifications_enabled', '1'),
(3, 'feature_x_enabled', '1'),
(4, 'background_image', 'uploads/1739318155_calculator-money-notepad-keyboard-yellow-background-flat-lay.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('user','admin','advisor','it_support') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `high_expense_limit` decimal(10,2) DEFAULT 500.00,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `reset_token`, `high_expense_limit`) VALUES
(1, 'Azib', 'azibfinn2004@gmail.com', '$2y$10$C9oRClgXCyB5X/rRsnMtTuwTUAM9Od6hSvxugPUDWthgznyzL/L46', 'user', '2025-02-03 13:07:07', '3eec1b3f0c20b9241aa1d48c4dc682ae320d727c42460468856c5680b09f12b1b072fc42f70adc2814ada412445dc36cf5a7', 500.00),
(2, 'admin1', 'admin@example.com', '$2y$10$i7TH.jv/XvZ7LYCyl5DDAutAMrVIh3Equwtk219jx.1ioUB257Il.', 'admin', '2025-02-04 00:59:23', NULL, 500.00),
(4, 'Mohammad Hazman', 'hazman@gmail.com', '$2y$10$FQwZr1RqRRj1hj6jcOfqVe2i8jdT6j7R.7QZolUAqGoQOY4owl.ji', 'user', '2025-02-11 16:58:05', NULL, 500.00),
(5, 'yongkit', 'yongkit1331@gmail.com', '$2y$10$QqcvkvbmOnJ4Oek7nohqBeaw83k3FBsYskg/TJxaGJ2Rr0lfB1g/G', 'advisor', '2025-02-12 08:33:19', NULL, 500.00);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `advisor_answers`
--
ALTER TABLE `advisor_answers`
  ADD CONSTRAINT `advisor_answers_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `advisor_questions` (`id`);

--
-- Constraints for table `advisor_questions`
--
ALTER TABLE `advisor_questions`
  ADD CONSTRAINT `advisor_questions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `budgets`
--
ALTER TABLE `budgets`
  ADD CONSTRAINT `budgets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `feedbacks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `goals`
--
ALTER TABLE `goals`
  ADD CONSTRAINT `goals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `income`
--
ALTER TABLE `income`
  ADD CONSTRAINT `income_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
