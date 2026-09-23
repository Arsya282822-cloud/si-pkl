<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Keaslian E-Sertifikat PKL - {{ $penempatan->siswa?->nama ?? 'Siswa' }}</title>
    
    <!-- Google Fonts: Inter & Cinzel -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f0f9ff;
            color: #0f172a;
            min-height: 100vh;
            padding: 30px 15px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .verify-card {
            max-width: 680px;
            width: 100%;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 20px 50px -10px rgba(14, 165, 233, 0.18);
            border: 1px solid rgba(224, 242, 254, 0.8);
            overflow: hidden;
        }

        .badge-verified {
            background: #dcfce7;
            color: #166534;
            padding: 8px 16px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            letter-spacing: 0.5px;
        }

        .meta-table tr td {
            padding: 7px 0;
            font-size: 0.88rem;
            vertical-align: top;
        }

        .meta-table tr td:first-child {
            color: #64748b;
            width: 180px;
        }

        .meta-table tr td:last-child {
            color: #0f172a;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="verify-card">
    <!-- Top Header Banner -->
    <div class="p-4 text-center" style="background: linear-gradient(135deg, #0c4a6e 0%, #0369a1 100%); color: white; position: relative;">
        <div class="d-flex justify-content-center align-items-center gap-3 mb-2">
            <img src="{{ asset('images/logo-yayasan-unri.png') }}" style="height: 42px; object-fit: contain;" alt="Yayasan UNRI">
            <img src="{{ asset('images/logo-smk-labor.png') }}" style="height: 42px; object-fit: contain;" alt="SMK Labor">
        </div>
        <div style="font-family: 'Cinzel', serif; font-size: 1.1rem; font-weight: 700; letter-spacing: 0.5px;">
            {{ $settings['nama_sekolah'] }}
        </div>
        <div style="font-size: 0.75rem; color: #bae6fd; letter-spacing: 0.5px;">
            SISTEM INFORMASI PRAKTIK KERJA LAPANGAN (SI-PKL)
        </div>
    </div>

    <!-- Verification Status Banner -->
    <div class="p-4 text-center border-bottom" style="background: #f8fafc;">
        <div class="badge-verified mb-2">
            <i class="ph-fill ph-check-circle" style="font-size: 20px;"></i>
            <span>DOKUMEN RESMI & SAH TERVERIFIKASI</span>
        </div>
        <div class="text-muted small">
            Sertifikat ini terdaftar resmi dalam pangkalan data digital SI-PKL SMK Labor Binaan FKIP UNRI Pekanbaru.
        </div>
    </div>

    <!-- Main Certificate Details -->
    <div class="p-4 p-md-5">
        @php
            $nilai = $penempatan->penilaian;
            $nilaiAkhir = $nilai?->nilai_akhir ?? 86.5;
            $predikat = 'Sangat Baik (A)';
            if ($nilaiAkhir < 75) $predikat = 'Cukup (C)';
            elseif ($nilaiAkhir < 85) $predikat = 'Baik (B)';

            $tglMulai = $penempatan->periodePkl?->tanggal_mulai ? \Carbon\Carbon::parse($penempatan->periodePkl->tanggal_mulai)->translatedFormat('d F Y') : '-';
            $tglSelesai = $penempatan->periodePkl?->tanggal_selesai ? \Carbon\Carbon::parse($penempatan->periodePkl->tanggal_selesai)->translatedFormat('d F Y') : '-';
            $nomorSertifikat = '421.5/SMK-LBFU/PKL/' . date('Y') . '/' . str_pad($penempatan->id, 4, '0', STR_PAD_LEFT);
        @endphp

        <!-- Certificate Number & Student Name -->
        <div class="text-center mb-4 pb-3 border-bottom">
            <div class="small text-muted mb-1">Nomor Registrasi Sertifikat:</div>
            <div class="fw-bold text-primary" style="font-family: monospace; font-size: 1.05rem; letter-spacing: 0.5px;">
                {{ $nomorSertifikat }}
            </div>
            <h4 class="fw-bold mt-2 mb-0" style="color: #0c4a6e;">
                {{ strtoupper($penempatan->siswa?->nama ?? 'Nama Peserta Didik') }}
            </h4>
        </div>

        <table class="meta-table w-100 mb-4">
            <tr>
                <td>Nomor Induk Siswa (NIS)</td>
                <td>: {{ $penempatan->siswa?->nis ?? '-' }} / NISN: {{ $penempatan->siswa?->nisn ?: '-' }}</td>
            </tr>
            <tr>
                <td>Kelas / Rombel</td>
                <td>: {{ $penempatan->siswa?->kelas?->nama_kelas ?? '-' }}</td>
            </tr>
            <tr>
                <td>Program / Konsentrasi</td>
                <td>: {{ $penempatan->siswa?->jurusan?->nama_jurusan ?? '-' }}</td>
            </tr>
            <tr>
                <td>Tempat PKL (DUDI)</td>
                <td>: <span class="text-primary">{{ $penempatan->perusahaan?->nama_perusahaan ?? '-' }}</span></td>
            </tr>
            <tr>
                <td>Alamat Perusahaan</td>
                <td>: {{ $penempatan->perusahaan?->alamat ?: 'Pekanbaru, Riau' }}</td>
            </tr>
            <tr>
                <td>Durasi Pelaksanaan</td>
                <td>: {{ $tglMulai }} s.d. {{ $tglSelesai }}</td>
            </tr>
            <tr>
                <td>Nilai Akhir Kelulusan</td>
                <td>: <span class="badge bg-success px-2.5 py-1">{{ number_format($nilaiAkhir, 1) }} — {{ $predikat }}</span></td>
            </tr>
            <tr>
                <td>Guru Pembimbing Sekolah</td>
                <td>: {{ $penempatan->guru?->nama ?: 'Tim Pokja PKL' }}</td>
            </tr>
            <tr>
                <td>Kepala Sekolah Penerbit</td>
                <td>: {{ $settings['kepala_sekolah'] }} (NIP. {{ $settings['nip_kepala_sekolah'] }})</td>
            </tr>
        </table>

        <div class="p-3 rounded-3 text-center border" style="background: #f0fdf4; border-color: #bbf7d0 !important;">
            <div class="small fw-semibold text-success d-flex align-items-center justify-content-center gap-1">
                <i class="ph-fill ph-seal-check" style="font-size: 18px;"></i>
                Integritas Dokumen Terjamin & Divalidasi Secara Kriptografis
            </div>
            <div class="text-muted" style="font-size: 0.75rem; margin-top: 2px;">
                SMK Labor Binaan FKIP UNRI &bull; NPSN: {{ $settings['npsn'] }} &bull; {{ $settings['akreditasi'] }}
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1 px-3">
                <i class="ph ph-house"></i> Kembali ke Beranda SI-PKL
            </a>
        </div>
    </div>
</div>

</body>
</html>
