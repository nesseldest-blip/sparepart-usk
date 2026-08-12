<?php
// ============================================
// KELOLA TRANSAKSI (ADMIN)
// Menampilkan dan mengubah status transaksi
// ============================================

require_once '../config/database.php';
requireAdmin(); // Proteksi: hanya admin yang bisa akses

// Proses ubah status transaksi
if (isset($_POST['update_status'])) {
    $order_id = (int)$_POST['order_id'];
    $status = cleanInput($_POST['status']);

    // Validasi status yang diizinkan
    $allowed_status = ['menunggu', 'diproses', 'selesai'];
    if (in_array($status, $allowed_status)) {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $order_id);

        if ($stmt->execute()) {
            setFlash('success', 'Status transaksi berhasil diubah!');
        } else {
            setFlash('danger', 'Gagal mengubah status transaksi!');
        }
        $stmt->close();
    }
    header('Location: orders.php');
    exit();
}

// Ambil semua transaksi dengan data customer
$orders = $conn->query("
    SELECT o.*, u.nama as customer_name, u.email as customer_email, u.no_hp as customer_phone
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
    ORDER BY o.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi - Sparepart USK</title>
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
                <a href="products.php">🔩 Kelola Produk</a>
                <a href="orders.php" class="active">📦 Transaksi</a>
                <a href="../auth/logout.php">🚪 Logout</a>
            </nav>
            <div class="sidebar-footer">
                <p>Halo, <?php echo $_SESSION['nama']; ?></p>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="topbar">
                <h1>Kelola Transaksi</h1>
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
                        <h2>Daftar Transaksi</h2>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No. Transaksi</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Alamat</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($orders->num_rows > 0): ?>
                                    <?php while ($order = $orders->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo $order['order_number']; ?></td>
                                            <td>
                                                <strong><?php echo $order['customer_name']; ?></strong><br>
                                                <small><?php echo $order['customer_email']; ?><br><?php echo $order['customer_phone']; ?></small>
                                            </td>
                                            <td><strong><?php echo formatRupiah($order['total']); ?></strong></td>
                                            <td class="text-truncate" style="max-width: 200px;"><?php echo $order['alamat']; ?></td>
                                            <td>
                                                <span class="badge badge-<?php echo $order['status']; ?>">
                                                    <?php echo ucfirst($order['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('d M Y H:i', strtotime($order['created_at'])); ?></td>
                                            <td>
                                                <button type="button" class="btn btn-info btn-sm" onclick="showDetail(<?php echo $order['id']; ?>)">Detail</button>
                                                <form method="POST" action="" class="inline-form">
                                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                                    <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                                                        <option value="menunggu" <?php echo $order['status'] == 'menunggu' ? 'selected' : ''; ?>>Menunggu</option>
                                                        <option value="diproses" <?php echo $order['status'] == 'diproses' ? 'selected' : ''; ?>>Diproses</option>
                                                        <option value="selesai" <?php echo $order['status'] == 'selesai' ? 'selected' : ''; ?>>Selesai</option>
                                                    </select>
                                                    <input type="hidden" name="update_status" value="1">
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada transaksi</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Detail Transaksi -->
    <div id="orderModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detail Transaksi</h2>
                <button type="button" class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Isi detail akan dimuat via AJAX -->
            </div>
        </div>
    </div>

    <script src="../assets/js/script.js"></script>
</body>
</html>