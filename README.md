# Kreavio Creative - Website Jasa Desain Digital Sederhana

> **Tagline:** *"Create Better. Present Better."*  
> **Mata Kuliah:** Digital Entrepreneurship  
> **Platform:** PHP 8+ Native, MySQL, Bootstrap 5, Midtrans Sandbox & Demo Payment Mode

---

## 1. Project Overview

**Kreavio Creative** adalah website bisnis digital yang menyediakan jasa desain grafis digital sederhana. Unit bisnis ini melayani kebutuhan desain visual untuk:
- Mahasiswa & pelajar (slide presentasi sidang/tugas, poster acara, laporan)
- Pelaku UMKM (desain banner promosi, logo branding, konten media sosial)
- Freelancer & pencari kerja (desain Curriculum Vitae ATS-friendly)
- Organisasi & komunitas kampus

Website ini **BUKAN marketplace** (tidak ada multi-vendor/toko pihak ketiga). Hanya ada satu penjual jasa, yaitu **Kreavio Creative**.

Tujuan utama sistem:
1. Menampilkan katalog jasa desain dengan harga transparan.
2. Memfasilitasi pelanggan membuat pesanan (brief kebutuhan & deadline) melalui **Single Order Checkout**.
3. Menyediakan simulasi pembayaran digital melalui **Midtrans Sandbox** dan **Demo Payment Mode**.
4. Memungkinkan administrator mengelola daftar layanan (CRUD) dan mengubah status pesanan.
5. Memungkinkan customer memantau perkembangan status pesanannya secara transparan.

---

## 2. Fitur Utama

### A. Pengunjung (Publik)
- **Homepage:** Hero section menarik, 4 pilar keunggulan, showcase layanan dari database, 4 langkah pemesanan, dan Call to Action.
- **Katalog Layanan (`services.php`):** Menampilkan daftar layanan aktif beserta harga dan estimasi durasi pengerjaan.
- **Detail Layanan (`service-detail.php`):** Menampilkan spesifikasi hasil yang didapatkan, harga, dan tombol langsung pesan.
- **Tentang Kami (`about.php`):** Informasi latar belakang bisnis, visi misi, target pasar, dan nilai utama.
- **Kontak (`contact.php`):** Info WhatsApp CS, email resmi, dan formulir pesan singkat.
- **Autentikasi Akun:** Registrasi customer baru dengan validasi password hash dan login customer.

### B. Pelanggan (Customer Portal)
- **Customer Dashboard (`customer/dashboard.php`):** Ringkasan statistik pesanan (total, belum bayar, sedang diproses, selesai) dan tabel pesanan terkini.
- **Formulir Order (`order.php`):** Form pemesanan mudah tanpa keranjang (Single Order Checkout) berisi pilihan layanan, deadline, brief deskripsi, dan catatan.
- **Checkout & Pembayaran (`checkout.php`):** Ringkasan biaya pesanan terpadu dengan opsi **Midtrans Sandbox** dan **Demo Payment Simulator**.
- **Bukti Pembayaran (`success.php`):** Konfirmasi pembayaran lunas dan struk transaksi.
- **Pesanan Saya (`customer/orders.php`):** Daftar riwayat seluruh pesanan yang pernah dibuat.
- **Detail & Tracking Pesanan (`customer/order-detail.php`):** Visual timeline progres 5 langkah (*Pesanan Dibuat &rarr; Dibayar &rarr; Diproses &rarr; Dikerjakan &rarr; Selesai*) serta riwayat aktivitas status.

### C. Pengelola (Administrator Portal)
- **Admin Login (`admin/login.php`):** Keamanan login terpisah khusus admin menggunakan session.
- **Admin Dashboard (`admin/index.php`):** 5 kartu metrik status (Total Pesanan, Menunggu Bayar, Menunggu Proses, Sedang Dikerjakan, Selesai) dan kalkulasi total omzet.
- **Kelola Pesanan (`admin/orders.php`):** Filter pencarian pesanan berdasarkan status dan keyword.
- **Detail Pesanan & Ubah Status (`admin/order-detail.php`):** Membaca brief pesanan, nomor WhatsApp customer, serta form mengubah status pengerjaan dan status pembayaran dengan catatan kustom.
- **CRUD Layanan Desain (`admin/services.php`):** Tambah layanan baru (`service-create.php`), edit layanan (`service-edit.php`), dan hapus/arsip layanan (`service-delete.php`).
- **Cetak Invoice Resmi (`admin/invoice.php`):** Halaman cetak invoice digital per transaksi dengan format standar profesional siap cetak atau simpan PDF.
- **Rekap Laporan Penjualan (`admin/sales-report.php`):** Laporan ringkasan transaksi & omzet berdasarkan rentang periode waktu (Hari Ini, Minggu Ini, Bulan Ini, dan Custom Date Range).
- **Ekspor Rekap ke Excel/CSV (`admin/export-excel.php`):** Unduh laporan transaksi dalam format CSV berstandar UTF-8 BOM yang kompatibel langsung dengan Microsoft Excel & Google Sheets.

---

## 3. Teknologi yang Digunakan

- **Frontend:**
  - HTML5 & CSS3 (Custom styling modern & clean di `assets/css/style.css`)
  - Bootstrap 5.3 (Responsive grid, modal, alerts, buttons)
  - Bootstrap Icons (Iconography)
  - JavaScript Vanilla (Interaksi modal, notifikasi auto-dismiss, Midtrans Snap handler)
- **Backend:**
  - PHP 8+ Native (Procedural, bersih, mudah dipelajari pemula)
  - PDO (PHP Data Objects) dengan Real Prepared Statements untuk keamanan database
  - Password Hash (`password_hash` & `password_verify` BCRYPT)
- **Database:**
  - MySQL / MariaDB (6 tabel utama terelasi dengan Foreign Key)
- **Payment Gateway:**
  - Midtrans Sandbox Snap API (Native cURL di backend)
  - Demo Payment Simulator (Bypass 1-klik untuk presentasi dosen tanpa perlu koneksi internet atau credential)

---

## 4. Kebutuhan Sistem (Prerequisites)

1. Komputer / Laptop (Windows / macOS / Linux).
2. XAMPP versi 8.0 ke atas (sudah mencakup Apache & MySQL).
3. Web browser modern (Google Chrome, Microsoft Edge, Mozilla Firefox).

---

## 5. Panduan Instalasi Menggunakan XAMPP

### Langkah 1: Penempatan Folder Project
1. Buka folder `htdocs` pada instalasi XAMPP Anda (biasanya di `C:\xampp\htdocs\`).
2. Buat folder baru bernama `kreavio` atau salin seluruh file project ini ke dalam:
   ```text
   C:\xampp\htdocs\kreavio\
   ```

### Langkah 2: Menjalankan Web Server XAMPP
1. Buka aplikasi **XAMPP Control Panel**.
2. Klik tombol **Start** pada modul **Apache**.
3. Klik tombol **Start** pada modul **MySQL**.
4. Pastikan kedua modul berwarna hijau (Running).

---

## 6. Cara Membuat & Import Database

1. Buka browser dan akses phpMyAdmin melalui link:
   ```text
   http://localhost/phpmyadmin/
   ```
2. Anda dapat mengimpor database dengan 2 cara:

#### Cara Cepat (Import file):
- Klik menu **Import** pada bar navigasi atas phpMyAdmin.
- Klik **Choose File** lalu pilih file:
  `database/schema.sql` (klik **Import** di bagian bawah).
- Setelah selesai, ulangi klik menu **Import**, pilih file:
  `database/seed.sql` (klik **Import**).

#### Cara Manual (SQL Tab):
- Klik tab **SQL** pada phpMyAdmin.
- Buka file `database/schema.sql` menggunakan Notepad, salin seluruh isinya, lalu tempel (*paste*) ke kotak SQL dan klik **Go**.
- Lakukan hal yang sama untuk file `database/seed.sql`.

Database `kreavio_db` beserta 6 tabel dan data awal testing otomatis terpasang dengan lengkap.

---

## 7. Konfigurasi Database & Environment

Konfigurasi database berada di file `config/config.php` dengan setelan default XAMPP:
- Host: `localhost`
- Port: `3306`
- Database: `kreavio_db`
- User: `root`
- Password: *(kosong)*

Jika Anda ingin mengubah setelan (misalnya jika MySQL Anda memiliki password), Anda cukup membuat file `.env` dengan menyalin template dari `.env.example`:
```ini
DB_HOST=localhost
DB_PORT=3306
DB_NAME=kreavio_db
DB_USER=root
DB_PASS=rahasia123
```

---

## 8. Konfigurasi Sistem Pembayaran (Midtrans Payment Gateway)

Website Kreavio Creative mengintegrasikan jalur pembayaran digital resmi menggunakan **Midtrans Snap API (Sandbox)**:
- Mendukung metode pembayaran digital: **QRIS**, **Virtual Account Bank (BCA, BNI, BRI, Mandiri)**, dan **E-Wallet (GoPay)**.
- Kredensial diatur melalui file [`.env`](file:///.env):
  ```ini
  MIDTRANS_SERVER_KEY=Mid-server-xxxxxxxxxxxxxxxxx
  MIDTRANS_CLIENT_KEY=Mid-client-xxxxxxxxxxxxxxxxx
  MIDTRANS_IS_PRODUCTION=false
  ```
- Dilengkapi sistem proteksi *auto-swap guard* di [`config/config.php`](file:///config/config.php) untuk mencegah kekeliruan posisi Server Key dan Client Key.
- Tombol silang (X) pada pop-up tidak membatalkan transaksi secara sembarangan dan menjaga status pesanan tetap aman (*pending payment*).

---

## 9. Fitur Keamanan Sistem (Security Hardening)

Website ini telah mengimplementasikan standar keamanan web aplikasi yang kokoh:
1. **Proteksi File Sensitif (`.htaccess`):** Memblokir akses langsung publik terhadap file [`.env`](file:///.env), skrip database `.sql`, file dokumentasi `.md`, dan log dengan respon `403 Forbidden`. Menonaktifkan *directory indexing* (`Options -Indexes`).
2. **Keamanan Sesi Login (Anti Session Fixation):** Menerapkan `session_regenerate_id(true)` saat login customer, login admin, dan pendaftaran akun baru.
3. **Pengamanan Cookie (Anti XSS Cookie Hijacking):** Mengaktifkan flag `session.cookie_httponly = 1` dan `session.use_only_cookies = 1`.
4. **Keamanan Database (Anti SQL Injection):** Seluruh manipulasi database menggunakan **PDO Prepared Statements** asli (`PDO::ATTR_EMULATE_PREPARES => false`) dengan parameter binding (`?`).
5. **Keamanan Tampilan (Anti Cross-Site Scripting / XSS):** Seluruh data dinamis di-escape menggunakan fungsi pembantu `e()` (`htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`).
6. **Kontrol Akses Ketat (Anti IDOR & Privilege Escalation):** Akses data pesanan pelanggan dibatasi secara ketat berdasarkan sesi user yang aktif (`WHERE user_id = ?`). Seluruh halaman admin dilindungi middleware `require_admin()`.
7. **Standarisasi Kontak WhatsApp:** Format nomor telepon otomatis disesuaikan ke kode negara internasional (`628xxx`) melalui helper `format_whatsapp_number()`.

---

## 10. Akun Uji Coba (Demo Accounts)

Untuk mempermudah dosen atau penilai melakukan pengujian, gunakan akun bawaan berikut:

### Akun Administrator
- **URL Login:** `http://localhost/kreavio/admin/login.php`
- **Email:** `admin@kreavio.test`
- **Password:** `admin123`

### Akun Customer Demo 1
- **URL Login:** `http://localhost/kreavio/login.php`
- **Email:** `budi@example.com`
- **Password:** `password123`

### Akun Customer Demo 2
- **Email:** `siti@example.com`
- **Password:** `password123`

*(Anda juga bisa mendaftarkan akun customer baru secara bebas melalui menu Register).*

---

## 11. Panduan Penggunaan & Alur Kerja (Flow Sistem)

### Alur Customer:
1. **Melihat Layanan:** Buka `http://localhost/kreavio/` lalu telusuri 6 layanan desain (CV, PPT, Poster, Banner, Sosmed, Logo).
2. **Pilih Layanan:** Klik tombol **Pesan Sekarang** pada layanan yang diminati.
3. **Login / Register:** Jika belum login, sistem akan meminta Anda login/daftar terlebih dahulu.
4. **Isi Form Order (`order.php`):** Tentukan deadline, ketik instruksi brief desain, dan catatan opsional.
5. **Checkout (`checkout.php`):** Periksa rincian biaya pesanan.
6. **Lakukan Pembayaran:** Klik tombol **"Bayar via Midtrans"** untuk membuka pop-up Snap dan pilih metode pembayaran digital (QRIS, VA Bank, GoPay).
7. **Pesanan Dikonfirmasi (`success.php`):** Status otomatis berubah menjadi `paid` dan `menunggu_proses`.
8. **Pantau Progres (`customer/order-detail.php`):** Pantau perkembangan desain melalui timeline visual 5 langkah.

### Alur Administrator:
1. **Login Admin:** Akses `http://localhost/kreavio/admin/login.php`.
2. **Lihat Dashboard:** Pantau jumlah pesanan masuk dan total pendapatan.
3. **Buka Pesanan:** Klik menu **Daftar Pesanan**, pilih pesanan yang baru masuk.
4. **Ubah Status Pengerjaan:**
   - Ubah status menjadi `sedang_dikerjakan` saat desainer mulai membuat draft.
   - Ubah status menjadi `selesai` setelah file desain final dikirimkan ke pelanggan.
   - Masukkan catatan update jika diperlukan.
5. **Cek Sisi Customer:** Saat customer me-refresh halaman detail pesanannya, status timeline akan otomatis maju secara real-time!
6. **Kelola Layanan:** Admin dapat menambah layanan baru, mengubah tarif harga, atau menonaktifkan layanan melalui menu **Kelola Layanan**.

---

## 12. Struktur Folder Project

```text
kreavio/
├── admin/                     # Modul Administrator
│   ├── index.php              # Dashboard admin (metrik ringkasan)
│   ├── login.php              # Autentikasi login admin
│   ├── logout.php             # Logout admin
│   ├── orders.php             # Daftar dan filter seluruh pesanan
│   ├── order-detail.php       # Detail pesanan & form update status
│   ├── services.php           # Tabel daftar layanan desain (CRUD Read)
│   ├── service-create.php     # Form tambah layanan baru (CRUD Create)
│   ├── service-edit.php       # Form edit layanan (CRUD Update)
│   └── service-delete.php     # Script hapus/arsip layanan (CRUD Delete)
│
├── customer/                  # Modul Pelanggan
│   ├── dashboard.php          # Dashboard ringkasan customer
│   ├── orders.php             # Riwayat pesanan customer
│   └── order-detail.php       # Detail pesanan & visual status timeline
│
├── config/                    # Pengaturan Sistem
│   ├── config.php             # Setelan umum, konstanta, helper format
│   └── database.php           # Koneksi PDO MySQL & error handler
│
├── includes/                  # Komponen Reusable
│   ├── header.php             # Template head & navbar customer
│   ├── footer.php             # Template footer website
│   ├── auth.php               # Middleware proteksi login customer
│   └── admin-auth.php         # Middleware proteksi login admin
│
├── payment/                   # Modul Pembayaran
│   ├── create.php             # Pengambilan Midtrans Snap Token via cURL
│   ├── notification.php       # Webhook notifikasi transaksi Midtrans
│   └── demo.php               # Simulator instan pembayaran demo
│
├── assets/                    # Asset Statis
│   ├── css/
│   │   └── style.css          # Stylesheet kustom utama
│   ├── js/
│   │   └── app.js             # Skrip vanilla JS & Snap payment
│   └── images/                # Logo & ilustrasi SVG 6 layanan
│
├── database/                  # Skrip SQL
│   ├── schema.sql             # Struktur 6 tabel database MySQL
│   └── seed.sql               # Data awal admin, customer, & layanan
│
├── documentation/             # Berkas Laporan Akademik
│   ├── manual-book-outline.md # Panduan pengoperasian (Manual Book)
│   └── article-outline.md     # Bahan artikel ilmiah / final project paper
│
├── index.php                  # Halaman Beranda (Homepage)
├── services.php               # Katalog seluruh layanan
├── service-detail.php         # Halaman spesifikasi detail per layanan
├── about.php                  # Halaman profil bisnis & nilai usaha
├── contact.php                # Halaman kontak & formulir konsultasi
├── login.php                  # Form login customer
├── register.php               # Form registrasi akun customer baru
├── logout.php                 # Logout customer
├── order.php                  # Formulir pemesanan desain (Single Order)
├── checkout.php               # Konfirmasi pembayaran
├── success.php                # Struk sukses pembayaran
├── .env.example               # Contoh konfigurasi environment
├── .gitignore                 # Daftar file abaikan git
└── README.md                  # Dokumentasi lengkap sistem
```

---

## 13. Checklist Pengujian Mandiri (Self Testing)

- [x] Register customer baru berhasil tersimpan ke tabel `users` dengan `password_hash`.
- [x] Login customer berhasil dan session tersimpan dengan perlindungan `session_regenerate_id`.
- [x] Customer dapat melihat daftar 6 layanan desain dari database.
- [x] Customer dapat membuat pesanan baru melalui `order.php`.
- [x] Pesanan tersimpan di tabel `orders` dengan status awal `pending_payment`.
- [x] Halaman checkout menampilkan ringkasan pesanan dengan benar.
- [x] Metode pembayaran **Midtrans Snap Sandbox** (QRIS, VA Bank, GoPay) berhasil mengubah status menjadi `paid` dan `menunggu_proses`.
- [x] Riwayat pembayaran tercatat di tabel `payments` dan `order_status_logs`.
- [x] Admin dapat login dengan akun `admin@kreavio.test`.
- [x] Admin dapat melihat pesanan baru di panel admin.
- [x] Admin dapat mengubah status pesanan (`menunggu_proses` &rarr; `sedang_dikerjakan` &rarr; `selesai`).
- [x] Customer melihat perubahan status pada halaman tracking detail pesanan secara real-time.
- [x] Admin dapat melakukan Create, Read, Update, Delete (CRUD) pada layanan desain.
- [x] Berkas sensitif (`.env`, `schema.sql`, `README.md`) terlindungi oleh `.htaccess` dengan respon `403 Forbidden`.
- [x] Seluruh input terlindungi dari SQL Injection (PDO Prepared Statements) dan XSS (`e()`).
- [x] Tampilan website responsive di layar Desktop, Tablet, dan Smartphone.
