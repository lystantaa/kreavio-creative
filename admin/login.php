<?php
// =====================================================================
// FILE: admin/login.php
// Halaman Login Administrator Kreavio Creative
// =====================================================================

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/admin-auth.php';

// Jika admin sudah login, alihkan ke dashboard admin
if (is_admin_logged_in()) {
    redirect('admin/index.php');
}

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Email dan password admin wajib diisi.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            // Regenerasi ID Session untuk mencegah session fixation
            session_regenerate_id(true);

            // Login sukses
            $_SESSION['admin_id']    = $admin['id'];
            $_SESSION['admin_name']  = $admin['name'];
            $_SESSION['admin_email'] = $admin['email'];

            set_flash('success', 'Login berhasil! Selamat datang di Panel Administrator, ' . $admin['name']);
            redirect('admin/index.php');
        } else {
            $error = 'Kombinasi email atau password admin salah.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrator - Kreavio Creative</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= asset_url('css/style.css?v=' . filemtime(__DIR__ . '/../assets/css/style.css')) ?>">
    <script>
        (function() {
            var savedTheme = localStorage.getItem('kreavio_theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        })();

        function toggleTheme() {
            var current = document.documentElement.getAttribute('data-bs-theme') || 'light';
            var next = (current === 'dark') ? 'light' : 'dark';
            document.documentElement.setAttribute('data-bs-theme', next);
            localStorage.setItem('kreavio_theme', next);
            updateAllThemeButtons(next);
        }

        function updateAllThemeButtons(theme) {
            var buttons = document.querySelectorAll('.theme-toggle-btn');
            buttons.forEach(function(btn) {
                var icon = btn.querySelector('i');
                if (icon) {
                    if (theme === 'dark') {
                        icon.className = 'bi bi-sun text-warning';
                        btn.setAttribute('title', 'Ganti ke Mode Terang');
                    } else {
                        icon.className = 'bi bi-moon';
                        btn.setAttribute('title', 'Ganti ke Mode Gelap');
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            var current = document.documentElement.getAttribute('data-bs-theme') || 'light';
            updateAllThemeButtons(current);
        });
    </script>
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100 position-relative">
    <div class="position-absolute top-0 end-0 p-3">
        <button type="button" class="btn btn-outline-secondary btn-sm theme-toggle-btn" onclick="toggleTheme()" title="Ganti Mode Tampilan" aria-label="Ganti Mode Tampilan">
            <i class="bi bi-moon"></i>
        </button>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="text-center mb-4">
                    <img src="<?= asset_url('images/logo.svg') ?>" alt="Kreavio" height="42" class="mb-2">
                    <h5 class="fw-bold text-dark">Portal Administrator</h5>
                    <p class="text-muted small">Kelola pesanan dan layanan desain Kreavio Creative</p>
                </div>

                <div class="card shadow-sm border p-4">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger py-2 small">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> <?= e($error) ?>
                        </div>
                    <?php endif; ?>

                    <?php display_flash(); ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email Admin</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= e($email) ?>" required placeholder="admin@kreavio.test">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required placeholder="Password admin">
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Panel Admin
                        </button>
                    </form>

                    <div class="mt-4 p-3 bg-light rounded-3 border">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="badge bg-secondary">Akun Uji Coba Admin</span>
                            <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size: 0.78rem;" onclick="fillDemoAdmin()">
                                Gunakan Akun Ini
                            </button>
                        </div>
                        <code class="d-block small text-dark">Email: admin@kreavio.test</code>
                        <code class="d-block small text-dark">Password: admin123</code>
                    </div>

                    <div class="text-center mt-3 pt-2 border-top">
                        <a href="<?= base_url('index.php') ?>" class="small text-muted text-decoration-none">
                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Website Utama
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset_url('js/app.js') ?>"></script>
    <script>
        function fillDemoAdmin() {
            document.getElementById('email').value = 'admin@kreavio.test';
            document.getElementById('password').value = 'admin123';
        }
    </script>
</body>
</html>
