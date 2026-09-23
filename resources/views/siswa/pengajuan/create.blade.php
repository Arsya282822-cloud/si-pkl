@extends('layouts.app')

@section('title', 'Formulir Pengajuan Tempat PKL Mandiri')

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1 text-dark">Formulir Pengajuan Tempat PKL Mandiri</h4>
            <p class="text-muted small mb-0">Lengkapi informasi tempat/instansi PKL yang Anda usulkan secara mandiri.</p>
        </div>
        <a href="{{ route('siswa.pengajuan.index') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
            <i class="ph ph-arrow-left"></i> Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger pro-card mb-4 border-0 p-3" style="background-color: #fef2f2; border-left: 5px solid #ef4444 !important;">
            <ul class="mb-0 small ps-3 text-danger">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="pro-card p-4 p-md-5">
                <form action="{{ route('siswa.pengajuan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- 1. Periode PKL -->
                    <div class="mb-4 pb-3 border-bottom">
                        <label class="form-label fw-bold text-dark mb-1">
                            <i class="ph ph-calendar text-primary me-1"></i> Periode Pelaksanaan PKL <span class="text-danger">*</span>
                        </label>
                        @if($periodeAktif)
                            <div class="p-3 bg-light rounded-3 border d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="fw-bold text-dark">{{ $periodeAktif->nama_periode }}</div>
                                    <small class="text-muted">Tahun Ajaran {{ $periodeAktif->tahun_ajaran }} ({{ \Carbon\Carbon::parse($periodeAktif->tanggal_mulai)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($periodeAktif->tanggal_selesai)->format('d M Y') }})</small>
                                </div>
                                <span class="badge bg-success">Periode Aktif</span>
                            </div>
                            <input type="hidden" name="periode_pkl_id" value="{{ $periodeAktif->id }}">
                        @else
                            <div class="alert alert-warning mb-0">Tidak ada periode aktif yang tersedia. Hubungi Admin.</div>
                        @endif
                    </div>

                    <!-- 2. Data Perusahaan -->
                    <h5 class="fw-bold text-dark mb-3">
                        <i class="ph ph-buildings text-primary me-1"></i> Profil Mitra / Perusahaan
                    </h5>

                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold text-dark">Nama Perusahaan / Instansi / Kantor <span class="text-danger">*</span></label>
                            <input type="text" name="nama_perusahaan" class="form-control" placeholder="Contoh: PT Telekomunikasi Indonesia Witel Riau" value="{{ old('nama_perusahaan') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-dark">Bidang Usaha</label>
                            <input type="text" name="bidang_usaha" class="form-control" placeholder="Contoh: IT, Jaringan, Perbankan" value="{{ old('bidang_usaha') }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark">Alamat Lengkap Perusahaan <span class="text-danger">*</span></label>
                            <textarea name="alamat_perusahaan" rows="2" class="form-control" placeholder="Contoh: Jl. Jenderal Sudirman No. 199, Pekanbaru" required>{{ old('alamat_perusahaan') }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">Kota / Kabupaten</label>
                            <input type="text" name="kota" class="form-control" placeholder="Pekanbaru" value="{{ old('kota', 'Pekanbaru') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">Nama Pimpinan / Kepala Cabang</label>
                            <input type="text" name="nama_pimpinan" class="form-control" placeholder="Contoh: Ir. Budi Santoso, M.M." value="{{ old('nama_pimpinan') }}">
                        </div>
                    </div>

                    <!-- 3. Kontak Person / HRD -->
                    <h5 class="fw-bold text-dark mb-3">
                        <i class="ph ph-identification-card text-primary me-1"></i> Narahubung / HRD Perusahaan
                    </h5>

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-dark">Nama Kontak Person (HRD/PIC)</label>
                            <input type="text" name="kontak_person" class="form-control" placeholder="Contoh: Ibu Rina (HRD)" value="{{ old('kontak_person') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-dark">No. WhatsApp / HP / Telp</label>
                            <input type="text" name="no_telepon" class="form-control" placeholder="Contoh: 081234567890" value="{{ old('no_telepon') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-dark">Email Resmi Perusahaan</label>
                            <input type="email" name="email" class="form-control" placeholder="hrd@perusahaan.com" value="{{ old('email') }}">
                        </div>
                    </div>

                    <!-- 4. Lampiran & Alasan -->
                    <h5 class="fw-bold text-dark mb-3">
                        <i class="ph ph-file-text text-primary me-1"></i> Alasan & Berkas Pendukung
                    </h5>

                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark">Alasan Memilih Perusahaan Ini</label>
                            <textarea name="alasan_memilih" rows="3" class="form-control" placeholder="Jelaskan kesesuaian keahlian, minat, atau fasilitas belajar yang disediakan perusahaan...">{{ old('alasan_memilih') }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark">Surat Balasan / Bukti Penerimaan (Opsional)</label>
                            <input type="file" name="file_surat_balasan" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-muted">Format: PDF, JPG, PNG (Maksimal 3MB). Upload jika sudah menerima surat balasan persetujuan dari perusahaan.</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('siswa.pengajuan.index') }}" class="btn btn-light px-4 py-2 fw-semibold">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2">
                            <i class="ph ph-paper-plane-tilt"></i> Kirim Pengajuan PKL
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
