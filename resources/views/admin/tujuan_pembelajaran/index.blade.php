@extends('layouts.app')

@section('title', 'Tujuan Pembelajaran PKL 2026')

@section('content')
<div class="container-fluid py-3">

    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 rounded-pill fw-semibold">
                    <i class="ph-bold ph-book-open me-1"></i> Kurikulum PKL 2026
                </span>
                <span class="badge bg-secondary-subtle text-secondary px-2.5 py-1 rounded-pill">
                    Total: {{ $totalAll }} Tujuan Pembelajaran
                </span>
            </div>
            <h4 class="fw-bold text-dark mb-0">Tujuan Pembelajaran (TP) PKL</h4>
            <p class="text-muted small mb-0">Daftar Capaian Pembelajaran dan Tujuan Pembelajaran resmi berdasarkan Konsentrasi Keahlian.</p>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="{{ route('admin.tujuan_pembelajaran.cetak', ['jurusan' => $selectedJurusan]) }}" target="_blank" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1.5 rounded-pill px-3 shadow-sm">
                <i class="ph-bold ph-printer"></i>
                <span>Cetak / PDF Dokumen TP</span>
            </a>
            <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-1.5 rounded-pill px-3.5 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahTp">
                <i class="ph-bold ph-plus-circle"></i>
                <span>Tambah TP Baru</span>
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert">
            <i class="ph-fill ph-check-circle me-1.5 text-success"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Jurusan Tabs / Filter Cards -->
    <div class="row g-2.5 mb-4">
        @php
            $jurusanList = [
                ['kode' => 'AK', 'nama' => 'Akuntansi', 'icon' => 'ph-calculator', 'color' => 'success'],
                ['kode' => 'BR', 'nama' => 'Bisnis Ritel', 'icon' => 'ph-storefront', 'color' => 'primary'],
                ['kode' => 'TKJ', 'nama' => 'Teknik Komputer & Jaringan', 'icon' => 'ph-hard-drives', 'color' => 'info'],
                ['kode' => 'RPL', 'nama' => 'Rekayasa Perangkat Lunak', 'icon' => 'ph-code', 'color' => 'warning'],
                ['kode' => 'MP', 'nama' => 'Manajemen Perkantoran', 'icon' => 'ph-buildings', 'color' => 'danger'],
            ];
        @endphp

        @foreach($jurusanList as $j)
            @php
                $isActive = $selectedJurusan === $j['kode'];
                $count = $stats[$j['kode']]->total ?? 0;
            @endphp
            <div class="col-6 col-md">
                <a href="{{ route('admin.tujuan_pembelajaran.index', ['jurusan' => $j['kode']]) }}" class="card text-decoration-none h-100 border-0 shadow-sm transition-all {{ $isActive ? 'bg-primary text-white' : 'bg-white text-dark border' }}" style="border-radius: 14px; {{ $isActive ? 'box-shadow: 0 8px 20px rgba(2,132,199,0.25) !important;' : '' }}">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="rounded-3 p-2 d-flex align-items-center justify-content-center {{ $isActive ? 'bg-white bg-opacity-20 text-white' : 'bg-light text-'.$j['color'] }}" style="width: 36px; height: 36px;">
                                <i class="ph-bold {{ $j['icon'] }}" style="font-size: 1.15rem;"></i>
                            </div>
                            <span class="badge rounded-pill {{ $isActive ? 'bg-white text-primary fw-bold' : 'bg-secondary-subtle text-secondary' }}" style="font-size: 0.75rem;">
                                {{ $count }} TP
                            </span>
                        </div>
                        <div class="fw-bold text-truncate" style="font-size: 0.875rem;">{{ $j['nama'] }}</div>
                        <div class="small {{ $isActive ? 'text-white text-opacity-75' : 'text-muted' }}" style="font-size: 0.725rem;">Kode: {{ $j['kode'] }}</div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <!-- Search & Filter Bar -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.tujuan_pembelajaran.index') }}" method="GET" class="row g-2 align-items-center">
                <input type="hidden" name="jurusan" value="{{ $selectedJurusan }}">
                <div class="col-md-6 col-12">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="ph ph-magnifying-glass"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Cari teks tujuan pembelajaran..." value="{{ $search }}">
                    </div>
                </div>
                <div class="col-md-4 col-8">
                    <select name="capaian" class="form-select form-select-sm bg-light">
                        <option value="">-- Semua Capaian Pembelajaran --</option>
                        <option value="POS" {{ str_contains($capaian ?? '', 'POS') ? 'selected' : '' }}>CP 1: Sesuai POS Dunia Kerja</option>
                        <option value="belum tuntas" {{ str_contains($capaian ?? '', 'belum tuntas') ? 'selected' : '' }}>CP 2: Kompetensi Baru / Lanjutan</option>
                    </select>
                </div>
                <div class="col-md-2 col-4 d-flex gap-1.5">
                    <button type="submit" class="btn btn-sm btn-primary w-100 rounded-3">
                        <i class="ph ph-funnel me-1"></i> Filter
                    </button>
                    @if($search || $capaian)
                        <a href="{{ route('admin.tujuan_pembelajaran.index', ['jurusan' => $selectedJurusan]) }}" class="btn btn-sm btn-light border rounded-3" title="Reset">
                            <i class="ph ph-arrow-counter-clockwise"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Grouped Content by Capaian Pembelajaran -->
    @if($items->isEmpty())
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
            <div class="text-muted mb-2"><i class="ph ph-folder-open" style="font-size: 3rem;"></i></div>
            <h6 class="fw-bold text-dark">Tidak ada data Tujuan Pembelajaran</h6>
            <p class="text-muted small mb-3">Belum ada data TP yang sesuai dengan filter pencarian ini.</p>
            <div>
                <a href="{{ route('admin.tujuan_pembelajaran.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                    Lihat Semua Jurusan
                </a>
            </div>
        </div>
    @else
        @foreach($items as $namaCapaian => $tpGroup)
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white border-bottom py-3 px-3.5">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                        <div>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 me-2 fw-semibold" style="font-size: 0.75rem;">
                                {{ str_contains($namaCapaian, 'POS') ? 'Capaian 1' : 'Capaian 2' }}
                            </span>
                            <strong class="text-dark" style="font-size: 0.95rem;">{{ $namaCapaian }}</strong>
                        </div>
                        <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill" style="font-size: 0.775rem;">
                            {{ $tpGroup->count() }} Indikator TP
                        </span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                        <thead class="table-light text-muted" style="font-size: 0.775rem; text-transform: uppercase; letter-spacing: 0.5px;">
                            <tr>
                                <th style="width: 55px;" class="text-center">No</th>
                                <th>Tujuan Pembelajaran (TP)</th>
                                <th style="width: 90px;" class="text-center">Tahun</th>
                                <th style="width: 90px;" class="text-center">Status</th>
                                <th style="width: 110px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tpGroup as $tp)
                                <tr>
                                    <td class="text-center fw-bold text-muted">{{ $tp->nomor_urut }}</td>
                                    <td>
                                        <div class="text-dark fw-medium lh-base">{{ $tp->tujuan_pembelajaran }}</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-secondary border">{{ $tp->tahun ?? '2026' }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($tp->status === 'aktif')
                                            <span class="badge bg-success-subtle text-success rounded-pill px-2">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-inline-flex gap-1">
                                            <button type="button" class="btn btn-sm btn-light border p-1 text-primary" title="Edit TP" data-bs-toggle="modal" data-bs-target="#modalEditTp{{ $tp->id }}">
                                                <i class="ph ph-pencil-simple" style="font-size: 15px;"></i>
                                            </button>
                                            <form action="{{ route('admin.tujuan_pembelajaran.destroy', $tp->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Tujuan Pembelajaran ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light border p-1 text-danger" title="Hapus TP">
                                                    <i class="ph ph-trash" style="font-size: 15px;"></i>
                                                </button>
                                            </form>
                                        </div>

                                        <!-- Modal Edit TP -->
                                        <div class="modal fade text-start" id="modalEditTp{{ $tp->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content rounded-4 border-0 shadow">
                                                    <form action="{{ route('admin.tujuan_pembelajaran.update', $tp->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header border-0 pb-0">
                                                            <h5 class="modal-title fw-bold text-dark">
                                                                <i class="ph ph-pencil-simple text-primary me-1"></i> Edit Tujuan Pembelajaran
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label small fw-semibold">Konsentrasi Keahlian</label>
                                                                <input type="text" class="form-control form-control-sm bg-light" value="{{ $tp->konsentrasi_keahlian }} ({{ $tp->kode_jurusan }})" readonly>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label small fw-semibold">Capaian Pembelajaran (CP)</label>
                                                                <textarea name="capaian_pembelajaran" class="form-control form-control-sm" rows="2" required>{{ $tp->capaian_pembelajaran }}</textarea>
                                                            </div>
                                                            <div class="row g-2 mb-3">
                                                                <div class="col-4">
                                                                    <label class="form-label small fw-semibold">Nomor Urut</label>
                                                                    <input type="number" name="nomor_urut" class="form-control form-control-sm" value="{{ $tp->nomor_urut }}" min="1" required>
                                                                </div>
                                                                <div class="col-4">
                                                                    <label class="form-label small fw-semibold">Tahun</label>
                                                                    <input type="text" name="tahun" class="form-control form-control-sm" value="{{ $tp->tahun ?? '2026' }}" required>
                                                                </div>
                                                                <div class="col-4">
                                                                    <label class="form-label small fw-semibold">Status</label>
                                                                    <select name="status" class="form-select form-select-sm">
                                                                        <option value="aktif" {{ $tp->status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                                                        <option value="nonaktif" {{ $tp->status === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label small fw-semibold">Teks Tujuan Pembelajaran (TP)</label>
                                                                <textarea name="tujuan_pembelajaran" class="form-control form-control-sm" rows="3" required>{{ $tp->tujuan_pembelajaran }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0 pt-0">
                                                            <button type="button" class="btn btn-sm btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3.5">Simpan Perubahan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    @endif

</div>

<!-- Modal Tambah TP -->
<div class="modal fade" id="modalTambahTp" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <form action="{{ route('admin.tujuan_pembelajaran.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark">
                        <i class="ph ph-plus-circle text-primary me-1"></i> Tambah Tujuan Pembelajaran
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Konsentrasi Keahlian / Jurusan</label>
                        <select name="kode_jurusan" class="form-select form-select-sm" id="selectJurusanModal" required>
                            @foreach($jurusans as $jur)
                                <option value="{{ $jur->kode_jurusan }}" data-nama="{{ $jur->nama_jurusan }}" data-id="{{ $jur->id }}" {{ $selectedJurusan === $jur->kode_jurusan ? 'selected' : '' }}>
                                    {{ $jur->nama_jurusan }} ({{ $jur->kode_jurusan }})
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="jurusan_id" id="jurusanIdModal" value="{{ $jurusans->firstWhere('kode_jurusan', $selectedJurusan)->id ?? '' }}">
                        <input type="hidden" name="konsentrasi_keahlian" id="konsentrasiKeahlianModal" value="{{ $jurusans->firstWhere('kode_jurusan', $selectedJurusan)->nama_jurusan ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Capaian Pembelajaran (CP)</label>
                        <select name="capaian_pembelajaran" class="form-select form-select-sm" required>
                            <option value="Menerapkan kompetensi teknis pada pekerjaan sesuai POS yang berlaku di dunia kerja">
                                Capaian 1: Menerapkan kompetensi teknis pada pekerjaan sesuai POS yang berlaku di dunia kerja
                            </option>
                            <option value="Menerapkan kompetensi teknis baru/atau kompetensi teknis yang belum tuntas dipelajari sesuai konsentrasi keahlian">
                                Capaian 2: Menerapkan kompetensi teknis baru/atau kompetensi teknis yang belum tuntas dipelajari sesuai konsentrasi keahlian
                            </option>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Nomor Urut</label>
                            <input type="number" name="nomor_urut" class="form-control form-control-sm" value="1" min="1" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Tahun Pelaksanaan</label>
                            <input type="text" name="tahun" class="form-control form-control-sm" value="2026" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Teks Tujuan Pembelajaran (TP)</label>
                        <textarea name="tujuan_pembelajaran" class="form-control form-control-sm" rows="3" placeholder="Contoh: Menganalisis siklus akuntansi..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-sm btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3.5">Tambahkan TP</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectJurusan = document.getElementById('selectJurusanModal');
        const inputId = document.getElementById('jurusanIdModal');
        const inputNama = document.getElementById('konsentrasiKeahlianModal');

        if (selectJurusan) {
            selectJurusan.addEventListener('change', function () {
                const opt = selectJurusan.options[selectJurusan.selectedIndex];
                inputId.value = opt.getAttribute('data-id') || '';
                inputNama.value = opt.getAttribute('data-nama') || '';
            });
        }
    });
</script>
@endsection
