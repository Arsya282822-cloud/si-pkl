<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Penempatan;
use App\Models\PeriodePkl;
use App\Models\Perusahaan;
use App\Models\Siswa;
use Illuminate\Http\Request;

class PenempatanController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q;
        $penempatan = Penempatan::with(['siswa', 'guru', 'perusahaan', 'periodePkl'])
            ->when($q, function ($query) use ($q) {
                $query->whereHas('siswa', function ($qSiswa) use ($q) {
                    $qSiswa->where('nama', 'like', "%{$q}%")
                        ->orWhere('nis', 'like', "%{$q}%");
                })
                    ->orWhereHas('perusahaan', function ($qPerusahaan) use ($q) {
                        $qPerusahaan->where('nama_perusahaan', 'like', "%{$q}%");
                    });
            })
            ->latest()
            ->paginate(10);

        return view('admin.penempatan.index', compact('penempatan', 'q'));
    }

    public function create()
    {
        // Get active periods
        $periode = PeriodePkl::where('status', 'aktif')->get();

        // Get siswa that are NOT placed in active periods
        $placedSiswaIds = Penempatan::whereHas('periodePkl', function ($q) {
            $q->where('status', 'aktif');
        })->pluck('siswa_id');

        $siswa = Siswa::whereNotIn('id', $placedSiswaIds)->get();
        $guru = Guru::all();
        $perusahaan = Perusahaan::where('status', 'aktif')->get();

        return view('admin.penempatan.create', compact('siswa', 'guru', 'perusahaan', 'periode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|array',
            'siswa_id.*' => 'exists:siswa,id',
            'perusahaan_id' => 'required|exists:perusahaan,id',
            'guru_id' => 'required|exists:guru,id',
            'periode_pkl_id' => 'required|exists:periode_pkl,id',
        ]);

        foreach ($request->siswa_id as $siswaId) {
            Penempatan::create([
                'siswa_id' => $siswaId,
                'perusahaan_id' => $request->perusahaan_id,
                'guru_id' => $request->guru_id,
                'periode_pkl_id' => $request->periode_pkl_id,
            ]);
        }

        return redirect()->route('admin.penempatan.index')->with('success', 'Data Penempatan berhasil ditambahkan!');
    }

    public function edit(Penempatan $penempatan)
    {
        $periode = PeriodePkl::all();
        $siswa = Siswa::all();
        $guru = Guru::all();
        $perusahaan = Perusahaan::all();

        return view('admin.penempatan.edit', compact('penempatan', 'periode', 'siswa', 'guru', 'perusahaan'));
    }

    public function update(Request $request, Penempatan $penempatan)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'perusahaan_id' => 'required|exists:perusahaan,id',
            'guru_id' => 'required|exists:guru,id',
            'periode_pkl_id' => 'required|exists:periode_pkl,id',
        ]);

        $penempatan->update($request->only('siswa_id', 'perusahaan_id', 'guru_id', 'periode_pkl_id'));

        return redirect()->route('admin.penempatan.index')->with('success', 'Data Penempatan berhasil diperbarui!');
    }

    public function destroy(Penempatan $penempatan)
    {
        $penempatan->delete();

        return redirect()->route('admin.penempatan.index')->with('success', 'Data Penempatan berhasil dihapus!');
    }
}
