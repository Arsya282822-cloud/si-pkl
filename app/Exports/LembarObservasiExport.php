<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LembarObservasiExport
{
    protected ?array $data;

    public function __construct(?array $data = null)
    {
        $this->data = $data;
    }

    /**
     * Generate & Download the formatted Excel spreadsheet
     */
    public function download(string $filename = 'lembar_observasi_pkl.xlsx'): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Lembar Observasi');

        // Page setup
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $sheet->getPageMargins()->setTop(0.5)->setBottom(0.5)->setLeft(0.6)->setRight(0.5);

        // Set Column Widths
        $sheet->getColumnDimension('A')->setWidth(6);   // No
        $sheet->getColumnDimension('B')->setWidth(46);  // Capaian dan Tujuan Pembelajaran
        $sheet->getColumnDimension('C')->setWidth(14);  // Observasi I
        $sheet->getColumnDimension('D')->setWidth(14);  // Observasi II
        $sheet->getColumnDimension('E')->setWidth(12);  // Akhir
        $sheet->getColumnDimension('F')->setWidth(12);  // Rerata

        // Default Font
        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);

        // Header Title
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'LEMBAR OBSERVASI');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:F2');
        $sheet->setCellValue('A2', 'PRAKTIK KERJA LAPANGAN');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Data Identitas
        $institusi = $this->data['nama_institusi'] ?? '';
        $alamat = $this->data['alamat_institusi'] ?? '';
        $telp = $this->data['telp_institusi'] ?? '';
        $pimpinan = $this->data['nama_pimpinan'] ?? '';
        $nipPimpinan = $this->data['nip_pimpinan'] ?? '';
        $jabatanPimpinan = $this->data['jabatan_pimpinan'] ?? 'Pimpinan Perusahaan / DUDI';
        $instruktur = $this->data['nama_instruktur'] ?? '';
        $nipInstruktur = $this->data['nip_instruktur'] ?? '';
        $jabatanInstruktur = $this->data['jabatan_instruktur'] ?? 'Pembimbing Lapangan / Instruktur';
        $murid = $this->data['nama_murid'] ?? '';
        $jurusan = $this->data['jurusan'] ?? '';

        $row = 4;
        $sheet->setCellValue("A{$row}", 'I. DATA INSTITUSI');
        $sheet->getStyle("A{$row}")->getFont()->setBold(true);
        $row++;
        $sheet->setCellValue("A{$row}", '- NAMA LENGKAP INSTITUSI');
        $sheet->setCellValue("B{$row}", ': ' . $institusi);
        $row++;
        $sheet->setCellValue("A{$row}", '- ALAMAT');
        $sheet->setCellValue("B{$row}", ': ' . $alamat);
        $row++;
        $sheet->setCellValue("A{$row}", '- TELP/FAX');
        $sheet->setCellValue("B{$row}", ': ' . $telp);
        $row++;

        $sheet->setCellValue("A{$row}", 'II. DATA PIMPINAN');
        $sheet->getStyle("A{$row}")->getFont()->setBold(true);
        $row++;
        $sheet->setCellValue("A{$row}", '- NAMA LENGKAP PIMPINAN');
        $sheet->setCellValue("B{$row}", ': ' . $pimpinan);
        $row++;
        $sheet->setCellValue("A{$row}", '- NIP/NO. REGISTRASI');
        $sheet->setCellValue("B{$row}", ': ' . $nipPimpinan);
        $row++;
        $sheet->setCellValue("A{$row}", '- JABATAN');
        $sheet->setCellValue("B{$row}", ': ' . $jabatanPimpinan);
        $row++;

        $sheet->setCellValue("A{$row}", 'III. DATA INSTRUKTUR');
        $sheet->getStyle("A{$row}")->getFont()->setBold(true);
        $row++;
        $sheet->setCellValue("A{$row}", '- NAMA LENGKAP INSTRUKTUR');
        $sheet->setCellValue("B{$row}", ': ' . $instruktur);
        $row++;
        $sheet->setCellValue("A{$row}", '- NIP/NO. REGISTRASI');
        $sheet->setCellValue("B{$row}", ': ' . $nipInstruktur);
        $row++;
        $sheet->setCellValue("A{$row}", '- JABATAN');
        $sheet->setCellValue("B{$row}", ': ' . $jabatanInstruktur);
        $row++;

        $sheet->setCellValue("A{$row}", 'IV. MURID');
        $sheet->getStyle("A{$row}")->getFont()->setBold(true);
        $row++;
        $sheet->setCellValue("A{$row}", '- NAMA LENGKAP MURID');
        $sheet->setCellValue("B{$row}", ': ' . $murid);
        $row++;
        $sheet->setCellValue("A{$row}", '- KONSENTRASI KEAHLIAN');
        $sheet->setCellValue("B{$row}", ': ' . $jurusan);
        $row += 2;

        // TABLE HEADER
        $tableHeaderRow = $row;
        $sheet->setCellValue("A{$row}", 'No');
        $sheet->setCellValue("B{$row}", 'Capaian dan Tujuan Pembelajaran');
        $sheet->setCellValue("C{$row}", 'Observasi I');
        $sheet->setCellValue("D{$row}", 'Observasi II');
        $sheet->setCellValue("E{$row}", 'Akhir');
        $sheet->setCellValue("F{$row}", 'Rerata');

        $headerStyle = [
            'font' => ['bold' => true, 'size' => 9.5],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E2E8F0']
            ]
        ];
        $sheet->getStyle("A{$row}:F{$row}")->applyFromArray($headerStyle);
        $sheet->getRowDimension($row)->setRowHeight(26);
        $tableStartRow = $row;
        $row++;

        // SECTION I: Soft Skills
        $sheet->setCellValue("A{$row}", 'I');
        $sheet->setCellValue("B{$row}", "Menerapkan soft skills yang dibutuhkan dalam\ndunia kerja (tempat PKL)");
        $sheet->getStyle("A{$row}:B{$row}")->getFont()->setBold(true);
        $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("B{$row}")->getAlignment()->setWrapText(true);
        $row++;

        $softskills = [
            'Etika dalam berkomunikasi lisan dan tulisan',
            "Integritas dalam bekerja (Jujur, Disiplin,\nkomitmen, dan tanggung jawab)",
            'Bekerja secara mandiri',
            'Bekerja secara tim',
            'Kepedulian sosial',
            'Ketaatan terhadap norma dan POS yang berlaku',
            'K3LH di lingkungan kerja.'
        ];

        foreach ($softskills as $item) {
            $sheet->setCellValue("B{$row}", $item);
            $sheet->getStyle("B{$row}")->getAlignment()->setWrapText(true);
            $sheet->setCellValue("F{$row}", "=IF(COUNT(C{$row}:E{$row})>0, ROUND(AVERAGE(C{$row}:E{$row}), 1), \"\")");
            $sheet->getStyle("C{$row}:F{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $row++;
        }

        // SECTION II: Kompetensi Teknis sesuai POS
        $sheet->setCellValue("A{$row}", 'II');
        $sheet->setCellValue("B{$row}", "Menerapkan kompetensi teknis pada pekerjaan\nsesuai POS yang berlaku di dunia kerja");
        $sheet->getStyle("A{$row}:B{$row}")->getFont()->setBold(true);
        $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("B{$row}")->getAlignment()->setWrapText(true);
        $row++;

        // 6 blank rows for specific technical competence
        for ($i = 0; $i < 6; $i++) {
            $sheet->setCellValue("B{$row}", "");
            $sheet->setCellValue("F{$row}", "=IF(COUNT(C{$row}:E{$row})>0, ROUND(AVERAGE(C{$row}:E{$row}), 1), \"\")");
            $sheet->getStyle("C{$row}:F{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getRowDimension($row)->setRowHeight(20);
            $row++;
        }

        // SECTION III: Kompetensi Teknis Baru
        $sheet->setCellValue("A{$row}", 'III');
        $sheet->setCellValue("B{$row}", "Menerapkan kompetensi teknis baru/atau\nkompetensi teknis yang belum tuntas dipelajari\nsesuai konsentrasi keahlian");
        $sheet->getStyle("A{$row}:B{$row}")->getFont()->setBold(true);
        $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("B{$row}")->getAlignment()->setWrapText(true);
        $row++;

        // 6 blank rows for new technical skills
        for ($i = 0; $i < 6; $i++) {
            $sheet->setCellValue("B{$row}", "");
            $sheet->setCellValue("F{$row}", "=IF(COUNT(C{$row}:E{$row})>0, ROUND(AVERAGE(C{$row}:E{$row}), 1), \"\")");
            $sheet->getStyle("C{$row}:F{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getRowDimension($row)->setRowHeight(20);
            $row++;
        }

        // SECTION IV: Analisis Usaha
        $sheet->setCellValue("A{$row}", 'IV');
        $sheet->setCellValue("B{$row}", "Melakukan analisis usaha secara mandiri");
        $sheet->getStyle("A{$row}:B{$row}")->getFont()->setBold(true);
        $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row++;

        $analisis = [
            "Menjelaskan bidang usaha/pekerjaan, alur\nbisnis/kerja tempat PKL.",
            "Memasarkan produk/jasa dengan menentukan\nharga produk dan segmen pasar.",
            "Menentukan media yang tepat untuk\nmempromosikan produk/jasa",
            "Memberikan layanan terhadap keluhan\npelanggan"
        ];

        foreach ($analisis as $item) {
            $sheet->setCellValue("B{$row}", $item);
            $sheet->getStyle("B{$row}")->getAlignment()->setWrapText(true);
            $sheet->setCellValue("F{$row}", "=IF(COUNT(C{$row}:E{$row})>0, ROUND(AVERAGE(C{$row}:E{$row}), 1), \"\")");
            $sheet->getStyle("C{$row}:F{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $row++;
        }

        // Tanggal Pelaksanaan & Tanda Tangan Table Footer
        $sheet->mergeCells("A{$row}:B{$row}");
        $sheet->setCellValue("A{$row}", "Tanggal Pelaksanaan Observasi");
        $sheet->getStyle("A{$row}")->getFont()->setBold(true);
        $sheet->getRowDimension($row)->setRowHeight(22);
        $row++;

        $sheet->mergeCells("A{$row}:B{$row}");
        $sheet->setCellValue("A{$row}", "Tanda Tangan Instruktur");
        $sheet->getStyle("A{$row}")->getFont()->setBold(true);
        $sheet->getRowDimension($row)->setRowHeight(35);
        $row++;

        $sheet->mergeCells("A{$row}:B{$row}");
        $sheet->setCellValue("A{$row}", "Tanda Tangan Guru Pembimbing");
        $sheet->getStyle("A{$row}")->getFont()->setBold(true);
        $sheet->getRowDimension($row)->setRowHeight(35);
        $tableEndRow = $row;

        // Apply Borders to Entire Table
        $tableStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ];
        $sheet->getStyle("A{$tableStartRow}:F{$tableEndRow}")->applyFromArray($tableStyle);

        // Signatures Footer Pimpinan
        $row += 2;
        $sheet->setCellValue("D{$row}", "Pekanbaru, " . date('Y'));
        $sheet->getStyle("D{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row++;
        $sheet->setCellValue("D{$row}", "Pimpinan");
        $sheet->getStyle("D{$row}")->getFont()->setBold(true);
        $sheet->getStyle("D{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $row += 4;
        $sheet->setCellValue("D{$row}", "( " . ($pimpinan ?: '................................................') . " )");
        $sheet->getStyle("D{$row}")->getFont()->setBold(true);
        $sheet->getStyle("D{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
