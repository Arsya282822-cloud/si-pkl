@extends('layouts.app')

@section('title', 'Detail Pengajuan PKL Mandiri')

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1 text-dark">Detail Pengajuan Tempat PKL</h4>
            <p class="text-muted small mb-0">Informasi lengkap dan status verifikasi usulan tempat PKL mandiri.</p>
        </div>
        <a href="{{ route('siswa.pengajuan.index') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
            <i class="ph ph-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="row g-4">
        <!-- Status Box & Timeline -->
        <div class="col-lg-4">
            <div class="pro-card p-4 mb-4">
                <h6 class="fw-bold text-dark mb-3">Status Pengajuan</h6>
                <div class="text-center p-3 rounded-3 mb-4" style="background: {{ $pengajuan->status === 'disetujui' ? '#ecfdf5' : ($pengajuan->status === 'ditolak' ? '#fef2f2' : '#fefce8') }};">
                    @if($pengajuan->status === 'disetujui')
                        <div class="display-6 text-success mb-2"><i class="ph ph-check-circle"></i></div>
                        <h6 class="fw-bold text-success mb-1">DISETUJUI</h6>
                        <small class="text-muted">Tempat PKL telah disetujui & penempatan aktif.</small>
                    @elseif($pengajuan->status === 'ditolak')
                        <div class="display-6 text-danger mb-2"><i class="ph ph-x-circle"></i></div>
                        <h6 class="fw-bold text-danger mb-1">DITOLAK</h6>
                        <small class="text-muted">Silakan periksa catatan atau ajukan tempat lain.</small>
                    @else
                        <div class="display-6 text-warning mb-2"><i class="ph ph-hourglass-medium"></i></div>
                        <h6 class="fw-bold text-warning mb-1">MENUNGGU VERIFIKASI</h6>
                        <small class="text-muted">Sedang ditinjau oleh Koordinator / Guru PKL.</small>
                    @endif
                </div>

                <!-- Verification Notes -->
                @if($pengajuan->catatan_verifikasi)
                    <div class="p-3 rounded-3 border mb-3 bg-light">
                        <small class="fw-bold text-dark d-block mb-1">Catatan Tim Pokja PKL:</small>
                        <p class="small text-muted mb-1">{{ $pengajuan->catatan_verifikasi }}</p>
                        @if($pengajuan->verifikator)
                            <small class="text-muted fst-italic" style="font-size: 0.75rem;">Oleh: {{ $pengajuan->verifikator->name }} ({{ $pengajuan->diverifikasi_pada?->format('d M Y, H:i') }})</small>
                        @endif
                    </div>
                @endif

                <!-- Progress Steps -->
                <div class="border-top pt-3">
                    <h6 class="fw-bold text-dark mb-3" style="font-size: 0.85rem;">Tahapan Verifikasi</h6>
                    <div class="timeline-simple">
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 24px; height: 24px; font-size: 12px;">
                                <i class="ph ph-check"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark" style="font-size: 0.85rem;">Pengajuan Terkirim</div>
                                <div class="text-muted" style="font-size: 0.75rem;">{{ $pengajuan->created_at->format('d M Y, H:i') }}</div>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3 mb-3">
                            <div class="rounded-circle {{ $pengajuan->status !== 'menunggu' ? 'bg-success text-white' : 'bg-warning text-dark' }} d-flex align-items-center justify-content-center flex-shrink-0" style="width: 24px; height: 24px; font-size: 12px;">
                                <i class="ph {{ $pengajuan->status !== 'menunggu' ? 'ph-check' : 'ph-hourglass' }}"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark" style="font-size: 0.85rem;">Review Pokja PKL</div>
                                <div class="text-muted" style="font-size: 0.75rem;">Pemeriksaan kesesuaian jurusan</div>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3">
                            <div class="rounded-circle {{ $pengajuan->status === 'disetujui' ? 'bg-success text-white' : ($pengajuan->status === 'ditolak' ? 'bg-danger text-white' : 'bg-secondary text-white') }} d-flex align-items-center justify-content-center flex-shrink-0" style="width: 24px; height: 24px; font-size: 12px;">
                                <i class="ph {{ $pengajuan->status === 'disetujui' ? 'ph-check' : ($pengajuan->status === 'ditolak' ? 'ph-x' : 'ph-circle') }}"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark" style="font-size: 0.85rem;">Keputusan Akhir</div>
                                <div class="text-muted" style="font-size: 0.75rem;">
                                    @if($pengajuan->status === 'disetujui') Disetujui & Ditempatkan @elseif($pengajuan->status === 'ditolak') Ditolak @else Menunggu Keputusan @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Data -->
        <div class="col-lg-8">
            <div class="pro-card p-4">
                <h5 class="fw-bold text-dark mb-4 pb-2 border-bottom d-flex align-items-center gap-2">
                    <i class="ph ph-buildings text-primary"></i> Data Usulan Perusahaan Mitra
                </h5>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="text-muted small d-block">Nama Perusahaan / Instansi</label>
                        <span class="fw-bold text-dark fs-6">{{ $pengajuan->nama_perusahaan }}</span>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small d-block">Bidang Usaha</label>
                        <span class="fw-semibold text-dark">{{ $pengajuan->bidang_usaha ?? '-' }}</span>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small d-block">Alamat Lengkap</label>
                        <span class="text-dark">{{ $pengajuan->alamat_perusahaan }} ({{ $pengajuan->kota ?? 'Pekanbaru' }})</span>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small d-block">Nama Pimpinan</label>
                        <span class="text-dark">{{ $pengajuan->nama_pimpinan ?? '-' }}</span>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small d-block">Periode PKL</label>
                        <span class="badge bg-light text-dark border">{{ $pengajuan->periode->nama_periode ?? '-' }}</span>
                    </div>
                </div>

                <h5 class="fw-bold text-dark mb-4 pb-2 border-bottom d-flex align-items-center gap-2">
                    <i class="ph ph-identification-card text-primary"></i> Kontak Person & Alasan
                </h5>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="text-muted small d-block">Nama Kontak Person (HRD/PIC)</label>
                        <span class="fw-semibold text-dark">{{ $pengajuan->kontak_person ?? '-' }}</span>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small d-block">Nomor Telepon / WhatsApp</label>
                        <span class="text-dark">{{ $pengajuan->no_telepon ?? '-' }}</span>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small d-block">Email Perusahaan</label>
                        <span class="text-dark">{{ $pengajuan->email ?? '-' }}</span>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small d-block">Alasan Memilih Tempat Ini</label>
                        <div class="p-3 bg-light rounded-3 text-dark small" style="white-space: pre-line;">
                            {{ $pengajuan->alasan_memilih ?? 'Tidak ada alasan khusus yang dicantumkan.' }}
                        </div>
                    </div>
                    @if($pengajuan->file_surat_balasan)
                        <div class="col-12">
                            <label class="text-muted small d-block">Lampiran Berkas / Surat Balasan</label>
                            <a href="{{ asset($pengajuan->file_surat_balasan) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-1 d-inline-flex align-items-center gap-2">
                                <i class="ph ph-file-arrow-down"></i> Lihat / Unduh Dokumen Balasan
                            </a>
                        </div>
                    @endif
                </div>

                @if($pengajuan->status === 'disetujui')
                    <div class="alert alert-success d-flex align-items-center justify-content-between p-3 mb-0" style="border-radius: 12px;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="ph ph-check-circle fs-4"></i>
                            <span>Selamat! Penempatan Anda telah aktif. Silakan buka menu <strong>Tempat PKL</strong> atau <strong>Absensi</strong>.</span>
                        </div>
                        <a href="{{ route('siswa.penempatan.index') }}" class="btn btn-sm btn-success fw-bold">Buka Tempat PKL</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
