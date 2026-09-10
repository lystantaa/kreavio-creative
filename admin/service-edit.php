<?php
// =====================================================================
// FILE: admin/service-edit.php
// Edit Layanan (CRUD Update) untuk Admin (Section 22)
// =====================================================================

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/admin-auth.php';

require_admin();
$admin = current_admin($pdo);

$serviceId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
$stmt->execute([$serviceId]);
$service = $stmt->fetch();

if (!$service) {
    set_flash('danger', 'Layanan tidak ditemukan.');
    redirect('admin/services.php');
}

$errors = [];
$name = $service['name'];
$description = $service['description'];
$price = $service['price'];
$duration = $service['duration'];
$active = $service['active'];

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $name        = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price       = (int)($_POST['price'] ?? 0);
    $duration    = trim($_POST['duration'] ?? '');
    $active      = isset($_POST['active']) ? 1 : 0;

    if (empty($name)) {
        $errors[] = 'Nama layanan wajib diisi.';
    }
    if (empty($description)) {
        $errors[] = 'Deskripsi layanan wajib diisi.';
    }
    if ($price <= 0) {
        $errors[] = 'Harga layanan harus lebih dari 0.';
    }
    if (empty($duration)) {
        $errors[] = 'Estimasi pengerjaan wajib diisi.';
    }

    if (empty($errors)) {
        try {
            $upStmt = $pdo->prepare("
                UPDATE services 
                SET name = ?, description = ?, price = ?, duration = ?, active = ?
                WHERE id = ?
            ");
            $upStmt->execute([$name, $description, $price, $duration, $active, $serviceId]);

            set_flash('success', 'Perubahan layanan berhasil disimpan!');
            redirect('admin/services.php');
        } catch (PDOException $e) {
            $errors[] = 'Gagal menyimpan perubahan: ' . $e->getMessage();
        }
    }
}

$pageTitle = 'Edit Layanan #' . $service['id'];
$currentAdminPage = 'service-edit.php';
require_once __DIR__ . '/../includes/admin-header.php';
?>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="fw-bold mb-0">Edit Layanan #<?= $service['id'] ?></h3>
                    <a href="<?= base_url('admin/services.php') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>

                <div class="card shadow-sm border-0 p-4">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger py-2">
                            <ul class="mb-0 small ps-3">
                                <?php foreach ($errors as $err): ?>
                                    <li><?= e($err) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Nama Layanan</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= e($name) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">Deskripsi Lengkap</label>
                            <textarea class="form-control" id="description" name="description" rows="4" required><?= e($description) ?></textarea>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="price" class="form-label fw-semibold">Harga (Rupiah)</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" id="price" name="price" value="<?= e($price) ?>" min="1000" step="1000" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="duration" class="form-label fw-semibold">Estimasi Pengerjaan</label>
                                <input type="text" class="form-control" id="duration" name="duration" value="<?= e($duration) ?>" required>
                            </div>
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" role="switch" id="active" name="active" value="1" <?= ($active == 1) ? 'checked' : '' ?>>
                            <label class="form-check-label fw-semibold" for="active">Layanan Aktif (Tampilkan di Website)</label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary py-2 fw-semibold">
                                <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
