<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Penempatan;
use App\Models\PenilaianPkl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenilaianController extends Controller
{
    public function index()
    {
        $guru = Auth::user()->guru;
        $penempatan = Penempatan::with(['siswa.kelas', 'perusahaan', 'periodePkl', 'penilaian'])
            ->where('guru_id', $guru->id)
            ->latest()
            ->get();

        return view('guru.penilaian.index', compact('penempatan'));
    }

    public function create(Penempatan $penempatan)
    {
        $guru = Auth::user()->guru;
        if ($penempatan->guru_id !== $guru->id) {
            abort(403);
        }

        if ($penempatan->penilaian) {
            return redirect()->route('guru.penilaian.edit', $penempatan);
        }

        $penempatan->load(['siswa.kelas', 'perusahaan']);

        return view('guru.penilaian.form', compact('penempatan'));
    }

    public function store(Request $request, Penempatan $penempatan)
    {
        $guru = Auth::user()->guru;
        if ($penempatan->guru_id !== $guru->id) {
            abort(403);
        }

        $request->validate([
            'nilai_sikap' => 'required|integer|min:0|max:100',
            'nilai_keterampilan' => 'required|integer|min:0|max:100',
            'nilai_pengetahuan' => 'required|integer|min:0|max:100',
            'catatan_guru' => 'nullable|string|max:1000',
        ]);

        $nilaiAkhir = round(($request->nilai_sikap + $request->nilai_keterampilan + $request->nilai_pengetahuan) / 3, 2);

        PenilaianPkl::create([
            'penempatan_id' => $penempatan->id,
            'nilai_sikap' => $request->nilai_sikap,
            'nilai_keterampilan' => $request->nilai_keterampilan,
            'nilai_pengetahuan' => $request->nilai_pengetahuan,
            'nilai_akhir' => $nilaiAkhir,
            'catatan_guru' => $request->catatan_guru,
        ]);

        return redirect()->route('guru.penilaian.index')->with('success', 'Penilaian berhasil disimpan.');
    }

    public function edit(Penempatan $penempatan)
    {
        $guru = Auth::user()->guru;
        if ($penempatan->guru_id !== $guru->id) {
            abort(403);
        }

        $penempatan->load(['siswa.kelas', 'perusahaan', 'penilaian']);

        return view('guru.penilaian.form', compact('penempatan'));
    }

    public function update(Request $request, Penempatan $penempatan)
    {
        $guru = Auth::user()->guru;
        if ($penempatan->guru_id !== $guru->id) {
            abort(403);
        }

        $request->validate([
            'nilai_sikap' => 'required|integer|min:0|max:100',
            'nilai_keterampilan' => 'required|integer|min:0|max:100',
            'nilai_pengetahuan' => 'required|integer|min:0|max:100',
            'catatan_guru' => 'nullable|string|max:1000',
        ]);

        $nilaiAkhir = round(($request->nilai_sikap + $request->nilai_keterampilan + $request->nilai_pengetahuan) / 3, 2);

        $penempatan->penilaian->update([
            'nilai_sikap' => $request->nilai_sikap,
            'nilai_keterampilan' => $request->nilai_keterampilan,
            'nilai_pengetahuan' => $request->nilai_pengetahuan,
            'nilai_akhir' => $nilaiAkhir,
            'catatan_guru' => $request->catatan_guru,
        ]);

        return redirect()->route('guru.penilaian.index')->with('success', 'Penilaian berhasil diperbarui.');
    }

    public function sertifikat(Penempatan $penempatan)
    {
        $guru = Auth::user()->guru;
        if ($penempatan->guru_id !== $guru->id) {
            abort(403);
        }

        $penempatan->load(['siswa.kelas', 'siswa.jurusan', 'perusahaan', 'guru', 'periodePkl', 'penilaian']);

        return view('admin.dokumen.sertifikat', compact('penempatan'));
    }
}
