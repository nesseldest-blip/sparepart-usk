<?php
// ============================================
// KONFIRMASI TRANSAKSI (CUSTOMER)
// Menampilkan ringkasan transaksi setelah checkout
// ============================================

require_once '../config/database.php';
requireLogin(); // Proteksi: harus login

// Ambil nomor transaksi dari URL
$order_number = isset($_GET['order']) ? cleanInput($_GET['order']) : '';
$user_id = $_SESSION['user_id'];

// Ambil data transaksi milik user yang login
$stmt = $conn->prepare("SELECT * FROM orders WHERE order_number = ? AND user_id = ?");
$stmt->bind_param("si", $order_number, $user_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

// Jika transaksi tidak ditemukan, redirect ke halaman transaksi
if (!$order) {
    header('Location: orders.php');
    exit();
}

// Ambil detail produk dari transaksi
$details = $conn->prepare("
    SELECT od.*, p.nama_produk, p.gambar 
    FROM order_details od 
    JOIN products p ON od.product_id = p.id 
    WHERE od.order_id = ?
");
$details->bind_param("i", $order['id']);
$details->execute();
$detail_result = $details->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Transaksi - Sparepart USK</title>
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
        <!-- Pesan Sukses -->
        <div class="alert alert-success">
            ✅ Transaksi berhasil! Terima kasih telah berbelanja.
        </div>

        <div class="konfirmasi-layout">
            <!-- Ringkasan Transaksi -->
            <div class="card">
                <div class="card-header">
                    <h2>Ringkasan Transaksi</h2>
                </div>
                <div class="card-body">
                    <div class="confirm-info">
                        <div class="confirm-row">
                            <span>Nomor Transaksi</span>
                            <strong><?php echo $order['order_number']; ?></strong>
                        </div>
                        <div class="confirm-row">
                            <span>Tanggal</span>
                            <strong><?php echo date('d M Y H:i', strtotime($order['created_at'])); ?></strong>
                        </div>
                        <div class="confirm-row">
                            <span>Status</span>
                            <span class="badge badge-<?php echo $order['status']; ?>">
                                <?php echo ucfirst($order['status']); ?>
                            </span>
                        </div>
                    </div>

                    <h3 class="section-title">Data Customer</h3>
                    <div class="confirm-info">
                        <div class="confirm-row">
                            <span>Nama</span>
                            <strong><?php echo $_SESSION['nama']; ?></strong>
                        </div>
                        <div class="confirm-row">
                            <span>Alamat Pengiriman</span>
                            <strong><?php echo $order['alamat']; ?></strong>
                        </div>
                    </div>

                    <h3 class="section-title">Detail Produk</h3>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($item = $detail_result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $item['nama_produk']; ?></td>
                                        <td><?php echo formatRupiah($item['harga']); ?></td>
                                        <td><?php echo $item['jumlah']; ?></td>
                                        <td><?php echo formatRupiah($item['subtotal']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-right"><strong>Total Pembayaran:</strong></td>
                                    <td><strong class="total-price"><?php echo formatRupiah($order['total']); ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="confirm-actions">
                        <a href="orders.php" class="btn btn-primary">Lihat Semua Transaksi</a>
                        <a href="products.php" class="btn btn-secondary">Belanja Lagi</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2026 Sparepart USK. Tugas USK Rekayasa Perangkat Lunak.</p>
    </footer>
</body>
</html>