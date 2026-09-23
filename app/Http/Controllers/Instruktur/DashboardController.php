<?php

namespace App\Http\Controllers\Instruktur;

use App\Http\Controllers\Controller;
use App\Models\Penempatan;
use App\Models\AbsensiPkl;
use App\Models\JurnalPkl;
use App\Models\PenilaianPkl;
use App\Models\PeriodePkl;
use App\Models\Pengumuman;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pembimbing = $user->pembimbingIndustri;
        $perusahaan = $pembimbing?->perusahaan;

        if (!$perusahaan) {
            // Fallback jika belum terikat ke perusahaan tertentu
            return view('instruktur.dashboard_empty');
        }

        $penempatanIds = Penempatan::where('perusahaan_id', $perusahaan->id)->pluck('id');

        $totalSiswa = $penempatanIds->count();
        $totalHadirHariIni = AbsensiPkl::whereIn('penempatan_id', $penempatanIds)
            ->where('tanggal', now()->format('Y-m-d'))
            ->where('status', 'hadir')
            ->count();
        $jurnalMenunggu = JurnalPkl::whereIn('penempatan_id', $penempatanIds)
            ->where('status_validasi', 'menunggu')
            ->count();
        $totalDinilai = PenilaianPkl::whereIn('penempatan_id', $penempatanIds)->count();

        $stats = [
            'total_siswa' => $totalSiswa,
            'presensi_hari_ini' => $totalHadirHariIni,
            'jurnal_menunggu' => $jurnalMenunggu,
            'total_dinilai' => $totalDinilai,
            'total_jurnal' => JurnalPkl::whereIn('penempatan_id', $penempatanIds)->count(),
        ];

        $periodeAktif = PeriodePkl::where('status', 'aktif')->first();

        // Siswa magang aktif
        $penempatans = Penempatan::where('perusahaan_id', $perusahaan->id)
            ->with(['siswa.kelas', 'siswa.jurusan', 'guru', 'periode'])
            ->latest()
            ->get();

        // Presensi hari ini
        $todayAbsensi = AbsensiPkl::whereIn('penempatan_id', $penempatanIds)
            ->where('tanggal', now()->format('Y-m-d'))
            ->get();

        // Jurnal terbaru
        $recentJurnals = JurnalPkl::whereIn('penempatan_id', $penempatanIds)
            ->with(['penempatan.siswa.kelas', 'penempatan.siswa.jurusan'])
            ->latest('tanggal')
            ->take(6)
            ->get();

        // Pengumuman
        $pengumumans = Pengumuman::aktif()
            ->forRole('instruktur')
            ->latest()
            ->take(3)
            ->get();

        return view('instruktur.dashboard', compact(
            'pembimbing',
            'perusahaan',
            'totalSiswa',
            'totalHadirHariIni',
            'jurnalMenunggu',
            'totalDinilai',
            'stats',
            'periodeAktif',
            'penempatans',
            'todayAbsensi',
            'recentJurnals',
            'pengumumans'
        ));
    }
}

