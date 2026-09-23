@extends('layouts.app')
@section('title', 'Dashboard Instruktur DUDI')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge bg-primary px-2.5 py-1" style="font-size: 0.75rem; border-radius: 6px;">
                <i class="ph ph-buildings me-1"></i> {{ $perusahaan->nama }}
            </span>
            @if($pembimbing->jabatan)
                <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 0.75rem; border-radius: 6px;">
                    {{ $pembimbing->jabatan }}
                </span>
            @endif
        </div>
        <h3 class="fw-bold mb-1" style="color: var(--text-main);">Dashboard Instruktur Lapangan</h3>
        <p class="text-muted mb-0" style="font-size: 0.875rem;">
            Selamat datang, <strong class="text-dark">{{ $pembimbing->nama ?? Auth::user()->name }}</strong>! Kelola kehadiran, validasi jurnal harian, dan evaluasi nilai peserta magang di perusahaan Anda.
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('instruktur.jurnal.index') }}" class="btn btn-primary d-inline-flex align-items-center gap-1.5 shadow-sm" style="border-radius: 10px; font-weight: 600;">
            <i class="ph ph-check-circle" style="font-size: 18px;"></i>
            <span>Validasi Jurnal ({{ $jurnalMenunggu }})</span>
        </a>
        <a href="{{ route('instruktur.penilaian.index') }}" class="btn btn-outline-primary d-inline-flex align-items-center gap-1.5" style="border-radius: 10px; font-weight: 600;">
            <i class="ph ph-list-checks" style="font-size: 18px;"></i>
            <span>Input Nilai DUDI</span>
        </a>
    </div>
</div>

<!-- Announcements Banner -->
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
            </div>
        @endforeach
    </div>
@endif

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="pro-card p-3 d-flex align-items-center gap-3 border-0 shadow-sm" style="border-radius: 14px; background: #ffffff;">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #e0f2fe; color: #0284c7;">
                <i class="ph ph-users-three" style="font-size: 26px;"></i>
            </div>
            <div>
                <p class="text-muted small mb-0 fw-medium">Siswa Ditempatkan</p>
                <h4 class="fw-bold mb-0" style="color: #0f172a;">{{ $totalSiswa }} <span class="fs-6 fw-normal text-muted">Siswa</span></h4>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="pro-card p-3 d-flex align-items-center gap-3 border-0 shadow-sm" style="border-radius: 14px; background: #ffffff;">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #dcfce7; color: #16a34a;">
                <i class="ph ph-calendar-check" style="font-size: 26px;"></i>
            </div>
            <div>
                <p class="text-muted small mb-0 fw-medium">Hadir Hari Ini</p>
                <h4 class="fw-bold mb-0" style="color: #0f172a;">{{ $totalHadirHariIni }} <span class="fs-6 fw-normal text-muted">/ {{ $totalSiswa }}</span></h4>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="pro-card p-3 d-flex align-items-center gap-3 border-0 shadow-sm" style="border-radius: 14px; background: #ffffff;">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fef3c7; color: #d97706;">
                <i class="ph ph-book-open-text" style="font-size: 26px;"></i>
            </div>
            <div>
                <p class="text-muted small mb-0 fw-medium">Jurnal Butuh Validasi</p>
                <h4 class="fw-bold mb-0 {{ $jurnalMenunggu > 0 ? 'text-warning' : '' }}">{{ $jurnalMenunggu }} <span class="fs-6 fw-normal text-muted">Laporan</span></h4>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="pro-card p-3 d-flex align-items-center gap-3 border-0 shadow-sm" style="border-radius: 14px; background: #ffffff;">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #ede9fe; color: #7c3aed;">
                <i class="ph ph-certificate" style="font-size: 26px;"></i>
            </div>
            <div>
                <p class="text-muted small mb-0 fw-medium">Siswa Telah Dinilai</p>
                <h4 class="fw-bold mb-0" style="color: #0f172a;">{{ $totalDinilai }} <span class="fs-6 fw-normal text-muted">/ {{ $totalSiswa }}</span></h4>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Presensi Hari Ini -->
    <div class="col-lg-6">
        <div class="pro-card p-4 h-100 border-0 shadow-sm" style="border-radius: 16px;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 rounded bg-light-primary text-primary" style="background: #e0f2fe;">
                        <i class="ph ph-clock-user" style="font-size: 20px;"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Presensi Siswa Hari Ini</h5>
                        <small class="text-muted">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</small>
                    </div>
                </div>
                <a href="{{ route('instruktur.absensi.index') }}" class="btn btn-sm btn-light border text-primary fw-semibold" style="border-radius: 8px;">
                    Lihat Semua
                </a>
            </div>

            @if($penempatans->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="ph ph-user-minus mb-2" style="font-size: 36px; color: #cbd5e1;"></i>
                    <p class="mb-0 small">Belum ada siswa yang ditempatkan di {{ $perusahaan->nama }}.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                        <thead class="table-light">
                            <tr>
                                <th>Siswa</th>
                                <th>Status</th>
                                <th>Jam Masuk</th>
                                <th>Foto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($penempatans as $penempatan)
                                @php
                                    $siswa = $penempatan->siswa;
                                    $absen = $todayAbsensi->firstWhere('penempatan_id', $penempatan->id) ?? ($siswa ? $todayAbsensi->firstWhere('siswa_id', $siswa->id) : null);
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center fw-bold text-secondary" style="width: 34px; height: 34px; font-size: 0.8rem; background: #f1f5f9;">
                                                {{ strtoupper(substr($siswa?->nama ?? 'S', 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-dark">{{ $siswa?->nama ?? '-' }}</div>
                                                <small class="text-muted">{{ $siswa?->kelas?->nama_kelas ?? $siswa?->jurusan?->nama_jurusan }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if(!$absen)
                                            <span class="badge bg-secondary-subtle text-secondary px-2 py-1" style="background: #f1f5f9; font-size: 0.75rem;">Belum Absen</span>
                                        @elseif($absen->status === 'hadir')
                                            <span class="badge bg-success px-2 py-1" style="font-size: 0.75rem;">Hadir</span>
                                        @elseif($absen->status === 'izin')
                                            <span class="badge bg-warning text-dark px-2 py-1" style="font-size: 0.75rem;">Izin</span>
                                        @elseif($absen->status === 'sakit')
                                            <span class="badge bg-info text-white px-2 py-1" style="font-size: 0.75rem;">Sakit</span>
                                        @else
                                            <span class="badge bg-danger px-2 py-1" style="font-size: 0.75rem;">Alpha</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($absen && $absen->jam_masuk)
                                            <span class="fw-semibold">{{ \Carbon\Carbon::parse($absen->jam_masuk)->format('H:i') }}</span>
                                            @if($absen->jam_pulang)
                                                <small class="text-muted"> - {{ \Carbon\Carbon::parse($absen->jam_pulang)->format('H:i') }}</small>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($absen && $absen->foto_masuk)
                                            <a href="{{ asset('storage/' . $absen->foto_masuk) }}" target="_blank" class="badge bg-light text-primary border" style="text-decoration: none;">
                                                <i class="ph ph-image me-0.5"></i> Bukti
                                            </a>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Jurnal Terbaru Menunggu Validasi -->
    <div class="col-lg-6">
        <div class="pro-card p-4 h-100 border-0 shadow-sm" style="border-radius: 16px;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 rounded bg-light-warning text-warning" style="background: #fef3c7;">
                        <i class="ph ph-book-bookmark" style="font-size: 20px;"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Jurnal Terbaru</h5>
                        <small class="text-muted">Aktivitas harian siswa di industri</small>
                    </div>
                </div>
                <a href="{{ route('instruktur.jurnal.index') }}" class="btn btn-sm btn-light border text-primary fw-semibold" style="border-radius: 8px;">
                    Kelola Jurnal
                </a>
            </div>

            @if($recentJurnals->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="ph ph-check-circle mb-2" style="font-size: 36px; color: #10b981;"></i>
                    <p class="mb-0 small">Belum ada jurnal baru yang dikirimkan oleh siswa.</p>
                </div>
            @else
                <div class="d-flex flex-column gap-3">
                    @foreach($recentJurnals as $jurnal)
                        @php
                            $siswaJurnal = $jurnal->penempatan?->siswa;
                        @endphp
                        <div class="p-3 border rounded-3 bg-light-subtle" style="background: #fafafa; border-radius: 12px !important;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 0.75rem;">
                                        {{ strtoupper(substr($siswaJurnal?->nama ?? 'S', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 0.875rem;">{{ $siswaJurnal?->nama ?? 'Siswa' }}</div>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($jurnal->tanggal)->isoFormat('dddd, D MMMM Y') }}</small>
                                    </div>
                                </div>
                                <div>
                                    @if($jurnal->status_validasi === 'disetujui')
                                        <span class="badge bg-success px-2 py-1" style="font-size: 0.7rem;">Disetujui</span>
                                    @elseif($jurnal->status_validasi === 'ditolak')
                                        <span class="badge bg-danger px-2 py-1" style="font-size: 0.7rem;">Ditolak</span>
                                    @else
                                        <span class="badge bg-warning text-dark px-2 py-1" style="font-size: 0.7rem;">Menunggu</span>
                                    @endif
                                </div>
                            </div>
                            <p class="text-secondary small mb-2 text-truncate" style="max-height: 40px;">
                                {{ $jurnal->ringkasan_kegiatan ?? Str::limit(strip_tags($jurnal->kegiatan), 120) }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                <span class="text-muted" style="font-size: 0.75rem;">
                                    <i class="ph ph-clock me-1"></i> {{ $jurnal->created_at->diffForHumans() }}
                                </span>
                                <div class="d-flex gap-1.5">
                                    <a href="{{ route('instruktur.jurnal.show', $jurnal->id) }}" class="btn btn-sm btn-outline-secondary py-0.5 px-2" style="font-size: 0.75rem; border-radius: 6px;">
                                        Detail
                                    </a>
                                    @if($jurnal->status_validasi === 'menunggu')
                                        <form action="{{ route('instruktur.jurnal.validasi', $jurnal->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status_validasi" value="disetujui">
                                            <button type="submit" class="btn btn-sm btn-success py-0.5 px-2 text-white" style="font-size: 0.75rem; border-radius: 6px;">
                                                <i class="ph ph-check"></i> Setujui
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
