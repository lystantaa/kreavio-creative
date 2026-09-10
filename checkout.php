<?php
// =====================================================================
// FILE: checkout.php
// Halaman Checkout & Pembayaran (Single Order Checkout)
// Mendukung Midtrans Sandbox & Demo Payment Mode untuk Presentasi
// =====================================================================

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

require_login();
$user = current_user($pdo);

$orderNumber = $_GET['order_number'] ?? '';

if (empty($orderNumber)) {
    set_flash('danger', 'Nomor pesanan tidak ditemukan.');
    redirect('customer/orders.php');
}

// Ambil data order milik customer yang sedang login
$stmt = $pdo->prepare("
    SELECT o.*, s.name AS service_name, s.description AS service_description, s.duration AS service_duration 
    FROM orders o 
    JOIN services s ON o.service_id = s.id 
    WHERE o.order_number = ? AND o.user_id = ?
");
$stmt->execute([$orderNumber, $user['id']]);
$order = $stmt->fetch();

if (!$order) {
    set_flash('danger', 'Pesanan tidak ditemukan atau Anda tidak memiliki akses ke pesanan ini.');
    redirect('customer/orders.php');
}

// Jika pesanan sudah dibayar, langsung alihkan ke halaman sukses
if ($order['payment_status'] === 'paid') {
    redirect('success.php?order_number=' . urlencode($orderNumber));
}

$pageTitle = 'Checkout Pesanan #' . $order['order_number'];
require_once __DIR__ . '/includes/header.php';
?>

<div class="bg-light py-4 border-bottom mb-4">
    <div class="container">
        <h2 class="fw-bold mb-1">Checkout Pembayaran</h2>
        <p class="text-muted mb-0">Periksa ringkasan pesanan Anda sebelum menyelesaikan pembayaran</p>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-4 justify-content-center">
        <!-- Kolom Kiri: Rincian Pesanan -->
        <div class="col-lg-7">
            <div class="card shadow-sm border p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center pb-3 mb-3 border-bottom">
                    <h5 class="fw-bold mb-0">Detail Pesanan</h5>
                    <span class="badge bg-secondary font-monospace"><?= e($order['order_number']) ?></span>
                </div>

                <div class="table-responsive mb-3">
                    <table class="table table-borderless align-middle mb-0">
                        <tbody>
                            <tr>
                                <td class="text-muted ps-0" style="width: 35%;">Layanan Desain:</td>
                                <td class="fw-bold fs-5 text-dark"><?= e($order['service_name']) ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-0">Estimasi Selesai:</td>
                                <td><?= e($order['service_duration']) ?> (Deadline: <span class="fw-semibold text-danger"><?= date('d M Y', strtotime($order['deadline'])) ?></span>)</td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-0">Nama Pemesan:</td>
                                <td class="fw-semibold"><?= e($user['name']) ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-0">WhatsApp:</td>
                                <td><?= e($user['phone']) ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-0">Status Saat Ini:</td>
                                <td><?= get_order_status_badge($order['order_status']) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="p-3 bg-light rounded-3 mb-3">
                    <h6 class="fw-bold mb-1">Brief Instruksi Desain:</h6>
                    <p class="text-muted small mb-0" style="white-space: pre-wrap;"><?= e($order['brief']) ?></p>
                    <?php if (!empty($order['notes'])): ?>
                        <div class="mt-2 pt-2 border-top border-secondary border-opacity-10">
                            <span class="fw-semibold small">Catatan:</span>
                            <span class="text-muted small"><?= e($order['notes']) ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                    <span class="fs-5 fw-bold">Total Pembayaran:</span>
                    <span class="fs-4 fw-bold text-primary"><?= format_rupiah($order['total']) ?></span>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Pembayaran Midtrans Gateway -->
        <div class="col-lg-5">
            <div class="card shadow-sm border mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="fw-bold mb-0 text-dark d-flex align-items-center">
                        <i class="bi bi-shield-check text-primary fs-5 me-2"></i> Midtrans Payment Gateway
                    </h5>
                    <small class="text-muted">Bayar otomatis via QRIS, Virtual Account (BCA/BNI/BRI/Mandiri), &amp; E-Wallet</small>
                </div>
                <div class="card-body p-4 text-center">
                    <div class="d-flex justify-content-center align-items-center gap-2 mb-3">
                        <span class="badge bg-light text-dark border px-2 py-1"><i class="bi bi-qr-code me-1"></i> QRIS</span>
                        <span class="badge bg-light text-dark border px-2 py-1"><i class="bi bi-bank me-1"></i> Virtual Account</span>
                        <span class="badge bg-light text-dark border px-2 py-1"><i class="bi bi-wallet2 me-1"></i> GoPay / E-Wallet</span>
                    </div>

                    <p class="text-muted small mb-4">
                        Buka jendela pembayaran resmi Midtrans untuk menyelesaikan tagihan secara aman.
                    </p>

                    <button id="pay-button" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm py-3">
                        <i class="bi bi-credit-card-2-front me-2"></i> Bayar via Midtrans
                    </button>
                    
                    <div class="mt-3 text-muted small">
                        <i class="bi bi-shield-lock-fill text-success me-1"></i> Transaksi terenkripsi &amp; terverifikasi otomatis oleh Midtrans
                    </div>

                    <div class="mt-4 pt-3 border-top text-start">
                        <div class="mb-2">
                            <span class="badge bg-light text-dark border">Pilihan Pembayaran Langsung</span>
                        </div>
                        <p class="text-muted small mb-2">
                            Konfirmasi pembayaran secara langsung tanpa membuka jendela baru:
                        </p>
                        <form method="POST" action="<?= base_url('payment/demo.php') ?>" class="d-flex flex-column gap-2">
                            <input type="hidden" name="order_number" value="<?= e($order['order_number']) ?>">
                            <button type="submit" name="payment_type" value="qris" class="btn btn-sm btn-outline-secondary text-start py-2">
                                <i class="bi bi-qr-code me-2"></i> Konfirmasi Pembayaran <strong>QRIS</strong>
                            </button>
                            <button type="submit" name="payment_type" value="bca_va" class="btn btn-sm btn-outline-secondary text-start py-2">
                                <i class="bi bi-bank me-2"></i> Konfirmasi <strong>Virtual Account Bank</strong>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <a href="<?= base_url('customer/orders.php') ?>" class="text-muted small text-decoration-none">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Pesanan Saya
                </a>
            </div>
        </div>
    </div>
</div>

<?php if (!empty(MIDTRANS_CLIENT_KEY) && !empty(MIDTRANS_SERVER_KEY)): ?>
    <!-- Script Snap Midtrans (Dinamis Production/Sandbox) -->
    <?php $snapJsUrl = MIDTRANS_IS_PRODUCTION ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js'; ?>
    <script src="<?= $snapJsUrl ?>" data-client-key="<?= e(MIDTRANS_CLIENT_KEY) ?>"></script>
    <script>
        document.getElementById('pay-button').onclick = function() {
            this.disabled = true;
            this.innerText = 'Menghubungi Midtrans...';

            fetch('<?= base_url('payment/create.php?order_number=' . urlencode($order['order_number'])) ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success' && data.token) {
                        payWithMidtrans(
                            data.token,
                            '<?= base_url('success.php?order_number=' . urlencode($order['order_number']) . '&paid_via=midtrans') ?>',
                            '<?= base_url('checkout.php?order_number=' . urlencode($order['order_number'])) ?>',
                            '<?= base_url('checkout.php?order_number=' . urlencode($order['order_number'])) ?>'
                        );
                    } else {
                        alert(data.message || 'Gagal membuat transaksi Midtrans. Silakan gunakan Demo Payment Mode.');
                    }
                })
                .catch(err => {
                    alert('Gagal menghubungi server pembayaran: ' + err);
                })
                .finally(() => {
                    document.getElementById('pay-button').disabled = false;
                    document.getElementById('pay-button').innerHTML = '<i class="bi bi-shield-check me-1"></i> Bayar via Midtrans Sandbox';
                });
        };
    </script>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
