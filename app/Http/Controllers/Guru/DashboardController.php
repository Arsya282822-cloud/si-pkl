<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AbsensiPkl;
use App\Models\JurnalPkl;
use App\Models\Penempatan;
use App\Models\Pengumuman;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $guru = $user->guru;

        if (! $guru) {
            return view('guru.dashboard', [
                'penempatan' => collect(),
                'guru' => null,
                'grouped' => collect(),
                'pengumumans' => collect(),
                'laporanDudi' => collect(),
                'statsJurnal' => ['total' => 0, 'disetujui' => 0, 'menunggu' => 0, 'ditolak' => 0, 'laporan_khusus' => 0],
                'absensiHariIni' => ['hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpha' => 0],
            ]);
        }

        // Get all placements assigned to this guru
        $penempatan = Penempatan::with(['siswa.kelas', 'siswa.jurusan', 'perusahaan', 'periodePkl', 'penilaian'])
            ->where('guru_id', $guru->id)
            ->latest()
            ->get();

        $grouped = $penempatan->groupBy('perusahaan_id');
        $pengumumans = Pengumuman::whereIn('target_role', ['semua', 'guru'])->latest()->take(3)->get();

        $penempatanIds = $penempatan->pluck('id');

        // Laporan & Catatan Khusus dari Instruktur DUDI
        $laporanDudi = JurnalPkl::with(['penempatan.siswa.kelas', 'penempatan.perusahaan'])
            ->whereIn('penempatan_id', $penempatanIds)
            ->where(function ($query) {
                $query->where('komentar_guru', 'like', '%[LAPORAN INSTRUKTUR DUDI]%')
                    ->orWhere('status_validasi', 'ditolak');
            })
            ->latest('tanggal')
            ->take(6)
            ->get();

        $statsJurnal = [
            'total' => JurnalPkl::whereIn('penempatan_id', $penempatanIds)->count(),
            'disetujui' => JurnalPkl::whereIn('penempatan_id', $penempatanIds)->where('status_validasi', 'disetujui')->count(),
            'menunggu' => JurnalPkl::whereIn('penempatan_id', $penempatanIds)->where('status_validasi', 'menunggu')->count(),
            'ditolak' => JurnalPkl::whereIn('penempatan_id', $penempatanIds)->where('status_validasi', 'ditolak')->count(),
            'laporan_khusus' => JurnalPkl::whereIn('penempatan_id', $penempatanIds)
                ->where('komentar_guru', 'like', '%[LAPORAN INSTRUKTUR DUDI]%')->count(),
        ];

        // Absensi Hari Ini Siswa Bimbingan
        $today = now()->format('Y-m-d');
        $absensiHariIni = [
            'hadir' => AbsensiPkl::whereIn('penempatan_id', $penempatanIds)->where('tanggal', $today)->where('status', 'hadir')->count(),
            'izin' => AbsensiPkl::whereIn('penempatan_id', $penempatanIds)->where('tanggal', $today)->where('status', 'izin')->count(),
            'sakit' => AbsensiPkl::whereIn('penempatan_id', $penempatanIds)->where('tanggal', $today)->where('status', 'sakit')->count(),
            'alpha' => AbsensiPkl::whereIn('penempatan_id', $penempatanIds)->where('tanggal', $today)->where('status', 'alpha')->count(),
        ];

        return view('guru.dashboard', compact('penempatan', 'guru', 'grouped', 'pengumumans', 'laporanDudi', 'statsJurnal', 'absensiHariIni'));
    }
}
