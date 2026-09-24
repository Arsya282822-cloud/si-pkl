<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        body {
            font-family: Calibri, 'Calibri Light', 'Segoe UI', Candara, Arial, sans-serif;
            color: #000;
            background: #f1f5f9;
            padding: 20px;
            margin: 0;
        }
        .page {
            width: 210mm;
            min-height: 297mm;
            padding-top: 0.7cm;
            padding-bottom: 0.75cm;
            padding-left: 2.54cm;
            padding-right: 2.25cm;
            margin: 0 auto 24px auto;
            background: #fff;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            position: relative;
            box-sizing: border-box;
            page-break-after: always;
        }
        .action-bar {
            width: 210mm;
            margin: 0 auto 14px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            padding: 10px 16px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
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
        
        body.tanpa-kop .kop-surat-wrapper {
            display: none !important;
        }
        body.tanpa-kop .page {
            padding-top: 4cm !important;
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: 0;
            }
            body {
                background: #fff;
                padding: 0;
                margin: 0;
            }
            .page {
                box-shadow: none;
                padding-top: 0.7cm;
                padding-bottom: 0.75cm;
                padding-left: 2.54cm;
                padding-right: 2.25cm;
                margin: 0;
                width: 100%;
                min-height: 297mm;
                box-sizing: border-box;
                page-break-after: always;
            }
            body.tanpa-kop .page {
                padding-top: 4cm !important;
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
    <a href="{{ route('admin.dokumen.index', ['tab' => 'pengantar']) }}" class="btn btn-sm btn-outline-secondary">
        <i class="ph ph-arrow-left me-1"></i> Kembali ke Dokumen Hub
    </a>
    
    <div class="d-flex align-items-center gap-2">
        <button type="button" id="toggleKopBtn" onclick="toggleKop()" class="btn btn-sm btn-outline-primary">
            <i class="ph ph-file-text me-1"></i> Mode: Dengan Kop Digital
        </button>
        <button onclick="window.print()" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1.5 fw-semibold">
            <i class="ph ph-printer" style="font-size: 16px;"></i> Cetak / Simpan PDF
        </button>
    </div>
</div>

@php
    $tglSurat = \Carbon\Carbon::now()->translatedFormat('d F Y');
    $tglMulai = $periode?->tanggal_mulai ? \Carbon\Carbon::parse($periode->tanggal_mulai)->translatedFormat('d F Y') : '15 Juli 2026';
    $tglSelesai = $periode?->tanggal_selesai ? \Carbon\Carbon::parse($periode->tanggal_selesai)->translatedFormat('d F Y') : '15 Oktober 2026';
@endphp

<!-- Dokumen Surat Pengantar Kolektif -->
<div class="page">
    
    <!-- KOP SURAT STANDAR RESMI -->
    <div class="kop-surat-wrapper" style="font-family: Tahoma, sans-serif;">
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 2px;">
            <tr>
                <td style="width: 2.16cm; vertical-align: middle; text-align: left; padding: 0;">
                    <img src="{{ asset('images/logo-yayasan-unri.png') }}" style="width: 2.07cm; height: 2.16cm; object-fit: contain;" alt="Yayasan UNRI">
                </td>
                <td style="text-align: center; vertical-align: middle; padding: 0 8px;">
                    <div style="font-size: 10.5pt; font-weight: bold; color: #000; line-height: 1.15;">YAYASAN UNIVERSITAS RIAU</div>
                    <div style="font-size: 13pt; font-weight: bold; color: #000; line-height: 1.15; margin: 1px 0;">SMK LABOR BINAAN FKIP UNRI</div>
                    <div style="font-size: 9pt; font-weight: bold; color: #000; line-height: 1.15;">TERAKREDITASI "A" (UNGGUL)</div>
                    <div style="font-size: 7.5pt; color: #000; line-height: 1.15; margin-top: 1px;">
                        Jl. Thamrin No. 97 Telp. (0761) 21544 Gobah - Pekanbaru 28132
                    </div>
                    <div style="font-size: 7.5pt; color: #000; line-height: 1.15;">
                        Website: smklabor.sch.id &bull; E-mail: smk_labor@yahoo.com
                    </div>
                </td>
                <td style="width: 2.16cm; vertical-align: middle; text-align: right; padding: 0;">
                    <img src="{{ asset('images/logo-smk-labor.png') }}" style="width: 2.07cm; height: 2.16cm; object-fit: contain;" alt="SMK Labor">
                </td>
            </tr>
        </table>
        <div style="border-top: 2px solid #000; border-bottom: 0.75px solid #000; height: 2px; margin-top: 2px; margin-bottom: 12px;"></div>
    </div>

    <!-- Nomor & Lampiran -->
    <table style="width: 100%; margin-bottom: 12px; font-size: 11pt; line-height: 1.3;">
        <tr>
            <td style="width: 12%;">Nomor</td>
            <td style="width: 2%;">:</td>
            <td style="width: 48%;" class="editable-field" contenteditable="true">421.5/SMK-LBFU/PKL/{{ date('Y') }}/COL-01</td>
            <td style="width: 38%; text-align: right;" class="editable-field" contenteditable="true">Pekanbaru, {{ $tglSurat }}</td>
        </tr>
        <tr>
            <td>Lampiran</td>
            <td>:</td>
            <td class="editable-field" contenteditable="true">{{ count($penempatanList) }} Berkas</td>
            <td></td>
        </tr>
        <tr>
            <td>Hal</td>
            <td>:</td>
            <td class="fw-bold editable-field" contenteditable="true">Permohonan & Pengantar Praktik Kerja Lapangan (PKL) Kolektif</td>
            <td></td>
        </tr>
    </table>

    <!-- Tujuan Surat -->
    <div style="margin-bottom: 14px; font-size: 11pt; line-height: 1.3;">
        Kepada Yth.<br>
        <strong class="editable-field" contenteditable="true">Pimpinan / HRD {{ $perusahaan?->nama_perusahaan ?? 'Mitra Industri / Perusahaan' }}</strong><br>
        <span class="editable-field" contenteditable="true">{{ $perusahaan?->alamat ?: 'Di Tempat' }}</span>
    </div>

    <!-- Isi Surat -->
    <div style="font-size: 11pt; line-height: 1.35; text-align: justify;">
        <p style="margin-bottom: 8px;">Dengan hormat,</p>
        <p style="margin-bottom: 8px; text-indent: 1cm;">
            Dalam rangka pelaksanaan Program Kurikulum Sekolah Menengah Kejuruan serta meningkatkan kompetensi dan pengalaman kerja peserta didik di dunia kerja yang nyata, dengan ini kami mengajukan permohonan pelaksanaan <strong>Praktik Kerja Lapangan (PKL)</strong> bagi siswa/i SMK Labor Binaan FKIP UNRI Pekanbaru.
        </p>
        <p style="margin-bottom: 8px; text-indent: 1cm;">
            Pelaksanaan PKL direncanakan berlangsung mulai tanggal <strong class="editable-field" contenteditable="true">{{ $tglMulai }}</strong> sampai dengan <strong class="editable-field" contenteditable="true">{{ $tglSelesai }}</strong>. Adapun daftar peserta didik yang ditugaskan adalah sebagai berikut:
        </p>

        <!-- Tabel Siswa Kolektif -->
        <table class="table table-bordered align-middle my-2" style="font-size: 9.5pt; border-color: #000;">
            <thead style="background: #f1f5f9; text-align: center;">
                <tr>
                    <th style="width: 30px;">No</th>
                    <th>Nama Lengkap Peserta Didik</th>
                    <th style="width: 110px;">NIS / NISN</th>
                    <th>Kelas / Program Keahlian</th>
                    <th>Guru Pembimbing</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penempatanList as $idx => $item)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td class="fw-semibold">{{ $item->siswa?->nama ?? '-' }}</td>
                        <td class="text-center">{{ $item->siswa?->nis ?? '-' }} / {{ $item->siswa?->nisn ?: '-' }}</td>
                        <td>{{ $item->siswa?->kelas?->nama_kelas ?? '-' }} - {{ $item->siswa?->jurusan?->nama_jurusan ?? '-' }}</td>
                        <td>{{ $item->guru?->nama ?? 'Tim Pokja PKL' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Tidak ada data siswa terpilih.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <p style="margin-top: 8px; margin-bottom: 8px; text-indent: 1cm;">
            Besar harapan kami Bapak/Ibu dapat menerima dan membimbing siswa/i kami selama proses PKL berlangsung. Atas perhatian, kerja sama, dan perkenan Bapak/Ibu, kami ucapkan terima kasih.
        </p>
    </div>

    <!-- Tanda Tangan -->
    @php
        $namaKepsek = \App\Models\Setting::get('pejabat_kepala_sekolah', 'JEFFRI HUNTER, M.Pd');
        $nipKepsek = \App\Models\Setting::get('pejabat_nip_kepala_sekolah', '-');
    @endphp
    <div style="margin-top: 20px; float: right; width: 45%; text-align: center; font-size: 11pt; line-height: 1.2;">
        Kepala Sekolah,<br><br><br><br>
        <strong class="text-decoration-underline editable-field" contenteditable="true">{{ $namaKepsek }}</strong><br>
        <span class="editable-field" contenteditable="true">{{ $nipKepsek !== '-' ? 'NIP. ' . $nipKepsek : '' }}</span>
    </div>
    <div style="clear: both;"></div>

</div>

<script>
    function toggleKop() {
        const body = document.body;
        const btn = document.getElementById('toggleKopBtn');
        if (body.classList.contains('tanpa-kop')) {
            body.classList.remove('tanpa-kop');
            btn.innerHTML = '<i class="ph ph-file-text me-1"></i> Mode: Dengan Kop Digital';
            btn.className = 'btn btn-sm btn-outline-primary';
        } else {
            body.classList.add('tanpa-kop');
            btn.innerHTML = '<i class="ph ph-file-dashed me-1"></i> Mode: Kertas Blangko (Tanpa Kop)';
            btn.className = 'btn btn-sm btn-warning text-dark';
        }
    }
</script>

</body>
</html>
