<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Jurusan;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q'));

        $kelas = Kelas::with(['jurusan', 'waliKelas'])
            ->when($q !== '', function ($query) use ($q) {
                $query->where('nama_kelas', 'like', "%{$q}%")
                    ->orWhere('tingkat', 'like', "%{$q}%")
                    ->orWhereHas('jurusan', function ($jurusan) use ($q) {
                        $jurusan->where('nama_jurusan', 'like', "%{$q}%");
                    });
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.kelas.index', compact('kelas', 'q'));
    }

    public function create()
    {
        $jurusan = Jurusan::where('status', true)->orderBy('nama_jurusan')->get();
        $guru = Guru::orderBy('nama')->get();

        return view('admin.kelas.create', compact('jurusan', 'guru'));
    }

    private function normalizeKelasData(array $validated): array
    {
        $map = ['12' => 'XII', '11' => 'XI', '10' => 'X'];
        $tingkatUpper = strtoupper(trim($validated['tingkat']));
        $validated['tingkat'] = $map[$tingkatUpper] ?? $tingkatUpper;

        $nama = trim($validated['nama_kelas']);
        $nama = preg_replace('/\b12\b/', 'XII', $nama);
        $nama = preg_replace('/\b11\b/', 'XI', $nama);
        $nama = preg_replace('/\b10\b/', 'X', $nama);
        $validated['nama_kelas'] = $nama;

        return $validated;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jurusan_id' => ['required', 'exists:jurusan,id'],
            'wali_kelas_id' => ['nullable', 'exists:guru,id'],
            'nama_kelas' => ['required', 'string', 'max:255'],
            'tingkat' => ['required', 'string', 'max:20'],
            'status' => ['required', 'boolean'],
        ]);

        $validated = $this->normalizeKelasData($validated);

        Kelas::create($validated);

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kelas)
    {
        $kelas->load(['jurusan', 'waliKelas']);
        $jurusan = Jurusan::where('status', true)->orderBy('nama_jurusan')->get();
        $guru = Guru::orderBy('nama')->get();

        return view('admin.kelas.edit', [
            'kelas' => $kelas,
            'jurusan' => $jurusan,
            'guru' => $guru,
        ]);
    }

    public function update(Request $request, Kelas $kelas)
    {
        $validated = $request->validate([
            'jurusan_id' => ['required', 'exists:jurusan,id'],
            'wali_kelas_id' => ['nullable', 'exists:guru,id'],
            'nama_kelas' => ['required', 'string', 'max:255'],
            'tingkat' => ['required', 'string', 'max:20'],
            'status' => ['required', 'boolean'],
        ]);

        $validated = $this->normalizeKelasData($validated);

        $kelas->update($validated);

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kelas)
    {
        if ($kelas->siswa()->exists()) {
            return back()->with('error', 'Kelas tidak dapat dihapus karena masih memiliki siswa.');
        }

        $kelas->delete();

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }
}
