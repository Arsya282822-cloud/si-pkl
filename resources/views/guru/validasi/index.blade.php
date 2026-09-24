@extends('layouts.app')
@section('title', 'Supervisi Jurnal Siswa & Laporan DUDI')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: var(--text-main);">Supervisi Jurnal Siswa & Laporan DUDI</h3>
        <p class="text-muted mb-0" style="font-size: 0.875rem;">
            Aktivitas harian disetujui & diverifikasi langsung oleh <strong>Instruktur DUDI</strong>. Guru pembimbing memantau perkembangan dan menindaklanjuti laporan khusus industri.
        </p>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="border-radius: 10px;">
    <i class="ph ph-check-circle me-1 fw-bold"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Quick Stats Summary --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-md-3">
        <a href="{{ route('guru.validasi.index') }}" class="text-decoration-none">
            <div class="pro-card p-3 d-flex align-items-center gap-3 bg-white" style="border-radius: 12px;">
                <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-2.5 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                    <i class="ph ph-files fs-5"></i>
                </div>
                <div>
                    <small class="text-muted d-block">Total Jurnal</small>
                    <h5 class="fw-bold mb-0 text-dark">{{ $stats['total'] }}</h5>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-md-3">
        <a href="{{ route('guru.validasi.index', ['status' => 'disetujui']) }}" class="text-decoration-none">
            <div class="pro-card p-3 d-flex align-items-center gap-3 bg-white" style="border-radius: 12px;">
                <div class="rounded-circle bg-success bg-opacity-10 text-success p-2.5 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                    <i class="ph ph-check-circle fs-5"></i>
                </div>
                <div>
                    <small class="text-muted d-block">Disetujui DUDI</small>
                    <h5 class="fw-bold mb-0 text-success">{{ $stats['disetujui'] }}</h5>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-md-3">
        <a href="{{ route('guru.validasi.index', ['status' => 'menunggu']) }}" class="text-decoration-none">
            <div class="pro-card p-3 d-flex align-items-center gap-3 bg-white" style="border-radius: 12px;">
                <div class="rounded-circle bg-warning bg-opacity-10 text-warning p-2.5 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                    <i class="ph ph-clock fs-5"></i>
                </div>
                <div>
                    <small class="text-muted d-block">Menunggu Review DUDI</small>
                    <h5 class="fw-bold mb-0 text-warning">{{ $stats['menunggu'] }}</h5>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-md-3">
        <a href="{{ route('guru.validasi.index', ['status' => 'laporan_khusus']) }}" class="text-decoration-none">
            <div class="pro-card p-3 d-flex align-items-center gap-3 bg-white" style="border-radius: 12px; border: {{ $stats['laporan_khusus'] > 0 ? '2px solid #ef4444' : '1px solid rgba(224, 242, 254, 0.5)' }};">
                <div class="rounded-circle {{ $stats['laporan_khusus'] > 0 ? 'bg-danger text-white' : 'bg-danger bg-opacity-10 text-danger' }} p-2.5 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                    <i class="ph ph-megaphone fs-5"></i>
                </div>
                <div>
                    <small class="text-muted d-block">Laporan Masuk DUDI</small>
                    <h5 class="fw-bold mb-0 text-danger">{{ $stats['laporan_khusus'] }}</h5>
                </div>
            </div>
        </a>
    </div>
</div>

{{-- Filter Card --}}
<div class="pro-card p-3 mb-4" style="border-radius: 14px;">
    <form class="row g-2 align-items-end" method="GET" action="{{ route('guru.validasi.index') }}">
        <div class="col-md-5">
            <label class="form-label small text-muted mb-1 fw-semibold">Cari Nama Siswa</label>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted border-end-0">
                    <i class="ph ph-magnifying-glass"></i>
                </span>
                <input name="q" value="{{ $q }}" class="form-control border-start-0 ps-0" placeholder="Ketik nama siswa bimbingan...">
            </div>
        </div>
        <div class="col-md-4">
            <label class="form-label small text-muted mb-1 fw-semibold">Filter Status Validasi DUDI</label>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted border-end-0">
                    <i class="ph ph-funnel"></i>
                </span>
                <select name="status" class="form-select border-start-0 ps-0">
                    <option value="">Semua Status Validasi</option>
                    <option value="laporan_khusus" {{ $status == 'laporan_khusus' ? 'selected' : '' }}>Laporan Khusus DUDI</option>
                    <option value="disetujui" {{ $status == 'disetujui' ? 'selected' : '' }}>Disetujui DUDI</option>
                    <option value="menunggu" {{ $status == 'menunggu' ? 'selected' : '' }}>Menunggu Review DUDI</option>
                    <option value="ditolak" {{ $status == 'ditolak' ? 'selected' : '' }}>Ditolak / Perlu Revisi</option>
                </select>
            </div>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-primary d-inline-flex align-items-center gap-1.5 flex-grow-1" style="border-radius: 8px; font-weight: 600;">
                <i class="ph ph-funnel"></i> Filter
            </button>
            @if($q || $status)
            <a href="{{ route('guru.validasi.index') }}" class="btn btn-outline-secondary" style="border-radius: 8px;">
                Reset
            </a>
            @endif
        </div>
    </form>
</div>

<div class="pro-card" style="border-radius: 16px;">
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="table-light" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">
                <tr>
                    <th class="ps-4">Tanggal</th>
                    <th>Siswa Bimbingan</th>
                    <th>Perusahaan Mitra (DUDI)</th>
                    <th>Kegiatan PKL</th>
                    <th>Validasi DUDI</th>
                    <th>Catatan / Laporan DUDI</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jurnal as $item)
                @php
                    $isLaporan = str_contains($item->komentar_guru ?? '', '[LAPORAN INSTRUKTUR DUDI]');
                    $cleanMsg = preg_replace('/^(\[LAPORAN INSTRUKTUR DUDI\]: |\[Catatan Instruktur\]: |\[Instruktur DUDI\]: )/', '', $item->komentar_guru ?? '');
                @endphp
                <tr class="{{ $isLaporan ? 'table-warning bg-opacity-25' : '' }}">
                    <td class="ps-4">
                        <div class="d-flex align-items-center gap-2">
                            <i class="ph ph-calendar text-primary" style="font-size: 18px;"></i>
                            <span class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="fw-bold text-dark">{{ $item->penempatan?->siswa?->nama ?? '-' }}</div>
                        <small class="text-muted">{{ $item->penempatan?->siswa?->kelas?->nama_kelas ?? '' }}</small>
                    </td>
                    <td>
                        <div class="fw-medium text-dark">{{ $item->penempatan?->perusahaan?->nama_perusahaan ?? '-' }}</div>
                    </td>
                    <td>
                        <div class="text-truncate" style="max-width: 220px;" title="{{ $item->kegiatan }}">
                            {{ $item->kegiatan }}
                        </div>
                        @if($item->foto)
                            <small class="text-primary d-inline-flex align-items-center gap-1 mt-0.5">
                                <i class="ph ph-image"></i> Ada Foto Dokumentasi
                            </small>
                        @endif
                    </td>
                    <td>
                        @if($item->status_validasi == 'menunggu')
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1" style="border-radius: 6px; font-weight: 600;">
                                <i class="ph ph-clock me-1"></i> Menunggu DUDI
                            </span>
                        @elseif($item->status_validasi == 'disetujui')
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1" style="border-radius: 6px; font-weight: 600;">
                                <i class="ph ph-check-circle me-1"></i> Disetujui DUDI
                            </span>
                        @else
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1" style="border-radius: 6px; font-weight: 600;">
                                <i class="ph ph-x-circle me-1"></i> Ditolak DUDI
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($isLaporan)
                            <div class="badge bg-danger text-white d-inline-flex align-items-center gap-1 px-2 py-1 mb-1" style="font-size: 0.72rem; border-radius: 6px;">
                                <i class="ph ph-megaphone"></i> Laporan Masuk
                            </div>
                            <div class="small text-danger fw-semibold text-truncate" style="max-width: 200px;" title="{{ $cleanMsg }}">
                                {{ $cleanMsg }}
                            </div>
                        @elseif($item->komentar_guru)
                            <div class="small text-muted text-truncate" style="max-width: 200px;" title="{{ $cleanMsg }}">
                                <i class="ph ph-chat-text me-1"></i> {{ $cleanMsg }}
                            </div>
                        @else
                            <span class="text-muted small">-</span>
                        @endif
                    </td>
                    <td class="text-end pe-4">
                        <a href="{{ route('guru.validasi.show', $item) }}" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1.5" style="border-radius: 8px; font-weight: 600;">
                            <i class="ph ph-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="ph ph-book-open fs-1 d-block mb-2 opacity-50"></i>
                        Tidak ada catatan jurnal harian yang ditemukan sesuai kriteria filter.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($jurnal->hasPages())
    <div class="p-3 border-top">
        {{ $jurnal->links() }}
    </div>
    @endif
</div>
@endsection

