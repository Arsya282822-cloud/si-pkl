<?php

namespace App\Services;

class KompetensiObservasiService
{
    /**
     * Daftar preset standar Capaian / Kompetensi Teknis per Jurusan
     */
    public static function getPresets(): array
    {
        return [
            'RPL' => [
                'nama' => 'Rekayasa Perangkat Lunak (RPL)',
                'kompetensi' => [
                    'Merancang dan membangun antarmuka pengguna (UI/UX) web atau aplikasi mobile',
                    'Mengembangkan kode program logika bisnis backend dan manipulasi database (SQL/ORM)',
                    'Membuat dan mengintegrasikan RESTful API serta layanan web',
                    'Melakukan pengujian perangkat lunak (testing), penanganan bug, dan debugging',
                    'Menerapkan sistem kontrol versi (Git/GitHub) dan standar clean code',
                    'Melakukan deployment, konfigurasi web server, dan pemeliharaan sistem informasi',
                ]
            ],
            'TKJ' => [
                'nama' => 'Teknik Komputer dan Jaringan (TKJ)',
                'kompetensi' => [
                    'Merakit, menginstalasi, dan menguji perangkat keras komputer serta sistem operasi',
                    'Melakukan pengkabelan jaringan LAN (Crimping UTP/STP) dan penyambungan Fiber Optic',
                    'Melakukan konfigurasi perangkat jaringan (Router Mikrotik/Cisco, Switch Managed, VLAN)',
                    'Mengkonfigurasi layanan server (DHCP, DNS, Web Server, File Server, SSH)',
                    'Mengatur manajemen bandwidth, firewall, dan keamanan jaringan',
                    'Melakukan instalasi dan troubleshooting wireless network (Access Point / Hotspot Gateway)',
                ]
            ],
            'DKV' => [
                'nama' => 'Desain Komunikasi Visual (DKV)',
                'kompetensi' => [
                    'Merancang identitas visual (Logo, Brand Guideline, Typography, Stationery)',
                    'Membuat materi desain grafis promosi (Poster, Banner, Brosur, Feed Medsos)',
                    'Mengoperasikan perangkat lunak desain standar industri (Photoshop, Illustrator, CorelDraw)',
                    'Melakukan pengambilan foto/video dokumentasi produk dan editing digital',
                    'Melakukan editing video, motion graphics, dan animasi promosi',
                    'Menyiapkan layout dan berkas siap cetak (Pre-press standard)',
                ]
            ],
            'AKL' => [
                'nama' => 'Akuntansi dan Keuangan Lembaga (AKL)',
                'kompetensi' => [
                    'Memverifikasi dan mengelola bukti transaksi keuangan serta dokumen kas',
                    'Melakukan pencatatan jurnal umum, jurnal khusus, dan posting buku besar',
                    'Mengoperasikan aplikasi komputer akuntansi (MYOB / Accurate / Spreadsheet)',
                    'Menyusun laporan keuangan periodik (Laba Rugi, Neraca, Perubahan Modal, Arus Kas)',
                    'Mengelola administrasi kas kecil (Petty Cash) dan rekonsiliasi bank',
                    'Melakukan perhitungan dan administrasi perpajakan sederhana (PPh / PPN)',
                ]
            ],
            'MPLB' => [
                'nama' => 'Manajemen Perkantoran dan Layanan Bisnis (MP/OTKP)',
                'kompetensi' => [
                    'Mengelola korespondensi dan tata naskah surat dinas/bisnis (masuk & keluar)',
                    'Melakukan sistem kearsipan dokumen fisik dan digital (E-filing system)',
                    'Memberikan pelayanan prima kepada tamu kantor dan komunikasi telepon profesional',
                    'Mengoperasikan aplikasi perkantoran (Word processing, Spreadsheet, Presentasi)',
                    'Mengatur agenda pimpinan, reservasi perjalanan dinas, dan notulensi rapat kerja',
                    'Mengelola kas kecil kantor dan inventarisasi sarana prasarana kerja',
                ]
            ],
            'BD' => [
                'nama' => 'Bisnis Digital / Pemasaran (BDP)',
                'kompetensi' => [
                    'Melakukan riset pasar, identifikasi segmen target, dan analisis produk kompetitor',
                    'Mengelola toko online dan katalog produk di platform E-Commerce / Marketplace',
                    'Merancang dan menjalankan kampanye digital marketing di media sosial',
                    'Menerapkan teknik copywriting promosi dan penanganan pesan pelanggan (Live Chat)',
                    'Melakukan display produk (Visual Merchandising) dan penataan stok barang',
                    'Melakukan transaksi penjualan, kasir (POS), dan rekapitulasi penjualan harian',
                ]
            ],
            'TKRO' => [
                'nama' => 'Teknik Kendaraan Ringan Otomotif (TKRO)',
                'kompetensi' => [
                    'Melakukan perawatan berkala mesin (Engine tune-up) dan penggantian oli/filter',
                    'Memeriksa dan memperbaiki sistem rem, kemudi, dan suspensi kendaraan',
                    'Menggunakan scanner diagnostic tools (OBD) untuk mendeteksi gangguan mesin',
                    'Memperbaiki dan merawat sistem kelistrikan body, starter, dan pengisian',
                    'Melakukan servis sistem transmisi, kopling, dan pemindah tenaga',
                    'Menerapkan standar K3LH bengkel dan penggunaan alat ukur presisi',
                ]
            ],
            'TBSM' => [
                'nama' => 'Teknik dan Bisnis Sepeda Motor (TBSM)',
                'kompetensi' => [
                    'Melakukan servis berkala mesin sepeda motor karburator dan injeksi (FI)',
                    'Memeriksa dan menyetel sistem bahan bakar, pengapian, dan klep mesin',
                    'Memperbaiki dan merawat sistem CVT dan rantai penggerak roda',
                    'Memperbaiki sistem pengereman, suspensi, dan roda sepeda motor',
                    'Menggunakan alat diagnostic scanner injeksi dan multitester',
                    'Menerapkan SOP bengkel resmi dan pelayanan kepuasan pelanggan',
                ]
            ],
        ];
    }

    /**
     * Get list of default soft skills
     */
    public static function getSoftSkills(): array
    {
        return [
            'Etika dalam berkomunikasi lisan dan tulisan',
            'Integritas dalam bekerja (Jujur, Disiplin, komitmen, dan tanggung jawab)',
            'Bekerja secara mandiri',
            'Bekerja secara tim',
            'Kepedulian sosial',
            'Ketaatan terhadap norma dan POS yang berlaku',
            'K3LH di lingkungan kerja.',
        ];
    }

    /**
     * Get list of default business analysis
     */
    public static function getAnalisisUsaha(): array
    {
        return [
            'Menjelaskan bidang usaha/pekerjaan, alur bisnis/kerja tempat PKL.',
            'Memasarkan produk/jasa dengan menentukan harga produk dan segmen pasar.',
            'Menentukan media yang tepat untuk mempromosikan produk/jasa',
            'Memberikan layanan terhadap keluhan pelanggan',
        ];
    }

    /**
     * Find best matching competencies by jurusan string or code
     */
    public static function getKompetensiByJurusan(?string $namaOrKode): array
    {
        if (!$namaOrKode) {
            return self::getPresets()['RPL']['kompetensi'];
        }

        $namaUpper = strtoupper($namaOrKode);
        $presets = self::getPresets();

        foreach ($presets as $code => $data) {
            if (str_contains($namaUpper, $code) || str_contains($namaUpper, strtoupper($data['nama']))) {
                return $data['kompetensi'];
            }
        }

        // Keywords detection
        if (str_contains($namaUpper, 'PERANGKAT LUNAK') || str_contains($namaUpper, 'SOFTWARE') || str_contains($namaUpper, 'KOMPUTER') && str_contains($namaUpper, 'PROGRAM')) {
            return $presets['RPL']['kompetensi'];
        }
        if (str_contains($namaUpper, 'JARINGAN') || str_contains($namaUpper, 'NETWORK') || str_contains($namaUpper, 'TKJ')) {
            return $presets['TKJ']['kompetensi'];
        }
        if (str_contains($namaUpper, 'DESAIN') || str_contains($namaUpper, 'VISUAL') || str_contains($namaUpper, 'DKV') || str_contains($namaUpper, 'MULTIMEDIA')) {
            return $presets['DKV']['kompetensi'];
        }
        if (str_contains($namaUpper, 'AKUNTANSI') || str_contains($namaUpper, 'KEUANGAN') || str_contains($namaUpper, 'AKL')) {
            return $presets['AKL']['kompetensi'];
        }
        if (str_contains($namaUpper, 'PERKANTORAN') || str_contains($namaUpper, 'OTKP') || str_contains($namaUpper, 'SEKRETARIS') || str_contains($namaUpper, 'MPLB')) {
            return $presets['MPLB']['kompetensi'];
        }
        if (str_contains($namaUpper, 'PEMASARAN') || str_contains($namaUpper, 'BISNIS') || str_contains($namaUpper, 'MARKETING')) {
            return $presets['BD']['kompetensi'];
        }
        if (str_contains($namaUpper, 'KENDARAAN') || str_contains($namaUpper, 'MOBIL') || str_contains($namaUpper, 'OTOMOTIF') || str_contains($namaUpper, 'TKRO')) {
            return $presets['TKRO']['kompetensi'];
        }
        if (str_contains($namaUpper, 'MOTOR') || str_contains($namaUpper, 'TBSM')) {
            return $presets['TBSM']['kompetensi'];
        }

        return $presets['RPL']['kompetensi'];
    }
}
