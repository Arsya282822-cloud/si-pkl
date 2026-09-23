@extends('layouts.app')

@section('title', 'Papan Pengumuman & Broadcast')

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1 text-dark">Papan Pengumuman & Broadcast</h4>
            <p class="text-muted small mb-0">Kelola pengumuman, jadwal pembekalan, instruksi, dan broadcast informasi penting untuk Siswa & Guru.</p>
        </div>
        <a href="{{ route('admin.pengumuman.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
            <i class="ph ph-plus-circle" style="font-size: 1.1rem;"></i> Buat Pengumuman Baru
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show pro-card mb-4 border-0 p-3" role="alert" style="background-color: #ecfdf5; border-left: 5px solid #10b981 !important;">
            <div class="d-flex align-items-center gap-2">
                <i class="ph ph-check-circle text-success fs-4"></i>
                <div class="text-success fw-medium">{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- 3 Stat Metric Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="pro-card p-3 d-flex align-items-center gap-3">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    <i class="ph ph-megaphone-simple"></i>
                </div>
                <div>
                    <span class="text-muted small d-block">Total Pengumuman</span>
                    <h4 class="fw-bold text-dark mb-0">{{ $stats['total'] }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="pro-card p-3 d-flex align-items-center gap-3">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    <i class="ph ph-check-circle"></i>
                </div>
                <div>
                    <span class="text-muted small d-block">Sedang Aktif</span>
                    <h4 class="fw-bold text-success mb-0">{{ $stats['aktif'] }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="pro-card p-3 d-flex align-items-center gap-3">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #fef9c3; color: #ca8a04; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    <i class="ph ph-push-pin"></i>
                </div>
                <div>
                    <span class="text-muted small d-block">Dipin ke Beranda</span>
                    <h4 class="fw-bold text-warning mb-0">{{ $stats['pinned'] }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Table Card -->
    <div class="pro-card p-4">
        <!-- Filter & Search Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-3 border-bottom">
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.pengumuman.index', ['kategori' => 'all', 'search' => $search]) }}" class="btn btn-sm {{ $kategori === 'all' ? 'btn-primary' : 'btn-light' }}">
                    Semua ({{ $stats['total'] }})
                </a>
                <a href="{{ route('admin.pengumuman.index', ['kategori' => 'penting', 'search' => $search]) }}" class="btn btn-sm {{ $kategori === 'penting' ? 'btn-danger text-white' : 'btn-light' }}">
                    Penting
                </a>
                <a href="{{ route('admin.pengumuman.index', ['kategori' => 'jadwal', 'search' => $search]) }}" class="btn btn-sm {{ $kategori === 'jadwal' ? 'btn-warning text-dark' : 'btn-light' }}">
                    Jadwal
                </a>
                <a href="{{ route('admin.pengumuman.index', ['kategori' => 'info', 'search' => $search]) }}" class="btn btn-sm {{ $kategori === 'info' ? 'btn-info text-white' : 'btn-light' }}">
                    Informasi
                </a>
            </div>

            <form action="{{ route('admin.pengumuman.index') }}" method="GET" class="d-flex gap-2">
                <input type="hidden" name="kategori" value="{{ $kategori }}">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" name="search" class="form-control" placeholder="Cari judul/konten..." value="{{ $search }}">
                    <button class="btn btn-outline-secondary" type="submit"><i class="ph ph-magnifying-glass"></i></button>
                </div>
                @if($search)
                    <a href="{{ route('admin.pengumuman.index', ['kategori' => $kategori]) }}" class="btn btn-sm btn-light" title="Reset Search"><i class="ph ph-x"></i></a>
                @endif
            </form>
        </div>

        @if($pengumuman->isEmpty())
            <div class="text-center py-5">
                <div class="mb-3" style="font-size: 48px; color: #94a3b8;">
                    <i class="ph ph-megaphone"></i>
                </div>
                <h6 class="fw-bold text-dark mb-1">Belum Ada Pengumuman</h6>
                <p class="text-muted small mb-3">Klik tombol di bawah untuk membuat pengumuman pertama Anda.</p>
                <a href="{{ route('admin.pengumuman.create') }}" class="btn btn-primary btn-sm px-3 py-2 fw-semibold">
                    <i class="ph ph-plus"></i> Buat Pengumuman
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                    <thead class="table-light">
                        <tr>
                            <th>Judul & Isi Pengumuman</th>
                            <th>Kategori</th>
                            <th>Target</th>
                            <th>Status</th>
                            <th>Tanggal Publikasi</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pengumuman as $p)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-start gap-2">
                                        @if($p->is_pinned)
                                            <span class="badge bg-warning text-dark px-1.5 py-0.5" title="Dipin di atas beranda"><i class="ph ph-push-pin"></i> Pin</span>
                                        @endif
                                        <div>
                                            <div class="fw-bold text-dark">{{ $p->judul }}</div>
                                            <p class="text-muted small mb-0 text-truncate" style="max-width: 380px;">{{ Str::limit($p->konten, 90) }}</p>
                                            @if($p->file_lampiran)
                                                <a href="{{ asset($p->file_lampiran) }}" target="_blank" class="badge bg-light text-primary border mt-1 text-decoration-none">
                                                    <i class="ph ph-paperclip"></i> Unduh Lampiran
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $catColor = match($p->kategori) {
                                            'penting' => 'danger',
                                            'jadwal' => 'warning text-dark',
                                            'peringatan' => 'danger',
                                            default => 'info text-white',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $catColor }} px-2 py-1">{{ ucfirst($p->kategori) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ ucfirst($p->target_role) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $p->status === 'aktif' ? 'success' : 'secondary' }} px-2 py-1">
                                        {{ ucfirst($p->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-dark">{{ $p->created_at->format('d M Y') }}</div>
                                    <small class="text-muted">{{ $p->created_at->format('H:i') }} WIB</small>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('admin.pengumuman.edit', $p->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="ph ph-pencil-simple"></i>
                                        </a>
                                        <form action="{{ route('admin.pengumuman.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="ph ph-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $pengumuman->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
