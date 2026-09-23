@extends('layouts.app')
@section('title', 'Tambah Penempatan')
@section('content')
<div class="mb-4">
    <a href="{{ route('admin.penempatan.index') }}" class="text-decoration-none text-muted">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <h1 class="page-title mt-2">Tambah Penempatan PKL</h1>
</div>

<div class="card dashboard-card">
    <div class="card-body p-4">
        <form action="{{ route('admin.penempatan.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Periode PKL (Aktif) <span class="text-danger">*</span></label>
                <select name="periode_pkl_id" class="form-select @error('periode_pkl_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Periode --</option>
                    @foreach($periode as $p)
                        <option value="{{ $p->id }}" {{ old('periode_pkl_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->nama_periode }} ({{ $p->tahun_ajaran }})
                        </option>
                    @endforeach
                </select>
                @error('periode_pkl_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Perusahaan Industri <span class="text-danger">*</span></label>
                <select name="perusahaan_id" class="form-select @error('perusahaan_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Perusahaan --</option>
                    @foreach($perusahaan as $p)
                        <option value="{{ $p->id }}" {{ old('perusahaan_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->nama_perusahaan }} - {{ $p->kota ?? '' }}
                        </option>
                    @endforeach
                </select>
                @error('perusahaan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Guru Pembimbing <span class="text-danger">*</span></label>
                <select name="guru_id" class="form-select @error('guru_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Guru --</option>
                    @foreach($guru as $g)
                        <option value="{{ $g->id }}" {{ old('guru_id') == $g->id ? 'selected' : '' }}>
                            {{ $g->nama }} (NIP: {{ $g->nip ?? '-' }})
                        </option>
                    @endforeach
                </select>
                @error('guru_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Pilih Siswa <span class="text-danger">*</span></label>
                <p class="text-muted small mb-2">Tahan tombol CTRL (atau CMD di Mac) untuk memilih lebih dari satu siswa sekaligus.</p>
                <select name="siswa_id[]" class="form-select @error('siswa_id') is-invalid @enderror" multiple size="8" required>
                    @foreach($siswa as $s)
                        <option value="{{ $s->id }}" {{ is_array(old('siswa_id')) && in_array($s->id, old('siswa_id')) ? 'selected' : '' }}>
                            {{ $s->kelas?->nama_kelas ?? '-' }} - {{ $s->nama }} ({{ $s->nis }})
                        </option>
                    @endforeach
                </select>
                @error('siswa_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                @if($siswa->isEmpty())
                <div class="form-text text-danger mt-1"><i class="bi bi-exclamation-triangle"></i> Semua siswa sudah ditempatkan pada periode aktif atau data siswa masih kosong.</div>
                @endif
            </div>

            <hr>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary" {{ $siswa->isEmpty() ? 'disabled' : '' }}>
                    <i class="bi bi-save me-1"></i> Simpan Penempatan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
