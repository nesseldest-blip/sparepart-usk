<?php
// ============================================
// KONFIGURASI DATABASE - TEMPLATE
// ============================================
// 
// 1. COPY FILE INI MENJADI: config/database.php
// 2. UNTUK DEVELOPMENT (LOCAL/XAMPP): gunakan konfigurasi di bawah
// 3. UNTUK PRODUCTION (HOSTING): ganti ke konfigurasi hosting Anda
// 4. ATAU gunakan environment variable di panel hosting
//    - DB_HOST, DB_NAME, DB_USER, DB_PASS
//
// ============================================

// --- KONFIGURASI DEVELOPMENT (LOCAL/XAMPP) ---
// Default XAMPP: host=localhost, user=root, pass=kosong
define('DB_HOST_DEV', 'localhost');
define('DB_NAME_DEV', 'sparepart_usk');
define('DB_USER_DEV', 'root');
define('DB_PASS_DEV', '');

// --- KONFIGURASI PRODUCTION (HOSTING) ---
// Ganti dengan konfigurasi hosting Anda, misalnya:
// define('DB_HOST_PROD', 'mysql:3306');  // Atau IP/hostname hosting
// define('DB_NAME_PROD', 'namadb_anda');
// define('DB_USER_PROD', 'user_anda');
// define('DB_PASS_PROD', 'pass_anda');

// Gunakan environment variable jika tersedia (hosting),
// jika tidak gunakan konfigurasi development (localhost)
$host = getenv('DB_HOST') ?: DB_HOST_DEV;
$user = getenv('DB_USER') ?: DB_USER_DEV;
$pass = getenv('DB_PASS') ?: DB_PASS_DEV;
$dbname = getenv('DB_NAME') ?: DB_NAME_DEV;

// Membuat koneksi ke MySQL
$conn = new mysqli($host, $user, $pass, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Set charset agar mendukung karakter Indonesia
$conn->set_charset("utf8mb4");

// Mulai session jika belum dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// ============================================
// FUNGSI BANTUAN (Helper Functions)
// ============================================

// Fungsi untuk membersihkan input dari user
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Fungsi untuk format harga Rupiah
function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

// Fungsi untuk cek apakah user sudah login
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Fungsi untuk cek apakah user adalah admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] == 'admin';
}

// Fungsi untuk proteksi halaman admin
function requireAdmin() {
    if (!isLoggedIn() || !isAdmin()) {
        header('Location: ../auth/login.php');
        exit();
    }
}

// Fungsi untuk proteksi halaman customer
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ../auth/login.php');
        exit();
    }
}

// Fungsi untuk menampilkan pesan flash
function setFlash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

// Fungsi untuk menampilkan dan menghapus pesan flash
function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}
?>
