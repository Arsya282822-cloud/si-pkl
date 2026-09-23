@extends('layouts.app')
@section('title', 'Absensi Harian PKL')
@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <h3 class="fw-bold mb-1" style="color: var(--text-main);">Presensi Harian PKL</h3>
        <p class="text-muted mb-0" style="font-size: 0.875rem;">Catat kehadiran Anda setiap hari kerja industri secara realtime menggunakan verifikasi GPS.</p>
    </div>
    <div class="col-md-5 text-end mt-3 mt-md-0">
        <div class="pro-card p-3 text-center border-0 text-white" style="background: linear-gradient(135deg, #0c4a6e 0%, #0284c7 100%); border-radius: 14px;">
            <div class="d-flex justify-content-center align-items-center gap-2 mb-1">
                <i class="ph ph-clock" style="font-size: 24px;"></i>
                <h3 class="mb-0 fw-bold font-monospace" id="live-clock" style="letter-spacing: 2px;">00:00:00</h3>
                <span class="badge bg-white text-dark fw-bold px-2 py-0.5 rounded-pill" style="font-size: 0.7rem;">WIB</span>
            </div>
            <small class="text-white-50"><i class="ph ph-calendar-blank me-1"></i>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</small>
        </div>
    </div>
</div>

<div class="pro-card mb-4" style="border-radius: 16px;">
    <div class="p-4 p-md-5 text-center">
        @if(!$hari_ini)
            <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-3 d-inline-flex mb-3">
                <i class="ph ph-fingerprint" style="font-size: 42px;"></i>
            </div>
            <h5 class="fw-bold text-dark mb-2">Presensi Masuk Hari Ini</h5>
            <p class="text-muted small mb-4">Pastikan Anda berada di lokasi DUDI dan browser memiliki izin akses lokasi GPS.</p>
            <div class="d-flex justify-content-center flex-wrap gap-3">
                <form id="form-absen-masuk" action="{{ route('siswa.absensi.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="lokasi" id="lokasi_masuk">
                    <button type="button" onclick="getLocationAndSubmit('form-absen-masuk', 'lokasi_masuk')" class="btn btn-success px-4 py-2.5 shadow-sm d-inline-flex align-items-center gap-2" id="btn-absen-masuk" style="border-radius: 10px; font-weight: 600; font-size: 1rem;">
                        <i class="ph ph-sign-in" style="font-size: 20px;"></i> Presensi Masuk Sekarang
                    </button>
                </form>
                <a href="{{ route('siswa.absensi.create') }}" class="btn btn-outline-secondary px-4 py-2.5 d-inline-flex align-items-center gap-1.5" style="border-radius: 10px; font-weight: 500;">
                    <i class="ph ph-envelope-simple"></i> Ajukan Izin / Sakit
                </a>
            </div>
        @elseif($hari_ini->status == 'hadir' && !$hari_ini->jam_keluar)
            <div class="p-3 bg-info-subtle border border-info-subtle rounded-3 mb-3 d-inline-flex align-items-center gap-2 text-info">
                <i class="ph ph-check-circle" style="font-size: 20px;"></i>
                <span>Anda sudah presensi masuk pada pukul <strong>{{ \Carbon\Carbon::parse($hari_ini->jam_masuk)->format('H:i') }} WIB</strong></span>
            </div>
            <h5 class="fw-bold text-dark mb-2">Presensi Pulang / Selesai Bekerja</h5>
            <p class="text-muted small mb-4">Lakukan presensi keluar saat jam operasional magang telah berakhir.</p>
            <form id="form-absen-keluar" action="{{ route('siswa.absensi.update', $hari_ini->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="lokasi" id="lokasi_keluar">
                <button type="button" onclick="getLocationAndSubmit('form-absen-keluar', 'lokasi_keluar')" class="btn btn-danger px-4 py-2.5 shadow-sm d-inline-flex align-items-center gap-2" id="btn-absen-keluar" style="border-radius: 10px; font-weight: 600; font-size: 1rem;">
                    <i class="ph ph-sign-out" style="font-size: 20px;"></i> Presensi Pulang (Keluar)
                </button>
            </form>
        @else
            <div class="p-4 bg-success-subtle border border-success-subtle rounded-4 d-inline-block px-4 py-3" style="max-width: 600px;">
                <div class="rounded-circle bg-success text-white p-3 d-inline-flex mb-2">
                    <i class="ph ph-check-bold" style="font-size: 28px;"></i>
                </div>
                <h5 class="fw-bold text-success mb-1">Presensi Hari Ini Lengkap & Tuntas</h5>
                @if($hari_ini->status == 'hadir')
                    <p class="mb-0 text-success-emphasis small">
                        Terima kasih atas dedikasi dan kerja keras Anda hari ini! <br>
                        (Masuk: <strong>{{ \Carbon\Carbon::parse($hari_ini->jam_masuk)->format('H:i') }} WIB</strong> • Keluar: <strong>{{ \Carbon\Carbon::parse($hari_ini->jam_keluar)->format('H:i') }} WIB</strong>)
                    </p>
                @else
                    <p class="mb-0 text-success-emphasis small">Status kehadiran hari ini tercatat: <strong>{{ ucfirst($hari_ini->status) }}</strong> ({{ $hari_ini->keterangan ?: 'Izin disetujui' }})</p>
                @endif
            </div>
        @endif
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="border-radius: 10px;">
    <i class="ph ph-check-circle me-1 fw-bold"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger mb-4" style="border-radius: 10px;">{{ session('error') }}</div>
@endif

{{-- Rekap Kehadiran Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="pro-card p-3 text-center" style="border-radius: 14px; background: #f0fdf4; border: 1px solid #bbf7d0;">
            <div class="text-success fw-bold fs-3 mb-0">{{ $rekap['hadir'] }}</div>
            <small class="text-muted fw-semibold">Hari Hadir</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="pro-card p-3 text-center" style="border-radius: 14px; background: #f0f9ff; border: 1px solid #bae6fd;">
            <div class="text-info fw-bold fs-3 mb-0">{{ $rekap['izin'] }}</div>
            <small class="text-muted fw-semibold">Hari Izin</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="pro-card p-3 text-center" style="border-radius: 14px; background: #fefce8; border: 1px solid #fef08a;">
            <div class="text-warning fw-bold fs-3 mb-0">{{ $rekap['sakit'] }}</div>
            <small class="text-muted fw-semibold">Hari Sakit</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="pro-card p-3 text-center" style="border-radius: 14px; background: #fef2f2; border: 1px solid #fecaca;">
            <div class="text-danger fw-bold fs-3 mb-0">{{ $rekap['alpha'] }}</div>
            <small class="text-muted fw-semibold">Hari Alpha</small>
        </div>
    </div>
</div>

<div class="pro-card" style="border-radius: 16px;">
    <div class="p-3 px-4 bg-light border-bottom d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
            <i class="ph ph-clock-counter-clockwise text-primary" style="font-size: 20px;"></i>
            Riwayat Log Presensi
        </h6>
    </div>
    <div class="p-0">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="table-light" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">
                    <tr>
                        <th class="ps-4">Hari & Tanggal</th>
                        <th>Status Kehadiran</th>
                        <th>Waktu Masuk & GPS</th>
                        <th>Waktu Pulang & GPS</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensi as $item)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-2">
                                <i class="ph ph-calendar text-primary" style="font-size: 18px;"></i>
                                <span class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d M Y') }}</span>
                            </div>
                        </td>
                        <td>
                            @if($item->status == 'hadir')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1" style="border-radius: 6px; font-weight: 600;">
                                    <i class="ph ph-check-circle me-1"></i> Hadir
                                </span>
                            @elseif($item->status == 'izin')
                                <span class="badge bg-info-subtle text-info border border-info-subtle px-2.5 py-1" style="border-radius: 6px; font-weight: 600;">
                                    <i class="ph ph-info me-1"></i> Izin
                                </span>
                            @elseif($item->status == 'sakit')
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1" style="border-radius: 6px; font-weight: 600;">
                                    <i class="ph ph-first-aid me-1"></i> Sakit
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1" style="border-radius: 6px; font-weight: 600;">
                                    <i class="ph ph-x-circle me-1"></i> Alpha
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $item->jam_masuk ? $item->jam_masuk . ' WIB' : '-' }}</div>
                            @if($item->lokasi_masuk)
                                <a href="https://maps.google.com/?q={{ $item->lokasi_masuk }}" target="_blank" class="badge bg-primary-subtle text-primary border border-primary-subtle text-decoration-none mt-1 d-inline-flex align-items-center gap-1" style="border-radius: 4px; font-size: 0.72rem;">
                                    <i class="ph ph-map-pin"></i> Peta GPS
                                </a>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $item->jam_keluar ? $item->jam_keluar . ' WIB' : '-' }}</div>
                            @if($item->lokasi_keluar)
                                <a href="https://maps.google.com/?q={{ $item->lokasi_keluar }}" target="_blank" class="badge bg-primary-subtle text-primary border border-primary-subtle text-decoration-none mt-1 d-inline-flex align-items-center gap-1" style="border-radius: 4px; font-size: 0.72rem;">
                                    <i class="ph ph-map-pin"></i> Peta GPS
                                </a>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted small">{{ $item->keterangan ?: '-' }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="ph ph-calendar-blank fs-1 d-block mb-2 opacity-50"></i>
                            Belum ada riwayat data presensi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($absensi->hasPages())
    <div class="p-3 border-top">
        {{ $absensi->links() }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        const clockElem = document.getElementById('live-clock');
        if(clockElem) clockElem.textContent = `${hours}:${minutes}:${seconds}`;
    }
    setInterval(updateClock, 1000);
    updateClock();

    function getLocationAndSubmit(formId, inputId) {
        const btn = document.getElementById(formId === 'form-absen-masuk' ? 'btn-absen-masuk' : 'btn-absen-keluar');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mendeteksi Lokasi GPS...';
        btn.disabled = true;

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    document.getElementById(inputId).value = lat + ',' + lon;
                    document.getElementById(formId).submit();
                },
                function(error) {
                    let errorMsg = 'Gagal mendeteksi koordinat GPS.';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMsg = 'Akses lokasi ditolak oleh browser/perangkat Anda.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMsg = 'Informasi lokasi perangkat tidak tersedia atau sinyal GPS lemah.';
                            break;
                        case error.TIMEOUT:
                            errorMsg = 'Waktu permintaan lokasi habis (Timeout).';
                            break;
                    }

                    const tetapAbsen = confirm(
                        errorMsg + '\n\n' +
                        'Tips: Klik ikon gembok/pengaturan di sebelah kiri URL browser untuk mengizinkan (Allow) Akses Lokasi.\n\n' +
                        'Apakah Anda ingin TETAP MELANJUTKAN presensi tanpa koordinat GPS?'
                    );

                    if (tetapAbsen) {
                        document.getElementById(inputId).value = 'Tanpa GPS (Izin Tidak Diberikan)';
                        document.getElementById(formId).submit();
                    } else {
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }
                },
                { enableHighAccuracy: false, timeout: 8000, maximumAge: 60000 }
            );
        } else {
            const tetapAbsen = confirm('Browser Anda tidak mendukung GPS Geolocation. Tetap lanjutkan presensi?');
            if (tetapAbsen) {
                document.getElementById(inputId).value = 'Browser Tidak Mendukung GPS';
                document.getElementById(formId).submit();
            } else {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        }
    }
</script>
@endpush

