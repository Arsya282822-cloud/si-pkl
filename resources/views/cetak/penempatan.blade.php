<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Penempatan PKL</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', serif; font-size: 12pt; padding: 2cm; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px double #000; padding-bottom: 10px; }
        .header h2 { font-size: 16pt; text-transform: uppercase; }
        .header p { font-size: 10pt; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 6px 8px; text-align: left; font-size: 10pt; }
        th { background: #f0f0f0; font-weight: bold; }
        .footer { margin-top: 30px; text-align: right; }
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
        <a href="{{ route('admin.penempatan.index') }}" style="margin-left: 10px; color: #666;">← Kembali</a>
    </div>

    <div class="header">
        <h2>Rekap Penempatan Praktik Kerja Lapangan</h2>
        @if($periodeAktif)
            <p>Periode: {{ $periodeAktif->nama_periode }} — Tahun Ajaran {{ $periodeAktif->tahun_ajaran }}</p>
        @endif
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:30px;">No</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Perusahaan</th>
                <th>Guru Pembimbing</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $i => $d)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $d->siswa?->nis }}</td>
                <td>{{ $d->siswa?->nama }}</td>
                <td>{{ $d->siswa?->kelas?->nama_kelas ?? '-' }}</td>
                <td>{{ $d->perusahaan?->nama_perusahaan }}</td>
                <td>{{ $d->guru?->nama }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Mengetahui,</p>
        <p style="margin-top: 60px;">(_________________________)</p>
        <p>Koordinator PKL</p>
    </div>
</body>
</html>
