-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 01, 2026 at 02:28 PM
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
-- Database: `natakos`
--

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('room','public') NOT NULL DEFAULT 'room',
  `icon` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `facilities`
--

INSERT INTO `facilities` (`id`, `name`, `type`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'Kasur', 'room', 'bed', '2026-05-16 11:48:38', '2026-05-16 11:48:38'),
(2, 'Lemari', 'room', 'wardrobe', '2026-05-16 11:48:38', '2026-05-16 11:48:38'),
(3, 'Meja Belajar', 'room', 'desk', '2026-05-16 11:48:38', '2026-05-16 11:48:38'),
(6, 'Kamar Mandi Dalam', 'room', 'bath', '2026-05-16 11:48:38', '2026-05-16 11:48:38'),
(7, 'WiFi', 'public', 'wifi', '2026-05-16 11:48:38', '2026-05-16 11:48:38'),
(8, 'Parkiran', 'public', 'parking', '2026-05-16 11:48:38', '2026-05-16 11:48:38');

-- --------------------------------------------------------

--
-- Table structure for table `facility_room`
--

CREATE TABLE `facility_room` (
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `facility_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `facility_room`
--

INSERT INTO `facility_room` (`room_id`, `facility_id`, `created_at`, `updated_at`) VALUES
(1, 7, '2026-05-16 22:16:39', '2026-05-16 22:16:39'),
(5, 1, '2026-05-16 22:16:19', '2026-05-16 22:16:19'),
(5, 2, '2026-05-16 22:16:19', '2026-05-16 22:16:19'),
(5, 3, '2026-05-16 22:16:19', '2026-05-16 22:16:19'),
(5, 7, '2026-05-16 22:16:19', '2026-05-16 22:16:19'),
(5, 8, '2026-05-16 22:16:45', '2026-05-16 22:16:45');

-- --------------------------------------------------------

--
-- Table structure for table `kos_profiles`
--

CREATE TABLE `kos_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `whatsapp_number` varchar(30) NOT NULL,
  `google_maps_url` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kos_profiles`
--

INSERT INTO `kos_profiles` (`id`, `name`, `description`, `address`, `whatsapp_number`, `google_maps_url`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'Ichikos', NULL, NULL, '6285217430009', NULL, 'kos/ivqWUPy5bjPmQaBC3ZozXH8yrQFYrGh7lgxNXC9r.jpg', '2026-05-16 11:48:38', '2026-05-17 08:51:50');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2026_05_18_000002_add_rejection_reason_to_payments_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `amount` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  `due_date` date NOT NULL,
  `paid_at` datetime DEFAULT NULL,
  `status` enum('unpaid','pending_verification','paid','rejected') NOT NULL DEFAULT 'unpaid',
  `proof_image` varchar(255) DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  `verified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `tenant_id`, `amount`, `period_start`, `period_end`, `due_date`, `paid_at`, `status`, `proof_image`, `verified_at`, `verified_by`, `notes`, `rejection_reason`, `created_at`, `updated_at`) VALUES
(8, 5, 800000, '2026-05-18', '2026-05-30', '2026-05-30', NULL, 'rejected', 'payments/6DU6RcVS9q3TfwUQpoz4xiNsPGgJxGipGHgkCGOd.jpg', '2026-05-18 00:16:12', 1, NULL, 'tidak valid', '2026-05-16 22:46:59', '2026-05-17 17:16:12');

-- --------------------------------------------------------

--
-- Stand-in structure for view `payment_deadline_view`
-- (See below for the actual view)
--
CREATE TABLE `payment_deadline_view` (
`id` bigint(20) unsigned
,`tenant_id` bigint(20) unsigned
,`user_id` bigint(20) unsigned
,`room_id` bigint(20) unsigned
,`amount` bigint(20) unsigned
,`period_start` date
,`period_end` date
,`due_date` date
,`paid_at` datetime
,`status` enum('unpaid','pending_verification','paid','rejected')
,`proof_image` varchar(255)
,`verified_at` datetime
,`verified_by` bigint(20) unsigned
,`days_remaining` int(7)
,`deadline_status` varchar(9)
);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `price` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `size` varchar(100) DEFAULT NULL,
  `floor` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('available','occupied','maintenance') NOT NULL DEFAULT 'available',
  `main_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `slug`, `price`, `size`, `floor`, `description`, `status`, `main_image`, `created_at`, `updated_at`) VALUES
(1, 'Kamar A1', 'kamar-a1', 750000, '3x3 meter', '1', 'Kamar nyaman untuk satu orang dengan fasilitas dasar.', 'available', 'rooms/IZAvYWzSdjsM7U0gdMKoBdeflstovG6ODSpJ0KJA.jpg', '2026-05-16 11:48:38', '2026-05-18 19:26:14'),
(5, 'Kamar A2', 'kamar-a2', 800000, '3x4 meter', '1', NULL, 'available', 'rooms/pI7NLvqRCon74WQ9dgNJ92abuy1GBZ754xuQacDx.jpg', '2026-05-16 22:16:19', '2026-05-17 17:40:25');

-- --------------------------------------------------------

--
-- Table structure for table `room_images`
--

CREATE TABLE `room_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `room_images`
--

INSERT INTO `room_images` (`id`, `room_id`, `image_path`, `caption`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 5, 'room-images/C3nQSz54QJKuU45Eq7KE9bRDVjfJW1Ep3vsnjh3J.jpg', NULL, 1, '2026-05-17 08:28:54', '2026-05-17 08:28:54'),
(2, 5, 'room-images/SHa3hqFg8MrZfcoHRdiiAkuiC8plHCfAtk1E2nW2.jpg', NULL, 2, '2026-05-17 08:29:04', '2026-05-17 08:29:04');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tenants`
--

CREATE TABLE `tenants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('active','inactive','moved_out') NOT NULL DEFAULT 'active',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tenants`
--

INSERT INTO `tenants` (`id`, `user_id`, `room_id`, `start_date`, `end_date`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(5, 7, 5, '2026-05-18', '2026-05-18', 'moved_out', NULL, '2026-05-16 22:31:03', '2026-05-17 17:40:25');

-- --------------------------------------------------------

--
-- Stand-in structure for view `tenant_end_date_view`
-- (See below for the actual view)
--
CREATE TABLE `tenant_end_date_view` (
`tenant_id` bigint(20) unsigned
,`user_id` bigint(20) unsigned
,`room_id` bigint(20) unsigned
,`start_date` date
,`end_date` date
,`status` enum('active','inactive','moved_out')
,`days_until_end` int(7)
,`rent_period_status` varchar(11)
);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `role` enum('admin','tenant') NOT NULL DEFAULT 'tenant',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `phone`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin NATAKOS', 'admin@natakos.test', NULL, '$2y$12$MXzge4avCAwLbuOhearRpeIssfowhLx7dq0xr9OmRZXNKL4ZhB47i', '085217430009', 'admin', 'ziQWxBl3ukMGQJURhghQsKBrXoMpvkuVRL1bC5mffPaeUCqThWFdtaBn07hD', '2026-05-16 11:48:38', '2026-06-01 12:27:07'),
(2, 'Tenant Test', 'tenant@natakos.test', NULL, '$2y$12$nh/idVkzUBBr0rGOXB.AsO5Ck/F1i6ZIrkWwgXprhjhZmy4q7Y/Iu', '081234567890', 'tenant', NULL, '2026-05-16 20:26:34', '2026-05-16 20:26:34'),
(7, 'Suki', 'suki@gmail.com', NULL, '$2y$12$uAAoOkAQ4NMjyBlovE5Vyu.9faGrp3n5EdFCDSYGEvSGJNAEwG7Y.', '080928109380192', 'tenant', 'nMGkcNQKn28xnbpaVug6Np6yw2aESsuHhWwvHnli6J5vVpmlUejUszo4iQ8a', '2026-05-16 22:31:03', '2026-06-01 12:23:03');

-- --------------------------------------------------------

--
-- Structure for view `payment_deadline_view`
--
DROP TABLE IF EXISTS `payment_deadline_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `payment_deadline_view`  AS SELECT `payments`.`id` AS `id`, `payments`.`tenant_id` AS `tenant_id`, `tenants`.`user_id` AS `user_id`, `tenants`.`room_id` AS `room_id`, `payments`.`amount` AS `amount`, `payments`.`period_start` AS `period_start`, `payments`.`period_end` AS `period_end`, `payments`.`due_date` AS `due_date`, `payments`.`paid_at` AS `paid_at`, `payments`.`status` AS `status`, `payments`.`proof_image` AS `proof_image`, `payments`.`verified_at` AS `verified_at`, `payments`.`verified_by` AS `verified_by`, to_days(`payments`.`due_date`) - to_days(curdate()) AS `days_remaining`, CASE WHEN `payments`.`status` = 'paid' THEN 'paid' WHEN `payments`.`due_date` < curdate() THEN 'overdue' WHEN `payments`.`due_date` = curdate() THEN 'due_today' WHEN to_days(`payments`.`due_date`) - to_days(curdate()) between 1 and 5 THEN 'due_soon' ELSE 'safe' END AS `deadline_status` FROM (`payments` join `tenants` on(`payments`.`tenant_id` = `tenants`.`id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `tenant_end_date_view`
--
DROP TABLE IF EXISTS `tenant_end_date_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `tenant_end_date_view`  AS SELECT `tenants`.`id` AS `tenant_id`, `tenants`.`user_id` AS `user_id`, `tenants`.`room_id` AS `room_id`, `tenants`.`start_date` AS `start_date`, `tenants`.`end_date` AS `end_date`, `tenants`.`status` AS `status`, to_days(`tenants`.`end_date`) - to_days(curdate()) AS `days_until_end`, CASE WHEN `tenants`.`status` <> 'active' THEN 'inactive' WHEN `tenants`.`end_date` is null THEN 'no_end_date' WHEN `tenants`.`end_date` < curdate() THEN 'ended' WHEN `tenants`.`end_date` = curdate() THEN 'ends_today' WHEN to_days(`tenants`.`end_date`) - to_days(curdate()) between 1 and 5 THEN 'ending_soon' ELSE 'safe' END AS `rent_period_status` FROM `tenants` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_facility_name_type` (`name`,`type`),
  ADD KEY `idx_facilities_type` (`type`);

--
-- Indexes for table `facility_room`
--
ALTER TABLE `facility_room`
  ADD PRIMARY KEY (`room_id`,`facility_id`),
  ADD KEY `fk_facility_room_facility` (`facility_id`);

--
-- Indexes for table `kos_profiles`
--
ALTER TABLE `kos_profiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_tenant_payment_period` (`tenant_id`,`period_start`,`period_end`),
  ADD KEY `idx_payments_tenant_id` (`tenant_id`),
  ADD KEY `idx_payments_status` (`status`),
  ADD KEY `idx_payments_due_date` (`due_date`),
  ADD KEY `idx_payments_verified_by` (`verified_by`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_rooms_status` (`status`),
  ADD KEY `idx_rooms_price` (`price`);

--
-- Indexes for table `room_images`
--
ALTER TABLE `room_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_room_images_room_id` (`room_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sessions_user_id` (`user_id`),
  ADD KEY `idx_sessions_last_activity` (`last_activity`);

--
-- Indexes for table `tenants`
--
ALTER TABLE `tenants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tenants_user_id` (`user_id`),
  ADD KEY `idx_tenants_room_id` (`room_id`),
  ADD KEY `idx_tenants_status` (`status`),
  ADD KEY `idx_tenants_end_date` (`end_date`);

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
-- AUTO_INCREMENT for table `facilities`
--
ALTER TABLE `facilities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `kos_profiles`
--
ALTER TABLE `kos_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `room_images`
--
ALTER TABLE `room_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `facility_room`
--
ALTER TABLE `facility_room`
  ADD CONSTRAINT `fk_facility_room_facility` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_facility_room_room` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_payments_verified_by` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `room_images`
--
ALTER TABLE `room_images`
  ADD CONSTRAINT `fk_room_images_room` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tenants`
--
ALTER TABLE `tenants`
  ADD CONSTRAINT `fk_tenants_room` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tenants_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
