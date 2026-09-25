<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - SI-PKL SMK Labor FKIP UNRI
|--------------------------------------------------------------------------
*/

Route::view('/', 'welcome')->name('welcome');

// Verifikasi Publik Keaslian E-Sertifikat via QR Code
Route::get('/verifikasi/{penempatan}', [VerifikasiController::class, 'sertifikat'])->name('verifikasi.sertifikat');

// ============================================
// ADMIN DEV & MAINTENANCE UTILITIES (PROTECTED)
// ============================================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin/dev-tools')
    ->name('admin.devtools.')
    ->group(function () {
        Route::match(['get', 'post'], '/setup-dummy', [DevToolController::class, 'setupDummy'])->name('setup_dummy');
        Route::match(['get', 'post'], '/wipe-dummy', [DevToolController::class, 'wipeDummy'])->name('wipe_dummy');
    });

Route::get('/dashboard', [DevToolController::class, 'dashboardRedirect'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/bantuan', [BantuanController::class, 'index'])->name('bantuan.index');
    Route::post('/bantuan/kontak', [BantuanController::class, 'updateKontak'])->name('bantuan.update_kontak');
});

// ============================================
// ADMIN ROUTES
// ============================================
use App\Http\Controllers\Admin\CetakController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\JurusanController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\PenempatanController;
use App\Http\Controllers\Admin\PeriodePklController;
use App\Http\Controllers\Admin\PerusahaanController;
use App\Http\Controllers\Admin\SiswaController;

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('jurusan', JurusanController::class)->parameters(['jurusan' => 'jurusan'])->except(['show']);
        Route::post('guru/import', [GuruController::class, 'import'])->name('guru.import');
        Route::post('guru/reset-all-password', [GuruController::class, 'resetAllPassword'])->name('guru.reset_all_password');
        Route::resource('guru', GuruController::class)->except(['show']);
        Route::resource('kelas', KelasController::class)->parameters(['kelas' => 'kelas'])->except(['show']);
        Route::post('siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
        Route::post('siswa/bulk-update-kelas', [SiswaController::class, 'bulkUpdateKelas'])->name('siswa.bulk_update_kelas');
        Route::resource('siswa', SiswaController::class)->except(['show']);
        Route::post('perusahaan/import', [PerusahaanController::class, 'import'])->name('perusahaan.import');
        Route::post('perusahaan/sync-instruktur', [PerusahaanController::class, 'syncAllInstruktur'])->name('perusahaan.sync_instruktur');
        Route::post('perusahaan/{perusahaan}/instruktur', [PerusahaanController::class, 'createInstruktur'])->name('perusahaan.instruktur');
        Route::resource('perusahaan', PerusahaanController::class)->except(['show']);
        Route::resource('pks', PksController::class)->except(['show']);
        Route::resource('periode', PeriodePklController::class)->except(['show']);
        Route::resource('penempatan', PenempatanController::class)->except(['show']);

        // Tujuan Pembelajaran Kurikulum PKL 2026
        Route::get('/tujuan-pembelajaran/cetak', [TujuanPembelajaranController::class, 'cetak'])->name('tujuan_pembelajaran.cetak');
        Route::resource('tujuan-pembelajaran', TujuanPembelajaranController::class)
            ->names('tujuan_pembelajaran')
            ->parameters(['tujuan-pembelajaran' => 'tujuan_pembelajaran'])
            ->except(['show', 'create', 'edit']);

        // Manajemen Pengguna & Hak Akses
        Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);

        // Pengaturan Sekolah & Dokumen
        Route::get('/pengaturan', [SettingController::class, 'index'])->name('pengaturan.index');
        Route::post('/pengaturan', [SettingController::class, 'update'])->name('pengaturan.update');

        // Laporan & Rekapitulasi
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

        Route::get('/laporan/absensi', [LaporanController::class, 'absensi'])->name('laporan.absensi');
        Route::get('/laporan/nilai', [LaporanController::class, 'nilai'])->name('laporan.nilai');

        // Cetak Dokumen / Surat
        Route::get('/dokumen', [DokumenController::class, 'index'])->name('dokumen.index');
        Route::get('/dokumen/surat-pengantar/{penempatan}', [DokumenController::class, 'suratPengantar'])->name('dokumen.surat_pengantar');
        Route::get('/dokumen/batch-surat-pengantar', [DokumenController::class, 'batchSuratPengantar'])->name('dokumen.batch_surat_pengantar');
        Route::get('/dokumen/surat-tugas/{guru}', [DokumenController::class, 'suratTugas'])->name('dokumen.surat_tugas');
        Route::get('/dokumen/surat-penarikan/{penempatan}', [DokumenController::class, 'suratPenarikan'])->name('dokumen.surat_penarikan');
        Route::get('/dokumen/sertifikat/{penempatan}', [DokumenController::class, 'sertifikat'])->name('dokumen.sertifikat');
        Route::get('/dokumen/batch-sertifikat', [DokumenController::class, 'batchSertifikat'])->name('dokumen.batch_sertifikat');
        Route::get('/dokumen/piagam-dudi/{penempatan}', [DokumenController::class, 'piagamDudi'])->name('dokumen.piagam_dudi');
        Route::get('/observasi/{observasi}/cetak', [LembarObservasiController::class, 'cetak'])->name('observasi.cetak');
        Route::get('/observasi/{observasi}/excel', [LembarObservasiController::class, 'exportExcel'])->name('observasi.excel');
        Route::get('/monitoring/blanko', [MonitoringController::class, 'blanko'])->name('monitoring.blanko');
        Route::get('/monitoring/excel', [MonitoringController::class, 'exportExcel'])->name('monitoring.excel');
        Route::resource('observasi', LembarObservasiController::class);

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
        Route::get('/pengajuan', [PengajuanPklController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan/{pengajuan}', [PengajuanPklController::class, 'show'])->name('pengajuan.show');
        Route::post('/pengajuan/{pengajuan}/approve', [PengajuanPklController::class, 'approve'])->name('pengajuan.approve');
        Route::post('/pengajuan/{pengajuan}/reject', [PengajuanPklController::class, 'reject'])->name('pengajuan.reject');

        // Pengumuman & Broadcast Koordinator PKL
        Route::resource('pengumuman', PengumumanController::class);

        // Log Aktivitas Sistem
        Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
        Route::get('/log-aktivitas', [ActivityLogController::class, 'index'])->name('activity-log.alias');
        Route::get('/activity_log', [ActivityLogController::class, 'index']);
        Route::post('/activity-log/clear', [ActivityLogController::class, 'clear'])->name('activity-log.clear');
        Route::delete('/activity-log/{activityLog}', [ActivityLogController::class, 'destroy'])->name('activity-log.destroy');

        // Cetak PDF
        Route::get('/cetak/penempatan', [CetakController::class, 'penempatan'])->name('cetak.penempatan');
        Route::get('/cetak/jurnal/{penempatan}', [CetakController::class, 'jurnalSiswa'])->name('cetak.jurnal');
        Route::get('/cetak/surat-pengantar/{penempatan}', [CetakController::class, 'suratPengantar'])->name('cetak.surat_pengantar');
        Route::get('/cetak/rapor/{penempatan}', [CetakController::class, 'raporPkl'])->name('cetak.rapor');
    });

// ============================================
// SISWA ROUTES
// ============================================
use App\Http\Controllers\Siswa\AbsensiController as SiswaAbsensiController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Siswa\JurnalController as SiswaJurnalController;
use App\Http\Controllers\Siswa\PenempatanInfoController;

Route::middleware(['auth', 'role:siswa'])
    ->prefix('siswa')
    ->name('siswa.')
    ->group(function () {
        Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');
        Route::get('/pengumuman', [PengumumanInfoController::class, 'index'])->name('pengumuman.index');
        Route::resource('pengajuan', App\Http\Controllers\Siswa\PengajuanPklController::class);
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
use App\Http\Controllers\Guru\MonitoringController;
use App\Http\Controllers\Guru\PantauanMapController;
use App\Http\Controllers\Guru\PenilaianController;
use App\Http\Controllers\Guru\ValidasiJurnalController;

Route::middleware(['auth', 'role:guru'])
    ->prefix('guru')
    ->name('guru.')
    ->group(function () {
        Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');
        Route::get('/pengumuman', [App\Http\Controllers\Guru\PengumumanInfoController::class, 'index'])->name('pengumuman.index');
        Route::get('/siswa-bimbingan', [SiswaBimbinganController::class, 'index'])->name('siswa.index');
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
        Route::get('/observasi/{observasi}/cetak', [LembarObservasiController::class, 'cetak'])->name('observasi.cetak');
        Route::get('/observasi/{observasi}/excel', [LembarObservasiController::class, 'exportExcel'])->name('observasi.excel');
        Route::resource('observasi', LembarObservasiController::class);

        // Laporan Guru
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/absensi', [LaporanController::class, 'absensi'])->name('laporan.absensi');
        Route::get('/laporan/nilai', [LaporanController::class, 'nilai'])->name('laporan.nilai');
    });

// ============================================
// INSTRUKTUR DUDI (PEMBIMBING INDUSTRI) ROUTES
// ============================================
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\DevToolController;
use App\Http\Controllers\Admin\DokumenController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PengajuanPklController;
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Admin\PksController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TujuanPembelajaranController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BantuanController;
use App\Http\Controllers\Guru\LembarObservasiController;
use App\Http\Controllers\Guru\SiswaBimbinganController;
use App\Http\Controllers\Instruktur\AbsensiController as InstrukturAbsensiController;
use App\Http\Controllers\Instruktur\DashboardController as InstrukturDashboardController;
use App\Http\Controllers\Instruktur\JurnalController as InstrukturJurnalController;
use App\Http\Controllers\Instruktur\PenilaianController as InstrukturPenilaianController;
use App\Http\Controllers\Instruktur\ProfilController as InstrukturProfilController;
use App\Http\Controllers\Instruktur\SiswaController as InstrukturSiswaController;
use App\Http\Controllers\Siswa\PengumumanInfoController;
use App\Http\Controllers\VerifikasiController;

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
