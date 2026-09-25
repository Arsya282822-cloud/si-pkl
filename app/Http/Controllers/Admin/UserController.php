<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Tampilkan daftar seluruh pengguna sistem dengan filter dan pencarian.
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q'));
        $roleId = $request->input('role_id');
        $status = $request->input('status');

        $usersQuery = User::with(['role', 'guru', 'siswa', 'pembimbingIndustri'])
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->when($roleId, function ($query) use ($roleId) {
                $query->where('role_id', $roleId);
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            });

        $users = $usersQuery->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Data Ringkasan / Statistik
        $stats = [
            'total' => User::count(),
            'admin' => User::whereHas('role', fn ($r) => $r->where('nama_role', 'admin'))->count(),
            'guru' => User::whereHas('role', fn ($r) => $r->where('nama_role', 'guru'))->count(),
            'siswa' => User::whereHas('role', fn ($r) => $r->where('nama_role', 'siswa'))->count(),
            'instruktur' => User::whereHas('role', fn ($r) => $r->where('nama_role', 'instruktur'))->count(),
            'nonaktif' => User::where('status', 'nonaktif')->count(),
        ];

        $roles = Role::orderBy('id')->get();

        return view('admin.users.index', compact('users', 'stats', 'roles', 'q', 'roleId', 'status'));
    }

    /**
     * Simpan pengguna baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role_id' => ['required', 'exists:roles,id'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ], [
            'name.required' => 'Nama lengkap pengguna wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'password.required' => 'Password akun wajib diisi.',
            'password.min' => 'Password minimal berisi 6 karakter.',
            'role_id.required' => 'Role hak akses wajib dipilih.',
            'role_id.exists' => 'Role yang dipilih tidak valid.',
            'status.required' => 'Status akun wajib ditentukan.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => strtolower(trim($validated['email'])),
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
            'status' => $validated['status'],
        ]);

        ActivityLogger::log(
            'Manajemen Pengguna',
            'Tambah Pengguna Baru',
            "Menambahkan pengguna baru: {$user->name} ({$user->email}) dengan Role ID {$user->role_id}"
        );

        return redirect()->route('admin.users.index')
            ->with('success', "Akun pengguna {$user->name} berhasil ditambahkan!");
    }

    /**
     * Perbarui data akun pengguna.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'role_id' => ['required', 'exists:roles,id'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ], [
            'name.required' => 'Nama lengkap pengguna wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'password.min' => 'Password minimal berisi 6 karakter jika ingin diubah.',
            'role_id.required' => 'Role hak akses wajib dipilih.',
        ]);

        // Cek jika pengguna mencoba menonaktifkan akun sendiri
        if ($user->id === Auth::id() && $validated['status'] === 'nonaktif') {
            return redirect()->back()
                ->with('error', 'Anda tidak dapat menonaktifkan akun yang sedang digunakan saat ini!');
        }

        $user->name = $validated['name'];
        $user->email = strtolower(trim($validated['email']));
        $user->role_id = $validated['role_id'];
        $user->status = $validated['status'];

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        ActivityLogger::log(
            'Manajemen Pengguna',
            'Update Pengguna',
            "Memperbarui profil pengguna: {$user->name} ({$user->email})"
        );

        return redirect()->route('admin.users.index')
            ->with('success', "Data pengguna {$user->name} berhasil diperbarui!");
    }

    /**
     * Toggle status aktif / nonaktif akun pengguna.
     */
    public function toggleStatus(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->back()
                ->with('error', 'Anda tidak dapat mengubah status akun Anda sendiri!');
        }

        $user->status = $user->status === 'aktif' ? 'nonaktif' : 'aktif';
        $user->save();

        ActivityLogger::log(
            'Manajemen Pengguna',
            'Ubah Status Akun',
            "Mengubah status akun {$user->name} menjadi {$user->status}"
        );

        return redirect()->back()
            ->with('success', "Status akun {$user->name} berhasil diubah menjadi ".strtoupper($user->status).'!');
    }

    /**
     * Reset password pengguna secara cepat.
     */
    public function resetPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'new_password' => ['required', 'string', 'min:6'],
        ], [
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password minimal 6 karakter.',
        ]);

        $user->password = Hash::make($validated['new_password']);
        $user->save();

        ActivityLogger::log(
            'Manajemen Pengguna',
            'Reset Password',
            "Mereset password akun: {$user->name} ({$user->email})"
        );

        return redirect()->back()
            ->with('success', "Password untuk pengguna {$user->name} berhasil direset!");
    }

    /**
     * Hapus akun pengguna dari sistem.
     */
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->back()
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif!');
        }

        $userName = $user->name;
        $userEmail = $user->email;

        // Jika user memiliki relasi data siswa/guru, hapus relasi atau biarkan null
        if ($user->siswa) {
            $user->siswa()->update(['user_id' => null]);
        }
        if ($user->guru) {
            $user->guru()->update(['user_id' => null]);
        }
        if ($user->pembimbingIndustri) {
            $user->pembimbingIndustri()->update(['user_id' => null]);
        }

        $user->delete();

        ActivityLogger::log(
            'Manajemen Pengguna',
            'Hapus Pengguna',
            "Menghapus akun pengguna: {$userName} ({$userEmail})"
        );

        return redirect()->route('admin.users.index')
            ->with('success', "Akun pengguna {$userName} telah berhasil dihapus dari sistem!");
    }
}
