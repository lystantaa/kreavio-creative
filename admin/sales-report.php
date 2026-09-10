<?php
// =====================================================================
// FILE: admin/sales-report.php
// Halaman Rekap Penjualan berdasarkan Periode (Fitur Admin Baru)
// =====================================================================

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/admin-auth.php';

require_admin();
$admin = current_admin($pdo);

$period = $_GET['period'] ?? 'month';
$startInput = $_GET['start_date'] ?? '';
$endInput   = $_GET['end_date'] ?? '';
[$startDate, $endDate] = resolve_date_range($period, $startInput, $endInput);

// Ambil data pesanan dalam rentang tanggal (memanfaatkan index idx_orders_created_at)
$stmt = $pdo->prepare("
    SELECT o.id, o.order_number, o.total, o.payment_status, o.order_status, o.created_at,
           u.name AS customer_name, s.name AS service_name
    FROM orders o
    JOIN users u ON o.user_id = u.id
    JOIN services s ON o.service_id = s.id
    WHERE o.created_at >= ? AND o.created_at <= ?
    ORDER BY o.created_at DESC
");
$stmt->execute([$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
$rows = $stmt->fetchAll();

// Hitung ringkasan
$totalTransaksi = count($rows);
$totalPenjualan = 0;
$totalPending   = 0;
foreach ($rows as $r) {
    if ($r['payment_status'] === 'paid') {
        $totalPenjualan += (int)$r['total'];
    } else {
        $totalPending += (int)$r['total'];
    }
}

// Query string untuk tombol export (bawa filter yang sama)
$exportQuery = http_build_query([
    'period' => $period,
    'start_date' => $startDate,
    'end_date' => $endDate,
]);

$pageTitle = 'Rekap Penjualan';
$currentAdminPage = 'sales-report.php';
require_once __DIR__ . '/../includes/admin-header.php';
?>

    <div class="container py-4">
        <?php display_flash(); ?>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Rekap Penjualan</h3>
                <p class="text-muted mb-0">Ringkasan transaksi dan total omzet berdasarkan periode waktu.</p>
            </div>
            <a href="<?= base_url('admin/export-excel.php?' . $exportQuery) ?>" class="btn btn-success mt-3 mt-md-0 shadow-sm">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export Excel / CSV
            </a>
        </div>

        <!-- FILTER PERIODE -->
        <div class="card shadow-sm border-0 p-3 mb-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Periode Waktu</label>
                    <select name="period" class="form-select" onchange="this.form.submit()">
                        <option value="today" <?= $period === 'today' ? 'selected' : '' ?>>Hari Ini</option>
                        <option value="week" <?= $period === 'week' ? 'selected' : '' ?>>Minggu Ini</option>
                        <option value="month" <?= $period === 'month' ? 'selected' : '' ?>>Bulan Ini</option>
                        <option value="custom" <?= $period === 'custom' ? 'selected' : '' ?>>Rentang Tanggal Kustom</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control" value="<?= e($startDate) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control" value="<?= e($endDate) ?>">
                </div>
                <div class="col-md-3">
                    <button type="submit" name="period" value="custom" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i> Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- RINGKASAN METRIK -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card p-3 border-0 shadow-sm h-100 border-start border-4 border-primary">
                    <span class="text-muted small fw-semibold">Jumlah Transaksi</span>
                    <h3 class="fw-bold my-2"><?= $totalTransaksi ?></h3>
                    <span class="small text-secondary">
                        <i class="bi bi-calendar-event me-1"></i><?= date('d M Y', strtotime($startDate)) ?> &ndash; <?= date('d M Y', strtotime($endDate)) ?>
                    </span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 border-0 shadow-sm h-100 border-start border-4 border-success">
                    <span class="text-muted small fw-semibold">Total Penjualan (Lunas)</span>
                    <h3 class="fw-bold my-2 text-success"><?= format_rupiah($totalPenjualan) ?></h3>
                    <span class="small text-muted"><i class="bi bi-check-circle me-1"></i>Dari transaksi berstatus paid</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 border-0 shadow-sm h-100 border-start border-4 border-warning">
                    <span class="text-muted small fw-semibold">Belum Lunas (Pending)</span>
                    <h3 class="fw-bold my-2 text-warning"><?= format_rupiah($totalPending) ?></h3>
                    <span class="small text-muted"><i class="bi bi-hourglass-split me-1"></i>Menunggu konfirmasi pembayaran</span>
                </div>
            </div>
        </div>

        <!-- TABEL REKAP -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Rincian Transaksi (<?= $totalTransaksi ?> data)</h5>
                <span class="badge bg-light text-dark border">
                    Periode: <?= date('d/m/Y', strtotime($startDate)) ?> &ndash; <?= date('d/m/Y', strtotime($endDate)) ?>
                </span>
            </div>
            <div class="card-body p-0">
                <?php if (empty($rows)): ?>
                    <div class="p-5 text-center text-muted">
                        <i class="bi bi-calendar-x fs-1 d-block mb-2 text-secondary"></i>
                        Tidak ada transaksi pada periode <?= date('d M Y', strtotime($startDate)) ?> &ndash; <?= date('d M Y', strtotime($endDate)) ?>.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th>Tanggal</th>
                                    <th>No. Order</th>
                                    <th>Pelanggan</th>
                                    <th>Layanan</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Total</th>
                                    <th>Pembayaran</th>
                                    <th>Status Pesanan</th>
                                    <th class="text-end" style="width: 170px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($rows as $row): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td class="small text-muted"><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                                        <td>
                                            <a href="<?= base_url('admin/order-detail.php?id=' . $row['id']) ?>" class="fw-bold font-monospace text-decoration-none">
                                                <?= e($row['order_number']) ?>
                                            </a>
                                        </td>
                                        <td><?= e($row['customer_name']) ?></td>
                                        <td><?= e($row['service_name']) ?></td>
                                        <td class="text-center">1</td>
                                        <td class="text-end fw-semibold"><?= format_rupiah($row['total']) ?></td>
                                        <td><?= get_payment_status_badge($row['payment_status']) ?></td>
                                        <td><?= get_order_status_badge($row['order_status']) ?></td>
                                        <td class="text-end">
                                            <a href="<?= base_url('admin/invoice.php?id=' . $row['id']) ?>" class="btn btn-sm btn-outline-success me-1" title="Cetak Invoice" target="_blank">
                                                <i class="bi bi-printer"></i> Invoice
                                            </a>
                                            <a href="<?= base_url('admin/order-detail.php?id=' . $row['id']) ?>" class="btn btn-sm btn-outline-primary" title="Detail Pesanan">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="6" class="text-end">TOTAL PENJUALAN LUNAS:</th>
                                    <th class="text-end text-success fs-6"><?= format_rupiah($totalPenjualan) ?></th>
                                    <th colspan="3"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
