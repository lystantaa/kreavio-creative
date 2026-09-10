<?php
// =====================================================================
// FILE: logout.php
// Logout Akun Customer
// =====================================================================

require_once __DIR__ . '/config/config.php';

// Hapus data sesi customer saja
unset($_SESSION['user_id']);
unset($_SESSION['user_name']);
unset($_SESSION['user_email']);

set_flash('info', 'Anda telah berhasil logout.');
redirect('login.php');
