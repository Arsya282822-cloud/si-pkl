<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Capaian Kompetensi PKL - {{ $penempatan->siswa->nama ?? 'Siswa' }}</title>
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
            font-size: 11pt;
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
            margin-bottom: 12px;
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
            font-size: 14pt;
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
            margin: 12px 0 4px 0;
            text-decoration: underline;
        }
        .subtitle-doc {
            text-align: center;
            font-size: 10pt;
            font-family: 'Inter', sans-serif;
            margin-bottom: 16px;
        }
        .table-identitas {
            width: 100%;
            font-size: 10pt;
            margin-bottom: 14px;
        }
        .table-identitas td {
            padding: 3px 4px;
            vertical-align: top;
        }
        .table-nilai {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
            margin-bottom: 14px;
        }
        .table-nilai th, .table-nilai td {
            border: 1px solid #000;
            padding: 5px 8px;
        }
        .table-nilai th {
            background-color: #f8fafc;
            text-align: center;
            font-weight: bold;
        }
        .signature-table {
            width: 100%;
            margin-top: 20px;
            font-size: 10pt;
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

    <!-- Tombol Cetak Melayang -->
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
                    <div class="kop-instansi">{{ $settings['nama_yayasan'] ?? 'YAYASAN PENDIDIKAN UNIVERSITAS RIAU' }}</div>
                    <div class="kop-sekolah">{{ $settings['nama_sekolah'] ?? 'SMK LABOR FKIP UNRI PEKANBARU' }}</div>
                    <div class="kop-alamat">
                        {{ $settings['alamat_sekolah'] ?? 'Jl. Air Dingin No. 12 Kec. Bukit Raya, Kota Pekanbaru, Riau' }}<br>
                        Telp: {{ $settings['telepon_sekolah'] ?? '(0761) 674500' }} | Email: {{ $settings['email_sekolah'] ?? 'smklabor.unri@gmail.com' }} | Website: {{ $settings['website_sekolah'] ?? 'www.smklabor.sch.id' }}
                    </div>
                </td>
                <td style="width: 75px; text-align: center;">
                    <img src="{{ asset('images/logo-smk-labor.png') }}" onerror="this.src='{{ asset('images/logo-sipkl.jpg') }}'" alt="Logo Sekolah">
                </td>
            </tr>
        </table>

        <!-- JUDUL -->
        <div class="title-doc">RAPOR CAPAIAN HASIL PRAKTIK KERJA LAPANGAN (PKL)</div>
        <div class="subtitle-doc">Tahun Ajaran {{ $penempatan->periodePkl->tahun_ajaran ?? '2026/2027' }}</div>

        <!-- IDENTITAS SISWA & MITRA -->
        <table class="table-identitas">
            <tr>
                <td style="width: 140px; font-weight: bold;">Nama Siswa</td>
                <td style="width: 10px;">:</td>
                <td style="width: 280px; font-weight: bold;">{{ strtoupper($penempatan->siswa->nama ?? '-') }}</td>
                <td style="width: 130px; font-weight: bold;">Mitra Industri / DUDI</td>
                <td style="width: 10px;">:</td>
                <td>{{ $penempatan->perusahaan->nama_perusahaan ?? '-' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">NIS / NISN</td>
                <td>:</td>
                <td>{{ $penempatan->siswa->nis ?? '-' }}</td>
                <td style="font-weight: bold;">Pembimbing Industri</td>
                <td>:</td>
                <td>{{ $penempatan->perusahaan->pembimbing_industri ?? 'Instruktur Mitra' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Kelas / Jurusan</td>
                <td>:</td>
                <td>{{ $penempatan->siswa->kelas->nama_kelas ?? '-' }} ({{ $penempatan->siswa->jurusan->nama_jurusan ?? '-' }})</td>
                <td style="font-weight: bold;">Guru Pembimbing</td>
                <td>:</td>
                <td>{{ $penempatan->guru->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Periode Magang</td>
                <td>:</td>
                <td>{{ date('d M Y', strtotime($penempatan->periodePkl->tanggal_mulai ?? now())) }} s.d {{ date('d M Y', strtotime($penempatan->periodePkl->tanggal_selesai ?? now())) }}</td>
                <td style="font-weight: bold;">Alamat Industri</td>
                <td>:</td>
                <td>{{ $penempatan->perusahaan->alamat ?? '-' }}</td>
            </tr>
        </table>

        <!-- TABEL NILAI -->
        <table class="table-nilai">
            <thead>
                <tr>
                    <th style="width: 35px;">No</th>
                    <th>Komponen Penilaian & Aspek Capaian</th>
                    <th style="width: 90px;">Skor Angka (0-100)</th>
                    <th style="width: 110px;">Predikat</th>
                </tr>
            </thead>
            <tbody>
                <!-- ASPEK NON TEKNIS -->
                <tr style="background-color: #fdfdfd;">
                    <td colspan="4" class="fw-bold ps-2">A. Aspek Sikap & Kepribadian Kerja (Soft Skills)</td>
                </tr>
                <tr>
                    <td class="text-center">1</td>
                    <td>Integritas, Disiplin, dan Kehadiran di Industri</td>
                    <td class="text-center fw-semibold">{{ $penempatan->penilaian->nilai_sikap ?? '-' }}</td>
                    <td class="text-center">
                        @php
                            $ns = $penempatan->penilaian->nilai_sikap ?? 0;
                            echo $ns >= 90 ? 'Sangat Baik (A)' : ($ns >= 80 ? 'Baik (B)' : ($ns >= 70 ? 'Cukup (C)' : '-'));
                        @endphp
                    </td>
                </tr>
                <tr>
                    <td class="text-center">2</td>
                    <td>Kerjasama Tim, Komunikasi, dan Tanggung Jawab Kerja</td>
                    <td class="text-center fw-semibold">{{ $penempatan->penilaian->nilai_pengetahuan ?? '-' }}</td>
                    <td class="text-center">
                        @php
                            $np = $penempatan->penilaian->nilai_pengetahuan ?? 0;
                            echo $np >= 90 ? 'Sangat Baik (A)' : ($np >= 80 ? 'Baik (B)' : ($np >= 70 ? 'Cukup (C)' : '-'));
                        @endphp
                    </td>
                </tr>

                <!-- ASPEK TEKNIS -->
                <tr style="background-color: #fdfdfd;">
                    <td colspan="4" class="fw-bold ps-2">B. Aspek Keterampilan & Kompetensi Keahlian (Hard Skills)</td>
                </tr>
                <tr>
                    <td class="text-center">3</td>
                    <td>Penguasaan Teknis Bidang Keahlian {{ $penempatan->siswa->jurusan->nama_jurusan ?? '' }}</td>
                    <td class="text-center fw-semibold">{{ $penempatan->penilaian->nilai_keterampilan ?? '-' }}</td>
                    <td class="text-center">
                        @php
                            $nk = $penempatan->penilaian->nilai_keterampilan ?? 0;
                            echo $nk >= 90 ? 'Sangat Kompeten' : ($nk >= 80 ? 'Kompeten' : ($nk >= 70 ? 'Cukup Kompeten' : '-'));
                        @endphp
                    </td>
                </tr>
                <tr>
                    <td class="text-center">4</td>
                    <td>Kreativitas, Inisiatif, dan Kualitas Penyelesaian Tugas</td>
                    <td class="text-center fw-semibold">{{ $penempatan->penilaian->nilai_akhir ?? '-' }}</td>
                    <td class="text-center">
                        @php
                            $na = $penempatan->penilaian->nilai_akhir ?? 0;
                            echo $na >= 90 ? 'Sangat Baik (A)' : ($na >= 80 ? 'Baik (B)' : ($na >= 70 ? 'Cukup (C)' : '-'));
                        @endphp
                    </td>
                </tr>

                <!-- NILAI RATA-RATA AKHIR -->
                @php
                    $nilaiAkhirRata = 0;
                    if ($penempatan->penilaian) {
                        $p = $penempatan->penilaian;
                        $nilaiAkhirRata = round(($p->nilai_sikap + $p->nilai_pengetahuan + $p->nilai_keterampilan + $p->nilai_akhir) / 4, 1);
                    }
                @endphp
                <tr style="background-color: #f1f5f9; font-weight: bold;">
                    <td colspan="2" class="text-center">NILAI AKHIR RATA-RATA KOMULATIF</td>
                    <td class="text-center font-monospace fs-6">{{ $nilaiAkhirRata > 0 ? $nilaiAkhirRata : '-' }}</td>
                    <td class="text-center">
                        @if($nilaiAkhirRata >= 85)
                            <span class="text-success">LULUS (MEMUASKAN)</span>
                        @elseif($nilaiAkhirRata >= 75)
                            <span class="text-primary">LULUS (BAIK)</span>
                        @elseif($nilaiAkhirRata > 0)
                            <span class="text-warning">LULUS (CUKUP)</span>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- REKAPITULASI KEHADIRAN & JURNAL -->
        <table style="width: 100%; margin-bottom: 14px; font-size: 9.5pt;">
            <tr>
                <td style="width: 50%; vertical-align: top; padding-right: 8px;">
                    <table class="table-nilai" style="margin-bottom: 0;">
                        <thead>
                            <tr>
                                <th colspan="2">Rekapitulasi Presensi / Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>Hadir Kerja</td><td class="text-center fw-bold">{{ $rekapAbsensi['hadir'] }} Hari</td></tr>
                            <tr><td>Sakit (Surat Dokter)</td><td class="text-center">{{ $rekapAbsensi['sakit'] }} Hari</td></tr>
                            <tr><td>Izin Resmi</td><td class="text-center">{{ $rekapAbsensi['izin'] }} Hari</td></tr>
                            <tr><td>Tanpa Keterangan (Alfa)</td><td class="text-center">{{ $rekapAbsensi['alfa'] }} Hari</td></tr>
                        </tbody>
                    </table>
                </td>
                <td style="width: 50%; vertical-align: top; padding-left: 8px;">
                    <table class="table-nilai" style="margin-bottom: 0;">
                        <thead>
                            <tr>
                                <th colspan="2">Catatan Jurnal Kegiatan PKL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>Total Jurnal Terinput</td><td class="text-center fw-bold">{{ $totalJurnal }} Aktivitas</td></tr>
                            <tr><td>Jurnal Telah Divalidasi</td><td class="text-center text-success fw-bold">{{ $jurnalDisetujui }} Disetujui</td></tr>
                            <tr>
                                <td colspan="2" style="font-size: 8.5pt; font-style: italic; padding: 6px;">
                                    "{{ $penempatan->penilaian->catatan_guru ?? 'Peserta telah melaksanakan seluruh kewajiban Praktik Kerja Lapangan dengan disiplin dan etos kerja yang memuaskan.' }}"
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>

        <!-- TANDA TANGAN & QR CODE -->
        <table class="signature-table">
            <tr>
                <td>
                    Mengetahui / Menyetujui,<br>
                    <strong>Pembimbing Industri (DUDI)</strong><br>
                    {{ $penempatan->perusahaan->nama_perusahaan ?? '-' }}
                    <div style="height: 55px;"></div>
                    <strong><u>{{ $penempatan->perusahaan->pembimbing_industri ?? 'Pembimbing Lapangan' }}</u></strong><br>
                    <span style="font-size: 8.5pt; color: #555;">NIP / ID Pegawai: -</span>
                </td>
                <td>
                    Pekanbaru, {{ date('d F Y') }}<br>
                    <strong>Guru Pembimbing Sekolah</strong><br>
                    {{ $settings['nama_sekolah'] ?? 'SMK Labor FKIP UNRI' }}
                    <div style="height: 55px;"></div>
                    <strong><u>{{ $penempatan->guru->nama ?? 'Guru Pembimbing' }}</u></strong><br>
                    <span style="font-size: 8.5pt; color: #555;">NIP: {{ $penempatan->guru->nip ?? '-' }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding-top: 18px; text-align: center;">
                    Mengetahui,<br>
                    <strong>Kepala Sekolah {{ $settings['nama_sekolah'] ?? 'SMK Labor FKIP UNRI' }}</strong>
                    <div style="height: 50px;"></div>
                    <strong><u>{{ $settings['nama_kepala_sekolah'] ?? 'Drs. H. Hendripides, M.Si.' }}</u></strong><br>
                    <span style="font-size: 8.5pt; color: #555;">NIP: {{ $settings['nip_kepala_sekolah'] ?? '19680512 199403 1 004' }}</span>
                </td>
            </tr>
        </table>

        <!-- FOOTER VERIFIKASI QR -->
        <div style="margin-top: 15px; border-top: 1px dashed #aaa; padding-top: 6px; display: flex; justify-content: space-between; align-items: center; font-size: 8pt; font-family: 'Inter', sans-serif; color: #666;">
            <div>
                Dokumen ini sah dan diterbitkan secara resmi melalui <strong>SI-PKL SMK Labor Binaan FKIP UNRI</strong>.
            </div>
            <div>
                Verifikasi: <a href="{{ route('verifikasi.sertifikat', $penempatan->id) }}" target="_blank" style="color: #0284c7; text-decoration: none;">{{ url('/verifikasi/'.$penempatan->id) }}</a>
            </div>
        </div>
    </div>

</body>
</html>
