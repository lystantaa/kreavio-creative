<?php
// =====================================================================
// FILE: admin/orders.php
// Halaman Kelola Semua Pesanan untuk Admin (Section 20)
// =====================================================================

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/admin-auth.php';

require_admin();
$admin = current_admin($pdo);

// Filter status jika ada
$statusFilter = $_GET['status'] ?? '';
$search = trim($_GET['search'] ?? '');

$sql = "
    SELECT o.*, u.name as customer_name, u.email as customer_email, u.phone as customer_phone, s.name as service_name 
    FROM orders o
    JOIN users u ON o.user_id = u.id
    JOIN services s ON o.service_id = s.id
    WHERE 1=1
";
$params = [];

if (!empty($statusFilter)) {
    $sql .= " AND o.order_status = ?";
    $params[] = $statusFilter;
}

if (!empty($search)) {
    $sql .= " AND (o.order_number LIKE ? OR u.name LIKE ? OR s.name LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$sql .= " ORDER BY o.created_at DESC";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $orders = $stmt->fetchAll();
} catch (PDOException $e) {
    $orders = [];
}

$pageTitle = 'Kelola Pesanan';
$currentAdminPage = 'orders.php';
require_once __DIR__ . '/../includes/admin-header.php';
?>

    <div class="container py-4">
        <?php display_flash(); ?>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Daftar Semua Pesanan</h3>
                <p class="text-muted mb-0">Pantau, periksa detail, dan perbarui status pesanan customer.</p>
            </div>
        </div>

        <!-- Filter & Search Box -->
        <div class="card shadow-sm border-0 p-3 mb-4">
            <form method="GET" action="" class="row g-3">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control" placeholder="Cari No. Order / Nama Customer / Layanan..." value="<?= e($search) ?>">
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select">
                        <option value="">-- Semua Status Order --</option>
                        <option value="pending_payment" <?= ($statusFilter === 'pending_payment') ? 'selected' : '' ?>>Menunggu Pembayaran</option>
                        <option value="menunggu_proses" <?= ($statusFilter === 'menunggu_proses') ? 'selected' : '' ?>>Menunggu Proses</option>
                        <option value="sedang_dikerjakan" <?= ($statusFilter === 'sedang_dikerjakan') ? 'selected' : '' ?>>Sedang Dikerjakan</option>
                        <option value="selesai" <?= ($statusFilter === 'selesai') ? 'selected' : '' ?>>Selesai</option>
                        <option value="cancelled" <?= ($statusFilter === 'cancelled') ? 'selected' : '' ?>>Dibatalkan</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    <a href="<?= base_url('admin/orders.php') ?>" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>

        <!-- Tabel Pesanan -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <?php if (empty($orders)): ?>
                    <div class="p-5 text-center text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        Tidak ada pesanan yang sesuai dengan filter.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nomor Order</th>
                                    <th>Customer</th>
                                    <th>Layanan</th>
                                    <th>Total</th>
                                    <th>Payment Status</th>
                                    <th>Order Status</th>
                                    <th>Tanggal</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $ord): ?>
                                    <tr>
                                        <td>
                                            <a href="<?= base_url('admin/order-detail.php?id=' . $ord['id']) ?>" class="fw-bold font-monospace text-decoration-none">
                                                <?= e($ord['order_number']) ?>
                                            </a>
                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?= e($ord['customer_name']) ?></div>
                                            <div class="small text-muted"><?= e($ord['customer_phone']) ?></div>
                                        </td>
                                        <td><?= e($ord['service_name']) ?></td>
                                        <td class="fw-semibold"><?= format_rupiah($ord['total']) ?></td>
                                        <td><?= get_payment_status_badge($ord['payment_status']) ?></td>
                                        <td><?= get_order_status_badge($ord['order_status']) ?></td>
                                        <td class="small text-muted"><?= date('d/m/Y H:i', strtotime($ord['created_at'])) ?></td>
                                        <td class="text-end">
                                            <a href="<?= base_url('admin/invoice.php?id=' . $ord['id']) ?>" class="btn btn-sm btn-outline-success me-1" title="Cetak Invoice" target="_blank">
                                                <i class="bi bi-printer"></i>
                                            </a>
                                            <a href="<?= base_url('admin/order-detail.php?id=' . $ord['id']) ?>" class="btn btn-sm btn-primary">
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

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
