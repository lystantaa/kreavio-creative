<?php
// =====================================================================
// FILE: payment/demo.php
// Simulator Pembayaran Instan (Demo Mode untuk Presentasi & Latihan)
// =====================================================================

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();
$user = current_user($pdo);

// Ambil nomor order dari POST atau GET
$orderNumber = $_POST['order_number'] ?? $_GET['order_number'] ?? '';

if (empty($orderNumber)) {
    set_flash('danger', 'Nomor pesanan tidak valid.');
    redirect('customer/orders.php');
}

// Cari data order di database
$stmt = $pdo->prepare("SELECT * FROM orders WHERE order_number = ? AND user_id = ?");
$stmt->execute([$orderNumber, $user['id']]);
$order = $stmt->fetch();

if (!$order) {
    set_flash('danger', 'Data pesanan tidak ditemukan atau bukan milik Anda.');
    redirect('customer/orders.php');
}

// Jika pesanan sudah lunas, langsung arahkan ke success
if ($order['payment_status'] === 'paid') {
    set_flash('info', 'Pesanan ini sudah dibayar sebelumnya.');
    redirect('success.php?order_number=' . urlencode($orderNumber));
}

try {
    $pdo->beginTransaction();

    // 1. Update status pesanan di tabel orders
    // Sesuai requirement: payment_status = 'paid', order_status = 'menunggu_proses'
    $updateOrder = $pdo->prepare("
        UPDATE orders 
        SET payment_status = 'paid', 
            order_status = 'menunggu_proses',
            updated_at = NOW() 
        WHERE id = ?
    ");
    $updateOrder->execute([$order['id']]);

    $paymentType = trim($_POST['payment_type'] ?? $_GET['payment_type'] ?? 'demo_simulator');
    if (empty($paymentType)) $paymentType = 'demo_simulator';

    $methodNames = [
        'mode_cepat'   => 'Pembayaran Mode Cepat',
        'qris'         => 'QRIS (E-Wallet & Mobile Banking)',
        'bca_va'       => 'BCA Virtual Account',
        'bni_va'       => 'BNI Virtual Account',
        'bri_va'       => 'BRI Virtual Account',
        'mandiri_va'   => 'Mandiri Virtual Account',
        'gopay'        => 'GoPay / QRIS',
        'shopeepay'    => 'ShopeePay',
        'midtrans_snap'=> 'Midtrans Payment Gateway',
        'demo_simulator'=> 'Pembayaran Mode Cepat'
    ];
    $methodLabel = $methodNames[$paymentType] ?? 'Pembayaran Mode Cepat';

    // 2. Catat ke tabel payments
    $transactionId = 'PAY-' . strtoupper(substr($paymentType, 0, 4)) . '-' . date('Ymd') . '-' . rand(1000, 9999);
    $insertPayment = $pdo->prepare("
        INSERT INTO payments (order_id, transaction_id, payment_type, amount, status, paid_at, created_at)
        VALUES (?, ?, ?, ?, 'settlement', NOW(), NOW())
    ");
    $insertPayment->execute([
        $order['id'],
        $transactionId,
        $paymentType,
        $order['total']
    ]);

    // 3. Catat ke tabel order_status_logs
    $insertLog = $pdo->prepare("
        INSERT INTO order_status_logs (order_id, status, note, created_at)
        VALUES (?, 'menunggu_proses', ?, NOW())
    ");
    $insertLog->execute([
        $order['id'],
        'Pembayaran berhasil dikonfirmasi via ' . $methodLabel
    ]);

    $pdo->commit();

    set_flash('success', "Pembayaran melalui {$methodLabel} berhasil dikonfirmasi!");
    redirect('success.php?order_number=' . urlencode($orderNumber) . '&method=' . urlencode($paymentType));

} catch (PDOException $e) {
    $pdo->rollBack();
    set_flash('danger', 'Terjadi kesalahan saat memproses pembayaran demo: ' . $e->getMessage());
    redirect('checkout.php?order_number=' . urlencode($orderNumber));
}
