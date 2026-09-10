# PANDUAN PENGGUNAAN SISTEM (MANUAL BOOK)
## WEBSITE BISNIS JASA DESAIN DIGITAL "KREAVIO CREATIVE"
### Tugas Mata Kuliah Digital Entrepreneurship

---

## DAFTAR ISI

- [BAB 1: PENDAHULUAN](#bab-1-pendahuluan)
  - [1.1 Tujuan Website](#11-tujuan-website)
  - [1.2 Tentang Kreavio Creative](#12-tentang-kreavio-creative)
- [BAB 2: PANDUAN PENGGUNA (CUSTOMER)](#bab-2-panduan-pengguna-customer)
  - [2.1 Membuka Website](#21-membuka-website)
  - [2.2 Mendaftar Akun Baru (Register)](#22-mendaftar-akun-baru-register)
  - [2.3 Masuk ke Akun (Login)](#23-masuk-ke-akun-login)
  - [2.4 Melihat Daftar Layanan](#24-melihat-daftar-layanan)
  - [2.5 Memilih Layanan & Melihat Detail](#25-memilih-layanan--melihat-detail)
  - [2.6 Membuat Pesanan (Form Order)](#26-membuat-pesanan-form-order)
  - [2.7 Halaman Checkout](#27-halaman-checkout)
  - [2.8 Melakukan Pembayaran (Midtrans / Demo Simulator)](#28-melakukan-pembayaran-midtrans--demo-simulator)
  - [2.9 Melihat Status & Timeline Pesanan](#29-melihat-status--timeline-pesanan)
  - [2.10 Melihat Riwayat Pesanan (Pesanan Saya)](#210-melihat-riwayat-pesanan-pesanan-saya)
  - [2.11 Logout Customer](#211-logout-customer)
- [BAB 3: PANDUAN PENGELOLA (ADMINISTRATOR)](#bab-3-panduan-pengelola-administrator)
  - [3.1 Login Administrator](#31-login-administrator)
  - [3.2 Melihat Dashboard Admin](#32-melihat-dashboard-admin)
  - [3.3 Melihat & Menyaring Daftar Pesanan](#33-melihat--menyaring-daftar-pesanan)
  - [3.4 Melihat Detail Informasi Pesanan](#34-melihat-detail-informasi-pesanan)
  - [3.5 Mengubah Status Pengerjaan Pesanan](#35-mengubah-status-pengerjaan-pesanan)
  - [3.6 Menambah Layanan Baru](#36-menambah-layanan-baru)
  - [3.7 Mengedit Layanan Desain](#37-mengedit-layanan-desain)
  - [3.8 Menghapus / Mengarsipkan Layanan](#38-menghapus--mengarsipkan-layanan)
  - [3.9 Mencetak Invoice Pesanan](#39-mencetak-invoice-pesanan)
  - [3.10 Rekap Laporan Penjualan](#310-rekap-laporan-penjualan)
  - [3.11 Ekspor Rekap Penjualan ke Excel / CSV](#311-ekspor-rekap-penjualan-ke-excel--csv)
  - [3.12 Logout Administrator](#312-logout-administrator)

---

## BAB 1: PENDAHULUAN

### 1.1 Tujuan Website
Website **Kreavio Creative** dikembangkan sebagai implementasi praktis mata kuliah **Digital Entrepreneurship**. Sistem ini bertujuan untuk:
1. Menyediakan platform komersial digital mandiri (bukan marketplace) bagi unit usaha jasa desain.
2. Memfasilitasi proses pemesanan terstruktur (*Single Order Checkout*) antara pelanggan dan desainer.
3. Menyediakan mekanisme pembayaran digital berbasis payment gateway (Midtrans Sandbox) dengan opsi pendukung Demo Payment Simulator.
4. Menyajikan pelacakan progres pengerjaan karya desain secara transparan (*real-time status updates*).

### 1.2 Tentang Kreavio Creative
- **Nama Bisnis:** Kreavio Creative
- **Tagline:** *"Create Better. Present Better."*
- **Kategori Produk:** Jasa Desain Digital (non-fisik, non-makanan/minuman).
- **Target Pengguna:** Mahasiswa, pelajar, pelaku UMKM, pencari kerja/fresh graduate, freelancer, dan organisasi kemahasiswaan.
- **Karakteristik Sistem:** Dibangun menggunakan teknologi native PHP 8, MySQL, Bootstrap 5, yang sederhana, ringan, stabil, dan mudah dipahami.

---

## BAB 2: PANDUAN PENGGUNA (CUSTOMER)

### 2.1 Membuka Website
Pengguna dapat mengakses halaman beranda (*Homepage*) Kreavio Creative melalui peramban web (*browser*) dengan mengetikkan alamat:
```text
http://localhost/kreavio/
```
Halaman beranda memuat pengenalan bisnis, nilai keunggulan, rangkuman 6 layanan utama, 4 langkah alur pemesanan, dan testimoni/CTA.

```text
[SCREENSHOT HOMEPAGE]
```

---

### 2.2 Mendaftar Akun Baru (Register)
Untuk dapat membuat pesanan, pelanggan diwajibkan memiliki akun terdaftar:
1. Klik tombol **Login** pada pojok kanan atas navbar, lalu klik tautan **"Daftar sekarang"**, atau langsung akses `http://localhost/kreavio/register.php`.
2. Masukkan data yang diminta:
   - **Nama Lengkap:** Nama pemesan.
   - **Alamat Email:** Email aktif (bersifat unik per akun).
   - **Nomor WhatsApp:** Nomor aktif untuk keperluan koordinasi file draft desain.
   - **Password:** Minimal 6 karakter.
   - **Ulangi Password:** Konfirmasi kecocokan password.
3. Klik tombol **"Daftar Sekarang"**. Sistem akan menyimpan akun terenkripsi (`password_hash`) dan secara otomatis memasukkan Anda ke akun.

```text
[SCREENSHOT REGISTER]
```

---

### 2.3 Masuk ke Akun (Login)
1. Buka tautan `http://localhost/kreavio/login.php`.
2. Masukkan **Alamat Email** dan **Password** yang telah didaftarkan.
3. Klik tombol **"Masuk Sekarang"**.
4. Jika berhasil, sistem akan mengarahkan pengguna langsung ke **Dashboard Pelanggan**.

```text
[SCREENSHOT LOGIN]
```

*(Catatan Pengujian: Tersedia akun uji coba `budi@example.com` dengan kata sandi `password123`).*

---

### 2.4 Melihat Daftar Layanan
1. Klik menu **Layanan** pada navigasi atas atau buka `http://localhost/kreavio/services.php`.
2. Pengguna akan disajikan 6 katalog desain utama:
   - Desain CV (Rp25.000)
   - Desain PowerPoint (Rp50.000)
   - Desain Poster (Rp35.000)
   - Desain Banner (Rp30.000)
   - Desain Social Media (Rp40.000)
   - Desain Logo (Rp75.000)
3. Setiap kartu menampilkan estimasi waktu pengerjaan dan harga awal.

```text
[SCREENSHOT DAFTAR LAYANAN]
```

---

### 2.5 Memilih Layanan & Melihat Detail
1. Klik tombol **"Lihat Detail Layanan"** pada kartu layanan yang diinginkan (contoh: `service-detail.php?id=1`).
2. Pengguna dapat membaca rincian apa saja yang diperoleh (format file master, kesempatan revisi, resolusi tinggi).
3. Klik tombol **"Pesan Layanan Ini"** untuk melanjutkan ke formulir order.

---

### 2.6 Membuat Pesanan (Form Order)
1. Pada formulir pemesanan (`order.php`):
   - **Pilih Layanan:** Pilih produk desain yang diinginkan (satu transaksi = satu produk / *Single Order Checkout*).
   - **Nama & WhatsApp:** Otomatis terisi dari profil akun, dapat disesuaikan jika perlu.
   - **Target Tanggal Selesai (Deadline):** Pilih tanggal kebutuhan selesai.
   - **Deskripsi / Brief Desain:** Tuliskan instruksi materi, teks yang ingin dicantumkan, dan preferensi warna/nuansa.
   - **Catatan Tambahan:** Opsional, misalnya permintaan format file tertentu.
2. Klik tombol **"Lanjut ke Pembayaran (Checkout)"**.

```text
[SCREENSHOT FORM ORDER]
```

---

### 2.7 Halaman Checkout
1. Sistem akan menghasilkan Nomor Order unik (contoh: `ORD-20260903-XXXX`).
2. Periksa kembali ringkasan biaya, estimasi durasi, dan catatan brief Anda.
3. Status awal pesanan adalah `pending_payment` (Belum Bayar).

```text
[SCREENSHOT CHECKOUT]
```

---

### 2.8 Melakukan Pembayaran via Midtrans Payment Gateway
Pada halaman checkout (`checkout.php`), sistem menggunakan integrasi resmi **Midtrans Payment Gateway**:
1. Pengguna dapat meninjau rincian biaya pesanan dan daftar channel pembayaran yang didukung (QRIS, Virtual Account BCA/BNI/BRI/Mandiri, dan GoPay).
2. Klik tombol biru **"Bayar via Midtrans"**.
3. Pop-up resmi Midtrans Snap akan muncul di layar. Pengguna dapat memilih channel pembayaran yang diinginkan.
4. Selesaikan pembayaran pada simulator perbankan untuk mengonfirmasi transaksi.
5. *Catatan Keamanan:* Jika pengguna menutup pop-up (klik tombol silang X), sistem akan menjaga status pesanan tetap `pending_payment` (belum lunas) dan tidak memproses pesanan sampai pembayaran benar-benar diselesaikan.

Setelah pembayaran diverifikasi berhasil, sistem akan otomatis mengarahkan pelanggan ke halaman **Struk Pembayaran Berhasil (`success.php`)** dengan status pembayaran `paid` (Lunas) dan status pengerjaan `menunggu_proses`.

```text
[SCREENSHOT CHECKOUT & MIDTRANS SNAP]
```

---

### 2.9 Melihat Status & Timeline Pesanan
1. Buka halaman detail pesanan (`customer/order-detail.php?id=X`).
2. Terdapat **Visual Timeline Tracking**:
   - **1. Pesanan Dibuat:** Formulir selesai diisi.
   - **2. Dibayar:** Pembayaran terkonfirmasi lunas.
   - **3. Diproses:** Pesanan masuk antrean pengerjaan tim.
   - **4. Dikerjakan:** Desainer sedang menyusun draft desain.
   - **5. Selesai:** Desain tuntas dikerjakan dan diserahkan ke pelanggan.
3. Terdapat tabel riwayat log perubahan status dari waktu ke waktu.

---

### 2.10 Melihat Riwayat Pesanan (Pesanan Saya)
1. Klik menu dropdown profil di navbar atas &rarr; pilih **Pesanan Saya** (`customer/orders.php`).
2. Seluruh transaksi pesanan terdahulu tampil dalam bentuk tabel rapi dengan informasi status pembayaran dan status pengerjaan.
3. Klik tombol **"Detail"** untuk membuka lembar informasi pesanan kapan saja.

```text
[SCREENSHOT CUSTOMER DASHBOARD]
```

---

### 2.11 Logout Customer
Klik tombol nama akun pada navbar atas &rarr; pilih **Logout**. Sesi akun akan dihapus dan diarahkan kembali ke halaman login.

---

## BAB 3: PANDUAN PENGELOLA (ADMINISTRATOR)

### 3.1 Login Administrator
1. Buka peramban dan akses alamat:
   ```text
   http://localhost/kreavio/admin/login.php
   ```
2. Masukkan kredensial administrator:
   - **Email:** `admin@kreavio.test`
   - **Password:** `admin123`
3. Klik tombol **"Masuk Panel Admin"**.

---

### 3.2 Melihat Dashboard Admin
Dashboard menampilkan ringkasan data usaha secara menyeluruh:
- **Total Pesanan:** Akumulasi seluruh pesanan masuk.
- **Menunggu Bayar:** Pesanan yang belum diselesaikan pembayarannya.
- **Menunggu Proses:** Pesanan yang sudah lunas dan siap diagendakan.
- **Sedang Dikerjakan:** Pesanan yang saat ini dalam proses kreatif desain.
- **Pesanan Selesai:** Pesanan yang telah tuntas.
- **Total Omzet:** Akumulasi pendapatan dari pesanan yang berstatus *Paid*.
- **Tabel Pesanan Masuk Terbaru:** Rangkuman 6 pesanan terkini.

```text
[SCREENSHOT ADMIN DASHBOARD]
```

---

### 3.3 Melihat & Menyaring Daftar Pesanan
1. Klik menu **Daftar Pesanan** pada navbar atas (`admin/orders.php`).
2. Admin dapat menggunakan kotak pencarian (berdasarkan nomor order, nama customer, atau jenis layanan).
3. Admin dapat menyaring berdasarkan status: *Menunggu Pembayaran, Menunggu Proses, Sedang Dikerjakan, Selesai, Dibatalkan*.

```text
[SCREENSHOT ADMIN ORDER]
```

---

### 3.4 Melihat Detail Informasi Pesanan
1. Klik tombol **"Detail"** pada salah satu baris pesanan di tabel.
2. Halaman `admin/order-detail.php` akan terbuka menampilkan:
   - Kontak pemesan (Nama, Email, dan tombol WhatsApp langsung).
   - Brief lengkap kebutuhan desain dan catatan pemesan.
   - Tanggal tenggat waktu (*deadline*).
   - Riwayat pencatatan log transaksi.

---

### 3.5 Mengubah Status Pengerjaan Pesanan
1. Pada halaman detail pesanan, perhatikan kotak **"Ubah Status Pesanan"** di sebelah kanan.
2. Pilih status baru:
   - `menunggu_proses`
   - `sedang_dikerjakan`
   - `selesai`
   - `cancelled`
3. Sesuaikan status pembayaran jika diperlukan (`paid` / `pending_payment`).
4. Berikan catatan opsional pada kolom teks (contoh: *"Draft 1 telah dikirimkan via WhatsApp"*).
5. Klik **"Simpan Perubahan Status"**.
6. Sistem langsung memperbarui database dan pelanggan dapat melihat update ini di akun mereka.

---

### 3.6 Menambah Layanan Baru
1. Klik menu **Kelola Layanan** &rarr; klik tombol **"Tambah Layanan Baru"** (`admin/service-create.php`).
2. Isi formulir:
   - **Nama Layanan:** Nama produk desain (contoh: *Desain Kemasan Produk*).
   - **Deskripsi Lengkap:** Penjelasan manfaat dan format yang disediakan.
   - **Harga (Rupiah):** Tarif jasa dalam angka bulat (contoh: `60000`).
   - **Estimasi Pengerjaan:** Durasi kerja (contoh: *2-3 Hari*).
   - **Status Aktif:** Centang agar tampil pada katalog publik.
3. Klik **"Simpan Layanan Baru"**.

```text
[SCREENSHOT CRUD LAYANAN]
```

---

### 3.7 Mengedit Layanan Desain
1. Pada tabel daftar layanan (`admin/services.php`), klik tombol **"Edit"** pada layanan yang ingin diubah.
2. Lakukan perbaikan data harga, nama, deskripsi, durasi, atau status aktif.
3. Klik **"Simpan Perubahan"**. Perubahan seketika berlaku di katalog publik.

---

### 3.8 Menghapus / Mengarsipkan Layanan
1. Pada tabel daftar layanan, klik ikon **Tempat Sampah (Hapus)**.
2. Sistem akan menampilkan dialog konfirmasi.
3. **Mekanisme Keamanan Integritas Data:**
   - Jika layanan belum pernah dipesan, data akan dihapus permanen.
   - Jika layanan sudah memiliki riwayat pesanan pelanggan terdahulu, sistem secara cerdas akan menonaktifkan status layanan (*soft archive*) sehingga riwayat transaksi pelanggan lama tidak rusak (*foreign key integrity safe*).

---

### 3.9 Mencetak Invoice Pesanan
1. Buka halaman **Detail Pesanan** (`admin/order-detail.php`), atau klik ikon **Printer** pada tabel Daftar Pesanan / Rekap Penjualan.
2. Klik tombol hijau **"Cetak Invoice"** di bagian kanan atas.
3. Halaman invoice resmi Kreavio Creative akan terbuka di tab baru (`admin/invoice.php`).
4. Klik tombol **"Cetak Invoice / Simpan PDF"** atau gunakan shortcut `Ctrl + P` untuk mencetak atau menyimpan dokumen sebagai file PDF.

```text
[SCREENSHOT INVOICE ADMIN]
```

---

### 3.10 Rekap Laporan Penjualan
1. Klik menu **"Rekap Penjualan"** pada bilah navigasi (navbar) admin (`admin/sales-report.php`).
2. Pilih filter periode yang diinginkan:
   - **Hari Ini:** Transaksi yang masuk pada hari berjalan.
   - **Minggu Ini:** Transaksi dari hari Senin hingga Minggu pekan ini.
   - **Bulan Ini:** Seluruh transaksi pada bulan kalender berjalan (default).
   - **Rentang Tanggal Kustom:** Masukkan tanggal awal dan tanggal akhir secara bebas, lalu klik **"Terapkan Filter"**.
3. Sistem secara otomatis menyajikan:
   - Kartu metrik **Jumlah Transaksi**.
   - Kartu metrik **Total Penjualan (Lunas)**.
   - Kartu metrik **Belum Lunas (Pending)**.
   - Tabel rincian seluruh transaksi beserta status pembayaran dan pengerjaannya.

```text
[SCREENSHOT REKAP PENJUALAN]
```

---

### 3.11 Ekspor Rekap Penjualan ke Excel / CSV
1. Pada halaman **Rekap Penjualan**, tentukan periode data yang ingin diunduh.
2. Klik tombol hijau **"Export Excel / CSV"** pada kanan atas.
3. File berformat `.csv` dengan encoding UTF-8 BOM otomatis terunduh ke perangkat Anda.
4. Buka file tersebut menggunakan **Microsoft Excel** atau **Google Sheets** untuk pengolahan pembukuan atau pelaporan lebih lanjut.

```text
[SCREENSHOT EKSPOR CSV EXCEL]
```

---

### 3.12 Logout Administrator
Klik tombol merah **"Logout"** di pojok kanan navbar admin. Sesi hak akses admin akan ditutup secara aman.
