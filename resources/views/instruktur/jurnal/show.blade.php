@extends('layouts.app')
@section('title', 'Detail & Validasi Jurnal PKL')
@section('content')
@php
    $siswa = $jurnal->penempatan?->siswa;
    $guru = $jurnal->penempatan?->guru;
@endphp

<div class="mb-4">
    <a href="{{ route('instruktur.jurnal.index') }}" class="btn btn-outline-secondary btn-sm mb-3" style="border-radius: 8px;">
        <i class="ph ph-arrow-left me-1"></i> Kembali ke Daftar Jurnal
    </a>
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h3 class="fw-bold mb-1" style="color: var(--text-main);">Detail Jurnal Harian Peserta Magang</h3>
            <p class="text-muted mb-0" style="font-size: 0.875rem;">
                Tanggal kegiatan: <strong>{{ \Carbon\Carbon::parse($jurnal->tanggal)->isoFormat('dddd, D MMMM Y') }}</strong>
            </p>
        </div>
        <div>
            @if($jurnal->status_validasi === 'disetujui')
                <span class="badge bg-success px-3 py-2 fs-6" style="border-radius: 8px;"><i class="ph ph-check-circle me-1"></i> Disetujui</span>
            @elseif($jurnal->status_validasi === 'ditolak')
                <span class="badge bg-danger px-3 py-2 fs-6" style="border-radius: 8px;"><i class="ph ph-x-circle me-1"></i> Ditolak / Perlu Revisi</span>
            @else
                <span class="badge bg-warning text-dark px-3 py-2 fs-6" style="border-radius: 8px;"><i class="ph ph-clock me-1"></i> Menunggu Validasi</span>
            @endif
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Jurnal & Konten Kegiatan -->
    <div class="col-lg-8">
        <div class="pro-card p-4 border-0 shadow-sm mb-4" style="border-radius: 16px;">
            <h5 class="fw-bold mb-3 border-bottom pb-2">Uraian Aktivitas & Pekerjaan</h5>
            
            <div class="mb-4 p-3 bg-light rounded-3">
                <div class="text-muted small mb-1 fw-semibold">RINGKASAN KEGIATAN:</div>
                <div class="fw-bold text-dark fs-6">{{ $jurnal->ringkasan_kegiatan ?? '-' }}</div>
            </div>

            <div class="mb-4">
                <div class="text-muted small mb-2 fw-semibold">DESKRIPSI LENGKAP KEGIATAN:</div>
                <div class="p-3 border rounded-3 bg-white" style="line-height: 1.7; font-size: 0.95rem; white-space: pre-line;">
                    {{ $jurnal->kegiatan }}
                </div>
            </div>

            @if($jurnal->foto_kegiatan)
                <div class="mb-3">
                    <div class="text-muted small mb-2 fw-semibold">FOTO DOKUMENTASI PEKERJAAN:</div>
                    <div class="p-2 border rounded-3 bg-light text-center">
                        <img src="{{ asset('storage/' . $jurnal->foto_kegiatan) }}" alt="Dokumentasi Pekerjaan" class="img-fluid rounded" style="max-height: 400px; object-fit: contain;">
                    </div>
                </div>
            @endif
        </div>

        <!-- Form Validasi -->
        <div class="pro-card p-4 border-0 shadow-sm" style="border-radius: 16px;">
            <h5 class="fw-bold mb-3 border-bottom pb-2">Verifikasi & Catatan Pembimbing Industri</h5>
            
            <form action="{{ route('instruktur.jurnal.validasi', $jurnal->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Pilih Status Validasi <span class="text-danger">*</span></label>
                    <div class="d-flex gap-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status_validasi" id="statusDisetujui" value="disetujui" {{ $jurnal->status_validasi === 'disetujui' ? 'checked' : '' }} required>
                            <label class="form-check-label text-success fw-bold" for="statusDisetujui">
                                <i class="ph ph-check-circle me-1"></i> Disetujui
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status_validasi" id="statusDitolak" value="ditolak" {{ $jurnal->status_validasi === 'ditolak' ? 'checked' : '' }}>
                            <label class="form-check-label text-danger fw-bold" for="statusDitolak">
                                <i class="ph ph-x-circle me-1"></i> Ditolak / Perlu Revisi
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="catatan_guru" class="form-label fw-semibold">Catatan / Masukan untuk Siswa (Opsional)</label>
                    <textarea name="catatan_guru" id="catatan_guru" rows="3" class="form-control" placeholder="Tuliskan catatan apresiasi, evaluasi kerja, atau alasan revisi...">{{ preg_replace('/^(\[LAPORAN INSTRUKTUR DUDI\]: |\[Catatan Instruktur\]: |\[Instruktur DUDI\]: )/', '', $jurnal->komentar_guru ?? '') }}</textarea>
                </div>

                <div class="mb-3 p-3 bg-light rounded-3 border">
                    <div class="form-check form-switch mb-1">
                        <input class="form-check-input" type="checkbox" name="kirim_laporan_guru" value="1" id="kirimLaporanGuru" {{ str_contains($jurnal->komentar_guru ?? '', '[LAPORAN INSTRUKTUR DUDI]') ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold text-dark" for="kirimLaporanGuru">
                            <i class="ph ph-megaphone text-warning me-1"></i> Teruskan sebagai Laporan Khusus ke Guru Pembimbing
                        </label>
                    </div>
                    <small class="text-muted d-block ms-4">
                        Centang opsi ini jika siswa melakukan pelanggaran, kendala kedisiplinan, atau capaian istimewa yang perlu ditindaklanjuti/diketahui langsung oleh Guru Pembimbing sekolah.
                    </small>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('instruktur.jurnal.index') }}" class="btn btn-light border px-3">Batal</a>
                    <button type="submit" class="btn btn-primary px-4 fw-semibold" style="border-radius: 8px;">
                        <i class="ph ph-floppy-disk me-1"></i> Simpan & Terapkan Validasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Siswa & Perusahaan -->
    <div class="col-lg-4">
        <div class="pro-card p-4 border-0 shadow-sm mb-4" style="border-radius: 16px;">
            <h5 class="fw-bold mb-3 border-bottom pb-2">Informasi Siswa</h5>
            <div class="text-center mb-3">
                @if($siswa?->foto)
                    <img src="{{ asset('storage/' . $siswa->foto) }}" alt="{{ $siswa->nama }}" class="rounded-circle object-fit-cover shadow-sm mb-2" style="width: 70px; height: 70px;">
                @else
                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center fw-bold fs-4 shadow-sm mb-2" style="width: 70px; height: 70px;">
                        {{ strtoupper(substr($siswa?->nama ?? 'S', 0, 2)) }}
                    </div>
                @endif
                <h6 class="fw-bold mb-0">{{ $siswa?->nama ?? '-' }}</h6>
                <small class="text-muted">{{ $siswa?->kelas?->nama_kelas ?? '-' }} - {{ $siswa?->jurusan?->nama_jurusan ?? '-' }}</small>
            </div>
            
            <div class="border-top pt-2">
                <div class="d-flex justify-content-between py-1.5 small border-bottom">
                    <span class="text-muted">NISN</span>
                    <span class="fw-semibold">{{ $siswa?->nisn ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between py-1.5 small border-bottom">
                    <span class="text-muted">No. WhatsApp</span>
                    <span class="fw-semibold">{{ $siswa?->no_hp ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between py-1.5 small">
                    <span class="text-muted">Guru Pembimbing</span>
                    <span class="fw-semibold">{{ $guru?->nama ?? '-' }}</span>
                </div>
            </div>
        </div>

        <div class="pro-card p-4 border-0 shadow-sm" style="border-radius: 16px;">
            <h5 class="fw-bold mb-3 border-bottom pb-2">Tempat Praktik (DUDI)</h5>
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="ph ph-buildings text-primary fs-5"></i>
                <h6 class="fw-bold mb-0 text-dark">{{ $perusahaan->nama }}</h6>
            </div>
            <p class="small text-muted mb-0">
                {{ $perusahaan->alamat ?? 'Alamat belum diatur' }}
            </p>
        </div>
    </div>
</div>
@endsection
