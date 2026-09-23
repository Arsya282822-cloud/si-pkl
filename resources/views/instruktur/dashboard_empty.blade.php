@extends('layouts.app')
@section('title', 'Dashboard Instruktur DUDI')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="pro-card text-center p-5 shadow-sm" style="border-radius: 16px;">
            <div class="mb-4">
                <div class="d-inline-flex align-items-center justify-content-center p-4 rounded-circle mb-3" style="width: 90px; height: 90px; background: #fef3c7; color: #d97706;">
                    <i class="ph ph-buildings" style="font-size: 48px;"></i>
                </div>
                <h4 class="fw-bold mb-2">Akun Belum Terhubung dengan Mitra DUDI</h4>
                <p class="text-muted mb-4" style="max-width: 500px; margin: 0 auto; line-height: 1.6;">
                    Akun instruktur Anda belum ditautkan ke profil Perusahaan Mitra DUDI manapun. Silakan hubungi Administrator PKL Sekolah untuk menautkan akun Anda ke perusahaan tempat Anda bertugas.
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('bantuan.index') }}" class="btn btn-outline-primary px-4 py-2" style="border-radius: 10px; font-weight: 600;">
                        <i class="ph ph-question me-1"></i> Bantuan & Kontak
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
