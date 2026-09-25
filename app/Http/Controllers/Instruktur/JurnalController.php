<?php

namespace App\Http\Controllers\Instruktur;

use App\Http\Controllers\Controller;
use App\Models\JurnalPkl;
use App\Models\Penempatan;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JurnalController extends Controller
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

        $status = $request->get('status', 'all');
        $search = $request->get('search');

        $query = JurnalPkl::whereIn('penempatan_id', $penempatanIds)
            ->with(['penempatan.siswa.kelas', 'penempatan.siswa.jurusan'])
            ->latest('tanggal');

        if ($status !== 'all' && in_array($status, ['menunggu', 'disetujui', 'ditolak'])) {
            $query->where('status_validasi', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kegiatan', 'like', "%{$search}%")
                    ->orWhereHas('penempatan.siswa', function ($sub) use ($search) {
                        $sub->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        $jurnals = $query->paginate(12)->withQueryString();

        $stats = [
            'total' => JurnalPkl::whereIn('penempatan_id', $penempatanIds)->count(),
            'menunggu' => JurnalPkl::whereIn('penempatan_id', $penempatanIds)->where('status_validasi', 'menunggu')->count(),
            'disetujui' => JurnalPkl::whereIn('penempatan_id', $penempatanIds)->where('status_validasi', 'disetujui')->count(),
        ];

        return view('instruktur.jurnal.index', compact('jurnals', 'perusahaan', 'status', 'search', 'stats'));
    }

    public function show(JurnalPkl $jurnal)
    {
        $user = Auth::user();
        $pembimbing = $user->pembimbingIndustri;
        $perusahaan = $pembimbing?->perusahaan;

        if (! $perusahaan || $jurnal->penempatan?->perusahaan_id !== $perusahaan->id) {
            abort(403, 'Akses ditolak: Jurnal ini bukan dari siswa di perusahaan Anda.');
        }

        $jurnal->load(['penempatan.siswa.kelas', 'penempatan.siswa.jurusan', 'penempatan.guru']);

        return view('instruktur.jurnal.show', compact('jurnal', 'perusahaan'));
    }

    public function update(Request $request, JurnalPkl $jurnal)
    {
        $user = Auth::user();
        $pembimbing = $user->pembimbingIndustri;
        $perusahaan = $pembimbing?->perusahaan;

        if (! $perusahaan || $jurnal->penempatan?->perusahaan_id !== $perusahaan->id) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'status_validasi' => 'required|in:disetujui,ditolak',
            'catatan_guru' => 'nullable|string|max:500',
            'kirim_laporan_guru' => 'nullable|boolean',
        ]);

        $catatan = $request->catatan_guru;
        if ($catatan) {
            if ($request->boolean('kirim_laporan_guru')) {
                $catatan = '[LAPORAN INSTRUKTUR DUDI]: '.$catatan;
            } else {
                $catatan = '[Catatan Instruktur]: '.$catatan;
            }
        }

        $jurnal->update([
            'status_validasi' => $request->status_validasi,
            'komentar_guru' => $catatan ?: $jurnal->komentar_guru,
        ]);

        ActivityLogger::log(
            'Validasi Jurnal DUDI',
            $request->boolean('kirim_laporan_guru') ? 'Laporan Khusus Instruktur ke Guru' : 'Validasi Jurnal oleh Instruktur',
            'Instruktur '.($pembimbing?->nama ?? 'DUDI').' memvalidasi jurnal '.($jurnal->penempatan?->siswa?->nama ?? 'siswa').' status: '.$request->status_validasi.($request->boolean('kirim_laporan_guru') ? ' (Dengan Laporan ke Guru)' : '')
        );

        return redirect()->route('instruktur.jurnal.index')->with('success', 'Jurnal harian berhasil divalidasi oleh Instruktur DUDI!');
    }
}
