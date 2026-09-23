@extends('layouts.app')
@section('title', 'Input Nilai PKL Siswa')
@section('content')
@php
    $siswa = $penempatan->siswa;
    $nilai = $penempatan->penilaianPkl;
    $guru = $penempatan->guru;
@endphp

<div class="mb-4">
    <a href="{{ route('instruktur.penilaian.index') }}" class="btn btn-outline-secondary btn-sm mb-3" style="border-radius: 8px;">
        <i class="ph ph-arrow-left me-1"></i> Kembali ke Rekap Nilai
    </a>
    <h3 class="fw-bold mb-1" style="color: var(--text-main);">Evaluasi & Penilaian PKL Mitra Industri</h3>
    <p class="text-muted mb-0" style="font-size: 0.875rem;">
        Formulir penilaian capaian pembelajaran dan performa kerja praktik siswa di <strong class="text-dark">{{ $perusahaan->nama }}</strong>.
    </p>
</div>

<div class="row g-4">
    <!-- Form Input Nilai -->
    <div class="col-lg-8">
        <div class="pro-card p-4 border-0 shadow-sm" style="border-radius: 16px;">
            <h5 class="fw-bold mb-3 border-bottom pb-2">Komponen Evaluasi & Asesmen Kerja</h5>
            
            <form action="{{ route('instruktur.penilaian.store', $penempatan->id) }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="form-label fw-semibold text-dark">
                        1. Aspek Sikap & Kedisiplinan Kerja (Bobot 30%) <span class="text-danger">*</span>
                    </label>
                    <p class="small text-muted mb-2">
                        Meliputi kehadiran tepat waktu, etika berkomunikasi, kerapian seragam/APD, kepatuhan SOP perusahaan, dan integritas kerja.
                    </p>
                    <div class="input-group" style="max-width: 250px;">
                        <input type="number" step="0.1" min="0" max="100" name="nilai_sikap" id="nilai_sikap" class="form-control" value="{{ old('nilai_sikap', $nilai?->nilai_sikap) }}" placeholder="Skala 0 - 100" required>
                        <span class="input-group-text bg-light">/ 100</span>
                    </div>
                    @error('nilai_sikap')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold text-dark">
                        2. Aspek Keterampilan Kerja / Teknis (Bobot 50%) <span class="text-danger">*</span>
                    </label>
                    <p class="small text-muted mb-2">
                        Meliputi penguasaan alat/perangkat kerja, kemampuan menyelesaikan tugas riil, kecepatan & ketepatan kerja, inisiatif, serta problem solving.
                    </p>
                    <div class="input-group" style="max-width: 250px;">
                        <input type="number" step="0.1" min="0" max="100" name="nilai_keterampilan" id="nilai_keterampilan" class="form-control" value="{{ old('nilai_keterampilan', $nilai?->nilai_keterampilan) }}" placeholder="Skala 0 - 100" required>
                        <span class="input-group-text bg-light">/ 100</span>
                    </div>
                    @error('nilai_keterampilan')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold text-dark">
                        3. Aspek Pengetahuan & Pemahaman Industri (Bobot 20%) <span class="text-danger">*</span>
                    </label>
                    <p class="small text-muted mb-2">
                        Meliputi pemahaman alur kerja/bisnis perusahaan, pemahaman teori pendukung, dan wawasan keselamatan kerja (K3).
                    </p>
                    <div class="input-group" style="max-width: 250px;">
                        <input type="number" step="0.1" min="0" max="100" name="nilai_pengetahuan" id="nilai_pengetahuan" class="form-control" value="{{ old('nilai_pengetahuan', $nilai?->nilai_pengetahuan) }}" placeholder="Skala 0 - 100" required>
                        <span class="input-group-text bg-light">/ 100</span>
                    </div>
                    @error('nilai_pengetahuan')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="catatan_guru" class="form-label fw-semibold text-dark">Catatan Rekomendasi & Evaluasi Instruktur</label>
                    <textarea name="catatan_guru" id="catatan_guru" rows="3" class="form-control" placeholder="Tuliskan catatan apresiasi potensi siswa atau saran peningkatan karir ke depan...">{{ old('catatan_guru', str_replace('[Catatan Instruktur]: ', '', $nilai?->catatan_guru ?? '')) }}</textarea>
                </div>

                <div class="p-3 bg-light rounded-3 d-flex justify-content-between align-items-center mb-4 border">
                    <div>
                        <div class="small text-muted fw-semibold">SIMULASI NILAI AKHIR:</div>
                        <div class="small text-secondary">(30% Sikap + 50% Keterampilan + 20% Pengetahuan)</div>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0 text-primary" id="previewNilaiAkhir">
                            {{ $nilai ? number_format($nilai->nilai_akhir, 1) : '0.0' }}
                        </h3>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('instruktur.penilaian.index') }}" class="btn btn-light border px-3">Batal</a>
                    <button type="submit" class="btn btn-primary px-4 fw-semibold" style="border-radius: 8px;">
                        <i class="ph ph-floppy-disk me-1"></i> Simpan Nilai Siswa
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Siswa -->
    <div class="col-lg-4">
        <div class="pro-card p-4 border-0 shadow-sm" style="border-radius: 16px;">
            <h5 class="fw-bold mb-3 border-bottom pb-2">Identitas Peserta Didik</h5>
            <div class="text-center mb-3">
                @if($siswa?->foto)
                    <img src="{{ asset('storage/' . $siswa->foto) }}" alt="{{ $siswa->nama }}" class="rounded-circle object-fit-cover shadow-sm mb-2" style="width: 70px; height: 70px;">
                @else
                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center fw-bold fs-4 shadow-sm mb-2" style="width: 70px; height: 70px;">
                        {{ strtoupper(substr($siswa?->nama ?? 'S', 0, 2)) }}
                    </div>
                @endif
                <h6 class="fw-bold mb-0">{{ $siswa?->nama ?? '-' }}</h6>
                <small class="text-muted">{{ $siswa?->kelas?->nama_kelas ?? '-' }}</small>
            </div>
            
            <div class="border-top pt-2">
                <div class="d-flex justify-content-between py-1.5 small border-bottom">
                    <span class="text-muted">NISN</span>
                    <span class="fw-semibold">{{ $siswa?->nisn ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between py-1.5 small border-bottom">
                    <span class="text-muted">Kompetensi Keahlian</span>
                    <span class="fw-semibold">{{ $siswa?->jurusan?->nama_jurusan ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between py-1.5 small border-bottom">
                    <span class="text-muted">Guru Pembimbing</span>
                    <span class="fw-semibold">{{ $guru?->nama ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between py-1.5 small">
                    <span class="text-muted">Perusahaan</span>
                    <span class="fw-semibold">{{ $perusahaan->nama }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inpSikap = document.getElementById('nilai_sikap');
        const inpKeterampilan = document.getElementById('nilai_keterampilan');
        const inpPengetahuan = document.getElementById('nilai_pengetahuan');
        const previewEl = document.getElementById('previewNilaiAkhir');

        function updatePreview() {
            const s = parseFloat(inpSikap.value) || 0;
            const k = parseFloat(inpKeterampilan.value) || 0;
            const p = parseFloat(inpPengetahuan.value) || 0;
            const finalScore = ((s * 0.3) + (k * 0.5) + (p * 0.2)).toFixed(1);
            previewEl.textContent = finalScore;
        }

        inpSikap.addEventListener('input', updatePreview);
        inpKeterampilan.addEventListener('input', updatePreview);
        inpPengetahuan.addEventListener('input', updatePreview);
    });
</script>
@endsection
