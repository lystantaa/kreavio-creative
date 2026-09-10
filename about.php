<?php
// =====================================================================
// FILE: about.php
// Halaman Profil Bisnis Kreavio Creative
// =====================================================================

$pageTitle = 'Tentang Kreavio Creative';
require_once __DIR__ . '/includes/header.php';
?>

<div class="bg-light py-5 border-bottom">
    <div class="container text-center">
        <span class="badge bg-primary-subtle text-primary px-3 py-2 fw-semibold mb-2">Profil Bisnis Digital</span>
        <h1 class="fw-bold mb-2">Tentang Kreavio Creative</h1>
        <p class="lead text-muted mb-0">"Create Better. Present Better."</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-5 align-items-center mb-5">
        <div class="col-lg-6">
            <h2 class="fw-bold mb-3">Membantu Ide Hebat Anda Tampil Lebih Percaya Diri</h2>
            <p class="text-muted" style="line-height: 1.8;">
                <strong>Kreavio Creative</strong> didirikan sebagai sebuah inisiatif bisnis digital yang berfokus pada penyediaan solusi jasa desain grafis terjangkau, cepat, dan berkualitas tinggi. Kami percaya bahwa visual yang rapi dan profesional adalah kunci utama untuk menyampaikan ide, memenangkan kesempatan kerja, atau mengembangkan bisnis.
            </p>
            <p class="text-muted" style="line-height: 1.8;">
                Banyak mahasiswa, pelajar, dan pemilik usaha mikro (UMKM) memiliki ide cemerlang, produk berkualitas, atau keahlian mumpuni, namun terkendala dalam menyajikan dokumen presentasi, CV, poster, maupun media promosi secara visual yang menarik. Kreavio hadir menjembatani kendala tersebut melalui sistem pemesanan yang sederhana dan terstruktur.
            </p>
        </div>
        <div class="col-lg-6">
            <div class="p-4 bg-light rounded-4 border">
                <h4 class="fw-bold text-primary mb-3">Nilai-Nilai Utama Kami</h4>
                <div class="d-flex mb-3">
                    <div class="me-3 fs-3 text-primary"><i class="bi bi-check2-circle"></i></div>
                    <div>
                        <h6 class="fw-bold mb-1">Aksesibilitas & Harga Mahasiswa</h6>
                        <p class="text-muted small mb-0">Layanan kami dirancang agar dapat dijangkau oleh kantong pelajar dan UMKM pemula.</p>
                    </div>
                </div>
                <div class="d-flex mb-3">
                    <div class="me-3 fs-3 text-primary"><i class="bi bi-clock-history"></i></div>
                    <div>
                        <h6 class="fw-bold mb-1">Ketepatan Waktu</h6>
                        <p class="text-muted small mb-0">Estimasi pengerjaan yang jelas dan komitmen menyelesaikan pekerjaan sebelum deadline.</p>
                    </div>
                </div>
                <div class="d-flex">
                    <div class="me-3 fs-3 text-primary"><i class="bi bi-shield-lock"></i></div>
                    <div>
                        <h6 class="fw-bold mb-1">Kemudahan & Keamanan Transaksi</h6>
                        <p class="text-muted small mb-0">Integrasi pembayaran digital aman dan pemantauan status pesanan real-time tanpa ribet.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Segmentasi Target Pengguna -->
    <div class="pt-5 border-top">
        <div class="text-center mb-5">
            <h3 class="fw-bold">Siapa yang Kami Layani?</h3>
            <p class="text-muted">Layanan Kreavio didesain spesifik untuk menjawab tantangan segmen pengguna kami</p>
        </div>
        <div class="row g-4 text-center">
            <div class="col-md-4 col-sm-6">
                <div class="p-4 border rounded-3 h-100 bg-white shadow-sm">
                    <i class="bi bi-mortarboard fs-1 text-primary mb-2 d-block"></i>
                    <h5 class="fw-bold">Mahasiswa & Pelajar</h5>
                    <p class="text-muted small mb-0">Desain slide presentasi sidang, laporan tugas kuliah, dan poster kepanitiaan acara kampus.</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="p-4 border rounded-3 h-100 bg-white shadow-sm">
                    <i class="bi bi-shop fs-1 text-primary mb-2 d-block"></i>
                    <h5 class="fw-bold">Pelaku UMKM</h5>
                    <p class="text-muted small mb-0">Identitas logo toko, banner jualan, serta konten promosi media sosial untuk menaikkan omzet.</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="p-4 border rounded-3 h-100 bg-white shadow-sm">
                    <i class="bi bi-briefcase fs-1 text-primary mb-2 d-block"></i>
                    <h5 class="fw-bold">Pencari Kerja & Freelancer</h5>
                    <p class="text-muted small mb-0">Desain Curriculum Vitae (CV) modern dan ATS-friendly agar lolos proses seleksi kerja.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
