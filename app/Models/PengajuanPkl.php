<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanPkl extends Model
{
    protected $table = 'pengajuan_pkl';

    protected $fillable = [
        'siswa_id',
        'periode_pkl_id',
        'nama_perusahaan',
        'bidang_usaha',
        'alamat_perusahaan',
        'kota',
        'nama_pimpinan',
        'kontak_person',
        'no_telepon',
        'email',
        'alasan_memilih',
        'file_surat_balasan',
        'status',
        'catatan_verifikasi',
        'diverifikasi_oleh',
        'diverifikasi_pada',
    ];

    protected $casts = [
        'diverifikasi_pada' => 'datetime',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function periode()
    {
        return $this->belongsTo(PeriodePkl::class, 'periode_pkl_id');
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }
}
