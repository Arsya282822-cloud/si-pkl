<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lembar Observasi - {{ $observasi->nama_murid }} ({{ $observasi->nama_institusi }})</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        body {
            font-family: Arial, Calibri, 'Segoe UI', sans-serif;
            color: #000;
            background: #f1f5f9;
            padding: 20px;
            margin: 0;
            font-size: 13px;
        }
        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 12mm 18mm 12mm 18mm;
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
        .table-obs {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-top: 10px;
        }
        .table-obs th, .table-obs td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: middle;
        }
        .table-obs th {
            text-align: center;
            font-weight: bold;
            background-color: #f8fafc;
        }

        .dotted-line {
            display: inline-block;
            width: calc(100% - 15px);
            border-bottom: 1px solid #000;
            min-height: 16px;
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: 10mm 15mm 10mm 15mm;
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
                box-sizing: border-box;
            }
            .action-bar, .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

@php
    $routePrefix = Auth::user()?->role?->nama_role === 'admin' ? 'admin' : 'guru';
@endphp

<div class="action-bar no-print">
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route($routePrefix . '.observasi.index') }}" class="btn btn-secondary btn-sm">
            <i class="ph ph-arrow-left me-1"></i> Kembali ke Daftar
        </a>
        <a href="{{ route($routePrefix . '.observasi.edit', $observasi->id) }}" class="btn btn-outline-primary btn-sm">
            <i class="ph ph-pencil-simple me-1"></i> Edit Data
        </a>
    </div>
    
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route($routePrefix . '.observasi.excel', $observasi->id) }}" class="btn btn-success btn-sm fw-semibold">
            <i class="ph ph-file-xls me-1"></i> Download Excel (.xlsx)
        </a>
        <button onclick="window.print()" class="btn btn-primary btn-sm fw-semibold">
            <i class="ph ph-printer me-1"></i> Cetak / Simpan PDF
        </button>
    </div>
</div>

<div class="page">
    <!-- Judul Dokumen -->
    <div class="text-center mb-3">
        <h5 class="fw-bold mb-0" style="letter-spacing: 0.5px; font-size: 13.5px;">LEMBAR OBSERVASI</h5>
        <h5 class="fw-bold mb-0" style="letter-spacing: 0.5px; font-size: 13.5px;">PRAKTIK KERJA LAPANGAN</h5>
    </div>

    <!-- I. DATA INSTITUSI -->
    <div style="line-height: 1.5; margin-bottom: 6px; font-size: 11.5px;">
        <div class="fw-bold">I. DATA INSTITUSI</div>
        <table style="width: 100%; font-size: 11.5px; margin-left: 10px;">
            <tr>
                <td style="width: 210px;">- NAMA LENGKAP INSTITUSI</td>
                <td style="width: 15px;">:</td>
                <td><span class="dotted-line fw-semibold">{{ $observasi->nama_institusi }}</span></td>
            </tr>
            <tr>
                <td>- ALAMAT</td>
                <td>:</td>
                <td><span class="dotted-line">{{ $observasi->alamat_institusi ?: '-' }}</span></td>
            </tr>
            <tr>
                <td>- TELP/FAX</td>
                <td>:</td>
                <td><span class="dotted-line">{{ $observasi->telp_institusi ?: '-' }}</span></td>
            </tr>
        </table>
    </div>

    <!-- II. DATA PIMPINAN -->
    <div style="line-height: 1.5; margin-bottom: 6px; font-size: 11.5px;">
        <div class="fw-bold">II. DATA PIMPINAN</div>
        <table style="width: 100%; font-size: 11.5px; margin-left: 10px;">
            <tr>
                <td style="width: 210px;">- NAMA LENGKAP PIMPINAN</td>
                <td style="width: 15px;">:</td>
                <td><span class="dotted-line fw-semibold">{{ $observasi->nama_pimpinan ?: '-' }}</span></td>
            </tr>
            <tr>
                <td>- NIP/NO. REGISTRASI</td>
                <td>:</td>
                <td><span class="dotted-line">{{ $observasi->nip_pimpinan ?: '-' }}</span></td>
            </tr>
            <tr>
                <td>- JABATAN</td>
                <td>:</td>
                <td><span class="dotted-line">{{ $observasi->jabatan_pimpinan ?: 'Pimpinan' }}</span></td>
            </tr>
        </table>
    </div>

    <!-- III. DATA INSTRUKTUR -->
    <div style="line-height: 1.5; margin-bottom: 6px; font-size: 11.5px;">
        <div class="fw-bold">III. DATA INSTRUKTUR</div>
        <table style="width: 100%; font-size: 11.5px; margin-left: 10px;">
            <tr>
                <td style="width: 210px;">- NAMA LENGKAP INSTRUKTUR</td>
                <td style="width: 15px;">:</td>
                <td><span class="dotted-line fw-semibold">{{ $observasi->nama_instruktur ?: '-' }}</span></td>
            </tr>
            <tr>
                <td>- NIP/NO. REGISTRASI</td>
                <td>:</td>
                <td><span class="dotted-line">{{ $observasi->nip_instruktur ?: '-' }}</span></td>
            </tr>
            <tr>
                <td>- JABATAN</td>
                <td>:</td>
                <td><span class="dotted-line">{{ $observasi->jabatan_instruktur ?: 'Pembimbing Lapangan' }}</span></td>
            </tr>
        </table>
    </div>

    <!-- IV. MURID -->
    <div style="line-height: 1.5; margin-bottom: 8px; font-size: 11.5px;">
        <div class="fw-bold">IV. MURID</div>
        <table style="width: 100%; font-size: 11.5px; margin-left: 10px;">
            <tr>
                <td style="width: 210px;">- NAMA LENGKAP MURID</td>
                <td style="width: 15px;">:</td>
                <td><span class="dotted-line fw-semibold">{{ $observasi->nama_murid }}</span></td>
            </tr>
            <tr>
                <td>- KONSENTRASI KEAHLIAN</td>
                <td>:</td>
                <td><span class="dotted-line fw-semibold">{{ $observasi->konsentrasi_keahlian }}</span></td>
            </tr>
        </table>
    </div>

    <!-- TABEL PENILAIAN OBSERVASI -->
    <table class="table-obs">
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th>Capaian dan Tujuan Pembelajaran</th>
                <th style="width: 75px;">Observasi I</th>
                <th style="width: 75px;">Observasi II</th>
                <th style="width: 65px;">Akhir</th>
                <th style="width: 65px;">Rerata</th>
            </tr>
        </thead>
        <tbody>
            <!-- SECTION I: Softskills -->
            <tr>
                <td class="text-center fw-bold">I</td>
                <td class="fw-bold">Menerapkan soft skills yang dibutuhkan dalam dunia kerja (tempat PKL)</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @php
                $softskills = $observasi->data_softskills ?: [];
            @endphp
            @foreach($softskills as $item)
            <tr>
                <td></td>
                <td>{{ $item['tujuan'] }}</td>
                <td class="text-center fw-semibold">{{ $item['obs1'] ?? '' }}</td>
                <td class="text-center fw-semibold">{{ $item['obs2'] ?? '' }}</td>
                <td class="text-center fw-semibold">{{ $item['akhir'] ?? '' }}</td>
                <td class="text-center fw-bold bg-light">{{ $item['rerata'] ?? '' }}</td>
            </tr>
            @endforeach

            <!-- SECTION II: Kompetensi Teknis POS -->
            <tr>
                <td class="text-center fw-bold">II</td>
                <td class="fw-bold">Menerapkan kompetensi teknis pada pekerjaan sesuai POS yang berlaku di dunia kerja</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @php
                $kompetensiTeknis = $observasi->data_kompetensi_teknis ?: [];
            @endphp
            @foreach($kompetensiTeknis as $item)
            <tr>
                <td></td>
                <td>{{ $item['tujuan'] }}</td>
                <td class="text-center fw-semibold">{{ $item['obs1'] ?? '' }}</td>
                <td class="text-center fw-semibold">{{ $item['obs2'] ?? '' }}</td>
                <td class="text-center fw-semibold">{{ $item['akhir'] ?? '' }}</td>
                <td class="text-center fw-bold bg-light">{{ $item['rerata'] ?? '' }}</td>
            </tr>
            @endforeach

            <!-- SECTION III: Kompetensi Teknis Baru -->
            <tr>
                <td class="text-center fw-bold">III</td>
                <td class="fw-bold">Menerapkan kompetensi teknis baru/atau kompetensi teknis yang belum tuntas dipelajari sesuai konsentrasi keahlian</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @php
                $kompetensiBaru = $observasi->data_kompetensi_baru ?: [];
            @endphp
            @if(count($kompetensiBaru) > 0)
                @foreach($kompetensiBaru as $item)
                <tr>
                    <td></td>
                    <td>{{ $item['tujuan'] }}</td>
                    <td class="text-center fw-semibold">{{ $item['obs1'] ?? '' }}</td>
                    <td class="text-center fw-semibold">{{ $item['obs2'] ?? '' }}</td>
                    <td class="text-center fw-semibold">{{ $item['akhir'] ?? '' }}</td>
                    <td class="text-center fw-bold bg-light">{{ $item['rerata'] ?? '' }}</td>
                </tr>
                @endforeach
            @else
                @for($i = 0; $i < 2; $i++)
                <tr>
                    <td></td>
                    <td>-</td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                </tr>
                @endfor
            @endif

            <!-- SECTION IV: Analisis Usaha -->
            <tr>
                <td class="text-center fw-bold">IV</td>
                <td class="fw-bold">Melakukan analisis usaha secara mandiri</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @php
                $analisisUsaha = $observasi->data_analisis_usaha ?: [];
            @endphp
            @foreach($analisisUsaha as $item)
            <tr>
                <td></td>
                <td>{{ $item['tujuan'] }}</td>
                <td class="text-center fw-semibold">{{ $item['obs1'] ?? '' }}</td>
                <td class="text-center fw-semibold">{{ $item['obs2'] ?? '' }}</td>
                <td class="text-center fw-semibold">{{ $item['akhir'] ?? '' }}</td>
                <td class="text-center fw-bold bg-light">{{ $item['rerata'] ?? '' }}</td>
            </tr>
            @endforeach

            <!-- FOOTER TABLE: TANGGAL & TTD -->
            <tr>
                <td colspan="2" class="fw-bold" style="padding: 6px 8px;">Tanggal Pelaksanaan Observasi</td>
                <td class="text-center fw-semibold">{{ $observasi->tgl_observasi_1 ? \Carbon\Carbon::parse($observasi->tgl_observasi_1)->format('d/m/Y') : '-' }}</td>
                <td class="text-center fw-semibold">{{ $observasi->tgl_observasi_2 ? \Carbon\Carbon::parse($observasi->tgl_observasi_2)->format('d/m/Y') : '-' }}</td>
                <td class="text-center fw-semibold">{{ $observasi->tgl_observasi_akhir ? \Carbon\Carbon::parse($observasi->tgl_observasi_akhir)->format('d/m/Y') : '-' }}</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2" class="fw-bold" style="padding: 16px 8px;">Tanda Tangan Instruktur</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2" class="fw-bold" style="padding: 16px 8px;">Tanda Tangan Guru Pembimbing</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <!-- TTD PIMPINAN -->
    <div class="row mt-4" style="font-size: 12px;">
        <div class="col-7"></div>
        <div class="col-5 text-center">
            <p class="mb-1">{{ $observasi->kota_cetak ?: 'Pekanbaru' }}, {{ $observasi->tgl_cetak ? \Carbon\Carbon::parse($observasi->tgl_cetak)->translatedFormat('d F Y') : date('Y') }}</p>
            <p class="fw-bold mb-4">{{ $observasi->jabatan_pimpinan ?: 'Pimpinan' }}</p>
            <div style="height: 48px;"></div>
            <p class="fw-bold mb-0">( {{ $observasi->nama_pimpinan ?: '................................................' }} )</p>
        </div>
    </div>
</div>

</body>
</html>
