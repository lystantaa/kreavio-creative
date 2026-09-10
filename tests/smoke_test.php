<?php
// =====================================================================
// FILE: tests/smoke_test.php
// ECC Quality Gate & Smoke Test Suite untuk Kreavio Creative
// =====================================================================

$rootDir = dirname(__DIR__);
require_once $rootDir . '/config/config.php';

$totalChecks = 0;
$passedChecks = 0;

function assert_check($title, $condition, $details = '') {
    global $totalChecks, $passedChecks;
    $totalChecks++;
    if ($condition) {
        $passedChecks++;
        echo "  [PASS] {$title}\n";
    } else {
        echo "  [FAIL] {$title}" . ($details ? " -> {$details}" : "") . "\n";
    }
}

echo "\n=======================================================\n";
echo "  KREAVIO CREATIVE - ECC AUTOMATED SMOKE TEST SUITE\n";
echo "=======================================================\n\n";

// 1. Pengecekan Sintaks PHP (Linting)
echo "1. Pengecekan Sintaks PHP (Linting):\n";
$phpBin = PHP_BINARY ?: 'php';
$keyFiles = [
    'config/config.php',
    'config/database.php',
    'includes/auth.php',
    'includes/admin-auth.php',
    'index.php',
    'login.php',
    'register.php',
    'order.php',
    'checkout.php',
    'success.php',
    'customer/invoice.php',
    'customer/orders.php',
    'customer/order-detail.php',
    'admin/index.php',
    'admin/invoice.php',
    'admin/sales-report.php',
    'payment/notification.php'
];

foreach ($keyFiles as $file) {
    $fullPath = $rootDir . '/' . $file;
    if (file_exists($fullPath)) {
        $output = [];
        $returnVar = 0;
        exec("\"{$phpBin}\" -l \"{$fullPath}\" 2>&1", $output, $returnVar);
        assert_check("Syntax {$file}", $returnVar === 0, implode(' ', $output));
    } else {
        assert_check("File exists {$file}", false, "File tidak ditemukan");
    }
}

// 2. Pengecekan Helper Keamanan & Format Data
echo "\n2. Pengecekan Helper Keamanan & Format Data:\n";
assert_check(
    "XSS Escaping: e('<script>') menghasilkan entitas aman",
    e('<script>') === '&lt;script&gt;'
);
assert_check(
    "Format Rupiah: format_rupiah(50000) === 'Rp50.000'",
    format_rupiah(50000) === 'Rp50.000'
);
assert_check(
    "WhatsApp Number: format_whatsapp_number('08123456789') === '628123456789'",
    format_whatsapp_number('08123456789') === '628123456789'
);
assert_check(
    "CSRF Token Generation: csrf_token() menghasilkan token 64 karakter hex",
    strlen(csrf_token()) === 64 && ctype_xdigit(csrf_token())
);
assert_check(
    "CSRF Field: csrf_field() menghasilkan input hidden berisikan token",
    strpos(csrf_field(), 'name="csrf_token"') !== false
);

// 3. Pengecekan Konfigurasi Lingkungan (.env & .env.example)
echo "\n3. Pengecekan Konfigurasi & Secret Management:\n";
assert_check(
    ".env.example tersedia sebagai acuan publik",
    file_exists($rootDir . '/.env.example')
);
assert_check(
    ".gitignore melindungi file .env dari commit publik",
    file_exists($rootDir . '/.gitignore') && strpos(file_get_contents($rootDir . '/.gitignore'), '.env') !== false
);

// 4. Pengecekan Database DSN & Parameterized Options
echo "\n4. Pengecekan Arsitektur Database PDO:\n";
assert_check(
    "Konstanta DB_HOST terdefinisi",
    defined('DB_HOST') && !empty(DB_HOST)
);
assert_check(
    "Konstanta DB_NAME terdefinisi sebagai 'kreavio_db'",
    defined('DB_NAME') && DB_NAME === 'kreavio_db'
);

// 5. Pengecekan Payment Gateway (Midtrans)
echo "\n5. Pengecekan Payment Gateway (Midtrans):\n";
assert_check(
    "Konstanta IS_DEMO_PAYMENT terdefinisi",
    defined('IS_DEMO_PAYMENT')
);
assert_check(
    "Konstanta MIDTRANS_IS_PRODUCTION terdefinisi sebagai Boolean",
    defined('MIDTRANS_IS_PRODUCTION') && is_bool(MIDTRANS_IS_PRODUCTION)
);

// Ringkasan Hasil
echo "\n-------------------------------------------------------\n";
echo "Hasil Pengujian: {$passedChecks} dari {$totalChecks} pengujian berhasil lolos.\n";
if ($passedChecks === $totalChecks) {
    echo "STATUS: [PASS] Semua pemeriksaan ECC Quality Gate terpenuhi!\n";
} else {
    echo "STATUS: [WARN] Beberapa pemeriksaan memerlukan perhatian.\n";
}
echo "=======================================================\n\n";

exit($passedChecks === $totalChecks ? 0 : 1);