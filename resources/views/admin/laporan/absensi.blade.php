@extends('layouts.app')
@section('title', 'Rekapitulasi Absensi PKL')
@section('topbar_title', 'Rekapitulasi Absensi PKL')

@section('content')
<style>
    @media print {
        @page {
            size: A4 landscape;
            margin: 0.8cm 1.5cm 0.8cm 1.5cm;
        }
        .pro-sidebar, .pro-header, .no-print, .btn, .nav-tabs, footer {
            display: none !important;
        }
        body {
            background: white !important;
            font-family: 'Calibri', sans-serif !important;
            color: black !important;
        }
        .pro-main, .pro-content {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }
        .card, .pro-card {
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
        }
        .print-header {
            display: block !important;
            margin-bottom: 15px;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid #333 !important;
            padding: 4px 6px !important;
            font-size: 11px !important;
        }
        .print-footer {
            display: block !important;
            margin-top: 25px;
            page-break-inside: avoid;
        }
    }
    .print-header, .print-footer {
        display: none;
    }
</style>

<!-- Official Kop Surat for Print -->
<div class="print-header">
    @include('admin.dokumen._kop')
    <div class="text-center mt-3 mb-3">
        <h4 class="fw-bold mb-0" style="text-decoration: underline; font-family: Tahoma, sans-serif; letter-spacing: 0.5px;">REKAPITULASI KEHADIRAN / ABSENSI PRAKTIK KERJA LAPANGAN (PKL)</h4>
        <div style="font-size: 13px; font-weight: 600; margin-top: 4px;">
            SMK LABOR BINAAN FKIP UNRI PEKANBARU
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-4 no-print">
    <a href="{{ route('admin.laporan.index') }}" class="btn btn-outline-secondary">
        <i class="ph ph-arrow-left me-1"></i> Kembali ke Laporan
    </a>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-outline-primary">
            <i class="ph ph-printer me-1"></i> Cetak / Print PDF
        </button>
        <a href="{{ route('admin.export.absensi') }}" class="btn btn-success">
            <i class="ph ph-download-simple me-1"></i> Export CSV
        </a>
    </div>
</div>

<div class="card dashboard-card">
    <div class="card-body p-4">
        <!-- Filter Form -->
        <form class="row g-2 mb-4 no-print" method="GET">
            <div class="col-md-3">
                <input name="q" value="{{ $q }}" class="form-control" placeholder="Cari nama siswa / perusahaan..." style="border-radius: 8px;">
            </div>
            <div class="col-md-3">
                <select name="periode_id" class="form-select" style="border-radius: 8px;" onchange="this.form.submit()">
                    <option value="">-- Semua Periode PKL --</option>
                    @foreach($periodeList as $per)
                        <option value="{{ $per->id }}" @selected($periodeId == $per->id)>{{ $per->nama_periode }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="jurusan_id" class="form-select" style="border-radius: 8px;" onchange="this.form.submit()">
                    <option value="">-- Semua Jurusan --</option>
                    @foreach($jurusanList as $jur)
                        <option value="{{ $jur->id }}" @selected($jurusanId == $jur->id)>{{ $jur->nama_jurusan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-primary" style="border-radius: 8px;"><i class="bi bi-search"></i> Filter</button>
            </div>
            @if($q || $periodeId || $jurusanId)
                <div class="col-auto">
                    <a href="{{ route('admin.laporan.absensi') }}" class="btn btn-outline-secondary" style="border-radius: 8px;">Reset</a>
                </div>
            @endif
        </form>

        <div class="table-responsive">
            <table class="table align-middle table-bordered table-hover">
                <thead class="table-light text-center">
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th class="text-start">Nama Siswa & NIS</th>
                        <th class="text-start">Kelas / Jurusan</th>
                        <th class="text-start">Perusahaan DUDI</th>
                        <th>Hadir</th>
                        <th>Izin</th>
                        <th>Sakit</th>
                        <th>Alpa</th>
                        <th>Total Hari</th>
                        <th>% Kehadiran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penempatanList as $i => $item)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td>
                                <div class="fw-semibold">{{ $item->siswa?->nama ?? '-' }}</div>
                                <small class="text-muted">{{ $item->siswa?->nis ?? '-' }}</small>
                            </td>
                            <td>
                                <div>{{ $item->siswa?->kelas?->nama_kelas ?? '-' }}</div>
                                <small class="text-muted">{{ $item->siswa?->jurusan?->kode_jurusan ?? '-' }}</small>
                            </td>
                            <td>{{ $item->perusahaan?->nama_perusahaan ?? '-' }}</td>
                            <td class="text-center text-success fw-bold">{{ $item->hadir_count }}</td>
                            <td class="text-center text-info fw-bold">{{ $item->izin_count }}</td>
                            <td class="text-center text-warning fw-bold">{{ $item->sakit_count }}</td>
                            <td class="text-center text-danger fw-bold">{{ $item->alpa_count }}</td>
                            <td class="text-center fw-bold">{{ $item->total_absen }}</td>
                            <td class="text-center">
                                <span class="badge {{ $item->persen_kehadiran >= 80 ? 'bg-success' : ($item->persen_kehadiran >= 60 ? 'bg-warning text-dark' : 'bg-danger') }}">
                                    {{ $item->persen_kehadiran }}%
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-5">Belum ada data absensi untuk filter ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Official Print Signatures -->
        @php
            $namaKepsek = \App\Models\Setting::get('pejabat_kepala_sekolah', 'JEFFRI HUNTER, M.Pd');
            $nipKepsek = \App\Models\Setting::get('pejabat_nip_kepala_sekolah', '-');
            $namaPokja = \App\Models\Setting::get('pejabat_ketua_pokja', 'Dedi Hendrawan, S.Kom., M.Kom.');
            $nipPokja = \App\Models\Setting::get('pejabat_nip_ketua_pokja', '-');
            $kotaTerbit = \App\Models\Setting::get('sekolah_kota_terbit', 'Pekanbaru');
        @endphp
        <div class="print-footer">
            <div class="d-flex justify-content-between text-center" style="font-size: 11pt; margin-top: 20px;">
                <div style="width: 280px;">
                    <div>Mengetahui,</div>
                    <div class="fw-bold">Kepala Sekolah</div>
                    <div style="height: 65px;"></div>
                    <div class="fw-bold" style="text-decoration: underline;">{{ strtoupper($namaKepsek) }}</div>
                    <div>{{ $nipKepsek && $nipKepsek !== '-' ? 'NIP. ' . $nipKepsek : '' }}</div>
                </div>
                <div style="width: 280px;">
                    <div>{{ $kotaTerbit }}, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                    <div class="fw-bold">Ketua Pokja Hubin & PKL</div>
                    <div style="height: 65px;"></div>
                    <div class="fw-bold" style="text-decoration: underline;">{{ strtoupper($namaPokja) }}</div>
                    <div>{{ $nipPokja && $nipPokja !== '-' ? 'NIP. ' . $nipPokja : '' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
