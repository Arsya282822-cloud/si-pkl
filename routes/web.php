<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - SI-PKL SMK Labor FKIP UNRI
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Verifikasi Publik Keaslian E-Sertifikat via QR Code
Route::get('/verifikasi/{penempatan}', [\App\Http\Controllers\VerifikasiController::class, 'sertifikat'])->name('verifikasi.sertifikat');

// ============================================
// ADMIN DEV & MAINTENANCE UTILITIES (PROTECTED)
// ============================================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin/dev-tools')
    ->name('admin.devtools.')
    ->group(function () {
        // Setup Dummy Data
        Route::get('/setup-dummy', function () {
            $roleGuru = \App\Models\Role::firstOrCreate(['nama_role' => 'guru']);
            $roleSiswa = \App\Models\Role::firstOrCreate(['nama_role' => 'siswa']);

            $jurusan = \App\Models\Jurusan::firstOrCreate(['kode_jurusan' => 'RPL'], ['nama_jurusan' => 'Rekayasa Perangkat Lunak', 'status' => true]);
            $kelas = \App\Models\Kelas::firstOrCreate(['nama_kelas' => 'XII RPL 1'], ['jurusan_id' => $jurusan->id, 'tingkat' => 'XII', 'wali_kelas' => 'Bapak Wali', 'status' => true]);
            
            $perusahaanList = [];
            for ($i = 1; $i <= 3; $i++) {
                $perusahaanList[] = \App\Models\Perusahaan::firstOrCreate(
                    ['nama_perusahaan' => 'PT Teknologi Masa Depan ' . $i],
                    ['bidang_usaha' => 'IT Software House', 'alamat' => 'Jl. Sudirman No. 12' . $i, 'pembimbing_industri' => 'Bpk. Budi ' . $i]
                );
            }
            
            $periode = \App\Models\PeriodePkl::firstOrCreate(
                ['nama_periode' => 'Gelombang 1 Tahun 2026'],
                ['tahun_ajaran' => '2026/2027', 'tanggal_mulai' => now()->subDays(15)->format('Y-m-d'), 'tanggal_selesai' => now()->addMonths(3)->format('Y-m-d'), 'status' => 'aktif']
            );

            $gurus = [];
            for ($i = 1; $i <= 5; $i++) {
                $userGuru = \App\Models\User::firstOrCreate(
                    ['email' => "guru{$i}@guru.com"],
                    ['name' => "Bapak Guru {$i}", 'password' => bcrypt('guru1234'), 'role_id' => $roleGuru->id, 'status' => 'aktif']
                );
                $gurus[] = \App\Models\Guru::firstOrCreate(
                    ['nip' => "1000000{$i}"],
                    ['user_id' => $userGuru->id, 'nama' => "Bapak Guru {$i}", 'no_hp' => "0812000000{$i}"]
                );
            }

            for ($i = 1; $i <= 20; $i++) {
                $userSiswa = \App\Models\User::firstOrCreate(
                    ['email' => "siswa{$i}@siswa.com"],
                    ['name' => "Siswa Teladan {$i}", 'password' => bcrypt('password'), 'role_id' => $roleSiswa->id, 'status' => 'aktif']
                );
                
                $nis = str_pad($i, 4, '0', STR_PAD_LEFT);
                $siswa = \App\Models\Siswa::firstOrCreate(
                    ['nis' => "2026{$nis}"],
                    ['user_id' => $userSiswa->id, 'kelas_id' => $kelas->id, 'jurusan_id' => $jurusan->id, 'nama' => "Siswa Teladan {$i}"]
                );

                $guru_id = $gurus[$i % 5]->id;
                $perusahaan_id = $perusahaanList[$i % 3]->id;

                $penempatan = \App\Models\Penempatan::firstOrCreate([
                    'siswa_id' => $siswa->id,
                    'periode_pkl_id' => $periode->id,
                ], [
                    'guru_id' => $guru_id,
                    'perusahaan_id' => $perusahaan_id,
                ]);

                $hari_terakhir = rand(3, 6);
                for ($j = $hari_terakhir; $j >= 0; $j--) {
                    $tanggal = now()->subDays($j)->format('Y-m-d');
                    
                    \App\Models\AbsensiPkl::firstOrCreate(
                        ['penempatan_id' => $penempatan->id, 'tanggal' => $tanggal],
                        ['status' => 'hadir', 'jam_masuk' => '08:00', 'jam_keluar' => '16:00', 'keterangan' => '-']
                    );

                    \App\Models\JurnalPkl::firstOrCreate(
                        ['penempatan_id' => $penempatan->id, 'tanggal' => $tanggal],
                        ['kegiatan' => "Melakukan tugas harian ke-".(10-$j)." di industri.", 'status_validasi' => $j > 2 ? 'disetujui' : 'menunggu']
                    );
                }

                if ($i % 3 == 0) {
                    \App\Models\PenilaianPkl::firstOrCreate(
                        ['penempatan_id' => $penempatan->id],
                        ['nilai_sikap' => rand(80, 95), 'nilai_keterampilan' => rand(80, 95), 'nilai_pengetahuan' => rand(80, 95), 'nilai_akhir' => rand(80, 95), 'catatan_guru' => 'Siswa berkinerja baik.']
                    );
                }
            }

            foreach ($gurus as $index => $g) {
                \App\Models\Monitoring::firstOrCreate(
                    ['guru_id' => $g->id, 'perusahaan_id' => $perusahaanList[$index % 3]->id, 'tanggal_kunjungan' => now()->subDays(rand(1, 5))->format('Y-m-d')],
                    ['catatan' => 'Kunjungan monitoring berjalan lancar, siswa dalam keadaan sehat dan aktif.']
                );
            }

            return redirect()->route('admin.dashboard')->with('success', 'Data Dummy PKL berhasil di-generate!');
        });

        // Wipe only dummy
        Route::get('/wipe-dummy', function () {
            \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
            try {
                $dummyUsers = \App\Models\User::where('email', 'like', '%@guru.com')
                                              ->orWhere('email', 'like', '%@siswa.com')
                                              ->get();
                                              
                $userIds = $dummyUsers->pluck('id')->toArray();
                
                if (count($userIds) > 0) {
                    $siswaIds = \App\Models\Siswa::whereIn('user_id', $userIds)->pluck('id')->toArray();
                    $guruIds = \App\Models\Guru::whereIn('user_id', $userIds)->pluck('id')->toArray();
                    
                    $penempatanIds = \App\Models\Penempatan::whereIn('siswa_id', $siswaIds)
                                                           ->orWhereIn('guru_id', $guruIds)
                                                           ->pluck('id')->toArray();
                    
                    \App\Models\Monitoring::whereIn('guru_id', $guruIds)->delete();
                    \App\Models\PenilaianPkl::whereIn('penempatan_id', $penempatanIds)->delete();
                    \App\Models\AbsensiPkl::whereIn('penempatan_id', $penempatanIds)->delete();
                    \App\Models\JurnalPkl::whereIn('penempatan_id', $penempatanIds)->delete();
                    \App\Models\Penempatan::whereIn('id', $penempatanIds)->delete();
                    
                    \App\Models\Siswa::whereIn('id', $siswaIds)->delete();
                    \App\Models\Guru::whereIn('id', $guruIds)->delete();
                    \App\Models\User::whereIn('id', $userIds)->delete();
                }

                \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
                return redirect()->route('admin.dashboard')->with('success', 'Akun Dummy berhasil dibersihkan.');
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
                return redirect()->route('admin.dashboard')->with('error', 'Gagal: ' . $e->getMessage());
            }
        });
    });

Route::get('/dashboard', function () {
    $user = auth()->user();
    if (!$user) return redirect('login');
    
    $role = $user->role?->nama_role ?? '';
    
    return match ($role) {
        'admin'      => redirect()->route('admin.dashboard'),
        'siswa'      => redirect()->route('siswa.dashboard'),
        'guru'       => redirect()->route('guru.dashboard'),
        'instruktur' => redirect()->route('instruktur.dashboard'),
        default      => view('dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/bantuan', [\App\Http\Controllers\BantuanController::class, 'index'])->name('bantuan.index');
    Route::post('/bantuan/kontak', [\App\Http\Controllers\BantuanController::class, 'updateKontak'])->name('bantuan.update_kontak');
});


// ============================================
// ADMIN ROUTES
// ============================================
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\JurusanController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\PerusahaanController;
use App\Http\Controllers\Admin\PeriodePklController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\PenempatanController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\CetakController;

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('jurusan', JurusanController::class)->except(['show']);
        Route::post('guru/import', [GuruController::class, 'import'])->name('guru.import');
        Route::post('guru/reset-all-password', [GuruController::class, 'resetAllPassword'])->name('guru.reset_all_password');
        Route::resource('guru', GuruController::class)->except(['show']);
        Route::resource('kelas', KelasController::class)->parameters(['kelas' => 'kelas'])->except(['show']);
        Route::post('siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
        Route::resource('siswa', SiswaController::class)->except(['show']);
        Route::post('perusahaan/import', [PerusahaanController::class, 'import'])->name('perusahaan.import');
        Route::post('perusahaan/sync-instruktur', [PerusahaanController::class, 'syncAllInstruktur'])->name('perusahaan.sync_instruktur');
        Route::post('perusahaan/{perusahaan}/instruktur', [PerusahaanController::class, 'createInstruktur'])->name('perusahaan.instruktur');
        Route::resource('perusahaan', PerusahaanController::class)->except(['show']);
        Route::resource('pks', \App\Http\Controllers\Admin\PksController::class)->except(['show']);
        Route::resource('periode', PeriodePklController::class)->except(['show']);
        Route::resource('penempatan', PenempatanController::class)->except(['show']);

        // Pengaturan Sekolah & Dokumen
        Route::get('/pengaturan', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('pengaturan.index');
        Route::post('/pengaturan', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('pengaturan.update');

        // Laporan & Rekapitulasi
        Route::get('/laporan', [\App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('laporan.index');

        Route::get('/laporan/absensi', [\App\Http\Controllers\Admin\LaporanController::class, 'absensi'])->name('laporan.absensi');
        Route::get('/laporan/nilai', [\App\Http\Controllers\Admin\LaporanController::class, 'nilai'])->name('laporan.nilai');

        // Cetak Dokumen / Surat
        Route::get('/dokumen', [\App\Http\Controllers\Admin\DokumenController::class, 'index'])->name('dokumen.index');
        Route::get('/dokumen/surat-pengantar/{penempatan}', [\App\Http\Controllers\Admin\DokumenController::class, 'suratPengantar'])->name('dokumen.surat_pengantar');
        Route::get('/dokumen/batch-surat-pengantar', [\App\Http\Controllers\Admin\DokumenController::class, 'batchSuratPengantar'])->name('dokumen.batch_surat_pengantar');
        Route::get('/dokumen/surat-tugas/{guru}', [\App\Http\Controllers\Admin\DokumenController::class, 'suratTugas'])->name('dokumen.surat_tugas');
        Route::get('/dokumen/surat-penarikan/{penempatan}', [\App\Http\Controllers\Admin\DokumenController::class, 'suratPenarikan'])->name('dokumen.surat_penarikan');
        Route::get('/dokumen/sertifikat/{penempatan}', [\App\Http\Controllers\Admin\DokumenController::class, 'sertifikat'])->name('dokumen.sertifikat');
        Route::get('/dokumen/batch-sertifikat', [\App\Http\Controllers\Admin\DokumenController::class, 'batchSertifikat'])->name('dokumen.batch_sertifikat');
        Route::get('/dokumen/piagam-dudi/{penempatan}', [\App\Http\Controllers\Admin\DokumenController::class, 'piagamDudi'])->name('dokumen.piagam_dudi');
        Route::get('/observasi/{observasi}/cetak', [\App\Http\Controllers\Guru\LembarObservasiController::class, 'cetak'])->name('observasi.cetak');
        Route::get('/observasi/{observasi}/excel', [\App\Http\Controllers\Guru\LembarObservasiController::class, 'exportExcel'])->name('observasi.excel');
        Route::get('/monitoring/blanko', [\App\Http\Controllers\Guru\MonitoringController::class, 'blanko'])->name('monitoring.blanko');
        Route::get('/monitoring/excel', [\App\Http\Controllers\Guru\MonitoringController::class, 'exportExcel'])->name('monitoring.excel');
        Route::resource('observasi', \App\Http\Controllers\Guru\LembarObservasiController::class);

        // Export Excel & Download Template
        Route::get('/export/siswa', [ExportController::class, 'siswa'])->name('export.siswa');
        Route::get('/export/penempatan', [ExportController::class, 'penempatan'])->name('export.penempatan');
        Route::get('/export/absensi', [ExportController::class, 'absensi'])->name('export.absensi');
        Route::get('/export/nilai', [ExportController::class, 'nilai'])->name('export.nilai');
        Route::get('/template/siswa', [ExportController::class, 'templateSiswa'])->name('template.siswa');
        Route::get('/template/guru', [ExportController::class, 'templateGuru'])->name('template.guru');
        Route::get('/template/perusahaan', [ExportController::class, 'templatePerusahaan'])->name('template.perusahaan');
        Route::get('/template/observasi', [ExportController::class, 'templateObservasi'])->name('template.observasi');
        Route::get('/backup/database', [ExportController::class, 'backupDatabase'])->name('backup.database');

        // Pengajuan Tempat PKL Mandiri Siswa
        Route::get('/pengajuan', [\App\Http\Controllers\Admin\PengajuanPklController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan/{pengajuan}', [\App\Http\Controllers\Admin\PengajuanPklController::class, 'show'])->name('pengajuan.show');
        Route::post('/pengajuan/{pengajuan}/approve', [\App\Http\Controllers\Admin\PengajuanPklController::class, 'approve'])->name('pengajuan.approve');
        Route::post('/pengajuan/{pengajuan}/reject', [\App\Http\Controllers\Admin\PengajuanPklController::class, 'reject'])->name('pengajuan.reject');

        // Pengumuman & Broadcast Pokja PKL
        Route::resource('pengumuman', \App\Http\Controllers\Admin\PengumumanController::class);

        // Log Aktivitas Sistem
        Route::get('/activity-log', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity-log.index');
        Route::get('/log-aktivitas', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity-log.alias');
        Route::get('/activity_log', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index']);
        Route::post('/activity-log/clear', [\App\Http\Controllers\Admin\ActivityLogController::class, 'clear'])->name('activity-log.clear');
        Route::delete('/activity-log/{activityLog}', [\App\Http\Controllers\Admin\ActivityLogController::class, 'destroy'])->name('activity-log.destroy');

        // Cetak PDF
        Route::get('/cetak/penempatan', [CetakController::class, 'penempatan'])->name('cetak.penempatan');
        Route::get('/cetak/jurnal/{penempatan}', [CetakController::class, 'jurnalSiswa'])->name('cetak.jurnal');
        Route::get('/cetak/surat-pengantar/{penempatan}', [CetakController::class, 'suratPengantar'])->name('cetak.surat_pengantar');
        Route::get('/cetak/rapor/{penempatan}', [CetakController::class, 'raporPkl'])->name('cetak.rapor');
    });

// ============================================
// SISWA ROUTES
// ============================================
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Siswa\JurnalController as SiswaJurnalController;
use App\Http\Controllers\Siswa\AbsensiController as SiswaAbsensiController;
use App\Http\Controllers\Siswa\PenempatanInfoController;

Route::middleware(['auth', 'role:siswa'])
    ->prefix('siswa')
    ->name('siswa.')
    ->group(function () {
        Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');
        Route::get('/pengumuman', [\App\Http\Controllers\Siswa\PengumumanInfoController::class, 'index'])->name('pengumuman.index');
        Route::resource('pengajuan', \App\Http\Controllers\Siswa\PengajuanPklController::class);
        Route::get('/penempatan', [PenempatanInfoController::class, 'index'])->name('penempatan.index');
        Route::get('/nilai', [PenempatanInfoController::class, 'nilai'])->name('nilai.index');
        Route::get('/sertifikat', [PenempatanInfoController::class, 'sertifikat'])->name('sertifikat');
        Route::get('/cetak-jurnal', [PenempatanInfoController::class, 'cetakJurnal'])->name('cetak.jurnal');
        Route::get('/cetak-rapor', [PenempatanInfoController::class, 'cetakRapor'])->name('cetak.rapor');
        Route::resource('jurnal', SiswaJurnalController::class)->except(['show', 'destroy']);
        Route::resource('absensi', SiswaAbsensiController::class)->only(['index', 'create', 'store', 'update']);
    });

// ============================================
// GURU ROUTES
// ============================================
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\ValidasiJurnalController;
use App\Http\Controllers\Guru\PenilaianController;
use App\Http\Controllers\Guru\MonitoringController;
use App\Http\Controllers\Guru\PantauanMapController;

Route::middleware(['auth', 'role:guru'])
    ->prefix('guru')
    ->name('guru.')
    ->group(function () {
        Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');
        Route::get('/pengumuman', [\App\Http\Controllers\Guru\PengumumanInfoController::class, 'index'])->name('pengumuman.index');
        Route::get('/siswa-bimbingan', [\App\Http\Controllers\Guru\SiswaBimbinganController::class, 'index'])->name('siswa.index');
        Route::get('/validasi', [ValidasiJurnalController::class, 'index'])->name('validasi.index');
        Route::get('/validasi/{jurnal}', [ValidasiJurnalController::class, 'show'])->name('validasi.show');
        Route::put('/validasi/{jurnal}', [ValidasiJurnalController::class, 'update'])->name('validasi.update');
        Route::resource('penilaian', PenilaianController::class)->except(['show', 'destroy']);
        Route::get('/penilaian/{penempatan}/sertifikat', [PenilaianController::class, 'sertifikat'])->name('penilaian.sertifikat');
        Route::get('/cetak/rapor/{penempatan}', [CetakController::class, 'raporPkl'])->name('cetak.rapor');
        Route::resource('monitoring', MonitoringController::class)->only(['index', 'create', 'store']);
        Route::get('/monitoring/blanko', [MonitoringController::class, 'blanko'])->name('monitoring.blanko');
        Route::get('/monitoring/excel', [MonitoringController::class, 'exportExcel'])->name('monitoring.excel');
        Route::get('/monitoring/cetak/{monitoring}', [MonitoringController::class, 'cetak'])->name('monitoring.cetak');
        Route::get('/pantauan-peta', [PantauanMapController::class, 'index'])->name('pantauan_map.index');

        // Lembar Observasi Online & Cetak PDF Resmi
        Route::get('/observasi/{observasi}/cetak', [\App\Http\Controllers\Guru\LembarObservasiController::class, 'cetak'])->name('observasi.cetak');
        Route::get('/observasi/{observasi}/excel', [\App\Http\Controllers\Guru\LembarObservasiController::class, 'exportExcel'])->name('observasi.excel');
        Route::resource('observasi', \App\Http\Controllers\Guru\LembarObservasiController::class);

        // Laporan Guru
        Route::get('/laporan', [\App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/absensi', [\App\Http\Controllers\Admin\LaporanController::class, 'absensi'])->name('laporan.absensi');
        Route::get('/laporan/nilai', [\App\Http\Controllers\Admin\LaporanController::class, 'nilai'])->name('laporan.nilai');
    });

// ============================================
// INSTRUKTUR DUDI (PEMBIMBING INDUSTRI) ROUTES
// ============================================
use App\Http\Controllers\Instruktur\DashboardController as InstrukturDashboardController;
use App\Http\Controllers\Instruktur\SiswaController as InstrukturSiswaController;
use App\Http\Controllers\Instruktur\AbsensiController as InstrukturAbsensiController;
use App\Http\Controllers\Instruktur\JurnalController as InstrukturJurnalController;
use App\Http\Controllers\Instruktur\PenilaianController as InstrukturPenilaianController;
use App\Http\Controllers\Instruktur\ProfilController as InstrukturProfilController;

Route::middleware(['auth', 'role:instruktur'])
    ->prefix('instruktur')
    ->name('instruktur.')
    ->group(function () {
        Route::get('/dashboard', [InstrukturDashboardController::class, 'index'])->name('dashboard');
        Route::get('/siswa', [InstrukturSiswaController::class, 'index'])->name('siswa.index');
        Route::get('/absensi', [InstrukturAbsensiController::class, 'index'])->name('absensi.index');
        Route::get('/jurnal', [InstrukturJurnalController::class, 'index'])->name('jurnal.index');
        Route::get('/jurnal/{jurnal}', [InstrukturJurnalController::class, 'show'])->name('jurnal.show');
        Route::match(['post', 'put'], '/jurnal/{jurnal}', [InstrukturJurnalController::class, 'update'])->name('jurnal.update');
        Route::match(['post', 'put'], '/jurnal/{jurnal}/validasi', [InstrukturJurnalController::class, 'update'])->name('jurnal.validasi');
        Route::get('/penilaian', [InstrukturPenilaianController::class, 'index'])->name('penilaian.index');
        Route::get('/penilaian/{penempatan}/input', [InstrukturPenilaianController::class, 'create'])->name('penilaian.create');
        Route::post('/penilaian/{penempatan}', [InstrukturPenilaianController::class, 'store'])->name('penilaian.store');
        Route::get('/profil', [InstrukturProfilController::class, 'index'])->name('profil.index');
        Route::match(['post', 'put'], '/profil', [InstrukturProfilController::class, 'update'])->name('profil.update');
    });

require __DIR__.'/auth.php';
