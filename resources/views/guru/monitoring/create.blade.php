@extends('layouts.app')
@section('title', 'Catat Kunjungan Monitoring')
@section('topbar_title', 'Catat Kunjungan Monitoring')

@section('content')
<div class="container-fluid px-0" style="max-width: 900px;">

    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--text-main);">Catat Kunjungan Supervisi</h4>
            <p class="text-muted mb-0" style="font-size: 0.875rem;">Dokumentasikan aktivitas kunjungan monitoring dan evaluasi perkembangan siswa di industri.</p>
        </div>
        <a href="{{ route('guru.monitoring.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1.5" style="border-radius: 8px;">
            <i class="ph ph-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="pro-card p-4" style="border-radius: 16px;">
        <form action="{{ route('guru.monitoring.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-md-7">
                    <label class="form-label fw-semibold" style="font-size: 0.875rem;">
                        Perusahaan / Tempat PKL <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted border-end-0">
                            <i class="ph ph-buildings"></i>
                        </span>
                        <select name="perusahaan_id" class="form-select border-start-0 ps-0 @error('perusahaan_id') is-invalid @enderror" required style="border-radius: 0 8px 8px 0;">
                            <option value="">-- Pilih Perusahaan Bimbingan --</option>
                            @foreach($perusahaan as $p)
                                <option value="{{ $p->id }}" {{ old('perusahaan_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_perusahaan }} ({{ $p->alamat ?: 'Pekanbaru' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('perusahaan_id') <div class="text-danger mt-1 small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-5">
                    <label class="form-label fw-semibold" style="font-size: 0.875rem;">
                        Tanggal Kunjungan <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted border-end-0">
                            <i class="ph ph-calendar"></i>
                        </span>
                        <input type="date" name="tanggal_kunjungan" class="form-control border-start-0 ps-0" value="{{ old('tanggal_kunjungan', date('Y-m-d')) }}" required style="border-radius: 0 8px 8px 0;">
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold" style="font-size: 0.875rem;">
                        Kesesuaian Kompetensi Tugas di DUDI <span class="text-danger">*</span>
                    </label>
                    <select name="kesesuaian_kompetensi" class="form-select" style="border-radius: 8px;" required>
                        <option value="sangat_sesuai" {{ old('kesesuaian_kompetensi') == 'sangat_sesuai' ? 'selected' : '' }}>🟢 Sangat Sesuai (Pekerjaan selaras dengan silabus jurusan)</option>
                        <option value="sesuai" {{ old('kesesuaian_kompetensi', 'sesuai') == 'sesuai' ? 'selected' : '' }}>🔵 Sesuai (Sebagian besar tugas relevan)</option>
                        <option value="cukup" {{ old('kesesuaian_kompetensi') == 'cukup' ? 'selected' : '' }}>🟡 Cukup (Tugas umum / administrasi ringan)</option>
                        <option value="kurang" {{ old('kesesuaian_kompetensi') == 'kurang' ? 'selected' : '' }}>🔴 Kurang Sesuai (Tugas tidak berhubungan)</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold" style="font-size: 0.875rem;">
                        Kedisiplinan & Sikap Siswa <span class="text-danger">*</span>
                    </label>
                    <select name="kedisiplinan_siswa" class="form-select" style="border-radius: 8px;" required>
                        <option value="sangat_baik" {{ old('kedisiplinan_siswa') == 'sangat_baik' ? 'selected' : '' }}>🟢 Sangat Baik (Hadir rajin, sopan, proaktif)</option>
                        <option value="baik" {{ old('kedisiplinan_siswa', 'baik') == 'baik' ? 'selected' : '' }}>🔵 Baik (Mengikuti jam kerja & SOP dengan baik)</option>
                        <option value="cukup" {{ old('kedisiplinan_siswa') == 'cukup' ? 'selected' : '' }}>🟡 Cukup (Perlu bimbingan motivasi)</option>
                        <option value="kurang" {{ old('kedisiplinan_siswa') == 'kurang' ? 'selected' : '' }}>🔴 Kurang (Sering terlambat / kurang disiplin)</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size: 0.875rem;">
                    Catatan Perkembangan & Hasil Observasi <span class="text-danger">*</span>
                </label>
                <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="4" required placeholder="Tuliskan hasil pengamatan di industri, adaptasi siswa, fasilitas kerja, dan progres kegiatan..." style="border-radius: 8px;">{{ old('catatan') }}</textarea>
                @error('catatan') <div class="text-danger mt-1 small">{{ $message }}</div> @enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold" style="font-size: 0.875rem;">
                        Kendala / Permasalahan Siswa (Opsional)
                    </label>
                    <textarea name="kendala_observasi" class="form-control" rows="2" placeholder="Tuliskan kendala teknis atau adaptasi jika ada..." style="border-radius: 8px;">{{ old('kendala_observasi') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold" style="font-size: 0.875rem;">
                        Masukan / Saran dari Pembimbing Industri (Opsional)
                    </label>
                    <textarea name="saran_dudi" class="form-control" rows="2" placeholder="Masukan dari pihak perusahaan untuk siswa / sekolah..." style="border-radius: 8px;">{{ old('saran_dudi') }}</textarea>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold" style="font-size: 0.875rem;">
                        <i class="ph ph-image me-1 text-primary"></i> Foto Dokumentasi Kunjungan
                    </label>
                    <input type="file" name="foto" class="form-control" accept="image/*" style="border-radius: 8px;">
                    <div class="form-text text-muted" style="font-size: 0.775rem;">Foto bersama siswa & pembimbing industri (Maks: 3 MB).</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold" style="font-size: 0.875rem;">
                        <i class="ph ph-file-pdf me-1 text-danger"></i>
                        <i class="ph ph-file-xls me-1 text-success"></i> Upload Berkas Lembar Observasi (PDF / Excel / Scan)
                    </label>
                    <input type="file" name="file_observasi" class="form-control" accept=".pdf,.xlsx,.xls,.jpg,.jpeg,.png,.doc,.docx" style="border-radius: 8px;">
                    <div class="form-text text-muted" style="font-size: 0.775rem;">Upload lembar observasi yang telah diisi (Format: Excel .xlsx, PDF, atau Foto Scan, Maks: 10 MB).</div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                <a href="{{ route('guru.monitoring.index') }}" class="btn btn-light px-4 py-2" style="border-radius: 8px;">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary px-4 py-2 d-inline-flex align-items-center gap-1.5" style="border-radius: 8px; font-weight: 600; box-shadow: 0 4px 12px rgba(14, 165, 233, 0.25);">
                    <i class="ph ph-check-circle" style="font-size: 18px;"></i>
                    Simpan Berkas & Observasi
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
