@extends('layouts.app')
@section('title', 'Edit Lembar Observasi PKL')
@section('topbar_title', 'Edit Lembar Observasi PKL')

@section('content')
@php
    $routePrefix = Auth::user()->role?->nama_role === 'admin' ? 'admin' : 'guru';
@endphp
<div class="container-fluid px-0" style="max-width: 1100px;">

    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--text-main);">Edit Lembar Observasi: {{ $observasi->nama_murid }}</h4>
            <p class="text-muted mb-0" style="font-size: 0.875rem;">
                Perbarui nilai atau butir capaian pembelajaran, lalu simpan dan cetak PDF resmi.
            </p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route($routePrefix . '.observasi.cetak', $observasi->id) }}" target="_blank" class="btn btn-outline-primary d-inline-flex align-items-center gap-1.5" style="border-radius: 8px;">
                <i class="ph ph-printer"></i> Lihat & Cetak PDF
            </a>
            <a href="{{ route($routePrefix . '.observasi.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1.5" style="border-radius: 8px;">
                <i class="ph ph-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 10px;">
            <strong class="d-block mb-1"><i class="ph ph-warning-circle me-1"></i> Mohon periksa kembali isian formulir:</strong>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route($routePrefix . '.observasi.update', $observasi->id) }}" method="POST" id="formObservasi">
        @csrf
        @method('PUT')

        <!-- DATA IDENTITAS RESMI (I - IV) -->
        <div class="pro-card p-4 mb-4 border-0 shadow-sm" style="border-radius: 16px;">
            <div class="d-flex align-items-center gap-2 mb-3 border-bottom pb-2">
                <h5 class="fw-bold mb-0 text-dark">Data Identitas Institusi, Pimpinan, Instruktur & Murid</h5>
            </div>

            <!-- I. DATA INSTITUSI -->
            <h6 class="fw-bold text-primary mb-2"><i class="ph ph-buildings me-1"></i> I. DATA INSTITUSI (TEMPAT PKL)</h6>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label small text-muted mb-1">Nama Lengkap Institusi / Perusahaan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_institusi" id="nama_institusi" class="form-control" value="{{ old('nama_institusi', $observasi->nama_institusi) }}" required style="border-radius: 8px;">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Telp / Fax</label>
                    <input type="text" name="telp_institusi" id="telp_institusi" class="form-control" value="{{ old('telp_institusi', $observasi->telp_institusi) }}" style="border-radius: 8px;">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Kota</label>
                    <input type="text" name="kota_cetak" id="kota_cetak" class="form-control" value="{{ old('kota_cetak', $observasi->kota_cetak ?: 'Pekanbaru') }}" style="border-radius: 8px;">
                </div>
                <div class="col-md-12">
                    <label class="form-label small text-muted mb-1">Alamat Institusi</label>
                    <input type="text" name="alamat_institusi" id="alamat_institusi" class="form-control" value="{{ old('alamat_institusi', $observasi->alamat_institusi) }}" style="border-radius: 8px;">
                </div>
            </div>

            <hr class="my-3 opacity-25">

            <!-- II. DATA PIMPINAN & III. DATA INSTRUKTUR -->
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <h6 class="fw-bold text-primary mb-2"><i class="ph ph-user-circle me-1"></i> II. DATA PIMPINAN INSTITUSI</h6>
                    <div class="mb-2">
                        <label class="form-label small text-muted mb-1">Nama Lengkap Pimpinan</label>
                        <input type="text" name="nama_pimpinan" id="nama_pimpinan" class="form-control" value="{{ old('nama_pimpinan', $observasi->nama_pimpinan) }}" style="border-radius: 8px;">
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1">NIP / No. Registrasi</label>
                            <input type="text" name="nip_pimpinan" id="nip_pimpinan" class="form-control" value="{{ old('nip_pimpinan', $observasi->nip_pimpinan) }}" style="border-radius: 8px;">
                        </div>
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1">Jabatan</label>
                            <input type="text" name="jabatan_pimpinan" id="jabatan_pimpinan" class="form-control" value="{{ old('jabatan_pimpinan', $observasi->jabatan_pimpinan ?: 'Pimpinan') }}" style="border-radius: 8px;">
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <h6 class="fw-bold text-primary mb-2"><i class="ph ph-chalkboard-teacher me-1"></i> III. DATA INSTRUKTUR (DUDI)</h6>
                    <div class="mb-2">
                        <label class="form-label small text-muted mb-1">Nama Lengkap Instruktur DUDI</label>
                        <input type="text" name="nama_instruktur" id="nama_instruktur" class="form-control" value="{{ old('nama_instruktur', $observasi->nama_instruktur) }}" style="border-radius: 8px;">
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1">NIP / No. Registrasi</label>
                            <input type="text" name="nip_instruktur" id="nip_instruktur" class="form-control" value="{{ old('nip_instruktur', $observasi->nip_instruktur) }}" style="border-radius: 8px;">
                        </div>
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1">Jabatan</label>
                            <input type="text" name="jabatan_instruktur" id="jabatan_instruktur" class="form-control" value="{{ old('jabatan_instruktur', $observasi->jabatan_instruktur ?: 'Pembimbing Lapangan') }}" style="border-radius: 8px;">
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-3 opacity-25">

            <!-- IV. MURID -->
            <h6 class="fw-bold text-primary mb-2"><i class="ph ph-student me-1"></i> IV. DATA MURID (SISWA PKL)</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small text-muted mb-1">Nama Lengkap Murid <span class="text-danger">*</span></label>
                    <input type="text" name="nama_murid" id="nama_murid" class="form-control" value="{{ old('nama_murid', $observasi->nama_murid) }}" required style="border-radius: 8px;">
                </div>
                <div class="col-md-6">
                    <label class="form-label small text-muted mb-1">Konsentrasi Keahlian / Jurusan <span class="text-danger">*</span></label>
                    <input type="text" name="konsentrasi_keahlian" id="konsentrasi_keahlian" class="form-control" value="{{ old('konsentrasi_keahlian', $observasi->konsentrasi_keahlian) }}" required style="border-radius: 8px;">
                </div>
            </div>
        </div>

        <!-- PENILAIAN CAPAIAN & KOMPETENSI -->
        <div class="pro-card p-4 mb-4 border-0 shadow-sm" style="border-radius: 16px;">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 border-bottom pb-2">
                <h5 class="fw-bold mb-0 text-dark">Tabel Penilaian Observasi & Capaian Pembelajaran</h5>
                <div class="d-flex align-items-center gap-2">
                    <label class="form-label small text-muted mb-0 text-nowrap"><i class="ph ph-sliders me-1"></i> Ganti Preset Jurusan:</label>
                    <select class="form-select form-select-sm" id="presetSelector" onchange="loadPresetJurusan(this.value)" style="border-radius: 8px; font-weight: 600; width: auto;">
                        @foreach($presets as $code => $preset)
                            <option value="{{ $code }}">{{ $preset['nama'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- TABEL PENILAIAN -->
            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="obsTable" style="font-size: 0.875rem;">
                    <thead class="table-light text-center">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Capaian dan Tujuan Pembelajaran</th>
                            <th style="width: 110px;">Observasi I</th>
                            <th style="width: 110px;">Observasi II</th>
                            <th style="width: 110px;">Akhir</th>
                            <th style="width: 90px;">Rerata</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- SECTION I: Soft Skills -->
                        <tr class="table-secondary fw-bold">
                            <td class="text-center">I</td>
                            <td colspan="5">Menerapkan soft skills yang dibutuhkan dalam dunia kerja (tempat PKL)</td>
                        </tr>
                        @php
                            $softskills = $observasi->data_softskills ?: [];
                        @endphp
                        @foreach($softskills as $idx => $item)
                        <tr class="score-row">
                            <td class="text-center text-muted">{{ $idx + 1 }}</td>
                            <td>
                                <input type="text" name="softskills[{{ $idx }}][tujuan]" class="form-control form-control-sm border-0 bg-transparent" value="{{ $item['tujuan'] ?? '' }}" readonly>
                            </td>
                            <td><input type="number" step="0.1" min="0" max="100" name="softskills[{{ $idx }}][obs1]" class="form-control form-control-sm text-center input-score obs1" value="{{ $item['obs1'] ?? '' }}" oninput="calcRow(this)" placeholder="0-100"></td>
                            <td><input type="number" step="0.1" min="0" max="100" name="softskills[{{ $idx }}][obs2]" class="form-control form-control-sm text-center input-score obs2" value="{{ $item['obs2'] ?? '' }}" oninput="calcRow(this)" placeholder="0-100"></td>
                            <td><input type="number" step="0.1" min="0" max="100" name="softskills[{{ $idx }}][akhir]" class="form-control form-control-sm text-center input-score akhir" value="{{ $item['akhir'] ?? '' }}" oninput="calcRow(this)" placeholder="0-100"></td>
                            <td><input type="text" name="softskills[{{ $idx }}][rerata]" class="form-control form-control-sm text-center bg-light fw-bold rerata" value="{{ $item['rerata'] ?? '' }}" readonly></td>
                        </tr>
                        @endforeach

                        <!-- SECTION II: Kompetensi Teknis POS -->
                        <tr class="table-secondary fw-bold">
                            <td class="text-center">II</td>
                            <td colspan="5" class="d-flex justify-content-between align-items-center">
                                <span>Menerapkan kompetensi teknis pada pekerjaan sesuai POS yang berlaku di dunia kerja</span>
                                <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2" onclick="addKompetensiRow('section2Body', 'kompetensi_teknis')" style="font-size: 0.75rem;">
                                    <i class="ph ph-plus me-1"></i> Tambah Baris
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tbody id="section2Body">
                        @php
                            $kompetensiTeknis = $observasi->data_kompetensi_teknis ?: [];
                        @endphp
                        @foreach($kompetensiTeknis as $idx => $item)
                        <tr class="score-row">
                            <td class="text-center text-muted">{{ $idx + 1 }}</td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="kompetensi_teknis[{{ $idx }}][tujuan]" class="form-control form-control-sm" value="{{ $item['tujuan'] ?? '' }}" placeholder="Tuliskan capaian kompetensi teknis...">
                                    <button class="btn btn-outline-danger" type="button" onclick="deleteRow(this)" title="Hapus"><i class="ph ph-trash"></i></button>
                                </div>
                            </td>
                            <td><input type="number" step="0.1" min="0" max="100" name="kompetensi_teknis[{{ $idx }}][obs1]" class="form-control form-control-sm text-center input-score obs1" value="{{ $item['obs1'] ?? '' }}" oninput="calcRow(this)" placeholder="0-100"></td>
                            <td><input type="number" step="0.1" min="0" max="100" name="kompetensi_teknis[{{ $idx }}][obs2]" class="form-control form-control-sm text-center input-score obs2" value="{{ $item['obs2'] ?? '' }}" oninput="calcRow(this)" placeholder="0-100"></td>
                            <td><input type="number" step="0.1" min="0" max="100" name="kompetensi_teknis[{{ $idx }}][akhir]" class="form-control form-control-sm text-center input-score akhir" value="{{ $item['akhir'] ?? '' }}" oninput="calcRow(this)" placeholder="0-100"></td>
                            <td><input type="text" name="kompetensi_teknis[{{ $idx }}][rerata]" class="form-control form-control-sm text-center bg-light fw-bold rerata" value="{{ $item['rerata'] ?? '' }}" readonly></td>
                        </tr>
                        @endforeach
                    </tbody>

                    <!-- SECTION III: Kompetensi Teknis Baru -->
                    <tbody>
                        <tr class="table-secondary fw-bold">
                            <td class="text-center">III</td>
                            <td colspan="5" class="d-flex justify-content-between align-items-center">
                                <span>Menerapkan kompetensi teknis baru/atau kompetensi teknis yang belum tuntas dipelajari sesuai konsentrasi keahlian</span>
                                <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2" onclick="addKompetensiRow('section3Body', 'kompetensi_baru')" style="font-size: 0.75rem;">
                                    <i class="ph ph-plus me-1"></i> Tambah Baris
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tbody id="section3Body">
                        @php
                            $kompetensiBaru = $observasi->data_kompetensi_baru ?: [];
                        @endphp
                        @foreach($kompetensiBaru as $idx => $item)
                        <tr class="score-row">
                            <td class="text-center text-muted">{{ $idx + 1 }}</td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="kompetensi_baru[{{ $idx }}][tujuan]" class="form-control form-control-sm" value="{{ $item['tujuan'] ?? '' }}" placeholder="Tuliskan kompetensi baru...">
                                    <button class="btn btn-outline-danger" type="button" onclick="deleteRow(this)" title="Hapus"><i class="ph ph-trash"></i></button>
                                </div>
                            </td>
                            <td><input type="number" step="0.1" min="0" max="100" name="kompetensi_baru[{{ $idx }}][obs1]" class="form-control form-control-sm text-center input-score obs1" value="{{ $item['obs1'] ?? '' }}" oninput="calcRow(this)" placeholder="0-100"></td>
                            <td><input type="number" step="0.1" min="0" max="100" name="kompetensi_baru[{{ $idx }}][obs2]" class="form-control form-control-sm text-center input-score obs2" value="{{ $item['obs2'] ?? '' }}" oninput="calcRow(this)" placeholder="0-100"></td>
                            <td><input type="number" step="0.1" min="0" max="100" name="kompetensi_baru[{{ $idx }}][akhir]" class="form-control form-control-sm text-center input-score akhir" value="{{ $item['akhir'] ?? '' }}" oninput="calcRow(this)" placeholder="0-100"></td>
                            <td><input type="text" name="kompetensi_baru[{{ $idx }}][rerata]" class="form-control form-control-sm text-center bg-light fw-bold rerata" value="{{ $item['rerata'] ?? '' }}" readonly></td>
                        </tr>
                        @endforeach
                    </tbody>

                    <!-- SECTION IV: Analisis Usaha -->
                    <tbody>
                        <tr class="table-secondary fw-bold">
                            <td class="text-center">IV</td>
                            <td colspan="5">Melakukan analisis usaha secara mandiri</td>
                        </tr>
                        @php
                            $analisisUsaha = $observasi->data_analisis_usaha ?: [];
                        @endphp
                        @foreach($analisisUsaha as $idx => $item)
                        <tr class="score-row">
                            <td class="text-center text-muted">{{ $idx + 1 }}</td>
                            <td>
                                <input type="text" name="analisis_usaha[{{ $idx }}][tujuan]" class="form-control form-control-sm border-0 bg-transparent" value="{{ $item['tujuan'] ?? '' }}" readonly>
                            </td>
                            <td><input type="number" step="0.1" min="0" max="100" name="analisis_usaha[{{ $idx }}][obs1]" class="form-control form-control-sm text-center input-score obs1" value="{{ $item['obs1'] ?? '' }}" oninput="calcRow(this)" placeholder="0-100"></td>
                            <td><input type="number" step="0.1" min="0" max="100" name="analisis_usaha[{{ $idx }}][obs2]" class="form-control form-control-sm text-center input-score obs2" value="{{ $item['obs2'] ?? '' }}" oninput="calcRow(this)" placeholder="0-100"></td>
                            <td><input type="number" step="0.1" min="0" max="100" name="analisis_usaha[{{ $idx }}][akhir]" class="form-control form-control-sm text-center input-score akhir" value="{{ $item['akhir'] ?? '' }}" oninput="calcRow(this)" placeholder="0-100"></td>
                            <td><input type="text" name="analisis_usaha[{{ $idx }}][rerata]" class="form-control form-control-sm text-center bg-light fw-bold rerata" value="{{ $item['rerata'] ?? '' }}" readonly></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TANGGAL PELAKSANAAN & PENGESAHAN -->
        <div class="pro-card p-4 mb-4 border-0 shadow-sm" style="border-radius: 16px;">
            <div class="d-flex align-items-center gap-2 mb-3 border-bottom pb-2">
                <h5 class="fw-bold mb-0 text-dark">Tanggal Pelaksanaan Observasi & Catatan</h5>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label small text-muted mb-1">Tanggal Observasi I</label>
                    <input type="date" name="tgl_observasi_1" class="form-control" value="{{ old('tgl_observasi_1', $observasi->tgl_observasi_1?->format('Y-m-d')) }}" style="border-radius: 8px;">
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted mb-1">Tanggal Observasi II</label>
                    <input type="date" name="tgl_observasi_2" class="form-control" value="{{ old('tgl_observasi_2', $observasi->tgl_observasi_2?->format('Y-m-d')) }}" style="border-radius: 8px;">
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted mb-1">Tanggal Observasi Akhir</label>
                    <input type="date" name="tgl_observasi_akhir" class="form-control" value="{{ old('tgl_observasi_akhir', $observasi->tgl_observasi_akhir?->format('Y-m-d')) }}" style="border-radius: 8px;">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small text-muted mb-1">Catatan Tambahan Observasi (Opsional)</label>
                <textarea name="catatan_umum" class="form-control" rows="2" placeholder="Tuliskan catatan khusus atau rekomendasi..." style="border-radius: 8px;">{{ old('catatan_umum', $observasi->catatan_umum) }}</textarea>
            </div>
        </div>

        <!-- ACTION BUTTONS -->
        <div class="d-flex flex-wrap justify-content-end align-items-center gap-2 p-3 bg-white border shadow-sm sticky-bottom" style="border-radius: 12px; z-index: 80;">
            <a href="{{ route('guru.observasi.index') }}" class="btn btn-light px-3 py-2" style="border-radius: 8px;">
                Batal
            </a>
            <button type="submit" name="action" value="simpan" class="btn btn-secondary px-4 py-2 d-inline-flex align-items-center gap-1.5" style="border-radius: 8px; font-weight: 600;">
                <i class="ph ph-floppy-disk" style="font-size: 18px;"></i>
                Simpan Perubahan
            </button>
            <button type="submit" name="action" value="simpan_cetak" class="btn btn-primary px-4 py-2 d-inline-flex align-items-center gap-1.5" style="border-radius: 8px; font-weight: 600; box-shadow: 0 4px 14px rgba(2, 132, 199, 0.3);">
                <i class="ph ph-printer" style="font-size: 18px;"></i>
                Simpan & Langsung Cetak PDF
            </button>
        </div>
    </form>
</div>

<!-- JS -->
<script>
    const presetsData = @json($presets);

    function loadPresetJurusan(code) {
        const preset = presetsData[code];
        if (!preset) return;
        if (!confirm('Ganti daftar kompetensi teknis dengan preset ' + preset.nama + '?')) return;

        const tbody = document.getElementById('section2Body');
        tbody.innerHTML = '';

        preset.kompetensi.forEach((item, idx) => {
            const tr = document.createElement('tr');
            tr.className = 'score-row';
            tr.innerHTML = `
                <td class="text-center text-muted">${idx + 1}</td>
                <td>
                    <div class="input-group input-group-sm">
                        <input type="text" name="kompetensi_teknis[${idx}][tujuan]" class="form-control form-control-sm" value="${item}" placeholder="Tuliskan capaian kompetensi teknis...">
                        <button class="btn btn-outline-danger" type="button" onclick="deleteRow(this)" title="Hapus"><i class="ph ph-trash"></i></button>
                    </div>
                </td>
                <td><input type="number" step="0.1" min="0" max="100" name="kompetensi_teknis[${idx}][obs1]" class="form-control form-control-sm text-center input-score obs1" oninput="calcRow(this)" placeholder="0-100"></td>
                <td><input type="number" step="0.1" min="0" max="100" name="kompetensi_teknis[${idx}][obs2]" class="form-control form-control-sm text-center input-score obs2" oninput="calcRow(this)" placeholder="0-100"></td>
                <td><input type="number" step="0.1" min="0" max="100" name="kompetensi_teknis[${idx}][akhir]" class="form-control form-control-sm text-center input-score akhir" oninput="calcRow(this)" placeholder="0-100"></td>
                <td><input type="text" name="kompetensi_teknis[${idx}][rerata]" class="form-control form-control-sm text-center bg-light fw-bold rerata" readonly></td>
            `;
            tbody.appendChild(tr);
        });
    }

    function addKompetensiRow(tbodyId, prefixName) {
        const tbody = document.getElementById(tbodyId);
        const idx = tbody.children.length;
        const tr = document.createElement('tr');
        tr.className = 'score-row';
        tr.innerHTML = `
            <td class="text-center text-muted">${idx + 1}</td>
            <td>
                <div class="input-group input-group-sm">
                    <input type="text" name="${prefixName}[${idx}][tujuan]" class="form-control form-control-sm" placeholder="Tuliskan butir capaian kompetensi...">
                    <button class="btn btn-outline-danger" type="button" onclick="deleteRow(this)" title="Hapus"><i class="ph ph-trash"></i></button>
                </div>
            </td>
            <td><input type="number" step="0.1" min="0" max="100" name="${prefixName}[${idx}][obs1]" class="form-control form-control-sm text-center input-score obs1" oninput="calcRow(this)" placeholder="0-100"></td>
            <td><input type="number" step="0.1" min="0" max="100" name="${prefixName}[${idx}][obs2]" class="form-control form-control-sm text-center input-score obs2" oninput="calcRow(this)" placeholder="0-100"></td>
            <td><input type="number" step="0.1" min="0" max="100" name="${prefixName}[${idx}][akhir]" class="form-control form-control-sm text-center input-score akhir" oninput="calcRow(this)" placeholder="0-100"></td>
            <td><input type="text" name="${prefixName}[${idx}][rerata]" class="form-control form-control-sm text-center bg-light fw-bold rerata" readonly></td>
        `;
        tbody.appendChild(tr);
    }

    function deleteRow(btn) {
        const row = btn.closest('tr');
        if (row) row.remove();
    }

    function calcRow(input) {
        const row = input.closest('tr');
        if (!row) return;

        const obs1Val = parseFloat(row.querySelector('.obs1')?.value);
        const obs2Val = parseFloat(row.querySelector('.obs2')?.value);
        const akhirVal = parseFloat(row.querySelector('.akhir')?.value);

        const nums = [];
        if (!isNaN(obs1Val)) nums.push(obs1Val);
        if (!isNaN(obs2Val)) nums.push(obs2Val);
        if (!isNaN(akhirVal)) nums.push(akhirVal);

        const rerataInput = row.querySelector('.rerata');
        if (rerataInput) {
            if (nums.length > 0) {
                const avg = nums.reduce((a, b) => a + b, 0) / nums.length;
                rerataInput.value = avg.toFixed(1);
            } else {
                rerataInput.value = '';
            }
        }
    }
</script>
@endsection
