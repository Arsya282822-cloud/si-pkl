@extends('layouts.app')

@section('title', 'Rincian Pengajuan PKL Siswa')

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1 text-dark">Rincian Pengajuan Tempat PKL</h4>
            <p class="text-muted small mb-0">Periksa kelengkapan data usulan tempat PKL dari siswa.</p>
        </div>
        <a href="{{ route('admin.pengajuan.index') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
            <i class="ph ph-arrow-left"></i> Kembali ke Daftar
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

    <div class="row g-4">
        <!-- Sidebar Info: Siswa & Status -->
        <div class="col-lg-4">
            <!-- Info Siswa Card -->
            <div class="pro-card p-4 mb-4">
                <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                    <i class="ph ph-student text-primary"></i> Data Siswa Pemohon
                </h6>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width: 48px; height: 48px; border-radius: 50%; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: bold;">
                        {{ substr($pengajuan->siswa->nama ?? 'S', 0, 1) }}
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-0">{{ $pengajuan->siswa->nama ?? '-' }}</h6>
                        <span class="text-muted small">NIS: {{ $pengajuan->siswa->nis ?? '-' }} / NISN: {{ $pengajuan->siswa->nisn ?? '-' }}</span>
                    </div>
                </div>

                <ul class="list-unstyled small text-muted mb-0" style="line-height: 2;">
                    <li><strong>Kelas:</strong> {{ $pengajuan->siswa->kelas->nama_kelas ?? '-' }}</li>
                    <li><strong>Jurusan:</strong> {{ $pengajuan->siswa->jurusan->nama_jurusan ?? '-' }}</li>
                    <li><strong>No. HP Siswa:</strong> {{ $pengajuan->siswa->no_hp ?? '-' }}</li>
                    <li><strong>Periode PKL:</strong> {{ $pengajuan->periode->nama_periode ?? '-' }}</li>
                </ul>
            </div>

            <!-- Status & Action Card -->
            <div class="pro-card p-4">
                <h6 class="fw-bold text-dark mb-3">Status Verifikasi</h6>
                <div class="text-center p-3 rounded-3 mb-3" style="background: {{ $pengajuan->status === 'disetujui' ? '#ecfdf5' : ($pengajuan->status === 'ditolak' ? '#fef2f2' : '#fefce8') }};">
                    @if($pengajuan->status === 'disetujui')
                        <div class="fs-2 text-success mb-1"><i class="ph ph-check-circle"></i></div>
                        <h6 class="fw-bold text-success mb-1">DISETUJUI</h6>
                        <small class="text-muted">Data penempatan siswa telah aktif.</small>
                    @elseif($pengajuan->status === 'ditolak')
                        <div class="fs-2 text-danger mb-1"><i class="ph ph-x-circle"></i></div>
                        <h6 class="fw-bold text-danger mb-1">DITOLAK</h6>
                        <small class="text-muted">Pengajuan ini tidak disetujui.</small>
                    @else
                        <div class="fs-2 text-warning mb-1"><i class="ph ph-hourglass-medium"></i></div>
                        <h6 class="fw-bold text-warning mb-1">MENUNGGU VERIFIKASI</h6>
                        <small class="text-muted">Silakan tentukan keputusan.</small>
                    @endif
                </div>

                @if($pengajuan->catatan_verifikasi)
                    <div class="p-3 bg-light rounded-3 border small mb-3">
                        <strong>Catatan Verifikator:</strong>
                        <p class="text-muted mb-1 mt-1">{{ $pengajuan->catatan_verifikasi }}</p>
                        @if($pengajuan->verifikator)
                            <small class="text-muted d-block">Oleh: {{ $pengajuan->verifikator->name }} ({{ $pengajuan->diverifikasi_pada?->format('d M Y, H:i') }})</small>
                        @endif
                    </div>
                @endif

                @if($pengajuan->status === 'menunggu')
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-success text-white fw-bold d-flex align-items-center justify-content-center gap-2" data-bs-toggle="modal" data-bs-target="#approveModal">
                            <i class="ph ph-check"></i> Setujui Pengajuan
                        </button>
                        <button type="button" class="btn btn-outline-danger fw-bold d-flex align-items-center justify-content-center gap-2" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="ph ph-x"></i> Tolak Pengajuan
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Detail Data Card -->
        <div class="col-lg-8">
            <div class="pro-card p-4">
                <h5 class="fw-bold text-dark mb-4 pb-2 border-bottom d-flex align-items-center gap-2">
                    <i class="ph ph-buildings text-primary"></i> Data Usulan Perusahaan
                </h5>

                <div class="row g-3 mb-4">
                    <div class="col-md-7">
                        <label class="text-muted small d-block">Nama Perusahaan / Instansi</label>
                        <span class="fw-bold text-dark fs-5">{{ $pengajuan->nama_perusahaan }}</span>
                    </div>
                    <div class="col-md-5">
                        <label class="text-muted small d-block">Bidang Usaha</label>
                        <span class="badge bg-light text-dark border fs-6">{{ $pengajuan->bidang_usaha ?? 'Umum' }}</span>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small d-block">Alamat Kantor / Lokasi</label>
                        <span class="text-dark">{{ $pengajuan->alamat_perusahaan }} ({{ $pengajuan->kota ?? 'Pekanbaru' }})</span>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small d-block">Nama Pimpinan / Kepala Cabang</label>
                        <span class="text-dark">{{ $pengajuan->nama_pimpinan ?? '-' }}</span>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small d-block">Waktu Pengajuan</label>
                        <span class="text-dark">{{ $pengajuan->created_at->format('d F Y, H:i') }} WIB</span>
                    </div>
                </div>

                <h5 class="fw-bold text-dark mb-4 pb-2 border-bottom d-flex align-items-center gap-2">
                    <i class="ph ph-address-book text-primary"></i> Kontak Person & Alasan
                </h5>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="text-muted small d-block">Nama HRD / Contact Person</label>
                        <span class="fw-semibold text-dark">{{ $pengajuan->kontak_person ?? '-' }}</span>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small d-block">No. Telepon / WhatsApp</label>
                        <span class="text-dark">{{ $pengajuan->no_telepon ?? '-' }}</span>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small d-block">Email Perusahaan</label>
                        <span class="text-dark">{{ $pengajuan->email ?? '-' }}</span>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small d-block">Alasan Pemilihan Tempat Ini oleh Siswa</label>
                        <div class="p-3 bg-light rounded-3 text-dark small" style="white-space: pre-line;">
                            {{ $pengajuan->alasan_memilih ?? 'Tidak ada alasan khusus yang dicantumkan.' }}
                        </div>
                    </div>
                    @if($pengajuan->file_surat_balasan)
                        <div class="col-12">
                            <label class="text-muted small d-block mb-1">Lampiran Dokumen Penerimaan / Balasan</label>
                            <a href="{{ asset($pengajuan->file_surat_balasan) }}" target="_blank" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-2">
                                <i class="ph ph-file-arrow-down"></i> Buka Lampiran Berkas ({{ basename($pengajuan->file_surat_balasan) }})
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Approve -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 pro-card">
                <form action="{{ route('admin.pengajuan.approve', $pengajuan->id) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-success text-white">
                        <h6 class="modal-title fw-bold"><i class="ph ph-check-circle me-1"></i> Setujui Pengajuan PKL</h6>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="small text-muted mb-3">
                            Apakah Anda yakin ingin menyetujui usulan PKL untuk <strong>{{ $pengajuan->siswa->nama ?? 'Siswa' }}</strong> di <strong>{{ $pengajuan->nama_perusahaan }}</strong>?
                        </p>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-dark">Tetapkan Guru Pembimbing Sekolah:</label>
                            <select name="guru_id" class="form-select form-select-sm">
                                <option value="">-- Tetapkan Nanti --</option>
                                @foreach($gurus as $g)
                                    <option value="{{ $g->id }}">{{ $g->nama }} ({{ $g->nip ?? 'NIP -' }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-2">
                            <label class="form-label fw-semibold small text-dark">Catatan Persetujuan (Opsional):</label>
                            <textarea name="catatan_verifikasi" rows="2" class="form-control form-control-sm" placeholder="Contoh: Disetujui. Silakan cetak surat permohonan/pengantar."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-2">
                        <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-sm btn-success px-3 fw-bold text-white">
                            <i class="ph ph-check"></i> Ya, Setujui Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Reject -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 pro-card">
                <form action="{{ route('admin.pengajuan.reject', $pengajuan->id) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-danger text-white">
                        <h6 class="modal-title fw-bold"><i class="ph ph-x-circle me-1"></i> Tolak Pengajuan PKL</h6>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-2">
                            <label class="form-label fw-semibold small text-dark">Alasan Penolakan <span class="text-danger">*</span>:</label>
                            <textarea name="catatan_verifikasi" rows="3" class="form-control form-control-sm" placeholder="Jelaskan alasan penolakan agar siswa dapat memperbaiki atau memilih tempat lain..." required></textarea>
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
</div>
@endsection
