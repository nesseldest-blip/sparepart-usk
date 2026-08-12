# 🔧 Sparepart USK - E-Commerce Sparepart Otomotif

Website e-commerce sparepart otomotif sederhana untuk tugas **USK Rekayasa Perangkat Lunak**.

Dibangun menggunakan:
- **HTML5, CSS3, JavaScript** - Frontend
- **PHP Native** - Backend
- **MySQL** - Database
- **XAMPP** - Server lokal

---

## 📋 Fitur

### Admin
- Login Admin
- Dashboard (statistik produk, customer, transaksi)
- Kelola Sparepart (tambah, edit, hapus, upload gambar)
- Kelola Transaksi (lihat detail, ubah status)
- Logout

### Customer
- Register & Login
- Halaman utama (banner, kategori, produk terbaru)
- Daftar produk dengan pencarian & filter kategori
- Detail produk
- Keranjang pembelian
- Checkout dengan alamat pengiriman
- Konfirmasi transaksi
- Riwayat transaksi & status (Menunggu, Diproses, Selesai)
- Logout

---

## 🚀 Cara Menjalankan dengan XAMPP

### 1. Install XAMPP
Download dan install XAMPP dari [https://www.apachefriends.org](https://www.apachefriends.org)

### 2. Copy Project ke htdocs
Copy folder project ini ke:
```
C:\xampp\htdocs\sparepart-usk
```

### 3. Start Apache & MySQL
1. Buka **XAMPP Control Panel**
2. Klik **Start** pada **Apache**
3. Klik **Start** pada **MySQL**

### 4. Import Database
1. Buka browser, akses: `http://localhost/phpmyadmin`
2. Klik menu **Import**
3. Pilih file **database.sql** dari folder project
4. Klik **Go** / **Import**

### 5. Setup Akun Admin
1. Akses: `http://localhost/sparepart-usk/setup.php`
2. Klik link yang muncul untuk membuat akun admin
3. Akun admin akan dibuat otomatis

### 6. Akses Website
Buka browser dan akses:
```
http://localhost/sparepart-usk/
```

---

## 🔑 Akun Default

### Admin
| Field | Value |
|-------|-------|
| Email | `admin@sparepart.com` |
| Password | `admin123` |

### Customer
Daftar akun baru melalui halaman register:
```
http://localhost/sparepart-usk/auth/register.php
```

---

## 📁 Struktur Folder

```
sparepart-usk/
│
├── admin/
│   ├── dashboard.php      # Dashboard admin
│   ├── products.php       # Daftar produk
│   ├── product_add.php    # Tambah produk
│   ├── product_edit.php   # Edit produk
│   ├── orders.php         # Kelola transaksi
│   ├── order_detail.php   # Detail transaksi (AJAX)
│   └── logout.php         # Logout admin
│
├── customer/
│   ├── dashboard.php      # Beranda customer
│   ├── products.php       # Daftar produk
│   ├── product_detail.php # Detail produk
│   ├── checkout.php       # Keranjang & checkout
│   ├── konfirmasi.php     # Konfirmasi transaksi
│   ├── orders.php         # Riwayat transaksi
│   └── logout.php         # Logout customer
│
├── config/
│   └── database.php       # Koneksi database
│
├── assets/
│   ├── css/
│   │   └── style.css      # Stylesheet utama
│   ├── js/
│   │   └── script.js      # JavaScript
│   └── images/            # Gambar statis
│
├── uploads/               # Folder upload gambar produk
│
├── auth/
│   ├── login.php          # Halaman login
│   ├── register.php       # Halaman register
│   └── logout.php         # Logout utama
│
├── index.php              # Halaman utama
├── setup.php              # Setup akun admin
├── database.sql           # File import database
└── README.md
```

---

## 🗄️ Struktur Database

### Tabel `users`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | INT (PK) | ID user |
| nama | VARCHAR(100) | Nama lengkap |
| email | VARCHAR(100) | Email (unik) |
| password | VARCHAR(255) | Password (hash) |
| role | ENUM | admin / customer |
| no_hp | VARCHAR(20) | Nomor HP |
| alamat | TEXT | Alamat |
| created_at | TIMESTAMP | Tanggal daftar |

### Tabel `products`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | INT (PK) | ID produk |
| nama_produk | VARCHAR(150) | Nama sparepart |
| kategori | VARCHAR(50) | Kategori |
| harga | DECIMAL(12,2) | Harga |
| stok | INT | Stok |
| gambar | VARCHAR(255) | Nama file gambar |
| deskripsi | TEXT | Deskripsi |
| created_at | TIMESTAMP | Tanggal input |

### Tabel `orders`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | INT (PK) | ID transaksi |
| order_number | VARCHAR(20) | Nomor transaksi (unik) |
| user_id | INT (FK) | ID customer |
| total | DECIMAL(12,2) | Total pembayaran |
| alamat | TEXT | Alamat pengiriman |
| status | ENUM | menunggu / diproses / selesai |
| created_at | TIMESTAMP | Tanggal transaksi |

### Tabel `order_details`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | INT (PK) | ID detail |
| order_id | INT (FK) | ID transaksi |
| product_id | INT (FK) | ID produk |
| jumlah | INT | Jumlah beli |
| harga | DECIMAL(12,2) | Harga satuan |
| subtotal | DECIMAL(12,2) | Subtotal |

---

## 🔒 Keamanan

- Password di-hash menggunakan `password_hash()`
- Login menggunakan `password_verify()`
- Session authentication
- Prepared statement untuk semua query database
- Proteksi halaman admin (hanya admin yang bisa akses)
- Proteksi halaman customer (harus login)
- Validasi input
- Validasi upload gambar (tipe & ukuran)

---

## 🧪 Testing

1. Admin bisa login ✅
2. Admin bisa menambah produk ✅
3. Admin bisa edit produk ✅
4. Admin bisa menghapus produk ✅
5. Customer bisa register ✅
6. Customer bisa login ✅
7. Customer bisa melihat produk ✅
8. Customer bisa melakukan pembelian ✅
9. Data pembelian masuk database ✅
10. Admin bisa melihat transaksi ✅
11. Admin bisa mengubah status transaksi ✅
12. Customer bisa melihat status transaksi ✅
13. Logout berfungsi ✅
14. Tidak ada error PHP/MySQL ✅

---

## 📝 Catatan

- Pastikan folder `uploads/` memiliki izin tulis (write permission)
- Jika port MySQL bukan 3306, sesuaikan di `config/database.php`
- Jika password MySQL berbeda, sesuaikan di `config/database.php`

---

© 2026 Sparepart USK - Tugas USK Rekayasa Perangkat Lunak