-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 17 Nov 2023 pada 06.56
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

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
-- Struktur dari tabel `auth_activation_attempts`
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
-- Struktur dari tabel `auth_groups`
--

CREATE TABLE `auth_groups` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `auth_groups`
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
-- Struktur dari tabel `auth_groups_permissions`
--

CREATE TABLE `auth_groups_permissions` (
  `group_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `permission_id` int(11) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `auth_groups_permissions`
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
-- Struktur dari tabel `auth_groups_users`
--

CREATE TABLE `auth_groups_users` (
  `group_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `user_id` int(11) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `auth_groups_users`
--

INSERT INTO `auth_groups_users` (`group_id`, `user_id`) VALUES
(40, 28),
(44, 29),
(46, 31),
(47, 32),
(48, 30);

-- --------------------------------------------------------

--
-- Struktur dari tabel `auth_logins`
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
-- Dumping data untuk tabel `auth_logins`
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
(147, '127.0.0.1', 'rizal@gmail.com', 28, '2023-11-17 05:18:17', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `auth_permissions`
--

CREATE TABLE `auth_permissions` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `auth_permissions`
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
-- Struktur dari tabel `auth_reset_attempts`
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
-- Struktur dari tabel `auth_tokens`
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
-- Struktur dari tabel `auth_users_permissions`
--

CREATE TABLE `auth_users_permissions` (
  `user_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `permission_id` int(11) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `bar`
--

CREATE TABLE `bar` (
  `id` int(11) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `bar`
--

INSERT INTO `bar` (`id`, `qty`) VALUES
(1, 20),
(2, 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `design`
--

CREATE TABLE `design` (
  `id` int(11) NOT NULL,
  `projectid` int(11) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `mdl`
--

CREATE TABLE `mdl` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mdl`
--

INSERT INTO `mdl` (`id`, `name`, `price`) VALUES
(2, 'Kursi', 50000),
(5, 'Lampu', 10000),
(6, 'Meja', 200000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
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
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2017-11-20-223112', 'Myth\\Auth\\Database\\Migrations\\CreateAuthTables', 'default', 'Myth\\Auth', 1697439488, 1),
(2, '2023-01-11-233057', 'App\\Database\\Migrations\\UpdateUser', 'default', 'App', 1698906173, 2),
(3, '2023-06-11-163157', 'App\\Database\\Migrations\\CreateTempProject', 'default', 'App', 1699456080, 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `project`
--

CREATE TABLE `project` (
  `id` int(11) NOT NULL,
  `clientid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `brief` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rab`
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
-- Dumping data untuk tabel `rab`
--

INSERT INTO `rab` (`id`, `mdlid`, `projectid`, `qty`, `qty_deliver`, `qty_complete`) VALUES
(9, 5, 6, 15, 15, 10),
(10, 5, 4, 10, 2, 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `temp_project`
--

CREATE TABLE `temp_project` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `brief` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `production` int(11) NOT NULL,
  `clientid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `temp_project`
--

INSERT INTO `temp_project` (`id`, `name`, `brief`, `status`, `production`, `clientid`) VALUES
(1, 'pembangunan jembatan', 'konstruksi jembatan ampera', 2, 10, 31),
(2, 'BRI', 'konstruksi kantor', 1, 0, 32);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
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
  `parentid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `photo`, `password_hash`, `reset_hash`, `reset_at`, `reset_expires`, `activate_hash`, `status`, `status_message`, `active`, `force_pass_reset`, `created_at`, `updated_at`, `deleted_at`, `firstname`, `lastname`, `parentid`) VALUES
(28, 'rizal@gmail.com', 'rizal', '', '$2y$10$9mc23oz8EUnQ0xuWeZ5bK.VCoftCziTkaNhgl1xaYMzNX/kthBy6q', NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, '2023-11-17 05:18:07', '2023-11-17 05:18:07', NULL, 'rizal', 'ramadhan', NULL),
(29, 'banar@gmail.com', 'dismas', '', '$2y$10$Tky8IQkRM1ZfqvSG5zXDbufH8iA55.GbBBsyfJMIcuo43WxVKI9Zq', NULL, '2023-11-17 05:21:27', NULL, NULL, NULL, NULL, 1, 0, '2023-11-17 05:20:25', '2023-11-17 05:21:27', NULL, 'dismas', 'pamungkas', NULL),
(30, 'bambang@gmail.com', 'bambang', '', '$2y$10$nXUp.ogSHKDhveRXfLqz4u3W1hDQl8u.xfuFkUaH7gwMURb0.cxpC', NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, '2023-11-17 05:22:17', '2023-11-17 05:22:25', NULL, 'bambang', 'pamungkas', NULL),
(31, 'harjo@gmail.com', 'harjo', '', '$2y$10$hf9U/lhKGeMUHfsOCg5n3eOfYjkGbAW3s8oSDv7NRlJz8XEFtHKvS', NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, '2023-11-17 05:23:32', '2023-11-17 05:23:32', NULL, 'harjo', 'lukito', NULL),
(32, 'ludiro@gmail.com', 'ludiro', '', '$2y$10$W0j1xZNslfGyM.shi09h6.sEhKIL04dhMq7/tLSgsjfJxqrzOxh0S', NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, '2023-11-17 05:24:35', '2023-11-17 05:24:35', NULL, 'ludiro', 'husodo', 31);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `auth_activation_attempts`
--
ALTER TABLE `auth_activation_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `auth_groups`
--
ALTER TABLE `auth_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `auth_groups_permissions`
--
ALTER TABLE `auth_groups_permissions`
  ADD KEY `auth_groups_permissions_permission_id_foreign` (`permission_id`),
  ADD KEY `group_id_permission_id` (`group_id`,`permission_id`);

--
-- Indeks untuk tabel `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  ADD KEY `auth_groups_users_user_id_foreign` (`user_id`),
  ADD KEY `group_id_user_id` (`group_id`,`user_id`);

--
-- Indeks untuk tabel `auth_logins`
--
ALTER TABLE `auth_logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `auth_permissions`
--
ALTER TABLE `auth_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `auth_reset_attempts`
--
ALTER TABLE `auth_reset_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `auth_tokens`
--
ALTER TABLE `auth_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auth_tokens_user_id_foreign` (`user_id`),
  ADD KEY `selector` (`selector`);

--
-- Indeks untuk tabel `auth_users_permissions`
--
ALTER TABLE `auth_users_permissions`
  ADD KEY `auth_users_permissions_permission_id_foreign` (`permission_id`),
  ADD KEY `user_id_permission_id` (`user_id`,`permission_id`);

--
-- Indeks untuk tabel `bar`
--
ALTER TABLE `bar`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `design`
--
ALTER TABLE `design`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `mdl`
--
ALTER TABLE `mdl`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `rab`
--
ALTER TABLE `rab`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `temp_project`
--
ALTER TABLE `temp_project`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `auth_activation_attempts`
--
ALTER TABLE `auth_activation_attempts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `auth_groups`
--
ALTER TABLE `auth_groups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT untuk tabel `auth_logins`
--
ALTER TABLE `auth_logins`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- AUTO_INCREMENT untuk tabel `auth_permissions`
--
ALTER TABLE `auth_permissions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT untuk tabel `auth_reset_attempts`
--
ALTER TABLE `auth_reset_attempts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `auth_tokens`
--
ALTER TABLE `auth_tokens`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `bar`
--
ALTER TABLE `bar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `design`
--
ALTER TABLE `design`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `mdl`
--
ALTER TABLE `mdl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `project`
--
ALTER TABLE `project`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `rab`
--
ALTER TABLE `rab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `temp_project`
--
ALTER TABLE `temp_project`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `auth_groups_permissions`
--
ALTER TABLE `auth_groups_permissions`
  ADD CONSTRAINT `auth_groups_permissions_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `auth_groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `auth_groups_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `auth_permissions` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  ADD CONSTRAINT `auth_groups_users_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `auth_groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `auth_groups_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `auth_tokens`
--
ALTER TABLE `auth_tokens`
  ADD CONSTRAINT `auth_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `auth_users_permissions`
--
ALTER TABLE `auth_users_permissions`
  ADD CONSTRAINT `auth_users_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `auth_permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `auth_users_permissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
