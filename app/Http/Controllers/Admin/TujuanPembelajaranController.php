<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\Setting;
use App\Models\TujuanPembelajaran;
use Illuminate\Http\Request;

class TujuanPembelajaranController extends Controller
{
    /**
     * Display a listing of the Tujuan Pembelajaran.
     */
    public function index(Request $request)
    {
        $jurusans = Jurusan::orderBy('nama_jurusan')->get();

        $selectedJurusan = $request->get('jurusan', 'AK');
        $search = $request->get('search');
        $capaian = $request->get('capaian');

        $query = TujuanPembelajaran::with('jurusan')
            ->where('status', 'aktif');

        if ($selectedJurusan && $selectedJurusan !== 'all') {
            $query->where('kode_jurusan', $selectedJurusan);
        }

        if ($capaian) {
            $query->where('capaian_pembelajaran', 'like', "%{$capaian}%");
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('tujuan_pembelajaran', 'like', "%{$search}%")
                  ->orWhere('konsentrasi_keahlian', 'like', "%{$search}%");
            });
        }

        $items = $query->orderBy('kode_jurusan')
            ->orderBy('capaian_pembelajaran')
            ->orderBy('nomor_urut')
            ->get()
            ->groupBy('capaian_pembelajaran');

        // Statistics per jurusan
        $stats = TujuanPembelajaran::where('status', 'aktif')
            ->selectRaw('kode_jurusan, konsentrasi_keahlian, COUNT(*) as total')
            ->groupBy('kode_jurusan', 'konsentrasi_keahlian')
            ->get()
            ->keyBy('kode_jurusan');

        $totalAll = TujuanPembelajaran::where('status', 'aktif')->count();

        return view('admin.tujuan_pembelajaran.index', compact(
            'jurusans',
            'selectedJurusan',
            'items',
            'stats',
            'totalAll',
            'search',
            'capaian'
        ));
    }

    /**
     * Store a newly created Tujuan Pembelajaran.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jurusan_id'           => 'nullable|exists:jurusan,id',
            'kode_jurusan'         => 'required|string|max:20',
            'konsentrasi_keahlian' => 'required|string|max:255',
            'capaian_pembelajaran' => 'required|string',
            'nomor_urut'           => 'required|integer|min:1',
            'tujuan_pembelajaran'  => 'required|string',
            'tahun'                => 'nullable|string|max:10',
        ]);

        $validated['tahun'] = $validated['tahun'] ?? '2026';
        $validated['status'] = 'aktif';

        TujuanPembelajaran::create($validated);

        return redirect()->back()->with('success', 'Tujuan Pembelajaran berhasil ditambahkan.');
    }

    /**
     * Update the specified Tujuan Pembelajaran.
     */
    public function update(Request $request, TujuanPembelajaran $tujuan_pembelajaran)
    {
        $validated = $request->validate([
            'capaian_pembelajaran' => 'required|string',
            'nomor_urut'           => 'required|integer|min:1',
            'tujuan_pembelajaran'  => 'required|string',
            'tahun'                => 'nullable|string|max:10',
            'status'               => 'nullable|in:aktif,nonaktif',
        ]);

        $tujuan_pembelajaran->update($validated);

        return redirect()->back()->with('success', 'Tujuan Pembelajaran berhasil diperbarui.');
    }

    /**
     * Remove the specified Tujuan Pembelajaran.
     */
    public function destroy(TujuanPembelajaran $tujuan_pembelajaran)
    {
        $tujuan_pembelajaran->delete();

        return redirect()->back()->with('success', 'Tujuan Pembelajaran berhasil dihapus.');
    }

    /**
     * Print View for Tujuan Pembelajaran
     */
    public function cetak(Request $request)
    {
        $selectedJurusan = $request->get('jurusan', 'all');

        $query = TujuanPembelajaran::with('jurusan')->where('status', 'aktif');

        if ($selectedJurusan && $selectedJurusan !== 'all') {
            $query->where('kode_jurusan', $selectedJurusan);
        }

        $itemsByJurusan = $query->orderBy('kode_jurusan')
            ->orderBy('capaian_pembelajaran')
            ->orderBy('nomor_urut')
            ->get()
            ->groupBy('konsentrasi_keahlian');

        $settings = [
            'nama_sekolah'        => Setting::get('sekolah_nama_sekolah', 'SMK LABOR BINAAN FKIP UNRI PEKANBARU'),
            'nama_yayasan'        => Setting::get('sekolah_nama_yayasan', 'YAYASAN UNIVERSITAS RIAU'),
            'alamat_sekolah'      => Setting::get('sekolah_alamat', 'Jl. Thamrin No. 97 Kec. Sail Pekanbaru – 28132'),
            'telepon_sekolah'     => Setting::get('sekolah_telepon', '0761 – 28760'),
            'email_sekolah'       => Setting::get('sekolah_email', 'smklabor.unri@gmail.com'),
            'nama_kepala_sekolah' => Setting::get('pejabat_kepala_sekolah', 'JEFFRI HUNTER, M.Pd'),
            'nip_kepala_sekolah'  => Setting::get('pejabat_nip_kepala_sekolah', '-'),
            'ketua_pokja'         => Setting::get('pejabat_ketua_pokja', 'Mahendra, S.Pd., M.Si.'),
            'nip_ketua_pokja'     => Setting::get('pejabat_nip_ketua_pokja', '198904122019031005'),
            'kota_terbit'         => Setting::get('sekolah_kota_terbit', 'Pekanbaru'),
        ];

        return view('admin.tujuan_pembelajaran.cetak', compact('itemsByJurusan', 'selectedJurusan', 'settings'));
    }
}
