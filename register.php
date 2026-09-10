<?php
// =====================================================================
// FILE: register.php
// Halaman Registrasi Akun Customer Kreavio Creative
// =====================================================================

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

// Jika sudah login, langsung arahkan ke dashboard customer
if (is_logged_in()) {
    redirect('customer/dashboard.php');
}

$errors = [];
$name = '';
$email = '';
$phone = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';

    // Validasi input pemula
    if (empty($name)) {
        $errors[] = 'Nama lengkap wajib diisi.';
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Format email tidak valid.';
    }
    if (empty($phone)) {
        $errors[] = 'Nomor WhatsApp wajib diisi untuk koordinasi desain.';
    }
    if (strlen($password) < 6) {
        $errors[] = 'Password minimal terdiri dari 6 karakter.';
    }
    if ($password !== $passwordConfirm) {
        $errors[] = 'Konfirmasi password tidak cocok.';
    }

    // Jika validasi lolos, cek apakah email sudah terdaftar
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'Email ini sudah terdaftar. Silakan login atau gunakan email lain.';
        } else {
            // Hash password dengan algoritma aman
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Simpan ke database
            $insertStmt = $pdo->prepare("INSERT INTO users (name, email, password, phone, created_at) VALUES (?, ?, ?, ?, NOW())");
            $success = $insertStmt->execute([$name, $email, $hashedPassword, $phone]);

            if ($success) {
                // Regenerasi ID Session untuk mencegah session fixation
                session_regenerate_id(true);

                // Ambil ID user baru dan otomatis login
                $userId = $pdo->lastInsertId();
                $_SESSION['user_id'] = $userId;
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;

                set_flash('success', 'Registrasi berhasil! Selamat datang di Kreavio Creative.');
                
                // Jika sebelumnya diarahkan saat hendak checkout
                if (isset($_SESSION['redirect_after_login'])) {
                    $target = $_SESSION['redirect_after_login'];
                    unset($_SESSION['redirect_after_login']);
                    header("Location: $target");
                    exit;
                }
                
                redirect('customer/dashboard.php');
            } else {
                $errors[] = 'Terjadi kesalahan sistem saat menyimpan akun. Silakan coba lagi.';
            }
        }
    }
}

$pageTitle = 'Daftar Akun Customer';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="card shadow-sm border p-4">
                <div class="text-center mb-4">
                    <h3 class="fw-bold">Buat Akun Baru</h3>
                    <p class="text-muted small">Daftar sekarang untuk memesan jasa desain digital</p>
                </div>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger py-2">
                        <ul class="mb-0 small ps-3">
                            <?php foreach ($errors as $error): ?>
                                <li><?= e($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= e($name) ?>" required placeholder="Contoh: Budi Pratama">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Alamat Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= e($email) ?>" required placeholder="nama@email.com">
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label fw-semibold">Nomor WhatsApp</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?= e($phone) ?>" required placeholder="Contoh: 081234567890">
                        <div class="form-text">Untuk update progres dan konfirmasi pesanan.</div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required placeholder="Minimal 6 karakter">
                    </div>

                    <div class="mb-3">
                        <label for="password_confirm" class="form-label fw-semibold">Ulangi Password</label>
                        <input type="password" class="form-control" id="password_confirm" name="password_confirm" required placeholder="Ketik ulang password">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                        <i class="bi bi-person-plus me-1"></i> Daftar Sekarang
                    </button>
                </form>

                <div class="text-center mt-4 pt-3 border-top">
                    <p class="small text-muted mb-0">
                        Sudah memiliki akun? <a href="<?= base_url('login.php') ?>" class="fw-semibold text-primary">Login di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
