<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GuruImport implements ToModel, WithHeadingRow
{
    /**
     * @return Model|null
     */
    public function model(array $row)
    {
        if (empty($row['nama'])) {
            return null;
        }

        $namaGuru = mb_strtoupper(trim($row['nama']));

        // Cari atau buat User berdasarkan email atau NIP
        $role = Role::where('nama_role', 'guru')->first();

        $email = ! empty($row['email']) ? $row['email'] : strtolower(str_replace(' ', '', $namaGuru)).'@guru.com';

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $namaGuru,
                'password' => bcrypt('guru1234'), // Password default guru
                'role_id' => $role->id ?? 2,
                'status' => 'aktif',
            ]
        );

        // Konversi jenis kelamin ke L atau P
        $jkRaw = strtolower(trim($row['jenis_kelamin'] ?? 'L'));
        $jenis_kelamin = 'L'; // Default
        if (in_array($jkRaw, ['p', 'perempuan', 'wanita'])) {
            $jenis_kelamin = 'P';
        }

        // Simpan data Guru
        return Guru::updateOrCreate(
            ['nip' => $row['nip'] ?? rand(100000000, 999999999)], // Gunakan NIP acak jika kosong agar tidak error unique
            [
                'user_id' => $user->id,
                'nama' => $row['nama'],
                'jenis_kelamin' => $jenis_kelamin,
                'alamat' => $row['alamat'] ?? null,
                'no_hp' => $row['no_hp'] ?? null,
            ]
        );
    }
}
