<?php
// ============================================
// HALAMAN LOGIN
// Digunakan untuk login Admin dan Customer
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

// Proses login saat form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = cleanInput($_POST['email']);
    $password = $_POST['password'];

    // Validasi input tidak kosong
    if (empty($email) || empty($password)) {
        $error = 'Email dan password wajib diisi!';
    } else {
        // Query user berdasarkan email menggunakan prepared statement
        $stmt = $conn->prepare("SELECT id, nama, email, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verifikasi password menggunakan password_verify()
            if (password_verify($password, $user['password'])) {
                // Set session login
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nama'] = $user['nama'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                // Redirect sesuai role
                if ($user['role'] == 'admin') {
                    header('Location: ../admin/dashboard.php');
                } else {
                    header('Location: ../customer/dashboard.php');
                }
                exit();
            } else {
                $error = 'Password salah!';
            }
        } else {
            $error = 'Email tidak terdaftar!';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sparepart USK</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="auth-body">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">🔧</div>
                <h1>Sparepart USK</h1>
                <p>Silakan login untuk melanjutkan</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Masukkan email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>

            <div class="auth-footer">
                <p>Belum punya akun? <a href="register.php">Daftar Sekarang</a></p>
                <p><a href="../index.php">← Kembali ke Beranda</a></p>
            </div>
        </div>
    </div>
</body>
</html>