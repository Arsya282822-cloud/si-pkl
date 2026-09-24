<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            // Identitas Sekolah & Kop
            'nama_yayasan' => Setting::get('sekolah_nama_yayasan', 'YAYASAN UNIV RIAU'),
            'nama_sekolah' => Setting::get('sekolah_nama_sekolah', 'SMK LABOR BINAAN FKIP UNRI PEKANBARU'),
            'npsn' => Setting::get('sekolah_npsn', '10403993'),
            'akreditasi' => Setting::get('sekolah_akreditasi', 'TERAKREDITASI "A" (UNGGUL)'),
            'alamat' => Setting::get('sekolah_alamat', 'Jl. Thamrin No. 97 Kec. Sail Pekanbaru – 28132'),
            'telepon' => Setting::get('sekolah_telepon', '0761 – 28760'),
            'website' => Setting::get('sekolah_website', 'www.smklabor.sch.id'),
            'email' => Setting::get('sekolah_email', 'smk_labor@yahoo.com'),
            'kota_terbit' => Setting::get('sekolah_kota_terbit', 'Pekanbaru'),

            // Pejabat Penandatangan: Kepala Sekolah Sekarang & Lama
            'kepala_sekolah_sekarang' => Setting::get('pejabat_kepala_sekolah_sekarang', 'JEFFRI HUNTER, M.Pd'),
            'nip_kepala_sekolah_sekarang' => Setting::get('pejabat_nip_kepala_sekolah_sekarang', '-'),
            'kepala_sekolah_lama' => Setting::get('pejabat_kepala_sekolah_lama', 'Drs. HENDRIPIDES, M.Si'),
            'nip_kepala_sekolah_lama' => Setting::get('pejabat_nip_kepala_sekolah_lama', '19680504 199303 1 003'),
            'kepala_sekolah_aktif' => Setting::get('pejabat_kepala_sekolah_aktif', 'sekarang'), // 'sekarang' atau 'lama'

            // Ketua Pokja PKL
            'ketua_pokja' => Setting::get('pejabat_ketua_pokja', 'Mahendra, S.Pd., M.Si.'),
            'nip_ketua_pokja' => Setting::get('pejabat_nip_ketua_pokja', '-'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_yayasan' => 'required|string|max:255',
            'nama_sekolah' => 'required|string|max:255',
            'npsn' => 'nullable|string|max:50',
            'akreditasi' => 'nullable|string|max:100',
            'alamat' => 'required|string|max:500',
            'telepon' => 'required|string|max:100',
            'website' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'kota_terbit' => 'required|string|max:100',

            'kepala_sekolah_sekarang' => 'required|string|max:255',
            'nip_kepala_sekolah_sekarang' => 'nullable|string|max:100',
            'kepala_sekolah_lama' => 'required|string|max:255',
            'nip_kepala_sekolah_lama' => 'nullable|string|max:100',
            'kepala_sekolah_aktif' => 'required|in:sekarang,lama',

            'ketua_pokja' => 'required|string|max:255',
            'nip_ketua_pokja' => 'nullable|string|max:100',
        ]);

        Setting::set('sekolah_nama_yayasan', $request->nama_yayasan, 'sekolah');
        Setting::set('sekolah_nama_sekolah', $request->nama_sekolah, 'sekolah');
        Setting::set('sekolah_npsn', $request->npsn, 'sekolah');
        Setting::set('sekolah_akreditasi', $request->akreditasi, 'sekolah');
        Setting::set('sekolah_alamat', $request->alamat, 'sekolah');
        Setting::set('sekolah_telepon', $request->telepon, 'sekolah');
        Setting::set('sekolah_website', $request->website, 'sekolah');
        Setting::set('sekolah_email', $request->email, 'sekolah');
        Setting::set('sekolah_kota_terbit', $request->kota_terbit, 'sekolah');

        // Simpan Kepala Sekolah Sekarang & Lama
        Setting::set('pejabat_kepala_sekolah_sekarang', $request->kepala_sekolah_sekarang, 'pejabat');
        Setting::set('pejabat_nip_kepala_sekolah_sekarang', $request->nip_kepala_sekolah_sekarang, 'pejabat');
        Setting::set('pejabat_kepala_sekolah_lama', $request->kepala_sekolah_lama, 'pejabat');
        Setting::set('pejabat_nip_kepala_sekolah_lama', $request->nip_kepala_sekolah_lama, 'pejabat');
        Setting::set('pejabat_kepala_sekolah_aktif', $request->kepala_sekolah_aktif, 'pejabat');

        // Tentukan pejabat aktif yang digunakan pada template dokumen
        if ($request->kepala_sekolah_aktif === 'lama') {
            Setting::set('pejabat_kepala_sekolah', $request->kepala_sekolah_lama, 'pejabat');
            Setting::set('pejabat_nip_kepala_sekolah', $request->nip_kepala_sekolah_lama, 'pejabat');
        } else {
            Setting::set('pejabat_kepala_sekolah', $request->kepala_sekolah_sekarang, 'pejabat');
            Setting::set('pejabat_nip_kepala_sekolah', $request->nip_kepala_sekolah_sekarang, 'pejabat');
        }

        Setting::set('pejabat_ketua_pokja', $request->ketua_pokja, 'pejabat');
        Setting::set('pejabat_nip_ketua_pokja', $request->nip_ketua_pokja, 'pejabat');

        \App\Services\ActivityLogger::log(
            'Pengaturan',
            'Update Pengaturan',
            'Memperbarui data identitas sekolah & pejabat penandatangan'
        );

        return redirect()->route('admin.pengaturan.index')->with('success', 'Pengaturan Identitas Sekolah & Data Kepala Sekolah (Lama & Baru) berhasil disimpan!');
    }
}

