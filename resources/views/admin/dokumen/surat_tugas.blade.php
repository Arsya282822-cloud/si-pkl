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

        /* Opsi Jika Cetak di Kertas Blangko Ber-Kop (Top 4cm) */
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
        <button type="button" id="toggleKopBtn" onclick="toggleKop()" class="btn btn-outline-primary" title="Alihkan jika menggunakan kertas blangko yang sudah ada kop cetak">
            <i class="ph ph-file-text me-1"></i> Mode: Dengan Kop Digital
        </button>
        <button onclick="location.reload()" class="btn btn-outline-secondary" title="Kembalikan teks awal">
            <i class="ph ph-arrow-counter-clockwise me-1"></i> Reset Teks
        </button>
        <button onclick="window.print()" class="btn btn-primary">
            <i class="ph ph-printer me-1"></i> Cetak / Simpan PDF
        </button>
    </div>
</div>

<div class="page">
    <!-- Notice Bar (Hidden on Print) -->
    <div class="edit-notice alert alert-info py-2 px-3 mb-2 d-flex align-items-center justify-content-between" style="font-size: 13px; border-radius: 8px;">
        <div>
            <i class="ph ph-pencil-simple me-1 fw-bold"></i>
            <strong>Margin:</strong> Atas 4 cm | Kiri 2.54 cm | Kanan 2.25 cm | Bawah 0.75 cm. Teks dapat langsung diklik & diedit.
        </div>
    </div>

    <!-- Kop Surat Resmi -->
    @include('admin.dokumen._kop')

    <!-- Judul Surat Tugas (Editable) -->
    <div class="text-center mb-4">
        <h5 class="fw-bold text-decoration-underline mb-1" style="letter-spacing: 0.5px;">
            <span class="editable-field px-1" contenteditable="true">SURAT PERINTAH TUGAS</span>
        </h5>
        <p style="font-size: 14px;" class="mb-0">
            Nomor: <span class="editable-field px-1" contenteditable="true">800/ST-PKL/SMK-LBFU/{{ \Carbon\Carbon::now()->format('m/Y') }}</span>
        </p>
    </div>

    <!-- Isi Surat Tugas (Editable) -->
    <div style="font-size: 14px; line-height: 1.55; text-align: justify;">
        <p class="mb-2">Kepala SMK Labor Binaan FKIP UNRI Pekanbaru dengan ini menugaskan kepada:</p>

        <table class="my-2 ms-3" style="font-size: 14px;">
            <tr>
                <td style="width: 150px;">Nama</td>
                <td style="width: 15px;">:</td>
                <td class="fw-bold">
                    <span class="editable-field px-1" contenteditable="true">{{ $guru->nama }}</span>
                </td>
            </tr>
            <tr>
                <td>NIP / NUPTK</td>
                <td>:</td>
                <td>
                    <span class="editable-field px-1" contenteditable="true">{{ $guru->nip ?: '-' }}</span>
                </td>
            </tr>
            <tr>
                <td>Jabatan / Tugas</td>
                <td>:</td>
                <td>
                    <span class="editable-field px-1" contenteditable="true">Guru Pembimbing Praktik Kerja Lapangan (PKL)</span>
                </td>
            </tr>
        </table>

        <p class="editable-field mt-3 mb-2" contenteditable="true">
            Untuk melaksanakan tugas pembimbingan, monitoring kunjungan ke DUDI, validasi jurnal kegiatan harian, serta evaluasi/penilaian bagi peserta didik SMK Labor Binaan FKIP UNRI Pekanbaru yang melaksanakan PKL pada:
        </p>

        <!-- Tabel Bimbingan (Editable) -->
        <table style="width: 100%; border-collapse: collapse; margin: 10px 0; font-size: 13px; border: 1px solid #000;">
            <thead>
                <tr style="background-color: #f8fafc; border-bottom: 1px solid #000;">
                    <th style="width: 35px; text-align: center; vertical-align: middle; padding: 6px 4px; border: 1px solid #000; font-weight: bold;">No</th>
                    <th style="width: 34%; vertical-align: middle; padding: 6px 8px; border: 1px solid #000; font-weight: bold;">Nama Peserta Didik</th>
                    <th style="width: 20%; text-align: center; vertical-align: middle; padding: 6px 8px; border: 1px solid #000; font-weight: bold; white-space: nowrap;">Kelas / Jurusan</th>
                    <th style="vertical-align: middle; padding: 6px 8px; border: 1px solid #000; font-weight: bold;">Tempat PKL (DUDI)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bimbinganList as $i => $item)
                    <tr>
                        <td style="text-align: center; vertical-align: middle; padding: 6px 4px; border: 1px solid #000;">{{ $i + 1 }}</td>
                        <td style="vertical-align: middle; padding: 6px 8px; border: 1px solid #000; font-weight: bold;">
                            <span class="editable-field px-1" contenteditable="true">{{ $item->siswa?->nama }}</span>
                        </td>
                        <td style="text-align: center; vertical-align: middle; padding: 6px 8px; border: 1px solid #000; white-space: nowrap;">
                            <span class="editable-field px-1" contenteditable="true">{{ $item->siswa?->kelas?->nama_kelas }} ({{ $item->siswa?->jurusan?->kode_jurusan ?: $item->siswa?->jurusan?->nama_jurusan }})</span>
                        </td>
                        <td style="vertical-align: middle; padding: 6px 8px; border: 1px solid #000;">
                            <span class="editable-field px-1" contenteditable="true">{{ $item->perusahaan?->nama_perusahaan }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 12px; border: 1px solid #000; color: #64748b;">Belum ada data siswa bimbingan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <p class="editable-field mt-3 mb-0" contenteditable="true">
            Demikian surat perintah tugas ini dibuat agar dapat dilaksanakan dengan sebaik-baiknya dan penuh rasa tanggung jawab.
        </p>
    </div>

    <!-- Tanda Tangan (Editable) -->
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
