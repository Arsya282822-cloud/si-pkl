@extends('layouts.app')
@section('title', 'Daftar Siswa Magang DUDI')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: var(--text-main);">Daftar Peserta Magang PKL</h3>
        <p class="text-muted mb-0" style="font-size: 0.875rem;">
            Siswa yang ditempatkan dan menjalani praktik kerja lapangan di <strong class="text-dark">{{ $perusahaan->nama }}</strong>.
        </p>
    </div>
</div>

<div class="pro-card p-4 border-0 shadow-sm" style="border-radius: 16px;">
    <!-- Filter -->
    <form method="GET" action="{{ route('instruktur.siswa.index') }}" class="row g-2 mb-4">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="ph ph-magnifying-glass"></i></span>
                <input type="text" name="q" value="{{ request('q') }}" class="form-control border-start-0" placeholder="Cari nama siswa atau NISN...">
            </div>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100 fw-semibold" style="border-radius: 8px;">
                <i class="ph ph-funnel me-1"></i> Filter
            </button>
        </div>
        @if(request('q'))
            <div class="col-md-2">
                <a href="{{ route('instruktur.siswa.index') }}" class="btn btn-light border w-100 text-muted" style="border-radius: 8px;">
                    Reset
                </a>
            </div>
        @endif
    </form>

    @if($penempatans->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="ph ph-users mb-2" style="font-size: 40px; color: #cbd5e1;"></i>
            <h6 class="fw-bold text-dark">Tidak Ada Data Siswa</h6>
            <p class="small mb-0">Belum ada peserta magang yang sesuai dengan kriteria pencarian.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Profil Siswa</th>
                        <th>NISN / NIS</th>
                        <th>Kelas & Jurusan</th>
                        <th>Guru Pembimbing</th>
                        <th>Periode PKL</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penempatans as $index => $penempatan)
                        @php
                            $siswa = $penempatan->siswa;
                            $guru = $penempatan->guru;
                            $periode = $penempatan->periode;
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    @if($siswa?->foto)
                                        <img src="{{ asset('storage/' . $siswa->foto) }}" alt="{{ $siswa->nama }}" class="rounded-circle object-fit-cover" style="width: 40px; height: 40px;">
                                    @else
                                        <div class="rounded-circle bg-light-primary text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; background: #e0f2fe; color: #0284c7;">
                                            {{ strtoupper(substr($siswa?->nama ?? 'S', 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold text-dark">{{ $siswa?->nama ?? '-' }}</div>
                                        <small class="text-muted"><i class="ph ph-phone me-1"></i>{{ $siswa?->no_hp ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-semibold text-secondary">{{ $siswa?->nisn ?? '-' }}</span>
                                @if($siswa?->nis)
                                    <br><small class="text-muted">NIS: {{ $siswa->nis }}</small>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $siswa?->kelas?->nama_kelas ?? '-' }}</div>
                                <small class="text-muted">{{ $siswa?->jurusan?->nama_jurusan ?? '-' }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $guru?->nama ?? 'Belum ditentukan' }}</div>
                                <small class="text-muted">{{ $guru?->no_hp ?? '' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1">
                                    {{ $periode?->nama_periode ?? 'Periode Aktif' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ route('instruktur.jurnal.index', ['siswa_id' => $siswa?->id]) }}" class="btn btn-sm btn-outline-primary" title="Lihat Jurnal Siswa" style="border-radius: 6px 0 0 6px;">
                                        <i class="ph ph-book-open"></i> Jurnal
                                    </a>
                                    <a href="{{ route('instruktur.penilaian.create', $penempatan->id) }}" class="btn btn-sm btn-outline-success" title="Beri Nilai Siswa" style="border-radius: 0 6px 6px 0;">
                                        <i class="ph ph-list-checks"></i> Nilai
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
