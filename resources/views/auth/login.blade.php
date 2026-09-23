<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
        <div class="alert-error" style="background: #f0fdf4; border-color: #bbf7d0; color: #16a34a; padding: 12px; border-radius: 8px; margin-bottom: 16px;">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" id="loginForm">
        @csrf

        <!-- Email Address -->
        <div class="form-group mb-3">
            <label for="email" class="form-label" style="font-weight: 600; font-size: 0.875rem;">Email Pengguna</label>
            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@email.com" style="border-radius: 8px; padding: 10px 14px;" />
            @error('email')
                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group mb-3">
            <label for="password" class="form-label" style="font-weight: 600; font-size: 0.875rem;">Kata Sandi</label>
            <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" style="border-radius: 8px; padding: 10px 14px;" />
            @error('password')
                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="form-group d-flex justify-content-between align-items-center mb-4 mt-3">
            <label for="remember_me" class="form-check d-flex align-items-center gap-2 mb-0" style="cursor: pointer;">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember" style="cursor: pointer;">
                <span class="form-check-label small text-muted">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="forgot-link small text-primary text-decoration-none" href="{{ route('password.request') }}">
                    Lupa sandi?
                </a>
            @endif
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary w-100 py-2.5 fw-semibold shadow-sm" style="border-radius: 8px; font-size: 0.95rem;">
                Masuk ke Dashboard
            </button>
        </div>
    </form>
</x-guest-layout>
