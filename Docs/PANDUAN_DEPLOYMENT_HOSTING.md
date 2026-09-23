# PANDUAN DEPLOYMENT & MENG-ONLINE-KAN APLIKASI
## SI-PKL SMK LABOR BINAAN FKIP UNRI

---

## 📌 1. PRASYARAT SERVER / WEB HOSTING
Untuk meng-online-kan aplikasi SI-PKL pada domain sekolah (misal: `https://pkl.smklabor.sch.id`), pastikan hosting atau VPS Anda memenuhi kriteria berikut:
* **Versi PHP**: PHP 8.2 atau lebih tinggi.
* **Ekstensi PHP Aktif**: `BCMath`, `Ctype`, `cURL`, `DOM`, `Fileinfo`, `JSON`, `Mbstring`, `OpenSSL`, `PDO`, `PDO_MySQL` / `PDO_SQLite`, `Tokenizer`, `XML`.
* **Sertifikat SSL (HTTPS)**: **Wajib Aktif (Let's Encrypt / Comodo)**.  
  *(Catatan: Fitur **PWA** dan **Geolocation GPS Browser** mewajibkan protokol HTTPS agar browser mengizinkan akses kamera & GPS).*

---

## 🚀 2. LANGKAH DEPLOYMENT DI CPANEL (SHARED / CLOUD HOSTING)

### Langkah A: Persiapan Berkas Proyek
1. Buka folder proyek di komputer Anda.
2. Kecualikan (*exclude*) folder `node_modules` dan folder `.git` agar ukuran zip lebih ringan.
3. Kompres seluruh folder proyek ke dalam format `.zip` (misal: `sipkl_app.zip`).

### Langkah B: Upload ke File Manager cPanel
1. Login ke **cPanel** akun hosting Anda.
2. Buka menu **Subdomains** $\rightarrow$ buat subdomain baru (contoh: `pkl.smklabor.sch.id`).
3. Buka menu **File Manager**.
4. Buat folder baru di luar `public_html` bernama `sipkl_source`.
5. Upload file `sipkl_app.zip` ke dalam folder `sipkl_source` lalu lakukan **Extract**.

### Langkah C: Menghubungkan Folder `public` ke Subdomain
1. Pindahkan seluruh isi berkas di dalam folder `sipkl_source/public/` ke dalam direktori Document Root subdomain Anda (contoh: `public_html/pkl/` atau `public_html/`).
2. Buka dan edit file `index.php` yang berada di `public_html/pkl/index.php`:
   ```php
   // Ubah baris ini agar mengarah ke folder sipkl_source:
   require __DIR__.'/../../sipkl_source/vendor/autoload.php';
   $app = require_once __DIR__.'/../../sipkl_source/bootstrap/app.php';
   ```

### Langkah D: Konfigurasi Database & File `.env`
1. Buka menu **MySQL Databases** di cPanel $\rightarrow$ Buat database baru (misal: `smklabor_sipkl`) dan user database beserta password-nya.
2. Berikan izin *ALL PRIVILEGES* user tersebut ke database.
3. Buka file `.env` di dalam folder `sipkl_source/` dan sesuaikan:
   ```env
   APP_NAME="SI-PKL SMK Labor UNRI"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://pkl.smklabor.sch.id

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=smklabor_sipkl
   DB_USERNAME=smklabor_user
   DB_PASSWORD=PasswordDatabaseAnda
   ```
4. Buka menu **phpMyAdmin** $\rightarrow$ Pilih database `smklabor_sipkl` $\rightarrow$ Lakukan Import file database atau jalankan perintah `php artisan migrate --force` via menu **Terminal** di cPanel.

### Langkah E: Optimasi & Caching Produksi
Buka menu **Terminal** di cPanel (atau SSH) lalu jalankan perintah optimasi:
```bash
cd sipkl_source
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
```

---

## 🔒 3. MENGAKTIFKAN HTTPS (SSL GRATIS)
1. Buka menu **SSL/TLS Status** atau **Let's Encrypt SSL** di cPanel.
2. Centang domain / subdomain `pkl.smklabor.sch.id`.
3. Klik tombol **Run AutoSSL** / **Issue SSL Certificate**.
4. Setelah SSL aktif (gembok hijau), buka web di HP untuk mencoba tombol **Presensi Masuk (GPS)** dan menu **Install PWA**.

---

## 📱 4. VERIFIKASI AKHIR DEPLOYMENT
1. Buka `https://pkl.smklabor.sch.id/login`.
2. Login sebagai admin, coba fitur **Cetak Dokumen & Backup DB**.
3. Buka di smartphone Android/iOS, lakukan uji coba **Install PWA** dan **Presensi Masuk/Pulang**.
4. Aplikasi SI-PKL resmi beroperasi penuh secara online! 🌐🎉
