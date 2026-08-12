<?php
// ============================================
// DAFTAR PRODUK (CUSTOMER)
// Menampilkan semua sparepart dengan filter
// ============================================

require_once '../config/database.php';
requireLogin(); // Proteksi: harus login

// Ambil parameter filter
$search = isset($_GET['search']) ? cleanInput($_GET['search']) : '';
$kategori = isset($_GET['kategori']) ? cleanInput($_GET['kategori']) : '';

// Query dengan filter
$query = "SELECT * FROM products WHERE 1=1";
$params = [];
$types = '';

if ($search != '') {
    $query .= " AND (nama_produk LIKE ? OR deskripsi LIKE ?)";
    $search_like = "%$search%";
    $params[] = $search_like;
    $params[] = $search_like;
    $types .= "ss";
}

if ($kategori != '') {
    $query .= " AND kategori = ?";
    $params[] = $kategori;
    $types .= "s";
}

$query .= " ORDER BY created_at DESC";

// Execute query dengan prepared statement
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$products = $stmt->get_result();

// Ambil semua kategori untuk filter
$categories = $conn->query("SELECT DISTINCT kategori FROM products ORDER BY kategori");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk - Sparepart USK</title>
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
                    <input type="text" name="search" placeholder="Cari sparepart..." class="search-input" value="<?php echo $search; ?>">
                    <button type="submit" class="search-btn">🔍</button>
                </form>
            </div>
            <div class="navbar-menu">
                <a href="dashboard.php">Beranda</a>
                <a href="products.php" class="active">Produk</a>
                <a href="orders.php">Transaksi</a>
                <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
            <div class="navbar-user">
                <span>👤 <?php echo $_SESSION['nama']; ?></span>
            </div>
        </div>
    </nav>

    <!-- Konten -->
    <div class="page-container">
        <div class="page-header">
            <h1>Daftar Sparepart</h1>
            <p>Silakan pilih sparepart yang Anda butuhkan</p>
        </div>

        <!-- Filter Produk -->
        <div class="filter-bar">
            <div class="filter-group">
                <span class="filter-label">Kategori:</span>
                <a href="products.php" class="filter-btn <?php echo $kategori == '' ? 'active' : ''; ?>">Semua</a>
                <?php while ($cat = $categories->fetch_assoc()): ?>
                    <a href="products.php?kategori=<?php echo urlencode($cat['kategori']); ?>" class="filter-btn <?php echo $kategori == $cat['kategori'] ? 'active' : ''; ?>">
                        <?php echo $cat['kategori']; ?>
                    </a>
                <?php endwhile; ?>
            </div>
        </div>

        <!-- Grid Produk -->
        <div class="product-grid">
            <?php if ($products->num_rows > 0): ?>
                <?php while ($product = $products->fetch_assoc()): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <?php if ($product['gambar'] && file_exists('../uploads/' . $product['gambar'])): ?>
                                <img src="../uploads/<?php echo $product['gambar']; ?>" alt="<?php echo $product['nama_produk']; ?>">
                            <?php else: ?>
                                <div class="product-image-placeholder">🔧</div>
                            <?php endif; ?>
                            <span class="product-category"><?php echo $product['kategori']; ?></span>
                        </div>
                        <div class="product-info">
                            <h3><?php echo $product['nama_produk']; ?></h3>
                            <p class="product-price"><?php echo formatRupiah($product['harga']); ?></p>
                            <p class="product-stock <?php echo $product['stok'] > 0 ? 'in-stock' : 'out-stock'; ?>">
                                <?php echo $product['stok'] > 0 ? 'Stok: ' . $product['stok'] : 'Stok Habis'; ?>
                            </p>
                            <div class="product-actions">
                                <a href="product_detail.php?id=<?php echo $product['id']; ?>" class="btn btn-secondary btn-sm">Detail</a>
                                <?php if ($product['stok'] > 0): ?>
                                    <a href="product_detail.php?id=<?php echo $product['id']; ?>" class="btn btn-primary btn-sm">Beli</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state">
                    <p>🔍 Produk tidak ditemukan.</p>
                    <a href="products.php" class="btn btn-primary">Lihat Semua Produk</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2026 Sparepart USK. Tugas USK Rekayasa Perangkat Lunak.</p>
    </footer>
</body>
</html>