// ============================================
// SPAREPART USK - SCRIPT.JS
// JavaScript untuk modal, navbar mobile, dan interaksi
// ============================================

// ============================================
// UTILITAS - Deteksi Perangkat
// ============================================

// Deteksi apakah pengguna menggunakan perangkat mobile
function isMobile() {
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}

// Deteksi apakah halaman diakses sebagai PWA (standalone)
function isPWA() {
    return window.matchMedia('(display-mode: standalone)').matches ||
           (window.navigator.standalone && window.navigator.standalone !== false);
}

// ============================================
// NAVBAR TOGGLE (Mobile Hamburger Menu)
// ============================================

function initNavbarToggle() {
    var toggle = document.getElementById('navbarToggle');
    var menu = document.getElementById('navbarMenu');

    if (toggle && menu) {
        toggle.addEventListener('click', function(e) {
            e.stopPropagation();
            menu.classList.toggle('active');
            // Toggle icon between menu and close
            if (menu.classList.contains('active')) {
                toggle.textContent = '✕';
            } else {
                toggle.textContent = '☰';
            }
        });

        // Close menu saat klik di luar
        document.addEventListener('click', function(event) {
            if (menu.classList.contains('active') &&
                !menu.contains(event.target) &&
                !toggle.contains(event.target)) {
                menu.classList.remove('active');
                toggle.textContent = '☰';
            }
        });
    }
}

// ============================================
// DOWNLOAD APK & BUKA DI BROWSER
// ============================================

// Hitung base URL situs (root direktori)
// Bekerja baik untuk domain root maupun subfolder
var pathParts = window.location.pathname.split('/');
var siteRoot = pathParts.length > 2 ? '/' + pathParts[1] + '/' : '/';

// Konfigurasi link download APK (root-relative path)
var APK_DOWNLOAD_URL = siteRoot + 'apk/sparepart-usk.apk';

// Unduh APK
function downloadAPK() {
    // Buat link sementara untuk trigger download
    var a = document.createElement('a');
    a.href = APK_DOWNLOAD_URL;
    a.download = 'SparepartUSK.apk';
    a.className = 'apk-download-link';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}

// Buka di browser (untuk pengguna webview/PWA)
function openInBrowser() {
    var url = window.location.href;
    // Jika di mobile, buka di tab browser baru
    if (isMobile()) {
        // Gunakan _blank untuk membuka di browser eksternal
        window.open(url, '_blank');
    } else {
        // Desktop: tetap buka di tab baru
        window.open(url, '_blank');
    }
}

// ============================================
// FLOATING DOWNLOAD BANNER (Mobile Only)
// ============================================

function injectMobileBanner() {
    if (!isMobile()) return;

    // Cek apakah banner sudah ada
    if (document.getElementById('mobileDownloadBanner')) return;

    var banner = document.createElement('div');
    banner.id = 'mobileDownloadBanner';
    banner.className = 'mobile-download-banner';
    banner.innerHTML =
        '<div class="banner-text">Ada aplikasi mobile! <strong>Unduh sekarang</strong></div>' +
        '<div class="banner-btn">' +
            '<a href="' + APK_DOWNLOAD_URL + '" class="btn btn-primary btn-sm" download="SparepartUSK.apk">APK</a>' +
            '<a href="#" class="btn btn-secondary btn-sm" onclick="openInBrowser(); return false;">Browser</a>' +
        '</div>';

    document.body.appendChild(banner);
}

// ============================================
// SERVICE WORKER REGISTRATION (PWA)
// ============================================

function registerServiceWorker() {
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('sw.js')
                .then(function(registration) {
                    // SW terdaftar
                })
                .catch(function(error) {
                    // Gagal mendaftarkan SW
                });
        });
    }
}

// ============================================
// MODAL DETAIL TRANSAKSI
// ============================================

// Fungsi untuk menampilkan modal detail transaksi
function showDetail(orderId) {
    var modal = document.getElementById('orderModal');
    var modalBody = document.getElementById('modalBody');

    if (!modal || !modalBody) return;

    modalBody.innerHTML = '<p>Memuat detail transaksi...</p>';
    modal.classList.add('show');

    fetch('order_detail.php?id=' + orderId)
        .then(function(response) {
            return response.text();
        })
        .then(function(data) {
            modalBody.innerHTML = data;
        })
        .catch(function(error) {
            modalBody.innerHTML = '<p class="alert alert-danger">Gagal memuat detail transaksi.</p>';
        });
}

// Fungsi untuk menutup modal
function closeModal() {
    var modal = document.getElementById('orderModal');
    if (modal) {
        modal.classList.remove('show');
    }
}

// Tutup modal saat klik di luar modal
document.addEventListener('click', function(event) {
    var modal = document.getElementById('orderModal');
    if (modal && modal.classList.contains('show')) {
        if (event.target === modal) {
            closeModal();
        }
    }
});

// Tutup modal saat tekan tombol ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal();
    }
});

// ============================================
// INISIALISASI
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    initNavbarToggle();
    injectMobileBanner();
    registerServiceWorker();
});
