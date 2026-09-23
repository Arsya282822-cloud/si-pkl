<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Penempatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaBimbinganController extends Controller
{
    public function index(Request $request)
    {
        $guru = Auth::user()->guru;

        if (!$guru) {
            return redirect()->route('guru.dashboard')->with('error', 'Data guru tidak ditemukan.');
        }

        $q = $request->q;

        $penempatan = Penempatan::with(['siswa.kelas', 'siswa.jurusan', 'perusahaan', 'periodePkl', 'penilaian', 'jurnal'])
            ->where('guru_id', $guru->id)
            ->when($q, function ($query) use ($q) {
                $query->whereHas('siswa', function ($qSiswa) use ($q) {
                    $qSiswa->where('nama', 'like', "%{$q}%")
                           ->orWhere('nis', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(15);

        return view('guru.siswa.index', compact('penempatan', 'q'));
    }
}
