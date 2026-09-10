// =====================================================================
// FILE: assets/js/app.js
// Javascript Vanilla untuk Kreavio Creative
// =====================================================================

document.addEventListener('DOMContentLoaded', function () {
    // 1. Inisialisasi Tooltip Bootstrap (jika elemen memiliki data-bs-toggle="tooltip")
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // 2. Auto-hide alert setelah 5 detik untuk pengalaman pengguna yang nyaman
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(function (alertEl) {
        setTimeout(function () {
            const bsAlert = new bootstrap.Alert(alertEl);
            bsAlert.close();
        }, 6000);
    });

    // 3. Sinkronkan ikon tombol tema dengan tema aktif saat ini
    const currentTheme = document.documentElement.getAttribute('data-bs-theme') || localStorage.getItem('kreavio_theme') || 'light';
    updateThemeButtons(currentTheme);
});

/**
 * Fungsi Pengalih Mode Tampilan (Terang / Gelap) via 1 Tombol
 */
function toggleTheme() {
    const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    document.documentElement.setAttribute('data-bs-theme', newTheme);
    localStorage.setItem('kreavio_theme', newTheme);
    updateThemeButtons(newTheme);
}

/**
 * Memperbarui tampilan ikon dan tooltip tombol pengalih tema
 */
function updateThemeButtons(theme) {
    const buttons = document.querySelectorAll('.theme-toggle-btn');
    buttons.forEach(function (btn) {
        const icon = btn.querySelector('i');
        if (icon) {
            if (theme === 'dark') {
                icon.className = 'bi bi-sun text-warning';
                btn.setAttribute('title', 'Ganti ke Mode Terang');
                btn.setAttribute('aria-label', 'Ganti ke Mode Terang');
            } else {
                icon.className = 'bi bi-moon';
                btn.setAttribute('title', 'Ganti ke Mode Gelap');
                btn.setAttribute('aria-label', 'Ganti ke Mode Gelap');
            }
        }
    });
}

/**
 * Konfirmasi sebelum menghapus data (Layanan, dll)
 */
function confirmDelete(message) {
    return confirm(message || 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.');
}

/**
 * Helper untuk memicu Snap Midtrans di halaman checkout
 */
function payWithMidtrans(snapToken, successUrl, pendingUrl, errorUrl) {
    const payBtn = document.getElementById('pay-button');

    if (typeof window.snap === 'undefined') {
        alert('Library Midtrans Snap belum termuat. Periksa koneksi internet atau gunakan Pembayaran Mode Cepat.');
        if (payBtn) {
            payBtn.disabled = false;
            payBtn.innerHTML = '<i class="bi bi-credit-card-2-front me-2"></i> Bayar via Midtrans';
        }
        return;
    }

    window.snap.pay(snapToken, {
        onSuccess: function (result) {
            console.log('Payment success:', result);
            window.location.href = successUrl;
        },
        onPending: function (result) {
            console.log('Payment pending:', result);
            alert('Menunggu pembayaran diselesaikan pada channel yang dipilih.');
            if (pendingUrl) window.location.href = pendingUrl;
        },
        onError: function (result) {
            console.error('Payment error:', result);
            alert('Pembayaran gagal atau terjadi kesalahan.');
            if (payBtn) {
                payBtn.disabled = false;
                payBtn.innerHTML = '<i class="bi bi-credit-card-2-front me-2"></i> Bayar via Midtrans';
            }
        },
        onClose: function () {
            // Ketika tombol silang (X) ditekan, pembayaran TIDAK langsung berhasil
            alert('Popup pembayaran ditutup. Pembayaran belum selesai.');
            if (payBtn) {
                payBtn.disabled = false;
                payBtn.innerHTML = '<i class="bi bi-credit-card-2-front me-2"></i> Bayar via Midtrans';
            }
        }
    });
}
