<?php
// =====================================================================
// FILE: service-detail.php
// Halaman Detail Layanan Desain Kreavio Creative
// =====================================================================

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

$serviceId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ? AND active = 1");
    $stmt->execute([$serviceId]);
    $service = $stmt->fetch();
} catch (PDOException $e) {
    $service = null;
}

if (!$service) {
    set_flash('danger', 'Layanan tidak ditemukan atau sedang tidak aktif.');
    redirect('services.php');
}

$pageTitle = $service['name'] . ' - Detail Layanan';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('index.php') ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('services.php') ?>">Layanan</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= e($service['name']) ?></li>
        </ol>
    </nav>

    <div class="row g-5">
        <!-- Kolom Kiri: Gambar Ilustrasi & Fitur Layanan -->
        <div class="col-lg-7">
            <div class="card shadow-sm border overflow-hidden mb-4">
                <img src="<?= get_service_image($service['id']) ?>" alt="<?= e($service['name']) ?>" class="img-fluid w-100" style="max-height: 380px; object-fit: cover;">
                <div class="card-body p-4">
                    <h2 class="fw-bold mb-3"><?= e($service['name']) ?></h2>
                    <p class="lead text-muted fs-6 mb-4" style="line-height: 1.7;">
                        <?= nl2br(e($service['description'])) ?>
                    </p>

                    <h5 class="fw-bold mb-3">Apa yang Anda Dapatkan?</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2 d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success me-2"></i> Hasil desain beresolusi tinggi (High Quality)
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success me-2"></i> File master / siap cetak & siap posting (PDF/PNG/JPG)
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success me-2"></i> Kesempatan revisi sesuai brief awal
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success me-2"></i> Komunikasi transparan & pemantauan status pesanan secara online
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Ringkasan Harga & Tombol Pesan -->
        <div class="col-lg-5">
            <div class="card shadow-sm border p-4 sticky-top" style="top: 90px;">
                <h4 class="fw-bold mb-3">Informasi Pemesanan</h4>
                
                <div class="p-3 bg-light rounded-3 mb-3">
                    <span class="text-muted small d-block">Harga Layanan</span>
                    <span class="fs-3 fw-bold text-primary"><?= format_rupiah($service['price']) ?></span>
                </div>

                <ul class="list-group list-group-flush mb-4">
                    <li class="list-group-item d-flex justify-content-between px-0 bg-transparent">
                        <span class="text-muted"><i class="bi bi-clock me-2"></i>Estimasi Pengerjaan:</span>
                        <span class="fw-semibold"><?= e($service['duration']) ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0 bg-transparent">
                        <span class="text-muted"><i class="bi bi-credit-card me-2"></i>Metode Bayar:</span>
                        <span class="fw-semibold">QRIS, Transfer Bank & E-Wallet</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0 bg-transparent">
                        <span class="text-muted"><i class="bi bi-shield-check me-2"></i>Tipe Pemesanan:</span>
                        <span class="fw-semibold">Pemesanan Langsung Online</span>
                    </li>
                </ul>

                <div class="d-grid gap-2">
                    <a href="<?= base_url('order.php?service_id=' . $service['id']) ?>" class="btn btn-primary btn-lg shadow-sm">
                        <i class="bi bi-cart-check me-1"></i> Pesan Layanan Ini
                    </a>
                    <a href="<?= base_url('services.php') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Layanan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
