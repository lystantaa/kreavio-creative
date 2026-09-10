<?php
// =====================================================================
// FILE: contact.php
// Halaman Kontak Kreavio Creative
// =====================================================================

$pageTitle = 'Kontak Kami - Kreavio Creative';
require_once __DIR__ . '/includes/header.php';

$sentSuccess = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        $error = 'Semua kolom wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid. Masukkan alamat email yang benar.';
    } else {
        $sentSuccess = true;
    }
}
?>

<div class="bg-light py-5 border-bottom">
    <div class="container text-center">
        <h1 class="fw-bold mb-2">Hubungi Tim Kreavio</h1>
        <p class="text-muted mb-0">Punya pertanyaan seputar layanan desain atau ingin konsultasi kebutuhan visual Anda?</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-5 justify-content-center">
        <!-- Info Kontak -->
        <div class="col-lg-5">
            <h3 class="fw-bold mb-4">Informasi Bisnis</h3>
            
            <div class="d-flex align-items-start mb-4">
                <div class="bg-primary-subtle text-primary p-3 rounded-circle me-3">
                    <i class="bi bi-geo-alt fs-4"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">Lokasi Studio</h6>
                    <p class="text-muted small mb-0">Kampus Creative Studio & Online Workspace, Indonesia</p>
                </div>
            </div>

            <div class="d-flex align-items-start mb-4">
                <div class="bg-success-subtle text-success p-3 rounded-circle me-3">
                    <i class="bi bi-whatsapp fs-4"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">WhatsApp Customer Service</h6>
                    <p class="text-muted small mb-0">+62 812-3456-7890 (Senin - Sabtu, 08:00 - 20:00 WIB)</p>
                </div>
            </div>

            <div class="d-flex align-items-start mb-4">
                <div class="bg-danger-subtle text-danger p-3 rounded-circle me-3">
                    <i class="bi bi-envelope fs-4"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">Email Resmi</h6>
                    <p class="text-muted small mb-0">halo@kreavio.test / support@kreavio.test</p>
                </div>
            </div>

            <div class="p-4 bg-light rounded-3 border">
                <h6 class="fw-bold mb-2"><i class="bi bi-info-circle me-1 text-primary"></i> Catatan Konsultasi</h6>
                <p class="text-muted small mb-0">
                    Bagi Anda yang membutuhkan penyesuaian khusus atau jumlah pesanan banyak, jangan ragu untuk berdiskusi melalui nomor WhatsApp kami.
                </p>
            </div>
        </div>

        <!-- Form Konsultasi Sederhana -->
        <div class="col-lg-6">
            <div class="card shadow-sm border p-4">
                <h4 class="fw-bold mb-3">Kirim Pesan atau Pertanyaan</h4>
                
                <?php if ($sentSuccess): ?>
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle-fill me-2"></i> Terima kasih, pesan Anda telah diterima! Tim kami akan segera merespons melalui email atau WhatsApp.
                    </div>
                <?php endif; ?>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger py-2 small">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> <?= e($error) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" class="form-control" id="name" name="name" required placeholder="Contoh: Budi Santoso">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email Aktif</label>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="nama@email.com">
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label fw-semibold">Pesan / Pertanyaan</label>
                        <textarea class="form-control" id="message" name="message" rows="4" required placeholder="Tuliskan pertanyaan atau kebutuhan desain Anda di sini..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2">
                        <i class="bi bi-send me-1"></i> Kirim Pesan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
