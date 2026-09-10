<?php
// =====================================================================
// FILE: login.php
// Halaman Login Customer Kreavio Creative
// =====================================================================

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

// Jika sudah login, langsung ke dashboard customer
if (is_logged_in()) {
    redirect('customer/dashboard.php');
}

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Silakan isi email dan password Anda.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Verifikasi kecocokan hash password
        if ($user && password_verify($password, $user['password'])) {
            // Regenerasi ID Session untuk mencegah session fixation
            session_regenerate_id(true);

            // Login sukses, simpan session customer
            $_SESSION['user_id']    = $user['id'];
            $_SESSION['user_name']  = $user['name'];
            $_SESSION['user_email'] = $user['email'];

            set_flash('success', 'Selamat datang kembali, ' . $user['name'] . '!');

            // Redirect ke halaman yang diinginkan sebelum login atau dashboard
            if (isset($_SESSION['redirect_after_login'])) {
                $target = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']);
                header("Location: $target");
                exit;
            }

            redirect('customer/dashboard.php');
        } else {
            $error = 'Email atau password salah. Silakan periksa kembali.';
        }
    }
}

$pageTitle = 'Login Customer';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm border p-4">
                <div class="text-center mb-4">
                    <h3 class="fw-bold">Login Pelanggan</h3>
                    <p class="text-muted small">Masuk untuk membuat dan memantau status pesanan desain Anda</p>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger py-2 small">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> <?= e($error) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Alamat Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= e($email) ?>" required placeholder="nama@email.com">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required placeholder="Masukkan password">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Sekarang
                    </button>
                </form>

                <div class="mt-4 p-3 bg-light rounded-3 border">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="badge bg-secondary">Akun Uji Coba</span>
                        <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size: 0.78rem;" onclick="fillDemoCustomer()">
                            Gunakan Akun Ini
                        </button>
                    </div>
                    <p class="small text-muted mb-1">Data login untuk pengujian:</p>
                    <code class="d-block small text-dark">Email: budi@example.com</code>
                    <code class="d-block small text-dark">Password: password123</code>
                </div>

                <div class="text-center mt-4 pt-3 border-top">
                    <p class="small text-muted mb-0">
                        Belum punya akun? <a href="<?= base_url('register.php') ?>" class="fw-semibold text-primary">Daftar sekarang</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function fillDemoCustomer() {
        document.getElementById('email').value = 'budi@example.com';
        document.getElementById('password').value = 'password123';
    }
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
