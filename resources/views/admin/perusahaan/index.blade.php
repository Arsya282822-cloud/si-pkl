@extends('layouts.app')
@section('title', 'Data Perusahaan Mitra DUDI')
@section('topbar_title', 'Perusahaan Mitra & Instruktur DUDI')

@section('content')
<style>
    .dudi-card-stat {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.15rem 1.25rem;
        transition: all 0.25s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }
    .dudi-card-stat:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.06);
        border-color: #cbd5e1;
    }
    .stat-icon-wrapper {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }
    .company-avatar {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.05rem;
        flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(2, 132, 199, 0.25);
    }
    .table-dudi th {
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        font-weight: 700;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 0.9rem 1rem;
    }
    .table-dudi td {
        padding: 0.95rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }
    .table-dudi tbody tr {
        transition: background-color 0.15s ease;
    }
    .table-dudi tbody tr:hover {
        background-color: #f8fafc;
    }
    .account-badge-box {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.45rem 0.65rem;
        display: inline-block;
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
        color: #475569;
        background: #f8fafc;
        border-color: #e2e8f0;
        text-decoration: none;
    }
    .action-btn:hover {
        transform: translateY(-1px);
    }
    .action-btn-instruktur {
        height: 32px;
        padding: 0 10px;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #eff6ff;
        color: #0284c7;
        border: 1px solid #bfdbfe;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .action-btn-instruktur:hover {
        background: #0284c7;
        color: #ffffff;
        border-color: #0284c7;
        box-shadow: 0 2px 6px rgba(2, 132, 199, 0.3);
    }
    .action-btn-edit {
        background: #f0fdf4;
        color: #16a34a;
        border-color: #bbf7d0;
    }
    .action-btn-edit:hover {
        background: #16a34a;
        color: #ffffff;
        border-color: #16a34a;
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
        border-color: #dc2626;
        box-shadow: 0 2px 6px rgba(220, 38, 38, 0.3);
    }
    .copy-chip {
        cursor: pointer;
        user-select: none;
        transition: all 0.15s ease;
    }
    .copy-chip:hover {
        filter: brightness(0.95);
    }
</style>

<!-- Header Title & Action Buttons -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h4 class="fw-bold mb-0 text-dark">Perusahaan Mitra & Instruktur DUDI</h4>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1">
                {{ $perusahaan->total() }} Mitra
            </span>
        </div>
        <p class="text-muted small mb-0">Kelola master data mitra industri PKL serta akun login instruktur pembimbing lapangan secara instan.</p>
    </div>
    <div class="d-flex flex-wrap align-items-center gap-2">
        <form action="{{ route('admin.perusahaan.sync_instruktur') }}" method="POST" class="d-inline" onsubmit="return confirm('Generate/sinkronkan akun login Instruktur untuk seluruh perusahaan DUDI dengan password default (dudi1234)?')">
            @csrf
            <button type="submit" class="btn btn-warning d-inline-flex align-items-center gap-1.5 fw-semibold text-dark shadow-sm" style="border-radius: 9px; font-size: 0.86rem; padding: 0.5rem 0.95rem;">
                <i class="ph-bold ph-lightning"></i> Generate Akun Semua Instruktur
            </button>
        </form>
        <button type="button" class="btn btn-outline-success d-inline-flex align-items-center gap-1.5 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#importModal" style="border-radius: 9px; font-size: 0.86rem; padding: 0.5rem 0.95rem;">
            <i class="ph-bold ph-file-arrow-up"></i> Import Excel
        </button>
        <a href="{{ route('admin.perusahaan.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-1.5 fw-semibold shadow-sm" style="border-radius: 9px; font-size: 0.86rem; padding: 0.5rem 1rem;">
            <i class="ph-bold ph-plus"></i> Tambah Perusahaan
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
    $totalMitra = \App\Models\Perusahaan::count();
    $totalInstruktur = \App\Models\PembimbingIndustri::count();
    $totalAktif = \App\Models\Perusahaan::where('status', 'aktif')->count();
    $totalKota = \App\Models\Perusahaan::whereNotNull('kota')->where('kota', '!=', '')->distinct('kota')->count('kota');
@endphp
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="dudi-card-stat d-flex align-items-center gap-3">
            <div class="stat-icon-wrapper bg-primary-subtle text-primary">
                <i class="ph-duotone ph-buildings"></i>
            </div>
            <div>
                <div class="text-muted small fw-medium">Total Mitra DUDI</div>
                <div class="fs-5 fw-bold text-dark">{{ $totalMitra }} <span class="fs-6 fw-normal text-muted">Perusahaan</span></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="dudi-card-stat d-flex align-items-center gap-3">
            <div class="stat-icon-wrapper bg-info-subtle text-info">
                <i class="ph-duotone ph-user-switch"></i>
            </div>
            <div>
                <div class="text-muted small fw-medium">Akun Instruktur</div>
                <div class="fs-5 fw-bold text-dark">{{ $totalInstruktur }} <span class="fs-6 fw-normal text-muted">Akun</span></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="dudi-card-stat d-flex align-items-center gap-3">
            <div class="stat-icon-wrapper bg-success-subtle text-success">
                <i class="ph-duotone ph-check-circle"></i>
            </div>
            <div>
                <div class="text-muted small fw-medium">Mitra Aktif</div>
                <div class="fs-5 fw-bold text-dark">{{ $totalAktif }} <span class="fs-6 fw-normal text-muted">DUDI</span></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="dudi-card-stat d-flex align-items-center gap-3">
            <div class="stat-icon-wrapper bg-warning-subtle text-warning">
                <i class="ph-duotone ph-map-pin"></i>
            </div>
            <div>
                <div class="text-muted small fw-medium">Cakupan Wilayah</div>
                <div class="fs-5 fw-bold text-dark">{{ $totalKota ?: 1 }} <span class="fs-6 fw-normal text-muted">Kota/Kab</span></div>
            </div>
        </div>
    </div>
</div>

<!-- Main Data Card -->
<div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden; background: #ffffff;">
    <div class="card-body p-4">
        <!-- Search & Filter Bar -->
        <form class="row g-2 mb-4 align-items-center" method="GET" action="{{ route('admin.perusahaan.index') }}">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius: 9px 0 0 9px;">
                        <i class="ph ph-magnifying-glass"></i>
                    </span>
                    <input name="q" value="{{ $q }}" class="form-control border-start-0 ps-0" placeholder="Cari nama perusahaan, kota, pimpinan..." style="border-radius: 0 9px 9px 0;">
                </div>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1.5 fw-semibold" style="border-radius: 9px;">
                    <i class="ph ph-funnel"></i> Filter
                </button>
            </div>
            @if($q)
                <div class="col-auto">
                    <a href="{{ route('admin.perusahaan.index') }}" class="btn btn-light border text-muted d-inline-flex align-items-center gap-1" style="border-radius: 9px;">
                        <i class="ph ph-x"></i> Reset
                    </a>
                </div>
            @endif
        </form>

        <!-- Table -->
        <div class="table-responsive rounded-3 border">
            <table class="table table-dudi align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;" class="text-center">No</th>
                        <th>Perusahaan Mitra</th>
                        <th>Kota / Lokasi</th>
                        <th>Pimpinan / Kontak</th>
                        <th>Akun Login Instruktur DUDI</th>
                        <th class="text-center">Status</th>
                        <th class="text-end" style="min-width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($perusahaan as $item)
                        @php
                            $instruktur = $item->pembimbingIndustri->first();
                            $dudiEmail = $instruktur?->email ?? ('dudi' . $item->id . '@dudi.com');
                            $initials = strtoupper(substr($item->nama_perusahaan, 0, 2));
                        @endphp
                        <tr>
                            <td class="text-center fw-semibold text-muted" style="font-size: 0.85rem;">
                                {{ $perusahaan->firstItem() + $loop->index }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="company-avatar">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $item->nama_perusahaan }}</div>
                                        <div class="small text-muted text-truncate" style="max-width: 250px;">
                                            <i class="ph ph-map-pin me-1 text-secondary"></i>{{ $item->alamat ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($item->kota)
                                    <span class="badge bg-light text-dark border fw-medium px-2.5 py-1">
                                        <i class="ph ph-map-trifold me-1 text-primary"></i>{{ $item->kota }}
                                    </span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold text-dark small">{{ $item->nama_pimpinan ?? '-' }}</div>
                                @if($item->no_telepon)
                                    <div class="small mt-0.5">
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->no_telepon) }}" target="_blank" class="text-decoration-none text-success fw-medium d-inline-flex align-items-center gap-1">
                                            <i class="ph-fill ph-whatsapp-logo"></i> {{ $item->no_telepon }}
                                        </a>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="account-badge-box">
                                    <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace px-2 py-0.5 copy-chip" onclick="copyToClipboard('{{ $dudiEmail }}', 'Email {{ $dudiEmail }} disalin!')" title="Klik untuk menyalin email" style="font-size: 0.82rem; font-weight: 700;">
                                            <i class="ph-bold ph-envelope-simple me-1"></i>{{ $dudiEmail }}
                                        </span>
                                        <button type="button" class="btn btn-link p-0 text-muted" onclick="copyToClipboard('{{ $dudiEmail }}', 'Email {{ $dudiEmail }} disalin!')" title="Salin Email">
                                            <i class="ph ph-copy fs-6"></i>
                                        </button>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 small text-muted" style="font-size: 0.73rem;">
                                        <span>Pass: <code class="text-dark fw-bold bg-white px-1.5 py-0.5 rounded border">dudi1234</code></span>
                                        @if($instruktur?->nama)
                                            <span class="text-truncate" style="max-width: 140px;" title="{{ $instruktur->nama }}">&bull; {{ $instruktur->nama }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                @if($item->status === 'aktif')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill fw-semibold">
                                        <i class="ph-fill ph-check-circle me-1"></i>Aktif
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2.5 py-1 rounded-pill fw-semibold">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex align-items-center justify-content-end gap-1.5">
                                    <!-- Tombol Kelola Akun Instruktur -->
                                    <button type="button" 
                                        class="action-btn-instruktur btn-open-instruktur-modal" 
                                        onclick="openInstrukturModal(this)"
                                        data-action="{{ route('admin.perusahaan.instruktur', $item->id) }}"
                                        data-company="{{ $item->nama_perusahaan }}"
                                        data-nama="{{ $instruktur?->nama ?? $item->nama_pimpinan ?? ('Pembimbing ' . $item->nama_perusahaan) }}"
                                        data-email="{{ $dudiEmail }}"
                                        data-jabatan="{{ $instruktur?->jabatan ?? 'Pembimbing Lapangan' }}"
                                        data-nohp="{{ $instruktur?->no_hp ?? $item->no_telepon }}"
                                        data-default-email="dudi{{ $item->id }}@dudi.com"
                                        title="Atur Akun Login Instruktur DUDI">
                                        <i class="ph-bold ph-user-gear fs-6"></i>
                                        <span>Instruktur</span>
                                    </button>

                                    <!-- Tombol Edit Perusahaan -->
                                    <a href="{{ route('admin.perusahaan.edit', $item) }}" class="action-btn action-btn-edit" title="Edit Data Perusahaan">
                                        <i class="ph-bold ph-pencil-simple fs-6"></i>
                                    </a>

                                    <!-- Tombol Hapus Perusahaan -->
                                    <form action="{{ route('admin.perusahaan.destroy', $item) }}" method="POST" class="d-inline m-0 p-0" onsubmit="return confirm('Hapus perusahaan {{ $item->nama_perusahaan }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-btn-delete" title="Hapus Perusahaan">
                                            <i class="ph-bold ph-trash fs-6"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <div class="py-4">
                                    <i class="ph-duotone ph-buildings fs-1 text-muted opacity-50 mb-2"></i>
                                    <div class="fw-semibold text-dark">Belum ada data perusahaan mitra</div>
                                    <p class="small text-muted">Klik tombol "Tambah Perusahaan" atau "Import Excel" untuk memulai.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $perusahaan->links() }}
        </div>
    </div>
</div>
@endsection

@push('modals')
<!-- Modal Akun Instruktur (Global Dynamic) -->
<div class="modal fade text-start" id="modalInstrukturGlobal" tabindex="-1" aria-labelledby="modalInstrukturGlobalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formInstrukturGlobal" action="" method="POST">
            @csrf
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-header bg-light border-bottom px-4 py-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="stat-icon-wrapper bg-primary text-white" style="width: 36px; height: 36px; font-size: 1.1rem; border-radius: 8px;">
                            <i class="ph-bold ph-user-gear"></i>
                        </div>
                        <div>
                            <h6 class="modal-title fw-bold text-dark mb-0" id="modalInstrukturGlobalLabel">Kelola Akun Instruktur DUDI</h6>
                            <div class="small text-muted" id="instrukturModalCompanyName">-</div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert bg-primary-subtle text-primary border border-primary-subtle p-3 mb-3 d-flex align-items-start gap-2.5" style="border-radius: 10px;">
                        <i class="ph-fill ph-info fs-5 mt-0.5"></i>
                        <div class="small">
                            <strong>Hak Akses Instruktur DUDI:</strong> Instruktur bertugas menyetujui absensi harian siswa, memvalidasi jurnal kerja, mengirim laporan ke guru pembimbing, dan memberikan nilai evaluasi industri.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small">Nama Lengkap Instruktur / PIC <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="instrukturInputNama" class="form-control" required style="border-radius: 8px;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small">Email Login Instruktur <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="instrukturInputEmail" class="form-control font-monospace fw-bold" required style="border-radius: 8px;">
                        <div class="form-text small text-muted">Format standar praktis: <code id="instrukturDefaultEmailCode">dudi@dudi.com</code></div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold text-dark small">Jabatan di DUDI</label>
                            <input type="text" name="jabatan" id="instrukturInputJabatan" class="form-control" placeholder="Contoh: HRD / Supervisor" style="border-radius: 8px;">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold text-dark small">No. WhatsApp</label>
                            <input type="text" name="no_hp" id="instrukturInputNoHp" class="form-control" placeholder="08xxxxxxxx" style="border-radius: 8px;">
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold text-dark small">Ubah Password Akun</label>
                        <input type="text" name="password" id="instrukturInputPassword" class="form-control font-monospace" placeholder="Kosongkan jika default 'dudi1234'" style="border-radius: 8px;">
                        <div class="form-text small text-muted">Password default bawaan: <code class="fw-bold">dudi1234</code></div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top px-4 py-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">Batal</button>
                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1.5 fw-semibold" style="border-radius: 8px;">
                        <i class="ph-bold ph-check"></i> Simpan Akun Instruktur
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Import Excel -->
<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.perusahaan.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-header bg-light border-bottom px-4 py-3">
                    <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="ph-bold ph-file-arrow-up text-success"></i> Import Data Perusahaan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Pilih File Spreadsheet (Excel/CSV)</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required style="border-radius: 8px;">
                        <div class="form-text small text-muted mt-2">
                            Kolom heading yang dikenali: <code>nama_perusahaan, alamat, kota, no_telepon, email, website, nama_pimpinan, status</code>.
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top px-4 py-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">Batal</button>
                    <button type="submit" class="btn btn-success d-inline-flex align-items-center gap-1.5 fw-semibold" style="border-radius: 8px;">
                        <i class="ph-bold ph-upload-simple"></i> Import Sekarang
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endpush

@push('scripts')
<!-- Toast & Dynamic Modal Script Helper -->
<script>
    function copyToClipboard(text, message) {
        navigator.clipboard.writeText(text).then(function() {
            if (window.toastNotification) {
                window.toastNotification(message || 'Tersalin ke clipboard!');
            } else {
                alert(message || 'Tersalin: ' + text);
            }
        }).catch(function() {
            prompt('Salin teks manual:', text);
        });
    }

    function openInstrukturModal(button) {
        if (!button) return;

        const form = document.getElementById('formInstrukturGlobal');
        const action = button.getAttribute('data-action');
        const company = button.getAttribute('data-company');
        const nama = button.getAttribute('data-nama');
        const email = button.getAttribute('data-email');
        const jabatan = button.getAttribute('data-jabatan');
        const nohp = button.getAttribute('data-nohp');
        const defaultEmail = button.getAttribute('data-default-email');

        if (form) form.action = action;
        const companyEl = document.getElementById('instrukturModalCompanyName');
        if (companyEl) companyEl.textContent = company || '-';
        const namaEl = document.getElementById('instrukturInputNama');
        if (namaEl) namaEl.value = nama || '';
        const emailEl = document.getElementById('instrukturInputEmail');
        if (emailEl) emailEl.value = email || '';
        const jabatanEl = document.getElementById('instrukturInputJabatan');
        if (jabatanEl) jabatanEl.value = jabatan || '';
        const nohpEl = document.getElementById('instrukturInputNoHp');
        if (nohpEl) nohpEl.value = nohp || '';
        const passEl = document.getElementById('instrukturInputPassword');
        if (passEl) passEl.value = '';
        const defEmailEl = document.getElementById('instrukturDefaultEmailCode');
        if (defEmailEl) defEmailEl.textContent = defaultEmail || 'dudi@dudi.com';

        const modalEl = document.getElementById('modalInstrukturGlobal');
        if (modalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
            bsModal.show();
        }
    }
</script>
@endpush
