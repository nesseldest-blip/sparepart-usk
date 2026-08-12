<?php
// ============================================
// HALAMAN REGISTER
// Digunakan untuk Customer membuat akun baru
// ============================================

require_once '../config/database.php';

// Jika sudah login, redirect ke halaman sesuai role
if (isLoggedIn()) {
    if (isAdmin()) {
        header('Location: ../admin/dashboard.php');
    } else {
        header('Location: ../customer/dashboard.php');
    }
    exit();
}

$error = '';
$success = '';

// Proses register saat form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = cleanInput($_POST['nama']);
    $email = cleanInput($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $no_hp = cleanInput($_POST['no_hp']);
    $alamat = cleanInput($_POST['alamat']);

    // Validasi input
    if (empty($nama) || empty($email) || empty($password) || empty($no_hp) || empty($alamat)) {
        $error = 'Semua field wajib diisi!';
    } elseif ($password !== $confirm_password) {
        $error = 'Password dan konfirmasi password tidak sama!';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid!';
    } else {
        // Cek apakah email sudah terdaftar
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = 'Email sudah terdaftar!';
        } else {
            // Hash password menggunakan password_hash()
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $role = 'customer';

            // Simpan user baru menggunakan prepared statement
            $stmt = $conn->prepare("INSERT INTO users (nama, email, password, role, no_hp, alamat) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $nama, $email, $hashed_password, $role, $no_hp, $alamat);

            if ($stmt->execute()) {
                $success = 'Registrasi berhasil! Silakan login.';
            } else {
                $error = 'Registrasi gagal: ' . $stmt->error;
            }
            $stmt->close();
        }
        $check->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sparepart USK</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="auth-body">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">🔧</div>
                <h1>Daftar Akun</h1>
                <p>Buat akun untuk mulai berbelanja</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Masukkan email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Ulangi password" required>
                </div>

                <div class="form-group">
                    <label for="no_hp">Nomor HP</label>
                    <input type="text" id="no_hp" name="no_hp" class="form-control" placeholder="Contoh: 081234567890" required>
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea id="alamat" name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Daftar</button>
            </form>

            <div class="auth-footer">
                <p>Sudah punya akun? <a href="login.php">Login Sekarang</a></p>
                <p><a href="../index.php">← Kembali ke Beranda</a></p>
            </div>
        </div>
    </div>
</body>
</html>