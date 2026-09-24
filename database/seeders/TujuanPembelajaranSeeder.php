<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jurusan;
use App\Models\TujuanPembelajaran;
use Illuminate\Support\Facades\DB;

class TujuanPembelajaranSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate to start fresh
        TujuanPembelajaran::truncate();

        // Get Jurusan Map
        $jurusans = Jurusan::all()->keyBy('kode_jurusan');

        $cp1 = 'Menerapkan kompetensi teknis pada pekerjaan sesuai POS yang berlaku di dunia kerja';
        $cp2 = 'Menerapkan kompetensi teknis baru/atau kompetensi teknis yang belum tuntas dipelajari sesuai konsentrasi keahlian';

        $data = [];

        // ==========================================
        // 1. AKUNTANSI (AK)
        // ==========================================
        $akId = $jurusans['AK']->id ?? null;

        $akTp1 = [
            'Menganalisis jenis – jenis perusahaan dalam akuntansi',
            'Menganalisis siklus akuntansi',
            'Menganalisis Perkembangan teknologi akuntansi',
            'Menganalisis permasalahan ekonomi',
            'Menerapkan ilmu ekonomi dalam kegiatan usaha',
            'Menerapkan administrasi dalam akuntansi dan keuangan lembaga',
            'Mendeskripsikan fungsi manajemen dalam akuntansi dan keuangan lembaga',
            'Menerapkan regulasi dan standar yang mengatur etika profesi akuntansi dan keuangan lembaga',
            'Menerapkan kode etik dalam praktik akuntansi dan keuangan lembaga, prinsip – prinsip etika profesi akuntansi dan keuangan Lembaga',
            'Menerapkan praktik - praktik kesehatan diri dan keselamatan kerja, praktik budaya kerja 5R',
            'Menerapkan dasar - dasar akuntansi',
            'Menerapkan dasar - dasar perbankan, produk dan jasa layanan perbankan',
            'Menerapkan penggunaan paket program pengolah angka (spreadsheet).',
            'Menganalisis dokumen sumber dan dokumen pendukung pada perusahaan (entitas) wajib pajak pribadi dan badan, baik PKP maupun non PKP',
            'Menerapkan proses pencatatan transaksi kedalam jurnal umum atau khusus',
            'Menerapkan pencatatan transaksi kedalam buku pembantu kartu piutang',
            'Menerapkan pencatatan transaksi kedalam buku pembantu kartu hutang',
            'Menerapkan pencatatan transaksi kedalam buku pembantu kartu persediaan barang dagang',
            'Menerapkan posting jurnal umum atau khusus ke dalam buku besar',
            'Menyusun neraca saldo',
            'Menganalisis transaksi penyesuaian, dan menyusun neraca lajur (worksheet)',
            'Menerapkan posting jurnal penyesuaian ke dalam buku besar',
            'Menyusun laporan laba/rugi, laporan perubahan ekuitas (perubahan modal), laporan posisi keuangan (neraca), laporan arus kas, dan catatan atas laporan keuangan.',
            'Menyusun jurnal penutup, menerapkan posting jurnal penutup ke dalam buku besar',
            'Menyusun neraca saldo setelah penutupan.',
            'Menerapkan standar akuntansi yang digunakan lembaga atau instansi pemerintah.',
            'Mengelola kartu piutang',
            'Mengelola kartu hutang',
            'Mengelola kartu persediaan',
            'Memproses dokumen dana kas kecil',
            'Dokumen dana kas di bank.',
            'Menjelaskan dan melakukan entri data awal perusahaan jasa.',
            'Mengidentifikasi dan membuat daftar akun perusahaan jasa.',
            'Membuat dan melakukan input data kartu piutang dan utang pada perusahaan jasa.',
            'Menjelaskan dan melakukan input Transaksi pembelian bahan, perlengkapan, aset tetap, dan pembayaran utang pada perusahaan jasa.',
            'Menjelaskan dan melakukan input transaksi penjualan, pelunasan piutang, dan penerimaan kas pada perusahaan jasa.',
            'Menjelaskan dan melakukan input transaksi pengeluaran kas dan pembayaran beban - beban pada perusahaan jasa.',
            'Menjelaskan dan melakukan input transaksi penyesuaian, membuat dan mencetak laporan keuangan perusahaan jasa.',
            'Menjelaskan dan melakukan entri data awal perusahaan dagang.',
            'Mengidentifikasi dan membuat daftar akun perusahaan dagang.',
            'Membuat dan melakukan input data kartu piutang dan utang dan persediaan pada perusahaan dagang.',
            'Menjelaskan dan melakukan input Transaksi pembelian bahan, perlengkapan, aset tetap, dan pembayaran utang pada perusahaan dagang.',
            'Menjelaskan dan melakukan input transaksi penjualan, pelunasan piutang, dan penerimaan kas pada perusahaan dagang.',
            'Menjelaskan dan melakukan input transaksi pengeluaran kas dan pembayaran beban - beban secara tunai pada perusahaan dagang.',
            'Menjelaskan dan melakukan input transaksi penyesuaian, serta membuat dan mencetak laporan keuangan perusahaan dagang.',
            'Menjelaskan dan melakukan entri data awal perusahaan Manufaktur',
            'Menjelaskan dan melakukan entri bukti transaksi perusahaan manufaktur.',
            'Menjelaskan dan melakukan entri transaksi jurnal penyesuaian perusahaan manufaktur.',
            'Membuat dan mencetak laporan keuangan perusahaan manufaktur.',
            'Melakukan Backup data.',
            'Menjelaskan ruang lingkup, jenis-jenis, serta hak dan kewajiban perpajakan.',
            'Mendeskripsikan kegunaan NPWP dan NPPKP dalam kegiatan perpajakan.',
            'Mengidentifikasi jenis-jenis dan kegunaan surat dalam perpajakan',
            'Menjelaskan ruang lingkup, pajak penghasilan.',
            'Menerapkan penghitungan pajak pada pajak penghasilan final.',
            'Menjelaskan ruang lingkup, dan melakukan perhitungan pajak penghasilan pasal 21,22,23, dan 24.',
            'Menerapkan penghitungan pajak penghasilan bagi pegawai tetap dan bukan pegawai, pejabat negara dan penerima pensiun.',
            'Menerapkan penghitungan pajak penghasilan bagi pegawai yang bersifat tidak teratur.',
            'Menerapkan dan melakukan perhitungan PPh Badan terutang.',
            'Menghitung pajak penghasilan wajib pajak orang pribadi.',
            'Menerapkan dan melakukan pengisian surat setoran pajak (SSP) PPh Badan dan PPh Orang Pribadi.',
            'Melakukan rekonsiliasi fiskal.',
            'Menganalisis dan melakukan perhitungan berbagai data yang terkait dengan PPN dan PPnBM.',
            'Menerapkan dan Melakukan pengisian surat pemberitahuan (SPT).',
        ];

        $akTp2 = [
            'Menerapkan akuntansi untuk perusahaan manufaktur',
            'Mengelola kartu aktiva tetap',
            'Menyajikan laporan harga pokok produk',
            'Menerapkan pencatatan utang wesel jangka panjang',
            'Menerapkan pencatatan penerbitan utang obligasi',
            'Menganalisis berbagai jenis modal perusahaan (perbedaan modal perorangan, firma, PT, CV, dan koperasi).',
        ];

        foreach ($akTp1 as $idx => $tp) {
            $data[] = [
                'jurusan_id' => $akId,
                'kode_jurusan' => 'AK',
                'konsentrasi_keahlian' => 'Akuntansi',
                'capaian_pembelajaran' => $cp1,
                'nomor_urut' => $idx + 1,
                'tujuan_pembelajaran' => $tp,
                'tahun' => '2026',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        foreach ($akTp2 as $idx => $tp) {
            $data[] = [
                'jurusan_id' => $akId,
                'kode_jurusan' => 'AK',
                'konsentrasi_keahlian' => 'Akuntansi',
                'capaian_pembelajaran' => $cp2,
                'nomor_urut' => $idx + 1,
                'tujuan_pembelajaran' => $tp,
                'tahun' => '2026',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // ==========================================
        // 2. BISNIS RITEL (BR)
        // ==========================================
        $brId = $jurusans['BR']->id ?? null;

        $brTp1 = [
            'Menganalisis proses bisnis dalam bidang pemasaran secara menyeluruh pada berbagai jenis industri dan usaha',
            'Menganalisis perkembangan pemasaran mulai dari konvensional sampai dengan penerapan teknologi modern, industri 4.0, Internet of Things (IoT)',
            'Menganalisis teknologi digital dalam pemasaran',
            'Menganalisis isu-isu perkembangan yang muncul dan hilang ke depan terkait dengan dunia pemasaran, seperti digital marketing, e-commerce, marketplace, media sosial, dan sejenisnya',
            'Menganalisis profil pekerjaan/profesi (job profile) dalam bidang pemasaran di masa sekarang dan dimasa mendatang, seperti kasir, pramuniaga, sales executive, merchandiser, digital marketer, public relation, dan sejenisnya',
            'Menganalisis peluang usaha di bidang pemasaran, seperti dropshipping, drop servicing, affiliate marketing, marketing agency, content creator, dan sejenisnya,',
            'Menentukan karir di bidang yang sesuai dengan bakat, minat, dan renjana (passion).',
            'Menerapkan prosedur kesehatan, keselamatan dan keamanan di tempat kerja',
            'Menerapkan penanganan dan antisipasi keadaan darurat',
            'Menerapkan standar penampilan pribadi, dan budaya kerja',
            'Menganalisis masalah-masalah ekonomi',
            'Menganalisis model ekonomi',
            'Menganalisis pelaku ekonomi',
            'Menganalisis perilaku konsumen dan produsen dalam kegiatan ekonomi',
            'Menerapkan ilmu ekonomi dalam kegiatan usaha',
            'Memahami administrasi umum serta fungsi-fungsi manajemen,',
            'Menganalisis faktor-faktor yang mempengaruhi perilaku konsumen dalam keputusan pembelian barang dan jasa',
            'Mengidentifikasi sinyal-sinyal calon pelanggan',
            'Menentukan bahasa pemasaran yang tepat serta membuat buyer persona, agar dapat mewujudkan kepuasan pelanggan',
            'Melakukan pelayanan penjualan',
            'Menganalisis konsep AIDA',
            'Mampu untuk bekerja di dalam tim (teamwork).',
            'Menganalisis konsep pasar dalam pemasaran (struktur dan bentuk pasar)',
            'Menyusun rencana usaha (proposal usaha atau Business Model Canvas)',
            'Memahami strategi pemasaran (Segmenting, Targeting, Positioning)',
            'Menganalisis ruang lingkup customer service',
            'Menerapkan POS (Prosedur Operasional Standar)',
            'Customer service dalam handling customer service dan handling complain baik secara offline maupun online',
            'Menerapkan komunikasi bisnis baik secara lisan maupun tertulis dalam Bahasa Indonesia dan atau Bahasa asing dalam pembuatan surat bisnis',
            'Menerapkan komunikasi bisnis baik secara lisan maupun tertulis dalam Bahasa Indonesia dan atau Bahasa asing dalam negosiasi bisnis',
            'Menganalisis ruang lingkup bisnis ritel',
            'Menerapkan proses bisnis ritel (ordering, receiving, warehousing, displaying, selling)',
            'Menganalisis strategi bauran ritel (Produk, Harga, Promosi, Pelayanan, Fasilitas Fisik)',
            'Menganalisis waralaba',
            'Mengembangkan rencana visual merchandising (planogram)',
            'Menerapkan visual merchandising',
            'Mengevaluasi visual merchandising',
            'Menerapkan pengemasan produk',
            'Menganalisis transaksi',
        ];

        $brTp2 = [
            'Menerapkan studi kelayakan usaha',
            'Memahami strategi bauran pemasaran (4p atau 7P)',
            'Menerapkan komunikasi bisnis baik secara lisan maupun tertulis dalam Bahasa Indonesia dan atau Bahasa asing dalam presentasi bisnis',
            'Menerapkan Daily activity retail',
            'Menganalisis manajemen persediaan barang dagang (pencatatan, perhitungan, dan stock opname)',
            'Menerapkan saluran distribusi',
            'Melakukan penyusunan dokumen pengiriman produk',
            'Melakukan pengiriman produk',
            'Menerapkan pengoperasian alat transaksi (mesin kasir, printer struk, EDC, barcode scanner, money detector, timbangan)',
            'Menganalisis layanan pembayaran tunai dan non tunai (QRIS, dompet digital, uang elektronik, kartu debet, kartu kredit)',
            'Menyusun laporan penjualan',
        ];

        foreach ($brTp1 as $idx => $tp) {
            $data[] = [
                'jurusan_id' => $brId,
                'kode_jurusan' => 'BR',
                'konsentrasi_keahlian' => 'Bisnis Ritel',
                'capaian_pembelajaran' => $cp1,
                'nomor_urut' => $idx + 1,
                'tujuan_pembelajaran' => $tp,
                'tahun' => '2026',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        foreach ($brTp2 as $idx => $tp) {
            $data[] = [
                'jurusan_id' => $brId,
                'kode_jurusan' => 'BR',
                'konsentrasi_keahlian' => 'Bisnis Ritel',
                'capaian_pembelajaran' => $cp2,
                'nomor_urut' => $idx + 1,
                'tujuan_pembelajaran' => $tp,
                'tahun' => '2026',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // ==========================================
        // 3. TEKNIK KOMPUTER DAN JARINGAN (TKJ)
        // ==========================================
        $tkjId = $jurusans['TKJ']->id ?? null;

        $tkjTp1 = [
            'Memahami perencanaan kebutuhan pelanggan',
            'Memahami kebutuhan pelanggan',
            'Melakukan perancangan prosedur kepuasan pelanggan',
            'Memahami perkembangan teknologi pada perangkat teknik jaringan komputer; 5G, IPV4 dan 6',
            'Memahami perkembangan teknologi pada perangkat teknik jaringan komputer; Microwave Link, Smart City dan Cloud Computing',
            'Memahami perkembangan teknologi pada perangkat teknik jaringan komputer; sistem sensor dan IoT',
            'Memahami jenis-jenis profesi dan kewirausahaan',
            'Memahami Job Profile',
            'Mengidentifikasi bahaya di tempat kerja Menerapkan prosedurprosedur dalam keadaan darurat',
            'Melakukan penerapan budaya mutu',
            'Memahami perkembangan teknologi pada perangkat teknik jaringan komputer; teknologi serat optik',
            'Memahami perkembangan teknologi pada perangkat teknik jaringan komputer; Information Security serta isu- isu implementasi teknologi jaringan',
            'Memahami Technopreneur',
            'Melaksankan simulasi Proyek Kewirausahaan',
            'Menerapkan prosedur K3LH sesuai peraturan yang berlaku Melaksanakan penggunaan alat perlindungan diri',
            'Menerapkan 5R (Ringkas, Rapi, Resik, Rawat, Rajin)',
            'Menerapkan prosedur kerja di tempat tinggi',
            'Memahami cara menggunakan peralatan/teknologi Personal Computer dan Laptop',
            'Memahami cara menggunakan peralatan/teknologi Kabel Jaringan dan NIC',
            'Memahami cara menggunakan peralatan/teknologi Router dan Manageable Switch',
            'Memahami cara menggunakan peralatan/teknologi Firewall dan Server',
            'Memahami cara menggunakan peralatan/teknologi Acces Point',
            'Memahami prinsip dasar system TCP IP, IPV4/IPV6',
            'Memahami prinsip dasar system Networking Service, dan keamanan jaringan telekomunikasi',
            'Memahami prinsip dasar Sistem Sistem Optik',
            'Memahami prinsip dasar Sistem Microwave, Sistem VSAT IP dan WLAN',
            'Menggunakan dan memelihara alat ukur jaringan computer',
            'Menggunakan dan memelihara alat ukur system telekomunikasi',
            'Memahami kebutuhan teknis pengguna dan peralatan jaringan dengan teknologi yang sesuai.',
            'Memahami perencanaan topologi dan arsitektur jaringan sesuai kebutuhan.',
            'Memahami pengalamatan jaringan, CIDR dan VLSM dan menghitung subnetting.',
            'Menerapkan instalasi jaringan kabel dan nirkabel.',
            'Menerapkan perawatan dan perbaikan jaringan kabel dan nirkabel.',
            'Menerapkan instalasi dan konfigurasi layanan Voice over IP (VoIP).',
            'Menerapkan pemasangan perangkat jaringan ke dalam sistem jaringan.',
            'Menerapkan penggantian perangkat jaringan sesuai dengan kebutuhan.',
            'Menerapkan konfigurasi dan pengujian VLAN.',
            'Menerapkan instalasi, konfigurasi dan pengujian layanan server (remote, DHCP, DNS,FTP, file, web, dan database server)',
        ];

        $tkjTp2 = [
            'Menerapkan instalasi jaringan fiber optik.',
            'Memahami perbaikan jaringan fiber optik.',
            'Menerapkan konfigurasi routing.',
            'Memahami permasalahan dan perbaikan konfigurasi routing statis dan routing dinamis',
            'Menerapkan konfigurasi NAT',
            'Memahami permasalahan internet gateway dan perbaikan konfigurasi NAT',
            'Menerapkan konfigurasi proxy server',
            'Memahami permasalahan dan perbaikan konfigurasi proxy server',
            'Memahami konfigurasi dan pengujian manajemen bandwidth',
            'Memahami konfigurasi dan pengujian load balancing',
            'Menerapkan instalasi, konfigurasi dan pengujian layanan server (Control Panel Hosting, Share Hosting Server, Dedicated Hosting Server, Virtual Private Server, VPN server, sistem kontrol dan monitoring).',
            'Memahami kebijakan penggunaan jaringan dan kemungkinan ancaman dan serangan terhadap keamanan jaringan',
            'Menentukan sistem keamanan jaringan yang dibutuhkan',
            'Memahami firewall pada host dan server dan kebutuhan persyaratan alat-alat untuk membangun server firewall serta menganalisis konsep dan implementasi firewall di host dan server',
            'Memahami fungsi dan cara kerja server autentifikasi',
            'Menganalisis cara kerja sistem pendeteksi dan penahan ancaman/serangan yang masuk ke jaringan dan menganalisis fungsi dan tata cara pengamanan server-server layanan pada jaringan',
            'Memahami tata cara pengamanan komunikasi data menggunakan teknik kriptografi',
        ];

        foreach ($tkjTp1 as $idx => $tp) {
            $data[] = [
                'jurusan_id' => $tkjId,
                'kode_jurusan' => 'TKJ',
                'konsentrasi_keahlian' => 'Teknik Komputer dan Jaringan',
                'capaian_pembelajaran' => $cp1,
                'nomor_urut' => $idx + 1,
                'tujuan_pembelajaran' => $tp,
                'tahun' => '2026',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        foreach ($tkjTp2 as $idx => $tp) {
            $data[] = [
                'jurusan_id' => $tkjId,
                'kode_jurusan' => 'TKJ',
                'konsentrasi_keahlian' => 'Teknik Komputer dan Jaringan',
                'capaian_pembelajaran' => $cp2,
                'nomor_urut' => $idx + 1,
                'tujuan_pembelajaran' => $tp,
                'tahun' => '2026',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // ==========================================
        // 4. REKAYASA PERANGKAT LUNAK (RPL)
        // ==========================================
        $rplId = $jurusans['RPL']->id ?? null;

        $rplTp1 = [
            'Memahami proses bisnis dan tata kelola pengembangan perangkat lunak dan gim',
            'Penerapan budaya mutu, Keselamatan dan Kesehatan Kerja serta Lingkungan Hidup (K3LH)',
            'Merencanakan manajemen proyek, serta pemahaman terhadap kebutuhan pelanggan, keinginan pelanggan, dan validasi sesuai dengan user experience (UX)',
            'Menjelaskan perkembangan teknologi pada perkembangan perangkat lunak dan gim dan penerapan industry 4.0 pada manajemen pengembangan perangkat lunak dan gim',
            'Menganalisis isu-isu penting bidang perangkat lunak dan gim beserta dampak positif dan negative gim, IoT, Cloud Computing, Information Security dan Big Data',
            'Menjelaskan jenis-jenis profesi dan kewira usahaan (job profile dan technopreneurship) dan Menganalisis peluang usaha dibidang perkembangan perangkat lunak dan gim',
            'Menganalisis peluang usaha dibidang industri perangkat lunak dan gim.',
            'Menerapkan K3LH dan budaya kerja industri (praktik-praktik kerja yang aman, bahaya-bahaya di tempat kerja, prosedur-prosedur dalam keadaan darurat)',
            'Menerapkan budaya kerja industri (Ringkas, Rapi, Resik, Rawat, Rajin), termasuk pencegahan kecelakaan kerja dan prosedur kerja',
            'Mampu menggunakan peralatan/ teknologi dibidang pengembangan perangkat lunak dan gim antara lain : basis data dan tools pengembangan perangkat lunak, ragam sistem operasi',
            'Menerapkan pengelolaan aset dan user interface (grafis, typography, warna, audio, video, interaksi pengguna)',
            'Menerapkan prinsip dasar algoritma pemrograman (varian dan invarian, alur logika pemrograman, flowchart dan teknik dasar algoritma umum)',
            'Melakukan pemrograman terstruktur menggunakan data statis dan data dinamis',
            'Menerapkan penggunaan tipe data, struktur kontrol perulangan dan percabangan pada proyek pengembangan perangkat lunak sederhana dan gim',
            'Melakukan pemrograman berorientasi obyek menggunakan class, obyek, method dan package',
            'Memahami berbagai macam access modifier',
            'Menerapkan enkapsulasi, interface, pewarisan dan polymorphism pada pengembangan perangkat lunak sederhana.',
            'Memahami pengertian konsep, struktur hirarki, dan komponen basis data.',
            'Menerapkan instalasi dan administrasi basis data.',
            'Menerapkan DDL (Data Definition Language ), DML (Data Manipulation Language ), dan DCL (Data Control Language ) pada pengelolaan basis data.',
            'Menerapkan perintah SQL bertingkat.',
            'Menerapkan penggunaan function dan stored procedure pada pengelolaan basis data.',
            'Menganalisis permasalahan terkait HAKI dalam pengembangan perangkat lunak dan gim',
            'Menerapkan aplikasi trigger , backup , restore , dan replikasi pada pengelolaan basis data sesuai permasalahan yang kontekstual.',
            'Menerapkan perintah HTML.',
            'Menerapkan perintah CSS.',
            'Menerapkan pemrograman JavaScript.',
            'Menerapkan framework pada pembuatan web statis.',
            'Menerapkan dokumentasi dan presentasi web statis.',
            'Menerapkan pemrograman server-side .',
            'Menerapkan framework pada pembuatan web dinamis.',
            'Memahami UI/UX untuk berbagai platform aplikasi.',
        ];

        $rplTp2 = [
            'Memahami pengertian, sejarah, dan komponen dalam sistem operasi serta pengembangan aplikasi pada perangkat bergerak.',
            'Memahami konsep IDE (Integrated Development Environment).',
            'Menerapkan framework dan bahasa pemrograman untuk pengembangan aplikasi perangkat bergerak.',
            'Menerapkan basis data perangkat bergerak.',
            'Menerapkan aplikasi perangkat bergerak menggunakan bahasa pemrograman untuk beragam kebutuhan yang kontekstual.',
            'Menerapkan antarmuka aplikasi yang saling berhubungan dengan aplikasi lainnya (Application Programming Interface/API).',
            'Menerapkan dokumentasi dan presentasi aplikasi perangkat bergerak yang telah dikembangkan.',
            'Menerapkan pemrograman terstruktur dan pemrograman berorientasi objek tingkat lanjut.',
            'Memahami dasar pemodelan perangkat lunak berorientasi objek.',
            'Menerapkan alur kerja sistem dan model.',
            'Menerapkan relasi antar kelas dan interaksi antar objek.',
            'Menerapkan objek multimedia dalam aplikasi dengan menunjukkan aplikasi yang dapat menampilkan gambar, audio, dan video.',
            'Menerapkan pemrograman antarmuka grafis (Graphical User Interface ) dengan memanfaatkan pustaka (library ) pada proyek yang lebih kompleks.',
            'Memahami model perangkat lunak secara kolaboratif pada proyek pengembangan perangkat lunak.',
            'Memahami konsep block chain dan data mining .',
        ];

        foreach ($rplTp1 as $idx => $tp) {
            $data[] = [
                'jurusan_id' => $rplId,
                'kode_jurusan' => 'RPL',
                'konsentrasi_keahlian' => 'Rekayasa Perangkat Lunak',
                'capaian_pembelajaran' => $cp1,
                'nomor_urut' => $idx + 1,
                'tujuan_pembelajaran' => $tp,
                'tahun' => '2026',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        foreach ($rplTp2 as $idx => $tp) {
            $data[] = [
                'jurusan_id' => $rplId,
                'kode_jurusan' => 'RPL',
                'konsentrasi_keahlian' => 'Rekayasa Perangkat Lunak',
                'capaian_pembelajaran' => $cp2,
                'nomor_urut' => $idx + 1,
                'tujuan_pembelajaran' => $tp,
                'tahun' => '2026',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // ==========================================
        // 5. MANAJEMEN PERKANTORAN (MP)
        // ==========================================
        $mpId = $jurusans['MP']->id ?? null;

        $mpTp1 = [
            'Menganalisis aktivitas pekerjaan pada bidang manajemen dan layanan bisnis.',
            'Menganalisis fungsi manajemen',
            'Menganalisis rantai pasok (supply chain)',
            'Menganalsis perkembangan manajemen perkantoran modern',
            'Menganalisis otomatisasi perkantoran',
            'Menganalisis profil pekerjaan pekerjaan/profesi (job-profile) di masa sekarang dan di masa mendatang',
            'Menganalisis peluang usaha dibidang manajemen perkantoran dan layanan bisnis',
            'Menganalisis Dasar-dasar ilmu ekonomi',
            'Menganalisis Dasar-dasar pemasaran',
            'Menganalisis E-commerce',
            'Menerapkan layanan pelanggan (customer service)',
            'Menerapkan prosedur dan instruksi kerja',
            'Menerapkan K3/K3LH',
            'Menerapkan budaya kerja,',
            'Menerapkan dasar-dasar prosedur penanganan dokumen',
            'Menerapkan prosedur penyimpanan dokumen',
            'Menerapkan pengelolaan peralatan kantor',
            'Menerapkan penggunaan perangkat keras',
            'Mendefinisikan data dan informasi',
            'Menerapkan prosedur penggunaan homepage',
            'Menerapkan dasar komunikasi',
            'Menerapkan konsep layanan bisnis logistic',
            'Menerapkan layanan pergudangan',
            'Menerapkan layanan transportasi',
            'Menganalisis layanan distribusi dan pengiriman (delivery).',
            'Menerapkan korespondensi bahasa Indonesia dan bahasa Inggris',
            'Menerapkan penanganan surat (mail handling)',
            'Menerapkan penyusunan surat',
            'Menerapkan akses data dan informasi ditempat kerja',
            'Menerapkan prosedur berkomunikasi melalui telepon',
            'Menerapkan presentasi',
            'Menerapkan Prosedur menyimpan arsip',
            'Menerapkan penggunaan arsip',
            'Mengembangkan berbagai dokumen kantor menggunakan aplikasi perkantoran (Office software)',
            'Menerapkan pengelolaan rapat secara online dan offline',
            'Mengembangkan materi presentasi',
            'Menganalisis kebutuhan pelanggan',
            'Menerapkan prosedur penanganan pelanggan',
        ];

        $mpTp2 = [
            'Menerapkan pencatatan kas kecil',
            'Menerapkan penyusunan jadwal kegiatan pimpinan (daily agenda)',
            'Penyusunan dokumen perjalanan dinas serta akomodasi dan transportasi perjalanan dinas (business traveling arrangement)',
            'Menerapkan Penanganan tamu kantor dalam bahasa Indonesia, bahasa Inggris dan bahasa asing lainnya',
            'Menerapkan Pemeliharaan arsip',
            'Menerapkan Penentuan masa retensi arsip',
            'Menerapkan Penyusutan arsip secara manual maupun elektronik',
            'Menerapkan komputasi berbasis online',
            'Membuat notulen rapat',
            'Pemrosesan keluhan kolega dan pelanggan',
        ];

        foreach ($mpTp1 as $idx => $tp) {
            $data[] = [
                'jurusan_id' => $mpId,
                'kode_jurusan' => 'MP',
                'konsentrasi_keahlian' => 'Manajemen Perkantoran',
                'capaian_pembelajaran' => $cp1,
                'nomor_urut' => $idx + 1,
                'tujuan_pembelajaran' => $tp,
                'tahun' => '2026',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        foreach ($mpTp2 as $idx => $tp) {
            $data[] = [
                'jurusan_id' => $mpId,
                'kode_jurusan' => 'MP',
                'konsentrasi_keahlian' => 'Manajemen Perkantoran',
                'capaian_pembelajaran' => $cp2,
                'nomor_urut' => $idx + 1,
                'tujuan_pembelajaran' => $tp,
                'tahun' => '2026',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert in chunks of 50
        foreach (array_chunk($data, 50) as $chunk) {
            TujuanPembelajaran::insert($chunk);
        }

        $this->command->info('Berhasil men-generate ' . count($data) . ' Tujuan Pembelajaran PKL 2026 untuk 5 Konsentrasi Keahlian.');
    }
}
