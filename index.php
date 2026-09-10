<?php
// =====================================================================
// FILE: index.php
// Halaman Utama (Homepage) Kreavio Creative
// =====================================================================

$pageTitle = 'Home - Solusi Desain Digital Profesional';
require_once __DIR__ . '/includes/header.php';

// Ambil daftar layanan aktif dari database
try {
    $stmt = $pdo->query("SELECT * FROM services WHERE active = 1 ORDER BY id ASC LIMIT 6");
    $services = $stmt->fetchAll();
} catch (PDOException $e) {
    $services = [];
}
?>

<!-- 1. HERO SECTION -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <span class="hero-badge">
                    <i class="bi bi-stars text-primary me-1"></i> Studio Desain Grafis Digital Sederhana
                </span>
                <h1 class="hero-title mb-3">
                    Solusi Desain Digital untuk <span class="text-gradient-primary">Membuat Ide Anda Lebih Profesional</span>.
                </h1>
                <p class="hero-subtitle mb-4">
                    Kreavio Creative membantu mahasiswa, UMKM, dan profesional menciptakan materi visual berstandar tinggi dengan alur pemesanan mudah, cepat, dan transparan.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="<?= base_url('services.php') ?>" class="btn btn-primary btn-lg shadow-sm">
                        <i class="bi bi-grid-fill me-1"></i> Lihat Semua Layanan
                    </a>
                    <a href="<?= base_url('services.php') ?>" class="btn btn-outline-secondary btn-lg">
                        <i class="bi bi-bag-plus me-1"></i> Pesan Sekarang
                    </a>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-4 mt-4 pt-2 text-muted small">
                    <span class="d-inline-flex align-items-center"><i class="bi bi-patch-check-fill text-primary me-1"></i> Pengerjaan Cepat 1-3 Hari</span>
                    <span class="d-inline-flex align-items-center"><i class="bi bi-arrow-repeat text-success me-1"></i> Garansi Bebas Revisi</span>
                    <span class="d-inline-flex align-items-center"><i class="bi bi-tag-fill text-primary me-1"></i> Mulai Rp25.000</span>
                </div>
            </div>
            <div class="col-lg-5 text-center">
                <div class="p-4 bg-white rounded-4 shadow border text-start">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                        <span class="badge bg-primary-subtle text-primary fw-semibold px-3 py-2">Kreavio Studio</span>
                        <span class="text-warning small fw-bold"><i class="bi bi-star-fill text-warning me-1"></i> 4.9/5 Rating</span>
                    </div>
                    <div class="card-img-wrap rounded-3 mb-3 border">
                        <img src="<?= asset_url('images/service-cv.svg') ?>" alt="Preview Desain" class="card-img-top">
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">Kualitas Desain Terjamin</h6>
                            <p class="text-muted small mb-0">Format siap pakai (PDF, PNG, SVG & PPTX).</p>
                        </div>
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 small">
                            <i class="bi bi-shield-check me-1"></i> Terpercaya
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. SECTION KEUNGGULAN (4 KEUNGGULAN) -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Mengapa Memilih Kreavio?</h2>
            <p class="text-muted">Komitmen kami untuk memberikan hasil visual terbaik bagi kebutuhan Anda</p>
        </div>
        <div class="row g-4">
            <!-- Keunggulan 1 -->
            <div class="col-md-6 col-lg-3">
                <div class="feature-box">
                    <div class="feature-icon" style="background-color: #eff6ff; color: #2563eb;">
                        <i class="bi bi-tags-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Harga Terjangkau</h5>
                    <p class="text-muted small mb-0">Biaya ramah mahasiswa dan pelaku UMKM mulai dari Rp25.000 tanpa biaya tersembunyi.</p>
                </div>
            </div>
            <!-- Keunggulan 2 -->
            <div class="col-md-6 col-lg-3">
                <div class="feature-box">
                    <div class="feature-icon" style="background-color: #fef3c7; color: #d97706;">
                        <i class="bi bi-lightning-charge-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Proses Mudah</h5>
                    <p class="text-muted small mb-0">Pilih layanan, isi deskripsi kebutuhan (brief), bayar secara digital, pesanan langsung diproses.</p>
                </div>
            </div>
            <!-- Keunggulan 3 -->
            <div class="col-md-6 col-lg-3">
                <div class="feature-box">
                    <div class="feature-icon" style="background-color: #ecfdf5; color: #059669;">
                        <i class="bi bi-award-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Desain Profesional</h5>
                    <p class="text-muted small mb-0">Tampilan estetik, rapi, terstruktur, dan disesuaikan dengan standar industri terkini.</p>
                </div>
            </div>
            <!-- Keunggulan 4 -->
            <div class="col-md-6 col-lg-3">
                <div class="feature-box">
                    <div class="feature-icon" style="background-color: #f5f3ff; color: #7c3aed;">
                        <i class="bi bi-sliders2-vertical"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Bisa Custom</h5>
                    <p class="text-muted small mb-0">Sesuaikan tema warna, preferensi gaya visual, serta format file yang Anda butuhkan.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 3. SECTION DAFTAR LAYANAN -->
<section class="py-5">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4">
            <div>
                <h2 class="fw-bold mb-1">Layanan Desain Pilihan</h2>
                <p class="text-muted mb-0">Pilih jenis desain yang Anda butuhkan untuk tugas, bisnis, atau karir</p>
            </div>
            <div class="mt-3 mt-md-0">
                <a href="<?= base_url('services.php') ?>" class="btn btn-outline-primary">
                    Lihat Semua Layanan <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        <div class="row g-4">
            <?php if (empty($services)): ?>
                <div class="col-12">
                    <div class="alert alert-info text-center py-4">
                        Belum ada layanan yang ditambahkan. Silakan login ke Admin untuk menambahkan layanan.
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($services as $service): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card card-service h-100">
                            <div class="card-img-wrap border-bottom">
                                <img src="<?= get_service_image($service['id']) ?>" class="card-img-top" alt="<?= e($service['name']) ?>">
                            </div>
                            <div class="card-body d-flex flex-column p-4">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title fw-bold mb-0 text-dark"><?= e($service['name']) ?></h5>
                                    <span class="badge bg-light text-primary border small">
                                        <i class="bi bi-clock me-1"></i><?= e($service['duration']) ?>
                                    </span>
                                </div>
                                <p class="card-text text-muted small flex-grow-1">
                                    <?= e($service['description']) ?>
                                </p>
                                <div class="pt-3 border-top d-flex justify-content-between align-items-center mt-3">
                                    <div>
                                        <span class="text-muted small d-block">Mulai dari</span>
                                        <span class="fw-bold text-primary fs-5 tabular-nums"><?= format_rupiah($service['price']) ?></span>
                                    </div>
                                    <a href="<?= base_url('order.php?service_id=' . $service['id']) ?>" class="btn btn-primary btn-sm px-3">
                                        Pesan <i class="bi bi-chevron-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- 4. SECTION CARA PEMESANAN (4 LANGKAH SEDERHANA) -->
<section class="py-5 bg-light border-top border-bottom">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Cara Pemesanan Sangat Mudah</h2>
            <p class="text-muted">4 langkah praktis mendapatkan desain digital berkualitas</p>
        </div>

        <div class="row g-4">
            <!-- Langkah 1 -->
            <div class="col-md-6 col-lg-3">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <h5 class="fw-bold mb-2 text-dark">Pilih Layanan</h5>
                    <p class="text-muted small mb-0">Temukan kategori desain yang sesuai dengan kebutuhan tugas atau bisnismu.</p>
                </div>
            </div>
            <!-- Langkah 2 -->
            <div class="col-md-6 col-lg-3">
                <div class="step-card">
                    <div class="step-number">2</div>
                    <h5 class="fw-bold mb-2 text-dark">Isi Pesanan</h5>
                    <p class="text-muted small mb-0">Lengkapi formulir singkat mengenai brief instruksi, warna, dan deadline yang diinginkan.</p>
                </div>
            </div>
            <!-- Langkah 3 -->
            <div class="col-md-6 col-lg-3">
                <div class="step-card">
                    <div class="step-number">3</div>
                    <h5 class="fw-bold mb-2 text-dark">Lakukan Pembayaran</h5>
                    <p class="text-muted small mb-0">Selesaikan pembayaran secara aman dan otomatis melalui QRIS, transfer bank, atau e-wallet.</p>
                </div>
            </div>
            <!-- Langkah 4 -->
            <div class="col-md-6 col-lg-3">
                <div class="step-card">
                    <div class="step-number">4</div>
                    <h5 class="fw-bold mb-2 text-dark">Pesanan Diproses</h5>
                    <p class="text-muted small mb-0">Tim kami langsung mengerjakan desain Anda dan Anda dapat memantau statusnya secara realtime.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 5. CTA SECTION -->
<section class="py-5 text-center">
    <div class="container">
        <div class="p-5 rounded-4 bg-primary text-white shadow-lg position-relative overflow-hidden" style="background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 60%, #0284c7 100%) !important;">
            <h2 class="fw-bold mb-3">Siap membuat desainmu lebih profesional?</h2>
            <p class="lead mb-4 opacity-75">Tingkatkan kualitas presentasi ide, produk, atau karirmu sekarang bersama Kreavio Creative.</p>
            <a href="<?= base_url('services.php') ?>" class="btn btn-light btn-lg px-4 text-primary fw-bold shadow-sm">
                Pesan Sekarang <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
