<?php
// =====================================================================
// FILE: services.php
// Halaman Katalog Daftar Layanan Kreavio Creative
// =====================================================================

$pageTitle = 'Daftar Layanan Desain Digital';
require_once __DIR__ . '/includes/header.php';

try {
    $stmt = $pdo->query("SELECT * FROM services WHERE active = 1 ORDER BY id ASC");
    $services = $stmt->fetchAll();
} catch (PDOException $e) {
    $services = [];
}
?>

<div class="bg-light py-5 border-bottom">
    <div class="container text-center">
        <h1 class="fw-bold mb-2">Layanan Desain Digital Kami</h1>
        <p class="text-muted mb-0">Solusi visual praktis, estetik, dan berkualitas untuk tugas kampus, karir, dan bisnis Anda.</p>
    </div>
</div>

<div class="container py-5">
    <?php if (empty($services)): ?>
        <div class="alert alert-info text-center py-4">
            <p class="mb-0">Saat ini belum ada layanan aktif yang tersedia.</p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($services as $service): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card card-service h-100">
                        <img src="<?= get_service_image($service['id']) ?>" class="card-img-top border-bottom" alt="<?= e($service['name']) ?>" style="height: 190px; object-fit: cover;">
                        <div class="card-body d-flex flex-column p-4">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title fw-bold mb-0"><?= e($service['name']) ?></h5>
                                <span class="badge bg-light text-primary border small">
                                    <i class="bi bi-clock me-1"></i><?= e($service['duration']) ?>
                                </span>
                            </div>
                            <p class="card-text text-muted small flex-grow-1">
                                <?= e($service['description']) ?>
                            </p>
                            <div class="pt-3 border-top mt-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-muted small">Mulai dari</span>
                                    <span class="fw-bold text-primary fs-5"><?= format_rupiah($service['price']) ?></span>
                                </div>
                                <div class="d-grid gap-2">
                                    <a href="<?= base_url('order.php?service_id=' . $service['id']) ?>" class="btn btn-primary">
                                        <i class="bi bi-cart-plus me-1"></i> Pesan Sekarang
                                    </a>
                                    <a href="<?= base_url('service-detail.php?id=' . $service['id']) ?>" class="btn btn-outline-secondary btn-sm">
                                        Lihat Detail Layanan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
