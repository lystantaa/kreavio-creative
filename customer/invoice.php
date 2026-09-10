<?php
// =====================================================================
// FILE: customer/invoice.php
// Halaman Cetak & Unduh Invoice PDF Customer Kreavio Creative
// Mengikuti Format Standar Resmi & Bersih (Pas 1 Halaman A4)
// =====================================================================

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

// Pastikan customer login atau admin
if (!is_logged_in() && !isset($_SESSION['admin_id'])) {
    require_login();
}

$user = is_logged_in() ? current_user($pdo) : null;
$isAdmin = isset($_SESSION['admin_id']);

$orderId     = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$orderNumber = trim($_GET['order_number'] ?? '');

if ($orderId <= 0 && empty($orderNumber)) {
    set_flash('danger', 'ID atau Nomor Pesanan tidak valid.');
    redirect('customer/orders.php');
}

// Ambil data order + customer + layanan
try {
    if ($isAdmin) {
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
    } else {
        if ($orderId > 0) {
            $stmt = $pdo->prepare("
                SELECT o.*,
                       u.name  AS customer_name, u.email AS customer_email, u.phone AS customer_phone,
                       s.name  AS service_name, s.duration AS service_duration
                FROM orders o
                JOIN users u ON o.user_id = u.id
                JOIN services s ON o.service_id = s.id
                WHERE o.id = ? AND o.user_id = ?
            ");
            $stmt->execute([$orderId, $user['id']]);
        } else {
            $stmt = $pdo->prepare("
                SELECT o.*,
                       u.name  AS customer_name, u.email AS customer_email, u.phone AS customer_phone,
                       s.name  AS service_name, s.duration AS service_duration
                FROM orders o
                JOIN users u ON o.user_id = u.id
                JOIN services s ON o.service_id = s.id
                WHERE o.order_number = ? AND o.user_id = ?
            ");
            $stmt->execute([$orderNumber, $user['id']]);
        }
    }
    $order = $stmt->fetch();

    if (!$order) {
        set_flash('danger', 'Pesanan tidak ditemukan.');
        redirect('customer/orders.php');
    }

    // Ambil data pembayaran terakhir (jika ada)
    $payStmt = $pdo->prepare("SELECT * FROM payments WHERE order_id = ? ORDER BY created_at DESC LIMIT 1");
    $payStmt->execute([$order['id']]);
    $payment = $payStmt->fetch();

} catch (PDOException $e) {
    set_flash('danger', 'Terjadi kesalahan basis data: ' . $e->getMessage());
    redirect('customer/orders.php');
}

$invoiceNumber = 'INV-' . $order['order_number'];
$invoiceDate   = $payment['paid_at'] ?? $order['updated_at'] ?? $order['created_at'];

// Label metode pembayaran
$methodNames = [
    'mode_cepat'        => 'Pembayaran Mode Cepat',
    'qris'              => 'QRIS (E-Wallet & Mobile Banking)',
    'bca_va'            => 'BCA Virtual Account',
    'bni_va'            => 'BNI Virtual Account',
    'bri_va'            => 'BRI Virtual Account',
    'mandiri_va'        => 'Mandiri Virtual Account',
    'gopay'             => 'GoPay / QRIS',
    'shopeepay'         => 'ShopeePay',
    'midtrans_snap'     => 'Midtrans Payment Gateway',
    'midtrans_sandbox'  => 'Midtrans Sandbox Gateway',
    'demo_simulator'    => 'Pembayaran Mode Cepat'
];
$rawMethod = $payment['payment_type'] ?? '';
$paymentMethodLabel = $methodNames[$rawMethod] ?? ($order['payment_status'] === 'paid' ? 'Midtrans / Online Transfer' : 'Menunggu Pembayaran');
$backUrl = base_url('customer/order-detail.php?id=' . $order['id']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice <?= e($invoiceNumber) ?> - Kreavio Creative</title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?= asset_url('images/logo.svg') ?>">
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= asset_url('css/style.css?v=' . filemtime(__DIR__ . '/../assets/css/style.css')) ?>">

    <!-- Sinkronisasi Tema Otomatis dengan Dashboard -->
    <script>
        (function() {
            var savedTheme = localStorage.getItem('kreavio_theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        })();

        function toggleTheme() {
            var current = document.documentElement.getAttribute('data-bs-theme') || 'light';
            var next = (current === 'dark') ? 'light' : 'dark';
            document.documentElement.setAttribute('data-bs-theme', next);
            localStorage.setItem('kreavio_theme', next);
            updateAllThemeButtons(next);
        }

        function updateAllThemeButtons(theme) {
            var buttons = document.querySelectorAll('.theme-toggle-btn');
            buttons.forEach(function(btn) {
                var icon = btn.querySelector('i');
                if (icon) {
                    if (theme === 'dark') {
                        icon.className = 'bi bi-sun text-warning';
                        btn.setAttribute('title', 'Ganti ke Mode Terang');
                    } else {
                        icon.className = 'bi bi-moon';
                        btn.setAttribute('title', 'Ganti ke Mode Gelap');
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            var current = document.documentElement.getAttribute('data-bs-theme') || 'light';
            updateAllThemeButtons(current);
        });
    </script>

    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #eef1f5;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .invoice-box {
            max-width: 820px;
            margin: 24px auto 40px;
            background: #fff;
            padding: 35px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }

        .invoice-box table td {
            padding: 8px 12px;
        }

        /* ===== DARK THEME SUPPORT (SESUAI DASHBOARD CUSTOMER & ADMIN) ===== */
        [data-bs-theme="dark"] body {
            background-color: #0f172a !important;
            color: #f8fafc !important;
        }
        [data-bs-theme="dark"] .invoice-box {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: #f8fafc !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4) !important;
        }
        [data-bs-theme="dark"] .bg-light {
            background-color: #0f172a !important;
            border-color: #334155 !important;
            color: #f8fafc !important;
        }
        [data-bs-theme="dark"] .text-dark {
            color: #f8fafc !important;
        }
        [data-bs-theme="dark"] .text-muted {
            color: #94a3b8 !important;
        }
        [data-bs-theme="dark"] .text-secondary {
            color: #cbd5e1 !important;
        }
        [data-bs-theme="dark"] .border,
        [data-bs-theme="dark"] .border-bottom,
        [data-bs-theme="dark"] .border-top {
            border-color: #334155 !important;
        }
        [data-bs-theme="dark"] .table {
            color: #f8fafc !important;
            border-color: #334155 !important;
        }
        [data-bs-theme="dark"] .table-light,
        [data-bs-theme="dark"] .table thead th {
            background-color: #0f172a !important;
            color: #cbd5e1 !important;
            border-color: #334155 !important;
        }
        [data-bs-theme="dark"] .table td,
        [data-bs-theme="dark"] .table tfoot th {
            border-color: #334155 !important;
        }

        /* ===== CETAK / PRINT RESMI (SELALU KERTAS PUTIH BERSIH & PAS 1 HALAMAN) ===== */
        @page {
            size: A4 portrait;
            margin: 8mm 12mm;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            html, body {
                background: #ffffff !important;
                margin: 0 !important;
                padding: 0 !important;
                color: #000000 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .invoice-box {
                background: #ffffff !important;
                color: #000000 !important;
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                max-width: 100% !important;
                width: 100% !important;
                padding: 10px 0 !important;
                page-break-after: avoid !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
            .bg-light {
                background-color: #f8fafc !important;
                border-color: #e2e8f0 !important;
                color: #000000 !important;
            }
            .text-dark { color: #000000 !important; }
            .text-muted { color: #64748b !important; }
            .text-secondary { color: #475569 !important; }
            .border, .border-bottom, .border-top { border-color: #cbd5e1 !important; }
            .table-light, .table thead th {
                background-color: #f8fafc !important;
                color: #334155 !important;
                border-color: #cbd5e1 !important;
            }
            .table td, .table tfoot th {
                border-color: #e2e8f0 !important;
                color: #000000 !important;
            }
            * {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
        }
    </style>
</head>
<body>

    <!-- Tombol Navigasi & Print (Hanya tampil di layar browser, tersembunyi saat cetak) -->
    <div class="container no-print pt-4" style="max-width: 820px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="<?= $backUrl ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Detail Pesanan
            </a>
            <div class="d-flex gap-2 align-items-center">
                <button type="button" class="btn btn-outline-secondary theme-toggle-btn shadow-sm" onclick="toggleTheme()" title="Ganti Mode Tema" aria-label="Ganti Mode Tema">
                    <i class="bi bi-moon"></i>
                </button>
                <button type="button" onclick="window.print()" class="btn btn-primary shadow-sm">
                    <i class="bi bi-printer me-1"></i> Cetak / Unduh PDF
                </button>
            </div>
        </div>
    </div>

    <!-- AREA INVOICE RESMI -->
    <div class="invoice-box" id="invoice-content">
        <!-- Header Invoice -->
        <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-3">
            <div>
                <div class="d-flex align-items-center mb-1">
                    <img src="<?= asset_url('images/logo.svg') ?>" alt="Kreavio Creative Logo" height="32" class="me-2" onerror="this.style.display='none'">
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
        <div class="row mb-3">
            <div class="col-sm-6 mb-2 mb-sm-0">
                <h6 class="fw-bold text-secondary text-uppercase small mb-2">Ditagihkan Kepada:</h6>
                <p class="mb-1 fw-bold fs-6 text-dark"><?= e($order['customer_name']) ?></p>
                <p class="mb-1 small text-muted"><i class="bi bi-envelope me-1"></i> <?= e($order['customer_email']) ?></p>
                <p class="mb-0 small text-muted"><i class="bi bi-whatsapp me-1"></i> <?= e($order['customer_phone'] ?: '-') ?></p>
            </div>
            <div class="col-sm-6 text-sm-end">
                <h6 class="fw-bold text-secondary text-uppercase small mb-2">Status Transaksi:</h6>
                <div class="mb-1"><?= get_payment_status_badge($order['payment_status']) ?></div>
                <div class="mb-1"><?= get_order_status_badge($order['order_status']) ?></div>
                <p class="mb-0 small text-muted"><strong>Target Deadline:</strong> <?= date('d M Y', strtotime($order['deadline'])) ?></p>
            </div>
        </div>

        <!-- Tabel Item Layanan -->
        <div class="table-responsive mb-3">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 45px;">No</th>
                        <th>Deskripsi Layanan</th>
                        <th style="width: 110px;">Estimasi Waktu</th>
                        <th class="text-center" style="width: 60px;">Qty</th>
                        <th class="text-end" style="width: 130px;">Harga Satuan</th>
                        <th class="text-end" style="width: 140px;">Subtotal</th>
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
        <div class="row g-3 mb-3">
            <div class="col-sm-6">
                <div class="p-3 bg-light rounded-3 border h-100">
                    <h6 class="fw-bold text-dark small mb-2"><i class="bi bi-credit-card me-1"></i> Informasi Pembayaran</h6>
                    <div class="small text-muted mb-1">
                        <strong>Metode:</strong> <?= e($paymentMethodLabel) ?>
                    </div>
                    <div class="small text-muted">
                        <strong>ID Referensi:</strong> <span class="font-monospace"><?= e($payment['transaction_id'] ?? '-') ?></span>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="p-3 bg-light rounded-3 border h-100">
                    <h6 class="fw-bold text-dark small mb-2"><i class="bi bi-info-circle me-1"></i> Catatan Pemesanan</h6>
                    <div class="small text-muted">
                        <?= !empty($order['notes']) ? e($order['notes']) : (!empty($order['brief']) ? e($order['brief']) : 'Pengerjaan sesuai brief customer. Estimasi selesai sebelum batas target deadline.') ?>
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-3">
        
        <div class="text-center text-muted small">
            <p class="mb-1 fw-semibold">Terima kasih atas kepercayaan Anda memilih jasa Kreavio Creative!</p>
            <p class="mb-0 text-secondary" style="font-size: 0.8rem;">Invoice ini sah dan diterbitkan secara digital oleh sistem Kreavio Creative.</p>
        </div>
    </div>

    <script>
        <?php if (isset($_GET['print']) || isset($_GET['download'])): ?>
        window.addEventListener('load', function() {
            setTimeout(function() { window.print(); }, 400);
        });
        <?php endif; ?>
    </script>
</body>
</html>
