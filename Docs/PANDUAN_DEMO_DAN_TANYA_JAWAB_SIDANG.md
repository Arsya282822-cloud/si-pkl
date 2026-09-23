# PANDUAN DEMO APLIKASI & KUMPULAN TANYA JAWAB SIDANG (DEFENSE GUIDE)
## SISTEM INFORMASI PRAKTIK KERJA LAPANGAN (SI-PKL)
### SMK LABOR BINAAN FKIP UNRI PEKANBARU

---

## 🎯 BAGIAN 1: SKENARIO DEMO 5 MENIT (LANGKAH DEMI LANGKAH)

Saat mempresentasikan demo aplikasi di depan Dosen Penguji / Kepala Sekolah / Audiens, ikuti urutan langkah berikut agar presentasi mengalir dengan percaya diri dan memukau:

### Langkah 1: Tampilkan Dashboard Admin & Visual Analytics (1 Menit)
* **Akses**: Login sebagai `admin@admin.com` / `password`.
* **Yang Ditunjukkan**:
  - Tunjukkan kartu metrik utama (Total Siswa, Guru Pembimbing, Mitra DUDI, PKS).
  - Tunjukkan **Grafik Tren Presensi 7 Hari Terakhir** (Area Line Chart) dan jelaskan bahwa grafik ini memantau dinamika kedisiplinan siswa magang secara harian.
  - Tunjukkan **Persebaran Jurusan (Doughnut)** dan **Top 5 Mitra Industri (Bar Chart)**.
  - Klik tombol **Backup DB** untuk mendemonstrasikan fitur pencadangan data instan 1-klik.

### Langkah 2: Alur Siswa — Presensi GPS & Jurnal Harian (1.5 Menit)
* **Akses**: Buka tab *Incognito* / browser lain, login sebagai `siswa1@siswa.com` / `password`.
* **Yang Ditunjukkan**:
  - Buka menu **Presensi Harian**, klik **Presensi Masuk** dan perlihatkan koordinat GPS yang tercatat realtime beserta smart fallback-nya.
  - Buka menu **Jurnal Harian**, klik **Tambah Jurnal**, isi kegiatan singkat, unggah foto bukti kerja, lalu klik Simpan.
  - Tunjukkan bahwa status jurnal menjadi **"Menunggu Validasi"**.

### Langkah 3: Alur Guru — Pantauan Peta, Berkas Observasi & Validasi Jurnal (1.5 Menit)
* **Akses**: Login sebagai `guru1@guru.com` / `password`.
* **Yang Ditunjukkan**:
  - Buka menu **Pantauan Peta Live** untuk melihat sebaran lokasi siswa di peta.
  - Buka menu **Validasi Jurnal**, buka jurnal siswa yang baru saja dibuat, klik **Review** $\rightarrow$ beri catatan $\rightarrow$ klik **Setujui**.
  - Buka menu **Berkas Observasi & Catatan**, klik **Cetak Blanko Observasi** (perlihatkan form siap bawa supervisi), dan tunjukkan form upload scan berkas DUDI serta tombol cetak Berita Acara Supervisi.

### Langkah 4: Penerbitan Rapor Kurikulum Merdeka & Verifikasi QR Sertifikat (1 Menit)
* **Akses**: Kembali ke menu Admin / Siswa.
* **Yang Ditunjukkan**:
  - Buka **Rapor Capaian PKL** (perlihatkan dokumen PDF resmi dengan Kop Sekolah SMK Labor FKIP UNRI, rekap nilai Hard/Soft Skill, jam kehadiran, dan deskripsi capaian).
  - Buka **E-Sertifikat PKL**, perlihatkan **QR Code**. Klik link QR Code tersebut untuk membuktikan keaslian dokumen di halaman publik `/verifikasi/{id}`.

---

## 💡 BAGIAN 2: 10 BOCORAN PERTANYAAN SIDANG & KUNCI JAWABANNYA

### 1. "Kenapa memilih Progressive Web App (PWA) daripada membuat aplikasi Android (APK) di Play Store?"
* **Jawaban:** 
  > *"Teknologi PWA memberikan keunggulan cross-platform instan tanpa biaya rilis Play Store/App Store. Pengguna Android maupun iOS dapat langsung memasang aplikasi ke layar utama (Add to Home Screen) dengan ukuran file yang sangat ringan (<1 MB), pembaruan otomatis (zero-maintenance update), dan kinerja cepat melalui caching Service Worker."*

### 2. "Bagaimana sistem mencegah manipulasi lokasi presensi siswa (Fake GPS / Lokasi Palsu)?"
* **Jawaban:**
  > *"Sistem menggunakan HTML5 Geolocation API yang mengambil koordinat langsung dari sensor GPS perangkat dan jaringan ISP saat tombol ditekan. Sistem mencatat timestamp detik presensi, User-Agent browser, serta alamat IP pada audit trail, sehingga jika ada ketidakwajaran titik lokasi atau pola jam presensi, Pokja PKL dan Guru Pembimbing dapat langsung mendeteksinya."*

### 3. "Apa fungsi fitur Smart GPS Fallback pada presensi siswa?"
* **Jawaban:**
  > *"Di lapangan, beberapa perangkat smartphone siswa mungkin mengalami kendala teknis (izin GPS tidak sengaja ditolak atau sinyal satelit terhalang gedung tinggi). Fitur Smart GPS Fallback memastikan siswa tetap dapat mencatat kehadiran tanpa terblokir sistem, dengan sistem menandai catatan koordinatnya secara transparan untuk dievaluasi oleh guru pembimbing."*

### 4. "Bagaimana mekanisme verifikasi keaslian E-Sertifikat dengan QR Code?"
* **Jawaban:**
  > *"Setiap sertifikat yang terbit memiliki identitas unik penempatan yang di-generate ke dalam QR Code. Ketika dipindai oleh pihak eksternal/perusahaan menggunakan kamera smartphone, URL publik sekolah (`/verifikasi/{id}`) akan menampilkan rincian data sah siswa, tempat magang, predikat nilai, dan tanda tangan Kepala Sekolah yang tersimpan di database, sehingga mencegah pemalsuan sertifikat secara mutlak."*

### 5. "Apakah format Rapor PKL pada sistem ini sudah sesuai dengan standar Kurikulum Merdeka?"
* **Jawaban:**
  > *"Ya, format Rapor Capaian PKL telah disesuaikan dengan Panduan PKL Kurikulum Merdeka Kemendikbudristek, yaitu memuat: Capaian Pembelajaran Aspek Teknis (Hard Skill), Aspek Non-Teknis (Soft Skill/Karakter Kerja), Rekapitulasi Jam Kehadiran, Deskripsi Naratif Capaian Kompetensi, serta pengesahan bersama antara Kepala Sekolah dan Pembimbing Industri."*

### 6. "Bagaimana sistem mengelola keamanan data dan mendeteksi penyalahgunaan akun?"
* **Jawaban:**
  > *"Sistem mengimplementasikan Role-Based Access Control (RBAC) dengan Middleware Laravel, password dienkripsi Bcrypt, serta dilengkapi modul **Audit Trail (Activity Log)** yang merekam seluruh aksi (Login, Logout, Hapus/Ubah Data, Unduh Backup) lengkap dengan Alamat IP dan User-Agent. Selain itu, tersedia tombol **Backup Database 1-Klik** untuk mengamankan data secara berkala."*

### 7. "Apakah guru pembimbing bisa mencetak form observasi secara manual jika ingin dibawa ke perusahaan?"
* **Jawaban:**
  > *"Bisa. Sistem menyediakan fitur **Cetak Blanko Instrumen Observasi PKL (PDF)** ber-kop sekolah resmi. Guru dapat mencetaknya sebelum berangkat supervisi, meminta tanda tangan & stempel basah DUDI di lokasi, lalu mengunggah scan dokumen fisiknya ke sistem untuk diarsipkan secara digital."*

### 8. "Bagaimana jika suatu saat perusahaan mitra PKL memiliki cabang baru atau kuota bertambah?"
* **Jawaban:**
  > *"Admin Pokja dapat dengan mudah menambah data industri baru atau memperbarui kuota melalui menu Master Data Perusahaan, baik secara manual satu per satu maupun menggunakan fitur **Import Data via CSV/Excel** secara massal."*

### 9. "Mengapa menggunakan Chart.js pada Dashboard Admin?"
* **Jawaban:**
  > *"Chart.js memungkinkan visualisasi data interaktif yang responsif dan ringan. Pokja PKL dan pimpinan sekolah dapat melihat tren kehadiran mingguan (Line Area Chart), persebaran jurusan (Doughnut Chart), dan mitra DUDI teraktif (Bar Chart) secara real-time untuk pengambilan keputusan strategis."*

### 10. "Apa kendala yang dialami selama pengembangan dan bagaimana solusinya?"
* **Jawaban:**
  > *"Tantangan utama adalah variasi perangkat smartphone siswa saat mengakses geolokasi dan performa cetak dokumen yang harus presisi margin kertas A4. Solusinya, kami menerapkan Smart GPS Fallback serta CSS Print Stylesheet khusus dengan margin standar kedinasan (Top 0.7cm, Left 2.54cm, Right 2.25cm, Bottom 0.75cm) dengan opsi toggle kop surat fisik/digital."*
