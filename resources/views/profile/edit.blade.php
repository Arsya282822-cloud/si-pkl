@extends('layouts.app')

@section('title', 'Profil Pengguna')

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1 text-dark">Profil & Pengaturan Akun</h4>
            <p class="text-muted small mb-0">Kelola informasi data pribadi dan keamanan kata sandi akun Anda.</p>
        </div>
    </div>

    @if(session('status') === 'profile-updated')
        <div class="alert alert-success alert-dismissible fade show pro-card mb-4 border-0 p-3" role="alert" style="background-color: #ecfdf5; border-left: 5px solid #10b981 !important;">
            <div class="d-flex align-items-center gap-2">
                <i class="ph ph-check-circle text-success fs-4"></i>
                <div class="text-success fw-medium">Informasi profil Anda berhasil diperbarui!</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('status') === 'password-updated')
        <div class="alert alert-success alert-dismissible fade show pro-card mb-4 border-0 p-3" role="alert" style="background-color: #ecfdf5; border-left: 5px solid #10b981 !important;">
            <div class="d-flex align-items-center gap-2">
                <i class="ph ph-check-circle text-success fs-4"></i>
                <div class="text-success fw-medium">Kata sandi akun Anda berhasil diganti!</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger pro-card mb-4 border-0 p-3" style="background-color: #fef2f2; border-left: 5px solid #ef4444 !important;">
            <ul class="mb-0 small ps-3 text-danger">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        <!-- Profile Identity Card -->
        <div class="col-lg-4">
            <div class="pro-card p-4 text-center">
                <div class="mx-auto mb-3" style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); color: white; display: flex; align-items: center; justify-content: center; font-size: 32px; font-weight: bold; box-shadow: 0 6px 20px rgba(14, 165, 233, 0.35);">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <h5 class="fw-bold text-dark mb-1">{{ Auth::user()->name }}</h5>
                <p class="text-muted small mb-2">{{ Auth::user()->email }}</p>
                <span class="badge bg-primary text-uppercase px-3 py-1.5 rounded-pill" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    Role: {{ Auth::user()->role?->nama_role ?? 'Pengguna' }}
                </span>

                <hr class="my-4" style="border-color: var(--border-color);">

                <!-- Linked Data Info -->
                <div class="text-start">
                    <h6 class="fw-bold text-dark mb-3" style="font-size: 0.85rem;">Data Terkait:</h6>
                    @if(Auth::user()->role?->nama_role === 'siswa' && Auth::user()->siswa)
                        <ul class="list-unstyled small text-muted mb-0" style="line-height: 2;">
                            <li><strong>NIS:</strong> {{ Auth::user()->siswa->nis }}</li>
                            <li><strong>NISN:</strong> {{ Auth::user()->siswa->nisn ?? '-' }}</li>
                            <li><strong>Kelas:</strong> {{ Auth::user()->siswa->kelas->nama_kelas ?? '-' }}</li>
                            <li><strong>Jurusan:</strong> {{ Auth::user()->siswa->jurusan->nama_jurusan ?? '-' }}</li>
                            <li><strong>No. HP:</strong> {{ Auth::user()->siswa->no_hp ?? '-' }}</li>
                        </ul>
                    @elseif(Auth::user()->role?->nama_role === 'guru' && Auth::user()->guru)
                        <ul class="list-unstyled small text-muted mb-0" style="line-height: 2;">
                            <li><strong>NIP:</strong> {{ Auth::user()->guru->nip ?? '-' }}</li>
                            <li><strong>Nama Lengkap:</strong> {{ Auth::user()->guru->nama }}</li>
                            <li><strong>No. HP:</strong> {{ Auth::user()->guru->no_hp ?? '-' }}</li>
                        </ul>
                    @else
                        <p class="small text-muted mb-0">Akun Administrator Sistem Informasi PKL SMK Labor FKIP UNRI Pekanbaru.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Form Edit Profile & Password -->
        <div class="col-lg-8">
            <!-- 1. Update Profile Info -->
            <div class="pro-card p-4 p-md-5 mb-4">
                <h5 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                    <i class="ph ph-user text-primary"></i> Perbarui Informasi Profil
                </h5>
                <p class="text-muted small mb-4">Perbarui nama pengguna dan alamat email akun Anda.</p>

                <form method="post" action="{{ route('profile.update') }}">
                    @csrf
                    @method('patch')

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', Auth::user()->name) }}" required autocomplete="name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">Alamat Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', Auth::user()->email) }}" required autocomplete="username">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4 fw-semibold d-inline-flex align-items-center gap-2">
                            <i class="ph ph-check"></i> Simpan Profil
                        </button>
                    </div>
                </form>
            </div>

            <!-- 2. Update Password -->
            <div class="pro-card p-4 p-md-5">
                <h5 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                    <i class="ph ph-lock-key text-primary"></i> Ganti Kata Sandi
                </h5>
                <p class="text-muted small mb-4">Pastikan akun Anda menggunakan kata sandi yang kuat dan aman.</p>

                <form method="post" action="{{ route('password.update') }}">
                    @csrf
                    @method('put')

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Kata Sandi Saat Ini</label>
                        <input type="password" name="current_password" class="form-control" placeholder="Masukkan password lama" autocomplete="current-password" required>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">Kata Sandi Baru</label>
                            <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" autocomplete="new-password" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru" autocomplete="new-password" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4 fw-semibold d-inline-flex align-items-center gap-2">
                            <i class="ph ph-key"></i> Perbarui Kata Sandi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
