<?php

namespace App\Http\Controllers;

use App\Models\Penempatan;
use App\Models\Setting;
use Illuminate\Http\Request;

class VerifikasiController extends Controller
{
    public function sertifikat(Penempatan $penempatan)
    {
        $penempatan->load(['siswa.kelas', 'siswa.jurusan', 'perusahaan', 'guru', 'periodePkl', 'penilaian']);

        $absensiHadir = \App\Models\AbsensiPkl::where('penempatan_id', $penempatan->id)->where('status', 'hadir')->count();
        $jurnalDisetujui = \App\Models\JurnalPkl::where('penempatan_id', $penempatan->id)->where('status_validasi', 'disetujui')->count();
        $observasi = \App\Models\LembarObservasi::where('penempatan_id', $penempatan->id)->latest('id')->first();

        $settings = [
            'nama_sekolah' => Setting::get('sekolah_nama_sekolah', 'SMK LABOR BINAAN FKIP UNRI PEKANBARU'),
            'kepala_sekolah' => Setting::get('pejabat_kepala_sekolah', 'JEFFRI HUNTER, M.Pd'),
            'nip_kepala_sekolah' => Setting::get('pejabat_nip_kepala_sekolah', '-'),
            'akreditasi' => Setting::get('sekolah_akreditasi', 'TERAKREDITASI "A" (UNGGUL)'),
            'npsn' => Setting::get('sekolah_npsn', '10403993'),
        ];

        return view('verifikasi.sertifikat', compact('penempatan', 'settings', 'absensiHadir', 'jurnalDisetujui', 'observasi'));
    }
}
