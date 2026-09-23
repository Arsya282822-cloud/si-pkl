# DOKUMENTASI STRUKTUR DATABASE & ARSITEKTUR SISTEM
## SI-PKL SMK LABOR BINAAN FKIP UNRI

---

## 1. STRUKTUR TABEL & DATA DICTIONARY

Berikut adalah tabel-tabel utama yang membentuk basis data sistem SI-PKL:

### 1.1 Tabel `users`
Menyimpan kredensial autentikasi seluruh aktor.
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT (PK, Auto) | ID Unik Pengguna |
| `role_id` | BIGINT (FK) | Relasi ke tabel `roles` (admin / guru / instruktur / siswa) |
| `name` | VARCHAR(255) | Nama Lengkap |
| `email` | VARCHAR(255) (Unique) | Email untuk Login |
| `password` | VARCHAR(255) | Hash Bcrypt Password (Default Guru: `guru1234`, Instruktur: `dudi1234`) |
| `status` | ENUM('aktif', 'nonaktif') | Status Akun |
| `created_at` / `updated_at` | TIMESTAMP | Waktu pembuatan & update |

---

### 1.2 Tabel `pembimbing_industri` (Instruktur DUDI)
Menyimpan identitas pembimbing lapangan perusahaan dan relasi ke akun login.
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT (PK, Auto) | ID Instruktur |
| `perusahaan_id` | BIGINT (FK) | Relasi ke `perusahaan.id` |
| `user_id` | BIGINT (FK, Nullable) | Relasi ke `users.id` (Role: `instruktur`) |
| `nama` | VARCHAR(255) | Nama Lengkap Instruktur Lapangan |
| `jabatan` | VARCHAR(100) | Jabatan di Perusahaan (HRD / SPV / Senior) |
| `no_hp` | VARCHAR(50) | No. WhatsApp Instruktur |
| `email` | VARCHAR(255) | Email Resmi Instruktur |

---

### 1.3 Tabel `siswa`
Menyimpan data identitas peserta didik.
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT (PK, Auto) | ID Siswa |
| `user_id` | BIGINT (FK) | Relasi ke `users.id` |
| `kelas_id` | BIGINT (FK) | Relasi ke `kelas.id` |
| `jurusan_id` | BIGINT (FK) | Relasi ke `jurusan.id` |
| `nis` | VARCHAR(50) (Unique) | Nomor Induk Siswa |
| `nisn` | VARCHAR(50) (Nullable) | Nomor Induk Siswa Nasional |
| `nama` | VARCHAR(255) | Nama Lengkap Siswa |
| `jenis_kelamin` | ENUM('L', 'P') | Jenis Kelamin |
| `no_hp` | VARCHAR(50) | Nomor WhatsApp Siswa |
| `alamat` | TEXT | Alamat Domisili Siswa |

---

### 1.3 Tabel `guru`
Menyimpan data identitas guru pembimbing sekolah.
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT (PK, Auto) | ID Guru |
| `user_id` | BIGINT (FK) | Relasi ke `users.id` |
| `nip` | VARCHAR(50) | NIP / NUPTK Guru |
| `nama` | VARCHAR(255) | Nama Lengkap beserta Gelar |
| `no_hp` | VARCHAR(50) | Nomor WhatsApp Guru |

---

### 1.4 Tabel `perusahaan` (DUDI)
Menyimpan data mitra industri tempat magang.
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT (PK, Auto) | ID Perusahaan |
| `nama_perusahaan` | VARCHAR(255) | Nama PT / CV / Instansi |
| `bidang_usaha` | VARCHAR(255) | Bidang Kerja / Industri |
| `alamat` | TEXT | Alamat Lengkap Perusahaan |
| `pembimbing_industri` | VARCHAR(255) | Nama Instruktur / HRD DUDI |
| `no_hp_pembimbing` | VARCHAR(50) | Nomor Kontak Pembimbing Industri |
| `kuota` | INT | Daya Tampung Siswa |
| `latitude` / `longitude` | VARCHAR(100) | Titik Koordinat Peta |

---

### 1.5 Tabel `penempatan`
Menyimpan relasi penempatan siswa ke DUDI dan Guru Pembimbing.
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT (PK, Auto) | ID Penempatan |
| `siswa_id` | BIGINT (FK) | Relasi ke `siswa.id` |
| `guru_id` | BIGINT (FK) | Relasi ke `guru.id` |
| `perusahaan_id` | BIGINT (FK) | Relasi ke `perusahaan.id` |
| `periode_pkl_id` | BIGINT (FK) | Relasi ke `periode_pkl.id` |
| `status` | VARCHAR(50) | Status Penempatan (aktif / selesai) |

---

### 1.6 Tabel `absensi_pkl`
Menyimpan riwayat presensi harian siswa.
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT (PK, Auto) | ID Absensi |
| `penempatan_id` | BIGINT (FK) | Relasi ke `penempatan.id` |
| `tanggal` | DATE | Tanggal Presensi |
| `jam_masuk` | TIME (Nullable) | Jam Presensi Masuk |
| `jam_keluar` | TIME (Nullable) | Jam Presensi Pulang |
| `lokasi_masuk` | VARCHAR(255) | Koordinat GPS Masuk |
| `lokasi_keluar` | VARCHAR(255) | Koordinat GPS Pulang |
| `status` | ENUM('hadir', 'izin', 'sakit', 'alpha') | Status Kehadiran |
| `keterangan` | TEXT | Alasan Izin / Sakit |

---

### 1.7 Tabel `jurnal_pkl`
Menyimpan catatan kegiatan harian siswa magang.
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT (PK, Auto) | ID Jurnal |
| `penempatan_id` | BIGINT (FK) | Relasi ke `penempatan.id` |
| `tanggal` | DATE | Tanggal Kegiatan |
| `kegiatan` | TEXT | Deskripsi Tugas / Aktivitas |
| `foto` | VARCHAR(255) | Path Foto Bukti Kerja |
| `status_validasi` | ENUM('menunggu', 'disetujui', 'ditolak') | Status Review Guru |
| `catatan_guru` | TEXT (Nullable) | Masukan / Koreksi dari Guru |

---

### 1.8 Tabel `monitoring`
Menyimpan catatan supervisi & berkas observasi guru pembimbing.
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT (PK, Auto) | ID Monitoring |
| `guru_id` | BIGINT (FK) | Relasi ke `guru.id` |
| `perusahaan_id` | BIGINT (FK) | Relasi ke `perusahaan.id` |
| `tanggal_kunjungan` | DATE | Tanggal Kunjungan Supervisi |
| `kesesuaian_kompetensi` | VARCHAR(50) | Evaluasi Silabus vs Tugas DUDI |
| `kedisiplinan_siswa` | VARCHAR(50) | Evaluasi Kedisiplinan Siswa |
| `catatan` | TEXT | Hasil Pengamatan Lapangan |
| `kendala_observasi` | TEXT (Nullable) | Permasalahan Lapangan |
| `saran_dudi` | TEXT (Nullable) | Masukan dari Pihak DUDI |
| `foto` | VARCHAR(255) (Nullable) | Foto Kunjungan Lapangan |
| `file_observasi` | VARCHAR(255) (Nullable) | Scan Berkas Bertanda Tangan DUDI (PDF/JPG) |

---

### 1.9 Tabel `penilaian_pkl`
Menyimpan hasil evaluasi akhir siswa.
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT (PK, Auto) | ID Penilaian |
| `penempatan_id` | BIGINT (FK) | Relasi ke `penempatan.id` |
| `nilai_sikap` | DECIMAL(5,2) | Nilai Soft Skill |
| `nilai_keterampilan` | DECIMAL(5,2) | Nilai Hard Skill / Teknis |
| `nilai_pengetahuan` | DECIMAL(5,2) | Nilai Teori Kerja |
| `nilai_akhir` | DECIMAL(5,2) | Kalkulasi Nilai Komprehensif |
| `catatan_guru` | TEXT | Deskripsi Capaian Kompetensi |

---

### 1.10 Tabel `activity_logs` (Audit Trail)
Menyimpan riwayat keamanan dan jejak aktivitas sistem.
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT (PK, Auto) | ID Log |
| `user_id` | BIGINT (Nullable) | Relasi ke `users.id` |
| `user_name` | VARCHAR(255) | Nama Pelaku Aksi |
| `role` | VARCHAR(100) | Role Pelaku (admin/guru/siswa) |
| `modul` | VARCHAR(100) | Nama Modul |
| `aktivitas` | VARCHAR(255) | Nama Aksi (Login, Simpan, dll) |
| `deskripsi` | TEXT (Nullable) | Rincian Aksi |
| `ip_address` | VARCHAR(45) | Alamat IP Pengguna |
| `user_agent` | VARCHAR(255) | Informasi Perangkat & Browser |

---

## 2. ARSITEKTUR KEAMANAN & VERIFIKASI QR CODE

```
[ Cetak E-Sertifikat ] 
       │
       ▼
[ Generate Signed Token / ID ] ──▶ [ QR Code Generator ] ──▶ Disematkan di Sertifikat PDF
                                                                     │
                                                              Scan Kamera HP
                                                                     ▼
                                                         [ Endpoint Verifikasi Publik ]
                                                         /verifikasi/{penempatan_id}
                                                                     │
                                                                     ▼
                                                      [ Tampil Status Validitas Sah ]
                                                      Nama Siswa, DUDI, Nilai & SK Sekolah
```
