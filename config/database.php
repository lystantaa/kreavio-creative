<?php
// =====================================================================
// FILE: config/database.php
// Koneksi Database MySQL menggunakan PHP Data Objects (PDO)
// =====================================================================

require_once __DIR__ . '/config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lempar Exception jika ada error SQL
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Kembalikan data sebagai array asosiatif
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Gunakan real prepared statements
    ];

    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

} catch (PDOException $e) {
    // Tampilan pesan error yang ramah untuk pemula jika database belum dibuat
    die("
    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 25px; border: 1px solid #f5c6cb; background-color: #f8d7da; color: #721c24; border-radius: 8px;'>
        <h3 style='margin-top:0;'>Gagal Terhubung ke Database MySQL!</h3>
        <p><strong>Pesan Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
        <hr style='border: 0; border-top: 1px solid #f5c6cb;'>
        <p><strong>Petunjuk untuk Pemula:</strong></p>
        <ol style='line-height: 1.6;'>
            <li>Pastikan modul <strong>MySQL di XAMPP Control Panel</strong> sudah berstatus <strong>Running (hijau)</strong>.</li>
            <li>Pastikan database <code>kreavio_db</code> sudah dibuat dan di-import dari file <code>database/schema.sql</code>.</li>
            <li>Periksa konfigurasi user dan password database di file <code>config/config.php</code> atau <code>.env</code>.</li>
        </ol>
    </div>
    ");
}
