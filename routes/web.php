<?php

use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;
use App\Http\Controllers\Admin\CetakController as AdminCetakController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DevToolController as AdminDevToolController;
use App\Http\Controllers\Admin\DokumenController as AdminDokumenController;
use App\Http\Controllers\Admin\ExportController as AdminExportController;
use App\Http\Controllers\Admin\GuruController as AdminGuruController;
use App\Http\Controllers\Admin\JurusanController as AdminJurusanController;
use App\Http\Controllers\Admin\KelasController as AdminKelasController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Admin\PenempatanController as AdminPenempatanController;
use App\Http\Controllers\Admin\PengajuanPklController as AdminPengajuanPklController;
use App\Http\Controllers\Admin\PengumumanController as AdminPengumumanController;
use App\Http\Controllers\Admin\PeriodePklController as AdminPeriodePklController;
use App\Http\Controllers\Admin\PerusahaanController as AdminPerusahaanController;
use App\Http\Controllers\Admin\PksController as AdminPksController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\SiswaController as AdminSiswaController;
use App\Http\Controllers\Admin\TujuanPembelajaranController as AdminTujuanPembelajaranController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\BantuanController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\LembarObservasiController as GuruLembarObservasiController;
use App\Http\Controllers\Guru\MonitoringController as GuruMonitoringController;
use App\Http\Controllers\Guru\PantauanMapController as GuruPantauanMapController;
use App\Http\Controllers\Guru\PengumumanInfoController as GuruPengumumanInfoController;
use App\Http\Controllers\Guru\PenilaianController as GuruPenilaianController;
use App\Http\Controllers\Guru\SiswaBimbinganController as GuruSiswaBimbinganController;
use App\Http\Controllers\Guru\ValidasiJurnalController as GuruValidasiJurnalController;
use App\Http\Controllers\Instruktur\AbsensiController as InstrukturAbsensiController;
use App\Http\Controllers\Instruktur\DashboardController as InstrukturDashboardController;
use App\Http\Controllers\Instruktur\JurnalController as InstrukturJurnalController;
use App\Http\Controllers\Instruktur\PenilaianController as InstrukturPenilaianController;
use App\Http\Controllers\Instruktur\ProfilController as InstrukturProfilController;
use App\Http\Controllers\Instruktur\SiswaController as InstrukturSiswaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Siswa\AbsensiController as SiswaAbsensiController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Siswa\JurnalController as SiswaJurnalController;
use App\Http\Controllers\Siswa\PenempatanInfoController as SiswaPenempatanInfoController;
use App\Http\Controllers\Siswa\PengajuanPklController as SiswaPengajuanPklController;
use App\Http\Controllers\Siswa\PengumumanInfoController as SiswaPengumumanInfoController;
use App\Http\Controllers\VerifikasiController;
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
        Route::match(['get', 'post'], '/setup-dummy', [AdminDevToolController::class, 'setupDummy'])->name('setup_dummy');
        Route::match(['get', 'post'], '/wipe-dummy', [AdminDevToolController::class, 'wipeDummy'])->name('wipe_dummy');
    });

Route::get('/dashboard', [AdminDevToolController::class, 'dashboardRedirect'])
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
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('jurusan', AdminJurusanController::class)->parameters(['jurusan' => 'jurusan'])->except(['show']);
        Route::post('guru/import', [AdminGuruController::class, 'import'])->name('guru.import');
        Route::post('guru/reset-all-password', [AdminGuruController::class, 'resetAllPassword'])->name('guru.reset_all_password');
        Route::resource('guru', AdminGuruController::class)->except(['show']);
        Route::resource('kelas', AdminKelasController::class)->parameters(['kelas' => 'kelas'])->except(['show']);
        Route::post('siswa/import', [AdminSiswaController::class, 'import'])->name('siswa.import');
        Route::post('siswa/bulk-update-kelas', [AdminSiswaController::class, 'bulkUpdateKelas'])->name('siswa.bulk_update_kelas');
        Route::resource('siswa', AdminSiswaController::class)->except(['show']);
        Route::post('perusahaan/import', [AdminPerusahaanController::class, 'import'])->name('perusahaan.import');
        Route::post('perusahaan/sync-instruktur', [AdminPerusahaanController::class, 'syncAllInstruktur'])->name('perusahaan.sync_instruktur');
        Route::post('perusahaan/{perusahaan}/instruktur', [AdminPerusahaanController::class, 'createInstruktur'])->name('perusahaan.instruktur');
        Route::resource('perusahaan', AdminPerusahaanController::class)->except(['show']);
        Route::resource('pks', AdminPksController::class)->except(['show']);
        Route::resource('periode', AdminPeriodePklController::class)->except(['show']);
        Route::resource('penempatan', AdminPenempatanController::class)->except(['show']);

        // Tujuan Pembelajaran Kurikulum PKL 2026
        Route::get('/tujuan-pembelajaran/cetak', [AdminTujuanPembelajaranController::class, 'cetak'])->name('tujuan_pembelajaran.cetak');
        Route::resource('tujuan-pembelajaran', AdminTujuanPembelajaranController::class)
            ->names('tujuan_pembelajaran')
            ->parameters(['tujuan-pembelajaran' => 'tujuan_pembelajaran'])
            ->except(['show', 'create', 'edit']);

        // Manajemen Pengguna & Hak Akses
        Route::post('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::post('/users/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('users.reset-password');
        Route::resource('users', AdminUserController::class)->except(['create', 'show', 'edit']);

        // Pengaturan Sekolah & Dokumen
        Route::get('/pengaturan', [AdminSettingController::class, 'index'])->name('pengaturan.index');
        Route::post('/pengaturan', [AdminSettingController::class, 'update'])->name('pengaturan.update');

        // Laporan & Rekapitulasi
        Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/absensi', [AdminLaporanController::class, 'absensi'])->name('laporan.absensi');
        Route::get('/laporan/nilai', [AdminLaporanController::class, 'nilai'])->name('laporan.nilai');

        // Cetak Dokumen / Surat
        Route::get('/dokumen', [AdminDokumenController::class, 'index'])->name('dokumen.index');
        Route::get('/dokumen/surat-pengantar/{penempatan}', [AdminDokumenController::class, 'suratPengantar'])->name('dokumen.surat_pengantar');
        Route::get('/dokumen/batch-surat-pengantar', [AdminDokumenController::class, 'batchSuratPengantar'])->name('dokumen.batch_surat_pengantar');
        Route::get('/dokumen/surat-tugas/{guru}', [AdminDokumenController::class, 'suratTugas'])->name('dokumen.surat_tugas');
        Route::get('/dokumen/surat-penarikan/{penempatan}', [AdminDokumenController::class, 'suratPenarikan'])->name('dokumen.surat_penarikan');
        Route::get('/dokumen/sertifikat/{penempatan}', [AdminDokumenController::class, 'sertifikat'])->name('dokumen.sertifikat');
        Route::get('/dokumen/batch-sertifikat', [AdminDokumenController::class, 'batchSertifikat'])->name('dokumen.batch_sertifikat');
        Route::get('/dokumen/piagam-dudi/{penempatan}', [AdminDokumenController::class, 'piagamDudi'])->name('dokumen.piagam_dudi');
        Route::get('/observasi/{observasi}/cetak', [GuruLembarObservasiController::class, 'cetak'])->name('observasi.cetak');
        Route::get('/observasi/{observasi}/excel', [GuruLembarObservasiController::class, 'exportExcel'])->name('observasi.excel');
        Route::get('/monitoring/blanko', [GuruMonitoringController::class, 'blanko'])->name('monitoring.blanko');
        Route::get('/monitoring/excel', [GuruMonitoringController::class, 'exportExcel'])->name('monitoring.excel');
        Route::resource('observasi', GuruLembarObservasiController::class);

        // Export Excel & Download Template
        Route::get('/export/siswa', [AdminExportController::class, 'siswa'])->name('export.siswa');
        Route::get('/export/penempatan', [AdminExportController::class, 'penempatan'])->name('export.penempatan');
        Route::get('/export/absensi', [AdminExportController::class, 'absensi'])->name('export.absensi');
        Route::get('/export/nilai', [AdminExportController::class, 'nilai'])->name('export.nilai');
        Route::get('/template/siswa', [AdminExportController::class, 'templateSiswa'])->name('template.siswa');
        Route::get('/template/guru', [AdminExportController::class, 'templateGuru'])->name('template.guru');
        Route::get('/template/perusahaan', [AdminExportController::class, 'templatePerusahaan'])->name('template.perusahaan');
        Route::get('/template/observasi', [AdminExportController::class, 'templateObservasi'])->name('template.observasi');
        Route::get('/backup/database', [AdminExportController::class, 'backupDatabase'])->name('backup.database');

        // Pengajuan Tempat PKL Mandiri Siswa
        Route::get('/pengajuan', [AdminPengajuanPklController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan/{pengajuan}', [AdminPengajuanPklController::class, 'show'])->name('pengajuan.show');
        Route::post('/pengajuan/{pengajuan}/approve', [AdminPengajuanPklController::class, 'approve'])->name('pengajuan.approve');
        Route::post('/pengajuan/{pengajuan}/reject', [AdminPengajuanPklController::class, 'reject'])->name('pengajuan.reject');

        // Pengumuman & Broadcast Koordinator PKL
        Route::resource('pengumuman', AdminPengumumanController::class);

        // Log Aktivitas Sistem
        Route::get('/activity-log', [AdminActivityLogController::class, 'index'])->name('activity-log.index');
        Route::get('/log-aktivitas', [AdminActivityLogController::class, 'index'])->name('activity-log.alias');
        Route::get('/activity_log', [AdminActivityLogController::class, 'index']);
        Route::post('/activity-log/clear', [AdminActivityLogController::class, 'clear'])->name('activity-log.clear');
        Route::delete('/activity-log/{activityLog}', [AdminActivityLogController::class, 'destroy'])->name('activity-log.destroy');

        // Cetak PDF
        Route::get('/cetak/penempatan', [AdminCetakController::class, 'penempatan'])->name('cetak.penempatan');
        Route::get('/cetak/jurnal/{penempatan}', [AdminCetakController::class, 'jurnalSiswa'])->name('cetak.jurnal');
        Route::get('/cetak/surat-pengantar/{penempatan}', [AdminCetakController::class, 'suratPengantar'])->name('cetak.surat_pengantar');
        Route::get('/cetak/rapor/{penempatan}', [AdminCetakController::class, 'raporPkl'])->name('cetak.rapor');
    });

// ============================================
// SISWA ROUTES
// ============================================
Route::middleware(['auth', 'role:siswa'])
    ->prefix('siswa')
    ->name('siswa.')
    ->group(function () {
        Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');
        Route::get('/pengumuman', [SiswaPengumumanInfoController::class, 'index'])->name('pengumuman.index');
        Route::resource('pengajuan', SiswaPengajuanPklController::class);
        Route::get('/penempatan', [SiswaPenempatanInfoController::class, 'index'])->name('penempatan.index');
        Route::get('/nilai', [SiswaPenempatanInfoController::class, 'nilai'])->name('nilai.index');
        Route::get('/sertifikat', [SiswaPenempatanInfoController::class, 'sertifikat'])->name('sertifikat');
        Route::get('/cetak-jurnal', [SiswaPenempatanInfoController::class, 'cetakJurnal'])->name('cetak.jurnal');
        Route::get('/cetak-rapor', [SiswaPenempatanInfoController::class, 'cetakRapor'])->name('cetak.rapor');
        Route::resource('jurnal', SiswaJurnalController::class)->except(['show', 'destroy']);
        Route::resource('absensi', SiswaAbsensiController::class)->only(['index', 'create', 'store', 'update']);
    });

// ============================================
// GURU ROUTES
// ============================================
Route::middleware(['auth', 'role:guru'])
    ->prefix('guru')
    ->name('guru.')
    ->group(function () {
        Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');
        Route::get('/pengumuman', [GuruPengumumanInfoController::class, 'index'])->name('pengumuman.index');
        Route::get('/siswa-bimbingan', [GuruSiswaBimbinganController::class, 'index'])->name('siswa.index');
        Route::get('/validasi', [GuruValidasiJurnalController::class, 'index'])->name('validasi.index');
        Route::get('/validasi/{jurnal}', [GuruValidasiJurnalController::class, 'show'])->name('validasi.show');
        Route::put('/validasi/{jurnal}', [GuruValidasiJurnalController::class, 'update'])->name('validasi.update');
        Route::resource('penilaian', GuruPenilaianController::class)->except(['show', 'destroy']);
        Route::get('/penilaian/{penempatan}/sertifikat', [GuruPenilaianController::class, 'sertifikat'])->name('penilaian.sertifikat');
        Route::get('/cetak/rapor/{penempatan}', [AdminCetakController::class, 'raporPkl'])->name('cetak.rapor');
        Route::resource('monitoring', GuruMonitoringController::class)->only(['index', 'create', 'store']);
        Route::get('/monitoring/blanko', [GuruMonitoringController::class, 'blanko'])->name('monitoring.blanko');
        Route::get('/monitoring/excel', [GuruMonitoringController::class, 'exportExcel'])->name('monitoring.excel');
        Route::get('/monitoring/cetak/{monitoring}', [GuruMonitoringController::class, 'cetak'])->name('monitoring.cetak');
        Route::get('/pantauan-peta', [GuruPantauanMapController::class, 'index'])->name('pantauan_map.index');

        // Lembar Observasi Online & Cetak PDF Resmi
        Route::get('/observasi/{observasi}/cetak', [GuruLembarObservasiController::class, 'cetak'])->name('observasi.cetak');
        Route::get('/observasi/{observasi}/excel', [GuruLembarObservasiController::class, 'exportExcel'])->name('observasi.excel');
        Route::resource('observasi', GuruLembarObservasiController::class);

        // Laporan Guru
        Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/absensi', [AdminLaporanController::class, 'absensi'])->name('laporan.absensi');
        Route::get('/laporan/nilai', [AdminLaporanController::class, 'nilai'])->name('laporan.nilai');
    });

// ============================================
// INSTRUKTUR DUDI (PEMBIMBING INDUSTRI) ROUTES
// ============================================
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
