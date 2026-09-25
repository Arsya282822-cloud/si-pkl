<?php

namespace App\Imports;

use App\Models\Perusahaan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PerusahaanImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new Perusahaan([
            'nama_perusahaan' => $row['nama_perusahaan'],
            'alamat' => $row['alamat'] ?? '-',
            'kota' => $row['kota'] ?? null,
            'no_telepon' => $row['no_telepon'] ?? null,
            'email' => $row['email'] ?? null,
            'website' => $row['website'] ?? null,
            'nama_pimpinan' => $row['nama_pimpinan'] ?? null,
            'status' => strtolower($row['status'] ?? 'aktif'),
        ]);
    }

    public function rules(): array
    {
        return [
            'nama_perusahaan' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:255',
            'no_telepon' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'nama_pimpinan' => 'nullable|string|max:255',
            'status' => 'nullable|in:aktif,nonaktif,AKTIF,NONAKTIF,Aktif,Nonaktif',
        ];
    }
}
