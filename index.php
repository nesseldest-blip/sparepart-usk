<?php
// ============================================
// HALAMAN UTAMA (Landing Page)
// Menampilkan produk untuk pengunjung
// ============================================

require_once 'config/database.php';

// Ambil produk terbaru (8 produk)
$latest_products = $conn->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 8");

// Ambil semua kategori
$categories = $conn->query("SELECT DISTINCT kategori FROM products ORDER BY kategori");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sparepart USK - Toko Sparepart Otomotif</title>
    <meta name="description" content="Toko sparepart otomotif online">
    <meta name="theme-color" content="#e63946">
    <link rel="manifest" href="manifest.json">
    <link rel="apple-touch-icon" href="assets/images/icon-192.png">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="index.php" class="navbar-brand">
                <span class="brand-icon">🔧</span>
                <span class="brand-text">Sparepart <strong>USK</strong></span>
            </a>
            <div class="navbar-search">
                <form action="customer/products.php" method="GET">
                    <input type="text" name="search" placeholder="Cari sparepart..." class="search-input">
                    <button type="submit" class="search-btn">🔍</button>
                </form>
            </div>
            <div class="navbar-menu" id="navbarMenu">
                <a href="index.php" class="active">Beranda</a>
                <a href="auth/login.php">Login</a>
                <a href="auth/register.php" class="btn btn-primary btn-sm">Daftar</a>
            </div>
            <button class="navbar-toggle" id="navbarToggle">☰</button>
        </div>
    </nav>

    <!-- Banner -->
    <section class="hero-section">
        <div class="hero-content">
            <h1>Sparepart Berkualitas<br>Untuk Kendaraan Anda</h1>
            <p>Temukan berbagai sparepart otomotif dengan harga terbaik</p>
            <a href="auth/register.php" class="btn btn-primary">Belanja Sekarang</a>
        </div>
    </section>

    <!-- Mobile App Download Section -->
    <section class="app-download-section">
        <div class="app-download-content">
            <h2>Unduh Aplikasi Mobile <strong>Sparepart USK</strong></h2>
            <p>Dapatkan pengalaman berbelanja yang lebih cepat dan nyaman dengan aplikasi mobile kami di ponsel Anda</p>
            <div class="app-download-buttons">
                <a href="apk/sparepart-usk.apk" class="btn btn-primary" id="downloadApkBtn" download="SparepartUSK.apk">
                    ⬇️ Download APK
                </a>
                <a href="#" class="btn btn-secondary" id="openBrowserBtn" onclick="openInBrowser(); return false;">
                    🌐 Buka di Browser
                </a>
            </div>
            <div class="app-feature-list">
                <div class="app-feature">⚡ Cepat</div>
                <div class="app-feature">📱 Mudah Digunakan</div>
                <div class="app-feature">💾 Ringan</div>
                <div class="app-feature">🔄 Update Real-time</div>
            </div>
        </div>
    </section>

    <!-- Kategori -->
    <section class="section">
        <div class="section-header">
            <h2>Kategori Sparepart</h2>
        </div>
        <div class="category-grid">
            <?php while ($cat = $categories->fetch_assoc()): ?>
                <a href="auth/login.php" class="category-card">
                    <div class="category-icon">🔩</div>
                    <h3><?php echo $cat['kategori']; ?></h3>
                </a>
            <?php endwhile; ?>
        </div>
    </section>

    <!-- Produk Terbaru -->
    <section class="section">
        <div class="section-header">
            <h2>Produk Terbaru</h2>
            <a href="auth/login.php" class="btn btn-secondary btn-sm">Lihat Semua</a>
        </div>
        <div class="product-grid">
            <?php while ($product = $latest_products->fetch_assoc()): ?>
                <div class="product-card">
                    <div class="product-image">
                        <?php if ($product['gambar'] && file_exists('uploads/' . $product['gambar'])): ?>
                            <img src="uploads/<?php echo $product['gambar']; ?>" alt="<?php echo $product['nama_produk']; ?>">
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
                            <a href="auth/login.php" class="btn btn-primary btn-sm">Beli</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2026 Sparepart USK. Tugas USK Rekayasa Perangkat Lunak.</p>
    </footer>

    <!-- JavaScript -->
    <script src="assets/js/script.js"></script>
</body>
</html>
