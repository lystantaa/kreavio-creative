<?php
// =====================================================================
// FILE: admin/services.php
// Halaman Kelola Layanan Desain (CRUD Read) untuk Admin (Section 22)
// =====================================================================

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/admin-auth.php';

require_admin();
$admin = current_admin($pdo);

try {
    $stmt = $pdo->query("SELECT * FROM services ORDER BY id ASC");
    $services = $stmt->fetchAll();
} catch (PDOException $e) {
    $services = [];
}

$pageTitle = 'Kelola Layanan';
$currentAdminPage = 'services.php';
require_once __DIR__ . '/../includes/admin-header.php';
?>

    <div class="container py-4">
        <?php display_flash(); ?>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Kelola Layanan Desain</h3>
                <p class="text-muted mb-0">Tambah, ubah nama, sesuaikan harga, atau nonaktifkan produk layanan desain.</p>
            </div>
            <div class="mt-3 mt-md-0">
                <a href="<?= base_url('admin/service-create.php') ?>" class="btn btn-primary shadow-sm">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Layanan Baru
                </a>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <?php if (empty($services)): ?>
                    <div class="p-5 text-center text-muted">
                        Belum ada layanan desain yang terdaftar.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Nama Layanan</th>
                                    <th>Deskripsi Singkat</th>
                                    <th>Harga</th>
                                    <th>Estimasi Waktu</th>
                                    <th>Status</th>
                                    <th class="text-end" style="width: 160px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($services as $srv): ?>
                                    <tr>
                                        <td class="text-muted fw-semibold">#<?= $srv['id'] ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="<?= get_service_image($srv['id']) ?>" alt="" width="42" height="30" class="rounded border me-2" style="object-fit: cover;">
                                                <strong class="text-dark"><?= e($srv['name']) ?></strong>
                                            </div>
                                        </td>
                                        <td>
                                            <small class="text-muted" style="max-width: 320px; display: inline-block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                <?= e($srv['description']) ?>
                                            </small>
                                        </td>
                                        <td class="fw-bold text-primary">
                                            <?= format_rupiah($srv['price']) ?>
                                        </td>
                                        <td><?= e($srv['duration']) ?></td>
                                        <td>
                                            <?php if ($srv['active'] == 1): ?>
                                                <span class="badge bg-success-subtle text-success border border-success-subtle">
                                                    <i class="bi bi-check-circle me-1"></i>Aktif
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary-subtle text-secondary border">
                                                    <i class="bi bi-dash-circle me-1"></i>Nonaktif
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end">
                                            <a href="<?= base_url('admin/service-edit.php?id=' . $srv['id']) ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <a href="<?= base_url('admin/service-delete.php?id=' . $srv['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus layanan ini?')" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
