-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2023 at 10:52 AM
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
-- Database: `dbsa`
--

-- --------------------------------------------------------

--
-- Table structure for table `auth_activation_attempts`
--

CREATE TABLE `auth_activation_attempts` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_groups`
--

CREATE TABLE `auth_groups` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `auth_groups`
--

INSERT INTO `auth_groups` (`id`, `name`, `description`) VALUES
(40, 'superuser', 'Site Administrators with god-like powers.'),
(41, 'owner', 'Pemilik.'),
(42, 'admin', 'Admin.'),
(43, 'marketing', 'Divisi Marketing.'),
(44, 'design', 'Divisi Design.'),
(45, 'production', 'Divisi Produksi.'),
(46, 'client pusat', 'Client Pusat.'),
(47, 'client cabang', 'Client Cabang.'),
(48, 'guests', 'Unauthorized users.');

-- --------------------------------------------------------

--
-- Table structure for table `auth_groups_permissions`
--

CREATE TABLE `auth_groups_permissions` (
  `group_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `permission_id` int(11) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `auth_groups_permissions`
--

INSERT INTO `auth_groups_permissions` (`group_id`, `permission_id`) VALUES
(40, 57),
(40, 58),
(40, 59),
(40, 60),
(40, 61),
(40, 62),
(40, 63),
(40, 64),
(40, 65),
(40, 66),
(40, 67),
(40, 68),
(40, 69),
(40, 70),
(40, 71),
(40, 72),
(40, 73),
(41, 60),
(41, 61),
(41, 62),
(41, 63),
(41, 64),
(41, 65),
(41, 66),
(41, 67),
(41, 68),
(41, 69),
(41, 70),
(41, 71),
(41, 72),
(41, 73),
(42, 60),
(42, 61),
(42, 62),
(42, 63),
(42, 64),
(42, 65),
(42, 66),
(42, 67),
(42, 68),
(42, 69),
(42, 70),
(43, 64),
(43, 65),
(43, 66),
(43, 67),
(43, 68),
(43, 69),
(43, 70),
(43, 71),
(44, 68),
(44, 72),
(45, 68),
(45, 73),
(46, 57),
(46, 58),
(47, 57),
(47, 59);

-- --------------------------------------------------------

--
-- Table structure for table `auth_groups_users`
--

CREATE TABLE `auth_groups_users` (
  `group_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `user_id` int(11) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `auth_groups_users`
--

INSERT INTO `auth_groups_users` (`group_id`, `user_id`) VALUES
(40, 28),
(40, 51),
(41, 43),
(41, 49),
(42, 29),
(42, 33),
(42, 45),
(44, 50),
(46, 34),
(46, 44),
(46, 47),
(47, 46),
(47, 48);

-- --------------------------------------------------------

--
-- Table structure for table `auth_logins`
--

CREATE TABLE `auth_logins` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `auth_logins`
--

INSERT INTO `auth_logins` (`id`, `ip_address`, `email`, `user_id`, `date`, `success`) VALUES
(1, '127.0.0.1', 'rizal@gmail.com', 1, '2023-10-16 07:32:19', 1),
(2, '127.0.0.1', 'rizal@gmail.com', 1, '2023-10-16 09:04:59', 1),
(3, '127.0.0.1', 'rizal@gmail.com', 1, '2023-10-16 09:08:21', 1),
(4, '127.0.0.1', 'rizal@gmail.com', 1, '2023-10-16 10:16:49', 1),
(5, '127.0.0.1', 'rizal@gmail.com', 1, '2023-10-16 12:12:46', 1),
(6, '127.0.0.1', 'rizal@gmail.com', 1, '2023-10-16 12:22:50', 1),
(7, '127.0.0.1', 'rizal@gmail.com', 1, '2023-10-16 12:38:01', 1),
(8, '127.0.0.1', 'rizal@gmail.com', 1, '2023-10-16 12:55:02', 1),
(9, '127.0.0.1', 'rizal@gmail.com', 1, '2023-10-17 15:01:33', 1),
(10, '127.0.0.1', 'rizal@gmail.com', 1, '2023-10-17 16:39:30', 1),
(11, '127.0.0.1', 'rizal@gmail.com', 1, '2023-10-17 17:29:27', 1),
(12, '127.0.0.1', 'rizal@gmail.com', 1, '2023-10-17 17:45:50', 1),
(13, '127.0.0.1', 'rizal', NULL, '2023-10-17 17:56:03', 0),
(14, '127.0.0.1', 'rizal@gmail.com', 1, '2023-10-17 17:56:08', 1),
(15, '127.0.0.1', 'rizal@gmail.com', 1, '2023-10-17 17:57:37', 1),
(16, '127.0.0.1', 'rizal@gmail.com', 1, '2023-10-18 11:32:37', 1),
(17, '127.0.0.1', 'rizal', NULL, '2023-10-18 12:43:07', 0),
(18, '127.0.0.1', 'rizal@gmail.com', 1, '2023-10-18 12:43:17', 1),
(19, '127.0.0.1', 'rizal@gmail.com', 1, '2023-10-20 06:43:06', 1),
(20, '127.0.0.1', 'rizal@gmail.com', 1, '2023-10-20 07:19:23', 1),
(21, '127.0.0.1', 'rizal@gmail.com', 1, '2023-10-20 08:05:47', 1),
(22, '127.0.0.1', 'rizal', NULL, '2023-10-20 08:18:38', 0),
(23, '127.0.0.1', 'rizal@gmail.com', 1, '2023-10-20 08:18:45', 1),
(24, '127.0.0.1', 'jordi@gmail.com', 2, '2023-10-20 09:10:35', 1),
(25, '127.0.0.1', 'rizal', NULL, '2023-10-20 09:18:51', 0),
(26, '127.0.0.1', 'rizal@gmail.com', 1, '2023-10-20 09:18:57', 1),
(27, '127.0.0.1', 'rizal@gmail.com', 1, '2023-10-20 11:35:42', 1),
(28, '127.0.0.1', 'rizal@gmail.com', 1, '2023-10-20 16:40:45', 1),
(29, '127.0.0.1', 'rizal@gmail.com', 1, '2023-10-21 04:40:27', 1),
(30, '127.0.0.1', 'rizal@gmail.com', 1, '2023-10-21 17:47:34', 1),
(31, '127.0.0.1', 'rizal@gmail.com', 1, '2023-10-22 07:41:27', 1),
(32, '127.0.0.1', 'rizal', NULL, '2023-10-24 12:59:09', 0),
(33, '127.0.0.1', 'rizal@gmail.com', 1, '2023-10-24 12:59:18', 1),
(34, '127.0.0.1', 'rizal', NULL, '2023-10-25 06:51:35', 0),
(35, '127.0.0.1', 'rizal', NULL, '2023-10-25 06:51:41', 0),
(36, '127.0.0.1', 'rizal', NULL, '2023-10-25 06:51:48', 0),
(37, '127.0.0.1', 'rizal', NULL, '2023-10-25 06:51:54', 0),
(38, '127.0.0.1', 'rizalramadhan@gmail.com', 1, '2023-10-25 06:52:05', 1),
(39, '127.0.0.1', 'rizal', NULL, '2023-10-25 14:23:25', 0),
(40, '127.0.0.1', 'rizalramadhan@gmail.com', 1, '2023-10-25 14:23:34', 1),
(41, '127.0.0.1', 'rizalramadhan@gmail.com', 1, '2023-10-25 17:35:45', 1),
(42, '127.0.0.1', 'rizalramadhan@gmail.com', 1, '2023-10-26 10:45:52', 1),
(43, '127.0.0.1', 'rizalramadhan@gmail.com', 1, '2023-10-27 07:25:57', 1),
(44, '127.0.0.1', 'admin', NULL, '2023-10-27 08:34:10', 0),
(45, '127.0.0.1', 'admin', NULL, '2023-10-27 08:34:19', 0),
(46, '127.0.0.1', 'admin', NULL, '2023-10-27 08:34:25', 0),
(47, '127.0.0.1', 'rizalramadhan@gmail.com', 1, '2023-10-27 08:34:40', 1),
(48, '127.0.0.1', 'rizalramadhan@gmail.com', 1, '2023-10-27 08:38:48', 1),
(49, '127.0.0.1', 'rizalramadhan@gmail.com', 1, '2023-10-27 11:53:09', 1),
(50, '127.0.0.1', 'rizal', NULL, '2023-10-28 05:00:05', 0),
(51, '127.0.0.1', 'rizalramadhan@gmail.com', 1, '2023-10-28 05:00:17', 1),
(52, '127.0.0.1', 'rizalramadhan@gmail.com', 1, '2023-10-28 16:13:06', 1),
(53, '127.0.0.1', 'rizalramadhan@gmail.com', 1, '2023-10-29 07:18:54', 1),
(54, '127.0.0.1', 'rizalramadhan@gmail.com', 1, '2023-10-29 23:58:45', 1),
(55, '127.0.0.1', 'rizal', NULL, '2023-10-30 05:31:10', 0),
(56, '127.0.0.1', 'rizalramadhan@gmail.com', 1, '2023-10-30 05:31:21', 1),
(57, '127.0.0.1', 'rizalramadhan@gmail.com', 1, '2023-10-30 09:30:10', 1),
(58, '127.0.0.1', 'admin', NULL, '2023-10-31 00:15:49', 0),
(59, '127.0.0.1', 'rizalramadhan@gmail.com', 1, '2023-10-31 00:15:56', 1),
(60, '127.0.0.1', 'rizalramadhan@gmail.com', 1, '2023-10-31 08:26:01', 1),
(61, '127.0.0.1', 'rizalramadhan@gmail.com', 1, '2023-10-31 13:33:48', 1),
(62, '127.0.0.1', 'admin', NULL, '2023-11-01 03:21:47', 0),
(63, '127.0.0.1', 'rizalramadhan@gmail.com', 1, '2023-11-01 03:21:54', 1),
(64, '127.0.0.1', 'rizalramadhan@gmail.com', 1, '2023-11-01 13:21:11', 1),
(65, '127.0.0.1', 'rizalramadhan@gmail.com', 1, '2023-11-02 06:17:37', 1),
(66, '127.0.0.1', 'rizalramadhan@gmail.com', 1, '2023-11-02 13:26:47', 1),
(67, '127.0.0.1', 'admin', NULL, '2023-11-02 20:33:38', 0),
(68, '127.0.0.1', 'joko@gmail.com', 4, '2023-11-02 20:33:52', 1),
(69, '127.0.0.1', 'admin', NULL, '2023-11-02 20:34:07', 0),
(70, '127.0.0.1', 'rizalramadhan@gmail.com', 1, '2023-11-02 20:34:13', 1),
(71, '127.0.0.1', 'admin', NULL, '2023-11-03 06:54:11', 0),
(72, '127.0.0.1', 'rizalramadhan@gmail.com', 1, '2023-11-03 06:54:17', 1),
(73, '127.0.0.1', 'admin', NULL, '2023-11-04 08:40:21', 0),
(74, '127.0.0.1', 'rizalramadhan@gmail.com', 1, '2023-11-04 08:40:29', 1),
(75, '127.0.0.1', 'rizalramadhan@gmail.com', 1, '2023-11-04 15:00:01', 1),
(76, '127.0.0.1', 'rizalramadhan@gmail.com', 1, '2023-11-05 06:37:54', 1),
(77, '127.0.0.1', 'rizalramadhan@gmail.com', 1, '2023-11-06 13:42:58', 1),
(78, '127.0.0.1', 'rizal@gmail.com', 6, '2023-11-06 15:00:58', 1),
(79, '127.0.0.1', 'rizal', NULL, '2023-11-06 15:01:21', 0),
(80, '127.0.0.1', 'rizal', NULL, '2023-11-06 15:01:25', 0),
(81, '127.0.0.1', 'rizal', NULL, '2023-11-06 15:01:31', 0),
(82, '127.0.0.1', 'rizal@gmai', NULL, '2023-11-06 15:01:44', 0),
(83, '127.0.0.1', 'rizal@gmail.com', NULL, '2023-11-06 15:01:54', 0),
(84, '127.0.0.1', 'rizal@gmail.com', 7, '2023-11-06 15:08:12', 1),
(85, '127.0.0.1', 'rizal@gmail.com', 7, '2023-11-07 04:09:45', 1),
(86, '127.0.0.1', 'rizal@gmail.com', 7, '2023-11-07 10:35:36', 1),
(87, '127.0.0.1', 'rizal@gmail.com', 7, '2023-11-07 14:15:49', 1),
(88, '127.0.0.1', 'admin', NULL, '2023-11-08 14:21:08', 0),
(89, '127.0.0.1', 'admin', NULL, '2023-11-08 14:21:13', 0),
(90, '127.0.0.1', 'rizal@gmail.com', 7, '2023-11-08 14:21:21', 1),
(91, '127.0.0.1', 'rizal@gmail.com', 7, '2023-11-08 18:15:36', 1),
(92, '127.0.0.1', 'rizal@gmail.com', 7, '2023-11-09 05:20:05', 1),
(93, '127.0.0.1', 'rizal@gmail.com', 7, '2023-11-09 12:58:10', 1),
(94, '127.0.0.1', 'rizal@gmail.com', 7, '2023-11-09 17:07:42', 1),
(95, '127.0.0.1', 'admin', NULL, '2023-11-09 20:58:34', 0),
(96, '127.0.0.1', 'rizal@gmail.com', 7, '2023-11-09 20:58:44', 1),
(97, '127.0.0.1', 'rizal@gmail.com', 7, '2023-11-10 07:21:25', 1),
(98, '127.0.0.1', 'uzumaki@gmail.com', 20, '2023-11-10 08:49:30', 1),
(99, '127.0.0.1', 'uzumaki@gmail.com', 20, '2023-11-10 08:51:25', 1),
(100, '127.0.0.1', 'rizal@gmail.com', 7, '2023-11-10 09:12:28', 1),
(101, '127.0.0.1', 'uzumaki@gmail.com', 20, '2023-11-10 12:23:27', 1),
(102, '127.0.0.1', 'rizal@gmail.com', 7, '2023-11-10 12:28:11', 1),
(103, '127.0.0.1', 'shisui@gmail.com', 24, '2023-11-10 12:34:55', 1),
(104, '127.0.0.1', 'uzumaki@gmail.com', 20, '2023-11-10 12:35:27', 1),
(105, '127.0.0.1', 'shisui@gmail.com', 24, '2023-11-10 12:36:41', 1),
(106, '127.0.0.1', 'uzumaki@gmail.com', 20, '2023-11-10 12:42:13', 1),
(107, '127.0.0.1', 'rizal@gmail.com', 7, '2023-11-10 12:43:00', 1),
(108, '127.0.0.1', 'shis', NULL, '2023-11-10 12:55:17', 0),
(109, '127.0.0.1', 'shisui@gmail.com', 24, '2023-11-10 12:55:27', 1),
(110, '127.0.0.1', 'uzumaki@gmail.com', 20, '2023-11-10 13:00:56', 1),
(111, '127.0.0.1', 'rizal@gmail.com', 7, '2023-11-10 13:08:45', 1),
(112, '127.0.0.1', 'shisui@gmail.com', 24, '2023-11-10 13:11:22', 1),
(113, '127.0.0.1', 'uzumaki@gmail.com', 20, '2023-11-10 13:12:06', 1),
(114, '127.0.0.1', 'rizal@gmail.com', 7, '2023-11-10 13:33:02', 1),
(115, '127.0.0.1', 'shisui@gmail.com', 24, '2023-11-10 13:33:27', 1),
(116, '127.0.0.1', 'uzumaki@gmail.com', 20, '2023-11-10 15:05:15', 1),
(117, '127.0.0.1', 'rizal@gmail.com', 7, '2023-11-10 15:06:08', 1),
(118, '127.0.0.1', 'shisui@gmail.com', 24, '2023-11-10 15:22:08', 1),
(119, '127.0.0.1', 'uzumaki@gmail.com', 20, '2023-11-10 15:22:41', 1),
(120, '127.0.0.1', 'rizal@gmail.com', 7, '2023-11-10 15:24:18', 1),
(121, '127.0.0.1', 'sarutobi@gmail.com', 26, '2023-11-10 15:28:39', 1),
(122, '127.0.0.1', 'hiruzen@gmail.com', 25, '2023-11-10 15:29:07', 1),
(123, '127.0.0.1', 'rizal@gmail.com', 7, '2023-11-10 15:39:54', 1),
(124, '127.0.0.1', 'rizal@gmail.com', 7, '2023-11-10 15:44:30', 1),
(125, '127.0.0.1', 'uzumaki@gmail.com', 20, '2023-11-10 15:45:01', 1),
(126, '127.0.0.1', 'sarutobi@gmail.com', 26, '2023-11-10 15:46:25', 1),
(127, '127.0.0.1', 'rizal@gmail.com', 7, '2023-11-10 16:00:42', 1),
(128, '127.0.0.1', 'uzumaki@gmail.com', 20, '2023-11-10 16:04:08', 1),
(129, '127.0.0.1', 'shisui@gmail.com', 24, '2023-11-10 16:05:09', 1),
(130, '127.0.0.1', 'uzumaki@gmail.com', 20, '2023-11-10 16:06:49', 1),
(131, '127.0.0.1', 'uzumaki@gmail.com', 20, '2023-11-10 16:08:59', 1),
(132, '127.0.0.1', 'rizal@gmail.com', 7, '2023-11-11 02:28:28', 1),
(133, '127.0.0.1', 'uzumaki@gmail.com', 20, '2023-11-11 02:32:23', 1),
(134, '127.0.0.1', 'shisui', NULL, '2023-11-11 02:38:25', 0),
(135, '127.0.0.1', 'shisui', NULL, '2023-11-11 02:38:29', 0),
(136, '127.0.0.1', 'shisui', NULL, '2023-11-11 02:38:34', 0),
(137, '127.0.0.1', 'sarutobi@gmail.com', 26, '2023-11-11 02:38:53', 1),
(138, '127.0.0.1', 'hiruzen@gmail.com', 25, '2023-11-11 02:39:06', 1),
(139, '127.0.0.1', 'rizal@gmail.com', 7, '2023-11-11 02:46:42', 1),
(140, '127.0.0.1', 'rizal@gmail.com', 7, '2023-11-11 16:00:04', 1),
(141, '127.0.0.1', 'uzumaki', NULL, '2023-11-11 16:01:10', 0),
(142, '127.0.0.1', 'uzumaki@gmail.com', 20, '2023-11-11 16:01:15', 1),
(143, '127.0.0.1', 'rizal@gmail.com', 7, '2023-11-11 16:02:51', 1),
(144, '127.0.0.1', 'rizal@gmail.com', 7, '2023-11-16 12:41:13', 1),
(145, '127.0.0.1', 'rizal', NULL, '2023-11-17 05:16:14', 0),
(146, '127.0.0.1', 'rizal@gmail.com', 7, '2023-11-17 05:16:22', 1),
(147, '127.0.0.1', 'rizal@gmail.com', 28, '2023-11-17 05:18:17', 1),
(148, '127.0.0.1', 'rizal@gmail.com', 28, '2023-11-17 06:09:14', 1),
(149, '127.0.0.1', 'admin@dpsa.com', 33, '2023-11-17 07:22:42', 1),
(150, '127.0.0.1', 'rizal@gmail.com', 28, '2023-11-17 07:29:35', 1),
(151, '127.0.0.1', 'rizal@gmail.com', 28, '2023-11-17 13:56:37', 1),
(152, '127.0.0.1', 'dismas', NULL, '2023-11-17 14:37:05', 0),
(153, '127.0.0.1', 'dismas', NULL, '2023-11-17 14:37:09', 0),
(154, '127.0.0.1', 'banar@gmail.com', 29, '2023-11-17 14:37:16', 1),
(155, '127.0.0.1', 'rizal@gmail.com', 28, '2023-11-18 11:44:53', 1),
(156, '127.0.0.1', 'banar@gmail.com', 29, '2023-11-18 11:46:46', 1),
(157, '127.0.0.1', 'rizal@gmail.com', 28, '2023-11-18 18:20:12', 1),
(158, '127.0.0.1', 'rizal@gmail.com', 28, '2023-11-19 09:12:36', 1),
(159, '127.0.0.1', 'rizal@gmail.com', 28, '2023-11-19 16:49:41', 1),
(160, '127.0.0.1', 'rizal@gmail.com', 28, '2023-11-20 00:07:35', 1),
(161, '127.0.0.1', 'rizal@gmail.com', 28, '2023-11-21 13:55:13', 1),
(162, '127.0.0.1', 'rizal@gmail.com', 28, '2023-11-22 10:13:01', 1),
(163, '127.0.0.1', 'rizal@gmail.com', 28, '2023-11-22 14:27:22', 1),
(164, '127.0.0.1', 'rizal@gmail.com', 28, '2023-11-22 17:08:14', 1),
(165, '127.0.0.1', 'rizal@gmail.com', 28, '2023-11-23 05:10:12', 1),
(166, '127.0.0.1', 'rizal@gmail.com', 28, '2023-11-24 06:17:58', 1),
(167, '127.0.0.1', 'rizal@gmail.com', 28, '2023-11-24 15:36:54', 1),
(168, '127.0.0.1', 'dismas', NULL, '2023-11-24 15:43:21', 0),
(169, '127.0.0.1', 'banar@gmail.com', 29, '2023-11-24 15:43:30', 1),
(170, '127.0.0.1', 'barcelona', NULL, '2023-11-24 15:45:29', 0),
(171, '127.0.0.1', 'barcelona', NULL, '2023-11-24 15:45:35', 0),
(172, '127.0.0.1', 'barca@gmail.com', 41, '2023-11-24 15:46:16', 1),
(173, '127.0.0.1', 'rizal@gmail.com', 28, '2023-11-27 02:56:58', 1),
(174, '127.0.0.1', 'herbal', NULL, '2023-11-27 03:41:30', 0),
(175, '127.0.0.1', 'dismas', NULL, '2023-11-27 03:41:51', 0),
(176, '127.0.0.1', 'dismas', NULL, '2023-11-27 03:41:55', 0),
(177, '127.0.0.1', 'dismasbp', NULL, '2023-11-27 03:42:06', 0),
(178, '127.0.0.1', 'dismas', NULL, '2023-11-27 03:42:13', 0),
(179, '127.0.0.1', 'dismas pamungkas', NULL, '2023-11-27 03:42:23', 0),
(180, '127.0.0.1', 'rizal@gmail.com', 28, '2023-11-27 03:42:32', 1),
(181, '127.0.0.1', 'herbalife@gmail.com', 34, '2023-11-27 03:44:48', 1),
(182, '127.0.0.1', 'barca@gmail.com', 41, '2023-11-27 03:46:01', 1),
(183, '127.0.0.1', 'rizal@gmail.com', 28, '2023-11-27 03:53:26', 1),
(184, '127.0.0.1', 'pku@gmail.com', 38, '2023-11-27 03:55:48', 1),
(185, '127.0.0.1', 'barca@gmail.com', 41, '2023-11-27 03:59:00', 1),
(186, '127.0.0.1', 'rizal@gmail.com', 28, '2023-11-27 04:03:13', 1),
(187, '127.0.0.1', 'barca@gmail.com', 41, '2023-11-27 04:05:49', 1),
(188, '127.0.0.1', 'rizal@gmail.com', 28, '2023-11-27 04:09:00', 1),
(189, '127.0.0.1', 'herbalife@gmail.com', 34, '2023-11-27 04:11:52', 1),
(190, '127.0.0.1', 'pku@gmail.com', 38, '2023-11-27 04:18:10', 1),
(191, '127.0.0.1', 'admin@dpsa.com', 33, '2023-11-27 04:30:43', 1),
(192, '127.0.0.1', 'rizal@gmail.com', 28, '2023-11-27 04:34:53', 1),
(193, '127.0.0.1', 'herbalife@gmail.com', 34, '2023-11-27 04:54:11', 1),
(194, '127.0.0.1', 'rizal@gmail.com', 28, '2023-11-27 04:54:32', 1),
(195, '127.0.0.1', 'barca@gmail.com', 41, '2023-11-27 04:54:57', 1),
(196, '127.0.0.1', 'rizal@gmail.com', 28, '2023-11-27 04:55:32', 1),
(197, '127.0.0.1', 'dismas', NULL, '2023-11-27 05:07:16', 0),
(198, '127.0.0.1', 'herbalife@gmail.com', 34, '2023-11-27 05:07:28', 1),
(199, '127.0.0.1', 'rizal@gmail.com', 28, '2023-11-27 05:13:58', 1),
(200, '127.0.0.1', 'dismas', NULL, '2023-11-27 06:17:09', 0),
(201, '127.0.0.1', 'banar@gmail.com', 29, '2023-11-27 06:17:16', 1),
(202, '127.0.0.1', 'pku@gmail.com', 38, '2023-11-27 06:21:03', 1),
(203, '127.0.0.1', 'rizal@gmail.com', 28, '2023-11-27 06:22:22', 1),
(204, '127.0.0.1', 'herminasleman@gmail.com', 36, '2023-11-27 06:22:51', 1),
(205, '127.0.0.1', 'pku jogja', NULL, '2023-11-27 06:23:26', 0),
(206, '127.0.0.1', 'rizal@gmail.com', 28, '2023-11-27 06:23:36', 1),
(207, '127.0.0.1', 'pkubantul@gmail.com', 40, '2023-11-27 06:24:02', 1),
(208, '127.0.0.1', 'pku@gmail.com', 38, '2023-11-27 06:24:22', 1),
(209, '127.0.0.1', 'rizal@gmail.com', 28, '2023-11-27 06:24:56', 1),
(210, '127.0.0.1', 'pkubantul@gmail.com', 40, '2023-11-27 06:25:21', 1),
(211, '127.0.0.1', 'herbalife@gmail.com', 34, '2023-11-27 06:26:10', 1),
(212, '127.0.0.1', 'barca@gmail.com', 41, '2023-11-27 06:26:27', 1),
(213, '127.0.0.1', 'barca@gmail.com', 41, '2023-11-27 06:28:12', 1),
(214, '127.0.0.1', 'herbalife@gmail.com', 34, '2023-11-27 06:28:42', 1),
(215, '127.0.0.1', 'rizal', NULL, '2023-11-27 06:29:48', 0),
(216, '127.0.0.1', 'dismas', NULL, '2023-11-27 06:30:03', 0),
(217, '127.0.0.1', 'banar@gmail.com', 29, '2023-11-27 06:30:31', 1),
(218, '127.0.0.1', 'rizal@gmail.com', 28, '2023-11-27 09:46:42', 1),
(219, '127.0.0.1', 'pkubantul@gmail.com', 40, '2023-11-27 10:10:07', 1),
(220, '127.0.0.1', 'hermina jogja', NULL, '2023-11-27 10:10:43', 0),
(221, '127.0.0.1', 'hermina@gmail.com', 35, '2023-11-27 10:10:52', 1),
(222, '127.0.0.1', 'rizal@gmail.com', 28, '2023-11-27 10:13:23', 1),
(223, '127.0.0.1', 'rizal@gmail.com', 28, '2023-11-28 10:45:50', 1),
(224, '127.0.0.1', 'rizal@gmail.com', 28, '2023-11-28 13:42:52', 1),
(225, '127.0.0.1', 'rizal@gmail.com', 28, '2023-11-29 03:16:10', 1),
(226, '127.0.0.1', 'rizal@gmail.com', 28, '2023-11-29 06:26:39', 1),
(227, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-01 06:42:44', 1),
(228, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-02 12:04:16', 1),
(229, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-02 22:58:32', 1),
(230, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-03 04:03:07', 1),
(231, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-03 08:37:47', 1),
(232, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-03 17:01:42', 1),
(233, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-04 04:10:38', 1),
(234, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-04 09:12:11', 1),
(235, '127.0.0.1', 'Erik@gmail.com', 44, '2023-12-04 09:12:28', 1),
(236, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-04 09:45:22', 1),
(237, '127.0.0.1', 'Erik@gmail.com', 44, '2023-12-04 09:47:21', 1),
(238, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-04 09:54:23', 1),
(239, '127.0.0.1', 'Erik@gmail.com', 44, '2023-12-04 09:55:39', 1),
(240, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-04 10:02:22', 1),
(241, '127.0.0.1', 'Erik@gmail.com', 44, '2023-12-04 10:02:43', 1),
(242, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-04 10:03:07', 1),
(243, '127.0.0.1', 'Erik@gmail.com', 44, '2023-12-04 10:09:56', 1),
(244, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-04 10:15:42', 1),
(245, '127.0.0.1', 'ridwan@gmail.com', 46, '2023-12-04 10:16:31', 1),
(246, '127.0.0.1', 'Erik@gmail.com', 44, '2023-12-04 10:20:21', 1),
(247, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-04 11:03:19', 1),
(248, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-04 21:03:52', 1),
(249, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-05 12:28:13', 1),
(250, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-05 16:44:31', 1),
(251, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-07 01:23:44', 1),
(252, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-07 23:33:55', 1),
(253, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-08 05:31:41', 1),
(254, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-08 05:33:31', 1),
(255, '127.0.0.1', 'narji', NULL, '2023-12-08 06:05:29', 0),
(256, '127.0.0.1', 'narji', NULL, '2023-12-08 06:05:36', 0),
(257, '127.0.0.1', 'narji@gmail.com', 47, '2023-12-08 06:05:42', 1),
(258, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-08 06:06:06', 1),
(259, '127.0.0.1', 'narji@gmail.com', 47, '2023-12-08 06:06:49', 1),
(260, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-08 06:21:36', 1),
(261, '127.0.0.1', 'rizal', NULL, '2023-12-08 07:36:30', 0),
(262, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-08 07:36:35', 1),
(263, '127.0.0.1', 'pku', NULL, '2023-12-08 07:37:00', 0),
(264, '127.0.0.1', 'pku', NULL, '2023-12-08 07:37:04', 0),
(265, '127.0.0.1', 'PKU', NULL, '2023-12-08 07:37:13', 0),
(266, '127.0.0.1', 'Harjo', NULL, '2023-12-08 07:37:27', 0),
(267, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-08 07:37:52', 1),
(268, '127.0.0.1', 'narji@gmail.com', 47, '2023-12-08 07:38:31', 1),
(269, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-08 07:55:50', 1),
(270, '127.0.0.1', 'narji@gmail.com', 47, '2023-12-08 07:58:12', 1),
(271, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-08 08:04:54', 1),
(272, '127.0.0.1', 'herbalife', 34, '2023-12-08 08:05:21', 0),
(273, '127.0.0.1', 'herbalife', NULL, '2023-12-08 08:05:27', 0),
(274, '127.0.0.1', 'herbalife', 34, '2023-12-08 08:05:34', 0),
(275, '127.0.0.1', 'herbalife', NULL, '2023-12-08 08:05:38', 0),
(276, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-08 08:05:47', 1),
(277, '127.0.0.1', 'Erik@gmail.com', 44, '2023-12-08 08:06:05', 1),
(278, '127.0.0.1', 'wendi@gmail.com', 48, '2023-12-08 08:06:39', 1),
(279, '127.0.0.1', 'narji', NULL, '2023-12-08 08:40:00', 0),
(280, '127.0.0.1', 'narji', NULL, '2023-12-08 08:40:07', 0),
(281, '127.0.0.1', 'narji@gmail.com', 47, '2023-12-08 08:40:12', 1),
(282, '127.0.0.1', 'Erik@gmail.com', 44, '2023-12-08 08:42:31', 1),
(283, '127.0.0.1', 'rizal', NULL, '2023-12-08 08:43:11', 0),
(284, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-08 08:43:15', 1),
(285, '127.0.0.1', 'Erik@gmail.com', 44, '2023-12-08 08:44:06', 1),
(286, '127.0.0.1', 'narji', NULL, '2023-12-08 08:45:03', 0),
(287, '127.0.0.1', 'narji@gmail.com', 47, '2023-12-08 08:45:07', 1),
(288, '127.0.0.1', 'rizal', NULL, '2023-12-08 08:45:38', 0),
(289, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-08 08:45:48', 1),
(290, '127.0.0.1', 'narji', NULL, '2023-12-08 08:46:36', 0),
(291, '127.0.0.1', 'narji@gmail.com', 47, '2023-12-08 08:46:40', 1),
(292, '127.0.0.1', 'Erik@gmail.com', 44, '2023-12-08 08:47:28', 1),
(293, '127.0.0.1', 'rizal', NULL, '2023-12-08 08:47:53', 0),
(294, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-08 08:48:02', 1),
(295, '127.0.0.1', 'Erik@gmail.com', 44, '2023-12-08 08:49:13', 1),
(296, '127.0.0.1', 'narji@gmail.com', 47, '2023-12-08 08:49:59', 1),
(297, '127.0.0.1', 'Erik@gmail.com', 44, '2023-12-08 08:58:08', 1),
(298, '127.0.0.1', 'rizal', NULL, '2023-12-08 08:58:44', 0),
(299, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-08 08:58:50', 1),
(300, '127.0.0.1', 'Erik@gmail.com', 44, '2023-12-08 08:59:29', 1),
(301, '127.0.0.1', 'narji', NULL, '2023-12-08 08:59:46', 0),
(302, '127.0.0.1', 'narji@gmail.com', 47, '2023-12-08 08:59:53', 1),
(303, '127.0.0.1', 'Erik@gmail.com', 44, '2023-12-08 09:23:52', 1),
(304, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-08 09:32:01', 1),
(305, '127.0.0.1', 'Erik@gmail.com', 44, '2023-12-08 09:36:33', 1),
(306, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-08 09:40:53', 1),
(307, '127.0.0.1', 'rizal', NULL, '2023-12-08 13:56:03', 0),
(308, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-08 13:56:15', 1),
(309, '127.0.0.1', 'Erik@gmail.com', 44, '2023-12-09 00:18:25', 1),
(310, '127.0.0.1', 'narji@gmail.com', 47, '2023-12-09 00:18:47', 1),
(311, '127.0.0.1', 'rizal', NULL, '2023-12-09 00:25:23', 0),
(312, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-09 00:25:30', 1),
(313, '127.0.0.1', 'rizal@gmail.com', 28, '2023-12-09 06:10:35', 1);

-- --------------------------------------------------------

--
-- Table structure for table `auth_permissions`
--

CREATE TABLE `auth_permissions` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `auth_permissions`
--

INSERT INTO `auth_permissions` (`id`, `name`, `description`) VALUES
(57, 'client.read', 'Melihat daftar Proyek.'),
(58, 'client.auth.holding', 'Otorisasi pusat.'),
(59, 'client.auth.branch', 'Otorisasi cabang.'),
(60, 'admin.user.read', 'Melihat daftar pengguna.'),
(61, 'admin.user.create', 'Membuat pengguna baru.'),
(62, 'admin.user.edit', 'Merubah data pengguna.'),
(63, 'admin.user.delete', 'Menghapus pengguna.'),
(64, 'admin.mdl.read', 'Melihat daftar MDL.'),
(65, 'admin.mdl.create', 'Membuat data MDL baru.'),
(66, 'admin.mdl.edit', 'Merubah data MDL.'),
(67, 'admin.mdl.delete', 'Menghapus data MDL.'),
(68, 'admin.project.read', 'Melihat daftar proyek.'),
(69, 'admin.project.delete', 'Menghapus proyek.'),
(70, 'admin.project.create', 'Membuat proyek baru.'),
(71, 'marketing.project.edit', 'Merubah data proyek.'),
(72, 'design.project.edit', 'Merubah data design proyek.'),
(73, 'production.project.edit', 'Merubah data produksi proyek.');

-- --------------------------------------------------------

--
-- Table structure for table `auth_reset_attempts`
--

CREATE TABLE `auth_reset_attempts` (
  `id` int(11) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_tokens`
--

CREATE TABLE `auth_tokens` (
  `id` int(11) UNSIGNED NOT NULL,
  `selector` varchar(255) NOT NULL,
  `hashedValidator` varchar(255) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `expires` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_users_permissions`
--

CREATE TABLE `auth_users_permissions` (
  `user_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `permission_id` int(11) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bar`
--

CREATE TABLE `bar` (
  `id` int(11) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bar`
--

INSERT INTO `bar` (`id`, `qty`) VALUES
(1, 20),
(2, 5);

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `id` int(11) UNSIGNED NOT NULL,
  `rsname` varchar(255) NOT NULL,
  `ptname` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `npwp` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `parentid` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`id`, `rsname`, `ptname`, `address`, `npwp`, `phone`, `parentid`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(3, 'PKU', 'PKU sejahtera abadi', 'Jalan selokan Mataram no 399', '2304243250', '02749083490', 0, 1, NULL, NULL, '2023-12-09 00:11:23'),
(4, 'PKU Sleman', 'PKU sejahtera abadi', 'Jalan Magelang No 58 ', '4553252729875', '0274729837292', 0, 0, NULL, '2023-12-09 06:45:22', NULL),
(5, 'Harjo Lukito', 'PT Lukito Bahagia', 'Jalan Kaliurang no 58', '2304243250', '02749083490', 0, 0, NULL, '2023-12-09 06:45:28', NULL),
(6, 'Harjo Bantul', 'PT Lukito Bahagia', 'Jalan Kaliurang ', '3234235532452', '027498375390', 5, 0, NULL, '2023-12-09 06:45:34', NULL),
(8, 'Ludiro', 'PT Ludiro Husodo', 'Jalan selokan Mataram no 400', '32342355324521', '027490834901', 0, 0, '2023-12-09 12:04:40', '2023-12-09 06:45:39', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `design`
--

CREATE TABLE `design` (
  `id` int(11) NOT NULL,
  `projectid` int(11) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mdl`
--

CREATE TABLE `mdl` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `length` varchar(255) DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `volume` int(11) DEFAULT NULL,
  `denomination` int(11) NOT NULL,
  `price` varchar(255) NOT NULL,
  `paketid` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `mdl`
--

INSERT INTO `mdl` (`id`, `name`, `length`, `width`, `height`, `volume`, `denomination`, `price`, `paketid`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'meja', '10', 10, 19, 10, 2, '30000', NULL, NULL, NULL, NULL),
(3, 'kursi', NULL, NULL, NULL, NULL, 1, '20000', NULL, NULL, NULL, NULL),
(4, 'lampu', NULL, NULL, NULL, NULL, 1, '50000', NULL, NULL, NULL, NULL),
(5, 'sulak', '12', 12, 34, 50, 3, '20000', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2017-11-20-223112', 'Myth\\Auth\\Database\\Migrations\\CreateAuthTables', 'default', 'Myth\\Auth', 1697439488, 1),
(2, '2023-01-11-233057', 'App\\Database\\Migrations\\UpdateUser', 'default', 'App', 1698906173, 2),
(6, '2023-06-11-163157', 'App\\Database\\Migrations\\CreateTempProject', 'default', 'App', 1701228461, 3),
(7, '2023-11-28-173850', 'App\\Database\\Migrations\\CreateMDL', 'default', 'App', 1701228461, 3),
(8, '2023-12-01-143835', 'App\\Database\\Migrations\\CreatePaket', 'default', 'App', 1701418205, 4),
(9, '2023-12-01-144030', 'App\\Database\\Migrations\\CreatePaketDetail', 'default', 'App', 1701418205, 4),
(10, '2023-12-01-144325', 'App\\Database\\Migrations\\CreateCompany', 'default', 'App', 1701418205, 4),
(18, '2023-12-01-152140', 'App\\Database\\Migrations\\UpdateUserPosition', 'default', 'App', 1702034411, 5),
(19, '2023-12-06-151420', 'App\\Database\\Migrations\\DeletingPaketDetailInsertPaketIdInMdl', 'default', 'App', 1702034411, 5),
(20, '2023-12-08-133920', 'App\\Database\\Migrations\\UpdateTimestampsInPaketAndMDL', 'default', 'App', 1702034411, 5),
(21, '2023-12-08-175100', 'App\\Database\\Migrations\\CreateProject', 'default', 'App', 1702034562, 6),
(22, '2023-12-09-000001', 'App\\Database\\Migrations\\UpdateCompany', 'default', 'App', 1702054792, 7);

-- --------------------------------------------------------

--
-- Table structure for table `paket`
--

CREATE TABLE `paket` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `brief` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `production` int(11) NOT NULL,
  `clientid` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`id`, `name`, `brief`, `status`, `production`, `clientid`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Vip Room Hermina', 'Vip Room Hermina', 1, 0, 4, '2023-12-08 09:06:39', '2023-12-08 21:11:48', '2023-12-09 06:42:57'),
(2, 'VIP Room RS PKU', 'Ruang VIP Rumah Sakit Lantai 1', 4, 10, 4, '2023-12-08 09:32:49', '2023-12-09 06:44:54', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rab`
--

CREATE TABLE `rab` (
  `id` int(11) NOT NULL,
  `mdlid` int(11) NOT NULL,
  `projectid` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `qty_deliver` int(11) NOT NULL,
  `qty_complete` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rab`
--

INSERT INTO `rab` (`id`, `mdlid`, `projectid`, `qty`, `qty_deliver`, `qty_complete`) VALUES
(9, 5, 6, 15, 15, 10),
(10, 5, 4, 10, 2, 5);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(30) DEFAULT NULL,
  `photo` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `reset_hash` varchar(255) DEFAULT NULL,
  `reset_at` datetime DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  `activate_hash` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `status_message` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `force_pass_reset` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `parentid` int(11) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `photo`, `password_hash`, `reset_hash`, `reset_at`, `reset_expires`, `activate_hash`, `status`, `status_message`, `active`, `force_pass_reset`, `created_at`, `updated_at`, `deleted_at`, `firstname`, `lastname`, `parentid`, `position`) VALUES
(28, 'rizal@gmail.com', 'rizal', '', '$2y$10$ltpY4eLnT90FbiOWNp.ZJuP8kR.x7JiG0nj49cnKt5PkzVy5bqS4m', NULL, '2023-12-08 05:33:31', NULL, NULL, NULL, NULL, 1, 0, '2023-11-17 05:18:07', '2023-12-08 05:33:31', NULL, 'rizal', 'ramadhan', NULL, NULL),
(29, 'banar@gmail.com', 'dismas', '', '$2y$10$Tky8IQkRM1ZfqvSG5zXDbufH8iA55.GbBBsyfJMIcuo43WxVKI9Zq', NULL, '2023-11-17 05:21:27', NULL, NULL, NULL, NULL, 0, 0, '2023-11-17 05:20:25', '2023-12-05 19:19:14', NULL, 'dismas', 'pamungkas', NULL, NULL),
(30, 'bambang@gmail.com', 'bambang', '', '$2y$10$nXUp.ogSHKDhveRXfLqz4u3W1hDQl8u.xfuFkUaH7gwMURb0.cxpC', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, '2023-11-17 05:22:17', '2023-12-03 13:34:53', '2023-12-03 13:34:53', 'bambang', 'pamungkas', NULL, NULL),
(33, 'admin@dpsa.com', 'admin', '', '$2y$10$VnGf0ocs/UxqrkFA0AAGFOywZEbHyk66n9fcd5z56GV79BwkUw0O2', NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, '2023-11-17 07:22:27', '2023-12-05 17:04:19', NULL, 'Admin', 'DPSA', NULL, NULL),
(34, 'herbalife@gmail.com', 'herbalife', '', '$2y$10$YhQhARlY3HrT89eqaJvQjeyQW99UcubGXUFJ98bFZX7qcGCSh6JDG', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, '2023-11-18 12:13:14', '2023-12-09 00:30:55', NULL, 'herbalife', 'herbal', 4, NULL),
(42, 'kodebiner@gmail.com', 'binary111', '', '$2y$10$/nJnqMKsbyO9ubYtss4eZO0bMEuD32qABIxLpOJmN3O.XCTxj82fu', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, '2023-11-27 09:49:04', '2023-11-27 09:51:56', '2023-11-27 09:51:56', 'kodebiner', 'binary111', NULL, NULL),
(43, 'kodebiner@kodebiner.com', 'kodebiner', '', '$2y$10$cjK7LyGkgoFXixgPDQKEj.URl9lY8qL5tmSUi7wPT4NTwH3nzXtri', NULL, '2023-12-08 05:32:41', NULL, NULL, NULL, NULL, 1, 0, '2023-11-27 09:54:53', '2023-12-08 05:32:41', NULL, 'kodebinary', '111', NULL, NULL),
(44, 'Erik@gmail.com', 'Erik', '', '$2y$10$fMbW.Dx8Gbii6ft0/f4lFea.3iXjdNV.SJ2Fjo8FWi3swXGazMvm6', NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, '2023-12-03 10:31:11', '2023-12-05 17:04:42', NULL, 'Erik', 'Siswanto', 5, NULL),
(45, 'erwin@gmail.com', 'Erwin', '', '$2y$10$eGqUzRuQwSGnJYOrzZcEMuv34ACeMbnXpIcE4q.SVRkl/L3yG8cBK', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, '2023-12-03 11:06:51', '2023-12-07 01:23:58', NULL, 'Er', 'Win', NULL, NULL),
(46, 'ridwan@gmail.com', 'Ridwan', '', '$2y$10$wT1rEedXK04oiqBo882sku28J1eQM9tGWnkszCn4/oVKJK1MMWPCu', NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, '2023-12-03 11:18:43', '2023-12-08 07:11:52', NULL, 'rid', 'wan', 4, NULL),
(47, 'narji@gmail.com', 'narji', '', '$2y$10$/mG7mmUd258/AqX6L6ZKJuLyi8J.W399fxNhNqoZQN5GZBVfZHB6q', NULL, '2023-12-08 06:06:36', NULL, NULL, NULL, NULL, 1, 0, '2023-12-04 21:20:27', '2023-12-09 06:11:26', NULL, 'narji', 'cagur', 5, NULL),
(48, 'wendi@gmail.com', 'wendi', '', '$2y$10$6QmbIpMOwIojwNo2d3OPeezYH2JmlMAISfvXiwejh4GAYSIo8HmTy', NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, '2023-12-04 21:23:33', '2023-12-08 07:12:03', NULL, 'wendi', 'cagur', 6, NULL),
(49, 'ibrahim@gmail.com', 'ibrahim', '', '$2y$10$2XBnqiRook1wFssO7lLrveRfACwFu5DwbBRQnIodVP9u8Hrbx1pl.', NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, '2023-12-07 23:36:17', '2023-12-07 23:36:17', NULL, 'ibrahim', 'movic', NULL, NULL),
(50, 'budi@gmail.com', 'budi', '', '$2y$10$kdV2/cKNHavoz1M7v2qi2enmSRXoi.K6I9wNcYDZG76r91FhjQ7ay', NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, '2023-12-07 23:37:34', '2023-12-07 23:37:34', NULL, 'budi', 'anduk', NULL, NULL),
(51, 'sarutobi@gmail.com', 'sarutobi', '', '$2y$10$Jb5vgogeTk0VOCE2.E.ltupb01bwKrg5jvktj.i6ES48qh8o71VR6', NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, '2023-12-08 06:27:29', '2023-12-08 07:11:27', NULL, 'sarutobio', 'senju', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auth_activation_attempts`
--
ALTER TABLE `auth_activation_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auth_groups`
--
ALTER TABLE `auth_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auth_groups_permissions`
--
ALTER TABLE `auth_groups_permissions`
  ADD KEY `auth_groups_permissions_permission_id_foreign` (`permission_id`),
  ADD KEY `group_id_permission_id` (`group_id`,`permission_id`);

--
-- Indexes for table `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  ADD KEY `auth_groups_users_user_id_foreign` (`user_id`),
  ADD KEY `group_id_user_id` (`group_id`,`user_id`);

--
-- Indexes for table `auth_logins`
--
ALTER TABLE `auth_logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `auth_permissions`
--
ALTER TABLE `auth_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auth_reset_attempts`
--
ALTER TABLE `auth_reset_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auth_tokens`
--
ALTER TABLE `auth_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auth_tokens_user_id_foreign` (`user_id`),
  ADD KEY `selector` (`selector`);

--
-- Indexes for table `auth_users_permissions`
--
ALTER TABLE `auth_users_permissions`
  ADD KEY `auth_users_permissions_permission_id_foreign` (`permission_id`),
  ADD KEY `user_id_permission_id` (`user_id`,`permission_id`);

--
-- Indexes for table `bar`
--
ALTER TABLE `bar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `design`
--
ALTER TABLE `design`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mdl`
--
ALTER TABLE `mdl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `paket`
--
ALTER TABLE `paket`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rab`
--
ALTER TABLE `rab`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auth_activation_attempts`
--
ALTER TABLE `auth_activation_attempts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth_groups`
--
ALTER TABLE `auth_groups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `auth_logins`
--
ALTER TABLE `auth_logins`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=314;

--
-- AUTO_INCREMENT for table `auth_permissions`
--
ALTER TABLE `auth_permissions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `auth_reset_attempts`
--
ALTER TABLE `auth_reset_attempts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth_tokens`
--
ALTER TABLE `auth_tokens`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bar`
--
ALTER TABLE `bar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `design`
--
ALTER TABLE `design`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mdl`
--
ALTER TABLE `mdl`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `paket`
--
ALTER TABLE `paket`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rab`
--
ALTER TABLE `rab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_groups_permissions`
--
ALTER TABLE `auth_groups_permissions`
  ADD CONSTRAINT `auth_groups_permissions_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `auth_groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `auth_groups_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `auth_permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  ADD CONSTRAINT `auth_groups_users_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `auth_groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `auth_groups_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_tokens`
--
ALTER TABLE `auth_tokens`
  ADD CONSTRAINT `auth_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_users_permissions`
--
ALTER TABLE `auth_users_permissions`
  ADD CONSTRAINT `auth_users_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `auth_permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `auth_users_permissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
