@extends('layouts.app')
@section('title', 'Presensi Siswa Magang DUDI')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: var(--text-main);">Presensi Harian Peserta Magang</h3>
        <p class="text-muted mb-0" style="font-size: 0.875rem;">
            Pantau kehadiran siswa magang di <strong class="text-dark">{{ $perusahaan->nama }}</strong> secara realtime.
        </p>
    </div>
</div>

<!-- Summary Row -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="pro-card p-3 d-flex align-items-center gap-3 border-0 shadow-sm" style="border-radius: 12px; background: #ffffff;">
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: #dcfce7; color: #16a34a;">
                <i class="ph ph-check-circle" style="font-size: 22px;"></i>
            </div>
            <div>
                <p class="text-muted small mb-0">Hadir</p>
                <h5 class="fw-bold mb-0 text-success">{{ $summary['hadir'] }} <span class="fs-6 fw-normal text-muted">Siswa</span></h5>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="pro-card p-3 d-flex align-items-center gap-3 border-0 shadow-sm" style="border-radius: 12px; background: #ffffff;">
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: #fef3c7; color: #d97706;">
                <i class="ph ph-envelope-open" style="font-size: 22px;"></i>
            </div>
            <div>
                <p class="text-muted small mb-0">Izin</p>
                <h5 class="fw-bold mb-0 text-warning">{{ $summary['izin'] }} <span class="fs-6 fw-normal text-muted">Siswa</span></h5>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="pro-card p-3 d-flex align-items-center gap-3 border-0 shadow-sm" style="border-radius: 12px; background: #ffffff;">
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: #e0f2fe; color: #0284c7;">
                <i class="ph ph-first-aid" style="font-size: 22px;"></i>
            </div>
            <div>
                <p class="text-muted small mb-0">Sakit</p>
                <h5 class="fw-bold mb-0 text-info">{{ $summary['sakit'] }} <span class="fs-6 fw-normal text-muted">Siswa</span></h5>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="pro-card p-3 d-flex align-items-center gap-3 border-0 shadow-sm" style="border-radius: 12px; background: #ffffff;">
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: #fee2e2; color: #dc2626;">
                <i class="ph ph-x-circle" style="font-size: 22px;"></i>
            </div>
            <div>
                <p class="text-muted small mb-0">Alpha</p>
                <h5 class="fw-bold mb-0 text-danger">{{ $summary['alpha'] }} <span class="fs-6 fw-normal text-muted">Siswa</span></h5>
            </div>
        </div>
    </div>
</div>

<div class="pro-card p-4 border-0 shadow-sm" style="border-radius: 16px;">
    <!-- Filter -->
    <form method="GET" action="{{ route('instruktur.absensi.index') }}" class="row g-2 mb-4">
        <div class="col-md-4">
            <label class="form-label small text-muted mb-1">Tanggal Presensi</label>
            <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control" style="border-radius: 8px;">
        </div>
        <div class="col-md-3">
            <label class="form-label small text-muted mb-1">Status Kehadiran</label>
            <select name="status" class="form-select" style="border-radius: 8px;">
                <option value="">Semua Status</option>
                <option value="hadir" {{ request('status') === 'hadir' ? 'selected' : '' }}>Hadir</option>
                <option value="izin" {{ request('status') === 'izin' ? 'selected' : '' }}>Izin</option>
                <option value="sakit" {{ request('status') === 'sakit' ? 'selected' : '' }}>Sakit</option>
                <option value="alpha" {{ request('status') === 'alpha' ? 'selected' : '' }}>Alpha</option>
            </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100 fw-semibold" style="border-radius: 8px;">
                <i class="ph ph-funnel me-1"></i> Tampilkan
            </button>
        </div>
        @if(request('tanggal') || request('status'))
            <div class="col-md-2 d-flex align-items-end">
                <a href="{{ route('instruktur.absensi.index') }}" class="btn btn-light border w-100 text-muted" style="border-radius: 8px;">
                    Reset
                </a>
            </div>
        @endif
    </form>

    @if($absensis->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="ph ph-calendar-x mb-2" style="font-size: 40px; color: #cbd5e1;"></i>
            <h6 class="fw-bold text-dark">Tidak Ada Data Presensi</h6>
            <p class="small mb-0">Belum ada catatan presensi pada tanggal / kriteria yang dipilih.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Siswa</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Jam Masuk</th>
                        <th>Jam Pulang</th>
                        <th>Foto & Bukti</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($absensis as $index => $absen)
                        @php
                            $siswa = $absen->siswa;
                        @endphp
                        <tr>
                            <td>{{ $absensis->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $siswa?->nama ?? '-' }}</div>
                                <small class="text-muted">{{ $siswa?->kelas?->nama_kelas ?? $siswa?->jurusan?->nama_jurusan }}</small>
                            </td>
                            <td>
                                <span class="fw-semibold text-secondary">
                                    {{ \Carbon\Carbon::parse($absen->tanggal)->isoFormat('D MMM Y') }}
                                </span>
                            </td>
                            <td>
                                @if($absen->status === 'hadir')
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
                                @if($absen->jam_masuk)
                                    <span class="fw-semibold text-dark"><i class="ph ph-sign-in text-success me-1"></i>{{ \Carbon\Carbon::parse($absen->jam_masuk)->format('H:i') }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($absen->jam_pulang)
                                    <span class="fw-semibold text-dark"><i class="ph ph-sign-out text-danger me-1"></i>{{ \Carbon\Carbon::parse($absen->jam_pulang)->format('H:i') }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    @if($absen->foto_masuk)
                                        <a href="{{ asset('storage/' . $absen->foto_masuk) }}" target="_blank" class="badge bg-light text-primary border" title="Foto Masuk">
                                            <i class="ph ph-camera me-1"></i>Masuk
                                        </a>
                                    @endif
                                    @if($absen->foto_pulang)
                                        <a href="{{ asset('storage/' . $absen->foto_pulang) }}" target="_blank" class="badge bg-light text-secondary border" title="Foto Pulang">
                                            <i class="ph ph-camera me-1"></i>Pulang
                                        </a>
                                    @endif
                                    @if($absen->surat_izin)
                                        <a href="{{ asset('storage/' . $absen->surat_izin) }}" target="_blank" class="badge bg-light text-warning border" title="Surat Izin/Sakit">
                                            <i class="ph ph-file-text me-1"></i>Surat
                                        </a>
                                    @endif
                                    @if(!$absen->foto_masuk && !$absen->foto_pulang && !$absen->surat_izin)
                                        <span class="text-muted small">-</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="small text-secondary">{{ $absen->keterangan ?? '-' }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $absensis->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
