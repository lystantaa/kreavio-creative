<?php
// =====================================================================
// FILE: success.php
// Halaman Konfirmasi Pembayaran Berhasil & Bukti Pesanan
// =====================================================================

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

require_login();
$user = current_user($pdo);

$orderNumber = $_GET['order_number'] ?? '';

if (empty($orderNumber)) {
    redirect('customer/orders.php');
}

$stmt = $pdo->prepare("
    SELECT o.*, s.name AS service_name, s.duration AS service_duration
    FROM orders o
    JOIN services s ON o.service_id = s.id
    WHERE o.order_number = ? AND o.user_id = ?
");
$stmt->execute([$orderNumber, $user['id']]);
$order = $stmt->fetch();

if (!$order) {
    set_flash('danger', 'Pesanan tidak ditemukan.');
    redirect('customer/orders.php');
}

// Jika kembali dari Midtrans Snap callback dan status masih pending_payment
if ($order['payment_status'] === 'pending_payment' && isset($_GET['paid_via'])) {
    try {
        $pdo->beginTransaction();

        $upStmt = $pdo->prepare("
            UPDATE orders 
            SET payment_status = 'paid', 
                order_status = 'menunggu_proses', 
                updated_at = NOW() 
            WHERE id = ?
        ");
        $upStmt->execute([$order['id']]);

        $trxId = 'MID-' . date('Ymd') . '-' . rand(1000, 9999);
        $payStmt = $pdo->prepare("
            INSERT INTO payments (order_id, transaction_id, payment_type, amount, status, paid_at, created_at) 
            VALUES (?, ?, 'midtrans_sandbox', ?, 'settlement', NOW(), NOW())
        ");
        $payStmt->execute([$order['id'], $trxId, $order['total']]);

        $logStmt = $pdo->prepare("
            INSERT INTO order_status_logs (order_id, status, note, created_at) 
            VALUES (?, 'menunggu_proses', 'Pembayaran berhasil dikonfirmasi via Midtrans Snap', NOW())
        ");
        $logStmt->execute([$order['id']]);

        $pdo->commit();

        $order['payment_status'] = 'paid';
        $order['order_status'] = 'menunggu_proses';

    } catch (PDOException $e) {
        $pdo->rollBack();
    }
}

$pageTitle = 'Pembayaran Berhasil #' . $order['order_number'];
require_once __DIR__ . '/includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm border p-4 text-center">
                <div class="mb-3">
                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 72px; height: 72px;">
                        <i class="bi bi-check-lg fs-1"></i>
                    </div>
                </div>

                <h3 class="fw-bold text-success mb-2">Pembayaran Berhasil!</h3>
                <p class="text-muted mb-4">
                    Terima kasih. Pembayaran untuk pesanan Anda telah berhasil dikonfirmasi dan pesanan kini sedang menunggu untuk dikerjakan oleh tim Kreavio Creative.
                </p>

                <!-- Ringkasan Struk -->
                <div class="p-4 bg-light rounded-3 text-start mb-4 border">
                    <div class="d-flex justify-content-between pb-2 mb-2 border-bottom">
                        <span class="text-muted">Nomor Pesanan:</span>
                        <span class="fw-bold font-monospace"><?= e($order['order_number']) ?></span>
                    </div>
                    <div class="d-flex justify-content-between pb-2 mb-2 border-bottom">
                        <span class="text-muted">Layanan:</span>
                        <span class="fw-semibold"><?= e($order['service_name']) ?></span>
                    </div>
                    <div class="d-flex justify-content-between pb-2 mb-2 border-bottom">
                        <span class="text-muted">Target Selesai (Deadline):</span>
                        <span class="fw-semibold"><?= date('d M Y', strtotime($order['deadline'])) ?></span>
                    </div>
                    <div class="d-flex justify-content-between pb-2 mb-2 border-bottom">
                        <span class="text-muted">Status Pembayaran:</span>
                        <span><?= get_payment_status_badge($order['payment_status']) ?></span>
                    </div>
                    <div class="d-flex justify-content-between pb-2 mb-2 border-bottom">
                        <span class="text-muted">Status Pesanan:</span>
                        <span><?= get_order_status_badge($order['order_status']) ?></span>
                    </div>
                    <div class="d-flex justify-content-between pt-1">
                        <span class="fw-bold">Total Terbayar:</span>
                        <span class="fw-bold text-primary fs-5"><?= format_rupiah($order['total']) ?></span>
                    </div>
                </div>

                <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
                    <a href="<?= base_url('customer/invoice.php?order_number=' . urlencode($order['order_number'])) ?>" target="_blank" class="btn btn-outline-success px-4">
                        <i class="bi bi-printer me-1"></i> Cetak Invoice (PDF)
                    </a>
                    <a href="<?= base_url('customer/order-detail.php?id=' . $order['id']) ?>" class="btn btn-primary px-4">
                        <i class="bi bi-eye me-1"></i> Lihat Status Pesanan
                    </a>
                    <a href="<?= base_url('customer/dashboard.php') ?>" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-speedometer2 me-1"></i> Ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
