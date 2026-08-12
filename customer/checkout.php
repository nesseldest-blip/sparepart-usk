<?php
// ============================================
// CHECKOUT / PEMBELIAN (CUSTOMER)
// Menampilkan keranjang dan memproses pembelian
// ============================================

require_once '../config/database.php';
requireLogin(); // Proteksi: harus login

// Inisialisasi cart kosong jika belum ada
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Proses update jumlah di cart
if (isset($_POST['update_cart'])) {
    foreach ($_POST['jumlah'] as $product_id => $jumlah) {
        $product_id = (int)$product_id;
        $jumlah = (int)$jumlah;

        if ($jumlah <= 0) {
            unset($_SESSION['cart'][$product_id]);
        } else {
            $_SESSION['cart'][$product_id] = $jumlah;
        }
    }
    setFlash('success', 'Keranjang berhasil diupdate!');
    header('Location: checkout.php');
    exit();
}

// Proses hapus item dari cart
if (isset($_GET['remove'])) {
    $product_id = (int)$_GET['remove'];
    unset($_SESSION['cart'][$product_id]);
    setFlash('success', 'Produk dihapus dari pembelian!');
    header('Location: checkout.php');
    exit();
}

// Proses checkout
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['checkout'])) {
    $alamat = cleanInput($_POST['alamat']);

    // Validasi alamat
    if (empty($alamat)) {
        setFlash('danger', 'Alamat pengiriman wajib diisi!');
        header('Location: checkout.php');
        exit();
    }

    // Validasi cart tidak kosong
    if (empty($_SESSION['cart'])) {
        setFlash('danger', 'Keranjang belanja kosong!');
        header('Location: checkout.php');
        exit();
    }

    // Ambil data user
    $user_id = $_SESSION['user_id'];

    // Generate nomor transaksi unik
    $order_number = 'TRX' . date('Ymd') . strtoupper(substr(uniqid(), -5));

    // Hitung total dan validasi stok
    $total = 0;
    $items = [];
    $stok_error = false;

    foreach ($_SESSION['cart'] as $product_id => $jumlah) {
        $stmt = $conn->prepare("SELECT id, nama_produk, harga, stok FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();

        if (!$product) {
            unset($_SESSION['cart'][$product_id]);
            continue;
        }

        // Cek stok tersedia
        if ($product['stok'] < $jumlah) {
            setFlash('danger', 'Stok produk "' . $product['nama_produk'] . '" tidak mencukupi!');
            $stok_error = true;
            break;
        }

        $subtotal = $product['harga'] * $jumlah;
        $total += $subtotal;

        $items[] = [
            'product_id' => $product_id,
            'nama_produk' => $product['nama_produk'],
            'harga' => $product['harga'],
            'jumlah' => $jumlah,
            'subtotal' => $subtotal
        ];
        $stmt->close();
    }

    if ($stok_error) {
        header('Location: checkout.php');
        exit();
    }

    // Simpan transaksi menggunakan transaction
    $conn->begin_transaction();

    try {
        // Insert ke tabel orders
        $stmt = $conn->prepare("INSERT INTO orders (order_number, user_id, total, alamat, status) VALUES (?, ?, ?, ?, 'menunggu')");
        $stmt->bind_param("sids", $order_number, $user_id, $total, $alamat);
        $stmt->execute();
        $order_id = $conn->insert_id;
        $stmt->close();

        // Insert detail dan kurangi stok
        foreach ($items as $item) {
            // Insert ke order_details
            $stmt = $conn->prepare("INSERT INTO order_details (order_id, product_id, jumlah, harga, subtotal) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iiidd", $order_id, $item['product_id'], $item['jumlah'], $item['harga'], $item['subtotal']);
            $stmt->execute();
            $stmt->close();

            // Kurangi stok produk
            $stmt = $conn->prepare("UPDATE products SET stok = stok - ? WHERE id = ?");
            $stmt->bind_param("ii", $item['jumlah'], $item['product_id']);
            $stmt->execute();
            $stmt->close();
        }

        // Commit transaksi
        $conn->commit();

        // Kosongkan cart
        $_SESSION['cart'] = [];

        // Redirect ke halaman konfirmasi
        header('Location: konfirmasi.php?order=' . $order_number);
        exit();

    } catch (Exception $e) {
        // Rollback jika ada error
        $conn->rollback();
        setFlash('danger', 'Transaksi gagal: ' . $e->getMessage());
        header('Location: checkout.php');
        exit();
    }
}

// Ambil data produk di cart
$cart_items = [];
$total_all = 0;

if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id => $jumlah) {
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();

        if ($product) {
            $subtotal = $product['harga'] * $jumlah;
            $total_all += $subtotal;
            $cart_items[] = [
                'product' => $product,
                'jumlah' => $jumlah,
                'subtotal' => $subtotal
            ];
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembelian - Sparepart USK</title>
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
        <?php $flash = getFlash(); ?>
        <?php if ($flash): ?>
            <div class="alert alert-<?php echo $flash['type']; ?>"><?php echo $flash['message']; ?></div>
        <?php endif; ?>

        <div class="page-header">
            <h1>Halaman Pembelian</h1>
            <p>Tinjau produk sebelum melakukan checkout</p>
        </div>

        <?php if (empty($cart_items)): ?>
            <!-- Cart Kosong -->
            <div class="empty-state">
                <p>🛒 Keranjang pembelian Anda kosong.</p>
                <a href="products.php" class="btn btn-primary">Lihat Produk</a>
            </div>
        <?php else: ?>
            <div class="checkout-layout">
                <!-- Daftar Item -->
                <div class="checkout-items">
                    <div class="card">
                        <div class="card-header">
                            <h2>Produk yang Dibeli</h2>
                        </div>
                        <form method="POST" action="" id="cartForm">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th>Harga</th>
                                            <th>Jumlah</th>
                                            <th>Subtotal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($cart_items as $item): ?>
                                            <tr>
                                                <td>
                                                    <div class="cart-product">
                                                        <?php if ($item['product']['gambar'] && file_exists('../uploads/' . $item['product']['gambar'])): ?>
                                                            <img src="../uploads/<?php echo $item['product']['gambar']; ?>" alt="<?php echo $item['product']['nama_produk']; ?>" class="cart-product-img">
                                                        <?php else: ?>
                                                            <div class="cart-product-placeholder">🔧</div>
                                                        <?php endif; ?>
                                                        <span><?php echo $item['product']['nama_produk']; ?></span>
                                                    </div>
                                                </td>
                                                <td><?php echo formatRupiah($item['product']['harga']); ?></td>
                                                <td>
                                                    <input type="number" name="jumlah[<?php echo $item['product']['id']; ?>]" class="form-control form-control-sm cart-qty" value="<?php echo $item['jumlah']; ?>" min="1" max="<?php echo $item['product']['stok']; ?>">
                                                </td>
                                                <td><?php echo formatRupiah($item['subtotal']); ?></td>
                                                <td>
                                                    <a href="checkout.php?remove=<?php echo $item['product']['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus produk ini dari pembelian?')">Hapus</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-right"><strong>Total:</strong></td>
                                            <td colspan="2"><strong class="total-price"><?php echo formatRupiah($total_all); ?></strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <button type="submit" name="update_cart" class="btn btn-secondary btn-sm">Update Jumlah</button>
                        </form>
                    </div>
                </div>

                <!-- Form Checkout -->
                <div class="checkout-form">
                    <div class="card">
                        <div class="card-header">
                            <h2>Informasi Pengiriman</h2>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="">
                                <div class="form-group">
                                    <label for="nama">Nama Penerima</label>
                                    <input type="text" id="nama" class="form-control" value="<?php echo $_SESSION['nama']; ?>" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat Pengiriman</label>
                                    <textarea id="alamat" name="alamat" class="form-control" rows="4" placeholder="Masukkan alamat pengiriman lengkap" required></textarea>
                                </div>

                                <div class="checkout-summary">
                                    <div class="summary-row">
                                        <span>Total Item</span>
                                        <span><?php echo count($cart_items); ?> produk</span>
                                    </div>
                                    <div class="summary-row">
                                        <span>Total Pembayaran</span>
                                        <span class="total-price"><?php echo formatRupiah($total_all); ?></span>
                                    </div>
                                </div>

                                <button type="submit" name="checkout" class="btn btn-primary btn-block btn-lg" onclick="return confirm('Yakin ingin melakukan checkout?')">Checkout Sekarang</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2026 Sparepart USK. Tugas USK Rekayasa Perangkat Lunak.</p>
    </footer>
</body>
</html>