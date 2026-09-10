<?php
// =====================================================================
// FILE: includes/admin-auth.php
// Middleware / Pemeriksaan Hak Akses Admin Kreavio
// =====================================================================

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

/**
 * Mengecek apakah admin sudah login
 */
function is_admin_logged_in() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

/**
 * Memastikan admin sudah login sebelum mengakses halaman admin.
 * Jika belum, redirect ke halaman login admin.
 */
function require_admin() {
    if (!is_admin_logged_in()) {
        set_flash('danger', 'Akses ditolak. Silakan login sebagai Administrator terlebih dahulu.');
        redirect('admin/login.php');
    }
}

/**
 * Mengambil data admin yang sedang login saat ini
 */
function current_admin($pdo) {
    if (!is_admin_logged_in()) {
        return null;
    }
    
    $stmt = $pdo->prepare("SELECT id, name, email, created_at FROM admins WHERE id = ?");
    $stmt->execute([$_SESSION['admin_id']]);
    return $stmt->fetch();
}

/**
 * Menentukan rentang tanggal (start, end) berdasarkan parameter periode.
 * Dipakai bersama oleh sales-report.php dan export-excel.php.
 */
function resolve_date_range($period, $startInput = '', $endInput = '') {
    $today = date('Y-m-d');
    switch ($period) {
        case 'today':
            return [$today, $today];
        case 'week':
            return [
                date('Y-m-d', strtotime('monday this week')),
                date('Y-m-d', strtotime('sunday this week'))
            ];
        case 'month':
            return [date('Y-m-01'), date('Y-m-t')];
        case 'custom':
            $start = $startInput ?: $today;
            $end   = $endInput ?: $today;
            if ($start > $end) {
                $temp = $start;
                $start = $end;
                $end = $temp;
            }
            return [$start, $end];
        default:
            return [date('Y-m-01'), date('Y-m-t')]; // default: bulan ini
    }
}
