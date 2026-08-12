<?php
// ============================================
// KELOLA PRODUK (ADMIN)
// Menampilkan daftar sparepart
// ============================================

require_once '../config/database.php';
requireAdmin(); // Proteksi: hanya admin yang bisa akses

// Proses hapus produk
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    // Ambil nama gambar untuk dihapus
    $get_img = $conn->prepare("SELECT gambar FROM products WHERE id = ?");
    $get_img->bind_param("i", $id);
    $get_img->execute();
    $img_result = $get_img->get_result();

    if ($img_result->num_rows > 0) {
        $img = $img_result->fetch_assoc();
        // Hapus file gambar jika ada
        if ($img['gambar'] && file_exists('../uploads/' . $img['gambar'])) {
            unlink('../uploads/' . $img['gambar']);
        }
    }

    // Hapus produk dari database
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        setFlash('success', 'Produk berhasil dihapus!');
    } else {
        setFlash('danger', 'Gagal menghapus produk!');
    }
    $stmt->close();
    header('Location: products.php');
    exit();
}

// Ambil semua produk
$products = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - Sparepart USK</title>
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
                <h1>Kelola Produk</h1>
                <div class="topbar-user">
                    <span><?php echo $_SESSION['nama']; ?></span>
                    <a href="../auth/logout.php" class="btn btn-danger btn-sm">Logout</a>
                </div>
            </header>

            <div class="content">
                <?php $flash = getFlash(); ?>
                <?php if ($flash): ?>
                    <div class="alert alert-<?php echo $flash['type']; ?>"><?php echo $flash['message']; ?></div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header">
                        <h2>Daftar Sparepart</h2>
                        <a href="product_add.php" class="btn btn-primary btn-sm">+ Tambah Produk</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Gambar</th>
                                    <th>Nama Produk</th>
                                    <th>Kategori</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($products->num_rows > 0): ?>
                                    <?php while ($product = $products->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo $product['id']; ?></td>
                                            <td>
                                                <?php if ($product['gambar'] && file_exists('../uploads/' . $product['gambar'])): ?>
                                                    <img src="../uploads/<?php echo $product['gambar']; ?>" alt="<?php echo $product['nama_produk']; ?>" class="table-img">
                                                <?php else: ?>
                                                    <div class="table-img-placeholder">🔧</div>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo $product['nama_produk']; ?></td>
                                            <td><?php echo $product['kategori']; ?></td>
                                            <td><?php echo formatRupiah($product['harga']); ?></td>
                                            <td>
                                                <span class="badge <?php echo $product['stok'] > 0 ? 'badge-success' : 'badge-danger'; ?>">
                                                    <?php echo $product['stok']; ?>
                                                </span>
                                            </td>
                                            <td class="action-btns">
                                                <a href="product_edit.php?id=<?php echo $product['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                <a href="products.php?delete=<?php echo $product['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada produk</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>