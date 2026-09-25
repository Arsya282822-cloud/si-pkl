<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q'));

        $jurusan = Jurusan::query()
            ->withCount(['siswa', 'kelas'])
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('kode_jurusan', 'like', "%{$q}%")
                        ->orWhere('nama_jurusan', 'like', "%{$q}%");
                });
            })
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.jurusan.index', compact('jurusan', 'q'));
    }

    public function create()
    {
        return view('admin.jurusan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_jurusan' => ['required', 'string', 'max:20', 'unique:jurusan,kode_jurusan'],
            'nama_jurusan' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'status' => ['required', 'boolean'],
        ]);

        Jurusan::create($validated);

        return redirect()->route('admin.jurusan.index')
            ->with('success', 'Jurusan berhasil ditambahkan.');
    }

    public function edit(Jurusan $jurusan)
    {
        return view('admin.jurusan.edit', compact('jurusan'));
    }

    public function update(Request $request, Jurusan $jurusan)
    {
        $validated = $request->validate([
            'kode_jurusan' => ['required', 'string', 'max:20', 'unique:jurusan,kode_jurusan,'.$jurusan->id],
            'nama_jurusan' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'status' => ['required', 'boolean'],
        ]);

        $jurusan->update($validated);

        return redirect()->route('admin.jurusan.index')
            ->with('success', 'Jurusan berhasil diperbarui.');
    }

    public function destroy(Jurusan $jurusan)
    {
        if ($jurusan->siswa()->exists() || $jurusan->kelas()->exists()) {
            return back()->with('error', 'Jurusan tidak dapat dihapus karena masih digunakan oleh siswa atau kelas.');
        }

        $jurusan->delete();

        return redirect()->route('admin.jurusan.index')
            ->with('success', 'Jurusan berhasil dihapus.');
    }
}
