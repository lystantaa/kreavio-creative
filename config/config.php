<?php
// =====================================================================
// FILE: config/config.php
// Konfigurasi Utama Website Kreavio Creative
// =====================================================================

// 1. Memulai Session jika belum berjalan (dengan proteksi cookie HttpOnly & SameSite Lax)
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_only_cookies', '1');
    if (PHP_VERSION_ID >= 70300) {
        session_set_cookie_params([
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    }
    session_start();
}

// Polyfills untuk kompatibilitas versi PHP
if (!function_exists('str_starts_with')) {
    function str_starts_with($haystack, $needle) {
        return (string)$needle !== '' && strncmp($haystack, $needle, strlen($needle)) === 0;
    }
}
if (!function_exists('str_ends_with')) {
    function str_ends_with($haystack, $needle) {
        return $needle === '' || $needle === substr($haystack, -strlen($needle));
    }
}

// 2. Parser Sederhana untuk file .env (jika ada) tanpa dependensi luar
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        if (strpos($line, '=') !== false) {
            list($key, $val) = explode('=', $line, 2);
            $key = trim($key);
            $val = trim($val);
            // Hapus tanda kutip jika ada
            $val = trim($val, "\"'");
            if (!array_key_exists($key, $_ENV)) {
                $_ENV[$key] = $val;
            }
        }
    }
}

// Helper untuk mengambil variabel env atau default
function env($key, $default = '') {
    return $_ENV[$key] ?? getenv($key) ?: $default;
}

// 3. Konfigurasi Database (Default XAMPP)
define('DB_HOST', env('DB_HOST', 'localhost'));
define('DB_PORT', env('DB_PORT', '3306'));
define('DB_NAME', env('DB_NAME', 'kreavio_db'));
define('DB_USER', env('DB_USER', 'root'));
define('DB_PASS', env('DB_PASS', ''));

// 4. Konfigurasi Midtrans Payment Gateway
$rawServerKey = env('MIDTRANS_SERVER_KEY', '');
$rawClientKey = env('MIDTRANS_CLIENT_KEY', '');

// Pengaman otomatis: Jika user terbalik meletakkan Server Key dan Client Key
if (strpos($rawServerKey, '-client-') !== false && strpos($rawClientKey, '-server-') !== false) {
    $tempKey = $rawServerKey;
    $rawServerKey = $rawClientKey;
    $rawClientKey = $tempKey;
}

// Status environment dari .env (default false = Sandbox)
$envIsProd = filter_var(env('MIDTRANS_IS_PRODUCTION', 'false'), FILTER_VALIDATE_BOOLEAN);

define('MIDTRANS_SERVER_KEY', $rawServerKey);
define('MIDTRANS_CLIENT_KEY', $rawClientKey);
define('MIDTRANS_IS_PRODUCTION', $envIsProd);

// Mode Demo aktif jika kredensial server key Midtrans masih kosong
define('IS_DEMO_PAYMENT', empty(MIDTRANS_SERVER_KEY));

// 5. Penentuan BASE_URL Otomatis
// Mendukung localhost/kreavio, subfolder lain, maupun php -S localhost:8000
$customAppUrl = env('APP_URL', '');
if (!empty($customAppUrl)) {
    $baseUrl = rtrim($customAppUrl, '/') . '/';
} else {
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
    $protocol = $isHttps ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    // Hitung relative path folder project dari Web Root
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $scriptDir  = str_replace('\\', '/', dirname($scriptName));
    
    // Cari root project berdasarkan keberadaan subfolder
    $subfolders = ['/admin', '/customer', '/payment', '/config', '/includes'];
    $cleanPath = $scriptDir;
    foreach ($subfolders as $sub) {
        if (str_ends_with($cleanPath, $sub)) {
            $cleanPath = substr($cleanPath, 0, -strlen($sub));
            break;
        }
    }
    
    $cleanPath = trim($cleanPath, '/');
    $baseUrl   = $protocol . $host . ($cleanPath !== '' ? '/' . $cleanPath : '') . '/';
}
define('BASE_URL', $baseUrl);

// 6. HELPER FUNCTIONS PEMULA

/**
 * Membuat link URL absolut di dalam project
 */
function base_url($path = '') {
    return BASE_URL . ltrim($path, '/');
}

/**
 * Mengarahkan ke asset (css, js, gambar)
 */
function asset_url($path = '') {
    return BASE_URL . 'assets/' . ltrim($path, '/');
}

/**
 * Mencegah XSS (Cross Site Scripting)
 */
function e($string) {
    return htmlspecialchars((string)($string ?? ''), ENT_QUOTES, 'UTF-8');
}

/**
 * Format angka ke Rupiah
 */
function format_rupiah($number) {
    return 'Rp' . number_format((float)$number, 0, ',', '.');
}

/**
 * Format tanggal Indonesia
 */
function format_date($datetime) {
    if (!$datetime) return '-';
    $timestamp = strtotime($datetime);
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    $d = date('d', $timestamp);
    $m = $bulan[(int)date('m', $timestamp)];
    $y = date('Y', $timestamp);
    $t = date('H:i', $timestamp);
    return "$d $m $y, $t WIB";
}

/**
 * Badge status pembayaran
 */
function get_payment_status_badge($status) {
    switch ($status) {
        case 'paid':
            return '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Lunas (Paid)</span>';
        case 'pending_payment':
            return '<span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Belum Bayar</span>';
        default:
            return '<span class="badge bg-secondary">' . e($status) . '</span>';
    }
}

/**
 * Badge status pesanan
 */
function get_order_status_badge($status) {
    switch ($status) {
        case 'pending_payment':
            return '<span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>Menunggu Pembayaran</span>';
        case 'menunggu_proses':
            return '<span class="badge bg-info text-dark"><i class="bi bi-inbox me-1"></i>Menunggu Diproses</span>';
        case 'sedang_dikerjakan':
            return '<span class="badge bg-primary"><i class="bi bi-palette me-1"></i>Sedang Dikerjakan</span>';
        case 'selesai':
            return '<span class="badge bg-success"><i class="bi bi-check2-all me-1"></i>Pesanan Selesai</span>';
        case 'cancelled':
            return '<span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Dibatalkan</span>';
        default:
            return '<span class="badge bg-secondary">' . e($status) . '</span>';
    }
}

/**
 * Flash message helper untuk notifikasi pengguna
 */
function set_flash($type, $message) {
    $_SESSION['flash_message'] = [
        'type' => $type, // success, danger, warning, info
        'message' => $message
    ];
}

function display_flash() {
    if (isset($_SESSION['flash_message'])) {
        $flash = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        $type = e($flash['type']);
        $msg = e($flash['message']);
        echo "<div class=\"alert alert-{$type} alert-dismissible fade show\" role=\"alert\">
                {$msg}
                <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
              </div>";
    }
}

/**
 * CSRF Protection Helpers (Standar Keamanan ECC)
 */
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['csrf_token'] ?? '';
        if (empty($token) || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(403);
            die('Akses ditolak: Validasi CSRF Token gagal.');
        }
    }
}

/**
 * Format nomor telepon Indonesia ke format standar WhatsApp internasional (628xxx)
 */
function format_whatsapp_number($phone) {
    $clean = preg_replace('/[^0-9]/', '', (string)$phone);
    if (str_starts_with($clean, '0')) {
        $clean = '62' . substr($clean, 1);
    }
    return $clean;
}

/**
 * Mengambil path gambar ilustrasi layanan
 */
function get_service_image($serviceId) {
    $map = [
        1 => 'service-cv.svg',
        2 => 'service-powerpoint.svg',
        3 => 'service-poster.svg',
        4 => 'service-banner.svg',
        5 => 'service-social.svg',
        6 => 'service-logo.svg',
    ];
    $filename = $map[$serviceId] ?? 'service-cv.svg';
    return asset_url('images/' . $filename);
}

/**
 * Redirect helper sederhana
 */
function redirect($path) {
    $url = (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) ? $path : base_url($path);
    header("Location: $url");
    exit;
}
