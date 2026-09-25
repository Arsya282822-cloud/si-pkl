<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\PerusahaanImport;
use App\Models\PembimbingIndustri;
use App\Models\Perusahaan;
use App\Models\Role;
use App\Models\User;
use App\Services\ActivityLogger;
use App\Services\InstrukturAccountService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PerusahaanController extends Controller
{
    public function index(Request $request)
    {
        // Auto-sync short email accounts (dudi{id}@dudi.com) if legacy format or missing accounts exist
        $hasLegacyOrMissing = PembimbingIndustri::where('email', 'like', 'instruktur.%@dudi.com')->exists()
            || Perusahaan::doesntHave('pembimbingIndustri')->exists();

        if ($hasLegacyOrMissing) {
            InstrukturAccountService::syncAllDudiAccounts('dudi1234');
        }

        $q = trim((string) $request->input('q'));

        $perusahaan = Perusahaan::query()
            ->with(['pembimbingIndustri'])
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('nama_perusahaan', 'like', "%{$q}%")
                        ->orWhere('kota', 'like', "%{$q}%")
                        ->orWhere('nama_pimpinan', 'like', "%{$q}%");
                });
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.perusahaan.index', compact('perusahaan', 'q'));
    }

    public function create()
    {
        return view('admin.perusahaan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_perusahaan' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string'],
            'kota' => ['nullable', 'string', 'max:255'],
            'no_telepon' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'nama_pimpinan' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        Perusahaan::create($validated);

        return redirect()->route('admin.perusahaan.index')
            ->with('success', 'Perusahaan berhasil ditambahkan.');
    }

    public function edit(Perusahaan $perusahaan)
    {
        return view('admin.perusahaan.edit', compact('perusahaan'));
    }

    public function update(Request $request, Perusahaan $perusahaan)
    {
        $validated = $request->validate([
            'nama_perusahaan' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string'],
            'kota' => ['nullable', 'string', 'max:255'],
            'no_telepon' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'nama_pimpinan' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        $perusahaan->update($validated);

        return redirect()->route('admin.perusahaan.index')
            ->with('success', 'Perusahaan berhasil diperbarui.');
    }

    public function destroy(Perusahaan $perusahaan)
    {
        if ($perusahaan->penempatan()->exists() || $perusahaan->pembimbingIndustri()->exists()) {
            return back()->with('error', 'Perusahaan tidak dapat dihapus karena masih digunakan pada penempatan atau pembimbing industri.');
        }

        $perusahaan->delete();

        return redirect()->route('admin.perusahaan.index')
            ->with('success', 'Perusahaan berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(new PerusahaanImport, $request->file('file'));

            return back()->with('success', 'Data Perusahaan berhasil diimport.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimport data: '.$e->getMessage());
        }
    }

    public function createInstruktur(Request $request, Perusahaan $perusahaan)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'jabatan' => ['nullable', 'string', 'max:100'],
            'no_hp' => ['nullable', 'string', 'max:50'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $roleInstruktur = Role::firstOrCreate(['nama_role' => 'instruktur']);
        $passwordText = $request->filled('password') ? $request->password : 'dudi1234';

        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->update([
                'name' => $request->nama,
                'role_id' => $roleInstruktur->id,
                'password' => bcrypt($passwordText),
                'status' => 'aktif',
            ]);
        } else {
            $user = User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => bcrypt($passwordText),
                'role_id' => $roleInstruktur->id,
                'status' => 'aktif',
            ]);
        }

        PembimbingIndustri::updateOrCreate(
            ['perusahaan_id' => $perusahaan->id, 'user_id' => $user->id],
            [
                'nama' => $request->nama,
                'jabatan' => $request->jabatan,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
            ]
        );

        ActivityLogger::log(
            'Kelola Akun Instruktur',
            'Buat Akun Instruktur DUDI',
            'Admin membuat/memperbarui akun instruktur '.$request->nama.' untuk '.$perusahaan->nama_perusahaan
        );

        return back()->with('success', "Akun Instruktur DUDI berhasil dibuat/diperbarui. Email: {$request->email} | Password: {$passwordText}");
    }

    public function syncAllInstruktur()
    {
        $count = InstrukturAccountService::syncAllDudiAccounts('dudi1234');

        ActivityLogger::log(
            'Kelola Akun Instruktur',
            'Generate Akun Semua Instruktur',
            "Admin meng-generate/mereset {$count} akun Instruktur DUDI dengan password default 'dudi1234'"
        );

        return back()->with('success', "Berhasil menyinkronkan & meng-generate {$count} akun Instruktur DUDI. Password default seluruh instruktur: dudi1234");
    }
}
