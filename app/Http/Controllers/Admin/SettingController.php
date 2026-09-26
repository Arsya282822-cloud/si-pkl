<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\ActivityLogger;
use App\Services\WhatsAppService;
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

            // WhatsApp Gateway Settings
            'wa_gateway_status' => Setting::get('wa_gateway_status', 'nonaktif'),
            'wa_gateway_provider' => Setting::get('wa_gateway_provider', 'fonnte'),
            'wa_gateway_api_key' => Setting::get('wa_gateway_api_key', ''),
            'wa_gateway_url' => Setting::get('wa_gateway_url', ''),
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

            'wa_gateway_status' => 'required|in:aktif,nonaktif',
            'wa_gateway_provider' => 'required|in:fonnte,wablas,starsender,custom',
            'wa_gateway_api_key' => 'nullable|string|max:500',
            'wa_gateway_url' => 'nullable|string|max:500',
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

        // Simpan Konfigurasi WhatsApp Gateway
        Setting::set('wa_gateway_status', $request->wa_gateway_status, 'whatsapp');
        Setting::set('wa_gateway_provider', $request->wa_gateway_provider, 'whatsapp');
        Setting::set('wa_gateway_api_key', $request->wa_gateway_api_key, 'whatsapp');
        Setting::set('wa_gateway_url', $request->wa_gateway_url, 'whatsapp');

        ActivityLogger::log(
            'Pengaturan',
            'Update Pengaturan',
            'Memperbarui data identitas sekolah, pejabat penandatangan, dan WhatsApp Gateway'
        );

        return redirect()->route('admin.pengaturan.index')->with('success', 'Seluruh pengaturan (Identitas Sekolah, Pejabat & WhatsApp Gateway) berhasil disimpan!');
    }

    public function testWhatsApp(Request $request)
    {
        $request->validate([
            'test_phone' => 'required|string|max:30',
        ]);

        $pesan = "Halo! Ini adalah pesan uji coba koneksi WhatsApp Gateway dari *SI-PKL SMK LABOR BINAAN FKIP UNRI*.\n\nStatus: Terhubung & Siap Digunakan ✅\nWaktu: ".now()->format('d/m/Y H:i:s').' WIB';

        $result = WhatsAppService::send($request->test_phone, $pesan);

        if ($result['success']) {
            return back()->with('success', 'Uji coba sukses! '.$result['message']);
        }

        return back()->with('error', 'Uji coba gagal: '.$result['message']);
    }
}
