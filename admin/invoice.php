<?php
// =====================================================================
// FILE: admin/invoice.php
// Halaman Cetak Invoice per Transaksi (Fitur Admin Baru)
// =====================================================================

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/admin-auth.php';

require_admin();
$admin = current_admin($pdo);

$orderId     = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$orderNumber = trim($_GET['order_number'] ?? '');

if ($orderId <= 0 && empty($orderNumber)) {
    set_flash('danger', 'ID atau Nomor Pesanan tidak valid.');
    redirect('admin/orders.php');
}

// Ambil data order + customer + layanan
if ($orderId > 0) {
    $stmt = $pdo->prepare("
        SELECT o.*,
               u.name  AS customer_name, u.email AS customer_email, u.phone AS customer_phone,
               s.name  AS service_name, s.duration AS service_duration
        FROM orders o
        JOIN users u ON o.user_id = u.id
        JOIN services s ON o.service_id = s.id
        WHERE o.id = ?
    ");
    $stmt->execute([$orderId]);
} else {
    $stmt = $pdo->prepare("
        SELECT o.*,
               u.name  AS customer_name, u.email AS customer_email, u.phone AS customer_phone,
               s.name  AS service_name, s.duration AS service_duration
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

// Ambil data pembayaran terakhir (jika ada)
$payStmt = $pdo->prepare("SELECT * FROM payments WHERE order_id = ? ORDER BY created_at DESC LIMIT 1");
$payStmt->execute([$order['id']]);
$payment = $payStmt->fetch();

$invoiceNumber = 'INV-' . $order['order_number'];
$invoiceDate   = $payment['paid_at'] ?? $order['updated_at'] ?? $order['created_at'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice <?= e($invoiceNumber) ?> - Kreavio Creative</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= asset_url('css/style.css') ?>">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; margin: 0; padding: 0; }
            .invoice-box {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                max-width: 100% !important;
                padding: 10px 0 !important;
            }
        }
        body { background: #eef1f5; }
        .invoice-box {
            max-width: 820px;
            margin: 30px auto 50px;
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,.08);
        }
        .invoice-box table td { padding: 8px 10px; }
    </style>
</head>
<body>

    <!-- Tombol Navigasi & Print (Hanya tampil di layar browser, tersembunyi saat cetak) -->
    <div class="container no-print pt-4" style="max-width: 820px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="<?= base_url('admin/order-detail.php?id=' . $order['id']) ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Detail Pesanan
            </a>
            <div class="d-flex gap-2">
                <a href="<?= base_url('admin/sales-report.php') ?>" class="btn btn-outline-primary">
                    <i class="bi bi-graph-up me-1"></i> Rekap Penjualan
                </a>
                <button onclick="window.print()" class="btn btn-success shadow-sm">
                    <i class="bi bi-printer me-1"></i> Cetak Invoice / Simpan PDF
                </button>
            </div>
        </div>
    </div>

    <!-- AREA INVOICE RESMI -->
    <div class="invoice-box">
        <!-- Header Invoice -->
        <div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-4">
            <div>
                <div class="d-flex align-items-center mb-2">
                    <img src="<?= asset_url('images/logo.svg') ?>" alt="Kreavio Creative Logo" height="34" class="me-2" onerror="this.style.display='none'">
                    <span class="fs-4 fw-bold text-primary">Kreavio Creative</span>
                </div>
                <p class="text-muted small mb-0 fw-semibold">Jasa Desain Grafis Digital Sederhana</p>
                <p class="text-muted small mb-0">Tagline: <em>"Create Better. Present Better."</em></p>
                <p class="text-muted small mb-0"><i class="bi bi-envelope me-1"></i> kreavio.creative@gmail.com</p>
            </div>
            <div class="text-end">
                <h3 class="fw-bold mb-1 text-primary">INVOICE</h3>
                <p class="mb-1 font-monospace fw-bold text-dark"><?= e($invoiceNumber) ?></p>
                <p class="mb-0 text-muted small"><strong>Tanggal:</strong> <?= format_date($invoiceDate) ?></p>
            </div>
        </div>

        <!-- Info Pemesan & Status -->
        <div class="row mb-4">
            <div class="col-sm-6 mb-3 mb-sm-0">
                <h6 class="fw-bold text-secondary text-uppercase small mb-2">Ditagihkan Kepada:</h6>
                <p class="mb-1 fw-bold fs-6 text-dark"><?= e($order['customer_name']) ?></p>
                <p class="mb-1 small text-muted"><i class="bi bi-envelope me-1"></i> <?= e($order['customer_email']) ?></p>
                <p class="mb-0 small text-muted"><i class="bi bi-whatsapp me-1"></i> <?= e($order['customer_phone']) ?></p>
            </div>
            <div class="col-sm-6 text-sm-end">
                <h6 class="fw-bold text-secondary text-uppercase small mb-2">Status Transaksi:</h6>
                <div class="mb-1"><?= get_payment_status_badge($order['payment_status']) ?></div>
                <div class="mb-1"><?= get_order_status_badge($order['order_status']) ?></div>
                <p class="mb-0 small text-muted"><strong>Target Deadline:</strong> <?= date('d M Y', strtotime($order['deadline'])) ?></p>
            </div>
        </div>

        <!-- Tabel Item Layanan -->
        <div class="table-responsive mb-4">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 50px;">No</th>
                        <th>Deskripsi Layanan</th>
                        <th>Estimasi Waktu</th>
                        <th class="text-center" style="width: 70px;">Qty</th>
                        <th class="text-end" style="width: 140px;">Harga Satuan</th>
                        <th class="text-end" style="width: 150px;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">1</td>
                        <td>
                            <strong class="text-dark"><?= e($order['service_name']) ?></strong>
                            <div class="small text-muted font-monospace">Order #<?= e($order['order_number']) ?></div>
                        </td>
                        <td><?= e($order['service_duration']) ?></td>
                        <td class="text-center">1</td>
                        <td class="text-end"><?= format_rupiah($order['total']) ?></td>
                        <td class="text-end fw-bold"><?= format_rupiah($order['total']) ?></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-end py-2">TOTAL PEMBAYARAN:</th>
                        <th class="text-end py-2 text-primary fs-5 fw-bold"><?= format_rupiah($order['total']) ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Detail Pembayaran & Catatan -->
        <div class="row g-3 mb-4">
            <div class="col-sm-6">
                <div class="p-3 bg-light rounded-3 border">
                    <h6 class="fw-bold text-dark small mb-2"><i class="bi bi-credit-card me-1"></i> Informasi Pembayaran</h6>
                    <div class="small text-muted mb-1">
                        <strong>Metode:</strong> <?= e($payment['payment_type'] ?? ($order['payment_status'] === 'paid' ? 'Midtrans / Online Transfer' : 'Menunggu Pembayaran')) ?>
                    </div>
                    <div class="small text-muted">
                        <strong>ID Referensi:</strong> <span class="font-monospace"><?= e($payment['transaction_id'] ?? '-') ?></span>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="p-3 bg-light rounded-3 border">
                    <h6 class="fw-bold text-dark small mb-2"><i class="bi bi-info-circle me-1"></i> Catatan Pemesanan</h6>
                    <div class="small text-muted">
                        <?= !empty($order['notes']) ? e($order['notes']) : 'Pengerjaan sesuai brief customer. Estimasi selesai sebelum batas target deadline.' ?>
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-4">
        
        <div class="text-center text-muted small">
            <p class="mb-1 fw-semibold">Terima kasih atas kepercayaan Anda memilih jasa Kreavio Creative!</p>
            <p class="mb-0 text-secondary" style="font-size: 0.8rem;">Invoice ini sah dan diterbitkan secara digital oleh sistem Kreavio Creative.</p>
        </div>
    </div>

</body>
</html>
