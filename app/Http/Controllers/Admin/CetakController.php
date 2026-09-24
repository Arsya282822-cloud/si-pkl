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
        $user = auth()->user();
        if ($user && $user->role?->nama_role === 'guru' && $user->guru && $penempatan->guru_id !== $user->guru->id) {
            abort(403, 'Anda tidak memiliki akses untuk mencetak rapor siswa di luar bimbingan Anda.');
        }

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

        // Data Lembar Observasi jika sudah diisi oleh Guru Pembimbing
        $observasi = \App\Models\LembarObservasi::where('penempatan_id', $penempatan->id)->latest('id')->first();

        // Pengaturan sekolah yang terstandarisasi
        $settings = [
            'nama_yayasan'        => \App\Models\Setting::get('sekolah_nama_yayasan', 'YAYASAN UNIVERSITAS RIAU'),
            'nama_sekolah'        => \App\Models\Setting::get('sekolah_nama_sekolah', 'SMK LABOR BINAAN FKIP UNRI PEKANBARU'),
            'npsn'                => \App\Models\Setting::get('sekolah_npsn', '10403993'),
            'akreditasi'          => \App\Models\Setting::get('sekolah_akreditasi', 'TERAKREDITASI "A" (UNGGUL)'),
            'alamat_sekolah'      => \App\Models\Setting::get('sekolah_alamat', 'Jl. Thamrin No. 97 Kec. Sail Pekanbaru – 28132'),
            'telepon_sekolah'     => \App\Models\Setting::get('sekolah_telepon', '0761 – 28760'),
            'email_sekolah'       => \App\Models\Setting::get('sekolah_email', 'smk_labor@yahoo.com'),
            'website_sekolah'     => \App\Models\Setting::get('sekolah_website', 'www.smklabor.sch.id'),
            'kota_terbit'         => \App\Models\Setting::get('sekolah_kota_terbit', 'Pekanbaru'),
            'nama_kepala_sekolah' => \App\Models\Setting::get('pejabat_kepala_sekolah', 'JEFFRI HUNTER, M.Pd'),
            'nip_kepala_sekolah'  => \App\Models\Setting::get('pejabat_nip_kepala_sekolah', '-'),
            'ketua_pokja'         => \App\Models\Setting::get('pejabat_ketua_pokja', 'Mahendra, S.Pd., M.Si.'),
            'nip_ketua_pokja'     => \App\Models\Setting::get('pejabat_nip_ketua_pokja', '-'),
        ];

        return view('cetak.rapor-pkl', compact('penempatan', 'rekapAbsensi', 'totalJurnal', 'jurnalDisetujui', 'observasi', 'settings'));
    }
}
