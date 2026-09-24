<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('lembar_observasi')) {
                \Illuminate\Support\Facades\Schema::create('lembar_observasi', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('penempatan_id')->nullable();
                    $table->unsignedBigInteger('guru_id')->nullable();
                    $table->unsignedBigInteger('perusahaan_id')->nullable();
                    $table->unsignedBigInteger('siswa_id')->nullable();
                    
                    // I. Data Institusi
                    $table->string('nama_institusi')->nullable();
                    $table->text('alamat_institusi')->nullable();
                    $table->string('telp_institusi')->nullable();

                    // II. Data Pimpinan
                    $table->string('nama_pimpinan')->nullable();
                    $table->string('nip_pimpinan')->nullable();
                    $table->string('jabatan_pimpinan')->nullable();

                    // III. Data Instruktur
                    $table->string('nama_instruktur')->nullable();
                    $table->string('nip_instruktur')->nullable();
                    $table->string('jabatan_instruktur')->nullable();

                    // IV. Data Murid
                    $table->string('nama_murid')->nullable();
                    $table->string('konsentrasi_keahlian')->nullable();

                    // JSON Assessment Sections
                    $table->json('data_softskills')->nullable();
                    $table->json('data_kompetensi_teknis')->nullable();
                    $table->json('data_kompetensi_baru')->nullable();
                    $table->json('data_analisis_usaha')->nullable();

                    // Dates & Signatures Metadata
                    $table->date('tgl_observasi_1')->nullable();
                    $table->date('tgl_observasi_2')->nullable();
                    $table->date('tgl_observasi_akhir')->nullable();
                    $table->date('tgl_cetak')->nullable();
                    $table->string('kota_cetak')->default('Pekanbaru');
                    $table->text('catatan_umum')->nullable();

                    $table->timestamps();
                });
            }

            if (!\Illuminate\Support\Facades\Schema::hasTable('pks')) {
                \Illuminate\Support\Facades\Schema::create('pks', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->string('vld', 50)->nullable();
                    $table->string('jenis_kerjasama')->nullable();
                    $table->string('dunia_usaha_industri')->nullable();
                    $table->string('nama_dudi');
                    $table->string('nomor_pks')->nullable();
                    $table->string('judul_pks')->nullable();
                    $table->date('tgl_mulai')->nullable();
                    $table->date('tgl_selesai')->nullable();
                    $table->string('npwp_dudi')->nullable();
                    $table->string('nama_bidang_usaha')->nullable();
                    $table->string('telp_kantor', 50)->nullable();
                    $table->string('fax', 50)->nullable();
                    $table->string('contact_person')->nullable();
                    $table->string('telepon_cp', 50)->nullable();
                    $table->string('jabatan_cp')->nullable();
                    $table->timestamps();
                });
            }

            if (!\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                \Illuminate\Support\Facades\Schema::create('settings', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->string('key')->unique();
                    $table->text('value')->nullable();
                    $table->string('group')->default('general');
                    $table->timestamps();
                });
            }

            if (!\Illuminate\Support\Facades\Schema::hasTable('activity_logs')) {
                \Illuminate\Support\Facades\Schema::create('activity_logs', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('user_id')->nullable();
                    $table->string('user_name')->nullable();
                    $table->string('role', 50)->nullable();
                    $table->string('modul', 100);
                    $table->string('aktivitas', 255);
                    $table->text('deskripsi')->nullable();
                    $table->string('ip_address', 50)->nullable();
                    $table->text('user_agent')->nullable();
                    $table->timestamps();
                });
            }

            if (\Illuminate\Support\Facades\Schema::hasTable('pembimbing_industri') && !\Illuminate\Support\Facades\Schema::hasColumn('pembimbing_industri', 'user_id')) {
                \Illuminate\Support\Facades\Schema::table('pembimbing_industri', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->unsignedBigInteger('user_id')->nullable()->after('id');
                    $table->string('jabatan', 100)->nullable()->after('nama');
                    $table->string('no_hp', 50)->nullable()->after('jabatan');
                    $table->string('email', 255)->nullable()->after('no_hp');
                });
            }

            // Inisialisasi awal 5 Jurusan & 5 Kelas HANYA jika tabel jurusan masih kosong (agar tidak menimpa saat Admin melakukan Edit/Update)
            if (
                \Illuminate\Support\Facades\Schema::hasTable('kelas') &&
                \Illuminate\Support\Facades\Schema::hasTable('jurusan') &&
                \App\Models\Jurusan::count() === 0
            ) {
                $initialMap = [
                    'TKJ' => ['nama_jurusan' => 'Teknik Komputer dan Jaringan', 'nama_kelas' => 'XII TKJ'],
                    'RPL' => ['nama_jurusan' => 'Rekayasa Perangkat Lunak', 'nama_kelas' => 'XII RPL'],
                    'AK'  => ['nama_jurusan' => 'Akuntansi dan Keuangan Lembaga', 'nama_kelas' => 'XII AK'],
                    'MP'  => ['nama_jurusan' => 'Manajemen Perkantoran dan Layanan Bisnis', 'nama_kelas' => 'XII MP'],
                    'BR'  => ['nama_jurusan' => 'Bisnis Retail', 'nama_kelas' => 'XII BR'],
                ];

                foreach ($initialMap as $kode => $cfg) {
                    $jur = \App\Models\Jurusan::firstOrCreate(
                        ['kode_jurusan' => $kode],
                        [
                            'nama_jurusan' => $cfg['nama_jurusan'],
                            'status'       => true,
                        ]
                    );

                    \App\Models\Kelas::firstOrCreate(
                        ['nama_kelas' => $cfg['nama_kelas']],
                        [
                            'tingkat'    => 'XII',
                            'jurusan_id' => $jur->id,
                            'status'     => true,
                        ]
                    );
                }
            }

            // Standarisasi Nama Siswa & Guru ke UPPERCASE di database
            if (\Illuminate\Support\Facades\Schema::hasTable('siswa')) {
                \Illuminate\Support\Facades\DB::table('siswa')->update(['nama' => \Illuminate\Support\Facades\DB::raw('UPPER(nama)')]);
            }
            if (\Illuminate\Support\Facades\Schema::hasTable('guru')) {
                \Illuminate\Support\Facades\DB::table('guru')->update(['nama' => \Illuminate\Support\Facades\DB::raw('UPPER(nama)')]);
            }
            if (\Illuminate\Support\Facades\Schema::hasTable('users')) {
                \Illuminate\Support\Facades\DB::table('users')->update(['name' => \Illuminate\Support\Facades\DB::raw('UPPER(name)')]);
            }
        } catch (\Exception $e) {
            // Ignore if database connection is not yet initialized
        }
    }
}
