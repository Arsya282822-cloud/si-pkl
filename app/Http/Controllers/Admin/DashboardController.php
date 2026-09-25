<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AbsensiPkl;
use App\Models\Guru;
use App\Models\JurnalPkl;
use App\Models\Jurusan;
use App\Models\Penempatan;
use App\Models\PeriodePkl;
use App\Models\Perusahaan;
use App\Models\Pks;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_siswa' => Siswa::count(),
            'total_guru' => Guru::count(),
            'total_perusahaan' => Perusahaan::where('status', 'aktif')->count(),
            'total_penempatan' => Penempatan::count(),
            'siswa_belum_ditempatkan' => Siswa::whereDoesntHave('penempatan')->count(),
            'jurnal_menunggu' => JurnalPkl::where('status_validasi', 'menunggu')->count(),
            'total_pks' => Schema::hasTable('pks') ? Pks::count() : 0,
        ];

        $periodeAktif = PeriodePkl::where('status', 'aktif')->first();

        // 1. Sinkronisasi otomatis jurusan_id pada siswa berdasarkan kelas yang ditempati
        if (Schema::hasTable('siswa') && Schema::hasTable('kelas')) {
            DB::table('siswa')
                ->join('kelas', 'siswa.kelas_id', '=', 'kelas.id')
                ->where(function ($q) {
                    $q->whereColumn('siswa.jurusan_id', '!=', 'kelas.jurusan_id')
                        ->orWhereNull('siswa.jurusan_id');
                })
                ->update(['siswa.jurusan_id' => DB::raw('kelas.jurusan_id')]);
        }

        // Chart 1: Persebaran Siswa per Jurusan
        $jurusanStats = Jurusan::withCount('siswa')->orderBy('id')->get();
        $jurusanLabels = $jurusanStats->map(function ($j) {
            return $j->kode_jurusan ? "{$j->kode_jurusan} - {$j->nama_jurusan}" : $j->nama_jurusan;
        })->toArray();
        $jurusanData = $jurusanStats->pluck('siswa_count')->toArray();

        // Chart 2: Top 5 Perusahaan Penempatan
        $topPerusahaan = Perusahaan::withCount('penempatan')
            ->orderByDesc('penempatan_count')
            ->take(5)
            ->get();
        $perusahaanLabels = $topPerusahaan->pluck('nama_perusahaan')->toArray();
        $perusahaanData = $topPerusahaan->pluck('penempatan_count')->toArray();

        // Chart 3: Tren Presensi 7 Hari Terakhir
        $presensiLabels = [];
        $presensiHadir = [];
        $presensiIzin = [];
        $presensiSakit = [];

        for ($i = 6; $i >= 0; $i--) {
            $d = now()->subDays($i);
            $dateStr = $d->format('Y-m-d');
            $presensiLabels[] = $d->translatedFormat('d M');

            $presensiHadir[] = AbsensiPkl::where('tanggal', $dateStr)->where('status', 'hadir')->count();
            $presensiIzin[] = AbsensiPkl::where('tanggal', $dateStr)->where('status', 'izin')->count();
            $presensiSakit[] = AbsensiPkl::where('tanggal', $dateStr)->where('status', 'sakit')->count();
        }

        // Recent jurnal submissions
        $recentJurnal = JurnalPkl::with(['penempatan.siswa', 'penempatan.perusahaan'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'periodeAktif',
            'recentJurnal',
            'jurusanStats',
            'jurusanLabels',
            'jurusanData',
            'perusahaanLabels',
            'perusahaanData',
            'presensiLabels',
            'presensiHadir',
            'presensiIzin',
            'presensiSakit'
        ));
    }
}
