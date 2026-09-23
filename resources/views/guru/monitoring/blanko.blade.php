<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lembar Observasi Praktik Kerja Lapangan - SI-PKL</title>
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
            padding: 15mm 20mm 15mm 20mm;
            margin: auto;
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
        .editable-field:hover {
            outline: 1px dashed #0284c7;
            background-color: #f0f9ff;
            cursor: text;
        }
        .editable-field:focus {
            outline: 2px solid #0284c7;
            background-color: #ffffff;
        }

        .table-obs {
            width: 100%;
            border-collapse: collapse;
            font-size: 11.5px;
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
            .page-break {
                page-break-before: always;
                padding-top: 10mm;
            }
            .action-bar, .edit-notice {
                display: none !important;
            }
            .editable-field:hover, .editable-field:focus {
                outline: none !important;
                background-color: transparent !important;
            }
        }
    </style>
</head>
<body>

<div class="action-bar">
    <button onclick="window.close()" class="btn btn-secondary btn-sm">
        <i class="ph ph-arrow-left me-1"></i> Kembali / Tutup
    </button>
    
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('guru.monitoring.excel') }}" class="btn btn-success btn-sm fw-semibold">
            <i class="ph ph-file-xls me-1"></i> Download Format Excel (.xlsx)
        </a>
        <button onclick="window.print()" class="btn btn-primary btn-sm fw-semibold">
            <i class="ph ph-printer me-1"></i> Cetak / Simpan PDF
        </button>
    </div>
</div>

<div class="page">
    <!-- Notice Bar -->
    <div class="edit-notice alert alert-primary py-2 px-3 mb-3 d-flex align-items-center justify-content-between" style="font-size: 12px; border-radius: 8px;">
        <div>
            <i class="ph ph-info me-1 fw-bold"></i>
            <strong>Format Resmi Lembar Observasi PKL:</strong> Klik langsung pada teks yang bergaris untuk mengedit data secara interaktif sebelum mencetak.
        </div>
    </div>

    <!-- Judul Dokumen -->
    <div class="text-center mb-4">
        <h5 class="fw-bold mb-0" style="letter-spacing: 0.5px; font-size: 14px;">LEMBAR OBSERVASI</h5>
        <h5 class="fw-bold mb-0" style="letter-spacing: 0.5px; font-size: 14px;">PRAKTIK KERJA LAPANGAN</h5>
    </div>

    <!-- I. DATA INSTITUSI -->
    <div style="line-height: 1.6; margin-bottom: 8px; font-size: 12px;">
        <div class="fw-bold">I. DATA INSTITUSI</div>
        <table style="width: 100%; font-size: 12px; margin-left: 10px;">
            <tr>
                <td style="width: 210px;">- NAMA LENGKAP INSTITUSI</td>
                <td style="width: 15px;">:</td>
                <td><span class="editable-field dotted-line" contenteditable="true"></span></td>
            </tr>
            <tr>
                <td>- ALAMAT</td>
                <td>:</td>
                <td><span class="editable-field dotted-line" contenteditable="true"></span></td>
            </tr>
            <tr>
                <td>- TELP/FAX</td>
                <td>:</td>
                <td><span class="editable-field dotted-line" contenteditable="true"></span></td>
            </tr>
        </table>
    </div>

    <!-- II. DATA PIMPINAN -->
    <div style="line-height: 1.6; margin-bottom: 8px; font-size: 12px;">
        <div class="fw-bold">II. DATA PIMPINAN</div>
        <table style="width: 100%; font-size: 12px; margin-left: 10px;">
            <tr>
                <td style="width: 210px;">- NAMA LENGKAP PIMPINAN</td>
                <td style="width: 15px;">:</td>
                <td><span class="editable-field dotted-line" contenteditable="true"></span></td>
            </tr>
            <tr>
                <td>- NIP/NO. REGISTRASI</td>
                <td>:</td>
                <td><span class="editable-field dotted-line" contenteditable="true"></span></td>
            </tr>
            <tr>
                <td>- JABATAN</td>
                <td>:</td>
                <td><span class="editable-field dotted-line" contenteditable="true"></span></td>
            </tr>
        </table>
    </div>

    <!-- III. DATA INSTRUKTUR -->
    <div style="line-height: 1.6; margin-bottom: 8px; font-size: 12px;">
        <div class="fw-bold">III. DATA INSTRUKTUR</div>
        <table style="width: 100%; font-size: 12px; margin-left: 10px;">
            <tr>
                <td style="width: 210px;">- NAMA LENGKAP INSTRUKTUR</td>
                <td style="width: 15px;">:</td>
                <td><span class="editable-field dotted-line" contenteditable="true"></span></td>
            </tr>
            <tr>
                <td>- NIP/NO. REGISTRASI</td>
                <td>:</td>
                <td><span class="editable-field dotted-line" contenteditable="true"></span></td>
            </tr>
            <tr>
                <td>- JABATAN</td>
                <td>:</td>
                <td><span class="editable-field dotted-line" contenteditable="true"></span></td>
            </tr>
        </table>
    </div>

    <!-- IV. MURID -->
    <div style="line-height: 1.6; margin-bottom: 12px; font-size: 12px;">
        <div class="fw-bold">IV. MURID</div>
        <table style="width: 100%; font-size: 12px; margin-left: 10px;">
            <tr>
                <td style="width: 210px;">- NAMA LENGKAP MURID</td>
                <td style="width: 15px;">:</td>
                <td><span class="editable-field dotted-line" contenteditable="true"></span></td>
            </tr>
            <tr>
                <td>- KONSENTRASI KEAHLIAN</td>
                <td>:</td>
                <td><span class="editable-field dotted-line" contenteditable="true"></span></td>
            </tr>
        </table>
    </div>

    <!-- TABEL PENILAIAN OBSERVASI (BAGIAN 1 & 2) -->
    <table class="table-obs">
        <thead>
            <tr>
                <th style="width: 35px;">No</th>
                <th>Capaian dan Tujuan Pembelajaran</th>
                <th style="width: 75px;">Observasi I</th>
                <th style="width: 75px;">Observasi II</th>
                <th style="width: 65px;">Akhir</th>
                <th style="width: 65px;">Rerata</th>
            </tr>
        </thead>
        <tbody>
            <!-- SECTION I -->
            <tr>
                <td class="text-center fw-bold">I</td>
                <td class="fw-bold">Menerapkan soft skills yang dibutuhkan dalam dunia kerja (tempat PKL)</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td>Etika dalam berkomunikasi lisan dan tulisan</td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
            </tr>
            <tr>
                <td></td>
                <td>Integritas dalam bekerja (Jujur, Disiplin, komitmen, dan tanggung jawab)</td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
            </tr>
            <tr>
                <td></td>
                <td>Bekerja secara mandiri</td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
            </tr>
            <tr>
                <td></td>
                <td>Bekerja secara tim</td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
            </tr>
            <tr>
                <td></td>
                <td>Kepedulian sosial</td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
            </tr>
            <tr>
                <td></td>
                <td>Ketaatan terhadap norma dan POS yang berlaku</td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
            </tr>
            <tr>
                <td></td>
                <td>K3LH di lingkungan kerja.</td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
            </tr>

            <!-- SECTION II -->
            <tr>
                <td class="text-center fw-bold">II</td>
                <td class="fw-bold">Menerapkan kompetensi teknis pada pekerjaan sesuai POS yang berlaku di dunia kerja</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @for($i = 0; $i < 6; $i++)
            <tr>
                <td></td>
                <td><span class="editable-field d-block" contenteditable="true" style="min-height: 18px;"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
            </tr>
            @endfor

            <!-- SECTION III -->
            <tr>
                <td class="text-center fw-bold">III</td>
                <td class="fw-bold">Menerapkan kompetensi teknis baru/atau kompetensi teknis yang belum tuntas dipelajari sesuai konsentrasi keahlian</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @for($i = 0; $i < 6; $i++)
            <tr>
                <td></td>
                <td><span class="editable-field d-block" contenteditable="true" style="min-height: 18px;"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
            </tr>
            @endfor

            <!-- SECTION IV -->
            <tr>
                <td class="text-center fw-bold">IV</td>
                <td class="fw-bold">Melakukan analisis usaha secara mandiri</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td>Menjelaskan bidang usaha/pekerjaan, alur bisnis/kerja tempat PKL.</td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
            </tr>
            <tr>
                <td></td>
                <td>Memasarkan produk/jasa dengan menentukan harga produk dan segmen pasar.</td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
            </tr>
            <tr>
                <td></td>
                <td>Menentukan media yang tepat untuk mempromosikan produk/jasa</td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
            </tr>
            <tr>
                <td></td>
                <td>Memberikan layanan terhadap keluhan pelanggan</td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
            </tr>

            <!-- FOOTER TABLE: TANGGAL & TTD -->
            <tr>
                <td colspan="2" class="fw-bold" style="padding: 6px 8px;">Tanggal Pelaksanaan Observasi</td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
                <td class="text-center"><span class="editable-field" contenteditable="true"></span></td>
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
    <div class="row mt-4" style="font-size: 12.5px;">
        <div class="col-7"></div>
        <div class="col-5 text-center">
            <p class="mb-1">Pekanbaru, <span class="editable-field px-1" contenteditable="true">{{ date('Y') }}</span></p>
            <p class="fw-bold mb-4">Pimpinan</p>
            <div style="height: 45px;"></div>
            <p class="fw-bold mb-0">( <span class="editable-field px-3" contenteditable="true">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> )</p>
        </div>
    </div>
</div>

</body>
</html>
