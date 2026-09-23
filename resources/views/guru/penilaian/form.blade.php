@extends('layouts.app')
@section('title', $penempatan->penilaian ? 'Edit Penilaian PKL' : 'Beri Penilaian PKL')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <a href="{{ route('guru.penilaian.index') }}" class="text-decoration-none text-muted d-inline-flex align-items-center gap-1 mb-2" style="font-size: 0.85rem;">
            <i class="ph ph-arrow-left"></i> Kembali ke Daftar Penilaian
        </a>
        <h3 class="fw-bold mb-0" style="color: var(--text-main);">{{ $penempatan->penilaian ? 'Edit' : 'Input' }} Penilaian Akhir PKL</h3>
    </div>
</div>

<div class="row g-4">
    {{-- Info Siswa --}}
    <div class="col-lg-4">
        <div class="pro-card p-4 h-100" style="border-radius: 16px;">
            <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                <div class="rounded-circle bg-primary bg-opacity-10 text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 1.2rem;">
                    {{ substr($penempatan->siswa->nama, 0, 1) }}
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-dark">{{ $penempatan->siswa->nama }}</h6>
                    <small class="text-muted">NIS: {{ $penempatan->siswa->nis }}</small>
                </div>
            </div>

            <h6 class="fw-bold mb-3 d-flex align-items-center gap-2 text-dark">
                <i class="ph ph-user-circle text-primary" style="font-size: 20px;"></i>
                Informasi Siswa
            </h6>
            <div class="d-flex flex-column gap-2.5">
                <div class="p-2.5 bg-light rounded-3">
                    <small class="text-muted d-block" style="font-size: 0.75rem;">Kelas & Konsentrasi Keahlian</small>
                    <span class="fw-semibold text-dark">{{ $penempatan->siswa->kelas?->nama_kelas ?? '-' }} ({{ $penempatan->siswa->jurusan?->kode_jurusan ?? '-' }})</span>
                </div>
                <div class="p-2.5 bg-light rounded-3">
                    <small class="text-muted d-block" style="font-size: 0.75rem;">Perusahaan Mitra</small>
                    <span class="fw-semibold text-dark">{{ $penempatan->perusahaan->nama_perusahaan }}</span>
                </div>
                <div class="p-2.5 bg-light rounded-3">
                    <small class="text-muted d-block" style="font-size: 0.75rem;">Periode PKL</small>
                    <span class="fw-semibold text-dark">{{ $penempatan->periodePkl?->nama_periode ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Penilaian --}}
    <div class="col-lg-8">
        <div class="pro-card p-4" style="border-radius: 16px;">
            <form action="{{ $penempatan->penilaian ? route('guru.penilaian.update', $penempatan) : route('guru.penilaian.store', $penempatan) }}" method="POST">
                @csrf
                @if($penempatan->penilaian) @method('PUT') @endif

                <h6 class="fw-bold mb-3 d-flex align-items-center gap-2 text-dark">
                    <i class="ph ph-chart-bar text-primary" style="font-size: 20px;"></i>
                    Komponen Penilaian (Skala 0 - 100)
                </h6>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" style="font-size: 0.875rem;">
                            Nilai Sikap <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0">
                                <i class="ph ph-heart"></i>
                            </span>
                            <input type="number" id="nilai_sikap" name="nilai_sikap" class="form-control border-start-0 ps-0 @error('nilai_sikap') is-invalid @enderror"
                                value="{{ old('nilai_sikap', $penempatan->penilaian?->nilai_sikap) }}" min="0" max="100" placeholder="0 - 100" required style="border-radius: 0 8px 8px 0;" oninput="hitungRataRata()">
                        </div>
                        <div class="form-text text-muted" style="font-size: 0.75rem;">Kedisiplinan, integritas, & etos kerja.</div>
                        @error('nilai_sikap') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold" style="font-size: 0.875rem;">
                            Nilai Keterampilan <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0">
                                <i class="ph ph-wrench"></i>
                            </span>
                            <input type="number" id="nilai_keterampilan" name="nilai_keterampilan" class="form-control border-start-0 ps-0 @error('nilai_keterampilan') is-invalid @enderror"
                                value="{{ old('nilai_keterampilan', $penempatan->penilaian?->nilai_keterampilan) }}" min="0" max="100" placeholder="0 - 100" required style="border-radius: 0 8px 8px 0;" oninput="hitungRataRata()">
                        </div>
                        <div class="form-text text-muted" style="font-size: 0.75rem;">Keahlian teknis & hasil kerja praktik.</div>
                        @error('nilai_keterampilan') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold" style="font-size: 0.875rem;">
                            Nilai Pengetahuan <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0">
                                <i class="ph ph-brain"></i>
                            </span>
                            <input type="number" id="nilai_pengetahuan" name="nilai_pengetahuan" class="form-control border-start-0 ps-0 @error('nilai_pengetahuan') is-invalid @enderror"
                                value="{{ old('nilai_pengetahuan', $penempatan->penilaian?->nilai_pengetahuan) }}" min="0" max="100" placeholder="0 - 100" required style="border-radius: 0 8px 8px 0;" oninput="hitungRataRata()">
                        </div>
                        <div class="form-text text-muted" style="font-size: 0.75rem;">Pemahaman materi & laporan PKL.</div>
                        @error('nilai_pengetahuan') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Preview Nilai Akhir Otomatis -->
                <div class="p-3 bg-primary-subtle border border-primary-subtle rounded-3 mb-4 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="fw-bold text-primary d-block">Simulasi Nilai Akhir (Rata-rata 3 Aspek)</span>
                        <small class="text-muted">Nilai akhir dihitung otomatis oleh sistem.</small>
                    </div>
                    <div>
                        <span class="badge bg-primary fs-5 px-3 py-1.5 rounded-pill" id="preview_nilai_akhir">
                            {{ $penempatan->penilaian?->nilai_akhir ?? '0.00' }}
                        </span>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold" style="font-size: 0.875rem;">
                        Catatan / Umpan Balik Guru Pembimbing (Opsional)
                    </label>
                    <textarea name="catatan_guru" class="form-control" rows="4" placeholder="Tuliskan catatan apresiasi, masukan peningkatan kompetensi, atau evaluasi menyeluruh untuk siswa..." style="border-radius: 8px;">{{ old('catatan_guru', $penempatan->penilaian?->catatan_guru) }}</textarea>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="{{ route('guru.penilaian.index') }}" class="btn btn-light px-4 py-2" style="border-radius: 8px;">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary px-4 py-2 d-inline-flex align-items-center gap-1.5" style="border-radius: 8px; font-weight: 600; box-shadow: 0 4px 12px rgba(14, 165, 233, 0.25);">
                        <i class="ph ph-check-circle" style="font-size: 18px;"></i>
                        Simpan Penilaian
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function hitungRataRata() {
    const s = parseFloat(document.getElementById('nilai_sikap').value) || 0;
    const k = parseFloat(document.getElementById('nilai_keterampilan').value) || 0;
    const p = parseFloat(document.getElementById('nilai_pengetahuan').value) || 0;
    
    if (s > 0 || k > 0 || p > 0) {
        const avg = ((s + k + p) / 3).toFixed(2);
        document.getElementById('preview_nilai_akhir').innerText = avg;
    } else {
        document.getElementById('preview_nilai_akhir').innerText = '0.00';
    }
}
document.addEventListener('DOMContentLoaded', hitungRataRata);
</script>
@endpush
@endsection

