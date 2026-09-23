@extends('layouts.app')

@section('title', 'Pusat Bantuan & FAQ PKL')

@section('content')
<div class="container-fluid px-0">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 d-flex align-items-center gap-2 mb-4" role="alert" style="border-radius: 12px; background: #dcfce7; color: #166534;">
            <i class="ph ph-check-circle" style="font-size: 22px;"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 mb-4" role="alert" style="border-radius: 12px;">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Header Banner -->
    <div class="pro-card p-4 mb-4" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); color: white; border: none;">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-2" style="background: rgba(255,255,255,0.15); font-size: 0.8rem; font-weight: 600;">
                    <i class="ph ph-question"></i> Panduan & Bantuan Resmi
                </div>
                <h2 class="h4 fw-bold mb-1">Pusat Bantuan & FAQ PKL</h2>
                <p class="mb-0 text-white-50" style="font-size: 0.9rem;">
                    Informasi prosedur operasional standar (SOP), tata tertib di industri, panduan sistem SI-PKL, dan kontak Pokja PKL SMK Labor FKIP UNRI.
                </p>
            </div>
            @if(Auth::user()->role?->nama_role === 'admin')
                <div>
                    <button type="button" class="btn btn-light d-inline-flex align-items-center gap-2 px-3.5 py-2 text-primary fw-semibold" data-bs-toggle="modal" data-bs-target="#modalEditKontak" style="border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                        <i class="ph ph-pencil-simple" style="font-size: 18px;"></i>
                        <span>Edit Kontak Bantuan</span>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- 3 Highlight Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="pro-card p-4 h-100">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                        <i class="ph ph-clock"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">Presensi & Kehadiran</h6>
                        <span class="text-muted small">Waktu & GPS</span>
                    </div>
                </div>
                <p class="small text-muted mb-0" style="line-height: 1.6;">
                    Siswa wajib melakukan presensi masuk di awal jam kerja dan presensi keluar di akhir jam kerja saat berada di lokasi industri.
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="pro-card p-4 h-100">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: #fef9c3; color: #ca8a04; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                        <i class="ph ph-book-open"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">Pengisian Jurnal</h6>
                        <span class="text-muted small">Logbook Harian</span>
                    </div>
                </div>
                <p class="small text-muted mb-0" style="line-height: 1.6;">
                    Catat rincian pekerjaan dan upload dokumentasi foto setiap hari. Guru pembimbing akan memverifikasi catatan mingguan Anda.
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="pro-card p-4 h-100">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                        <i class="ph ph-medal"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">Penilaian & Sertifikat</h6>
                        <span class="text-muted small">Syarat Kelulusan</span>
                    </div>
                </div>
                <p class="small text-muted mb-0" style="line-height: 1.6;">
                    Nilai akhir merupakan gabungan aspek Sikap, Keterampilan, dan Pengetahuan. Sertifikat kelulusan dilengkapi verifikasi QR Code resmi.
                </p>
            </div>
        </div>
    </div>

    <!-- FAQ Accordion & Contact Pokja -->
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="pro-card p-4 p-md-5">
                <h5 class="fw-bold text-dark mb-4 d-flex align-items-center gap-2">
                    <i class="ph ph-chats-circle text-primary"></i> Pertanyaan yang Sering Diajukan (FAQ)
                </h5>

                <div class="accordion accordion-flush" id="faqAccordion">
                    <!-- FAQ 1 -->
                    <div class="accordion-item border-bottom">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed fw-semibold text-dark px-0" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                1. Bagaimana jika saya ingin mengajukan tempat PKL di perusahaan pilihan sendiri?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                            <div class="accordion-body px-0 text-muted small" style="line-height: 1.7;">
                                Anda dapat membuka menu <strong>Tempat PKL &rarr; Pengajuan Mandiri</strong> pada portal siswa, kemudian isi formulir profil perusahaan beserta kontak narahubung HRD. Usulan Anda akan ditinjau oleh Tim Pokja PKL untuk diverifikasi kesesuaian jurusannya.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="accordion-item border-bottom">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed fw-semibold text-dark px-0" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                2. Bagaimana prosedur jika saya berhalangan hadir (Izin / Sakit)?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                            <div class="accordion-body px-0 text-muted small" style="line-height: 1.7;">
                                Buka menu <strong>Presensi Harian &rarr; Ajukan Izin / Sakit</strong>, pilih status, dan tuliskan alasan secara jelas. Selain di sistem, Anda wajib memberitahukan langsung kepada Pembimbing Lapangan di industri serta Guru Pembimbing sekolah.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="accordion-item border-bottom">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed fw-semibold text-dark px-0" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                3. Kapan saya bisa mencetak Buku Jurnal / Logbook PKL?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                            <div class="accordion-body px-0 text-muted small" style="line-height: 1.7;">
                                Buku Jurnal lengkap dapat dicetak kapan saja melalui menu <strong>Jurnal Kegiatan &rarr; Cetak Buku Logbook PKL</strong>. Cetakan ini sudah berformat siap jilid dengan lembar pengesahan, rekap presensi, dan rincian tugas harian untuk keperluan sidang laporan PKL.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 4 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed fw-semibold text-dark px-0" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                4. Apa saja kriteria penilaian kelulusan PKL?
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                            <div class="accordion-body px-0 text-muted small" style="line-height: 1.7;">
                                Penilaian terdiri dari 3 aspek: Nilai Sikap (Kedisiplinan, Tanggung Jawab, Etika), Nilai Keterampilan (Kualitas Hasil Kerja Teknis), dan Nilai Pengetahuan (Pemahaman Teori & Troubleshooting). Nilai minimum kelulusan adalah 75.0 (Predikat B).
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Emergency / Secretariat Contact -->
        <div class="col-lg-4">
            <div class="pro-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                        <i class="ph ph-phone-call text-primary"></i> Kontak Pokja PKL & Hubin
                    </h6>
                    @if(Auth::user()->role?->nama_role === 'admin')
                        <button type="button" class="btn btn-sm btn-outline-primary px-2 py-1" data-bs-toggle="modal" data-bs-target="#modalEditKontak" title="Edit Data Kontak">
                            <i class="ph ph-pencil-simple"></i> Edit
                        </button>
                    @endif
                </div>
                
                <p class="small text-muted mb-3">
                    Jika terjadi kendala operasional, darurat di lokasi industri, atau kendala akun, silakan hubungi:
                </p>

                <!-- Box 1: Sekretariat -->
                <div class="p-3 bg-light rounded-3 mb-3 border">
                    <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $kontak['sekretariat_nama'] ?? 'Sekretariat Pokja PKL SMK Labor' }}</div>
                    <div class="small text-muted mt-1"><i class="ph ph-map-pin me-1 text-primary"></i> {{ $kontak['sekretariat_lokasi'] ?? 'Gedung Hubinmas Lantai 1' }}</div>
                    <div class="small text-muted"><i class="ph ph-envelope me-1 text-primary"></i> <a href="mailto:{{ $kontak['sekretariat_email'] ?? 'hubin@smklabor.sch.id' }}" class="text-decoration-none text-muted">{{ $kontak['sekretariat_email'] ?? 'hubin@smklabor.sch.id' }}</a></div>
                    <div class="small text-muted"><i class="ph ph-phone me-1 text-primary"></i> {{ $kontak['sekretariat_telepon'] ?? '(0761) 853245' }}</div>
                    @if(!empty($kontak['jam_layanan']))
                        <div class="small text-muted mt-1"><i class="ph ph-clock me-1 text-primary"></i> {{ $kontak['jam_layanan'] }}</div>
                    @endif
                </div>

                <!-- Box 2: Ketua Pokja CP WhatsApp -->
                @php
                    $waNumberClean = preg_replace('/[^0-9]/', '', $kontak['cp_whatsapp'] ?? '081275001122');
                    if (str_starts_with($waNumberClean, '0')) {
                        $waNumberClean = '62' . substr($waNumberClean, 1);
                    }
                @endphp
                <div class="p-3 bg-light rounded-3 border">
                    <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $kontak['cp_jabatan'] ?? 'Ketua Pokja Hubin & PKL' }}</div>
                    <div class="small text-dark fw-semibold mt-1">{{ $kontak['cp_nama'] ?? 'Dedi Hendrawan, S.Kom., M.Kom.' }}</div>
                    <div class="mt-2">
                        <a href="https://wa.me/{{ $waNumberClean }}?text=Halo%20Admin%20Pokja%20PKL%20SMK%20Labor,%20saya%20ingin%20bertanya%20mengenai%20PKL" target="_blank" class="btn btn-sm btn-success d-inline-flex align-items-center gap-1.5 px-3" style="border-radius: 8px;">
                            <i class="ph ph-whatsapp-logo" style="font-size: 16px;"></i>
                            <span>{{ $kontak['cp_whatsapp'] ?? '0812-7500-1122' }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(Auth::user()->role?->nama_role === 'admin')
<!-- Modal Edit Kontak CP & Sekretariat -->
<div class="modal fade" id="modalEditKontak" tabindex="-1" aria-labelledby="modalEditKontakLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px;">
            <form action="{{ route('bantuan.update_kontak') }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="modalEditKontakLabel">
                        <i class="ph ph-pencil-simple text-primary me-1"></i> Edit Kontak Bantuan & CP Pokja
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">Informasi kontak ini akan tampil pada halaman Pusat Bantuan bagi seluruh Siswa dan Guru Pembimbing.</p>

                    <h6 class="fw-bold text-primary mb-2" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">1. Informasi Sekretariat</h6>
                    <div class="mb-2.5">
                        <label class="form-label small fw-semibold">Nama Sekretariat / Kantor</label>
                        <input type="text" name="sekretariat_nama" class="form-control" value="{{ $kontak['sekretariat_nama'] ?? 'Sekretariat Pokja PKL SMK Labor' }}" required style="border-radius: 8px;">
                    </div>
                    <div class="mb-2.5">
                        <label class="form-label small fw-semibold">Lokasi / Ruangan</label>
                        <input type="text" name="sekretariat_lokasi" class="form-control" value="{{ $kontak['sekretariat_lokasi'] ?? 'Gedung Hubinmas Lantai 1' }}" required style="border-radius: 8px;">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Email Bantuan</label>
                            <input type="email" name="sekretariat_email" class="form-control" value="{{ $kontak['sekretariat_email'] ?? 'hubin@smklabor.sch.id' }}" required style="border-radius: 8px;">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Telepon Kantor</label>
                            <input type="text" name="sekretariat_telepon" class="form-control" value="{{ $kontak['sekretariat_telepon'] ?? '(0761) 853245' }}" required style="border-radius: 8px;">
                        </div>
                    </div>

                    <h6 class="fw-bold text-primary mb-2" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">2. Contact Person (CP) & Narahubung</h6>
                    <div class="mb-2.5">
                        <label class="form-label small fw-semibold">Nama Narahubung / Pejabat</label>
                        <input type="text" name="cp_nama" class="form-control" value="{{ $kontak['cp_nama'] ?? 'Dedi Hendrawan, S.Kom., M.Kom.' }}" required style="border-radius: 8px;">
                    </div>
                    <div class="row g-2 mb-2.5">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Jabatan / Posisi</label>
                            <input type="text" name="cp_jabatan" class="form-control" value="{{ $kontak['cp_jabatan'] ?? 'Ketua Pokja Hubin & PKL' }}" required style="border-radius: 8px;">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Nomor WhatsApp / HP</label>
                            <input type="text" name="cp_whatsapp" class="form-control" value="{{ $kontak['cp_whatsapp'] ?? '0812-7500-1122' }}" placeholder="0812-xxxx-xxxx" required style="border-radius: 8px;">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Jam Layanan Operasional</label>
                        <input type="text" name="jam_layanan" class="form-control" value="{{ $kontak['jam_layanan'] ?? 'Senin - Jumat (07.30 - 16.00 WIB)' }}" placeholder="Senin - Jumat (07.30 - 16.00 WIB)" style="border-radius: 8px;">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1.5 px-3 fw-semibold">
                        <i class="ph ph-floppy-disk"></i> Simpan Kontak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection
