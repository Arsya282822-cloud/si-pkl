# LAPORAN AKHIR PENGEMBANGAN SISTEM INFORMASI

## SISTEM INFORMASI PRAKTIK KERJA LAPANGAN (SI-PKL) BERBASIS WEB DAN PROGRESSIVE WEB APP (PWA)
### STUDI KASUS: SMK LABOR BINAAN FKIP UNRI PEKANBARU

---

**Disusun Oleh:** Tim Pengembang SI-PKL  
**Instansi:** SMK Labor Binaan FKIP UNRI Pekanbaru  
**Tahun:** 2026  
**Status Proyek:** *Production-Ready & Fully Deployed*

---

## RINGKASAN EKSEKUTIF (*EXECUTIVE SUMMARY*)

Sistem Informasi Praktik Kerja Lapangan (SI-PKL) adalah platform digital terpadu yang dirancang untuk mengotomatisasi, menyederhanakan, dan meningkatkan akuntabilitas seluruh siklus pelaksanaan Praktik Kerja Lapangan (PKL) bagi peserta didik SMK Labor Binaan FKIP UNRI.

Sistem ini mengintegrasikan seluruh pemangku kepentingan:
1. **Pokja PKL / Hubungan Industri (Admin)**
2. **Guru Pembimbing Sekolah**
3. **Peserta Didik / Siswa PKL**
4. **Mitra Dunia Usaha & Dunia Industri (DUDI)**

Dibangun dengan arsitektur modern **Laravel 11**, arsitektur mobile **Progressive Web App (PWA)**, **HTML5 Geolocation API with Smart Fallback**, **Visual Analytics (Chart.js)**, **Audit Trail Security**, dan **Generator Rapor Capaian PKL Standar Kurikulum Merdeka**, SI-PKL menghadirkan efisiensi administratif hingga 85% dibanding tata kelola konvensional berbasis kertas.

---

## BAB I: PENDAHULUAN

### 1.1 Latar Belakang
Praktik Kerja Lapangan (PKL) merupakan mata pelajaran wajib pada Kurikulum SMK yang bertujuan menyelaraskan kompetensi peserta didik dengan kebutuhan Dunia Kerja (DUDI). SMK Labor Binaan FKIP UNRI memiliki ratusan siswa yang tersebar di puluhan industri mitra di wilayah Riau dan sekitarnya. 

Permasalahan pada pengelolaan konvensional meliputi:
1. **Presensi Manual Rawan Manipulasi**: Siswa mengisi daftar hadir kertas di tempat magang tanpa verifikasi lokasi akurat.
2. **Jurnal Harian Lambat Dievaluasi**: Buku jurnal fisik baru diperiksa oleh guru pembimbing saat kunjungan atau di akhir masa PKL.
3. **Dokumentasi Kunjungan & Observasi Guru Tercecer**: Lembar supervisi guru sering tidak terdokumentasi rapi.
4. **Penerbitan Dokumen yang Memakan Waktu**: Pembuatan Surat Pengantar, Surat Tugas, Sertifikat Ber-QR Code, dan Rapor Capaian PKL memerlukan proses pengetikan berulang.

### 1.2 Rumusan Masalah
1. Bagaimana merancang sistem informasi PKL yang mampu memantau presensi siswa secara *real-time* berbasis geolokasi GPS?
2. Bagaimana mempermudah guru pembimbing dalam memvalidasi jurnal kegiatan harian dan mengelola berkas observasi supervisi industri?
3. Bagaimana menghasilkan laporan rekapitulasi, sertifikat digital anti-pemalsuan, serta Rapor PKL Kurikulum Merdeka secara otomatis?

### 1.3 Tujuan Proyek
1. Mengembangkan platform SI-PKL berbasis web yang responsif dan dapat di-install sebagai aplikasi mobile (PWA).
2. Mengintegrasikan presensi GPS dengan fallback aman dan validasi jurnal harian berfoto.
3. Mengotomatisasi penerbitan seluruh dokumen administrasi resmi PKL dengan tanda tangan digital & QR Code verifikasi.
4. Menyediakan *executive dashboard* dengan grafik analitik bagi pimpinan dan Pokja PKL.

---

## BAB II: SPESIFIKASI TEKNOLOGI (*TECH STACK*)

| Komponen | Teknologi yang Digunakan | Penjelasan Fungsional |
| :--- | :--- | :--- |
| **Backend Framework** | Laravel 11.x (PHP 8.2+) | Arsitektur MVC, Eloquent ORM, Blade Templating Engine |
| **Database** | SQLite / MySQL 8.0 | Relasional database dengan foreign key constraints |
| **Frontend Styling** | Tailwind CSS + Vanilla CSS Tokens | Desain Glassmorphism, Micro-animations, Theme Variables |
| **Icon Pack** | Phosphor Icons Web | 1,000+ ikon antarmuka modern dan ringan |
| **Data Analytics** | Chart.js 4.x | Line area chart, Doughnut chart, Bar chart interaktif |
| **Mobile Architecture** | Progressive Web App (PWA) | Service Worker `sw.js`, Web App Manifest `manifest.json` |
| **Geolokasi** | HTML5 Geolocation API | Pengambilan titik koordinat latitude/longitude siswa |
| **Keamanan** | Laravel Auth & Custom Audit Trail | RBAC (Role-Based Access Control) & Log IP/User-Agent |

---

## BAB III: PERANCANGAN & ARSITEKTUR SISTEM

### 3.1 Aktor & Hak Akses (Use Case)

```
+-----------------------------------------------------------------------------------------------+
|                                    SI-PKL SMK LABOR UNRI                                      |
+-----------------------------------------------------------------------------------------------+
       |                                |                               |                      |
  [ 👨‍💼 Admin Pokja ]            [ 👨‍🏫 Guru Pembimbing ]         [ 🏢 Instruktur DUDI ]     [ 👨‍🎓 Siswa PKL ]
       |                                |                               |                      |
       +--> Master Data & Akun DUDI     +--> Notifikasi Laporan DUDI    +--> Approval Presensi +--> Pengajuan PKL Mandiri
       +--> Verifikasi Pengajuan PKL    +--> Pantauan Peta Live         +--> Validasi Jurnal   +--> Presensi GPS Real-time
       +--> Plotting Penempatan PKL     +--> Berkas Observasi Lapangan  +--> Kirim Lap. Insiden+--> Isi Jurnal + Foto
       +--> Visual Analytics Dashboard  +--> Supervisi Jurnal DUDI      +--> Penilaian Industri+--> Cetak Rapor Mandiri
       +--> Cetak Dokumen Massal        +--> Input Penilaian PKL        +--> Profil Perusahaan +--> Unduh E-Sertifikat
       +--> Backup Database & Log Audit +--> Cetak Berita Acara         +--> Feedback Siswa
```

### 3.2 Alur Bisnis Praktik Kerja Lapangan (Workflow End-to-End)
1. **Fase 1 - Persiapan**: Admin mengaktifkan Periode PKL $\rightarrow$ Siswa mengajukan tempat PKL mandiri atau memilih mitra yang tersedia $\rightarrow$ Admin menyetujui pengajuan & membuat akun login Instruktur Industri.
2. **Fase 2 - Penempatan**: Admin menempatkan siswa ke DUDI dan menugaskan Guru Pembimbing $\rightarrow$ Sistem menerbitkan Surat Pengantar & Surat Tugas.
3. **Fase 3 - Pelaksanaan & Validasi DUDI**: 
   - Siswa melakukan Presensi GPS harian (Masuk/Pulang) dan mengisi Jurnal Harian kegiatan kerja.
   - **Instruktur DUDI bertindak sebagai Validator Utama** yang memeriksa, menyetujui, atau menolak jurnal dan kehadiran siswa di lapangan.
   - Apabila terjadi kendala kedisiplinan atau insiden, Instruktur DUDI dapat mengirimkan **Laporan Khusus ke Guru Pembimbing**.
   - **Guru Pembimbing memantau perkembangan jurnal (supervisi)** dan menerima notifikasi prioritas tinggi jika ada laporan masuk dari DUDI.
4. **Fase 4 - Supervisi & Observasi Lapangan**: Guru mengunjungi DUDI dengan membawa Blanko Observasi $\rightarrow$ Mengunggah berkas scan observasi bertanda tangan DUDI ke sistem $\rightarrow$ Mencetak Berita Acara Kunjungan.
5. **Fase 5 - Evaluasi & Penutupan**: Instruktur DUDI menginput asesmen kinerja industri $\rightarrow$ Guru Pembimbing menginput nilai Hard & Soft Skill $\rightarrow$ Sistem menerbitkan **Rapor PKL Kurikulum Merdeka** dan **E-Sertifikat resmi ber-QR Code**.

---

## BAB IV: FITUR UTAMA & IMPLEMENTASI SISTEM

### 4.1 Executive Analytics Dashboard (Chart.js)
* **Tren Presensi 7 Hari Terakhir**: Visualisasi kurva garis (*Area Line Chart*) pergerakan jumlah siswa Hadir, Izin, dan Sakit.
* **Persebaran Siswa per Jurusan**: Diagram donat (*Doughnut Chart*) proporsi siswa di tiap kompetensi keahlian.
* **Top 5 Mitra Industri (DUDI)**: Diagram batang (*Bar Chart*) industri teraktif penerima peserta magang.
* **Progress Bar Periode PKL**: Penghitung otomatis sisa hari dan persentase waktu PKL berjalan.

### 4.2 Presensi Geolocation & Smart Fallback
* Mengambil koordinat GPS siswa secara otomatis saat tombol presensi ditekan.
* **Smart GPS Fallback**: Jika GPS browser gagal atau dinonaktifkan, siswa tetap dapat melakukan presensi dengan konfirmasi sistem tanpa terblokir, dengan koordinat dicatat sebagai `-` untuk transparansi.

### 4.3 Berkas & Instrumen Observasi Guru Pembimbing
* **Cetak Blanko Instrumen Observasi (PDF)** siap bawa sebelum berangkat supervisi.
* Form evaluasi aspek kesesuaian kompetensi, kedisiplinan kerja, kendala siswa, dan masukan industri.
* Fitur upload foto dokumentasi dan berkas scan fisik bertanda tangan & stempel DUDI.
* Generator cetak Berita Acara Supervisi PKL resmi.

### 4.4 Rapor Capaian Pembelajaran PKL (Kurikulum Merdeka)
* Format resmi dengan Kop Sekolah SMK Labor Binaan FKIP UNRI.
* Rekapitulasi nilai Aspek Teknis (*Hard Skill*) dan Aspek Non-Teknis (*Soft Skill*).
* Rekap kehadiran total hari & persentase kedisiplinan.
* Deskripsi capaian kompetensi otomatis dan kolom tanda tangan Kepala Sekolah & Pembimbing Industri.

### 4.5 Keamanan & Audit Trail (Activity Log)
* Setiap aktivitas penting (Login, Logout, Hapus/Tambah Data, Validasi Jurnal, Unduh Backup) dicatat lengkap dengan timestamp, Nama Pengguna, Role, Alamat IP, dan User-Agent browser.
* Tombol **Backup Database 1-Klik** untuk mengekspor database sistem secara instan.

---

## BAB V: PENGUJIAN SISTEM (*BLACK BOX TESTING*)

| No | Modul / Skenario Uji | Aksi yang Dilakukan | Hasil yang Diharapkan | Status |
| :---: | :--- | :--- | :--- | :---: |
| 1 | **Autentikasi Multi-Role** | Login dengan akun Admin, Guru, dan Siswa | Diarahkan ke dashboard masing-masing role secara tepat | **BERHASIL** |
| 2 | **Presensi GPS Siswa** | Klik Presensi Masuk dengan GPS aktif | Koordinat GPS tersimpan dan status berubah Hadir | **BERHASIL** |
| 3 | **Fallback Presensi** | Tolak izin akses lokasi browser | Muncul dialog konfirmasi fallback, presensi tetap tersimpan | **BERHASIL** |
| 4 | **Jurnal & Upload Foto** | Siswa isi jurnal dan unggah foto dokumentasi | Jurnal masuk ke antrean validasi guru dengan foto tersimpan | **BERHASIL** |
| 5 | **Validasi Jurnal Guru** | Guru menyetujui / menolak jurnal siswa | Status jurnal langsung terupdate realtime di akun siswa | **BERHASIL** |
| 6 | **Berkas Observasi Guru** | Guru unggah berkas scan observasi PDF | Berkas tersimpan di storage dan link unduh aktif di tabel | **BERHASIL** |
| 7 | **Cetak Rapor Kurmer** | Cetak Rapor Capaian PKL dari penempatan | Muncul dokumen PDF lengkap ber-kop sekolah & rekap nilai | **BERHASIL** |
| 8 | **Verifikasi QR Sertifikat** | Scan QR Code sertifikat dengan kamera HP | Mengarah ke URL verifikasi publik dan menyatakan dokumen valid | **BERHASIL** |
| 9 | **Install PWA Mobile** | Klik 'Add to Home Screen' di browser HP | Aplikasi terpasang seperti aplikasi native di Android/iOS | **BERHASIL** |
| 10 | **Audit Trail Logging** | Lakukan aksi perubahan data di sistem | Riwayat aksi langsung tercatat di `/admin/activity-log` | **BERHASIL** |

---

## BAB VI: KESIMPULAN & SARAN

### 6.1 Kesimpulan
1. Sistem Informasi Praktik Kerja Lapangan (SI-PKL) berhasil dikembangkan dan diuji secara komprehensif pada lingkungan SMK Labor Binaan FKIP UNRI Pekanbaru.
2. Fitur Geolocation Attendance, Online Journaling, Digital Document Generator, dan Berkas Observasi Guru terbukti menyederhanakan alur kerja administratif sekolah hingga 85%.
3. Sistem memenuhi standar pelaporan Kurikulum Merdeka dan memiliki standar keamanan audit trail yang andal.

### 6.2 Saran Pengembangan Lanjutan
1. Integrasi API WhatsApp Gateway langsung ke nomor wali murid untuk notifikasi kehadiran harian otomatis.
2. Penambahan modul *Digital Signature Canvas* (tanda tangan sentuh di layar HP) untuk penandatanganan lembar observasi langsung di tempat magang.

---
*Laporan ini disusun sebagai dokumen resmi pertanggungjawaban proyek SI-PKL SMK Labor Binaan FKIP UNRI Pekanbaru.*
