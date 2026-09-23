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

        $settings = [
            'nama_sekolah' => Setting::get('sekolah_nama_sekolah', 'SMK LABOR BINAAN FKIP UNRI PEKANBARU'),
            'kepala_sekolah' => Setting::get('pejabat_kepala_sekolah', 'Drs. Hendripides, M.Si'),
            'nip_kepala_sekolah' => Setting::get('pejabat_nip_kepala_sekolah', '19680504 199303 1 003'),
            'akreditasi' => Setting::get('sekolah_akreditasi', 'TERAKREDITASI "A" (UNGGUL)'),
            'npsn' => Setting::get('sekolah_npsn', '10403993'),
        ];

        return view('verifikasi.sertifikat', compact('penempatan', 'settings'));
    }
}
