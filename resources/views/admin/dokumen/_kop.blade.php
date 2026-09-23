<!-- KOP SURAT RESMI SMK LABOR BINAAN FKIP UNRI PEKANBARU (FONT: TAHOMA) -->
@php
    $kopYayasan = \App\Models\Setting::get('sekolah_nama_yayasan', 'YAYASAN UNIV RIAU');
    $kopSekolah = \App\Models\Setting::get('sekolah_nama_sekolah', 'LABOR BINAAN FKIP UNRI PEKANBARU');
    $kopAlamat = \App\Models\Setting::get('sekolah_alamat', 'Jl. Thamrin No. 97 Kec. Sail Pekanbaru – 28132');
    $kopTelepon = \App\Models\Setting::get('sekolah_telepon', '0761 – 28760');
    $kopWebsite = \App\Models\Setting::get('sekolah_website', 'www.smklabor.sch.id');
    $kopEmail = \App\Models\Setting::get('sekolah_email', 'humas@smklabor.sch.id');
@endphp
<div class="kop-surat-wrapper" style="font-family: Tahoma, Verdana, sans-serif; color: #000; width: 100%; margin-bottom: 0;">
    
    <!-- 1. Baris Atas: Logo Kiri + Teks Nama Sekolah + 2 Logo Kanan -->
    <table style="width: 100%; border-collapse: collapse; margin: 0; padding: 0;">
        <tr>
            <!-- Logo Kiri: Tut Wuri Handayani (2.16 cm x 2.07 cm) -->
            <td style="width: 2.2cm; vertical-align: middle; text-align: left; padding: 0;">
                <img src="{{ asset('images/logo-tutwuri.png') }}" alt="Logo Tut Wuri" style="width: 2.16cm; height: 2.07cm; object-fit: contain; display: block;">
            </td>

            <!-- Teks Tengah: Nama Yayasan & Sekolah -->
            <td style="vertical-align: middle; text-align: center; padding: 0 4px;">
                <div style="font-size: 15px; font-weight: normal; letter-spacing: 0.5px; line-height: 1.25; margin: 0; padding: 0; text-transform: uppercase;">{{ $kopYayasan }}</div>
                <div style="font-size: 14px; font-weight: normal; letter-spacing: 0.5px; line-height: 1.25; margin: 1px 0 0 0; padding: 0;">SEKOLAH MENENGAH KEJURUAN (SMK)</div>
                <div style="font-size: 17px; font-weight: bold; letter-spacing: 0.3px; line-height: 1.25; margin: 2px 0 0 0; padding: 0; white-space: nowrap;">{{ str_replace(['SEKOLAH MENENGAH KEJURUAN (SMK)', 'SMK '], '', $kopSekolah) }}</div>
            </td>

            <!-- Logo Kanan: Yayasan Univ Riau & SMK Labor (2.16 cm x 2.07 cm) -->
            <td style="width: 4.5cm; vertical-align: middle; text-align: right; padding: 0; white-space: nowrap;">
                <img src="{{ asset('images/logo-yayasan-unri.png') }}" alt="Logo Yayasan" style="width: 2.16cm; height: 2.07cm; object-fit: contain; display: inline-block; vertical-align: middle; margin-right: 4px;">
                <img src="{{ asset('images/logo-smk-labor.png') }}" alt="Logo SMK Labor" style="width: 2.16cm; height: 2.07cm; object-fit: contain; display: inline-block; vertical-align: middle;">
            </td>
        </tr>
    </table>

    <!-- 2. Baris Alamat Lengkap & Kontak (Lebar Penuh Melintang Bebas di Bawah Logo) -->
    <div style="text-align: center; font-size: 9.2px; font-weight: normal; margin-top: 3px; line-height: 1.25; white-space: nowrap;">
        {{ $kopAlamat }} Telp./Fax. {{ $kopTelepon }}, <span style="color: #0000ee; text-decoration: underline;">{{ $kopWebsite }}</span> e-mail: <span style="color: #0000ee; text-decoration: underline;">{{ $kopEmail }}</span>
    </div>


    <!-- 3. Garis Pemisah Kop Surat (DI ATAS KOMPETENSI & KONSENTRASI) -->
    <div style="border-bottom: 2px solid #000; margin-top: 5px; margin-bottom: 5px;"></div>

    <!-- 4. Rincian Kompetensi (Bold) & Konsentrasi (Bold) - List Items Normal -->
    <table style="width: 100%; border-collapse: collapse; font-size: 8.8px; line-height: 1.35; margin-bottom: 10px;">
        <tr>
            <td style="width: 53%; vertical-align: top; text-align: left; padding: 0;">
                <div style="font-weight: bold; margin-bottom: 1px;">KOMPETENSI KEAHLIAN</div>
                <div style="font-weight: normal;">• TEKNIK JARINGAN KOMPUTER DAN TELEKOMUNIKASI</div>
                <div style="font-weight: normal;">• PENGEMBANGAN PERANGKAT LUNAK DAN GIM</div>
                <div style="font-weight: normal;">• AKUNTANSI DAN KUANGAN LEMBAGA</div>
                <div style="font-weight: normal;">• MANAJEMEN PERKANTORAN DAN LAYANAN BISNIS</div>
                <div style="font-weight: normal;">• PEMASARAN</div>
            </td>
            <td style="width: 47%; vertical-align: top; text-align: right; padding: 0;">
                <div style="font-weight: bold; margin-bottom: 1px;">KONSENTRASI KEAHLIAN</div>
                <div style="font-weight: normal;">* TEKNIK KOMPUTER DAN JARINGAN</div>
                <div style="font-weight: normal;">* REKAYASA PERANGKAT LUNAK</div>
                <div style="font-weight: normal;">* AKUNTANSI</div>
                <div style="font-weight: normal;">* MANAJEMEN PERKANTORAN</div>
                <div style="font-weight: normal;">* BISNIS RETAIL</div>
            </td>
        </tr>
    </table>
</div>
