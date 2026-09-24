@csrf
<div class="row g-3">
    <div class="col-md-6"><label class="form-label">Nama Kelas <span class="text-danger">*</span></label><input name="nama_kelas" value="{{ old('nama_kelas', $kelas->nama_kelas ?? '') }}" class="form-control" placeholder="Contoh: XII RPL 1" required></div>
    <div class="col-md-3">
        <label class="form-label">Tingkat <span class="text-danger">*</span></label>
        @php $selectedTingkat = old('tingkat', $kelas->tingkat ?? 'XII'); @endphp
        <select name="tingkat" class="form-select" required>
            <option value="XII" @selected(in_array($selectedTingkat, ['XII', '12']))>XII (Kelas 12)</option>
            <option value="XI" @selected(in_array($selectedTingkat, ['XI', '11']))>XI (Kelas 11)</option>
            <option value="X" @selected(in_array($selectedTingkat, ['X', '10']))>X (Kelas 10)</option>
        </select>
    </div>
    <div class="col-md-3"><label class="form-label">Status</label><select name="status" class="form-select"><option value="1" @selected(old('status', $kelas->status ?? true))>Aktif</option><option value="0" @selected(!old('status', $kelas->status ?? true))>Nonaktif</option></select></div>
    <div class="col-md-6"><label class="form-label">Jurusan <span class="text-danger">*</span></label><select name="jurusan_id" class="form-select" required><option value="">- Pilih Jurusan -</option>@foreach($jurusan as $item)<option value="{{ $item->id }}" @selected(old('jurusan_id', $kelas->jurusan_id ?? '') == $item->id)>{{ $item->kode_jurusan }} - {{ $item->nama_jurusan }}</option>@endforeach</select></div>
    <div class="col-md-6"><label class="form-label">Wali Kelas</label><select name="wali_kelas_id" class="form-select"><option value="">- Pilih Wali Kelas -</option>@foreach($guru as $item)<option value="{{ $item->id }}" @selected(old('wali_kelas_id', $kelas->wali_kelas_id ?? '') == $item->id)>{{ $item->nama }}</option>@endforeach</select></div>
</div>
