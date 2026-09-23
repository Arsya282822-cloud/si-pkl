<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\LembarObservasi;
use App\Models\Penempatan;
use App\Models\Perusahaan;
use App\Services\KompetensiObservasiService;
use App\Exports\LembarObservasiExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LembarObservasiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = $user->role?->nama_role;
        $guru = $user->guru;

        $query = LembarObservasi::with(['penempatan.siswa.kelas', 'penempatan.perusahaan', 'guru']);

        if ($role === 'guru' && $guru) {
            $query->where('guru_id', $guru->id);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($sub) use ($q) {
                $sub->where('nama_murid', 'like', "%{$q}%")
                    ->orWhere('nama_institusi', 'like', "%{$q}%")
                    ->orWhere('konsentrasi_keahlian', 'like', "%{$q}%");
            });
        }

        $observasis = $query->latest('id')->paginate(10)->withQueryString();

        return view('guru.observasi.index', compact('observasis', 'guru'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $role = $user->role?->nama_role;
        $guru = $user->guru;

        // List siswa bimbingan guru atau seluruh siswa untuk admin
        $penempatans = Penempatan::with(['siswa.kelas', 'siswa.jurusan', 'perusahaan.pembimbingIndustri', 'guru'])
            ->when($role === 'guru' && $guru, function($q) use ($guru) {
                $q->where('guru_id', $guru->id);
            })
            ->get();

        $selectedPenempatan = null;
        if ($request->filled('penempatan_id')) {
            $selectedPenempatan = $penempatans->firstWhere('id', $request->penempatan_id);
        }

        $presets = KompetensiObservasiService::getPresets();
        $defaultSoftskills = KompetensiObservasiService::getSoftSkills();
        $defaultAnalisis = KompetensiObservasiService::getAnalisisUsaha();

        $selectedJurusan = $selectedPenempatan?->siswa?->jurusan?->nama_jurusan ?? 'RPL';
        $defaultKompetensi = KompetensiObservasiService::getKompetensiByJurusan($selectedJurusan);

        return view('guru.observasi.create', compact(
            'penempatans',
            'selectedPenempatan',
            'presets',
            'defaultSoftskills',
            'defaultKompetensi',
            'defaultAnalisis',
            'guru'
        ));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $guru = $user->guru;

        $request->validate([
            'penempatan_id' => 'nullable|exists:penempatan,id',
            'nama_institusi' => 'required|string|max:255',
            'nama_murid' => 'required|string|max:255',
            'konsentrasi_keahlian' => 'required|string|max:255',
        ]);

        $penempatan = $request->filled('penempatan_id') ? Penempatan::find($request->penempatan_id) : null;

        // Process Softskills JSON
        $softskills = [];
        if ($request->has('softskills')) {
            foreach ($request->softskills as $item) {
                if (!empty($item['tujuan'])) {
                    $obs1 = is_numeric($item['obs1'] ?? '') ? (float)$item['obs1'] : null;
                    $obs2 = is_numeric($item['obs2'] ?? '') ? (float)$item['obs2'] : null;
                    $akhir = is_numeric($item['akhir'] ?? '') ? (float)$item['akhir'] : null;
                    $vals = array_filter([$obs1, $obs2, $akhir], fn($v) => !is_null($v));
                    $rerata = count($vals) > 0 ? round(array_sum($vals) / count($vals), 1) : null;

                    $softskills[] = [
                        'tujuan' => $item['tujuan'],
                        'obs1' => $obs1,
                        'obs2' => $obs2,
                        'akhir' => $akhir,
                        'rerata' => $rerata,
                    ];
                }
            }
        }

        // Process Kompetensi Teknis POS JSON
        $kompetensiTeknis = [];
        if ($request->has('kompetensi_teknis')) {
            foreach ($request->kompetensi_teknis as $item) {
                if (!empty($item['tujuan'])) {
                    $obs1 = is_numeric($item['obs1'] ?? '') ? (float)$item['obs1'] : null;
                    $obs2 = is_numeric($item['obs2'] ?? '') ? (float)$item['obs2'] : null;
                    $akhir = is_numeric($item['akhir'] ?? '') ? (float)$item['akhir'] : null;
                    $vals = array_filter([$obs1, $obs2, $akhir], fn($v) => !is_null($v));
                    $rerata = count($vals) > 0 ? round(array_sum($vals) / count($vals), 1) : null;

                    $kompetensiTeknis[] = [
                        'tujuan' => $item['tujuan'],
                        'obs1' => $obs1,
                        'obs2' => $obs2,
                        'akhir' => $akhir,
                        'rerata' => $rerata,
                    ];
                }
            }
        }

        // Process Kompetensi Baru JSON
        $kompetensiBaru = [];
        if ($request->has('kompetensi_baru')) {
            foreach ($request->kompetensi_baru as $item) {
                if (!empty($item['tujuan'])) {
                    $obs1 = is_numeric($item['obs1'] ?? '') ? (float)$item['obs1'] : null;
                    $obs2 = is_numeric($item['obs2'] ?? '') ? (float)$item['obs2'] : null;
                    $akhir = is_numeric($item['akhir'] ?? '') ? (float)$item['akhir'] : null;
                    $vals = array_filter([$obs1, $obs2, $akhir], fn($v) => !is_null($v));
                    $rerata = count($vals) > 0 ? round(array_sum($vals) / count($vals), 1) : null;

                    $kompetensiBaru[] = [
                        'tujuan' => $item['tujuan'],
                        'obs1' => $obs1,
                        'obs2' => $obs2,
                        'akhir' => $akhir,
                        'rerata' => $rerata,
                    ];
                }
            }
        }

        // Process Analisis Usaha JSON
        $analisisUsaha = [];
        if ($request->has('analisis_usaha')) {
            foreach ($request->analisis_usaha as $item) {
                if (!empty($item['tujuan'])) {
                    $obs1 = is_numeric($item['obs1'] ?? '') ? (float)$item['obs1'] : null;
                    $obs2 = is_numeric($item['obs2'] ?? '') ? (float)$item['obs2'] : null;
                    $akhir = is_numeric($item['akhir'] ?? '') ? (float)$item['akhir'] : null;
                    $vals = array_filter([$obs1, $obs2, $akhir], fn($v) => !is_null($v));
                    $rerata = count($vals) > 0 ? round(array_sum($vals) / count($vals), 1) : null;

                    $analisisUsaha[] = [
                        'tujuan' => $item['tujuan'],
                        'obs1' => $obs1,
                        'obs2' => $obs2,
                        'akhir' => $akhir,
                        'rerata' => $rerata,
                    ];
                }
            }
        }

        $observasi = LembarObservasi::create([
            'penempatan_id' => $penempatan?->id,
            'guru_id' => $guru?->id ?? $penempatan?->guru_id,
            'perusahaan_id' => $penempatan?->perusahaan_id,
            'siswa_id' => $penempatan?->siswa_id,
            'nama_institusi' => $request->nama_institusi,
            'alamat_institusi' => $request->alamat_institusi,
            'telp_institusi' => $request->telp_institusi,
            'nama_pimpinan' => $request->nama_pimpinan,
            'nip_pimpinan' => $request->nip_pimpinan,
            'jabatan_pimpinan' => $request->jabatan_pimpinan,
            'nama_instruktur' => $request->nama_instruktur,
            'nip_instruktur' => $request->nip_instruktur,
            'jabatan_instruktur' => $request->jabatan_instruktur,
            'nama_murid' => $request->nama_murid,
            'konsentrasi_keahlian' => $request->konsentrasi_keahlian,
            'data_softskills' => $softskills,
            'data_kompetensi_teknis' => $kompetensiTeknis,
            'data_kompetensi_baru' => $kompetensiBaru,
            'data_analisis_usaha' => $analisisUsaha,
            'tgl_observasi_1' => $request->tgl_observasi_1,
            'tgl_observasi_2' => $request->tgl_observasi_2,
            'tgl_observasi_akhir' => $request->tgl_observasi_akhir,
            'tgl_cetak' => $request->tgl_cetak ?: date('Y-m-d'),
            'kota_cetak' => $request->kota_cetak ?: 'Pekanbaru',
            'catatan_umum' => $request->catatan_umum,
        ]);

        \App\Services\ActivityLogger::log(
            'Lembar Observasi',
            'Pengisian Lembar Observasi Online',
            'Pengisian lembar observasi PKL untuk ' . $request->nama_murid . ' di ' . $request->nama_institusi
        );

        $prefix = Auth::user()->role?->nama_role === 'admin' ? 'admin' : 'guru';

        if ($request->action === 'simpan_cetak') {
            return redirect()->route($prefix . '.observasi.cetak', $observasi->id);
        }

        return redirect()->route($prefix . '.observasi.index')->with('success', 'Lembar Observasi PKL berhasil disimpan!');
    }

    public function edit(LembarObservasi $observasi)
    {
        $user = Auth::user();
        $role = $user->role?->nama_role;
        $guru = $user->guru;

        $penempatans = Penempatan::with(['siswa.kelas', 'siswa.jurusan', 'perusahaan.pembimbingIndustri', 'guru'])
            ->when($role === 'guru' && $guru, function($q) use ($guru) {
                $q->where('guru_id', $guru->id);
            })
            ->get();

        $presets = KompetensiObservasiService::getPresets();
        $defaultSoftskills = KompetensiObservasiService::getSoftSkills();
        $defaultAnalisis = KompetensiObservasiService::getAnalisisUsaha();

        return view('guru.observasi.edit', compact(
            'observasi',
            'penempatans',
            'presets',
            'defaultSoftskills',
            'defaultAnalisis',
            'guru'
        ));
    }

    public function update(Request $request, LembarObservasi $observasi)
    {
        $request->validate([
            'nama_institusi' => 'required|string|max:255',
            'nama_murid' => 'required|string|max:255',
            'konsentrasi_keahlian' => 'required|string|max:255',
        ]);

        // Process Softskills JSON
        $softskills = [];
        if ($request->has('softskills')) {
            foreach ($request->softskills as $item) {
                if (!empty($item['tujuan'])) {
                    $obs1 = is_numeric($item['obs1'] ?? '') ? (float)$item['obs1'] : null;
                    $obs2 = is_numeric($item['obs2'] ?? '') ? (float)$item['obs2'] : null;
                    $akhir = is_numeric($item['akhir'] ?? '') ? (float)$item['akhir'] : null;
                    $vals = array_filter([$obs1, $obs2, $akhir], fn($v) => !is_null($v));
                    $rerata = count($vals) > 0 ? round(array_sum($vals) / count($vals), 1) : null;

                    $softskills[] = [
                        'tujuan' => $item['tujuan'],
                        'obs1' => $obs1,
                        'obs2' => $obs2,
                        'akhir' => $akhir,
                        'rerata' => $rerata,
                    ];
                }
            }
        }

        // Process Kompetensi Teknis POS JSON
        $kompetensiTeknis = [];
        if ($request->has('kompetensi_teknis')) {
            foreach ($request->kompetensi_teknis as $item) {
                if (!empty($item['tujuan'])) {
                    $obs1 = is_numeric($item['obs1'] ?? '') ? (float)$item['obs1'] : null;
                    $obs2 = is_numeric($item['obs2'] ?? '') ? (float)$item['obs2'] : null;
                    $akhir = is_numeric($item['akhir'] ?? '') ? (float)$item['akhir'] : null;
                    $vals = array_filter([$obs1, $obs2, $akhir], fn($v) => !is_null($v));
                    $rerata = count($vals) > 0 ? round(array_sum($vals) / count($vals), 1) : null;

                    $kompetensiTeknis[] = [
                        'tujuan' => $item['tujuan'],
                        'obs1' => $obs1,
                        'obs2' => $obs2,
                        'akhir' => $akhir,
                        'rerata' => $rerata,
                    ];
                }
            }
        }

        // Process Kompetensi Baru JSON
        $kompetensiBaru = [];
        if ($request->has('kompetensi_baru')) {
            foreach ($request->kompetensi_baru as $item) {
                if (!empty($item['tujuan'])) {
                    $obs1 = is_numeric($item['obs1'] ?? '') ? (float)$item['obs1'] : null;
                    $obs2 = is_numeric($item['obs2'] ?? '') ? (float)$item['obs2'] : null;
                    $akhir = is_numeric($item['akhir'] ?? '') ? (float)$item['akhir'] : null;
                    $vals = array_filter([$obs1, $obs2, $akhir], fn($v) => !is_null($v));
                    $rerata = count($vals) > 0 ? round(array_sum($vals) / count($vals), 1) : null;

                    $kompetensiBaru[] = [
                        'tujuan' => $item['tujuan'],
                        'obs1' => $obs1,
                        'obs2' => $obs2,
                        'akhir' => $akhir,
                        'rerata' => $rerata,
                    ];
                }
            }
        }

        // Process Analisis Usaha JSON
        $analisisUsaha = [];
        if ($request->has('analisis_usaha')) {
            foreach ($request->analisis_usaha as $item) {
                if (!empty($item['tujuan'])) {
                    $obs1 = is_numeric($item['obs1'] ?? '') ? (float)$item['obs1'] : null;
                    $obs2 = is_numeric($item['obs2'] ?? '') ? (float)$item['obs2'] : null;
                    $akhir = is_numeric($item['akhir'] ?? '') ? (float)$item['akhir'] : null;
                    $vals = array_filter([$obs1, $obs2, $akhir], fn($v) => !is_null($v));
                    $rerata = count($vals) > 0 ? round(array_sum($vals) / count($vals), 1) : null;

                    $analisisUsaha[] = [
                        'tujuan' => $item['tujuan'],
                        'obs1' => $obs1,
                        'obs2' => $obs2,
                        'akhir' => $akhir,
                        'rerata' => $rerata,
                    ];
                }
            }
        }

        $observasi->update([
            'nama_institusi' => $request->nama_institusi,
            'alamat_institusi' => $request->alamat_institusi,
            'telp_institusi' => $request->telp_institusi,
            'nama_pimpinan' => $request->nama_pimpinan,
            'nip_pimpinan' => $request->nip_pimpinan,
            'jabatan_pimpinan' => $request->jabatan_pimpinan,
            'nama_instruktur' => $request->nama_instruktur,
            'nip_instruktur' => $request->nip_instruktur,
            'jabatan_instruktur' => $request->jabatan_instruktur,
            'nama_murid' => $request->nama_murid,
            'konsentrasi_keahlian' => $request->konsentrasi_keahlian,
            'data_softskills' => $softskills,
            'data_kompetensi_teknis' => $kompetensiTeknis,
            'data_kompetensi_baru' => $kompetensiBaru,
            'data_analisis_usaha' => $analisisUsaha,
            'tgl_observasi_1' => $request->tgl_observasi_1,
            'tgl_observasi_2' => $request->tgl_observasi_2,
            'tgl_observasi_akhir' => $request->tgl_observasi_akhir,
            'tgl_cetak' => $request->tgl_cetak ?: date('Y-m-d'),
            'kota_cetak' => $request->kota_cetak ?: 'Pekanbaru',
            'catatan_umum' => $request->catatan_umum,
        ]);

        $prefix = Auth::user()->role?->nama_role === 'admin' ? 'admin' : 'guru';

        if ($request->action === 'simpan_cetak') {
            return redirect()->route($prefix . '.observasi.cetak', $observasi->id);
        }

        return redirect()->route($prefix . '.observasi.index')->with('success', 'Perubahan Lembar Observasi berhasil disimpan!');
    }

    public function cetak(LembarObservasi $observasi)
    {
        $observasi->load(['penempatan.siswa', 'penempatan.perusahaan', 'guru']);
        return view('guru.observasi.cetak', compact('observasi'));
    }

    public function destroy(LembarObservasi $observasi)
    {
        $prefix = Auth::user()->role?->nama_role === 'admin' ? 'admin' : 'guru';
        $observasi->delete();
        return redirect()->route($prefix . '.observasi.index')->with('success', 'Lembar Observasi berhasil dihapus.');
    }

    public function exportExcel(LembarObservasi $observasi)
    {
        $data = [
            'nama_institusi' => $observasi->nama_institusi,
            'alamat_institusi' => $observasi->alamat_institusi,
            'telp_institusi' => $observasi->telp_institusi,
            'nama_pimpinan' => $observasi->nama_pimpinan,
            'nip_pimpinan' => $observasi->nip_pimpinan,
            'jabatan_pimpinan' => $observasi->jabatan_pimpinan,
            'nama_instruktur' => $observasi->nama_instruktur,
            'nip_instruktur' => $observasi->nip_instruktur,
            'jabatan_instruktur' => $observasi->jabatan_instruktur,
            'nama_murid' => $observasi->nama_murid,
            'jurusan' => $observasi->konsentrasi_keahlian,
        ];

        $export = new LembarObservasiExport($data);
        $namaFile = 'lembar_observasi_' . \Illuminate\Support\Str::slug($observasi->nama_murid ?: 'siswa') . '.xlsx';
        return $export->download($namaFile);
    }
}
