@extends('layouts.app')
@section('title', 'Dashboard Overview')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container-fluid px-0">

    <!-- Clean Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1" style="letter-spacing: -0.5px; color: var(--text-main);">Dashboard Overview</h3>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">
                Monitoring penempatan, presensi realtime, jurnal harian, dan statistik kemitraan PKL.
            </p>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="{{ route('admin.backup.database') }}" class="btn btn-outline-secondary px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1.5" style="border-radius: 10px; font-size: 0.85rem;" title="Download Cadangan Database">
                <i class="ph ph-database" style="font-size: 18px;"></i>
                <span>Backup DB</span>
            </a>
            <a href="{{ route('admin.dokumen.index') }}" class="btn btn-primary px-3.5 py-2 fw-semibold d-inline-flex align-items-center gap-2" style="border-radius: 10px; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);">
                <i class="ph ph-printer" style="font-size: 20px;"></i>
                <span>Pusat Cetak Dokumen</span>
            </a>
        </div>
    </div>

    <!-- Stat Metric Cards -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Total Siswa -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="pro-card p-3 d-flex align-items-center gap-3" style="border-radius: 16px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    <i class="ph ph-users"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Total Siswa</div>
                    <div class="fw-bold fs-4" style="color: var(--text-main);">{{ $stats['total_siswa'] }}</div>
                    <div class="text-muted" style="font-size: 0.75rem;">
                        <span class="text-success fw-semibold">{{ $stats['total_penempatan'] }}</span> ditempatkan
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Guru Pembimbing -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="pro-card p-3 d-flex align-items-center gap-3" style="border-radius: 16px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    <i class="ph ph-graduation-cap"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Guru Pembimbing</div>
                    <div class="fw-bold fs-4" style="color: var(--text-main);">{{ $stats['total_guru'] }}</div>
                    <div class="text-muted" style="font-size: 0.75rem;">Aktif mendampingi</div>
                </div>
            </div>
        </div>

        <!-- Card 3: Mitra Perusahaan -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="pro-card p-3 d-flex align-items-center gap-3" style="border-radius: 16px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #faf5ff; color: #9333ea; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    <i class="ph ph-buildings"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Mitra DUDI</div>
                    <div class="fw-bold fs-4" style="color: var(--text-main);">{{ $stats['total_perusahaan'] }}</div>
                    <div class="text-muted" style="font-size: 0.75rem;">Perusahaan mitra PKL</div>
                </div>
            </div>
        </div>

        <!-- Card 4: Dokumen PKS -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="pro-card p-3 d-flex align-items-center gap-3" style="border-radius: 16px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    <i class="ph ph-handshake"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Kerja Sama (PKS)</div>
                    <div class="fw-bold fs-4" style="color: var(--text-main);">{{ $stats['total_pks'] }}</div>
                    <div class="text-muted" style="font-size: 0.75rem;">MOU & PKS tercatat</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics & Charts Row -->
    <div class="row g-4 mb-4">
        <!-- Chart 1: Persebaran Siswa per Jurusan -->
        <div class="col-lg-5">
            <div class="pro-card p-4 h-100 d-flex flex-column justify-content-between" style="border-radius: 16px;">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0" style="color: var(--text-main); font-size: 1rem;">
                            <i class="ph ph-chart-pie me-1 text-primary"></i> Persebaran Siswa per Jurusan
                        </h5>
                    </div>
                    <p class="text-muted" style="font-size: 0.8rem;">Distribusi jumlah peserta didik di setiap konsentrasi keahlian.</p>
                </div>
                <div style="position: relative; height: 240px;" class="d-flex justify-content-center align-items-center">
                    <canvas id="jurusanChart"></canvas>
                </div>
                <div class="pt-2 border-top text-center text-muted" style="font-size: 0.75rem;">
                    Total {{ $stats['total_siswa'] }} Siswa Terdaftar
                </div>
            </div>
        </div>

        <!-- Chart 2: Top Mitra Perusahaan -->
        <div class="col-lg-7">
            <div class="pro-card p-4 h-100 d-flex flex-column justify-content-between" style="border-radius: 16px;">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0" style="color: var(--text-main); font-size: 1rem;">
                            <i class="ph ph-chart-bar me-1 text-primary"></i> Top 5 Mitra Industri (DUDI)
                        </h5>
                        <span class="badge bg-primary-subtle text-primary">Kemitraan Teraktif</span>
                    </div>
                    <p class="text-muted" style="font-size: 0.8rem;">Perusahaan yang menerima penempatan siswa terbanyak pada periode ini.</p>
                </div>
                <div style="position: relative; height: 240px;">
                    <canvas id="perusahaanChart"></canvas>
                </div>
                <div class="pt-2 border-top d-flex justify-content-between align-items-center" style="font-size: 0.75rem;">
                    <span class="text-muted">Diperbarui secara real-time</span>
                    <a href="{{ route('admin.perusahaan.index') }}" class="text-primary text-decoration-none fw-semibold">Kelola Semua Mitra →</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Row 2: Tren Presensi Kehadiran 7 Hari Terakhir -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="pro-card p-4" style="border-radius: 16px;">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
                    <div>
                        <h5 class="fw-bold mb-1" style="color: var(--text-main); font-size: 1.05rem;">
                            <i class="ph ph-chart-line-up me-1 text-primary"></i> Tren Presensi Siswa PKL (7 Hari Terakhir)
                        </h5>
                        <p class="text-muted mb-0" style="font-size: 0.825rem;">Monitoring harian kedisiplinan dan status kehadiran siswa di seluruh industri mitra.</p>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center gap-1.5" style="font-size: 0.8rem;">
                            <span style="width: 12px; height: 12px; border-radius: 3px; background: #10b981; display: inline-block;"></span>
                            <span class="text-muted">Hadir ({{ array_sum($presensiHadir) }})</span>
                        </div>
                        <div class="d-flex align-items-center gap-1.5" style="font-size: 0.8rem;">
                            <span style="width: 12px; height: 12px; border-radius: 3px; background: #0ea5e9; display: inline-block;"></span>
                            <span class="text-muted">Izin ({{ array_sum($presensiIzin) }})</span>
                        </div>
                        <div class="d-flex align-items-center gap-1.5" style="font-size: 0.8rem;">
                            <span style="width: 12px; height: 12px; border-radius: 3px; background: #f59e0b; display: inline-block;"></span>
                            <span class="text-muted">Sakit ({{ array_sum($presensiSakit) }})</span>
                        </div>
                    </div>
                </div>
                <div style="position: relative; height: 260px;">
                    <canvas id="presensiChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Period & Recent Activities Row -->
    <div class="row g-4 mb-4">
        <!-- Periode PKL Berjalan -->
        <div class="col-lg-4">
            <div class="pro-card h-100 p-4" style="border-radius: 16px;">
                <h5 class="fw-bold mb-3" style="font-size: 1rem; color: var(--text-main);">
                    <i class="ph ph-calendar-check me-1 text-primary"></i> Periode PKL Berjalan
                </h5>
                
                @if($periodeAktif)
                    @php
                        $start = $periodeAktif->tanggal_mulai ? \Carbon\Carbon::parse($periodeAktif->tanggal_mulai)->startOfDay() : null;
                        $end = $periodeAktif->tanggal_selesai ? \Carbon\Carbon::parse($periodeAktif->tanggal_selesai)->startOfDay() : null;
                        $now = \Carbon\Carbon::now()->startOfDay();
                        $totalDays = ($start && $end) ? max(1, (int) round($start->diffInDays($end))) : 1;
                        $passedDays = ($start && $now->greaterThan($start)) ? min($totalDays, (int) round($start->diffInDays($now))) : 0;
                        $progressPercent = min(100, max(0, (int) round(($passedDays / $totalDays) * 100)));
                        $remainingDays = ($end && $end->greaterThanOrEqualTo($now)) ? (int) round($now->diffInDays($end)) : 0;
                    @endphp

                    <div class="p-3 mb-3" style="background: #f0f9ff; border-radius: 12px; border: 1px solid #bae6fd;">
                        <span class="badge bg-success mb-2">Aktif</span>
                        <h6 class="fw-bold mb-1" style="color: #0369a1;">{{ $periodeAktif->nama_periode }}</h6>
                        <small class="text-muted">Tahun Ajaran {{ $periodeAktif->tahun_ajaran }}</small>
                    </div>

                    <div class="mb-3" style="font-size: 0.85rem;">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Waktu Berjalan:</span>
                            <strong>{{ $progressPercent }}%</strong>
                        </div>
                        <div class="progress" style="height: 8px; border-radius: 10px;">
                            <div class="progress-bar bg-info" style="width: {{ $progressPercent }}%;"></div>
                        </div>
                        <div class="text-end text-muted mt-1" style="font-size: 0.75rem;">Sisa {{ $remainingDays }} hari lagi</div>
                    </div>

                    <div class="d-flex flex-column gap-2" style="font-size: 0.825rem;">
                        <div class="d-flex justify-content-between p-2 bg-light rounded">
                            <span class="text-muted">Mulai:</span>
                            <strong>{{ $periodeAktif->tanggal_mulai ? $periodeAktif->tanggal_mulai->translatedFormat('d M Y') : '-' }}</strong>
                        </div>
                        <div class="d-flex justify-content-between p-2 bg-light rounded">
                            <span class="text-muted">Selesai:</span>
                            <strong>{{ $periodeAktif->tanggal_selesai ? $periodeAktif->tanggal_selesai->translatedFormat('d M Y') : '-' }}</strong>
                        </div>
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="ph ph-calendar-x fs-1 mb-2"></i>
                        <p class="mb-0">Belum ada periode aktif.</p>
                        <a href="{{ route('admin.periode.create') }}" class="btn btn-sm btn-primary mt-2">Tambah Periode</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Jurnal Masuk Terbaru -->
        <div class="col-lg-8">
            <div class="pro-card h-100 p-0" style="border-radius: 16px; overflow: hidden;">
                <div class="p-4 d-flex justify-content-between align-items-center border-bottom">
                    <h5 class="fw-bold mb-0" style="font-size: 1rem; color: var(--text-main);">
                        <i class="ph ph-book-open-text me-1 text-primary"></i> Jurnal Masuk Terbaru
                    </h5>
                    <span class="badge bg-warning-subtle text-warning border px-2.5 py-1" style="border-radius: 8px;">
                        {{ $stats['jurnal_menunggu'] }} Menunggu Validasi
                    </span>
                </div>
                
                <div class="table-responsive p-3">
                    <table class="table align-middle table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Siswa</th>
                                <th>Tempat PKL</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentJurnal as $j)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $j->penempatan?->siswa?->nama ?? '-' }}</div>
                                    <small class="text-muted">{{ $j->penempatan?->siswa?->kelas?->nama_kelas }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1.5 text-muted" style="font-size: 0.85rem;">
                                        <i class="ph ph-buildings"></i>
                                        {{ $j->penempatan?->perusahaan?->nama_perusahaan ?? '-' }}
                                    </div>
                                </td>
                                <td style="font-size: 0.85rem;">
                                    {{ \Carbon\Carbon::parse($j->tanggal)->translatedFormat('d M Y') }}
                                </td>
                                <td>
                                    @if($j->status_validasi == 'menunggu')
                                        <span class="badge bg-warning-subtle text-warning border">Menunggu</span>
                                    @elseif($j->status_validasi == 'disetujui')
                                        <span class="badge bg-success-subtle text-success border">Disetujui</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border">Ditolak</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="ph ph-tray fs-2 mb-2 d-block opacity-50"></i>
                                    Belum ada jurnal masuk
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Action Hub -->
    <div class="pro-card p-4" style="border-radius: 16px;">
        <h5 class="fw-bold mb-3" style="font-size: 1rem; color: var(--text-main);">
            <i class="ph ph-lightning me-1 text-primary"></i> Pintasan & Ekspor Cepat
        </h5>
        
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.dokumen.index', ['tab' => 'pengantar']) }}" class="btn btn-outline-primary d-inline-flex align-items-center gap-1.5 px-3 py-2" style="border-radius: 8px;">
                <i class="ph ph-envelope-simple"></i> Cetak Surat Pengantar
            </a>
            <a href="{{ route('admin.dokumen.index', ['tab' => 'tugas']) }}" class="btn btn-outline-primary d-inline-flex align-items-center gap-1.5 px-3 py-2" style="border-radius: 8px;">
                <i class="ph ph-identification-badge"></i> Cetak Surat Tugas
            </a>
            <a href="{{ route('admin.dokumen.index', ['tab' => 'sertifikat']) }}" class="btn btn-outline-success d-inline-flex align-items-center gap-1.5 px-3 py-2" style="border-radius: 8px;">
                <i class="ph ph-certificate"></i> Cetak E-Sertifikat PKL
            </a>
            <a href="{{ route('admin.pks.index') }}" class="btn btn-outline-warning d-inline-flex align-items-center gap-1.5 px-3 py-2" style="border-radius: 8px;">
                <i class="ph ph-handshake"></i> Kelola Data PKS
            </a>
            <a href="{{ route('admin.export.siswa') }}" class="btn btn-light border d-inline-flex align-items-center gap-1.5 px-3 py-2" style="border-radius: 8px;">
                <i class="ph ph-file-csv"></i> Export Data Siswa (CSV)
            </a>
            <a href="{{ route('admin.export.penempatan') }}" class="btn btn-light border d-inline-flex align-items-center gap-1.5 px-3 py-2" style="border-radius: 8px;">
                <i class="ph ph-file-csv"></i> Export Penempatan (CSV)
            </a>
        </div>
    </div>

    <!-- Bottom Banner (PANEL HUBIN & KOORDINATOR PKL) -->
    <div class="pro-card p-4 mt-4" style="border-radius: 16px; background: linear-gradient(135deg, #0c4a6e 0%, #0369a1 100%); color: #ffffff; position: relative; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(12, 74, 110, 0.3);">
        <div style="position: absolute; right: -20px; bottom: -20px; opacity: 0.12; font-size: 150px; line-height: 1;">
            <i class="ph ph-buildings"></i>
        </div>
        <div class="row align-items-center position-relative">
            <div class="col-lg-8">
                <span class="badge bg-white text-primary px-3 py-1.5 mb-2 fw-semibold" style="border-radius: 20px; font-size: 0.75rem; letter-spacing: 0.5px;">
                    PUSAT KENDALI HUBIN & KOORDINATOR PKL
                </span>
                <h4 class="fw-bold mb-1" style="letter-spacing: -0.5px;">Sinergi Pendidikan Vokasi & Kemitraan Industri</h4>
                <p class="mb-0 opacity-85" style="font-size: 0.9rem; max-width: 650px; line-height: 1.5;">
                    Mewujudkan tata kelola Praktik Kerja Lapangan yang akuntabel, transparan, dan terintegrasi di SMK Labor Binaan FKIP UNRI Pekanbaru untuk mencetak lulusan unggul siap kerja.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0 d-flex justify-content-lg-end gap-2 flex-wrap">
                <a href="{{ route('admin.pengaturan.index') }}" class="btn btn-light px-3.5 py-2 text-primary fw-semibold d-inline-flex align-items-center gap-2" style="border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                    <i data-lucide="settings" style="width: 18px; height: 18px;"></i>
                    <span>Pengaturan Sekolah</span>
                </a>
            </div>
        </div>
    </div>

</div>

<!-- Chart.js Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Chart Persebaran Siswa per Jurusan
    const ctxJurusan = document.getElementById('jurusanChart');
    if (ctxJurusan) {
        new Chart(ctxJurusan, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($jurusanLabels) !!},
                datasets: [{
                    data: {!! json_encode($jurusanData) !!},
                    backgroundColor: [
                        '#0284c7', // Sky Blue (TKJ)
                        '#10b981', // Emerald (RPL)
                        '#f59e0b', // Amber (AK)
                        '#8b5cf6', // Violet (MP)
                        '#ec4899', // Pink (BR)
                        '#06b6d4',
                        '#f97316'
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 10,
                            usePointStyle: true,
                            font: { size: 11, family: 'Inter' }
                        }
                    },
                    tooltip: {
                        padding: 10,
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 12 },
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let val = context.raw || 0;
                                let total = context.chart._metasets[0].total || 1;
                                let pct = Math.round((val / total) * 100);
                                return ` ${val} Siswa (${pct}%)`;
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    }

    // 2. Chart Top Perusahaan
    const ctxPerusahaan = document.getElementById('perusahaanChart');
    if (ctxPerusahaan) {
        new Chart(ctxPerusahaan, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_map(function($label) { return strlen($label) > 20 ? substr($label, 0, 18) . '...' : $label; }, $perusahaanLabels)) !!},
                datasets: [{
                    label: 'Jumlah Siswa',
                    data: {!! json_encode($perusahaanData) !!},
                    backgroundColor: '#0ea5e9',
                    borderRadius: 6,
                    maxBarThickness: 32
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, font: { size: 11 } },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 } }
                    }
                }
            }
        });
    }

    // 3. Chart Tren Presensi 7 Hari Terakhir
    const ctxPresensi = document.getElementById('presensiChart');
    if (ctxPresensi) {
        new Chart(ctxPresensi, {
            type: 'line',
            data: {
                labels: {!! json_encode($presensiLabels) !!},
                datasets: [
                    {
                        label: 'Hadir',
                        data: {!! json_encode($presensiHadir) !!},
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#10b981',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Izin',
                        data: {!! json_encode($presensiIzin) !!},
                        borderColor: '#0ea5e9',
                        backgroundColor: 'rgba(14, 165, 233, 0.05)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#0ea5e9',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Sakit',
                        data: {!! json_encode($presensiSakit) !!},
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.05)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#f59e0b',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            boxWidth: 10,
                            usePointStyle: true,
                            font: { size: 11, family: 'Inter' }
                        }
                    },
                    tooltip: {
                        padding: 10,
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 12 }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, font: { size: 11 } },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 } }
                    }
                }
            }
        });
    }
});
</script>

@endsection
