@extends('layouts.app')

@section('title', 'Edit Pengumuman')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1 text-dark">Edit Pengumuman</h4>
            <p class="text-muted small mb-0">Perbarui rincian, kategori, atau status publikasi pengumuman.</p>
        </div>
        <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
            <i class="ph ph-arrow-left"></i> Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger pro-card mb-4 border-0 p-3" style="background-color: #fef2f2; border-left: 5px solid #ef4444 !important;">
            <ul class="mb-0 small ps-3 text-danger">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="pro-card p-4 p-md-5">
                <form action="{{ route('admin.pengumuman.update', $pengumuman->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">Judul Pengumuman <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control" value="{{ old('judul', $pengumuman->judul) }}" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-dark">Kategori</label>
                            <select name="kategori" class="form-select" required>
                                <option value="info" @selected(old('kategori', $pengumuman->kategori) === 'info')>ℹ️ Informasi Umum</option>
                                <option value="penting" @selected(old('kategori', $pengumuman->kategori) === 'penting')>🚨 Penting / Urgent</option>
                                <option value="jadwal" @selected(old('kategori', $pengumuman->kategori) === 'jadwal')>📅 Agenda & Jadwal</option>
                                <option value="peringatan" @selected(old('kategori', $pengumuman->kategori) === 'peringatan')>⚠️ Peringatan</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-dark">Target Pembaca</label>
                            <select name="target_role" class="form-select" required>
                                <option value="semua" @selected(old('target_role', $pengumuman->target_role) === 'semua')>Semua (Siswa & Guru)</option>
                                <option value="siswa" @selected(old('target_role', $pengumuman->target_role) === 'siswa')>Khusus Siswa</option>
                                <option value="guru" @selected(old('target_role', $pengumuman->target_role) === 'guru')>Khusus Guru Pembimbing</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-dark">Status Publikasi</label>
                            <select name="status" class="form-select" required>
                                <option value="aktif" @selected(old('status', $pengumuman->status) === 'aktif')>Aktif (Tampilkan)</option>
                                <option value="nonaktif" @selected(old('status', $pengumuman->status) === 'nonaktif')>Nonaktif (Draft)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">Isi Lengkap Pengumuman <span class="text-danger">*</span></label>
                        <textarea name="konten" rows="6" class="form-control" required>{{ old('konten', $pengumuman->konten) }}</textarea>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">File Lampiran / Berkas Pendukung</label>
                            <input type="file" name="file_lampiran" class="form-control" accept=".pdf,.docx,.xlsx,.jpg,.jpeg,.png">
                            @if($pengumuman->file_lampiran)
                                <div class="mt-1">
                                    <small class="text-muted">File saat ini: <a href="{{ asset($pengumuman->file_lampiran) }}" target="_blank" class="text-primary">{{ basename($pengumuman->file_lampiran) }}</a></small>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6 d-flex align-items-center pt-md-3">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="is_pinned" id="is_pinned" value="1" @checked(old('is_pinned', $pengumuman->is_pinned))>
                                <label class="form-check-label fw-semibold text-dark" for="is_pinned">
                                    📌 Pin ke Bagian Teratas Beranda
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-light px-4 py-2 fw-semibold">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2">
                            <i class="ph ph-check"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
