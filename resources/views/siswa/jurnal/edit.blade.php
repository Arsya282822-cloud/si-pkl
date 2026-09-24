@extends('layouts.app')
@section('title', 'Edit Jurnal Harian PKL')

@section('content')
<div class="container-fluid px-0" style="max-width: 950px;">
    <div class="mb-4">
        <a href="{{ route('siswa.jurnal.index') }}" class="text-decoration-none text-muted d-inline-flex align-items-center gap-1">
            <i class="ph ph-arrow-left"></i> Kembali ke Riwayat Jurnal
        </a>
        <h4 class="page-title mt-2 fw-bold text-dark">Edit Jurnal Kegiatan Harian</h4>
        <p class="text-muted small mb-0">Perbarui catatan aktivitas dan capaian pembelajaran Anda.</p>
    </div>

    <div class="card dashboard-card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('siswa.jurnal.update', $jurnal) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold small">Tanggal Kegiatan <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control form-control-sm rounded-3 @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', $jurnal->tanggal) }}" max="{{ date('Y-m-d') }}" required>
                        @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- 🎯 OPSI TUJUAN PEMBELAJARAN (KURIKULUM PKL 2026) -->
                <div class="p-3 bg-light rounded-3 border mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label fw-bold text-primary small mb-0 d-inline-flex align-items-center gap-1.5">
                            <i class="ph-bold ph-target"></i> 🎯 Pilih / Sisipkan Tujuan Pembelajaran (TP)
                        </label>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-0.5 rounded-pill" style="font-size: 0.7rem;">
                            Kurikulum PKL 2026
                        </span>
                    </div>

                    <div class="row g-2 align-items-center">
                        <div class="col-md-9 col-12">
                            <select id="selectTp" class="form-select form-select-sm rounded-3" style="font-size: 0.825rem;">
                                <option value="">-- Pilih Tujuan Pembelajaran (TP) {{ $penempatan->siswa->jurusan->nama_jurusan }} --</option>
                                @if(isset($tujuanPembelajarans) && $tujuanPembelajarans->isNotEmpty())
                                    @foreach($tujuanPembelajarans as $capaianName => $tps)
                                        <optgroup label="{{ str_contains($capaianName, 'POS') ? 'Capaian 1: Sesuai POS Dunia Kerja' : 'Capaian 2: Kompetensi Baru / Lanjutan' }}">
                                            @foreach($tps as $tp)
                                                <option value="[TP {{ $tp->nomor_urut }}] {{ $tp->tujuan_pembelajaran }}">
                                                    TP {{ $tp->nomor_urut }}: {{ Str::limit($tp->tujuan_pembelajaran, 90) }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-3 col-12">
                            <button type="button" class="btn btn-sm btn-primary w-100 rounded-3 d-inline-flex align-items-center justify-content-center gap-1" onclick="sisipkanTp()">
                                <i class="ph-bold ph-plus"></i> Sisipkan ke Jurnal
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Deskripsi Pekerjaan / Kegiatan Aktual <span class="text-danger">*</span></label>
                    <textarea name="kegiatan" id="kegiatanTextarea" class="form-control rounded-3 @error('kegiatan') is-invalid @enderror" rows="6" required>{{ old('kegiatan', $jurnal->kegiatan) }}</textarea>
                    @error('kegiatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold small">Unggah Foto Baru (Biarkan kosong jika tidak ingin mengganti)</label>
                    @if($jurnal->foto)
                        <div class="mb-2">
                            <span class="d-block text-muted small mb-1">Foto Kegiatan Saat Ini:</span>
                            <img src="{{ asset($jurnal->foto) }}" onerror="this.src='{{ asset('storage/' . $jurnal->foto) }}'" alt="Foto Jurnal" class="img-thumbnail rounded-3" style="max-height: 150px;">
                        </div>
                    @endif
                    <input type="file" name="foto" class="form-control form-control-sm rounded-3 @error('foto') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg">
                    <div class="form-text" style="font-size: 0.75rem;">Format yang diizinkan: JPG, JPEG, PNG. Ukuran maksimal: 2MB.</div>
                    @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <hr class="my-4">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('siswa.jurnal.index') }}" class="btn btn-sm btn-light border rounded-pill px-3.5">Batal</a>
                    <button type="submit" class="btn btn-sm btn-primary rounded-pill px-4 shadow-sm d-inline-flex align-items-center gap-1.5">
                        <i class="ph-bold ph-floppy-disk"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function sisipkanTp() {
        const select = document.getElementById('selectTp');
        const textarea = document.getElementById('kegiatanTextarea');
        const selectedVal = select.value;

        if (!selectedVal) {
            alert('Silakan pilih salah satu Tujuan Pembelajaran terlebih dahulu.');
            return;
        }

        const template = `Tujuan Pembelajaran: ${selectedVal}\n\nUraian Kegiatan:\n- `;
        
        if (textarea.value.trim() === '') {
            textarea.value = template;
        } else {
            textarea.value = textarea.value + `\n\n${template}`;
        }

        textarea.focus();
    }
</script>
@endsection
