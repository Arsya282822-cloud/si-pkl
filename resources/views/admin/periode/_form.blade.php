@csrf
<div class="row g-4">
    <div class="col-md-8">
        <label class="form-label fw-semibold" style="font-size: 0.875rem;">
            Nama Gelombang / Periode <span class="text-danger">*</span>
        </label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0 text-muted">
                <i class="ph ph-calendar-star"></i>
            </span>
            <input type="text" name="nama_periode" value="{{ old('nama_periode', $periode->nama_periode ?? '') }}" 
                   class="form-control border-start-0 ps-0 @error('nama_periode') is-invalid @enderror" 
                   placeholder="Contoh: PKL Semester Genap 2025/2026 Gelombang 1" required>
        </div>
        <div class="form-text text-muted" style="font-size: 0.775rem;">Tuliskan nama periode yang jelas dan mudah diidentifikasi oleh guru & siswa.</div>
        @error('nama_periode')
            <div class="text-danger mt-1" style="font-size: 0.75rem;">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold" style="font-size: 0.875rem;">
            Tahun Ajaran <span class="text-danger">*</span>
        </label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0 text-muted">
                <i class="ph ph-graduation-cap"></i>
            </span>
            <input type="text" name="tahun_ajaran" value="{{ old('tahun_ajaran', $periode->tahun_ajaran ?? '2025/2026') }}" 
                   class="form-control border-start-0 ps-0 @error('tahun_ajaran') is-invalid @enderror" 
                   placeholder="2025/2026" required>
        </div>
        @error('tahun_ajaran')
            <div class="text-danger mt-1" style="font-size: 0.75rem;">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold" style="font-size: 0.875rem;">
            Tanggal Mulai <span class="text-danger">*</span>
        </label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0 text-muted">
                <i class="ph ph-calendar-plus"></i>
            </span>
            <input type="date" name="tanggal_mulai" id="tanggal_mulai" 
                   value="{{ old('tanggal_mulai', optional($periode->tanggal_mulai ?? null)->format('Y-m-d')) }}" 
                   class="form-control border-start-0 ps-0 @error('tanggal_mulai') is-invalid @enderror" required onchange="calculateDuration()">
        </div>
        @error('tanggal_mulai')
            <div class="text-danger mt-1" style="font-size: 0.75rem;">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold" style="font-size: 0.875rem;">
            Tanggal Selesai <span class="text-danger">*</span>
        </label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0 text-muted">
                <i class="ph ph-calendar-check"></i>
            </span>
            <input type="date" name="tanggal_selesai" id="tanggal_selesai" 
                   value="{{ old('tanggal_selesai', optional($periode->tanggal_selesai ?? null)->format('Y-m-d')) }}" 
                   class="form-control border-start-0 ps-0 @error('tanggal_selesai') is-invalid @enderror" required onchange="calculateDuration()">
        </div>
        @error('tanggal_selesai')
            <div class="text-danger mt-1" style="font-size: 0.75rem;">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold" style="font-size: 0.875rem;">
            Status Periode <span class="text-danger">*</span>
        </label>
        <select name="status" class="form-select @error('status') is-invalid @enderror" style="border-radius: 8px;">
            <option value="aktif" @selected(old('status', $periode->status ?? 'aktif') === 'aktif')>Aktif (Sedang Berlangsung)</option>
            <option value="nonaktif" @selected(old('status', $periode->status ?? '') === 'nonaktif')>Nonaktif (Draft / Belum Dimulai)</option>
            <option value="selesai" @selected(old('status', $periode->status ?? '') === 'selesai')>Selesai (Arsip Lulus)</option>
        </select>
        @error('status')
            <div class="text-danger mt-1" style="font-size: 0.75rem;">{{ $message }}</div>
        @enderror
    </div>

    <!-- Duration Preview Box -->
    <div class="col-12">
        <div class="p-3 bg-light rounded-3 d-flex align-items-center gap-3 border" id="durationBox" style="font-size: 0.85rem;">
            <i data-lucide="clock" class="text-primary" style="width: 24px; height: 24px;"></i>
            <div>
                <span class="text-muted">Estimasi Durasi Periode:</span>
                <strong id="durationText" class="text-dark ms-1">Pilih tanggal mulai & selesai</strong>
            </div>
        </div>
    </div>
</div>

<script>
    function calculateDuration() {
        const start = document.getElementById('tanggal_mulai').value;
        const end = document.getElementById('tanggal_selesai').value;
        const textElem = document.getElementById('durationText');
        
        if (start && end) {
            const startDate = new Date(start);
            const endDate = new Date(end);
            const diffTime = endDate - startDate;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            if (diffDays >= 0) {
                const months = (diffDays / 30).toFixed(1);
                textElem.innerHTML = `<span class="badge bg-primary me-1">${diffDays} Hari</span> (kurang lebih ~${months} Bulan)`;
            } else {
                textElem.innerHTML = `<span class="text-danger">Tanggal selesai tidak boleh sebelum tanggal mulai!</span>`;
            }
        }
    }
    document.addEventListener('DOMContentLoaded', calculateDuration);
</script>
