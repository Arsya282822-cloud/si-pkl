@if(Auth::user()->role?->nama_role === 'admin')
    <!-- ============================================ -->
    <!-- 1. MENU UTAMA -->
    <!-- ============================================ -->
    <div class="nav-label">Menu Utama</div>

    <a href="{{ route('admin.dashboard') }}" class="pro-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" title="Beranda Admin">
        <i data-lucide="layout-dashboard" class="nav-icon"></i>
        <span>Beranda</span>
    </a>

    <a href="{{ route('admin.pengumuman.index') }}" class="pro-nav-link {{ request()->routeIs('admin.pengumuman.*') ? 'active' : '' }}" title="Pesan & Pengumuman">
        <i data-lucide="message-square" class="nav-icon"></i>
        <span>Pesan & Info</span>
    </a>

    <!-- ============================================ -->
    <!-- 2. DATA MASTER & KEJURUAN -->
    <!-- ============================================ -->
    <div class="nav-label">Data Master & Kejuruan</div>

    @php
        $isRombelOpen = request()->routeIs('admin.kelas.*') || request()->routeIs('admin.jurusan.*');
    @endphp
    <details class="pro-nav-group" {{ $isRombelOpen ? 'open' : '' }}>
        <summary class="pro-nav-link {{ $isRombelOpen ? 'active' : '' }}" title="Rombongan Belajar">
            <i data-lucide="school" class="nav-icon"></i>
            <span>Rombongan Belajar</span>
            <i data-lucide="chevron-right" class="nav-arrow"></i>
        </summary>
        <ul class="sub-nav">
            <li>
                <a href="{{ route('admin.kelas.index') }}" class="sub-nav-link {{ request()->routeIs('admin.kelas.*') ? 'active' : '' }}">
                    Kelas / Rombel
                </a>
            </li>
            <li>
                <a href="{{ route('admin.jurusan.index') }}" class="sub-nav-link {{ request()->routeIs('admin.jurusan.*') ? 'active' : '' }}">
                    Jurusan / Keahlian
                </a>
            </li>
        </ul>
    </details>

    <a href="{{ route('admin.guru.index') }}" class="pro-nav-link {{ request()->routeIs('admin.guru.*') ? 'active' : '' }}" title="Guru Pembimbing">
        <i data-lucide="graduation-cap" class="nav-icon"></i>
        <span>Guru Pembimbing</span>
    </a>

    <a href="{{ route('admin.siswa.index') }}" class="pro-nav-link {{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}" title="Peserta Didik PKL">
        <i data-lucide="users" class="nav-icon"></i>
        <span>Peserta Didik PKL</span>
    </a>

    @php
        $isSekolahOpen = request()->routeIs('admin.perusahaan.*') || request()->routeIs('admin.pks.*');
    @endphp
    <details class="pro-nav-group" {{ $isSekolahOpen ? 'open' : '' }}>
        <summary class="pro-nav-link {{ $isSekolahOpen ? 'active' : '' }}" title="Mitra DUDI & PKS">
            <i data-lucide="building-2" class="nav-icon"></i>
            <span>Mitra DUDI & PKS</span>
            <i data-lucide="chevron-right" class="nav-arrow"></i>
        </summary>
        <ul class="sub-nav">
            <li>
                <a href="{{ route('admin.perusahaan.index') }}" class="sub-nav-link {{ request()->routeIs('admin.perusahaan.*') ? 'active' : '' }}">
                    Perusahaan Mitra DUDI
                </a>
            </li>
            <li>
                <a href="{{ route('admin.pks.index') }}" class="sub-nav-link {{ request()->routeIs('admin.pks.*') ? 'active' : '' }}">
                    Kerja Sama (PKS)
                </a>
            </li>
        </ul>
    </details>

    <a href="{{ route('admin.tujuan_pembelajaran.index') }}" class="pro-nav-link {{ request()->routeIs('admin.tujuan_pembelajaran.*') ? 'active' : '' }}" title="Tujuan Pembelajaran PKL 2026">
        <i data-lucide="book-open-check" class="nav-icon"></i>
        <span>Tujuan Pembelajaran</span>
        <span class="badge-new">2026</span>
    </a>

    <!-- ============================================ -->
    <!-- 3. MANAJEMEN OPERASIONAL PKL -->
    <!-- ============================================ -->
    <div class="nav-label">Manajemen PKL</div>

    @php
        $isManajemenPklOpen = request()->routeIs('admin.periode.*') || request()->routeIs('admin.pengajuan.*') || request()->routeIs('admin.penempatan.*');
        $pendingPengajuan = \App\Models\PengajuanPkl::where('status', 'menunggu')->count();
    @endphp
    <details class="pro-nav-group" {{ $isManajemenPklOpen ? 'open' : '' }}>
        <summary class="pro-nav-link {{ $isManajemenPklOpen ? 'active' : '' }}" title="Penempatan & Periode PKL">
            <i data-lucide="calendar-check" class="nav-icon"></i>
            <span>Penempatan PKL</span>
            @if($pendingPengajuan > 0)
                <span class="nav-badge">{{ $pendingPengajuan }}</span>
            @endif
            <i data-lucide="chevron-right" class="nav-arrow"></i>
        </summary>
        <ul class="sub-nav">
            <li>
                <a href="{{ route('admin.periode.index') }}" class="sub-nav-link {{ request()->routeIs('admin.periode.*') ? 'active' : '' }}">
                    Periode PKL
                </a>
            </li>
            <li>
                <a href="{{ route('admin.penempatan.index') }}" class="sub-nav-link {{ request()->routeIs('admin.penempatan.*') ? 'active' : '' }}">
                    Data Penempatan
                </a>
            </li>
            <li>
                <a href="{{ route('admin.pengajuan.index') }}" class="sub-nav-link {{ request()->routeIs('admin.pengajuan.*') ? 'active' : '' }}">
                    Pengajuan Mandiri
                    @if($pendingPengajuan > 0)
                        <span class="badge bg-warning text-dark ms-auto" style="font-size: 0.65rem;">{{ $pendingPengajuan }}</span>
                    @endif
                </a>
            </li>
        </ul>
    </details>

    <a href="{{ route('admin.laporan.absensi') }}" class="pro-nav-link {{ request()->routeIs('admin.laporan.absensi') ? 'active' : '' }}" title="Rekap Presensi Siswa">
        <i data-lucide="clock" class="nav-icon"></i>
        <span>Presensi Siswa</span>
    </a>

    <!-- ============================================ -->
    <!-- 4. OBSERVASI, NILAI & DOKUMEN -->
    <!-- ============================================ -->
    <div class="nav-label">Evaluasi & Dokumen</div>

    <a href="{{ route('admin.observasi.index') }}" class="pro-nav-link {{ request()->routeIs('admin.observasi.*') ? 'active' : '' }}" title="Lembar Observasi Siswa">
        <i data-lucide="clipboard-check" class="nav-icon"></i>
        <span>Lembar Observasi</span>
    </a>

    @php
        $isNilaiOpen = request()->routeIs('admin.laporan.nilai') || request()->routeIs('admin.dokumen.sertifikat') || request()->routeIs('admin.dokumen.batch_sertifikat');
    @endphp
    <details class="pro-nav-group" {{ $isNilaiOpen ? 'open' : '' }}>
        <summary class="pro-nav-link {{ $isNilaiOpen ? 'active' : '' }}" title="Nilai & Sertifikasi">
            <i data-lucide="award" class="nav-icon"></i>
            <span>Nilai & Sertifikat</span>
            <i data-lucide="chevron-right" class="nav-arrow"></i>
        </summary>
        <ul class="sub-nav">
            <li>
                <a href="{{ route('admin.laporan.nilai') }}" class="sub-nav-link {{ request()->routeIs('admin.laporan.nilai') ? 'active' : '' }}">
                    Rekapitulasi Nilai
                </a>
            </li>
            <li>
                <a href="{{ route('admin.dokumen.batch_sertifikat') }}" target="_blank" class="sub-nav-link">
                    Cetak Batch E-Sertifikat
                </a>
            </li>
        </ul>
    </details>

    <a href="{{ route('admin.dokumen.index') }}" class="pro-nav-link {{ request()->routeIs('admin.dokumen.index') ? 'active' : '' }}" title="Pusat Cetak Berkas & Surat">
        <i data-lucide="printer" class="nav-icon"></i>
        <span>Pusat Cetak Dokumen</span>
    </a>

    <a href="{{ route('admin.laporan.index') }}" class="pro-nav-link {{ request()->routeIs('admin.laporan.index') ? 'active' : '' }}" title="Rekapitulasi Laporan">
        <i data-lucide="bar-chart-3" class="nav-icon"></i>
        <span>Rekap Laporan</span>
    </a>

    <!-- ============================================ -->
    <!-- 5. PUSAT UNDUHAN & TARIK DATA -->
    <!-- ============================================ -->
    <div class="nav-label">Data & Cadangan</div>

    <details class="pro-nav-group">
        <summary class="pro-nav-link" title="Export & Backup">
            <i data-lucide="download" class="nav-icon"></i>
            <span>Tarik & Backup Data</span>
            <i data-lucide="chevron-right" class="nav-arrow"></i>
        </summary>
        <ul class="sub-nav">
            <li><a href="{{ route('admin.export.siswa') }}" class="sub-nav-link">Export Data Siswa</a></li>
            <li><a href="{{ route('admin.export.penempatan') }}" class="sub-nav-link">Export Penempatan</a></li>
            <li><a href="{{ route('admin.export.absensi') }}" class="sub-nav-link">Export Presensi</a></li>
            <li><a href="{{ route('admin.export.nilai') }}" class="sub-nav-link">Export Rekap Nilai</a></li>
            <li><a href="{{ route('admin.backup.database') }}" class="sub-nav-link">Backup SQLite DB</a></li>
        </ul>
    </details>

    <details class="pro-nav-group">
        <summary class="pro-nav-link" title="Pusat Unduhan Template">
            <i data-lucide="folder-down" class="nav-icon"></i>
            <span>Pusat Unduhan</span>
            <i data-lucide="chevron-right" class="nav-arrow"></i>
        </summary>
        <ul class="sub-nav">
            <li><a href="{{ route('admin.template.siswa') }}" class="sub-nav-link">Template Import Siswa</a></li>
            <li><a href="{{ route('admin.template.guru') }}" class="sub-nav-link">Template Import Guru</a></li>
            <li><a href="{{ route('admin.template.perusahaan') }}" class="sub-nav-link">Template Import DUDI</a></li>
            <li><a href="{{ route('admin.template.observasi') }}" class="sub-nav-link text-success fw-semibold">Format Lembar Observasi</a></li>
        </ul>
    </details>

    <!-- ============================================ -->
    <!-- 6. SISTEM & AKUN -->
    <!-- ============================================ -->
    <div class="nav-label">Sistem & Akun</div>

    <a href="{{ route('admin.users.index') }}" class="pro-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" title="Manajemen Pengguna & Hak Akses">
        <i data-lucide="users-2" class="nav-icon"></i>
        <span>Manajemen Pengguna</span>
    </a>

    <a href="{{ route('admin.pengaturan.index') }}" class="pro-nav-link {{ request()->routeIs('admin.pengaturan.*') ? 'active' : '' }}" title="Pengaturan Sekolah">
        <i data-lucide="settings" class="nav-icon"></i>
        <span>Pengaturan Sekolah</span>
    </a>

    <a href="{{ route('admin.activity-log.index') }}" class="pro-nav-link {{ request()->routeIs('admin.activity-log.*') ? 'active' : '' }}" title="Log Aktivitas Sistem">
        <i data-lucide="shield-alert" class="nav-icon"></i>
        <span>Log Aktivitas</span>
    </a>

    <a href="{{ route('profile.edit') }}" class="pro-nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" title="Profil Saya">
        <i data-lucide="user" class="nav-icon"></i>
        <span>Profil Pengguna</span>
    </a>

    <a href="{{ route('bantuan.index') }}" class="pro-nav-link {{ request()->routeIs('bantuan.*') ? 'active' : '' }}" title="Pusat Bantuan & Petunjuk">
        <i data-lucide="help-circle" class="nav-icon"></i>
        <span>Pusat Bantuan</span>
    </a>

@elseif(Auth::user()->role?->nama_role === 'guru')
    <!-- ============================================ -->
    <!-- 1. MENU UTAMA GURU -->
    <!-- ============================================ -->
    <div class="nav-label">Menu Utama</div>

    <a href="{{ route('guru.dashboard') }}" class="pro-nav-link {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}" title="Beranda Guru">
        <i data-lucide="layout-dashboard" class="nav-icon"></i>
        <span>Beranda</span>
    </a>

    <a href="{{ route('guru.pengumuman.index') }}" class="pro-nav-link {{ request()->routeIs('guru.pengumuman.*') ? 'active' : '' }}" title="Pesan & Pengumuman">
        <i data-lucide="message-square" class="nav-icon"></i>
        <span>Pesan & Info</span>
    </a>

    <!-- ============================================ -->
    <!-- 2. BIMBINGAN & SUPERVISI -->
    <!-- ============================================ -->
    <div class="nav-label">Bimbingan & Supervisi</div>

    <a href="{{ route('guru.siswa.index') }}" class="pro-nav-link {{ request()->routeIs('guru.siswa.*') ? 'active' : '' }}" title="Siswa Bimbingan PKL">
        <i data-lucide="users" class="nav-icon"></i>
        <span>Peserta Didik</span>
    </a>

    <a href="{{ route('guru.pantauan_map.index') }}" class="pro-nav-link {{ request()->routeIs('guru.pantauan_map.*') ? 'active' : '' }}" title="Pantauan Peta Live">
        <i data-lucide="map-pin" class="nav-icon"></i>
        <span>Pantauan Peta Live</span>
        <span class="badge-new">LIVE</span>
    </a>

    @php
        $isMonOpen = request()->routeIs('guru.monitoring.*') || request()->routeIs('guru.observasi.*');
    @endphp
    <details class="pro-nav-group" {{ $isMonOpen ? 'open' : '' }}>
        <summary class="pro-nav-link {{ $isMonOpen ? 'active' : '' }}" title="Supervisi & Observasi">
            <i data-lucide="clipboard-check" class="nav-icon"></i>
            <span>Observasi & Supervisi</span>
            <i data-lucide="chevron-right" class="nav-arrow"></i>
        </summary>
        <ul class="sub-nav">
            <li>
                <a href="{{ route('guru.monitoring.index') }}" class="sub-nav-link {{ request()->routeIs('guru.monitoring.index') || request()->routeIs('guru.monitoring.create') ? 'active' : '' }}">
                    Catatan Kunjungan Supervisi
                </a>
            </li>
            <li>
                <a href="{{ route('guru.observasi.index') }}" class="sub-nav-link {{ request()->routeIs('guru.observasi.*') ? 'active' : '' }}">
                    Lembar Observasi Siswa
                </a>
            </li>
            <li>
                <a href="{{ route('guru.monitoring.blanko') }}" target="_blank" class="sub-nav-link">
                    Cetak Blanko Observasi
                </a>
            </li>
            <li>
                <a href="{{ route('guru.monitoring.excel') }}" class="sub-nav-link text-success fw-semibold">
                    Format Excel Observasi
                </a>
            </li>
            <li>
                <a href="{{ route('admin.tujuan_pembelajaran.cetak') }}" target="_blank" class="sub-nav-link text-info fw-semibold">
                    Dokumen TP PKL 2026
                </a>
            </li>
        </ul>
    </details>

    <!-- ============================================ -->
    <!-- 3. VALIDASI & PENILAIAN -->
    <!-- ============================================ -->
    <div class="nav-label">Validasi & Nilai</div>

    @php
        $pendingJurnal = App\Models\JurnalPkl::whereHas('penempatan', function($q) {
            $q->where('guru_id', Auth::user()->guru?->id);
        })->where('status_validasi', 'menunggu')->count();
    @endphp
    <a href="{{ route('guru.validasi.index') }}" class="pro-nav-link {{ request()->routeIs('guru.validasi.*') ? 'active' : '' }}" title="Validasi & Supervisi Jurnal">
        <i data-lucide="check-circle-2" class="nav-icon"></i>
        <span>Validasi Jurnal</span>
        @if($pendingJurnal > 0)
            <span class="nav-badge">{{ $pendingJurnal }}</span>
        @endif
    </a>

    <a href="{{ route('guru.penilaian.index') }}" class="pro-nav-link {{ request()->routeIs('guru.penilaian.*') ? 'active' : '' }}" title="Penilaian PKL">
        <i data-lucide="award" class="nav-icon"></i>
        <span>Nilai PKL & Rapor</span>
    </a>

    <a href="{{ route('guru.laporan.index') }}" class="pro-nav-link {{ request()->routeIs('guru.laporan.*') ? 'active' : '' }}" title="Rekapitulasi Laporan">
        <i data-lucide="bar-chart-3" class="nav-icon"></i>
        <span>Rekap Laporan</span>
    </a>

    <!-- ============================================ -->
    <!-- 4. AKUN & BANTUAN -->
    <!-- ============================================ -->
    <div class="nav-label">Akun & Bantuan</div>

    <a href="{{ route('profile.edit') }}" class="pro-nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" title="Profil Saya">
        <i data-lucide="user" class="nav-icon"></i>
        <span>Profil Saya</span>
    </a>

    <a href="{{ route('bantuan.index') }}" class="pro-nav-link {{ request()->routeIs('bantuan.*') ? 'active' : '' }}" title="Pusat Bantuan">
        <i data-lucide="help-circle" class="nav-icon"></i>
        <span>Pusat Bantuan</span>
    </a>

@elseif(Auth::user()->role?->nama_role === 'siswa')
    <!-- ============================================ -->
    <!-- 1. MENU UTAMA SISWA -->
    <!-- ============================================ -->
    <div class="nav-label">Menu Utama</div>

    <a href="{{ route('siswa.dashboard') }}" class="pro-nav-link {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}" title="Beranda Siswa">
        <i data-lucide="layout-dashboard" class="nav-icon"></i>
        <span>Beranda</span>
    </a>

    <a href="{{ route('siswa.pengumuman.index') }}" class="pro-nav-link {{ request()->routeIs('siswa.pengumuman.*') ? 'active' : '' }}" title="Pesan & Pengumuman">
        <i data-lucide="message-square" class="nav-icon"></i>
        <span>Pesan & Info</span>
    </a>

    <!-- ============================================ -->
    <!-- 2. AKTIVITAS PKL -->
    <!-- ============================================ -->
    <div class="nav-label">Aktivitas PKL</div>

    @php
        $isTempatOpen = request()->routeIs('siswa.penempatan.*') || request()->routeIs('siswa.pengajuan.*');
    @endphp
    <details class="pro-nav-group" {{ $isTempatOpen ? 'open' : '' }}>
        <summary class="pro-nav-link {{ $isTempatOpen ? 'active' : '' }}" title="Tempat PKL & Pengajuan">
            <i data-lucide="building-2" class="nav-icon"></i>
            <span>Tempat PKL</span>
            <i data-lucide="chevron-right" class="nav-arrow"></i>
        </summary>
        <ul class="sub-nav">
            <li>
                <a href="{{ route('siswa.penempatan.index') }}" class="sub-nav-link {{ request()->routeIs('siswa.penempatan.*') ? 'active' : '' }}">
                    Tempat PKL Saya
                </a>
            </li>
            <li>
                <a href="{{ route('siswa.pengajuan.index') }}" class="sub-nav-link {{ request()->routeIs('siswa.pengajuan.*') ? 'active' : '' }}">
                    Pengajuan Mandiri
                </a>
            </li>
        </ul>
    </details>

    <a href="{{ route('siswa.absensi.index') }}" class="pro-nav-link {{ request()->routeIs('siswa.absensi.*') ? 'active' : '' }}" title="Presensi Harian">
        <i data-lucide="calendar-check" class="nav-icon"></i>
        <span>Presensi Harian</span>
    </a>

    <a href="{{ route('siswa.jurnal.index') }}" class="pro-nav-link {{ request()->routeIs('siswa.jurnal.*') ? 'active' : '' }}" title="Jurnal Kegiatan">
        <i data-lucide="book-open" class="nav-icon"></i>
        <span>Jurnal Kegiatan</span>
    </a>

    <!-- ============================================ -->
    <!-- 3. HASIL & EVALUASI -->
    <!-- ============================================ -->
    <div class="nav-label">Hasil & Sertifikasi</div>

    <a href="{{ route('siswa.nilai.index') }}" class="pro-nav-link {{ request()->routeIs('siswa.nilai.*') || request()->routeIs('siswa.sertifikat') ? 'active' : '' }}" title="Nilai & E-Sertifikat">
        <i data-lucide="award" class="nav-icon"></i>
        <span>Nilai & Sertifikat</span>
    </a>

    <!-- ============================================ -->
    <!-- 4. AKUN & BANTUAN -->
    <!-- ============================================ -->
    <div class="nav-label">Akun & Bantuan</div>

    <a href="{{ route('profile.edit') }}" class="pro-nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" title="Profil Saya">
        <i data-lucide="user" class="nav-icon"></i>
        <span>Profil Saya</span>
    </a>

    <a href="{{ route('bantuan.index') }}" class="pro-nav-link {{ request()->routeIs('bantuan.*') ? 'active' : '' }}" title="Pusat Bantuan">
        <i data-lucide="help-circle" class="nav-icon"></i>
        <span>Pusat Bantuan</span>
    </a>

@elseif(Auth::user()->role?->nama_role === 'instruktur')
    <!-- ============================================ -->
    <!-- 1. MENU UTAMA INSTRUKTUR -->
    <!-- ============================================ -->
    <div class="nav-label">Menu Utama</div>

    <a href="{{ route('instruktur.dashboard') }}" class="pro-nav-link {{ request()->routeIs('instruktur.dashboard') ? 'active' : '' }}" title="Beranda DUDI">
        <i data-lucide="layout-dashboard" class="nav-icon"></i>
        <span>Beranda DUDI</span>
    </a>

    <!-- ============================================ -->
    <!-- 2. BIMBINGAN LAPANGAN -->
    <!-- ============================================ -->
    <div class="nav-label">Bimbingan Industri</div>

    <a href="{{ route('instruktur.siswa.index') }}" class="pro-nav-link {{ request()->routeIs('instruktur.siswa.*') ? 'active' : '' }}" title="Peserta Didik Magang">
        <i data-lucide="users" class="nav-icon"></i>
        <span>Siswa Magang</span>
    </a>

    <a href="{{ route('instruktur.absensi.index') }}" class="pro-nav-link {{ request()->routeIs('instruktur.absensi.*') ? 'active' : '' }}" title="Presensi Siswa">
        <i data-lucide="calendar-check" class="nav-icon"></i>
        <span>Presensi Siswa</span>
    </a>

    <!-- ============================================ -->
    <!-- 3. VALIDASI & PENILAIAN DUDI -->
    <!-- ============================================ -->
    <div class="nav-label">Validasi & Nilai</div>

    @php
        $dudiId = Auth::user()->pembimbingIndustri?->perusahaan_id;
        $pendingJurnalDudi = 0;
        if ($dudiId) {
            $pendingJurnalDudi = \App\Models\JurnalPkl::whereHas('penempatan', function($q) use ($dudiId) {
                $q->where('perusahaan_id', $dudiId);
            })->where('status_validasi', 'menunggu')->count();
        }
    @endphp
    <a href="{{ route('instruktur.jurnal.index') }}" class="pro-nav-link {{ request()->routeIs('instruktur.jurnal.*') ? 'active' : '' }}" title="Validasi Jurnal">
        <i data-lucide="check-circle-2" class="nav-icon"></i>
        <span>Validasi Jurnal</span>
        @if($pendingJurnalDudi > 0)
            <span class="nav-badge">{{ $pendingJurnalDudi }}</span>
        @endif
    </a>

    <a href="{{ route('instruktur.penilaian.index') }}" class="pro-nav-link {{ request()->routeIs('instruktur.penilaian.*') ? 'active' : '' }}" title="Penilaian DU/DI">
        <i data-lucide="award" class="nav-icon"></i>
        <span>Penilaian DUDI</span>
    </a>

    <!-- ============================================ -->
    <!-- 4. AKUN & BANTUAN -->
    <!-- ============================================ -->
    <div class="nav-label">Akun & Bantuan</div>

    <a href="{{ route('instruktur.profil.index') }}" class="pro-nav-link {{ request()->routeIs('instruktur.profil.*') ? 'active' : '' }}" title="Profil Instruktur">
        <i data-lucide="user-cog" class="nav-icon"></i>
        <span>Profil Instruktur</span>
    </a>

    <a href="{{ route('bantuan.index') }}" class="pro-nav-link {{ request()->routeIs('bantuan.*') ? 'active' : '' }}" title="Pusat Bantuan">
        <i data-lucide="help-circle" class="nav-icon"></i>
        <span>Pusat Bantuan</span>
    </a>
@endif
