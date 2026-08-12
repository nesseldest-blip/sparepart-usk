<?php
// ============================================
// DASHBOARD ADMIN
// Menampilkan statistik toko
// ============================================

require_once '../config/database.php';
requireAdmin(); // Proteksi: hanya admin yang bisa akses

// Ambil statistik dari database
$total_products = $conn->query("SELECT COUNT(*) as total FROM products")->fetch_assoc()['total'];
$total_customers = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'customer'")->fetch_assoc()['total'];
$total_orders = $conn->query("SELECT COUNT(*) as total FROM orders")->fetch_assoc()['total'];
$pending_orders = $conn->query("SELECT COUNT(*) as total FROM orders WHERE status = 'menunggu'")->fetch_assoc()['total'];

// Ambil 5 transaksi terbaru
$recent_orders = $conn->query("
    SELECT o.*, u.nama as customer_name 
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
    ORDER BY o.created_at DESC 
    LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sparepart USK</title>
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
                <a href="dashboard.php" class="active">📊 Dashboard</a>
                <a href="products.php">🔩 Kelola Produk</a>
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
                <h1>Dashboard</h1>
                <div class="topbar-user">
                    <span><?php echo $_SESSION['nama']; ?></span>
                    <a href="../auth/logout.php" class="btn btn-danger btn-sm">Logout</a>
                </div>
            </header>

            <div class="content">
                <!-- Statistik Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">🔩</div>
                        <div class="stat-info">
                            <h3><?php echo $total_products; ?></h3>
                            <p>Total Produk</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">👥</div>
                        <div class="stat-info">
                            <h3><?php echo $total_customers; ?></h3>
                            <p>Total Customer</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">📦</div>
                        <div class="stat-info">
                            <h3><?php echo $total_orders; ?></h3>
                            <p>Total Transaksi</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">⏳</div>
                        <div class="stat-info">
                            <h3><?php echo $pending_orders; ?></h3>
                            <p>Menunggu Konfirmasi</p>
                        </div>
                    </div>
                </div>

                <!-- Transaksi Terbaru -->
                <div class="card">
                    <div class="card-header">
                        <h2>Transaksi Terbaru</h2>
                        <a href="orders.php" class="btn btn-primary btn-sm">Lihat Semua</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No. Transaksi</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($recent_orders->num_rows > 0): ?>
                                    <?php while ($order = $recent_orders->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo $order['order_number']; ?></td>
                                            <td><?php echo $order['customer_name']; ?></td>
                                            <td><?php echo formatRupiah($order['total']); ?></td>
                                            <td>
                                                <span class="badge badge-<?php echo $order['status']; ?>">
                                                    <?php echo ucfirst($order['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('d M Y', strtotime($order['created_at'])); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Belum ada transaksi</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <!-- JavaScript -->
    <script src="../assets/js/script.js"></script>
</body>
</html>