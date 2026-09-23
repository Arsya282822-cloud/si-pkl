@extends('layouts.app')
@section('title', 'Cetak Surat & Dokumen PKL')
@section('topbar_title', 'Cetak Surat & Dokumen PKL')

@section('content')
<div class="container-fluid px-0">

    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--text-main);">Pusat Cetak Dokumen & Sertifikat PKL</h4>
            <p class="text-muted mb-0" style="font-size: 0.875rem;">Cetak surat permohonan, surat tugas, surat penarikan, hingga e-sertifikat kelulusan ber-kop resmi.</p>
        </div>
    </div>

    <!-- Main Navigation Tabs -->
    <div class="pro-card p-3 mb-4" style="border-radius: 16px;">
        <div class="d-flex flex-wrap gap-2">
            <a class="btn d-inline-flex align-items-center gap-1.5 px-3 py-2 {{ $tab === 'pengantar' ? 'btn-primary' : 'btn-outline-secondary border-0 bg-light' }}" 
               href="{{ route('admin.dokumen.index', ['tab' => 'pengantar']) }}" style="border-radius: 10px; font-weight: 600; font-size: 0.875rem;">
                <i class="ph ph-envelope-simple" style="font-size: 18px;"></i>
                1. Surat Pengantar PKL
            </a>
            <a class="btn d-inline-flex align-items-center gap-1.5 px-3 py-2 {{ $tab === 'tugas' ? 'btn-primary' : 'btn-outline-secondary border-0 bg-light' }}" 
               href="{{ route('admin.dokumen.index', ['tab' => 'tugas']) }}" style="border-radius: 10px; font-weight: 600; font-size: 0.875rem;">
                <i class="ph ph-identification-badge" style="font-size: 18px;"></i>
                2. Surat Tugas Pembimbing
            </a>
            <a class="btn d-inline-flex align-items-center gap-1.5 px-3 py-2 {{ $tab === 'penarikan' ? 'btn-primary' : 'btn-outline-secondary border-0 bg-light' }}" 
               href="{{ route('admin.dokumen.index', ['tab' => 'penarikan']) }}" style="border-radius: 10px; font-weight: 600; font-size: 0.875rem;">
                <i class="ph ph-arrow-u-down-left" style="font-size: 18px;"></i>
                3. Surat Penarikan Siswa
            </a>
            <a class="btn d-inline-flex align-items-center gap-1.5 px-3 py-2 {{ $tab === 'sertifikat' ? 'btn-primary' : 'btn-outline-secondary border-0 bg-light' }}" 
               href="{{ route('admin.dokumen.index', ['tab' => 'sertifikat']) }}" style="border-radius: 10px; font-weight: 600; font-size: 0.875rem;">
                <i class="ph ph-certificate" style="font-size: 18px;"></i>
                4. E-Sertifikat & Piagam DUDI
            </a>
            <a class="btn d-inline-flex align-items-center gap-1.5 px-3 py-2 {{ $tab === 'jurnal' ? 'btn-primary' : 'btn-outline-secondary border-0 bg-light' }}" 
               href="{{ route('admin.dokumen.index', ['tab' => 'jurnal']) }}" style="border-radius: 10px; font-weight: 600; font-size: 0.875rem;">
                <i class="ph ph-book-open" style="font-size: 18px;"></i>
                5. Buku Jurnal (Siap Jilid)
            </a>
        </div>
    </div>


    <!-- Content Cards based on Tab -->
    <div class="pro-card p-4" style="border-radius: 16px;">
        
        @if($tab === 'pengantar')
            <!-- ================= TAB 1: SURAT PENGANTAR ================= -->
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <h5 class="fw-bold mb-0">Daftar Surat Pengantar / Permohonan PKL</h5>
                <button type="button" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1.5" data-bs-toggle="modal" data-bs-target="#modalBatchPengantar" style="border-radius: 8px; font-weight: 600;">
                    <i class="ph ph-files" style="font-size: 16px;"></i> Cetak Surat Pengantar Kolektif (Per DUDI)
                </button>
            </div>
            
            <form class="row g-2 mb-4" method="GET">
                <input type="hidden" name="tab" value="pengantar">
                <div class="col-md-5">
                    <input name="q" value="{{ $q }}" class="form-control" placeholder="Cari nama siswa atau perusahaan..." style="border-radius: 8px;">
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary" style="border-radius: 8px;"><i class="ph ph-magnifying-glass me-1"></i> Cari</button>
                </div>
                @if($q)
                    <div class="col-auto">
                        <a href="{{ route('admin.dokumen.index', ['tab' => 'pengantar']) }}" class="btn btn-outline-secondary" style="border-radius: 8px;">Reset</a>
                    </div>
                @endif
            </form>


            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40px;">No</th>
                            <th>Nama Siswa</th>
                            <th>Kelas / Jurusan</th>
                            <th>Perusahaan Tujuan</th>
                            <th>Periode PKL</th>
                            <th class="text-end">Aksi Cetak</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penempatanList as $i => $item)
                            <tr>
                                <td>{{ $penempatanList->firstItem() + $i }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $item->siswa?->nama ?? '-' }}</div>
                                    <small class="text-muted">NIS: {{ $item->siswa?->nis ?? '-' }}</small>
                                </td>
                                <td>
                                    <div>{{ $item->siswa?->kelas?->nama_kelas ?? '-' }}</div>
                                    <small class="text-muted">{{ $item->siswa?->jurusan?->nama_jurusan ?? '-' }}</small>
                                </td>
                                <td class="fw-semibold text-primary">{{ $item->perusahaan?->nama_perusahaan ?? '-' }}</td>
                                <td>{{ $item->periodePkl?->nama_periode ?? '-' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.dokumen.surat_pengantar', $item) }}" target="_blank" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1" style="border-radius: 8px;">
                                        <i class="ph ph-printer"></i> Cetak Surat Pengantar
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">Belum ada data penempatan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $penempatanList->appends(['tab' => 'pengantar', 'q' => $q])->links() }}
            </div>

        @elseif($tab === 'tugas')
            <!-- ================= TAB 2: SURAT TUGAS GURU ================= -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Daftar Surat Perintah Tugas Guru Pembimbing</h5>
            </div>

            <form class="row g-2 mb-4" method="GET">
                <input type="hidden" name="tab" value="tugas">
                <div class="col-md-5">
                    <input name="q" value="{{ $q }}" class="form-control" placeholder="Cari nama guru atau NIP..." style="border-radius: 8px;">
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary" style="border-radius: 8px;"><i class="ph ph-magnifying-glass me-1"></i> Cari</button>
                </div>
                @if($q)
                    <div class="col-auto">
                        <a href="{{ route('admin.dokumen.index', ['tab' => 'tugas']) }}" class="btn btn-outline-secondary" style="border-radius: 8px;">Reset</a>
                    </div>
                @endif
            </form>

            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40px;">No</th>
                            <th>Nama Guru Pembimbing</th>
                            <th>NIP / Identitas</th>
                            <th>Jumlah Bimbingan</th>
                            <th class="text-end">Aksi Cetak</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($guruList as $i => $guru)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $guru->nama }}</div>
                                    <small class="text-muted">{{ $guru->user?->email }}</small>
                                </td>
                                <td>{{ $guru->nip ?: '-' }}</td>
                                <td>
                                    <span class="badge bg-primary px-2.5 py-1" style="border-radius: 8px;">{{ $guru->penempatan_count }} Siswa Bimbingan</span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.dokumen.surat_tugas', $guru) }}" target="_blank" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1" style="border-radius: 8px;">
                                        <i class="ph ph-printer"></i> Cetak Surat Tugas
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">Belum ada data guru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        @elseif($tab === 'penarikan')
            <!-- ================= TAB 3: SURAT PENARIKAN SISWA ================= -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Daftar Surat Penarikan Siswa PKL (Akhir Pelaksanaan)</h5>
            </div>

            <form class="row g-2 mb-4" method="GET">
                <input type="hidden" name="tab" value="penarikan">
                <div class="col-md-5">
                    <input name="q" value="{{ $q }}" class="form-control" placeholder="Cari nama siswa atau perusahaan..." style="border-radius: 8px;">
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary" style="border-radius: 8px;"><i class="ph ph-magnifying-glass me-1"></i> Cari</button>
                </div>
                @if($q)
                    <div class="col-auto">
                        <a href="{{ route('admin.dokumen.index', ['tab' => 'penarikan']) }}" class="btn btn-outline-secondary" style="border-radius: 8px;">Reset</a>
                    </div>
                @endif
            </form>

            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40px;">No</th>
                            <th>Nama Siswa</th>
                            <th>Kelas / Jurusan</th>
                            <th>Tempat PKL (DUDI)</th>
                            <th>Periode PKL</th>
                            <th class="text-end">Aksi Cetak</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penempatanList as $i => $item)
                            <tr>
                                <td>{{ $penempatanList->firstItem() + $i }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $item->siswa?->nama ?? '-' }}</div>
                                    <small class="text-muted">NIS: {{ $item->siswa?->nis ?? '-' }}</small>
                                </td>
                                <td>
                                    <div>{{ $item->siswa?->kelas?->nama_kelas ?? '-' }}</div>
                                    <small class="text-muted">{{ $item->siswa?->jurusan?->nama_jurusan ?? '-' }}</small>
                                </td>
                                <td class="fw-semibold text-primary">{{ $item->perusahaan?->nama_perusahaan ?? '-' }}</td>
                                <td>{{ $item->periodePkl?->nama_periode ?? '-' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.dokumen.surat_penarikan', $item) }}" target="_blank" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1" style="border-radius: 8px;">
                                        <i class="ph ph-printer"></i> Cetak Surat Penarikan
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">Belum ada data penempatan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $penempatanList->appends(['tab' => 'penarikan', 'q' => $q])->links() }}
            </div>

        @elseif($tab === 'sertifikat')
            <!-- ================= TAB 4: SERTIFIKAT PKL & PIAGAM ================= -->
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div>
                    <h5 class="fw-bold mb-0">E-Sertifikat Kelulusan PKL & Piagam Mitra Industri</h5>
                    <p class="text-muted mb-0" style="font-size: 0.825rem;">Format cetak landscape berbingkai resmi dengan QR Code verifikasi & transkrip nilai dua sisi.</p>
                </div>
                <button type="button" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1.5" data-bs-toggle="modal" data-bs-target="#modalBatchSertifikat" style="border-radius: 8px; font-weight: 600;">
                    <i class="ph ph-printer" style="font-size: 16px;"></i> Cetak Masal E-Sertifikat (1 Rombel)
                </button>
            </div>

            <form class="row g-2 mb-4" method="GET">
                <input type="hidden" name="tab" value="sertifikat">
                <div class="col-md-5">
                    <input name="q" value="{{ $q }}" class="form-control" placeholder="Cari nama siswa atau perusahaan..." style="border-radius: 8px;">
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary" style="border-radius: 8px;"><i class="ph ph-magnifying-glass me-1"></i> Cari</button>
                </div>
                @if($q)
                    <div class="col-auto">
                        <a href="{{ route('admin.dokumen.index', ['tab' => 'sertifikat']) }}" class="btn btn-outline-secondary" style="border-radius: 8px;">Reset</a>
                    </div>
                @endif
            </form>

            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40px;">No</th>
                            <th>Nama Siswa & NIS</th>
                            <th>Konsentrasi Keahlian</th>
                            <th>Tempat PKL (DUDI)</th>
                            <th class="text-end">Cetak Dokumen Resmi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penempatanList as $i => $item)
                            <tr>
                                <td>{{ $penempatanList->firstItem() + $i }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $item->siswa?->nama ?? '-' }}</div>
                                    <small class="text-muted">NIS: {{ $item->siswa?->nis ?? '-' }} / NISN: {{ $item->siswa?->nisn ?: '-' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $item->siswa?->jurusan?->nama_jurusan ?? '-' }}</span>
                                </td>
                                <td class="fw-semibold text-primary">{{ $item->perusahaan?->nama_perusahaan ?? '-' }}</td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-2">
                                        <a href="{{ route('admin.dokumen.sertifikat', $item) }}" target="_blank" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1" style="border-radius: 8px; font-weight: 600;">
                                            <i class="ph ph-certificate"></i> Cetak E-Sertifikat Siswa
                                        </a>
                                        <a href="{{ route('admin.dokumen.piagam_dudi', $item) }}" target="_blank" class="btn btn-sm btn-outline-info d-inline-flex align-items-center gap-1" style="border-radius: 8px;">
                                            <i class="ph ph-medal"></i> Piagam DUDI
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">Belum ada data siswa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $penempatanList->appends(['tab' => 'sertifikat', 'q' => $q])->links() }}
            </div>

        @elseif($tab === 'jurnal')
            <!-- ================= TAB 5: BUKU JURNAL SIAP JILID ================= -->
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div>
                    <h5 class="fw-bold mb-0">Buku Jurnal & Logbook Aktivitas PKL (Siap Jilid)</h5>
                    <p class="text-muted mb-0" style="font-size: 0.825rem;">Laporan lengkap portofolio harian siswa, rekapitulasi kehadiran, dan lembar pengesahan pembimbing.</p>
                </div>
            </div>

            <form class="row g-2 mb-4" method="GET">
                <input type="hidden" name="tab" value="jurnal">
                <div class="col-md-5">
                    <input name="q" value="{{ $q }}" class="form-control" placeholder="Cari nama siswa atau tempat PKL..." style="border-radius: 8px;">
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary" style="border-radius: 8px;"><i class="ph ph-magnifying-glass me-1"></i> Cari</button>
                </div>
                @if($q)
                    <div class="col-auto">
                        <a href="{{ route('admin.dokumen.index', ['tab' => 'jurnal']) }}" class="btn btn-outline-secondary" style="border-radius: 8px;">Reset</a>
                    </div>
                @endif
            </form>

            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40px;">No</th>
                            <th>Nama Siswa & NIS</th>
                            <th>Kelas / Rombel</th>
                            <th>Tempat PKL (DUDI)</th>
                            <th>Guru Pembimbing</th>
                            <th class="text-end">Cetak Berkas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penempatanList as $i => $item)
                            <tr>
                                <td>{{ $penempatanList->firstItem() + $i }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $item->siswa?->nama ?? '-' }}</div>
                                    <small class="text-muted">NIS: {{ $item->siswa?->nis ?? '-' }}</small>
                                </td>
                                <td>
                                    <div>{{ $item->siswa?->kelas?->nama_kelas ?? '-' }}</div>
                                    <small class="text-muted">{{ $item->siswa?->jurusan?->nama_jurusan ?? '-' }}</small>
                                </td>
                                <td class="fw-semibold text-primary">{{ $item->perusahaan?->nama_perusahaan ?? '-' }}</td>
                                <td>{{ $item->guru?->nama ?? '-' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.cetak.jurnal', $item) }}" target="_blank" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1" style="border-radius: 8px; font-weight: 600;">
                                        <i class="ph ph-book-open"></i> Cetak Jurnal Siap Jilid
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">Belum ada data siswa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $penempatanList->appends(['tab' => 'jurnal', 'q' => $q])->links() }}
            </div>
        @endif


    </div>

</div>

<!-- Modal Batch Cetak Surat Pengantar -->
<div class="modal fade" id="modalBatchPengantar" tabindex="-1" aria-labelledby="modalBatchPengantarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 16px;">
            <form action="{{ route('admin.dokumen.batch_surat_pengantar') }}" method="GET" target="_blank">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="modalBatchPengantarLabel">Cetak Pengantar Kolektif</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted" style="font-size: 0.85rem;">Pilih Perusahaan Mitra atau Rombel untuk mencetak 1 Surat Pengantar resmi berisi daftar seluruh siswa yang ditempatkan.</p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Mitra Industri (DUDI)</label>
                        <select name="perusahaan_id" class="form-select" style="border-radius: 8px;">
                            <option value="">-- Semua Perusahaan Mitra --</option>
                            @foreach($perusahaanList as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_perusahaan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Kelas / Rombel</label>
                        <select name="kelas_id" class="form-select" style="border-radius: 8px;">
                            <option value="">-- Semua Kelas --</option>
                            @foreach($kelasList as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Periode PKL</label>
                        <select name="periode_id" class="form-select" style="border-radius: 8px;">
                            @foreach($periodeList as $pr)
                                <option value="{{ $pr->id }}" {{ $pr->status === 'aktif' ? 'selected' : '' }}>{{ $pr->nama_periode }} ({{ $pr->tahun_ajaran }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1">
                        <i class="ph ph-printer"></i> Buka Lembar Cetak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Batch Cetak Sertifikat -->
<div class="modal fade" id="modalBatchSertifikat" tabindex="-1" aria-labelledby="modalBatchSertifikatLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 16px;">
            <form action="{{ route('admin.dokumen.batch_sertifikat') }}" method="GET" target="_blank">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="modalBatchSertifikatLabel">Cetak Masal E-Sertifikat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted" style="font-size: 0.85rem;">Cetak seluruh e-sertifikat 2 sisi (Depan + Transkrip Belakang) untuk satu rombel/jurusan sekaligus dalam satu dokumen siap cetak/PDF.</p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Kelas / Rombel</label>
                        <select name="kelas_id" class="form-select" style="border-radius: 8px;">
                            <option value="">-- Semua Kelas --</option>
                            @foreach($kelasList as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Jurusan / Program Keahlian</label>
                        <select name="jurusan_id" class="form-select" style="border-radius: 8px;">
                            <option value="">-- Semua Jurusan --</option>
                            @foreach($jurusanList as $j)
                                <option value="{{ $j->id }}">{{ $j->nama_jurusan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Periode PKL</label>
                        <select name="periode_id" class="form-select" style="border-radius: 8px;">
                            @foreach($periodeList as $pr)
                                <option value="{{ $pr->id }}" {{ $pr->status === 'aktif' ? 'selected' : '' }}>{{ $pr->nama_periode }} ({{ $pr->tahun_ajaran }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1">
                        <i class="ph ph-printer"></i> Generate Sertifikat Rombel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

