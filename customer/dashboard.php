<?php
// =====================================================================
// FILE: customer/dashboard.php
// Dashboard Pelanggan Kreavio Creative
// =====================================================================

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();
$user = current_user($pdo);

// Ambil data statistik pesanan customer ini
$stats = [
    'total'       => 0,
    'unpaid'      => 0,
    'in_progress' => 0,
    'completed'   => 0
];

try {
    $countStmt = $pdo->prepare("
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN payment_status = 'pending_payment' THEN 1 ELSE 0 END) as unpaid,
            SUM(CASE WHEN order_status IN ('menunggu_proses', 'sedang_dikerjakan') THEN 1 ELSE 0 END) as in_progress,
            SUM(CASE WHEN order_status = 'selesai' THEN 1 ELSE 0 END) as completed
        FROM orders 
        WHERE user_id = ?
    ");
    $countStmt->execute([$user['id']]);
    $res = $countStmt->fetch();
    if ($res) {
        $stats['total']       = (int)$res['total'];
        $stats['unpaid']      = (int)$res['unpaid'];
        $stats['in_progress'] = (int)$res['in_progress'];
        $stats['completed']   = (int)$res['completed'];
    }

    // Ambil 5 pesanan terbaru
    $recentStmt = $pdo->prepare("
        SELECT o.*, s.name as service_name 
        FROM orders o
        JOIN services s ON o.service_id = s.id
        WHERE o.user_id = ?
        ORDER BY o.created_at DESC
        LIMIT 5
    ");
    $recentStmt->execute([$user['id']]);
    $recentOrders = $recentStmt->fetchAll();

} catch (PDOException $e) {
    $recentOrders = [];
}

$pageTitle = 'Dashboard Pelanggan';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <!-- Header Sambutan -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-3 border-bottom">
        <div>
            <h2 class="fw-bold mb-1">Halo, <?= e($user['name']) ?>!</h2>
            <p class="text-muted mb-0">Selamat datang di dashboard akun Kreavio Creative Anda.</p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="<?= base_url('services.php') ?>" class="btn btn-primary shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Buat Pesanan Baru
            </a>
        </div>
    </div>

    <!-- 4 Kartu Statistik -->
    <div class="row g-3 mb-5">
        <div class="col-6 col-md-3">
            <div class="card p-3 border h-100 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-semibold">Total Pesanan</span>
                    <i class="bi bi-folder2 text-primary fs-4"></i>
                </div>
                <h3 class="fw-bold mb-0"><?= $stats['total'] ?></h3>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card p-3 border h-100 shadow-sm border-warning-subtle">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-semibold">Belum Bayar</span>
                    <i class="bi bi-clock-history text-warning fs-4"></i>
                </div>
                <h3 class="fw-bold mb-0 text-warning"><?= $stats['unpaid'] ?></h3>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card p-3 border h-100 shadow-sm border-info-subtle">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-semibold">Sedang Diproses</span>
                    <i class="bi bi-palette text-info fs-4"></i>
                </div>
                <h3 class="fw-bold mb-0 text-info"><?= $stats['in_progress'] ?></h3>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card p-3 border h-100 shadow-sm border-success-subtle">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-semibold">Selesai</span>
                    <i class="bi bi-check-circle text-success fs-4"></i>
                </div>
                <h3 class="fw-bold mb-0 text-success"><?= $stats['completed'] ?></h3>
            </div>
        </div>
    </div>

    <!-- Tabel Pesanan Terbaru -->
    <div class="card shadow-sm border">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">Pesanan Terbaru</h5>
            <a href="<?= base_url('customer/orders.php') ?>" class="btn btn-sm btn-outline-primary">
                Lihat Semua (<?= $stats['total'] ?>)
            </a>
        </div>
        <div class="card-body p-0">
            <?php if (empty($recentOrders)): ?>
                <div class="p-5 text-center text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                    <p class="mb-3">Anda belum memiliki pesanan desain apapun.</p>
                    <a href="<?= base_url('services.php') ?>" class="btn btn-primary">Pesan Desain Sekarang</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No. Order</th>
                                <th>Layanan</th>
                                <th>Total</th>
                                <th>Pembayaran</th>
                                <th>Status Order</th>
                                <th>Tanggal</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentOrders as $ord): ?>
                                <tr>
                                    <td>
                                        <span class="font-monospace fw-semibold"><?= e($ord['order_number']) ?></span>
                                    </td>
                                    <td><?= e($ord['service_name']) ?></td>
                                    <td class="fw-semibold"><?= format_rupiah($ord['total']) ?></td>
                                    <td><?= get_payment_status_badge($ord['payment_status']) ?></td>
                                    <td><?= get_order_status_badge($ord['order_status']) ?></td>
                                    <td class="small text-muted"><?= date('d/m/Y', strtotime($ord['created_at'])) ?></td>
                                    <td class="text-end">
                                        <?php if ($ord['payment_status'] === 'pending_payment'): ?>
                                            <a href="<?= base_url('checkout.php?order_number=' . urlencode($ord['order_number'])) ?>" class="btn btn-sm btn-warning me-1">
                                                Bayar
                                            </a>
                                        <?php endif; ?>
                                        <a href="<?= base_url('customer/order-detail.php?id=' . $ord['id']) ?>" class="btn btn-sm btn-outline-secondary">
                                            Detail
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
