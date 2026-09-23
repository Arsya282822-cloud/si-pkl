@extends('layouts.app')
@section('title', 'Nilai PKL Saya')
@section('topbar_title', 'Transkrip Nilai PKL')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: var(--text-main);">Transkrip & Evaluasi Nilai PKL</h3>
        <p class="text-muted mb-0" style="font-size: 0.875rem;">Hasil penilaian kompetensi magang oleh Guru Pembimbing Sekolah.</p>
    </div>
</div>

@if(!$penempatan)
    <div class="pro-card p-5 text-center" style="border-radius: 16px;">
        <div class="rounded-circle bg-warning bg-opacity-10 text-warning p-3 d-inline-flex mb-3">
            <i class="ph ph-warning-circle" style="font-size: 42px;"></i>
        </div>
        <h5 class="fw-bold text-dark">Data Penempatan Belum Tersedia</h5>
        <p class="text-muted mb-0">Anda belum memiliki riwayat penempatan PKL aktif.</p>
    </div>
@elseif(!$penilaian)
    <div class="pro-card p-5 text-center" style="border-radius: 16px;">
        <div class="rounded-circle bg-warning bg-opacity-10 text-warning p-3 d-inline-flex mb-3">
            <i class="ph ph-hourglass-high" style="font-size: 42px;"></i>
        </div>
        <h5 class="fw-bold text-dark">Penilaian Belum Diterbitkan</h5>
        <p class="text-muted mb-0" style="max-width: 500px; margin: auto;">
            Guru Pembimbing (<strong>{{ $penempatan->guru?->nama ?: 'Guru Pembimbing' }}</strong>) belum menginput nilai akhir PKL Anda. Nilai dan E-Sertifikat akan muncul otomatis setelah evaluasi selesai.
        </p>
    </div>
@else
    @php
        $nilaiAkhir = $penilaian->nilai_akhir;
        $predikat = 'Kurang';
        $badgeBg = 'danger';
        $statusLulus = 'TIDAK LULUS';
        if ($nilaiAkhir >= 90) {
            $predikat = 'Sangat Baik (A)';
            $badgeBg = 'success';
            $statusLulus = 'LULUS DENGAN PUJIAN';
        } elseif ($nilaiAkhir >= 80) {
            $predikat = 'Baik (B)';
            $badgeBg = 'primary';
            $statusLulus = 'LULUS';
        } elseif ($nilaiAkhir >= 70) {
            $predikat = 'Cukup (C)';
            $badgeBg = 'warning text-dark';
            $statusLulus = 'LULUS';
        }
    @endphp

    <div class="row g-4">
        <!-- Banner Nilai Akhir -->
        <div class="col-lg-4">
            <div class="pro-card text-center p-4 h-100 d-flex flex-column justify-content-between" style="border-radius: 16px;">
                <div class="py-3">
                    <span class="text-muted fw-bold d-block mb-2" style="font-size: 0.8rem; letter-spacing: 1px;">NILAI AKHIR PKL</span>
                    <h1 class="display-3 fw-bold text-primary mb-2">{{ number_format($nilaiAkhir, 1) }}</h1>
                    <span class="badge bg-{{ $badgeBg }} fs-6 px-3 py-1.5 rounded-pill mb-3">{{ $predikat }}</span>
                </div>
                
                <div class="p-3 bg-light rounded-3 w-100 border">
                    <small class="text-muted d-block mb-1">Status Kelulusan PKL</small>
                    <h6 class="fw-bold {{ $nilaiAkhir >= 70 ? 'text-success' : 'text-danger' }} mb-0 d-flex align-items-center justify-content-center gap-1">
                        <i class="ph {{ $nilaiAkhir >= 70 ? 'ph-check-circle' : 'ph-x-circle' }} fs-5"></i> {{ $statusLulus }}
                    </h6>
                </div>
            </div>
        </div>

        <!-- Rincian Aspek Nilai -->
        <div class="col-lg-8">
            <div class="pro-card h-100" style="border-radius: 16px;">
                <div class="p-3 px-4 bg-light border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                        <i class="ph ph-chart-bar text-primary" style="font-size: 20px;"></i>
                        Rincian Komponen Nilai
                    </h6>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('siswa.cetak.rapor') }}" target="_blank" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1.5 shadow-sm" style="border-radius: 8px; font-weight: 600;">
                            <i class="ph ph-file-text"></i> Cetak Rapor PKL
                        </a>
                        <a href="{{ route('siswa.sertifikat') }}" target="_blank" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1.5 shadow-sm" style="border-radius: 8px; font-weight: 600;">
                            <i class="ph ph-certificate"></i> Cetak E-Sertifikat
                        </a>
                        <button onclick="window.print()" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1" style="border-radius: 8px;">
                            <i class="ph ph-printer"></i> Cetak Halaman
                        </button>
                    </div>
                </div>
                <div class="p-4">
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-light" style="font-size: 0.8rem; text-transform: uppercase;">
                                <tr>
                                    <th style="width: 45px;" class="text-center">No</th>
                                    <th>Aspek Penilaian</th>
                                    <th style="width: 130px;" class="text-center">Nilai Angka</th>
                                    <th style="width: 140px;" class="text-center">Kategori</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center fw-bold">1</td>
                                    <td>
                                        <strong class="text-dark">Aspek Sikap & Perilaku (Soft Skills)</strong>
                                        <div class="small text-muted">Kedisiplinan, etos kerja, tanggung jawab, dan adaptasi industri.</div>
                                    </td>
                                    <td class="text-center fw-bold fs-5 text-primary">{{ $penilaian->nilai_sikap }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $penilaian->nilai_sikap >= 80 ? 'success-subtle text-success border border-success-subtle' : 'warning-subtle text-warning border border-warning-subtle' }} px-2.5 py-1" style="border-radius: 6px;">
                                            {{ $penilaian->nilai_sikap >= 80 ? 'Baik' : 'Cukup' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold">2</td>
                                    <td>
                                        <strong class="text-dark">Aspek Keterampilan Kerja (Hard Skills)</strong>
                                        <div class="small text-muted">Penguasaan kompetensi kejuruan, produktivitas, dan hasil praktik.</div>
                                    </td>
                                    <td class="text-center fw-bold fs-5 text-primary">{{ $penilaian->nilai_keterampilan }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $penilaian->nilai_keterampilan >= 80 ? 'success-subtle text-success border border-success-subtle' : 'warning-subtle text-warning border border-warning-subtle' }} px-2.5 py-1" style="border-radius: 6px;">
                                            {{ $penilaian->nilai_keterampilan >= 80 ? 'Baik' : 'Cukup' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold">3</td>
                                    <td>
                                        <strong class="text-dark">Aspek Pengetahuan (Knowledge)</strong>
                                        <div class="small text-muted">Pemahaman alur kerja, logbook harian, dan penyusunan laporan.</div>
                                    </td>
                                    <td class="text-center fw-bold fs-5 text-primary">{{ $penilaian->nilai_pengetahuan }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $penilaian->nilai_pengetahuan >= 80 ? 'success-subtle text-success border border-success-subtle' : 'warning-subtle text-warning border border-warning-subtle' }} px-2.5 py-1" style="border-radius: 6px;">
                                            {{ $penilaian->nilai_pengetahuan >= 80 ? 'Baik' : 'Cukup' }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="p-3 bg-light rounded-3 border">
                        <h6 class="fw-bold mb-1 text-dark d-flex align-items-center gap-1.5">
                            <i class="ph ph-chat-centered-text text-primary"></i> Catatan & Ulasan Guru Pembimbing:
                        </h6>
                        <p class="text-muted mb-0 small" style="line-height: 1.6;">{{ $penilaian->catatan ?: 'Peserta didik telah menyelesaikan kegiatan Praktik Kerja Lapangan dengan baik sesuai standar kurikulum.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

