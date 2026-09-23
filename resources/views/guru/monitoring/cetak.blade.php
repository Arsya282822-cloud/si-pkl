<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita Acara Monitoring - {{ $monitoring->perusahaan?->nama_perusahaan }}</title>
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
            /* Margins Sesuai Standar: Top 0.7cm, Left 2.54cm, Right 2.25cm, Bottom 0.75cm */
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
        <a href="{{ route('guru.monitoring.excel') }}" class="btn btn-success">
            <i class="ph ph-file-xls me-1"></i> Download Excel (.xlsx)
        </a>
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
            <strong>Margin:</strong> Atas 0.7 cm | Kiri 2.54 cm | Kanan 2.25 cm | Bawah 0.75 cm. Teks dapat diedit langsung.
        </div>
    </div>

    <!-- Kop Surat Resmi -->
    @include('admin.dokumen._kop')

    <!-- Judul Dokumen -->
    <div class="text-center my-3">
        <h5 class="fw-bold text-decoration-underline mb-1" style="letter-spacing: 0.5px;">
            <span class="editable-field px-1" contenteditable="true">BERITA ACARA & LEMBAR KUNJUNGAN MONITORING PKL</span>
        </h5>
        <p style="font-size: 13.5px;" class="mb-0">
            Nomor: <span class="editable-field px-1" contenteditable="true">800/BA-MON/SMK-LBFU/{{ \Carbon\Carbon::parse($monitoring->tanggal_kunjungan)->format('m/Y') }}</span>
        </p>
    </div>

    <!-- Informasi Kunjungan -->
    <div style="font-size: 13.5px; line-height: 1.5; margin-bottom: 12px;">
        <p class="mb-2">Pada hari ini, tanggal <strong class="editable-field px-1" contenteditable="true">{{ \Carbon\Carbon::parse($monitoring->tanggal_kunjungan)->translatedFormat('l, d F Y') }}</strong>, telah dilaksanakan kegiatan monitoring / supervisi Praktik Kerja Lapangan (PKL) oleh Guru Pembimbing SMK Labor Binaan FKIP UNRI Pekanbaru dengan rincian data sebagai berikut:</p>

        <table style="width: 100%; margin-left: 10px; font-size: 13.5px;">
            <tr>
                <td style="width: 180px;">Nama Guru Pembimbing</td>
                <td style="width: 12px;">:</td>
                <td class="fw-bold">
                    <span class="editable-field px-1" contenteditable="true">{{ $monitoring->guru?->nama }}</span>
                </td>
            </tr>
            <tr>
                <td>NIP / NUPTK</td>
                <td>:</td>
                <td>
                    <span class="editable-field px-1" contenteditable="true">{{ $monitoring->guru?->nip ?: '-' }}</span>
                </td>
            </tr>
            <tr>
                <td>Nama Tempat PKL (DUDI)</td>
                <td>:</td>
                <td class="fw-bold">
                    <span class="editable-field px-1" contenteditable="true">{{ $monitoring->perusahaan?->nama_perusahaan }}</span>
                </td>
            </tr>
            <tr>
                <td>Alamat Perusahaan</td>
                <td>:</td>
                <td>
                    <span class="editable-field px-1" contenteditable="true">{{ $monitoring->perusahaan?->alamat ?: 'Pekanbaru, Riau' }}</span>
                </td>
            </tr>
            <tr>
                <td>Pembimbing Industri</td>
                <td>:</td>
                <td>
                    <span class="editable-field px-1" contenteditable="true">{{ $monitoring->perusahaan?->pembimbing_industri ?: 'Pembimbing Lapangan / HRD' }}</span>
                </td>
            </tr>
        </table>
    </div>

    <!-- Tabel Siswa yang Dimonitor -->
    <div style="font-size: 13px; margin-top: 15px;">
        <strong style="font-size: 13.5px;">A. Daftar Peserta Didik yang Dimonitor:</strong>
        <table style="width: 100%; border-collapse: collapse; margin-top: 6px; font-size: 12.5px; border: 1px solid #000;">
            <thead>
                <tr style="background-color: #f8fafc; border-bottom: 1px solid #000;">
                    <th style="width: 35px; text-align: center; padding: 6px 4px; border: 1px solid #000;">No</th>
                    <th style="width: 35%; padding: 6px 8px; border: 1px solid #000;">Nama Peserta Didik</th>
                    <th style="width: 25%; text-align: center; padding: 6px 8px; border: 1px solid #000;">NIS / Kelas</th>
                    <th style="padding: 6px 8px; border: 1px solid #000;">Konsentrasi Keahlian</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswaList as $i => $item)
                    <tr>
                        <td style="text-align: center; padding: 5px 4px; border: 1px solid #000;">{{ $i + 1 }}</td>
                        <td style="padding: 5px 8px; border: 1px solid #000; font-weight: bold;">
                            <span class="editable-field px-1" contenteditable="true">{{ $item->siswa?->nama }}</span>
                        </td>
                        <td style="text-align: center; padding: 5px 8px; border: 1px solid #000;">
                            <span class="editable-field px-1" contenteditable="true">{{ $item->siswa?->nis }} / {{ $item->siswa?->kelas?->nama_kelas }}</span>
                        </td>
                        <td style="padding: 5px 8px; border: 1px solid #000;">
                            <span class="editable-field px-1" contenteditable="true">{{ $item->siswa?->jurusan?->nama_jurusan }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td style="text-align: center; padding: 5px 4px; border: 1px solid #000;">1</td>
                        <td style="padding: 5px 8px; border: 1px solid #000; font-weight: bold;"><span class="editable-field px-1" contenteditable="true">Peserta Didik PKL</span></td>
                        <td style="text-align: center; padding: 5px 8px; border: 1px solid #000;"><span class="editable-field px-1" contenteditable="true">-</span></td>
                        <td style="padding: 5px 8px; border: 1px solid #000;"><span class="editable-field px-1" contenteditable="true">-</span></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Catatan & Hasil Evaluasi Monitoring -->
    <div style="font-size: 13px; margin-top: 15px;">
        <strong style="font-size: 13.5px;">B. Catatan Perkembangan, Kedisiplinan & Evaluasi Lapangan:</strong>
        <div class="p-2 border mt-1 editable-field" contenteditable="true" style="min-height: 80px; text-align: justify; font-size: 13px; border-color: #000 !important;">
            {{ $monitoring->catatan ?: 'Peserta didik menjalankan kegiatan praktik kerja lapangan dengan tertib, disiplin, dan mampu beradaptasi dengan lingkungan kerja industri secara baik.' }}
        </div>
    </div>

    <!-- Foto Dokumentasi (Jika Ada) -->
    @if($monitoring->foto)
        <div style="font-size: 13px; margin-top: 12px;" class="text-center">
            <strong style="font-size: 13px; display: block; margin-bottom: 4px; text-align: left;">C. Dokumentasi Kunjungan:</strong>
            <img src="{{ asset('storage/' . $monitoring->foto) }}" alt="Foto Monitoring" style="max-height: 140px; border: 1px solid #cbd5e1; border-radius: 6px; padding: 2px;">
        </div>
    @endif

    <!-- Tanda Tangan -->
    <table style="width: 100%; border-collapse: collapse; font-size: 13px; text-align: center; margin-top: 25px;">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <p class="mb-1">Mengetahui,</p>
                <p class="fw-bold mb-0">Pembimbing Industri (DUDI)</p>
                <div style="height: 60px;"></div>
                <p class="fw-bold text-decoration-underline mb-0">
                    <span class="editable-field px-1" contenteditable="true">{{ $monitoring->perusahaan?->pembimbing_industri ?: 'Pembimbing Industri' }}</span>
                </p>
                <small class="text-muted">{{ $monitoring->perusahaan?->nama_perusahaan }}</small>
            </td>
            <td style="width: 50%; vertical-align: top;">
                <p class="mb-1">Pekanbaru, <span class="editable-field px-1" contenteditable="true">{{ \Carbon\Carbon::parse($monitoring->tanggal_kunjungan)->translatedFormat('d F Y') }}</span></p>
                <p class="fw-bold mb-0">Guru Pembimbing Sekolah</p>
                <div style="height: 60px;"></div>
                <p class="fw-bold text-decoration-underline mb-0">
                    <span class="editable-field px-1" contenteditable="true">{{ $monitoring->guru?->nama }}</span>
                </p>
                <small class="text-muted">NIP. {{ $monitoring->guru?->nip ?: '-' }}</small>
            </td>
        </tr>
    </table>
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
