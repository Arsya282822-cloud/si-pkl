@extends('layouts.app')
@section('title', 'Tempat PKL & Pembimbing')
@section('topbar_title', 'Informasi Tempat PKL')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: var(--text-main);">Informasi Tempat PKL & Pembimbing</h3>
        <p class="text-muted mb-0" style="font-size: 0.875rem;">Detail profil instansi tempat magang, kontak pembimbing industri, dan guru pembimbing sekolah.</p>
    </div>
</div>

@if(!$penempatan)
    <div class="pro-card p-5 text-center" style="border-radius: 16px;">
        <div class="rounded-circle bg-warning bg-opacity-10 text-warning p-3 d-inline-flex mb-3">
            <i class="ph ph-buildings" style="font-size: 42px;"></i>
        </div>
        <h4 class="fw-bold text-dark">Belum Ada Data Penempatan</h4>
        <p class="text-muted mb-0">Anda belum ditempatkan di perusahaan/instansi mitra. Silakan ajukan usulan PKL mandiri atau hubungi Pokja PKL sekolah.</p>
    </div>
@else
    <div class="row g-4">
        <!-- Info Perusahaan -->
        <div class="col-lg-7">
            <div class="pro-card h-100" style="border-radius: 16px;">
                <div class="p-3 px-4 bg-light border-bottom d-flex align-items-center gap-2">
                    <i class="ph ph-buildings text-primary" style="font-size: 22px;"></i>
                    <h6 class="fw-bold mb-0 text-dark">Profil Perusahaan Mitra (DUDI)</h6>
                </div>
                <div class="p-4">
                    <h4 class="fw-bold text-primary mb-3">{{ $penempatan->perusahaan?->nama_perusahaan }}</h4>
                    
                    <div class="mb-3">
                        <label class="text-muted small fw-semibold d-block">Alamat Kantor / Instansi</label>
                        <p class="mb-0 text-dark" style="line-height: 1.6;"><i class="ph ph-map-pin text-danger me-1"></i>{{ $penempatan->perusahaan?->alamat ?: 'Pekanbaru, Riau' }}</p>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label class="text-muted small fw-semibold d-block">No. Telepon / WhatsApp</label>
                            <p class="mb-0 text-dark fw-semibold"><i class="ph ph-phone text-primary me-1"></i>{{ $penempatan->perusahaan?->telepon ?: '-' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="text-muted small fw-semibold d-block">Email Perusahaan</label>
                            <p class="mb-0 text-dark fw-semibold"><i class="ph ph-envelope text-info me-1"></i>{{ $penempatan->perusahaan?->email ?: '-' }}</p>
                        </div>
                    </div>

                    <div class="mb-3 p-3 bg-light rounded-3 border">
                        <label class="text-muted small fw-semibold d-block mb-1">Pembimbing Lapangan / Penanggung Jawab Industri (CP)</label>
                        <p class="mb-0 text-dark fw-bold"><i class="ph ph-user-circle text-primary me-1"></i>{{ $penempatan->perusahaan?->pembimbing_industri ?: ($penempatan->perusahaan?->contact_person ?: 'Pembimbing Industri / HRD') }}</p>
                    </div>

                    @if($penempatan->perusahaan?->latitude && $penempatan->perusahaan?->longitude)
                        <div class="mt-4 pt-3 border-top">
                            <a href="https://www.google.com/maps?q={{ $penempatan->perusahaan->latitude }},{{ $penempatan->perusahaan->longitude }}" target="_blank" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1.5" style="border-radius: 8px;">
                                <i class="ph ph-map-pin"></i> Buka Lokasi di Google Maps
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Info Pembimbing & Periode -->
        <div class="col-lg-5">
            <div class="pro-card mb-4" style="border-radius: 16px;">
                <div class="p-3 px-4 bg-light border-bottom d-flex align-items-center gap-2">
                    <i class="ph ph-graduation-cap text-success" style="font-size: 22px;"></i>
                    <h6 class="fw-bold mb-0 text-dark">Guru Pembimbing Sekolah</h6>
                </div>
                <div class="p-4">
                    <h5 class="fw-bold text-dark mb-1">{{ $penempatan->guru?->nama ?: 'Belum ditentukan' }}</h5>
                    <p class="text-muted small mb-3">NIP: {{ $penempatan->guru?->nip ?: '-' }}</p>
                    
                    <div class="d-flex flex-column gap-2">
                        <div class="p-2.5 bg-light rounded-3">
                            <small class="text-muted d-block" style="font-size: 0.75rem;">Email Kontak</small>
                            <span class="fw-semibold text-dark">{{ $penempatan->guru?->user?->email ?: '-' }}</span>
                        </div>
                        <div class="p-2.5 bg-light rounded-3">
                            <small class="text-muted d-block" style="font-size: 0.75rem;">Nomor HP / WhatsApp</small>
                            <span class="fw-semibold text-dark">{{ $penempatan->guru?->no_hp ?: '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pro-card" style="border-radius: 16px;">
                <div class="p-3 px-4 bg-light border-bottom d-flex align-items-center gap-2">
                    <i class="ph ph-calendar text-info" style="font-size: 22px;"></i>
                    <h6 class="fw-bold mb-0 text-dark">Periode Pelaksanaan PKL</h6>
                </div>
                <div class="p-4">
                    <h6 class="fw-bold text-dark mb-1">{{ $penempatan->periodePkl?->nama_periode }}</h6>
                    <p class="text-muted small mb-3">Tahun Ajaran: {{ $penempatan->periodePkl?->tahun_ajaran }}</p>

                    <div class="d-flex justify-content-between p-3 rounded-3 bg-light border">
                        <div>
                            <small class="text-muted d-block">Tanggal Mulai</small>
                            <strong class="text-dark">{{ $penempatan->periodePkl?->tanggal_mulai ? \Carbon\Carbon::parse($penempatan->periodePkl->tanggal_mulai)->translatedFormat('d M Y') : '-' }}</strong>
                        </div>
                        <div class="text-end">
                            <small class="text-muted d-block">Tanggal Selesai</small>
                            <strong class="text-dark">{{ $penempatan->periodePkl?->tanggal_selesai ? \Carbon\Carbon::parse($penempatan->periodePkl->tanggal_selesai)->translatedFormat('d M Y') : '-' }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

