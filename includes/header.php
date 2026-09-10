<?php
// =====================================================================
// FILE: includes/header.php
// Header & Navbar Publik Website Kreavio Creative
// =====================================================================

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

$pageTitle = isset($pageTitle) ? $pageTitle . ' - Kreavio Creative' : 'Kreavio Creative - Create Better. Present Better.';
$currentPage = basename($_SERVER['PHP_SELF']);
$isCustomer = is_logged_in();
$currentUser = $isCustomer ? current_user($pdo) : null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?= asset_url('images/logo.svg') ?>">
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom CSS (Cache Busted) -->
    <link rel="stylesheet" href="<?= asset_url('css/style.css?v=' . filemtime(__DIR__ . '/../assets/css/style.css')) ?>">
    
    <!-- Script Tema Mandiri (Bebas dari cache browser) -->
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
<body class="bg-light">

    <!-- NAVBAR UTAMA -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="<?= base_url('index.php') ?>">
                <img src="<?= asset_url('images/logo.svg') ?>" alt="Kreavio Creative Logo" height="38" class="me-2" style="filter: brightness(0) invert(1);">
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarKreavio" aria-controls="navbarKreavio" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarKreavio">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                    <li class="nav-item">
                        <a class="nav-link <?= ($currentPage === 'index.php') ? 'active fw-bold text-white' : 'text-light' ?>" href="<?= base_url('index.php') ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($currentPage === 'services.php' || $currentPage === 'service-detail.php') ? 'active fw-bold text-white' : 'text-light' ?>" href="<?= base_url('services.php') ?>">Layanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($currentPage === 'about.php') ? 'active fw-bold text-white' : 'text-light' ?>" href="<?= base_url('about.php') ?>">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($currentPage === 'contact.php') ? 'active fw-bold text-white' : 'text-light' ?>" href="<?= base_url('contact.php') ?>">Contact</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-3">
                    <button type="button" class="btn btn-sm btn-outline-light theme-toggle-btn px-2" onclick="toggleTheme()" title="Ganti Mode Tampilan" aria-label="Ganti Mode Tampilan">
                        <i class="bi bi-moon"></i>
                    </button>
                    <?php if ($isCustomer): ?>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-light dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle"></i>
                                <span><?= e($currentUser['name'] ?? 'Akun Saya') ?></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li><a class="dropdown-item" href="<?= base_url('customer/dashboard.php') ?>"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('customer/orders.php') ?>"><i class="bi bi-bag-check me-2"></i>Pesanan Saya</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="<?= base_url('logout.php') ?>"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                            </ul>
                        </div>
                        <a href="<?= base_url('services.php') ?>" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> Pesan Sekarang
                        </a>
                    <?php else: ?>
                        <a href="<?= base_url('login.php') ?>" class="btn btn-sm btn-outline-light">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Login
                        </a>
                        <a href="<?= base_url('services.php') ?>" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> Pesan Sekarang
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- NOTIFIKASI FLASH MESSAGE -->
    <div class="container mt-3">
        <?php display_flash(); ?>
    </div>

    <!-- MAIN CONTENT WRAPPER -->
    <main>
