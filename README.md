# 📁 E-Arsip Samsat Banda Aceh

Sistem Manajemen Arsip Digital Kendaraan Bermotor untuk Kantor Bersama SAMSAT Kota Banda Aceh, Provinsi Aceh.

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?logo=mysql&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green)

---

## Panduan pengembangan

Buka `http://localhost/Project-E-Arsip-SamsatBandaAceh/login`. Apache 2.4 memerlukan mod_rewrite dan izin membaca aturan Options, FileInfo serta Indexes dari .htaccess. Bookmark halaman lama diarahkan ke URL baru.

- Ubah navigasi dan markup dashboard di `app/views/dashboard.php`. Identitas visual dan pemuatan aset bersama berada di `app/views/layout.php`. CSS dan JavaScript portal berada di `assets/css/modern-style.css` dan `assets/js/portal.js`.
- Tambahkan halaman per fitur di `pages/`, lalu daftarkan hak aksesnya di `dashboard.php`. Pengaturan sesi berada di `app/session.php`, sedangkan fungsi akses arsip, kueri, dan CSRF berada di `app/portal.php`.
- Atur koneksi melalui variabel lingkungan `EARSIP_DB_HOST`, `EARSIP_DB_NAME`, `EARSIP_DB_USER`, dan `EARSIP_DB_PASSWORD` pada proses Apache dan PHP CLI. Gunakan akun database khusus aplikasi pada server operasional.
- Unduhan dan pratinjau melewati rute `download` untuk pemeriksaan hak akses. Direktori `arsip/` dan modul internal dibatasi oleh Apache.

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

## Contoh arsip pembayaran pajak

Masuk, buka **Data arsip**, lalu cari `DEMO-PKB` untuk melihat lembar penerimaan berkas, ringkasan data STNK, dan rekap pembayaran pajak. Ketiganya merupakan PDF satu halaman dengan data fiktif yang tersedia bagi akun terdaftar. Instalasi baru memuat contoh ini dari `arsip_kita.sql`.

Lambang lalu lintas berasal dari aset awal proyek. Logo Pancacita dan Jasa Raharja disimpan lokal dari publikasi instansi. [Pemerintah Aceh](https://bpba.acehprov.go.id/media/2022.09/logo_pancacita1.png) dan [Jasa Raharja](https://jasaraharja.co.id/id).

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
| Ikon | Font Awesome lokal |
| Tipografi | Sans-serif sistem dengan palet navy dan emas |
| Umpan balik | Pesan formulir dan notifikasi sesi |
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
   - **Via PHP CLI untuk database kosong:** jalankan `php setup.php`. Untuk instalasi yang sudah memakai tabel `users` terpadu, jalankan `php alter_db.php` sekali. Migrasi menambahkan kolom penerima dan aksi riwayat tanpa menghapus data.

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
| Admin | `admin` | `admin123` |
| Petugas | `petugas1` | `petugas1` |
| User | *(Daftar melalui halaman Register)* | — |

> ⚠️ **Penting:** Segera ubah password default setelah login pertama kali melalui menu **Profil Saya**.

---

## 📱 Hak Akses per Role

### Admin
- Melihat seluruh data arsip
- Mengelola semua pengguna (CRUD)
- Mengelola kategori arsip
- Mengubah status, metadata, dan penerima dokumen
- Melihat seluruh riwayat aktivitas sistem

### Petugas
- Upload & scan dokumen baru dengan memilih satu akun wajib pajak sebagai penerima
- Mengelola kategori arsip
- Mengubah status, metadata, dan penerima dokumen
- Melihat riwayat aktivitas user & petugas

### User (Wajib Pajak)
- Melihat ringkasan, mencari dokumen, dan membuka pratinjau arsip yang ditujukan ke akun sendiri. Arsip lama tanpa penerima hanya terlihat oleh admin dan petugas sampai penerima ditetapkan melalui detail dokumen.
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
