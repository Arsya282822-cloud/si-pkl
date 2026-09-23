<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penempatan;
use App\Models\JurnalPkl;
use App\Models\PeriodePkl;
use Illuminate\Http\Request;

class CetakController extends Controller
{
    public function penempatan()
    {
        $periodeAktif = PeriodePkl::where('status', 'aktif')->first();
        $data = Penempatan::with(['siswa.kelas', 'siswa.jurusan', 'guru', 'perusahaan', 'periodePkl'])
            ->when($periodeAktif, fn($q) => $q->where('periode_pkl_id', $periodeAktif->id))
            ->get();

        return view('cetak.penempatan', compact('data', 'periodeAktif'));
    }

    public function jurnalSiswa(Penempatan $penempatan)
    {
        $penempatan->load(['siswa.kelas', 'siswa.jurusan', 'guru', 'perusahaan', 'periodePkl']);
        $jurnal = JurnalPkl::where('penempatan_id', $penempatan->id)->orderBy('tanggal')->get();
        $absensi = \App\Models\AbsensiPkl::where('penempatan_id', $penempatan->id)->orderBy('tanggal')->get();

        return view('cetak.jurnal', compact('penempatan', 'jurnal', 'absensi'));
    }

    public function suratPengantar(Penempatan $penempatan)
    {
        $penempatan->load(['siswa.kelas', 'siswa.jurusan', 'perusahaan', 'periodePkl']);

        return view('cetak.surat_pengantar', compact('penempatan'));
    }

    public function raporPkl(Penempatan $penempatan)
    {
        $penempatan->load(['siswa.kelas', 'siswa.jurusan', 'guru', 'perusahaan', 'periodePkl', 'penilaian']);
        
        // Rekap kehadiran
        $absensi = \App\Models\AbsensiPkl::where('penempatan_id', $penempatan->id)->get();
        $rekapAbsensi = [
            'hadir' => $absensi->where('status', 'hadir')->count(),
            'izin'  => $absensi->where('status', 'izin')->count(),
            'sakit' => $absensi->where('status', 'sakit')->count(),
            'alfa'  => $absensi->where('status', 'alfa')->count(),
            'total' => $absensi->count()
        ];

        // Total jurnal divalidasi
        $totalJurnal = \App\Models\JurnalPkl::where('penempatan_id', $penempatan->id)->count();
        $jurnalDisetujui = \App\Models\JurnalPkl::where('penempatan_id', $penempatan->id)->where('status_validasi', 'disetujui')->count();

        // Pengaturan sekolah
        $settings = \App\Models\Setting::pluck('value', 'key')->all();

        return view('cetak.rapor-pkl', compact('penempatan', 'rekapAbsensi', 'totalJurnal', 'jurnalDisetujui', 'settings'));
    }
}
