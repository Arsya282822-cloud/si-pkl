@extends('layouts.app')
@section('title', 'Tambah Periode PKL')
@section('topbar_title', 'Tambah Periode PKL')

@section('content')
<div class="container-fluid px-0" style="max-width: 900px;">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--text-main);">Tambah Periode PKL</h4>
            <p class="text-muted mb-0" style="font-size: 0.875rem;">Buat gelombang atau jadwal baru untuk pelaksanaan Praktik Kerja Lapangan.</p>
        </div>
        <a href="{{ route('admin.periode.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1.5" style="border-radius: 8px;">
            <i class="ph ph-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="pro-card p-4" style="border-radius: 16px;">
        <form method="POST" action="{{ route('admin.periode.store') }}">
            @include('admin.periode._form')
            
            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                <a href="{{ route('admin.periode.index') }}" class="btn btn-light px-4 py-2" style="border-radius: 8px; font-weight: 500;">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary px-4 py-2 d-flex align-items-center gap-2" style="border-radius: 8px; font-weight: 600; box-shadow: 0 4px 12px rgba(14, 165, 233, 0.25);">
                    <i class="ph ph-check-circle" style="font-size: 18px;"></i> Simpan Periode
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
