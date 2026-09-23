@extends('layouts.app')
@section('title', 'Validasi Jurnal PKL DUDI')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: var(--text-main);">Validasi Jurnal Harian Siswa</h3>
        <p class="text-muted mb-0" style="font-size: 0.875rem;">
            Periksa dan verifikasi aktivitas kerja harian yang diinput oleh peserta magang di <strong class="text-dark">{{ $perusahaan->nama }}</strong>.
        </p>
    </div>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <div class="pro-card p-3 d-flex align-items-center gap-3 border-0 shadow-sm" style="border-radius: 12px; background: #ffffff;">
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: #e0f2fe; color: #0284c7;">
                <i class="ph ph-files" style="font-size: 22px;"></i>
            </div>
            <div>
                <p class="text-muted small mb-0">Total Jurnal Masuk</p>
                <h5 class="fw-bold mb-0 text-dark">{{ $stats['total'] }} <span class="fs-6 fw-normal text-muted">Laporan</span></h5>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="pro-card p-3 d-flex align-items-center gap-3 border-0 shadow-sm" style="border-radius: 12px; background: #ffffff;">
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: #fef3c7; color: #d97706;">
                <i class="ph ph-clock-countdown" style="font-size: 22px;"></i>
            </div>
            <div>
                <p class="text-muted small mb-0">Menunggu Validasi</p>
                <h5 class="fw-bold mb-0 text-warning">{{ $stats['menunggu'] }} <span class="fs-6 fw-normal text-muted">Laporan</span></h5>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="pro-card p-3 d-flex align-items-center gap-3 border-0 shadow-sm" style="border-radius: 12px; background: #ffffff;">
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: #dcfce7; color: #16a34a;">
                <i class="ph ph-check-circle" style="font-size: 22px;"></i>
            </div>
            <div>
                <p class="text-muted small mb-0">Telah Disetujui</p>
                <h5 class="fw-bold mb-0 text-success">{{ $stats['disetujui'] }} <span class="fs-6 fw-normal text-muted">Laporan</span></h5>
            </div>
        </div>
    </div>
</div>

<div class="pro-card p-4 border-0 shadow-sm" style="border-radius: 16px;">
    <!-- Filter -->
    <form method="GET" action="{{ route('instruktur.jurnal.index') }}" class="row g-2 mb-4">
        <div class="col-md-3">
            <select name="status" class="form-select" style="border-radius: 8px;">
                <option value="all" {{ request('status') === 'all' || !request('status') ? 'selected' : '' }}>Semua Status</option>
                <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>Menunggu Validasi</option>
                <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak / Revisi</option>
            </select>
        </div>
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="ph ph-magnifying-glass"></i></span>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control border-start-0" placeholder="Cari nama siswa atau uraian kegiatan...">
            </div>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100 fw-semibold" style="border-radius: 8px;">
                <i class="ph ph-funnel me-1"></i> Filter
            </button>
        </div>
        @if(request('search') || (request('status') && request('status') !== 'all'))
            <div class="col-md-2">
                <a href="{{ route('instruktur.jurnal.index') }}" class="btn btn-light border w-100 text-muted" style="border-radius: 8px;">
                    Reset
                </a>
            </div>
        @endif
    </form>

    @if($jurnals->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="ph ph-book-open mb-2" style="font-size: 40px; color: #cbd5e1;"></i>
            <h6 class="fw-bold text-dark">Tidak Ada Jurnal</h6>
            <p class="small mb-0">Belum ada jurnal yang sesuai dengan filter pencarian Anda.</p>
        </div>
    @else
        <div class="row g-3">
            @foreach($jurnals as $jurnal)
                @php
                    $siswa = $jurnal->penempatan?->siswa;
                @endphp
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border shadow-none" style="border-radius: 12px; background: #ffffff;">
                        <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-light-primary text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 0.75rem; background: #e0f2fe;">
                                            {{ strtoupper(substr($siswa?->nama ?? 'S', 0, 1)) }}
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;">{{ $siswa?->nama ?? 'Siswa' }}</h6>
                                            <small class="text-muted">{{ $siswa?->kelas?->nama_kelas ?? $siswa?->jurusan?->nama_jurusan }}</small>
                                        </div>
                                    </div>
                                    <div>
                                        @if($jurnal->status_validasi === 'disetujui')
                                            <span class="badge bg-success px-2 py-1" style="font-size: 0.7rem;">Disetujui</span>
                                        @elseif($jurnal->status_validasi === 'ditolak')
                                            <span class="badge bg-danger px-2 py-1" style="font-size: 0.7rem;">Ditolak</span>
                                        @else
                                            <span class="badge bg-warning text-dark px-2 py-1" style="font-size: 0.7rem;">Menunggu</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <small class="text-muted d-block mb-1">
                                        <i class="ph ph-calendar me-1"></i> {{ \Carbon\Carbon::parse($jurnal->tanggal)->isoFormat('dddd, D MMMM Y') }}
                                    </small>
                                    <p class="text-dark small mb-0" style="line-height: 1.5; max-height: 80px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                                        {{ $jurnal->ringkasan_kegiatan ?? strip_tags($jurnal->kegiatan) }}
                                    </p>
                                </div>

                                @if($jurnal->foto_kegiatan)
                                    <div class="mb-2">
                                        <a href="{{ asset('storage/' . $jurnal->foto_kegiatan) }}" target="_blank" class="badge bg-light text-primary border" style="text-decoration: none;">
                                            <i class="ph ph-image me-1"></i> Lihat Foto Dokumentasi
                                        </a>
                                    </div>
                                @endif

                                @if($jurnal->komentar_guru)
                                    @php
                                        $isLaporan = str_contains($jurnal->komentar_guru, '[LAPORAN INSTRUKTUR DUDI]');
                                    @endphp
                                    <div class="p-2 rounded {{ $isLaporan ? 'bg-warning-subtle border border-warning text-dark' : 'bg-light border text-muted' }} small mb-2" style="font-size: 0.75rem;">
                                        <i class="ph {{ $isLaporan ? 'ph-megaphone text-warning' : 'ph-chat-text' }} me-1"></i> {{ $jurnal->komentar_guru }}
                                    </div>
                                @endif
                            </div>

                            <div class="pt-2 border-top d-flex justify-content-between align-items-center mt-2">
                                <a href="{{ route('instruktur.jurnal.show', $jurnal->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius: 6px; font-size: 0.8rem;">
                                    <i class="ph ph-eye me-1"></i> Detail & Catatan
                                </a>
                                @if($jurnal->status_validasi === 'menunggu')
                                    <form action="{{ route('instruktur.jurnal.validasi', $jurnal->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status_validasi" value="disetujui">
                                        <button type="submit" class="btn btn-sm btn-success text-white" style="border-radius: 6px; font-size: 0.8rem;">
                                            <i class="ph ph-check me-0.5"></i> Setujui
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $jurnals->links() }}
        </div>
    @endif
</div>
@endsection
