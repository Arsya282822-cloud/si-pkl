@extends('layouts.app')
@section('title', 'Profil Instruktur DUDI')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: var(--text-main);">Profil Instruktur Pembimbing DUDI</h3>
        <p class="text-muted mb-0" style="font-size: 0.875rem;">
            Informasi identitas pembimbing lapangan dan data profil perusahaan tempat praktik.
        </p>
    </div>
</div>

<div class="row g-4">
    <!-- Form Edit Profil -->
    <div class="col-lg-7">
        <div class="pro-card p-4 border-0 shadow-sm" style="border-radius: 16px;">
            <h5 class="fw-bold mb-3 border-bottom pb-2">Edit Data Akun & Profil</h5>
            
            <form action="{{ route('instruktur.profil.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email Login</label>
                    <input type="email" id="email" class="form-control bg-light" value="{{ $user->email }}" readonly disabled>
                    <small class="text-muted">Email digunakan sebagai username untuk masuk ke sistem.</small>
                </div>

                <div class="mb-3">
                    <label for="nama" class="form-label fw-semibold">Nama Lengkap Instruktur <span class="text-danger">*</span></label>
                    <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $pembimbing?->nama ?? $user->name) }}" required>
                    @error('nama')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="jabatan" class="form-label fw-semibold">Jabatan di Perusahaan</label>
                    <input type="text" name="jabatan" id="jabatan" class="form-control" value="{{ old('jabatan', $pembimbing?->jabatan) }}" placeholder="Contoh: Senior Developer, Head of HRD, Supervisor...">
                    @error('jabatan')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="no_hp" class="form-label fw-semibold">No. Telepon / WhatsApp</label>
                    <input type="text" name="no_hp" id="no_hp" class="form-control" value="{{ old('no_hp', $pembimbing?->no_hp) }}" placeholder="08xxxxxxxxxx">
                    @error('no_hp')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label fw-semibold">Ganti Password Baru (Opsional)</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Biarkan kosong jika tidak ingin mengubah password">
                    <small class="text-muted">Minimal 6 karakter.</small>
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary px-4 fw-semibold" style="border-radius: 8px;">
                        <i class="ph ph-floppy-disk me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Perusahaan -->
    <div class="col-lg-5">
        <div class="pro-card p-4 border-0 shadow-sm mb-4" style="border-radius: 16px;">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="rounded-circle bg-light-primary text-primary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: #e0f2fe; color: #0284c7;">
                    <i class="ph ph-buildings" style="font-size: 26px;"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0 text-dark">{{ $perusahaan?->nama ?? 'Mitra DUDI' }}</h5>
                    <small class="text-muted">{{ $perusahaan?->bidang_usaha ?? 'Industri Mitra PKL' }}</small>
                </div>
            </div>

            <div class="border-top pt-3">
                <div class="mb-2">
                    <span class="text-muted small d-block">Alamat Perusahaan:</span>
                    <p class="small text-dark mb-0 fw-medium">{{ $perusahaan?->alamat ?? '-' }}</p>
                </div>
                <div class="mb-2">
                    <span class="text-muted small d-block">Kontak / Telepon Perusahaan:</span>
                    <p class="small text-dark mb-0 fw-medium">{{ $perusahaan?->no_hp ?? $perusahaan?->telepon ?? '-' }}</p>
                </div>
                <div class="mb-2">
                    <span class="text-muted small d-block">Email Perusahaan:</span>
                    <p class="small text-dark mb-0 fw-medium">{{ $perusahaan?->email ?? '-' }}</p>
                </div>
                <div>
                    <span class="text-muted small d-block">Pimpinan / PIC:</span>
                    <p class="small text-dark mb-0 fw-medium">{{ $perusahaan?->pimpinan ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="pro-card p-4 border-0 shadow-sm" style="border-radius: 16px; background: #f8fafc;">
            <h6 class="fw-bold mb-2 text-dark"><i class="ph ph-shield-check text-success me-1"></i> Peran & Hak Akses Instruktur</h6>
            <ul class="small text-muted mb-0 ps-3" style="line-height: 1.6;">
                <li>Memantau data & kehadiran presensi peserta magang.</li>
                <li>Menerima dan memvalidasi jurnal kerja harian siswa.</li>
                <li>Memberikan umpan balik / masukan koreksi pada jurnal siswa.</li>
                <li>Mengisi nilai evaluasi akhir PKL aspek DUDI/Industri.</li>
            </ul>
        </div>
    </div>
</div>
@endsection
