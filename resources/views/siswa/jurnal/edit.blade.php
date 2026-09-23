@extends('layouts.app')
@section('title', 'Edit Jurnal Harian')
@section('content')
<div class="mb-4">
    <a href="{{ route('siswa.jurnal.index') }}" class="text-decoration-none text-muted">
        <i class="bi bi-arrow-left"></i> Kembali ke Riwayat Jurnal
    </a>
    <h1 class="page-title mt-2">Edit Jurnal</h1>
</div>

<div class="card dashboard-card">
    <div class="card-body p-4">
        <form action="{{ route('siswa.jurnal.update', $jurnal) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Tanggal Kegiatan <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', $jurnal->tanggal) }}" max="{{ date('Y-m-d') }}" required>
                    @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi Pekerjaan/Kegiatan <span class="text-danger">*</span></label>
                <textarea name="kegiatan" class="form-control @error('kegiatan') is-invalid @enderror" rows="5" required>{{ old('kegiatan', $jurnal->kegiatan) }}</textarea>
                @error('kegiatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Unggah Foto Baru (Biarkan kosong jika tidak ingin mengubah foto)</label>
                @if($jurnal->foto)
                    <div class="mb-2">
                        <span class="d-block text-muted small mb-1">Foto Saat Ini:</span>
                        <img src="{{ asset('storage/' . $jurnal->foto) }}" alt="Foto Jurnal" class="img-thumbnail" style="max-height: 150px;">
                    </div>
                @endif
                <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg">
                <div class="form-text">Format yang diizinkan: JPG, JPEG, PNG. Ukuran maksimal: 2MB.</div>
                @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <hr>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
