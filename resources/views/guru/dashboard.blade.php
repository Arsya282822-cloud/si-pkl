@extends('layouts.app')
@section('title', 'Dashboard Guru Pembimbing')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: var(--text-main);">Dashboard Guru Pembimbing</h3>
        <p class="text-muted mb-0" style="font-size: 0.875rem;">Selamat datang, <strong class="text-dark">{{ $guru?->nama ?? Auth::user()->name }}</strong>! Kelola bimbingan, verifikasi jurnal, dan pantau aktivitas siswa di industri.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('guru.monitoring.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-1.5 shadow-sm" style="border-radius: 10px; font-weight: 600;">
            <i class="ph ph-plus-circle" style="font-size: 18px;"></i>
            <span>Catat Supervisi</span>
        </a>
        <a href="{{ route('guru.pantauan_map.index') }}" class="btn btn-outline-primary d-inline-flex align-items-center gap-1.5" style="border-radius: 10px; font-weight: 600;">
            <i class="ph ph-map-pin-line" style="font-size: 18px;"></i>
            <span>Peta Live</span>
        </a>
    </div>
</div>

<!-- Announcements Banner for Guru -->
@if(isset($pengumumans) && $pengumumans->isNotEmpty())
    <div class="mb-4">
        @foreach($pengumumans as $p)
            @php
                $alertBg = match($p->kategori) {
                    'penting' => '#fef2f2',
                    'jadwal' => '#fefce8',
                    'peringatan' => '#fff1f2',
                    default => '#f0f9ff',
                };
                $borderCol = match($p->kategori) {
                    'penting' => '#ef4444',
                    'jadwal' => '#ca8a04',
                    'peringatan' => '#e11d48',
                    default => '#0284c7',
                };
                $badgeBg = match($p->kategori) {
                    'penting' => 'danger',
                    'jadwal' => 'warning text-dark',
                    'peringatan' => 'danger',
                    default => 'info text-white',
                };
            @endphp
            <div class="pro-card p-3 p-md-4 mb-3 border-0 shadow-sm" style="background-color: {{ $alertBg }}; border-left: 6px solid {{ $borderCol }} !important; border-radius: 14px;">
                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="badge bg-{{ $badgeBg }} px-2.5 py-1 text-uppercase" style="font-size: 0.7rem; font-weight: 700; border-radius: 6px;">
                            {{ $p->kategori }}
                        </span>
                        @if($p->is_pinned)
                            <span class="badge bg-dark text-white px-2 py-1" style="font-size: 0.7rem; border-radius: 6px;">
                                <i class="ph ph-push-pin me-1"></i> Disematkan
                            </span>
                        @endif
                        <h6 class="fw-bold mb-0 text-dark">{{ $p->judul }}</h6>
                    </div>
                    <small class="text-muted" style="font-size: 0.75rem;">{{ $p->created_at->diffForHumans() }}</small>
                </div>
                <p class="small text-dark mb-0" style="white-space: pre-line; line-height: 1.6;">{{ $p->konten }}</p>
                @if($p->file_lampiran)
                    <div class="mt-2 pt-2 border-top" style="border-color: rgba(0,0,0,0.08) !important;">
                        <a href="{{ asset($p->file_lampiran) }}" target="_blank" class="btn btn-sm btn-light border px-2.5 py-1 text-primary fw-semibold d-inline-flex align-items-center gap-1.5" style="font-size: 0.8rem; border-radius: 6px;">
                            <i class="ph ph-file-arrow-down"></i> Unduh Lampiran Berkas
                        </a>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger mb-4" style="border-radius: 10px;">{{ session('error') }}</div>
@endif

@if(!$guru)
    <div class="pro-card p-5 text-center">
        <div class="rounded-circle bg-warning bg-opacity-10 p-4 d-inline-flex mb-3">
            <i class="ph ph-warning text-warning" style="font-size: 48px;"></i>
        </div>
        <h5 class="fw-bold text-dark">Data Guru Belum Terhubung</h5>
        <p class="text-muted mb-0">Akun Anda belum tertaut dengan data guru di sistem. Silakan hubungi Pokja PKL / Administrator.</p>
    </div>
@else
    {{-- Presensi Hari Ini Quick Bar --}}
    <div class="pro-card p-3 mb-4 bg-white" style="border-radius: 14px;">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div class="d-flex align-items-center gap-2">
                <i class="ph ph-calendar-check text-primary fs-5"></i>
                <div>
                    <h6 class="fw-bold mb-0 text-dark">Presensi Siswa Hari Ini ({{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }})</h6>
                    <small class="text-muted">Status kehadiran siswa bimbingan yang tercatat di sistem</small>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill fw-semibold">
                    <i class="ph ph-check me-1"></i> Hadir: {{ $absensiHariIni['hadir'] }}
                </span>
                <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-1.5 rounded-pill fw-semibold">
                    <i class="ph ph-envelope me-1"></i> Izin: {{ $absensiHariIni['izin'] }}
                </span>
                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-1.5 rounded-pill fw-semibold">
                    <i class="ph ph-first-aid me-1"></i> Sakit: {{ $absensiHariIni['sakit'] }}
                </span>
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1.5 rounded-pill fw-semibold">
                    <i class="ph ph-x me-1"></i> Belum Absen/Alpha: {{ max(0, $penempatan->count() - ($absensiHariIni['hadir'] + $absensiHariIni['izin'] + $absensiHariIni['sakit'])) }}
                </span>
            </div>
        </div>
    </div>

    {{-- Laporan Khusus / Catatan dari Instruktur DUDI --}}
    @if(isset($laporanDudi) && $laporanDudi->isNotEmpty())
        <div class="pro-card p-4 mb-4 border-0 shadow-sm" style="border-radius: 16px; background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border-left: 6px solid #f59e0b !important;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-warning text-dark p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                        <i class="ph ph-megaphone" style="font-size: 20px;"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Laporan & Catatan dari Instruktur DUDI</h5>
                        <small class="text-muted">Instruktur industri mengirimkan laporan khusus atau catatan penting terkait siswa bimbingan</small>
                    </div>
                </div>
                <span class="badge bg-danger rounded-pill px-3 py-1.5 fw-semibold">
                    {{ $laporanDudi->count() }} Laporan Masuk
                </span>
            </div>

            <div class="row g-3">
                @foreach($laporanDudi as $lap)
                    @php
                        $isReport = str_contains($lap->komentar_guru ?? '', '[LAPORAN INSTRUKTUR DUDI]');
                        $cleanMsg = preg_replace('/^(\[LAPORAN INSTRUKTUR DUDI\]: |\[Catatan Instruktur\]: |\[Instruktur DUDI\]: )/', '', $lap->komentar_guru ?? '');
                    @endphp
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100 p-3 bg-white" style="border-radius: 12px;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">{{ $lap->penempatan?->siswa?->nama ?? 'Siswa' }}</h6>
                                    <small class="text-muted">{{ $lap->penempatan?->perusahaan?->nama_perusahaan }}</small>
                                </div>
                                <span class="badge {{ $isReport ? 'bg-danger text-white' : ($lap->status_validasi === 'ditolak' ? 'bg-danger text-white' : 'bg-warning text-dark') }} px-2 py-1" style="font-size: 0.72rem; border-radius: 6px;">
                                    {{ $isReport ? 'Laporan DUDI' : ($lap->status_validasi === 'ditolak' ? 'Revisi Jurnal' : 'Catatan DUDI') }}
                                </span>
                            </div>
                            <p class="small text-dark mb-2 bg-light p-2.5 rounded-3" style="line-height: 1.5;">
                                <i class="ph ph-quotes me-1 text-muted"></i>
                                {{ $cleanMsg ?: 'Jurnal ditolak oleh instruktur industri.' }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top">
                                <small class="text-muted">
                                    <i class="ph ph-calendar me-1"></i> {{ \Carbon\Carbon::parse($lap->tanggal)->isoFormat('D MMM Y') }}
                                </small>
                                <a href="{{ route('guru.validasi.show', $lap->id) }}" class="btn btn-sm btn-outline-primary px-2.5 py-1" style="border-radius: 6px; font-size: 0.8rem; font-weight: 600;">
                                    <i class="ph ph-eye me-1"></i> Buka Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Summary Metric Cards --}}
    @php
        $monitoringCount = \App\Models\Monitoring::where('guru_id', $guru->id)->count();
        $sudahDinilaiCount = $penempatan->filter(fn($p) => $p->penilaian !== null)->count();
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="pro-card p-3 p-md-4 h-100" style="border-radius: 16px;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-primary bg-opacity-10 p-3 text-primary d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                        <i class="ph ph-users" style="font-size: 28px;"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Siswa Bimbingan</span>
                        <h3 class="fw-bold mb-0 text-dark">{{ $penempatan->count() }} <small class="fs-6 fw-normal text-muted">Siswa</small></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="pro-card p-3 p-md-4 h-100" style="border-radius: 16px;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-info bg-opacity-10 p-3 text-info d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                        <i class="ph ph-buildings" style="font-size: 28px;"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Perusahaan Mitra</span>
                        <h3 class="fw-bold mb-0 text-dark">{{ $grouped->count() }} <small class="fs-6 fw-normal text-muted">DUDI</small></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <a href="{{ route('guru.validasi.index') }}" class="text-decoration-none">
                <div class="pro-card p-3 p-md-4 h-100" style="border-radius: 16px;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-success bg-opacity-10 p-3 text-success d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                            <i class="ph ph-check-square-offset" style="font-size: 28px;"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Tervalidasi DUDI</span>
                            <div class="d-flex align-items-center gap-2">
                                <h3 class="fw-bold mb-0 text-success">{{ $statsJurnal['disetujui'] }} <small class="fs-6 fw-normal text-muted">/ {{ $statsJurnal['total'] }}</small></h3>
                            </div>
                            <small class="text-muted" style="font-size: 0.72rem;">{{ $statsJurnal['menunggu'] }} Menunggu review DUDI</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="pro-card p-3 p-md-4 h-100" style="border-radius: 16px;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-warning bg-opacity-10 p-3 text-warning d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                        <i class="ph ph-medal" style="font-size: 28px;"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Sudah Dinilai</span>
                        <h3 class="fw-bold mb-0 text-dark">{{ $sudahDinilaiCount }} / {{ $penempatan->count() }}</h3>
                        <small class="text-muted" style="font-size: 0.72rem;">Penilaian Akhir Guru</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Action Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <a href="{{ route('guru.validasi.index') }}" class="text-decoration-none">
                <div class="pro-card p-3 h-100 d-flex align-items-center gap-3 bg-white" style="border-radius: 12px;">
                    <div class="rounded-2 bg-primary bg-opacity-10 p-2 text-primary">
                        <i class="ph ph-book-open" style="font-size: 24px;"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;">Supervisi Jurnal</h6>
                        <small class="text-muted">Pantau jurnal tervalidasi DUDI</small>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('guru.monitoring.index') }}" class="text-decoration-none">
                <div class="pro-card p-3 h-100 d-flex align-items-center gap-3 bg-white" style="border-radius: 12px;">
                    <div class="rounded-2 bg-info bg-opacity-10 p-2 text-info">
                        <i class="ph ph-camera" style="font-size: 24px;"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;">Monitoring & BA</h6>
                        <small class="text-muted">Riwayat & cetak berita acara</small>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('guru.penilaian.index') }}" class="text-decoration-none">
                <div class="pro-card p-3 h-100 d-flex align-items-center gap-3 bg-white" style="border-radius: 12px;">
                    <div class="rounded-2 bg-success bg-opacity-10 p-2 text-success">
                        <i class="ph ph-chart-line-up" style="font-size: 24px;"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;">Penilaian Akhir</h6>
                        <small class="text-muted">Input skor & sertifikat</small>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('guru.pantauan_map.index') }}" class="text-decoration-none">
                <div class="pro-card p-3 h-100 d-flex align-items-center gap-3 bg-white" style="border-radius: 12px;">
                    <div class="rounded-2 bg-danger bg-opacity-10 p-2 text-danger">
                        <i class="ph ph-map-pin" style="font-size: 24px;"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;">Peta Siswa Live</h6>
                        <small class="text-muted">Pantau titik presensi hari ini</small>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- List siswa per perusahaan --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 d-flex align-items-center gap-2" style="color: var(--text-main);">
            <i class="ph ph-tree-structure text-primary" style="font-size: 22px;"></i>
            Daftar Siswa Bimbingan per Perusahaan Mitra
        </h5>
        <span class="badge bg-light text-muted border px-3 py-1.5" style="border-radius: 20px; font-weight: 600;">
            Total: {{ $penempatan->count() }} Siswa dalam {{ $grouped->count() }} Industri
        </span>
    </div>

    @forelse($grouped as $perusahaanId => $items)
        <div class="pro-card mb-4" style="border-radius: 16px;">
            <div class="p-3 px-4 bg-light border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-2 bg-primary text-white p-1.5 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="ph ph-buildings" style="font-size: 18px;"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">{{ $items->first()->perusahaan->nama_perusahaan }}</h6>
                        <small class="text-muted"><i class="ph ph-map-pin me-1"></i>{{ $items->first()->perusahaan->alamat ?: 'Pekanbaru' }}</small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1.5 rounded-pill" style="font-weight: 600;">
                        {{ $items->count() }} Siswa Bimbingan
                    </span>
                    <a href="{{ route('guru.monitoring.create', ['perusahaan_id' => $perusahaanId]) }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1" style="border-radius: 8px;">
                        <i class="ph ph-camera"></i> Catat Supervisi
                    </a>
                </div>
            </div>
            <div class="p-0">
                <div class="table-responsive">
                    <table class="table align-middle table-hover mb-0">
                        <thead class="table-light" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">
                            <tr>
                                <th class="ps-4">Siswa</th>
                                <th>Kelas & Jurusan</th>
                                <th>Periode PKL</th>
                                <th>Jurnal Harian (DUDI)</th>
                                <th>Nilai Akhir</th>
                                <th class="text-end pe-4">Aksi Cepat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $p)
                            @php
                                $jurnalTotal = $p->jurnal()->count();
                                $jurnalDisetujui = $p->jurnal()->where('status_validasi', 'disetujui')->count();
                                $jurnalPending = $p->jurnal()->where('status_validasi', 'menunggu')->count();
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; font-size: 0.85rem;">
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
                                    <span class="badge bg-light text-dark border px-2.5 py-1" style="border-radius: 6px;">
                                        {{ $p->periodePkl?->nama_periode ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <div class="d-flex align-items-center gap-1.5">
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5" style="font-size: 0.72rem;">
                                                {{ $jurnalDisetujui }} Disetujui
                                            </span>
                                            @if($jurnalPending > 0)
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-0.5" style="font-size: 0.72rem;">
                                                    {{ $jurnalPending }} Review DUDI
                                                </span>
                                            @endif
                                        </div>
                                        <small class="text-muted" style="font-size: 0.75rem;">Total: {{ $jurnalTotal }} catatan</small>
                                    </div>
                                </td>
                                <td>
                                    @if($p->penilaian)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1" style="border-radius: 6px; font-size: 0.85rem; font-weight: 700;">
                                            {{ $p->penilaian->nilai_akhir }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary px-2.5 py-1" style="border-radius: 6px; font-size: 0.75rem;">
                                            Belum Dinilai
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex gap-1.5">
                                        <a href="{{ route('guru.validasi.index', ['q' => $p->siswa->nama]) }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1" style="border-radius: 8px;" title="Lihat Jurnal Siswa">
                                            <i class="ph ph-book-open"></i> Jurnal
                                        </a>
                                        @if($p->penilaian)
                                            <a href="{{ route('guru.penilaian.edit', $p) }}" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1" style="border-radius: 8px;" title="Edit Nilai">
                                                <i class="ph ph-pencil-simple"></i> Nilai
                                            </a>
                                        @else
                                            <a href="{{ route('guru.penilaian.create', $p) }}" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1" style="border-radius: 8px; font-weight: 600;" title="Beri Nilai">
                                                <i class="ph ph-medal"></i> Nilai
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @empty
        <div class="pro-card p-5 text-center text-muted" style="border-radius: 16px;">
            <i class="ph ph-folder-open fs-1 d-block mb-2 opacity-50"></i>
            <h6 class="fw-bold text-dark">Belum Ada Siswa Bimbingan</h6>
            <p class="small text-muted mb-0">Belum ada siswa yang ditempatkan di bawah bimbingan Anda untuk periode saat ini.</p>
        </div>
    @endforelse
@endif
@endsection

