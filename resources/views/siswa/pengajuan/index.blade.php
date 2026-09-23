@extends('layouts.app')

@section('title', 'Pengajuan Tempat PKL Mandiri')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Banner -->
    <div class="pro-card p-4 mb-4" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); color: white; border: none;">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-2" style="background: rgba(255,255,255,0.15); font-size: 0.8rem; font-weight: 600;">
                    <i class="ph ph-hand-pointing"></i> Jalur Mandiri / Kemitraan Siswa
                </div>
                <h2 class="h4 fw-bold mb-1">Pengajuan Tempat PKL Mandiri</h2>
                <p class="mb-0 text-white-50" style="font-size: 0.9rem;">
                    Ajukan usulan tempat PKL / Perusahaan mitra jika Anda berencana magang di industri pilihan sendiri.
                </p>
            </div>
            <div>
                @if(!$penempatan && !$hasPending)
                    <a href="{{ route('siswa.pengajuan.create') }}" class="btn btn-light fw-bold px-4 py-2 shadow-sm d-inline-flex align-items-center gap-2" style="color: #0284c7; border-radius: 10px;">
                        <i class="ph ph-plus-circle" style="font-size: 1.2rem;"></i> Ajukan Tempat PKL Baru
                    </a>
                @elseif($hasPending)
                    <div class="badge bg-warning text-dark px-3 py-2 fs-6 rounded-3 shadow-sm d-inline-flex align-items-center gap-2">
                        <i class="ph ph-hourglass-medium"></i> Sedang Dalam Proses Review
                    </div>
                @elseif($penempatan)
                    <div class="badge bg-success px-3 py-2 fs-6 rounded-3 shadow-sm d-inline-flex align-items-center gap-2">
                        <i class="ph ph-check-circle"></i> Sudah Resmi Ditempatkan
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show pro-card mb-4 border-0 p-3" role="alert" style="background-color: #ecfdf5; border-left: 5px solid #10b981 !important;">
            <div class="d-flex align-items-center gap-2">
                <i class="ph ph-check-circle text-success fs-4"></i>
                <div class="text-success fw-medium">{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show pro-card mb-4 border-0 p-3" role="alert" style="background-color: #f0f9ff; border-left: 5px solid #0284c7 !important;">
            <div class="d-flex align-items-center gap-2">
                <i class="ph ph-info text-info fs-4"></i>
                <div class="text-primary fw-medium">{{ session('info') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Information Status Box -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="pro-card p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div style="width: 44px; height: 44px; border-radius: 12px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                            <i class="ph ph-info"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">Alur Pengajuan Mandiri</h6>
                            <span class="text-muted small">Tahapan Verifikasi</span>
                        </div>
                    </div>
                    <ol class="small text-muted ps-3 mb-0" style="line-height: 1.7;">
                        <li>Isi formulir data profil perusahaan mitra.</li>
                        <li>Lampirkan surat balasan/penerimaan jika ada.</li>
                        <li>Tim Pokja/Guru memeriksa kesesuaian kompetensi keahlian.</li>
                        <li>Setelah disetujui, data penempatan & pembimbing aktif otomatis.</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="pro-card p-4 h-100">
                <h6 class="fw-bold mb-3 text-dark d-flex align-items-center gap-2">
                    <i class="ph ph-clock-counter-clockwise text-primary"></i> Riwayat & Status Pengajuan Saya
                </h6>

                @if($pengajuans->isEmpty())
                    <div class="text-center py-5">
                        <div class="mb-3" style="font-size: 48px; color: #94a3b8;">
                            <i class="ph ph-folder-dashed"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Belum Ada Pengajuan</h6>
                        <p class="text-muted small mb-3">Anda belum pernah mengajukan usulan tempat PKL mandiri.</p>
                        @if(!$penempatan)
                            <a href="{{ route('siswa.pengajuan.create') }}" class="btn btn-primary btn-sm px-3 py-2 fw-semibold">
                                <i class="ph ph-plus"></i> Buat Pengajuan Sekarang
                            </a>
                        @endif
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                            <thead class="table-light">
                                <tr>
                                    <th>Perusahaan / Mitra</th>
                                    <th>Periode</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pengajuans as $p)
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $p->nama_perusahaan }}</div>
                                            <span class="text-muted small"><i class="ph ph-map-pin"></i> {{ $p->kota ?? 'Pekanbaru' }} ({{ $p->bidang_usaha ?? '-' }})</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ $p->periode->nama_periode ?? '-' }}</span>
                                        </td>
                                        <td>
                                            {{ $p->created_at->format('d M Y, H:i') }}
                                        </td>
                                        <td>
                                            @if($p->status === 'menunggu')
                                                <span class="badge bg-warning text-dark px-2 py-1 rounded-pill">
                                                    <i class="ph ph-hourglass-medium"></i> Menunggu Review
                                                </span>
                                            @elseif($p->status === 'disetujui')
                                                <span class="badge bg-success text-white px-2 py-1 rounded-pill">
                                                    <i class="ph ph-check-circle"></i> Disetujui
                                                </span>
                                            @else
                                                <span class="badge bg-danger text-white px-2 py-1 rounded-pill">
                                                    <i class="ph ph-x-circle"></i> Ditolak
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-inline-flex gap-1">
                                                <a href="{{ route('siswa.pengajuan.show', $p->id) }}" class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                                    <i class="ph ph-eye"></i> Detail
                                                </a>
                                                @if($p->status === 'menunggu')
                                                    <form action="{{ route('siswa.pengajuan.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Batalkan">
                                                            <i class="ph ph-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
