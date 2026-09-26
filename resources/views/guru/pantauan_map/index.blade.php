@extends('layouts.app')
@section('title', 'Peta Pantauan Guru')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: var(--text-main);">Peta Pantauan Siswa (Live Realtime)</h3>
        <p class="text-muted mb-0" style="font-size: 0.875rem;">Pantau koordinat GPS presensi masuk siswa bimbingan Anda hari ini secara real-time.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill d-inline-flex align-items-center gap-1.5" style="font-weight: 600;">
            <span class="spinner-grow spinner-grow-sm text-success" style="width: 8px; height: 8px;"></span> Live GPS Active
        </span>
        <button onclick="window.location.reload()" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1" style="border-radius: 8px;">
            <i class="ph ph-arrows-clockwise"></i> Refresh Peta
        </button>
    </div>
</div>

<div class="pro-card mb-4" style="border-radius: 16px; overflow: hidden;">
    <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <i class="ph ph-map-trifold text-primary" style="font-size: 20px;"></i>
            <span class="fw-bold text-dark" style="font-size: 0.9rem;">Geolokasi Presensi Siswa Bimbingan</span>
        </div>
        <small class="text-muted"><i class="ph ph-calendar me-1"></i> Hari ini: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</small>
    </div>
    <div class="p-0">
        <!-- Map Container -->
        <div id="map" style="height: 520px; width: 100%;"></div>
    </div>
</div>

<div class="pro-card" style="border-radius: 16px;">
    <div class="p-3 px-4 bg-light border-bottom d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
            <i class="ph ph-clock-counter-clockwise text-primary" style="font-size: 20px;"></i>
            Log Presensi Siswa Bimbingan Hari Ini
        </h6>
        <span class="badge bg-primary rounded-pill px-2.5 py-1">{{ $absensi->count() }} Presensi</span>
    </div>
    <div class="p-0">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="table-light" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">
                    <tr>
                        <th class="ps-4">Siswa</th>
                        <th>Perusahaan / Tempat PKL</th>
                        <th>Waktu Masuk</th>
                        <th>Status Radius & Selfie</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensi as $absen)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $absen->penempatan->siswa->nama ?? '-' }}</div>
                                <small class="text-muted">NIS: {{ $absen->penempatan->siswa->nis ?? '-' }} • {{ $absen->penempatan->siswa->kelas?->nama_kelas ?? '-' }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $absen->penempatan->perusahaan->nama_perusahaan ?? '-' }}</div>
                                <small class="text-muted">{{ $absen->penempatan->perusahaan->alamat ?: 'Pekanbaru' }}</small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-1.5 fw-semibold text-dark">
                                    <i class="ph ph-clock text-primary"></i>
                                    {{ $absen->jam_masuk ? \Carbon\Carbon::parse($absen->jam_masuk)->format('H:i') . ' WIB' : '-' }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap align-items-center gap-1.5">
                                    @if($absen->jarak_masuk_meter !== null)
                                        @if($absen->status_lokasi_masuk === 'dalam_radius')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="border-radius: 6px; font-weight: 600; font-size: 0.72rem;">
                                                <i class="ph ph-check-circle me-1"></i> Radius Valid ({{ $absen->jarak_masuk_meter }}m)
                                            </span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1" style="border-radius: 6px; font-weight: 600; font-size: 0.72rem;">
                                                <i class="ph ph-warning-circle me-1"></i> Luar Radius ({{ $absen->jarak_masuk_meter }}m)
                                            </span>
                                        @endif
                                    @elseif($absen->lokasi_masuk)
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1" style="border-radius: 6px; font-weight: 600; font-size: 0.72rem;">
                                            <i class="ph ph-map-pin me-1"></i> GPS: {{ Str::limit($absen->lokasi_masuk, 18) }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border px-2 py-1" style="border-radius: 6px; font-weight: 600; font-size: 0.72rem;">
                                            <i class="ph ph-warning-circle me-1"></i> Tanpa GPS
                                        </span>
                                    @endif

                                    @if($absen->foto_masuk)
                                        <a href="javascript:void(0)" onclick="openPhotoModal('{{ asset('storage/'.$absen->foto_masuk) }}', 'Selfie Masuk - {{ $absen->penempatan->siswa->nama ?? 'Siswa' }}')" class="badge bg-info-subtle text-info border border-info-subtle text-decoration-none d-inline-flex align-items-center gap-1" style="border-radius: 6px; font-size: 0.72rem;">
                                            <i class="ph ph-camera"></i> Foto Selfie
                                        </a>
                                    @endif
                                </div>
                                @if($absen->lokasi_masuk)
                                    <div class="small text-muted font-monospace mt-1" style="font-size: 0.7rem;">{{ $absen->lokasi_masuk }}</div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="ph ph-map-pin-line fs-1 d-block mb-2 opacity-50"></i>
                                Belum ada siswa bimbingan yang melakukan absensi masuk hari ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
function openPhotoModal(imageUrl, title) {
    document.getElementById('photo-preview-img').src = imageUrl;
    document.getElementById('photo-preview-title').textContent = title || 'Foto Selfie Presensi';
    const modal = new bootstrap.Modal(document.getElementById('modalPhotoPreview'));
    modal.show();
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize map centering on Pekanbaru
    var map = L.map('map').setView([0.5071, 101.4478], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Data from backend
    var absensiData = @json($absensi);
    var bounds = [];

    // Add markers
    absensiData.forEach(function(absen) {
        if (absen.lokasi_masuk) {
            var parts = absen.lokasi_masuk.split(',');
            if (parts.length === 2) {
                var lat = parseFloat(parts[0].trim());
                var lng = parseFloat(parts[1].trim());

                if (!isNaN(lat) && !isNaN(lng)) {
                    var marker = L.marker([lat, lng]).addTo(map);
                    
                    var photoHtml = '';
                    if (absen.foto_masuk) {
                        photoHtml = `
                            <div style="margin-top: 8px; text-align: center;">
                                <img src="/storage/${absen.foto_masuk}" style="width: 100%; max-height: 120px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0;">
                            </div>
                        `;
                    }

                    var popupContent = `
                        <div style="font-family: 'Inter', sans-serif; padding: 4px; min-width: 180px;">
                            <h6 style="margin: 0 0 4px 0; font-weight: 700; color: #0f172a; font-size: 13px;">${absen.penempatan.siswa.nama}</h6>
                            <div style="font-size: 11px; color: #64748b; line-height: 1.4;">
                                <div><strong class="text-dark">${absen.penempatan.perusahaan.nama_perusahaan}</strong></div>
                                <div>Masuk: <strong>${absen.jam_masuk || '-'}</strong> WIB</div>
                                <div>Status: <span class="badge ${absen.status_lokasi_masuk === 'dalam_radius' ? 'bg-success' : 'bg-warning'} text-white" style="font-size: 10px;">${absen.status_lokasi_masuk === 'dalam_radius' ? 'Radius Valid' : 'Luar Radius'}</span></div>
                            </div>
                            ${photoHtml}
                        </div>
                    `;
                    marker.bindPopup(popupContent);
                    bounds.push([lat, lng]);
                }
            }
        }
    });

    // Fit map to bounds if there are any markers
    if (bounds.length > 0) {
        map.fitBounds(bounds, { padding: [50, 50] });
    } else {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                map.setView([position.coords.latitude, position.coords.longitude], 13);
            });
        }
    }
});
</script>
@endpush

