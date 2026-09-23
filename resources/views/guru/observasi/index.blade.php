@extends('layouts.app')
@section('title', 'Lembar Observasi PKL')
@section('topbar_title', 'Lembar Observasi PKL')

@section('content')
@php
    $routePrefix = Auth::user()->role?->nama_role === 'admin' ? 'admin' : 'guru';
@endphp
<div class="container-fluid px-0">

    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--text-main);">Lembar Observasi Praktik Kerja Lapangan</h4>
            <p class="text-muted mb-0" style="font-size: 0.875rem;">
                Instrumen resmi penilaian supervisi capaian pembelajaran siswa di dunia kerja (Soft skills, Kompetensi Teknis POS, Kompetensi Baru, & Analisis Usaha).
            </p>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="{{ route($routePrefix . '.monitoring.excel') }}" class="btn btn-success d-inline-flex align-items-center gap-1.5 px-3 py-2 text-white" style="border-radius: 10px; font-weight: 600;">
                <i class="ph ph-file-xls" style="font-size: 18px;"></i>
                <span>Template Excel (.xlsx)</span>
            </a>
            <a href="{{ route($routePrefix . '.monitoring.blanko') }}" target="_blank" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1.5 px-3 py-2" style="border-radius: 10px; font-weight: 600;">
                <i class="ph ph-printer" style="font-size: 18px;"></i>
                <span>Cetak Blanko Kosong</span>
            </a>
            <a href="{{ route($routePrefix . '.observasi.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-1.5 px-3 py-2" style="border-radius: 10px; font-weight: 600; box-shadow: 0 4px 14px rgba(2, 132, 199, 0.25);">
                <i class="ph ph-plus-circle" style="font-size: 18px;"></i>
                <span>Isi Lembar Observasi Baru</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 10px;">
            <i class="ph ph-check-circle me-1 fw-bold"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="pro-card p-4 border-0 shadow-sm" style="border-radius: 16px;">
        <!-- Search Filter -->
        <form method="GET" action="{{ route($routePrefix . '.observasi.index') }}" class="row g-2 mb-4">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0">
                        <i class="ph ph-magnifying-glass"></i>
                    </span>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control border-start-0 ps-0" placeholder="Cari nama siswa, jurusan, atau perusahaan..." style="border-radius: 0 8px 8px 0;">
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100 fw-semibold" style="border-radius: 8px;">
                    <i class="ph ph-funnel me-1"></i> Filter
                </button>
            </div>
            @if(request('q'))
                <div class="col-md-2">
                    <a href="{{ route($routePrefix . '.observasi.index') }}" class="btn btn-light border w-100 text-muted" style="border-radius: 8px;">
                        Reset
                    </a>
                </div>
            @endif
        </form>

        @if($observasis->isEmpty())
            <div class="text-center py-5 text-muted">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; background: #f0f9ff; color: #0284c7;">
                    <i class="ph ph-clipboard-text" style="font-size: 36px;"></i>
                </div>
                <h6 class="fw-bold text-dark">Belum Ada Lembar Observasi</h6>
                <p class="small text-muted mb-3" style="max-width: 480px; margin: auto;">
                    Belum ada lembar observasi digital yang diisi. Klik tombol di bawah untuk mulai mengisi instrumen capaian pembelajaran.
                </p>
                <a href="{{ route($routePrefix . '.observasi.create') }}" class="btn btn-primary px-4 py-2" style="border-radius: 8px; font-weight: 600;">
                    <i class="ph ph-plus-circle me-1"></i> Mulai Isi Lembar Observasi
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40px;">No</th>
                            <th>Peserta Didik & Jurusan</th>
                            <th>Institusi (Tempat PKL)</th>
                            <th>Instruktur DUDI</th>
                            <th>Periode Observasi</th>
                            <th class="text-end">Aksi Dokumen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($observasis as $index => $item)
                        <tr>
                            <td>{{ $observasis->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $item->nama_murid }}</div>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-0.5" style="font-size: 0.725rem;">
                                    {{ $item->konsentrasi_keahlian }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $item->nama_institusi }}</div>
                                <small class="text-muted d-block">{{ $item->alamat_institusi ?: '-' }}</small>
                            </td>
                            <td>
                                <div class="text-dark">{{ $item->nama_instruktur ?: '-' }}</div>
                                <small class="text-muted">{{ $item->jabatan_instruktur ?: 'Instruktur' }}</small>
                            </td>
                            <td>
                                <div class="small">
                                    <span class="text-muted">Akhir:</span> 
                                    <strong>{{ $item->tgl_observasi_akhir ? \Carbon\Carbon::parse($item->tgl_observasi_akhir)->format('d M Y') : '-' }}</strong>
                                </div>
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route($routePrefix . '.observasi.cetak', $item->id) }}" target="_blank" class="btn btn-sm btn-primary fw-semibold" title="Cetak PDF">
                                    <i class="ph ph-printer me-1"></i> Cetak PDF
                                </a>
                                <a href="{{ route($routePrefix . '.observasi.excel', $item->id) }}" class="btn btn-sm btn-outline-success fw-semibold" title="Download Excel">
                                    <i class="ph ph-file-xls me-1"></i> Excel
                                </a>
                                <a href="{{ route($routePrefix . '.observasi.edit', $item->id) }}" class="btn btn-sm btn-outline-secondary" title="Edit Data">
                                    <i class="ph ph-pencil-simple"></i>
                                </a>
                                <form action="{{ route($routePrefix . '.observasi.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus lembar observasi untuk {{ $item->nama_murid }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="ph ph-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $observasis->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
