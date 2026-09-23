@extends('layouts.app')
@section('title', 'Pesan & Informasi PKL')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: var(--text-main);">Pesan & Informasi PKL</h3>
        <p class="text-muted mb-0" style="font-size: 0.875rem;">Pengumuman resmi, jadwal penting, dan panduan pelaksanaan PKL dari Pokja PKL dan Sekolah.</p>
    </div>
</div>

<!-- Filter Bar -->
<div class="pro-card p-3 mb-4" style="border-radius: 14px;">
    <form method="GET" action="{{ route('siswa.pengumuman.index') }}" class="row g-2 align-items-center">
        <div class="col-md-5">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white border-end-0"><i class="ph ph-magnifying-glass text-muted"></i></span>
                <input type="text" name="q" class="form-control border-start-0" placeholder="Cari pengumuman..." value="{{ $q }}">
            </div>
        </div>
        <div class="col-md-4">
            <select name="kategori" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">-- Semua Kategori --</option>
                <option value="umum" {{ $kategori == 'umum' ? 'selected' : '' }}>Umum & Informasi</option>
                <option value="penting" {{ $kategori == 'penting' ? 'selected' : '' }}>Penting & Mendesak</option>
                <option value="jadwal" {{ $kategori == 'jadwal' ? 'selected' : '' }}>Jadwal & Agenda</option>
                <option value="peringatan" {{ $kategori == 'peringatan' ? 'selected' : '' }}>Peringatan / Disiplin</option>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-sm btn-primary w-100 fw-semibold">
                <i class="ph ph-funnel me-1"></i> Filter
            </button>
            @if($q || $kategori)
                <a href="{{ route('siswa.pengumuman.index') }}" class="btn btn-sm btn-light border text-muted">Reset</a>
            @endif
        </div>
    </form>
</div>

<!-- List Pengumuman -->
<div class="row g-3">
    @forelse($pengumuman as $p)
        @php
            $alertBg = match($p->kategori) {
                'penting' => '#fef2f2',
                'jadwal' => '#fefce8',
                'peringatan' => '#fff1f2',
                default => '#f0f9ff',
            };
            $borderCol = match($p->kategori) {
                'penting' => '#ef4444',
                'jadwal' => '#ca8a04',
                'peringatan' => '#e11d48',
                default => '#0284c7',
            };
            $badgeBg = match($p->kategori) {
                'penting' => 'danger',
                'jadwal' => 'warning text-dark',
                'peringatan' => 'danger',
                default => 'info text-white',
            };
        @endphp
        <div class="col-12">
            <div class="pro-card p-4 border-0 shadow-sm" style="background-color: {{ $alertBg }}; border-left: 6px solid {{ $borderCol }} !important; border-radius: 14px;">
                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="badge bg-{{ $badgeBg }} px-2.5 py-1 text-uppercase" style="font-size: 0.7rem; font-weight: 700; border-radius: 6px;">
                            {{ $p->kategori }}
                        </span>
                        @if($p->is_pinned)
                            <span class="badge bg-dark text-white px-2 py-1" style="font-size: 0.7rem; border-radius: 6px;">
                                <i class="ph ph-push-pin me-1"></i> Disematkan
                            </span>
                        @endif
                        <h5 class="fw-bold mb-0 text-dark">{{ $p->judul }}</h5>
                    </div>
                    <small class="text-muted" style="font-size: 0.8rem;"><i class="ph ph-calendar-blank me-1"></i>{{ $p->created_at->translatedFormat('d M Y, H:i') }} WIB</small>
                </div>
                <p class="text-dark mb-0" style="white-space: pre-line; line-height: 1.7; font-size: 0.925rem;">{{ $p->konten }}</p>
                
                @if($p->file_lampiran)
                    <div class="mt-3 pt-3 border-top d-flex align-items-center justify-content-between" style="border-color: rgba(0,0,0,0.08) !important;">
                        <small class="text-muted"><i class="ph ph-paperclip me-1"></i>Lampiran Berkas</small>
                        <a href="{{ asset($p->file_lampiran) }}" target="_blank" class="btn btn-sm btn-white border shadow-sm text-primary fw-semibold d-inline-flex align-items-center gap-1.5" style="border-radius: 8px; font-size: 0.825rem;">
                            <i class="ph ph-file-arrow-down" style="font-size: 16px;"></i> Unduh Lampiran
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="pro-card p-5 text-center text-muted" style="border-radius: 16px;">
                <i class="ph ph-chat-centered-dots fs-1 d-block mb-2 opacity-50"></i>
                <h6 class="fw-bold text-dark">Tidak Ada Pengumuman</h6>
                <p class="small text-muted mb-0">Belum ada pengumuman atau broadcast pesan untuk siswa saat ini.</p>
            </div>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $pengumuman->links() }}
</div>
@endsection
