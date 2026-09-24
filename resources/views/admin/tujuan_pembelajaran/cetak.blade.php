<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tujuan Pembelajaran PKL 2026 - {{ $settings['nama_sekolah'] }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Tinos:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 15mm 15mm 15mm;
        }
        body {
            font-family: 'Tinos', 'Times New Roman', serif;
            font-size: 10.5pt;
            line-height: 1.35;
            color: #000;
            background-color: #f1f5f9;
            margin: 0;
            padding: 20px;
        }
        .page-container {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            background: #fff;
            padding: 15mm 18mm;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            box-sizing: border-box;
            position: relative;
        }
        .header-table {
            width: 100%;
            border-bottom: 3px double #000;
            margin-bottom: 14px;
            padding-bottom: 6px;
        }
        .header-table img {
            width: 65px;
            height: 65px;
            object-fit: contain;
        }
        .kop-text {
            text-align: center;
            line-height: 1.2;
        }
        .kop-instansi {
            font-size: 11pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .kop-sekolah {
            font-size: 13.5pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 2px 0;
        }
        .kop-alamat {
            font-size: 8.5pt;
            font-family: 'Inter', sans-serif;
            color: #333;
        }
        .title-doc {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 10px 0 2px 0;
        }
        .subtitle-doc {
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 16px;
        }
        .table-tp {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
            margin-bottom: 16px;
            page-break-inside: auto;
        }
        .table-tp tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        .table-tp th, .table-tp td {
            border: 1px solid #000;
            padding: 4px 7px;
        }
        .table-tp th {
            background-color: #f8fafc;
            text-align: center;
            font-weight: bold;
        }
        .signature-table {
            width: 100%;
            margin-top: 24px;
            font-size: 10pt;
            page-break-inside: avoid;
        }
        .signature-table td {
            vertical-align: top;
            text-align: center;
            width: 50%;
        }
        .btn-print {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 999;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        @media print {
            body {
                background: none;
                padding: 0;
            }
            .page-container {
                box-shadow: none;
                padding: 0;
                width: 100%;
                min-height: auto;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Floating Print Button -->
    <div class="no-print btn-print">
        <button onclick="window.print()" class="btn btn-primary btn-lg rounded-pill d-flex align-items-center gap-2 px-4 shadow">
            <i class="ph-bold ph-printer" style="font-size: 20px;"></i> Cetak / Simpan PDF
        </button>
    </div>

    <div class="page-container">
        <!-- KOP SURAT -->
        <table class="header-table">
            <tr>
                <td style="width: 75px; text-align: center;">
                    <img src="{{ asset('images/logo-yayasan-unri.png') }}" onerror="this.src='{{ asset('images/logo-sipkl.jpg') }}'" alt="Logo Yayasan">
                </td>
                <td class="kop-text">
                    <div class="kop-instansi">{{ $settings['nama_yayasan'] }}</div>
                    <div class="kop-sekolah">{{ $settings['nama_sekolah'] }}</div>
                    <div class="kop-alamat">
                        {{ $settings['alamat_sekolah'] }}<br>
                        Telp: {{ $settings['telepon_sekolah'] }} | Email: {{ $settings['email_sekolah'] }}
                    </div>
                </td>
                <td style="width: 75px; text-align: center;">
                    <img src="{{ asset('images/logo-smk-labor.png') }}" onerror="this.src='{{ asset('images/logo-sipkl.jpg') }}'" alt="Logo Sekolah">
                </td>
            </tr>
        </table>

        <!-- JUDUL DOKUMEN -->
        <div class="title-doc">Tujuan Pembelajaran</div>
        <div class="subtitle-doc">Praktik Kerja Lapangan Tahun 2026</div>

        @foreach($itemsByJurusan as $namaKonsentrasi => $tpList)
            <div class="mb-4" style="page-break-inside: avoid;">
                <div style="font-weight: bold; font-size: 10.5pt; margin-bottom: 4px;">
                    Konsentrasi Keahlian : {{ $namaKonsentrasi }}
                </div>

                @php
                    $groupedByCp = $tpList->groupBy('capaian_pembelajaran');
                @endphp

                @foreach($groupedByCp as $cpText => $tps)
                    <div style="font-weight: bold; font-size: 10pt; margin-top: 8px; margin-bottom: 4px;">
                        Capaian Pembelajaran : {{ $cpText }}
                    </div>

                    <table class="table-tp">
                        <thead>
                            <tr>
                                <th style="width: 35px;">No.</th>
                                <th>Tujuan Pembelajaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tps as $tp)
                                <tr>
                                    <td style="text-align: center; font-weight: bold; width: 35px;">{{ $tp->nomor_urut }}</td>
                                    <td>{{ $tp->tujuan_pembelajaran }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endforeach
            </div>
        @endforeach

        <!-- TANDA TANGAN PENGESAHAN -->
        <table class="signature-table">
            <tr>
                <td>
                    Mengetahui,<br>
                    <strong>Kepala SMK Labor FKIP UNRI</strong>
                    <br><br><br><br><br>
                    <strong style="text-decoration: underline;">{{ $settings['nama_kepala_sekolah'] }}</strong><br>
                    <span>NIP. {{ $settings['nip_kepala_sekolah'] }}</span>
                </td>
                <td>
                    {{ $settings['kota_terbit'] }}, {{ date('d F Y') }}<br>
                    <strong>Koordinator / Ketua Pokja PKL</strong>
                    <br><br><br><br><br>
                    <strong style="text-decoration: underline;">{{ $settings['ketua_pokja'] }}</strong><br>
                    <span>NIP. {{ $settings['nip_ketua_pokja'] }}</span>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
