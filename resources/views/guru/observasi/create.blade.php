@extends('layouts.app')
@section('title', 'Isi Lembar Observasi PKL')
@section('topbar_title', 'Formulir Lembar Observasi PKL')

@section('content')
@php
    $routePrefix = Auth::user()->role?->nama_role === 'admin' ? 'admin' : 'guru';
@endphp
<div class="container-fluid px-0" style="max-width: 1100px;">

    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--text-main);">Pengisian Lembar Observasi PKL</h4>
            <p class="text-muted mb-0" style="font-size: 0.875rem;">
                Isi instrumen observasi capaian pembelajaran siswa di dunia kerja secara digital, pilih kompetensi sesuai kejuruan, dan cetak PDF resmi.
            </p>
        </div>
        <a href="{{ route($routePrefix . '.observasi.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1.5" style="border-radius: 8px;">
            <i class="ph ph-arrow-left"></i> Kembali ke Daftar
        </a>
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

    <form action="{{ route($routePrefix . '.observasi.store') }}" method="POST" id="formObservasi">
        @csrf

        <!-- 1. PILIH SISWA BIMBINGAN (AUTO-FILL) -->
        <div class="pro-card p-4 mb-4 border-0 shadow-sm" style="border-radius: 16px;">
            <div class="d-flex align-items-center gap-2 mb-3 border-bottom pb-2">
                <span class="badge bg-primary px-2.5 py-1.5" style="font-size: 0.8rem; border-radius: 6px;">Langkah 1</span>
                <h5 class="fw-bold mb-0 text-dark">Pilih Siswa & Data Penempatan PKL</h5>
            </div>

            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label fw-semibold text-dark" style="font-size: 0.875rem;">
                        Pilih Peserta Didik Bimbingan <span class="text-danger">*</span>
                    </label>
                    <select class="form-select form-select-lg" id="selectPenempatan" name="penempatan_id" onchange="onPenempatanChange(this)" style="border-radius: 10px; font-size: 0.95rem;">
                        <option value="">-- Pilih Siswa / Tempat PKL --</option>
                        @foreach($penempatans as $p)
                            @php
                                $instruktur = $p->perusahaan?->pembimbingIndustri?->first();
                            @endphp
                            <option value="{{ $p->id }}"
                                data-murid="{{ $p->siswa?->nama }}"
                                data-jurusan="{{ $p->siswa?->jurusan?->nama_jurusan }}"
                                data-kodejurusan="{{ $p->siswa?->jurusan?->kode_jurusan }}"
                                data-institusi="{{ $p->perusahaan?->nama_perusahaan }}"
                                data-alamat="{{ $p->perusahaan?->alamat ?: 'Pekanbaru' }}"
                                data-telp="{{ $p->perusahaan?->no_telepon ?: '-' }}"
                                data-pimpinan="{{ $p->perusahaan?->nama_pimpinan ?: '-' }}"
                                data-instruktur="{{ $instruktur?->nama ?: ($p->perusahaan?->pembimbing_industri ?: '-') }}"
                                data-jabataninstruktur="{{ $instruktur?->jabatan ?: 'Pembimbing Lapangan / Instruktur' }}"
                                {{ (old('penempatan_id') == $p->id || ($selectedPenempatan && $selectedPenempatan->id == $p->id)) ? 'selected' : '' }}>
                                {{ $p->siswa?->nama }} - {{ $p->siswa?->kelas?->nama_kelas }} ({{ $p->perusahaan?->nama_perusahaan }})
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted d-block mt-1">Memilih siswa akan otomatis mengisi data identitas di bawah ini.</small>
                </div>
            </div>
        </div>

        <!-- 2. DATA IDENTITAS RESMI (I - IV) -->
        <div class="pro-card p-4 mb-4 border-0 shadow-sm" style="border-radius: 16px;">
            <div class="d-flex align-items-center gap-2 mb-3 border-bottom pb-2">
                <span class="badge bg-primary px-2.5 py-1.5" style="font-size: 0.8rem; border-radius: 6px;">Langkah 2</span>
                <h5 class="fw-bold mb-0 text-dark">Data Identitas Institusi, Pimpinan, Instruktur & Murid</h5>
            </div>

            <!-- I. DATA INSTITUSI -->
            <h6 class="fw-bold text-primary mb-2"><i class="ph ph-buildings me-1"></i> I. DATA INSTITUSI (TEMPAT PKL)</h6>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label small text-muted mb-1">Nama Lengkap Institusi / Perusahaan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_institusi" id="nama_institusi" class="form-control" value="{{ old('nama_institusi', $selectedPenempatan?->perusahaan?->nama_perusahaan) }}" required style="border-radius: 8px;">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Telp / Fax</label>
                    <input type="text" name="telp_institusi" id="telp_institusi" class="form-control" value="{{ old('telp_institusi', $selectedPenempatan?->perusahaan?->no_telepon) }}" style="border-radius: 8px;">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Kota</label>
                    <input type="text" name="kota_cetak" id="kota_cetak" class="form-control" value="{{ old('kota_cetak', 'Pekanbaru') }}" style="border-radius: 8px;">
                </div>
                <div class="col-md-12">
                    <label class="form-label small text-muted mb-1">Alamat Institusi</label>
                    <input type="text" name="alamat_institusi" id="alamat_institusi" class="form-control" value="{{ old('alamat_institusi', $selectedPenempatan?->perusahaan?->alamat) }}" style="border-radius: 8px;">
                </div>
            </div>

            <hr class="my-3 opacity-25">

            <!-- II. DATA PIMPINAN & III. DATA INSTRUKTUR -->
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <h6 class="fw-bold text-primary mb-2"><i class="ph ph-user-circle me-1"></i> II. DATA PIMPINAN INSTITUSI</h6>
                    <div class="mb-2">
                        <label class="form-label small text-muted mb-1">Nama Lengkap Pimpinan</label>
                        <input type="text" name="nama_pimpinan" id="nama_pimpinan" class="form-control" value="{{ old('nama_pimpinan', $selectedPenempatan?->perusahaan?->nama_pimpinan) }}" style="border-radius: 8px;">
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1">NIP / No. Registrasi</label>
                            <input type="text" name="nip_pimpinan" id="nip_pimpinan" class="form-control" value="{{ old('nip_pimpinan') }}" placeholder="Opsional" style="border-radius: 8px;">
                        </div>
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1">Jabatan</label>
                            <input type="text" name="jabatan_pimpinan" id="jabatan_pimpinan" class="form-control" value="{{ old('jabatan_pimpinan', 'Pimpinan') }}" style="border-radius: 8px;">
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    @php
                        $instruktur = $selectedPenempatan?->perusahaan?->pembimbingIndustri?->first();
                    @endphp
                    <h6 class="fw-bold text-primary mb-2"><i class="ph ph-chalkboard-teacher me-1"></i> III. DATA INSTRUKTUR (DUDI)</h6>
                    <div class="mb-2">
                        <label class="form-label small text-muted mb-1">Nama Lengkap Instruktur DUDI</label>
                        <input type="text" name="nama_instruktur" id="nama_instruktur" class="form-control" value="{{ old('nama_instruktur', $instruktur?->nama ?? $selectedPenempatan?->perusahaan?->pembimbing_industri) }}" style="border-radius: 8px;">
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1">NIP / No. Registrasi</label>
                            <input type="text" name="nip_instruktur" id="nip_instruktur" class="form-control" value="{{ old('nip_instruktur') }}" placeholder="Opsional" style="border-radius: 8px;">
                        </div>
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1">Jabatan</label>
                            <input type="text" name="jabatan_instruktur" id="jabatan_instruktur" class="form-control" value="{{ old('jabatan_instruktur', $instruktur?->jabatan ?? 'Pembimbing Lapangan') }}" style="border-radius: 8px;">
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
                    <input type="text" name="nama_murid" id="nama_murid" class="form-control" value="{{ old('nama_murid', $selectedPenempatan?->siswa?->nama) }}" required style="border-radius: 8px;">
                </div>
                <div class="col-md-6">
                    <label class="form-label small text-muted mb-1">Konsentrasi Keahlian / Jurusan <span class="text-danger">*</span></label>
                    <input type="text" name="konsentrasi_keahlian" id="konsentrasi_keahlian" class="form-control" value="{{ old('konsentrasi_keahlian', $selectedPenempatan?->siswa?->jurusan?->nama_jurusan) }}" required style="border-radius: 8px;">
                </div>
            </div>
        </div>

        <!-- 3. PENILAIAN CAPAIAN & PEMILIHAN KOMPETENSI JURUSAN -->
        <div class="pro-card p-4 mb-4 border-0 shadow-sm" style="border-radius: 16px;">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 border-bottom pb-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary px-2.5 py-1.5" style="font-size: 0.8rem; border-radius: 6px;">Langkah 3</span>
                    <h5 class="fw-bold mb-0 text-dark">Tabel Penilaian Observasi & Capaian Pembelajaran</h5>
                </div>
                <!-- PRESET SELECTOR -->
                <div class="d-flex align-items-center gap-2">
                    <label class="form-label small text-muted mb-0 text-nowrap"><i class="ph ph-sliders me-1"></i> Preset Jurusan:</label>
                    <select class="form-select form-select-sm" id="presetSelector" onchange="loadPresetJurusan(this.value)" style="border-radius: 8px; font-weight: 600; width: auto;">
                        @foreach($presets as $code => $preset)
                            <option value="{{ $code }}">{{ $preset['nama'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="alert alert-light border py-2 px-3 mb-3 d-flex align-items-center justify-content-between" style="font-size: 13px; border-radius: 8px;">
                <div>
                    <i class="ph ph-info me-1 text-primary fw-bold"></i>
                    <strong>Petunjuk:</strong> Nilai <em>Observasi I</em>, <em>Observasi II</em>, dan <em>Akhir</em> dapat diisi rentang <strong>0 - 100</strong>. Kolom <strong>Rerata</strong> akan otomatis dihitung secara realtime!
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
                        @foreach($defaultSoftskills as $idx => $item)
                        <tr class="score-row">
                            <td class="text-center text-muted">{{ $idx + 1 }}</td>
                            <td>
                                <input type="text" name="softskills[{{ $idx }}][tujuan]" class="form-control form-control-sm border-0 bg-transparent" value="{{ $item }}" readonly>
                            </td>
                            <td><input type="number" step="0.1" min="0" max="100" name="softskills[{{ $idx }}][obs1]" class="form-control form-control-sm text-center input-score obs1" oninput="calcRow(this)" placeholder="0-100"></td>
                            <td><input type="number" step="0.1" min="0" max="100" name="softskills[{{ $idx }}][obs2]" class="form-control form-control-sm text-center input-score obs2" oninput="calcRow(this)" placeholder="0-100"></td>
                            <td><input type="number" step="0.1" min="0" max="100" name="softskills[{{ $idx }}][akhir]" class="form-control form-control-sm text-center input-score akhir" oninput="calcRow(this)" placeholder="0-100"></td>
                            <td><input type="text" name="softskills[{{ $idx }}][rerata]" class="form-control form-control-sm text-center bg-light fw-bold rerata" readonly></td>
                        </tr>
                        @endforeach

                        <!-- SECTION II: Kompetensi Teknis POS (DYNAMIC BY JURUSAN) -->
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
                        @foreach($defaultKompetensi as $idx => $item)
                        <tr class="score-row">
                            <td class="text-center text-muted">{{ $idx + 1 }}</td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="kompetensi_teknis[{{ $idx }}][tujuan]" class="form-control form-control-sm" value="{{ $item }}" placeholder="Pilih/ketik capaian kompetensi teknis..." list="datalistTpKelas">
                                    <button class="btn btn-outline-danger" type="button" onclick="deleteRow(this)" title="Hapus"><i class="ph ph-trash"></i></button>
                                </div>
                            </td>
                            <td><input type="number" step="0.1" min="0" max="100" name="kompetensi_teknis[{{ $idx }}][obs1]" class="form-control form-control-sm text-center input-score obs1" oninput="calcRow(this)" placeholder="0-100"></td>
                            <td><input type="number" step="0.1" min="0" max="100" name="kompetensi_teknis[{{ $idx }}][obs2]" class="form-control form-control-sm text-center input-score obs2" oninput="calcRow(this)" placeholder="0-100"></td>
                            <td><input type="number" step="0.1" min="0" max="100" name="kompetensi_teknis[{{ $idx }}][akhir]" class="form-control form-control-sm text-center input-score akhir" oninput="calcRow(this)" placeholder="0-100"></td>
                            <td><input type="text" name="kompetensi_teknis[{{ $idx }}][rerata]" class="form-control form-control-sm text-center bg-light fw-bold rerata" readonly></td>
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
                        @foreach($defaultKompetensiBaru ?? ['', '', ''] as $i => $itemBaru)
                        <tr class="score-row">
                            <td class="text-center text-muted">{{ $i + 1 }}</td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="kompetensi_baru[{{ $i }}][tujuan]" class="form-control form-control-sm" value="{{ $itemBaru }}" placeholder="Pilih/ketik kompetensi teknis baru yang dipelajari di DUDI..." list="datalistTpKelas">
                                    <button class="btn btn-outline-danger" type="button" onclick="deleteRow(this)" title="Hapus"><i class="ph ph-trash"></i></button>
                                </div>
                            </td>
                            <td><input type="number" step="0.1" min="0" max="100" name="kompetensi_baru[{{ $i }}][obs1]" class="form-control form-control-sm text-center input-score obs1" oninput="calcRow(this)" placeholder="0-100"></td>
                            <td><input type="number" step="0.1" min="0" max="100" name="kompetensi_baru[{{ $i }}][obs2]" class="form-control form-control-sm text-center input-score obs2" oninput="calcRow(this)" placeholder="0-100"></td>
                            <td><input type="number" step="0.1" min="0" max="100" name="kompetensi_baru[{{ $i }}][akhir]" class="form-control form-control-sm text-center input-score akhir" oninput="calcRow(this)" placeholder="0-100"></td>
                            <td><input type="text" name="kompetensi_baru[{{ $i }}][rerata]" class="form-control form-control-sm text-center bg-light fw-bold rerata" readonly></td>
                        </tr>
                        @endforeach
                    </tbody>

                    <!-- SECTION IV: Analisis Usaha -->
                    <tbody>
                        <tr class="table-secondary fw-bold">
                            <td class="text-center">IV</td>
                            <td colspan="5">Melakukan analisis usaha secara mandiri</td>
                        </tr>
                        @foreach($defaultAnalisis as $idx => $item)
                        <tr class="score-row">
                            <td class="text-center text-muted">{{ $idx + 1 }}</td>
                            <td>
                                <input type="text" name="analisis_usaha[{{ $idx }}][tujuan]" class="form-control form-control-sm border-0 bg-transparent" value="{{ $item }}" readonly>
                            </td>
                            <td><input type="number" step="0.1" min="0" max="100" name="analisis_usaha[{{ $idx }}][obs1]" class="form-control form-control-sm text-center input-score obs1" oninput="calcRow(this)" placeholder="0-100"></td>
                            <td><input type="number" step="0.1" min="0" max="100" name="analisis_usaha[{{ $idx }}][obs2]" class="form-control form-control-sm text-center input-score obs2" oninput="calcRow(this)" placeholder="0-100"></td>
                            <td><input type="number" step="0.1" min="0" max="100" name="analisis_usaha[{{ $idx }}][akhir]" class="form-control form-control-sm text-center input-score akhir" oninput="calcRow(this)" placeholder="0-100"></td>
                            <td><input type="text" name="analisis_usaha[{{ $idx }}][rerata]" class="form-control form-control-sm text-center bg-light fw-bold rerata" readonly></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 4. TANGGAL PELAKSANAAN & PENGESAHAN -->
        <div class="pro-card p-4 mb-4 border-0 shadow-sm" style="border-radius: 16px;">
            <div class="d-flex align-items-center gap-2 mb-3 border-bottom pb-2">
                <span class="badge bg-primary px-2.5 py-1.5" style="font-size: 0.8rem; border-radius: 6px;">Langkah 4</span>
                <h5 class="fw-bold mb-0 text-dark">Tanggal Pelaksanaan Observasi & Catatan Tambahan</h5>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label small text-muted mb-1">Tanggal Observasi I</label>
                    <input type="date" name="tgl_observasi_1" class="form-control" value="{{ old('tgl_observasi_1', date('Y-m-d', strtotime('-1 month'))) }}" style="border-radius: 8px;">
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted mb-1">Tanggal Observasi II</label>
                    <input type="date" name="tgl_observasi_2" class="form-control" value="{{ old('tgl_observasi_2', date('Y-m-d', strtotime('-2 weeks'))) }}" style="border-radius: 8px;">
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted mb-1">Tanggal Observasi Akhir</label>
                    <input type="date" name="tgl_observasi_akhir" class="form-control" value="{{ old('tgl_observasi_akhir', date('Y-m-d')) }}" style="border-radius: 8px;">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small text-muted mb-1">Catatan Tambahan Observasi (Opsional)</label>
                <textarea name="catatan_umum" class="form-control" rows="2" placeholder="Tuliskan catatan khusus atau rekomendasi untuk siswa..." style="border-radius: 8px;">{{ old('catatan_umum') }}</textarea>
            </div>
        </div>

        <!-- ACTION BUTTONS -->
        <div class="d-flex flex-wrap justify-content-end align-items-center gap-2 p-3 bg-white border shadow-sm sticky-bottom" style="border-radius: 12px; z-index: 80;">
            <a href="{{ route('guru.observasi.index') }}" class="btn btn-light px-3 py-2" style="border-radius: 8px;">
                Batal
            </a>
            <button type="submit" name="action" value="simpan" class="btn btn-secondary px-4 py-2 d-inline-flex align-items-center gap-1.5" style="border-radius: 8px; font-weight: 600;">
                <i class="ph ph-floppy-disk" style="font-size: 18px;"></i>
                Simpan Draf Observasi
            </button>
            <button type="submit" name="action" value="simpan_cetak" class="btn btn-primary px-4 py-2 d-inline-flex align-items-center gap-1.5" style="border-radius: 8px; font-weight: 600; box-shadow: 0 4px 14px rgba(2, 132, 199, 0.3);">
                <i class="ph ph-printer" style="font-size: 18px;"></i>
                Simpan & Langsung Cetak PDF
            </button>
        </div>

        <!-- DATALIST PILIHAN TUJUAN PEMBELAJARAN (KURIKULUM PKL 2026) -->
        <datalist id="datalistTpKelas">
            @if(isset($allTpGrouped))
                @foreach($allTpGrouped as $kodeJ => $byCp)
                    @foreach($byCp as $cpName => $tps)
                        @foreach($tps as $tp)
                            <option value="{{ $tp->tujuan_pembelajaran }}">[{{ $kodeJ }} - TP {{ $tp->nomor_urut }}] {{ $tp->tujuan_pembelajaran }}</option>
                        @endforeach
                    @endforeach
                @endforeach
            @endif
        </datalist>
    </form>
</div>

<!-- JS FOR AUTO-FILL, DYNAMIC PRESETS & REALTIME CALCULATION -->
<script>
    const presetsData = @json($presets);

    function onPenempatanChange(select) {
        const opt = select.options[select.selectedIndex];
        if (!opt || !opt.value) return;

        document.getElementById('nama_murid').value = opt.getAttribute('data-murid') || '';
        document.getElementById('konsentrasi_keahlian').value = opt.getAttribute('data-jurusan') || '';
        document.getElementById('nama_institusi').value = opt.getAttribute('data-institusi') || '';
        document.getElementById('alamat_institusi').value = opt.getAttribute('data-alamat') || '';
        document.getElementById('telp_institusi').value = opt.getAttribute('data-telp') || '';
        document.getElementById('nama_pimpinan').value = opt.getAttribute('data-pimpinan') || '';
        document.getElementById('nama_instruktur').value = opt.getAttribute('data-instruktur') || '';
        document.getElementById('jabatan_instruktur').value = opt.getAttribute('data-jabataninstruktur') || 'Pembimbing Lapangan / Instruktur';

        const kodeJurusan = opt.getAttribute('data-kodejurusan') || '';
        const namaJurusan = opt.getAttribute('data-jurusan') || '';

        // Match preset (RPL, TKJ, AK, MP, BR)
        let matchCode = 'RPL';
        const str = (kodeJurusan + ' ' + namaJurusan).toUpperCase();
        if (str.includes('TKJ') || str.includes('JARINGAN') || str.includes('NETWORK')) {
            matchCode = 'TKJ';
        } else if (str.includes('AK') || str.includes('AKL') || str.includes('AKUNTANSI') || str.includes('KEUANGAN')) {
            matchCode = 'AK';
        } else if (str.includes('MP') || str.includes('MPLB') || str.includes('PERKANTORAN') || str.includes('OTKP') || str.includes('SEKRETARIS')) {
            matchCode = 'MP';
        } else if (str.includes('BR') || str.includes('RITEL') || str.includes('RETAIL') || str.includes('PEMASARAN') || str.includes('BISNIS') || str.includes('BD')) {
            matchCode = 'BR';
        } else if (str.includes('RPL') || str.includes('PERANGKAT LUNAK') || str.includes('SOFTWARE') || str.includes('PROGRAM')) {
            matchCode = 'RPL';
        }

        const presetSelect = document.getElementById('presetSelector');
        if (presetSelect) {
            presetSelect.value = matchCode;
            loadPresetJurusan(matchCode);
        }
    }

    function loadPresetJurusan(code) {
        const preset = presetsData[code];
        if (!preset) return;

        const tbody = document.getElementById('section2Body');
        tbody.innerHTML = '';

        preset.kompetensi.forEach((item, idx) => {
            const tr = document.createElement('tr');
            tr.className = 'score-row';
            tr.innerHTML = `
                <td class="text-center text-muted">${idx + 1}</td>
                <td>
                    <div class="input-group input-group-sm">
                        <input type="text" name="kompetensi_teknis[${idx}][tujuan]" class="form-control form-control-sm" value="${item}" placeholder="Pilih/ketik capaian kompetensi..." list="datalistTpKelas">
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
                    <input type="text" name="${prefixName}[${idx}][tujuan]" class="form-control form-control-sm" placeholder="Pilih/ketik butir capaian kompetensi..." list="datalistTpKelas">
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
