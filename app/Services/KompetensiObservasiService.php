<?php

namespace App\Services;

use App\Models\TujuanPembelajaran;

class KompetensiObservasiService
{
    /**
     * Resolve kode jurusan from string / code
     */
    public static function resolveKodeJurusan(?string $namaOrKode): string
    {
        if (! $namaOrKode) {
            return 'RPL';
        }

        $namaUpper = strtoupper($namaOrKode);

        if (in_array($namaUpper, ['RPL', 'TKJ', 'AK', 'MP', 'BR'])) {
            return $namaUpper;
        }

        if (str_contains($namaUpper, 'RPL') || str_contains($namaUpper, 'PERANGKAT LUNAK') || str_contains($namaUpper, 'SOFTWARE') || (str_contains($namaUpper, 'KOMPUTER') && str_contains($namaUpper, 'PROGRAM'))) {
            return 'RPL';
        }
        if (str_contains($namaUpper, 'TKJ') || str_contains($namaUpper, 'JARINGAN') || str_contains($namaUpper, 'NETWORK')) {
            return 'TKJ';
        }
        if (str_contains($namaUpper, 'AK') || str_contains($namaUpper, 'AKL') || str_contains($namaUpper, 'AKUNTANSI') || str_contains($namaUpper, 'KEUANGAN')) {
            return 'AK';
        }
        if (str_contains($namaUpper, 'MP') || str_contains($namaUpper, 'MPLB') || str_contains($namaUpper, 'PERKANTORAN') || str_contains($namaUpper, 'OTKP') || str_contains($namaUpper, 'SEKRETARIS')) {
            return 'MP';
        }
        if (str_contains($namaUpper, 'BR') || str_contains($namaUpper, 'RITEL') || str_contains($namaUpper, 'RETAIL') || str_contains($namaUpper, 'BD') || str_contains($namaUpper, 'BDP') || str_contains($namaUpper, 'PEMASARAN') || str_contains($namaUpper, 'BISNIS') || str_contains($namaUpper, 'MARKETING')) {
            return 'BR';
        }

        return 'RPL';
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
     * Find standard competencies (Capaian 1: POS Dunia Kerja) for Lembar Observasi
     */
    public static function getKompetensiByJurusan(?string $namaOrKode): array
    {
        $kode = self::resolveKodeJurusan($namaOrKode);

        // Try getting from database first
        $dbTps = TujuanPembelajaran::where('kode_jurusan', $kode)
            ->where('status', 'aktif')
            ->where('capaian_pembelajaran', 'like', '%POS%')
            ->orderBy('nomor_urut')
            ->take(6)
            ->pluck('tujuan_pembelajaran')
            ->toArray();

        if (! empty($dbTps)) {
            return $dbTps;
        }

        return self::getPresets()[$kode]['kompetensi'] ?? self::getPresets()['RPL']['kompetensi'];
    }

    /**
     * Find new competencies (Capaian 2: Kompetensi Baru/Lanjutan) for Lembar Observasi
     */
    public static function getKompetensiBaruByJurusan(?string $namaOrKode): array
    {
        $kode = self::resolveKodeJurusan($namaOrKode);

        return TujuanPembelajaran::where('kode_jurusan', $kode)
            ->where('status', 'aktif')
            ->where('capaian_pembelajaran', 'like', '%belum tuntas%')
            ->orderBy('nomor_urut')
            ->take(3)
            ->pluck('tujuan_pembelajaran')
            ->toArray();
    }

    /**
     * Get ALL Tujuan Pembelajaran for a jurusan grouped by Capaian
     */
    public static function getAllTujuanPembelajaranByJurusan(?string $namaOrKode)
    {
        $kode = self::resolveKodeJurusan($namaOrKode);

        return TujuanPembelajaran::where('kode_jurusan', $kode)
            ->where('status', 'aktif')
            ->orderBy('capaian_pembelajaran')
            ->orderBy('nomor_urut')
            ->get()
            ->groupBy('capaian_pembelajaran');
    }

    /**
     * Fallback preset list
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
                ],
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
                ],
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
                ],
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
                ],
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
                ],
            ],
        ];
    }
}
