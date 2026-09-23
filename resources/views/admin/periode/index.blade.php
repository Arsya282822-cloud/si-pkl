@extends('layouts.app')
@section('title', 'Periode PKL')
@section('topbar_title', 'Periode PKL')

@section('content')
<div class="container-fluid px-0">

    <!-- Top Action Bar -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--text-main);">Kelola Periode PKL</h4>
            <p class="text-muted mb-0" style="font-size: 0.875rem;">Atur jadwal gelombang, tahun ajaran, dan pantau status pelaksanaan PKL.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.periode.create') }}" class="btn btn-primary d-flex align-items-center gap-2 px-3 py-2" style="border-radius: 10px; font-weight: 600; box-shadow: 0 4px 12px rgba(14, 165, 233, 0.25);">
                <i class="ph ph-plus-circle" style="font-size: 20px;"></i>
                <span>Tambah Periode Baru</span>
            </a>
        </div>
    </div>

    <!-- Metric Stat Cards -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Periode Aktif -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="pro-card p-3 d-flex align-items-center gap-3" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); color: #fff; border-radius: 16px; box-shadow: 0 10px 25px -5px rgba(2, 132, 199, 0.35); position: relative; overflow: hidden;">
                <div style="position: absolute; right: -15px; bottom: -15px; opacity: 0.15; font-size: 90px; line-height: 1;">
                    <i class="ph ph-calendar-check"></i>
                </div>
                <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    <i class="ph ph-radio-button"></i>
                </div>
                <div>
                    <div style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.9;">Periode Aktif</div>
                    <div class="fw-bold fs-5 text-truncate" style="max-width: 170px;">
                        {{ $activePeriod ? $activePeriod->nama_periode : 'Tidak Ada' }}
                    </div>
                    <div style="font-size: 0.75rem; opacity: 0.85;">
                        {{ $activePeriod ? $activePeriod->tahun_ajaran : '-' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Total Periode -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="pro-card p-3 d-flex align-items-center gap-3" style="border-radius: 16px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    <i class="ph ph-calendar"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Total Periode</div>
                    <div class="fw-bold fs-4" style="color: var(--text-main);">{{ $totalPeriode }}</div>
                    <div class="text-muted" style="font-size: 0.75rem;">Semua gelombang</div>
                </div>
            </div>
        </div>

        <!-- Card 3: Total Siswa PKL -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="pro-card p-3 d-flex align-items-center gap-3" style="border-radius: 16px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    <i class="ph ph-users-three"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Total Penempatan</div>
                    <div class="fw-bold fs-4" style="color: var(--text-main);">{{ $totalSiswaPkl }}</div>
                    <div class="text-muted" style="font-size: 0.75rem;">Siswa terdaftar</div>
                </div>
            </div>
        </div>

        <!-- Card 4: Selesai / Arsip -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="pro-card p-3 d-flex align-items-center gap-3" style="border-radius: 16px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #f1f5f9; color: #64748b; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    <i class="ph ph-archive"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Periode Selesai</div>
                    <div class="fw-bold fs-4" style="color: var(--text-main);">{{ $totalSelesai }}</div>
                    <div class="text-muted" style="font-size: 0.75rem;">Riwayat kelulusan PKL</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Period Spotlight Banner (if active) -->
    @if($activePeriod)
        @php
            $start = $activePeriod->tanggal_mulai ? \Carbon\Carbon::parse($activePeriod->tanggal_mulai)->startOfDay() : null;
            $end = $activePeriod->tanggal_selesai ? \Carbon\Carbon::parse($activePeriod->tanggal_selesai)->startOfDay() : null;
            $now = \Carbon\Carbon::now()->startOfDay();
            $totalDays = ($start && $end) ? max(1, (int) round($start->diffInDays($end))) : 1;
            $passedDays = ($start && $now->greaterThan($start)) ? min($totalDays, (int) round($start->diffInDays($now))) : 0;
            $progressPercent = min(100, max(0, (int) round(($passedDays / $totalDays) * 100)));
            $remainingDays = ($end && $end->greaterThanOrEqualTo($now)) ? (int) round($now->diffInDays($end)) : 0;
        @endphp
        <div class="pro-card p-4 mb-4" style="border-radius: 16px; background: #ffffff; border-left: 5px solid #0ea5e9;">
            <div class="row align-items-center gy-3">
                <div class="col-lg-6">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-success d-inline-flex align-items-center gap-1 px-2.5 py-1" style="font-size: 0.75rem; border-radius: 8px;">
                            <span class="spinner-grow spinner-grow-sm" role="status" style="width: 7px; height: 7px;"></span>
                            Sedang Berjalan (Aktif)
                        </span>
                        <span class="badge bg-light text-dark px-2.5 py-1" style="border-radius: 8px; font-size: 0.75rem; border: 1px solid #e2e8f0;">
                            T.A. {{ $activePeriod->tahun_ajaran }}
                        </span>
                    </div>
                    <h5 class="fw-bold mb-1" style="color: var(--text-main);">{{ $activePeriod->nama_periode }}</h5>
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">
                        <i class="ph ph-calendar-blank me-1 text-primary"></i>
                        {{ $activePeriod->tanggal_mulai ? $activePeriod->tanggal_mulai->translatedFormat('d F Y') : '-' }} &nbsp;s/d&nbsp; 
                        {{ $activePeriod->tanggal_selesai ? $activePeriod->tanggal_selesai->translatedFormat('d F Y') : '-' }}
                        <span class="ms-2 badge bg-primary-subtle text-primary fw-medium">{{ $totalDays }} Hari</span>
                    </p>
                </div>
                <div class="col-lg-6">
                    <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.8rem;">
                        <span class="text-muted fw-semibold">Progres Waktu: {{ $progressPercent }}%</span>
                        <span class="text-muted">
                            @if($start && $now->lessThan($start))
                                Belum dimulai (mulai dalam {{ (int) round($now->diffInDays($start)) }} hari)
                            @elseif($end && $now->greaterThan($end))
                                Telah selesai
                            @else
                                Sisa <strong>{{ $remainingDays }} hari</strong> lagi
                            @endif
                        </span>
                    </div>
                    <div class="progress" style="height: 10px; border-radius: 10px; background-color: #e2e8f0;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" style="width: {{ $progressPercent }}%;" aria-valuenow="{{ $progressPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('admin.penempatan.index', ['periode_id' => $activePeriod->id]) }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1" style="border-radius: 8px;">
                            <i class="ph ph-users me-1"></i> Lihat Siswa ({{ $activePeriod->penempatan_count }})
                        </a>
                        <a href="{{ route('admin.periode.edit', $activePeriod) }}" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1" style="border-radius: 8px;">
                            <i class="ph ph-pencil-simple me-1"></i> Edit Periode
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Filter & Search Controls -->
    <div class="pro-card p-3 mb-4" style="border-radius: 16px;">
        <div class="row g-3 align-items-center justify-content-between">
            <!-- Status Tabs -->
            <div class="col-md-7">
                <div class="nav nav-pills gap-1" style="font-size: 0.85rem;">
                    <a href="{{ route('admin.periode.index', array_merge(request()->except('status'), [])) }}" 
                       class="nav-link px-3 py-1.5 {{ $statusFilter === '' ? 'active bg-primary' : 'text-muted' }}" 
                       style="border-radius: 8px; font-weight: 500;">
                        Semua Periode ({{ $totalPeriode }})
                    </a>
                    <a href="{{ route('admin.periode.index', array_merge(request()->except('status'), ['status' => 'aktif'])) }}" 
                       class="nav-link px-3 py-1.5 {{ $statusFilter === 'aktif' ? 'active bg-success' : 'text-muted' }}" 
                       style="border-radius: 8px; font-weight: 500;">
                        <span class="d-inline-block rounded-circle bg-success me-1" style="width: 7px; height: 7px;"></span>
                        Aktif ({{ $totalAktif }})
                    </a>
                    <a href="{{ route('admin.periode.index', array_merge(request()->except('status'), ['status' => 'selesai'])) }}" 
                       class="nav-link px-3 py-1.5 {{ $statusFilter === 'selesai' ? 'active bg-info text-white' : 'text-muted' }}" 
                       style="border-radius: 8px; font-weight: 500;">
                        Selesai ({{ $totalSelesai }})
                    </a>
                    <a href="{{ route('admin.periode.index', array_merge(request()->except('status'), ['status' => 'nonaktif'])) }}" 
                       class="nav-link px-3 py-1.5 {{ $statusFilter === 'nonaktif' ? 'active bg-secondary' : 'text-muted' }}" 
                       style="border-radius: 8px; font-weight: 500;">
                        Nonaktif
                    </a>
                </div>
            </div>

            <!-- Search Input -->
            <div class="col-md-5">
                <form method="GET" action="{{ route('admin.periode.index') }}" class="d-flex gap-2">
                    @if($statusFilter)
                        <input type="hidden" name="status" value="{{ $statusFilter }}">
                    @endif
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius: 8px 0 0 8px;">
                            <i class="ph ph-magnifying-glass"></i>
                        </span>
                        <input type="text" name="q" value="{{ $q }}" class="form-control border-start-0 ps-0" placeholder="Cari nama periode atau tahun..." style="border-radius: 0 8px 8px 0; font-size: 0.875rem;">
                    </div>
                    <button type="submit" class="btn btn-outline-primary px-3" style="border-radius: 8px;">Cari</button>
                    @if($q || $statusFilter)
                        <a href="{{ route('admin.periode.index') }}" class="btn btn-outline-secondary px-3" style="border-radius: 8px;" title="Reset Filter">Reset</a>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <!-- Period Cards Grid -->
    <div class="row g-3 mb-4">
        @forelse($periode as $item)
            @php
                $itemStart = $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai)->startOfDay() : null;
                $itemEnd = $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->startOfDay() : null;
                $itemDuration = ($itemStart && $itemEnd) ? (int) round($itemStart->diffInDays($itemEnd)) : null;
                $months = ($itemStart && $itemEnd) ? round($itemStart->diffInMonths($itemEnd), 1) : null;
            @endphp
            <div class="col-12 col-md-6 col-xl-4">
                <div class="pro-card h-100 d-flex flex-column justify-content-between p-4" style="border-radius: 16px; transition: transform 0.2s, box-shadow 0.2s; position: relative;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 12px 24px rgba(0,0,0,0.06)';" onmouseout="this.style.transform='none'; this.style.boxShadow='none';">
                    
                    <div>
                        <!-- Header: Badges & Dropdown Action -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="d-flex flex-wrap gap-1.5">
                                @if($item->status === 'aktif')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle d-inline-flex align-items-center gap-1 px-2.5 py-1" style="border-radius: 8px; font-weight: 600; font-size: 0.75rem;">
                                        <span class="rounded-circle bg-success" style="width: 6px; height: 6px;"></span> Aktif
                                    </span>
                                @elseif($item->status === 'selesai')
                                    <span class="badge bg-info-subtle text-info border border-info-subtle d-inline-flex align-items-center gap-1 px-2.5 py-1" style="border-radius: 8px; font-weight: 600; font-size: 0.75rem;">
                                        <i class="ph ph-check-circle"></i> Selesai
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle d-inline-flex align-items-center gap-1 px-2.5 py-1" style="border-radius: 8px; font-weight: 600; font-size: 0.75rem;">
                                        Nonaktif
                                    </span>
                                @endif

                                <span class="badge bg-light text-muted border px-2.5 py-1" style="border-radius: 8px; font-size: 0.75rem;">
                                    T.A. {{ $item->tahun_ajaran }}
                                </span>
                            </div>

                            <div class="dropdown">
                                <button class="btn btn-sm btn-light p-1" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 8px;">
                                    <i class="ph ph-dots-three-vertical" style="font-size: 18px;"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="border-radius: 10px; font-size: 0.85rem;">
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('admin.penempatan.index', ['periode_id' => $item->id]) }}">
                                            <i class="ph ph-users text-primary" style="font-size: 16px;"></i> Daftar Siswa ({{ $item->penempatan_count }})
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('admin.periode.edit', $item) }}">
                                            <i class="ph ph-pencil-simple text-warning" style="font-size: 16px;"></i> Edit Periode
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('admin.periode.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus periode {{ $item->nama_periode }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2 py-2">
                                                <i class="ph ph-trash" style="font-size: 16px;"></i> Hapus Periode
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Periode Title -->
                        <h5 class="fw-bold mb-3" style="color: var(--text-main); font-size: 1.05rem; line-height: 1.35;">
                            {{ $item->nama_periode }}
                        </h5>

                        <!-- Date Timeline Info Box -->
                        <div class="p-3 mb-3" style="background-color: #f8fafc; border-radius: 12px; border: 1px dashed #e2e8f0;">
                            <div class="d-flex align-items-center gap-2 mb-2" style="font-size: 0.825rem;">
                                <div style="width: 24px; height: 24px; border-radius: 6px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 13px;">
                                    <i class="ph ph-calendar-plus"></i>
                                </div>
                                <div>
                                    <span class="text-muted">Mulai:</span>
                                    <strong class="text-dark ms-1">{{ $itemStart ? $itemStart->translatedFormat('d M Y') : '-' }}</strong>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2" style="font-size: 0.825rem;">
                                <div style="width: 24px; height: 24px; border-radius: 6px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 13px;">
                                    <i class="ph ph-calendar-check"></i>
                                </div>
                                <div>
                                    <span class="text-muted">Selesai:</span>
                                    <strong class="text-dark ms-1">{{ $itemEnd ? $itemEnd->translatedFormat('d M Y') : '-' }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer: Siswa Count & Action Buttons -->
                    <div class="pt-2 border-top d-flex justify-content-between align-items-center" style="font-size: 0.85rem;">
                        <div class="d-flex align-items-center gap-1.5 text-muted">
                            <i class="ph ph-student" style="font-size: 18px; color: var(--primary-blue);"></i>
                            <span><strong>{{ $item->penempatan_count }}</strong> Siswa Ditempatkan</span>
                        </div>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.periode.edit', $item) }}" class="btn btn-sm btn-outline-primary py-1 px-2.5" style="border-radius: 8px;" title="Edit">
                                <i class="ph ph-pencil-simple"></i>
                            </a>
                            <form action="{{ route('admin.periode.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus periode ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2.5" style="border-radius: 8px;" title="Hapus">
                                    <i class="ph ph-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="pro-card p-5 text-center" style="border-radius: 16px;">
                    <div style="width: 72px; height: 72px; border-radius: 20px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 36px; margin: 0 auto 16px auto;">
                        <i class="ph ph-calendar-x"></i>
                    </div>
                    <h5 class="fw-bold mb-1">Belum Ada Data Periode PKL</h5>
                    <p class="text-muted mb-3" style="font-size: 0.875rem;">Buat gelombang periode PKL pertama Anda untuk mulai mengatur penempatan siswa.</p>
                    <a href="{{ route('admin.periode.create') }}" class="btn btn-primary px-4 py-2" style="border-radius: 10px; font-weight: 600;">
                        <i class="ph ph-plus-circle me-1"></i> Tambah Periode Sekarang
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $periode->links() }}
    </div>

</div>
@endsection
