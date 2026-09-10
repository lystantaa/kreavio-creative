<?php
// =====================================================================
// FILE: admin/service-delete.php
// Hapus Layanan (CRUD Delete) untuk Admin (Section 22)
// =====================================================================

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/admin-auth.php';

require_admin();

$serviceId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($serviceId <= 0) {
    set_flash('danger', 'ID layanan tidak valid.');
    redirect('admin/services.php');
}

try {
    // Cek apakah ada pesanan yang menggunakan layanan ini
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE service_id = ?");
    $checkStmt->execute([$serviceId]);
    $orderCount = (int)$checkStmt->fetchColumn();

    if ($orderCount > 0) {
        // Jika sudah ada pesanan yang terhubung, jangan hapus hard delete untuk menjaga integritas database
        // Ubah status menjadi nonaktif (active = 0)
        $deactivateStmt = $pdo->prepare("UPDATE services SET active = 0 WHERE id = ?");
        $deactivateStmt->execute([$serviceId]);

        set_flash('warning', "Layanan ini telah memiliki {$orderCount} riwayat pesanan, sehingga dinonaktifkan (arsip) agar data pesanan sebelumnya tetap utuh.");
    } else {
        // Jika belum ada pesanan, aman untuk dihapus permanen
        $delStmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
        $delStmt->execute([$serviceId]);

        set_flash('success', 'Layanan berhasil dihapus dari sistem.');
    }
} catch (PDOException $e) {
    set_flash('danger', 'Gagal menghapus layanan: ' . $e->getMessage());
}

redirect('admin/services.php');
