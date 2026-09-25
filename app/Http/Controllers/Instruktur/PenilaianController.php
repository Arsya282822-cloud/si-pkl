<?php

namespace App\Http\Controllers\Instruktur;

use App\Http\Controllers\Controller;
use App\Models\Penempatan;
use App\Models\PenilaianPkl;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenilaianController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $pembimbing = $user->pembimbingIndustri;
        $perusahaan = $pembimbing?->perusahaan;

        if (! $perusahaan) {
            return redirect()->route('instruktur.dashboard')->with('error', 'Akun belum terhubung ke perusahaan.');
        }

        $penempatan = Penempatan::where('perusahaan_id', $perusahaan->id)
            ->with(['siswa.kelas', 'siswa.jurusan', 'penilaianPkl', 'guru'])
            ->get();

        return view('instruktur.penilaian.index', compact('penempatan', 'perusahaan'));
    }

    public function create(Penempatan $penempatan)
    {
        $user = Auth::user();
        $pembimbing = $user->pembimbingIndustri;
        $perusahaan = $pembimbing?->perusahaan;

        if (! $perusahaan || $penempatan->perusahaan_id !== $perusahaan->id) {
            abort(403);
        }

        $penempatan->load(['siswa.kelas', 'siswa.jurusan', 'penilaianPkl', 'guru']);

        return view('instruktur.penilaian.create', compact('penempatan', 'perusahaan'));
    }

    public function store(Request $request, Penempatan $penempatan)
    {
        $user = Auth::user();
        $pembimbing = $user->pembimbingIndustri;
        $perusahaan = $pembimbing?->perusahaan;

        if (! $perusahaan || $penempatan->perusahaan_id !== $perusahaan->id) {
            abort(403);
        }

        $request->validate([
            'nilai_sikap' => 'required|numeric|min:0|max:100',
            'nilai_keterampilan' => 'required|numeric|min:0|max:100',
            'nilai_pengetahuan' => 'required|numeric|min:0|max:100',
            'catatan_guru' => 'nullable|string|max:500',
        ]);

        $nilaiAkhir = round(($request->nilai_sikap * 0.3) + ($request->nilai_keterampilan * 0.5) + ($request->nilai_pengetahuan * 0.2), 2);

        PenilaianPkl::updateOrCreate(
            ['penempatan_id' => $penempatan->id],
            [
                'nilai_sikap' => $request->nilai_sikap,
                'nilai_keterampilan' => $request->nilai_keterampilan,
                'nilai_pengetahuan' => $request->nilai_pengetahuan,
                'nilai_akhir' => $nilaiAkhir,
                'catatan_guru' => $request->catatan_guru ? '[Catatan Instruktur]: '.$request->catatan_guru : 'Siswa berkinerja baik di industri.',
            ]
        );

        ActivityLogger::log(
            'Penilaian PKL DUDI',
            'Input Nilai Siswa oleh Instruktur',
            'Instruktur memberi nilai akhir '.$nilaiAkhir.' untuk '.($penempatan->siswa?->nama ?? 'siswa')
        );

        return redirect()->route('instruktur.penilaian.index')->with('success', 'Nilai PKL siswa berhasil disimpan!');
    }
}
