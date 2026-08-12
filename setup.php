<?php
// ============================================
// SETUP ADMIN
// Jalankan file ini sekali setelah import database.sql
// URL: http://localhost/sparepart-usk/setup.php
// ============================================

require_once 'config/database.php';

// Cek apakah admin sudah ada
$check = $conn->query("SELECT id FROM users WHERE role = 'admin' LIMIT 1");

if ($check->num_rows > 0) {
    echo "Admin sudah ada. Tidak perlu setup ulang.";
    echo "<br><a href='auth/login.php'>Kembali ke Login</a>";
    exit();
}

// Data admin default
$nama = 'Administrator';
$email = 'admin@sparepart.com';
$password = 'admin123';
$role = 'admin';

// Hash password menggunakan password_hash()
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Simpan admin ke database menggunakan prepared statement
$stmt = $conn->prepare("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nama, $email, $hashed_password, $role);

if ($stmt->execute()) {
    echo "✅ Setup berhasil!";
    echo "<br>Email: admin@sparepart.com";
    echo "<br>Password: admin123";
    echo "<br><a href='auth/login.php'>Silakan Login</a>";
} else {
    echo "❌ Setup gagal: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>