<?php
// =====================================================================
// FILE: includes/footer.php
// Footer Publik Website Kreavio Creative
// =====================================================================
?>
    </main>

    <!-- FOOTER -->
    <footer>
        <div class="container">
            <div class="row g-4">
                <!-- Kolom 1: Profil Bisnis -->
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex align-items-center mb-3">
                        <img src="<?= asset_url('images/logo.svg') ?>" alt="Kreavio Creative" height="36" style="filter: brightness(0) invert(1);">
                    </div>
                    <p class="text-secondary mb-3" style="line-height: 1.6;">
                        <strong>"Create Better. Present Better."</strong><br>
                        Kreavio Creative adalah unit bisnis jasa desain digital sederhana yang membantu mahasiswa, pelajar, UMKM, dan profesional menghasilkan karya visual yang rapi dan memikat.
                    </p>
                    <span class="badge bg-primary text-white">Digital Services Only</span>
                </div>

                <!-- Kolom 2: Navigasi Cepat -->
                <div class="col-lg-2 col-md-6">
                    <h6 class="text-white mb-3">Navigasi</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?= base_url('index.php') ?>">Home</a></li>
                        <li class="mb-2"><a href="<?= base_url('services.php') ?>">Daftar Layanan</a></li>
                        <li class="mb-2"><a href="<?= base_url('about.php') ?>">Tentang Kami</a></li>
                        <li class="mb-2"><a href="<?= base_url('contact.php') ?>">Kontak Bisnis</a></li>
                    </ul>
                </div>

                <!-- Kolom 3: Layanan Populer -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="text-white mb-3">Layanan Kami</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?= base_url('service-detail.php?id=1') ?>">Desain CV ATS-Friendly</a></li>
                        <li class="mb-2"><a href="<?= base_url('service-detail.php?id=2') ?>">Desain Slide PowerPoint</a></li>
                        <li class="mb-2"><a href="<?= base_url('service-detail.php?id=3') ?>">Desain Poster Acara</a></li>
                        <li class="mb-2"><a href="<?= base_url('service-detail.php?id=6') ?>">Desain Logo Branding</a></li>
                    </ul>
                </div>

                <!-- Kolom 4: Kontak & Portal Admin -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="text-white mb-3">Hubungi Kami</h6>
                    <p class="text-secondary small mb-2">
                        <i class="bi bi-geo-alt me-2 text-primary"></i> Kampus & Online Studio
                    </p>
                    <p class="text-secondary small mb-2">
                        <i class="bi bi-whatsapp me-2 text-success"></i> +62 812-3456-7890
                    </p>
                    <p class="text-secondary small mb-3">
                        <i class="bi bi-envelope me-2 text-danger"></i> halo@kreavio.test
                    </p>
                    <div class="pt-2 border-top border-secondary border-opacity-25">
                        <a href="<?= base_url('admin/login.php') ?>" class="text-secondary small">
                            <i class="bi bi-shield-lock me-1"></i> Portal Admin
                        </a>
                    </div>
                </div>
            </div>

            <!-- Copyright -->
            <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <div>
                    &copy; <?= date('Y') ?> <strong>Kreavio Creative</strong>. All rights reserved.
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JavaScript -->
    <script src="<?= asset_url('js/app.js?v=' . filemtime(__DIR__ . '/../assets/js/app.js')) ?>"></script>
</body>
</html>
