@extends('layouts.app')
@section('title', 'Dashboard Siswa')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: var(--text-main);">Beranda Siswa PKL</h3>
        <p class="text-muted mb-0" style="font-size: 0.875rem;">Selamat datang, <strong class="text-dark">{{ Auth::user()->name }}</strong>! Kelola presensi harian, jurnal kegiatan, dan pantau status PKL Anda.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('siswa.absensi.index') }}" class="btn btn-primary d-inline-flex align-items-center gap-1.5 shadow-sm" style="border-radius: 10px; font-weight: 600;">
            <i class="ph ph-calendar-check" style="font-size: 18px;"></i>
            <span>Presensi Harian</span>
        </a>
        <a href="{{ route('siswa.jurnal.create') }}" class="btn btn-outline-primary d-inline-flex align-items-center gap-1.5" style="border-radius: 10px; font-weight: 600;">
            <i class="ph ph-plus-circle" style="font-size: 18px;"></i>
            <span>Tulis Jurnal</span>
        </a>
    </div>
</div>

<!-- Announcements Banner/Section -->
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

<div class="row g-4">
    <div class="col-lg-8">
        {{-- Status Penempatan --}}
        <div class="pro-card p-4 h-100" style="border-radius: 16px;">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    <i class="ph ph-buildings text-primary" style="font-size: 22px;"></i>
                    Status Penempatan PKL
                </h5>
                @if($penempatan)
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill" style="font-weight: 600;">
                        <i class="ph ph-check-circle me-1"></i> Aktif Magang
                    </span>
                @endif
            </div>

            @if($penempatan)
                <div class="p-3 bg-primary-subtle border border-primary-subtle rounded-3 mb-4 d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary text-white p-2.5 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                        <i class="ph ph-calendar" style="font-size: 22px;"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block" style="font-size: 0.75rem;">Periode Pelaksanaan PKL</small>
                        <strong class="text-dark">{{ $penempatan->periodePkl->nama_periode }}</strong>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 border h-100">
                            <span class="d-block text-muted small mb-1 fw-semibold"><i class="ph ph-buildings text-primary me-1"></i> Perusahaan / DUDI Mitra</span>
                            <h6 class="fw-bold mb-1 text-dark">{{ $penempatan->perusahaan->nama_perusahaan }}</h6>
                            <small class="text-muted"><i class="ph ph-map-pin me-1"></i>{{ $penempatan->perusahaan->alamat ?: 'Pekanbaru' }}</small>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 border h-100">
                            <span class="d-block text-muted small mb-1 fw-semibold"><i class="ph ph-graduation-cap text-success me-1"></i> Guru Pembimbing Sekolah</span>
                            <h6 class="fw-bold mb-1 text-dark">{{ $penempatan->guru->nama }}</h6>
                            <small class="text-muted"><i class="ph ph-phone me-1"></i>{{ $penempatan->guru->no_hp ?: '-' }}</small>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2 pt-3 border-top">
                    <a href="{{ route('siswa.jurnal.index') }}" class="btn btn-primary d-inline-flex align-items-center gap-1.5" style="border-radius: 8px; font-weight: 600;">
                        <i class="ph ph-book-open"></i> Buka Jurnal Kegiatan
                    </a>
                    <a href="{{ route('siswa.penempatan.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1.5" style="border-radius: 8px;">
                        <i class="ph ph-info"></i> Detail Tempat PKL
                    </a>
                    <a href="{{ route('siswa.cetak.jurnal') }}" target="_blank" class="btn btn-outline-primary d-inline-flex align-items-center gap-1.5 ms-auto" style="border-radius: 8px;">
                        <i class="ph ph-printer"></i> Cetak Jurnal Lengkap
                    </a>
                </div>
            @else
                @if($pengajuanAktif && $pengajuanAktif->status === 'menunggu')
                    <div class="alert alert-warning border-0 p-4 rounded-3 text-start mb-0" style="background-color: #fefce8; border-left: 5px solid #f59e0b !important;">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div style="width: 42px; height: 42px; border-radius: 50%; background: #fef08a; color: #ca8a04; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                                <i class="ph ph-hourglass-medium"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">Pengajuan Tempat PKL Sedang Diverifikasi</h6>
                                <span class="text-muted small">Usulan DUDI: <strong>{{ $pengajuanAktif->nama_perusahaan }}</strong> ({{ $pengajuanAktif->kota ?? 'Pekanbaru' }})</span>
                            </div>
                        </div>
                        <p class="small text-muted mb-3 mt-2">
                            Tim Pokja PKL sedang meninjau kelengkapan berkas dan kesesuaian keahlian tempat PKL yang Anda ajukan.
                        </p>
                        <a href="{{ route('siswa.pengajuan.show', $pengajuanAktif->id) }}" class="btn btn-sm btn-warning text-dark fw-bold px-3" style="border-radius: 8px;">
                            <i class="ph ph-eye me-1"></i> Pantau Status Pengajuan
                        </a>
                    </div>
                @elseif($pengajuanAktif && $pengajuanAktif->status === 'ditolak')
                    <div class="alert alert-danger border-0 p-4 rounded-3 text-start mb-0" style="background-color: #fef2f2; border-left: 5px solid #ef4444 !important;">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div style="width: 42px; height: 42px; border-radius: 50%; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                                <i class="ph ph-x-circle"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">Pengajuan Sebelumnya Ditolak</h6>
                                <span class="text-muted small">Usulan: <strong>{{ $pengajuanAktif->nama_perusahaan }}</strong></span>
                            </div>
                        </div>
                        <p class="small text-muted mb-3 mt-2">
                            <strong>Catatan Verifikator:</strong> {{ $pengajuanAktif->catatan_verifikasi ?? 'Keahlian belum sesuai.' }}
                        </p>
                        <a href="{{ route('siswa.pengajuan.create') }}" class="btn btn-sm btn-danger text-white fw-bold px-3" style="border-radius: 8px;">
                            <i class="ph ph-arrow-clockwise me-1"></i> Ajukan Tempat Baru
                        </a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <div class="mb-3 text-muted" style="font-size: 48px;">
                            <i class="ph ph-buildings"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">Belum Memiliki Tempat PKL</h5>
                        <p class="text-muted small mb-4">
                            Anda belum ditempatkan oleh sekolah. Anda dapat menunggu penempatan dari Koordinator PKL atau mengajukan usulan tempat PKL secara mandiri.
                        </p>
                        <div class="d-inline-flex gap-2">
                            <a href="{{ route('siswa.pengajuan.create') }}" class="btn btn-primary px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1.5 shadow-sm" style="border-radius: 8px;">
                                <i class="ph ph-hand-pointing"></i> Ajukan Tempat PKL Mandiri
                            </a>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>

    {{-- Profil Siswa & Info Penting --}}
    <div class="col-lg-4">
        <div class="pro-card p-4 h-100" style="border-radius: 16px;">
            <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                <div class="rounded-circle bg-primary bg-opacity-10 text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 1.2rem;">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-dark">{{ Auth::user()->name }}</h6>
                    <small class="text-muted">{{ Auth::user()->email }}</small>
                </div>
            </div>

            <h6 class="fw-bold mb-3 d-flex align-items-center gap-2 text-dark">
                <i class="ph ph-user-focus text-primary" style="font-size: 20px;"></i>
                Identitas Peserta Didik
            </h6>

            @if(Auth::user()->siswa)
                <div class="d-flex flex-column gap-2.5">
                    <div class="p-2.5 bg-light rounded-3 d-flex justify-content-between align-items-center">
                        <span class="text-muted small">NIS</span>
                        <strong class="text-dark">{{ Auth::user()->siswa->nis }}</strong>
                    </div>
                    <div class="p-2.5 bg-light rounded-3 d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Kelas / Rombel</span>
                        <strong class="text-dark">{{ Auth::user()->siswa->kelas->nama_kelas ?? '-' }}</strong>
                    </div>
                    <div class="p-2.5 bg-light rounded-3 d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Konsentrasi Keahlian</span>
                        <strong class="text-dark">{{ Auth::user()->siswa->jurusan->nama_jurusan ?? '-' }} ({{ Auth::user()->siswa->jurusan->kode_jurusan ?? '-' }})</strong>
                    </div>
                </div>
            @else
                <div class="alert alert-warning text-sm mb-0" style="border-radius: 8px;">
                    Data profil siswa Anda belum lengkap terhubung. Silakan hubungi Administrator.
                </div>
            @endif

            <div class="mt-4 pt-3 border-top">
                <a href="{{ route('bantuan.index') }}" class="btn btn-sm btn-light border text-dark w-100 d-inline-flex align-items-center justify-content-center gap-1.5" style="border-radius: 8px; font-weight: 500;">
                    <i class="ph ph-question"></i> Butuh Bantuan / Hubungi Guru
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

