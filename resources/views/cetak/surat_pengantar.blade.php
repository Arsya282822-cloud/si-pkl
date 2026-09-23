<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Pengantar PKL - {{ $penempatan->siswa->nama }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', serif; font-size: 12pt; padding: 2cm; line-height: 1.5; }
        
        .kop-surat { text-align: center; border-bottom: 3px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .kop-surat h1 { font-size: 18pt; text-transform: uppercase; margin-bottom: 5px; }
        .kop-surat p { font-size: 11pt; margin-bottom: 2px; }
        
        .info-surat { margin-bottom: 30px; }
        .info-surat table { border: none; width: 100%; }
        .info-surat td { padding: 2px; border: none; vertical-align: top; }
        
        .tujuan { margin-bottom: 30px; }
        
        .isi-surat { text-align: justify; margin-bottom: 20px; }
        
        .tabel-siswa { width: 80%; margin: 20px auto; border-collapse: collapse; }
        .tabel-siswa th, .tabel-siswa td { border: 1px solid #000; padding: 8px; text-align: left; }
        
        .ttd { width: 100%; margin-top: 50px; }
        .ttd table { width: 100%; border: none; }
        .ttd td { border: none; width: 50%; }
        
        .no-print { margin-bottom: 20px; }
        @media print {
            .no-print { display: none; }
            body { padding: 1cm; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; background: #0d6efd; color: #fff; border: none; border-radius: 5px; font-size: 14px;">
            🖨️ Cetak / Print
        </button>
    </div>

    <div class="kop-surat">
        <h1>SMK NEGERI CONTOH KOTA</h1>
        <p>Jl. Pendidikan No. 123, Kota Pendidikan, Provinsi Ilmu</p>
        <p>Telp: (021) 1234567 | Email: info@smkncontoh.sch.id | Web: www.smkncontoh.sch.id</p>
    </div>

    <div class="info-surat">
        <table>
            <tr>
                <td style="width: 80px;">Nomor</td>
                <td style="width: 10px;">:</td>
                <td>421.5 / ______ / SMK / {{ date('Y') }}</td>
                <td style="text-align: right;">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td>Lampiran</td>
                <td>:</td>
                <td colspan="2">1 (Satu) Berkas</td>
            </tr>
            <tr>
                <td>Hal</td>
                <td>:</td>
                <td colspan="2"><strong>Pengantar Praktik Kerja Lapangan (PKL)</strong></td>
            </tr>
        </table>
    </div>

    <div class="tujuan">
        <p>Yth. Pimpinan/Direktur</p>
        <p><strong>{{ $penempatan->perusahaan->nama_perusahaan }}</strong></p>
        <p>{{ $penempatan->perusahaan->alamat }}</p>
    </div>

    <div class="isi-surat">
        <p>Dengan hormat,</p>
        <p style="text-indent: 30px; margin-top: 10px;">Dalam rangka pelaksanaan Kurikulum SMK, setiap siswa diwajibkan untuk melaksanakan Praktik Kerja Lapangan (PKL) di Dunia Usaha / Dunia Industri guna meningkatkan kompetensi dan pengalaman kerja nyata.</p>
        <p style="text-indent: 30px; margin-top: 10px;">Sehubungan dengan hal tersebut, kami mohon bantuan Bapak/Ibu agar berkenan menerima siswa kami di bawah ini untuk melaksanakan PKL di perusahaan/instansi yang Bapak/Ibu pimpin.</p>
    </div>

    <table class="tabel-siswa">
        <tr>
            <th style="width: 30px;">No</th>
            <th>Nama Siswa</th>
            <th>NIS / NISN</th>
            <th>Kelas / Jurusan</th>
        </tr>
        <tr>
            <td style="text-align: center;">1</td>
            <td><strong>{{ $penempatan->siswa->nama }}</strong></td>
            <td>{{ $penempatan->siswa->nis }} / {{ $penempatan->siswa->nisn ?? '-' }}</td>
            <td>{{ $penempatan->siswa->kelas?->nama_kelas ?? '-' }} / {{ $penempatan->siswa->jurusan?->nama_jurusan ?? '-' }}</td>
        </tr>
    </table>

    <div class="isi-surat">
        <p style="text-indent: 30px;">Pelaksanaan PKL dijadwalkan pada <strong>{{ $penempatan->periodePkl?->nama_periode ?? '[Nama Periode]' }}</strong>.</p>
        <p style="text-indent: 30px; margin-top: 10px;">Demikian surat pengantar ini kami sampaikan. Atas perhatian dan kerjasama Bapak/Ibu, kami ucapkan terima kasih.</p>
    </div>

    <div class="ttd">
        <table>
            <tr>
                <td></td>
                <td style="text-align: center;">
                    <p>Kepala Sekolah,</p>
                    <p style="margin-top: 70px;"><strong><u>Drs. Nama Kepala Sekolah, M.Pd</u></strong></p>
                    <p>NIP. 19700101 199512 1 001</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
