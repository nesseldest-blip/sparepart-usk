<?php
// ============================================
// TAMBAH PRODUK (ADMIN)
// Form untuk menambah sparepart baru
// ============================================

require_once '../config/database.php';
requireAdmin(); // Proteksi: hanya admin yang bisa akses

$error = '';
$success = '';

// Proses tambah produk saat form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_produk = cleanInput($_POST['nama_produk']);
    $kategori = cleanInput($_POST['kategori']);
    $harga = cleanInput($_POST['harga']);
    $stok = cleanInput($_POST['stok']);
    $deskripsi = cleanInput($_POST['deskripsi']);

    // Validasi input
    if (empty($nama_produk) || empty($kategori) || empty($harga) || empty($stok)) {
        $error = 'Semua field wajib diisi!';
    } elseif (!is_numeric($harga) || $harga <= 0) {
        $error = 'Harga harus berupa angka positif!';
    } elseif (!is_numeric($stok) || $stok < 0) {
        $error = 'Stok harus berupa angka!';
    } else {
        // Proses upload gambar
        $gambar = '';
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
            $target_dir = '../uploads/';
            $file_name = time() . '_' . basename($_FILES['gambar']['name']);
            $target_file = $target_dir . $file_name;
            $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Validasi tipe file gambar
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (!in_array($file_type, $allowed_types)) {
                $error = 'Format gambar harus JPG, JPEG, PNG, GIF, atau WEBP!';
            } elseif ($_FILES['gambar']['size'] > 2000000) {
                $error = 'Ukuran gambar maksimal 2MB!';
            } else {
                // Upload file
                if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
                    $gambar = $file_name;
                } else {
                    $error = 'Gagal mengupload gambar!';
                }
            }
        }

        // Jika tidak ada error, simpan ke database
        if (empty($error)) {
            $stmt = $conn->prepare("INSERT INTO products (nama_produk, kategori, harga, stok, gambar, deskripsi) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdiss", $nama_produk, $kategori, $harga, $stok, $gambar, $deskripsi);

            if ($stmt->execute()) {
                setFlash('success', 'Produk berhasil ditambahkan!');
                header('Location: products.php');
                exit();
            } else {
                $error = 'Gagal menyimpan produk: ' . $stmt->error;
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - Sparepart USK</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">🔧</div>
                <h2>Sparepart USK</h2>
                <p>Admin Panel</p>
            </div>
            <nav class="sidebar-nav">
                <a href="dashboard.php">📊 Dashboard</a>
                <a href="products.php" class="active">🔩 Kelola Produk</a>
                <a href="orders.php">📦 Transaksi</a>
                <a href="../auth/logout.php">🚪 Logout</a>
            </nav>
            <div class="sidebar-footer">
                <p>Halo, <?php echo $_SESSION['nama']; ?></p>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="topbar">
                <h1>Tambah Produk</h1>
                <div class="topbar-user">
                    <span><?php echo $_SESSION['nama']; ?></span>
                    <a href="../auth/logout.php" class="btn btn-danger btn-sm">Logout</a>
                </div>
            </header>

            <div class="content">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header">
                        <h2>Form Tambah Sparepart</h2>
                        <a href="products.php" class="btn btn-secondary btn-sm">← Kembali</a>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="nama_produk">Nama Produk</label>
                                <input type="text" id="nama_produk" name="nama_produk" class="form-control" placeholder="Contoh: Oli Mesin 1L" required>
                            </div>

                            <div class="form-group">
                                <label for="kategori">Kategori</label>
                                <select id="kategori" name="kategori" class="form-control" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="Oli & Pelumas">Oli & Pelumas</option>
                                    <option value="Filter">Filter</option>
                                    <option value="Mesin">Mesin</option>
                                    <option value="Rem">Rem</option>
                                    <option value="Kelistrikan">Kelistrikan</option>
                                    <option value="Ban">Ban</option>
                                    <option value="Suspensi">Suspensi</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="harga">Harga (Rp)</label>
                                    <input type="number" id="harga" name="harga" class="form-control" placeholder="Contoh: 85000" min="0" required>
                                </div>

                                <div class="form-group">
                                    <label for="stok">Stok</label>
                                    <input type="number" id="stok" name="stok" class="form-control" placeholder="Contoh: 50" min="0" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="gambar">Gambar Produk</label>
                                <input type="file" id="gambar" name="gambar" class="form-control" accept="image/*">
                                <small class="form-text">Format: JPG, JPEG, PNG, GIF, WEBP. Maksimal 2MB.</small>
                            </div>

                            <div class="form-group">
                                <label for="deskripsi">Deskripsi</label>
                                <textarea id="deskripsi" name="deskripsi" class="form-control" rows="4" placeholder="Masukkan deskripsi produk"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Simpan Produk</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <!-- JavaScript -->
    <script src="../assets/js/script.js"></script>
</body>
</html>