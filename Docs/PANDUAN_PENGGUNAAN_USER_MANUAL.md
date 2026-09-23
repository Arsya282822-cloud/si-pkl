# BUKU PANDUAN PENGGUNA (USER MANUAL)
## SISTEM INFORMASI PRAKTIK KERJA LAPANGAN (SI-PKL)
### SMK LABOR BINAAN FKIP UNRI PEKANBARU

---

## 📌 DAFTAR ISI
1. [Panduan untuk Administrator / Pokja PKL](#1-panduan-administrator--pokja-pkl)
2. [Panduan untuk Guru Pembimbing](#2-panduan-guru-pembimbing)
3. [Panduan untuk Instruktur / Pembimbing Industri (DUDI)](#3-panduan-instruktur--pembimbing-industri-dudi)
4. [Panduan untuk Peserta Didik / Siswa PKL](#4-panduan-peserta-didik--siswa-pkl)
5. [Cara Memasang Aplikasi di HP (Install PWA)](#5-cara-memasang-aplikasi-di-hp-install-pwa)
6. [Tanya Jawab Umum (FAQ) & Solusi Kendala](#6-tanya-jawab-umum-faq--solusi-kendala)

---

## 1. PANDUAN ADMINISTRATOR / POKJA PKL

### A. Membuka & Masuk ke Sistem
1. Buka browser (Google Chrome / Edge) dan akses alamat sistem: `http://localhost:8000/login` (atau domain sekolah).
2. Masukkan email: `admin@admin.com` dan kata sandi: `password`.
3. Klik tombol **Masuk ke Sistem**.

### B. Mengelola Master Data
* **Periode PKL**: Buka menu **Master Data > Periode PKL** untuk menambah tahun ajaran, gelombang, tanggal mulai dan selesai.
* **Perusahaan / DUDI & Akun Instruktur**: Buka menu **Sekolah & Mitra > Perusahaan Mitra DUDI**. Anda dapat menambah mitra industri dan membuatkan akun login Instruktur Lapangan (default password: `dudi1234`).
* **Guru & Siswa**: Buka menu **GTK (Pembimbing)** atau **Peserta Didik**. Anda dapat menginput satu per satu atau menggunakan fitur **Import CSV/Excel**. Password default seluruh guru adalah `guru1234`.

### C. Verifikasi Pengajuan & Penempatan Siswa
1. Buka menu **Pengajuan PKL** untuk melihat usulan tempat magang mandiri dari siswa.
2. Klik tombol **Setujui** atau **Tolak** dengan memberikan alasan.
3. Buka menu **Penempatan PKL** untuk memasangkan siswa ke mitra DUDI dan menunjuk Guru Pembimbingnya.

### D. Mencetak Dokumen Resmi
1. Buka menu **Pusat Cetak Dokumen**.
2. Pilih tab dokumen yang diinginkan:
   - **Surat Pengantar PKL** (Per siswa atau Batch per Perusahaan).
   - **Surat Tugas Pembimbing**.
   - **Surat Penarikan PKL**.
   - **E-Sertifikat PKL** (lengkap dengan QR Code verifikasi).
   - **Rapor Capaian PKL Kurikulum Merdeka**.

### E. Backup Database & Audit Trail
* **Backup Database**: Klik tombol **Backup DB** di kanan atas dashboard untuk mengunduh salinan database SQLite instan.
* **Audit Trail**: Buka menu **Audit Trail / Log Aktivitas** untuk memantau siapa saja yang login, menginput data, atau memvalidasi jurnal beserta alamat IP dan waktunya.

---

## 2. PANDUAN GURU PEMBIMBING (SUPERVISOR SEKOLAH)

### A. Masuk ke Panel Guru
1. Masuk menggunakan akun email guru Anda (contoh: `guru1@guru.com` / `guru1234`).
2. Di Dashboard Guru, Anda akan langsung disajikan:
   - **Pusat Laporan & Notifikasi Khusus dari Instruktur DUDI** (muncul jika ada insiden/laporan kedisiplinan dari instruktur industri).
   - **Rekap Presensi Siswa Hari Ini** (jumlah hadir, izin, sakit, dan belum absen).
   - **Statistik Jurnal yang Tervalidasi oleh Instruktur DUDI**.

### B. Supervisi & Monitoring Jurnal Siswa
1. **Peran Utama**: Jurnal harian dan presensi disetujui serta diverifikasi langsung oleh **Instruktur DUDI**. Guru pembimbing bertindak sebagai pengawas/supervisor akademik.
2. Buka menu **Supervisi Jurnal**.
3. Guru dapat memfilter jurnal: *Semua*, *Disetujui DUDI*, *Menunggu DUDI*, atau *🚨 Laporan Khusus DUDI*.
4. Klik tombol **Detail** untuk membaca uraian kegiatan, melihat foto dokumentasi, dan melihat catatan evaluasi dari Instruktur DUDI.
5. Guru pembimbing dapat menambahkan catatan bimbingan atau menghubungi siswa langsung via tombol **WhatsApp**.

### C. Memantau Lokasi Siswa di Peta Live
1. Buka menu **Observasi & Supervisi > Pantauan Peta Live**.
2. Anda dapat melihat titik lokasi seluruh siswa bimbingan yang tersebar di peta Pekanbaru & Riau secara visual.

### D. Cetak Blanko, Catat Kunjungan & Unggah Berkas Observasi
1. **Cetak Blanko Kosong**: Buka menu **Observasi & Supervisi > Cetak Blanko Observasi** jika ingin membawa form fisik saat kunjungan supervisi.
2. **Input Hasil Kunjungan**:
   - Buka menu **Observasi & Supervisi > Berkas Observasi & Catatan**.
   - Klik **Catat & Unggah Berkas Baru**.
   - Pilih tempat PKL, tanggal kunjungan, evaluasi kesesuaian kompetensi, kedisiplinan, catatan pengamatan, serta foto dokumentasi.
   - Unggah scan lembar instrumen yang telah ditandatangani dan distempel pihak DUDI (format PDF / Foto).
   - Klik **Simpan Berkas & Observasi**.
3. **Cetak Berita Acara**: Pada tabel riwayat observasi, klik tombol **Cetak Berita Acara** untuk mencetak dokumen supervisi resmi.

### E. Input Penilaian PKL & Rapor
1. Buka menu **Penilaian PKL**.
2. Klik tombol **Input Nilai** pada siswa yang telah menyelesaikan PKL.
3. Masukkan nilai aspek teknis (*Hard Skill*), non-teknis (*Soft Skill*), dan catatan guru.
4. Klik **Simpan Nilai**. Sistem akan otomatis mengkalkulasi Nilai Akhir dan siap mencetak Rapor Kurikulum Merdeka.

---

## 3. PANDUAN INSTRUKTUR / PEMBIMBING INDUSTRI (DUDI)

### A. Masuk ke Panel Instruktur DUDI
1. Masuk menggunakan akun email instruktur masing-masing perusahaan (contoh: `dudi1@dudi.com`, `dudi2@dudi.com`, dst. dengan kata sandi: `dudi1234`).
2. Di Dashboard Instruktur, Anda dapat melihat peserta magang di perusahaan Anda, rekap absensi hari ini, serta daftar jurnal harian yang butuh persetujuan.

### B. Memantau & Menyetujui Presensi Siswa di Industri
1. Buka menu **Presensi Siswa**.
2. Anda dapat memantau jam masuk, jam pulang, surat izin/sakit, dan foto kehadiran siswa magang setiap hari.

### C. Validasi Jurnal Harian & Mengirimkan Laporan ke Guru
1. **Peran Utama**: Instruktur DUDI adalah **Penilai & Pemberi Persetujuan Utama** bagi jurnal kerja harian siswa.
2. Buka menu **Validasi Jurnal**.
3. Klik tombol **Detail & Catatan** pada jurnal yang dikirim siswa:
   - Pilih status: **Disetujui** atau **Ditolak / Perlu Revisi**.
   - Tuliskan masukan atau arahan kerja di kolom catatan.
   - **Fitur Laporan Khusus ke Guru**: Jika siswa melakukan pelanggaran, absensi bermasalah, kendala keselamatan, atau prestasi luar biasa, aktifkan sakelar **"Teruskan sebagai Laporan Khusus ke Guru Pembimbing"**. Laporan ini otomatis memicu peringatan berprioritas tinggi di Dashboard Guru Pembimbing.
4. Klik **Simpan & Terapkan Validasi**.

### D. Penilaian Kinerja Industri (Asesmen DUDI)
1. Buka menu **Penilaian DUDI**.
2. Input nilai kinerja lapangan untuk siswa yang bersangkutan. Nilai ini menjadi rujukan utama bagi sekolah.
2. Klik **Input Nilai** pada peserta magang yang akan dinilai.
3. Berikan penilaian berdasarkan 3 komponen:
   - **Sikap & Kedisiplinan Kerja (Bobot 30%)**: Etika, kehadiran, SOP & keselamatan kerja.
   - **Keterampilan Kerja / Teknis (Bobot 50%)**: Kecepatan, ketelitian, dan hasil tugas praktis.
   - **Pengetahuan Industri (Bobot 20%)**: Pemahaman alur kerja dan wawasan industri.
4. Tuliskan catatan rekomendasi karir/evaluasi untuk siswa, lalu klik **Simpan Nilai Siswa**.

---

## 4. PANDUAN PESERTA DIDIK / SISWA PKL

### A. Masuk ke Akun Siswa
1. Masuk menggunakan akun siswa Anda (contoh: `siswa1@siswa.com` / `password`).
2. Pastikan data profil, kelas, dan jurusan Anda sudah benar.

### B. Melakukan Presensi Harian (GPS)
1. Buka menu **Presensi Harian**.
2. Izinkan browser untuk mengakses lokasi perangkat Anda (*Allow Location Access*).
3. **Presensi Masuk**: Klik tombol **Presensi Masuk** saat Anda tiba di tempat kerja (koordinat GPS otomatis terdeteksi).
4. **Presensi Pulang**: Klik tombol **Presensi Pulang** saat jam kerja magang selesai.
5. *Jika izin GPS ditolak/tidak aktif di HP Anda, konfirmasi dialog pesan untuk melanjutkan presensi cadangan (Fallback).*

### C. Mengisi Jurnal Kegiatan Harian
1. Buka menu **Jurnal Harian PKL > Tambah Jurnal Baru**.
2. Pilih tanggal kegiatan.
3. Tuliskan rincian tugas/pekerjaan yang dilakukan di tempat magang.
4. Unggah foto bukti dokumentasi kerja (maksimal 2 MB).
5. Klik **Simpan Jurnal**.
6. Pantau status validasi dari Guru Pembimbing & Instruktur Industri (*Menunggu / Disetujui / Ditolak*).

### D. Mengajukan Izin atau Sakit
1. Buka menu **Presensi Harian > Ajukan Izin / Sakit**.
2. Pilih status (Izin / Sakit), tuliskan alasan/keterangan lengkap, lalu klik kirim.

### E. Melihat Rapor & Mengunduh Sertifikat
1. Buka menu **Rapor & Nilai** untuk melihat rekap nilai dan capaian pembelajaran Anda.
2. Buka menu **E-Sertifikat** untuk mengunduh sertifikat resmi bertanda tangan sekolah & QR Code verifikasi.

---

## 5. CARA MEMASANG APLIKASI DI HP (INSTALL PWA)

Aplikasi SI-PKL ini telah mendukung teknologi **Progressive Web App (PWA)** sehingga dapat dipasang langsung di layar utama smartphone tanpa perlu mengunduh dari Play Store:

### Pada HP Android (Google Chrome):
1. Buka browser Chrome di HP dan kunjungi website SI-PKL.
2. Tekan menu titik tiga **(⋮)** di pojok kanan atas browser.
3. Pilih menu **"Tambahkan ke Layar Utama"** (*Add to Home screen*) atau klik tombol **"Install Aplikasi"** yang muncul di bagian bawah.
4. Ikon **SI-PKL** akan muncul di menu HP Anda dan dapat dibuka layaknya aplikasi native!

### Pada iPhone / iPad (Safari):
1. Buka browser Safari dan kunjungi website SI-PKL.
2. Tekan tombol **Bagikan** (*Share Button* - ikon kotak dengan panah ke atas di bagian bawah).
3. Gulir ke bawah dan pilih **"Tambahkan ke Layar Utama"** (*Add to Home Screen*).
4. Tekan **Tambah** di pojok kanan atas.

---

## 6. TANYA JAWAB UMUM (FAQ) & SOLUSI KENDALA

* **Q: Bagaimana akun Instruktur DUDI didapatkan oleh pihak industri?**  
  **A:** Admin Pokja PKL membuatkan akun dari menu **Perusahaan Mitra DUDI** dengan email resmi perusahaan/instruktur dan password default `dudi1234`.

* **Q: Mengapa saat presensi muncul pesan "Gagal mengambil lokasi"?**  
  **A:** Pastikan GPS HP Anda aktif dan izin lokasi pada browser Chrome/Safari sudah diatur ke *Allow / Izinkan*. Jika tetap tidak bisa, klik tombol "Lanjutkan Presensi Tanpa GPS" pada kotak dialog konfirmasi.

* **Q: Bagaimana jika siswa salah menginput jurnal harian?**  
  **A:** Selama jurnal masih berstatus *Menunggu Validasi*, siswa dapat mengklik tombol **Edit** untuk memperbaiki tulisan atau mengganti foto.

* **Q: Bagaimana cara memverifikasi keaslian E-Sertifikat PKL?**  
  **A:** Siapa pun dapat memindai (*scan*) QR Code yang ada di pojok sertifikat menggunakan kamera HP. Kamera akan otomatis membuka link verifikasi resmi SMK Labor FKIP UNRI yang menampilkan nama siswa, nilai, dan tanggal penerbitan asli.

