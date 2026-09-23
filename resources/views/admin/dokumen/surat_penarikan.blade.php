<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Penarikan PKL - {{ $penempatan->siswa?->nama }}</title>
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
            /* Margins: Top 0.7cm, Left 2.54cm, Right 2.25cm, Bottom 0.75cm */
            padding-top: 0.7cm;
            padding-bottom: 0.75cm;
            padding-left: 2.54cm;
            padding-right: 2.25cm;
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
            }
            body.tanpa-kop .page {
                padding-top: 4cm !important;
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
    <button onclick="window.close()" class="btn btn-secondary">
        <i class="ph ph-arrow-left me-1"></i> Tutup
    </button>
    
    <div class="d-flex align-items-center gap-2">
        <button type="button" id="toggleKopBtn" onclick="toggleKop()" class="btn btn-outline-primary">
            <i class="ph ph-file-text me-1"></i> Mode: Dengan Kop Digital
        </button>
        <button onclick="location.reload()" class="btn btn-outline-secondary">
            <i class="ph ph-arrow-counter-clockwise me-1"></i> Reset Teks
        </button>
        <button onclick="window.print()" class="btn btn-primary">
            <i class="ph ph-printer me-1"></i> Cetak / Simpan PDF
        </button>
    </div>
</div>

<div class="page">
    <!-- Notice Bar -->
    <div class="edit-notice alert alert-info py-2 px-3 mb-2 d-flex align-items-center justify-content-between" style="font-size: 13px; border-radius: 8px;">
        <div>
            <i class="ph ph-pencil-simple me-1 fw-bold"></i>
            <strong>Margin:</strong> Atas 0.7 cm | Kiri 2.54 cm | Kanan 2.25 cm | Bawah 0.75 cm. Teks dapat langsung diedit sebelum dicetak.
        </div>
    </div>

    <!-- Kop Surat Resmi -->
    @include('admin.dokumen._kop')

    <!-- Info & Tanggal Surat -->
    <div class="row mb-3" style="font-size: 14px; line-height: 1.45;">
        <div class="col-7">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 80px;">Nomor</td>
                    <td style="width: 12px;">:</td>
                    <td>
                        <span class="editable-field px-1" contenteditable="true">905/UM/SMK-LBFU/PENARIKAN/{{ date('Y') }}</span>
                    </td>
                </tr>
                <tr>
                    <td>Lampiran</td>
                    <td>:</td>
                    <td>
                        <span class="editable-field px-1" contenteditable="true">-</span>
                    </td>
                </tr>
                <tr>
                    <td>Hal</td>
                    <td>:</td>
                    <td>
                        <strong class="editable-field px-1" contenteditable="true">Penarikan Siswa Praktik Kerja Lapangan (PKL)</strong>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-5 text-end">
            <span class="editable-field px-1" contenteditable="true">Pekanbaru, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
        </div>
    </div>

    <!-- Tujuan Surat -->
    <div class="mb-3" style="font-size: 14px; line-height: 1.45;">
        <p class="mb-0">Kepada Yth,</p>
        <p class="fw-bold mb-0">
            <span class="editable-field px-1" contenteditable="true">Pimpinan / HRD {{ $penempatan->perusahaan?->nama_perusahaan }}</span>
        </p>
        <p class="mb-0">
            <span class="editable-field px-1" contenteditable="true">{{ $penempatan->perusahaan?->alamat ?: 'Di Tempat' }}</span>
        </p>
    </div>

    <!-- Isi Surat -->
    <div style="font-size: 14px; line-height: 1.55; text-align: justify;">
        <p class="mb-2">Dengan hormat,</p>
        <p class="editable-field mb-2" contenteditable="true">
            Sehubungan dengan telah berakhirnya masa pelaksanaan kegiatan Praktik Kerja Lapangan (PKL) bagi siswa/siswi <strong>SMK Labor Binaan FKIP UNRI Pekanbaru</strong> Tahun Ajaran {{ $penempatan->periodePkl?->tahun_ajaran ?? date('Y').'/'.(date('Y')+1) }}, maka dengan ini kami bermaksud untuk <strong>menarik kembali</strong> peserta didik kami:
        </p>

        <!-- Tabel Peserta Didik -->
        <table style="width: 100%; border-collapse: collapse; margin: 10px 0; font-size: 13px; border: 1px solid #000;">
            <thead>
                <tr style="background-color: #f8fafc; border-bottom: 1px solid #000;">
                    <th style="width: 35px; text-align: center; vertical-align: middle; padding: 6px 4px; border: 1px solid #000; font-weight: bold;">No</th>
                    <th style="width: 32%; vertical-align: middle; padding: 6px 8px; border: 1px solid #000; font-weight: bold;">Nama Peserta Didik</th>
                    <th style="width: 20%; text-align: center; vertical-align: middle; padding: 6px 8px; border: 1px solid #000; font-weight: bold; white-space: nowrap;">NIS / NISN</th>
                    <th style="width: 30%; vertical-align: middle; padding: 6px 8px; border: 1px solid #000; font-weight: bold;">Konsentrasi Keahlian</th>
                    <th style="width: 15%; text-align: center; vertical-align: middle; padding: 6px 8px; border: 1px solid #000; font-weight: bold; white-space: nowrap;">Kelas</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center; vertical-align: middle; padding: 6px 4px; border: 1px solid #000;">1</td>
                    <td style="vertical-align: middle; padding: 6px 8px; border: 1px solid #000; font-weight: bold;">
                        <span class="editable-field px-1" contenteditable="true">{{ $penempatan->siswa?->nama }}</span>
                    </td>
                    <td style="text-align: center; vertical-align: middle; padding: 6px 8px; border: 1px solid #000; white-space: nowrap;">
                        <span class="editable-field px-1" contenteditable="true">{{ $penempatan->siswa?->nis }} / {{ $penempatan->siswa?->nisn ?: '-' }}</span>
                    </td>
                    <td style="vertical-align: middle; padding: 6px 8px; border: 1px solid #000;">
                        <span class="editable-field px-1" contenteditable="true">{{ $penempatan->siswa?->jurusan?->nama_jurusan }}</span>
                    </td>
                    <td style="text-align: center; vertical-align: middle; padding: 6px 8px; border: 1px solid #000; white-space: nowrap;">
                        <span class="editable-field px-1" contenteditable="true">{{ $penempatan->siswa?->kelas?->nama_kelas }}</span>
                    </td>
                </tr>
            </tbody>
        </table>

        <p class="editable-field mb-2 mt-3" contenteditable="true">
            Atas nama keluarga besar <strong>SMK Labor Binaan FKIP UNRI Pekanbaru</strong>, kami mengucapkan terima kasih dan apresiasi yang setinggi-tingginya kepada segenap pimpinan, staf, dan pembimbing industri atas bimbingan, arahan, serta fasilitas yang telah diberikan kepada siswa kami selama melaksanakan PKL.
        </p>

        <p class="editable-field mb-2" contenteditable="true">
            Apabila selama proses kegiatan PKL berlangsung terdapat tutur kata atau tingkah laku siswa kami yang kurang berkenan, kami memohon maaf yang sebesar-besarnya. Kami berharap kerja sama yang baik antara sekolah dan industri ini dapat terus terjalin secara berkelanjutan di masa mendatang.
        </p>

        <p class="editable-field mb-0" contenteditable="true">
            Demikian surat penarikan ini kami sampaikan. Atas perhatian dan kerja sama yang sangat berharga ini, kami haturkan terima kasih.
        </p>
    </div>

    <!-- Tanda Tangan -->
    @php
        $namaKepsek = \App\Models\Setting::get('pejabat_kepala_sekolah', 'JEFFRI HUNTER, M.Pd');
        $nipKepsek = \App\Models\Setting::get('pejabat_nip_kepala_sekolah', '-');
        $kotaTerbit = \App\Models\Setting::get('sekolah_kota_terbit', 'Pekanbaru');
    @endphp
    <div class="row mt-4" style="font-size: 14px;">
        <div class="col-7"></div>
        <div class="col-5 text-center">
            <p class="mb-1">
                <span class="editable-field px-1" contenteditable="true">{{ $kotaTerbit }}, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
            </p>
            <p class="mb-0">
                <span class="editable-field px-1" contenteditable="true">Kepala Sekolah,</span>
            </p>
            <div style="height: 60px;"></div>
            <p class="fw-bold text-decoration-underline mb-0">
                <span class="editable-field px-1" contenteditable="true">{{ $namaKepsek }}</span>
            </p>
            @if($nipKepsek && $nipKepsek !== '-')
                <p class="small text-muted mb-0">
                    <span class="editable-field px-1" contenteditable="true">NIP. {{ $nipKepsek }}</span>
                </p>
            @endif
        </div>
    </div>
</div>


<script>
    let pakaiKop = true;
    function toggleKop() {
        pakaiKop = !pakaiKop;
        const btn = document.getElementById('toggleKopBtn');
        if (pakaiKop) {
            document.body.classList.remove('tanpa-kop');
            btn.innerHTML = '<i class="ph ph-file-text me-1"></i> Mode: Dengan Kop Digital';
            btn.className = 'btn btn-outline-primary';
        } else {
            document.body.classList.add('tanpa-kop');
            btn.innerHTML = '<i class="ph ph-file-dashed me-1"></i> Mode: Tanpa Kop (Top 4 cm)';
            btn.className = 'btn btn-warning';
        }
    }
</script>

</body>
</html>
