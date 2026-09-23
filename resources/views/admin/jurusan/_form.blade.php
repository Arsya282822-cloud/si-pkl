@csrf
<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Kode Jurusan <span class="text-danger">*</span></label>
        <input name="kode_jurusan" value="{{ old('kode_jurusan', $jurusan->kode_jurusan ?? '') }}" class="form-control @error('kode_jurusan') is-invalid @enderror" required>
        @error('kode_jurusan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-8">
        <label class="form-label">Nama Jurusan <span class="text-danger">*</span></label>
        <input name="nama_jurusan" value="{{ old('nama_jurusan', $jurusan->nama_jurusan ?? '') }}" class="form-control @error('nama_jurusan') is-invalid @enderror" required>
        @error('nama_jurusan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label class="form-label">Deskripsi</label>
        <textarea name="deskripsi" rows="4" class="form-control">{{ old('deskripsi', $jurusan->deskripsi ?? '') }}</textarea>
    </div>
    <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="1" @selected(old('status', $jurusan->status ?? true))>Aktif</option>
            <option value="0" @selected(!old('status', $jurusan->status ?? true))>Nonaktif</option>
        </select>
    </div>
</div>
