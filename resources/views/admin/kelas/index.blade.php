@extends('layouts.app')
@section('title', 'Data Rombongan Belajar / Kelas')
@section('topbar_title', 'Data Rombongan Belajar / Kelas')

@section('content')
<style>
    .action-btn {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: 1px solid transparent;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .action-btn:hover {
        transform: translateY(-1px);
    }
    .action-btn-edit {
        background: #f0fdf4;
        color: #16a34a;
        border-color: #bbf7d0;
    }
    .action-btn-edit:hover {
        background: #16a34a;
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(22, 163, 74, 0.3);
    }
    .action-btn-delete {
        background: #fef2f2;
        color: #dc2626;
        border-color: #fecaca;
    }
    .action-btn-delete:hover {
        background: #dc2626;
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(220, 38, 38, 0.3);
    }
</style>

<!-- Header Title & Action Buttons -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h4 class="fw-bold mb-0 text-dark">Data Rombongan Belajar (Kelas)</h4>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1">
                {{ $kelas->total() }} Kelas
            </span>
        </div>
        <p class="text-muted small mb-0">Kelola master kelas rombel tingkat X, XI, XII beserta wali kelas dan jurusannya.</p>
    </div>
    <div>
        <a href="{{ route('admin.kelas.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-1.5 fw-semibold shadow-sm" style="border-radius: 9px; font-size: 0.86rem; padding: 0.5rem 1rem;">
            <i class="ph-bold ph-plus"></i> Tambah Kelas
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4 d-flex align-items-center gap-2 shadow-sm" role="alert" style="border-radius: 12px; border-left: 4px solid #16a34a;">
        <i class="ph-fill ph-check-circle fs-5 text-success"></i>
        <div class="small fw-semibold">{{ session('success') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4 d-flex align-items-center gap-2 shadow-sm" role="alert" style="border-radius: 12px; border-left: 4px solid #dc2626;">
        <i class="ph-fill ph-warning-circle fs-5 text-danger"></i>
        <div class="small fw-semibold">{{ session('error') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Main Table Card -->
<div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden; background: #ffffff;">
    <div class="card-body p-4">
        <!-- Search Bar -->
        <form class="row g-2 mb-4 align-items-center" method="GET" action="{{ route('admin.kelas.index') }}">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius: 9px 0 0 9px;">
                        <i class="ph ph-magnifying-glass"></i>
                    </span>
                    <input name="q" value="{{ $q }}" class="form-control border-start-0 ps-0" placeholder="Cari nama kelas atau jurusan..." style="border-radius: 0 9px 9px 0;">
                </div>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1.5 fw-semibold" style="border-radius: 9px;">
                    <i class="ph ph-funnel"></i> Filter
                </button>
            </div>
            @if($q)
                <div class="col-auto">
                    <a href="{{ route('admin.kelas.index') }}" class="btn btn-light border text-muted d-inline-flex align-items-center gap-1" style="border-radius: 9px;">
                        <i class="ph ph-x"></i> Reset
                    </a>
                </div>
            @endif
        </form>

        <!-- Table -->
        <div class="table-responsive rounded-3 border">
            <table class="table align-middle mb-0" style="font-size: 0.88rem;">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 50px;" class="text-center text-muted fw-bold text-uppercase py-3">No</th>
                        <th class="text-muted fw-bold text-uppercase py-3">Nama Rombel / Kelas</th>
                        <th class="text-muted fw-bold text-uppercase py-3">Tingkat</th>
                        <th class="text-muted fw-bold text-uppercase py-3">Jurusan</th>
                        <th class="text-muted fw-bold text-uppercase py-3">Wali Kelas</th>
                        <th class="text-center text-muted fw-bold text-uppercase py-3">Status</th>
                        <th class="text-end text-muted fw-bold text-uppercase py-3 pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kelas as $item)
                        <tr>
                            <td class="text-center fw-semibold text-muted">
                                {{ $kelas->firstItem() + $loop->index }}
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $item->nama_kelas }}</div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border fw-medium px-2 py-1">
                                    Tingkat {{ $item->tingkat }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $item->jurusan?->nama_jurusan ?? '-' }}</div>
                            </td>
                            <td>
                                <div class="text-dark">{{ $item->waliKelas?->nama ?? ($item->wali_kelas ?? '-') }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $item->status ? 'success' : 'secondary' }}-subtle text-{{ $item->status ? 'success' : 'secondary' }} border border-{{ $item->status ? 'success' : 'secondary' }}-subtle px-2.5 py-1 rounded-pill fw-semibold">
                                    {{ $item->status ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="text-end pe-3">
                                <div class="d-inline-flex align-items-center justify-content-end gap-1.5">
                                    <a href="{{ route('admin.kelas.edit', $item) }}" class="action-btn action-btn-edit" title="Edit Kelas">
                                        <i class="ph-bold ph-pencil-simple fs-6"></i>
                                    </a>
                                    <form action="{{ route('admin.kelas.destroy', $item) }}" method="POST" class="d-inline m-0 p-0" onsubmit="return confirm('Hapus kelas {{ $item->nama_kelas }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-btn-delete" title="Hapus Kelas">
                                            <i class="ph-bold ph-trash fs-6"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="ph-duotone ph-chalkboard fs-1 text-muted opacity-50 mb-2"></i>
                                <div class="fw-semibold text-dark">Belum ada data kelas</div>
                                <p class="small text-muted">Klik tombol "Tambah Kelas" untuk menambahkan rombel baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $kelas->links() }}
        </div>
    </div>
</div>
@endsection
