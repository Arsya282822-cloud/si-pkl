@extends('layouts.app')
@section('title', 'Jurnal Kegiatan Harian')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: var(--text-main);">Jurnal Kegiatan Harian PKL</h3>
        <p class="text-muted mb-0" style="font-size: 0.875rem;">Catat, dokumentasikan foto, dan pantau status validasi bimbingan setiap hari.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('siswa.cetak.jurnal') }}" target="_blank" class="btn btn-outline-primary d-inline-flex align-items-center gap-1.5" style="border-radius: 10px; font-weight: 500;">
            <i class="ph ph-printer"></i> Cetak Jurnal Lengkap
        </a>
        <a href="{{ route('siswa.jurnal.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-1.5 shadow-sm" style="border-radius: 10px; font-weight: 600;">
            <i class="ph ph-plus-circle" style="font-size: 18px;"></i>
            <span>Tulis Jurnal Baru</span>
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="border-radius: 10px;">
    <i class="ph ph-check-circle me-1 fw-bold"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger mb-4" style="border-radius: 10px;">{{ session('error') }}</div>
@endif

<div class="pro-card" style="border-radius: 16px;">
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="table-light" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">
                <tr>
                    <th class="ps-4">Hari & Tanggal</th>
                    <th>Rincian Aktivitas Kegiatan</th>
                    <th>Foto Bukti</th>
                    <th>Validasi Instruktur DUDI</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jurnal as $item)
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center gap-2">
                            <i class="ph ph-calendar text-primary" style="font-size: 18px;"></i>
                            <span class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d M Y') }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="text-truncate" style="max-width: 320px;" title="{{ $item->kegiatan }}">
                            {{ $item->kegiatan }}
                        </div>
                    </td>
                    <td>
                        @if($item->foto)
                            @php
                                $photoUrl = str_starts_with($item->foto, 'uploads/') ? asset($item->foto) : asset('storage/' . $item->foto);
                            @endphp
                            <a href="{{ $photoUrl }}" target="_blank" class="badge bg-info-subtle text-info border border-info-subtle text-decoration-none d-inline-flex align-items-center gap-1 px-2.5 py-1" style="border-radius: 6px;">
                                <i class="ph ph-image"></i> Lihat Foto
                            </a>
                        @else
                            <span class="text-muted small">-</span>
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

                        @if($item->komentar_guru)
                            <button type="button" class="btn btn-link btn-sm p-0 ms-1 text-decoration-none" data-bs-toggle="popover" data-bs-trigger="focus" title="Catatan Pembimbing / Instruktur" data-bs-content="{{ $item->komentar_guru }}">
                                <i class="ph ph-chat-centered-dots text-primary" style="font-size: 18px;"></i>
                            </button>
                        @endif
                    </td>
                    <td class="text-end pe-4">
                        @if($item->status_validasi == 'menunggu')
                            <a href="{{ route('siswa.jurnal.edit', $item) }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1" style="border-radius: 8px;">
                                <i class="ph ph-pencil-simple"></i> Edit
                            </a>
                        @else
                            <span class="badge bg-light text-muted border px-2 py-1" style="border-radius: 6px; font-size: 0.72rem;">
                                <i class="ph ph-lock-key me-1"></i> Terkunci
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <i class="ph ph-book-open fs-1 d-block mb-2 opacity-50"></i>
                        Belum ada catatan jurnal harian.<br>
                        Mulai catat kegiatan PKL Anda dengan klik tombol "Tulis Jurnal Baru".
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

<!-- Script to initialize popovers for comments -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    })
})
</script>
@endsection

