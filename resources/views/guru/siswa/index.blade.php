@extends('layouts.app')
@section('title', 'Peserta Didik Bimbingan')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: var(--text-main);">Daftar Siswa Bimbingan PKL</h3>
        <p class="text-muted mb-0" style="font-size: 0.875rem;">Seluruh peserta didik yang berada di bawah bimbingan dan pengawasan Anda di dunia industri.</p>
    </div>
</div>

<!-- Search & Filter Card -->
<div class="pro-card p-3 mb-4" style="border-radius: 14px;">
    <form method="GET" action="{{ route('guru.siswa.index') }}" class="row g-2 align-items-center">
        <div class="col-md-8">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white border-end-0"><i class="ph ph-magnifying-glass text-muted"></i></span>
                <input type="text" name="q" class="form-control border-start-0" placeholder="Cari nama atau NIS siswa..." value="{{ $q }}">
            </div>
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-sm btn-primary w-100 fw-semibold">
                <i class="ph ph-funnel me-1"></i> Cari Siswa
            </button>
            @if($q)
                <a href="{{ route('guru.siswa.index') }}" class="btn btn-sm btn-light border text-muted">Reset</a>
            @endif
        </div>
    </form>
</div>

<!-- Table Siswa Bimbingan -->
<div class="pro-card" style="border-radius: 16px;">
    <div class="p-0">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="table-light" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">
                    <tr>
                        <th class="ps-4">Siswa Bimbingan</th>
                        <th>Kelas & Jurusan</th>
                        <th>Perusahaan Tempat PKL</th>
                        <th>Kontak / No. HP</th>
                        <th>Jurnal Logbook</th>
                        <th>Status Nilai</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penempatan as $p)
                    @php
                        $jurnalCount = $p->jurnal->count();
                        $jurnalPending = $p->jurnal->where('status_validasi', 'menunggu')->count();
                    @endphp
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="rounded-circle bg-primary bg-opacity-10 text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; font-size: 0.9rem;">
                                    {{ substr($p->siswa->nama, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $p->siswa->nama }}</div>
                                    <small class="text-muted">NIS: {{ $p->siswa->nis }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $p->siswa->kelas?->nama_kelas ?? '-' }}</div>
                            <small class="text-muted">{{ $p->siswa->jurusan?->nama_jurusan ?? '-' }}</small>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $p->perusahaan->nama_perusahaan }}</div>
                            <small class="text-muted"><i class="ph ph-map-pin me-1"></i>{{ $p->perusahaan->alamat ?: 'Pekanbaru' }}</small>
                        </td>
                        <td>
                            @if($p->siswa->no_hp)
                                <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $p->siswa->no_hp)) }}" target="_blank" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1" style="border-radius: 6px; font-size: 0.75rem;">
                                    <i class="ph ph-whatsapp-logo" style="font-size: 14px;"></i> {{ $p->siswa->no_hp }}
                                </a>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="small fw-semibold">{{ $jurnalCount }} entri</span>
                            @if($jurnalPending > 0)
                                <span class="badge bg-warning text-dark ms-1" style="font-size: 0.7rem; border-radius: 6px;">{{ $jurnalPending }} menunggu</span>
                            @endif
                        </td>
                        <td>
                            @if($p->penilaian)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1" style="border-radius: 6px; font-weight: 700;">
                                    {{ $p->penilaian->nilai_akhir }}
                                </span>
                            @else
                                <span class="badge bg-light text-muted border px-2 py-0.5" style="border-radius: 6px; font-size: 0.75rem;">
                                    Belum Dinilai
                                </span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('guru.validasi.index', ['q' => $p->siswa->nama]) }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1" style="border-radius: 6px;" title="Lihat Jurnal">
                                    <i class="ph ph-book-open"></i> Jurnal
                                </a>
                                @if($p->penilaian)
                                    <a href="{{ route('guru.penilaian.edit', $p) }}" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1" style="border-radius: 6px;" title="Edit Nilai">
                                        <i class="ph ph-pencil-simple"></i> Nilai
                                    </a>
                                @else
                                    <a href="{{ route('guru.penilaian.create', $p) }}" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1" style="border-radius: 6px; font-weight: 600;" title="Beri Nilai">
                                        <i class="ph ph-medal"></i> Nilai
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="ph ph-users fs-1 d-block mb-2 opacity-50"></i>
                            Tidak ada siswa bimbingan yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $penempatan->links() }}
</div>
@endsection
