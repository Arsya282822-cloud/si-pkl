<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\JurnalPkl;
use App\Models\Penempatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidasiJurnalController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $guru = $user->guru;

        if (!$guru) {
            return redirect()->route('guru.dashboard')->with('error', 'Data guru tidak ditemukan.');
        }

        // Get all penempatan IDs assigned to this guru
        $penempatanIds = Penempatan::where('guru_id', $guru->id)->pluck('id');

        $status = $request->status;
        $q = $request->q;

        $query = JurnalPkl::with(['penempatan.siswa.kelas', 'penempatan.perusahaan'])
            ->whereIn('penempatan_id', $penempatanIds);

        if ($status === 'laporan_khusus') {
            $query->where('komentar_guru', 'like', '%[LAPORAN INSTRUKTUR DUDI]%');
        } elseif ($status && in_array($status, ['menunggu', 'disetujui', 'ditolak'])) {
            $query->where('status_validasi', $status);
        }

        if ($q) {
            $query->whereHas('penempatan.siswa', function ($qSiswa) use ($q) {
                $qSiswa->where('nama', 'like', "%{$q}%");
            });
        }

        $jurnal = $query->orderBy('tanggal', 'desc')->paginate(15)->withQueryString();

        $stats = [
            'total' => JurnalPkl::whereIn('penempatan_id', $penempatanIds)->count(),
            'disetujui' => JurnalPkl::whereIn('penempatan_id', $penempatanIds)->where('status_validasi', 'disetujui')->count(),
            'menunggu' => JurnalPkl::whereIn('penempatan_id', $penempatanIds)->where('status_validasi', 'menunggu')->count(),
            'ditolak' => JurnalPkl::whereIn('penempatan_id', $penempatanIds)->where('status_validasi', 'ditolak')->count(),
            'laporan_khusus' => JurnalPkl::whereIn('penempatan_id', $penempatanIds)->where('komentar_guru', 'like', '%[LAPORAN INSTRUKTUR DUDI]%')->count(),
        ];

        return view('guru.validasi.index', compact('jurnal', 'status', 'q', 'stats'));
    }

    public function show(JurnalPkl $jurnal)
    {
        $user = Auth::user();
        $guru = $user->guru;

        // Check ownership
        $penempatanIds = Penempatan::where('guru_id', $guru->id)->pluck('id');
        if (!$penempatanIds->contains($jurnal->penempatan_id)) {
            abort(403, 'Unauthorized action.');
        }

        $jurnal->load(['penempatan.siswa.kelas', 'penempatan.siswa.jurusan', 'penempatan.perusahaan']);

        return view('guru.validasi.show', compact('jurnal'));
    }

    public function update(Request $request, JurnalPkl $jurnal)
    {
        $user = Auth::user();
        $guru = $user->guru;

        // Check ownership
        $penempatanIds = Penempatan::where('guru_id', $guru->id)->pluck('id');
        if (!$penempatanIds->contains($jurnal->penempatan_id)) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status_validasi' => 'required|in:disetujui,ditolak',
            'komentar_guru' => 'nullable|string|max:500',
        ]);

        $jurnal->update([
            'status_validasi' => $request->status_validasi,
            'komentar_guru' => $request->komentar_guru,
        ]);

        $statusLabel = $request->status_validasi === 'disetujui' ? 'disetujui' : 'ditolak';

        \App\Services\ActivityLogger::log(
            'jurnal',
            "Validasi Jurnal {$statusLabel}",
            "Guru {$guru->nama} memvalidasi jurnal milik siswa {$jurnal->penempatan->siswa->nama} (Status: {$statusLabel})"
        );

        return redirect()->route('guru.validasi.index')
            ->with('success', "Jurnal berhasil {$statusLabel}.");
    }
}
