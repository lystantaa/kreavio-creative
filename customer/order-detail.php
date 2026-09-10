<?php
// =====================================================================
// FILE: customer/order-detail.php
// Halaman Detail & Tracking Pesanan Customer Kreavio Creative
// =====================================================================

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();
$user = current_user($pdo);

$orderId     = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$orderNumber = trim($_GET['order_number'] ?? '');

try {
    if ($orderId > 0) {
        $stmt = $pdo->prepare("
            SELECT o.*, s.name as service_name, s.description as service_desc, s.duration as service_duration
            FROM orders o
            JOIN services s ON o.service_id = s.id
            WHERE o.id = ? AND o.user_id = ?
        ");
        $stmt->execute([$orderId, $user['id']]);
    } else {
        $stmt = $pdo->prepare("
            SELECT o.*, s.name as service_name, s.description as service_desc, s.duration as service_duration
            FROM orders o
            JOIN services s ON o.service_id = s.id
            WHERE o.order_number = ? AND o.user_id = ?
        ");
        $stmt->execute([$orderNumber, $user['id']]);
    }
    $order = $stmt->fetch();

    if (!$order) {
        set_flash('danger', 'Pesanan tidak ditemukan atau Anda tidak memiliki hak akses.');
        redirect('customer/orders.php');
    }

    // Ambil riwayat log status pesanan
    $logStmt = $pdo->prepare("SELECT * FROM order_status_logs WHERE order_id = ? ORDER BY created_at ASC");
    $logStmt->execute([$order['id']]);
    $statusLogs = $logStmt->fetchAll();

} catch (PDOException $e) {
    set_flash('danger', 'Terjadi kesalahan sistem: ' . $e->getMessage());
    redirect('customer/orders.php');
}

// Menentukan langkah aktif pada timeline sederhana
// Tahapan: 1. Pesanan Dibuat | 2. Dibayar | 3. Diproses | 4. Dikerjakan | 5. Selesai
$stepLevel = 1;
if ($order['payment_status'] === 'paid') {
    $stepLevel = 2;
}
if ($order['order_status'] === 'menunggu_proses') {
    $stepLevel = 3;
} elseif ($order['order_status'] === 'sedang_dikerjakan') {
    $stepLevel = 4;
} elseif ($order['order_status'] === 'selesai') {
    $stepLevel = 5;
}

$pageTitle = 'Detail Pesanan #' . $order['order_number'];
require_once __DIR__ . '/../includes/header.php';
?>

<div class="bg-light py-4 border-bottom mb-4">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="<?= base_url('customer/dashboard.php') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('customer/orders.php') ?>">Pesanan Saya</a></li>
                    <li class="breadcrumb-item active"><?= e($order['order_number']) ?></li>
                </ol>
            </nav>
            <h2 class="fw-bold mb-0">Pesanan: <?= e($order['order_number']) ?></h2>
        </div>
        <div class="mt-3 mt-md-0 d-flex gap-2">
            <?php if ($order['payment_status'] === 'pending_payment'): ?>
                <a href="<?= base_url('checkout.php?order_number=' . urlencode($order['order_number'])) ?>" class="btn btn-warning fw-bold">
                    <i class="bi bi-wallet2 me-1"></i> Bayar Sekarang
                </a>
            <?php endif; ?>
            <a href="<?= base_url('customer/orders.php') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="container pb-5">
    <!-- TIMELINE SEDERHANA (Requirement Section 17) -->
    <!-- Pesanan Dibuat -> Dibayar -> Diproses -> Dikerjakan -> Selesai -->
    <div class="card shadow-sm border p-4 mb-4">
        <h5 class="fw-bold mb-3"><i class="bi bi-diagram-3 me-2 text-primary"></i>Tracking Progres Pesanan</h5>
        
        <?php if ($order['order_status'] === 'cancelled'): ?>
            <div class="alert alert-danger mb-0">
                <i class="bi bi-x-circle-fill me-2"></i> <strong>Pesanan ini telah Dibatalkan.</strong> Silakan hubungi tim Kreavio jika Anda memiliki pertanyaan.
            </div>
        <?php else: ?>
            <div class="timeline-steps">
                <!-- Step 1: Pesanan Dibuat -->
                <div class="timeline-step <?= ($stepLevel >= 1) ? 'completed' : '' ?>">
                    <div class="timeline-icon">
                        <i class="bi bi-file-earmark-plus"></i>
                    </div>
                    <div class="timeline-label">1. Pesanan Dibuat</div>
                </div>

                <!-- Step 2: Dibayar -->
                <div class="timeline-step <?= ($stepLevel > 2) ? 'completed' : (($stepLevel == 2) ? 'active' : '') ?>">
                    <div class="timeline-icon">
                        <i class="bi bi-credit-card"></i>
                    </div>
                    <div class="timeline-label">2. Dibayar</div>
                </div>

                <!-- Step 3: Diproses -->
                <div class="timeline-step <?= ($stepLevel > 3) ? 'completed' : (($stepLevel == 3) ? 'active' : '') ?>">
                    <div class="timeline-icon">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <div class="timeline-label">3. Diproses</div>
                </div>

                <!-- Step 4: Dikerjakan -->
                <div class="timeline-step <?= ($stepLevel > 4) ? 'completed' : (($stepLevel == 4) ? 'active' : '') ?>">
                    <div class="timeline-icon">
                        <i class="bi bi-palette"></i>
                    </div>
                    <div class="timeline-label">4. Dikerjakan</div>
                </div>

                <!-- Step 5: Selesai -->
                <div class="timeline-step <?= ($stepLevel == 5) ? 'completed' : '' ?>">
                    <div class="timeline-icon">
                        <i class="bi bi-check2-all"></i>
                    </div>
                    <div class="timeline-label">5. Selesai</div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="row g-4">
        <!-- Kolom Kiri: Detail Informasi Pesanan -->
        <div class="col-lg-8">
            <div class="card shadow-sm border p-4 mb-4">
                <h5 class="fw-bold mb-3 border-bottom pb-2">Informasi Lengkap Pesanan</h5>

                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0">
                        <tbody>
                            <tr>
                                <td class="text-muted ps-0" style="width: 30%;">Nomor Order</td>
                                <td class="fw-bold font-monospace"><?= e($order['order_number']) ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-0">Jenis Layanan</td>
                                <td class="fw-bold text-primary"><?= e($order['service_name']) ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-0">Estimasi Durasi</td>
                                <td><?= e($order['service_duration']) ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-0">Target Selesai (Deadline)</td>
                                <td class="fw-semibold text-danger"><?= date('d F Y', strtotime($order['deadline'])) ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-0">Tanggal Pemesanan</td>
                                <td><?= format_date($order['created_at']) ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-0">Status Pembayaran</td>
                                <td><?= get_payment_status_badge($order['payment_status']) ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-0">Status Pengerjaan</td>
                                <td><?= get_order_status_badge($order['order_status']) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <hr class="my-3">

                <h6 class="fw-bold mb-2">Deskripsi / Brief Kebutuhan Desain:</h6>
                <div class="p-3 bg-light rounded-3 mb-3">
                    <p class="mb-0" style="white-space: pre-wrap; line-height: 1.6;"><?= e($order['brief']) ?></p>
                </div>

                <?php if (!empty($order['notes'])): ?>
                    <h6 class="fw-bold mb-2">Catatan Tambahan:</h6>
                    <div class="p-3 bg-light rounded-3 mb-3">
                        <p class="mb-0 text-muted" style="white-space: pre-wrap;"><?= e($order['notes']) ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Riwayat Status Log -->
            <div class="card shadow-sm border p-4">
                <h5 class="fw-bold mb-3 border-bottom pb-2">Riwayat Perubahan Status</h5>
                <?php if (empty($statusLogs)): ?>
                    <p class="text-muted small mb-0">Belum ada riwayat aktivitas pada pesanan ini.</p>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($statusLogs as $log): ?>
                            <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-start bg-transparent">
                                <div>
                                    <div class="fw-semibold mb-1"><?= get_order_status_badge($log['status']) ?></div>
                                    <div class="small text-muted"><?= e($log['note'] ?: 'Status diperbarui oleh sistem') ?></div>
                                </div>
                                <div class="small text-muted text-end">
                                    <?= format_date($log['created_at']) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Kolom Kanan: Rincian Biaya & Kontak Bantuan -->
        <div class="col-lg-4">
            <div class="card shadow-sm border p-4 mb-4">
                <h5 class="fw-bold mb-3 border-bottom pb-2">Rincian Pembayaran</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Biaya Desain:</span>
                    <span class="fw-semibold"><?= format_rupiah($order['total']) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Biaya Admin:</span>
                    <span class="fw-semibold text-success">Rp0 (Gratis)</span>
                </div>
                <div class="d-flex justify-content-between pt-2 border-top">
                    <span class="fw-bold fs-5">Total:</span>
                    <span class="fw-bold fs-5 text-primary"><?= format_rupiah($order['total']) ?></span>
                </div>

                <?php if ($order['payment_status'] === 'pending_payment'): ?>
                    <div class="d-grid mt-4">
                        <a href="<?= base_url('checkout.php?order_number=' . urlencode($order['order_number'])) ?>" class="btn btn-warning fw-bold btn-lg shadow-sm">
                            <i class="bi bi-credit-card me-1"></i> Selesaikan Pembayaran
                        </a>
                    </div>
                <?php else: ?>
                    <div class="alert alert-success text-center mt-3 mb-0 py-2 small">
                        <i class="bi bi-check-circle-fill me-1"></i> Pembayaran Telah Lunas
                    </div>
                <?php endif; ?>
            </div>

            <!-- Card Bantuan WhatsApp -->
            <div class="card shadow-sm border p-4 bg-light">
                <h6 class="fw-bold mb-2"><i class="bi bi-whatsapp text-success me-1"></i> Butuh Bantuan?</h6>
                <p class="small text-muted mb-3">
                    Ingin mengirimkan file pendukung atau berdiskusi langsung dengan tim desainer Kreavio?
                </p>
                <a href="https://wa.me/6281234567890?text=Halo%20Kreavio,%20saya%20ingin%20koordinasi%20pesanan%20<?= urlencode($order['order_number']) ?>" target="_blank" class="btn btn-outline-success btn-sm w-100">
                    <i class="bi bi-chat-dots me-1"></i> Hubungi CS via WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
