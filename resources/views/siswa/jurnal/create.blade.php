@extends('layouts.app')
@section('title', 'Isi Jurnal Harian')
@section('content')
<div class="mb-4">
    <a href="{{ route('siswa.jurnal.index') }}" class="text-decoration-none text-muted">
        <i class="bi bi-arrow-left"></i> Kembali ke Riwayat Jurnal
    </a>
    <h1 class="page-title mt-2">Isi Jurnal Baru</h1>
</div>

<div class="card dashboard-card">
    <div class="card-body p-4">
        <div class="alert alert-info border-0 bg-light text-dark mb-4">
            <i class="bi bi-info-circle-fill text-info me-2"></i> 
            Pastikan Anda mengisi jurnal sesuai dengan kegiatan aktual di tempat PKL ({{ $penempatan->perusahaan->nama_perusahaan }}).
        </div>

        <form action="{{ route('siswa.jurnal.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Tanggal Kegiatan <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                    @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi Pekerjaan/Kegiatan <span class="text-danger">*</span></label>
                <textarea name="kegiatan" class="form-control @error('kegiatan') is-invalid @enderror" rows="5" placeholder="Contoh: Memperbaiki jaringan LAN di ruang rapat, mengatur konfigurasi router mikrotik..." required>{{ old('kegiatan') }}</textarea>
                @error('kegiatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Unggah Foto/Dokumentasi (Opsional)</label>
                <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg">
                <div class="form-text">Format yang diizinkan: JPG, JPEG, PNG. Ukuran maksimal: 2MB.</div>
                @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <hr>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-send me-1"></i> Simpan & Kirim
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
