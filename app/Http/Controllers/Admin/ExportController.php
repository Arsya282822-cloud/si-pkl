<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Perusahaan;
use App\Models\Penempatan;
use App\Models\AbsensiPkl;
use App\Models\PenilaianPkl;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    /**
     * Helper untuk mengalirkan berkas Spreadsheet XLSX ke browser
     */
    private function streamXlsx(Spreadsheet $spreadsheet, string $filename): StreamedResponse
    {
        $response = new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $filename . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    /**
     * Terapkan styling header tabel standar (Navy/Blue header dengan teks putih tebal)
     */
    private function styleHeader(Spreadsheet $spreadsheet, string $range, string $bgColor = '0284C7'): void
    {
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getStyle($range)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 10,
                'name' => 'Segoe UI',
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => $bgColor],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(26);
    }

    /**
     * Terapkan border dan alignment default untuk seluruh baris data
     */
    private function styleDataGrid(Spreadsheet $spreadsheet, string $range): void
    {
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getStyle($range)->applyFromArray([
            'font' => [
                'size' => 10,
                'name' => 'Segoe UI',
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D1D5DB'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
    }

    // =========================================================================
    // 1. TEMPLATE IMPORT XLSX
    // =========================================================================

    /**
     * Download Template Import Siswa (Format XLSX)
     */
    public function templateSiswa()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Import Siswa');

        // Headers
        $headers = [
            'A1' => 'nis',
            'B1' => 'nisn',
            'C1' => 'nama',
            'D1' => 'jenis_kelamin',
            'E1' => 'kelas',
            'F1' => 'jurusan',
            'G1' => 'tempat_lahir',
            'H1' => 'tanggal_lahir',
            'I1' => 'alamat',
            'J1' => 'no_hp',
            'K1' => 'email',
        ];

        foreach ($headers as $cell => $val) {
            $sheet->setCellValue($cell, $val);
        }

        // Contoh Data Baris
        $sampleData = [
            ['2026001', '0081234567', 'Ahmad Fauzan', 'L', 'XII RPL 1', 'Rekayasa Perangkat Lunak', 'Pekanbaru', '2008-05-14', 'Jl. HR Soebrantas No. 12', '081234567890', 'ahmad@siswa.com'],
            ['2026002', '0087654321', 'Siti Aisyah', 'P', 'XII TKJ 1', 'Teknik Komputer dan Jaringan', 'Kampar', '2008-08-20', 'Jl. Tuanku Tambusai No. 45', '081398765432', 'siti@siswa.com'],
            ['2026003', '0089988776', 'Budi Pratama', 'L', 'XII AK 1', 'Akuntansi dan Keuangan Lembaga', 'Pekanbaru', '2008-01-10', 'Jl. Sudirman No. 88', '085211223344', 'budi@siswa.com'],
        ];

        $row = 2;
        foreach ($sampleData as $item) {
            $col = 'A';
            foreach ($item as $val) {
                $sheet->setCellValueExplicit($col . $row, $val, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $col++;
            }
            $row++;
        }

        // Styling
        $this->styleHeader($spreadsheet, 'A1:K1', '0284C7');
        $this->styleDataGrid($spreadsheet, 'A2:K' . ($row - 1));

        // Auto Width Columns
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return $this->streamXlsx($spreadsheet, 'template_import_siswa.xlsx');
    }

    /**
     * Download Template Import Guru (Format XLSX)
     */
    public function templateGuru()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Import Guru');

        $headers = [
            'A1' => 'nip',
            'B1' => 'nama',
            'C1' => 'jenis_kelamin',
            'D1' => 'no_hp',
            'E1' => 'alamat',
            'F1' => 'email',
        ];

        foreach ($headers as $cell => $val) {
            $sheet->setCellValue($cell, $val);
        }

        $sampleData = [
            ['198904122019031005', 'Mahendra, S.Pd., M.Si.', 'L', '081275001122', 'Jl. Garuda Sakti Km. 2 Pekanbaru', 'mahendra.guru@smklabor.sch.id'],
            ['199205152020122008', 'Rina Kartika, M.Kom.', 'P', '081365443322', 'Jl. Delima Gang Melati No. 4', 'rina.guru@smklabor.sch.id'],
        ];

        $row = 2;
        foreach ($sampleData as $item) {
            $col = 'A';
            foreach ($item as $val) {
                $sheet->setCellValueExplicit($col . $row, $val, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $col++;
            }
            $row++;
        }

        $this->styleHeader($spreadsheet, 'A1:F1', '059669');
        $this->styleDataGrid($spreadsheet, 'A2:F' . ($row - 1));

        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return $this->streamXlsx($spreadsheet, 'template_import_guru.xlsx');
    }

    /**
     * Download Template Import Perusahaan Mitra DUDI (Format XLSX)
     */
    public function templatePerusahaan()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Import DUDI');

        $headers = [
            'A1' => 'nama_perusahaan',
            'B1' => 'alamat',
            'C1' => 'kota',
            'D1' => 'no_telepon',
            'E1' => 'email',
            'F1' => 'website',
            'G1' => 'nama_pimpinan',
        ];

        foreach ($headers as $cell => $val) {
            $sheet->setCellValue($cell, $val);
        }

        $sampleData = [
            ['PT TELKOM INDONESIA WITEL RIAU', 'Jl. Jend. Sudirman No. 199', 'Pekanbaru', '0761853000', 'info@telkomriau.co.id', 'https://telkom.co.id', 'Ir. Budi Hartono'],
            ['PT RIAU ANDALAN PULP AND PAPER (RAPP)', 'Pangkalan Kerinci', 'Pelalawan', '0761491000', 'hrd@aprilasia.com', 'https://aprilasia.com', 'Drs. Hendra Wijaya'],
        ];

        $row = 2;
        foreach ($sampleData as $item) {
            $col = 'A';
            foreach ($item as $val) {
                $sheet->setCellValueExplicit($col . $row, $val, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $col++;
            }
            $row++;
        }

        $this->styleHeader($spreadsheet, 'A1:G1', 'D97706');
        $this->styleDataGrid($spreadsheet, 'A2:G' . ($row - 1));

        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return $this->streamXlsx($spreadsheet, 'template_import_perusahaan.xlsx');
    }

    /**
     * Download Template Lembar Observasi (Format XLSX)
     */
    public function templateObservasi()
    {
        $export = new \App\Exports\LembarObservasiExport();
        return $export->download('template_lembar_observasi_pkl.xlsx');
    }

    // =========================================================================
    // 2. EXPORT DATA RESMI (FORMAT XLSX)
    // =========================================================================

    /**
     * Export Seluruh Data Siswa ke XLSX
     */
    public function siswa()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Siswa');

        $headers = ['No', 'NIS', 'NISN', 'Nama Lengkap', 'Jenis Kelamin', 'Kelas', 'Jurusan', 'No HP', 'Alamat', 'Email'];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '1', $h);
            $col++;
        }

        $siswa = Siswa::with(['kelas', 'jurusan', 'user'])->orderBy('nama', 'asc')->get();
        $row = 2;
        foreach ($siswa as $i => $s) {
            $sheet->setCellValue('A' . $row, $i + 1);
            $sheet->setCellValueExplicit('B' . $row, $s->nis, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('C' . $row, $s->nisn ?? '-', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('D' . $row, $s->nama);
            $sheet->setCellValue('E' . $row, $s->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan');
            $sheet->setCellValue('F' . $row, $s->kelas?->nama_kelas ?? '-');
            $sheet->setCellValue('G' . $row, $s->jurusan?->nama_jurusan ?? '-');
            $sheet->setCellValueExplicit('H' . $row, $s->no_hp ?? '-', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('I' . $row, $s->alamat ?? '-');
            $sheet->setCellValue('J' . $row, $s->user?->email ?? '-');
            $row++;
        }

        $lastRow = max(2, $row - 1);
        $this->styleHeader($spreadsheet, 'A1:J1', '0284C7');
        $this->styleDataGrid($spreadsheet, 'A2:J' . $lastRow);

        foreach (range('A', 'J') as $c) {
            $sheet->getColumnDimension($c)->setAutoSize(true);
        }

        return $this->streamXlsx($spreadsheet, 'data_siswa_' . date('Ymd_His') . '.xlsx');
    }

    /**
     * Export Seluruh Data Penempatan PKL ke XLSX
     */
    public function penempatan()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Penempatan PKL');

        $headers = ['No', 'NIS', 'Nama Siswa', 'Kelas / Jurusan', 'Perusahaan Mitra DUDI', 'Guru Pembimbing', 'Periode PKL'];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '1', $h);
            $col++;
        }

        $data = Penempatan::with(['siswa.kelas', 'siswa.jurusan', 'guru', 'perusahaan', 'periodePkl'])->get();
        $row = 2;
        foreach ($data as $i => $d) {
            $sheet->setCellValue('A' . $row, $i + 1);
            $sheet->setCellValueExplicit('B' . $row, $d->siswa?->nis ?? '-', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('C' . $row, $d->siswa?->nama ?? '-');
            $sheet->setCellValue('D' . $row, ($d->siswa?->kelas?->nama_kelas ?? '') . ' / ' . ($d->siswa?->jurusan?->nama_jurusan ?? '-'));
            $sheet->setCellValue('E' . $row, $d->perusahaan?->nama_perusahaan ?? '-');
            $sheet->setCellValue('F' . $row, $d->guru?->nama ?? '-');
            $sheet->setCellValue('G' . $row, $d->periodePkl?->nama_periode ?? '-');
            $row++;
        }

        $lastRow = max(2, $row - 1);
        $this->styleHeader($spreadsheet, 'A1:G1', '4F46E5');
        $this->styleDataGrid($spreadsheet, 'A2:G' . $lastRow);

        foreach (range('A', 'G') as $c) {
            $sheet->getColumnDimension($c)->setAutoSize(true);
        }

        return $this->streamXlsx($spreadsheet, 'data_penempatan_' . date('Ymd_His') . '.xlsx');
    }

    /**
     * Export Rekapitulasi Presensi PKL ke XLSX
     */
    public function absensi()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekapitulasi Presensi');

        $headers = ['No', 'Tanggal', 'NIS', 'Nama Siswa', 'Perusahaan Mitra', 'Status Presensi', 'Jam Masuk', 'Jam Pulang', 'Keterangan'];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '1', $h);
            $col++;
        }

        $data = AbsensiPkl::with(['penempatan.siswa', 'penempatan.perusahaan'])->orderBy('tanggal', 'desc')->get();
        $row = 2;
        foreach ($data as $i => $d) {
            $sheet->setCellValue('A' . $row, $i + 1);
            $sheet->setCellValue('B' . $row, $d->tanggal);
            $sheet->setCellValueExplicit('C' . $row, $d->penempatan?->siswa?->nis ?? '-', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('D' . $row, $d->penempatan?->siswa?->nama ?? '-');
            $sheet->setCellValue('E' . $row, $d->penempatan?->perusahaan?->nama_perusahaan ?? '-');
            $sheet->setCellValue('F' . $row, ucfirst($d->status));
            $sheet->setCellValue('G' . $row, $d->jam_masuk ?? '-');
            $sheet->setCellValue('H' . $row, $d->jam_keluar ?? '-');
            $sheet->setCellValue('I' . $row, $d->keterangan ?? '-');
            $row++;
        }

        $lastRow = max(2, $row - 1);
        $this->styleHeader($spreadsheet, 'A1:I1', '059669');
        $this->styleDataGrid($spreadsheet, 'A2:I' . $lastRow);

        foreach (range('A', 'I') as $c) {
            $sheet->getColumnDimension($c)->setAutoSize(true);
        }

        return $this->streamXlsx($spreadsheet, 'rekap_absensi_' . date('Ymd_His') . '.xlsx');
    }

    /**
     * Export Rekapitulasi Nilai PKL ke XLSX
     */
    public function nilai()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekapitulasi Nilai PKL');

        $headers = ['No', 'NIS', 'Nama Siswa', 'Perusahaan Mitra DUDI', 'Guru Pembimbing', 'Nilai Sikap', 'Nilai Keterampilan', 'Nilai Pengetahuan', 'Nilai Akhir', 'Catatan Pembimbing'];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '1', $h);
            $col++;
        }

        $data = PenilaianPkl::with(['penempatan.siswa', 'penempatan.perusahaan', 'penempatan.guru'])->get();
        $row = 2;
        foreach ($data as $i => $d) {
            $sheet->setCellValue('A' . $row, $i + 1);
            $sheet->setCellValueExplicit('B' . $row, $d->penempatan?->siswa?->nis ?? '-', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('C' . $row, $d->penempatan?->siswa?->nama ?? '-');
            $sheet->setCellValue('D' . $row, $d->penempatan?->perusahaan?->nama_perusahaan ?? '-');
            $sheet->setCellValue('E' . $row, $d->penempatan?->guru?->nama ?? '-');
            $sheet->setCellValue('F' . $row, $d->nilai_sikap);
            $sheet->setCellValue('G' . $row, $d->nilai_keterampilan);
            $sheet->setCellValue('H' . $row, $d->nilai_pengetahuan);
            $sheet->setCellValue('I' . $row, $d->nilai_akhir);
            $sheet->setCellValue('J' . $row, $d->catatan_guru ?? '-');
            $row++;
        }

        $lastRow = max(2, $row - 1);
        $this->styleHeader($spreadsheet, 'A1:J1', 'D97706');
        $this->styleDataGrid($spreadsheet, 'A2:J' . $lastRow);

        foreach (range('A', 'J') as $c) {
            $sheet->getColumnDimension($c)->setAutoSize(true);
        }

        return $this->streamXlsx($spreadsheet, 'rekap_nilai_' . date('Ymd_His') . '.xlsx');
    }

    /**
     * Backup Database SQLite
     */
    public function backupDatabase()
    {
        $dbPath = database_path('database.sqlite');
        if (!file_exists($dbPath)) {
            return back()->with('error', 'File database tidak ditemukan di ' . $dbPath);
        }

        $backupFileName = 'sipkl_backup_' . date('Y_m_d_His') . '.sqlite';

        \App\Services\ActivityLogger::log(
            'Backup Database',
            'Unduh Cadangan Database',
            'Admin mengunduh file backup: ' . $backupFileName
        );

        return response()->download($dbPath, $backupFileName, [
            'Content-Type' => 'application/x-sqlite3',
        ]);
    }
}
