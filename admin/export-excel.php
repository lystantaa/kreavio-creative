<?php
// =====================================================================
// FILE: admin/export-excel.php
// Export Rekap Penjualan ke file CSV (dibuka oleh Excel / Google Sheets)
// Tidak memerlukan library tambahan agar sesuai dengan stack PHP Native
// yang sudah digunakan project ini (tanpa Composer/vendor).
// =====================================================================

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/admin-auth.php';

require_admin();

$period = $_GET['period'] ?? 'month';
$startInput = $_GET['start_date'] ?? '';
$endInput   = $_GET['end_date'] ?? '';
[$startDate, $endDate] = resolve_date_range($period, $startInput, $endInput);

// Query dengan rentang tanggal memanfaatkan index idx_orders_created_at
$stmt = $pdo->prepare("
    SELECT o.order_number, o.total, o.payment_status, o.order_status, o.created_at,
           u.name AS customer_name, s.name AS service_name
    FROM orders o
    JOIN users u ON o.user_id = u.id
    JOIN services s ON o.service_id = s.id
    WHERE o.created_at >= ? AND o.created_at <= ?
    ORDER BY o.created_at ASC
");
$stmt->execute([$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
$rows = $stmt->fetchAll();

$filename = 'rekap-penjualan-kreavio-' . $startDate . '_sd_' . $endDate . '.csv';

// Header agar browser mengunduh sebagai file CSV (dapat dibuka Excel / Google Sheets)
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

$output = fopen('php://output', 'w');

// Tambahkan BOM UTF-8 agar karakter/format terbaca benar di Microsoft Excel
fwrite($output, "\xEF\xBB\xBF");

// Header kolom sesuai format yang diminta
fputcsv($output, ['No', 'Tanggal', 'ID Transaksi', 'Pelanggan', 'Produk/Layanan', 'Jumlah', 'Harga', 'Total', 'Status']);

$no = 1;
$totalKeseluruhan = 0;
foreach ($rows as $row) {
    $statusLabel = match ($row['order_status']) {
        'pending_payment'   => 'Menunggu Pembayaran',
        'menunggu_proses'   => 'Menunggu Diproses (Lunas)',
        'sedang_dikerjakan' => 'Sedang Dikerjakan (Lunas)',
        'selesai'           => 'Selesai (Lunas)',
        'cancelled'         => 'Dibatalkan',
        default             => $row['order_status'],
    };

    fputcsv($output, [
        $no,
        date('d/m/Y H:i', strtotime($row['created_at'])),
        $row['order_number'],
        $row['customer_name'],
        $row['service_name'],
        1,
        $row['total'],
        $row['total'],
        $statusLabel,
    ]);

    if ($row['payment_status'] === 'paid') {
        $totalKeseluruhan += (int)$row['total'];
    }
    $no++;
}

// Baris ringkasan total di bagian bawah
fputcsv($output, []);
fputcsv($output, ['', '', '', '', '', '', '', 'TOTAL PENJUALAN (LUNAS)', $totalKeseluruhan]);

fclose($output);
exit;
