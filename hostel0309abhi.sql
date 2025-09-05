-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 03, 2025 at 09:59 AM
-- Server version: 8.0.31
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hostel0309abhi`
--

-- --------------------------------------------------------

--
-- Table structure for table `accessory`
--

DROP TABLE IF EXISTS `accessory`;
CREATE TABLE IF NOT EXISTS `accessory` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `accessory_head_id` bigint UNSIGNED NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `accessory`
--

INSERT INTO `accessory` (`id`, `accessory_head_id`, `price`, `is_default`, `from_date`, `to_date`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, '0.00', 1, '2025-07-02', NULL, 1, 2, '2025-07-02 06:50:19', '2025-07-02 06:50:19'),
(2, 2, '0.00', 1, '2025-07-02', NULL, 1, 2, '2025-07-02 06:50:26', '2025-07-02 06:50:26'),
(3, 3, '1000.00', 0, '2025-07-02', '2025-08-13', 0, 2, '2025-07-02 06:59:18', '2025-08-13 07:30:58'),
(4, 4, '5000.00', 0, '2025-07-14', NULL, 1, 2, '2025-07-14 06:21:41', '2025-07-14 06:21:41'),
(5, 5, '50.00', 0, '2025-07-31', NULL, 1, 2, '2025-07-31 01:55:25', '2025-07-31 01:55:25'),
(6, 3, '1100.00', 0, '2025-08-13', NULL, 1, 2, '2025-08-13 07:30:58', '2025-08-13 07:30:58'),
(7, 10, '0.00', 1, '2025-08-29', NULL, 1, 54, '2025-08-29 01:41:05', '2025-08-29 01:41:05'),
(8, 27, '0.00', 1, '2025-08-29', NULL, 1, 54, '2025-08-29 01:41:47', '2025-08-29 01:41:47'),
(9, 14, '1000.00', 0, '2025-08-29', NULL, 1, 54, '2025-08-29 01:41:59', '2025-08-29 01:41:59'),
(10, 24, '3000.00', 0, '2025-08-29', NULL, 1, 54, '2025-08-29 01:42:08', '2025-08-29 01:42:08'),
(11, 26, '150.00', 0, '2025-08-29', NULL, 1, 54, '2025-08-29 01:42:14', '2025-08-29 01:42:14');

-- --------------------------------------------------------

--
-- Table structure for table `accessory_checkout_logs`
--

DROP TABLE IF EXISTS `accessory_checkout_logs`;
CREATE TABLE IF NOT EXISTS `accessory_checkout_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `checkout_id` bigint UNSIGNED NOT NULL,
  `accessory_head_id` bigint UNSIGNED NOT NULL,
  `is_returned` tinyint(1) NOT NULL,
  `debit_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `remark` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `accessory_checkout_logs_checkout_id_foreign` (`checkout_id`),
  KEY `accessory_checkout_logs_accessory_head_id_foreign` (`accessory_head_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `accessory_checkout_logs`
--

INSERT INTO `accessory_checkout_logs` (`id`, `checkout_id`, `accessory_head_id`, `is_returned`, `debit_amount`, `remark`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '0.00', NULL, '2025-07-25 00:34:30', '2025-07-25 00:34:30'),
(2, 1, 2, 0, '200.00', 'broken', '2025-07-25 00:34:30', '2025-07-25 00:34:30'),
(3, 2, 1, 1, '25.00', 'Slight damage', '2025-08-07 03:42:01', '2025-08-07 03:42:01'),
(4, 2, 2, 0, '100.00', 'Lost item', '2025-08-07 03:42:01', '2025-08-07 03:42:01'),
(5, 2, 3, 1, '0.00', 'Returned', '2025-08-07 03:42:01', '2025-08-07 03:42:01'),
(6, 1, 1, 0, '100.00', 'take 100', '2025-08-17 12:24:33', '2025-08-17 12:24:33'),
(7, 1, 2, 1, '0.00', NULL, '2025-08-17 12:24:33', '2025-08-17 12:24:33'),
(8, 4, 10, 1, '0.00', NULL, '2025-08-30 01:05:00', '2025-08-30 01:05:00'),
(9, 4, 2, 1, '0.00', NULL, '2025-08-30 01:05:00', '2025-08-30 01:05:00'),
(10, 4, 4, 1, '0.00', NULL, '2025-08-30 01:05:00', '2025-08-30 01:05:00'),
(11, 4, 5, 1, '500.00', 'Damage', '2025-08-30 01:05:00', '2025-08-30 01:05:00');

-- --------------------------------------------------------

--
-- Table structure for table `accessory_heads`
--

DROP TABLE IF EXISTS `accessory_heads`;
CREATE TABLE IF NOT EXISTS `accessory_heads` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `university_id` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `accessory_heads`
--

INSERT INTO `accessory_heads` (`id`, `name`, `university_id`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'Mugga', '1', '2025-07-02 06:50:00', '2025-07-02 06:50:00', 2, NULL),
(2, 'Bucket', '1', '2025-07-02 06:50:11', '2025-07-02 06:50:11', 2, NULL),
(3, 'Cooler', '1', '2025-07-02 06:50:36', '2025-07-02 06:50:36', 2, NULL),
(4, 'Air Conditioner', '1', '2025-07-14 06:20:40', '2025-07-14 06:20:40', 2, NULL),
(5, 'Chair', '1', '2025-07-31 01:16:13', '2025-07-31 01:23:56', 2, NULL),
(6, 'Iron', '1', '2025-08-13 07:33:12', '2025-08-13 07:33:12', 2, NULL),
(10, 'Mug', '4', '2025-08-29 00:13:41', '2025-08-29 00:13:41', 54, NULL),
(14, 'Cooler', '4', '2025-08-29 00:34:18', '2025-08-29 00:34:18', 54, NULL),
(24, 'Air Conditioner', '4', '2025-08-29 01:36:14', '2025-08-29 01:36:14', 54, NULL),
(26, 'Iron', '4', '2025-08-29 01:40:47', '2025-08-29 01:40:47', 54, NULL),
(27, 'Bucket', '4', '2025-08-29 01:41:38', '2025-08-29 01:41:38', 54, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `beds`
--

DROP TABLE IF EXISTS `beds`;
CREATE TABLE IF NOT EXISTS `beds` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `bed_number` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `room_id` bigint UNSIGNED NOT NULL,
  `status` enum('available','occupied','maintenance') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `beds_room_id_foreign` (`room_id`)
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `beds`
--

INSERT INTO `beds` (`id`, `bed_number`, `room_id`, `status`, `created_at`, `updated_at`, `created_by`) VALUES
(1, '1', 1, 'available', '2025-07-02 06:16:02', '2025-08-07 04:18:53', NULL),
(2, '2', 1, 'available', '2025-07-02 06:16:13', '2025-08-05 01:29:06', NULL),
(3, '1', 2, 'occupied', '2025-07-02 06:16:27', '2025-08-24 23:47:24', NULL),
(4, '2', 2, 'occupied', '2025-07-02 06:16:40', '2025-07-02 06:16:40', NULL),
(5, '1', 3, 'occupied', '2025-07-02 06:30:10', '2025-08-25 00:28:10', NULL),
(6, '2', 3, 'occupied', '2025-07-02 06:30:22', '2025-07-30 04:42:02', NULL),
(7, '1', 4, 'available', '2025-07-02 06:30:43', '2025-07-02 06:30:43', NULL),
(8, '2', 4, 'available', '2025-07-02 06:30:52', '2025-07-02 06:30:52', NULL),
(9, '1', 5, 'occupied', '2025-07-02 06:31:08', '2025-08-25 00:27:54', NULL),
(10, '2', 5, 'available', '2025-07-02 06:31:35', '2025-07-02 06:31:35', NULL),
(11, '1', 6, 'available', '2025-07-02 06:32:06', '2025-07-02 06:32:06', NULL),
(12, '2', 6, 'available', '2025-07-02 06:32:23', '2025-07-02 06:32:23', NULL),
(13, '1', 7, 'available', '2025-07-02 06:32:48', '2025-07-02 06:32:48', NULL),
(14, '2', 7, 'available', '2025-07-02 06:32:56', '2025-07-02 06:32:56', NULL),
(15, '1', 8, 'available', '2025-07-02 06:33:13', '2025-07-02 06:33:13', NULL),
(16, '2', 8, 'available', '2025-07-02 06:33:20', '2025-07-02 06:33:20', NULL),
(17, '1', 9, 'available', '2025-07-02 06:33:32', '2025-07-02 06:33:32', NULL),
(18, '2', 9, 'available', '2025-07-02 06:33:46', '2025-07-02 06:33:46', NULL),
(19, '1', 10, 'available', '2025-07-02 06:34:37', '2025-07-02 06:34:37', NULL),
(20, '2', 10, 'available', '2025-07-02 06:34:51', '2025-07-02 06:34:51', NULL),
(21, '1', 11, 'available', '2025-07-02 06:35:04', '2025-07-02 06:35:04', NULL),
(22, '2', 11, 'available', '2025-07-02 06:35:16', '2025-07-02 06:35:16', NULL),
(23, '1', 15, 'available', '2025-07-02 06:35:30', '2025-07-02 06:35:30', NULL),
(24, '2', 15, 'available', '2025-07-02 06:35:40', '2025-07-02 06:35:40', NULL),
(25, '1', 12, 'available', '2025-07-02 06:35:49', '2025-07-02 06:35:49', NULL),
(26, '2', 12, 'available', '2025-07-02 06:35:58', '2025-07-02 06:35:58', NULL),
(27, '1', 13, 'available', '2025-07-02 06:36:05', '2025-07-02 06:36:05', NULL),
(28, '2', 13, 'available', '2025-07-02 06:36:13', '2025-07-02 06:36:13', NULL),
(29, '1', 14, 'available', '2025-07-02 06:36:25', '2025-07-02 06:36:25', NULL),
(30, '2', 14, 'available', '2025-07-02 06:36:37', '2025-07-02 06:36:37', NULL),
(33, '1', 19, 'occupied', '2025-08-13 02:15:24', '2025-08-13 05:35:38', NULL),
(36, '1', 20, 'occupied', '2025-08-28 03:40:50', '2025-09-02 05:42:11', NULL),
(37, '2', 20, 'occupied', '2025-08-28 03:40:58', '2025-09-02 10:04:21', NULL),
(38, '3', 20, 'available', '2025-08-28 03:41:09', '2025-08-28 03:41:09', NULL),
(39, '1', 21, 'occupied', '2025-08-28 03:41:56', '2025-09-02 09:56:57', NULL),
(40, '2', 21, 'available', '2025-08-28 03:42:03', '2025-08-28 03:42:03', NULL),
(41, '1', 22, 'available', '2025-08-28 03:42:15', '2025-08-28 03:42:15', NULL),
(42, '2', 22, 'available', '2025-08-28 03:42:25', '2025-08-28 03:42:25', NULL),
(43, '3', 22, 'available', '2025-08-28 03:42:58', '2025-08-28 03:42:58', NULL),
(44, '1', 23, 'available', '2025-08-28 03:43:10', '2025-08-28 03:43:10', NULL),
(45, '2', 23, 'available', '2025-08-28 03:43:16', '2025-08-28 03:43:16', NULL),
(46, '3', 23, 'available', '2025-08-28 03:43:27', '2025-08-28 03:43:27', NULL),
(47, '1', 24, 'available', '2025-08-28 03:43:34', '2025-08-28 03:43:34', NULL),
(48, '2', 24, 'available', '2025-08-28 03:43:51', '2025-08-28 03:43:51', NULL),
(49, '3', 24, 'available', '2025-08-28 03:43:55', '2025-08-28 03:43:55', NULL),
(50, '1', 25, 'available', '2025-08-28 03:44:05', '2025-08-29 07:26:34', NULL),
(51, '2', 25, 'available', '2025-08-28 03:44:08', '2025-08-28 03:44:08', NULL),
(52, '3', 25, 'available', '2025-08-28 03:44:11', '2025-08-28 03:44:11', NULL),
(53, '1', 26, 'available', '2025-08-28 03:44:20', '2025-08-30 01:06:40', NULL),
(54, '2', 26, 'available', '2025-08-28 03:44:23', '2025-08-28 03:44:23', NULL),
(55, '3', 26, 'available', '2025-08-28 03:44:25', '2025-08-28 03:44:25', NULL),
(56, '1', 27, 'available', '2025-08-28 03:44:34', '2025-08-28 03:44:34', NULL),
(57, '2', 27, 'available', '2025-08-28 03:44:36', '2025-08-28 03:44:36', NULL),
(58, '3', 27, 'available', '2025-08-28 03:44:43', '2025-08-28 03:44:43', NULL),
(59, '1', 28, 'available', '2025-08-28 03:45:00', '2025-08-28 03:45:00', NULL),
(60, '2', 28, 'available', '2025-08-28 03:45:03', '2025-08-28 03:45:03', NULL),
(61, '3', 28, 'available', '2025-08-28 03:45:06', '2025-08-28 03:45:06', NULL),
(62, '1', 29, 'available', '2025-08-28 03:45:13', '2025-08-28 03:45:13', NULL),
(63, '2', 29, 'available', '2025-08-28 03:45:16', '2025-08-28 03:45:16', NULL),
(64, '3', 29, 'available', '2025-08-28 03:45:19', '2025-08-28 03:45:19', NULL),
(65, '1', 31, 'available', '2025-08-28 03:45:33', '2025-08-28 03:45:33', NULL),
(66, '2', 31, 'available', '2025-08-28 03:45:37', '2025-08-28 03:45:37', NULL),
(67, '3', 31, 'available', '2025-08-28 03:45:40', '2025-08-28 03:45:40', NULL),
(68, '1', 32, 'available', '2025-08-28 03:45:46', '2025-08-28 03:45:46', NULL),
(69, '2', 32, 'available', '2025-08-28 03:45:48', '2025-08-28 03:45:48', NULL),
(70, '3', 32, 'available', '2025-08-28 03:45:52', '2025-08-28 03:45:52', NULL),
(71, '1', 33, 'available', '2025-08-28 03:46:04', '2025-08-28 03:46:04', NULL),
(72, '2', 33, 'available', '2025-08-28 03:46:07', '2025-08-28 03:46:07', NULL),
(73, '3', 33, 'available', '2025-08-28 03:46:10', '2025-08-28 03:46:10', NULL),
(74, '1', 34, 'available', '2025-08-28 03:46:15', '2025-08-28 03:46:15', NULL),
(75, '2', 34, 'available', '2025-08-28 03:46:18', '2025-08-28 03:46:18', NULL),
(76, '3', 34, 'available', '2025-08-28 03:46:21', '2025-08-28 03:46:21', NULL),
(77, '1', 35, 'available', '2025-08-28 03:46:34', '2025-08-28 03:46:34', NULL),
(78, '2', 35, 'available', '2025-08-28 03:46:38', '2025-08-28 03:46:38', NULL),
(79, '3', 35, 'available', '2025-08-28 03:46:40', '2025-08-29 07:10:46', NULL),
(80, '1', 36, 'available', '2025-08-28 03:46:49', '2025-08-28 03:46:49', NULL),
(81, '2', 36, 'available', '2025-08-28 03:46:53', '2025-08-28 03:46:53', NULL),
(82, '3', 36, 'available', '2025-08-28 03:46:57', '2025-08-28 03:46:57', NULL),
(83, '1', 37, 'available', '2025-08-28 03:47:05', '2025-08-28 03:47:05', NULL),
(84, '2', 37, 'available', '2025-08-28 03:47:08', '2025-08-28 03:47:08', NULL),
(85, '3', 37, 'available', '2025-08-28 03:47:11', '2025-08-28 03:47:11', NULL),
(86, '1', 38, 'available', '2025-08-28 03:47:22', '2025-08-28 03:47:22', NULL),
(87, '2', 38, 'available', '2025-08-28 03:47:25', '2025-08-28 03:47:25', NULL),
(88, '3', 38, 'available', '2025-08-28 03:47:27', '2025-08-29 06:56:33', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `buildings`
--

DROP TABLE IF EXISTS `buildings`;
CREATE TABLE IF NOT EXISTS `buildings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `building_code` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `university_id` bigint UNSIGNED NOT NULL,
  `status` enum('active','inactive') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'active',
  `floors` int UNSIGNED NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `buildings_building_code_unique` (`building_code`),
  KEY `buildings_university_id_foreign` (`university_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `buildings`
--

INSERT INTO `buildings` (`id`, `name`, `building_code`, `university_id`, `status`, `floors`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Sarojini Naydu', '1', 1, 'active', 3, 2, '2025-07-02 06:12:19', '2025-07-02 06:12:19'),
(2, 'Block B', 'B001', 1, 'active', 5, 2, '2025-07-25 00:01:22', '2025-07-25 00:01:22'),
(4, 'Sunshine', 'S121', 1, 'active', 5, 2, '2025-07-28 06:04:39', '2025-07-28 06:04:39'),
(5, 'Tilak Block', 'T121', 1, 'active', 6, 2, '2025-07-28 06:38:40', '2025-07-29 01:52:45'),
(6, 'Tagore Bhavan', 'T101', 1, 'active', 4, 2, '2025-08-12 05:06:47', '2025-08-12 05:59:56'),
(7, 'CV Raman Block', 'CV01', 4, 'active', 5, 54, '2025-08-28 01:59:06', '2025-08-28 01:59:06'),
(8, 'Sarojini Naydu', 'SN01', 4, 'active', 3, 54, '2025-08-28 01:59:34', '2025-08-28 01:59:34');

-- --------------------------------------------------------

--
-- Table structure for table `checkouts`
--

DROP TABLE IF EXISTS `checkouts`;
CREATE TABLE IF NOT EXISTS `checkouts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `resident_id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `reason` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `admin_approval` enum('pending','approved','denied') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'pending',
  `account_approval` enum('pending','approved','denied') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'pending',
  `remark` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `action` enum('completed','rejected','admin_checked','pending') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'pending',
  `deposited_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `checkouts_resident_id_foreign` (`resident_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `checkouts`
--

INSERT INTO `checkouts` (`id`, `resident_id`, `date`, `reason`, `admin_approval`, `account_approval`, `remark`, `action`, `deposited_amount`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, '2025-07-26', 'abc', 'pending', 'approved', NULL, 'admin_checked', '9700.00', NULL, '2025-07-25 00:34:02', '2025-08-25 02:10:40'),
(2, 4, '2025-08-15', 'Test', 'approved', 'approved', 'All okay', 'completed', '9875.00', NULL, '2025-08-06 04:16:33', '2025-08-07 04:18:53'),
(3, 2, '2025-08-20', 'abc', 'pending', 'pending', NULL, 'pending', '10000.00', NULL, '2025-08-19 00:24:52', '2025-08-19 00:24:52'),
(4, 11, '2025-09-01', 'test', 'approved', 'approved', NULL, 'completed', '500.00', NULL, '2025-08-29 14:18:34', '2025-08-30 01:06:40');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
CREATE TABLE IF NOT EXISTS `courses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `department_id` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` varchar(5) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `name`, `department_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'BCA', '5', '1', '2025-08-20 15:57:28', '2025-08-20 15:57:28'),
(4, 'MCA', '5', '1', '2025-08-20 17:51:08', '2025-08-20 17:51:08'),
(5, 'Bachelors in Mechanical Engineering', '15', '1', '2025-08-28 12:13:01', '2025-08-28 12:13:01'),
(6, 'Bachelors in Electronics & Electrical Engineering', '16', '1', '2025-08-28 12:13:35', '2025-08-28 12:13:35'),
(7, 'BE in Computer Science', '17', '1', '2025-08-28 12:14:16', '2025-08-28 12:14:16'),
(8, 'BE in Information Technology', '17', '1', '2025-08-28 12:14:44', '2025-08-28 12:14:44'),
(9, 'BE in Electronics & Communication', '18', '1', '2025-08-28 12:15:13', '2025-08-28 12:15:13'),
(10, 'BE in Civil Engineering', '19', '1', '2025-08-28 12:15:32', '2025-08-28 12:15:32'),
(11, 'BE in Electrical Engineering', '20', '1', '2025-08-28 12:15:54', '2025-08-28 12:15:54'),
(12, 'MA in Education', '21', '1', '2025-08-28 12:16:13', '2025-08-28 12:16:13'),
(13, 'Bachelor in Physical Education', '21', '1', '2025-08-28 12:16:38', '2025-08-28 12:16:38'),
(14, 'Bachelor in Computer Application', '23', '1', '2025-08-28 12:17:20', '2025-08-28 12:17:20'),
(15, 'Master in Computer Application', '23', '1', '2025-08-28 12:17:37', '2025-08-28 12:17:37'),
(16, 'Bachelor in Commerce', '24', '1', '2025-08-28 12:17:55', '2025-08-28 12:17:55'),
(17, 'Master in Commerce', '24', '1', '2025-08-28 12:18:16', '2025-08-28 12:18:16'),
(18, 'BBA', '24', '1', '2025-08-28 12:18:31', '2025-08-28 12:18:31'),
(19, 'MBA', '24', '1', '2025-08-28 12:18:42', '2025-08-28 12:18:42'),
(20, 'Bachelor of Science in Physics', '25', '1', '2025-08-28 12:19:10', '2025-08-28 12:19:10'),
(21, 'M.Sc. in Physics', '25', '1', '2025-08-28 12:19:25', '2025-08-28 12:19:25'),
(22, 'B.Sc.(Chemistry)', '26', '1', '2025-08-28 12:20:12', '2025-08-28 12:20:12'),
(23, 'M.Sc(Chemistry)', '26', '1', '2025-08-28 12:20:29', '2025-08-28 12:20:29'),
(24, 'B.Sc.(Mathematics)', '27', '1', '2025-08-28 12:21:01', '2025-08-28 12:21:01'),
(25, 'M.Sc.(Mathematics)', '27', '1', '2025-08-28 12:21:24', '2025-08-28 12:21:24'),
(26, 'B.Sc.(Botony)', '28', '1', '2025-08-28 12:22:20', '2025-08-28 12:22:20'),
(27, 'B.Sc.(Zoology)', '28', '1', '2025-08-28 12:22:47', '2025-08-28 12:22:47');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
CREATE TABLE IF NOT EXISTS `departments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `faculty_id` varchar(5) NOT NULL,
  `status` varchar(5) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `faculty_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Department of Computer Science & Engineering', '5', '1', '2025-08-20 09:32:30', '2025-08-20 09:32:30'),
(2, 'Department of Electronics & Communication Engineering', '5', '1', '2025-08-20 09:36:26', '2025-08-20 09:36:26'),
(3, 'Department of Civil Engineering', '5', '1', '2025-08-20 09:36:49', '2025-08-20 09:36:49'),
(4, 'Department of Mechanical Engineering', '5', '1', '2025-08-20 09:37:13', '2025-08-20 09:37:13'),
(5, 'Department of CS & IT', '5', '1', '2025-08-20 09:38:33', '2025-08-20 17:43:19'),
(6, 'Department of Electrical & Electronics Engineering', '5', '1', '2025-08-20 09:39:20', '2025-08-20 09:39:20'),
(7, 'Department of Commerce', '3', '1', '2025-08-20 09:41:26', '2025-08-20 09:41:26'),
(8, 'Department of Management', '7', '1', '2025-08-20 09:41:39', '2025-08-20 09:41:39'),
(9, 'Department of Law', '6', '1', '2025-08-20 09:41:54', '2025-08-20 09:41:54'),
(10, 'Department of Physical Science', '9', '1', '2025-08-20 09:42:27', '2025-08-20 09:42:27'),
(11, 'Department of Life Science', '9', '1', '2025-08-20 09:42:50', '2025-08-20 09:42:50'),
(12, 'Department of Education', '4', '1', '2025-08-20 09:46:44', '2025-08-20 09:46:44'),
(14, 'Department of Physical Education', '4', '1', '2025-08-20 17:47:28', '2025-08-20 17:47:28'),
(15, 'Mechanical Engineering', '14', '1', '2025-08-28 11:56:33', '2025-08-28 11:56:33'),
(16, 'Electronics & Electrical Engineering', '14', '1', '2025-08-28 11:56:58', '2025-08-28 11:56:58'),
(17, 'CS & IT', '14', '1', '2025-08-28 11:57:16', '2025-08-28 11:57:16'),
(18, 'Electronics & Communication', '14', '1', '2025-08-28 11:57:42', '2025-08-28 11:57:42'),
(19, 'Civil Engineering', '14', '1', '2025-08-28 12:05:59', '2025-08-28 12:05:59'),
(20, 'Electrical Engineering', '14', '1', '2025-08-28 12:06:35', '2025-08-28 12:06:35'),
(21, 'Education', '15', '1', '2025-08-28 12:06:59', '2025-08-28 12:06:59'),
(22, 'Physical Education', '15', '1', '2025-08-28 12:07:13', '2025-08-28 12:07:13'),
(23, 'Department IT', '16', '1', '2025-08-28 12:07:50', '2025-08-28 12:07:50'),
(24, 'Coomerce & Management', '17', '1', '2025-08-28 12:08:32', '2025-08-28 12:08:32'),
(25, 'Physics', '18', '1', '2025-08-28 12:09:25', '2025-08-28 12:09:25'),
(26, 'Chemistry', '18', '1', '2025-08-28 12:09:46', '2025-08-28 12:09:46'),
(27, 'Mathematics', '18', '1', '2025-08-28 12:09:59', '2025-08-28 12:09:59'),
(28, 'Life Sciences', '18', '1', '2025-08-28 12:10:30', '2025-08-28 12:10:30'),
(29, 'Rural Technology', '18', '1', '2025-08-28 12:10:48', '2025-08-28 12:10:48');

-- --------------------------------------------------------

--
-- Table structure for table `faculties`
--

DROP TABLE IF EXISTS `faculties`;
CREATE TABLE IF NOT EXISTS `faculties` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `status` varchar(5) NOT NULL,
  `university_id` varchar(5) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `faculties`
--

INSERT INTO `faculties` (`id`, `name`, `status`, `university_id`, `created_at`, `updated_at`) VALUES
(1, 'Agriculture', '1', '1', '2025-08-20 06:50:58', '2025-08-20 07:11:02'),
(2, 'Humanities & Liberal Arts', '1', '1', '2025-08-20 07:07:48', '2025-08-20 07:07:48'),
(3, 'Commerce', '1', '1', '2025-08-20 07:08:01', '2025-08-20 07:08:01'),
(4, 'Education', '1', '1', '2025-08-20 07:08:16', '2025-08-20 07:08:16'),
(5, 'Engineering & Technology', '1', '1', '2025-08-20 07:08:31', '2025-08-20 07:08:31'),
(6, 'Law', '1', '1', '2025-08-20 07:08:40', '2025-08-20 07:08:40'),
(7, 'Management', '1', '1', '2025-08-20 07:08:54', '2025-08-20 07:08:54'),
(8, 'Medical Science', '1', '1', '2025-08-20 07:09:07', '2025-08-20 07:09:07'),
(9, 'Science', '1', '1', '2025-08-20 07:10:19', '2025-08-20 07:10:19'),
(10, 'B.Voc / M.Voc', '1', '1', '2025-08-20 07:10:30', '2025-08-20 07:10:30'),
(13, 'Tagore National School of Drama', '1', '1', '2025-08-20 07:26:44', '2025-08-20 07:26:44'),
(14, 'Engineering & Technology', '1', '4', '2025-08-28 10:13:23', '2025-08-28 10:13:23'),
(15, 'Education & Physical Education', '1', '4', '2025-08-28 10:28:14', '2025-08-28 10:28:14'),
(16, 'Information & Technology', '1', '4', '2025-08-28 10:28:37', '2025-08-28 10:28:37'),
(17, 'Commerce & Management', '1', '4', '2025-08-28 10:29:11', '2025-08-28 10:29:11'),
(18, 'Science', '1', '4', '2025-08-28 10:29:21', '2025-08-28 10:29:21'),
(19, 'Arts', '1', '4', '2025-08-28 10:29:29', '2025-08-28 10:29:29'),
(20, 'Law', '1', '4', '2025-08-28 10:29:36', '2025-08-28 10:29:36'),
(21, 'Pharmaceutical Science', '1', '4', '2025-08-28 10:29:59', '2025-08-28 10:29:59');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

DROP TABLE IF EXISTS `feedbacks`;
CREATE TABLE IF NOT EXISTS `feedbacks` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `resident_id` bigint UNSIGNED NOT NULL,
  `facility_name` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `feedback` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `suggestion` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `photo_path` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `feedbacks_resident_id_foreign` (`resident_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `feedbacks`
--

INSERT INTO `feedbacks` (`id`, `resident_id`, `facility_name`, `feedback`, `suggestion`, `photo_path`, `created_at`, `updated_at`, `created_by`) VALUES
(1, 2, 'RO Facility', 'testing', 'testing', NULL, '2025-08-18 12:38:33', '2025-08-18 12:38:33', NULL),
(2, 11, 'Everithing', 'All to facilities and amenities are good', 'Security guards should be polite to students.', NULL, '2025-08-29 12:56:37', '2025-08-29 12:56:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `fees`
--

DROP TABLE IF EXISTS `fees`;
CREATE TABLE IF NOT EXISTS `fees` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `fee_head_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fees_fee_head_id_foreign` (`fee_head_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `fees`
--

INSERT INTO `fees` (`id`, `name`, `fee_head_id`, `amount`, `from_date`, `to_date`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Hostel Fee', 1, '6000.00', '2025-07-25', '2025-07-25', 0, 3, '2025-07-25 00:06:52', '2025-07-25 00:09:05'),
(2, 'Mess Fee', 2, '3000.00', '2025-07-25', NULL, 1, 3, '2025-07-25 00:07:41', '2025-07-25 00:07:41'),
(3, 'Caution Money', 4, '10000.00', '2025-07-25', NULL, 1, 3, '2025-07-25 00:08:16', '2025-07-25 00:08:16'),
(4, 'Other', 3, '0.00', '2025-07-25', '2025-08-25', 0, 3, '2025-07-25 00:08:28', '2025-08-25 10:09:30'),
(5, 'Hostel Fee', 1, '3000.00', '2025-07-25', NULL, 1, 3, '2025-07-25 00:09:05', '2025-07-25 00:09:05'),
(6, 'Test', 5, '100.00', '2025-08-25', '2025-08-25', 0, NULL, '2025-08-25 01:56:48', '2025-08-25 01:57:00'),
(7, 'Test', 5, '100.00', '2025-08-25', '2025-08-25', 0, NULL, '2025-08-25 01:57:01', '2025-08-25 01:57:07'),
(8, 'Test', 5, '101.00', '2025-08-25', NULL, 1, NULL, '2025-08-25 01:57:07', '2025-08-25 01:57:07'),
(9, 'Other', 3, '1100.00', '2025-08-25', NULL, 1, NULL, '2025-08-25 10:09:30', '2025-08-25 10:09:30');

-- --------------------------------------------------------

--
-- Table structure for table `fee_exceptions`
--

DROP TABLE IF EXISTS `fee_exceptions`;
CREATE TABLE IF NOT EXISTS `fee_exceptions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `guest_id` bigint UNSIGNED NOT NULL,
  `hostel_fee` decimal(10,2) DEFAULT NULL,
  `caution_money` decimal(10,2) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `facility` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `approved_by` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `remarks` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `document_path` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `account_remark` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fee_exceptions_guest_id_foreign` (`guest_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `fee_exceptions`
--

INSERT INTO `fee_exceptions` (`id`, `guest_id`, `hostel_fee`, `caution_money`, `total_amount`, `facility`, `approved_by`, `remarks`, `document_path`, `created_by`, `created_at`, `updated_at`, `account_remark`, `start_date`, `end_date`) VALUES
(1, 2, '1000.00', '0.00', '1000.00', NULL, 'Registrar of RNTU', NULL, 'storage/fee_documents/1756046961_ChatGPT Image Aug 11, 2025, 12_28_11 PM.png', 2, '2025-07-02 07:12:42', '2025-08-24 09:19:21', NULL, '2025-07-15', '2025-07-30'),
(2, 10, '5000.00', '1000.00', '6000.00', NULL, NULL, NULL, NULL, 2, '2025-08-24 23:44:21', '2025-08-24 23:44:21', NULL, '2025-09-01', '2025-09-30'),
(3, 11, '2000.00', '1000.00', '3000.00', 'test', 'Registrar of CVRU CG', 'test', 'storage/fee_documents/1756402691_ChatGPT Image Aug 26, 2025, 09_30_23 PM.png', 54, '2025-08-28 12:08:11', '2025-08-28 12:08:11', NULL, '2025-09-01', '2025-09-30'),
(4, 12, '1200.00', '250.00', '1450.00', NULL, NULL, NULL, NULL, 54, '2025-09-02 05:39:08', '2025-09-02 05:39:08', NULL, '2025-09-05', '2025-11-30');

-- --------------------------------------------------------

--
-- Table structure for table `fee_heads`
--

DROP TABLE IF EXISTS `fee_heads`;
CREATE TABLE IF NOT EXISTS `fee_heads` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `fee_heads`
--

INSERT INTO `fee_heads` (`id`, `name`, `created_at`, `updated_at`, `created_by`) VALUES
(1, 'Hostel Fee', '2025-07-25 00:06:00', '2025-07-25 00:06:00', 3),
(2, 'Mess Fee', '2025-07-25 00:06:12', '2025-07-25 00:06:12', 3),
(3, 'Other', '2025-07-25 00:06:27', '2025-07-25 00:10:46', 3),
(4, 'Caution Money', '2025-07-25 00:08:06', '2025-07-25 00:08:06', 3),
(5, 'Test 2', '2025-08-25 01:55:59', '2025-08-25 10:09:59', 53);

-- --------------------------------------------------------

--
-- Table structure for table `grievances`
--

DROP TABLE IF EXISTS `grievances`;
CREATE TABLE IF NOT EXISTS `grievances` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `resident_id` bigint UNSIGNED NOT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `responded_by` bigint UNSIGNED DEFAULT NULL,
  `type_of_complaint` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` enum('open','agreed_by_resident','closed') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'open',
  `token_id` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `photo` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `grievances_token_id_unique` (`token_id`),
  KEY `grievances_resident_id_foreign` (`resident_id`),
  KEY `grievances_created_by_foreign` (`created_by`),
  KEY `grievances_responded_by_foreign` (`responded_by`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `grievances`
--

INSERT INTO `grievances` (`id`, `resident_id`, `created_by`, `responded_by`, `type_of_complaint`, `description`, `status`, `token_id`, `photo`, `created_at`, `updated_at`) VALUES
(1, 2, 10, NULL, 'Maintenance', 'Water is not available.', 'open', 'grievance-785669', NULL, '2025-08-18 03:14:16', '2025-08-18 03:14:16'),
(2, 2, 10, NULL, 'Behavior', 'Testing', 'open', 'grievance-230252', NULL, '2025-08-18 04:01:22', '2025-08-18 04:01:22'),
(3, 2, 10, NULL, 'Cleanliness', 'please clean floor in my room', 'open', 'grievance-838730', NULL, '2025-08-19 02:04:28', '2025-08-19 02:04:28'),
(4, 11, 60, NULL, 'Cleanliness', 'We are facing cleanliness issue, no one came here for cleaning.', 'closed', 'grievance-386357', NULL, '2025-08-29 10:13:11', '2025-08-29 12:21:04');

-- --------------------------------------------------------

--
-- Table structure for table `grievance_responses`
--

DROP TABLE IF EXISTS `grievance_responses`;
CREATE TABLE IF NOT EXISTS `grievance_responses` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `grievance_id` bigint UNSIGNED NOT NULL,
  `responded_by` bigint UNSIGNED NOT NULL,
  `description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `grievance_responses_grievance_id_foreign` (`grievance_id`),
  KEY `grievance_responses_responded_by_foreign` (`responded_by`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `grievance_responses`
--

INSERT INTO `grievance_responses` (`id`, `grievance_id`, `responded_by`, `description`, `created_at`, `updated_at`, `created_by`) VALUES
(1, 1, 2, 'hi', '2025-08-18 03:49:55', '2025-08-18 03:49:55', NULL),
(2, 1, 2, 'hey', '2025-08-18 03:53:09', '2025-08-18 03:53:09', NULL),
(3, 1, 2, 'hi', '2025-08-18 03:55:16', '2025-08-18 03:55:16', NULL),
(4, 1, 10, 'hi', '2025-08-18 04:00:55', '2025-08-18 04:00:55', NULL),
(5, 2, 10, 'hi', '2025-08-18 04:01:35', '2025-08-18 04:01:35', NULL),
(6, 2, 10, 'hey', '2025-08-18 04:07:14', '2025-08-18 04:07:14', NULL),
(7, 1, 10, 'hia', '2025-08-18 04:21:52', '2025-08-18 04:21:52', NULL),
(8, 1, 10, 'hello', '2025-08-18 04:44:19', '2025-08-18 04:44:19', NULL),
(9, 1, 10, 'hi', '2025-08-19 02:03:58', '2025-08-19 02:03:58', NULL),
(10, 4, 54, 'We are sending someone.', '2025-08-29 12:19:06', '2025-08-29 12:19:06', NULL),
(11, 4, 60, 'Please send', '2025-08-29 12:20:56', '2025-08-29 12:20:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `guests`
--

DROP TABLE IF EXISTS `guests`;
CREATE TABLE IF NOT EXISTS `guests` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `token` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `token_expiry` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `faculty_id` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `department_id` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `course_id` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `gender` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `scholar_no` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `fathers_name` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `mothers_name` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `months` tinyint UNSIGNED NOT NULL DEFAULT '3' COMMENT 'Duration of stay in months',
  `days` tinyint UNSIGNED DEFAULT NULL,
  `local_guardian_name` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `attachment_path` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `remarks` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `admin_remarks` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `fee_waiver` tinyint(1) NOT NULL DEFAULT '0',
  `bihar_credit_card` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `tnsd` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `emergency_no` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `number` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `parent_no` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `guardian_no` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `room_preference` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `food_preference` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `is_verified` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `status` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'pending',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `guests_email_unique` (`email`),
  UNIQUE KEY `guests_scholar_no_unique` (`scholar_no`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `guests`
--

INSERT INTO `guests` (`id`, `name`, `email`, `token`, `token_expiry`, `faculty_id`, `department_id`, `course_id`, `gender`, `scholar_no`, `fathers_name`, `mothers_name`, `months`, `days`, `local_guardian_name`, `attachment_path`, `remarks`, `admin_remarks`, `fee_waiver`, `bihar_credit_card`, `tnsd`, `emergency_no`, `number`, `parent_no`, `guardian_no`, `room_preference`, `food_preference`, `is_verified`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Shiv', 'shiv@gmail.com', '2y12ddqTd0TbgSbpcrWjIdCVLuYBRvV1h9XDyAsxZwK7lrbzEvxbltwy', '1755942789', '5', '5', '4', 'Male', '1001', 'Abhishek Verma', 'Khushi Verma', 3, 15, 'abhi', NULL, 'xyz', 'abc', 1, NULL, NULL, '9039137768', '9632514245', '9632154246', '9225421524', 'Single', 'Veg', '1', 'pending', 2, '2025-07-02 07:06:48', '2025-08-24 08:51:55'),
(2, 'Rudra Verma', 'rudra@gmail.com', '2y121ppCSa6CjUOx2DaJGc7J4bGAj2sRoynNNLOISjK0TzEPRkmLgK', '1756286949', '5', '5', '1', 'Male', '1002', 'Abhishek Verma', 'Khushi Verma', 1, 15, 'Abhi', NULL, 'Approved by Registrar of RNTU', NULL, 1, NULL, NULL, '9170021375', NULL, NULL, NULL, 'Single', 'Veg', '1', 'pending', 40, '2025-07-02 07:08:05', '2025-08-26 03:59:09'),
(3, 'Abhishek Verma', 'verma.abhishek111@gmail.com', '2y12lJhyCaUlqUyJ83Dtjf6eXBIXKq2tBuAj1REuedPDRdQZxrntaYq', '1755686544', NULL, NULL, NULL, 'Male', '1003', 'Vijay Verma', 'Shanti Devi', 3, NULL, 'Test', NULL, NULL, NULL, 0, NULL, NULL, '9039137760', NULL, NULL, NULL, 'Double', 'Non-Veg', NULL, 'approved', NULL, '2025-07-14 06:13:44', '2025-08-19 05:12:24'),
(4, 'A1', 'a1@gmail.com', NULL, NULL, NULL, NULL, NULL, 'Male', '1111', 'B1', 'C1', 1, NULL, 'D1', NULL, NULL, NULL, 0, NULL, NULL, '5151515151', NULL, NULL, NULL, 'Single', 'Veg', NULL, 'approved', NULL, '2025-07-24 05:07:41', '2025-08-23 05:54:42'),
(5, 'A2', 'a2@gmail.com', NULL, NULL, NULL, NULL, NULL, 'Male', '11111', 'B2', 'C2', 3, NULL, 'D2', NULL, 'Test', NULL, 0, NULL, NULL, '1515151515', '1414141414', NULL, NULL, 'Single', 'Veg', NULL, 'approved', NULL, '2025-07-24 05:14:57', '2025-07-25 01:00:59'),
(6, 'C1', 'c1@gmail.com', NULL, NULL, '5', '5', '1', 'Male', '11111111', 'D', 'E', 3, NULL, 'F', NULL, NULL, NULL, 0, NULL, NULL, '1231231231', '9632514245', '9632514245', '9632514245', 'Single', 'Veg', '1', 'paid', 40, '2025-07-24 05:41:04', '2025-08-26 03:54:55'),
(7, 'Sonu', 'sonu@gmail.com', NULL, NULL, NULL, NULL, NULL, 'Male', 'AU210341', 'Sonu Kumar', 'Sunita Devi', 3, NULL, 'Uncle', NULL, NULL, NULL, 0, NULL, NULL, '1234567890', '1234567890', '1234567890', '1234567890', 'Double', 'Veg', NULL, 'paid', NULL, '2025-07-25 00:03:33', '2025-07-25 00:09:28'),
(8, 'Aditya Singh Sengar', 'adiyasinghsengar@gmail.com', '2y12eScoNge6YAnqDJ1u7wt2nsnYXM8vdpZGY7echUVxpk2OUucvZMw2', '1755942865', NULL, NULL, NULL, 'Male', 'SCH16638999', 'Mr. Sengar', 'Mrs. Sengar', 3, NULL, 'Uncle Sengar', NULL, NULL, NULL, 0, NULL, NULL, '9632124242', '9630251542', '9630251542', '9562425254', 'Single bed', 'Veg', NULL, 'pending', NULL, '2025-08-02 03:51:43', '2025-08-22 04:24:25'),
(9, 'T1', 't1@test.com', '2y12JZ9HhgTQ6qRoNzgCIDQxOxCEFWP2jPDjFZfhk5Xr3Hc04VI9oqi', '1756114105', '5', '5', '4', 'Female', 'M23545', 'T2', 'T3', 3, NULL, 'T4', 'guest_attachments/h3QUr1ESJYyliul7vJn9a03t4Y2k7afQvY0o34If.png', 'Test data kk', NULL, 1, NULL, NULL, '9352421211', '1523456222', '9632154243', '9825124244', 'Double', 'Veg', '1', 'pending', 2, '2025-08-24 03:57:04', '2025-08-24 08:26:08'),
(10, 'Anu Kumari', 'anukumari@test.com', '2y127RrqWCjIgcIZiYJGcvQzoOmuZuNyI169Zw4JytNuorpNsA4wpwi5m', '1756185350', '5', '5', '4', 'Female', '12345678', 'Ankur Raj', 'Anita Singh', 1, NULL, 'Ankur Raj', 'attachments/jB8Ao2eSG7tSsK3epixlBbExSIlVM3qW7r8rVZsi.jpg', 'Testing', NULL, 1, NULL, NULL, '9305212421', '9305212421', '9305212421', '9305212421', 'Single', 'Veg', '1', 'paid', 2, '2025-08-24 23:36:14', '2025-08-24 23:46:18'),
(11, 'Shivangi Shukla', 'shivangi@gmail.com', '2y12bv6aEytJtNWHkxEiPaCeLFuzMSQBxgoYHOwEpxYRs9scDNDK2Sy', '1756489164', '14', '17', '7', 'Female', '1524215', 'Mohan Shukla', 'Kanchan Shukla', 1, NULL, 'Golu Shukla', NULL, 'Testing', NULL, 1, NULL, NULL, '`', '9632151212', '9632151212', '9632151212', 'Single', 'Non-Veg', '1', 'paid', 55, '2025-08-28 07:05:46', '2025-08-28 12:12:22'),
(12, 'Ankush Narang', 'ankushnarang@gmail.com', '2y12pVOuqDr7emGIL6f6mJyKuqLXZNKAduRvIWoYVrHc04IptLCwMEW', '1756897804', '18', '28', '26', 'Male', 'CV25259', 'Ankur Narang', 'Ankita Narang', 2, 25, 'Chhagan', 'attachments/yqCd74QDkAf9FDRXNfUEKcRlGYinMJsFRwgXI2Rq.jpg', 'Testing', NULL, 1, NULL, NULL, '9625254212', '9625254212', '9625254212', '9625254212', 'Single', NULL, '1', 'paid', 55, '2025-08-28 11:41:28', '2025-09-02 05:40:36'),
(19, 'Shyam Benegal', 'shyamben@gmail.com', '2y121Zg3EZqzuYyYMZe7dfvruX63cYeh6jJ5LLFoEWCKgeFXbY2Iq', '1756881850', '17', '24', '16', 'Male', 'C125421', 'Sundar Benegal', 'Meera Benegal', 3, NULL, 'Tinku', NULL, 'Testing for Bihar Credit Card', NULL, 0, NULL, NULL, '9632154242', '9632154242', '9632154242', '9632154242', 'Double', NULL, NULL, 'pending', NULL, '2025-09-01 12:48:05', '2025-09-02 01:14:10'),
(20, 'Tanmay Gupta', 'tanmaygupta@gmail.com', '2y12lOgpROYn6N3qiWt5kcfFtOkeVdpsTEsAmXGa2atCGjzdNs37WdeZm', '1756883622', '17', '24', '19', 'Male', 'CV52102', 'Sudhir Gupta', 'Rani Gupta', 3, NULL, 'N/A', NULL, 'Testing For Bihar Credit Card', NULL, 1, NULL, NULL, '9632154211', '9632154211', '9632154211', '9632154211', 'Single', NULL, NULL, 'pending', NULL, '2025-09-02 01:31:20', '2025-09-02 01:43:42'),
(24, 'Sunil Singh', 'sunilsingh@gmail.com', '2y12rvXkl71pgyGHnSVeMDCuHPsmQ0Yu78TBFmKaMvvb3wUTrNcr1fe', '1756884224', '18', '26', '22', 'Male', 'CV1201', 'Raj Singh', 'Sunita Singh', 3, NULL, 'abc', NULL, 'Testing Bihar Credit Card', NULL, 0, NULL, NULL, '3625421212', '3625421212', '3625421212', '3625421212', 'Single', NULL, NULL, 'pending', NULL, '2025-09-02 01:53:44', '2025-09-02 01:53:44'),
(25, 'Akansha Chauhan', 'akanshach@gmail.com', '2y12AB2Wg8etGZxiBqExjcOy4w3KioPcdwMzStsJVkcwmFqDvyYBS', '1756884432', '18', '25', '20', 'Female', 'CV5252', 'Rakesh Chauhan', 'Jiya Chauhan', 3, NULL, 'Tinku', NULL, 'Testing BCC', NULL, 0, NULL, NULL, '9632521242', '9632521242', '9632521242', '9632521242', 'Single', NULL, NULL, 'pending', NULL, '2025-09-02 01:57:12', '2025-09-02 01:57:12'),
(26, 'Pihu Tiwari', 'pihutiwari@gmail.com', '2y1262V0ZpIZnblcukyEBYKufiBR2MuOMkSkVs2BFrsQqmx5WNCIPdO', '1756884659', '15', '21', '12', 'Female', 'CV252521', 'Rinku Tiwari', 'Jamala Devi', 3, NULL, 'Testing', NULL, 'Testing BCC', NULL, 0, NULL, NULL, '9656545215', '9656545215', '9656545215', '9656545215', 'Single', NULL, NULL, 'pending', NULL, '2025-09-02 02:00:59', '2025-09-02 02:00:59'),
(27, 'Sona Shukla', 'sonashukla@gmail.com', '2y12v0nXaCXzyEq3SuwEdI9Ke4DECE249S64HgBUsbddwkW5N20ZNRwW', '1756961017', '16', '23', '15', 'Female', 'C8525', 'Raj Lakhan Shukla', 'Nirmala Shukla', 3, NULL, 'Testing', NULL, 'Testing BCC', NULL, 0, '1', '0', '9636235652', '9636235652', '9636235652', '9636235652', 'Single', NULL, '1', 'approved', 55, '2025-09-02 02:05:49', '2025-09-02 23:13:37'),
(28, 'Jiya Raj', 'jiyaraj@gmail.com', '2y12CB5l9sgZR9wfB6N6uYuc7uK1LOD8nV4rl5g8hTaFepOsGCYk8kPe', '1756913461', '17', '24', '16', 'Female', 'CV25250', 'Sundar Raj', 'Sharmila Raj', 3, NULL, 'Testing', NULL, 'BCC Testing', NULL, 0, '1', '0', '8548758458', '8548758458', '8548758458', '8548758458', 'Single', NULL, '1', 'paid', 55, '2025-09-02 02:07:34', '2025-09-02 10:01:25'),
(29, 'Test', 'test@gmail.com', '2y125aOGNdVHrV1xWTwb2WakTeXnXgHHLK2mJzvienBSyh9kN9iDowFl2', '1756975849', '17', '24', '17', 'Male', '2023', 'Father', 'Mother', 3, NULL, 'Guardien', NULL, NULL, NULL, 0, '0', '0', '9874563210', '9874563210', '9874563210', '9874563210', 'Single', NULL, '1', 'pending', 55, '2025-09-03 01:15:09', '2025-09-03 03:21:17'),
(30, 'Test2', 'test2@gmail.com', '2y12ZKVO9zZwFSAGbxqIrzBf2ehzKbFFMuAQvcQVYNYIlsc4ace7hVdq', '1756970093', '17', '24', '18', 'Female', '012345', 'father', 'Mother', 3, NULL, 'Guardien', NULL, NULL, NULL, 0, '0', '0', '9874563210', '9874563210', '9874563210', '9874563210', 'Single', NULL, '1', 'pending', 55, '2025-09-03 01:44:53', '2025-09-03 03:15:44');

-- --------------------------------------------------------

--
-- Table structure for table `guest_accessory`
--

DROP TABLE IF EXISTS `guest_accessory`;
CREATE TABLE IF NOT EXISTS `guest_accessory` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `guest_id` bigint UNSIGNED NOT NULL,
  `accessory_head_id` bigint UNSIGNED NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=161 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `guest_accessory`
--

INSERT INTO `guest_accessory` (`id`, `guest_id`, `accessory_head_id`, `price`, `total_amount`, `from_date`, `to_date`, `created_at`, `updated_at`, `created_by`) VALUES
(1, 1, 1, '0.00', '0.00', '2025-08-24', '2025-11-24', '2025-07-02 07:06:48', '2025-08-24 08:51:55', NULL),
(2, 1, 2, '0.00', '0.00', '2025-08-24', '2025-11-24', '2025-07-02 07:06:48', '2025-08-24 08:51:55', NULL),
(3, 2, 1, '0.00', '0.00', '2025-08-26', '2025-09-26', '2025-07-02 07:08:05', '2025-08-26 03:46:44', NULL),
(4, 2, 2, '0.00', '0.00', '2025-08-26', '2025-09-26', '2025-07-02 07:08:05', '2025-08-26 03:46:44', NULL),
(5, 2, 3, '1100.00', '1100.00', '2025-08-26', '2025-09-26', '2025-07-02 07:08:05', '2025-08-26 03:46:44', NULL),
(6, 3, 1, '0.00', '0.00', '2025-07-14', '2025-10-14', '2025-07-14 06:13:44', '2025-07-14 06:13:44', NULL),
(7, 3, 2, '0.00', '0.00', '2025-07-14', '2025-10-14', '2025-07-14 06:13:44', '2025-07-14 06:13:44', NULL),
(8, 3, 3, '1000.00', '3000.00', '2025-07-14', '2025-10-14', '2025-07-14 06:13:44', '2025-07-14 06:13:44', NULL),
(9, 4, 1, '0.00', '0.00', '2025-07-24', '2025-08-24', '2025-07-24 05:07:41', '2025-07-24 05:07:41', NULL),
(10, 4, 2, '0.00', '0.00', '2025-07-24', '2025-08-24', '2025-07-24 05:07:41', '2025-07-24 05:07:41', NULL),
(11, 4, 3, '1000.00', '1000.00', '2025-07-24', '2025-08-24', '2025-07-24 05:07:41', '2025-07-24 05:07:41', NULL),
(12, 5, 1, '0.00', '0.00', '2025-07-24', '2025-10-24', '2025-07-24 05:14:57', '2025-07-24 05:14:57', NULL),
(13, 5, 2, '0.00', '0.00', '2025-07-24', '2025-10-24', '2025-07-24 05:14:57', '2025-07-24 05:14:57', NULL),
(14, 6, 1, '0.00', '0.00', '2025-08-26', '2025-11-26', '2025-07-24 05:41:04', '2025-08-26 03:54:55', NULL),
(15, 6, 2, '0.00', '0.00', '2025-08-26', '2025-11-26', '2025-07-24 05:41:04', '2025-08-26 03:54:55', NULL),
(16, 7, 1, '0.00', '0.00', '2025-07-25', '2025-10-25', '2025-07-25 00:03:33', '2025-07-25 00:03:33', NULL),
(17, 7, 2, '0.00', '0.00', '2025-07-25', '2025-10-25', '2025-07-25 00:03:33', '2025-07-25 00:03:33', NULL),
(18, 7, 3, '1000.00', '3000.00', '2025-07-25', '2025-10-25', '2025-07-25 00:03:33', '2025-07-25 00:03:33', NULL),
(19, 7, 4, '5000.00', '15000.00', '2025-07-25', '2025-10-25', '2025-07-25 00:03:33', '2025-07-25 00:03:33', NULL),
(20, 1, 3, '1100.00', '3300.00', '2025-08-24', '2025-11-24', '2025-08-24 06:05:16', '2025-08-24 08:51:56', NULL),
(21, 9, 1, '0.00', '0.00', '2025-08-24', '2025-11-24', '2025-08-24 03:57:04', '2025-08-24 08:38:29', NULL),
(22, 9, 2, '0.00', '0.00', '2025-08-24', '2025-11-24', '2025-08-24 03:57:04', '2025-08-24 08:38:29', NULL),
(25, 9, 3, '1100.00', '3300.00', '2025-08-24', '2025-11-24', '2025-08-24 03:57:04', '2025-08-24 08:38:29', NULL),
(26, 9, 4, '5000.00', '15000.00', '2025-08-24', '2025-11-24', '2025-08-24 08:36:05', '2025-08-24 08:38:29', NULL),
(27, 9, 5, '50.00', '150.00', '2025-08-24', '2025-11-24', '2025-08-24 08:38:29', '2025-08-24 08:38:29', NULL),
(28, 1, 5, '50.00', '150.00', '2025-08-24', '2025-11-24', '2025-08-24 08:50:39', '2025-08-24 08:51:55', NULL),
(29, 10, 1, '0.00', '0.00', '2025-08-25', '2025-09-25', '2025-08-24 23:36:14', '2025-08-24 23:44:56', NULL),
(30, 10, 2, '0.00', '0.00', '2025-08-25', '2025-09-25', '2025-08-24 23:36:14', '2025-08-24 23:44:56', NULL),
(31, 10, 4, '5000.00', '5000.00', '2025-08-25', '2025-09-25', '2025-08-24 23:36:14', '2025-08-24 23:44:56', NULL),
(32, 10, 5, '50.00', '50.00', '2025-08-25', '2025-09-25', '2025-08-24 23:36:14', '2025-08-24 23:44:56', NULL),
(33, 6, 4, '5000.00', '15000.00', '2025-08-26', '2025-11-26', '2025-08-26 03:54:55', '2025-08-26 03:54:55', NULL),
(34, 11, 1, '0.00', '0.00', '2025-08-28', '2025-09-28', '2025-08-28 07:05:46', '2025-08-28 11:56:13', NULL),
(35, 11, 2, '0.00', '0.00', '2025-08-28', '2025-09-28', '2025-08-28 07:05:46', '2025-08-28 11:56:13', NULL),
(36, 11, 4, '5000.00', '5000.00', '2025-08-28', '2025-09-28', '2025-08-28 07:05:46', '2025-08-28 11:56:13', NULL),
(37, 11, 5, '50.00', '50.00', '2025-08-28', '2025-09-28', '2025-08-28 07:05:46', '2025-08-28 11:56:13', NULL),
(74, 19, 1, '0.00', '0.00', '2025-09-01', '2025-12-01', '2025-09-01 12:48:05', '2025-09-01 12:48:05', NULL),
(75, 19, 2, '0.00', '0.00', '2025-09-01', '2025-12-01', '2025-09-01 12:48:05', '2025-09-01 12:48:05', NULL),
(76, 19, 10, '0.00', '0.00', '2025-09-01', '2025-12-01', '2025-09-01 12:48:05', '2025-09-01 12:48:05', NULL),
(77, 19, 27, '0.00', '0.00', '2025-09-01', '2025-12-01', '2025-09-01 12:48:05', '2025-09-01 12:48:05', NULL),
(78, 19, 14, '1000.00', '3000.00', '2025-09-01', '2025-12-01', '2025-09-01 12:48:05', '2025-09-01 12:48:05', NULL),
(79, 20, 10, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:31:20', '2025-09-02 01:31:20', NULL),
(80, 20, 27, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:31:20', '2025-09-02 01:31:20', NULL),
(81, 20, 14, '1000.00', '3000.00', '2025-09-02', '2025-12-02', '2025-09-02 01:31:20', '2025-09-02 01:31:20', NULL),
(82, 20, 26, '150.00', '450.00', '2025-09-02', '2025-12-02', '2025-09-02 01:31:20', '2025-09-02 01:31:20', NULL),
(83, 24, 1, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:53:44', '2025-09-02 01:53:44', NULL),
(84, 24, 2, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:53:44', '2025-09-02 01:53:44', NULL),
(85, 24, 10, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:53:44', '2025-09-02 01:53:44', NULL),
(86, 24, 27, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:53:44', '2025-09-02 01:53:44', NULL),
(87, 24, 14, '1000.00', '3000.00', '2025-09-02', '2025-12-02', '2025-09-02 01:53:44', '2025-09-02 01:53:44', NULL),
(88, 24, 26, '150.00', '450.00', '2025-09-02', '2025-12-02', '2025-09-02 01:53:44', '2025-09-02 01:53:44', NULL),
(89, 25, 1, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(90, 25, 2, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(91, 25, 1, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(92, 25, 2, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(93, 25, 1, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(94, 25, 2, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(95, 25, 1, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(96, 25, 2, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(97, 25, 1, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(98, 25, 2, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(99, 25, 1, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(100, 25, 2, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(101, 25, 1, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(102, 25, 2, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(103, 25, 1, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(104, 25, 2, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(105, 25, 1, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(106, 25, 2, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(107, 25, 1, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(108, 25, 2, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(109, 25, 1, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(110, 25, 2, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(111, 25, 10, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(112, 25, 27, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(113, 25, 10, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(114, 25, 27, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(115, 25, 10, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(116, 25, 27, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(117, 25, 10, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(118, 25, 27, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(119, 25, 10, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(120, 25, 27, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(121, 25, 10, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(122, 25, 27, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(123, 25, 10, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(124, 25, 27, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(125, 25, 14, '1000.00', '3000.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(126, 25, 26, '150.00', '450.00', '2025-09-02', '2025-12-02', '2025-09-02 01:57:12', '2025-09-02 01:57:12', NULL),
(127, 26, 10, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 02:00:59', '2025-09-02 02:00:59', NULL),
(128, 26, 27, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 02:00:59', '2025-09-02 02:00:59', NULL),
(129, 26, 14, '1000.00', '3000.00', '2025-09-02', '2025-12-02', '2025-09-02 02:00:59', '2025-09-02 02:00:59', NULL),
(130, 26, 26, '150.00', '450.00', '2025-09-02', '2025-12-02', '2025-09-02 02:00:59', '2025-09-02 02:00:59', NULL),
(139, 27, 10, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 02:05:49', '2025-09-02 11:42:32', NULL),
(140, 27, 27, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 02:05:49', '2025-09-02 11:42:32', NULL),
(141, 27, 14, '1000.00', '3000.00', '2025-09-02', '2025-12-02', '2025-09-02 02:05:49', '2025-09-02 11:42:32', NULL),
(142, 27, 26, '150.00', '450.00', '2025-09-02', '2025-12-02', '2025-09-02 02:05:49', '2025-09-02 11:42:32', NULL),
(145, 28, 10, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 02:07:34', '2025-09-02 07:23:31', NULL),
(146, 28, 27, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 02:07:34', '2025-09-02 07:23:31', NULL),
(147, 28, 14, '1000.00', '3000.00', '2025-09-02', '2025-12-02', '2025-09-02 02:07:34', '2025-09-02 07:23:31', NULL),
(148, 28, 26, '150.00', '450.00', '2025-09-02', '2025-12-02', '2025-09-02 02:07:34', '2025-09-02 07:23:31', NULL),
(152, 12, 10, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 04:02:11', '2025-09-02 04:02:11', NULL),
(153, 12, 27, '0.00', '0.00', '2025-09-02', '2025-12-02', '2025-09-02 04:02:11', '2025-09-02 04:02:11', NULL),
(154, 12, 26, '150.00', '450.00', '2025-09-02', '2025-12-02', '2025-09-02 04:02:11', '2025-09-02 04:02:11', NULL),
(155, 29, 10, '0.00', '0.00', '2025-09-03', '2025-12-03', '2025-09-03 01:15:09', '2025-09-03 01:57:26', NULL),
(156, 29, 27, '0.00', '0.00', '2025-09-03', '2025-12-03', '2025-09-03 01:15:09', '2025-09-03 01:57:26', NULL),
(157, 29, 14, '1000.00', '3000.00', '2025-09-03', '2025-12-03', '2025-09-03 01:15:09', '2025-09-03 01:57:26', NULL),
(158, 30, 10, '0.00', '0.00', '2025-09-03', '2025-12-03', '2025-09-03 01:44:53', '2025-09-03 01:48:53', NULL),
(159, 30, 27, '0.00', '0.00', '2025-09-03', '2025-12-03', '2025-09-03 01:44:53', '2025-09-03 01:48:53', NULL),
(160, 30, 14, '1000.00', '3000.00', '2025-09-03', '2025-12-03', '2025-09-03 01:44:53', '2025-09-03 01:48:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hods`
--

DROP TABLE IF EXISTS `hods`;
CREATE TABLE IF NOT EXISTS `hods` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) DEFAULT NULL,
  `department_id` varchar(10) NOT NULL,
  `status` varchar(10) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_notifications`
--

DROP TABLE IF EXISTS `leave_notifications`;
CREATE TABLE IF NOT EXISTS `leave_notifications` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `leave_request_id` bigint UNSIGNED NOT NULL,
  `resident_id` bigint UNSIGNED NOT NULL,
  `notification_type` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `sms_gateway_message_id` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `sent_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `leave_notifications_leave_request_id_notification_type_unique` (`leave_request_id`,`notification_type`),
  KEY `leave_notifications_resident_id_foreign` (`resident_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `leave_notifications`
--

INSERT INTO `leave_notifications` (`id`, `leave_request_id`, `resident_id`, `notification_type`, `sms_gateway_message_id`, `sent_at`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'hod_approved_leave_request', 'LVE_MSG_68831d2f1fafd', '2025-07-25 05:59:11', 0, '2025-07-25 00:29:11', '2025-07-25 00:29:11'),
(4, 1, 1, 'admin_approved_leave_request', 'LVE_MSG_68831d3280fb7', '2025-07-25 05:59:14', 0, '2025-07-25 00:29:14', '2025-07-25 00:29:14'),
(7, 2, 1, 'hod_approved_leave_request', 'LVE_MSG_68832b3486132', '2025-07-25 06:59:00', 0, '2025-07-25 01:29:00', '2025-07-25 01:29:00'),
(10, 2, 1, 'admin_approved_leave_request', 'LVE_MSG_68832b39aea5f', '2025-07-25 06:59:05', 0, '2025-07-25 01:29:05', '2025-07-25 01:29:05'),
(13, 5, 11, 'hod_approved_leave_request', 'LVE_MSG_68b15b17beee0', '2025-08-29 07:47:35', 0, '2025-08-29 02:17:35', '2025-08-29 02:17:35'),
(16, 5, 11, 'admin_approved_leave_request', 'LVE_MSG_68b17ba878817', '2025-08-29 10:06:32', 0, '2025-08-29 04:36:32', '2025-08-29 04:36:32');

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

DROP TABLE IF EXISTS `leave_requests`;
CREATE TABLE IF NOT EXISTS `leave_requests` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `resident_id` bigint UNSIGNED NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `reason` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `photo` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `hod_status` enum('pending','approved','denied') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'pending',
  `admin_status` enum('pending','approved','denied') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `leave_requests_resident_id_foreign` (`resident_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`id`, `resident_id`, `from_date`, `to_date`, `reason`, `photo`, `hod_status`, `admin_status`, `created_at`, `updated_at`, `created_by`) VALUES
(1, 1, '2025-07-26', '2025-07-29', 'abc', NULL, 'approved', 'approved', '2025-07-25 00:25:10', '2025-07-25 00:29:14', NULL),
(2, 1, '2025-07-26', '2025-07-30', 'abcd', NULL, 'approved', 'approved', '2025-07-25 00:28:04', '2025-07-25 01:29:05', NULL),
(3, 2, '2025-08-18', '2025-08-20', 'Test', NULL, 'pending', 'pending', '2025-08-18 01:17:23', '2025-08-18 01:17:23', NULL),
(4, 2, '2025-08-18', '2025-08-20', 'Test', NULL, 'pending', 'pending', '2025-08-18 01:18:58', '2025-08-18 01:18:58', NULL),
(5, 11, '2025-08-29', '2025-08-30', 'One Day Leave', NULL, 'approved', 'approved', '2025-08-29 02:03:17', '2025-08-29 04:36:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `messes`
--

DROP TABLE IF EXISTS `messes`;
CREATE TABLE IF NOT EXISTS `messes` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `resident_id` bigint UNSIGNED DEFAULT NULL,
  `guest_id` bigint UNSIGNED DEFAULT NULL,
  `building_id` bigint UNSIGNED DEFAULT NULL,
  `university_id` bigint UNSIGNED DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `food_preference` enum('veg','non_veg') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `messes_user_id_foreign` (`user_id`),
  KEY `messes_resident_id_foreign` (`resident_id`),
  KEY `messes_guest_id_foreign` (`guest_id`),
  KEY `messes_building_id_foreign` (`building_id`),
  KEY `messes_university_id_foreign` (`university_id`),
  KEY `messes_created_by_foreign` (`created_by`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `messes`
--

INSERT INTO `messes` (`id`, `user_id`, `resident_id`, `guest_id`, `building_id`, `university_id`, `created_by`, `food_preference`, `from_date`, `to_date`, `due_date`, `created_at`, `updated_at`) VALUES
(1, 9, 1, 7, NULL, NULL, 9, 'veg', '2025-07-25', '2025-10-25', '2025-10-25', '2025-07-25 00:09:28', '2025-07-25 00:09:28'),
(2, 10, 2, 6, NULL, NULL, 10, 'veg', '2025-07-25', '2025-10-25', '2025-10-25', '2025-07-25 01:01:49', '2025-07-25 01:01:49'),
(3, 18, 4, 8, NULL, NULL, 18, 'veg', '2025-08-02', '2025-11-02', '2025-11-02', '2025-08-02 06:13:40', '2025-08-02 06:13:40'),
(4, 20, 5, 1, NULL, NULL, 20, 'veg', '2025-08-19', '2025-11-19', '2025-11-19', '2025-08-19 07:21:10', '2025-08-19 07:21:10'),
(5, 50, 6, 2, NULL, NULL, 50, 'veg', '2025-08-24', '2025-08-24', '2025-08-24', '2025-08-24 09:21:04', '2025-08-24 09:21:04'),
(6, 51, 7, 10, NULL, NULL, 51, 'veg', '2025-08-25', '2025-09-25', '2025-09-25', '2025-08-24 23:46:18', '2025-08-24 23:46:18'),
(7, 60, 11, 11, NULL, NULL, 60, 'veg', '2025-08-28', '2025-09-28', '2025-09-28', '2025-08-28 12:12:22', '2025-08-28 12:12:22'),
(8, 65, 12, 12, NULL, NULL, 65, 'veg', '2025-09-02', '2025-11-02', '2025-11-02', '2025-09-02 05:40:36', '2025-09-02 05:40:36'),
(9, 66, 13, 28, NULL, NULL, 66, 'veg', '2025-09-02', '2025-12-02', '2025-12-02', '2025-09-02 10:01:25', '2025-09-02 10:01:25'),
(10, 67, 14, 30, NULL, NULL, 67, 'veg', '2025-09-03', '2025-12-03', '2025-12-03', '2025-09-03 03:15:44', '2025-09-03 03:15:44'),
(11, 68, 15, 29, NULL, NULL, 68, 'veg', '2025-09-03', '2025-12-03', '2025-12-03', '2025-09-03 03:21:17', '2025-09-03 03:21:17');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(2, '2019_08_19_000000_create_failed_jobs_table', 1),
(3, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(4, '2025_03_11_105823_create_permission_tables', 1),
(5, '2025_03_12_054526_create_universities_table', 1),
(6, '2025_03_12_054527_create_buildings_table', 1),
(7, '2025_03_12_054527_create_rooms_table', 1),
(8, '2025_03_12_054527_create_users_table', 1),
(9, '2025_03_12_054528_create_beds_table', 1),
(10, '2025_03_13_085116_create_accessory_heads_table', 1),
(11, '2025_03_13_085117_create_accessory_table', 1),
(12, '2025_03_15_100000_create_guests_table', 1),
(13, '2025_03_16_105121_create_residents_table', 1),
(14, '2025_03_17_102821_create_leave_requests_table', 1),
(15, '2025_03_17_105122_create_student_accessory_table', 1),
(16, '2025_03_19_094302_create_feedbacks_table', 1),
(17, '2025_03_20_065709_create_notices_table', 1),
(18, '2025_03_20_101557_create_room_change_requests_table', 1),
(19, '2025_03_24_051349_create_grievances_table', 1),
(20, '2025_03_24_100015_create_fee_heads_table', 1),
(21, '2025_03_24_100016_create_fees_table', 1),
(22, '2025_03_28_054500_create_checkouts_table', 1),
(23, '2025_03_29_073421_create_subscriptions_table', 1),
(24, '2025_04_03_085110_create_guest_accessory_table', 1),
(25, '2025_04_17_100742_create_messes_table', 1),
(26, '2025_04_28_064816_create_grievance_responses_table', 1),
(27, '2025_04_28_090049_create_room_change_messages_table', 1),
(28, '2025_05_15_054721_create_accessory_checkout_logs_table', 1),
(29, '2025_06_01_073422_create_payments_table', 1),
(30, '2025_06_05_041253_create_fee_exceptions_table', 2),
(31, '2025_06_08_055124_create_notifications_table', 2),
(32, '2025_06_11_051341_create_jobs_table', 2),
(33, '2025_06_12_050229_create_payment_notifications_table', 2),
(34, '2025_06_12_063740_create_leave_notifications_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(4, 'App\\Models\\User', 3),
(4, 'App\\Models\\User', 4),
(5, 'App\\Models\\User', 5),
(6, 'App\\Models\\User', 6),
(7, 'App\\Models\\User', 7),
(8, 'App\\Models\\User', 8),
(3, 'App\\Models\\User', 9),
(3, 'App\\Models\\User', 10),
(2, 'App\\Models\\User', 11),
(5, 'App\\Models\\User', 12),
(3, 'App\\Models\\User', 13),
(3, 'App\\Models\\User', 14),
(3, 'App\\Models\\User', 15),
(3, 'App\\Models\\User', 16),
(3, 'App\\Models\\User', 17),
(3, 'App\\Models\\User', 18),
(5, 'App\\Models\\User', 19),
(3, 'App\\Models\\User', 20),
(8, 'App\\Models\\User', 21),
(8, 'App\\Models\\User', 38),
(8, 'App\\Models\\User', 39),
(10, 'App\\Models\\User', 40),
(2, 'App\\Models\\User', 41),
(2, 'App\\Models\\User', 42),
(2, 'App\\Models\\User', 43),
(2, 'App\\Models\\User', 44),
(2, 'App\\Models\\User', 45),
(2, 'App\\Models\\User', 46),
(2, 'App\\Models\\User', 47),
(2, 'App\\Models\\User', 48),
(1, 'App\\Models\\User', 49),
(3, 'App\\Models\\User', 50),
(3, 'App\\Models\\User', 51),
(2, 'App\\Models\\User', 52),
(9, 'App\\Models\\User', 53),
(2, 'App\\Models\\User', 54),
(10, 'App\\Models\\User', 55),
(9, 'App\\Models\\User', 56),
(3, 'App\\Models\\User', 60),
(4, 'App\\Models\\User', 61),
(5, 'App\\Models\\User', 62),
(8, 'App\\Models\\User', 63),
(8, 'App\\Models\\User', 64),
(3, 'App\\Models\\User', 65),
(3, 'App\\Models\\User', 66),
(3, 'App\\Models\\User', 67),
(3, 'App\\Models\\User', 68);

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

DROP TABLE IF EXISTS `notices`;
CREATE TABLE IF NOT EXISTS `notices` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `message_from` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `university_id` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `notices`
--

INSERT INTO `notices` (`id`, `message_from`, `message`, `from_date`, `to_date`, `university_id`, `created_at`, `updated_at`, `created_by`) VALUES
(1, 'Hostem Management', 'Submit your hostel before due data. Thank You.', '2025-07-01', '2025-07-31', '1', '2025-07-02 06:49:25', '2025-07-02 06:49:25', NULL),
(2, 'Test', 'Test', '2025-08-16', '2025-08-25', '1', '2025-08-17 12:08:42', '2025-08-17 12:08:42', NULL),
(4, 'PhD Entrance Registration', 'Testing', '2025-09-01', '2025-09-30', '4', '2025-08-29 13:15:46', '2025-08-29 13:15:46', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` char(36) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `type` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `notifiable_type` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `reference_id` char(36) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `guest_id` bigint UNSIGNED DEFAULT NULL,
  `order_id` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_id` decimal(8,2) DEFAULT NULL,
  `status` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'pending',
  `message` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_method` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `purpose` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `origin_url` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `redirect_url` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `callback_route` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_reference_id_unique` (`reference_id`),
  UNIQUE KEY `orders_order_id_unique` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `reference_id`, `user_id`, `guest_id`, `order_id`, `amount`, `payment_id`, `status`, `message`, `payment_method`, `purpose`, `origin_url`, `redirect_url`, `callback_route`, `metadata`, `created_at`, `updated_at`) VALUES
(1, '20dbd1a8-e28a-4d39-840b-09316589677d', NULL, 29, 'G-ORD250903MOX1', '31000.00', NULL, 'pending', 'pending', NULL, 'guest_payment', 'http://127.0.0.1:8000/api/payments/initiate', 'http://127.0.0.1:8000/paytm/callback', NULL, '{\"remarks\": \"Advance payment for hostel\", \"guest_id\": \"29\", \"accessory_head_ids\": [\"24\"]}', '2025-09-03 03:41:22', '2025-09-03 03:41:22'),
(2, '5032b860-305f-4205-9c8a-1615febbf699', NULL, 29, 'G-ORD250903NGA2', '31000.00', NULL, 'TXN_SUCCESS', 'Txn Success', 'UPI', 'guest_payment', 'http://127.0.0.1:8000/api/payments/initiate', 'http://127.0.0.1:8000/paytm/callback', NULL, '{\"remarks\": \"Advance payment for hostel\", \"guest_id\": \"29\", \"accessory_head_ids\": [\"24\"]}', '2025-09-03 03:45:17', '2025-09-03 03:45:25');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `token` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `guest_id` bigint UNSIGNED DEFAULT NULL,
  `resident_id` bigint UNSIGNED DEFAULT NULL,
  `fee_head_id` bigint UNSIGNED DEFAULT NULL,
  `subscription_id` bigint UNSIGNED DEFAULT NULL,
  `student_accessory_id` bigint UNSIGNED DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `amount` decimal(10,2) NOT NULL,
  `remaining_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `transaction_id` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `payment_method` enum('Cash','UPI','Bank Transfer','Card','Other','Null') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'Cash',
  `payment_status` enum('Pending','Completed') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'Pending',
  `is_caution_money` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `remarks` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_transaction_id_unique` (`transaction_id`),
  KEY `payments_guest_id_foreign` (`guest_id`),
  KEY `payments_resident_id_foreign` (`resident_id`),
  KEY `payments_fee_head_id_foreign` (`fee_head_id`),
  KEY `payments_subscription_id_foreign` (`subscription_id`),
  KEY `payments_student_accessory_id_foreign` (`student_accessory_id`),
  KEY `payments_created_by_foreign` (`created_by`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `guest_id`, `resident_id`, `fee_head_id`, `subscription_id`, `student_accessory_id`, `total_amount`, `amount`, `remaining_amount`, `transaction_id`, `payment_method`, `payment_status`, `is_caution_money`, `created_by`, `due_date`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 7, 1, NULL, NULL, NULL, '36000.00', '36000.00', '0.00', NULL, 'UPI', 'Completed', 0, 9, '2025-10-25', 'Advance payment for hostel', '2025-07-25 00:09:28', '2025-07-25 00:09:28'),
(2, 7, 1, NULL, NULL, NULL, '10000.00', '10000.00', '0.00', NULL, 'UPI', 'Completed', 1, 9, '2025-10-25', 'Caution Money', '2025-07-25 00:09:28', '2025-07-25 00:09:28'),
(3, NULL, 1, NULL, NULL, 1, '3000.00', '0.00', '3000.00', NULL, 'Null', 'Pending', 0, 2, '2025-08-24', NULL, '2025-07-25 00:14:26', '2025-07-25 00:14:26'),
(4, NULL, 1, NULL, NULL, 1, '3000.00', '2000.00', '1000.00', 'ACC-TRX-1753422308399-776550', 'UPI', 'Pending', 0, NULL, '2025-08-24', NULL, '2025-07-25 00:15:08', '2025-07-25 00:15:08'),
(5, NULL, 1, 3, 1, NULL, '200.00', '0.00', '200.00', NULL, 'Null', 'Pending', 0, 3, NULL, NULL, '2025-07-25 00:20:53', '2025-07-25 00:20:53'),
(6, NULL, 1, NULL, 1, NULL, '200.00', '100.00', '100.00', 'TXN202296263', 'Bank Transfer', 'Pending', 0, NULL, NULL, NULL, '2025-07-25 00:24:22', '2025-07-25 00:24:22'),
(7, 6, 2, NULL, NULL, NULL, '18000.00', '18000.00', '0.00', NULL, 'UPI', 'Completed', 0, 10, '2025-10-25', 'Advance payment for hostel', '2025-07-25 01:01:49', '2025-07-25 01:01:49'),
(8, 6, 2, NULL, NULL, NULL, '10000.00', '10000.00', '0.00', NULL, 'UPI', 'Completed', 1, 10, '2025-10-25', 'Caution Money', '2025-07-25 01:01:49', '2025-07-25 01:01:49'),
(9, NULL, 1, NULL, NULL, 2, '3000.00', '0.00', '3000.00', NULL, 'Null', 'Pending', 0, NULL, '2025-08-24', NULL, '2025-07-25 01:59:42', '2025-07-25 01:59:42'),
(10, NULL, 2, NULL, NULL, 3, '50.00', '0.00', '50.00', NULL, 'Null', 'Pending', 0, 2, '2025-08-30', 'Checking', '2025-07-31 04:22:48', '2025-07-31 04:22:48'),
(11, 8, 4, NULL, NULL, NULL, '18000.00', '18000.00', '0.00', NULL, 'UPI', 'Completed', 0, 18, '2025-11-02', 'Advance payment for hostel', '2025-08-02 06:13:40', '2025-08-02 06:13:40'),
(12, 8, 4, NULL, NULL, NULL, '10000.00', '10000.00', '0.00', NULL, 'UPI', 'Completed', 1, 18, '2025-11-02', 'Caution Money', '2025-08-02 06:13:40', '2025-08-02 06:13:40'),
(13, NULL, 4, NULL, NULL, 4, '15000.00', '0.00', '15000.00', NULL, 'Null', 'Pending', 0, NULL, '2025-09-04', NULL, '2025-08-05 06:53:38', '2025-08-05 06:53:38'),
(14, NULL, 4, NULL, NULL, 4, '15000.00', '10000.00', '5000.00', '122244', 'UPI', 'Pending', 0, NULL, '2025-09-04', NULL, '2025-08-05 07:00:14', '2025-08-05 07:00:14'),
(15, NULL, 1, 1, 2, NULL, '9000.00', '0.00', '9000.00', NULL, 'Null', 'Pending', 0, NULL, '2025-08-24', NULL, '2025-08-17 12:27:22', '2025-08-17 12:27:22'),
(16, NULL, 2, NULL, NULL, 5, '0.00', '0.00', '0.00', NULL, 'Null', 'Pending', 0, 2, '2025-09-16', 'default', '2025-08-17 12:31:04', '2025-08-17 12:31:04'),
(17, NULL, 2, NULL, NULL, 6, '50.00', '0.00', '50.00', NULL, 'Null', 'Pending', 0, NULL, '2025-09-17', NULL, '2025-08-18 06:02:04', '2025-08-18 06:02:04'),
(18, NULL, 2, NULL, NULL, 3, '50.00', '25.00', '25.00', '102121', 'Cash', 'Pending', 0, NULL, '2025-08-30', NULL, '2025-08-18 12:29:47', '2025-08-18 12:29:47'),
(19, NULL, 2, NULL, NULL, 3, '50.00', '25.00', '0.00', '1001', 'Cash', 'Completed', 0, NULL, '2025-08-30', NULL, '2025-08-19 02:01:36', '2025-08-19 02:01:36'),
(20, 1, 5, NULL, NULL, NULL, '18000.00', '18000.00', '0.00', NULL, 'UPI', 'Completed', 0, 20, '2025-11-19', 'Advance payment for hostel', '2025-08-19 07:21:10', '2025-08-19 07:21:10'),
(21, 1, 5, NULL, NULL, NULL, '10000.00', '10000.00', '0.00', NULL, 'UPI', 'Completed', 1, 20, '2025-11-19', 'Caution Money', '2025-08-19 07:21:10', '2025-08-19 07:21:10'),
(22, 2, 6, NULL, NULL, NULL, '2000.00', '2000.00', '0.00', NULL, 'Cash', 'Completed', 0, 50, '2025-08-24', 'Advance payment for hostel', '2025-08-24 09:21:04', '2025-08-24 09:21:04'),
(23, 2, 6, NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, 'Cash', 'Completed', 1, 50, '2025-08-24', 'Caution Money', '2025-08-24 09:21:04', '2025-08-24 09:21:04'),
(24, 10, 7, NULL, NULL, NULL, '10050.00', '10050.00', '0.00', NULL, 'UPI', 'Completed', 0, 51, '2025-09-25', 'Advance payment for hostel', '2025-08-24 23:46:18', '2025-08-24 23:46:18'),
(25, 10, 7, NULL, NULL, NULL, '1000.00', '1000.00', '0.00', NULL, 'UPI', 'Completed', 1, 51, '2025-09-25', 'Caution Money', '2025-08-24 23:46:18', '2025-08-24 23:46:18'),
(26, NULL, 1, 3, 1, NULL, '200.00', '25.00', '75.00', 'TRX-1756125108822-699069', 'UPI', 'Pending', 0, NULL, NULL, NULL, '2025-08-25 07:01:57', '2025-08-25 07:01:57'),
(27, NULL, 1, NULL, NULL, 1, '3000.00', '1000.00', '0.00', 'ACC-TRX-1756127648624-437157', 'UPI', 'Completed', 0, NULL, '2025-08-24', NULL, '2025-08-25 07:44:09', '2025-08-25 07:44:09'),
(28, NULL, 2, 3, 3, NULL, '500.00', '0.00', '500.00', NULL, 'Null', 'Pending', 0, NULL, NULL, 'vgh', '2025-08-25 10:27:27', '2025-08-25 10:27:27'),
(29, 11, 11, NULL, NULL, NULL, '7050.00', '7050.00', '0.00', NULL, 'Cash', 'Completed', 0, 60, '2025-09-28', 'Advance payment for hostel', '2025-08-28 12:12:22', '2025-08-28 12:12:22'),
(30, 11, 11, NULL, NULL, NULL, '1000.00', '1000.00', '0.00', NULL, 'Cash', 'Completed', 1, 60, '2025-09-28', 'Caution Money', '2025-08-28 12:12:22', '2025-08-28 12:12:22'),
(31, NULL, 11, 3, 4, NULL, '225.00', '0.00', '225.00', NULL, 'Null', 'Pending', 0, NULL, NULL, 'Testing', '2025-08-29 01:56:02', '2025-08-29 01:56:02'),
(32, NULL, 11, 1, 5, NULL, '3000.00', '0.00', '3000.00', NULL, 'Null', 'Pending', 0, NULL, '2025-09-05', NULL, '2025-08-29 13:32:07', '2025-08-29 13:32:07'),
(33, NULL, 11, 1, 7, NULL, '3000.00', '0.00', '3000.00', NULL, 'Null', 'Pending', 0, NULL, '2025-09-06', NULL, '2025-08-30 01:07:40', '2025-08-30 01:07:40'),
(34, NULL, 11, NULL, NULL, 15, '1000.00', '0.00', '1000.00', NULL, 'Null', 'Pending', 0, 54, '2025-09-29', 'test', '2025-08-30 01:47:19', '2025-08-30 01:47:19'),
(36, NULL, 11, NULL, NULL, 15, '1000.00', '500.00', '500.00', '1021012', 'Cash', 'Pending', 0, NULL, '2025-09-29', NULL, '2025-08-30 12:14:02', '2025-08-30 12:14:02'),
(37, NULL, 11, NULL, NULL, 15, '1000.00', '500.00', '0.00', '222022', 'Cash', 'Completed', 0, NULL, '2025-09-29', NULL, '2025-08-30 12:15:58', '2025-08-30 12:15:58'),
(38, NULL, 11, NULL, NULL, 16, '150.00', '0.00', '150.00', NULL, 'Null', 'Pending', 0, NULL, '2025-10-01', NULL, '2025-09-01 04:01:38', '2025-09-01 04:01:38'),
(39, NULL, 11, NULL, NULL, 16, '150.00', '150.00', '0.00', '2456', 'Cash', 'Completed', 0, NULL, '2025-10-01', NULL, '2025-09-01 04:02:24', '2025-09-01 04:02:24'),
(40, 12, 12, NULL, NULL, NULL, '1650.00', '1650.00', '0.00', NULL, 'Cash', 'Completed', 0, 65, '2025-11-02', 'Advance payment for hostel', '2025-09-02 05:40:36', '2025-09-02 05:40:36'),
(41, 12, 12, NULL, NULL, NULL, '250.00', '250.00', '0.00', NULL, 'Cash', 'Completed', 1, 65, '2025-11-02', 'Caution Money', '2025-09-02 05:40:36', '2025-09-02 05:40:36'),
(42, 28, 13, NULL, NULL, NULL, '21450.00', '21450.00', '0.00', NULL, 'Cash', 'Completed', 0, 66, '2025-12-02', 'Advance payment for hostel', '2025-09-02 10:01:25', '2025-09-02 10:01:25'),
(43, 28, 13, NULL, NULL, NULL, '10000.00', '10000.00', '0.00', NULL, 'Cash', 'Completed', 1, 66, '2025-12-02', 'Caution Money', '2025-09-02 10:01:25', '2025-09-02 10:01:25'),
(44, 30, 14, NULL, NULL, NULL, '21000.00', '21000.00', '0.00', NULL, 'UPI', 'Completed', 0, 67, '2025-12-03', 'Advance payment for hostel', '2025-09-03 03:15:44', '2025-09-03 03:15:44'),
(45, 30, 14, NULL, NULL, NULL, '10000.00', '10000.00', '0.00', NULL, 'UPI', 'Completed', 1, 67, '2025-12-03', 'Caution Money', '2025-09-03 03:15:44', '2025-09-03 03:15:44'),
(46, 29, 15, NULL, NULL, NULL, '21000.00', '21000.00', '0.00', NULL, 'UPI', 'Completed', 0, 68, '2025-12-03', 'Advance payment for hostel', '2025-09-03 03:21:17', '2025-09-03 03:21:17'),
(47, 29, 15, NULL, NULL, NULL, '10000.00', '10000.00', '0.00', NULL, 'UPI', 'Completed', 1, 68, '2025-12-03', 'Caution Money', '2025-09-03 03:21:17', '2025-09-03 03:21:17');

-- --------------------------------------------------------

--
-- Table structure for table `payment_notifications`
--

DROP TABLE IF EXISTS `payment_notifications`;
CREATE TABLE IF NOT EXISTS `payment_notifications` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `payment_id` bigint UNSIGNED NOT NULL,
  `resident_id` bigint UNSIGNED NOT NULL,
  `notification_type` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `sms_gateway_message_id` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `sent_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payment_notifications_payment_id_notification_type_unique` (`payment_id`,`notification_type`),
  KEY `payment_notifications_resident_id_foreign` (`resident_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `paytm_payments`
--

DROP TABLE IF EXISTS `paytm_payments`;
CREATE TABLE IF NOT EXISTS `paytm_payments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `identity` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `order_id` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `transaction_id` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `status` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `bank_name` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `payment_mode` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `currency` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `response_code` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `response_message` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `response_payload` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `guard_name` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `custom_roles_unique_key` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\Guest', 3, 'guest-token', '79f4738d3181c719399daef664b36b02c2c7e0fa2851866ee9b60d7f03e2ee11', '[\"*\"]', NULL, NULL, '2025-07-17 06:26:54', '2025-07-17 06:26:54'),
(2, 'App\\Models\\Guest', 3, 'guest-token', 'bfce462bd01392be46e46ba3b1128c48662a04d835841ba23703545a77d0b4bd', '[\"*\"]', NULL, NULL, '2025-07-18 00:37:59', '2025-07-18 00:37:59'),
(3, 'App\\Models\\Guest', 3, 'guest-token', '7c1fa33a9fe6997444eeb711558e90fe3a56e618ee3eba44a909d6319ab29e92', '[\"*\"]', NULL, NULL, '2025-07-18 00:38:11', '2025-07-18 00:38:11'),
(4, 'App\\Models\\Guest', 3, 'guest-token', '62bbcbada010eb17b0751c16176846c6951ef6a1395c264878e15f6e2316ca33', '[\"*\"]', NULL, '2025-07-18 02:41:10', '2025-07-18 00:41:10', '2025-07-18 00:41:10'),
(5, 'App\\Models\\Guest', 3, 'guest-token', '7e70a5ec6ad322e2d5a1d59abad5fcf06e9b186c48186d345f0a880eb5f4e873', '[\"*\"]', NULL, '2025-07-18 04:14:22', '2025-07-18 02:14:22', '2025-07-18 02:14:22'),
(6, 'App\\Models\\Guest', 3, 'guest-token', '4e0cd6d8268259d0dd4b613a5b80172c2542ebd8810d259300949b0bc4af833b', '[\"*\"]', NULL, '2025-07-18 06:31:47', '2025-07-18 04:31:47', '2025-07-18 04:31:47'),
(7, 'App\\Models\\Guest', 3, 'guest-token', '0d2a1fc00f723a094c27d5751584ad688079a364b0b76e4fb41fbc086366a07d', '[\"*\"]', NULL, '2025-07-18 06:37:13', '2025-07-18 04:37:13', '2025-07-18 04:37:13'),
(8, 'App\\Models\\Guest', 3, 'guest-token', '4402a95706b0b70e51f7c04bf250a4ab90699962fc8443ea8da3e2acff0911c0', '[\"*\"]', NULL, '2025-07-18 06:39:36', '2025-07-18 04:39:36', '2025-07-18 04:39:36'),
(9, 'App\\Models\\Guest', 3, 'guest-token', 'c93c6aa815880003a46da90ba4edce7c6657261da3155474355542aa76fecfc8', '[\"*\"]', NULL, '2025-07-18 07:19:29', '2025-07-18 05:19:29', '2025-07-18 05:19:29'),
(10, 'App\\Models\\Guest', 3, 'guest-token', '165ec51254ac00db60a97c3bd4bf4646b53fc6ecf92fe9a74868aa9ad1679395', '[\"*\"]', NULL, NULL, '2025-07-18 05:37:03', '2025-07-18 05:37:03'),
(11, 'App\\Models\\Guest', 3, 'guest-token', '8b6c43978bd36c33821119d18b188ea766d2041d362527f102899248bcc53f71', '[\"*\"]', NULL, NULL, '2025-07-18 05:45:35', '2025-07-18 05:45:35'),
(12, 'App\\Models\\Guest', 3, 'guest-token', '536e42638d25f2a5f3d4c7adef87f02ae39db8f09cc578defb5d09782a3152ba', '[\"*\"]', NULL, NULL, '2025-07-18 06:07:44', '2025-07-18 06:07:44'),
(13, 'App\\Models\\Guest', 3, 'guest-token', '9498bd50e73acdc5691ecf5e877819cd01fd0c9ce70b038c4d15bc8c6a506fcb', '[\"*\"]', NULL, NULL, '2025-07-18 06:18:42', '2025-07-18 06:18:42'),
(14, 'App\\Models\\Guest', 3, 'guest-token', 'e04c1c2c942ffdb32a971cef7119f7f9c533fdb431c2e6dbd030acc9dae63956', '[\"*\"]', NULL, NULL, '2025-07-18 06:24:23', '2025-07-18 06:24:23'),
(15, 'App\\Models\\Guest', 3, 'guest-token', '50345c85abe337ee8b49e822c9fbc091d92c8c3ab7660ab9be91f9e1c07cdf5b', '[\"*\"]', NULL, NULL, '2025-07-18 06:35:13', '2025-07-18 06:35:13'),
(16, 'App\\Models\\Guest', 3, 'guest-token', 'c72f647ee91567ec27b8f6b90beca8a31fc3e8a4837e80cfd8fcacb6c0b92df3', '[\"*\"]', NULL, NULL, '2025-07-18 07:06:55', '2025-07-18 07:06:55'),
(17, 'App\\Models\\Guest', 3, 'guest-token', 'f9b28ea4178dd28effe67a9c00eb8925f5d55c9da19b4cc5aae5d8a4f22330ab', '[\"*\"]', NULL, NULL, '2025-07-18 07:09:13', '2025-07-18 07:09:13'),
(18, 'App\\Models\\Guest', 3, 'guest-token', '6739589a569f163e5fd2e5344f923e38d40c363d9b191dd72c90887547bec13c', '[\"*\"]', NULL, NULL, '2025-07-18 07:13:54', '2025-07-18 07:13:54');

-- --------------------------------------------------------

--
-- Table structure for table `residents`
--

DROP TABLE IF EXISTS `residents`;
CREATE TABLE IF NOT EXISTS `residents` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `email` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `gender` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `scholar_no` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `number` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `parent_no` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `guardian_no` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `fathers_name` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `mothers_name` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `bed_id` bigint UNSIGNED DEFAULT NULL,
  `status` enum('pending','active','inactive','checkout') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'pending',
  `guest_id` bigint UNSIGNED DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `residents_scholar_no_unique` (`scholar_no`),
  UNIQUE KEY `residents_email_unique` (`email`),
  KEY `residents_user_id_foreign` (`user_id`),
  KEY `residents_bed_id_foreign` (`bed_id`),
  KEY `residents_guest_id_foreign` (`guest_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `residents`
--

INSERT INTO `residents` (`id`, `name`, `email`, `gender`, `scholar_no`, `number`, `parent_no`, `guardian_no`, `fathers_name`, `mothers_name`, `user_id`, `bed_id`, `status`, `guest_id`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Sonu', 'sonu@gmail.com', 'Male', 'AU210341', '1234567890', '1234567890', '1234567890', 'Sonu Kumar', 'Sunita Devi', 9, 33, 'active', 7, 9, '2025-07-25 00:09:28', '2025-08-13 05:35:38'),
(2, 'C1', 'c1@gmail.com', 'Male', '11111111', NULL, NULL, NULL, 'D', 'E', 10, 6, 'active', 6, 10, '2025-07-25 01:01:49', '2025-07-30 04:42:02'),
(4, 'Aditya Singh Sengar', 'adiyasinghsengar@gmail.com', 'Male', 'SCH16638999', '9630251542', '9630251542', '9562425254', 'Mr. Sengar', 'Mrs. Sengar', 18, 1, 'checkout', 8, 18, '2025-08-02 06:13:40', '2025-08-07 04:18:53'),
(5, 'Shiv', 'shiv@gmail.com', 'Male', '1001', NULL, NULL, NULL, 'Abhishek Verma', 'Khushi Verma', 20, 9, 'active', 1, 20, '2025-08-19 07:21:10', '2025-08-25 00:27:54'),
(6, 'Rudra Verma', 'rudra@gmail.com', 'Male', '1002', NULL, NULL, NULL, 'Abhishek Verma', 'Khushi Verma', 50, 5, 'active', 2, 50, '2025-08-24 09:21:04', '2025-08-25 00:28:09'),
(7, 'Anu Kumari', 'anukumari@test.com', 'Female', '12345678', '9305212421', '9305212421', '9305212421', 'Ankur Raj', 'Anita Singh', 51, 39, 'active', 10, 51, '2025-08-24 23:46:18', '2025-09-02 09:56:57'),
(11, 'Shivangi Shukla', 'shivangi@gmail.com', 'Female', '1524215', '9632151212', '9632151212', '9632151212', 'Mohan Shukla', 'Kanchan Shukla', 60, 53, 'checkout', 11, 60, '2025-08-28 12:12:22', '2025-08-30 01:06:40'),
(12, 'Ankush Narang', 'ankushnarang@gmail.com', 'Male', 'CV25259', '9625254212', '9625254212', '9625254212', 'Ankur Narang', 'Ankita Narang', 65, 36, 'active', 12, 65, '2025-09-02 05:40:36', '2025-09-02 05:42:11'),
(13, 'Jiya Raj', 'jiyaraj@gmail.com', 'Female', 'CV25250', '8548758458', '8548758458', '8548758458', 'Sundar Raj', 'Sharmila Raj', 66, 37, 'active', 28, 66, '2025-09-02 10:01:25', '2025-09-02 10:04:21'),
(14, 'Test2', 'test2@gmail.com', 'Female', '012345', '9874563210', '9874563210', '9874563210', 'father', 'Mother', 67, NULL, 'pending', 30, 67, '2025-09-03 03:15:44', '2025-09-03 03:15:44'),
(15, 'Test', 'test@gmail.com', 'Male', '2023', '9874563210', '9874563210', '9874563210', 'Father', 'Mother', 68, NULL, 'pending', 29, 68, '2025-09-03 03:21:17', '2025-09-03 03:21:17');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `fullname` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `guard_name` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `custom_roles_unique_key` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `fullname`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super_admin', 'Super Admin', 'web', '2025-07-02 03:32:46', '2025-07-02 03:32:46'),
(2, 'admin', 'Admin', 'web', '2025-07-02 03:32:46', '2025-07-02 03:32:46'),
(3, 'resident', 'Resident', 'web', '2025-07-02 03:32:46', '2025-07-02 03:32:46'),
(4, 'warden', 'Warden', 'web', '2025-07-02 03:32:46', '2025-07-02 03:32:46'),
(5, 'security', 'Security', 'web', '2025-07-02 03:32:46', '2025-07-02 03:32:46'),
(6, 'mess_manager', 'Mess Manager', 'web', '2025-07-02 03:32:46', '2025-07-02 03:32:46'),
(7, 'gym_manager', 'Gym Manager', 'web', '2025-07-02 03:32:46', '2025-07-02 03:32:46'),
(8, 'hod', 'HOD', 'web', '2025-07-02 03:32:46', '2025-07-02 03:32:46'),
(9, 'accountant', 'Accountant', 'web', '2025-07-02 03:32:46', '2025-07-02 03:32:46'),
(10, 'admission', 'Admission', 'web', '2025-07-02 03:32:46', '2025-07-02 03:32:46');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

DROP TABLE IF EXISTS `rooms`;
CREATE TABLE IF NOT EXISTS `rooms` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `room_number` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `building_id` bigint UNSIGNED NOT NULL,
  `floor_no` int NOT NULL,
  `status` enum('available','occupied','maintenance') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rooms_building_id_foreign` (`building_id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_number`, `building_id`, `floor_no`, `status`, `created_at`, `updated_at`, `created_by`) VALUES
(1, '101', 1, 1, 'available', '2025-07-02 06:12:46', '2025-07-02 06:12:46', NULL),
(2, '102', 1, 1, 'available', '2025-07-02 06:12:56', '2025-07-02 06:12:56', NULL),
(3, '103', 1, 1, 'available', '2025-07-02 06:13:06', '2025-07-02 06:13:06', NULL),
(4, '201', 1, 2, 'available', '2025-07-02 06:13:15', '2025-07-02 06:13:15', NULL),
(5, '202', 1, 2, 'available', '2025-07-02 06:13:24', '2025-07-02 06:13:24', NULL),
(6, '203', 1, 2, 'available', '2025-07-02 06:13:33', '2025-07-02 06:13:33', NULL),
(7, '204', 1, 2, 'available', '2025-07-02 06:13:48', '2025-07-02 06:13:48', NULL),
(8, '205', 1, 2, 'available', '2025-07-02 06:13:58', '2025-07-02 06:13:58', NULL),
(9, '206', 1, 2, 'available', '2025-07-02 06:14:07', '2025-07-02 06:14:07', NULL),
(10, '301', 1, 3, 'available', '2025-07-02 06:14:16', '2025-07-02 06:14:16', NULL),
(11, '302', 1, 3, 'available', '2025-07-02 06:14:28', '2025-07-02 06:14:28', NULL),
(12, '304', 1, 3, 'available', '2025-07-02 06:14:39', '2025-07-02 06:14:39', NULL),
(13, '305', 1, 3, 'available', '2025-07-02 06:14:59', '2025-07-02 06:14:59', NULL),
(14, '306', 1, 3, 'available', '2025-07-02 06:15:08', '2025-07-02 06:15:08', NULL),
(15, '303', 1, 3, 'available', '2025-07-02 06:15:30', '2025-07-02 06:15:30', NULL),
(19, '101', 6, 1, 'available', '2025-08-13 01:37:44', '2025-08-13 01:37:44', NULL),
(20, '101', 7, 1, 'available', '2025-08-28 02:11:13', '2025-08-28 02:11:13', NULL),
(21, '102', 7, 1, 'available', '2025-08-28 02:12:02', '2025-08-28 02:12:02', NULL),
(22, '103', 7, 1, 'available', '2025-08-28 02:12:09', '2025-08-28 02:12:09', NULL),
(23, '201', 7, 2, 'available', '2025-08-28 02:12:17', '2025-08-28 02:12:17', NULL),
(24, '202', 7, 2, 'available', '2025-08-28 02:12:25', '2025-08-28 02:12:25', NULL),
(25, '301', 7, 2, 'available', '2025-08-28 02:12:33', '2025-08-28 02:12:33', NULL),
(26, '302', 7, 2, 'available', '2025-08-28 02:12:43', '2025-08-28 02:12:43', NULL),
(27, '401', 7, 4, 'available', '2025-08-28 02:15:40', '2025-08-28 02:15:40', NULL),
(28, '402', 7, 4, 'available', '2025-08-28 02:15:49', '2025-08-28 02:15:49', NULL),
(29, '501', 7, 5, 'available', '2025-08-28 02:15:59', '2025-08-28 02:15:59', NULL),
(30, '502', 7, 5, 'available', '2025-08-28 02:16:07', '2025-08-28 02:16:07', NULL),
(31, '101', 8, 1, 'available', '2025-08-28 02:16:16', '2025-08-28 02:16:16', NULL),
(32, '102', 8, 1, 'available', '2025-08-28 02:16:24', '2025-08-28 02:16:24', NULL),
(33, '201', 8, 2, 'available', '2025-08-28 02:16:33', '2025-08-28 02:16:33', NULL),
(34, '202', 8, 2, 'available', '2025-08-28 02:16:40', '2025-08-28 02:16:40', NULL),
(35, '203', 8, 2, 'available', '2025-08-28 02:16:51', '2025-08-28 02:16:51', NULL),
(36, '301', 8, 3, 'available', '2025-08-28 02:17:01', '2025-08-28 02:17:01', NULL),
(37, '302', 8, 3, 'available', '2025-08-28 02:17:10', '2025-08-28 02:17:10', NULL),
(38, '303', 8, 3, 'available', '2025-08-28 02:17:18', '2025-08-28 02:17:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `room_change_messages`
--

DROP TABLE IF EXISTS `room_change_messages`;
CREATE TABLE IF NOT EXISTS `room_change_messages` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `room_change_request_id` bigint UNSIGNED NOT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `sender` enum('admin','resident') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `room_change_messages_room_change_request_id_foreign` (`room_change_request_id`),
  KEY `room_change_messages_created_by_foreign` (`created_by`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `room_change_messages`
--

INSERT INTO `room_change_messages` (`id`, `room_change_request_id`, `created_by`, `sender`, `message`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'admin', 'Room no 203 is available', '2025-07-25 00:30:20', '2025-07-25 00:30:20'),
(2, 1, NULL, 'resident', 'ok done', '2025-07-25 00:30:41', '2025-07-25 00:30:41'),
(3, 1, 2, 'admin', 'Room 205 is available', '2025-08-01 10:47:12', '2025-08-01 10:47:12'),
(4, 1, NULL, 'admin', 'Room 205 is available', '2025-08-01 10:55:38', '2025-08-01 10:55:38'),
(5, 1, 2, 'admin', 'Room 205 is available', '2025-08-05 01:26:11', '2025-08-05 01:26:11'),
(6, 2, NULL, 'admin', 'Your requested Room is not available, Room No 104 is available.', '2025-08-13 06:56:57', '2025-08-13 06:56:57'),
(7, 3, NULL, 'resident', 'hi', '2025-08-18 02:09:30', '2025-08-18 02:09:30'),
(8, 3, NULL, 'resident', 'hi', '2025-08-18 02:10:27', '2025-08-18 02:10:27'),
(9, 3, 10, 'resident', 'hi', '2025-08-18 02:13:19', '2025-08-18 02:13:19'),
(10, 4, 10, 'resident', 'hi', '2025-08-19 02:01:03', '2025-08-19 02:01:03'),
(11, 5, 54, 'admin', '302 is available', '2025-08-29 04:51:58', '2025-08-29 04:51:58'),
(12, 5, 60, 'resident', 'Okay', '2025-08-29 04:52:41', '2025-08-29 04:52:41');

-- --------------------------------------------------------

--
-- Table structure for table `room_change_requests`
--

DROP TABLE IF EXISTS `room_change_requests`;
CREATE TABLE IF NOT EXISTS `room_change_requests` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `resident_id` bigint UNSIGNED NOT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `reason` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `preference` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `token` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `action` enum('pending','available','not_available','completed') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'pending',
  `remark` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `resident_agree` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `room_change_requests_token_unique` (`token`),
  KEY `room_change_requests_resident_id_foreign` (`resident_id`),
  KEY `room_change_requests_created_by_foreign` (`created_by`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `room_change_requests`
--

INSERT INTO `room_change_requests` (`id`, `resident_id`, `created_by`, `reason`, `preference`, `token`, `action`, `remark`, `resident_agree`, `created_at`, `updated_at`) VALUES
(1, 1, 9, 'ABCD', 'B22', 'H5URxPkLCaWsLSubJVd8ZFCOekP5w8', 'not_available', 'Test', 1, '2025-07-25 00:29:51', '2025-08-05 01:38:49'),
(2, 4, 2, 'Mutual Understaing with some other guys', 'Room 205', '94X6ZvOM4iHhyLVKFq0iiSjIfGrUDK', 'pending', NULL, NULL, '2025-08-05 04:28:15', '2025-08-05 04:28:15'),
(3, 2, 10, 'Mutual Understaing with some other guys', 'Room 205', 'DP799CfAzVmrHO1Uvj6th7SzzKJC7X', 'pending', NULL, NULL, '2025-08-18 01:33:44', '2025-08-18 01:33:44'),
(4, 2, 10, 'i need to change the room', '202', '2FqyTtjgoyrle6NHfzcqUEZfVmMHrH', 'pending', NULL, 1, '2025-08-18 01:35:21', '2025-08-18 02:18:09'),
(5, 11, 60, 'I want to change the room', '301', 'OBeXBYCmZmaBqsAFdMD6I6NpUenaGB', 'completed', NULL, 1, '2025-08-29 04:50:40', '2025-08-29 07:26:34');

-- --------------------------------------------------------

--
-- Table structure for table `student_accessory`
--

DROP TABLE IF EXISTS `student_accessory`;
CREATE TABLE IF NOT EXISTS `student_accessory` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `resident_id` bigint UNSIGNED NOT NULL,
  `accessory_head_id` bigint UNSIGNED NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `due_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `student_accessory`
--

INSERT INTO `student_accessory` (`id`, `resident_id`, `accessory_head_id`, `price`, `total_amount`, `from_date`, `to_date`, `due_date`, `created_at`, `updated_at`, `created_by`) VALUES
(1, 1, 3, '1000.00', '3000.00', '2025-07-25', '2025-10-25', '2025-08-24', '2025-07-25 00:14:26', '2025-07-25 00:14:26', NULL),
(2, 1, 3, '1000.00', '3000.00', '2025-07-25', '2025-10-25', '2025-08-24', '2025-07-25 01:59:42', '2025-07-25 01:59:42', NULL),
(3, 2, 5, '50.00', '50.00', '2025-07-31', '2025-08-31', '2025-08-30', '2025-07-31 04:22:48', '2025-07-31 04:22:48', NULL),
(4, 4, 4, '5000.00', '15000.00', '2025-08-05', '2025-11-05', '2025-09-04', '2025-08-05 06:53:38', '2025-08-05 06:53:38', NULL),
(5, 2, 1, '0.00', '0.00', '2025-08-17', '2025-11-17', '2025-09-16', '2025-08-17 12:31:04', '2025-08-17 12:31:04', NULL),
(6, 2, 5, '50.00', '50.00', '2025-08-18', '2025-09-18', '2025-09-17', '2025-08-18 06:02:04', '2025-08-18 06:02:04', NULL),
(15, 11, 14, '1000.00', '1000.00', '2025-08-30', '2025-09-30', '2025-09-29', '2025-08-30 01:47:19', '2025-08-30 01:47:19', NULL),
(16, 11, 26, '150.00', '150.00', '2025-09-01', '2025-10-01', '2025-10-01', '2025-09-01 04:01:38', '2025-09-01 04:01:38', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
CREATE TABLE IF NOT EXISTS `subscriptions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `resident_id` bigint UNSIGNED NOT NULL,
  `fee_head_id` bigint UNSIGNED NOT NULL,
  `subscription_type` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('Pending','Active','Expired') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'Pending',
  `remarks` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subscriptions_resident_id_foreign` (`resident_id`),
  KEY `subscriptions_fee_head_id_foreign` (`fee_head_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `resident_id`, `fee_head_id`, `subscription_type`, `price`, `total_amount`, `start_date`, `end_date`, `status`, `remarks`, `created_at`, `updated_at`, `created_by`) VALUES
(1, 1, 3, 'Other', '200.00', '200.00', NULL, NULL, 'Pending', 'abcd', '2025-07-25 00:20:25', '2025-07-25 00:20:53', 2),
(2, 1, 1, 'Hostel Fee', '3000.00', '9000.00', '2025-08-17', '2025-11-17', 'Pending', '1222', '2025-08-17 12:27:22', '2025-08-17 12:27:22', NULL),
(3, 2, 3, 'Other', '500.00', '500.00', NULL, NULL, 'Pending', 'Testing', '2025-08-17 12:32:25', '2025-08-25 10:27:27', NULL),
(4, 11, 3, 'Other', '225.00', '225.00', NULL, NULL, 'Pending', 'Test', '2025-08-29 01:55:24', '2025-08-29 01:56:02', 54),
(5, 11, 1, 'Hostel Fee', '3000.00', '3000.00', '2025-08-29', '2025-09-29', 'Pending', 'testing', '2025-08-29 13:32:07', '2025-08-29 13:32:07', NULL),
(6, 11, 3, 'Other', '1100.00', '0.00', NULL, NULL, 'Pending', 'Test', '2025-08-29 14:16:01', '2025-08-29 14:16:01', 54),
(7, 11, 1, 'Hostel Fee', '3000.00', '3000.00', '2025-08-30', '2025-09-30', 'Pending', 'xyz', '2025-08-30 01:07:40', '2025-08-30 01:07:40', NULL),
(8, 11, 3, 'Other', '1100.00', '0.00', NULL, NULL, 'Pending', '100', '2025-08-30 01:48:13', '2025-08-30 01:48:13', 54),
(9, 13, 3, 'Other', '1100.00', '0.00', NULL, NULL, 'Pending', '1234', '2025-09-02 10:06:41', '2025-09-02 10:06:41', 54);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `txn_id` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `status` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `bank_name` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `payment_mode` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `txn_amount` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `currency` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `response_code` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `response_message` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `bank_txn_id` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `m_id` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `response_payload` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `order_id`, `txn_id`, `status`, `bank_name`, `payment_mode`, `txn_amount`, `currency`, `response_code`, `response_message`, `bank_txn_id`, `m_id`, `response_payload`, `created_at`, `updated_at`) VALUES
(1, '2', NULL, 'TXN_SUCCESS', 'Mock Bank', 'UPI', '31000.00', 'INR', '01', 'Txn Success', NULL, 'Resell00448805757124', '{\"MID\": \"Resell00448805757124\", \"TXNID\": \"MOCKTXN123456789\", \"STATUS\": \"TXN_SUCCESS\", \"ORDERID\": \"G-ORD250903NGA2\", \"RESPMSG\": \"Txn Success\", \"BANKNAME\": \"Mock Bank\", \"CURRENCY\": \"INR\", \"RESPCODE\": \"01\", \"BANKTXNID\": null, \"TXNAMOUNT\": \"31000.00\", \"PAYMENTMODE\": \"UPI\"}', '2025-09-03 03:45:25', '2025-09-03 03:45:25');

-- --------------------------------------------------------

--
-- Table structure for table `universities`
--

DROP TABLE IF EXISTS `universities`;
CREATE TABLE IF NOT EXISTS `universities` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `location` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `state` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `district` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `pincode` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `mobile` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `universities_id_unique` (`id`),
  UNIQUE KEY `universities_pincode_unique` (`pincode`),
  UNIQUE KEY `universities_mobile_unique` (`mobile`),
  UNIQUE KEY `universities_email_unique` (`email`),
  KEY `universities_state_index` (`state`),
  KEY `universities_district_index` (`district`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `universities`
--

INSERT INTO `universities` (`id`, `name`, `location`, `state`, `district`, `pincode`, `address`, `mobile`, `email`, `created_at`, `updated_at`, `created_by`) VALUES
(1, 'RNTU', 'Bangrasia', 'Madhya Pradesh', 'Raisen', '464993', 'Mendua RNTU', '9039137768', 'verma.abhishek92@gmail.com', '2025-07-02 06:10:34', '2025-08-25 01:12:11', NULL),
(2, 'CVRU Bihar', 'Vaishali', 'Bihar', 'Vaishali', '854600', 'Bhagwanpur, Vaishali', '9856321542', 'info@cvrubihar.ac.in', '2025-08-01 01:46:29', '2025-08-25 01:11:53', NULL),
(3, 'CVRU Khandwa', 'Khandwa', 'Madhya Pradesh', 'Khandwa', '412345', 'Khandwa', '9125421512', 'info@cvrump.ac.in', '2025-08-25 01:23:56', '2025-08-25 01:23:56', NULL),
(4, 'CVRU CG', 'Kota Campus Bilaspur', 'Chhatisgarh', 'Bilaspur', '495001', 'Kota Campus, Bilaspur, CG', '9652124215', 'info@cvru.ac.in', '2025-08-28 01:08:48', '2025-08-28 01:08:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `gender` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `university_id` bigint UNSIGNED DEFAULT NULL,
  `building_id` bigint UNSIGNED DEFAULT NULL,
  `department_id` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `token` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `token_expiry` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `status` varchar(2) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_university_id_foreign` (`university_id`),
  KEY `users_building_id_foreign` (`building_id`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `gender`, `password`, `university_id`, `building_id`, `department_id`, `token`, `token_expiry`, `status`, `created_at`, `updated_at`, `created_by`) VALUES
(1, 'Super Admin', 'superadmin@gmail.com', NULL, '$2y$12$rPgR047UecQ76k9xicxryezFB79D7zGOMihGpnfcao5.Let/3nTwK', NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-02 03:32:59', '2025-08-28 01:37:29', NULL),
(2, 'Abhishek Verma', 'abhishek.verma@aisect.org', NULL, '$2y$12$aSH9tjCpYxTJ7wkpYnhd..nh2nOqOuzFA9oYfWmHZGQ3zMRiJRkaS', 1, NULL, NULL, NULL, NULL, NULL, '2025-07-02 06:11:04', '2025-09-02 05:41:35', NULL),
(3, 'Amresh Singh', 'amresh.bpl1@gmail.com', NULL, '$2y$12$SseJt.0LUJvShHGhOP1aFu46/RSp8Xyrv3hhzODbkYxUemPdcrwyG', 1, 1, NULL, '2y12jzCrJSzuEYVmRZ6D3h1FeTx2A8rMo7CNObnjbmGG3fK1eVbp2Me', '1756189614', NULL, '2025-07-02 06:37:43', '2025-08-25 00:56:54', NULL),
(4, 'Abhishek Sen', 'abhishek@gmail.com', NULL, '$2y$12$Lhd4enHDAd.s69OLdT8LlOLk2V/VJ65naqHbfmi8hAgZSphwwsIzC', 1, 1, NULL, NULL, NULL, NULL, '2025-07-02 06:38:11', '2025-07-02 06:38:11', NULL),
(5, 'Security', 'security@gmail.com', NULL, '$2y$12$MWYs9Ckp2O8lT/zazNqhmuFXAnFsysKsenwgWEn3YlZ/cWX0L1EFK', 1, 1, NULL, NULL, NULL, NULL, '2025-07-02 06:43:53', '2025-07-02 06:43:53', NULL),
(6, 'Mess Manager', 'mess@gmail.com', NULL, '$2y$12$TkvfGXU3RR9bo8f.tplBEuRJwE/Y5KkX5wDA1QMLR5mQYk9wTgPxy', 1, 1, NULL, NULL, NULL, NULL, '2025-07-02 06:44:22', '2025-07-02 06:44:22', NULL),
(7, 'Gym Manager', 'gym@gmail.com', NULL, '$2y$12$p58A7hk7pEmNcAJgYUz9mOYqNzUlfhihJ3EQuN/Fmdu3Kn6prz5SK', 1, 1, NULL, NULL, NULL, NULL, '2025-07-02 06:44:47', '2025-07-02 06:44:47', NULL),
(8, 'HOD of Agriculture', 'hod_agri@gmail.com', NULL, '$2y$12$SOJqOLtmPoYrroHD/I1UxeclvN.6H4XgESXPOa3Cw448FgWmZfcba', NULL, 1, NULL, NULL, NULL, NULL, '2025-07-02 06:45:57', '2025-08-26 00:47:44', NULL),
(9, 'Sonu', 'sonu@gmail.com', NULL, '$2y$12$aKExHZzLOLvlWiVgFzYJouHCTEbctKwK74OZia1xKDddfPKC4GqJa', NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-25 00:09:28', '2025-07-25 00:09:28', NULL),
(10, 'C1', 'c1@gmail.com', NULL, '$2y$12$pSCaBUVxV8nfEDq5wV5yweUwwgs/pp6Uo8e4ZiNvFC.9rZVifcgvO', NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-25 01:01:49', '2025-08-19 03:42:20', NULL),
(11, 'Birjesh Singh', 'vsion_mission1@gmail.com', NULL, '$2y$12$RAqACDQsc7fIJDPMj5k3uOvJrBtSLaLhERSC1y9RovPhXbb9XW676', 2, NULL, NULL, NULL, NULL, NULL, '2025-08-01 01:52:02', '2025-08-25 01:15:15', NULL),
(12, 'Amrish Singh', 'amrishsingh@cvrubihar.ac.in', NULL, '$2y$12$Ux31i8Mvg8cuSAU1Q6WaeeyonlLOhplMyiA6Gwj8sL9RqiRymDp.m', NULL, 4, NULL, NULL, NULL, NULL, '2025-08-01 04:44:49', '2025-08-01 04:51:27', NULL),
(13, 'Ansh Shuri', 'anshshuri@gmail.com', NULL, '$2y$12$2dxwJWJWJ/hWJKNwXCGapeNS15ZTIOvq.y4KhQ9IQXeqMHfQn3dzG', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-01 05:10:12', '2025-08-01 05:10:12', NULL),
(14, 'Ansh Shuri', 'anshshuria@gmail.com', NULL, '$2y$12$Om8d6yLbQI5x3C/FcA5uAOCkCPXv/U0ftwM5DPvtjkL/JcPHEyBle', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-01 05:36:31', '2025-08-01 05:36:31', NULL),
(15, 'Ansh Shuri', 'anshshuriaa@gmail.com', NULL, '$2y$12$LnHZRwiqWh3uoprsXR3ZIeWECow7p6zf5KxMF5e2g1ICEkkk7QiNO', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-01 05:37:14', '2025-08-01 05:37:14', NULL),
(16, 'Ansh Shuri', 'anshshuris@gmail.com', NULL, '$2y$12$8n.lL0MsZtKbR.JZxhx/V.wSiGC4mhfJaTEnSUp9RqeTjEv/HVxoS', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-01 05:37:46', '2025-08-01 05:37:46', NULL),
(17, 'Ansh Shuri', 'anshshuriss@gmail.com', NULL, '$2y$12$6J12D4nBIR0RFN.ok/F1l.MepmLDQSyBK/ramvKf5QPP7otYC.5bS', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-01 05:38:37', '2025-08-01 05:38:37', NULL),
(18, 'Aditya Singh Sengar', 'adiyasinghsengar@gmail.com', NULL, '$2y$12$bYOo0S8SuF9RtzZ7yA.TiOOUw4TD4kpUgJoAL1xWFiIaXVc4OemC2', NULL, NULL, NULL, '2y125ZTBoV3mqClEEK8hwTmhcFaw6Add0tOUHU0DDSSfgFSNhyzYFPi', '1754477589', NULL, '2025-08-02 06:13:40', '2025-08-05 05:23:09', NULL),
(19, 'Ankur', 'ankur@gmail.com', NULL, '$2y$12$ROugmXFOdMOXvV7P0reSyOGNpG3GgfQyqXqFmgi24K4vcvaktvbaq', NULL, 6, NULL, NULL, NULL, NULL, '2025-08-14 04:13:22', '2025-08-14 04:13:22', NULL),
(20, 'Shiv', 'shiv@gmail.com', NULL, '$2y$12$2R4blAWvTCbDq7RPoEI1u.pkN206dckB9DSsAYveP8elVT/yzu7hq', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-19 07:21:10', '2025-08-19 07:21:10', NULL),
(21, 'Akhil Verma', 'akhil.verma@aisect.org', NULL, '$2y$12$aSH9tjCpYxTJ7wkpYnhd..nh2nOqOuzFA9oYfWmHZGQ3zMRiJRkaS', 1, NULL, NULL, NULL, NULL, NULL, '2025-07-02 06:11:04', '2025-08-20 12:22:53', NULL),
(38, 'Prity Shrivastava', 'priti.shrivas@rntu.ac.in', NULL, '$2y$12$3MuZ5OtwalpDCM1RBjpr9exnjWHiluMX/YT5/ffjZjON.wdvk1h6a', NULL, NULL, '8', NULL, NULL, '0', '2025-08-22 03:32:54', '2025-08-22 05:39:35', NULL),
(39, 'Ankur Khare', 'ankurkhare@rntu.ac.in', NULL, '$2y$12$z65nkaBtMO2/X6h4tuTOZuZz9MvPBsywMophO9oQhCf7Z6UKKvvM.', NULL, NULL, '5', NULL, NULL, '1', '2025-08-22 03:35:00', '2025-08-22 05:38:25', NULL),
(40, 'Anil Tiwari', 'aniltiwari@rntu.ac.in', NULL, '$2y$12$nM06a9AAqDdmZTcxbngrZuWun5ZQZaR6kjOI8p8Qsbex1Q6d5mFNm', 1, NULL, NULL, NULL, NULL, NULL, '2025-08-22 06:45:41', '2025-08-26 03:57:49', NULL),
(41, 'Test Admin', 'testadmin@aisect.org', NULL, '$2y$12$a0jv7EDh7PoAHIMJ54AeVe.zKyrJh/GRtUth059UWKyhoZPz5QzLy', NULL, NULL, NULL, NULL, NULL, '1', '2025-08-22 07:16:01', '2025-08-22 07:43:22', NULL),
(49, 'Test', 'test2@aisect.org', NULL, '$2y$12$..k5qxzmwZZey3TR.fPZH.EU/srrYGha/lS/YsDpaHWyvaoCjNOr6', NULL, NULL, NULL, NULL, NULL, '1', '2025-08-22 07:42:44', '2025-08-22 07:42:44', NULL),
(50, 'Rudra Verma', 'rudra@gmail.com', NULL, '$2y$12$uWB0b2FoRTt2BzZx3T8SEe/i1tdRSrYOrhfyWELaU6KxaG6Lh.PfS', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-24 09:21:04', '2025-08-24 12:01:05', NULL),
(51, 'Anu Kumari', 'anukumari@test.com', NULL, '$2y$12$8TLk8IHFeITaTGzY/lYArOZyM5cYznqAtDMrCAyJqFY9dIK5gLfCq', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-24 23:46:18', '2025-08-24 23:46:18', NULL),
(52, 'Mandeep Singh', 'mandeep@gmail.com', NULL, '$2y$12$KP5TW2P0UgdMU5xtvOIuSukFX/TEnOBDoJy.379ZLXXlEmPxCHdd2', 3, NULL, NULL, NULL, NULL, NULL, '2025-08-25 01:24:40', '2025-08-25 01:24:40', NULL),
(53, 'Binay Jadhav', 'accountant@rntu.ac.in', NULL, '$2y$12$287fHd6NSfxHWfkFsHKm7OjzXltuiWz7HftoCmtdKpk2lLvs7oGBK', 1, NULL, NULL, NULL, NULL, '1', '2025-08-25 01:33:54', '2025-08-25 10:45:19', NULL),
(54, 'Arvind Tiwari', 'arvind@cvru.ac.in', NULL, '$2y$12$8B9HiNHrM7Tr82CBIlPGHOus17WvG47EQYe.Y0z33WZT64hrGMlK2', 4, NULL, NULL, '2y12S6JHG9AHRfC8CrxyqAxHetU5oCVSU9EcZhoOWyDyH7VVXdALXrW', '1756970871', NULL, '2025-08-28 01:36:25', '2025-09-03 01:57:51', NULL),
(55, 'Abhishek Mishra', 'abhishekmishra@cvru.ac.in', NULL, '$2y$12$Z2JTnAAm2KcyGCxo7HLHJOoB4SAeEk4kSMyFOu8PTfbUJwy4pBl8q', 4, NULL, NULL, NULL, NULL, '1', '2025-08-28 10:17:48', '2025-09-03 01:57:37', NULL),
(56, 'Abhishek Shukla', 'abhishekshukla@cvru.ac.in', NULL, '$2y$12$Z2JTnAAm2KcyGCxo7HLHJOoB4SAeEk4kSMyFOu8PTfbUJwy4pBl8q', 4, NULL, NULL, NULL, NULL, '1', '2025-08-28 10:25:38', '2025-08-30 01:06:13', 54),
(60, 'Shivangi Shukla', 'shivangi@gmail.com', 'Female', '$2y$12$UjCWXD26HfgkrUnoeKgQS.heDJf0lvgVEIGjLqi6yQnk7H4f8Agoi', 4, NULL, NULL, NULL, NULL, NULL, '2025-08-28 12:12:22', '2025-09-01 07:17:15', NULL),
(61, 'Nirankush', 'nirankush@gmail.com', NULL, '$2y$12$suoM6yj.NNNRoacdokzdjOdI5G8pZuKkn3KsqAmDLslbZ4chLK0nm', 4, 7, NULL, NULL, NULL, NULL, '2025-08-28 12:44:27', '2025-08-28 12:44:27', NULL),
(62, 'Manchandani', 'mancha@gmail.com', NULL, '$2y$12$GqaqWuX7n1JBPndYoaHHruhtJ5l2ohelzHA5CS4IACsocUoxWhc5m', 4, 7, NULL, NULL, NULL, NULL, '2025-08-28 12:44:50', '2025-08-28 12:44:50', NULL),
(63, 'Shantanu Sharma', 'shantanusharma@cvru.ac.in', NULL, '$2y$12$DjhbN4nYYmjIGFfNrhwmUeXMBUAT8T2KjeHPWkE3lIYFuMd4EJieS', 4, NULL, '17', NULL, NULL, '1', '2025-08-28 12:52:54', '2025-08-29 03:25:10', NULL),
(64, 'Sangeeta Soni', 'sangeetasoni@cvru.ac.in', NULL, '$2y$12$9XjD57VCGneGyMcVHJmrEeIXIcCRfAht6UMuKjpjm/lsU7rC.NocW', 4, NULL, '28', NULL, NULL, '1', '2025-08-28 12:54:32', '2025-08-29 02:15:38', NULL),
(65, 'Ankush Narang', 'ankushnarang@gmail.com', 'Male', '$2y$12$dxeFrswH1ghgW8FaeqY8x./FSPNXzVfa/SMAxIpi2rIVtq.P7ON2W', 4, NULL, NULL, NULL, NULL, NULL, '2025-09-02 05:40:36', '2025-09-02 05:41:12', NULL),
(66, 'Jiya Raj', 'jiyaraj@gmail.com', 'Female', '$2y$12$vTO4aze4djF7IpfMm9uVUOo9wleihxRQHmn/LstPLIg/mhwDdrT8y', 4, NULL, NULL, NULL, NULL, NULL, '2025-09-02 10:01:25', '2025-09-02 11:41:40', NULL),
(67, 'Test2', 'test2@gmail.com', 'Female', '$2y$12$Rnr9bEkWrp3tuYkvrrxet.4BtP/kXPuB6RoZGCEwPzVnqSTjlBqhm', 4, NULL, NULL, NULL, NULL, NULL, '2025-09-03 03:15:44', '2025-09-03 03:15:44', NULL),
(68, 'Test', 'test@gmail.com', 'Male', '$2y$12$/mBsNX560Bu5WLB9XM3iXO1iQSn39xuzFCzu0utuNwFE.HUna.tiC', 4, NULL, NULL, NULL, NULL, NULL, '2025-09-03 03:21:17', '2025-09-03 03:21:17', NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accessory_checkout_logs`
--
ALTER TABLE `accessory_checkout_logs`
  ADD CONSTRAINT `accessory_checkout_logs_accessory_head_id_foreign` FOREIGN KEY (`accessory_head_id`) REFERENCES `accessory` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `accessory_checkout_logs_checkout_id_foreign` FOREIGN KEY (`checkout_id`) REFERENCES `checkouts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `beds`
--
ALTER TABLE `beds`
  ADD CONSTRAINT `beds_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `buildings`
--
ALTER TABLE `buildings`
  ADD CONSTRAINT `buildings_university_id_foreign` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `checkouts`
--
ALTER TABLE `checkouts`
  ADD CONSTRAINT `checkouts_resident_id_foreign` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `feedbacks_resident_id_foreign` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fees`
--
ALTER TABLE `fees`
  ADD CONSTRAINT `fees_fee_head_id_foreign` FOREIGN KEY (`fee_head_id`) REFERENCES `fee_heads` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fee_exceptions`
--
ALTER TABLE `fee_exceptions`
  ADD CONSTRAINT `fee_exceptions_guest_id_foreign` FOREIGN KEY (`guest_id`) REFERENCES `guests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `grievances`
--
ALTER TABLE `grievances`
  ADD CONSTRAINT `grievances_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `grievances_resident_id_foreign` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `grievances_responded_by_foreign` FOREIGN KEY (`responded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `grievance_responses`
--
ALTER TABLE `grievance_responses`
  ADD CONSTRAINT `grievance_responses_grievance_id_foreign` FOREIGN KEY (`grievance_id`) REFERENCES `grievances` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `grievance_responses_responded_by_foreign` FOREIGN KEY (`responded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leave_notifications`
--
ALTER TABLE `leave_notifications`
  ADD CONSTRAINT `leave_notifications_leave_request_id_foreign` FOREIGN KEY (`leave_request_id`) REFERENCES `leave_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leave_notifications_resident_id_foreign` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD CONSTRAINT `leave_requests_resident_id_foreign` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messes`
--
ALTER TABLE `messes`
  ADD CONSTRAINT `messes_building_id_foreign` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `messes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `messes_guest_id_foreign` FOREIGN KEY (`guest_id`) REFERENCES `guests` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `messes_resident_id_foreign` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messes_university_id_foreign` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `messes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_fee_head_id_foreign` FOREIGN KEY (`fee_head_id`) REFERENCES `fees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_guest_id_foreign` FOREIGN KEY (`guest_id`) REFERENCES `guests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_resident_id_foreign` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_student_accessory_id_foreign` FOREIGN KEY (`student_accessory_id`) REFERENCES `student_accessory` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payment_notifications`
--
ALTER TABLE `payment_notifications`
  ADD CONSTRAINT `payment_notifications_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_notifications_resident_id_foreign` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `residents`
--
ALTER TABLE `residents`
  ADD CONSTRAINT `residents_bed_id_foreign` FOREIGN KEY (`bed_id`) REFERENCES `beds` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `residents_guest_id_foreign` FOREIGN KEY (`guest_id`) REFERENCES `guests` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `residents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_building_id_foreign` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `room_change_messages`
--
ALTER TABLE `room_change_messages`
  ADD CONSTRAINT `room_change_messages_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `room_change_messages_room_change_request_id_foreign` FOREIGN KEY (`room_change_request_id`) REFERENCES `room_change_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `room_change_requests`
--
ALTER TABLE `room_change_requests`
  ADD CONSTRAINT `room_change_requests_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `room_change_requests_resident_id_foreign` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_fee_head_id_foreign` FOREIGN KEY (`fee_head_id`) REFERENCES `fees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscriptions_resident_id_foreign` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_building_id_foreign` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_university_id_foreign` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
