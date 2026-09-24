<?php

namespace Database\Seeders;

use App\Models\AbsensiPkl;
use App\Models\Guru;
use App\Models\JurnalPkl;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\PembimbingIndustri;
use App\Models\Penempatan;
use App\Models\PenilaianPkl;
use App\Models\PeriodePkl;
use App\Models\Perusahaan;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's master data for SMK Labor Binaan FKIP UNRI Pekanbaru.
     */
    public function run(): void
    {
        // 1. Roles (Idempotent)
        $roleAdmin      = Role::firstOrCreate(['nama_role' => 'admin']);
        $roleGuru       = Role::firstOrCreate(['nama_role' => 'guru']);
        $roleSiswa      = Role::firstOrCreate(['nama_role' => 'siswa']);
        $roleInstruktur = Role::firstOrCreate(['nama_role' => 'instruktur']);

        // 2. Pengaturan Identitas & Pejabat Sekolah Resmi
        $defaultSettings = [
            ['sekolah_nama_yayasan', 'YAYASAN UNIVERSITAS RIAU', 'sekolah'],
            ['sekolah_nama_sekolah', 'SMK LABOR BINAAN FKIP UNRI PEKANBARU', 'sekolah'],
            ['sekolah_npsn', '10403993', 'sekolah'],
            ['sekolah_akreditasi', 'TERAKREDITASI "A" (UNGGUL)', 'sekolah'],
            ['sekolah_alamat', 'Jl. Thamrin No. 97 Kec. Sail Pekanbaru – 28132', 'sekolah'],
            ['sekolah_telepon', '0761 – 28760', 'sekolah'],
            ['sekolah_website', 'www.smklabor.sch.id', 'sekolah'],
            ['sekolah_email', 'smk_labor@yahoo.com', 'sekolah'],
            ['sekolah_kota_terbit', 'Pekanbaru', 'sekolah'],
            ['pejabat_kepala_sekolah_sekarang', 'JEFFRI HUNTER, M.Pd', 'pejabat'],
            ['pejabat_nip_kepala_sekolah_sekarang', '-', 'pejabat'],
            ['pejabat_kepala_sekolah_lama', 'Drs. HENDRIPIDES, M.Si', 'pejabat'],
            ['pejabat_nip_kepala_sekolah_lama', '19680504 199303 1 003', 'pejabat'],
            ['pejabat_kepala_sekolah_aktif', 'sekarang', 'pejabat'],
            ['pejabat_kepala_sekolah', 'JEFFRI HUNTER, M.Pd', 'pejabat'],
            ['pejabat_nip_kepala_sekolah', '-', 'pejabat'],
            ['pejabat_ketua_pokja', 'Dedi Hendrawan, S.Kom., M.Kom.', 'pejabat'],
            ['pejabat_nip_ketua_pokja', '-', 'pejabat'],
        ];

        foreach ($defaultSettings as [$key, $val, $group]) {
            if (Setting::get($key) === null) {
                Setting::set($key, $val, $group);
            }
        }

        // 3. Akun Administrator Utama
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name'     => 'Administrator Pokja PKL',
                'password' => bcrypt('password'),
                'role_id'  => $roleAdmin->id,
                'status'   => 'aktif',
            ]
        );

        // 4. Akun & Profil Guru Pembimbing Utama
        $userGuru = User::updateOrCreate(
            ['email' => 'guru@smklabor.sch.id'],
            [
                'name'     => 'Dedi Hendrawan, S.Kom., M.Kom.',
                'password' => bcrypt('password'),
                'role_id'  => $roleGuru->id,
                'status'   => 'aktif',
            ]
        );

        $guruUtama = Guru::firstOrCreate(
            ['nip' => '198904122019031005'],
            [
                'user_id'       => $userGuru->id,
                'nama'          => 'Dedi Hendrawan, S.Kom., M.Kom.',
                'jenis_kelamin' => 'L',
                'no_hp'         => '081276001234',
                'alamat'        => 'Jl. Thamrin No. 97, Pekanbaru',
            ]
        );

        // 5. Master Data Jurusan / Konsentrasi Keahlian SMK Labor Binaan FKIP UNRI
        $jurusanMaster = [
            ['kode_jurusan' => 'RPL',  'nama_jurusan' => 'Rekayasa Perangkat Lunak'],
            ['kode_jurusan' => 'TKJ',  'nama_jurusan' => 'Teknik Komputer dan Jaringan'],
            ['kode_jurusan' => 'AKL',  'nama_jurusan' => 'Akuntansi dan Keuangan Lembaga'],
            ['kode_jurusan' => 'MPLB', 'nama_jurusan' => 'Manajemen Perkantoran dan Layanan Bisnis'],
            ['kode_jurusan' => 'PM',   'nama_jurusan' => 'Pemasaran'],
        ];

        $jurusanMap = [];
        $kelasMap = [];
        foreach ($jurusanMaster as $jm) {
            $jur = Jurusan::firstOrCreate(
                ['kode_jurusan' => $jm['kode_jurusan']],
                ['nama_jurusan' => $jm['nama_jurusan'], 'status' => true]
            );
            $jurusanMap[$jm['kode_jurusan']] = $jur;

            $kls = Kelas::firstOrCreate(
                ['nama_kelas' => 'XII ' . $jm['kode_jurusan'] . ' 1'],
                [
                    'jurusan_id'    => $jur->id,
                    'wali_kelas_id' => $guruUtama->id,
                    'tingkat'       => 'XII',
                    'status'        => true,
                ]
            );
            $kelasMap[$jm['kode_jurusan']] = $kls;
        }

        // 6. Periode PKL Aktif
        $periodeAktif = PeriodePkl::firstOrCreate(
            ['nama_periode' => 'PKL Semester Ganjil TA 2026/2027'],
            [
                'tahun_ajaran'    => '2026/2027',
                'tanggal_mulai'   => '2026-07-15',
                'tanggal_selesai' => '2026-11-30',
                'status'          => 'aktif',
            ]
        );

        // 7. Data Mitra DUDI Pekanbaru & Akun Instruktur Industri
        $userInstruktur = User::updateOrCreate(
            ['email' => 'instruktur@dudi.com'],
            [
                'name'     => 'Ir. Rahmat Hidayat (Instruktur DUDI)',
                'password' => bcrypt('password'),
                'role_id'  => $roleInstruktur->id,
                'status'   => 'aktif',
            ]
        );

        $dudiList = [
            [
                'nama_perusahaan' => 'PT Garuda Cyber Indonesia',
                'alamat'          => 'Jl. HR. Soebrantas No. 188, Panam, Kota Pekanbaru, Riau',
                'kota'            => 'Pekanbaru',
                'no_telepon'      => '0761-567890',
                'email'           => 'hrd@garudacyber.co.id',
                'website'         => 'www.garudacyber.co.id',
                'nama_pimpinan'   => 'Ir. Rahmat Hidayat',
            ],
            [
                'nama_perusahaan' => 'UPT Teknologi Informasi dan Komunikasi (TIK) Universitas Riau',
                'alamat'          => 'Kampus Bina Widya KM 12.5, Simpang Baru, Pekanbaru',
                'kota'            => 'Pekanbaru',
                'no_telepon'      => '0761-63266',
                'email'           => 'tik@unri.ac.id',
                'website'         => 'tik.unri.ac.id',
                'nama_pimpinan'   => 'Dr. Eng. Fauzan Ardiansyah, S.T., M.T.',
            ],
            [
                'nama_perusahaan' => 'PT Bank Riau Kepri Syariah (Perseroda) Kantor Pusat',
                'alamat'          => 'Menara Dang Merdu, Jl. Jend. Sudirman No. 462, Pekanbaru',
                'kota'            => 'Pekanbaru',
                'no_telepon'      => '0761-47070',
                'email'           => 'corsec@brksyariah.co.id',
                'website'         => 'www.brksyariah.co.id',
                'nama_pimpinan'   => 'Hj. Siti Aminah, S.E., Ak.',
            ],
            [
                'nama_perusahaan' => 'Dinas Komunikasi, Informatika dan Statistik Provinsi Riau',
                'alamat'          => 'Jl. Diponegoro No. 24A, Kota Pekanbaru, Riau',
                'kota'            => 'Pekanbaru',
                'no_telepon'      => '0761-45505',
                'email'           => 'diskominfotik@riau.go.id',
                'website'         => 'diskominfotik.riau.go.id',
                'nama_pimpinan'   => 'M. Rizki Pratama, S.Kom., M.Si.',
            ],
        ];

        $perusahaanUtama = null;
        foreach ($dudiList as $idx => $dudi) {
            $prs = Perusahaan::firstOrCreate(
                ['nama_perusahaan' => $dudi['nama_perusahaan']],
                [
                    'alamat'        => $dudi['alamat'],
                    'kota'          => $dudi['kota'],
                    'no_telepon'    => $dudi['no_telepon'],
                    'email'         => $dudi['email'],
                    'website'       => $dudi['website'],
                    'nama_pimpinan' => $dudi['nama_pimpinan'],
                    'status'        => 'aktif',
                ]
            );
            if ($idx === 0) {
                $perusahaanUtama = $prs;
                PembimbingIndustri::firstOrCreate(
                    ['perusahaan_id' => $prs->id, 'nama' => 'Ir. Rahmat Hidayat'],
                    [
                        'user_id' => $userInstruktur->id,
                        'jabatan' => 'Senior Lead Developer',
                        'no_hp'   => '081275009988',
                        'email'   => 'instruktur@dudi.com',
                    ]
                );
            }
        }

        // 8. Akun & Profil Siswa Contoh + Penempatan Aktif
        $userSiswa = User::updateOrCreate(
            ['email' => 'siswa@smklabor.sch.id'],
            [
                'name'     => 'Muhammad Farel Alfarizi',
                'password' => bcrypt('password'),
                'role_id'  => $roleSiswa->id,
                'status'   => 'aktif',
            ]
        );

        $siswaUtama = Siswa::firstOrCreate(
            ['nis' => '20241001'],
            [
                'user_id'       => $userSiswa->id,
                'nisn'          => '0081234567',
                'nama'          => 'Muhammad Farel Alfarizi',
                'jenis_kelamin' => 'L',
                'kelas_id'      => $kelasMap['RPL']->id,
                'jurusan_id'    => $jurusanMap['RPL']->id,
                'no_hp'         => '082388990011',
                'alamat'        => 'Jl. Hangtuah Ujung, Pekanbaru',
            ]
        );

        if ($perusahaanUtama) {
            $penempatan = Penempatan::firstOrCreate(
                [
                    'siswa_id'       => $siswaUtama->id,
                    'periode_pkl_id' => $periodeAktif->id,
                ],
                [
                    'guru_id'       => $guruUtama->id,
                    'perusahaan_id' => $perusahaanUtama->id,
                ]
            );

            AbsensiPkl::firstOrCreate(
                ['penempatan_id' => $penempatan->id, 'tanggal' => now()->format('Y-m-d')],
                ['status' => 'hadir', 'jam_masuk' => '08:00', 'jam_keluar' => '16:00', 'keterangan' => 'Hadir tepat waktu']
            );

            JurnalPkl::firstOrCreate(
                ['penempatan_id' => $penempatan->id, 'tanggal' => now()->format('Y-m-d')],
                [
                    'kegiatan'        => 'Melakukan pengembangan modul sistem informasi dan pengujian fitur.',
                    'status_validasi' => 'disetujui',
                    'komentar_guru'   => 'Pertahankan progres dan dokumentasi kode dengan rapi.',
                ]
            );

            PenilaianPkl::firstOrCreate(
                ['penempatan_id' => $penempatan->id],
                [
                    'nilai_sikap'        => 92,
                    'nilai_pengetahuan'  => 89,
                    'nilai_keterampilan' => 91,
                    'nilai_akhir'        => 91,
                    'catatan_guru'       => 'Siswa sangat kompeten, disiplin tinggi, dan mampu menyelesaikan proyek tepat waktu.',
                ]
            );
        }
    }
}
