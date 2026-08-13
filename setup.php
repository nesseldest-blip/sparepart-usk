<?php
// ============================================
// SETUP ADMIN
// Jalankan file ini SEKALI setelah import database.sql
// URL: https://namadomainanda.com/setup.php
// ============================================
// PENTING: Setelah admin berhasil dibuat, HAPUS file ini!
// ============================================

require_once 'config/database.php';

// Cek apakah admin sudah ada
$check = $conn->query("SELECT id FROM users WHERE role = 'admin' LIMIT 1");

if ($check->num_rows > 0) {
    echo "<!DOCTYPE html>";
    echo "<html lang='id'><head><meta charset='UTF-8'>";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    echo "<title>Setup Selesai - Sparepart USK</title>";
    echo "<style>";
    echo "body{font-family:Arial,sans-serif;max-width:600px;margin:50px auto;padding:20px;text-align:center;}";
    echo ".box{background:#f8f9fa;border:1px solid #dee2e6;border-radius:10px;padding:30px;}";
    echo ".btn{display:inline-block;padding:12px 24px;background:#e63946;color:#fff;text-decoration:none;border-radius:5px;margin:10px;}";
    echo ".btn:hover{background:#c1121f;}";
    echo "</style></head><body>";
    echo "<div class='box'>";
    echo "<h2>Setup Sudah Selesai</h2>";
    echo "<p>Admin sudah ada. Tidak perlu setup ulang.</p>";
    echo "<p><strong>HAPUS file setup.php dari server untuk keamanan!</strong></p>";
    echo "<a href='auth/login.php' class='btn'>Login Sekarang</a>";
    echo "</div></body></html>";
    exit();
}

$error = '';
$success = '';

// Proses setup saat form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = cleanInput($_POST['nama']);
    $email = cleanInput($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    // Validasi input
    if (empty($nama) || empty($email) || empty($password)) {
        $error = 'Semua field wajib diisi!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid!';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter!';
    } elseif ($password !== $password_confirm) {
        $error = 'Konfirmasi password tidak cocok!';
    } else {
        // Cek apakah email sudah digunakan
        $check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check_email->bind_param("s", $email);
        $check_email->execute();
        if ($check_email->get_result()->num_rows > 0) {
            $error = 'Email sudah digunakan!';
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $role = 'admin';

            // Simpan admin ke database
            $stmt = $conn->prepare("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nama, $email, $hashed_password, $role);

            if ($stmt->execute()) {
                $success = true;
            } else {
                $error = 'Gagal menyimpan admin: ' . $stmt->error;
            }
            $stmt->close();
        }
        $check_email->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Admin - Sparepart USK</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 500px; margin: 50px auto; }
        .card { background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden; }
        .card-header { background: #e63946; color: #fff; padding: 20px; text-align: center; }
        .card-body { padding: 30px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
        .form-group input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; }
        .form-group input:focus { outline: none; border-color: #e63946; }
        .btn { display: block; width: 100%; padding: 12px; background: #e63946; color: #fff; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; }
        .btn:hover { background: #c1121f; }
        .alert { padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .warning { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Setup Admin</h1>
                <p>Sparepart USK</p>
            </div>
            <div class="card-body">
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <strong>Setup Berhasil!</strong><br>
                        Admin berhasil dibuat. Anda bisa login sekarang.
                        <br><br>
                        <strong>PENTING:</strong> Hapus file <code>setup.php</code> dari server untuk keamanan!
                        <br><br>
                        <a href="auth/login.php" style="color:#155724;font-weight:bold;">Login Sekarang &rarr;</a>
                    </div>
                <?php else: ?>
                    <div class="warning">
                        <strong>Penting:</strong> Jalankan setup ini hanya sekali. Setelah admin dibuat, hapus file ini dari server.
                    </div>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="nama">Nama Lengkap</label>
                            <input type="text" id="nama" name="nama" placeholder="Administrator" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" placeholder="admin@namadomain.com" required>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" placeholder="Minimal 6 karakter" minlength="6" required>
                        </div>

                        <div class="form-group">
                            <label for="password_confirm">Konfirmasi Password</label>
                            <input type="password" id="password_confirm" name="password_confirm" placeholder="Ulangi password" minlength="6" required>
                        </div>

                        <button type="submit" class="btn">Buat Admin &amp; Setup</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>
