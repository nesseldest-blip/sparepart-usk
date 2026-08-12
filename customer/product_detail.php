<?php
// ============================================
// DETAIL PRODUK (CUSTOMER)
// Menampilkan detail sparepart dengan pembelian
// ============================================

require_once '../config/database.php';
requireLogin(); // Proteksi: harus login

// Ambil ID produk dari URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ambil data produk
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Jika produk tidak ditemukan, redirect ke daftar produk
if ($result->num_rows == 0) {
    header('Location: products.php');
    exit();
}

$product = $result->fetch_assoc();

// Proses "Tambah ke Pembelian"
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $jumlah = (int)$_POST['jumlah'];

    // Validasi jumlah
    if ($jumlah <= 0) {
        setFlash('danger', 'Jumlah pembelian harus minimal 1!');
    } elseif ($jumlah > $product['stok']) {
        setFlash('danger', 'Jumlah melebihi stok yang tersedia!');
    } else {
        // Simpan ke session cart
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Jika produk sudah ada di cart, tambah jumlahnya
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] += $jumlah;
        } else {
            $_SESSION['cart'][$id] = $jumlah;
        }

        setFlash('success', 'Produk berhasil ditambahkan ke pembelian!');
        header('Location: checkout.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk - Sparepart USK</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Navbar Customer -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="dashboard.php" class="navbar-brand">
                <span class="brand-icon">🔧</span>
                <span class="brand-text">Sparepart <strong>USK</strong></span>
            </a>
            <div class="navbar-search">
                <form action="products.php" method="GET">
                    <input type="text" name="search" placeholder="Cari sparepart..." class="search-input">
                    <button type="submit" class="search-btn">🔍</button>
                </form>
            </div>
            <div class="navbar-menu" id="navbarMenu">
                <a href="dashboard.php">Beranda</a>
                <a href="products.php">Produk</a>
                <a href="orders.php">Transaksi</a>
                <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
            <button class="navbar-toggle" id="navbarToggle">☰</button>
            <div class="navbar-user">
                <span>👤 <?php echo $_SESSION['nama']; ?></span>
            </div>
        </div>
    </nav>

    <!-- Konten -->
    <div class="page-container">
        <?php $flash = getFlash(); ?>
        <?php if ($flash): ?>
            <div class="alert alert-<?php echo $flash['type']; ?>"><?php echo $flash['message']; ?></div>
        <?php endif; ?>

        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="products.php">Produk</a> / <span><?php echo $product['nama_produk']; ?></span>
        </div>

        <div class="detail-layout">
            <!-- Foto Produk -->
            <div class="detail-image">
                <?php if ($product['gambar'] && file_exists('../uploads/' . $product['gambar'])): ?>
                    <img src="../uploads/<?php echo $product['gambar']; ?>" alt="<?php echo $product['nama_produk']; ?>">
                <?php else: ?>
                    <div class="detail-image-placeholder">🔧</div>
                <?php endif; ?>
            </div>

            <!-- Info Produk -->
            <div class="detail-info">
                <span class="badge badge-info"><?php echo $product['kategori']; ?></span>
                <h1><?php echo $product['nama_produk']; ?></h1>
                <p class="detail-price"><?php echo formatRupiah($product['harga']); ?></p>

                <div class="detail-stock">
                    <?php if ($product['stok'] > 0): ?>
                        <span class="badge badge-success">Stok Tersedia: <?php echo $product['stok']; ?></span>
                    <?php else: ?>
                        <span class="badge badge-danger">Stok Habis</span>
                    <?php endif; ?>
                </div>

                <div class="detail-description">
                    <h3>Deskripsi</h3>
                    <p><?php echo nl2br($product['deskripsi']); ?></p>
                </div>

                <!-- Form Pembelian -->
                <?php if ($product['stok'] > 0): ?>
                    <form method="POST" action="" class="buy-form">
                        <div class="form-group">
                            <label for="jumlah">Jumlah Pembelian</label>
                            <input type="number" id="jumlah" name="jumlah" class="form-control" value="1" min="1" max="<?php echo $product['stok']; ?>" required>
                            <small class="form-text">Stok maksimal yang tersedia: <?php echo $product['stok']; ?></small>
                        </div>
                        <button type="submit" name="add_to_cart" class="btn btn-primary btn-lg">Tambah ke Pembelian</button>
                        <a href="products.php" class="btn btn-secondary btn-lg">← Kembali</a>
                    </form>
                <?php else: ?>
                    <p class="alert alert-danger">Maaf, produk ini sedang habis stok.</p>
                    <a href="products.php" class="btn btn-secondary">← Kembali</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2026 Sparepart USK. Tugas USK Rekayasa Perangkat Lunak.</p>
    </footer>

    <!-- JavaScript -->
    <script src="../assets/js/script.js"></script>
</body>
</html>