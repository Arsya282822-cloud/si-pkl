@extends('layouts.app')

@section('title', 'Pengaturan Sekolah & WhatsApp Gateway')

@section('content')
<div class="container-fluid px-0">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 d-flex align-items-center gap-2 mb-4" role="alert" style="border-radius: 12px; background: #dcfce7; color: #166534;">
            <i class="ph ph-check-circle" style="font-size: 22px;"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 d-flex align-items-center gap-2 mb-4" role="alert" style="border-radius: 12px; background: #fee2e2; color: #991b1b;">
            <i class="ph ph-warning-circle" style="font-size: 22px;"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 mb-4" role="alert" style="border-radius: 12px;">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Header Banner -->
    <div class="pro-card p-4 mb-4" style="background: linear-gradient(135deg, #0c4a6e 0%, #0369a1 100%); color: white; border: none; position: relative; overflow: hidden;">
        <div style="position: absolute; right: -15px; bottom: -15px; opacity: 0.12; font-size: 130px; line-height: 1;">
            <i class="ph ph-gear"></i>
        </div>
        <div class="row align-items-center position-relative">
            <div class="col-lg-8">
                <span class="badge bg-white text-primary px-3 py-1 mb-2 fw-semibold" style="border-radius: 20px; font-size: 0.75rem;">
                    KONFIGURASI RESMI SEKOLAH & INTEGRASI
                </span>
                <h3 class="fw-bold mb-1">Pengaturan Identitas, Pejabat & WhatsApp Gateway</h3>
                <p class="mb-0 opacity-85" style="font-size: 0.9rem;">
                    Kelola data identitas sekolah, kop surat dinas, pejabat penandatangan dokumen resmi, serta integrasi gateway WhatsApp untuk notifikasi otomatis ke siswa & guru.
                </p>
            </div>
        </div>
    </div>

    <!-- Form Setting -->
    <form action="{{ route('admin.pengaturan.update') }}" method="POST">
        @csrf
        <div class="row g-4 mb-4">
            <!-- Kolom Kiri: Identitas Sekolah & Kop -->
            <div class="col-lg-6">
                <div class="pro-card p-4 h-100">
                    <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                        <div style="width: 36px; height: 36px; border-radius: 10px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                            <i class="ph ph-buildings"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">1. Identitas Sekolah & Kop Surat</h5>
                            <small class="text-muted">Data ini dicetak pada bagian kepala surat resmi</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nama Yayasan</label>
                        <input type="text" name="nama_yayasan" class="form-control" value="{{ old('nama_yayasan', $settings['nama_yayasan']) }}" required style="border-radius: 8px;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nama Sekolah (Lengkap)</label>
                        <input type="text" name="nama_sekolah" class="form-control" value="{{ old('nama_sekolah', $settings['nama_sekolah']) }}" required style="border-radius: 8px;">
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Status Akreditasi</label>
                            <input type="text" name="akreditasi" class="form-control" value="{{ old('akreditasi', $settings['akreditasi']) }}" style="border-radius: 8px;">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small">NPSN</label>
                            <input type="text" name="npsn" class="form-control" value="{{ old('npsn', $settings['npsn']) }}" style="border-radius: 8px;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Alamat Lengkap Sekolah</label>
                        <textarea name="alamat" class="form-control" rows="2" required style="border-radius: 8px;">{{ old('alamat', $settings['alamat']) }}</textarea>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold small">No. Telepon / Fax</label>
                            <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $settings['telepon']) }}" required style="border-radius: 8px;">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Kota Penerbitan Dokumen</label>
                            <input type="text" name="kota_terbit" class="form-control" value="{{ old('kota_terbit', $settings['kota_terbit']) }}" required style="border-radius: 8px;">
                        </div>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Website Resmi</label>
                            <input type="text" name="website" class="form-control" value="{{ old('website', $settings['website']) }}" required style="border-radius: 8px;">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Email Resmi Sekolah</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $settings['email']) }}" required style="border-radius: 8px;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Pejabat Penandatangan -->
            <div class="col-lg-6">
                <div class="pro-card p-4 h-100">
                    <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                        <div style="width: 36px; height: 36px; border-radius: 10px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                            <i class="ph ph-signature"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">2. Pejabat Penandatangan</h5>
                            <small class="text-muted">Kelola data Kepala Sekolah (Baru & Lama) serta Koordinator PKL</small>
                        </div>
                    </div>

                    <!-- Status Penandatangan Aktif -->
                    <div class="p-3 mb-3 border rounded-3" style="background: #f0f9ff; border-color: #bae6fd !important;">
                        <label class="form-label fw-bold text-dark mb-2 d-flex align-items-center gap-1.5" style="font-size: 0.875rem;">
                            <i class="ph ph-check-circle text-primary" style="font-size: 18px;"></i>
                            Pejabat TTD Dokumen & Sertifikat Saat Ini:
                        </label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="kepala_sekolah_aktif" id="kepsek_sekarang" value="sekarang" {{ old('kepala_sekolah_aktif', $settings['kepala_sekolah_aktif']) === 'sekarang' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold text-dark small" for="kepsek_sekarang">
                                    Kepala Sekolah Sekarang (Baru)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="kepala_sekolah_aktif" id="kepsek_lama" value="lama" {{ old('kepala_sekolah_aktif', $settings['kepala_sekolah_aktif']) === 'lama' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold text-dark small" for="kepsek_lama">
                                    Kepala Sekolah Lama
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- 1. Kepala Sekolah Sekarang -->
                    <div class="p-3 bg-light rounded-3 mb-3 border">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold text-primary mb-0" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                <i class="ph ph-user-check me-1"></i> Kepala Sekolah Sekarang (Baru)
                            </h6>
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5" style="font-size: 0.68rem; font-weight: 700;">AKTIF</span>
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-semibold small">Nama Kepala Sekolah Baru</label>
                            <input type="text" name="kepala_sekolah_sekarang" class="form-control bg-white" value="{{ old('kepala_sekolah_sekarang', $settings['kepala_sekolah_sekarang']) }}" required style="border-radius: 8px;">
                        </div>
                        <div>
                            <label class="form-label fw-semibold small">NIP Kepala Sekolah Baru</label>
                            <input type="text" name="nip_kepala_sekolah_sekarang" class="form-control bg-white" value="{{ old('nip_kepala_sekolah_sekarang', $settings['nip_kepala_sekolah_sekarang']) }}" placeholder="-" style="border-radius: 8px;">
                        </div>
                    </div>

                    <!-- 2. Kepala Sekolah Lama -->
                    <div class="p-3 bg-light rounded-3 mb-3 border">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold text-secondary mb-0" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                <i class="ph ph-clock-counter-clockwise me-1"></i> Kepala Sekolah Sebelumnya (Lama)
                            </h6>
                            <span class="badge bg-secondary-subtle text-secondary border px-2 py-0.5" style="font-size: 0.68rem; font-weight: 600;">ARSIP / SEBELUMNYA</span>
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-semibold small">Nama Kepala Sekolah Lama</label>
                            <input type="text" name="kepala_sekolah_lama" class="form-control bg-white" value="{{ old('kepala_sekolah_lama', $settings['kepala_sekolah_lama']) }}" required style="border-radius: 8px;">
                        </div>
                        <div>
                            <label class="form-label fw-semibold small">NIP Kepala Sekolah Lama</label>
                            <input type="text" name="nip_kepala_sekolah_lama" class="form-control bg-white" value="{{ old('nip_kepala_sekolah_lama', $settings['nip_kepala_sekolah_lama']) }}" placeholder="19680504 199303 1 003" style="border-radius: 8px;">
                        </div>
                    </div>

                    <!-- 3. Koordinator PKL -->
                    <div class="p-3 bg-light rounded-3 border">
                        <h6 class="fw-bold text-primary mb-2" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
                            <i class="ph ph-user-circle me-1"></i> Koordinator PKL & Hubin
                        </h6>
                        <div class="mb-2">
                            <label class="form-label fw-semibold small">Nama Koordinator PKL (Lengkap dengan Gelar)</label>
                            <input type="text" name="ketua_pokja" class="form-control bg-white" value="{{ old('ketua_pokja', $settings['ketua_pokja']) }}" required style="border-radius: 8px;">
                        </div>
                        <div>
                            <label class="form-label fw-semibold small">NIP / Identitas Pegawai (Opsional)</label>
                            <input type="text" name="nip_ketua_pokja" class="form-control bg-white" value="{{ old('nip_ketua_pokja', $settings['nip_ketua_pokja']) }}" placeholder="-" style="border-radius: 8px;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- BAGIAN 3: KONFIGURASI WHATSAPP GATEWAY -->
            <div class="col-12">
                <div class="pro-card p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width: 36px; height: 36px; border-radius: 10px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                                <i class="ph ph-whatsapp-logo"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0 text-dark">3. Integrasi WhatsApp Gateway (Notifikasi Otomatis)</h5>
                                <small class="text-muted">Kirim notifikasi otomatis saat pengajuan disetujui/ditolak & broadcast informasi</small>
                            </div>
                        </div>
                        @if($settings['wa_gateway_status'] === 'aktif')
                            <span class="badge bg-success-subtle text-success border border-success px-3 py-1.5 fw-bold" style="border-radius: 20px;">
                                <i class="ph ph-check-circle me-1"></i> STATUS: AKTIF
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border px-3 py-1.5 fw-bold" style="border-radius: 20px;">
                                <i class="ph ph-x-circle me-1"></i> STATUS: NONAKTIF
                            </span>
                        @endif
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Status Integrasi</label>
                            <select name="wa_gateway_status" class="form-select" style="border-radius: 8px;">
                                <option value="aktif" @selected(old('wa_gateway_status', $settings['wa_gateway_status']) === 'aktif')>🟢 Aktif (Kirim Notifikasi Otomatis)</option>
                                <option value="nonaktif" @selected(old('wa_gateway_status', $settings['wa_gateway_status']) === 'nonaktif')>⚪ Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Provider WhatsApp</label>
                            <select name="wa_gateway_provider" class="form-select" style="border-radius: 8px;">
                                <option value="fonnte" @selected(old('wa_gateway_provider', $settings['wa_gateway_provider']) === 'fonnte')>Fonnte (Rekomendasi - fonnte.com)</option>
                                <option value="wablas" @selected(old('wa_gateway_provider', $settings['wa_gateway_provider']) === 'wablas')>Wablas (wablas.com)</option>
                                <option value="starsender" @selected(old('wa_gateway_provider', $settings['wa_gateway_provider']) === 'starsender')>Starsender (starsender.online)</option>
                                <option value="custom" @selected(old('wa_gateway_provider', $settings['wa_gateway_provider']) === 'custom')>Custom Webhook / Self-Hosted Gateway</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">API Token / Secret Key</label>
                            <input type="password" name="wa_gateway_api_key" class="form-control" value="{{ old('wa_gateway_api_key', $settings['wa_gateway_api_key']) }}" placeholder="Masukkan API Token Provider" style="border-radius: 8px;">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Custom Endpoint URL (Opsional / Hanya jika menggunakan custom URL)</label>
                            <input type="url" name="wa_gateway_url" class="form-control" value="{{ old('wa_gateway_url', $settings['wa_gateway_url']) }}" placeholder="Default otomatis sesuai provider (contoh: https://api.fonnte.com/send)" style="border-radius: 8px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Simpan Pengaturan -->
        <div class="text-end mb-4">
            <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2 px-5 py-2.5 fw-semibold shadow-sm" style="border-radius: 12px; font-size: 1rem;">
                <i class="ph ph-floppy-disk" style="font-size: 22px;"></i>
                <span>Simpan Seluruh Pengaturan Sistem</span>
            </button>
        </div>
    </form>

    <!-- UJI COBA PENGIRIMAN PESAN WHATSAPP (TEST FORM) -->
    <div class="pro-card p-4 border" style="border-radius: 16px; background: #f8fafc;">
        <div class="d-flex align-items-center gap-2 mb-3">
            <div style="width: 32px; height: 32px; border-radius: 8px; background: #e2e8f0; color: #475569; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                <i class="ph ph-paper-plane-tilt"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0 text-dark">Uji Coba Pengiriman Pesan WhatsApp</h6>
                <small class="text-muted">Kirim pesan uji coba ke nomor Anda untuk memastikan API token & koneksi device WA berhasil.</small>
            </div>
        </div>

        <form action="{{ route('admin.pengaturan.test_wa') }}" method="POST" class="row g-2 align-items-center">
            @csrf
            <div class="col-md-4">
                <input type="text" name="test_phone" class="form-control" placeholder="Nomor WhatsApp (Contoh: 081234567890)" required style="border-radius: 8px;">
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-success d-inline-flex align-items-center gap-1.5 px-4 py-2 fw-semibold" style="border-radius: 8px;">
                    <i class="ph ph-paper-plane-right"></i> Kirim Pesan Uji Coba
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
