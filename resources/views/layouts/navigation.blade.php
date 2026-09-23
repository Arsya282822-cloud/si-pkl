@if(Auth::user()->role?->nama_role === 'admin')
    <!-- 1. BERANDA -->
    <a href="{{ route('admin.dashboard') }}" class="pro-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="ph ph-desktop nav-icon"></i>
        <span>Beranda</span>
    </a>

    <!-- 2. PESAN & PENGUMUMAN -->
    <a href="{{ route('admin.pengumuman.index') }}" class="pro-nav-link {{ request()->routeIs('admin.pengumuman.*') ? 'active' : '' }}">
        <i class="ph ph-chat-circle-dots nav-icon"></i>
        <span>Pesan & Info</span>
    </a>

    <!-- 3. SEKOLAH & MITRA -->
    @php
        $isSekolahOpen = request()->routeIs('admin.perusahaan.*') || request()->routeIs('admin.pks.*');
    @endphp
    <div>
        <a class="pro-nav-link {{ $isSekolahOpen ? 'active' : '' }}" data-bs-toggle="collapse" href="#menuSekolah" data-bs-target="#menuSekolah" role="button" aria-expanded="{{ $isSekolahOpen ? 'true' : 'false' }}" aria-controls="menuSekolah">
            <i class="ph ph-buildings nav-icon"></i>
            <span>Sekolah & Mitra</span>
            <i class="ph ph-caret-right nav-arrow"></i>
        </a>
        <div class="collapse {{ $isSekolahOpen ? 'show' : '' }}" id="menuSekolah">
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
        </div>
    </div>

    <!-- 4. SARPRAS / OPERASIONAL PKL -->
    @php
        $isSarprasOpen = request()->routeIs('admin.periode.*') || request()->routeIs('admin.pengajuan.*') || request()->routeIs('admin.penempatan.*');
        $pendingPengajuan = \App\Models\PengajuanPkl::where('status', 'menunggu')->count();
    @endphp
    <div>
        <a class="pro-nav-link {{ $isSarprasOpen ? 'active' : '' }}" data-bs-toggle="collapse" href="#menuSarpras" data-bs-target="#menuSarpras" role="button" aria-expanded="{{ $isSarprasOpen ? 'true' : 'false' }}" aria-controls="menuSarpras">
            <i class="ph ph-road-horizon nav-icon"></i>
            <span>Sarpras PKL</span>
            <span class="badge-new">NEW</span>
            @if($pendingPengajuan > 0)
                <span class="nav-badge">{{ $pendingPengajuan }}</span>
            @endif
            <i class="ph ph-caret-right nav-arrow"></i>
        </a>
        <div class="collapse {{ $isSarprasOpen ? 'show' : '' }}" id="menuSarpras">
            <ul class="sub-nav">
                <li>
                    <a href="{{ route('admin.periode.index') }}" class="sub-nav-link {{ request()->routeIs('admin.periode.*') ? 'active' : '' }}">
                        Periode PKL
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.penempatan.index') }}" class="sub-nav-link {{ request()->routeIs('admin.penempatan.*') ? 'active' : '' }}">
                        Penempatan Siswa
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
        </div>
    </div>

    <!-- 5. GTK (GURU & PENDIDIK) -->
    @php
        $isGtkOpen = request()->routeIs('admin.guru.*');
    @endphp
    <div>
        <a class="pro-nav-link {{ $isGtkOpen ? 'active' : '' }}" data-bs-toggle="collapse" href="#menuGtk" data-bs-target="#menuGtk" role="button" aria-expanded="{{ $isGtkOpen ? 'true' : 'false' }}" aria-controls="menuGtk">
            <i class="ph ph-graduation-cap nav-icon"></i>
            <span>GTK (Pembimbing)</span>
            <i class="ph ph-caret-right nav-arrow"></i>
        </a>
        <div class="collapse {{ $isGtkOpen ? 'show' : '' }}" id="menuGtk">
            <ul class="sub-nav">
                <li>
                    <a href="{{ route('admin.guru.index') }}" class="sub-nav-link {{ request()->routeIs('admin.guru.index') ? 'active' : '' }}">
                        Data Guru Pembimbing
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.guru.create') }}" class="sub-nav-link {{ request()->routeIs('admin.guru.create') ? 'active' : '' }}">
                        Tambah Guru Baru
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- 6. PESERTA DIDIK -->
    @php
        $isPesertaOpen = request()->routeIs('admin.siswa.*') || request()->routeIs('admin.laporan.absensi');
    @endphp
    <div>
        <a class="pro-nav-link {{ $isPesertaOpen ? 'active' : '' }}" data-bs-toggle="collapse" href="#menuPeserta" data-bs-target="#menuPeserta" role="button" aria-expanded="{{ $isPesertaOpen ? 'true' : 'false' }}" aria-controls="menuPeserta">
            <i class="ph ph-smiley nav-icon"></i>
            <span>Peserta Didik</span>
            <i class="ph ph-caret-right nav-arrow"></i>
        </a>
        <div class="collapse {{ $isPesertaOpen ? 'show' : '' }}" id="menuPeserta">
            <ul class="sub-nav">
                <li>
                    <a href="{{ route('admin.siswa.index') }}" class="sub-nav-link {{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}">
                        Data Siswa PKL
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.laporan.absensi') }}" class="sub-nav-link {{ request()->routeIs('admin.laporan.absensi') ? 'active' : '' }}">
                        Rekap Presensi Siswa
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- 7. ROMBONGAN BELAJAR -->
    @php
        $isRombelOpen = request()->routeIs('admin.kelas.*') || request()->routeIs('admin.jurusan.*');
    @endphp
    <div>
        <a class="pro-nav-link {{ $isRombelOpen ? 'active' : '' }}" data-bs-toggle="collapse" href="#menuRombel" data-bs-target="#menuRombel" role="button" aria-expanded="{{ $isRombelOpen ? 'true' : 'false' }}" aria-controls="menuRombel">
            <i class="ph ph-users-three nav-icon"></i>
            <span>Rombongan Belajar</span>
            <i class="ph ph-caret-right nav-arrow"></i>
        </a>
        <div class="collapse {{ $isRombelOpen ? 'show' : '' }}" id="menuRombel">
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
        </div>
    </div>

    <!-- 8. NILAI & SERTIFIKAT -->
    @php
        $isNilaiOpen = request()->routeIs('admin.laporan.nilai') || request()->routeIs('admin.dokumen.sertifikat') || request()->routeIs('admin.dokumen.batch_sertifikat');
    @endphp
    <div>
        <a class="pro-nav-link {{ $isNilaiOpen ? 'active' : '' }}" data-bs-toggle="collapse" href="#menuNilai" data-bs-target="#menuNilai" role="button" aria-expanded="{{ $isNilaiOpen ? 'true' : 'false' }}" aria-controls="menuNilai">
            <i class="ph ph-list-checks nav-icon"></i>
            <span>Nilai</span>
            <i class="ph ph-caret-right nav-arrow"></i>
        </a>
        <div class="collapse {{ $isNilaiOpen ? 'show' : '' }}" id="menuNilai">
            <ul class="sub-nav">
                <li>
                    <a href="{{ route('admin.laporan.nilai') }}" class="sub-nav-link {{ request()->routeIs('admin.laporan.nilai') ? 'active' : '' }}">
                        Rekapitulasi Nilai PKL
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.dokumen.batch_sertifikat') }}" target="_blank" class="sub-nav-link">
                        Cetak Batch E-Sertifikat
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- 9. VALIDASI & DOKUMEN -->
    @php
        $isValidasiOpen = request()->routeIs('admin.dokumen.index') || request()->routeIs('admin.laporan.index');
    @endphp
    <div>
        <a class="pro-nav-link {{ $isValidasiOpen ? 'active' : '' }}" data-bs-toggle="collapse" href="#menuValidasi" data-bs-target="#menuValidasi" role="button" aria-expanded="{{ $isValidasiOpen ? 'true' : 'false' }}" aria-controls="menuValidasi">
            <i class="ph ph-check-bold nav-icon"></i>
            <span>Validasi & Dokumen</span>
            <i class="ph ph-caret-right nav-arrow"></i>
        </a>
        <div class="collapse {{ $isValidasiOpen ? 'show' : '' }}" id="menuValidasi">
            <ul class="sub-nav">
                <li>
                    <a href="{{ route('admin.dokumen.index') }}" class="sub-nav-link {{ request()->routeIs('admin.dokumen.index') ? 'active' : '' }}">
                        Cetak Berkas & Surat
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.observasi.index') }}" class="sub-nav-link {{ request()->routeIs('admin.observasi.*') ? 'active' : '' }}">
                        Lembar Observasi Siswa
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.laporan.index') }}" class="sub-nav-link {{ request()->routeIs('admin.laporan.index') ? 'active' : '' }}">
                        Rekapitulasi Laporan
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- 10. TARIK DATA -->
    <div>
        <a class="pro-nav-link" data-bs-toggle="collapse" href="#menuTarikData" data-bs-target="#menuTarikData" role="button" aria-expanded="false" aria-controls="menuTarikData">
            <i class="ph ph-arrow-down nav-icon"></i>
            <span>Tarik Data</span>
            <i class="ph ph-caret-right nav-arrow"></i>
        </a>
        <div class="collapse" id="menuTarikData">
            <ul class="sub-nav">
                <li><a href="{{ route('admin.export.siswa') }}" class="sub-nav-link">Export Data Siswa</a></li>
                <li><a href="{{ route('admin.export.penempatan') }}" class="sub-nav-link">Export Penempatan</a></li>
                <li><a href="{{ route('admin.export.absensi') }}" class="sub-nav-link">Export Presensi</a></li>
                <li><a href="{{ route('admin.export.nilai') }}" class="sub-nav-link">Export Rekap Nilai</a></li>
                <li><a href="{{ route('admin.backup.database') }}" class="sub-nav-link">Backup SQLite DB</a></li>
            </ul>
        </div>
    </div>

    <!-- 11. PUSAT UNDUHAN -->
    <div>
        <a class="pro-nav-link" data-bs-toggle="collapse" href="#menuUnduhan" data-bs-target="#menuUnduhan" role="button" aria-expanded="false" aria-controls="menuUnduhan">
            <i class="ph ph-download-simple nav-icon"></i>
            <span>Pusat Unduhan</span>
            <i class="ph ph-caret-right nav-arrow"></i>
        </a>
        <div class="collapse" id="menuUnduhan">
            <ul class="sub-nav">
                <li><a href="{{ route('admin.template.siswa') }}" class="sub-nav-link">Template Import Siswa</a></li>
                <li><a href="{{ route('admin.template.guru') }}" class="sub-nav-link">Template Import Guru</a></li>
                <li><a href="{{ route('admin.template.perusahaan') }}" class="sub-nav-link">Template Import DUDI</a></li>
                <li><a href="{{ route('admin.template.observasi') }}" class="sub-nav-link text-success fw-semibold"><i class="ph ph-file-xls me-1"></i> Format Lembar Observasi</a></li>
            </ul>
        </div>
    </div>

    <!-- 12. PROFIL PENGGUNA -->
    <a href="{{ route('profile.edit') }}" class="pro-nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
        <i class="ph ph-user nav-icon"></i>
        <span>Profil Pengguna</span>
    </a>

    <!-- 13. PENGATURAN -->
    <a href="{{ route('admin.pengaturan.index') }}" class="pro-nav-link {{ request()->routeIs('admin.pengaturan.*') ? 'active' : '' }}">
        <i class="ph ph-gear nav-icon"></i>
        <span>Pengaturan</span>
    </a>

    <!-- 14. LOG AKTIVITAS -->
    <a href="{{ route('admin.activity-log.index') }}" class="pro-nav-link {{ request()->routeIs('admin.activity-log.*') ? 'active' : '' }}">
        <i class="ph ph-shield-check nav-icon"></i>
        <span>Log Aktivitas</span>
    </a>

    <!-- 15. TENTANG & BANTUAN -->
    <a href="{{ route('bantuan.index') }}" class="pro-nav-link {{ request()->routeIs('bantuan.*') ? 'active' : '' }}">
        <i class="ph ph-copyright nav-icon"></i>
        <span>Tentang & Bantuan</span>
    </a>

@elseif(Auth::user()->role?->nama_role === 'guru')
    <!-- 1. BERANDA -->
    <a href="{{ route('guru.dashboard') }}" class="pro-nav-link {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
        <i class="ph ph-desktop nav-icon"></i>
        <span>Beranda</span>
    </a>

    <!-- 2. PESAN & PENGUMUMAN -->
    <a href="{{ route('guru.pengumuman.index') }}" class="pro-nav-link {{ request()->routeIs('guru.pengumuman.*') ? 'active' : '' }}">
        <i class="ph ph-chat-circle-dots nav-icon"></i>
        <span>Pesan & Info</span>
    </a>

    <!-- 3. MONITORING & BERKAS OBSERVASI -->
    @php
        $isMonOpen = request()->routeIs('guru.pantauan_map.*') || request()->routeIs('guru.monitoring.*');
    @endphp
    <div>
        <a class="pro-nav-link {{ $isMonOpen ? 'active' : '' }}" data-bs-toggle="collapse" href="#menuMonGuru" data-bs-target="#menuMonGuru" role="button" aria-expanded="{{ $isMonOpen ? 'true' : 'false' }}" aria-controls="menuMonGuru">
            <i class="ph ph-clipboard-text nav-icon"></i>
            <span>Observasi & Supervisi</span>
            <span class="badge-new">LIVE</span>
            <i class="ph ph-caret-right nav-arrow"></i>
        </a>
        <div class="collapse {{ $isMonOpen ? 'show' : '' }}" id="menuMonGuru">
            <ul class="sub-nav">
                <li>
                    <a href="{{ route('guru.pantauan_map.index') }}" class="sub-nav-link {{ request()->routeIs('guru.pantauan_map.*') ? 'active' : '' }}">
                        <i class="ph ph-map-pin me-1"></i> Pantauan Peta Live
                    </a>
                </li>
                <li>
                    <a href="{{ route('guru.monitoring.index') }}" class="sub-nav-link {{ request()->routeIs('guru.monitoring.index') || request()->routeIs('guru.monitoring.create') ? 'active' : '' }}">
                        <i class="ph ph-files me-1"></i> Berkas Observasi & Catatan
                    </a>
                </li>
                <li>
                    <a href="{{ route('guru.observasi.index') }}" class="sub-nav-link {{ request()->routeIs('guru.observasi.*') ? 'active' : '' }}">
                        <i class="ph ph-list-checks me-1 text-info"></i> Lembar Observasi Siswa
                    </a>
                </li>
                <li>
                    <a href="{{ route('guru.monitoring.blanko') }}" target="_blank" class="sub-nav-link {{ request()->routeIs('guru.monitoring.blanko') ? 'active' : '' }}">
                        <i class="ph ph-printer me-1"></i> Cetak Blanko Observasi
                    </a>
                </li>
                <li>
                    <a href="{{ route('guru.monitoring.excel') }}" class="sub-nav-link text-success fw-semibold">
                        <i class="ph ph-file-xls me-1"></i> Format Excel Observasi
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- 4. PESERTA DIDIK BIMBINGAN -->
    <a href="{{ route('guru.siswa.index') }}" class="pro-nav-link {{ request()->routeIs('guru.siswa.*') ? 'active' : '' }}">
        <i class="ph ph-smiley nav-icon"></i>
        <span>Peserta Didik</span>
    </a>

    <!-- 5. VALIDASI JURNAL -->
    @php
        $pendingJurnal = App\Models\JurnalPkl::whereHas('penempatan', function($q) {
            $q->where('guru_id', Auth::user()->guru?->id);
        })->where('status_validasi', 'menunggu')->count();
    @endphp
    <a href="{{ route('guru.validasi.index') }}" class="pro-nav-link {{ request()->routeIs('guru.validasi.*') ? 'active' : '' }}">
        <i class="ph ph-check-bold nav-icon"></i>
        <span>Validasi Jurnal</span>
        @if($pendingJurnal > 0)
            <span class="nav-badge">{{ $pendingJurnal }}</span>
        @endif
    </a>

    <!-- 6. NILAI -->
    <a href="{{ route('guru.penilaian.index') }}" class="pro-nav-link {{ request()->routeIs('guru.penilaian.*') ? 'active' : '' }}">
        <i class="ph ph-list-checks nav-icon"></i>
        <span>Nilai PKL</span>
    </a>

    <!-- 7. REKAPITULASI LAPORAN -->
    <a href="{{ route('guru.laporan.index') }}" class="pro-nav-link {{ request()->routeIs('guru.laporan.*') ? 'active' : '' }}">
        <i class="ph ph-chart-bar nav-icon"></i>
        <span>Rekap Laporan</span>
    </a>

    <!-- 8. PROFIL PENGGUNA -->
    <a href="{{ route('profile.edit') }}" class="pro-nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
        <i class="ph ph-user nav-icon"></i>
        <span>Profil Pengguna</span>
    </a>

    <!-- 9. TENTANG & BANTUAN -->
    <a href="{{ route('bantuan.index') }}" class="pro-nav-link {{ request()->routeIs('bantuan.*') ? 'active' : '' }}">
        <i class="ph ph-copyright nav-icon"></i>
        <span>Tentang & Bantuan</span>
    </a>

@elseif(Auth::user()->role?->nama_role === 'siswa')
    <!-- 1. BERANDA -->
    <a href="{{ route('siswa.dashboard') }}" class="pro-nav-link {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
        <i class="ph ph-desktop nav-icon"></i>
        <span>Beranda</span>
    </a>

    <!-- 2. PESAN & INFO -->
    <a href="{{ route('siswa.pengumuman.index') }}" class="pro-nav-link {{ request()->routeIs('siswa.pengumuman.*') ? 'active' : '' }}">
        <i class="ph ph-chat-circle-dots nav-icon"></i>
        <span>Pesan & Info</span>
    </a>

    <!-- 3. TEMPAT PKL / DUDI -->
    @php
        $isTempatOpen = request()->routeIs('siswa.penempatan.*') || request()->routeIs('siswa.pengajuan.*');
    @endphp
    <div>
        <a class="pro-nav-link {{ $isTempatOpen ? 'active' : '' }}" data-bs-toggle="collapse" href="#menuTempatSiswa" data-bs-target="#menuTempatSiswa" role="button" aria-expanded="{{ $isTempatOpen ? 'true' : 'false' }}" aria-controls="menuTempatSiswa">
            <i class="ph ph-buildings nav-icon"></i>
            <span>Tempat PKL</span>
            <i class="ph ph-caret-right nav-arrow"></i>
        </a>
        <div class="collapse {{ $isTempatOpen ? 'show' : '' }}" id="menuTempatSiswa">
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
        </div>
    </div>

    <!-- 4. PRESENSI HARIAN -->
    <a href="{{ route('siswa.absensi.index') }}" class="pro-nav-link {{ request()->routeIs('siswa.absensi.*') ? 'active' : '' }}">
        <i class="ph ph-calendar-check nav-icon"></i>
        <span>Presensi Harian</span>
    </a>

    <!-- 5. JURNAL KEGIATAN -->
    <a href="{{ route('siswa.jurnal.index') }}" class="pro-nav-link {{ request()->routeIs('siswa.jurnal.*') ? 'active' : '' }}">
        <i class="ph ph-book-open nav-icon"></i>
        <span>Jurnal Kegiatan</span>
    </a>

    <!-- 6. NILAI & SERTIFIKAT -->
    <a href="{{ route('siswa.nilai.index') }}" class="pro-nav-link {{ request()->routeIs('siswa.nilai.*') ? 'active' : '' }}">
        <i class="ph ph-list-checks nav-icon"></i>
        <span>Nilai & Sertifikat</span>
    </a>

    <!-- 7. PROFIL PENGGUNA -->
    <a href="{{ route('profile.edit') }}" class="pro-nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
        <i class="ph ph-user nav-icon"></i>
        <span>Profil Pengguna</span>
    </a>

    <!-- 8. TENTANG & BANTUAN -->
    <a href="{{ route('bantuan.index') }}" class="pro-nav-link {{ request()->routeIs('bantuan.*') ? 'active' : '' }}">
        <i class="ph ph-copyright nav-icon"></i>
        <span>Tentang & Bantuan</span>
    </a>

@elseif(Auth::user()->role?->nama_role === 'instruktur')
    <!-- 1. BERANDA -->
    <a href="{{ route('instruktur.dashboard') }}" class="pro-nav-link {{ request()->routeIs('instruktur.dashboard') ? 'active' : '' }}">
        <i class="ph ph-desktop nav-icon"></i>
        <span>Beranda DUDI</span>
    </a>

    <!-- 2. PESERTA MAGANG -->
    <a href="{{ route('instruktur.siswa.index') }}" class="pro-nav-link {{ request()->routeIs('instruktur.siswa.*') ? 'active' : '' }}">
        <i class="ph ph-users-three nav-icon"></i>
        <span>Siswa Magang</span>
    </a>

    <!-- 3. PRESENSI SISWA -->
    <a href="{{ route('instruktur.absensi.index') }}" class="pro-nav-link {{ request()->routeIs('instruktur.absensi.*') ? 'active' : '' }}">
        <i class="ph ph-calendar-check nav-icon"></i>
        <span>Presensi Siswa</span>
    </a>

    <!-- 4. VALIDASI JURNAL -->
    @php
        $dudiId = Auth::user()->pembimbingIndustri?->perusahaan_id;
        $pendingJurnalDudi = 0;
        if ($dudiId) {
            $pendingJurnalDudi = \App\Models\JurnalPkl::whereHas('penempatan', function($q) use ($dudiId) {
                $q->where('perusahaan_id', $dudiId);
            })->where('status_validasi', 'menunggu')->count();
        }
    @endphp
    <a href="{{ route('instruktur.jurnal.index') }}" class="pro-nav-link {{ request()->routeIs('instruktur.jurnal.*') ? 'active' : '' }}">
        <i class="ph ph-book-open-text nav-icon"></i>
        <span>Validasi Jurnal</span>
        @if($pendingJurnalDudi > 0)
            <span class="nav-badge">{{ $pendingJurnalDudi }}</span>
        @endif
    </a>

    <!-- 5. PENILAIAN PKL DUDI -->
    <a href="{{ route('instruktur.penilaian.index') }}" class="pro-nav-link {{ request()->routeIs('instruktur.penilaian.*') ? 'active' : '' }}">
        <i class="ph ph-list-checks nav-icon"></i>
        <span>Penilaian DUDI</span>
    </a>

    <!-- 6. PROFIL SAYA & DUDI -->
    <a href="{{ route('instruktur.profil.index') }}" class="pro-nav-link {{ request()->routeIs('instruktur.profil.*') ? 'active' : '' }}">
        <i class="ph ph-user-gear nav-icon"></i>
        <span>Profil Instruktur</span>
    </a>

    <!-- 7. TENTANG & BANTUAN -->
    <a href="{{ route('bantuan.index') }}" class="pro-nav-link {{ request()->routeIs('bantuan.*') ? 'active' : '' }}">
        <i class="ph ph-copyright nav-icon"></i>
        <span>Tentang & Bantuan</span>
    </a>
@endif
