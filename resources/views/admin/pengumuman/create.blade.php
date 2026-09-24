@extends('layouts.app')

@section('title', 'Buat Pengumuman Baru')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1 text-dark">Buat Pengumuman Baru</h4>
            <p class="text-muted small mb-0">Publikasikan informasi, jadwal pembekalan, atau pengumuman penting untuk warga PKL.</p>
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
                <form action="{{ route('admin.pengumuman.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">Judul Pengumuman <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control" placeholder="Contoh: Jadwal Pembekalan & Penyerahan Berkas PKL 2026" value="{{ old('judul') }}" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-dark">Kategori</label>
                            <select name="kategori" class="form-select" required>
                                <option value="info" @selected(old('kategori') === 'info')>Informasi Umum</option>
                                <option value="penting" @selected(old('kategori') === 'penting')>Penting / Urgent</option>
                                <option value="jadwal" @selected(old('kategori') === 'jadwal')>Agenda & Jadwal</option>
                                <option value="peringatan" @selected(old('kategori') === 'peringatan')>Peringatan</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-dark">Target Pembaca</label>
                            <select name="target_role" class="form-select" required>
                                <option value="semua" @selected(old('target_role') === 'semua')>Semua (Siswa & Guru)</option>
                                <option value="siswa" @selected(old('target_role') === 'siswa')>Khusus Siswa</option>
                                <option value="guru" @selected(old('target_role') === 'guru')>Khusus Guru Pembimbing</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-dark">Status Publikasi</label>
                            <select name="status" class="form-select" required>
                                <option value="aktif" @selected(old('status', 'aktif') === 'aktif')>Aktif (Tampilkan)</option>
                                <option value="nonaktif" @selected(old('status') === 'nonaktif')>Nonaktif (Draft)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">Isi Lengkap Pengumuman <span class="text-danger">*</span></label>
                        <textarea name="konten" rows="6" class="form-control" placeholder="Tuliskan isi pengumuman atau instruksi di sini..." required>{{ old('konten') }}</textarea>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">File Lampiran / Berkas Pendukung (Opsional)</label>
                            <input type="file" name="file_lampiran" class="form-control" accept=".pdf,.docx,.xlsx,.jpg,.jpeg,.png">
                            <small class="text-muted">Format: PDF, Word, Excel, JPG, PNG (Maks 5MB).</small>
                        </div>
                        <div class="col-md-6 d-flex align-items-center pt-md-3">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="is_pinned" id="is_pinned" value="1" @checked(old('is_pinned'))>
                                <label class="form-check-label fw-semibold text-dark" for="is_pinned">
                                    Pin ke Bagian Teratas Beranda
                                </label>
                                <div class="text-muted" style="font-size: 0.75rem;">Pengumuman ini akan disematkan di banner paling atas.</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-light px-4 py-2 fw-semibold">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2">
                            <i class="ph ph-paper-plane-tilt"></i> Publikasikan Pengumuman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
