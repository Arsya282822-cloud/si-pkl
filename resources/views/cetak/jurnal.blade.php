<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Jurnal Logbook PKL - {{ $penempatan->siswa?->nama }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        body {
            font-family: Calibri, 'Segoe UI', Candara, Arial, sans-serif;
            color: #000;
            background: #f1f5f9;
            padding: 20px;
            margin: 0;
        }
        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 1.5cm 2cm 1.5cm 2cm;
            margin: 0 auto 20px auto;
            background: #fff;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            position: relative;
            box-sizing: border-box;
        }
        .action-bar {
            width: 210mm;
            margin: 0 auto 14px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .table-custom {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-top: 10px;
        }
        .table-custom th, .table-custom td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: middle;
        }
        .table-custom th {
            background-color: #f8fafc;
            font-weight: bold;
            text-align: center;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            border-bottom: 2px solid #000;
            padding-bottom: 4px;
            margin-top: 20px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        @media print {
            @page {
                size: A4 portrait;
                margin: 1.2cm;
            }
            body {
                background: #fff;
                padding: 0;
                margin: 0;
            }
            .page {
                box-shadow: none;
                padding: 0;
                margin: 0;
                width: 100%;
                min-height: auto;
            }
            .action-bar {
                display: none !important;
            }
            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>
<body>

<div class="action-bar">
    <button onclick="window.close()" class="btn btn-secondary">
        <i class="ph ph-arrow-left me-1"></i> Tutup
    </button>
    
    <div class="d-flex align-items-center gap-2">
        <button onclick="window.print()" class="btn btn-primary px-4 py-2" style="font-weight: 600;">
            <i class="ph ph-printer me-1"></i> Cetak / Simpan PDF
        </button>
    </div>
</div>

<!-- ======================================================= -->
<!-- LEMBAR 1: KOP & BUKU JURNAL LOGBOOK PKL                -->
<!-- ======================================================= -->
<div class="page">
    <!-- Kop Surat Resmi -->
    @include('admin.dokumen._kop')

    <!-- Judul Dokumen -->
    <div class="text-center my-3">
        <h4 class="fw-bold mb-1" style="letter-spacing: 0.5px; text-decoration: underline;">
            BUKU JURNAL & LOGBOOK KEGIATAN PKL
        </h4>
        <div style="font-size: 13px; color: #334155;">
            TAHUN AJARAN {{ $penempatan->periodePkl?->tahun_ajaran ?? date('Y').'/'.(date('Y')+1) }}
        </div>
    </div>

    <!-- Identitas Siswa & Tempat PKL -->
    <div class="section-title">I. IDENTITAS PESERTA DIDIK & DUDI</div>
    <table style="width: 100%; font-size: 13px; line-height: 1.6; margin-bottom: 15px;">
        <tr>
            <td style="width: 180px;">Nama Peserta Didik</td>
            <td style="width: 15px;">:</td>
            <td class="fw-bold">{{ $penempatan->siswa?->nama }}</td>
        </tr>
        <tr>
            <td>NIS / NISN</td>
            <td>:</td>
            <td>{{ $penempatan->siswa?->nis }} / {{ $penempatan->siswa?->nisn ?: '-' }}</td>
        </tr>
        <tr>
            <td>Kelas / Konsentrasi</td>
            <td>:</td>
            <td>{{ $penempatan->siswa?->kelas?->nama_kelas }} — {{ $penempatan->siswa?->jurusan?->nama_jurusan }}</td>
        </tr>
        <tr>
            <td>Tempat PKL (DUDI)</td>
            <td>:</td>
            <td class="fw-bold">{{ $penempatan->perusahaan?->nama_perusahaan }}</td>
        </tr>
        <tr>
            <td>Alamat Perusahaan</td>
            <td>:</td>
            <td>{{ $penempatan->perusahaan?->alamat ?: 'Pekanbaru, Riau' }}</td>
        </tr>
        <tr>
            <td>Pembimbing Industri</td>
            <td>:</td>
            <td>{{ $penempatan->perusahaan?->pembimbing_industri ?: 'Pembimbing Industri DUDI' }}</td>
        </tr>
        <tr>
            <td>Guru Pembimbing</td>
            <td>:</td>
            <td>{{ $penempatan->guru?->nama }} (NIP: {{ $penempatan->guru?->nip ?: '-' }})</td>
        </tr>
        <tr>
            <td>Waktu Pelaksanaan</td>
            <td>:</td>
            <td>{{ $penempatan->periodePkl?->tanggal_mulai ? $penempatan->periodePkl->tanggal_mulai->translatedFormat('d F Y') : '-' }} s/d {{ $penempatan->periodePkl?->tanggal_selesai ? $penempatan->periodePkl->tanggal_selesai->translatedFormat('d F Y') : '-' }}</td>
        </tr>
    </table>

    <!-- Ringkasan Kehadiran -->
    <div class="section-title">II. REKAPITULASI PRESENSI & KEHADIRAN</div>
    @php
        $totalHadir = isset($absensi) ? $absensi->where('status', 'hadir')->count() : 0;
        $totalIzin = isset($absensi) ? $absensi->where('status', 'izin')->count() : 0;
        $totalSakit = isset($absensi) ? $absensi->where('status', 'sakit')->count() : 0;
    @endphp
    <div class="row g-2 mb-3 text-center" style="font-size: 13px;">
        <div class="col-4">
            <div class="p-2 border rounded bg-light">
                Hadir: <strong>{{ $totalHadir }} Hari</strong>
            </div>
        </div>
        <div class="col-4">
            <div class="p-2 border rounded bg-light">
                Izin: <strong>{{ $totalIzin }} Hari</strong>
            </div>
        </div>
        <div class="col-4">
            <div class="p-2 border rounded bg-light">
                Sakit: <strong>{{ $totalSakit }} Hari</strong>
            </div>
        </div>
    </div>

    <!-- Catatan Jurnal Harian -->
    <div class="section-title">III. CATATAN AKTIVITAS & JURNAL HARIAN</div>
    <table class="table-custom mb-4">
        <thead>
            <tr>
                <th style="width: 35px;">No</th>
                <th style="width: 110px;">Hari, Tanggal</th>
                <th>Uraian Aktivitas / Kegiatan Praktik Kerja</th>
                <th style="width: 100px;">Paraf/Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jurnal as $i => $j)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($j->tanggal)->translatedFormat('d M Y') }}</td>
                    <td style="text-align: justify;">{{ $j->kegiatan }}</td>
                    <td class="text-center">
                        @if($j->status_validasi === 'disetujui' || $j->status === 'disetujui')
                            <span class="text-success fw-bold">✓ ACC Guru</span>
                        @else
                            <span class="text-muted">Tervalidasi</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted">Belum ada data jurnal harian yang dimasukkan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pengesahan -->
    <div class="section-title">IV. LEMBAR PENGESAHAN LAPORAN PKL</div>
    <p style="font-size: 12.5px; text-align: justify; margin-bottom: 20px;">
        Demikian buku jurnal dan laporan kegiatan Praktik Kerja Lapangan (PKL) ini disusun dengan sebenar-benarnya berdasarkan aktivitas kerja yang dilaksanakan di <strong>{{ $penempatan->perusahaan?->nama_perusahaan }}</strong>.
    </p>

    <table style="width: 100%; border-collapse: collapse; font-size: 13px; text-align: center; margin-top: 20px;">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <p class="mb-1">Mengetahui,</p>
                <p class="fw-bold mb-0">Pembimbing Industri (DUDI)</p>
                <div style="height: 65px;"></div>
                <p class="fw-bold text-decoration-underline mb-0">
                    {{ $penempatan->perusahaan?->pembimbing_industri ?: 'Pimpinan / Pembimbing DUDI' }}
                </p>
            </td>
            <td style="width: 50%; vertical-align: top;">
                <p class="mb-1">Pekanbaru, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                <p class="fw-bold mb-0">Guru Pembimbing Sekolah</p>
                <div style="height: 65px;"></div>
                <p class="fw-bold text-decoration-underline mb-0">
                    {{ $penempatan->guru?->nama }}
                </p>
                <small class="text-muted">NIP. {{ $penempatan->guru?->nip ?: '-' }}</small>
            </td>
        </tr>
    </table>
</div>

</body>
</html>
