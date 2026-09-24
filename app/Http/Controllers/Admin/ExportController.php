<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Penempatan;
use App\Models\AbsensiPkl;
use App\Models\PenilaianPkl;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function siswa()
    {
        $filename = 'data_siswa_' . date('Ymd') . '.csv';
        $siswa = Siswa::with(['kelas', 'jurusan'])->get();

        return new StreamedResponse(function () use ($siswa) {
            $handle = fopen('php://output', 'w');
            // BOM for Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, ['No', 'NIS', 'NISN', 'Nama', 'Jenis Kelamin', 'Kelas', 'Jurusan', 'No HP', 'Alamat'], ';');

            foreach ($siswa as $i => $s) {
                fputcsv($handle, [
                    $i + 1,
                    $s->nis,
                    $s->nisn ?? '-',
                    $s->nama,
                    $s->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan',
                    $s->kelas?->nama_kelas ?? '-',
                    $s->jurusan?->nama_jurusan ?? '-',
                    $s->no_hp ?? '-',
                    $s->alamat ?? '-',
                ], ';');
            }
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function penempatan()
    {
        $filename = 'data_penempatan_' . date('Ymd') . '.csv';
        $data = Penempatan::with(['siswa', 'guru', 'perusahaan', 'periodePkl'])->get();

        return new StreamedResponse(function () use ($data) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, ['No', 'NIS', 'Nama Siswa', 'Perusahaan', 'Guru Pembimbing', 'Periode'], ';');

            foreach ($data as $i => $d) {
                fputcsv($handle, [
                    $i + 1,
                    $d->siswa?->nis ?? '-',
                    $d->siswa?->nama ?? '-',
                    $d->perusahaan?->nama_perusahaan ?? '-',
                    $d->guru?->nama ?? '-',
                    $d->periodePkl?->nama_periode ?? '-',
                ], ';');
            }
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function absensi()
    {
        $filename = 'rekap_absensi_' . date('Ymd') . '.csv';
        $data = AbsensiPkl::with(['penempatan.siswa', 'penempatan.perusahaan'])->orderBy('tanggal', 'desc')->get();

        return new StreamedResponse(function () use ($data) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, ['No', 'Tanggal', 'Nama Siswa', 'Perusahaan', 'Status', 'Jam Masuk', 'Jam Keluar', 'Keterangan'], ';');

            foreach ($data as $i => $d) {
                fputcsv($handle, [
                    $i + 1,
                    $d->tanggal,
                    $d->penempatan?->siswa?->nama ?? '-',
                    $d->penempatan?->perusahaan?->nama_perusahaan ?? '-',
                    ucfirst($d->status),
                    $d->jam_masuk ?? '-',
                    $d->jam_keluar ?? '-',
                    $d->keterangan ?? '-',
                ], ';');
            }
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function nilai()
    {
        $filename = 'rekap_nilai_' . date('Ymd') . '.csv';
        $data = PenilaianPkl::with(['penempatan.siswa', 'penempatan.perusahaan', 'penempatan.guru'])->get();

        return new StreamedResponse(function () use ($data) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, ['No', 'Nama Siswa', 'Perusahaan', 'Guru Pembimbing', 'Sikap', 'Keterampilan', 'Pengetahuan', 'Nilai Akhir', 'Catatan'], ';');

            foreach ($data as $i => $d) {
                fputcsv($handle, [
                    $i + 1,
                    $d->penempatan?->siswa?->nama ?? '-',
                    $d->penempatan?->perusahaan?->nama_perusahaan ?? '-',
                    $d->penempatan?->guru?->nama ?? '-',
                    $d->nilai_sikap,
                    $d->nilai_keterampilan,
                    $d->nilai_pengetahuan,
                    $d->nilai_akhir,
                    $d->catatan_guru ?? '-',
                ], ';');
            }
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function templateSiswa()
    {
        $filename = 'format_import_siswa.csv';

        return new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, ['nis', 'nisn', 'nama', 'jenis_kelamin', 'kelas', 'jurusan', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'no_hp', 'email'], ';');
            
            // Contoh baris
            fputcsv($handle, ['2026001', '0081234567', 'Ahmad Fauzan', 'L', 'XII RPL 1', 'Rekayasa Perangkat Lunak', 'Pekanbaru', '2008-05-14', 'Jl. HR Soebrantas No. 12', '081234567890', 'ahmad@siswa.com'], ';');
            fputcsv($handle, ['2026002', '0087654321', 'Siti Aisyah', 'P', 'XII TKJ 1', 'Teknik Komputer dan Jaringan', 'Kampar', '2008-08-20', 'Jl. Tuanku Tambusai No. 45', '081398765432', 'siti@siswa.com'], ';');
            
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function templateGuru()
    {
        $filename = 'format_import_guru.csv';

        return new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, ['nip', 'nama', 'jenis_kelamin', 'no_hp', 'alamat', 'email'], ';');
            fputcsv($handle, ['198904122019031005', 'Mahendra, S.Pd., M.Si.', 'L', '081275001122', 'Jl. Garuda Sakti Km. 2', 'mahendra.guru@smklabor.sch.id'], ';');
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function templatePerusahaan()
    {
        $filename = 'format_import_perusahaan.csv';

        return new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, ['nama_perusahaan', 'alamat', 'kota', 'no_telepon', 'email', 'website', 'nama_pimpinan'], ';');
            fputcsv($handle, ['PT TELKOM INDONESIA WITEL RIAU', 'Jl. Jend. Sudirman No. 199', 'Pekanbaru', '0761853000', 'info@telkomriau.co.id', 'https://telkom.co.id', 'Ir. Budi Hartono'], ';');
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function templateObservasi()
    {
        $export = new \App\Exports\LembarObservasiExport();
        return $export->download('template_lembar_observasi_pkl.xlsx');
    }

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
