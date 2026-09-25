<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Admin\CetakController;
use App\Http\Controllers\Controller;
use App\Models\AbsensiPkl;
use App\Models\JurnalPkl;
use App\Models\Penempatan;
use Illuminate\Support\Facades\Auth;

class PenempatanInfoController extends Controller
{
    private function getStudentPlacement()
    {
        $siswa = Auth::user()->siswa;
        if (! $siswa) {
            return null;
        }

        return Penempatan::where('siswa_id', $siswa->id)
            ->with(['perusahaan', 'guru', 'periodePkl', 'penilaian'])
            ->latest('id')
            ->first();
    }

    public function index()
    {
        $penempatan = $this->getStudentPlacement();

        return view('siswa.penempatan.index', compact('penempatan'));
    }

    public function nilai()
    {
        $penempatan = $this->getStudentPlacement();
        $penilaian = $penempatan?->penilaian;

        return view('siswa.penempatan.nilai', compact('penempatan', 'penilaian'));
    }

    public function cetakJurnal()
    {
        $penempatan = $this->getStudentPlacement();
        if (! $penempatan) {
            return back()->with('error', 'Data penempatan belum ditemukan.');
        }

        $penempatan->load(['siswa.kelas', 'siswa.jurusan', 'guru', 'perusahaan', 'periodePkl']);
        $jurnal = JurnalPkl::where('penempatan_id', $penempatan->id)->orderBy('tanggal')->get();
        $absensi = AbsensiPkl::where('penempatan_id', $penempatan->id)->orderBy('tanggal')->get();

        return view('cetak.jurnal', compact('penempatan', 'jurnal', 'absensi'));
    }

    public function sertifikat()
    {
        $penempatan = $this->getStudentPlacement();
        if (! $penempatan) {
            return back()->with('error', 'Data penempatan belum ditemukan.');
        }

        $penempatan->load(['siswa.kelas', 'siswa.jurusan', 'perusahaan', 'guru', 'periodePkl', 'penilaian']);

        return view('admin.dokumen.sertifikat', compact('penempatan'));
    }

    public function cetakRapor()
    {
        $penempatan = $this->getStudentPlacement();
        if (! $penempatan) {
            return back()->with('error', 'Data penempatan belum ditemukan.');
        }

        return app(CetakController::class)->raporPkl($penempatan);
    }
}
