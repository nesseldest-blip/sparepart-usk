-- ============================================
-- DATABASE: sparepart_usk
-- E-Commerce Sparepart Otomotif
-- Import file ini melalui phpMyAdmin
-- ============================================

CREATE DATABASE IF NOT EXISTS sparepart_usk;
USE sparepart_usk;

-- ============================================
-- TABEL: users
-- Menyimpan data admin dan customer
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'customer') DEFAULT 'customer',
    no_hp VARCHAR(20),
    alamat TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- TABEL: products
-- Menyimpan data sparepart
-- ============================================
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_produk VARCHAR(150) NOT NULL,
    kategori VARCHAR(50) NOT NULL,
    harga DECIMAL(12,2) NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    gambar VARCHAR(255),
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- TABEL: orders
-- Menyimpan data transaksi
-- ============================================
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(20) NOT NULL UNIQUE,
    user_id INT NOT NULL,
    total DECIMAL(12,2) NOT NULL,
    alamat TEXT NOT NULL,
    status ENUM('menunggu', 'diproses', 'selesai') DEFAULT 'menunggu',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- TABEL: order_details
-- Menyimpan detail produk dalam transaksi
-- ============================================
CREATE TABLE IF NOT EXISTS order_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    jumlah INT NOT NULL,
    harga DECIMAL(12,2) NOT NULL,
    subtotal DECIMAL(12,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- DATA AWAL: Contoh Produk Sparepart
-- (Admin dibuat melalui setup.php)
-- ============================================
INSERT INTO products (nama_produk, kategori, harga, stok, gambar, deskripsi) VALUES
('Oli Mesin 1L', 'Oli & Pelumas', 85000, 50, 'oli-mesin.jpg', 'Oli mesin berkualitas tinggi untuk performa mesin optimal.'),
('Filter Udara', 'Filter', 45000, 30, 'filter-udara.jpg', 'Filter udara untuk menjaga kebersihan udara masuk ke mesin.'),
('Busi Iridium', 'Mesin', 120000, 25, 'busi-iridium.jpg', 'Busi iridium dengan daya tahan lama dan pembakaran sempurna.'),
('Kampas Rem Depan', 'Rem', 150000, 20, 'kampas-rem.jpg', 'Kampas rem depan dengan daya cengkeram kuat.'),
('Aki 35Ah', 'Kelistrikan', 450000, 15, 'aki-35ah.jpg', 'Aki kering 35Ah untuk mobil dan motor.'),
('Ban Radial 185/65 R15', 'Ban', 750000, 10, 'ban-radial.jpg', 'Ban radial dengan grip yang baik di segala cuaca.'),
('Kabel Busi', 'Mesin', 25000, 40, 'kabel-busi.jpg', 'Kabel busi berkualitas untuk pengapian yang stabil.'),
('Shockbreaker Depan', 'Suspensi', 350000, 12, 'shockbreaker.jpg', 'Shockbreaker depan dengan peredaman nyaman.'),
('Lampu LED H4', 'Kelistrikan', 180000, 18, 'lampu-led.jpg', 'Lampu LED H4 dengan cahaya terang dan hemat energi.'),
('V-Belt', 'Mesin', 55000, 35, 'v-belt.jpg', 'V-belt berkualitas untuk transmisi yang halus.');