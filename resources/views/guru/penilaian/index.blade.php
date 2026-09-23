@extends('layouts.app')
@section('title', 'Penilaian PKL')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: var(--text-main);">Penilaian PKL Siswa</h3>
        <p class="text-muted mb-0" style="font-size: 0.875rem;">Input nilai aspek Sikap, Keterampilan, dan Pengetahuan serta cetak E-Sertifikat resmi.</p>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="border-radius: 10px;">
    <i class="ph ph-check-circle me-1 fw-bold"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="pro-card" style="border-radius: 16px;">
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="table-light" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">
                <tr>
                    <th class="ps-4">Siswa Bimbingan</th>
                    <th>Perusahaan Mitra</th>
                    <th class="text-center">Sikap</th>
                    <th class="text-center">Keterampilan</th>
                    <th class="text-center">Pengetahuan</th>
                    <th class="text-center">Nilai Akhir</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penempatan as $p)
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; font-size: 0.85rem;">
                                {{ substr($p->siswa->nama, 0, 1) }}
                            </div>
                            <div>
                                <div class="fw-bold text-dark">{{ $p->siswa->nama }}</div>
                                <small class="text-muted">NIS: {{ $p->siswa->nis }} • {{ $p->siswa->kelas?->nama_kelas ?? '-' }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="fw-semibold text-dark">{{ $p->perusahaan->nama_perusahaan }}</div>
                        <small class="text-muted">{{ $p->perusahaan->alamat ?: 'Pekanbaru' }}</small>
                    </td>
                    <td class="text-center">
                        <span class="fw-semibold">{{ $p->penilaian?->nilai_sikap ?? '-' }}</span>
                    </td>
                    <td class="text-center">
                        <span class="fw-semibold">{{ $p->penilaian?->nilai_keterampilan ?? '-' }}</span>
                    </td>
                    <td class="text-center">
                        <span class="fw-semibold">{{ $p->penilaian?->nilai_pengetahuan ?? '-' }}</span>
                    </td>
                    <td class="text-center">
                        @if($p->penilaian)
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1" style="border-radius: 6px; font-size: 0.875rem; font-weight: 700;">
                                {{ $p->penilaian->nilai_akhir }}
                            </span>
                        @else
                            <span class="badge bg-light text-muted border px-2.5 py-1" style="border-radius: 6px; font-size: 0.75rem;">
                                Belum Dinilai
                            </span>
                        @endif
                    </td>
                    <td class="text-end pe-4">
                        @if($p->penilaian)
                            <div class="d-inline-flex gap-1.5">
                                <a href="{{ route('guru.penilaian.edit', $p) }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1" style="border-radius: 8px;">
                                    <i class="ph ph-pencil-simple"></i> Edit Nilai
                                </a>
                                <a href="{{ route('guru.penilaian.sertifikat', $p) }}" target="_blank" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1" style="border-radius: 8px;" title="Lihat E-Sertifikat Siswa">
                                    <i class="ph ph-certificate"></i> E-Sertifikat
                                </a>
                            </div>
                        @else
                            <a href="{{ route('guru.penilaian.create', $p) }}" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1" style="border-radius: 8px; font-weight: 600;">
                                <i class="ph ph-plus-circle"></i> Beri Nilai
                            </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="ph ph-medal fs-1 d-block mb-2 opacity-50"></i>
                        Belum ada siswa bimbingan yang terdaftar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

