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

## 📱 Mobile & APK

Website sudah dioptimalkan untuk penggunaan di perangkat mobile (HP, tablet) dan juga mendukung PWA (Progressive Web App) untuk di-install seperti aplikasi asli.

### Fitur Mobile
- **Navbar responsif** dengan menu hamburger di layar kecil
- **Floating download banner** yang muncul otomatis saat membuka di HP
- **Deteksi perangkat otomatis** menampilkan opsi yang tepat
- **PWA (Progressive Web App)** bisa di-install langsung dari browser HP
- **Tombol download APK** tersedia di halaman utama dan banner mobile

### Download APK
1. Buka website di HP atau komputer
2. Pada halaman utama, klik tombol **"⬇️ Download APK"**
3. File APK akan terdownload di `apk/sparepart-usk.apk`
4. Buka file APK dan pasang di perangkat Android Anda

> **Catatan:** Untuk membuat file APK, gunakan [Capacitor](https://capacitorjs.com/) yang sudah tersedia di folder `apk/`. Ikuti panduan build di bawah.

### Buka di Browser
Jika aplikasi sudah terpasang, klik tombol **"🌐 Buka di Browser"** untuk membuka versi web di browser HP Anda. Ini berguna jika Anda ingin beralih dari aplikasi ke versi web.

### Build APK (untuk developer)
```bash
cd apk
npm install
npm run build
```
Setelah build selesai, copy file APK ke folder `apk/` dan pastikan nama file sesuai: `apk/sparepart-usk.apk`

---

## 🚀 Hosting & Deployment Online 24 Jam

Berikut panduan langkah demi langkah agar website dapat diakses online melalui internet (bukan localhost):

### Persiapan Awal
- Pastikan Anda memiliki akun hosting yang mendukung **PHP** dan **MySQL**
- Rekomendasi hosting: Niagahoster, Hostinger, Bluehost, IDCloudHost, dll.
- Pastikan hosting sudah aktif dan domain sudah terhubung

### Langkah 1: Siapkan Hosting & Database
1. Login ke panel kontrol hosting (cPanel / Plesk)
2. Buka menu **MySQL Databases** (atau phpMyAdmin)
3. Buat database baru, misalnya: `sparepart_usk`
4. Buat user database baru dan catat:
   - **DB_HOST** (host database, biasanya `localhost` atau IP tertentu)
   - **DB_NAME** (nama database yang baru dibuat)
   - **DB_USER** (username database)
   - **DB_PASSWORD** (password database)
5. Berikan hak akses penuh user ke database yang dibuat

### Langkah 2: Import Database
1. Buka **phpMyAdmin** di hosting Anda
2. Pilih database yang baru dibuat (`sparepart_usk`)
3. Klik menu **Import**
4. Pilih file `database.sql` dari folder project
5. Klik **Go** / **Import** untuk mengimpor struktur dan data

### Langkah 3: Konfigurasi Database untuk Production
Buka file `config/database.php` dan sesuaikan dengan konfigurasi hosting Anda:

```php
// Ganti bagian ini dengan konfigurasi hosting Anda:
define('DB_HOST_DEV', 'localhost');  // Ganti dengan host database hosting
define('DB_NAME_DEV', 'sparepart_usk'); // Ganti dengan nama database hosting
define('DB_USER_DEV', 'root');       // Ganti dengan user database hosting
define('DB_PASS_DEV', '');           // Ganti dengan password database hosting
```

Atau gunakan environment variable di panel hosting (jika didukung):
- `DB_HOST` = host database hosting
- `DB_NAME` = nama database
- `DB_USER` = username database
- `DB_PASS` = password database

> **Penting:** Password database TIDAK akan pernah ditampilkan di halaman website.

### Langkah 4: Upload Project ke Hosting
1. Pastikan semua file sudah lengkap di folder project
2. Buat zip dari seluruh folder project (kecuali folder `apk/` dan `.git/`)
3. Buka **File Manager** di cPanel hosting
4. Upload file zip ke folder `public_html` (atau `www`)
5. Extract file zip di folder `public_html`
6. Pastikan strukturnya:
```
public_html/
├── index.php
├── config/
├── auth/
├── customer/
├── admin/
├── assets/
├── uploads/
└── manifest.json
```

### Langkah 5: Atur Permission Folder Upload
1. Di File Manager, klik kanan folder `uploads/`
2. Pilih **Change Permissions**
3. Beri centang semua kotak (Read, Write, Execute) atau set ke `777`
4. Klik **Change** untuk menyimpan

### Langkah 6: Hubungkan Domain
1. Di panel kontrol hosting, buka menu **Addon Domain** atau **Subdomain**
2. Jika menggunakan domain utama: website langsung diakses via `https://namadomain.com`
3. Jika pakai subdomain: buat subdomain seperti `https://sparepart.domain.com`
4. Arahkan ke folder `public_html`

### Langkah 7: Aktifkan HTTPS / SSL
1. Di panel hosting, cari menu **SSL/TLS** atau **Let's Encrypt**
2. Klik **Install SSL** untuk domain Anda
3. Setelah SSL aktif, buka file `config/database.php`
4. Tambahkan kode untuk force HTTPS (opsional):
```php
// Force redirect to HTTPS
if ($_SERVER['HTTPS'] != "on" && !empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] !== 'https') {
    header("Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
}
```

### Langkah 8: Buat Akun Admin
1. Akses: `https://namadomain.com/setup.php`
2. Ikuti langkah-langkah untuk membuat akun admin
3. Catat email dan password admin untuk login

### Langkah 9: Test Website di Berbagai Perangkat

#### a. Test di Laptop/Komputer
- Buka `https://namadomain.com` di browser
- Pastikan semua halaman loading dengan benar
- Login sebagai admin: `https://namadomain.com/auth/login.php`
- Login sebagai customer (daftar dulu jika belum punya akun)

#### b. Test di HP via Wi-Fi
- Hubungkan HP ke Wi-Fi yang sama
- Buka `https://namadomain.com` di browser HP
- Pastikan tampilan responsif
- Test login dan fitur utama

#### c. Test di HP via Data Seluler
- Matikan Wi-Fi HP (gunakan data seluler/mobile data)
- Buka `https://namadomain.com` di browser HP
- Pastikan website masih dapat diakses
- Test fitur download APK dan buka di browser

#### d. Test PWA (Tambah ke Layar Utama)
- Buka website di HP menggunakan Chrome
- Klik ikon **tambah ke layar utama** (chrome menu)
- Buka dari layar utama dan pastikan PWA berfungsi

#### e. Test dari HP Lain
- Minta teman/family untuk membuka website di HP mereka
- Pastikan website responsif di berbagai ukuran layar

### Langkah 10: Test Fitur Utama

| No | Fitur | Cara Test |
|----|-------|-----------|
| 1 | Login Admin | Akses `/auth/login.php` dengan akun admin |
| 2 | Tambah Produk | Login admin → Produk → Tambah Sparepart |
| 3 | Register Customer | Akses `/auth/register.php` |
| 4 | Login Customer | Akses `/auth/login.php` dengan akun customer |
| 5 | Lihat Produk | Buka `/customer/products.php` |
| 6 | Belanja/Purchase | Detail produk → Beli → Checkout |
| 7 | Transaksi | Lihat di `/customer/orders.php` |
| 8 | Upload Gambar | Tambah/edit produk dengan gambar |
| 9 | Database | Cek phpMyAdmin, pastikan data tersimpan |
| 10 | APK Download | Buka di HP, klik "Download APK" |

### Troubleshoot Deployment
- **Error koneksi database:** Pastikan `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASSWORD` sudah benar sesuai hosting
- **Halaman putih/blank:** Aktifkan error reporting di PHP atau cek error log hosting
- **Upload gambar gagal:** Pastikan folder `uploads/` memiliki permission 755 atau 777
- **Session login tidak bekerja:** Pastikan session path di hosting berikan izin tulis
- **Mixed content (HTTP/HTTPS):** Pastikan semua resource pakai HTTPS atau gunakan relative URL

---

## 🔧 Penggunaan di Komputer Developer

### Jalankan di Localhost (XAMPP)
Ikuti langkah-langkah di bagian **[Cara Menjalankan dengan XAMPP](#-cara-menjalankan-dengan-xampp)** di atas.

---

## 📋 Ringkasan Fitur Mobile

| Fitur | Desktop | Mobile (HP) |
|-------|---------|-------------|
| Navbar | Full menu | Hamburger toggle |
| Produk | Grid 4 kolom | Grid 2 kolom |
| PWA | Install di browser | Install dari home screen |
| APK Download | Di halaman utama | Floating banner + halaman utama |
| Buka di Browser | Di halaman utama | Floating banner + halaman utama |
| Responsive | ✅ | ✅ |

---

© 2026 Sparepart USK - Tugas USK Rekayasa Perangkat Lunak