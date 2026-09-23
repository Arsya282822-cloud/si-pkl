@extends('layouts.app')
@section('title', 'Tambah Data PKS')
@section('topbar_title', 'Tambah Data PKS')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.pks.index') }}" class="btn btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i> Kembali</a>
    <div>
        <h1 class="page-title mb-1">Tambah Data PKS</h1>
        <p class="page-subtitle mb-0">Masukkan informasi Perjanjian Kerja Sama baru.</p>
    </div>
</div>

<div class="card dashboard-card">
    <div class="card-body p-4">
        <form action="{{ route('admin.pks.store') }}" method="POST">
            @csrf
            
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Validasi (Vld)</label>
                    <input type="text" name="vld" class="form-control" value="{{ old('vld') }}" placeholder="Contoh: Ya / Tidak">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Jenis Kerjasama</label>
                    <input type="text" name="jenis_kerjasama" class="form-control" value="{{ old('jenis_kerjasama') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Dunia Usaha & Industri</label>
                    <input type="text" name="dunia_usaha_industri" class="form-control" value="{{ old('dunia_usaha_industri') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Nama DUDI <span class="text-danger">*</span></label>
                    <input type="text" name="nama_dudi" class="form-control @error('nama_dudi') is-invalid @enderror" value="{{ old('nama_dudi') }}" required>
                    @error('nama_dudi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Nomor PKS</label>
                    <input type="text" name="nomor_pks" class="form-control" value="{{ old('nomor_pks') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Judul PKS</label>
                    <input type="text" name="judul_pks" class="form-control" value="{{ old('judul_pks') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Tgl Mulai</label>
                    <input type="date" name="tgl_mulai" class="form-control" value="{{ old('tgl_mulai') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Tgl Selesai</label>
                    <input type="date" name="tgl_selesai" class="form-control" value="{{ old('tgl_selesai') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">NPWP DUDI</label>
                    <input type="text" name="npwp_dudi" class="form-control" value="{{ old('npwp_dudi') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Nama Bidang Usaha</label>
                    <input type="text" name="nama_bidang_usaha" class="form-control" value="{{ old('nama_bidang_usaha') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Telp Kantor</label>
                    <input type="text" name="telp_kantor" class="form-control" value="{{ old('telp_kantor') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Fax</label>
                    <input type="text" name="fax" class="form-control" value="{{ old('fax') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Contact Person</label>
                    <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Telepon CP</label>
                    <input type="text" name="telepon_cp" class="form-control" value="{{ old('telepon_cp') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Jabatan CP</label>
                    <input type="text" name="jabatan_cp" class="form-control" value="{{ old('jabatan_cp') }}">
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.pks.index') }}" class="btn btn-light">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Data</button>
            </div>
        </form>
    </div>
</div>
@endsection
