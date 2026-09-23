<?php

namespace App\Http\Controllers\Instruktur;

use App\Http\Controllers\Controller;
use App\Models\Penempatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $pembimbing = $user->pembimbingIndustri;
        $perusahaan = $pembimbing?->perusahaan;

        if (!$perusahaan) {
            return redirect()->route('instruktur.dashboard')->with('error', 'Akun belum terhubung ke perusahaan.');
        }

        $search = $request->get('search') ?? $request->get('q');

        $query = Penempatan::where('perusahaan_id', $perusahaan->id)
            ->with(['siswa.kelas', 'siswa.jurusan', 'guru', 'periode']);

        if ($search) {
            $query->whereHas('siswa', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $penempatans = $query->paginate(15)->withQueryString();
        $siswaList = $penempatans;

        return view('instruktur.siswa.index', compact('penempatans', 'siswaList', 'perusahaan', 'search'));
    }
}
