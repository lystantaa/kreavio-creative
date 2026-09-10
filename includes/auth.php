<?php
// =====================================================================
// FILE: includes/auth.php
// Middleware / Pemeriksaan Hak Akses Customer
// =====================================================================

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

/**
 * Mengecek apakah customer sudah login
 */
function is_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Memastikan customer sudah login sebelum mengakses halaman tertentu.
 * Jika belum, redirect ke halaman login.
 */
function require_login() {
    if (!is_logged_in()) {
        set_flash('warning', 'Silakan login terlebih dahulu untuk mengakses halaman tersebut.');
        // Simpan URL asal untuk redirect kembali setelah login
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        redirect('login.php');
    }
}

/**
 * Mengambil data customer yang sedang login saat ini
 */
function current_user($pdo) {
    if (!is_logged_in()) {
        return null;
    }
    
    $stmt = $pdo->prepare("SELECT id, name, email, phone, created_at FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}
