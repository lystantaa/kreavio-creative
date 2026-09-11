<?php
// =====================================================================
// FILE: admin/order-detail.php
// Halaman Detail & Pembaruan Status Pesanan oleh Admin (Section 21)
// =====================================================================

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/admin-auth.php';

require_admin();
$admin = current_admin($pdo);

$orderId     = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$orderNumber = trim($_GET['order_number'] ?? '');

// Ambil data order, customer, dan layanan
if ($orderId > 0) {
    $stmt = $pdo->prepare("
        SELECT o.*, 
               u.name as customer_name, u.email as customer_email, u.phone as customer_phone,
               s.name as service_name, s.duration as service_duration
        FROM orders o
        JOIN users u ON o.user_id = u.id
        JOIN services s ON o.service_id = s.id
        WHERE o.id = ?
    ");
    $stmt->execute([$orderId]);
} else {
    $stmt = $pdo->prepare("
        SELECT o.*, 
               u.name as customer_name, u.email as customer_email, u.phone as customer_phone,
               s.name as service_name, s.duration as service_duration
        FROM orders o
        JOIN users u ON o.user_id = u.id
        JOIN services s ON o.service_id = s.id
        WHERE o.order_number = ?
    ");
    $stmt->execute([$orderNumber]);
}
$order = $stmt->fetch();

if (!$order) {
    set_flash('danger', 'Pesanan tidak ditemukan.');
    redirect('admin/orders.php');
}

// Proses pembaruan status order
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['update_status'])) {
    $newStatus       = trim($_POST['order_status'] ?? '');
    $newPaymentStatus= trim($_POST['payment_status'] ?? '');
    $adminNote       = trim($_POST['admin_note'] ?? '');

    $allowedStatuses = ['pending_payment', 'menunggu_proses', 'sedang_dikerjakan', 'selesai', 'cancelled'];
    $allowedPayments = ['pending_payment', 'paid'];

    if (in_array($newStatus, $allowedStatuses) && in_array($newPaymentStatus, $allowedPayments)) {
        try {
            $pdo->beginTransaction();

            // Update status pesanan
            $upStmt = $pdo->prepare("
                UPDATE orders 
                SET order_status = ?, 
                    payment_status = ?, 
                    updated_at = NOW() 
                WHERE id = ?
            ");
            $upStmt->execute([$newStatus, $newPaymentStatus, $order['id']]);

            // Jika ada perubahan status atau catatan, simpan ke log
            $logNote = !empty($adminNote) ? $adminNote : 'Status pesanan diubah oleh Admin menjadi: ' . $newStatus;
            $logStmt = $pdo->prepare("
                INSERT INTO order_status_logs (order_id, status, note, created_at)
                VALUES (?, ?, ?, NOW())
            ");
            $logStmt->execute([$order['id'], $newStatus, $logNote]);

            // Jika status pembayaran diubah jadi paid dan belum ada payment log
            if ($newPaymentStatus === 'paid' && $order['payment_status'] !== 'paid') {
                $payStmt = $pdo->prepare("
                    INSERT INTO payments (order_id, transaction_id, payment_type, amount, status, paid_at, created_at)
                    VALUES (?, ?, 'manual_admin_confirmation', ?, 'settlement', NOW(), NOW())
                ");
                $payStmt->execute([$order['id'], 'ADM-' . time(), $order['total']]);
            }

            $pdo->commit();

            set_flash('success', 'Status pesanan berhasil diperbarui!');
            redirect('admin/order-detail.php?id=' . $order['id']);

        } catch (PDOException $e) {
            $pdo->rollBack();
            set_flash('danger', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    } else {
        set_flash('danger', 'Pilihan status tidak valid.');
    }
}

// Ambil riwayat log status
$logsStmt = $pdo->prepare("SELECT * FROM order_status_logs WHERE order_id = ? ORDER BY created_at DESC");
$logsStmt->execute([$order['id']]);
$statusLogs = $logsStmt->fetchAll();

// Ambil riwayat pembayaran
$payStmt = $pdo->prepare("SELECT * FROM payments WHERE order_id = ? ORDER BY created_at DESC");
$payStmt->execute([$order['id']]);
$payments = $payStmt->fetchAll();
$pageTitle = 'Detail Order #' . $order['order_number'];
$currentAdminPage = 'order-detail.php';
require_once __DIR__ . '/../includes/admin-header.php';
?>

    <div class="container py-4">
        <?php display_flash(); ?>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/orders.php') ?>">Pesanan</a></li>
                        <li class="breadcrumb-item active"><?= e($order['order_number']) ?></li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-0">Pesanan: <?= e($order['order_number']) ?></h3>
            </div>
            <div class="mt-3 mt-md-0 d-flex gap-2">
                <a href="<?= base_url('admin/invoice.php?id=' . $order['id']) ?>" class="btn btn-success" target="_blank">
                    <i class="bi bi-printer me-1"></i> Cetak Invoice
                </a>
                <a href="<?= base_url('admin/orders.php') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Pesanan
                </a>
            </div>
        </div>

        <div class="row g-4">
            <!-- Kolom Kiri: Data Pesanan, Customer, & Brief -->
            <div class="col-lg-7">
                <!-- Data Customer -->
                <div class="card shadow-sm border-0 p-4 mb-4">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">
                        <i class="bi bi-person me-2 text-primary"></i>Informasi Pemesan
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted ps-0" style="width: 35%;">Nama Customer:</td>
                                    <td class="fw-bold"><?= e($order['customer_name']) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted ps-0">Email:</td>
                                    <td><a href="mailto:<?= e($order['customer_email']) ?>"><?= e($order['customer_email']) ?></a></td>
                                </tr>
                                <tr>
                                    <td class="text-muted ps-0">Nomor WhatsApp:</td>
                                    <td>
                                        <a href="https://wa.me/<?= format_whatsapp_number($order['customer_phone']) ?>?text=Halo%20<?= urlencode($order['customer_name']) ?>%20dari%20Kreavio%20mengenai%20pesanan%20<?= urlencode($order['order_number']) ?>" target="_blank" class="btn btn-sm btn-success py-0 px-2">
                                            <i class="bi bi-whatsapp me-1"></i> <?= e($order['customer_phone']) ?>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Detail Order -->
                <div class="card shadow-sm border-0 p-4 mb-4">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">
                        <i class="bi bi-card-text me-2 text-primary"></i>Detail Kebutuhan Desain
                    </h5>
                    <div class="table-responsive mb-3">
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted ps-0" style="width: 35%;">Layanan:</td>
                                    <td class="fw-bold fs-5 text-primary"><?= e($order['service_name']) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted ps-0">Estimasi Durasi:</td>
                                    <td><?= e($order['service_duration']) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted ps-0">Deadline Ditargetkan:</td>
                                    <td class="fw-bold text-danger"><?= date('d F Y', strtotime($order['deadline'])) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted ps-0">Total Biaya:</td>
                                    <td class="fw-bold fs-5 text-dark"><?= format_rupiah($order['total']) ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h6 class="fw-bold mb-2">Brief Instruksi:</h6>
                    <div class="p-3 bg-light rounded-3 mb-3 border">
                        <p class="mb-0" style="white-space: pre-wrap; line-height: 1.6;"><?= e($order['brief']) ?></p>
                    </div>

                    <?php if (!empty($order['notes'])): ?>
                        <h6 class="fw-bold mb-2">Catatan Tambahan:</h6>
                        <div class="p-3 bg-light rounded-3 border">
                            <p class="mb-0 text-muted" style="white-space: pre-wrap;"><?= e($order['notes']) ?></p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Riwayat Perubahan Status -->
                <div class="card shadow-sm border-0 p-4">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">
                        <i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Status Pengerjaan
                    </h5>
                    <div class="list-group list-group-flush">
                        <?php foreach ($statusLogs as $log): ?>
                            <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-start bg-transparent">
                                <div>
                                    <div class="mb-1"><?= get_order_status_badge($log['status']) ?></div>
                                    <div class="small text-muted"><?= e($log['note'] ?: '-') ?></div>
                                </div>
                                <span class="small text-muted"><?= format_date($log['created_at']) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Form Update Status Pesanan -->
            <div class="col-lg-5">
                <div class="card shadow-sm border-0 p-4 mb-4">
                    <h5 class="fw-bold mb-3 border-bottom pb-2 text-primary">
                        <i class="bi bi-sliders me-2"></i>Ubah Status Pesanan
                    </h5>
                    <p class="text-muted small">
                        Perubahan status yang Anda simpan di sini akan langsung tampil pada dashboard dan halaman tracking customer.
                    </p>

                    <form method="POST" action="">
                        <input type="hidden" name="update_status" value="1">

                        <!-- Status Order -->
                        <div class="mb-3">
                            <label for="order_status" class="form-label fw-semibold">Status Pengerjaan Order</label>
                            <select class="form-select form-select-lg" id="order_status" name="order_status" required>
                                <option value="pending_payment" <?= ($order['order_status'] === 'pending_payment') ? 'selected' : '' ?>>
                                    Menunggu Pembayaran
                                </option>
                                <option value="menunggu_proses" <?= ($order['order_status'] === 'menunggu_proses') ? 'selected' : '' ?>>
                                    Menunggu Diproses
                                </option>
                                <option value="sedang_dikerjakan" <?= ($order['order_status'] === 'sedang_dikerjakan') ? 'selected' : '' ?>>
                                    Sedang Dikerjakan
                                </option>
                                <option value="selesai" <?= ($order['order_status'] === 'selesai') ? 'selected' : '' ?>>
                                    Selesai
                                </option>
                                <option value="cancelled" <?= ($order['order_status'] === 'cancelled') ? 'selected' : '' ?>>
                                    Dibatalkan
                                </option>
                            </select>
                        </div>

                        <!-- Status Pembayaran -->
                        <div class="mb-3">
                            <label for="payment_status" class="form-label fw-semibold">Status Pembayaran</label>
                            <select class="form-select" id="payment_status" name="payment_status" required>
                                <option value="pending_payment" <?= ($order['payment_status'] === 'pending_payment') ? 'selected' : '' ?>>
                                    Belum Bayar
                                </option>
                                <option value="paid" <?= ($order['payment_status'] === 'paid') ? 'selected' : '' ?>>
                                    Lunas
                                </option>
                            </select>
                        </div>

                        <!-- Catatan Perubahan -->
                        <div class="mb-4">
                            <label for="admin_note" class="form-label fw-semibold">Catatan untuk Customer (Opsional)</label>
                            <textarea class="form-control" id="admin_note" name="admin_note" rows="3" placeholder="Contoh: Desain sedang dibuat oleh desainer, estimasi draft pertama besok siang."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan Status
                        </button>
                    </form>
                </div>

                <!-- Informasi Transaksi Pembayaran -->
                <div class="card shadow-sm border-0 p-4">
                    <h6 class="fw-bold mb-3 border-bottom pb-2">Log Transaksi Pembayaran</h6>
                    <?php if (empty($payments)): ?>
                        <p class="small text-muted mb-0">Belum ada transaksi pembayaran yang tercatat.</p>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($payments as $pay): ?>
                                <div class="list-group-item px-0 py-2 small bg-transparent">
                                    <div class="d-flex justify-content-between">
                                        <strong class="font-monospace"><?= e($pay['transaction_id']) ?></strong>
                                        <span class="badge bg-success"><?= e($pay['status']) ?></span>
                                    </div>
                                    <div class="text-muted">
                                        Metode: <?= e($pay['payment_type']) ?> &bull; <?= format_rupiah($pay['amount']) ?>
                                    </div>
                                    <div class="text-muted small">
                                        <?= format_date($pay['paid_at'] ?: $pay['created_at']) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
