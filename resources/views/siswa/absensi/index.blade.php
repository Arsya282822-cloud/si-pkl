@extends('layouts.app')
@section('title', 'Presensi Harian PKL (Geofencing GPS)')
@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <h3 class="fw-bold mb-1" style="color: var(--text-main);">Presensi Harian PKL</h3>
        <p class="text-muted mb-0" style="font-size: 0.875rem;">
            Verifikasi presensi kehadiran otomatis dengan radius GPS kantor mitra DUDI:
            <strong>{{ $penempatan->perusahaan->nama_perusahaan ?? 'DUDI' }}</strong>
        </p>
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

{{-- INFORMASI PENEMPATAN & GEOFENCING WIDGET --}}
<div class="row g-3 mb-4">
    <div class="col-lg-7">
        <div class="pro-card h-100 p-4" style="border-radius: 16px;">
            <div class="text-center py-2">
                @if($lockedByJournal && !$hari_ini)
                    <div class="p-3 py-4 bg-danger bg-opacity-10 border border-danger border-opacity-25 rounded-4 text-center">
                        <div class="rounded-circle bg-danger text-white p-3 d-inline-flex mb-2 shadow-sm">
                            <i class="ph ph-lock-key" style="font-size: 36px;"></i>
                        </div>
                        <h5 class="fw-bold text-danger mb-1">Presensi Hari Ini Terkunci!</h5>
                        <p class="text-dark small mb-2" style="max-width: 550px; margin: 0 auto;">
                            Sesuai aturan kedisiplinan PKL, Anda wajib mengisi jurnal harian untuk kehadiran tanggal:<br>
                            <span class="badge bg-danger text-white px-3 py-1.5 fs-6 mt-1">
                                <i class="ph ph-calendar-blank me-1"></i> {{ \Carbon\Carbon::parse($lockedByJournal->tanggal)->translatedFormat('l, d F Y') }}
                            </span>
                        </p>
                        <p class="text-muted small mb-3">
                            Silakan lengkapi jurnal kegiatan tanggal tersebut. Setelah disimpan, <strong>kunci presensi hari ini akan otomatis terbuka kembali</strong>.
                        </p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('siswa.jurnal.create', ['tanggal' => $lockedByJournal->tanggal]) }}" class="btn btn-danger px-4 py-2.5 shadow-sm d-inline-flex align-items-center gap-2" style="border-radius: 10px; font-weight: 600;">
                                <i class="ph ph-note-pencil" style="font-size: 20px;"></i> Lengkapi Jurnal Tanggal {{ \Carbon\Carbon::parse($lockedByJournal->tanggal)->format('d/m/Y') }} Sekarang
                            </a>
                        </div>
                    </div>
                @elseif(!$hari_ini)
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-3 d-inline-flex mb-3">
                        <i class="ph ph-fingerprint" style="font-size: 42px;"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Presensi Masuk Hari Ini</h5>
                    <p class="text-muted small mb-3">
                        Lokasi kantor: <strong>{{ $penempatan->perusahaan->nama_perusahaan }}</strong><br>
                        Maksimal toleransi jarak: <span class="badge bg-primary-subtle text-primary fw-bold">{{ $penempatan->perusahaan->radius_meter ?: 150 }} Meter</span>
                    </p>

                    {{-- LIVE DISTANCE INDICATOR BADGE --}}
                    <div id="distance-indicator" class="alert alert-secondary py-2 px-3 d-inline-flex align-items-center gap-2 mb-4" style="border-radius: 10px; font-size: 0.85rem;">
                        <span class="spinner-border spinner-border-sm text-secondary" role="status"></span>
                        <span>Mendeteksi jarak Anda ke kantor...</span>
                    </div>

                    <div class="d-flex justify-content-center flex-wrap gap-2">
                        <form id="form-absen-masuk" action="{{ route('siswa.absensi.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="lokasi" id="lokasi_masuk">
                            <button type="button" onclick="getLocationAndSubmit('form-absen-masuk', 'lokasi_masuk')" class="btn btn-success px-4 py-2.5 shadow-sm d-inline-flex align-items-center gap-2" id="btn-absen-masuk" style="border-radius: 10px; font-weight: 600;">
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
                        <span>Presensi masuk tercatat pukul <strong>{{ \Carbon\Carbon::parse($hari_ini->jam_masuk)->format('H:i') }} WIB</strong></span>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Presensi Pulang / Selesai Magang</h5>
                    <p class="text-muted small mb-3">Klik tombol di bawah saat jam kerja Anda di industri telah selesai.</p>

                    <div id="distance-indicator" class="alert alert-secondary py-2 px-3 d-inline-flex align-items-center gap-2 mb-4" style="border-radius: 10px; font-size: 0.85rem;">
                        <span class="spinner-border spinner-border-sm text-secondary" role="status"></span>
                        <span>Mendeteksi jarak Anda ke kantor...</span>
                    </div>

                    <form id="form-absen-keluar" action="{{ route('siswa.absensi.update', $hari_ini->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="lokasi" id="lokasi_keluar">
                        <button type="button" onclick="getLocationAndSubmit('form-absen-keluar', 'lokasi_keluar')" class="btn btn-danger px-4 py-2.5 shadow-sm d-inline-flex align-items-center gap-2" id="btn-absen-keluar" style="border-radius: 10px; font-weight: 600;">
                            <i class="ph ph-sign-out" style="font-size: 20px;"></i> Presensi Pulang (Keluar)
                        </button>
                    </form>
                @else
                    <div class="p-4 bg-success-subtle border border-success-subtle rounded-4 d-inline-block px-4 py-3">
                        <div class="rounded-circle bg-success text-white p-3 d-inline-flex mb-2">
                            <i class="ph ph-check-bold" style="font-size: 28px;"></i>
                        </div>
                        <h5 class="fw-bold text-success mb-1">Presensi Hari Ini Lengkap & Valid</h5>
                        @if($hari_ini->status == 'hadir')
                            <p class="mb-0 text-success-emphasis small">
                                Masuk: <strong>{{ \Carbon\Carbon::parse($hari_ini->jam_masuk)->format('H:i') }} WIB</strong> 
                                • Keluar: <strong>{{ \Carbon\Carbon::parse($hari_ini->jam_keluar)->format('H:i') }} WIB</strong>
                            </p>
                        @else
                            <p class="mb-0 text-success-emphasis small">Status: <strong>{{ ucfirst($hari_ini->status) }}</strong> ({{ $hari_ini->keterangan ?: '-' }})</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- MAP GEOFENCING RADAR --}}
    <div class="col-lg-5">
        <div class="pro-card h-100 p-3" style="border-radius: 16px;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-bold small text-dark d-flex align-items-center gap-1">
                    <i class="ph ph-radar text-primary fs-5"></i> Radar Geofencing DUDI
                </span>
                <span class="badge bg-light text-muted border" id="gps-accuracy-badge">GPS Akurasi: ...</span>
            </div>
            <div id="live-geofence-map" style="height: 220px; border-radius: 12px; border: 1px solid #cbd5e1;"></div>
            <div class="mt-2 d-flex justify-content-between align-items-center text-muted" style="font-size: 0.75rem;">
                <span>🟢 Lingkaran Hijau: Radius Kantor</span>
                <span>🔵 Pin Biru: Posisi Anda</span>
            </div>
        </div>
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
            Riwayat Log Presensi & Verifikasi Radius
        </h6>
    </div>
    <div class="p-0">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="table-light" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">
                    <tr>
                        <th class="ps-4">Hari & Tanggal</th>
                        <th>Status</th>
                        <th>Waktu Masuk & Jarak</th>
                        <th>Waktu Pulang & Jarak</th>
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
                            @if($item->jarak_masuk_meter !== null)
                                @if($item->status_lokasi_masuk === 'dalam_radius')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle mt-1" style="font-size: 0.72rem;">
                                        <i class="ph ph-check"></i> {{ $item->jarak_masuk_meter }}m (Dalam Radius)
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle mt-1" style="font-size: 0.72rem;">
                                        <i class="ph ph-warning"></i> {{ $item->jarak_masuk_meter }}m (Luar Radius)
                                    </span>
                                @endif
                            @elseif($item->lokasi_masuk)
                                <span class="badge bg-light text-muted border mt-1" style="font-size: 0.72rem;">
                                    {{ Str::limit($item->lokasi_masuk, 18) }}
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $item->jam_keluar ? $item->jam_keluar . ' WIB' : '-' }}</div>
                            @if($item->jarak_keluar_meter !== null)
                                @if($item->status_lokasi_keluar === 'dalam_radius')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle mt-1" style="font-size: 0.72rem;">
                                        <i class="ph ph-check"></i> {{ $item->jarak_keluar_meter }}m (Dalam Radius)
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle mt-1" style="font-size: 0.72rem;">
                                        <i class="ph ph-warning"></i> {{ $item->jarak_keluar_meter }}m (Luar Radius)
                                    </span>
                                @endif
                            @elseif($item->lokasi_keluar)
                                <span class="badge bg-light text-muted border mt-1" style="font-size: 0.72rem;">
                                    {{ Str::limit($item->lokasi_keluar, 18) }}
                                </span>
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

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
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

    // Geofencing Coordinates Setup
    const companyLat = {{ (float)($penempatan->perusahaan->latitude ?? 0.507068) }};
    const companyLng = {{ (float)($penempatan->perusahaan->longitude ?? 101.447779) }};
    const allowedRadius = {{ (int)($penempatan->perusahaan->radius_meter ?: 150) }};
    const companyName = "{{ addslashes($penempatan->perusahaan->nama_perusahaan ?? 'Kantor DUDI') }}";

    let radarMap = null;
    let userMarker = null;
    let companyCircle = null;

    // Haversine Formula (Client Side Distance Calculation)
    function calculateDistanceClient(lat1, lon1, lat2, lon2) {
        const R = 6371000;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLon/2) * Math.sin(dLon/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return Math.round(R * c);
    }

    document.addEventListener('DOMContentLoaded', function() {
        radarMap = L.map('live-geofence-map').setView([companyLat, companyLng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(radarMap);

        // Marker Kantor DUDI
        L.marker([companyLat, companyLng]).addTo(radarMap)
            .bindPopup(`<b>${companyName}</b><br>Radius: ${allowedRadius} Meter`)
            .openPopup();

        // Circle Radius Kantor
        companyCircle = L.circle([companyLat, companyLng], {
            radius: allowedRadius,
            color: '#16a34a',
            fillColor: '#4ade80',
            fillOpacity: 0.25
        }).addTo(radarMap);

        // Auto-detect student location on page load
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(pos) {
                const uLat = pos.coords.latitude;
                const uLng = pos.coords.longitude;
                const accuracy = Math.round(pos.coords.accuracy);

                document.getElementById('gps-accuracy-badge').textContent = `GPS Akurasi: ±${accuracy}m`;

                // Add User Marker
                userMarker = L.circleMarker([uLat, uLng], {
                    radius: 8,
                    color: '#0284c7',
                    fillColor: '#38bdf8',
                    fillOpacity: 0.9
                }).addTo(radarMap).bindPopup("Lokasi Anda Saat Ini");

                // Fit Bounds to show both company and user
                const bounds = L.latLngBounds([[companyLat, companyLng], [uLat, uLng]]);
                radarMap.fitBounds(bounds, { padding: [30, 30] });

                // Calculate Distance
                const dist = calculateDistanceClient(uLat, uLng, companyLat, companyLng);
                const indicator = document.getElementById('distance-indicator');

                if (indicator) {
                    if (dist <= allowedRadius) {
                        indicator.className = 'alert alert-success py-2 px-3 d-inline-flex align-items-center gap-2 mb-4';
                        indicator.innerHTML = `<i class="ph ph-check-circle text-success fs-5"></i> <span>Jarak Anda: <strong>${dist} Meter</strong> (Dalam Radius Kantor ✅)</span>`;
                    } else {
                        indicator.className = 'alert alert-warning py-2 px-3 d-inline-flex align-items-center gap-2 mb-4';
                        indicator.innerHTML = `<i class="ph ph-warning text-warning fs-5"></i> <span>Jarak Anda: <strong>${dist} Meter</strong> (Di Luar Radius Kantor ${allowedRadius}m ⚠️)</span>`;
                    }
                }
            }, function() {
                const indicator = document.getElementById('distance-indicator');
                if (indicator) {
                    indicator.className = 'alert alert-light py-2 px-3 d-inline-flex align-items-center gap-2 mb-4 border';
                    indicator.innerHTML = `<i class="ph ph-map-pin text-muted fs-5"></i> <span>Izin GPS belum aktif. Klik tombol presensi untuk menyalakan.</span>`;
                }
            }, { enableHighAccuracy: true, timeout: 10000 });
        }
    });

    function getLocationAndSubmit(formId, inputId) {
        const btn = document.getElementById(formId === 'form-absen-masuk' ? 'btn-absen-masuk' : 'btn-absen-keluar');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Memverifikasi Radius GPS...';
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
                { enableHighAccuracy: true, timeout: 8000, maximumAge: 30000 }
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
