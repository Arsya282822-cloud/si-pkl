<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Jurusan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class SiswaImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Generate kode_jurusan dari singkatan (misal: "Bisnis Retail" -> "BR")
        $kodeJurusan = collect(explode(' ', trim($row['jurusan'])))
            ->map(function($word) { return strtoupper(substr($word, 0, 1)); })
            ->join('');
        
        if (empty($kodeJurusan) || strlen($kodeJurusan) < 2) {
            $kodeJurusan = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $row['jurusan']), 0, 3));
        }

        // Cari atau buat Jurusan
        $jurusan = Jurusan::firstOrCreate(
            ['nama_jurusan' => $row['jurusan']],
            ['kode_jurusan' => $kodeJurusan]
        );

        // Tentukan tingkat berdasarkan kata pertama dari nama kelas (misal: "XII RPL 1" -> "XII")
        $tingkat = explode(' ', trim($row['kelas']))[0] ?? 'XII';
        // Konversi 10, 11, 12 ke romawi jika perlu (sebagai fallback sederhana)
        $tingkatMap = ['10' => 'X', '11' => 'XI', '12' => 'XII'];
        if (array_key_exists($tingkat, $tingkatMap)) {
            $tingkat = $tingkatMap[$tingkat];
        }

        // Cari atau buat Kelas
        $kelas = Kelas::firstOrCreate(
            ['nama_kelas' => $row['kelas']],
            ['jurusan_id' => $jurusan->id, 'tingkat' => $tingkat]
        );

        // Cari atau buat User berdasarkan email atau NIS
        $email = !empty($row['email']) ? $row['email'] : $row['nis'] . '@smklabor.sch.id';
        $roleId = \App\Models\Role::where('nama_role', 'siswa')->value('id') ?? 3;
        
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $row['nama'],
                'password' => Hash::make($row['nis']), // Password default adalah NIS
                'role_id' => $roleId
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
