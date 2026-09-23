@extends('layouts.app')
@section('title', 'Absen Hari Ini')
@section('content')
<div class="mb-4">
    <a href="{{ route('siswa.absensi.index') }}" class="text-decoration-none text-muted">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <h1 class="page-title mt-2">Pengajuan Izin / Sakit — {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</h1>
</div>

<div class="card dashboard-card">
    <div class="card-body p-4">
        <form action="{{ route('siswa.absensi.store') }}" method="POST">
            @csrf

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Status Kehadiran <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="izin" {{ old('status') == 'izin' ? 'selected' : '' }}>📋 Izin</option>
                        <option value="sakit" {{ old('status') == 'sakit' ? 'selected' : '' }}>🤒 Sakit</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>



            <div class="mb-4">
                <label class="form-label">Keterangan (Opsional)</label>
                <textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: Izin menghadiri acara keluarga...">{{ old('keterangan') }}</textarea>
            </div>

            <hr>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-send me-1"></i> Ajukan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
