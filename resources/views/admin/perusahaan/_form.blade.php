@csrf
<div class="row g-3">
    <div class="col-md-8"><label class="form-label">Nama Perusahaan <span class="text-danger">*</span></label><input name="nama_perusahaan" value="{{ old('nama_perusahaan',$perusahaan->nama_perusahaan ?? '') }}" class="form-control" required></div>
    <div class="col-md-4"><label class="form-label">Kota</label><input name="kota" value="{{ old('kota',$perusahaan->kota ?? '') }}" class="form-control"></div>
    <div class="col-md-4"><label class="form-label">No. Telepon</label><input name="no_telepon" value="{{ old('no_telepon',$perusahaan->no_telepon ?? '') }}" class="form-control"></div>
    <div class="col-md-4"><label class="form-label">Email</label><input type="email" name="email" value="{{ old('email',$perusahaan->email ?? '') }}" class="form-control"></div>
    <div class="col-md-4"><label class="form-label">Website</label><input type="url" name="website" value="{{ old('website',$perusahaan->website ?? '') }}" class="form-control" placeholder="https://contoh.com"></div>
    <div class="col-md-6"><label class="form-label">Nama Pimpinan</label><input name="nama_pimpinan" value="{{ old('nama_pimpinan',$perusahaan->nama_pimpinan ?? '') }}" class="form-control"></div>
    <div class="col-md-6"><label class="form-label">Status</label><select name="status" class="form-select"><option value="aktif" @selected(old('status',$perusahaan->status ?? 'aktif')==='aktif')>Aktif</option><option value="nonaktif" @selected(old('status',$perusahaan->status ?? '')==='nonaktif')>Nonaktif</option></select></div>
    <div class="col-12"><label class="form-label">Alamat <span class="text-danger">*</span></label><textarea name="alamat" rows="4" class="form-control" required>{{ old('alamat',$perusahaan->alamat ?? '') }}</textarea></div>
</div>
