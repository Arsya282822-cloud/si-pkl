@extends('layouts.app')
@section('title', 'Penilaian PKL Peserta Magang')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: var(--text-main);">Penilaian PKL Mitra Industri (DUDI)</h3>
        <p class="text-muted mb-0" style="font-size: 0.875rem;">
            Evaluasi aspek sikap, keterampilan kerja/teknis, dan pengetahuan praktis siswa di <strong class="text-dark">{{ $perusahaan->nama }}</strong>.
        </p>
    </div>
</div>

<div class="pro-card p-4 border-0 shadow-sm" style="border-radius: 16px;">
    @if($penempatan->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="ph ph-certificate mb-2" style="font-size: 40px; color: #cbd5e1;"></i>
            <h6 class="fw-bold text-dark">Belum Ada Siswa Ditempatkan</h6>
            <p class="small mb-0">Belum ada peserta magang yang tercatat di {{ $perusahaan->nama }}.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Siswa</th>
                        <th>Kelas & Jurusan</th>
                        <th class="text-center">Sikap (30%)</th>
                        <th class="text-center">Keterampilan (50%)</th>
                        <th class="text-center">Pengetahuan (20%)</th>
                        <th class="text-center">Nilai Akhir</th>
                        <th class="text-center">Predikat</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penempatan as $index => $item)
                        @php
                            $siswa = $item->siswa;
                            $nilai = $item->penilaianPkl;
                            $predikat = '-';
                            $badgeClass = 'bg-secondary';
                            if ($nilai) {
                                if ($nilai->nilai_akhir >= 90) { $predikat = 'Sangat Baik (A)'; $badgeClass = 'bg-success'; }
                                elseif ($nilai->nilai_akhir >= 80) { $predikat = 'Baik (B)'; $badgeClass = 'bg-primary'; }
                                elseif ($nilai->nilai_akhir >= 70) { $predikat = 'Cukup (C)'; $badgeClass = 'bg-warning text-dark'; }
                                else { $predikat = 'Kurang (D)'; $badgeClass = 'bg-danger'; }
                            }
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-light-primary text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 34px; height: 34px; font-size: 0.8rem; background: #e0f2fe;">
                                        {{ strtoupper(substr($siswa?->nama ?? 'S', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $siswa?->nama ?? '-' }}</div>
                                        <small class="text-muted">NISN: {{ $siswa?->nisn ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $siswa?->kelas?->nama_kelas ?? '-' }}</div>
                                <small class="text-muted">{{ $siswa?->jurusan?->nama_jurusan ?? '-' }}</small>
                            </td>
                            <td class="text-center">
                                @if($nilai)
                                    <span class="fw-bold text-dark">{{ number_format($nilai->nilai_sikap, 1) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($nilai)
                                    <span class="fw-bold text-dark">{{ number_format($nilai->nilai_keterampilan, 1) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($nilai)
                                    <span class="fw-bold text-dark">{{ number_format($nilai->nilai_pengetahuan, 1) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($nilai)
                                    <span class="badge bg-primary fs-6 px-2.5 py-1">{{ number_format($nilai->nilai_akhir, 1) }}</span>
                                @else
                                    <span class="badge bg-light text-muted border px-2 py-1">Belum Dinilai</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($nilai)
                                    <span class="badge {{ $badgeClass }} px-2 py-1" style="font-size: 0.75rem;">{{ $predikat }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('instruktur.penilaian.create', $item->id) }}" class="btn btn-sm {{ $nilai ? 'btn-outline-primary' : 'btn-primary' }} fw-semibold" style="border-radius: 8px;">
                                    <i class="ph {{ $nilai ? 'ph-pencil-simple' : 'ph-plus-circle' }} me-1"></i>
                                    {{ $nilai ? 'Edit Nilai' : 'Input Nilai' }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
