@extends('layouts.app')
@section('title', 'Detail Jurnal Siswa & Supervisi')
@section('content')
@php
    $isLaporan = str_contains($jurnal->komentar_guru ?? '', '[LAPORAN INSTRUKTUR DUDI]');
    $cleanMsg = preg_replace('/^(\[LAPORAN INSTRUKTUR DUDI\]: |\[Catatan Instruktur\]: |\[Instruktur DUDI\]: )/', '', $jurnal->komentar_guru ?? '');
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <a href="{{ route('guru.validasi.index') }}" class="text-decoration-none text-muted d-inline-flex align-items-center gap-1 mb-2" style="font-size: 0.85rem;">
            <i class="ph ph-arrow-left"></i> Kembali ke Daftar Jurnal
        </a>
        <h3 class="fw-bold mb-0" style="color: var(--text-main);">Supervisi Jurnal Harian Siswa</h3>
    </div>
</div>

{{-- Banner Laporan DUDI jika ada --}}
@if($isLaporan)
    <div class="alert alert-danger d-flex align-items-center gap-3 p-3 mb-4 shadow-sm" style="border-radius: 12px; border-left: 6px solid #dc2626;">
        <i class="ph ph-megaphone fs-2 text-danger"></i>
        <div class="flex-grow-1">
            <h6 class="fw-bold mb-1 text-danger">Pemberitahuan Khusus dari Instruktur Industri (DUDI)</h6>
            <div class="small text-dark fw-medium">{{ $cleanMsg }}</div>
        </div>
    </div>
@endif

<div class="row g-4">
    {{-- Info Siswa & DUDI --}}
    <div class="col-lg-4">
        <div class="pro-card p-4 h-100" style="border-radius: 16px;">
            <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                <div class="rounded-circle bg-primary bg-opacity-10 text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 1.2rem;">
                    {{ substr($jurnal->penempatan->siswa->nama, 0, 1) }}
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-dark">{{ $jurnal->penempatan->siswa->nama }}</h6>
                    <small class="text-muted">NIS: {{ $jurnal->penempatan->siswa->nis }}</small>
                </div>
            </div>

            <h6 class="fw-bold mb-3 d-flex align-items-center gap-2 text-dark">
                <i class="ph ph-user-circle text-primary" style="font-size: 20px;"></i>
                Data Penempatan
            </h6>
            <div class="d-flex flex-column gap-2.5 mb-4">
                <div class="p-2.5 bg-light rounded-3">
                    <small class="text-muted d-block" style="font-size: 0.75rem;">Kelas & Konsentrasi Keahlian</small>
                    <span class="fw-semibold text-dark">{{ $jurnal->penempatan->siswa->kelas?->nama_kelas ?? '-' }} ({{ $jurnal->penempatan->siswa->jurusan?->kode_jurusan ?? '-' }})</span>
                </div>
                <div class="p-2.5 bg-light rounded-3">
                    <small class="text-muted d-block" style="font-size: 0.75rem;">Perusahaan / DUDI Mitra</small>
                    <span class="fw-semibold text-dark">{{ $jurnal->penempatan->perusahaan->nama_perusahaan }}</span>
                </div>
                <div class="p-2.5 bg-light rounded-3">
                    <small class="text-muted d-block" style="font-size: 0.75rem;">Lokasi Perusahaan</small>
                    <span class="fw-semibold text-dark">{{ $jurnal->penempatan->perusahaan->alamat ?: 'Pekanbaru' }}</span>
                </div>
            </div>

            @if($jurnal->penempatan->siswa->no_hp)
            @php
                $waUrl = \App\Services\WhatsAppNotification::getBroadcastUrl(
                    $jurnal->penempatan->siswa->no_hp,
                    "Bimbingan Jurnal PKL: {$jurnal->penempatan->siswa->nama}",
                    "Halo {$jurnal->penempatan->siswa->nama}, saya telah memeriksa jurnal PKL Anda tanggal " . \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('d F Y') . ". Silakan cek portal SI-PKL."
                );
            @endphp
            <div class="pt-2 border-top">
                <a href="{{ $waUrl }}" target="_blank" class="btn btn-outline-success btn-sm w-100 d-inline-flex align-items-center justify-content-center gap-2" style="border-radius: 8px; font-weight: 600;">
                    <i class="ph-bold ph-whatsapp-logo" style="font-size: 18px;"></i> Hubungi Siswa via WhatsApp
                </a>
            </div>
            @endif
        </div>
    </div>

    {{-- Detail Jurnal --}}
    <div class="col-lg-8">
        <div class="pro-card p-4 mb-4" style="border-radius: 16px;">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4 pb-3 border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <i class="ph ph-calendar text-primary" style="font-size: 22px;"></i>
                    <h5 class="fw-bold mb-0 text-dark">{{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('l, d F Y') }}</h5>
                </div>
                <div>
                    @if($jurnal->status_validasi == 'menunggu')
                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-1.5 rounded-pill" style="font-weight: 600;">
                            <i class="ph ph-clock me-1"></i> Menunggu Validasi DUDI
                        </span>
                    @elseif($jurnal->status_validasi == 'disetujui')
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill" style="font-weight: 600;">
                            <i class="ph ph-check-circle me-1"></i> Disetujui oleh Instruktur DUDI
                        </span>
                    @else
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1.5 rounded-pill" style="font-weight: 600;">
                            <i class="ph ph-x-circle me-1"></i> Ditolak oleh Instruktur DUDI
                        </span>
                    @endif
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold text-dark mb-2 d-flex align-items-center gap-1.5">
                    <i class="ph ph-notepad text-primary"></i> Rincian Aktivitas / Pekerjaan yang Dilakukan:
                </label>
                <div class="p-3 bg-light rounded-3 border" style="line-height: 1.7; font-size: 0.925rem; white-space: pre-line;">{{ $jurnal->kegiatan }}</div>
            </div>

            @if($jurnal->foto)
            @php
                $photoUrl = str_starts_with($jurnal->foto, 'uploads/') ? asset($jurnal->foto) : asset('storage/' . $jurnal->foto);
            @endphp
            <div class="mb-4">
                <label class="form-label fw-bold text-dark mb-2 d-flex align-items-center gap-1.5">
                    <i class="ph ph-image text-primary"></i> Bukti Dokumentasi Foto Kegiatan:
                </label>
                <div>
                    <a href="{{ $photoUrl }}" target="_blank">
                        <img src="{{ $photoUrl }}" alt="Foto Kegiatan" class="img-fluid rounded-3 border shadow-sm" style="max-height: 360px; object-fit: contain;">
                    </a>
                    <small class="text-muted d-block mt-1"><i class="ph ph-magnifying-glass-plus me-1"></i> Klik gambar untuk memperbesar.</small>
                </div>
            </div>
            @endif

            {{-- Catatan / Feedback DUDI yang tersimpan --}}
            @if($jurnal->komentar_guru)
                <div class="mb-4 p-3 rounded-3 {{ $isLaporan ? 'bg-danger-subtle border border-danger-subtle' : 'bg-light border' }}">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="ph {{ $isLaporan ? 'ph-megaphone text-danger' : 'ph-chat-circle text-primary' }} fs-5"></i>
                        <h6 class="fw-bold mb-0 text-dark">{{ $isLaporan ? 'Laporan Khusus Instruktur DUDI' : 'Catatan / Catatan Instruktur DUDI' }}</h6>
                    </div>
                    <div class="small text-dark ps-4">{{ $cleanMsg }}</div>
                </div>
            @endif

            <hr class="my-4">

            {{-- Form Supervisi Guru Pembimbing --}}
            <form action="{{ route('guru.validasi.update', $jurnal) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="form-label fw-bold text-dark d-flex align-items-center gap-1.5">
                        <i class="ph ph-chat-centered-text text-primary"></i> Catatan Pembinaan / Tanggapan Guru Pembimbing (Opsional)
                    </label>
                    <textarea name="komentar_guru" class="form-control" rows="3" placeholder="Tuliskan bimbingan, catatan koordinasi ke industri, atau feedback untuk siswa..." style="border-radius: 8px;">{{ old('komentar_guru', $jurnal->komentar_guru) }}</textarea>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                    <button type="submit" name="status_validasi" value="ditolak" class="btn btn-outline-danger d-inline-flex align-items-center gap-1.5 px-3 py-2" style="border-radius: 8px; font-weight: 600;"
                        onclick="return confirm('Tandai jurnal ini perlu revisi / penolakan?')">
                        <i class="ph ph-x-circle" style="font-size: 18px;"></i> Minta Revisi Siswa
                    </button>
                    <button type="submit" name="status_validasi" value="disetujui" class="btn btn-success d-inline-flex align-items-center gap-1.5 px-4 py-2" style="border-radius: 8px; font-weight: 600; box-shadow: 0 4px 12px rgba(34, 197, 94, 0.25);">
                        <i class="ph ph-check-circle" style="font-size: 18px;"></i> Simpan / Konfirmasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

