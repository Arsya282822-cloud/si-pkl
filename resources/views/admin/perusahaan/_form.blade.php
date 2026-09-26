@csrf
<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label fw-semibold">Nama Perusahaan <span class="text-danger">*</span></label>
        <input name="nama_perusahaan" value="{{ old('nama_perusahaan', $perusahaan->nama_perusahaan ?? '') }}" class="form-control" required placeholder="Contoh: PT Garuda Cyber Indonesia">
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Kota</label>
        <input name="kota" value="{{ old('kota', $perusahaan->kota ?? 'Pekanbaru') }}" class="form-control" placeholder="Contoh: Pekanbaru">
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">No. Telepon</label>
        <input name="no_telepon" value="{{ old('no_telepon', $perusahaan->no_telepon ?? '') }}" class="form-control" placeholder="0761-xxxxxx">
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Email</label>
        <input type="email" name="email" value="{{ old('email', $perusahaan->email ?? '') }}" class="form-control" placeholder="dudi@perusahaan.com">
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Website</label>
        <input type="url" name="website" value="{{ old('website', $perusahaan->website ?? '') }}" class="form-control" placeholder="https://perusahaan.com">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Nama Pimpinan / HRD</label>
        <input name="nama_pimpinan" value="{{ old('nama_pimpinan', $perusahaan->nama_pimpinan ?? '') }}" class="form-control" placeholder="Nama pimpinan atau penanggung jawab">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Status Mitra</label>
        <select name="status" class="form-select">
            <option value="aktif" @selected(old('status', $perusahaan->status ?? 'aktif') === 'aktif')>Aktif (Bisa Dipilih Siswa)</option>
            <option value="nonaktif" @selected(old('status', $perusahaan->status ?? '') === 'nonaktif')>Nonaktif</option>
        </select>
    </div>
    <div class="col-12">
        <label class="form-label fw-semibold">Alamat Lengkap Kantor <span class="text-danger">*</span></label>
        <textarea name="alamat" rows="2" class="form-control" required placeholder="Jl. Contoh No. 123...">{{ old('alamat', $perusahaan->alamat ?? '') }}</textarea>
    </div>

    {{-- GEOFENCING / KOORDINAT GPS UNTUK VALIDASI ABSENSI SISWA --}}
    <div class="col-12 mt-4">
        <div class="p-3 border rounded-3 bg-light bg-opacity-50">
            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                <div>
                    <h6 class="fw-bold mb-0 text-primary d-flex align-items-center gap-1.5">
                        <i class="ph ph-map-pin-line fs-5"></i> Titik Koordinat GPS & Geofencing Presensi Siswa
                    </h6>
                    <small class="text-muted">Klik pada peta atau geser pin untuk menentukan titik kantor & batas radius presensi siswa.</small>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1" onclick="detectCurrentLocation()">
                    <i class="ph ph-crosshair"></i> Gunakan Lokasi Saya
                </button>
            </div>

            <div class="row g-2 mb-3">
                <div class="col-md-4">
                    <label class="form-label small text-muted mb-1">Latitude</label>
                    <input type="text" name="latitude" id="input_latitude" value="{{ old('latitude', $perusahaan->latitude ?? '') }}" class="form-control form-control-sm" placeholder="Contoh: 0.507068" onchange="updateMapFromInputs()">
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted mb-1">Longitude</label>
                    <input type="text" name="longitude" id="input_longitude" value="{{ old('longitude', $perusahaan->longitude ?? '') }}" class="form-control form-control-sm" placeholder="Contoh: 101.447779" onchange="updateMapFromInputs()">
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted mb-1">Batas Radius Presensi (Meter)</label>
                    <div class="input-group input-group-sm">
                        <input type="number" name="radius_meter" id="input_radius" value="{{ old('radius_meter', $perusahaan->radius_meter ?? 150) }}" class="form-control" min="10" max="5000" onchange="updateRadiusCircle()">
                        <span class="input-group-text">Meter</span>
                    </div>
                </div>
            </div>

            <div id="leaflet-map-picker" style="height: 250px; border-radius: 10px; border: 1px solid #cbd5e1;"></div>
        </div>
    </div>
</div>

{{-- Leaflet CSS & JS for Map Picker --}}
@once
    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    @endpush
    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    @endpush
@endonce

@push('scripts')
<script>
    let mapInstance = null;
    let markerInstance = null;
    let circleInstance = null;

    document.addEventListener('DOMContentLoaded', function() {
        const defaultLat = {{ (float)(old('latitude', $perusahaan->latitude ?? 0.507068)) ?: 0.507068 }};
        const defaultLng = {{ (float)(old('longitude', $perusahaan->longitude ?? 101.447779)) ?: 101.447779 }};
        const defaultRadius = parseInt(document.getElementById('input_radius').value) || 150;

        mapInstance = L.map('leaflet-map-picker').setView([defaultLat, defaultLng], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(mapInstance);

        markerInstance = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(mapInstance);
        circleInstance = L.circle([defaultLat, defaultLng], {
            radius: defaultRadius,
            color: '#0284c7',
            fillColor: '#38bdf8',
            fillOpacity: 0.25
        }).addTo(mapInstance);

        markerInstance.on('dragend', function(e) {
            const pos = e.target.getLatLng();
            setInputs(pos.lat, pos.lng);
        });

        mapInstance.on('click', function(e) {
            setInputs(e.latlng.lat, e.latlng.lng);
        });

        setTimeout(() => { mapInstance.invalidateSize(); }, 300);
    });

    function setInputs(lat, lng) {
        document.getElementById('input_latitude').value = lat.toFixed(6);
        document.getElementById('input_longitude').value = lng.toFixed(6);
        markerInstance.setLatLng([lat, lng]);
        circleInstance.setLatLng([lat, lng]);
    }

    function updateMapFromInputs() {
        const lat = parseFloat(document.getElementById('input_latitude').value);
        const lng = parseFloat(document.getElementById('input_longitude').value);
        if (!isNaN(lat) && !isNaN(lng)) {
            setInputs(lat, lng);
            mapInstance.panTo([lat, lng]);
        }
    }

    function updateRadiusCircle() {
        const radius = parseInt(document.getElementById('input_radius').value) || 150;
        if (circleInstance) {
            circleInstance.setRadius(radius);
        }
    }

    function detectCurrentLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(pos) {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                setInputs(lat, lng);
                mapInstance.setView([lat, lng], 16);
            }, function() {
                alert('Gagal mendeteksi lokasi browser. Pastikan izin GPS diaktifkan.');
            });
        } else {
            alert('Browser tidak mendukung deteksi lokasi.');
        }
    }
</script>
@endpush
