<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();
        $role = $user->role?->nama_role ?? '';

        // Catat Log Login
        ActivityLogger::log(
            'autentikasi',
            'User Login',
            "Pengguna {$user->name} ({$user->email}) berhasil login ke sistem sebagai ".ucfirst($role)
        );

        $redirectTo = match ($role) {
            'admin' => route('admin.dashboard'),
            'siswa' => route('siswa.dashboard'),
            'guru' => route('guru.dashboard'),
            'instruktur' => route('instruktur.dashboard'),
            default => route('dashboard'),
        };

        return redirect()->intended($redirectTo);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if ($user) {
            ActivityLogger::log(
                'autentikasi',
                'User Logout',
                "Pengguna {$user->name} ({$user->email}) telah logout dari sistem."
            );
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
