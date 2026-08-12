<?php
// ============================================
// TRANSAKSI CUSTOMER
// Menampilkan riwayat transaksi dan status
// ============================================

require_once '../config/database.php';
requireLogin(); // Proteksi: harus login

$user_id = $_SESSION['user_id'];

// Ambil semua transaksi milik user yang login
$orders = $conn->prepare("
    SELECT o.*, 
           (SELECT COUNT(*) FROM order_details od WHERE od.order_id = o.id) as total_items
    FROM orders o 
    WHERE o.user_id = ? 
    ORDER BY o.created_at DESC
");
$orders->bind_param("i", $user_id);
$orders->execute();
$orders_result = $orders->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Saya - Sparepart USK</title>
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
            <div class="navbar-menu">
                <a href="dashboard.php">Beranda</a>
                <a href="products.php">Produk</a>
                <a href="orders.php" class="active">Transaksi</a>
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
            <h1>Riwayat Transaksi</h1>
            <p>Pantau status pesanan Anda di sini</p>
        </div>

        <?php if ($orders_result->num_rows > 0): ?>
            <div class="card">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No. Transaksi</th>
                                <th>Tanggal</th>
                                <th>Jumlah Item</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($order = $orders_result->fetch_assoc()): ?>
                                <tr>
                                    <td><strong><?php echo $order['order_number']; ?></strong></td>
                                    <td><?php echo date('d M Y H:i', strtotime($order['created_at'])); ?></td>
                                    <td><?php echo $order['total_items']; ?> item</td>
                                    <td><strong><?php echo formatRupiah($order['total']); ?></strong></td>
                                    <td>
                                        <span class="badge badge-<?php echo $order['status']; ?>">
                                            <?php echo ucfirst($order['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="konfirmasi.php?order=<?php echo $order['order_number']; ?>" class="btn btn-info btn-sm">Detail</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <p>📦 Anda belum memiliki transaksi.</p>
                <a href="products.php" class="btn btn-primary">Belanja Sekarang</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2026 Sparepart USK. Tugas USK Rekayasa Perangkat Lunak.</p>
    </footer>
</body>
</html>