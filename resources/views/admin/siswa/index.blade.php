@extends('layouts.app')
@section('title', 'Data Peserta Didik PKL')
@section('topbar_title', 'Data Peserta Didik PKL')

@section('content')
<style>
    .stat-card-clean {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.15rem 1.25rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        transition: all 0.2s ease;
    }
    .stat-card-clean:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.06);
    }
    .student-avatar {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.95rem;
        flex-shrink: 0;
    }
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
            <h4 class="fw-bold mb-0 text-dark">Data Peserta Didik PKL</h4>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1">
                {{ $siswa->total() }} Siswa
            </span>
        </div>
        <p class="text-muted small mb-0">Kelola master data siswa peserta Praktik Kerja Lapangan dan status akun sistem.</p>
    </div>
    <div class="d-flex flex-wrap align-items-center gap-2">
        <button type="button" class="btn btn-outline-success d-inline-flex align-items-center gap-1.5 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#importModal" style="border-radius: 9px; font-size: 0.86rem; padding: 0.5rem 0.95rem;">
            <i class="ph-bold ph-file-arrow-up"></i> Import Excel
        </button>
        <a href="{{ route('admin.export.siswa') }}" class="btn btn-light border d-inline-flex align-items-center gap-1.5 fw-semibold shadow-sm" style="border-radius: 9px; font-size: 0.86rem; padding: 0.5rem 0.95rem;">
            <i class="ph-bold ph-download-simple"></i> Export Excel
        </a>
        <a href="{{ route('admin.siswa.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-1.5 fw-semibold shadow-sm" style="border-radius: 9px; font-size: 0.86rem; padding: 0.5rem 1rem;">
            <i class="ph-bold ph-plus"></i> Tambah Siswa
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

<!-- Quick Stat Cards -->
@php
    $totalSiswa = \App\Models\Siswa::count();
    $totalLaki = \App\Models\Siswa::where('jenis_kelamin', 'L')->count();
    $totalPerempuan = \App\Models\Siswa::where('jenis_kelamin', 'P')->count();
    $totalJurusan = \App\Models\Jurusan::count();
@endphp
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card-clean d-flex align-items-center gap-3">
            <div class="rounded-3 bg-primary-subtle text-primary p-2.5 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; font-size: 1.3rem;">
                <i class="ph-duotone ph-student"></i>
            </div>
            <div>
                <div class="text-muted small fw-medium">Total Siswa</div>
                <div class="fs-5 fw-bold text-dark">{{ $totalSiswa }} <span class="fs-6 fw-normal text-muted">Siswa</span></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card-clean d-flex align-items-center gap-3">
            <div class="rounded-3 bg-info-subtle text-info p-2.5 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; font-size: 1.3rem;">
                <i class="ph-duotone ph-gender-male"></i>
            </div>
            <div>
                <div class="text-muted small fw-medium">Laki-laki</div>
                <div class="fs-5 fw-bold text-dark">{{ $totalLaki }} <span class="fs-6 fw-normal text-muted">Siswa</span></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card-clean d-flex align-items-center gap-3">
            <div class="rounded-3 bg-danger-subtle text-danger p-2.5 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; font-size: 1.3rem;">
                <i class="ph-duotone ph-gender-female"></i>
            </div>
            <div>
                <div class="text-muted small fw-medium">Perempuan</div>
                <div class="fs-5 fw-bold text-dark">{{ $totalPerempuan }} <span class="fs-6 fw-normal text-muted">Siswi</span></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card-clean d-flex align-items-center gap-3">
            <div class="rounded-3 bg-success-subtle text-success p-2.5 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; font-size: 1.3rem;">
                <i class="ph-duotone ph-books"></i>
            </div>
            <div>
                <div class="text-muted small fw-medium">Kompetensi Keahlian</div>
                <div class="fs-5 fw-bold text-dark">{{ $totalJurusan }} <span class="fs-6 fw-normal text-muted">Jurusan</span></div>
            </div>
        </div>
    </div>
</div>

<!-- Main Table Card -->
<div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden; background: #ffffff;">
    <div class="card-body p-4">
        <!-- Search & Filter Form -->
        <form class="row g-2 mb-4 align-items-center" method="GET" action="{{ route('admin.siswa.index') }}">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius: 9px 0 0 9px;">
                        <i class="ph ph-magnifying-glass"></i>
                    </span>
                    <input name="q" value="{{ $q }}" class="form-control border-start-0 ps-0" placeholder="Cari nama, NIS, NISN..." style="border-radius: 0 9px 9px 0;">
                </div>
            </div>
            <div class="col-md-3">
                <select name="jurusan_id" class="form-select" style="border-radius: 9px;" onchange="this.form.submit()">
                    <option value="">-- Semua Jurusan --</option>
                    @foreach($jurusanList as $j)
                        <option value="{{ $j->id }}" @selected($jurusan_id == $j->id)>{{ $j->nama_jurusan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="per_page" class="form-select" style="border-radius: 9px;" onchange="this.form.submit()">
                    <option value="10" @selected(isset($per_page_input) && $per_page_input == '10')>Tampilkan 10</option>
                    <option value="25" @selected(isset($per_page_input) && $per_page_input == '25')>Tampilkan 25</option>
                    <option value="50" @selected(isset($per_page_input) && $per_page_input == '50')>Tampilkan 50</option>
                    <option value="all" @selected(isset($per_page_input) && $per_page_input == 'all')>Semua</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1.5 fw-semibold" style="border-radius: 9px;">
                    <i class="ph ph-funnel"></i> Filter
                </button>
            </div>
            @if($q || $jurusan_id || (isset($per_page_input) && $per_page_input != '10'))
                <div class="col-auto">
                    <a href="{{ route('admin.siswa.index') }}" class="btn btn-light border text-muted d-inline-flex align-items-center gap-1" style="border-radius: 9px;">
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
                        <th class="text-muted fw-bold text-uppercase py-3">Nama Siswa</th>
                        <th class="text-muted fw-bold text-uppercase py-3">NIS / NISN</th>
                        <th class="text-muted fw-bold text-uppercase py-3">Kelas & Jurusan</th>
                        <th class="text-muted fw-bold text-uppercase py-3">Status Akun</th>
                        <th class="text-end text-muted fw-bold text-uppercase py-3 pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswa as $item)
                        @php
                            $initials = strtoupper(substr($item->nama, 0, 2));
                        @endphp
                        <tr>
                            <td class="text-center fw-semibold text-muted">
                                {{ $siswa->firstItem() + $loop->index }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="student-avatar">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $item->nama }}</div>
                                        <div class="small text-muted">
                                            {{ $item->jenis_kelamin === 'P' ? 'Perempuan' : ($item->jenis_kelamin === 'L' ? 'Laki-laki' : '-') }} &bull; {{ $item->user?->email ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $item->nis }}</div>
                                <div class="small text-muted">{{ $item->nisn ?? '-' }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $item->kelas?->nama_kelas ?? '-' }}</div>
                                <span class="badge bg-light text-secondary border">{{ $item->jurusan?->kode_jurusan ?? '-' }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $item->user?->status === 'aktif' ? 'success':'secondary' }}-subtle text-{{ $item->user?->status === 'aktif' ? 'success':'secondary' }} border border-{{ $item->user?->status === 'aktif' ? 'success':'secondary' }}-subtle px-2.5 py-1 rounded-pill fw-semibold">
                                    {{ ucfirst($item->user?->status ?? 'nonaktif') }}
                                </span>
                            </td>
                            <td class="text-end pe-3">
                                <div class="d-inline-flex align-items-center justify-content-end gap-1.5">
                                    <a href="{{ route('admin.siswa.edit', $item) }}" class="action-btn action-btn-edit" title="Edit Data Siswa">
                                        <i class="ph-bold ph-pencil-simple fs-6"></i>
                                    </a>
                                    <form action="{{ route('admin.siswa.destroy', $item) }}" method="POST" class="d-inline m-0 p-0" onsubmit="return confirm('Hapus siswa {{ $item->nama }} beserta akun loginnya?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-btn-delete" title="Hapus Siswa">
                                            <i class="ph-bold ph-trash fs-6"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="ph-duotone ph-student fs-1 text-muted opacity-50 mb-2"></i>
                                <div class="fw-semibold text-dark">Belum ada data siswa</div>
                                <p class="small text-muted">Klik tombol "Tambah Siswa" atau "Import Excel" untuk memulai.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $siswa->links() }}
        </div>
    </div>
</div>

<!-- Modal Import Excel -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header bg-light border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="importModalLabel">
                    <i class="ph-bold ph-file-arrow-up text-success"></i> Import Data Siswa PKL
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert bg-primary-subtle text-primary border border-primary-subtle p-3 mb-3 d-flex flex-column gap-2" style="border-radius: 10px;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold small"><i class="ph-fill ph-info me-1"></i> Format Kolom Excel:</span>
                            <a href="{{ route('admin.template.siswa') }}" class="btn btn-sm btn-primary text-white py-1 px-2.5 d-inline-flex align-items-center gap-1" style="font-size: 0.75rem; border-radius: 6px;">
                                <i class="ph-bold ph-download-simple"></i> Download Template
                            </a>
                        </div>
                        <div class="small text-dark font-monospace" style="font-size: 0.78rem;">
                            nis, nisn, nama, jenis_kelamin, kelas, jurusan, tempat_lahir, tanggal_lahir, alamat, no_hp, email
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="file_excel" class="form-label fw-semibold text-dark small">Pilih File Spreadsheet (.xlsx, .xls, .csv)</label>
                        <input class="form-control" type="file" id="file_excel" name="file_excel" accept=".xlsx, .xls, .csv" required style="border-radius: 8px;">
                    </div>
                </div>
                <div class="modal-footer bg-light border-top px-4 py-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">Batal</button>
                    <button type="submit" class="btn btn-success d-inline-flex align-items-center gap-1.5 fw-semibold" style="border-radius: 8px;">
                        <i class="ph-bold ph-upload-simple"></i> Upload & Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
