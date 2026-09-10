<?php
// =====================================================================
// FILE: payment/notification.php
// Webhook Handler untuk Menerima Notifikasi Resmi dari Midtrans
// =====================================================================

header('Content-Type: application/json');
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

// Ambil raw JSON notification payload dari Midtrans
$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid JSON payload']);
    exit;
}

$midtransOrderId = $data['order_id'] ?? '';
$statusCode      = $data['status_code'] ?? '';
$grossAmount     = $data['gross_amount'] ?? 0;
$signatureKey    = $data['signature_key'] ?? '';
$transactionStatus = $data['transaction_status'] ?? '';
$paymentType     = $data['payment_type'] ?? 'midtrans';
$transactionId   = $data['transaction_id'] ?? ('MID-' . time());

// Ekstrak nomor order asli dari order_id gabungan (ORD-YYYYMMDD-XXX atau dengan timestamp)
$orderNumber = $midtransOrderId;
$stmtCheck = $pdo->prepare("SELECT id FROM orders WHERE order_number = ?");
$stmtCheck->execute([$midtransOrderId]);
if (!$stmtCheck->fetch()) {
    $lastDashPos = strrpos($midtransOrderId, '-');
    if ($lastDashPos !== false) {
        $candidate = substr($midtransOrderId, 0, $lastDashPos);
        $stmtCheck->execute([$candidate]);
        if ($stmtCheck->fetch()) {
            $orderNumber = $candidate;
        } else {
            $parts = explode('-', $midtransOrderId);
            if (count($parts) >= 3) {
                $orderNumber = $parts[0] . '-' . $parts[1] . '-' . $parts[2];
            }
        }
    }
}

// Verifikasi signature jika Server Key ada
if (!empty(MIDTRANS_SERVER_KEY)) {
    $expectedSignature = hash('sha512', $midtransOrderId . $statusCode . $grossAmount . MIDTRANS_SERVER_KEY);
    if ($signatureKey !== $expectedSignature) {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'Invalid Signature']);
        exit;
    }
}

// Cari order di database
$stmt = $pdo->prepare("SELECT * FROM orders WHERE order_number = ?");
$stmt->execute([$orderNumber]);
$order = $stmt->fetch();

if (!$order) {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'Order not found']);
    exit;
}

// Evaluasi status pembayaran
// settlement / capture (success) -> bayar berhasil
if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {
    $pdo->beginTransaction();
    try {
        // Update status order
        $upStmt = $pdo->prepare("
            UPDATE orders 
            SET payment_status = 'paid', 
                order_status = 'menunggu_proses',
                updated_at = NOW() 
            WHERE id = ?
        ");
        $upStmt->execute([$order['id']]);

        // Simpan catatan pembayaran
        $payStmt = $pdo->prepare("
            INSERT INTO payments (order_id, transaction_id, payment_type, amount, status, paid_at, created_at)
            VALUES (?, ?, ?, ?, 'settlement', NOW(), NOW())
        ");
        $payStmt->execute([$order['id'], $transactionId, $paymentType, $grossAmount]);

        // Catat ke logs
        $logStmt = $pdo->prepare("
            INSERT INTO order_status_logs (order_id, status, note, created_at)
            VALUES (?, 'menunggu_proses', 'Pembayaran terkonfirmasi via Midtrans Webhook', NOW())
        ");
        $logStmt->execute([$order['id']]);

        $pdo->commit();
        echo json_encode(['status' => 'success', 'message' => 'Order updated to paid']);
        exit;
    } catch (PDOException $e) {
        $pdo->rollBack();
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
} elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
    $upStmt = $pdo->prepare("UPDATE orders SET order_status = 'cancelled', updated_at = NOW() WHERE id = ?");
    $upStmt->execute([$order['id']]);

    $logStmt = $pdo->prepare("INSERT INTO order_status_logs (order_id, status, note, created_at) VALUES (?, 'cancelled', 'Pembayaran dibatalkan/kadaluarsa dari Midtrans', NOW())");
    $logStmt->execute([$order['id']]);

    echo json_encode(['status' => 'success', 'message' => 'Order updated to cancelled']);
    exit;
}

echo json_encode(['status' => 'ignored', 'message' => 'Status pending or unhandled']);
