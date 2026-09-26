<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\AbsensiPkl;
use App\Models\JurnalPkl;
use App\Models\Penempatan;
use App\Services\ActivityLogger;
use App\Services\GeoLocationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    /**
     * Cari riwayat presensi hadir sebelum hari ini yang belum memiliki jurnal harian.
     */
    public static function getUnfilledPastAttendance(int $penempatanId): ?AbsensiPkl
    {
        $today = now()->format('Y-m-d');

        // Ambil riwayat kehadiran 'hadir' sebelum hari ini, urut dari yang paling baru
        $pastPresences = AbsensiPkl::where('penempatan_id', $penempatanId)
            ->where('status', 'hadir')
            ->where('tanggal', '<', $today)
            ->orderBy('tanggal', 'desc')
            ->get();

        foreach ($pastPresences as $presence) {
            $hasJournal = JurnalPkl::where('penempatan_id', $penempatanId)
                ->where('tanggal', $presence->tanggal)
                ->exists();

            if (! $hasJournal) {
                return $presence;
            }
        }

        return null;
    }

    public function index()
    {
        $user = Auth::user();
        $penempatan = Penempatan::with(['perusahaan', 'guru'])
            ->where('siswa_id', $user->siswa->id)
            ->latest()
            ->first();

        if (! $penempatan) {
            return redirect()->route('siswa.dashboard')->with('error', 'Anda belum ditempatkan.');
        }

        $absensi = AbsensiPkl::where('penempatan_id', $penempatan->id)
            ->orderBy('tanggal', 'desc')
            ->paginate(15);

        // Check if already absen today
        $hari_ini = AbsensiPkl::where('penempatan_id', $penempatan->id)
            ->where('tanggal', now()->format('Y-m-d'))
            ->first();

        // Cek apakah presensi terkunci karena ada jurnal kemarin yang belum diisi
        $lockedByJournal = self::getUnfilledPastAttendance($penempatan->id);

        // Hitung rekap
        $rekap = [
            'hadir' => AbsensiPkl::where('penempatan_id', $penempatan->id)->where('status', 'hadir')->count(),
            'izin' => AbsensiPkl::where('penempatan_id', $penempatan->id)->where('status', 'izin')->count(),
            'sakit' => AbsensiPkl::where('penempatan_id', $penempatan->id)->where('status', 'sakit')->count(),
            'alpha' => AbsensiPkl::where('penempatan_id', $penempatan->id)->where('status', 'alpha')->count(),
        ];

        return view('siswa.absensi.index', compact('absensi', 'penempatan', 'rekap', 'hari_ini', 'lockedByJournal'));
    }

    public function create()
    {
        $user = Auth::user();
        $penempatan = Penempatan::with('perusahaan')->where('siswa_id', $user->siswa->id)->latest()->first();

        if (! $penempatan) {
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
        $penempatan = Penempatan::with('perusahaan')->where('siswa_id', $user->siswa->id)->latest()->first();

        if (! $penempatan) {
            return redirect()->route('siswa.dashboard')->with('error', 'Anda belum memiliki penempatan.');
        }

        // Cek jika sudah absen hari ini
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

            ActivityLogger::log(
                'Presensi Siswa',
                'Pengajuan '.ucfirst($request->status),
                'Mengajukan '.$request->status.': '.$request->keterangan
            );

            return redirect()->route('siswa.absensi.index')->with('success', 'Pengajuan '.ucfirst($request->status).' berhasil dikirim.');
        }

        // Cek apakah presensi terkunci karena ada jurnal hari sebelumnya yang belum diisi
        $unfilled = self::getUnfilledPastAttendance($penempatan->id);
        if ($unfilled) {
            $tglFormatted = Carbon::parse($unfilled->tanggal)->translatedFormat('l, d F Y');

            return redirect()->route('siswa.absensi.index')->with('error', "Presensi hari ini terkunci! Anda belum mengisi jurnal kegiatan pada tanggal {$tglFormatted}. Silakan lengkapi jurnal hari tersebut terlebih dahulu.");
        }

        // Jika tombol Absen Masuk diklik (Hadir Real-time)
        $waktuMasuk = now();
        $lokasiString = $request->input('lokasi');
        $coords = GeoLocationService::parseCoordinates($lokasiString);

        $jarakMeter = null;
        $statusLokasi = 'tanpa_gps';

        if ($coords && $penempatan->perusahaan && $penempatan->perusahaan->latitude && $penempatan->perusahaan->longitude) {
            $companyLat = (float) $penempatan->perusahaan->latitude;
            $companyLng = (float) $penempatan->perusahaan->longitude;
            $jarakMeter = GeoLocationService::calculateDistance($coords[0], $coords[1], $companyLat, $companyLng);
            $maxRadius = $penempatan->perusahaan->radius_meter ?: 150;
            $statusLokasi = ($jarakMeter <= $maxRadius) ? 'dalam_radius' : 'luar_radius';
        } elseif ($coords) {
            $statusLokasi = 'dalam_radius'; // Jika perusahaan belum setting koordinat, anggap valid
        }

        AbsensiPkl::create([
            'penempatan_id' => $penempatan->id,
            'tanggal' => $waktuMasuk->format('Y-m-d'),
            'status' => 'hadir',
            'jam_masuk' => $waktuMasuk->format('H:i:s'),
            'lokasi_masuk' => $lokasiString,
            'jarak_masuk_meter' => $jarakMeter,
            'status_lokasi_masuk' => $statusLokasi,
        ]);

        $jarakInfo = $jarakMeter !== null ? " (Jarak: {$jarakMeter}m - ".($statusLokasi === 'dalam_radius' ? 'Valid' : 'Luar Radius').')' : '';

        ActivityLogger::log(
            'Presensi Siswa',
            'Presensi Masuk',
            'Presensi masuk pukul '.$waktuMasuk->format('H:i').' WIB'.$jarakInfo
        );

        $successMsg = 'Berhasil Presensi Masuk pada '.$waktuMasuk->format('H:i').' WIB';
        if ($statusLokasi === 'luar_radius') {
            $successMsg .= " (Perhatian: Jarak terdeteksi {$jarakMeter}m dari kantor DUDI).";
        }

        return redirect()->route('siswa.absensi.index')->with('success', $successMsg);
    }

    public function update(Request $request, string $id)
    {
        $absensi = AbsensiPkl::findOrFail($id);

        $user = Auth::user();
        $penempatan = Penempatan::with('perusahaan')->where('siswa_id', $user->siswa->id)->latest()->first();

        // Pastikan absensi milik siswa ini dan belum keluar
        if ($absensi->penempatan_id != $penempatan->id) {
            abort(403);
        }

        if ($absensi->jam_keluar != null) {
            return redirect()->route('siswa.absensi.index')->with('error', 'Anda sudah melakukan Presensi Pulang sebelumnya.');
        }

        $waktuKeluar = now();
        $lokasiString = $request->input('lokasi');
        $coords = GeoLocationService::parseCoordinates($lokasiString);

        $jarakMeter = null;
        $statusLokasi = 'tanpa_gps';

        if ($coords && $penempatan->perusahaan && $penempatan->perusahaan->latitude && $penempatan->perusahaan->longitude) {
            $companyLat = (float) $penempatan->perusahaan->latitude;
            $companyLng = (float) $penempatan->perusahaan->longitude;
            $jarakMeter = GeoLocationService::calculateDistance($coords[0], $coords[1], $companyLat, $companyLng);
            $maxRadius = $penempatan->perusahaan->radius_meter ?: 150;
            $statusLokasi = ($jarakMeter <= $maxRadius) ? 'dalam_radius' : 'luar_radius';
        } elseif ($coords) {
            $statusLokasi = 'dalam_radius';
        }

        $absensi->update([
            'jam_keluar' => $waktuKeluar->format('H:i:s'),
            'lokasi_keluar' => $lokasiString,
            'jarak_keluar_meter' => $jarakMeter,
            'status_lokasi_keluar' => $statusLokasi,
        ]);

        $jarakInfo = $jarakMeter !== null ? " (Jarak: {$jarakMeter}m - ".($statusLokasi === 'dalam_radius' ? 'Valid' : 'Luar Radius').')' : '';

        ActivityLogger::log(
            'Presensi Siswa',
            'Presensi Pulang',
            'Presensi pulang pukul '.$waktuKeluar->format('H:i').' WIB'.$jarakInfo
        );

        $successMsg = 'Berhasil Presensi Pulang pada '.$waktuKeluar->format('H:i').' WIB';
        if ($statusLokasi === 'luar_radius') {
            $successMsg .= " (Perhatian: Jarak terdeteksi {$jarakMeter}m dari kantor DUDI).";
        }

        return redirect()->route('siswa.absensi.index')->with('success', $successMsg);
    }
}
