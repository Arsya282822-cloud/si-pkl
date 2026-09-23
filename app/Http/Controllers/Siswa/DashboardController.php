<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Penempatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Find penempatan for this student
        // Assuming the currently active periode is what we want, or just get the latest one
        $penempatan = null;
        $pengajuanAktif = null;
        if ($user->siswa) {
            $penempatan = Penempatan::with(['perusahaan', 'guru', 'periodePkl'])
                ->where('siswa_id', $user->siswa->id)
                ->latest()
                ->first();

            $pengajuanAktif = \App\Models\PengajuanPkl::where('siswa_id', $user->siswa->id)
                ->latest()
                ->first();
        }

        $pengumumans = \App\Models\Pengumuman::aktif()
            ->forRole('siswa')
            ->orderByDesc('is_pinned')
            ->latest()
            ->get();

        return view('siswa.dashboard', compact('penempatan', 'pengajuanAktif', 'pengumumans'));
    }
}
