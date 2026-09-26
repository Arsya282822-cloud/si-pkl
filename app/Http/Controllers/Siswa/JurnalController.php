<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\JurnalPkl;
use App\Models\Penempatan;
use App\Models\TujuanPembelajaran;
use App\Services\ActivityLogger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JurnalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (! $user->siswa) {
            return redirect()->route('siswa.dashboard')->with('error', 'Data siswa tidak ditemukan.');
        }

        $penempatan = Penempatan::where('siswa_id', $user->siswa->id)->latest()->first();

        if (! $penempatan) {
            return redirect()->route('siswa.dashboard')->with('error', 'Anda belum ditempatkan, tidak dapat mengisi jurnal.');
        }

        $jurnal = JurnalPkl::where('penempatan_id', $penempatan->id)
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        // Cek apakah ada kewajiban jurnal kemarin yang belum diisi
        $unfilledPast = AbsensiController::getUnfilledPastAttendance($penempatan->id);

        return view('siswa.jurnal.index', compact('jurnal', 'penempatan', 'unfilledPast'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $penempatan = Penempatan::where('siswa_id', $user->siswa->id)->with('siswa.jurusan', 'perusahaan')->latest()->first();

        if (! $penempatan) {
            return redirect()->route('siswa.dashboard')->with('error', 'Anda belum ditempatkan, tidak dapat mengisi jurnal.');
        }

        // Cek jika siswa memiliki jurnal tertinggal yang mengunci absensi
        $unfilledPast = AbsensiController::getUnfilledPastAttendance($penempatan->id);

        $targetTanggal = now()->format('Y-m-d');
        $isCatchup = false;

        if ($unfilledPast) {
            // Prioritaskan tanggal tertinggal agar kunci presensi terbuka
            $targetTanggal = $unfilledPast->tanggal;
            $isCatchup = true;
        } elseif ($request->filled('tanggal') && $request->tanggal <= now()->format('Y-m-d')) {
            $targetTanggal = $request->tanggal;
        }

        // Cek jika sudah pernah mengisi jurnal untuk targetTanggal tersebut
        $existingJournal = JurnalPkl::where('penempatan_id', $penempatan->id)
            ->where('tanggal', $targetTanggal)
            ->first();

        if ($existingJournal) {
            return redirect()->route('siswa.jurnal.index')
                ->with('error', 'Jurnal kegiatan untuk tanggal '.Carbon::parse($targetTanggal)->translatedFormat('d F Y').' sudah pernah dibuat.');
        }

        $kodeJurusan = $penempatan->siswa?->jurusan?->kode_jurusan;
        $tujuanPembelajarans = TujuanPembelajaran::where('status', 'aktif')
            ->when($kodeJurusan, function ($q) use ($kodeJurusan) {
                $q->where('kode_jurusan', $kodeJurusan);
            })
            ->orderBy('capaian_pembelajaran')
            ->orderBy('nomor_urut')
            ->get()
            ->groupBy('capaian_pembelajaran');

        return view('siswa.jurnal.create', compact('penempatan', 'tujuanPembelajarans', 'targetTanggal', 'isCatchup'));
    }

    public function store(Request $request)
    {
        $today = now()->format('Y-m-d');

        $request->validate([
            'tanggal' => "required|date|before_or_equal:{$today}",
            'kegiatan' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'tanggal.before_or_equal' => 'Tanggal kegiatan tidak boleh memilih tanggal di masa depan.',
        ]);

        $user = Auth::user();
        $penempatan = Penempatan::where('siswa_id', $user->siswa->id)->latest()->first();

        if (! $penempatan) {
            return redirect()->route('siswa.dashboard')->with('error', 'Anda belum ditempatkan.');
        }

        // Cek duplikasi jurnal di tanggal yang sama
        $alreadyExists = JurnalPkl::where('penempatan_id', $penempatan->id)
            ->where('tanggal', $request->tanggal)
            ->exists();

        if ($alreadyExists) {
            return redirect()->route('siswa.jurnal.index')->with('error', 'Jurnal untuk tanggal '.Carbon::parse($request->tanggal)->translatedFormat('d F Y').' sudah pernah dibuat.');
        }

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fileName = 'jurnal_'.time().'_'.preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $file->move(public_path('uploads/jurnal'), $fileName);
            $fotoPath = 'uploads/jurnal/'.$fileName;
        }

        JurnalPkl::create([
            'penempatan_id' => $penempatan->id,
            'tanggal' => $request->tanggal,
            'kegiatan' => $request->kegiatan,
            'foto' => $fotoPath,
            'status_validasi' => 'menunggu',
        ]);

        ActivityLogger::log(
            'Jurnal Siswa',
            'Tambah Jurnal PKL',
            'Mengisi jurnal kegiatan tanggal '.$request->tanggal
        );

        $successMsg = 'Jurnal kegiatan harian berhasil disimpan.';
        if ($request->tanggal < $today) {
            $successMsg = 'Jurnal tertinggal berhasil disimpan! Kunci presensi hari ini telah terbuka kembali. Silakan lakukan Presensi Masuk.';
        }

        return redirect()->route('siswa.jurnal.index')->with('success', $successMsg);
    }

    public function edit(JurnalPkl $jurnal)
    {
        $user = Auth::user();
        $penempatan = Penempatan::where('siswa_id', $user->siswa->id)->with('siswa.jurusan')->latest()->first();

        // Check ownership
        if ($jurnal->penempatan_id !== $penempatan->id) {
            abort(403, 'Unauthorized action.');
        }

        // Check if status is waiting
        if ($jurnal->status_validasi !== 'menunggu') {
            return redirect()->route('siswa.jurnal.index')->with('error', 'Jurnal yang sudah diproses tidak dapat diedit.');
        }

        $kodeJurusan = $penempatan->siswa?->jurusan?->kode_jurusan;
        $tujuanPembelajarans = TujuanPembelajaran::where('status', 'aktif')
            ->when($kodeJurusan, function ($q) use ($kodeJurusan) {
                $q->where('kode_jurusan', $kodeJurusan);
            })
            ->orderBy('capaian_pembelajaran')
            ->orderBy('nomor_urut')
            ->get()
            ->groupBy('capaian_pembelajaran');

        return view('siswa.jurnal.edit', compact('jurnal', 'penempatan', 'tujuanPembelajarans'));
    }

    public function update(Request $request, JurnalPkl $jurnal)
    {
        $user = Auth::user();
        $penempatan = Penempatan::where('siswa_id', $user->siswa->id)->latest()->first();

        // Check ownership
        if ($jurnal->penempatan_id !== $penempatan->id) {
            abort(403, 'Unauthorized action.');
        }

        // Check if status is waiting
        if ($jurnal->status_validasi !== 'menunggu') {
            return redirect()->route('siswa.jurnal.index')->with('error', 'Jurnal yang sudah diproses tidak dapat diedit.');
        }

        $today = now()->format('Y-m-d');
        $request->validate([
            'tanggal' => "required|date|before_or_equal:{$today}",
            'kegiatan' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:3072',
        ]);

        $data = [
            'tanggal' => $request->tanggal,
            'kegiatan' => $request->kegiatan,
        ];

        if ($request->hasFile('foto')) {
            // Delete old photo
            if ($jurnal->foto && file_exists(public_path($jurnal->foto))) {
                @unlink(public_path($jurnal->foto));
            }
            $file = $request->file('foto');
            $fileName = 'jurnal_'.time().'_'.preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $file->move(public_path('uploads/jurnal'), $fileName);
            $data['foto'] = 'uploads/jurnal/'.$fileName;
        }

        $jurnal->update($data);

        return redirect()->route('siswa.jurnal.index')->with('success', 'Jurnal harian berhasil diperbarui.');
    }
}
