<?php

namespace App\Http\Controllers\Instruktur;

use App\Http\Controllers\Controller;
use App\Models\AbsensiPkl;
use App\Models\Penempatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $pembimbing = $user->pembimbingIndustri;
        $perusahaan = $pembimbing?->perusahaan;

        if (! $perusahaan) {
            return redirect()->route('instruktur.dashboard')->with('error', 'Akun belum terhubung ke perusahaan.');
        }

        $penempatanIds = Penempatan::where('perusahaan_id', $perusahaan->id)->pluck('id');

        $tanggal = $request->get('tanggal', now()->format('Y-m-d'));
        $status = $request->get('status');

        $query = AbsensiPkl::whereIn('penempatan_id', $penempatanIds)
            ->with(['penempatan.siswa.kelas', 'penempatan.siswa.jurusan'])
            ->latest('tanggal');

        if ($tanggal) {
            $query->where('tanggal', $tanggal);
        }

        if ($status && in_array($status, ['hadir', 'izin', 'sakit', 'alpha'])) {
            $query->where('status', $status);
        }

        $absensis = $query->paginate(15)->withQueryString();
        $absensi = $absensis;

        $summary = [
            'hadir' => AbsensiPkl::whereIn('penempatan_id', $penempatanIds)->where('tanggal', $tanggal)->where('status', 'hadir')->count(),
            'izin' => AbsensiPkl::whereIn('penempatan_id', $penempatanIds)->where('tanggal', $tanggal)->where('status', 'izin')->count(),
            'sakit' => AbsensiPkl::whereIn('penempatan_id', $penempatanIds)->where('tanggal', $tanggal)->where('status', 'sakit')->count(),
            'alpha' => AbsensiPkl::whereIn('penempatan_id', $penempatanIds)->where('tanggal', $tanggal)->where('status', 'alpha')->count(),
            'total_siswa' => $penempatanIds->count(),
        ];
        $rekapHariIni = $summary;

        return view('instruktur.absensi.index', compact('absensis', 'absensi', 'perusahaan', 'tanggal', 'status', 'summary', 'rekapHariIni'));
    }
}
