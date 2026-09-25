<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AbsensiPkl;
use App\Models\Jurusan;
use App\Models\Penempatan;
use App\Models\PeriodePkl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    private function baseQuery()
    {
        $query = Penempatan::with([
            'siswa.kelas',
            'siswa.jurusan',
            'perusahaan',
            'guru',
            'periodePkl',
            'penilaian',
        ]);

        if (Auth::user()->role?->nama_role === 'guru') {
            $guruId = Auth::user()->guru?->id;
            $query->where('guru_id', $guruId);
        }

        return $query;
    }

    public function index()
    {
        $totalSiswa = $this->baseQuery()->count();
        $totalPerusahaan = $this->baseQuery()->distinct('perusahaan_id')->count('perusahaan_id');
        $sudahDinilai = $this->baseQuery()->whereHas('penilaian')->count();
        $belumDinilai = $totalSiswa - $sudahDinilai;

        $periodeList = PeriodePkl::all();
        $jurusanList = Jurusan::all();

        return view('admin.laporan.index', compact(
            'totalSiswa',
            'totalPerusahaan',
            'sudahDinilai',
            'belumDinilai',
            'periodeList',
            'jurusanList'
        ));
    }

    public function absensi(Request $request)
    {
        $periodeId = $request->input('periode_id');
        $jurusanId = $request->input('jurusan_id');
        $q = trim((string) $request->input('q'));

        $query = $this->baseQuery();

        if ($periodeId) {
            $query->where('periode_pkl_id', $periodeId);
        }

        if ($jurusanId) {
            $query->whereHas('siswa', function ($sq) use ($jurusanId) {
                $sq->where('jurusan_id', $jurusanId);
            });
        }

        if ($q !== '') {
            $query->whereHas('siswa', function ($sq) use ($q) {
                $sq->where('nama', 'like', "%{$q}%")
                    ->orWhere('nis', 'like', "%{$q}%");
            })->orWhereHas('perusahaan', function ($pq) use ($q) {
                $pq->where('nama_perusahaan', 'like', "%{$q}%");
            });
        }

        $penempatanList = $query->get()->map(function ($p) {
            // Hitung absensi per siswa
            $absensi = AbsensiPkl::where('penempatan_id', $p->id)->get();
            $p->hadir_count = $absensi->where('status', 'hadir')->count();
            $p->izin_count = $absensi->where('status', 'izin')->count();
            $p->sakit_count = $absensi->where('status', 'sakit')->count();
            $p->alpa_count = $absensi->where('status', 'alpa')->count();
            $p->total_absen = $absensi->count();
            $p->persen_kehadiran = $p->total_absen > 0 ? round(($p->hadir_count / $p->total_absen) * 100, 1) : 0;

            return $p;
        });

        $periodeList = PeriodePkl::all();
        $jurusanList = Jurusan::all();

        return view('admin.laporan.absensi', compact('penempatanList', 'periodeList', 'jurusanList', 'periodeId', 'jurusanId', 'q'));
    }

    public function nilai(Request $request)
    {
        $periodeId = $request->input('periode_id');
        $jurusanId = $request->input('jurusan_id');
        $q = trim((string) $request->input('q'));

        $query = $this->baseQuery();

        if ($periodeId) {
            $query->where('periode_pkl_id', $periodeId);
        }

        if ($jurusanId) {
            $query->whereHas('siswa', function ($sq) use ($jurusanId) {
                $sq->where('jurusan_id', $jurusanId);
            });
        }

        if ($q !== '') {
            $query->whereHas('siswa', function ($sq) use ($q) {
                $sq->where('nama', 'like', "%{$q}%")
                    ->orWhere('nis', 'like', "%{$q}%");
            });
        }

        $penempatanList = $query->get();
        $periodeList = PeriodePkl::all();
        $jurusanList = Jurusan::all();

        return view('admin.laporan.nilai', compact('penempatanList', 'periodeList', 'jurusanList', 'periodeId', 'jurusanId', 'q'));
    }
}
