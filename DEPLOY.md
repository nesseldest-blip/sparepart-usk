# PANDUAN DEPLOYMENT - Sparepart USK

## Cara Deploy Website ke Server/Hosting Online 24 Jam

Panduan ini menjelaskan langkah demi langkah cara meng-upload website Sparepart USK ke hosting agar bisa diakses online 24 jam dari HP, laptop, tablet, dan komputer.

---

## ALUR DEPLOYMENT

```
HP / Laptop / Tablet
        |
        v
    Internet
        |
        v
  Domain / URL
  (https://namadomain.com)
        |
        v
  Hosting / Server
  (PHP + MySQL)
        |
        v
  PHP Application
  (Website Sparepart USK)
        |
        v
  MySQL Database
  (Data Produk, User, Transaksi)
```

**Setelah deployment selesai, website bisa diakses tanpa menjalankan XAMPP di komputer developer.**

---

## BAGIAN 1: PERSIAPAN

### 1.1 Yang Perlu Disiapkan

| No | Kebutuhan | Keterangan |
|----|-----------|------------|
| 1 | Akun Hosting | Hosting PHP + MySQL (cPanel/Plesk) |
| 2 | Domain | namaanda.com atau sparepart.namaanda.com |
| 3 | File Project | Seluruh folder project Sparepart USK |
| 4 | database.sql | File database dari project |
| 5 | FileZilla / Browser | Untuk upload file ke hosting |

### 1.2 Rekomendasi Hosting

Berikut beberapa hosting yang mendukung PHP + MySQL:

| Hosting | Harga Mulai | Kelebihan |
|---------|-------------|-----------|
| **Niagahoster** | Rp 10.000/bulan | Murah, support Indonesia |
| **Domainesia** | Rp 9.000/bulan | Murah, mudah digunakan |
| **IdCloudHost** | Rp 15.000/bulan | Performa baik |
| **Hostinger** | Rp 13.000/bulan | Panel modern |
| **Rumahweb** | Rp 10.000/bulan | Support lokal |

**Yang harus dicari saat memilih hosting:**
- Support PHP 7.4 atau lebih tinggi
- Support MySQL 5.7 atau lebih tinggi
- Ada cPanel atau Panel manage sendiri
- Support .htaccess (Apache)
- Storage minimal 500MB
- Bandwidth unlimited atau besar

---

## BAGIAN 2: SETUP HOSTING

### 2.1 Login ke cPanel Hosting

1. Buka browser di komputer
2. Ketik: `https://namadomainanda.com/cpanel` atau `https://serveranda:2083`
3. Masukkan username dan password dari hosting
4. Klik **Login**

### 2.2 Buat Database MySQL

1. Di cPanel, cari menu **"MySQL Databases"** atau **"Database"**
2. Di bagian **"Create New Database"**:
   - Ketik nama database: `sparepart_usk`
   - Klik **"Create Database"**
3. Catat nama database lengkapnya (biasanya: `username_sparepart_usk`)

### 2.3 Buat User Database

1. Di halaman yang sama, cari bagian **"MySQL Users"** > **"Add New User"**
2. Isi:
   - Username: `sparepart_user`
   - Password: `BuatPasswordYangKuat123!`
   - **CATAT PASSWORD INI!**
3. Klik **"Create User"**

### 2.4 Hubungkan User ke Database

1. Di bagian **"Add User to Database"**:
   - Pilih User: `sparepart_user`
   - Pilih Database: `sparepart_usk`
   - Klik **"Add"**
2. Berikan semua hak akses (**ALL PRIVILEGES**)
3. Klik **"Make Changes"**

### 2.5 Catat Konfigurasi Database

Simpan informasi ini:

```
DB_HOST    : localhost (atau sesuai info hosting)
DB_NAME    : username_sparepart_usk
DB_USER    : username_sparepart_user
DB_PASS    : PasswordYangKuat123!
```

> **PENTING:** Jangan berikan informasi ini kepada orang lain!

---

## BAGIAN 3: IMPORT DATABASE

### 3.1 Buka phpMyAdmin

1. Di cPanel, cari menu **"phpMyAdmin"**
2. Klik untuk membuka phpMyAdmin
3. Login dengan username dan password database

### 3.2 Import Database

1. Di phpMyAdmin, klik tab **"Import"** di bagian atas
2. Klik **"Choose File"** atau **"Browse"**
3. Cari dan pilih file `database.sql` dari folder project
4. Pastikan encoding: **utf-8** atau **utf-8mb4**
5. Klik **"Go"** atau **"Import"**
6. Tunggu hingga proses selesai
7. Jika berhasil, akan muncul pesan sukses

### 3.3 Verifikasi Database

1. Di phpMyAdmin, klik tab **"Database"** di kiri
2. Klik database `sparepart_usk`
3. Pastikan ada 4 tabel:
   - `users`
   - `products`
   - `orders`
   - `order_details`
4. Klik tabel `products` > pastikan ada 10 data produk

---

## BAGIAN 4: UPLOAD FILE PROJECT

### 4.1 Siapkan File Project

Pastikan folder project sudah siap:

```
sparepart-usk/
├── admin/
├── assets/
├── auth/
├── config/
│   ├── database.php    <-- yang akan diedit
│   └── database.example.php
├── customer/
├── uploads/
├── .htaccess           <-- file baru
├── database.sql
├── index.php
├── setup.php
├── sw.js
└── manifest.json
```

### 4.2 Upload via File Manager cPanel

1. Di cPanel, cari menu **"File Manager"**
2. Navigasi ke folder **`public_html`** (ini adalah folder root website)
3. Klik **"Upload"** di bagian atas
4. Pilih **semua file dan folder** dari project
5. Klik **"Upload"**
6. Tunggu hingga proses selesai

### 4.3 Upload via FileZilla (Alternatif)

1. Download dan install **FileZilla** dari https://filezilla-project.org
2. Buka FileZilla
3. Isi kolom:
   - **Host:** `namadomainanda.com`
   - **Username:** `username_ftp`
   - **Password:** `password_ftp`
   - **Port:** `21`
4. Klik **"Quickconnect"**
5. Di panel kiri (komputer), navigasi ke folder project
6. Di panel kanan (server), buka folder `public_html`
7. Select semua file di panel kiri
8. Klik kanan > **"Upload"**
9. Tunggu hingga selesai

### 4.4 Struktur Folder di Hosting

Setelah upload, pastikan struktur di hosting seperti ini:

```
public_html/
├── admin/
│   ├── dashboard.php
│   ├── orders.php
│   ├── product_add.php
│   ├── product_edit.php
│   └── products.php
├── assets/
│   ├── css/
│   │   └── style.css
│   ├── images/
│   │   ├── icon-192.png
│   │   └── icon-512.png
│   └── js/
│       └── script.js
├── auth/
│   ├── login.php
│   ├── logout.php
│   └── register.php
├── config/
│   ├── database.php
│   └── database.example.php
├── customer/
│   ├── checkout.php
│   ├── dashboard.php
│   ├── konfirmasi.php
│   ├── orders.php
│   ├── product_detail.php
│   └── products.php
├── uploads/
│   └── (folder untuk gambar produk)
├── .htaccess
├── database.sql
├── index.php
├── setup.php
├── sw.js
└── manifest.json
```

---

## BAGIAN 5: KONFIGURASI DATABASE

### 5.1 Edit File database.php

1. Di File Manager, buka folder `config/`
2. Klik kanan pada `database.php` > **"Edit"**
3. Ganti bagian **KONFIGURASI PRODUCTION**:

```php
// --- KONFIGURASI PRODUCTION (HOSTING) ---
define('DB_HOST_PROD', 'localhost');
define('DB_NAME_PROD', 'username_sparepart_usk');
define('DB_USER_PROD', 'username_sparepart_user');
define('DB_PASS_PROD', 'PasswordYangKuat123!');
```

4. Ganti nilai sesuai dengan konfigurasi hosting Anda:

| Konfigurasi | Ganti dengan |
|-------------|--------------|
| `DB_HOST_PROD` | `localhost` (atau sesuai info hosting) |
| `DB_NAME_PROD` | Nama database hosting Anda |
| `DB_USER_PROD` | Username database hosting Anda |
| `DB_PASS_PROD` | Password database hosting Anda |

5. Klik **"Save Changes"**

### 5.2 Contoh Konfigurasi

```php
// Contoh untuk hosting Niagahoster:
define('DB_HOST_PROD', 'localhost');
define('DB_NAME_PROD', 'usk26542_sparepart');
define('DB_USER_PROD', 'usk26542_admin');
define('DB_PASS_PROD', 'Xk9#mP2$vL5nQ8wR');
```

> **PENTING:** Jangan lupa mengganti nilai di atas dengan data hosting Anda!

---

## BAGIAN 6: SETUP ADMIN

### 6.1 Jalankan Setup Admin

1. Buka browser
2. Ketik: `https://namadomainanda.com/setup.php`
3. Jika berhasil, akan muncul pesan:
   ```
   Setup berhasil!
   Email: admin@sparepart.com
   Password: admin123
   ```
4. Klik **"Silakan Login"** untuk masuk ke halaman login

### 6.2 Hapus File setup.php (KEAMANAN!)

**Setelah admin berhasil dibuat, HAPUS file `setup.php` dari hosting:**

1. Di File Manager, cari file `setup.php`
2. Klik kanan > **"Delete"**
3. Konfirmasi penghapusan

> **PENTING:** File setup.php harus dihapus untuk keamanan!

---

## BAGIAN 7: SETTING DOMAIN

### 7.1 Hubungkan Domain ke Hosting

Jika domain berbeda dari hosting:

1. Login ke panel domain (di mana Anda membeli domain)
2. Cari menu **"Nameservers"** atau **"DNS Management"**
3. Ganti nameserver sesuai info dari hosting:
   ```
   ns1.namahosting.com
   ns2.namahosting.com
   ```
4. Simpan perubahan
5. Tunggu **24-48 jam** untuk propagasi DNS

### 7.2 Setup Subdomain (Opsional)

Jika ingin menggunakan `sparepart.namaanda.com`:

1. Di cPanel, cari **"Subdomains"**
2. Isi:
   - Subdomain: `sparepart`
   - Domain: `namaanda.com`
   - Document Root: `public_html`
3. Klik **"Create"**

---

## BAGIAN 8: AKTIFKAN HTTPS/SSL

### 8.1 Aktifkan SSL di cPanel

1. Di cPanel, cari menu **"SSL/TLS"** atau **"Let's Encrypt"**
2. Pilih domain Anda
3. Klik **"Issue"** atau **"Install"**
4. Tunggu hingga SSL terinstall

### 8.2 Aktifkan HTTPS Otomatis

Jika hosting menggunakan **Let's Encrypt** atau **Certbot**:

1. Di cPanel, cari **"SSL/TLS Status"**
2. Pilih domain Anda
3. Klik **"Run AutoSSL"**
4. Tunggu proses selesai

### 8.3 Verifikasi HTTPS

1. Buka browser
2. Ketik: `https://namadomainanda.com`
3. Pastikan ada **ikon gembok** di address bar
4. Klik ikon gembok > pastikan tertulis **"Connection is secure"**

---

## BAGIAN 9: SETTING FOLDER UPLOADS

### 9.1 Berikan Permission yang Benar

1. Di File Manager, buka folder `uploads/`
2. Klik kanan > **"Change Permissions"** atau **"Properties"**
3. Set permission: **755** (untuk folder)
4. Untuk file gambar: **644**

### 9.2 Buat Folder Uploads (Jika Belum Ada)

Jika folder `uploads/` belum ada di hosting:

1. Di File Manager, buka `public_html/`
2. Klik **"New Folder"**
3. Nama folder: `uploads`
4. Permission: **755**

---

## BAGIAN 10: TEST WEBSITE

### 10.1 Test Melalui Wi-Fi

1. Pastikan komputer terhubung ke Wi-Fi
2. Buka browser
3. Ketik: `https://namadomainanda.com`
4. Pastikan website muncul dengan benar

### 10.2 Test Melalui Data Seluler (HP)

1. Matikan Wi-Fi di HP
2. Aktifkan data seluler
3. Buka browser di HP
4. Ketik: `https://namadomainanda.com`
5. Pastikan website bisa diakses

### 10.3 Test dari HP Landing

1. Minta teman/kerabat buka website dari HP mereka
2. Pastikan website bisa diakses dari jaringan berbeda

### 10.4 Test Login Admin

1. Buka: `https://namadomainanda.com/auth/login.php`
2. Email: `admin@sparepart.com`
3. Password: `admin123`
4. Klik **Login**
5. Pastikan masuk ke Dashboard Admin

### 10.5 Test Login Customer

1. Logout dari admin
2. Buka: `https://namadomainanda.com/auth/register.php`
3. Daftar akun baru (customer)
4. Login dengan akun baru
5. Pastikan masuk ke Dashboard Customer

### 10.6 Test Tambah Produk

1. Login sebagai Admin
2. Buka menu **"Kelola Produk"**
3. Klik **"Tambah Produk"**
4. Isi data produk:
   - Nama Produk: `Test Produk`
   - Kategori: `Lainnya`
   - Harga: `50000`
   - Stok: `10`
   - Gambar: Upload gambar
   - Deskripsi: `Ini produk test`
5. Klik **"Simpan Produk"**
6. Pastikan produk berhasil ditambahkan

### 10.7 Test Pembelian

1. Login sebagai Customer
2. Cari produk
3. Klik **"Beli"** atau **"Tambah ke Keranjang"**
4. Buka keranjang
5. Klik **"Checkout"**
6. Isi alamat pengiriman
7. Klik **"Buat Pesanan"**
8. Pastikan pesanan berhasil dibuat

### 10.8 Test Database dan Transaksi

1. Login sebagai Admin
2. Buka menu **"Transaksi"**
3. Pastikan transaksi dari customer muncul
4. Klik **"Detail"** untuk melihat detail
5. Ubah status pesanan
6. Login sebagai Customer
7. Buka menu **"Pesanan Saya"**
8. Pastikan status pesanan sudah berubah

---

## BAGIAN 11: TROUBLESHOOTING

### Masalah 1: Website Tidak Buka

**Cek:**
- [ ] Domain sudah propagasi? (tunggu 24-48 jam)
- [ ] Nameserver sudah benar?
- [ ] File index.php ada di folder `public_html`?

### Masalah 2: Error Database

**Cek:**
- [ ] Nama database benar?
- [ ] Username database benar?
- [ ] Password database benar?
- [ ] User sudah di-grant ALL PRIVILEGES?
- [ ] Database sudah di-import?

### Masalah 3: Gambar Tidak Muncul

**Cek:**
- [ ] Folder `uploads/` ada?
- [ ] Permission folder `uploads/` = 755?
- [ ] File gambar sudah di-upload?

### Masalah 4: Login Tidak Berfungsi

**Cek:**
- [ ] Session sudah diaktifkan?
- [ ] Cookie sudah aktif di browser?
- [ ] HTTPS sudah aktif?

### Masalah 5: Error 500 Internal Server Error

**Cek:**
- [ ] File `.htaccess` syntax benar?
- [ ] PHP version sesuai (minimal 7.4)?
- [ ] Cek error log di cPanel > "Error Logs"

---

## BAGIAN 12: CHECKLIST DEPLOYMENT

Sebelum website dinyatakan online, pastikan semua centang ini:

### File & Struktur
- [ ] Semua file sudah di-upload ke `public_html`
- [ ] Folder `uploads/` ada dan permission benar
- [ ] File `database.php` sudah dikonfigurasi untuk hosting
- [ ] File `setup.php` sudah dihapus
- [ ] File `.htaccess` ada di root

### Database
- [ ] Database sudah di-import
- [ ] 4 tabel sudah ada (users, products, orders, order_details)
- [ ] Data produk sudah ada (10 produk)
- [ ] Admin sudah dibuat melalui `setup.php`

### Akses
- [ ] Website bisa diakses via Wi-Fi
- [ ] Website bisa diakses via data seluler
- [ ] Website bisa diakses dari HP lain
- [ ] HTTPS/SSL sudah aktif (gembok hijau)

### Fitur
- [ ] Login Admin berhasil
- [ ] Login Customer berhasil
- [ ] Tambah produk berhasil
- [ ] Pembelian berhasil
- [ ] Database dan transaksi berfungsi

---

## BAGIAN 13: URL AKSES

Setelah deployment selesai, website bisa diakses di:

| Halaman | URL |
|---------|-----|
| Beranda | `https://namadomainanda.com` |
| Login | `https://namadomainanda.com/auth/login.php` |
| Register | `https://namadomainanda.com/auth/register.php` |
| Dashboard Admin | `https://namadomainanda.com/admin/dashboard.php` |
| Dashboard Customer | `https://namadomainanda.com/customer/dashboard.php` |

---

## BAGIAN 4: TIPS KEAMANAN

1. **Ganti Password Default** - Setelah setup, ganti password admin dari `admin123` ke password yang kuat
2. **Hapus setup.php** - File setup harus dihapus setelah admin dibuat
3. **Gunakan HTTPS** - Selalu akses website via `https://`
4. **Backup Database** - Backup database secara berkala dari phpMyAdmin
5. **Update Password Hosting** - Ganti password cPanel secara berkala
6. **Jangan Bagikan Info Database** - Jangan berikan kredensial database kepada orang lain

---

## BAGIAN 15: BACKUP

### Backup Database

1. Login ke phpMyAdmin
2. Pilih database `sparepart_usk`
3. Klik tab **"Export"**
4. Pilih **"Quick"** method
5. Klik **"Go"**
6. Simpan file `.sql` di komputer

### Backup File

1. Di File Manager, pilih semua file di `public_html`
2. Klik **"Compress"** atau **"Archive"**
3. Download file `.zip` atau `.tar.gz`

---

## RINGKASAN

| Langkah | Keterangan | Status |
|---------|------------|--------|
| 1 | Buat hosting + database | ☐ |
| 2 | Import database.sql | ☐ |
| 3 | Upload file project | ☐ |
| 4 | Konfigurasi database.php | ☐ |
| 5 | Jalankan setup.php | ☐ |
| 6 | Hapus setup.php | ☐ |
| 7 | Setting domain | ☐ |
| 8 | Aktifkan HTTPS | ☐ |
| 9 | Test semua fitur | ☐ |

---

*Panduan ini dibuat untuk siswa SMK - Rekayasa Perangkat Lunak*
*Website: Sparepart USK - E-Commerce Sparepart Otomotif*
