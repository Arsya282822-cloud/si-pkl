<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\JurnalPkl;
use App\Models\Penempatan;
use App\Models\Perusahaan;
use App\Models\Siswa;
use App\Models\PeriodePkl;

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
            'total_pks' => \App\Models\Pks::count(),
        ];

        $periodeAktif = PeriodePkl::where('status', 'aktif')->first();

        // Chart 1: Persebaran Siswa per Jurusan
        $jurusanLabels = \App\Models\Jurusan::pluck('nama_jurusan')->toArray();
        $jurusanData = \App\Models\Jurusan::withCount('siswa')->pluck('siswa_count')->toArray();

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

            $presensiHadir[] = \App\Models\AbsensiPkl::where('tanggal', $dateStr)->where('status', 'hadir')->count();
            $presensiIzin[] = \App\Models\AbsensiPkl::where('tanggal', $dateStr)->where('status', 'izin')->count();
            $presensiSakit[] = \App\Models\AbsensiPkl::where('tanggal', $dateStr)->where('status', 'sakit')->count();
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
