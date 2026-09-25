<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\GuruImport;
use App\Models\Guru;
use App\Models\Role;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q'));
        $jenis_kelamin = $request->input('jenis_kelamin');

        $guru = Guru::with('user')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('nama', 'like', "%{$q}%")
                        ->orWhere('nip', 'like', "%{$q}%")
                        ->orWhere('no_hp', 'like', "%{$q}%")
                        ->orWhereHas('user', function ($user) use ($q) {
                            $user->where('email', 'like', "%{$q}%");
                        });
                });
            })
            ->when($jenis_kelamin, function ($query) use ($jenis_kelamin) {
                $query->where('jenis_kelamin', $jenis_kelamin);
            })
            ->orderBy('nama', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.guru.index', compact('guru', 'q', 'jenis_kelamin'));
    }

    public function create()
    {
        return view('admin.guru.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:50', 'unique:guru,nip'],
            'jenis_kelamin' => ['nullable', 'in:L,P'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        DB::transaction(function () use ($validated) {
            $roleId = Role::where('nama_role', 'guru')->value('id');
            $namaUpper = mb_strtoupper(trim($validated['nama']));

            $password = ! empty($validated['password']) ? $validated['password'] : 'guru1234';

            $user = User::create([
                'name' => $namaUpper,
                'email' => $validated['email'],
                'password' => Hash::make($password),
                'role_id' => $roleId,
                'status' => 'aktif',
            ]);

            Guru::create([
                'user_id' => $user->id,
                'nip' => $validated['nip'] ?? null,
                'nama' => $namaUpper,
                'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
                'no_hp' => $validated['no_hp'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
            ]);
        });

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru dan akun login berhasil dibuat (Password default: guru1234).');
    }

    public function resetAllPassword()
    {
        $roleGuru = Role::where('nama_role', 'guru')->first();
        $guruUserIds = Guru::pluck('user_id')->filter()->toArray();

        $query = User::query();
        if ($roleGuru) {
            $query->where('role_id', $roleGuru->id);
        }
        if (! empty($guruUserIds)) {
            $query->orWhereIn('id', $guruUserIds);
        }

        $count = $query->update([
            'password' => Hash::make('guru1234'),
        ]);

        ActivityLogger::log(
            'Master Data Guru',
            'Reset Massal Password Guru',
            'Admin mereset password '.$count.' akun guru menjadi guru1234'
        );

        return redirect()->route('admin.guru.index')
            ->with('success', "Berhasil mereset password seluruh akun Guru ({$count} akun) menjadi 'guru1234'.");
    }

    public function edit(Guru $guru)
    {
        $guru->load('user');

        return view('admin.guru.edit', compact('guru'));
    }

    public function update(Request $request, Guru $guru)
    {
        $guru->load('user');

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:50', 'unique:guru,nip,'.$guru->id],
            'jenis_kelamin' => ['nullable', 'in:L,P'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$guru->user_id],
            'password' => ['nullable', 'string', 'min:8'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        DB::transaction(function () use ($validated, $guru) {
            $namaUpper = mb_strtoupper(trim($validated['nama']));

            $guru->update([
                'nip' => $validated['nip'] ?? null,
                'nama' => $namaUpper,
                'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
                'no_hp' => $validated['no_hp'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
            ]);

            $userData = [
                'name' => $namaUpper,
                'email' => $validated['email'],
                'status' => $validated['status'],
            ];

            if (! empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $guru->user->update($userData);
        });

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Guru $guru)
    {
        if ($guru->kelasWali()->exists() || $guru->penempatan()->exists() || $guru->monitoring()->exists()) {
            return back()->with('error', 'Guru tidak dapat dihapus karena masih terhubung dengan kelas, penempatan PKL, atau monitoring.');
        }

        DB::transaction(function () use ($guru) {
            $user = $guru->user;
            $guru->delete();
            if ($user) {
                $user->delete();
            }
        });

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru dan akun login berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new GuruImport, $request->file('file_excel'));

            return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->route('admin.guru.index')->with('error', 'Terjadi kesalahan saat import: '.$e->getMessage());
        }
    }
}
