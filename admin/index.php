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
                <p class="text-muted mb-0">Ringkasan transaksi dan status pengerjaan pesanan desain.</p>
            </div>
            <div class="mt-3 mt-md-0">
                <span class="badge bg-primary fs-6 px-3 py-2">
                    Total Omzet: <?= format_rupiah($stats['total_pendapatan']) ?>
                </span>
            </div>
        </div>

        <!-- 5 CARD STATISTIK (Sesuai Section 19) -->
        <div class="row g-3 mb-4">
            <!-- Total Pesanan -->
            <div class="col-sm-6 col-lg-4 col-xl-2">
                <div class="card p-3 border shadow-sm h-100">
                    <span class="text-muted small fw-semibold">Total Pesanan</span>
                    <h3 class="fw-bold my-2 text-dark"><?= $stats['total'] ?></h3>
                    <span class="small text-secondary"><i class="bi bi-folder me-1"></i>Semua transaksi</span>
                </div>
            </div>

            <!-- Menunggu Pembayaran -->
            <div class="col-sm-6 col-lg-4 col-xl-2">
                <div class="card p-3 border shadow-sm h-100 border-start border-4 border-warning">
                    <span class="text-muted small fw-semibold">Menunggu Bayar</span>
                    <h3 class="fw-bold my-2 text-warning"><?= $stats['pending_payment'] ?></h3>
                    <span class="small text-muted">Belum lunas</span>
                </div>
            </div>

            <!-- Pesanan Baru / Menunggu Proses -->
            <div class="col-sm-6 col-lg-4 col-xl-2">
                <div class="card p-3 border shadow-sm h-100 border-start border-4 border-info">
                    <span class="text-muted small fw-semibold">Menunggu Proses</span>
                    <h3 class="fw-bold my-2 text-info"><?= $stats['menunggu_proses'] ?></h3>
                    <span class="small text-muted">Lunas, antre pengerjaan</span>
                </div>
            </div>

            <!-- Sedang Dikerjakan -->
            <div class="col-sm-6 col-lg-4 col-xl-3">
                <div class="card p-3 border shadow-sm h-100 border-start border-4 border-primary">
                    <span class="text-muted small fw-semibold">Sedang Dikerjakan</span>
                    <h3 class="fw-bold my-2 text-primary"><?= $stats['sedang_dikerjakan'] ?></h3>
                    <span class="small text-muted">Dalam proses desain</span>
                </div>
            </div>

            <!-- Pesanan Selesai -->
            <div class="col-sm-6 col-lg-4 col-xl-3">
                <div class="card p-3 border shadow-sm h-100 border-start border-4 border-success">
                    <span class="text-muted small fw-semibold">Pesanan Selesai</span>
                    <h3 class="fw-bold my-2 text-success"><?= $stats['selesai'] ?></h3>
                    <span class="small text-muted">Desain diserahkan</span>
                </div>
            </div>
        </div>

        <!-- TABEL PESANAN TERBARU -->
        <div class="card shadow-sm border mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Pesanan Masuk Terbaru</h5>
                <a href="<?= base_url('admin/orders.php') ?>" class="btn btn-sm btn-outline-primary">
                    Lihat Semua Pesanan
                </a>
            </div>
            <div class="card-body p-0">
                <?php if (empty($recentOrders)): ?>
                    <div class="p-4 text-center text-muted">Belum ada data pesanan masuk.</div>
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
                                    <tr>
                                        <td>
                                            <a href="<?= base_url('admin/order-detail.php?id=' . $ord['id']) ?>" class="fw-bold font-monospace text-decoration-none">
                                                <?= e($ord['order_number']) ?>
                                            </a>
                                        </td>
                                        <td><?= e($ord['customer_name']) ?></td>
                                        <td><?= e($ord['service_name']) ?></td>
                                        <td class="fw-semibold"><?= format_rupiah($ord['total']) ?></td>
                                        <td><?= get_payment_status_badge($ord['payment_status']) ?></td>
                                        <td><?= get_order_status_badge($ord['order_status']) ?></td>
                                        <td class="small text-muted"><?= date('d/m/Y H:i', strtotime($ord['created_at'])) ?></td>
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
