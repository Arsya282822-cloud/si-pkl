<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penempatan;
use App\Models\Guru;
use App\Models\PeriodePkl;
use Illuminate\Http\Request;

class DokumenController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q'));
        $tab = $request->input('tab', 'pengantar');

        $penempatanList = Penempatan::with(['siswa.kelas', 'siswa.jurusan', 'perusahaan', 'guru', 'periodePkl'])
            ->when($q !== '', function ($query) use ($q) {
                $query->whereHas('siswa', function ($sq) use ($q) {
                    $sq->where('nama', 'like', "%{$q}%")->orWhere('nis', 'like', "%{$q}%");
                })->orWhereHas('perusahaan', function ($pq) use ($q) {
                    $pq->where('nama_perusahaan', 'like', "%{$q}%");
                });
            })
            ->latest('id')
            ->paginate(15);

        $guruList = Guru::withCount('penempatan')
            ->when($q !== '', function ($query) use ($q) {
                $query->where('nama', 'like', "%{$q}%")->orWhere('nip', 'like', "%{$q}%");
            })
            ->get();

        $kelasList = \App\Models\Kelas::all();
        $jurusanList = \App\Models\Jurusan::all();
        $periodeList = PeriodePkl::all();
        $perusahaanList = \App\Models\Perusahaan::where('status', 'aktif')->orderBy('nama_perusahaan')->get();

        return view('admin.dokumen.index', compact(
            'penempatanList', 
            'guruList', 
            'q', 
            'tab', 
            'kelasList', 
            'jurusanList', 
            'periodeList', 
            'perusahaanList'
        ));
    }

    public function suratPengantar(Penempatan $penempatan)
    {
        $penempatan->load(['siswa.kelas', 'siswa.jurusan', 'perusahaan', 'guru', 'periodePkl']);
        return view('admin.dokumen.surat_pengantar', compact('penempatan'));
    }

    public function batchSuratPengantar(Request $request)
    {
        $perusahaanId = $request->input('perusahaan_id');
        $kelasId = $request->input('kelas_id');
        $periodeId = $request->input('periode_id');

        $query = Penempatan::with(['siswa.kelas', 'siswa.jurusan', 'perusahaan', 'guru', 'periodePkl']);

        if ($perusahaanId) {
            $query->where('perusahaan_id', $perusahaanId);
        }
        if ($kelasId) {
            $query->whereHas('siswa', fn($q) => $q->where('kelas_id', $kelasId));
        }
        if ($periodeId) {
            $query->where('periode_pkl_id', $periodeId);
        }

        $penempatanList = $query->get();
        $perusahaan = $perusahaanId ? \App\Models\Perusahaan::find($perusahaanId) : null;
        $periode = $periodeId ? PeriodePkl::find($periodeId) : PeriodePkl::where('status', 'aktif')->first();

        return view('admin.dokumen.batch_surat_pengantar', compact('penempatanList', 'perusahaan', 'periode'));
    }

    public function suratTugas(Guru $guru, Request $request)
    {
        $periodeId = $request->input('periode_id');
        $query = Penempatan::where('guru_id', $guru->id)->with(['siswa.kelas', 'siswa.jurusan', 'perusahaan', 'periodePkl']);

        if ($periodeId) {
            $query->where('periode_pkl_id', $periodeId);
        }

        $bimbinganList = $query->get();
        $periode = $periodeId ? PeriodePkl::find($periodeId) : PeriodePkl::where('status', 'aktif')->first();

        return view('admin.dokumen.surat_tugas', compact('guru', 'bimbinganList', 'periode'));
    }

    public function suratPenarikan(Penempatan $penempatan)
    {
        $penempatan->load(['siswa.kelas', 'siswa.jurusan', 'perusahaan', 'guru', 'periodePkl']);
        return view('admin.dokumen.surat_penarikan', compact('penempatan'));
    }

    public function sertifikat(Penempatan $penempatan)
    {
        $penempatan->load(['siswa.kelas', 'siswa.jurusan', 'perusahaan', 'guru', 'periodePkl', 'penilaian']);
        return view('admin.dokumen.sertifikat', compact('penempatan'));
    }

    public function batchSertifikat(Request $request)
    {
        $kelasId = $request->input('kelas_id');
        $jurusanId = $request->input('jurusan_id');
        $periodeId = $request->input('periode_id');

        $query = Penempatan::with(['siswa.kelas', 'siswa.jurusan', 'perusahaan', 'guru', 'periodePkl', 'penilaian']);

        if ($kelasId) {
            $query->whereHas('siswa', fn($q) => $q->where('kelas_id', $kelasId));
        }
        if ($jurusanId) {
            $query->whereHas('siswa', fn($q) => $q->where('jurusan_id', $jurusanId));
        }
        if ($periodeId) {
            $query->where('periode_pkl_id', $periodeId);
        }

        $penempatanList = $query->get();
        $kelas = $kelasId ? \App\Models\Kelas::find($kelasId) : null;
        $jurusan = $jurusanId ? \App\Models\Jurusan::find($jurusanId) : null;
        $periode = $periodeId ? PeriodePkl::find($periodeId) : PeriodePkl::where('status', 'aktif')->first();

        return view('admin.dokumen.batch_sertifikat', compact('penempatanList', 'kelas', 'jurusan', 'periode'));
    }

    public function piagamDudi(Penempatan $penempatan)
    {
        $penempatan->load(['siswa.kelas', 'siswa.jurusan', 'perusahaan', 'periodePkl']);
        return view('admin.dokumen.piagam_dudi', compact('penempatan'));
    }
}

