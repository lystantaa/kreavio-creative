<?php
// =====================================================================
// FILE: customer/orders.php
// Halaman Riwayat Pesanan Customer Kreavio Creative
// =====================================================================

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();
$user = current_user($pdo);

try {
    $stmt = $pdo->prepare("
        SELECT o.*, s.name as service_name, s.duration as service_duration
        FROM orders o
        JOIN services s ON o.service_id = s.id
        WHERE o.user_id = ?
        ORDER BY o.created_at DESC
    ");
    $stmt->execute([$user['id']]);
    $orders = $stmt->fetchAll();
} catch (PDOException $e) {
    $orders = [];
}

$pageTitle = 'Pesanan Saya';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="bg-light py-4 border-bottom mb-4">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div>
            <h2 class="fw-bold mb-1">Pesanan Saya</h2>
            <p class="text-muted mb-0">Daftar seluruh riwayat pesanan desain digital Anda</p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="<?= base_url('services.php') ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Pesan Desain Baru
            </a>
        </div>
    </div>
</div>

<div class="container pb-5">
    <div class="card shadow-sm border">
        <div class="card-body p-0">
            <?php if (empty($orders)): ?>
                <div class="p-5 text-center text-muted">
                    <i class="bi bi-bag-x fs-1 d-block mb-3 text-secondary"></i>
                    <h5 class="fw-bold">Belum Ada Pesanan</h5>
                    <p class="small mb-3">Anda belum pernah membuat pesanan jasa desain di Kreavio.</p>
                    <a href="<?= base_url('services.php') ?>" class="btn btn-primary">Mulai Pesan Sekarang</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No. Order</th>
                                <th>Layanan</th>
                                <th>Deadline</th>
                                <th>Total</th>
                                <th>Pembayaran</th>
                                <th>Status Order</th>
                                <th>Tanggal Buat</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $ord): ?>
                                <tr>
                                    <td>
                                        <a href="<?= base_url('customer/order-detail.php?id=' . $ord['id']) ?>" class="fw-bold font-monospace text-decoration-none">
                                            <?= e($ord['order_number']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <strong><?= e($ord['service_name']) ?></strong>
                                    </td>
                                    <td>
                                        <span class="small text-muted"><?= date('d/m/Y', strtotime($ord['deadline'])) ?></span>
                                    </td>
                                    <td class="fw-semibold text-dark">
                                        <?= format_rupiah($ord['total']) ?>
                                    </td>
                                    <td>
                                        <?= get_payment_status_badge($ord['payment_status']) ?>
                                    </td>
                                    <td>
                                        <?= get_order_status_badge($ord['order_status']) ?>
                                    </td>
                                    <td class="small text-muted">
                                        <?= date('d/m/Y H:i', strtotime($ord['created_at'])) ?>
                                    </td>
                                    <td class="text-end">
                                        <?php if ($ord['payment_status'] === 'pending_payment'): ?>
                                            <a href="<?= base_url('checkout.php?order_number=' . urlencode($ord['order_number'])) ?>" class="btn btn-sm btn-warning me-1">
                                                <i class="bi bi-wallet2 me-1"></i> Bayar
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= base_url('customer/invoice.php?id=' . $ord['id']) ?>" target="_blank" class="btn btn-sm btn-outline-success me-1" title="Cetak Invoice PDF">
                                                <i class="bi bi-file-earmark-pdf"></i> Invoice
                                            </a>
                                        <?php endif; ?>
                                        <a href="<?= base_url('customer/order-detail.php?id=' . $ord['id']) ?>" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-eye me-1"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
