<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPkl;
use App\Models\PeriodePkl;
use App\Models\Penempatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanPklController extends Controller
{
    public function index()
    {
        $siswa = Auth::user()->siswa;
        if (!$siswa) {
            return redirect()->route('siswa.dashboard')->with('error', 'Profil siswa tidak ditemukan.');
        }

        $pengajuans = PengajuanPkl::where('siswa_id', $siswa->id)
            ->with(['periode', 'verifikator'])
            ->latest()
            ->get();

        $penempatan = Penempatan::where('siswa_id', $siswa->id)->latest('id')->first();
        $hasPending = $pengajuans->where('status', 'menunggu')->isNotEmpty();
        $hasApproved = $pengajuans->where('status', 'disetujui')->isNotEmpty();

        return view('siswa.pengajuan.index', compact('pengajuans', 'penempatan', 'hasPending', 'hasApproved'));
    }

    public function create()
    {
        $siswa = Auth::user()->siswa;
        if (!$siswa) {
            return redirect()->route('siswa.dashboard')->with('error', 'Profil siswa tidak ditemukan.');
        }

        // Cek jika sudah ditempatkan
        $penempatan = Penempatan::where('siswa_id', $siswa->id)->first();
        if ($penempatan) {
            return redirect()->route('siswa.pengajuan.index')->with('info', 'Anda telah ditempatkan di ' . ($penempatan->perusahaan->nama_perusahaan ?? 'Perusahaan') . '. Tidak perlu mengajukan tempat baru.');
        }

        // Cek jika ada pengajuan pending
        $pending = PengajuanPkl::where('siswa_id', $siswa->id)->where('status', 'menunggu')->first();
        if ($pending) {
            return redirect()->route('siswa.pengajuan.index')->with('warning', 'Anda masih memiliki pengajuan tempat PKL yang sedang ditinjau.');
        }

        $periodeAktif = PeriodePkl::where('status', 'aktif')->latest()->first() ?? PeriodePkl::latest()->first();

        return view('siswa.pengajuan.create', compact('periodeAktif'));
    }

    public function store(Request $request)
    {
        $siswa = Auth::user()->siswa;
        if (!$siswa) {
            return redirect()->route('siswa.dashboard')->with('error', 'Profil siswa tidak ditemukan.');
        }

        $request->validate([
            'periode_pkl_id' => 'required|exists:periode_pkl,id',
            'nama_perusahaan' => 'required|string|max:255',
            'bidang_usaha' => 'nullable|string|max:255',
            'alamat_perusahaan' => 'required|string',
            'kota' => 'nullable|string|max:100',
            'nama_pimpinan' => 'nullable|string|max:255',
            'kontak_person' => 'nullable|string|max:255',
            'no_telepon' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'alasan_memilih' => 'nullable|string',
            'file_surat_balasan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:3072',
        ]);

        $filePath = null;
        if ($request->hasFile('file_surat_balasan')) {
            $file = $request->file('file_surat_balasan');
            $fileName = 'surat_balasan_' . $siswa->nis . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/pengajuan_pkl'), $fileName);
            $filePath = 'uploads/pengajuan_pkl/' . $fileName;
        }

        PengajuanPkl::create([
            'siswa_id' => $siswa->id,
            'periode_pkl_id' => $request->periode_pkl_id,
            'nama_perusahaan' => $request->nama_perusahaan,
            'bidang_usaha' => $request->bidang_usaha,
            'alamat_perusahaan' => $request->alamat_perusahaan,
            'kota' => $request->kota ?? 'Pekanbaru',
            'nama_pimpinan' => $request->nama_pimpinan,
            'kontak_person' => $request->kontak_person,
            'no_telepon' => $request->no_telepon,
            'email' => $request->email,
            'alasan_memilih' => $request->alasan_memilih,
            'file_surat_balasan' => $filePath,
            'status' => 'menunggu',
        ]);

        return redirect()->route('siswa.pengajuan.index')->with('success', '🎉 Pengajuan tempat PKL mandiri berhasil dikirim! Silakan tunggu verifikasi dari Koordinator PKL / Guru Pembimbing.');
    }

    public function show($id)
    {
        $siswa = Auth::user()->siswa;
        $pengajuan = PengajuanPkl::where('siswa_id', $siswa->id)
            ->with(['periode', 'verifikator'])
            ->findOrFail($id);

        return view('siswa.pengajuan.show', compact('pengajuan'));
    }

    public function destroy($id)
    {
        $siswa = Auth::user()->siswa;
        $pengajuan = PengajuanPkl::where('siswa_id', $siswa->id)->where('status', 'menunggu')->findOrFail($id);

        if ($pengajuan->file_surat_balasan && file_exists(public_path($pengajuan->file_surat_balasan))) {
            @unlink(public_path($pengajuan->file_surat_balasan));
        }

        $pengajuan->delete();

        return redirect()->route('siswa.pengajuan.index')->with('success', 'Pengajuan PKL berhasil dibatalkan.');
    }
}
