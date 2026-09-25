<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pks;
use Illuminate\Http\Request;

class PksController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q'));

        $pksList = Pks::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where('nama_dudi', 'like', "%{$q}%")
                    ->orWhere('nomor_pks', 'like', "%{$q}%")
                    ->orWhere('judul_pks', 'like', "%{$q}%");
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.pks.index', compact('pksList', 'q'));
    }

    public function create()
    {
        return view('admin.pks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vld' => 'nullable|string|max:50',
            'jenis_kerjasama' => 'nullable|string|max:255',
            'dunia_usaha_industri' => 'nullable|string|max:255',
            'nama_dudi' => 'required|string|max:255',
            'nomor_pks' => 'nullable|string|max:255',
            'judul_pks' => 'nullable|string|max:255',
            'tgl_mulai' => 'nullable|date',
            'tgl_selesai' => 'nullable|date',
            'npwp_dudi' => 'nullable|string|max:255',
            'nama_bidang_usaha' => 'nullable|string|max:255',
            'telp_kantor' => 'nullable|string|max:50',
            'fax' => 'nullable|string|max:50',
            'contact_person' => 'nullable|string|max:255',
            'telepon_cp' => 'nullable|string|max:50',
            'jabatan_cp' => 'nullable|string|max:255',
        ]);

        Pks::create($validated);

        return redirect()->route('admin.pks.index')->with('success', 'Data PKS berhasil ditambahkan.');
    }

    public function edit(Pks $pk)
    {
        // the variable matches the singular parameter "pk" (as Laravel infers from "pks")
        return view('admin.pks.edit', compact('pk'));
    }

    public function update(Request $request, Pks $pk)
    {
        $validated = $request->validate([
            'vld' => 'nullable|string|max:50',
            'jenis_kerjasama' => 'nullable|string|max:255',
            'dunia_usaha_industri' => 'nullable|string|max:255',
            'nama_dudi' => 'required|string|max:255',
            'nomor_pks' => 'nullable|string|max:255',
            'judul_pks' => 'nullable|string|max:255',
            'tgl_mulai' => 'nullable|date',
            'tgl_selesai' => 'nullable|date',
            'npwp_dudi' => 'nullable|string|max:255',
            'nama_bidang_usaha' => 'nullable|string|max:255',
            'telp_kantor' => 'nullable|string|max:50',
            'fax' => 'nullable|string|max:50',
            'contact_person' => 'nullable|string|max:255',
            'telepon_cp' => 'nullable|string|max:50',
            'jabatan_cp' => 'nullable|string|max:255',
        ]);

        $pk->update($validated);

        return redirect()->route('admin.pks.index')->with('success', 'Data PKS berhasil diperbarui.');
    }

    public function destroy(Pks $pk)
    {
        $pk->delete();

        return redirect()->route('admin.pks.index')->with('success', 'Data PKS berhasil dihapus.');
    }
}
