<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Penempatan;
use App\Models\PengajuanPkl;
use App\Models\Perusahaan;
use App\Services\ActivityLogger;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengajuanPklController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = $request->get('search');

        $query = PengajuanPkl::with(['siswa.kelas', 'siswa.jurusan', 'periode', 'verifikator'])
            ->latest();

        if ($status !== 'all' && in_array($status, ['menunggu', 'disetujui', 'ditolak'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_perusahaan', 'like', "%{$search}%")
                    ->orWhere('kota', 'like', "%{$search}%")
                    ->orWhereHas('siswa', function ($sq) use ($search) {
                        $sq->where('nama', 'like', "%{$search}%")
                            ->orWhere('nis', 'like', "%{$search}%");
                    });
            });
        }

        $pengajuans = $query->paginate(10)->withQueryString();

        // Metrics
        $stats = [
            'total' => PengajuanPkl::count(),
            'menunggu' => PengajuanPkl::where('status', 'menunggu')->count(),
            'disetujui' => PengajuanPkl::where('status', 'disetujui')->count(),
            'ditolak' => PengajuanPkl::where('status', 'ditolak')->count(),
        ];

        $gurus = Guru::orderBy('nama')->get();

        return view('admin.pengajuan.index', compact('pengajuans', 'stats', 'status', 'search', 'gurus'));
    }

    public function show($id)
    {
        $pengajuan = PengajuanPkl::with(['siswa.kelas', 'siswa.jurusan', 'periode', 'verifikator'])
            ->findOrFail($id);
        $gurus = Guru::orderBy('nama')->get();

        return view('admin.pengajuan.show', compact('pengajuan', 'gurus'));
    }

    public function approve(Request $request, $id)
    {
        $pengajuan = PengajuanPkl::findOrFail($id);

        $request->validate([
            'guru_id' => 'nullable|exists:guru,id',
            'catatan_verifikasi' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // 1. Cari atau buat data Perusahaan secara otomatis
            $perusahaan = Perusahaan::firstOrCreate(
                ['nama_perusahaan' => trim($pengajuan->nama_perusahaan)],
                [
                    'alamat' => $pengajuan->alamat_perusahaan,
                    'kota' => $pengajuan->kota ?? 'Pekanbaru',
                    'no_telepon' => $pengajuan->no_telepon,
                    'email' => $pengajuan->email,
                    'nama_pimpinan' => $pengajuan->nama_pimpinan ?? $pengajuan->kontak_person,
                    'status' => 'aktif',
                ]
            );

            // 2. Buat atau update Penempatan Siswa
            Penempatan::updateOrCreate(
                [
                    'siswa_id' => $pengajuan->siswa_id,
                    'periode_pkl_id' => $pengajuan->periode_pkl_id,
                ],
                [
                    'perusahaan_id' => $perusahaan->id,
                    'guru_id' => $request->guru_id,
                ]
            );

            // 3. Update status Pengajuan
            $pengajuan->update([
                'status' => 'disetujui',
                'catatan_verifikasi' => $request->catatan_verifikasi ?? 'Pengajuan disetujui oleh Administrator / Koordinator PKL.',
                'diverifikasi_oleh' => Auth::id(),
                'diverifikasi_pada' => now(),
            ]);

            DB::commit();

            // Kirim notifikasi WhatsApp otomatis ke siswa (jika gateway aktif)
            WhatsAppService::notifyPengajuanApproved($pengajuan);

            ActivityLogger::log(
                'pengajuan_pkl',
                'Persetujuan Pengajuan PKL Mandiri',
                "Pengajuan PKL siswa {$pengajuan->siswa->nama} di {$pengajuan->nama_perusahaan} disetujui oleh admin."
            );

            return redirect()->back()->with('success', 'Pengajuan tempat PKL berhasil disetujui! Data Penempatan Siswa dan Perusahaan otomatis dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Gagal memproses persetujuan: '.$e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $pengajuan = PengajuanPkl::findOrFail($id);

        $request->validate([
            'catatan_verifikasi' => 'required|string|max:500',
        ], [
            'catatan_verifikasi.required' => 'Alasan penolakan wajib diisi agar siswa mengetahui alasan perbaikan.',
        ]);

        $pengajuan->update([
            'status' => 'ditolak',
            'catatan_verifikasi' => $request->catatan_verifikasi,
            'diverifikasi_oleh' => Auth::id(),
            'diverifikasi_pada' => now(),
        ]);

        // Kirim notifikasi WhatsApp otomatis ke siswa (jika gateway aktif)
        WhatsAppService::notifyPengajuanRejected($pengajuan, $request->catatan_verifikasi);

        ActivityLogger::log(
            'pengajuan_pkl',
            'Penolakan Pengajuan PKL Mandiri',
            "Pengajuan PKL siswa {$pengajuan->siswa->nama} di {$pengajuan->nama_perusahaan} ditolak (Alasan: {$request->catatan_verifikasi})"
        );

        return redirect()->back()->with('success', 'Pengajuan tempat PKL telah ditolak dengan catatan yang dikirimkan ke siswa.');
    }
}
