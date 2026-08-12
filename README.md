# 📁 E-Arsip Samsat Banda Aceh

Sistem Manajemen Arsip Digital Kendaraan Bermotor untuk Kantor Bersama SAMSAT Kota Banda Aceh, Provinsi Aceh.

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?logo=mysql&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green)

---

## 📋 Deskripsi

**E-Arsip Samsat** adalah aplikasi web berbasis PHP yang dirancang untuk memudahkan pengelolaan, pengarsipan, dan pelacakan dokumen kendaraan bermotor (STNK, BPKB, bukti pembayaran pajak, dll.) secara elektronik. Sistem ini menggantikan proses pengarsipan manual menjadi digital yang lebih cepat, aman, dan transparan.

---

## ✨ Fitur Utama

| Fitur | Deskripsi |
|-------|-----------|
| 🔐 **Multi-Role Access** | Tiga tingkatan hak akses: **Admin**, **Petugas**, dan **User** (Wajib Pajak) |
| 📸 **Scan Dokumen Mobile** | Petugas dapat memfoto dokumen fisik langsung dari kamera HP melalui browser |
| 📤 **Upload Dokumen** | Upload file dokumen (JPG, PNG, PDF) ke server dengan penamaan otomatis |
| 📍 **Tracking Status** | Pelacakan status dokumen real-time (Diterima → Verifikasi → Pembayaran → Tersimpan) |
| 🗂️ **Kategorisasi Arsip** | Pengelolaan kategori dokumen secara dinamis (Tambah / Hapus) |
| 📜 **Riwayat Audit** | Pencatatan otomatis setiap aktivitas upload dan download per role |
| 👤 **Profil Pengguna** | Halaman profil modern dengan fitur upload foto dan ubah identitas |
| 👥 **Manajemen User** | Admin dapat menambah, mengedit, dan menghapus seluruh akun pengguna |
| 📝 **Pendaftaran Mandiri** | Wajib Pajak dapat mendaftar akun sendiri melalui halaman Register |

---

## 🔒 Keamanan

- **Password Hashing** — Bcrypt (`password_hash()` / `password_verify()`) dengan auto-migrasi dari MD5 lama
- **SQL Injection Prevention** — PDO Prepared Statements di seluruh query
- **XSS Protection** — `htmlspecialchars()` pada seluruh output
- **Session-based Auth** — Validasi sesi dan role di setiap halaman

---

## 🛠️ Teknologi

| Komponen | Teknologi |
|----------|-----------|
| Backend | PHP 8.x |
| Database | MySQL 8.x / MariaDB |
| Frontend | HTML5, Vanilla CSS, JavaScript |
| Ikon | Font Awesome 6 |
| Tipografi | Google Fonts (Inter) |
| Alert | SweetAlert2 |
| Web Server | Apache (Laragon / XAMPP / LAMP) |

---

## 📂 Struktur Proyek

```
Project-E-Arsip-SamsatBandaAceh/
├── assets/
│   ├── css/
│   │   └── modern-style.css      # Stylesheet utama (Dashboard, Auth)
│   └── img/
│       └── logo/                  # Logo dan ikon sistem
├── arsip/                         # Direktori penyimpanan file dokumen
├── gambar/                        # Direktori gambar (profil, depan)
├── pages/
│   ├── home.php                   # Dashboard overview
│   ├── arsip.php                  # Data arsip & tracking status
│   ├── upload.php                 # Upload & scan dokumen
│   ├── kategori.php               # Manajemen kategori arsip
│   ├── users.php                  # Manajemen pengguna (Admin)
│   ├── riwayat.php                # Log riwayat aktivitas
│   └── profil.php                 # Profil pengguna
├── index.php                      # Landing page
├── login.php                      # Halaman login
├── register.php                   # Halaman pendaftaran
├── logout.php                     # Proses logout
├── dashboard.php                  # Layout utama dashboard + routing
├── download.php                   # Handler download file + logging
├── koneksi.php                    # Konfigurasi koneksi database (PDO)
├── setup.php                      # Skrip instalasi database otomatis
├── arsip_kita.sql                 # Skema & data awal database
└── README.md                      # Dokumentasi ini
```

---

## 🚀 Instalasi & Penggunaan

### Prasyarat

- **PHP** ≥ 7.4 (disarankan 8.x)
- **MySQL** ≥ 5.7 atau **MariaDB** ≥ 10.x
- **Apache** Web Server (atau Nginx)
- **Laragon** / **XAMPP** / **LAMP** stack

### Langkah Instalasi

1. **Clone repository** ke direktori web server Anda:
   ```bash
   git clone https://github.com/MrLouisEmmanuel/E-Arsip-Samsat-Banda-Aceh.git
   ```
   Atau ekstrak file ZIP ke folder `htdocs` (XAMPP) / `www` (Laragon).

2. **Buat database** MySQL bernama `arsip_kita`:
   ```sql
   CREATE DATABASE arsip_kita;
   ```

3. **Import skema database** menggunakan salah satu cara:

   - **Via phpMyAdmin:** Import file `arsip_kita.sql`
   - **Via Terminal:**
     ```bash
     mysql -u root arsip_kita < arsip_kita.sql
     ```
   - **Via Browser:** Buka `http://localhost/Project-E-Arsip-SamsatBandaAceh/setup.php`

4. **Konfigurasi koneksi** database di file `koneksi.php` (jika diperlukan):
   ```php
   $host     = 'localhost';
   $dbname   = 'arsip_kita';
   $username = 'root';
   $password = '';
   ```

5. **Akses aplikasi** melalui browser:
   ```
   http://localhost/Project-E-Arsip-SamsatBandaAceh/
   ```

---

## 👤 Akun Default

| Role | Username | Password |
|------|----------|----------|
| Admin | `admin` | `admin` |
| Petugas | `petugas1` | `petugas1` |
| User | *(Daftar melalui halaman Register)* | — |

> ⚠️ **Penting:** Segera ubah password default setelah login pertama kali melalui menu **Profil Saya**.

---

## 📱 Hak Akses per Role

### Admin
- Melihat seluruh data arsip
- Mengelola semua pengguna (CRUD)
- Mengelola kategori arsip
- Mengubah status tracking dokumen
- Melihat seluruh riwayat aktivitas sistem

### Petugas
- Upload & scan dokumen baru
- Mengelola kategori arsip
- Mengubah status tracking dokumen
- Melihat riwayat aktivitas user & petugas

### User (Wajib Pajak)
- Melihat data arsip yang tersedia
- Mengunduh dokumen
- Melihat riwayat download pribadi
- Mengelola profil sendiri

---

## 🌐 Deployment (Produksi)

Untuk deploy ke server publik (contoh: Google Cloud Compute Engine):

1. Sewa VM Instance (misal `e2-micro`, region `asia-southeast2`)
2. Instal LAMP Stack: `sudo apt install apache2 mysql-server php php-mysql php-pdo -y`
3. Upload file proyek ke `/var/www/html/`
4. Import database dan sesuaikan `koneksi.php`
5. Pastikan folder `arsip/` dan `gambar/` memiliki izin tulis: `sudo chown -R www-data:www-data /var/www/html/`

---

## 📄 Lisensi

Proyek ini dibuat untuk keperluan internal Kantor Bersama SAMSAT Kota Banda Aceh, Provinsi Aceh.

---

<p align="center">
  Dibuat dengan ❤️ untuk pelayanan publik yang lebih baik
</p>
