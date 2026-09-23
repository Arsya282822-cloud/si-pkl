@extends('layouts.app')
@section('title', 'Laporan & Rekapitulasi')
@section('topbar_title', 'Laporan & Rekapitulasi PKL')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="pro-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted fw-semibold">Total Siswa PKL</span>
                <div class="p-2 bg-primary bg-opacity-10 text-primary rounded-3">
                    <i class="ph ph-users fs-4"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-0 text-dark">{{ $totalSiswa }}</h3>
            <small class="text-muted">Siswa terdaftar penempatan</small>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="pro-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted fw-semibold">Mitra DUDI</span>
                <div class="p-2 bg-success bg-opacity-10 text-success rounded-3">
                    <i class="ph ph-buildings fs-4"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-0 text-dark">{{ $totalPerusahaan }}</h3>
            <small class="text-muted">Perusahaan tempat PKL</small>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="pro-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted fw-semibold">Sudah Dinilai</span>
                <div class="p-2 bg-info bg-opacity-10 text-info rounded-3">
                    <i class="ph ph-check-circle fs-4"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-0 text-dark">{{ $sudahDinilai }}</h3>
            <small class="text-muted">Siswa telah memperoleh nilai</small>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="pro-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted fw-semibold">Belum Dinilai</span>
                <div class="p-2 bg-warning bg-opacity-10 text-warning rounded-3">
                    <i class="ph ph-clock fs-4"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-0 text-dark">{{ $belumDinilai }}</h3>
            <small class="text-muted">Menunggu penilaian guru</small>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Rekapitulasi Kehadiran -->
    <div class="col-lg-6">
        <div class="card dashboard-card h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                <h5 class="fw-bold mb-0"><i class="ph ph-calendar-check text-primary me-2"></i> Rekap Absensi & Kehadiran</h5>
                <a href="{{ route('admin.export.absensi') }}" class="btn btn-sm btn-outline-success">
                    <i class="ph ph-download-simple me-1"></i> Download CSV
                </a>
            </div>
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <p class="text-muted mb-4">
                    Lihat dan cetak rekapitulasi kehadiran siswa selama masa PKL (jumlah hadir, izin, sakit, alpa, dan persentase kehadiran per siswa).
                </p>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.laporan.absensi') }}" class="btn btn-primary w-100">
                        <i class="ph ph-eye me-1"></i> Buka Rekap Absensi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Rekapitulasi Nilai PKL -->
    <div class="col-lg-6">
        <div class="card dashboard-card h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                <h5 class="fw-bold mb-0"><i class="ph ph-medal text-warning me-2"></i> Rekapitulasi Nilai Akhir PKL</h5>
                <a href="{{ route('admin.export.nilai') }}" class="btn btn-sm btn-outline-success">
                    <i class="ph ph-download-simple me-1"></i> Download CSV
                </a>
            </div>
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <p class="text-muted mb-4">
                    Rekapitulasi lengkap transkrip nilai PKL dari guru pembimbing (nilai sikap, keterampilan, pengetahuan, nilai akhir & predikat).
                </p>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.laporan.nilai') }}" class="btn btn-primary w-100">
                        <i class="ph ph-eye me-1"></i> Buka Rekap Nilai
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
