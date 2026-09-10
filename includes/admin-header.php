<?php
// =====================================================================
// FILE: includes/admin-header.php
// Layout Header & Navigasi Bersama untuk Portal Administrator
// =====================================================================

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/admin-auth.php';

require_admin();
$admin = current_admin($pdo);

$pageTitle = isset($pageTitle) ? $pageTitle . ' - Admin Kreavio' : 'Admin Panel - Kreavio Creative';
$currentAdminPage = $currentAdminPage ?? basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <link rel="icon" type="image/svg+xml" href="<?= asset_url('images/logo.svg') ?>">
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
<body class="bg-light">

    <!-- NAVBAR ADMIN TERPADU -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark navbar-admin sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-white d-flex align-items-center" href="<?= base_url('admin/index.php') ?>">
                <i class="bi bi-shield-lock-fill text-primary me-2"></i> Kreavio Admin
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?= ($currentAdminPage === 'index.php') ? 'active fw-bold text-white' : 'text-light' ?>" href="<?= base_url('admin/index.php') ?>">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= in_array($currentAdminPage, ['orders.php', 'order-detail.php']) ? 'active fw-bold text-white' : 'text-light' ?>" href="<?= base_url('admin/orders.php') ?>">
                            <i class="bi bi-cart-check me-1"></i> Daftar Pesanan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= in_array($currentAdminPage, ['services.php', 'service-create.php', 'service-edit.php']) ? 'active fw-bold text-white' : 'text-light' ?>" href="<?= base_url('admin/services.php') ?>">
                            <i class="bi bi-grid-3x3-gap me-1"></i> Kelola Layanan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($currentAdminPage === 'sales-report.php') ? 'active fw-bold text-white' : 'text-light' ?>" href="<?= base_url('admin/sales-report.php') ?>">
                            <i class="bi bi-graph-up me-1"></i> Rekap Penjualan
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-3">
                    <button type="button" class="btn btn-sm btn-outline-light theme-toggle-btn px-2" onclick="toggleTheme()" title="Ganti Mode Tampilan" aria-label="Ganti Mode Tampilan">
                        <i class="bi bi-moon"></i>
                    </button>
                    <a href="<?= base_url('index.php') ?>" target="_blank" class="btn btn-sm btn-outline-light">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Lihat Web
                    </a>
                    <span class="admin-user-badge small text-white"><i class="bi bi-person me-1"></i><?= e($admin['name']) ?></span>
                    <a href="<?= base_url('admin/logout.php') ?>" class="btn btn-sm btn-danger">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>
