@extends('layouts.app')

@section('title', 'Audit Trail & Log Aktivitas Sistem')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold fs-4 mb-1 text-dark">Log Aktivitas Sistem</h2>
        <p class="text-muted small mb-0">Catatan jejak digital transparansi dan akuntabilitas aksi pengguna di SI-PKL.</p>
    </div>
    @if($logs->total() > 0)
    <div>
        <form action="{{ route('admin.activity-log.clear') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus seluruh rekaman log aktivitas? Tindakan ini tidak dapat dibatalkan.');">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm d-flex align-items-center gap-2">
                <i class="ph-bold ph-trash"></i> Bersihkan Semua Log
            </button>
        </form>
    </div>
    @endif
</div>

<!-- Filter & Search Card -->
<div class="pro-card p-3 mb-4 bg-white">
    <form method="GET" action="{{ route('admin.activity-log.index') }}" class="row g-2 align-items-center">
        <div class="col-12 col-md-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light border-end-0"><i class="ph ph-magnifying-glass"></i></span>
                <input type="text" name="search" class="form-control form-control-sm border-start-0" placeholder="Cari nama, aktivitas, atau keterangan..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-6 col-md-3">
            <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua Role</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="guru" {{ request('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                <option value="siswa" {{ request('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
            </select>
        </div>
        <div class="col-6 col-md-3">
            <select name="modul" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua Modul</option>
                @foreach($modules as $mod)
                    <option value="{{ $mod }}" {{ request('modul') == $mod ? 'selected' : '' }}>{{ ucfirst($mod) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-2 d-flex gap-1">
            <button type="submit" class="btn btn-primary btn-sm flex-grow-1">Filter</button>
            @if(request()->hasAny(['search', 'role', 'modul']))
                <a href="{{ route('admin.activity-log.index') }}" class="btn btn-light btn-sm"><i class="ph ph-x"></i></a>
            @endif
        </div>
    </form>
</div>

<!-- Table Card -->
<div class="pro-card bg-white shadow-sm overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
            <thead class="table-light text-muted fw-semibold">
                <tr>
                    <th style="width: 170px;" class="ps-4">Waktu</th>
                    <th style="width: 180px;">Pengguna</th>
                    <th style="width: 120px;">Modul</th>
                    <th>Aktivitas & Keterangan</th>
                    <th style="width: 130px;">IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td class="ps-4 text-nowrap">
                            <div class="fw-semibold text-dark">{{ $log->created_at->format('d/m/Y H:i') }}</div>
                            <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                    {{ substr($log->user_name ?? 'U', 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark">{{ $log->user_name ?? 'Guest' }}</div>
                                    @php
                                        $badgeClass = match($log->role) {
                                            'admin' => 'bg-danger text-white',
                                            'guru'  => 'bg-primary text-white',
                                            'siswa' => 'bg-success text-white',
                                            default => 'bg-secondary text-white'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}" style="font-size: 0.65rem; padding: 2px 6px;">{{ ucfirst($log->role ?? 'tamu') }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-2 py-1">{{ ucfirst($log->modul) }}</span>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $log->aktivitas }}</div>
                            @if($log->deskripsi)
                                <div class="text-muted small mt-1 text-break">{{ $log->deskripsi }}</div>
                            @endif
                        </td>
                        <td class="text-muted small font-monospace">
                            {{ $log->ip_address ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="ph ph-shield-check display-6 d-block mb-2 text-primary opacity-50"></i>
                            <div class="fw-semibold">Belum Ada Rekaman Aktivitas</div>
                            <small>Seluruh riwayat aksi pengguna akan tercatat otomatis di halaman ini.</small>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
        <div class="p-3 border-top d-flex justify-content-end">
            {{ $logs->links() }}
        </div>
    @endif
</div>
@endsection
