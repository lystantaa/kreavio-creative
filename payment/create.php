<?php
// =====================================================================
// FILE: payment/create.php
// Endpoint Backend untuk Mengambil Midtrans Snap Token via cURL
// Server Key HANYA berada di backend (Aman & Memenuhi Requirement)
// =====================================================================

header('Content-Type: application/json');
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

if (!is_logged_in()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$user = current_user($pdo);
$orderNumber = $_GET['order_number'] ?? $_POST['order_number'] ?? '';

if (empty($orderNumber)) {
    echo json_encode(['status' => 'error', 'message' => 'Nomor pesanan diperlukan']);
    exit;
}

// Ambil order
$stmt = $pdo->prepare("
    SELECT o.*, s.name AS service_name 
    FROM orders o 
    JOIN services s ON o.service_id = s.id 
    WHERE o.order_number = ? AND o.user_id = ?
");
$stmt->execute([$orderNumber, $user['id']]);
$order = $stmt->fetch();

if (!$order) {
    echo json_encode(['status' => 'error', 'message' => 'Pesanan tidak ditemukan']);
    exit;
}

// Jika Midtrans belum dikonfigurasi, beritahu frontend untuk beralih ke Demo Mode
if (empty(MIDTRANS_SERVER_KEY)) {
    echo json_encode([
        'status' => 'demo_mode',
        'message' => 'Kredensial Midtrans Server Key belum diatur di .env. Silakan gunakan Demo Payment Mode.'
    ]);
    exit;
}

// Tambahkan suffix timestamp agar unik jika user menutup popup dan mencoba bayar kembali
$midtransOrderId = $order['order_number'] . '-' . time();

// Persiapkan payload Midtrans Snap API
$payload = [
    'transaction_details' => [
        'order_id'     => $midtransOrderId,
        'gross_amount' => (int)$order['total'],
    ],
    'customer_details' => [
        'first_name' => $user['name'],
        'email'      => $user['email'],
        'phone'      => $user['phone'],
    ],
    'item_details' => [
        [
            'id'       => (string)$order['service_id'],
            'price'    => (int)$order['total'],
            'quantity' => 1,
            'name'     => substr($order['service_name'], 0, 50),
        ]
    ]
];

$apiUrl = MIDTRANS_IS_PRODUCTION 
    ? 'https://app.midtrans.com/snap/v1/transactions' 
    : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

// Native cURL request ke Midtrans API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    'Authorization: Basic ' . base64_encode(MIDTRANS_SERVER_KEY . ':')
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_TIMEOUT, 15);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlErr  = curl_error($ch);
curl_close($ch);

if ($curlErr || $httpCode >= 400) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal menghubungi server Midtrans: ' . ($curlErr ?: $response),
        'fallback_to_demo' => true
    ]);
    exit;
}

$data = json_decode($response, true);
if (isset($data['token'])) {
    echo json_encode([
        'status' => 'success',
        'token'  => $data['token'],
        'redirect_url' => $data['redirect_url'] ?? ''
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => $data['error_messages'][0] ?? 'Terjadi kesalahan pada Midtrans',
        'fallback_to_demo' => true
    ]);
}
