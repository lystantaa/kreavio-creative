-- =====================================================================
-- DATABASE SEED: KREAVIO CREATIVE
-- Database: kreavio_db
-- Dibuat untuk: Data Awal & Testing Demo
-- =====================================================================

USE `kreavio_db`;

-- Kosongkan tabel jika ingin mengulang (urutan memperhatikan foreign key)
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE `order_status_logs`;
TRUNCATE TABLE `payments`;
TRUNCATE TABLE `orders`;
TRUNCATE TABLE `services`;
TRUNCATE TABLE `admins`;
TRUNCATE TABLE `users`;
SET FOREIGN_KEY_CHECKS = 1;

-- ---------------------------------------------------------------------
-- 1. SEED ADMIN
-- Email: admin@kreavio.test | Password: admin123
-- ---------------------------------------------------------------------
INSERT INTO `admins` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Administrator Kreavio', 'admin@kreavio.test', '$2y$10$Ncd5pRUefdZONeirnf74aeTQqDvEGtU5t/2g0GSWBE4MkMTe.yZ8O', NOW()),
(2, 'Administrator Kreavio', 'admin@krevio.test', '$2y$10$Ncd5pRUefdZONeirnf74aeTQqDvEGtU5t/2g0GSWBE4MkMTe.yZ8O', NOW());

-- ---------------------------------------------------------------------
-- 2. SEED USERS (CUSTOMER)
-- Password untuk semua customer demo: password123
-- ---------------------------------------------------------------------
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `created_at`) VALUES
(1, 'Budi Pratama', 'budi@example.com', '$2y$10$fBY/nrnyW77KRJt7fR1k7eiZt047RKlfMNgEiHyD46M.0YF5Vs/Ca', '081234567890', NOW()),
(2, 'Siti Rahma', 'siti@example.com', '$2y$10$fBY/nrnyW77KRJt7fR1k7eiZt047RKlfMNgEiHyD46M.0YF5Vs/Ca', '089876543210', NOW());

-- ---------------------------------------------------------------------
-- 3. SEED SERVICES (6 LAYANAN DESAIN)
-- ---------------------------------------------------------------------
INSERT INTO `services` (`id`, `name`, `description`, `price`, `duration`, `active`, `created_at`) VALUES
(1, 'Desain CV', 'Pembuatan Curriculum Vitae (CV) profesional, ATS-friendly, dan modern untuk melamar kerja atau magang. Termasuk format PDF siap cetak dan file pendukung.', 25000, '1-2 Hari', 1, NOW()),
(2, 'Desain PowerPoint', 'Desain slide presentasi PowerPoint / Canva yang menarik, komunikatif, dan rapi untuk tugas kuliah, sidang skripsi, pitching bisnis, maupun seminar.', 50000, '2-3 Hari', 1, NOW()),
(3, 'Desain Poster', 'Desain poster promosi acara kampus, seminar, bazar, event organisasi, maupun infografis edukatif dengan komposisi visual yang estetik dan informatif.', 35000, '1-2 Hari', 1, NOW()),
(4, 'Desain Banner', 'Desain banner cetak (spanduk, roll up banner, x-banner) maupun banner web/digital untuk branding toko, promosi jualan UMKM, dan kegiatan.', 30000, '1-2 Hari', 1, NOW()),
(5, 'Desain Social Media', 'Paket desain feed & story Instagram/TikTok untuk kebutuhan personal branding, promosi produk UMKM, dan konten informatif agar akun terlihat kredibel.', 40000, '1-3 Hari', 1, NOW()),
(6, 'Desain Logo', 'Perancangan logo identitas brand bisnis pemula, UMKM, toko online, atau komunitas organisasi. Desain orisinal, filosofis, dan scalable.', 75000, '2-4 Hari', 1, NOW());

-- ---------------------------------------------------------------------
-- 4. SEED ORDERS (CONTOH PESANAN TESTING)
-- ---------------------------------------------------------------------
INSERT INTO `orders` (`id`, `order_number`, `user_id`, `service_id`, `deadline`, `brief`, `notes`, `total`, `payment_status`, `order_status`, `created_at`, `updated_at`) VALUES
(1, 'ORD-20260901-001', 1, 1, DATE_ADD(CURRENT_DATE, INTERVAL 2 DAY), 'Tolong buatkan CV untuk melamar posisi UI/UX Designer fresh graduate. Warna tema navy blue minimalis, ada bagian skill tools Figma dan portfolio link.', 'Tolong kirimkan versi PDF high quality ya kak.', 25000, 'paid', 'sedang_dikerjakan', DATE_SUB(NOW(), INTERVAL 2 DAY), NOW()),
(2, 'ORD-20260902-002', 1, 3, DATE_ADD(CURRENT_DATE, INTERVAL 3 DAY), 'Poster webinar kewirausahaan mahasiswa tema "Membangun Startup dari Kampus". Warna cerah orange dan biru, narasumber 2 orang.', 'Foto narasumber akan saya kirimkan.', 35000, 'paid', 'selesai', DATE_SUB(NOW(), INTERVAL 1 DAY), NOW()),
(3, 'ORD-20260903-003', 2, 6, DATE_ADD(CURRENT_DATE, INTERVAL 5 DAY), 'Logo untuk brand minuman kopi kekinian "Kopi Kita". Konsep modern, elegan, dominan warna cokelat kopi dan krem.', 'Ingin logo yang simpel dan mudah diingat.', 75000, 'paid', 'menunggu_proses', NOW(), NOW()),
(4, 'ORD-20260903-004', 2, 2, DATE_ADD(CURRENT_DATE, INTERVAL 4 DAY), 'Desain presentasi 15 slide untuk tugas akhir mata kuliah kewirausahaan. Tema pitch deck startup agritech.', 'Slide harus banyak infografis dan icon.', 50000, 'pending_payment', 'pending_payment', NOW(), NOW());

-- ---------------------------------------------------------------------
-- 5. SEED PAYMENTS (CONTOH PEMBAYARAN)
-- ---------------------------------------------------------------------
INSERT INTO `payments` (`id`, `order_id`, `transaction_id`, `payment_type`, `amount`, `status`, `paid_at`, `created_at`) VALUES
(1, 1, 'PAY-DEMO-001', 'demo_simulator', 25000, 'settlement', DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY)),
(2, 2, 'PAY-DEMO-002', 'demo_simulator', 35000, 'settlement', DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY)),
(3, 3, 'PAY-DEMO-003', 'demo_simulator', 75000, 'settlement', NOW(), NOW());

-- ---------------------------------------------------------------------
-- 6. SEED ORDER STATUS LOGS (CATATAN RIWAYAT STATUS)
-- ---------------------------------------------------------------------
INSERT INTO `order_status_logs` (`id`, `order_id`, `status`, `note`, `created_at`) VALUES
(1, 1, 'pending_payment', 'Pesanan dibuat oleh customer', DATE_SUB(NOW(), INTERVAL 2 DAY)),
(2, 1, 'menunggu_proses', 'Pembayaran berhasil dikonfirmasi', DATE_SUB(NOW(), INTERVAL 2 DAY)),
(3, 1, 'sedang_dikerjakan', 'Pesanan sedang dikerjakan oleh tim desainer Kreavio', DATE_SUB(NOW(), INTERVAL 1 DAY)),

(4, 2, 'pending_payment', 'Pesanan dibuat oleh customer', DATE_SUB(NOW(), INTERVAL 1 DAY)),
(5, 2, 'menunggu_proses', 'Pembayaran berhasil dikonfirmasi', DATE_SUB(NOW(), INTERVAL 1 DAY)),
(6, 2, 'sedang_dikerjakan', 'Pesanan sedang dikerjakan oleh tim desainer Kreavio', DATE_SUB(NOW(), INTERVAL 1 DAY)),
(7, 2, 'selesai', 'Desain telah selesai dan file hasil telah diserahkan', NOW()),

(8, 3, 'pending_payment', 'Pesanan dibuat oleh customer', NOW()),
(9, 3, 'menunggu_proses', 'Pembayaran berhasil dikonfirmasi via Demo Payment Mode', NOW()),

(10, 4, 'pending_payment', 'Pesanan dibuat oleh customer, menunggu pembayaran', NOW());
