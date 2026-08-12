<?php
// ============================================
// DETAIL TRANSAKSI (AJAX)
// Mengembalikan HTML detail transaksi untuk modal
// ============================================

require_once '../config/database.php';
requireAdmin(); // Proteksi: hanya admin yang bisa akses

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ambil data transaksi
$stmt = $conn->prepare("
    SELECT o.*, u.nama as customer_name, u.email as customer_email, u.no_hp as customer_phone, u.alamat as customer_address
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
    WHERE o.id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    echo '<p>Transaksi tidak ditemukan.</p>';
    exit();
}

// Ambil detail produk dalam transaksi
$details = $conn->prepare("
    SELECT od.*, p.nama_produk, p.gambar 
    FROM order_details od 
    JOIN products p ON od.product_id = p.id 
    WHERE od.order_id = ?
");
$details->bind_param("i", $order_id);
$details->execute();
$detail_result = $details->get_result();
?>
<div class="order-detail">
    <div class="detail-section">
        <h3>Informasi Transaksi</h3>
        <p><strong>No. Transaksi:</strong> <?php echo $order['order_number']; ?></p>
        <p><strong>Tanggal:</strong> <?php echo date('d M Y H:i', strtotime($order['created_at'])); ?></p>
        <p><strong>Status:</strong> <span class="badge badge-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></p>
    </div>

    <div class="detail-section">
        <h3>Data Customer</h3>
        <p><strong>Nama:</strong> <?php echo $order['customer_name']; ?></p>
        <p><strong>Email:</strong> <?php echo $order['customer_email']; ?></p>
        <p><strong>No. HP:</strong> <?php echo $order['customer_phone']; ?></p>
        <p><strong>Alamat:</strong> <?php echo $order['alamat']; ?></p>
    </div>

    <div class="detail-section">
        <h3>Detail Produk</h3>
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
                    <td><strong><?php echo formatRupiah($order['total']); ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>