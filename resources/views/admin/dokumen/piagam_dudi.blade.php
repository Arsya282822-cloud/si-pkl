<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Piagam Penghargaan DUDI - {{ $penempatan->perusahaan?->nama_perusahaan }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            color: #1e293b;
            background: #e2e8f0;
            padding: 24px;
            margin: 0;
        }
        
        .action-bar {
            width: 297mm;
            margin: 0 auto 16px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cert-page {
            width: 297mm;
            height: 210mm;
            padding: 12mm;
            margin: 0 auto 24px auto;
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
            position: relative;
            box-sizing: border-box;
            page-break-after: always;
            overflow: hidden;
        }

        .cert-border {
            width: 100%;
            height: 100%;
            border: 3px solid #0284c7;
            outline: 2px solid #bae6fd;
            outline-offset: -6px;
            padding: 10mm 14mm;
            position: relative;
            box-sizing: border-box;
            background: radial-gradient(circle at center, #ffffff 0%, #f0f9ff 100%);
        }

        .cert-corner {
            position: absolute;
            width: 32px;
            height: 32px;
            border: 3px solid #0284c7;
        }
        .cert-corner-tl { top: 4px; left: 4px; border-right: none; border-bottom: none; }
        .cert-corner-tr { top: 4px; right: 4px; border-left: none; border-bottom: none; }
        .cert-corner-bl { bottom: 4px; left: 4px; border-right: none; border-top: none; }
        .cert-corner-br { bottom: 4px; right: 4px; border-left: none; border-top: none; }

        .editable-field:hover {
            outline: 1px dashed #0284c7;
            background-color: #f0f9ff;
            cursor: text;
        }
        .editable-field:focus {
            outline: 2px solid #0284c7;
            background-color: #ffffff;
        }

        @media print {
            @page {
                size: A4 landscape;
                margin: 0;
            }
            body {
                background: #fff;
                padding: 0;
                margin: 0;
            }
            .cert-page {
                box-shadow: none;
                margin: 0;
                width: 297mm;
                height: 210mm;
            }
            .action-bar {
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
    <button onclick="window.close()" class="btn btn-secondary">
        <i class="ph ph-arrow-left me-1"></i> Tutup
    </button>
    
    <div class="d-flex align-items-center gap-2">
        <button onclick="location.reload()" class="btn btn-outline-secondary">
            <i class="ph ph-arrow-counter-clockwise me-1"></i> Reset Teks
        </button>
        <button onclick="window.print()" class="btn btn-primary px-4 py-2" style="font-weight: 600;">
            <i class="ph ph-printer me-1"></i> Cetak / Simpan PDF (Landscape)
        </button>
    </div>
</div>

<div class="cert-page">
    <div class="cert-border d-flex flex-column justify-content-between">
        <div class="cert-corner cert-corner-tl"></div>
        <div class="cert-corner cert-corner-tr"></div>
        <div class="cert-corner cert-corner-bl"></div>
        <div class="cert-corner cert-corner-br"></div>

        <!-- Header -->
        <div>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 70px; vertical-align: middle; text-align: left;">
                        <img src="{{ asset('images/logo-tutwuri.png') }}" alt="Tut Wuri" style="height: 52px; object-fit: contain;">
                    </td>
                    <td style="text-align: center; vertical-align: middle;">
                        <div style="font-size: 11px; letter-spacing: 2px; text-transform: uppercase; color: #64748b; font-weight: 600;">YAYASAN UNIVERSITAS RIAU</div>
                        <div style="font-family: 'Cinzel', serif; font-size: 16px; font-weight: 700; color: #0f172a; letter-spacing: 1px; margin-top: 2px;">
                            SMK LABOR BINAAN FKIP UNRI PEKANBARU
                        </div>
                        <div style="font-size: 9.5px; color: #64748b; margin-top: 1px;">
                            Jl. Thamrin No. 97 Kec. Sail Pekanbaru – Riau 28132 | Website: www.smklabor.sch.id
                        </div>
                    </td>
                    <td style="width: 110px; vertical-align: middle; text-align: right; white-space: nowrap;">
                        <img src="{{ asset('images/logo-yayasan-unri.png') }}" alt="Yayasan UNRI" style="height: 48px; object-fit: contain; margin-right: 6px;">
                        <img src="{{ asset('images/logo-smk-labor.png') }}" alt="SMK Labor" style="height: 48px; object-fit: contain;">
                    </td>
                </tr>
            </table>

            <div style="border-bottom: 2px solid #0284c7; margin-top: 8px; margin-bottom: 12px; position: relative;">
                <div style="position: absolute; top: -6px; left: 50%; transform: translateX(-50%); background: #f0f9ff; padding: 0 12px; color: #0284c7; font-size: 10px; font-weight: bold; letter-spacing: 1px;">
                    PENGHARGAAN MITRA INDUSTRI
                </div>
            </div>

            <!-- Title -->
            <div class="text-center" style="margin-top: 10px;">
                <h2 style="font-family: 'Cinzel', serif; font-size: 24px; font-weight: 800; color: #0369a1; letter-spacing: 2px; margin-bottom: 2px;">
                    PIAGAM PENGHARGAAN
                </h2>
                <div style="font-size: 11px; color: #475569; letter-spacing: 1px;">
                    Nomor: <span class="editable-field px-1" contenteditable="true">421.5 / SMK-LBFU / PIAGAM-DUDI / {{ date('Y') }} / {{ str_pad($penempatan->perusahaan?->id ?? 1, 3, '0', STR_PAD_LEFT) }}</span>
                </div>
            </div>
        </div>

        <!-- Body Content -->
        <div class="text-center my-auto" style="padding: 10px 30px;">
            <div style="font-size: 12.5px; color: #475569; font-style: italic; margin-bottom: 6px;">
                Diberikan dengan penuh hormat dan apresiasi setinggi-tingginya kepada:
            </div>
            
            <h1 class="editable-field d-inline-block px-4 py-2 mb-2" contenteditable="true" style="font-family: 'Cinzel', serif; font-size: 24px; font-weight: 800; color: #0c4a6e; letter-spacing: 1px; border-bottom: 2px solid #0284c7;">
                {{ strtoupper($penempatan->perusahaan?->nama_perusahaan ?? 'NAMA PERUSAHAAN MITRA') }}
            </h1>

            <p class="editable-field mt-3 mb-0" contenteditable="true" style="font-size: 13.5px; color: #334155; line-height: 1.7; max-width: 820px; margin-left: auto; margin-right: auto;">
                Sebagai <strong>Mitra Dunia Usaha dan Dunia Industri (DUDI)</strong> atas dedikasi, kontribusi, dan kerja sama yang sangat baik dalam mendukung program link and match pendidikan vokasi serta memfasilitasi pelaksanaan <strong>Praktik Kerja Lapangan (PKL)</strong> bagi peserta didik SMK Labor Binaan FKIP UNRI Pekanbaru Tahun Ajaran {{ $penempatan->periodePkl?->tahun_ajaran ?? date('Y').'/'.(date('Y')+1) }}.
            </p>
        </div>

        <!-- Signature -->
        @php
            $namaKepsek = \App\Models\Setting::get('pejabat_kepala_sekolah', 'JEFFRI HUNTER, M.Pd');
            $nipKepsek = \App\Models\Setting::get('pejabat_nip_kepala_sekolah', '-');
            $kotaTerbit = \App\Models\Setting::get('sekolah_kota_terbit', 'Pekanbaru');
        @endphp
        <div>
            <table style="width: 100%; border-collapse: collapse; font-size: 12px; margin-top: 10px;">
                <tr>
                    <td style="width: 60%;"></td>
                    <td style="width: 40%; text-align: center; vertical-align: top;">
                        <p class="mb-1" style="color: #64748b;">{{ $kotaTerbit }}, <span class="editable-field px-1" contenteditable="true">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span></p>
                        <p class="fw-bold mb-0">
                            <span class="editable-field px-1" contenteditable="true">Kepala SMK Labor Binaan FKIP UNRI</span>
                        </p>
                        <p class="mb-0 text-muted" style="font-size: 11px;">Pekanbaru - Riau</p>
                        <div style="height: 52px;"></div>
                        <p class="fw-bold text-decoration-underline mb-0">
                            <span class="editable-field px-1" contenteditable="true">{{ $namaKepsek }}</span>
                        </p>
                        @if($nipKepsek && $nipKepsek !== '-')
                            <p class="text-muted mb-0" style="font-size: 11px;">
                                <span class="editable-field px-1" contenteditable="true">NIP. {{ $nipKepsek }}</span>
                            </p>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>


</body>
</html>
