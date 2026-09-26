@extends('layouts.app')
@section('title', 'Presensi Harian PKL (Geofencing GPS & Foto Selfie)')
@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <h3 class="fw-bold mb-1" style="color: var(--text-main);">Presensi Harian PKL</h3>
        <p class="text-muted mb-0" style="font-size: 0.875rem;">
            Verifikasi kehadiran ganda dengan <strong>Geofencing Radius GPS</strong> dan <strong>Foto Selfie Real-time</strong> di mitra DUDI:
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
                    <div id="distance-indicator" class="p-2.5 px-3.5 d-inline-flex align-items-center gap-2 mb-4 rounded-3 border" style="font-size: 0.85rem; background: #f8fafc;">
                        <span class="spinner-border spinner-border-sm text-secondary" role="status"></span>
                        <span>Mendeteksi jarak Anda ke kantor...</span>
                    </div>

                    <div class="d-flex justify-content-center flex-wrap gap-2">
                        <button type="button" onclick="startSelfieAttendance('masuk')" class="btn btn-success px-4 py-2.5 shadow-sm d-inline-flex align-items-center gap-2" id="btn-trigger-masuk" style="border-radius: 10px; font-weight: 600;">
                            <i class="ph ph-camera" style="font-size: 20px;"></i> Foto Selfie & Presensi Masuk
                        </button>
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
                    <p class="text-muted small mb-3">Ambil foto selfie di kantor saat jam kerja PKL Anda hari ini telah selesai.</p>

                    <div id="distance-indicator" class="p-2.5 px-3.5 d-inline-flex align-items-center gap-2 mb-4 rounded-3 border" style="font-size: 0.85rem; background: #f8fafc;">
                        <span class="spinner-border spinner-border-sm text-secondary" role="status"></span>
                        <span>Mendeteksi jarak Anda ke kantor...</span>
                    </div>

                    <div>
                        <button type="button" onclick="startSelfieAttendance('keluar')" class="btn btn-danger px-4 py-2.5 shadow-sm d-inline-flex align-items-center gap-2" id="btn-trigger-keluar" style="border-radius: 10px; font-weight: 600;">
                            <i class="ph ph-camera" style="font-size: 20px;"></i> Foto Selfie & Presensi Pulang
                        </button>
                    </div>
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
                        <th>Waktu Masuk & Selfie</th>
                        <th>Waktu Pulang & Selfie</th>
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
                            <div class="d-flex flex-wrap align-items-center gap-1 mt-1">
                                @if($item->jarak_masuk_meter !== null)
                                    @if($item->status_lokasi_masuk === 'dalam_radius')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.72rem;">
                                            <i class="ph ph-check"></i> {{ $item->jarak_masuk_meter }}m (Radius Valid)
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle" style="font-size: 0.72rem;">
                                            <i class="ph ph-warning"></i> {{ $item->jarak_masuk_meter }}m (Luar Radius)
                                        </span>
                                    @endif
                                @endif

                                @if($item->foto_masuk)
                                    <a href="javascript:void(0)" onclick="openPhotoModal('{{ asset('storage/'.$item->foto_masuk) }}', 'Foto Selfie Masuk - {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }} ({{ $item->jam_masuk }} WIB)')" class="badge bg-primary-subtle text-primary border border-primary-subtle text-decoration-none d-inline-flex align-items-center gap-1" style="font-size: 0.72rem;">
                                        <i class="ph ph-camera"></i> Lihat Selfie
                                    </a>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $item->jam_keluar ? $item->jam_keluar . ' WIB' : '-' }}</div>
                            <div class="d-flex flex-wrap align-items-center gap-1 mt-1">
                                @if($item->jarak_keluar_meter !== null)
                                    @if($item->status_lokasi_keluar === 'dalam_radius')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.72rem;">
                                            <i class="ph ph-check"></i> {{ $item->jarak_keluar_meter }}m (Radius Valid)
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle" style="font-size: 0.72rem;">
                                            <i class="ph ph-warning"></i> {{ $item->jarak_keluar_meter }}m (Luar Radius)
                                        </span>
                                    @endif
                                @endif

                                @if($item->foto_keluar)
                                    <a href="javascript:void(0)" onclick="openPhotoModal('{{ asset('storage/'.$item->foto_keluar) }}', 'Foto Selfie Pulang - {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }} ({{ $item->jam_keluar }} WIB)')" class="badge bg-danger-subtle text-danger border border-danger-subtle text-decoration-none d-inline-flex align-items-center gap-1" style="font-size: 0.72rem;">
                                        <i class="ph ph-camera"></i> Lihat Selfie
                                    </a>
                                @endif
                            </div>
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

{{-- HIDDEN FORMS FOR ATTENDANCE SUBMISSION --}}
<form id="form-absen-masuk" action="{{ route('siswa.absensi.store') }}" method="POST" enctype="multipart/form-data" style="display:none;">
    @csrf
    <input type="hidden" name="lokasi" id="lokasi_masuk">
    <input type="hidden" name="foto_masuk" id="foto_masuk">
</form>

@if($hari_ini)
<form id="form-absen-keluar" action="{{ route('siswa.absensi.update', $hari_ini->id) }}" method="POST" enctype="multipart/form-data" style="display:none;">
    @csrf
    @method('PUT')
    <input type="hidden" name="lokasi" id="lokasi_keluar">
    <input type="hidden" name="foto_keluar" id="foto_keluar">
</form>
@endif

{{-- MODAL LIVE WEBRTC SELFIE CAMERA --}}
<div class="modal fade" id="modalSelfieAttendance" tabindex="-1" aria-labelledby="modalSelfieAttendanceLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header border-0 bg-dark text-white px-4 py-3">
                <div>
                    <h6 class="modal-title fw-bold d-flex align-items-center gap-2 mb-0" id="modalSelfieAttendanceLabel">
                        <i class="ph ph-camera fs-5 text-info"></i> <span id="camera-modal-title">Foto Selfie Presensi</span>
                    </h6>
                    <small class="text-white-50" style="font-size: 0.75rem;">Posisikan wajah Anda dengan jelas di depan kamera</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" onclick="closeCameraStream()"></button>
            </div>
            <div class="modal-body p-4 bg-light text-center">
                {{-- STATUS DISTANCE & GPS BADGE INSIDE MODAL --}}
                <div id="modal-distance-badge" class="p-2 px-3 rounded-pill bg-white border shadow-sm mb-3 d-inline-flex align-items-center gap-2 small text-muted">
                    <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                    <span>Mengunci sinyal GPS & Jarak...</span>
                </div>

                {{-- CAMERA VIEWFINDER CONTAINER --}}
                <div class="position-relative mx-auto bg-black rounded-4 overflow-hidden shadow-sm" style="max-width: 400px; aspect-ratio: 4/3;">
                    {{-- Live Video Stream --}}
                    <video id="webcam-video" autoplay playsinline muted style="width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1);"></video>

                    {{-- Image Preview after snap --}}
                    <img id="selfie-preview-img" src="" alt="Pratinjau Selfie" style="display: none; width: 100%; height: 100%; object-fit: cover;">

                    {{-- Hidden Canvas for Capture Processing --}}
                    <canvas id="webcam-canvas" style="display: none;"></canvas>

                    {{-- Viewfinder Framing Guidelines --}}
                    <div id="camera-overlay-frame" class="position-absolute top-0 start-0 w-100 h-100 pointer-events-none d-flex flex-column justify-content-between p-3" style="border: 2px dashed rgba(255,255,255,0.4); border-radius: 16px; pointer-events: none;">
                        <div class="d-flex justify-content-between">
                            <span class="badge bg-dark bg-opacity-75 text-white fw-normal" style="font-size: 0.7rem;"><i class="ph ph-circle text-danger me-1"></i> LIVE</span>
                            <span class="badge bg-dark bg-opacity-75 text-white fw-normal" style="font-size: 0.7rem;" id="modal-live-clock">00:00:00 WIB</span>
                        </div>
                        <div class="text-center">
                            <span class="badge bg-dark bg-opacity-75 text-white-50 fw-normal px-2 py-1" style="font-size: 0.7rem;">SMK LABOR PKL</span>
                        </div>
                    </div>
                </div>

                {{-- CAMERA ERROR / FALLBACK NOTIFICATION --}}
                <div id="camera-fallback-alert" class="alert alert-warning border mt-3 text-start small mb-0" style="display: none; border-radius: 12px;">
                    <div class="fw-bold mb-1"><i class="ph ph-warning me-1"></i> Akses Kamera Browser Tidak Tersedia</div>
                    <p class="mb-2 text-muted" style="font-size: 0.78rem;">
                        Browser Anda tidak mengizinkan akses webcam langsung. Anda tetap bisa mengambil foto menggunakan kamera perangkat Anda:
                    </p>
                    <input type="file" id="fallback-file-input" accept="image/*" capture="user" class="form-control form-control-sm" onchange="handleFallbackFileInput(this)">
                </div>
            </div>

            <div class="modal-footer border-0 bg-light px-4 pb-4 pt-0 justify-content-center gap-2">
                {{-- Snap Button --}}
                <button type="button" id="btn-snap-photo" onclick="snapSelfiePhoto()" class="btn btn-primary px-4 py-2.5 rounded-3 d-inline-flex align-items-center gap-2 shadow-sm font-semibold">
                    <i class="ph ph-camera" style="font-size: 20px;"></i> Ambil Foto Selfie
                </button>

                {{-- Retake Button --}}
                <button type="button" id="btn-retake-photo" onclick="retakeSelfiePhoto()" class="btn btn-outline-secondary px-3 py-2.5 rounded-3 d-inline-flex align-items-center gap-1.5" style="display: none;">
                    <i class="ph ph-arrow-counter-clockwise"></i> Foto Ulang
                </button>

                {{-- Submit Attendance Button --}}
                <button type="button" id="btn-submit-attendance" onclick="submitAttendancePayload()" class="btn btn-success px-4 py-2.5 rounded-3 d-inline-flex align-items-center gap-2 shadow-sm font-semibold" style="display: none;">
                    <i class="ph ph-check-circle" style="font-size: 20px;"></i> Konfirmasi & Kirim Presensi
                </button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL LIGHTBOX PHOTO PREVIEW --}}
<div class="modal fade" id="modalPhotoPreview" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header border-0 bg-dark text-white px-4 py-3">
                <h6 class="modal-title fw-bold" id="photo-preview-title">Foto Selfie Presensi</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0 text-center bg-black">
                <img id="photo-preview-img" src="" alt="Selfie" style="width: 100%; max-height: 480px; object-fit: contain;">
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    // Live Clock Management
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        const timeStr = `${hours}:${minutes}:${seconds}`;
        const clockElem = document.getElementById('live-clock');
        const modalClock = document.getElementById('modal-live-clock');
        if(clockElem) clockElem.textContent = timeStr;
        if(modalClock) modalClock.textContent = `${timeStr} WIB`;
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

    let currentLat = null;
    let currentLng = null;
    let currentAccuracy = null;
    let currentDistance = null;

    // Active attendance type ('masuk' | 'keluar')
    let activeAttendanceType = 'masuk';
    let mediaStream = null;
    let capturedPhotoBase64 = null;

    // Haversine Distance Calculation
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

        setTimeout(function() {
            if (radarMap) radarMap.invalidateSize();
        }, 200);

        window.addEventListener('resize', function() {
            if (radarMap) radarMap.invalidateSize();
        });

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
                currentLat = pos.coords.latitude;
                currentLng = pos.coords.longitude;
                currentAccuracy = Math.round(pos.coords.accuracy);

                document.getElementById('gps-accuracy-badge').textContent = `GPS Akurasi: ±${currentAccuracy}m`;

                // Add User Marker
                userMarker = L.circleMarker([currentLat, currentLng], {
                    radius: 8,
                    color: '#0284c7',
                    fillColor: '#38bdf8',
                    fillOpacity: 0.9
                }).addTo(radarMap).bindPopup("Lokasi Anda Saat Ini");

                // Fit Bounds to show both company and user
                const bounds = L.latLngBounds([[companyLat, companyLng], [currentLat, currentLng]]);
                radarMap.fitBounds(bounds, { padding: [30, 30] });

                setTimeout(function() {
                    if (radarMap) radarMap.invalidateSize();
                }, 100);

                // Calculate Distance
                currentDistance = calculateDistanceClient(currentLat, currentLng, companyLat, companyLng);
                updateDistanceIndicatorUI(currentDistance);
            }, function() {
                const indicator = document.getElementById('distance-indicator');
                if (indicator) {
                    indicator.className = 'p-2.5 px-3.5 d-inline-flex align-items-center gap-2 mb-4 rounded-3 border bg-light text-muted';
                    indicator.innerHTML = `<i class="ph ph-map-pin text-muted fs-5"></i> <span>Izin GPS belum aktif. Klik tombol presensi untuk menyalakan.</span>`;
                }
            }, { enableHighAccuracy: true, timeout: 10000 });
        }
    });

    function updateDistanceIndicatorUI(dist) {
        const indicator = document.getElementById('distance-indicator');
        const modalBadge = document.getElementById('modal-distance-badge');

        if (indicator) {
            if (dist <= allowedRadius) {
                indicator.className = 'p-2.5 px-3.5 d-inline-flex align-items-center gap-2 mb-4 rounded-3 border border-success-subtle bg-success bg-opacity-10 text-success fw-medium';
                indicator.innerHTML = `<i class="ph ph-check-circle fs-5"></i> <span>Jarak Anda: <strong>${dist} Meter</strong> (Dalam Radius Kantor ✅)</span>`;
            } else {
                indicator.className = 'p-2.5 px-3.5 d-inline-flex align-items-center gap-2 mb-4 rounded-3 border border-warning-subtle bg-warning bg-opacity-10 text-warning-emphasis fw-medium';
                indicator.innerHTML = `<i class="ph ph-warning fs-5 text-warning"></i> <span>Jarak Anda: <strong>${dist} Meter</strong> (Di Luar Radius Kantor ${allowedRadius}m ⚠️)</span>`;
            }
        }

        if (modalBadge) {
            if (dist <= allowedRadius) {
                modalBadge.className = 'p-2 px-3 rounded-pill bg-success-subtle text-success border border-success-subtle shadow-sm mb-3 d-inline-flex align-items-center gap-2 small fw-semibold';
                modalBadge.innerHTML = `<i class="ph ph-check-circle"></i> <span>Jarak: ${dist}m (Radius Valid)</span>`;
            } else {
                modalBadge.className = 'p-2 px-3 rounded-pill bg-warning-subtle text-warning-emphasis border border-warning-subtle shadow-sm mb-3 d-inline-flex align-items-center gap-2 small fw-semibold';
                modalBadge.innerHTML = `<i class="ph ph-warning"></i> <span>Jarak: ${dist}m (Di Luar Radius Kantor)</span>`;
            }
        }
    }

    // --- WEBRTC CAMERA & SELFIE CAPTURE ---
    function startSelfieAttendance(type) {
        activeAttendanceType = type;
        capturedPhotoBase64 = null;

        document.getElementById('camera-modal-title').textContent = type === 'masuk' 
            ? 'Foto Selfie Presensi Masuk' 
            : 'Foto Selfie Presensi Pulang';

        // Reset elements
        document.getElementById('webcam-video').style.display = 'block';
        document.getElementById('selfie-preview-img').style.display = 'none';
        document.getElementById('camera-overlay-frame').style.display = 'flex';
        document.getElementById('camera-fallback-alert').style.display = 'none';
        document.getElementById('btn-snap-photo').style.display = 'inline-flex';
        document.getElementById('btn-retake-photo').style.display = 'none';
        document.getElementById('btn-submit-attendance').style.display = 'none';

        const modalElem = document.getElementById('modalSelfieAttendance');
        const bsModal = new bootstrap.Modal(modalElem);
        bsModal.show();

        // Refresh location when opening modal
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(pos) {
                currentLat = pos.coords.latitude;
                currentLng = pos.coords.longitude;
                currentDistance = calculateDistanceClient(currentLat, currentLng, companyLat, companyLng);
                updateDistanceIndicatorUI(currentDistance);
            }, function() {}, { enableHighAccuracy: true, timeout: 8000 });
        }

        // Start WebRTC camera stream
        startCameraStream();
    }

    async function startCameraStream() {
        const video = document.getElementById('webcam-video');
        const fallbackAlert = document.getElementById('camera-fallback-alert');

        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            try {
                if (mediaStream) {
                    mediaStream.getTracks().forEach(track => track.stop());
                }

                mediaStream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'user',
                        width: { ideal: 640 },
                        height: { ideal: 480 }
                    },
                    audio: false
                });

                video.srcObject = mediaStream;
                video.style.display = 'block';
                fallbackAlert.style.display = 'none';
            } catch (err) {
                console.warn("Camera access failed:", err);
                video.style.display = 'none';
                fallbackAlert.style.display = 'block';
                document.getElementById('btn-snap-photo').style.display = 'none';
            }
        } else {
            video.style.display = 'none';
            fallbackAlert.style.display = 'block';
            document.getElementById('btn-snap-photo').style.display = 'none';
        }
    }

    function closeCameraStream() {
        if (mediaStream) {
            mediaStream.getTracks().forEach(track => track.stop());
            mediaStream = null;
        }
    }

    function snapSelfiePhoto() {
        const video = document.getElementById('webcam-video');
        const canvas = document.getElementById('webcam-canvas');
        const previewImg = document.getElementById('selfie-preview-img');

        if (!video.videoWidth || !video.videoHeight) {
            alert("Kamera belum siap. Harap tunggu beberapa saat.");
            return;
        }

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const ctx = canvas.getContext('2d');

        // Flip horizontally to match selfie mirror mode
        ctx.translate(canvas.width, 0);
        ctx.scale(-1, 1);
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        ctx.setTransform(1, 0, 0, 1, 0, 0);

        // Watermark Banner at the bottom
        const bannerHeight = 44;
        ctx.fillStyle = 'rgba(0, 0, 0, 0.65)';
        ctx.fillRect(0, canvas.height - bannerHeight, canvas.width, bannerHeight);

        // Watermark Text
        ctx.fillStyle = '#ffffff';
        ctx.font = 'bold 13px sans-serif';
        const now = new Date();
        const dateStr = now.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) + ' ' + now.toLocaleTimeString('id-ID') + ' WIB';
        const distStr = currentDistance !== null ? ` | Jarak: ${currentDistance}m` : '';
        ctx.fillText(`SMK LABOR PKL • ${dateStr}${distStr}`, 12, canvas.height - 18);

        // Export to Base64 JPEG
        capturedPhotoBase64 = canvas.toDataURL('image/jpeg', 0.85);

        // Switch View
        previewImg.src = capturedPhotoBase64;
        previewImg.style.display = 'block';
        video.style.display = 'none';
        document.getElementById('camera-overlay-frame').style.display = 'none';

        // Toggle Buttons
        document.getElementById('btn-snap-photo').style.display = 'none';
        document.getElementById('btn-retake-photo').style.display = 'inline-flex';
        document.getElementById('btn-submit-attendance').style.display = 'inline-flex';
    }

    function retakeSelfiePhoto() {
        capturedPhotoBase64 = null;
        document.getElementById('selfie-preview-img').style.display = 'none';
        document.getElementById('webcam-video').style.display = 'block';
        document.getElementById('camera-overlay-frame').style.display = 'flex';

        document.getElementById('btn-snap-photo').style.display = 'inline-flex';
        document.getElementById('btn-retake-photo').style.display = 'none';
        document.getElementById('btn-submit-attendance').style.display = 'none';
    }

    function handleFallbackFileInput(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                capturedPhotoBase64 = e.target.result;
                const previewImg = document.getElementById('selfie-preview-img');
                previewImg.src = capturedPhotoBase64;
                previewImg.style.display = 'block';
                document.getElementById('webcam-video').style.display = 'none';
                document.getElementById('btn-submit-attendance').style.display = 'inline-flex';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function submitAttendancePayload() {
        if (!capturedPhotoBase64) {
            alert('Silakan ambil foto selfie terlebih dahulu.');
            return;
        }

        const submitBtn = document.getElementById('btn-submit-attendance');
        const origText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan Presensi...';
        submitBtn.disabled = true;

        const executeSubmit = function(lat, lon) {
            const form = document.getElementById(activeAttendanceType === 'masuk' ? 'form-absen-masuk' : 'form-absen-keluar');
            const inputLokasi = document.getElementById(activeAttendanceType === 'masuk' ? 'lokasi_masuk' : 'lokasi_keluar');
            const inputFoto = document.getElementById(activeAttendanceType === 'masuk' ? 'foto_masuk' : 'foto_keluar');

            inputLokasi.value = (lat && lon) ? `${lat},${lon}` : 'Tanpa GPS';
            inputFoto.value = capturedPhotoBase64;

            closeCameraStream();
            form.submit();
        };

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(pos) {
                    const lat = pos.coords.latitude;
                    const lon = pos.coords.longitude;
                    const dist = calculateDistanceClient(lat, lon, companyLat, companyLng);

                    if (dist > allowedRadius) {
                        const confirmOutside = confirm(
                            `⚠️ PERHATIAN: LOKASI ANDA DI LUAR RADIUS KANTOR!\n\n` +
                            `• Jarak Anda saat ini: ${dist} Meter\n` +
                            `• Batas Toleransi Radius Kantor: ${allowedRadius} Meter\n` +
                            `• Lokasi Kantor: ${companyName}\n\n` +
                            `Presensi Anda akan dicatat dengan status 'Luar Radius' dan tercatat pada rekap evaluasi pembimbing.\n\n` +
                            `Apakah Anda ingin TETAP MELANJUTKAN presensi?`
                        );

                        if (!confirmOutside) {
                            submitBtn.innerHTML = origText;
                            submitBtn.disabled = false;
                            return;
                        }
                    }

                    executeSubmit(lat, lon);
                },
                function() {
                    const confirmNoGps = confirm('Gagal mendeteksi koordinat GPS perangkat. Apakah Anda ingin tetap mengirim presensi dengan foto selfie?');
                    if (confirmNoGps) {
                        executeSubmit(null, null);
                    } else {
                        submitBtn.innerHTML = origText;
                        submitBtn.disabled = false;
                    }
                },
                { enableHighAccuracy: true, timeout: 8000 }
            );
        } else {
            executeSubmit(null, null);
        }
    }

    // Photo Lightbox Zoom Modal
    function openPhotoModal(imageUrl, title) {
        document.getElementById('photo-preview-img').src = imageUrl;
        document.getElementById('photo-preview-title').textContent = title || 'Foto Selfie Presensi';
        const modal = new bootstrap.Modal(document.getElementById('modalPhotoPreview'));
        modal.show();
    }
</script>
@endpush
