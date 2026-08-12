# 🚀 Panduan Build APK Sparepart USK

Folder ini berisi konfigurasi [Capacitor](https://capacitorjs.com/) untuk membangun aplikasi Android (APK).

## 📋 Persiapan

Pastikan Anda memiliki:
- **Node.js** (v16+) terinstall
- **Android Studio** terinstall (untuk build APK)
- **Java JDK** 11+

## 🔧 Cara Build APK

### 1. Install Dependensi
```bash
cd apk
npm install
```

### 2. Sinkronkan dengan Android
```bash
npx cap sync android
```

### 3. Build APK (Debug)
Buka Android Studio:
```bash
npx cap open android
```

Di Android Studio:
1. Pilih **Build** → **Build Bundle(s) / APK(s)** → **Build APK(s)**
2. Tunggu sampai proses selesai
3. Klik **Locate** untuk menemukan file APK

### 4. Build APK (Release - Production)
Untuk APK release yang lebih optimal:
1. Di Android Studio, pilih **Build** → **Generate Signed Bundle / APK**
2. Pilih **APK** dan klik **Next**
3. Buat key store baru atau gunakan yang ada
4. Pilih **release** build variant
5. Selesaikan hingga selesai

## 📁 Penempatan APK untuk Download Web

Setelah membangun APK, copy file APK ke folder project:

```
sparepart-usk/
├── apk/
│   ├── sparepart-usk.apk  ← Letakkan APK di sini
│   ├── capacitor.config.json
│   ├── package.json
│   └── README.md
├── index.php
└── ...
```

Nama file APK harus `sparepart-usk.apk` agar sesuai dengan link download di website.

## 🔗 Cara Kerja Link Download

Setelah APK ditempatkan di folder `apk/`:
- Website akan menampilkan link download di halaman utama
- Floating banner di HP akan menampilkan tombol download APK
- Link download: `https://namadomain.com/apk/sparepart-usk.apk`

## 📱 Test di HP

1. Buka website di HP: `https://namadomain.com`
2. Klik tombol **⬇️ Download APK**
3. Buka file APK yang terdownload
4. Jika ada peringatan keamanan, pilih **Settings** → **Allow from this source**
5. Instal APK dan buka aplikasi

## ⚙️ Konfigurasi Capacitor

File `capacitor.config.json` berisi:
- **appId**: `com.sparepart.usk` (package name Android)
- **appName**: `Sparepart USK`
- **webDir**: `www` (folder web yang akan dibundled)
- **server**: Konfigurasi untuk development dan production

Untuk production (deploy APK), ganti `server` menjadi:
```json
"server": {
    "androidScheme": "https",
    "url": "https://namadomain.com",
    "cleartext": false
}
```
