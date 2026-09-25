<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AbsensiPkl;
use App\Models\Penempatan;
use Illuminate\Support\Facades\Auth;

class PantauanMapController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $guru = $user->guru;

        if (! $guru) {
            return redirect()->route('guru.dashboard')->with('error', 'Data guru tidak ditemukan.');
        }

        // Get all placements for this guru
        $penempatanIds = Penempatan::where('guru_id', $guru->id)->pluck('id');

        // Get today's attendance records with locations
        $today = now()->format('Y-m-d');
        $absensi = AbsensiPkl::with(['penempatan.siswa', 'penempatan.perusahaan'])
            ->whereIn('penempatan_id', $penempatanIds)
            ->where('tanggal', $today)
            ->whereNotNull('lokasi_masuk')
            ->get();

        return view('guru.pantauan_map.index', compact('absensi'));
    }
}
