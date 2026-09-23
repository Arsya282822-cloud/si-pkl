@extends('layouts.app')

@section('title', 'Verifikasi Pengajuan PKL Mandiri Siswa')

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1 text-dark">Verifikasi Pengajuan PKL Siswa</h4>
            <p class="text-muted small mb-0">Tinjau dan setujui usulan tempat magang/PKL yang diajukan siswa secara mandiri.</p>
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show pro-card mb-4 border-0 p-3" role="alert" style="background-color: #fef2f2; border-left: 5px solid #ef4444 !important;">
            <div class="d-flex align-items-center gap-2">
                <i class="ph ph-warning-circle text-danger fs-4"></i>
                <div class="text-danger fw-medium">{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- 4 Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="pro-card p-3 d-flex align-items-center gap-3">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    <i class="ph ph-files"></i>
                </div>
                <div>
                    <span class="text-muted small d-block">Total Pengajuan</span>
                    <h4 class="fw-bold text-dark mb-0">{{ $stats['total'] }}</h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="pro-card p-3 d-flex align-items-center gap-3">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #fef9c3; color: #ca8a04; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    <i class="ph ph-hourglass-medium"></i>
                </div>
                <div>
                    <span class="text-muted small d-block">Menunggu Review</span>
                    <h4 class="fw-bold text-warning mb-0">{{ $stats['menunggu'] }}</h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="pro-card p-3 d-flex align-items-center gap-3">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    <i class="ph ph-check-circle"></i>
                </div>
                <div>
                    <span class="text-muted small d-block">Disetujui</span>
                    <h4 class="fw-bold text-success mb-0">{{ $stats['disetujui'] }}</h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="pro-card p-3 d-flex align-items-center gap-3">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    <i class="ph ph-x-circle"></i>
                </div>
                <div>
                    <span class="text-muted small d-block">Ditolak</span>
                    <h4 class="fw-bold text-danger mb-0">{{ $stats['ditolak'] }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="pro-card p-4">
        <!-- Filter & Search Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-3 border-bottom">
            <!-- Filter Tabs -->
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.pengajuan.index', ['status' => 'all', 'search' => $search]) }}" class="btn btn-sm {{ $status === 'all' ? 'btn-primary' : 'btn-light' }}">
                    Semua ({{ $stats['total'] }})
                </a>
                <a href="{{ route('admin.pengajuan.index', ['status' => 'menunggu', 'search' => $search]) }}" class="btn btn-sm {{ $status === 'menunggu' ? 'btn-warning text-dark' : 'btn-light' }}">
                    Menunggu ({{ $stats['menunggu'] }})
                </a>
                <a href="{{ route('admin.pengajuan.index', ['status' => 'disetujui', 'search' => $search]) }}" class="btn btn-sm {{ $status === 'disetujui' ? 'btn-success text-white' : 'btn-light' }}">
                    Disetujui ({{ $stats['disetujui'] }})
                </a>
                <a href="{{ route('admin.pengajuan.index', ['status' => 'ditolak', 'search' => $search]) }}" class="btn btn-sm {{ $status === 'ditolak' ? 'btn-danger text-white' : 'btn-light' }}">
                    Ditolak ({{ $stats['ditolak'] }})
                </a>
            </div>

            <!-- Search Bar -->
            <form action="{{ route('admin.pengajuan.index') }}" method="GET" class="d-flex gap-2">
                <input type="hidden" name="status" value="{{ $status }}">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" name="search" class="form-control" placeholder="Cari siswa/perusahaan..." value="{{ $search }}">
                    <button class="btn btn-outline-secondary" type="submit"><i class="ph ph-magnifying-glass"></i></button>
                </div>
                @if($search)
                    <a href="{{ route('admin.pengajuan.index', ['status' => $status]) }}" class="btn btn-sm btn-light" title="Reset Search"><i class="ph ph-x"></i></a>
                @endif
            </form>
        </div>

        @if($pengajuans->isEmpty())
            <div class="text-center py-5">
                <div class="mb-3" style="font-size: 48px; color: #94a3b8;">
                    <i class="ph ph-tray"></i>
                </div>
                <h6 class="fw-bold text-dark mb-1">Tidak Ada Data Pengajuan</h6>
                <p class="text-muted small">Belum ada data pengajuan tempat PKL yang sesuai dengan kriteria filter.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                    <thead class="table-light">
                        <tr>
                            <th>Siswa Pemohon</th>
                            <th>Usulan Perusahaan</th>
                            <th>Kontak & Alamat</th>
                            <th>Tanggal Diajukan</th>
                            <th>Status</th>
                            <th class="text-center" style="width: 160px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pengajuans as $p)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $p->siswa->nama ?? 'Siswa' }}</div>
                                    <div class="text-muted small">
                                        NIS: {{ $p->siswa->nis ?? '-' }} &bull; {{ $p->siswa->kelas->nama_kelas ?? '-' }}
                                    </div>
                                    <span class="badge bg-light text-primary border mt-1">{{ $p->siswa->jurusan->nama_jurusan ?? '-' }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $p->nama_perusahaan }}</div>
                                    <span class="text-muted small"><i class="ph ph-briefcase"></i> {{ $p->bidang_usaha ?? 'Umum' }}</span>
                                    @if($p->file_surat_balasan)
                                        <div class="mt-1">
                                            <a href="{{ asset($p->file_surat_balasan) }}" target="_blank" class="badge bg-info text-white text-decoration-none">
                                                <i class="ph ph-paperclip"></i> Ada Berkas
                                            </a>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-dark"><i class="ph ph-user"></i> {{ $p->kontak_person ?? ($p->nama_pimpinan ?? '-') }}</div>
                                    <div class="text-muted small"><i class="ph ph-phone"></i> {{ $p->no_telepon ?? '-' }}</div>
                                    <div class="text-muted small text-truncate" style="max-width: 200px;"><i class="ph ph-map-pin"></i> {{ $p->kota ?? 'Pekanbaru' }}</div>
                                </td>
                                <td>
                                    <div class="text-dark">{{ $p->created_at->format('d/m/Y') }}</div>
                                    <small class="text-muted">{{ $p->created_at->format('H:i') }} WIB</small>
                                </td>
                                <td>
                                    @if($p->status === 'menunggu')
                                        <span class="badge bg-warning text-dark px-2 py-1 rounded-pill">
                                            <i class="ph ph-hourglass-medium"></i> Menunggu
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
                                        <a href="{{ route('admin.pengajuan.show', $p->id) }}" class="btn btn-sm btn-outline-primary" title="Lihat Rincian">
                                            <i class="ph ph-eye"></i>
                                        </a>

                                        @if($p->status === 'menunggu')
                                            <!-- Tombol Setujui Modal -->
                                            <button type="button" class="btn btn-sm btn-success text-white" data-bs-toggle="modal" data-bs-target="#approveModal{{ $p->id }}" title="Setujui Pengajuan">
                                                <i class="ph ph-check"></i>
                                            </button>

                                            <!-- Tombol Tolak Modal -->
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $p->id }}" title="Tolak Pengajuan">
                                                <i class="ph ph-x"></i>
                                            </button>
                                        @endif
                                    </div>

                                    <!-- Modal Approve -->
                                    <div class="modal fade" id="approveModal{{ $p->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered text-start">
                                            <div class="modal-content border-0 pro-card">
                                                <form action="{{ route('admin.pengajuan.approve', $p->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header bg-success text-white">
                                                        <h6 class="modal-title fw-bold"><i class="ph ph-check-circle me-1"></i> Persetujuan Tempat PKL</h6>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body p-4">
                                                        <p class="small text-muted mb-3">
                                                            Menyetujui usulan PKL untuk <strong>{{ $p->siswa->nama ?? 'Siswa' }}</strong> di <strong>{{ $p->nama_perusahaan }}</strong>. Sistem akan otomatis mendaftarkan perusahaan & menempatkan siswa.
                                                        </p>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold small text-dark">Pilih Guru Pembimbing Sekolah (Opsional):</label>
                                                            <select name="guru_id" class="form-select form-select-sm">
                                                                <option value="">-- Tetapkan Nanti / Belum Ada --</option>
                                                                @foreach($gurus as $g)
                                                                    <option value="{{ $g->id }}">{{ $g->nama }} ({{ $g->nip ?? 'NIP -' }})</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="mb-2">
                                                            <label class="form-label fw-semibold small text-dark">Catatan Persetujuan:</label>
                                                            <textarea name="catatan_verifikasi" rows="2" class="form-control form-control-sm" placeholder="Contoh: Disetujui sesuai kompetensi keahlian jurusan."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light p-2">
                                                        <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-sm btn-success px-3 fw-bold text-white">
                                                            <i class="ph ph-check"></i> Konfirmasi Setujui
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Reject -->
                                    <div class="modal fade" id="rejectModal{{ $p->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered text-start">
                                            <div class="modal-content border-0 pro-card">
                                                <form action="{{ route('admin.pengajuan.reject', $p->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header bg-danger text-white">
                                                        <h6 class="modal-title fw-bold"><i class="ph ph-x-circle me-1"></i> Penolakan Usulan Tempat PKL</h6>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body p-4">
                                                        <p class="small text-muted mb-3">
                                                            Anda akan menolak usulan PKL dari <strong>{{ $p->siswa->nama ?? 'Siswa' }}</strong> di <strong>{{ $p->nama_perusahaan }}</strong>.
                                                        </p>

                                                        <div class="mb-2">
                                                            <label class="form-label fw-semibold small text-dark">Alasan Penolakan <span class="text-danger">*</span>:</label>
                                                            <textarea name="catatan_verifikasi" rows="3" class="form-control form-control-sm" placeholder="Contoh: Bidang kerja tidak sesuai dengan capaian kompetensi jurusan, atau kuota perusahaan tidak mencukupi." required></textarea>
                                                            <small class="text-muted" style="font-size: 0.75rem;">Alasan ini akan ditampilkan langsung di akun siswa.</small>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light p-2">
                                                        <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-sm btn-danger px-3 fw-bold text-white">
                                                            <i class="ph ph-x"></i> Tolak Pengajuan
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $pengajuans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
