-- =====================================================================
-- DATABASE SCHEMA: KREAVIO CREATIVE
-- Database: kreavio_db
-- Dibuat untuk: Tugas Mata Kuliah Digital Entrepreneurship
-- Karakteristik: Sederhana, Stabil, Standar MySQL / MariaDB (XAMPP)
-- =====================================================================

CREATE DATABASE IF NOT EXISTS `kreavio_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `kreavio_db`;

-- ---------------------------------------------------------------------
-- 1. TABEL USERS (Data Pelanggan / Customer)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(25) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 2. TABEL ADMINS (Data Pengelola / Admin Kreavio)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_admins_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 3. TABEL SERVICES (Data Layanan Desain Digital)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `services` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `description` TEXT NOT NULL,
  `price` INT UNSIGNED NOT NULL,
  `duration` VARCHAR(50) NOT NULL,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 4. TABEL ORDERS (Data Pesanan Customer)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_number` VARCHAR(50) NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `service_id` INT UNSIGNED NOT NULL,
  `deadline` DATE NOT NULL,
  `brief` TEXT NOT NULL,
  `notes` TEXT NULL,
  `total` INT UNSIGNED NOT NULL,
  `payment_status` VARCHAR(30) NOT NULL DEFAULT 'pending_payment',
  `order_status` VARCHAR(30) NOT NULL DEFAULT 'pending_payment',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_orders_number` (`order_number`),
  KEY `idx_orders_user` (`user_id`),
  KEY `idx_orders_service` (`service_id`),
  KEY `idx_orders_created_at` (`created_at`),
  CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_orders_service` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 5. TABEL PAYMENTS (Data Pembayaran Transaksi)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `payments` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` INT UNSIGNED NOT NULL,
  `transaction_id` VARCHAR(100) NOT NULL,
  `payment_type` VARCHAR(50) NOT NULL,
  `amount` INT UNSIGNED NOT NULL,
  `status` VARCHAR(50) NOT NULL,
  `paid_at` DATETIME NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_payments_order` (`order_id`),
  CONSTRAINT `fk_payments_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 6. TABEL ORDER_STATUS_LOGS (Riwayat Perubahan Status Pesanan)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `order_status_logs` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` INT UNSIGNED NOT NULL,
  `status` VARCHAR(50) NOT NULL,
  `note` TEXT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_logs_order` (`order_id`),
  CONSTRAINT `fk_logs_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
