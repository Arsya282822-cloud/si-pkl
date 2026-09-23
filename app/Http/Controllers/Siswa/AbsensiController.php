<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\AbsensiPkl;
use App\Models\Penempatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $penempatan = Penempatan::where('siswa_id', $user->siswa->id)->latest()->first();

        if (!$penempatan) {
            return redirect()->route('siswa.dashboard')->with('error', 'Anda belum ditempatkan.');
        }

        $absensi = AbsensiPkl::where('penempatan_id', $penempatan->id)
            ->orderBy('tanggal', 'desc')
            ->paginate(15);

        // Check if already absen today
        $hari_ini = AbsensiPkl::where('penempatan_id', $penempatan->id)
            ->where('tanggal', now()->format('Y-m-d'))
            ->first();

        // Hitung rekap
        $rekap = [
            'hadir' => AbsensiPkl::where('penempatan_id', $penempatan->id)->where('status', 'hadir')->count(),
            'izin' => AbsensiPkl::where('penempatan_id', $penempatan->id)->where('status', 'izin')->count(),
            'sakit' => AbsensiPkl::where('penempatan_id', $penempatan->id)->where('status', 'sakit')->count(),
            'alpha' => AbsensiPkl::where('penempatan_id', $penempatan->id)->where('status', 'alpha')->count(),
        ];

        return view('siswa.absensi.index', compact('absensi', 'penempatan', 'rekap', 'hari_ini'));
    }

    public function create()
    {
        $user = Auth::user();
        $penempatan = Penempatan::where('siswa_id', $user->siswa->id)->latest()->first();

        if (!$penempatan) {
            return redirect()->route('siswa.dashboard')->with('error', 'Anda belum ditempatkan.');
        }

        // Check if already absen today
        $today = AbsensiPkl::where('penempatan_id', $penempatan->id)
            ->where('tanggal', now()->format('Y-m-d'))
            ->first();

        if ($today) {
            return redirect()->route('siswa.absensi.index')->with('error', 'Anda sudah melakukan absensi hari ini.');
        }

        return view('siswa.absensi.create', compact('penempatan'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $penempatan = Penempatan::where('siswa_id', $user->siswa->id)->latest()->first();

        // Cek jika sudah absen
        $today = AbsensiPkl::where('penempatan_id', $penempatan->id)
            ->where('tanggal', now()->format('Y-m-d'))
            ->first();
            
        if ($today) {
            return redirect()->route('siswa.absensi.index')->with('error', 'Anda sudah melakukan absensi hari ini.');
        }

        // Jika form untuk izin/sakit (dikirim dari form create)
        if ($request->has('status') && in_array($request->status, ['izin', 'sakit'])) {
            $request->validate([
                'status' => 'required|in:izin,sakit',
                'keterangan' => 'required|string|max:500',
            ]);

            AbsensiPkl::create([
                'penempatan_id' => $penempatan->id,
                'tanggal' => now()->format('Y-m-d'),
                'status' => $request->status,
                'keterangan' => $request->keterangan,
            ]);

            \App\Services\ActivityLogger::log(
                'Presensi Siswa',
                'Pengajuan ' . ucfirst($request->status),
                'Mengajukan ' . $request->status . ': ' . $request->keterangan
            );

            return redirect()->route('siswa.absensi.index')->with('success', 'Pengajuan ' . ucfirst($request->status) . ' berhasil dikirim.');
        }

        // Jika tombol Absen Masuk diklik (Hadir Real-time)
        $waktuMasuk = now();
        AbsensiPkl::create([
            'penempatan_id' => $penempatan->id,
            'tanggal' => $waktuMasuk->format('Y-m-d'),
            'status' => 'hadir',
            'jam_masuk' => $waktuMasuk->format('H:i:s'),
            'lokasi_masuk' => $request->lokasi // Menerima data koordinat GPS
        ]);

        \App\Services\ActivityLogger::log(
            'Presensi Siswa',
            'Presensi Masuk',
            'Presensi masuk pukul ' . $waktuMasuk->format('H:i') . ' WIB' . ($request->lokasi ? ' (Koordinat: ' . $request->lokasi . ')' : '')
        );

        return redirect()->route('siswa.absensi.index')->with('success', 'Berhasil Presensi Masuk pada ' . $waktuMasuk->format('H:i') . ' WIB');
    }

    public function update(Request $request, string $id)
    {
        $absensi = AbsensiPkl::findOrFail($id);
        
        $user = Auth::user();
        $penempatan = Penempatan::where('siswa_id', $user->siswa->id)->latest()->first();

        // Pastikan absensi milik siswa ini dan belum keluar
        if ($absensi->penempatan_id != $penempatan->id) {
            abort(403);
        }

        if ($absensi->jam_keluar != null) {
            return redirect()->route('siswa.absensi.index')->with('error', 'Anda sudah melakukan Presensi Pulang sebelumnya.');
        }

        $waktuKeluar = now();
        $absensi->update([
            'jam_keluar' => $waktuKeluar->format('H:i:s'),
            'lokasi_keluar' => $request->lokasi // Menerima data koordinat GPS
        ]);

        \App\Services\ActivityLogger::log(
            'Presensi Siswa',
            'Presensi Pulang',
            'Presensi pulang pukul ' . $waktuKeluar->format('H:i') . ' WIB'
        );

        return redirect()->route('siswa.absensi.index')->with('success', 'Berhasil Presensi Pulang pada ' . $waktuKeluar->format('H:i') . ' WIB');
    }
}
