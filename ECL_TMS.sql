-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.46 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for ecl_task_management
CREATE DATABASE IF NOT EXISTS `ecl_task_management` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `ecl_task_management`;

-- Dumping structure for table ecl_task_management.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table ecl_task_management.cache: ~0 rows (approximately)

-- Dumping structure for table ecl_task_management.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table ecl_task_management.cache_locks: ~0 rows (approximately)

-- Dumping structure for table ecl_task_management.departments
CREATE TABLE IF NOT EXISTS `departments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `departments_name_unique` (`name`),
  UNIQUE KEY `departments_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table ecl_task_management.departments: ~58 rows (approximately)
INSERT IGNORE INTO `departments` (`id`, `parent_id`, `name`, `code`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, NULL, 'Chairman-cum-Managing Director', 'CCMD', 1, '2026-09-23 06:12:11', '2026-09-23 06:12:11'),
	(2, 1, 'GM (T&MS)/TS to CMD', 'GM/TS', 1, '2026-09-23 06:12:11', '2026-09-23 06:12:11'),
	(3, 1, 'CVO', 'CVO', 1, '2026-09-23 06:12:11', '2026-09-23 06:12:11'),
	(4, 1, 'HoD (Internal Audit)', 'HOD', 1, '2026-09-23 06:12:11', '2026-09-23 06:12:11'),
	(5, 1, 'Company Secretary', 'CS', 1, '2026-09-23 06:12:11', '2026-09-23 06:12:11'),
	(6, 1, 'Director (Technical) Operations', 'DTO', 1, '2026-09-23 06:12:11', '2026-09-23 06:12:11'),
	(7, 1, 'Director (Technical) Project & Planning', 'DTPP', 1, '2026-09-23 06:12:11', '2026-09-23 06:12:11'),
	(8, 1, 'Director (HR)', 'DHR', 1, '2026-09-23 06:12:11', '2026-09-23 06:12:11'),
	(9, 1, 'Director (Finance)', 'DOF', 1, '2026-09-23 06:12:11', '2026-09-23 06:12:11'),
	(10, 8, 'IR', 'IR', 1, '2026-09-23 06:12:11', '2026-09-23 06:12:11'),
	(11, 8, 'Executive Establishment', 'EE', 1, '2026-09-23 06:12:11', '2026-09-23 06:12:11'),
	(12, 8, 'Security', 'SEC', 1, '2026-09-23 06:12:11', '2026-09-23 06:12:11'),
	(13, 8, 'Manpower', 'MP', 1, '2026-09-23 06:12:11', '2026-09-23 06:12:11'),
	(14, 8, 'Legal', 'LEG', 1, '2026-09-23 06:12:11', '2026-09-23 06:12:11'),
	(15, 8, 'Administration', 'ADM', 1, '2026-09-23 06:12:11', '2026-09-23 06:12:11'),
	(16, 8, 'Welfare and CSR', 'WCSR', 1, '2026-09-23 06:12:11', '2026-09-23 06:12:11'),
	(17, 8, 'RTI Cell', 'RTI', 1, '2026-09-23 06:12:11', '2026-09-23 06:12:11'),
	(18, 8, 'NIDAN Cell', 'NIDAN', 1, '2026-09-23 06:12:11', '2026-09-23 06:12:11'),
	(19, 8, 'CMS', 'CMS', 1, '2026-09-23 06:12:11', '2026-09-23 06:12:11'),
	(20, 8, 'HRD', 'HRD', 1, '2026-09-23 06:12:11', '2026-09-23 06:12:11'),
	(21, 8, 'Pension', 'PEN', 1, '2026-09-23 06:12:11', '2026-09-23 06:12:11'),
	(22, 8, 'PR', 'PR', 1, '2026-09-23 06:12:11', '2026-09-23 06:12:11'),
	(23, 8, 'Rajbhasha', 'RJB', 1, '2026-09-23 06:12:11', '2026-09-23 06:12:11'),
	(24, 8, 'WBE', 'WBE', 1, '2026-09-23 06:12:11', '2026-09-23 06:12:11'),
	(25, 8, 'Employment', 'EMP', 1, '2026-09-23 06:12:11', '2026-09-23 06:12:11'),
	(26, 9, 'Finance', 'FIN', 1, '2026-09-23 06:12:11', '2026-09-23 06:12:11'),
	(27, 9, 'Systems', 'STM', 1, '2026-09-23 06:12:11', '2026-09-23 06:12:11'),
	(28, 9, 'PAD', 'PAD', 1, '2026-09-23 06:12:11', '2026-09-23 06:12:11'),
	(29, 9, 'Electronics and Telecommunication', 'E&T', 1, '2026-09-23 06:12:11', '2026-09-23 06:12:11'),
	(30, 6, 'Salanpur', 'SAL', 1, '2026-09-23 06:12:11', '2026-09-23 06:12:11'),
	(31, 6, 'Satgram - Sripur', 'STS', 1, '2026-09-23 06:12:11', '2026-09-23 06:12:11'),
	(32, 6, 'Sodepur', 'SOD', 1, '2026-09-23 06:12:11', '2026-09-23 06:12:11'),
	(33, 6, 'Mugma', 'MUG', 1, '2026-09-23 06:12:11', '2026-09-23 06:12:11'),
	(34, 6, 'S.P. Mines', 'SPM', 1, '2026-09-23 06:12:12', '2026-09-23 06:12:12'),
	(35, 6, 'Rajmahal', 'RJM', 1, '2026-09-23 06:12:12', '2026-09-23 06:12:12'),
	(36, 6, 'Kajora', 'KAJ', 1, '2026-09-23 06:12:12', '2026-09-23 06:12:12'),
	(37, 6, 'Production / Coal Stock Management / Blasting', 'PRD', 1, '2026-09-23 06:12:12', '2026-09-23 06:12:12'),
	(38, 6, 'E&M', 'ENM', 1, '2026-09-23 06:12:12', '2026-09-23 06:12:12'),
	(39, 6, 'MM/Purchase/Stores', 'MMS', 1, '2026-09-23 06:12:12', '2026-09-23 06:12:12'),
	(40, 6, 'Excavation', 'EXC', 1, '2026-09-23 06:12:12', '2026-09-23 06:12:12'),
	(41, 6, 'Safety & Rescue', 'SAF', 1, '2026-09-23 06:12:12', '2026-09-23 06:12:12'),
	(42, 6, 'QC', 'QC', 1, '2026-09-23 06:12:12', '2026-09-23 06:12:12'),
	(43, 6, 'M&S', 'MNS', 1, '2026-09-23 06:12:12', '2026-09-23 06:12:12'),
	(44, 6, 'Survey', 'SRV', 1, '2026-09-23 06:12:12', '2026-09-23 06:12:12'),
	(45, 7, 'Bankola', 'BNK', 1, '2026-09-23 06:12:12', '2026-09-23 06:12:12'),
	(46, 7, 'Jhanjra', 'JHJ', 1, '2026-09-23 06:12:12', '2026-09-23 06:12:12'),
	(47, 7, 'Kenda', 'KEN', 1, '2026-09-23 06:12:12', '2026-09-23 06:12:12'),
	(48, 7, 'Kunustoria', 'KUN', 1, '2026-09-23 06:12:12', '2026-09-23 06:12:12'),
	(49, 7, 'Pandaveswar', 'PAN', 1, '2026-09-23 06:12:12', '2026-09-23 06:12:12'),
	(50, 7, 'Sonepur Bazari', 'SPB', 1, '2026-09-23 06:12:12', '2026-09-23 06:12:12'),
	(51, 7, 'Project & Planning', 'PNP', 1, '2026-09-23 06:12:12', '2026-09-23 06:12:12'),
	(52, 7, 'CMC', 'CMC', 1, '2026-09-23 06:12:12', '2026-09-23 06:12:12'),
	(53, 7, 'Geology & New Initiatives', 'GNI', 1, '2026-09-23 06:12:12', '2026-09-23 06:12:12'),
	(54, 7, 'Environment & Forest', 'ENF', 1, '2026-09-23 06:12:12', '2026-09-23 06:12:12'),
	(55, 7, 'Civil', 'CVL', 1, '2026-09-23 06:12:12', '2026-09-23 06:12:12'),
	(56, 7, 'LRE', 'LRE', 1, '2026-09-23 06:12:12', '2026-09-23 06:12:12'),
	(57, 7, 'Industrial Engineering', 'INE', 1, '2026-09-23 06:12:12', '2026-09-23 06:12:12'),
	(58, 7, 'New Coal Block', 'NCB', 1, '2026-09-23 06:12:12', '2026-09-23 06:12:12');

-- Dumping structure for table ecl_task_management.device_tokens
CREATE TABLE IF NOT EXISTS `device_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `device_token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `platform` enum('android','ios','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'android',
  `last_seen_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `device_tokens_device_token_unique` (`device_token`),
  KEY `device_tokens_user_id_foreign` (`user_id`),
  CONSTRAINT `device_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table ecl_task_management.device_tokens: ~3 rows (approximately)

-- Dumping structure for table ecl_task_management.employees
CREATE TABLE IF NOT EXISTS `employees` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department_id` bigint unsigned NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employees_email_unique` (`email`),
  KEY `employees_department_id_foreign` (`department_id`),
  CONSTRAINT `employees_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table ecl_task_management.employees: ~0 rows (approximately)

-- Dumping structure for table ecl_task_management.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table ecl_task_management.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table ecl_task_management.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table ecl_task_management.jobs: ~0 rows (approximately)

-- Dumping structure for table ecl_task_management.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table ecl_task_management.job_batches: ~0 rows (approximately)

-- Dumping structure for table ecl_task_management.messages
CREATE TABLE IF NOT EXISTS `messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `task_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `messages_task_id_foreign` (`task_id`),
  KEY `messages_user_id_foreign` (`user_id`),
  CONSTRAINT `messages_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table ecl_task_management.messages: ~20 rows (approximately)

-- Dumping structure for table ecl_task_management.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table ecl_task_management.migrations: ~30 rows (approximately)
INSERT IGNORE INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2026_01_01_000000_create_departments_table', 1),
	(5, '2026_08_12_000000_add_department_fk_to_users', 1),
	(6, '2026_08_12_000002_create_tasks_table', 1),
	(7, '2026_08_12_000003_create_task_attachments_table', 1),
	(8, '2026_08_12_000004_create_task_comments_table', 1),
	(9, '2026_08_12_000006_create_device_tokens_table', 1),
	(10, '2026_08_12_000007_create_sessions_table', 1),
	(11, '2026_08_12_000008_create_password_reset_tokens_table', 1),
	(12, '2026_08_12_000009_create_personal_access_tokens_table', 1),
	(13, '2026_08_19_145408_add_otp_fields_to_users_table', 1),
	(14, '2026_08_25_105234_create_notices_table', 1),
	(15, '2026_08_25_114453_add_parent_id_to_users_table', 1),
	(16, '2026_08_25_150916_add_phone_to_users_table', 1),
	(17, '2026_08_25_151613_add_phone_column_to_users_table', 1),
	(18, '2026_08_26_114026_add_branch_to_users_table', 1),
	(19, '2026_08_26_114537_add_designation_and_branch_to_users_table', 1),
	(20, '2026_08_26_125815_create_employees_table', 1),
	(21, '2026_08_26_130515_add_department_id_to_users_table', 1),
	(22, '2026_08_27_131128_add_is_dual_role_to_users_table', 1),
	(23, '2026_08_28_104815_drop_otp_fields_from_users_table', 1),
	(24, '2026_08_29_113645_add_parent_id_to_departments_table', 1),
	(25, '2026_08_31_105813_add_priority_to_tasks_table', 1),
	(26, '2026_09_01_111529_create_notifications_table', 1),
	(27, '2026_09_04_135749_change_task_comments_id_to_string', 1),
	(28, '2026_09_11_162923_add_fcm_token_to_users_table', 1),
	(29, '2026_09_11_164639_add_columns_to_notifications_table', 1),
	(30, '2026_09_14_111649_create_messages_table', 1);

-- Dumping structure for table ecl_task_management.notices
CREATE TABLE IF NOT EXISTS `notices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sender_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table ecl_task_management.notices: ~0 rows (approximately)

-- Dumping structure for table ecl_task_management.notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `task_id` bigint unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_foreign` (`user_id`),
  KEY `notifications_task_id_foreign` (`task_id`),
  CONSTRAINT `notifications_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table ecl_task_management.notifications: ~0 rows (approximately)

-- Dumping structure for table ecl_task_management.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table ecl_task_management.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table ecl_task_management.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table ecl_task_management.personal_access_tokens: ~254 rows (approximately)

-- Dumping structure for table ecl_task_management.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table ecl_task_management.sessions: ~0 rows (approximately)

-- Dumping structure for table ecl_task_management.tasks
CREATE TABLE IF NOT EXISTS `tasks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `priority` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Medium',
  `department_id` bigint unsigned DEFAULT NULL,
  `assigned_to` bigint unsigned DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `status` enum('Pending','In Progress','Completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `due_date` date DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tasks_created_by_foreign` (`created_by`),
  KEY `tasks_department_id_status_index` (`department_id`,`status`),
  KEY `tasks_assigned_to_status_index` (`assigned_to`,`status`),
  KEY `tasks_status_index` (`status`),
  KEY `tasks_due_date_index` (`due_date`),
  CONSTRAINT `tasks_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `tasks_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `tasks_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table ecl_task_management.tasks: ~0 rows (approximately)

-- Dumping structure for table ecl_task_management.task_attachments
CREATE TABLE IF NOT EXISTS `task_attachments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `task_id` bigint unsigned NOT NULL,
  `uploaded_by` bigint unsigned DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `task_attachments_task_id_foreign` (`task_id`),
  KEY `task_attachments_uploaded_by_foreign` (`uploaded_by`),
  CONSTRAINT `task_attachments_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `task_attachments_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table ecl_task_management.task_attachments: ~0 rows (approximately)

-- Dumping structure for table ecl_task_management.task_comments
CREATE TABLE IF NOT EXISTS `task_comments` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `task_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `user_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table ecl_task_management.task_comments: ~0 rows (approximately)

-- Dumping structure for table ecl_task_management.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint unsigned DEFAULT NULL,
  `department_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fcm_token` text COLLATE utf8mb4_unicode_ci,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'employee',
  `is_dual_role` tinyint(1) NOT NULL DEFAULT '0',
  `department` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `designation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_department_id_foreign` (`department_id`),
  KEY `users_parent_id_foreign` (`parent_id`),
  CONSTRAINT `users_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table ecl_task_management.users: ~2 rows (approximately)
INSERT IGNORE INTO `users` (`id`, `parent_id`, `department_id`, `name`, `email`, `fcm_token`, `phone`, `password`, `role`, `is_dual_role`, `department`, `status`, `designation`, `created_at`, `updated_at`, `branch`) VALUES
	(1, NULL, 1, 'Master Admin', 'masteradmin@coalindia.in', NULL, '+91 8670676756', '$2y$12$/4NTaQCFev7H0qVRNMMiCuBrnqDY7mioyWxFL65/9mWdXeouwYIOe', 'admin', 0, 'CCMD', 'active', 'CCMD', '2026-09-23 06:12:12', '2026-09-23 06:12:12', 'CMD Office'),
	(2, NULL, 8, 'Vivek Kumar', 'tsdp.ecl.cil@coalindia.in', NULL, '+91 9876543210', '$2y$12$0oN2SNejn9xs6sP2eHXXMe6lf8BWBMiR7gg9vIv.K/HHRdLowJ/4y', 'admin', 0, 'DHR', 'active', 'Chief Manager(HR)/TS to D(HR)', '2026-09-23 06:12:12', '2026-09-23 06:12:12', 'CMD Office');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
