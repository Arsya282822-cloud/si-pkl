<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Monitoring;
use App\Models\Penempatan;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MonitoringController extends Controller
{
    public function index()
    {
        $guru = Auth::user()->guru;
        $monitoring = Monitoring::with('perusahaan')
            ->where('guru_id', $guru->id)
            ->orderBy('tanggal_kunjungan', 'desc')
            ->paginate(10);

        return view('guru.monitoring.index', compact('monitoring'));
    }

    public function create()
    {
        $guru = Auth::user()->guru;
        // Get perusahaan where this guru has placements
        $perusahaanIds = Penempatan::where('guru_id', $guru->id)->pluck('perusahaan_id')->unique();
        $perusahaan = Perusahaan::whereIn('id', $perusahaanIds)->get();

        return view('guru.monitoring.create', compact('perusahaan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'perusahaan_id' => 'required|exists:perusahaan,id',
            'tanggal_kunjungan' => 'required|date',
            'catatan' => 'required|string',
            'kesesuaian_kompetensi' => 'nullable|string|max:50',
            'kedisiplinan_siswa' => 'nullable|string|max:50',
            'kendala_observasi' => 'nullable|string',
            'saran_dudi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:3072',
            'file_observasi' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xlsx,xls|max:10240',
        ]);

        $guru = Auth::user()->guru;
        $fotoPath = null;
        $berkasPath = null;

        // Upload foto
        if ($request->hasFile('foto')) {
            $dir = public_path('uploads/monitoring');
            if (!file_exists($dir)) {
                @mkdir($dir, 0777, true);
            }
            $file = $request->file('foto');
            $fileName = 'foto_obs_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $file->move($dir, $fileName);
            $fotoPath = 'uploads/monitoring/' . $fileName;
        }

        // Upload berkas scan observasi
        if ($request->hasFile('file_observasi')) {
            $dir = public_path('uploads/berkas_observasi');
            if (!file_exists($dir)) {
                @mkdir($dir, 0777, true);
            }
            $file = $request->file('file_observasi');
            $fileName = 'berkas_obs_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $file->move($dir, $fileName);
            $berkasPath = 'uploads/berkas_observasi/' . $fileName;
        }

        $perusahaan = Perusahaan::find($request->perusahaan_id);

        $monitoring = Monitoring::create([
            'guru_id' => $guru->id,
            'perusahaan_id' => $request->perusahaan_id,
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'catatan' => $request->catatan,
            'kesesuaian_kompetensi' => $request->kesesuaian_kompetensi ?? 'sesuai',
            'kedisiplinan_siswa' => $request->kedisiplinan_siswa ?? 'baik',
            'kendala_observasi' => $request->kendala_observasi,
            'saran_dudi' => $request->saran_dudi,
            'foto' => $fotoPath,
            'file_observasi' => $berkasPath,
        ]);

        \App\Services\ActivityLogger::log(
            'Monitoring & Observasi',
            'Unggah Berkas Observasi',
            'Guru ' . $guru->nama . ' mendokumentasikan observasi di ' . ($perusahaan?->nama_perusahaan ?? 'DUDI')
        );

        return redirect()->route('guru.monitoring.index')->with('success', 'Berkas & Catatan Kunjungan Observasi PKL berhasil disimpan!');
    }

    public function blanko()
    {
        $guru = Auth::user()->guru;
        return view('guru.monitoring.blanko', compact('guru'));
    }

    public function exportExcel(Request $request)
    {
        $guru = Auth::user()->guru;
        $data = [];

        if ($request->filled('penempatan_id')) {
            $penempatan = Penempatan::with(['siswa.jurusan', 'perusahaan.pembimbingIndustri', 'guru'])->find($request->penempatan_id);
            if ($penempatan) {
                $pembimbing = $penempatan->perusahaan?->pembimbingIndustri?->first();
                $data = [
                    'nama_institusi' => $penempatan->perusahaan?->nama_perusahaan,
                    'alamat_institusi' => $penempatan->perusahaan?->alamat,
                    'telp_institusi' => $penempatan->perusahaan?->no_telepon,
                    'nama_pimpinan' => $penempatan->perusahaan?->nama_pimpinan,
                    'jabatan_pimpinan' => 'Pimpinan Perusahaan / DUDI',
                    'nama_instruktur' => $pembimbing?->nama ?? $penempatan->perusahaan?->pembimbing_industri,
                    'jabatan_instruktur' => $pembimbing?->jabatan ?? 'Pembimbing Lapangan / Instruktur',
                    'nama_murid' => $penempatan->siswa?->nama,
                    'jurusan' => $penempatan->siswa?->jurusan?->nama_jurusan,
                ];
            }
        }

        $export = new \App\Exports\LembarObservasiExport($data);
        $namaFile = 'lembar_observasi_' . ($data['nama_murid'] ? \Illuminate\Support\Str::slug($data['nama_murid']) : 'pkl') . '.xlsx';
        return $export->download($namaFile);
    }

    public function cetak(Monitoring $monitoring)
    {
        $monitoring->load(['guru', 'perusahaan']);
        
        // Get all students under this teacher at this company
        $siswaList = Penempatan::where('perusahaan_id', $monitoring->perusahaan_id)
            ->where('guru_id', $monitoring->guru_id)
            ->with(['siswa.kelas', 'siswa.jurusan', 'periodePkl'])
            ->get();

        return view('guru.monitoring.cetak', compact('monitoring', 'siswaList'));
    }
}
