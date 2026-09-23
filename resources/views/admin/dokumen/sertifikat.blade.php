<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat PKL - {{ $penempatan->siswa?->nama }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Montserrat:wght@400;500;600;700&family=Great+Vibes&display=swap" rel="stylesheet">
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

        /* Landscape A4 Page */
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

        /* Certificate Ornate Golden Border */
        .cert-border {
            width: 100%;
            height: 100%;
            border: 3px solid #b45309;
            outline: 2px solid #fef3c7;
            outline-offset: -6px;
            padding: 10mm 14mm;
            position: relative;
            box-sizing: border-box;
            background: radial-gradient(circle at center, #ffffff 0%, #fffdf7 100%);
        }

        .cert-corner {
            position: absolute;
            width: 32px;
            height: 32px;
            border: 3px solid #b45309;
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
                page-break-after: always;
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

<!-- ========================================================= -->
<!-- HALAMAN 1: SERTIFIKAT KELULUSAN PKL (DEPAN)               -->
<!-- ========================================================= -->
<div class="cert-page">
    <div class="cert-border d-flex flex-column justify-content-between">
        <div class="cert-corner cert-corner-tl"></div>
        <div class="cert-corner cert-corner-tr"></div>
        <div class="cert-corner cert-corner-bl"></div>
        <div class="cert-corner cert-corner-br"></div>

        <!-- Header Logos & School Title -->
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

            <div style="border-bottom: 2px solid #b45309; margin-top: 8px; margin-bottom: 12px; position: relative;">
                <div style="position: absolute; top: -6px; left: 50%; transform: translateX(-50%); background: #fffdf7; padding: 0 12px; color: #b45309; font-size: 10px; font-weight: bold; letter-spacing: 1px;">
                    SERTIFIKAT RESMI
                </div>
            </div>

            <!-- Title -->
            <div class="text-center" style="margin-top: 10px;">
                <h2 style="font-family: 'Cinzel', serif; font-size: 24px; font-weight: 800; color: #b45309; letter-spacing: 2px; margin-bottom: 2px;">
                    SERTIFIKAT PRAKTIK KERJA LAPANGAN
                </h2>
                <div style="font-size: 11px; color: #475569; letter-spacing: 1px;">
                    Nomor: <span class="editable-field px-1" contenteditable="true">421.5 / SMK-LBFU / SERT-PKL / {{ date('Y') }} / {{ str_pad($penempatan->id, 3, '0', STR_PAD_LEFT) }}</span>
                </div>
            </div>
        </div>

        <!-- Student & Internship Info -->
        <div class="text-center my-auto" style="padding: 6px 20px;">
            <div style="font-size: 12px; color: #475569; font-style: italic; margin-bottom: 4px;">Diberikan kepada:</div>
            
            <h1 class="editable-field d-inline-block px-3 py-1 mb-1" contenteditable="true" style="font-family: 'Cinzel', serif; font-size: 26px; font-weight: 800; color: #0c4a6e; letter-spacing: 1px; border-bottom: 2px solid #0ea5e9;">
                {{ strtoupper($penempatan->siswa?->nama ?? 'NAMA PESERTA DIDIK') }}
            </h1>

            <div style="font-size: 12.5px; color: #334155; margin-top: 4px;">
                NIS / NISN : <strong class="editable-field px-1" contenteditable="true">{{ $penempatan->siswa?->nis ?? '-' }} / {{ $penempatan->siswa?->nisn ?? '-' }}</strong> &nbsp;|&nbsp; 
                Konsentrasi Keahlian : <strong class="editable-field px-1" contenteditable="true">{{ $penempatan->siswa?->jurusan?->nama_jurusan ?? '-' }}</strong>
            </div>

            <p class="editable-field mt-3 mb-1" contenteditable="true" style="font-size: 12.5px; color: #334155; line-height: 1.6; max-width: 820px; margin-left: auto; margin-right: auto;">
                Telah melaksanakan dan menyelesaikan Program <strong>Praktik Kerja Lapangan (PKL)</strong> di:
            </p>

            <h4 class="editable-field fw-bold mb-1" contenteditable="true" style="color: #0369a1; font-size: 17px; letter-spacing: 0.5px;">
                {{ strtoupper($penempatan->perusahaan?->nama_perusahaan ?? 'NAMA PERUSAHAAN / DUDI') }}
            </h4>

            <div style="font-size: 12px; color: #475569;">
                Terhitung mulai tanggal <strong class="editable-field px-1" contenteditable="true">{{ $penempatan->periodePkl?->tanggal_mulai ? $penempatan->periodePkl->tanggal_mulai->translatedFormat('d F Y') : '-' }}</strong> 
                sampai dengan <strong class="editable-field px-1" contenteditable="true">{{ $penempatan->periodePkl?->tanggal_selesai ? $penempatan->periodePkl->tanggal_selesai->translatedFormat('d F Y') : '-' }}</strong> 
                dengan predikat:
            </div>

            <div class="mt-2">
                <span class="editable-field px-3 py-1 fw-bold text-white d-inline-block" contenteditable="true" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); border-radius: 20px; font-size: 13px; letter-spacing: 1px; box-shadow: 0 4px 10px rgba(2, 132, 199, 0.3);">
                    SANGAT BAIK (A)
                </span>
            </div>
        </div>

        <!-- Signatures & Verification QR -->
        <div>
            <table style="width: 100%; border-collapse: collapse; font-size: 12px; margin-top: 10px;">
                <tr>
                    <!-- Pihak DUDI -->
                    <td style="width: 38%; text-align: center; vertical-align: top;">
                        <p class="mb-1" style="color: #64748b;">Mengetahui,</p>
                        <p class="fw-bold mb-0">
                            <span class="editable-field px-1" contenteditable="true">Pimpinan / Pembimbing Industri</span>
                        </p>
                        <p class="mb-0 text-muted" style="font-size: 11px;">
                            <span class="editable-field px-1" contenteditable="true">{{ $penempatan->perusahaan?->nama_perusahaan }}</span>
                        </p>
                        <div style="height: 48px;"></div>
                        <p class="fw-bold text-decoration-underline mb-0">
                            <span class="editable-field px-1" contenteditable="true">{{ $penempatan->perusahaan?->pembimbing_industri ?: 'Pimpinan Perusahaan' }}</span>
                        </p>
                    </td>

                    <!-- QR Code Verifikasi Tengah -->
                    <td style="width: 24%; text-align: center; vertical-align: middle;">
                        @php
                            $qrData = urlencode(route('verifikasi.sertifikat', $penempatan));
                        @endphp
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=72x72&data={{ $qrData }}" alt="QR Verifikasi" style="width: 62px; height: 62px; border: 1px solid #cbd5e1; padding: 2px; background: #fff;">
                        <div style="font-size: 8.5px; color: #64748b; margin-top: 4px; font-weight: 500;">
                            Scan Verifikasi Keaslian
                        </div>
                    </td>


                    <!-- Kepala Sekolah -->
                    @php
                        $namaKepsek = \App\Models\Setting::get('pejabat_kepala_sekolah', 'JEFFRI HUNTER, M.Pd');
                        $nipKepsek = \App\Models\Setting::get('pejabat_nip_kepala_sekolah', '-');
                        $kotaTerbit = \App\Models\Setting::get('sekolah_kota_terbit', 'Pekanbaru');
                    @endphp
                    <td style="width: 38%; text-align: center; vertical-align: top;">
                        <p class="mb-1" style="color: #64748b;">{{ $kotaTerbit }}, <span class="editable-field px-1" contenteditable="true">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span></p>
                        <p class="fw-bold mb-0">
                            <span class="editable-field px-1" contenteditable="true">Kepala SMK Labor Binaan FKIP UNRI</span>
                        </p>
                        <p class="mb-0 text-muted" style="font-size: 11px;">Pekanbaru - Riau</p>
                        <div style="height: 48px;"></div>
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



<!-- ========================================================= -->
<!-- HALAMAN 2: TRANSKRIP NILAI PKL (BELAKANG)                 -->
<!-- ========================================================= -->
<div class="cert-page">
    <div class="cert-border d-flex flex-column justify-content-between">
        <div class="cert-corner cert-corner-tl"></div>
        <div class="cert-corner cert-corner-tr"></div>
        <div class="cert-corner cert-corner-bl"></div>
        <div class="cert-corner cert-corner-br"></div>

        <!-- Header Transkrip -->
        <div>
            <div class="text-center mb-3">
                <h3 style="font-family: 'Cinzel', serif; font-size: 18px; font-weight: 800; color: #0c4a6e; letter-spacing: 1.5px; margin-bottom: 2px;">
                    DAFTAR NILAI PRAKTIK KERJA LAPANGAN
                </h3>
                <div style="font-size: 11px; color: #475569;">
                    TAHUN AJARAN {{ $penempatan->periodePkl?->tahun_ajaran ?? date('Y').'/'.(date('Y')+1) }}
                </div>
            </div>

            <!-- Identitas Siswa -->
            <table style="width: 100%; font-size: 12px; margin-bottom: 12px;">
                <tr>
                    <td style="width: 18%;">Nama Peserta Didik</td>
                    <td style="width: 2%;">:</td>
                    <td style="width: 40%; font-weight: bold;">
                        <span class="editable-field px-1" contenteditable="true">{{ $penempatan->siswa?->nama }}</span>
                    </td>
                    <td style="width: 18%;">Tempat PKL (DUDI)</td>
                    <td style="width: 2%;">:</td>
                    <td style="width: 20%; font-weight: bold;">
                        <span class="editable-field px-1" contenteditable="true">{{ $penempatan->perusahaan?->nama_perusahaan }}</span>
                    </td>
                </tr>
                <tr>
                    <td>NIS / NISN</td>
                    <td>:</td>
                    <td>
                        <span class="editable-field px-1" contenteditable="true">{{ $penempatan->siswa?->nis }} / {{ $penempatan->siswa?->nisn ?: '-' }}</span>
                    </td>
                    <td>Konsentrasi Keahlian</td>
                    <td>:</td>
                    <td>
                        <span class="editable-field px-1" contenteditable="true">{{ $penempatan->siswa?->jurusan?->nama_jurusan }}</span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Tabel Nilai Komponen -->
        <div class="my-auto">
            <table style="width: 100%; border-collapse: collapse; font-size: 11.5px; border: 1.5px solid #000;">
                <thead>
                    <tr style="background-color: #f1f5f9; border-bottom: 1.5px solid #000;">
                        <th style="width: 40px; text-align: center; padding: 6px; border: 1px solid #000;">NO</th>
                        <th style="text-align: left; padding: 6px 10px; border: 1px solid #000;">KOMPONEN PENILAIAN</th>
                        <th style="width: 110px; text-align: center; padding: 6px; border: 1px solid #000;">NILAI ANGKA</th>
                        <th style="width: 130px; text-align: center; padding: 6px; border: 1px solid #000;">PREDIKAT / HURUF</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="background-color: #f8fafc; font-weight: bold;">
                        <td style="text-align: center; padding: 5px; border: 1px solid #000;">A</td>
                        <td colspan="3" style="padding: 5px 10px; border: 1px solid #000;">ASPEK NON-TEKNIS (SOFT SKILLS)</td>
                    </tr>
                    <tr>
                        <td style="text-align: center; padding: 4px; border: 1px solid #000;">1</td>
                        <td style="padding: 4px 10px; border: 1px solid #000;">Kedisiplinan dan Kehadiran Kerja</td>
                        <td style="text-align: center; font-weight: bold; border: 1px solid #000;"><span class="editable-field px-1" contenteditable="true">92</span></td>
                        <td style="text-align: center; border: 1px solid #000;"><span class="editable-field px-1" contenteditable="true">Sangat Baik (A)</span></td>
                    </tr>
                    <tr>
                        <td style="text-align: center; padding: 4px; border: 1px solid #000;">2</td>
                        <td style="padding: 4px 10px; border: 1px solid #000;">Tanggung Jawab dan Kemandirian Kerja</td>
                        <td style="text-align: center; font-weight: bold; border: 1px solid #000;"><span class="editable-field px-1" contenteditable="true">90</span></td>
                        <td style="text-align: center; border: 1px solid #000;"><span class="editable-field px-1" contenteditable="true">Sangat Baik (A)</span></td>
                    </tr>
                    <tr>
                        <td style="text-align: center; padding: 4px; border: 1px solid #000;">3</td>
                        <td style="padding: 4px 10px; border: 1px solid #000;">Inisiatif, Kreativitas, dan Kerja Sama Tim</td>
                        <td style="text-align: center; font-weight: bold; border: 1px solid #000;"><span class="editable-field px-1" contenteditable="true">88</span></td>
                        <td style="text-align: center; border: 1px solid #000;"><span class="editable-field px-1" contenteditable="true">Baik (B)</span></td>
                    </tr>
                    <tr>
                        <td style="text-align: center; padding: 4px; border: 1px solid #000;">4</td>
                        <td style="padding: 4px 10px; border: 1px solid #000;">Etika Profesi, Sopan Santun, dan Kejujuran</td>
                        <td style="text-align: center; font-weight: bold; border: 1px solid #000;"><span class="editable-field px-1" contenteditable="true">95</span></td>
                        <td style="text-align: center; border: 1px solid #000;"><span class="editable-field px-1" contenteditable="true">Sangat Baik (A)</span></td>
                    </tr>

                    <tr style="background-color: #f8fafc; font-weight: bold;">
                        <td style="text-align: center; padding: 5px; border: 1px solid #000;">B</td>
                        <td colspan="3" style="padding: 5px 10px; border: 1px solid #000;">ASPEK TEKNIS (HARD SKILLS)</td>
                    </tr>
                    <tr>
                        <td style="text-align: center; padding: 4px; border: 1px solid #000;">5</td>
                        <td style="padding: 4px 10px; border: 1px solid #000;">Penguasaan Keterampilan / Kompetensi Keahlian</td>
                        <td style="text-align: center; font-weight: bold; border: 1px solid #000;"><span class="editable-field px-1" contenteditable="true">90</span></td>
                        <td style="text-align: center; border: 1px solid #000;"><span class="editable-field px-1" contenteditable="true">Sangat Baik (A)</span></td>
                    </tr>
                    <tr>
                        <td style="text-align: center; padding: 4px; border: 1px solid #000;">6</td>
                        <td style="padding: 4px 10px; border: 1px solid #000;">Kualitas dan Ketelitian Hasil Pekerjaan</td>
                        <td style="text-align: center; font-weight: bold; border: 1px solid #000;"><span class="editable-field px-1" contenteditable="true">89</span></td>
                        <td style="text-align: center; border: 1px solid #000;"><span class="editable-field px-1" contenteditable="true">Baik (B)</span></td>
                    </tr>
                    <tr>
                        <td style="text-align: center; padding: 4px; border: 1px solid #000;">7</td>
                        <td style="padding: 4px 10px; border: 1px solid #000;">Penerapan Keselamatan dan Kesehatan Kerja (K3)</td>
                        <td style="text-align: center; font-weight: bold; border: 1px solid #000;"><span class="editable-field px-1" contenteditable="true">94</span></td>
                        <td style="text-align: center; border: 1px solid #000;"><span class="editable-field px-1" contenteditable="true">Sangat Baik (A)</span></td>
                    </tr>
                    <tr>
                        <td style="text-align: center; padding: 4px; border: 1px solid #000;">8</td>
                        <td style="padding: 4px 10px; border: 1px solid #000;">Laporan Akhir dan Jurnal Kegiatan PKL</td>
                        <td style="text-align: center; font-weight: bold; border: 1px solid #000;"><span class="editable-field px-1" contenteditable="true">91</span></td>
                        <td style="text-align: center; border: 1px solid #000;"><span class="editable-field px-1" contenteditable="true">Sangat Baik (A)</span></td>
                    </tr>

                    <tr style="background-color: #f1f5f9; font-weight: bold;">
                        <td colspan="2" style="text-align: right; padding: 6px 12px; border: 1.5px solid #000;">NILAI RATA-RATA AKHIR</td>
                        <td style="text-align: center; font-size: 13px; color: #0284c7; border: 1.5px solid #000;"><span class="editable-field px-1" contenteditable="true">91.13</span></td>
                        <td style="text-align: center; font-size: 12px; color: #0284c7; border: 1.5px solid #000;"><span class="editable-field px-1" contenteditable="true">SANGAT BAIK (A)</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Keterangan Skala Nilai & Tanda Tangan -->
        <div>
            <table style="width: 100%; border-collapse: collapse; font-size: 11.5px; margin-top: 10px;">
                <tr>
                    <td style="width: 45%; vertical-align: top; font-size: 10px; color: #64748b;">
                        <strong class="text-dark">Keterangan Rentang Nilai:</strong><br>
                        • 90 – 100 : Sangat Baik (A)<br>
                        • 80 – 89  : Baik (B)<br>
                        • 70 – 79  : Cukup (C)<br>
                        • < 70     : Kurang (D)
                    </td>
                    <td style="width: 55%; text-align: center; vertical-align: top;">
                        <p class="mb-1">Pekanbaru, <span class="editable-field px-1" contenteditable="true">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span></p>
                        <p class="fw-bold mb-0">
                            <span class="editable-field px-1" contenteditable="true">Pembimbing Praktik Kerja Lapangan,</span>
                        </p>
                        <div style="height: 46px;"></div>
                        <p class="fw-bold text-decoration-underline mb-0">
                            <span class="editable-field px-1" contenteditable="true">{{ $penempatan->guru?->nama ?: 'Guru Pembimbing Sekolah' }}</span>
                        </p>
                        <small class="text-muted">NIP: {{ $penempatan->guru?->nip ?: '-' }}</small>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

</body>
</html>
