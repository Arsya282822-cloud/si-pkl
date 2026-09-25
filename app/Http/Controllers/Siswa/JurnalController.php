<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\JurnalPkl;
use App\Models\Penempatan;
use App\Models\TujuanPembelajaran;
use App\Services\ActivityLogger;
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

        return view('siswa.jurnal.index', compact('jurnal', 'penempatan'));
    }

    public function create()
    {
        $user = Auth::user();
        $penempatan = Penempatan::where('siswa_id', $user->siswa->id)->with('siswa.jurusan')->latest()->first();

        if (! $penempatan) {
            return redirect()->route('siswa.dashboard')->with('error', 'Anda belum ditempatkan, tidak dapat mengisi jurnal.');
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

        return view('siswa.jurnal.create', compact('penempatan', 'tujuanPembelajarans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kegiatan' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();
        $penempatan = Penempatan::where('siswa_id', $user->siswa->id)->latest()->first();

        if (! $penempatan) {
            return redirect()->route('siswa.dashboard')->with('error', 'Anda belum ditempatkan.');
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

        return redirect()->route('siswa.jurnal.index')->with('success', 'Jurnal harian berhasil ditambahkan.');
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

        $request->validate([
            'tanggal' => 'required|date',
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
