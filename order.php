<?php
// =====================================================================
// FILE: order.php
// Halaman Formulir Pemesanan Layanan Desain (Single Order Checkout)
// =====================================================================

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

// Customer wajib login untuk membuat pesanan
require_login();
$user = current_user($pdo);

// Ambil semua layanan yang aktif untuk pilihan dropdown
try {
    $servicesStmt = $pdo->query("SELECT * FROM services WHERE active = 1 ORDER BY price ASC");
    $services = $servicesStmt->fetchAll();
} catch (PDOException $e) {
    $services = [];
}

// Cek jika ada parameter service_id yang dipilih dari halaman katalog
$selectedServiceId = isset($_GET['service_id']) ? (int)$_GET['service_id'] : 0;
if ($selectedServiceId === 0 && !empty($services)) {
    $selectedServiceId = $services[0]['id'];
}

$errors = [];
$customerName = $user['name'] ?? '';
$customerPhone = $user['phone'] ?? '';
$deadline = date('Y-m-d', strtotime('+3 days'));
$brief = '';
$notes = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedServiceId = (int)($_POST['service_id'] ?? 0);
    $customerName      = trim($_POST['customer_name'] ?? '');
    $customerPhone     = trim($_POST['customer_phone'] ?? '');
    $deadline          = trim($_POST['deadline'] ?? '');
    $brief             = trim($_POST['brief'] ?? '');
    $notes             = trim($_POST['notes'] ?? '');

    // Validasi form
    if ($selectedServiceId <= 0) {
        $errors[] = 'Silakan pilih layanan desain yang diinginkan.';
    }
    if (empty($customerName)) {
        $errors[] = 'Nama pemesan tidak boleh kosong.';
    }
    if (empty($customerPhone)) {
        $errors[] = 'Nomor WhatsApp wajib diisi untuk koordinasi.';
    }
    if (empty($deadline)) {
        $errors[] = 'Tanggal deadline wajib ditentukan.';
    } elseif (strtotime($deadline) < strtotime(date('Y-m-d'))) {
        $errors[] = 'Tanggal deadline minimal hari ini atau setelahnya.';
    }
    if (empty($brief)) {
        $errors[] = 'Deskripsi / brief kebutuhan desain wajib diisi agar desainer memahami tugas.';
    }

    // Ambil data layanan terpilih dari database
    $serviceStmt = $pdo->prepare("SELECT * FROM services WHERE id = ? AND active = 1");
    $serviceStmt->execute([$selectedServiceId]);
    $serviceData = $serviceStmt->fetch();

    if (!$serviceData) {
        $errors[] = 'Layanan yang dipilih tidak valid atau sudah tidak aktif.';
    }

    // Jika validasi sukses, buat order baru
    if (empty($errors)) {
        // Generate nomor order unik, contoh: ORD-20260903-847
        $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
        $total = $serviceData['price'];

        try {
            $pdo->beginTransaction();

            // 1. Simpan ke tabel orders
            $orderInsert = $pdo->prepare("
                INSERT INTO orders (order_number, user_id, service_id, deadline, brief, notes, total, payment_status, order_status, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'pending_payment', 'pending_payment', NOW(), NOW())
            ");
            $orderInsert->execute([
                $orderNumber,
                $user['id'],
                $serviceData['id'],
                $deadline,
                $brief,
                $notes,
                $total
            ]);
            $orderId = $pdo->lastInsertId();

            // 2. Simpan ke tabel order_status_logs
            $logInsert = $pdo->prepare("
                INSERT INTO order_status_logs (order_id, status, note, created_at)
                VALUES (?, 'pending_payment', 'Pesanan baru dibuat oleh customer', NOW())
            ");
            $logInsert->execute([$orderId]);

            // 3. Perbarui nomor telepon atau nama customer jika disesuaikan
            if ($customerPhone !== $user['phone'] || $customerName !== $user['name']) {
                $userUpdate = $pdo->prepare("UPDATE users SET name = ?, phone = ? WHERE id = ?");
                $userUpdate->execute([$customerName, $customerPhone, $user['id']]);
                $_SESSION['user_name'] = $customerName;
            }

            $pdo->commit();

            // Arahkan langsung ke halaman checkout
            redirect('checkout.php?order_number=' . urlencode($orderNumber));

        } catch (PDOException $e) {
            $pdo->rollBack();
            $errors[] = 'Gagal memproses pesanan: ' . $e->getMessage();
        }
    }
}

$pageTitle = 'Formulir Pemesanan Jasa Desain';
require_once __DIR__ . '/includes/header.php';
?>

<div class="bg-light py-4 border-bottom mb-4">
    <div class="container">
        <h2 class="fw-bold mb-1">Form Pemesanan Desain</h2>
        <p class="text-muted mb-0">Isi detail kebutuhan desain Anda secara jelas untuk hasil yang maksimal</p>
    </div>
</div>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border p-4">
                
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0 small ps-3">
                            <?php foreach ($errors as $err): ?>
                                <li><?= e($err) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <!-- 1. Pilihan Layanan -->
                    <div class="mb-4 pb-3 border-bottom">
                        <h5 class="fw-bold text-primary mb-3"><i class="bi bi-palette me-2"></i>1. Pilih Layanan Desain</h5>
                        <div class="mb-3">
                            <label for="service_id" class="form-label fw-semibold">Jenis Layanan</label>
                            <select class="form-select form-select-lg" id="service_id" name="service_id" required>
                                <option value="">-- Pilih Jenis Layanan --</option>
                                <?php foreach ($services as $srv): ?>
                                    <option value="<?= $srv['id'] ?>" <?= ($selectedServiceId == $srv['id']) ? 'selected' : '' ?>>
                                        <?= e($srv['name']) ?> &mdash; <?= format_rupiah($srv['price']) ?> (Estimasi: <?= e($srv['duration']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">Single Order Checkout: Setiap pesanan fokus pada satu produk desain terbaik.</div>
                        </div>
                    </div>

                    <!-- 2. Data Pemesan -->
                    <div class="mb-4 pb-3 border-bottom">
                        <h5 class="fw-bold text-primary mb-3"><i class="bi bi-person-lines-fill me-2"></i>2. Data Kontak Pemesan</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="customer_name" class="form-label fw-semibold">Nama Pemesan</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name" value="<?= e($customerName) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="customer_phone" class="form-label fw-semibold">Nomor WhatsApp</label>
                                <input type="text" class="form-control" id="customer_phone" name="customer_phone" value="<?= e($customerPhone) ?>" required placeholder="Contoh: 081234567890">
                            </div>
                        </div>
                    </div>

                    <!-- 3. Detail Brief Desain -->
                    <div class="mb-4">
                        <h5 class="fw-bold text-primary mb-3"><i class="bi bi-card-text me-2"></i>3. Detail & Brief Kebutuhan</h5>
                        <div class="mb-3">
                            <label for="deadline" class="form-label fw-semibold">Target Tanggal Selesai (Deadline)</label>
                            <input type="date" class="form-control" id="deadline" name="deadline" value="<?= e($deadline) ?>" min="<?= date('Y-m-d') ?>" required>
                            <div class="form-text">Beri waktu yang realistis sesuai estimasi durasi layanan.</div>
                        </div>

                        <div class="mb-3">
                            <label for="brief" class="form-label fw-semibold">Deskripsi / Brief Desain</label>
                            <textarea class="form-control" id="brief" name="brief" rows="4" required placeholder="Jelaskan kebutuhan desain Anda secara spesifik. Contoh:&#10;- Judul/Tema: CV Fresh Graduate Teknik Informatika&#10;- Nuansa Warna: Navy Blue & Minimalis Modern&#10;- Isi Konten: Profil singkat, riwayat kuliah, skill tools Figma & PHP"><?= e($brief) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label fw-semibold">Catatan Tambahan (Opsional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Catatan opsional, contoh: mohon kirimkan format PDF high quality dan file Canva / link Google Drive"><?= e($notes) ?></textarea>
                        </div>
                    </div>

                    <div class="d-grid gap-2 pt-2">
                        <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                            <i class="bi bi-arrow-right-circle me-1"></i> Lanjut ke Pembayaran (Checkout)
                        </button>
                        <a href="<?= base_url('services.php') ?>" class="btn btn-outline-secondary">
                            Batal & Kembali ke Layanan
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
