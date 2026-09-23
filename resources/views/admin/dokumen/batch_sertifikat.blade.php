<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Masal Sertifikat PKL - {{ $kelas?->nama_kelas ?? ($jurusan?->nama_jurusan ?? 'Semua') }}</title>
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
            margin: 0 auto 20px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #ffffff;
            padding: 12px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
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

    <!-- Top Action Bar -->
    <div class="action-bar">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.dokumen.index', ['tab' => 'sertifikat']) }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
                <i class="ph ph-arrow-left"></i> Kembali ke Dokumen Hub
            </a>
            <span class="fw-bold" style="font-size: 0.95rem;">
                <i class="ph ph-certificate text-warning me-1"></i> Cetak Masal E-Sertifikat (Total: {{ count($penempatanList) }} Siswa)
            </span>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-2 px-3 fw-semibold">
                <i class="ph ph-printer" style="font-size: 18px;"></i> Cetak / Simpan PDF Seluruh Rombel
            </button>
        </div>
    </div>

    @if(count($penempatanList) === 0)
        <div class="cert-page d-flex align-items-center justify-content-center">
            <div class="text-center text-muted">
                <i class="ph ph-empty mb-2" style="font-size: 48px;"></i>
                <h5>Tidak ada data siswa ditemukan untuk kriteria ini.</h5>
                <p>Silakan kembali dan pilih filter kelas/jurusan lain.</p>
            </div>
        </div>
    @endif

    @foreach($penempatanList as $idx => $penempatan)
        @php
            $nilai = $penempatan->penilaian;
            $nilaiSikap = $nilai?->nilai_sikap ?? 85;
            $nilaiPengetahuan = $nilai?->nilai_pengetahuan ?? 88;
            $nilaiKeterampilan = $nilai?->nilai_keterampilan ?? 87;
            $nilaiAkhir = $nilai?->nilai_akhir ?? round(($nilaiSikap + $nilaiPengetahuan + $nilaiKeterampilan) / 3, 1);
            
            $predikat = 'Sangat Baik (A)';
            if ($nilaiAkhir < 75) $predikat = 'Cukup (C)';
            elseif ($nilaiAkhir < 85) $predikat = 'Baik (B)';
            
            $tglMulai = $penempatan->periodePkl?->tanggal_mulai ? \Carbon\Carbon::parse($penempatan->periodePkl->tanggal_mulai)->translatedFormat('d F Y') : '15 Juli 2025';
            $tglSelesai = $penempatan->periodePkl?->tanggal_selesai ? \Carbon\Carbon::parse($penempatan->periodePkl->tanggal_selesai)->translatedFormat('d F Y') : '15 Oktober 2025';
            $tglSertifikat = $penempatan->periodePkl?->tanggal_selesai ? \Carbon\Carbon::parse($penempatan->periodePkl->tanggal_selesai)->translatedFormat('d F Y') : \Carbon\Carbon::now()->translatedFormat('d F Y');
        @endphp

        <!-- ================= HALAMAN 1 (DEPAN: SERTIFIKAT KELULUSAN) ================= -->
        <div class="cert-page">
            <div class="cert-border">
                <div class="cert-corner cert-corner-tl"></div>
                <div class="cert-corner cert-corner-tr"></div>
                <div class="cert-corner cert-corner-bl"></div>
                <div class="cert-corner cert-corner-br"></div>

                <!-- Header Logos & School Identity -->
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <img src="{{ asset('images/logo-yayasan-unri.png') }}" style="height: 48px; object-fit: contain;" alt="Yayasan UNRI">
                    <div class="text-center">
                        <div style="font-family: 'Cinzel', serif; font-size: 13pt; font-weight: 700; letter-spacing: 1px; color: #0c4a6e;">
                            SMK LABOR BINAAN FKIP UNRI PEKANBARU
                        </div>
                        <div style="font-size: 8pt; color: #64748b; letter-spacing: 0.5px;">
                            TERAKREDITASI "A" (UNGGUL) &bull; NPSN: 10403993
                        </div>
                    </div>
                    <img src="{{ asset('images/logo-smk-labor.png') }}" style="height: 48px; object-fit: contain;" alt="SMK Labor">
                </div>

                <div class="text-center my-2">
                    <div style="font-family: 'Cinzel', serif; font-size: 20pt; font-weight: 800; color: #b45309; letter-spacing: 3px;">
                        SERTIFIKAT
                    </div>
                    <div style="font-size: 9pt; font-weight: 600; color: #475569; letter-spacing: 1.5px; text-transform: uppercase;">
                        PRAKTIK KERJA LAPANGAN (PKL)
                    </div>
                    <div style="font-size: 8.5pt; color: #64748b;" class="editable-field" contenteditable="true">
                        Nomor: 421.5/SMK-LBFU/PKL/{{ date('Y') }}/{{ str_pad($penempatan->id, 4, '0', STR_PAD_LEFT) }}
                    </div>
                </div>

                <div class="text-center mb-1" style="font-size: 9.5pt; color: #334155;">
                    Kepala SMK Labor Binaan FKIP UNRI Pekanbaru menerangkan bahwa:
                </div>

                <div class="text-center my-2">
                    <div style="font-family: 'Montserrat', sans-serif; font-size: 16pt; font-weight: 700; color: #0f172a; border-bottom: 2px solid #b45309; display: inline-block; padding: 0 20px 2px 20px;" class="editable-field" contenteditable="true">
                        {{ strtoupper($penempatan->siswa?->nama ?? 'NAMA SISWA LENGKAP') }}
                    </div>
                    <div class="mt-1" style="font-size: 9pt; color: #475569;">
                        NIS: <span class="fw-semibold">{{ $penempatan->siswa?->nis ?? '-' }}</span> | NISN: <span class="fw-semibold">{{ $penempatan->siswa?->nisn ?? '-' }}</span> | Kelas: <span class="fw-semibold">{{ $penempatan->siswa?->kelas?->nama_kelas ?? '-' }}</span>
                    </div>
                    <div style="font-size: 9.5pt; font-weight: 600; color: #0284c7; margin-top: 2px;">
                        Program Keahlian: {{ $penempatan->siswa?->jurusan?->nama_jurusan ?? '-' }}
                    </div>
                </div>

                <div class="text-center mx-auto mb-3" style="max-width: 220mm; font-size: 9pt; line-height: 1.45; color: #334155;">
                    Telah melaksanakan kegiatan <strong>Praktik Kerja Lapangan (PKL)</strong> pada Dunia Usaha / Dunia Industri:
                    <div style="font-size: 11pt; font-weight: 700; color: #0c4a6e; margin: 3px 0;" class="editable-field" contenteditable="true">
                        {{ strtoupper($penempatan->perusahaan?->nama_perusahaan ?? 'NAMA MITRA PERUSAHAAN DUDI') }}
                    </div>
                    yang dilaksanakan mulai tanggal <strong>{{ $tglMulai }}</strong> s.d. <strong>{{ $tglSelesai }}</strong> dengan predikat kelulusan:
                    <div style="font-size: 11pt; font-weight: 800; color: #16a34a; margin-top: 2px;">
                        "{{ $predikat }}"
                    </div>
                </div>

                <!-- Signatures Block -->
                <div class="row align-items-end mt-2" style="font-size: 9pt;">
                    <div class="col-4 text-center">
                        <div>Mengetahui,</div>
                        <div class="fw-bold">Pimpinan / Pembimbing Industri</div>
                        <div class="fw-semibold text-muted" style="font-size: 8pt;">{{ $penempatan->perusahaan?->nama_perusahaan }}</div>
                        <div style="height: 48px;"></div>
                        <div class="fw-bold text-decoration-underline editable-field" contenteditable="true">
                            {{ $penempatan->perusahaan?->pembimbing_industri ?: 'Pimpinan Perusahaan' }}
                        </div>
                        <div style="font-size: 8pt; color: #64748b;">NIP/NIK. ........................................</div>
                    </div>

                    <div class="col-4 text-center">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{ urlencode(route('verifikasi.sertifikat', $penempatan)) }}" style="width: 65px; height: 65px; border: 1px solid #e2e8f0; padding: 2px; border-radius: 6px;" alt="QR Verification">
                        <div style="font-size: 7pt; color: #64748b; margin-top: 2px;">Validasi Digital SI-PKL</div>
                    </div>

                    @php
                        $namaKepsek = \App\Models\Setting::get('pejabat_kepala_sekolah', 'JEFFRI HUNTER, M.Pd');
                        $nipKepsek = \App\Models\Setting::get('pejabat_nip_kepala_sekolah', '-');
                        $kotaTerbit = \App\Models\Setting::get('sekolah_kota_terbit', 'Pekanbaru');
                    @endphp
                    <div class="col-4 text-center">
                        <div>{{ $kotaTerbit }}, <span class="editable-field" contenteditable="true">{{ $tglSertifikat }}</span></div>
                        <div class="fw-bold">Kepala Sekolah,</div>
                        <div class="fw-semibold text-muted" style="font-size: 8pt;">SMK Labor Binaan FKIP UNRI</div>
                        <div style="height: 48px;"></div>
                        <div class="fw-bold text-decoration-underline editable-field" contenteditable="true">
                            {{ $namaKepsek }}
                        </div>
                        @if($nipKepsek && $nipKepsek !== '-')
                            <div style="font-size: 8pt; color: #64748b;">NIP. {{ $nipKepsek }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>


        <!-- ================= HALAMAN 2 (BELAKANG: TRANSKRIP NILAI) ================= -->
        <div class="cert-page">
            <div class="cert-border" style="background: #ffffff;">
                <div class="cert-corner cert-corner-tl"></div>
                <div class="cert-corner cert-corner-tr"></div>
                <div class="cert-corner cert-corner-bl"></div>
                <div class="cert-corner cert-corner-br"></div>

                <div class="text-center mb-3">
                    <div style="font-family: 'Cinzel', serif; font-size: 14pt; font-weight: 700; color: #0c4a6e; letter-spacing: 1.5px;">
                        DAFTAR PENILAIAN HASIL BELAJAR PKL
                    </div>
                    <div style="font-size: 8.5pt; color: #64748b;">
                        Lampiran Sertifikat Nomor: 421.5/SMK-LBFU/PKL/{{ date('Y') }}/{{ str_pad($penempatan->id, 4, '0', STR_PAD_LEFT) }}
                    </div>
                </div>

                <!-- Student Identity Bio -->
                <div class="row g-2 mb-3" style="font-size: 8.5pt; max-width: 220mm; margin: 0 auto;">
                    <div class="col-6">
                        <table class="w-100">
                            <tr><td style="width: 130px;" class="text-muted">Nama Peserta Didik</td><td style="width: 10px;">:</td><td class="fw-bold">{{ $penempatan->siswa?->nama }}</td></tr>
                            <tr><td class="text-muted">Nomor Induk Siswa</td><td>:</td><td>{{ $penempatan->siswa?->nis }} / {{ $penempatan->siswa?->nisn ?: '-' }}</td></tr>
                            <tr><td class="text-muted">Kelas / Rombel</td><td>:</td><td>{{ $penempatan->siswa?->kelas?->nama_kelas ?? '-' }}</td></tr>
                        </table>
                    </div>
                    <div class="col-6">
                        <table class="w-100">
                            <tr><td style="width: 130px;" class="text-muted">Program Keahlian</td><td style="width: 10px;">:</td><td class="fw-bold text-primary">{{ $penempatan->siswa?->jurusan?->nama_jurusan }}</td></tr>
                            <tr><td class="text-muted">Tempat PKL (DUDI)</td><td>:</td><td class="fw-semibold">{{ $penempatan->perusahaan?->nama_perusahaan }}</td></tr>
                            <tr><td class="text-muted">Durasi Waktu</td><td>:</td><td>{{ $tglMulai }} s.d. {{ $tglSelesai }}</td></tr>
                        </table>
                    </div>
                </div>

                <!-- Score Transcript Table -->
                <table class="table table-bordered align-middle mx-auto mb-3" style="max-width: 220mm; font-size: 8.5pt; border-color: #cbd5e1;">
                    <thead style="background: #f1f5f9; text-align: center;">
                        <tr>
                            <th style="width: 40px;">No</th>
                            <th>Komponen / Aspek Penilaian</th>
                            <th style="width: 100px;">Nilai Angka</th>
                            <th style="width: 140px;">Kriteria / Predikat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="background: #f8fafc; font-weight: 700;">
                            <td class="text-center">I</td>
                            <td colspan="3">ASPEK NON-TEKNIS (SIKAP & INTEGRITAS KERJA)</td>
                        </tr>
                        <tr>
                            <td class="text-center">1</td>
                            <td>Disiplin, Kehadiran, dan Ketepatan Waktu Kerja</td>
                            <td class="text-center fw-semibold editable-field" contenteditable="true">{{ $nilaiSikap }}</td>
                            <td class="text-center">{{ $nilaiSikap >= 85 ? 'Sangat Baik' : 'Baik' }}</td>
                        </tr>
                        <tr>
                            <td class="text-center">2</td>
                            <td>Tanggung Jawab, Kemandirian, dan Inisiatif Kerja</td>
                            <td class="text-center fw-semibold editable-field" contenteditable="true">{{ $nilaiSikap }}</td>
                            <td class="text-center">{{ $nilaiSikap >= 85 ? 'Sangat Baik' : 'Baik' }}</td>
                        </tr>
                        <tr>
                            <td class="text-center">3</td>
                            <td>Kerja Sama Tim, Komunikasi, dan Etika Budaya Industri</td>
                            <td class="text-center fw-semibold editable-field" contenteditable="true">{{ $nilaiSikap }}</td>
                            <td class="text-center">{{ $nilaiSikap >= 85 ? 'Sangat Baik' : 'Baik' }}</td>
                        </tr>

                        <tr style="background: #f8fafc; font-weight: 700;">
                            <td class="text-center">II</td>
                            <td colspan="3">ASPEK TEKNIS (KEMAMPUAN PROFESIONAL & KEJURUAN)</td>
                        </tr>
                        <tr>
                            <td class="text-center">1</td>
                            <td>Penguasaan Teori, Prosedur, dan Standard Operating Procedure (SOP)</td>
                            <td class="text-center fw-semibold editable-field" contenteditable="true">{{ $nilaiPengetahuan }}</td>
                            <td class="text-center">{{ $nilaiPengetahuan >= 85 ? 'Sangat Baik' : 'Baik' }}</td>
                        </tr>
                        <tr>
                            <td class="text-center">2</td>
                            <td>Keterampilan Praktik, Efisiensi, dan Kualitas Hasil Pekerjaan</td>
                            <td class="text-center fw-semibold editable-field" contenteditable="true">{{ $nilaiKeterampilan }}</td>
                            <td class="text-center">{{ $nilaiKeterampilan >= 85 ? 'Sangat Baik' : 'Baik' }}</td>
                        </tr>
                        <tr>
                            <td class="text-center">3</td>
                            <td>Penerapan K3LH (Keselamatan & Kesehatan Kerja Lingkungan Hidup)</td>
                            <td class="text-center fw-semibold editable-field" contenteditable="true">{{ $nilaiKeterampilan }}</td>
                            <td class="text-center">{{ $nilaiKeterampilan >= 85 ? 'Sangat Baik' : 'Baik' }}</td>
                        </tr>

                        <tr style="background: #f0fdf4; font-weight: 700;">
                            <td colspan="2" class="text-center text-uppercase">Nilai Rata-rata Akhir (Kelulusan PKL)</td>
                            <td class="text-center fs-6 text-success fw-bold editable-field" contenteditable="true">{{ $nilaiAkhir }}</td>
                            <td class="text-center text-success fw-bold">{{ $predikat }}</td>
                        </tr>
                    </tbody>
                </table>

                <!-- Bottom Signature for Back Page -->
                <div class="row align-items-end mt-1" style="font-size: 8.5pt; max-width: 220mm; margin: 0 auto;">
                    <div class="col-6 text-center">
                        <div>Pembimbing Dunia Usaha / Industri,</div>
                        <div style="height: 36px;"></div>
                        <div class="fw-bold text-decoration-underline editable-field" contenteditable="true">
                            {{ $penempatan->perusahaan?->pembimbing_industri ?: 'Pembimbing Industri' }}
                        </div>
                    </div>
                    <div class="col-6 text-center">
                        <div>Guru Pembimbing Sekolah,</div>
                        <div style="height: 36px;"></div>
                        <div class="fw-bold text-decoration-underline editable-field" contenteditable="true">
                            {{ $penempatan->guru?->nama ?: 'Guru Pembimbing, S.Kom' }}
                        </div>
                        <div style="font-size: 7.5pt; color: #64748b;">NIP. {{ $penempatan->guru?->nip ?: '........................' }}</div>
                    </div>
                </div>

            </div>
        </div>
    @endforeach

</body>
</html>
