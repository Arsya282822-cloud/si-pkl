<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BantuanController extends Controller
{
    public function index()
    {
        $kontak = [
            'sekretariat_nama' => Setting::get('bantuan_sekretariat_nama', 'Sekretariat Pokja PKL SMK Labor'),
            'sekretariat_lokasi' => Setting::get('bantuan_sekretariat_lokasi', 'Gedung Hubinmas Lantai 1'),
            'sekretariat_email' => Setting::get('bantuan_sekretariat_email', 'hubin@smklabor.sch.id'),
            'sekretariat_telepon' => Setting::get('bantuan_sekretariat_telepon', '(0761) 853245'),
            'cp_nama' => Setting::get('bantuan_cp_nama', 'Dedi Hendrawan, S.Kom., M.Kom.'),
            'cp_jabatan' => Setting::get('bantuan_cp_jabatan', 'Ketua Pokja Hubin & PKL'),
            'cp_whatsapp' => Setting::get('bantuan_cp_whatsapp', '0812-7500-1122'),
            'jam_layanan' => Setting::get('bantuan_jam_layanan', 'Senin - Jumat (07.30 - 16.00 WIB)'),
        ];

        return view('bantuan.index', compact('kontak'));
    }

    public function updateKontak(Request $request)
    {
        // Hanya admin yang diizinkan mengedit kontak bantuan
        if (Auth::user()->role?->nama_role !== 'admin') {
            abort(403, 'Hanya Admin yang dapat mengedit kontak bantuan.');
        }

        $request->validate([
            'sekretariat_nama' => 'required|string|max:255',
            'sekretariat_lokasi' => 'required|string|max:255',
            'sekretariat_email' => 'required|email|max:255',
            'sekretariat_telepon' => 'required|string|max:50',
            'cp_nama' => 'required|string|max:255',
            'cp_jabatan' => 'required|string|max:255',
            'cp_whatsapp' => 'required|string|max:50',
            'jam_layanan' => 'nullable|string|max:255',
        ]);

        Setting::set('bantuan_sekretariat_nama', $request->sekretariat_nama, 'bantuan');
        Setting::set('bantuan_sekretariat_lokasi', $request->sekretariat_lokasi, 'bantuan');
        Setting::set('bantuan_sekretariat_email', $request->sekretariat_email, 'bantuan');
        Setting::set('bantuan_sekretariat_telepon', $request->sekretariat_telepon, 'bantuan');
        Setting::set('bantuan_cp_nama', $request->cp_nama, 'bantuan');
        Setting::set('bantuan_cp_jabatan', $request->cp_jabatan, 'bantuan');
        Setting::set('bantuan_cp_whatsapp', $request->cp_whatsapp, 'bantuan');
        Setting::set('bantuan_jam_layanan', $request->jam_layanan, 'bantuan');

        return redirect()->route('bantuan.index')->with('success', 'Kontak Pokja PKL & Sekretariat berhasil diperbarui!');
    }
}
