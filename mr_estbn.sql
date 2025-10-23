-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 15, 2025 at 12:05 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mr_estbn`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_07_13_133428_create_services_table', 1),
(5, '2025_07_13_133514_create_service_types_table', 1),
(6, '2025_07_13_135400_create_sub_service_types_table', 1),
(7, '2025_07_15_073806_create_personal_access_tokens_table', 2);

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
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'mohamed@gmail.com', '538d308d5fbe8c06417b1ef25aa8b39bada1498fa3fc2385e7785d8b146aeae0', '[\"*\"]', NULL, NULL, '2025-07-15 04:54:56', '2025-07-15 04:54:56'),
(2, 'App\\Models\\User', 1, 'mohamed@gmail.com', '29c4ffa1e43479943f286e33edc19a3d1702fbf17e2865b2f5733b9dfb654ed2', '[\"*\"]', NULL, NULL, '2025-07-15 05:09:26', '2025-07-15 05:09:26');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'زيوت وفلاتر', NULL, '2025-07-13 11:33:57', '2025-07-13 11:33:57'),
(2, 'كاوتش', NULL, '2025-07-13 11:33:57', '2025-07-13 11:33:57'),
(3, 'بطارية', NULL, '2025-07-13 11:33:57', '2025-07-13 11:33:57'),
(4, 'خدمة كار كير', NULL, '2025-07-13 11:33:57', '2025-07-13 11:33:57'),
(5, 'صيانة دورية', NULL, '2025-07-13 11:33:57', '2025-07-13 11:33:57'),
(6, 'خدمات اخري', NULL, '2025-07-13 11:33:57', '2025-07-13 11:33:57');

-- --------------------------------------------------------

--
-- Table structure for table `service_types`
--

CREATE TABLE `service_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_types`
--

INSERT INTO `service_types` (`id`, `name`, `description`, `service_id`, `created_at`, `updated_at`) VALUES
(1, 'فحص وكشف الزيوت والفلاتر', NULL, 1, '2025-07-13 11:42:10', '2025-07-13 11:42:10'),
(2, 'تغيير زيت وفلاتر', NULL, 1, '2025-07-13 11:42:10', '2025-07-13 11:42:10'),
(3, 'فحص وكشف الكاوتش', NULL, 2, '2025-07-13 11:42:10', '2025-07-13 11:42:10'),
(4, 'تغيير كاوتش', NULL, 2, '2025-07-13 11:42:10', '2025-07-13 11:42:10'),
(5, 'فحص وكشف البطارية', NULL, 3, '2025-07-13 11:42:10', '2025-07-13 11:42:10'),
(6, 'تغيير البطارية', NULL, 3, '2025-07-13 11:42:10', '2025-07-13 11:42:10'),
(7, 'غسيل خارجي للسيارة', NULL, 4, '2025-07-13 11:42:10', '2025-07-13 11:42:10'),
(8, 'تنضيف داخلي للسيارة', NULL, 4, '2025-07-13 11:42:10', '2025-07-13 11:42:10'),
(9, 'صيانة 10000 كيلو', NULL, 5, '2025-07-13 11:42:10', '2025-07-13 11:42:10'),
(10, 'صيانة 60000 كيلو', NULL, 5, '2025-07-13 11:42:10', '2025-07-13 11:42:10');

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
-- Table structure for table `sub_service_types`
--

CREATE TABLE `sub_service_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `service_type_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sub_service_types`
--

INSERT INTO `sub_service_types` (`id`, `name`, `price`, `service_type_id`, `created_at`, `updated_at`) VALUES
(1, 'كشف علي زيت الماتور', '0.00', 1, '2025-07-15 04:33:23', '2025-07-15 04:33:23'),
(2, 'فحص تسريب زيت', '0.00', 1, '2025-07-15 04:33:23', '2025-07-15 04:33:23'),
(3, 'فحص فلتر الزيت وتنضيفه', '0.00', 1, '2025-07-15 04:33:23', '2025-07-15 04:33:23'),
(4, 'ماركة الزيت', '0.00', 2, '2025-07-15 04:33:23', '2025-07-15 04:33:23'),
(5, 'درجة اللزوجة', '0.00', 2, '2025-07-15 04:33:23', '2025-07-15 04:33:23'),
(6, 'حجم العبوة', '0.00', 2, '2025-07-15 04:33:23', '2025-07-15 04:33:23'),
(7, 'نوع الفلتر', '0.00', 2, '2025-07-15 04:33:23', '2025-07-15 04:33:23'),
(8, 'كشف ثقوب', '0.00', 3, '2025-07-15 04:33:23', '2025-07-15 04:33:23'),
(9, 'ملئ نيتروجين', '0.00', 3, '2025-07-15 04:33:23', '2025-07-15 04:33:23'),
(10, '(بدون استبدال) تبديل موضع الاطارات', '0.00', 3, '2025-07-15 04:33:23', '2025-07-15 04:33:23'),
(11, 'كشف اتزان', '0.00', 3, '2025-07-15 04:33:23', '2025-07-15 04:33:23'),
(12, 'كشف استعدال جنوط', '0.00', 3, '2025-07-15 04:33:23', '2025-07-15 04:33:23'),
(13, 'مقاس الكاوتش', '0.00', 4, '2025-07-15 04:33:23', '2025-07-15 04:33:23'),
(14, 'نوع الكاوتش', '0.00', 4, '2025-07-15 04:33:23', '2025-07-15 04:33:23'),
(15, 'ماركة التصنيع', '0.00', 4, '2025-07-15 04:33:23', '2025-07-15 04:33:23'),
(16, 'كشف علي البطارية', '0.00', 5, '2025-07-15 04:33:23', '2025-07-15 04:33:23'),
(17, 'كشف وظائف الدينامو', '0.00', 5, '2025-07-15 04:33:23', '2025-07-15 04:33:23'),
(18, 'كشف دورة الكهرباء', '0.00', 5, '2025-07-15 04:33:23', '2025-07-15 04:33:23'),
(19, 'نوع البطارية', '0.00', 6, '2025-07-15 04:33:23', '2025-07-15 04:33:23'),
(20, 'امبير البطارية', '0.00', 6, '2025-07-15 04:33:23', '2025-07-15 04:33:23'),
(21, 'ماركة البطارية', '0.00', 6, '2025-07-15 04:33:23', '2025-07-15 04:33:23'),
(22, 'غسيل خارجي للسيارة', '0.00', 7, '2025-07-15 06:42:06', '2025-07-15 06:42:06'),
(23, 'غسيل خارجي waterless', '0.00', 7, '2025-07-15 06:42:53', '2025-07-15 06:42:53'),
(24, 'ورنيش وتلميع السيارة', '0.00', 7, '2025-07-15 06:43:11', '2025-07-15 06:43:11'),
(25, 'ورنيش وتلميع الجنوط', '0.00', 7, '2025-07-15 06:43:24', '2025-07-15 06:43:24'),
(26, 'تنضيف داخلي يدوي', '0.00', 8, '2025-07-15 06:44:10', '2025-07-15 06:44:10'),
(27, 'تنضيف داخلي بالمكنسة', '0.00', 8, '2025-07-15 06:44:33', '2025-07-15 06:44:33'),
(28, 'تنضيف بالبخار', '0.00', 8, '2025-07-15 06:44:47', '2025-07-15 06:44:47'),
(29, 'تنضيف الفرش والدواسات', '0.00', 8, '2025-07-15 06:45:19', '2025-07-15 06:45:19'),
(30, 'تعقيم السيارة', '0.00', 8, '2025-07-15 06:45:47', '2025-07-15 06:45:47'),
(31, 'زيت المحرك', '0.00', 9, '2025-07-15 06:52:06', '2025-07-15 06:52:06'),
(32, 'تغيير فلتر زيت المحرك', '0.00', 9, '2025-07-15 06:52:29', '2025-07-15 06:52:29'),
(33, 'استبدال قابس الصرف', '0.00', 9, '2025-07-15 06:52:49', '2025-07-15 06:52:49'),
(34, 'فحص المحرك', '0.00', 9, '2025-07-15 06:53:04', '2025-07-15 06:53:04'),
(35, 'فحص الفرامل الامامية والخلفية', '0.00', 9, '2025-07-15 06:53:22', '2025-07-15 06:53:22'),
(36, 'فحص سائل البطارية', '0.00', 9, '2025-07-15 06:53:34', '2025-07-15 06:53:34'),
(37, 'زيت المحرك', '0.00', 10, '2025-07-15 06:54:56', '2025-07-15 06:54:56'),
(38, 'تغيير فلتر زيت المحرك', '0.00', 10, '2025-07-15 06:55:15', '2025-07-15 06:55:15'),
(39, 'استبدال قابس الصرف', '0.00', 10, '2025-07-15 06:55:29', '2025-07-15 06:55:29'),
(40, 'فحص المحرك', '0.00', 10, '2025-07-15 06:55:40', '2025-07-15 06:55:40'),
(41, 'فحص الفرامل الامامية والخلفية', '0.00', 10, '2025-07-15 06:55:59', '2025-07-15 06:55:59'),
(42, 'فحص سائل البطارية', '0.00', 10, '2025-07-15 06:56:11', '2025-07-15 06:56:11'),
(43, 'تغيير فلتر الهواء', '0.00', 10, '2025-07-15 06:56:26', '2025-07-15 06:56:26'),
(44, 'استبدال مرشح مكيف الهواء', '0.00', 10, '2025-07-15 06:56:40', '2025-07-15 06:56:40'),
(45, 'استبدال سائل تبريد المحرك', '0.00', 10, '2025-07-15 06:57:00', '2025-07-15 06:57:00'),
(46, 'تغيير سائل الفرامل', '0.00', 10, '2025-07-15 06:57:13', '2025-07-15 06:57:13');

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
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'mohamed', 'mohamed@gmail.com', NULL, '$2y$12$0PgDZweomoPjq.Rsoh7iQ.tls86o9EiwQBMvOtl4BEJaDo0Mblt8u', NULL, '2025-07-15 04:54:56', '2025-07-15 04:54:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
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
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_types`
--
ALTER TABLE `service_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_types_service_id_foreign` (`service_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `sub_service_types`
--
ALTER TABLE `sub_service_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sub_service_types_service_type_id_foreign` (`service_type_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `service_types`
--
ALTER TABLE `service_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `sub_service_types`
--
ALTER TABLE `sub_service_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `service_types`
--
ALTER TABLE `service_types`
  ADD CONSTRAINT `service_types_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sub_service_types`
--
ALTER TABLE `sub_service_types`
  ADD CONSTRAINT `sub_service_types_service_type_id_foreign` FOREIGN KEY (`service_type_id`) REFERENCES `service_types` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
