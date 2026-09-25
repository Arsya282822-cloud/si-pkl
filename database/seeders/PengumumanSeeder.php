<?php

namespace Database\Seeders;

use App\Models\Pengumuman;
use App\Models\User;
use Illuminate\Database\Seeder;

class PengumumanSeeder extends Seeder
{
    /**
     * Run the database seeds for Pesan & Info / Pengumuman SI-PKL.
     */
    public function run(): void
    {
        $adminUser = User::whereHas('role', function ($q) {
            $q->where('nama_role', 'admin');
        })->first() ?? User::first();

        if (! $adminUser) {
            $this->command->error('User admin tidak ditemukan. Harap seed database user terlebih dahulu.');

            return;
        }

        $items = [
            [
                'author_id' => $adminUser->id,
                'judul' => 'Pemberitahuan Wajib Pembekalan Teknis & Apel Pelepasan PKL Periode 2026/2027',
                'konten' => 'Diberitahukan kepada seluruh siswa kelas XII calon peserta Praktik Kerja Lapangan (PKL) Tahun Ajaran 2026/2027 bahwa Apel Pelepasan dan Pembekalan Akhir akan dilaksanakan pada:

📅 Hari/Tanggal: Senin, 14 Juli 2026
⏰ Waktu: Pukul 07.30 WIB s.d. Selesai
📍 Tempat: Lapangan Utama SMK Labor Binaan FKIP UNRI Pekanbaru
👔 Pakaian: Seragam Kejuruan Lengkap + Almamater + ID Card Peserta PKL

Agenda:
1. Pengarahan oleh Kepala Sekolah & Koordinator PKL.
2. Penyerahan berkas resmi Surat Pengantar dan Buku Panduan PKL.
3. Penjelasan teknis aplikasi presensi GPS dan pengisian jurnal online SI-PKL.

Kehadiran bersifat WAJIB. Siswa yang tidak hadir tanpa keterangan sah tidak akan diberikan izin berangkat ke industri mitra.',
                'kategori' => 'penting',
                'target_role' => 'semua',
                'is_pinned' => true,
                'status' => 'aktif',
                'created_at' => now()->subDays(5),
            ],
            [
                'author_id' => $adminUser->id,
                'judul' => 'Ketentuan Jam Presensi GPS & Pengisian Jurnal Harian Kerja Siswa',
                'konten' => 'Demi tertibnya administrasi dan rekapitulasi kehadiran industri, seluruh siswa peserta PKL wajib mematuhi ketentuan berikut:

1. Presensi Masuk: Wajib dilakukan antara pukul 06.30 - 08.00 WIB (atau sesuai jam masuk shift DUDI) dengan menyalakan GPS dan swafoto di area lokasi kerja.
2. Presensi Pulang: Wajib dilakukan saat jam kepulangan selesai kerja (pukul 16.00 - 18.00 WIB).
3. Pengisian Jurnal Harian: Ditulis setiap hari kerja sebelum pukul 21.00 WIB, memuat uraian pekerjaan, alat/teknologi yang digunakan, serta bukti foto kegiatan.
4. Validasi Jurnal: Jurnal akan divalidasi berkala oleh Instruktur Lapangan dan Guru Pembimbing Sekolah.

Keterlambatan presensi dan pengisian jurnal akan otomatis terekam di sistem dan mempengaruhi nilai kedisiplinan pada Rapor PKL.',
                'kategori' => 'jadwal',
                'target_role' => 'siswa',
                'is_pinned' => true,
                'status' => 'aktif',
                'created_at' => now()->subDays(4),
            ],
            [
                'author_id' => $adminUser->id,
                'judul' => 'Tata Tertib, Kode Etik Profesional, dan Sanksi Pelanggaran di Lingkungan DUDI',
                'konten' => 'Selama melaksanakan kegiatan PKL di Dunia Usaha dan Dunia Industri (DUDI), seluruh siswa adalah duta sekolah yang wajib menjaga nama baik almamater:

Tata Tertib:
1. Mematuhi SOP keselamatan dan kesehatan kerja (K3) serta peraturan internal perusahaan.
2. Berpakaian rapi, sopan, dan mengenakan tanda pengenal/ID Card PKL.
3. Menjaga kerahasiaan data, dokumen, dan source code milik industri.
4. Dilarang meninggalkan tempat PKL pada jam operasional tanpa izin tertulis dari pembimbing industri.

Prosedur Izin/Sakit:
- Wajib melampirkan Surat Keterangan Dokter asli pada menu Izin/Sakit aplikasi SI-PKL.
- Wajib konfirmasi via WhatsApp ke Pembimbing Industri dan Guru Pembimbing pada hari yang sama.

Pelanggaran disiplin berat dapat berakibat pada penarikan siswa seketika dan pembatalan nilai PKL.',
                'kategori' => 'peringatan',
                'target_role' => 'siswa',
                'is_pinned' => false,
                'status' => 'aktif',
                'created_at' => now()->subDays(3),
            ],
            [
                'author_id' => $adminUser->id,
                'judul' => 'Jadwal Monitoring Berkala & Pengisian Lembar Observasi Guru Pembimbing',
                'konten' => 'Kepada Bapak/Ibu Guru Pembimbing PKL SMK Labor Binaan FKIP UNRI,

Dihimbau untuk mulai merencanakan jadwal kunjungan monitoring tatap muka dan pengawasan berkala ke DUDI bimbingan masing-masing:

Tahapan Monitoring:
- Monitoring 1 (Minggu 1-2): Konfirmasi penempatan siswa dan koordinasi awal dengan pimpinan/instruktur DUDI.
- Monitoring 2 (Bulan ke-2): Evaluasi progres capaian kompetensi kejuruan dan penyelesaian kendala di lapangan.
- Monitoring 3 (Akhir Periode): Penarikan resmi siswa dan penyerahan Piagam Penghargaan Kemitraan DUDI.

Setiap selesai kunjungan, Bapak/Ibu Guru dimohon langsung mengisi Laporan Monitoring dan Lembar Observasi Online serta mengunggah dokumentasi foto kunjungan di menu Guru > Monitoring.',
                'kategori' => 'info',
                'target_role' => 'guru',
                'is_pinned' => false,
                'status' => 'aktif',
                'created_at' => now()->subDays(2),
            ],
            [
                'author_id' => $adminUser->id,
                'judul' => 'Alur Penilaian Akhir Industri dan Penerbitan E-Sertifikat Terverifikasi QR Code',
                'konten' => 'Informasi mekanisme evaluasi akhir dan sertifikasi kompetensi PKL:

1. Penilaian Instruktur DUDI: Dilakukan melalui akun login Instruktur DUDI mencakup aspek Hard Skills (Teknis), Soft Skills (Komunikasi/Inisiatif), dan Nilai Sikap/Disiplin.
2. Penilaian Guru Pembimbing: Nilai laporan jurnal kerja, hasil lembar observasi, dan penguasaan kompetensi.
3. Nilai Akhir & Rapor PKL: Sistem akan mengkalkulasikan bobot nilai industri (60%) dan nilai sekolah (40%).
4. E-Sertifikat Resmi: Siswa yang dinyatakan lulus berhak mengunduh E-Sertifikat berstandar industri dengan tanda tangan digital dan QR Code verifikasi publik online.',
                'kategori' => 'info',
                'target_role' => 'semua',
                'is_pinned' => false,
                'status' => 'aktif',
                'created_at' => now()->subDay(),
            ],
            [
                'author_id' => $adminUser->id,
                'judul' => 'Layanan Pengaduan & Helpdesk Cepat Koordinator PKL SMK Labor',
                'konten' => 'Apabila siswa atau guru pembimbing mengalami kendala teknis sistem, perselisihan penugasan di DUDI, ataupun kondisi kedaruratan lainnya di tempat magang, silakan segera menghubungi saluran resmi:

📞 Hotline Koordinator PKL: (0761) 28760
💬 WhatsApp Helpdesk: +62 823-8733-4892 (Pak Mahendra, S.Pd., M.Si. / Koordinator PKL)
✉️ Email: hubin.pkl@smklabor.sch.id
🏢 Ruang Sekretariat Koordinator PKL & Hubin, Gedung Utama Lt. 2 SMK Labor Pekanbaru

Layanan responsif beroperasi setiap hari kerja pukul 07.30 - 16.30 WIB.',
                'kategori' => 'info',
                'target_role' => 'semua',
                'is_pinned' => false,
                'status' => 'aktif',
                'created_at' => now(),
            ],
        ];

        foreach ($items as $data) {
            Pengumuman::updateOrCreate(
                [
                    'judul' => $data['judul'],
                ],
                $data
            );
        }

        $this->command->info('Berhasil men-generate '.count($items).' data Pesan & Info Pengumuman resmi.');
    }
}
