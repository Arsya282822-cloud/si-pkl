@extends('layouts.app')
@section('title', 'Monitoring Kunjungan')
@section('topbar_title', 'Monitoring & Supervisi PKL')

@section('content')
<div class="container-fluid px-0">

    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--text-main);">Monitoring & Berkas Observasi PKL</h4>
            <p class="text-muted mb-0" style="font-size: 0.875rem;">Dokumentasikan supervisi lapangan, kelola berkas observasi bertanda tangan DUDI, dan cetak berita acara resmi.</p>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="{{ route('guru.monitoring.excel') }}" class="btn btn-success d-inline-flex align-items-center gap-1.5 px-3 py-2 text-white" style="border-radius: 10px; font-weight: 600;">
                <i class="ph ph-file-xls" style="font-size: 18px;"></i>
                <span>Format Excel (.xlsx)</span>
            </a>
            <a href="{{ route('guru.monitoring.blanko') }}" target="_blank" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1.5 px-3 py-2" style="border-radius: 10px; font-weight: 600;">
                <i class="ph ph-printer" style="font-size: 18px;"></i>
                <span>Cetak Blanko Observasi</span>
            </a>
            <a href="{{ route('guru.monitoring.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-1.5 px-3 py-2" style="border-radius: 10px; font-weight: 600; box-shadow: 0 4px 12px rgba(14, 165, 233, 0.25);">
                <i class="ph ph-plus-circle" style="font-size: 18px;"></i>
                <span>Catat & Unggah Berkas Baru</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 10px;">
            <i class="ph ph-check-circle me-1 fw-bold"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="pro-card p-4" style="border-radius: 16px;">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th>Tanggal Kunjungan</th>
                        <th>Perusahaan / DUDI</th>
                        <th>Aspek & Evaluasi Lapangan</th>
                        <th>Foto & Berkas Scan</th>
                        <th class="text-end">Aksi Dokumen</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($monitoring as $i => $item)
                    <tr>
                        <td>{{ $monitoring->firstItem() + $i }}</td>
                        <td class="fw-semibold">
                            <div class="d-flex align-items-center gap-2">
                                <i class="ph ph-calendar text-primary" style="font-size: 18px;"></i>
                                {{ \Carbon\Carbon::parse($item->tanggal_kunjungan)->translatedFormat('d M Y') }}
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->perusahaan?->nama_perusahaan ?? '-' }}</div>
                            <small class="text-muted">{{ $item->perusahaan?->alamat ?: 'Pekanbaru' }}</small>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-1 mb-1">
                                @if($item->kesesuaian_kompetensi == 'sangat_sesuai')
                                    <span class="badge bg-success-subtle text-success border">Kompetensi: Sangat Sesuai</span>
                                @elseif($item->kesesuaian_kompetensi == 'sesuai')
                                    <span class="badge bg-primary-subtle text-primary border">Kompetensi: Sesuai</span>
                                @elseif($item->kesesuaian_kompetensi == 'cukup')
                                    <span class="badge bg-warning-subtle text-warning border">Kompetensi: Cukup</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border">Kompetensi: Sesuai</span>
                                @endif

                                @if($item->kedisiplinan_siswa == 'sangat_baik' || $item->kedisiplinan_siswa == 'baik')
                                    <span class="badge bg-info-subtle text-info border">Sikap: Baik</span>
                                @endif
                            </div>
                            <div class="text-truncate text-muted" style="max-width: 250px; font-size: 0.825rem;" title="{{ $item->catatan }}">
                                {{ $item->catatan }}
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1.5 align-items-start">
                                @if($item->foto)
                                    <a href="{{ asset($item->foto) }}" target="_blank" class="badge bg-info-subtle text-info border border-info-subtle text-decoration-none d-inline-flex align-items-center gap-1 px-2 py-1" style="border-radius: 6px;">
                                        <i class="ph ph-image"></i> Foto Kegiatan
                                    </a>
                                @endif

                                @if($item->file_observasi)
                                    <a href="{{ asset($item->file_observasi) }}" target="_blank" class="badge bg-danger-subtle text-danger border border-danger-subtle text-decoration-none d-inline-flex align-items-center gap-1 px-2 py-1" style="border-radius: 6px;">
                                        <i class="ph ph-file-pdf"></i> Berkas Scan DUDI
                                    </a>
                                @endif

                                @if(!$item->foto && !$item->file_observasi)
                                    <span class="text-muted small">Belum diunggah</span>
                                @endif
                            </div>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('guru.monitoring.cetak', $item) }}" target="_blank" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1" style="border-radius: 8px;">
                                <i class="ph ph-printer"></i> Cetak Berita Acara
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="ph ph-clipboard-text fs-1 d-block mb-2 opacity-50"></i>
                            Belum ada berkas atau catatan observasi.<br>
                            Klik <strong>"Catat & Unggah Berkas Baru"</strong> untuk mendokumentasikan hasil supervisi PKL.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($monitoring->hasPages())
        <div class="mt-4 pt-3 border-top">
            {{ $monitoring->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
