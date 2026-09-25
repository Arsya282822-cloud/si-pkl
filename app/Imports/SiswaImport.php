<?php

namespace App\Imports;

use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Role;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class SiswaImport implements ToModel, WithHeadingRow
{
    /**
     * @return Model|null
     */
    public function model(array $row)
    {
        $rawKelas = strtoupper(trim($row['kelas'] ?? ''));
        $rawJurusan = strtoupper(trim($row['jurusan'] ?? ''));

        // Deteksi kode standar SMK Labor (TKJ, RPL, AK, MP, BR) dari kolom jurusan maupun kelas
        $combined = $rawKelas.' '.$rawJurusan;
        if (str_contains($combined, 'TKJ') || str_contains($combined, 'JARINGAN')) {
            $kodeJurusan = 'TKJ';
            $namaJurusan = 'Teknik Komputer dan Jaringan';
            $namaKelas = 'XII TKJ';
        } elseif (str_contains($combined, 'RPL') || str_contains($combined, 'PERANGKAT LUNAK') || str_contains($combined, 'PPLG')) {
            $kodeJurusan = 'RPL';
            $namaJurusan = 'Rekayasa Perangkat Lunak';
            $namaKelas = 'XII RPL';
        } elseif (str_contains($combined, 'AK') || str_contains($combined, 'AKUNTANSI')) {
            $kodeJurusan = 'AK';
            $namaJurusan = 'Akuntansi dan Keuangan Lembaga';
            $namaKelas = 'XII AK';
        } elseif (str_contains($combined, 'MP') || str_contains($combined, 'PERKANTORAN') || str_contains($combined, 'OTKP')) {
            $kodeJurusan = 'MP';
            $namaJurusan = 'Manajemen Perkantoran dan Layanan Bisnis';
            $namaKelas = 'XII MP';
        } else {
            $kodeJurusan = 'BR';
            $namaJurusan = 'Bisnis Retail';
            $namaKelas = 'XII BR';
        }

        $jurusan = Jurusan::firstOrCreate(
            ['kode_jurusan' => $kodeJurusan],
            ['nama_jurusan' => $namaJurusan, 'status' => true]
        );

        $kelas = Kelas::firstOrCreate(
            ['nama_kelas' => $namaKelas],
            ['jurusan_id' => $jurusan->id, 'tingkat' => 'XII', 'status' => true]
        );

        $namaSiswa = mb_strtoupper(trim($row['nama'] ?? ''));

        // Cari atau buat User berdasarkan email atau NIS
        $email = ! empty($row['email']) ? $row['email'] : $row['nis'].'@smklabor.sch.id';
        $roleId = Role::where('nama_role', 'siswa')->value('id') ?? 3;

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $namaSiswa,
                'password' => Hash::make($row['nis']), // Password default adalah NIS
                'role_id' => $roleId,
            ]
        );

        // Konversi format tanggal excel jika perlu
        $tanggal_lahir = null;
        if (isset($row['tanggal_lahir'])) {
            if (is_numeric($row['tanggal_lahir'])) {
                $tanggal_lahir = Date::excelToDateTimeObject($row['tanggal_lahir'])->format('Y-m-d');
            } else {
                $tanggal_lahir = date('Y-m-d', strtotime($row['tanggal_lahir']));
            }
        }

        // Konversi jenis kelamin ke L atau P
        $jkRaw = strtolower(trim($row['jenis_kelamin'] ?? 'L'));
        $jenis_kelamin = 'L'; // Default
        if (in_array($jkRaw, ['p', 'perempuan', 'wanita'])) {
            $jenis_kelamin = 'P';
        }

        // Simpan data Siswa
        return Siswa::updateOrCreate(
            ['nis' => $row['nis']], // Kunci pencarian agar tidak ganda
            [
                'user_id' => $user->id,
                'kelas_id' => $kelas->id,
                'jurusan_id' => $jurusan->id,
                'nisn' => $row['nisn'] ?? null,
                'nama' => $row['nama'],
                'jenis_kelamin' => $jenis_kelamin,
                'tempat_lahir' => $row['tempat_lahir'] ?? null,
                'tanggal_lahir' => $tanggal_lahir,
                'alamat' => $row['alamat'] ?? null,
                'no_hp' => $row['no_hp'] ?? null,
            ]
        );
    }
}
