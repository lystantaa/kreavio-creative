# IMPLEMENTASI DIGITAL ENTREPRENEURSHIP MELALUI WEBSITE JASA DESAIN DIGITAL KREAVIO CREATIVE

> **Mata Kuliah:** Digital Entrepreneurship  
> **Unit Usaha Digital:** Kreavio Creative (*"Create Better. Present Better."*)  
> **Penyusun:** Tim Mahasiswa Tugas Kelompok Digital Entrepreneurship  

---

## DAFTAR ISI

1. [Abstrak](#1-abstrak)
2. [Pendahuluan](#2-pendahuluan)
3. [Latar Belakang](#3-latar-belakang)
4. [Identifikasi Masalah](#4-identifikasi-masalah)
5. [Tujuan](#5-tujuan)
6. [Metode Pengembangan](#6-metode-pengembangan)
7. [Target Market](#7-target-market)
8. [Business Model Canvas (BMC)](#8-business-model-canvas-bmc)
9. [Perancangan Sistem](#9-perancangan-sistem)
10. [Implementasi Website](#10-implementasi-website)
11. [Implementasi Payment Gateway](#11-implementasi-payment-gateway)
12. [Strategi Digital Marketing](#12-strategi-digital-marketing)
13. [Hasil dan Pembahasan](#13-hasil-dan-pembahasan)
14. [Kelebihan dan Kekurangan](#14-kelebihan-dan-kekurangan)
15. [Kesimpulan](#15-kesimpulan)
16. [Daftar Pustaka](#16-daftar-pustaka)

---

## 1. ABSTRAK

Perkembangan era transformasi digital telah mengubah pola komunikasi visual menjadi elemen krusial dalam dunia pendidikan, karir, dan bisnis rintisan (UMKM). Mahasiswa dan pelaku usaha pemula kerap menghadapi kendala dalam memvisualisasikan gagasan secara profesional karena keterbatasan keterampilan teknis perangkat lunak desain grafis maupun keterbatasan waktu pengerjaan. Penelitian dan perancangan project ini bertujuan mengimplementasikan prinsip-prinsip *digital entrepreneurship* melalui perancangan dan pembangunan platform web mandiri bernama **Kreavio Creative**. 

Kreavio Creative merupakan unit bisnis jasa desain digital terpadu yang memfasilitasi pemesanan desain presentasi, *Curriculum Vitae* (CV) ATS-friendly, poster kegiatan, spanduk/banner, materi media sosial, dan logo branding dengan pendekatan *Single Order Checkout*. Sistem dibangun menggunakan arsitektur web berbasis PHP Native, MySQL, Bootstrap 5, serta integrasi ganda mekanisme pembayaran melalui *Midtrans Sandbox* dan *Demo Payment Mode*. Hasil implementasi menunjukkan bahwa sistem mampu mempermudah koordinasi *briefing* kebutuhan desain, mempercepat transaksi secara digital, serta menyediakan pelacakan status pengerjaan transparan bagi pelanggan. Platform ini membuktikan bahwa bisnis jasa digital berskala mikro dapat dijalankan secara efisien dengan teknologi yang terjangkau, stabil, dan tepat guna.

**Kata Kunci:** *Digital Entrepreneurship*, Jasa Desain Grafis, Kreavio Creative, PHP Native, Payment Gateway, Single Order Checkout.

---

## 2. PENDAHULUAN

Era ekonomi digital membuka peluang luas bagi mahasiswa untuk menginisiasi usaha rintisan berbasis keahlian (*skill-based business*). Desain grafis tidak lagi sekadar pelengkap estetika, melainkan aset strategis untuk membangun identitas merek (*branding*), memenangkan kompetisi pitching bisnis, merebut peluang karir, serta menyukseskan acara seminar kampus.

Meskipun demikian, model pemesanan jasa desain konvensional yang mengandalkan obrolan pesan instan tanpa platform terstruktur sering kali menimbulkan miskomunikasi *brief*, ketidakjelasan batas waktu pengerjaan (*deadline*), serta keraguan dalam proses verifikasi transfer dana manual. Berangkat dari tantangan tersebut, **Kreavio Creative** dirancang sebagai sebuah unit usaha digital mandiri dengan dukungan situs web transaksional yang mengedepankan kesederhanaan proses, keterjangkauan tarif, dan kepastian alur kerja.

---

## 3. LATAR BELAKANG

Kebutuhan akan materi visual berkualitas di lingkungan perguruan tinggi dan sektor UMKM terus mengalami lonjakan. Mahasiswa dituntut menyajikan slide presentasi tugas akhir dan sidang skripsi yang komunikatif. Di sisi lain, lulusan baru (*fresh graduate*) membutuhkan CV berstandar ATS (*Applicant Tracking System*) yang rapi guna meningkatkan peluang panggilan kerja. Sementara itu, pelaku UMKM membutuhkan materi promosi digital yang konsisten di platform media sosial seperti Instagram dan TikTok guna mempertahankan daya saing usaha.

Berdasarkan pengamatan awal di lingkungan kampus, mayoritas mahasiswa dan UMKM belum memiliki anggaran memadai untuk menyewa agensi periklanan profesional bernilai jutaan rupiah. Peluang pasar ini melahirkan celah bisnis potensial bagi penyedia jasa desain skala rintisan yang mampu menawarkan harga terjangkau (mulai Rp25.000 hingga Rp75.000) dengan kualitas terstandarisasi dan sistem pemesanan yang praktis.

---

## 4. IDENTIFIKASI MASALAH

Dari observasi lapangan, dirumuskan beberapa masalah utama:
1. **Miskomunikasi Brief:** Instruksi pemesanan yang diserahkan secara informal sering kali tercecer dan tidak terarsip dengan baik, memicu tingginya frekuensi revisi.
2. **Ketidakpastian Status Progres:** Pelanggan tidak dapat memantau secara mandiri apakah pesanan mereka sudah masuk antrean pengerjaan atau masih menunggu verifikasi.
3. **Kendala Verifikasi Pembayaran:** Verifikasi transfer bank konvensional memerlukan pengunggahan bukti transfer manual yang rentan terhadap pemalsuan struk atau keterlambatan pengecekan mutasi rekening.
4. **Ketiadaan Katalog Terstandarisasi:** Harga jasa desain sering kali berubah-ubah tanpa transparansi komponen output yang didapatkan pelanggan.

---

## 5. TUJUAN

Tujuan pelaksanaan proyek *Digital Entrepreneurship* ini adalah:
1. Merancang dan membangun website bisnis digital jasa desain mandiri **Kreavio Creative** menggunakan PHP dan MySQL.
2. Menerapkan alur pemesanan terstruktur (*Single Order Checkout*) yang mengaitkan pemilihan layanan, pengisian brief, penentuan deadline, dan checkout instan.
3. Mengintegrasikan teknologi *payment gateway* (Midtrans Sandbox) dan fitur penunjang *Demo Payment Mode* untuk kelancaran transaksi digital dan demonstrasi evaluasi akademik.
4. Menyediakan antarmuka pemantauan status pesanan bagi pelanggan (*Customer Tracking Timeline*) dan portal manajemen bagi pengelola (*Admin Panel*).
5. Menganalisis kelayakan model bisnis melalui kerangka *Business Model Canvas* (BMC).

---

## 6. METODE PENGEMBANGAN

Pengembangan produk digital ini mengadopsi pendekatan **Iteratif & Bertahap** (*Staged Development Model*):
1. **Analisis Kebutuhan & Konseptualisasi Bisnis:** Penentuan jenis 6 layanan inti, penetapan harga ramah mahasiswa, dan perumusan BMC.
2. **Perancangan Basis Data (Database Design):** Merancang skema relasi data 6 tabel utama (`users`, `admins`, `services`, `orders`, `payments`, `order_status_logs`) dengan penegakan integritas *foreign key*.
3. **Pengembangan Frontend:** Menggunakan HTML5, CSS kustom, dan Bootstrap 5 yang *clean* dan *responsive* untuk menjamin keterbacaan optimal di berbagai resolusi layar (ponsel pintar, tablet, laptop).
4. **Pengembangan Backend (Core Logic):** Menggunakan PHP 8 Native dengan ekstensi PDO (PHP Data Objects) dan *prepared statements* guna menghindari celah kerentanan SQL Injection.
5. **Integrasi Transaksi & Payment Gateway:** Menghubungkan API Midtrans Sandbox via cURL aman di sisi backend, didukung sistem bypass Demo Mode.
6. **Pengujian Fungsionalitas & Evaluasi:** Melakukan verifikasi alur otentikasi, pembuatan order, konfirmasi pembayaran, serta simulasi pengubahan status oleh administrator.

---

## 7. TARGET MARKET

Target pasar Kreavio Creative diklasifikasikan ke dalam 6 kelompok pengguna utama:
1. **Mahasiswa:** Kebutuhan desain slide presentasi tugas/sidang, poster kegiatan organisasi, dan infografis makalah.
2. **Pelajar:** Pembuatan materi presentasi sekolah dan poster karya ilmiah remaja.
3. **Pelaku UMKM:** Kebutuhan logo toko, banner jualan fisik, spanduk promosi, dan feed Instagram produk.
4. **Pencari Kerja & Fresh Graduate:** Kebutuhan desain resume dan Curriculum Vitae modern ATS-friendly.
5. **Freelancer / Konsultan Independen:** Portofolio profesional dan kartu nama digital.
6. **Organisasi & Komunitas Pemuda:** Banner acara webinar, sertifikat penghargaan, dan materi publikasi.

---

## 8. BUSINESS MODEL CANVAS (BMC)

Model bisnis Kreavio Creative dianalisis menggunakan 9 blok bangunan BMC (*Business Model Canvas*):

```text
+---------------------+-------------------+---------------------+--------------------+--------------------+
| 8. KEY PARTNERS     | 6. KEY ACTIVITIES | 2. VALUE            | 4. CUSTOMER        | 1. CUSTOMER        |
|                     | - Pembuatan       |    PROPOSITIONS     |    RELATIONSHIPS   |    SEGMENTS        |
| - Payment Gateway   |   desain digital  | - Harga terjangkau  | - Sistem website   | - Mahasiswa &      |
|   (Midtrans)        | - Manajemen order |   (ramah mahasiswa) |   otomatis         |   pelajar          |
| - Platform hosting  | - Customer service| - Pemesanan online  | - Layanan WhatsApp | - Pelaku UMKM      |
|   & domain          | - Pemasaran digital|   praktis (no cart) |   responsif        | - Pencari kerja    |
| - Komunitas kampus  +-------------------+ - Desain custom     | - Transparansi     | - Freelancer       |
|                     | 7. KEY RESOURCES  |   sesuai brief      |   tracking status  | - Organisasi       |
|                     | - Situs website   | - Pembayaran digital|                    |                    |
|                     | - Komputer kerja  |   aman & fleksibel  |                    |                    |
|                     | - Software desain |                     +--------------------+                    |
|                     | - Desainer kreatif|                     | 3. CHANNELS        |                    |
|                     |                   |                     | - Website Kreavio  |                    |
|                     |                   |                     | - Instagram & WA   |                    |
|                     |                   |                     | - Promosi Word-of- |                    |
|                     |                   |                     |   Mouth Kampus     |                    |
+---------------------+-------------------+---------------------+--------------------+--------------------+
| 9. COST STRUCTURE                                             | 5. REVENUE STREAMS                      |
| - Biaya hosting web & registrasi domain tahunan               | - Penjualan jasa Desain CV (Rp25.000)   |
| - Biaya lisensi/software desain grafis pendukung              | - Penjualan jasa Desain Banner (Rp30.000) |
| - Alokasi promosi/marketing digital di media sosial           | - Penjualan jasa Desain Poster (Rp35.000) |
| - Biaya operasional listrik, kuota internet, & tim desainer   | - Penjualan jasa Desain Sosmed (Rp40.000) |
|                                                               | - Penjualan jasa Desain PPT (Rp50.000)    |
|                                                               | - Penjualan jasa Desain Logo (Rp75.000)   |
+---------------------------------------------------------------+-----------------------------------------+
```

---

## 9. PERANCANGAN SISTEM

### 9.1 Entity Relationship Diagram (ERD Teks)
Basis data `kreavio_db` dirancang ramping dengan 6 entitas tabel yang saling terintegrasi:

```text
[USERS] (1) <---------- (N) [ORDERS] (N) ----------> (1) [SERVICES]
  - id (PK)                   - id (PK)                    - id (PK)
  - name                      - order_number (UNIQUE)      - name
  - email (UNIQUE)            - user_id (FK)               - description
  - password                  - service_id (FK)            - price
  - phone                     - deadline                   - duration
  - created_at                - brief                      - active
                              - notes                      - created_at
                              - total
                              - payment_status
                              - order_status
                              - created_at
                              - updated_at
                                  | (1)
                                  +------------ (N) [PAYMENTS]
                                  |                   - id (PK)
                                  |                   - order_id (FK)
                                  |                   - transaction_id
                                  |                   - payment_type
                                  |                   - amount
                                  |                   - status
                                  |                   - paid_at
                                  |                   - created_at
                                  |
                                  +------------ (N) [ORDER_STATUS_LOGS]
                                                      - id (PK)
                                                      - order_id (FK)
                                                      - status
                                                      - note
                                                      - created_at

[ADMINS]
  - id (PK), name, email (UNIQUE), password, created_at
```

### 9.2 Alur Transaksi Sistem (Data Flow)
```text
Pelanggan                         Sistem Kreavio                       Administrator
   |                                    |                                    |
   |---- 1. Pilih Layanan ------------->|                                    |
   |---- 2. Isi Brief & Deadline ------>| (Simpan Order: pending_payment)    |
   |---- 3. Bayar (Midtrans/Demo) ----->|                                    |
   |                                    |---- Simpan Payment: paid --------->|
   |                                    |---- Order: menunggu_proses ------->| (Notifikasi Pesanan Masuk)
   |<--- 4. Lihat Status Timeline ------|                                    |
   |                                    |<--- 5. Update: sedang_dikerjakan --|
   |<--- 6. Status Real-time berubah ---|                                    |
   |                                    |<--- 7. Update: selesai ------------|
   |<--- 8. Desain Diterima ------------|                                    |
```

---

## 10. IMPLEMENTASI WEBSITE

Sistem diimplementasikan ke dalam 17 berkas halaman terstruktur:
- **Modul Publik:** Beranda (`index.php`), Katalog Layanan (`services.php`), Rincian Layanan (`service-detail.php`), Profil Usaha (`about.php`), Formulir Kontak (`contact.php`), Pendaftaran Customer (`register.php`), dan Masuk Customer (`login.php`).
- **Modul Transaksi:** Pemesanan Mandiri (`order.php`), Verifikasi Pembayaran (`checkout.php`), dan Tanda Terima Sukses (`success.php`).
- **Modul Pelanggan:** Panel Statistik (`customer/dashboard.php`), Daftar Riwayat (`customer/orders.php`), dan Pelacak Status Progres (`customer/order-detail.php`).
- **Modul Administrator:** Autentikasi Pengelola (`admin/login.php`), Panel Ringkasan Bisnis (`admin/index.php`), Pemfilteran Pesanan (`admin/orders.php`), Pengubah Status Pengerjaan (`admin/order-detail.php`), serta Pengelolaan Katalog Layanan (`admin/services.php`, `service-create.php`, `service-edit.php`, `service-delete.php`).

Keamanan menyeluruh diterapkan melalui pembungkusan `password_hash()` berstandar industri, eliminasi potensi manipulasi SQL dengan *PDO Prepared Statements*, sanitasi luaran antarmuka dengan `htmlspecialchars()` / `e()`, pengamanan rute dengan proteksi sesi peranan (*session role protection*), pencegahan pembajakan sesi dengan `session_regenerate_id()`, proteksi *HttpOnly* cookie, serta proteksi berkas sensitif (`.env` dan skrip SQL) menggunakan konfigurasi `.htaccess` berstatus `403 Forbidden`.

---

## 11. IMPLEMENTASI SISTEM PEMBAYARAN (MIDTRANS PAYMENT GATEWAY)

Website mengimplementasikan Midtrans Snap API versi Sandbox yang aman sebagai solusi pemrosesan transaksi terpusat:
- **Arsitektur Keamanan Token:** Seluruh komunikasi pertukaran token pembayaran dilakukan dari sisi *backend* (`payment/create.php`) menggunakan *native PHP cURL*, sehingga *Server Key* terlindungi secara aman dan tidak pernah terekspos pada JavaScript *frontend*.
- **Multichannel Digital Payment:** Pelanggan menerima *Snap Token* untuk menampilkan *modal popup* resmi dengan ragam metode pembayaran digital nasional: **QRIS**, **Virtual Account Bank (BCA, BNI, BRI, Mandiri)**, dan **E-Wallet (GoPay)**.
- **Resilience & Key Guard:** Sistem dilengkapi *auto-swap guard* pada pembacaan kredensial di konfigurasi untuk mencegah pertukaran posisi kunci secara otomatis.
- **Siklus Transaksi Konsisten:** Penutupan pop-up secara sepihak (tombol silang X) ditangani secara disiplin sehingga menjaga status pesanan tetap aman (*pending payment*) dan hanya mengubah status menjadi `paid` dan `menunggu_proses` ketika verifikasi pembayaran berhasil diterima. Rekam jejak audit tersimpan rapi pada tabel `payments` dan `order_status_logs`.

---

## 12. STRATEGI DIGITAL MARKETING

Untuk menjangkau target pasar secara optimal, Kreavio Creative merancang bauran strategi pemasaran digital:
1. **Pemasaran Media Sosial Organik (Content Marketing):**
   - Menayangkan konten tips visual CV ATS-friendly di TikTok dan Instagram Reels.
   - Menampilkan perbandingan desain *Before & After* slide presentasi untuk memperlihatkan nilai transformasi visual.
2. **Promosi Komunitas Kampus (Word-of-Mouth):**
   - Menawarkan paket promo tugas akhir bagi himpunan mahasiswa jurusan.
   - Menjadi sponsor media partner pada kegiatan seminar kampus.
3. **Pemasaran Percakapan (Conversational Commerce):**
   - Integrasi tombol WhatsApp langsung dari halaman detail pesanan untuk mempercepat konsultasi file master atau instruksi lanjutan.

---

## 13. HASIL DAN PEMBAHASAN

Berdasarkan pengujian internal dan uji coba pengguna awal (*Alpha Testing*):
- **Keberhasilan Transaksi:** [ISI BERDASARKAN HASIL SURVEY KELOMPOK, contoh: Dari 15 pesanan uji coba yang dilakukan pengguna simulasi, 100% pesanan berhasil terdata di sistem dan diproses ke tahap checkout].
- **Respon Waktu Pemesanan:** Rata-rata waktu yang dibutuhkan pengguna dari memilih layanan hingga menyelesaikan checkout berada pada kisaran [ISI BERDASARKAN HASIL SURVEY KELOMPOK, contoh: 2 hingga 3 menit], menunjukkan bahwa mekanisme *Single Order Checkout* sangat efisien dibandingkan sistem keranjang belanja multi-langkah.
- **Tingkat Kepuasan Antarmuka:** [ISI BERDASARKAN HASIL SURVEY KELOMPOK, contoh: 88% responden menyatakan tata letak website mudah dipahami dan tampilan status tracking timeline memberikan rasa aman terhadap progres pesanan].

---

## 14. KELEBIHAN DAN KEKURANGAN

### Kelebihan:
1. **Kesederhanaan Arsitektur:** Tanpa framework yang membebani memori, website dapat dijalankan seketika di XAMPP komputer manapun.
2. **Alur Pemesanan Cepat:** Pendekatan *Single Order Checkout* mengurangi *friction* pembelian.
3. **Keandalan Uji Coba:** Dukungan *Demo Payment Mode* menjamin presentasi bebas dari kegagalan jaringan eksternal.
4. **Keamanan Data Terjaga:** Integritas relasional basis data mencegah terhapusnya layanan yang memiliki riwayat transaksi aktif.

### Kekurangan & Peluang Pengembangan Mendatang:
1. **Ketiadaan Fitur Unggah File:** Saat ini file referensi masih dikoordinasikan melalui tautan Google Drive / WhatsApp guna menjaga kode tetap ramah pemula.
2. **Belum Ada Notifikasi SMS/WhatsApp Otomatis:** Status pesanan baru dapat dilihat saat pengguna mengakses dashboard.
3. **Belum Tersedia Opsi Pembayaran Multi-Item:** Pesanan dilakukan per satu produk desain untuk menjaga fokus transaksi.

---

## 15. KESIMPULAN

Proyek pembangunan website **Kreavio Creative** membuktikan bahwa prinsip *digital entrepreneurship* dapat diwujudkan secara efektif oleh mahasiswa melalui platform mandiri berskala mikro. Dengan berfokus pada kebutuhan riil mahasiswa dan UMKM di bidang visual digital, penerapan kode yang sederhana, struktur database relasional yang rapi, serta integrasi simulasi pembayaran digital, Kreavio Creative berhasil memenuhi seluruh kriteria fungsional tugas mata kuliah tanpa terjebak dalam kerumitan arsitektur piranti lunak yang berlebihan. Sistem ini siap dipresentasikan, mudah dipelihara, dan memiliki prospek komersial nyata untuk dikembangkan lebih lanjut.

---

## 16. DAFTAR PUSTAKA

1. Chaffey, D., & Ellis-Chadwick, F. (2019). *Digital Marketing: Strategy, Implementation and Practice* (7th ed.). Pearson Education.
2. Connolly, T., & Begg, C. (2015). *Database Systems: A Practical Approach to Design, Implementation, and Management* (6th ed.). Pearson.
3. Kotler, P., & Armstrong, G. (2021). *Principles of Marketing* (18th ed.). Pearson.
4. Midtrans API Documentation. (2024). *Snap API Reference Guide*. Midtrans Developer Portal.
5. Osterwalder, A., & Pigneur, Y. (2010). *Business Model Generation: A Handbook for Visionaries, Game Changers, and Challengers*. John Wiley & Sons.
6. PHP Documentation Group. (2024). *PHP Manual: PHP Data Objects (PDO)*. https://www.php.net/manual/en/book.pdo.php
7. [TAMBAHKAN SUMBER BUKU / JURNAL REFERENSI KELOMPOK LAINNYA JIKA DIPERLUKAN]
