<?php
// =====================================================================
// FILE: admin/index.php
// Dashboard Administrator Kreavio Creative (Section 19)
// =====================================================================

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/admin-auth.php';

require_admin();
$admin = current_admin($pdo);

// Ambil data statistik pesanan untuk card dashboard
$stats = [
    'total'            => 0,
    'pending_payment'  => 0,
    'menunggu_proses'  => 0,
    'sedang_dikerjakan'=> 0,
    'selesai'          => 0,
    'total_pendapatan' => 0
];

try {
    $statStmt = $pdo->query("
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN payment_status = 'pending_payment' THEN 1 ELSE 0 END) as pending_payment,
            SUM(CASE WHEN order_status = 'menunggu_proses' THEN 1 ELSE 0 END) as menunggu_proses,
            SUM(CASE WHEN order_status = 'sedang_dikerjakan' THEN 1 ELSE 0 END) as sedang_dikerjakan,
            SUM(CASE WHEN order_status = 'selesai' THEN 1 ELSE 0 END) as selesai,
            SUM(CASE WHEN payment_status = 'paid' THEN total ELSE 0 END) as total_pendapatan
        FROM orders
    ");
    $res = $statStmt->fetch();
    if ($res) {
        $stats['total']            = (int)$res['total'];
        $stats['pending_payment']  = (int)$res['pending_payment'];
        $stats['menunggu_proses']  = (int)$res['menunggu_proses'];
        $stats['sedang_dikerjakan']= (int)$res['sedang_dikerjakan'];
        $stats['selesai']          = (int)$res['selesai'];
        $stats['total_pendapatan'] = (int)$res['total_pendapatan'];
    }

    // Ambil 6 pesanan terbaru
    $recentStmt = $pdo->query("
        SELECT o.*, u.name as customer_name, s.name as service_name 
        FROM orders o
        JOIN users u ON o.user_id = u.id
        JOIN services s ON o.service_id = s.id
        ORDER BY o.created_at DESC
        LIMIT 6
    ");
    $recentOrders = $recentStmt->fetchAll();

} catch (PDOException $e) {
    $recentOrders = [];
}

$pageTitle = 'Dashboard Admin';
$currentAdminPage = 'index.php';
require_once __DIR__ . '/../includes/admin-header.php';
?>

    <!-- KONTEN DASHBOARD -->
    <div class="container py-4">
        <?php display_flash(); ?>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Dashboard Administrator</h3>
                <p class="text-muted mb-0">Ringkasan transaksi dan status pengerjaan pesanan desain secara realtime.</p>
            </div>
            <div class="mt-3 mt-md-0">
                <span class="badge px-3 py-2 shadow-sm fs-6" style="background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 50%, #0284c7 100%);">
                    <i class="bi bi-wallet2 me-1"></i> Total Omzet: <span class="tabular-nums"><?= format_rupiah($stats['total_pendapatan']) ?></span>
                </span>
            </div>
        </div>

        <!-- 5 CARD STATISTIK (ECC Modern UI Pattern) -->
        <div class="row g-3 mb-4">
            <!-- Total Pesanan -->
            <div class="col-sm-6 col-lg-4 col-xl-2">
                <div class="admin-stat-card shadow-sm">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-semibold">Total Pesanan</span>
                        <div class="admin-stat-icon bg-secondary-subtle text-secondary">
                            <i class="bi bi-receipt"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="fw-bold my-1 text-dark tabular-nums"><?= $stats['total'] ?></h3>
                        <span class="small text-muted"><i class="bi bi-folder me-1"></i>Semua transaksi</span>
                    </div>
                </div>
            </div>

            <!-- Menunggu Pembayaran -->
            <div class="col-sm-6 col-lg-4 col-xl-2">
                <div class="admin-stat-card shadow-sm border-start border-4 border-warning">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-semibold">Belum Bayar</span>
                        <div class="admin-stat-icon bg-warning-subtle text-warning">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="fw-bold my-1 text-warning tabular-nums"><?= $stats['pending_payment'] ?></h3>
                        <span class="small text-muted">Menunggu transfer</span>
                    </div>
                </div>
            </div>

            <!-- Pesanan Baru / Menunggu Proses -->
            <div class="col-sm-6 col-lg-4 col-xl-2">
                <div class="admin-stat-card shadow-sm border-start border-4 border-info">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-semibold">Menunggu Proses</span>
                        <div class="admin-stat-icon bg-info-subtle text-info">
                            <i class="bi bi-inbox-fill"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="fw-bold my-1 text-info tabular-nums"><?= $stats['menunggu_proses'] ?></h3>
                        <span class="small text-muted">Antre pengerjaan</span>
                    </div>
                </div>
            </div>

            <!-- Sedang Dikerjakan -->
            <div class="col-sm-6 col-lg-4 col-xl-3">
                <div class="admin-stat-card shadow-sm border-start border-4 border-primary">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-semibold">Sedang Dikerjakan</span>
                        <div class="admin-stat-icon bg-primary-subtle text-primary">
                            <i class="bi bi-palette-fill"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="fw-bold my-1 text-primary tabular-nums"><?= $stats['sedang_dikerjakan'] ?></h3>
                        <span class="small text-muted">Dalam proses desain</span>
                    </div>
                </div>
            </div>

            <!-- Pesanan Selesai -->
            <div class="col-sm-6 col-lg-4 col-xl-3">
                <div class="admin-stat-card shadow-sm border-start border-4 border-success">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-semibold">Pesanan Selesai</span>
                        <div class="admin-stat-icon bg-success-subtle text-success">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="fw-bold my-1 text-success tabular-nums"><?= $stats['selesai'] ?></h3>
                        <span class="small text-muted">Desain diserahkan</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABEL PESANAN TERBARU -->
        <div class="card shadow-sm border mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark">Pesanan Masuk Terbaru</h5>
                <a href="<?= base_url('admin/orders.php') ?>" class="btn btn-sm btn-outline-primary">
                    Lihat Semua Pesanan <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <?php if (empty($recentOrders)): ?>
                    <div class="p-5 text-center text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
                        Belum ada data pesanan masuk.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No. Order</th>
                                    <th>Customer</th>
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
                                    <?php 
                                        $initial = strtoupper(substr($ord['customer_name'] ?? 'U', 0, 1));
                                    ?>
                                    <tr>
                                        <td>
                                            <a href="<?= base_url('admin/order-detail.php?id=' . $ord['id']) ?>" class="fw-bold font-monospace text-decoration-none">
                                                <?= e($ord['order_number']) ?>
                                            </a>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center fw-bold me-2" style="width: 32px; height: 32px; font-size: 0.8rem; flex-shrink: 0;">
                                                    <?= e($initial) ?>
                                                </div>
                                                <span class="fw-medium text-dark"><?= e($ord['customer_name']) ?></span>
                                            </div>
                                        </td>
                                        <td><?= e($ord['service_name']) ?></td>
                                        <td class="fw-semibold tabular-nums"><?= format_rupiah($ord['total']) ?></td>
                                        <td><?= get_payment_status_badge($ord['payment_status']) ?></td>
                                        <td><?= get_order_status_badge($ord['order_status']) ?></td>
                                        <td class="small text-muted tabular-nums"><?= date('d/m/Y H:i', strtotime($ord['created_at'])) ?></td>
                                        <td class="text-end">
                                            <a href="<?= base_url('admin/invoice.php?id=' . $ord['id']) ?>" class="btn btn-sm btn-outline-success me-1" title="Cetak Invoice" target="_blank">
                                                <i class="bi bi-printer"></i>
                                            </a>
                                            <a href="<?= base_url('admin/order-detail.php?id=' . $ord['id']) ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil-square me-1"></i> Detail &amp; Status
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

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
