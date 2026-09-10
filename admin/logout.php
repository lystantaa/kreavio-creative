<?php
// =====================================================================
// FILE: admin/logout.php
// Logout Administrator Kreavio Creative
// =====================================================================

require_once __DIR__ . '/../config/config.php';

unset($_SESSION['admin_id']);
unset($_SESSION['admin_name']);
unset($_SESSION['admin_email']);

set_flash('info', 'Anda telah berhasil logout dari Panel Admin.');
redirect('admin/login.php');
