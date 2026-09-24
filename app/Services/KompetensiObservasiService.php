<?php

namespace App\Services;

class KompetensiObservasiService
{
    /**
     * Daftar preset standar Capaian / Kompetensi Teknis per Jurusan (RPL, TKJ, AK, MP, BR)
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
            'AK' => [
                'nama' => 'Akuntansi (AK)',
                'kompetensi' => [
                    'Memverifikasi dan mengelola bukti transaksi keuangan serta dokumen kas',
                    'Melakukan pencatatan jurnal umum, jurnal khusus, dan posting buku besar',
                    'Mengoperasikan aplikasi komputer akuntansi (MYOB / Accurate / Spreadsheet Excel)',
                    'Menyusun laporan keuangan periodik (Laba Rugi, Neraca, Perubahan Modal, Arus Kas)',
                    'Mengelola administrasi kas kecil (Petty Cash) dan rekonsiliasi bank',
                    'Melakukan perhitungan dan administrasi perpajakan sederhana (PPh / PPN)',
                ]
            ],
            'MP' => [
                'nama' => 'Manajemen Perkantoran (MP)',
                'kompetensi' => [
                    'Mengelola korespondensi dan tata naskah surat dinas/bisnis (masuk & keluar)',
                    'Melakukan sistem kearsipan dokumen fisik dan digital (E-filing system)',
                    'Memberikan pelayanan prima kepada tamu kantor dan komunikasi telepon profesional',
                    'Mengoperasikan aplikasi perkantoran (Word processing, Spreadsheet, Presentasi)',
                    'Mengatur agenda pimpinan, reservasi perjalanan dinas, dan notulensi rapat kerja',
                    'Mengelola kas kecil kantor dan inventarisasi sarana prasarana kerja',
                ]
            ],
            'BR' => [
                'nama' => 'Bisnis Ritel (BR)',
                'kompetensi' => [
                    'Melakukan riset pasar, identifikasi segmen target, dan analisis produk kompetitor',
                    'Mengelola toko online dan katalog produk di platform E-Commerce / Marketplace',
                    'Merancang dan menjalankan materi promosi digital di media sosial',
                    'Menerapkan teknik copywriting promosi dan penanganan komunikasi pelanggan (Customer Service)',
                    'Melakukan display produk (Visual Merchandising) dan penataan stok barang di toko/ritel',
                    'Melakukan operasional transaksi kasir (POS), scan barcode, dan rekapitulasi penjualan harian',
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

        // 1. Direct match on code
        if (isset($presets[$namaUpper])) {
            return $presets[$namaUpper]['kompetensi'];
        }

        // 2. Keyword detection
        if (str_contains($namaUpper, 'RPL') || str_contains($namaUpper, 'PERANGKAT LUNAK') || str_contains($namaUpper, 'SOFTWARE') || (str_contains($namaUpper, 'KOMPUTER') && str_contains($namaUpper, 'PROGRAM'))) {
            return $presets['RPL']['kompetensi'];
        }
        if (str_contains($namaUpper, 'TKJ') || str_contains($namaUpper, 'JARINGAN') || str_contains($namaUpper, 'NETWORK')) {
            return $presets['TKJ']['kompetensi'];
        }
        if (str_contains($namaUpper, 'AK') || str_contains($namaUpper, 'AKL') || str_contains($namaUpper, 'AKUNTANSI') || str_contains($namaUpper, 'KEUANGAN')) {
            return $presets['AK']['kompetensi'];
        }
        if (str_contains($namaUpper, 'MP') || str_contains($namaUpper, 'MPLB') || str_contains($namaUpper, 'PERKANTORAN') || str_contains($namaUpper, 'OTKP') || str_contains($namaUpper, 'SEKRETARIS')) {
            return $presets['MP']['kompetensi'];
        }
        if (str_contains($namaUpper, 'BR') || str_contains($namaUpper, 'RITEL') || str_contains($namaUpper, 'RETAIL') || str_contains($namaUpper, 'BD') || str_contains($namaUpper, 'BDP') || str_contains($namaUpper, 'PEMASARAN') || str_contains($namaUpper, 'BISNIS') || str_contains($namaUpper, 'MARKETING')) {
            return $presets['BR']['kompetensi'];
        }

        return $presets['RPL']['kompetensi'];
    }
}

