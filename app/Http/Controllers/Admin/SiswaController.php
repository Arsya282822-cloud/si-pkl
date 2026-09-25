<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\SiswaImport;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Role;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q'));
        $jurusan_id = $request->input('jurusan_id');

        $per_page_input = $request->input('per_page', 10);
        $per_page = $per_page_input === 'all' ? Siswa::count() : (int) $per_page_input;
        if ($per_page <= 0) {
            $per_page = 10;
        }

        $siswa = Siswa::with(['user', 'kelas', 'jurusan'])
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('nama', 'like', "%{$q}%")
                        ->orWhere('nis', 'like', "%{$q}%")
                        ->orWhere('nisn', 'like', "%{$q}%")
                        ->orWhereHas('kelas', function ($kelas) use ($q) {
                            $kelas->where('nama_kelas', 'like', "%{$q}%");
                        });
                });
            })
            ->when($jurusan_id, function ($query) use ($jurusan_id) {
                $query->where('jurusan_id', $jurusan_id);
            })
            ->orderBy('nama', 'asc')
            ->paginate($per_page)
            ->withQueryString();

        $jurusanList = Jurusan::orderBy('kode_jurusan')->get();
        $kelasList = Kelas::with('jurusan')->orderBy('nama_kelas')->get();

        return view('admin.siswa.index', compact('siswa', 'q', 'jurusan_id', 'jurusanList', 'kelasList', 'per_page_input'));
    }

    public function bulkUpdateKelas(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'siswa_ids' => 'required|array|min:1',
            'siswa_ids.*' => 'exists:siswa,id',
        ]);

        $kelas = Kelas::findOrFail($request->kelas_id);

        Siswa::whereIn('id', $request->siswa_ids)->update([
            'kelas_id' => $kelas->id,
            'jurusan_id' => $kelas->jurusan_id,
        ]);

        return back()->with('success', count($request->siswa_ids).' data siswa berhasil dipindahkan ke kelas '.$kelas->nama_kelas.'.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new SiswaImport, $request->file('file_excel'));

            return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil diimport beserta akun login-nya.');
        } catch (\Exception $e) {
            return redirect()->route('admin.siswa.index')->with('error', 'Terjadi kesalahan saat import: '.$e->getMessage());
        }
    }

    public function create()
    {
        $jurusan = Jurusan::where('status', true)->orderBy('nama_jurusan')->get();
        $kelas = Kelas::with('jurusan')
            ->where('status', true)
            ->orderBy('nama_kelas')
            ->get();

        return view('admin.siswa.create', compact('jurusan', 'kelas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis' => ['required', 'string', 'max:50', 'unique:siswa,nis'],
            'nisn' => ['nullable', 'string', 'max:50', 'unique:siswa,nisn'],
            'nama' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['nullable', 'in:L,P'],
            'tempat_lahir' => ['nullable', 'string', 'max:255'],
            'tanggal_lahir' => ['nullable', 'date'],
            'alamat' => ['nullable', 'string'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'kelas_id' => ['required', 'exists:kelas,id'],
            'jurusan_id' => ['required', 'exists:jurusan,id'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        DB::transaction(function () use ($validated) {
            $roleId = Role::where('nama_role', 'siswa')->value('id');
            $namaUpper = mb_strtoupper(trim($validated['nama']));

            $user = User::create([
                'name' => $namaUpper,
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => $roleId,
                'status' => 'aktif',
            ]);

            Siswa::create([
                'user_id' => $user->id,
                'kelas_id' => $validated['kelas_id'],
                'jurusan_id' => $validated['jurusan_id'],
                'nis' => $validated['nis'],
                'nisn' => $validated['nisn'] ?? null,
                'nama' => $namaUpper,
                'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
                'tempat_lahir' => $validated['tempat_lahir'] ?? null,
                'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
                'no_hp' => $validated['no_hp'] ?? null,
            ]);
        });

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa dan akun login berhasil dibuat.');
    }

    public function edit(Siswa $siswa)
    {
        $siswa->load(['user', 'kelas', 'jurusan']);
        $jurusan = Jurusan::where('status', true)->orderBy('nama_jurusan')->get();
        $kelas = Kelas::with('jurusan')
            ->where('status', true)
            ->orderBy('nama_kelas')
            ->get();

        return view('admin.siswa.edit', compact('siswa', 'jurusan', 'kelas'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $siswa->load('user');

        $validated = $request->validate([
            'nis' => ['required', 'string', 'max:50', 'unique:siswa,nis,'.$siswa->id],
            'nisn' => ['nullable', 'string', 'max:50', 'unique:siswa,nisn,'.$siswa->id],
            'nama' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['nullable', 'in:L,P'],
            'tempat_lahir' => ['nullable', 'string', 'max:255'],
            'tanggal_lahir' => ['nullable', 'date'],
            'alamat' => ['nullable', 'string'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'kelas_id' => ['required', 'exists:kelas,id'],
            'jurusan_id' => ['required', 'exists:jurusan,id'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$siswa->user_id],
            'password' => ['nullable', 'string', 'min:8'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        DB::transaction(function () use ($validated, $siswa) {
            $namaUpper = mb_strtoupper(trim($validated['nama']));

            $siswa->update([
                'kelas_id' => $validated['kelas_id'],
                'jurusan_id' => $validated['jurusan_id'],
                'nis' => $validated['nis'],
                'nisn' => $validated['nisn'] ?? null,
                'nama' => $namaUpper,
                'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
                'tempat_lahir' => $validated['tempat_lahir'] ?? null,
                'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
                'no_hp' => $validated['no_hp'] ?? null,
            ]);

            $userData = [
                'name' => $namaUpper,
                'email' => $validated['email'],
                'status' => $validated['status'],
            ];

            if (! empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $siswa->user->update($userData);
        });

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        if ($siswa->penempatan()->exists()) {
            return back()->with('error', 'Siswa tidak dapat dihapus karena sudah memiliki riwayat penempatan PKL.');
        }

        DB::transaction(function () use ($siswa) {
            $user = $siswa->user;
            $siswa->delete();
            if ($user) {
                $user->delete();
            }
        });

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa dan akun login berhasil dihapus.');
    }
}
