<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pks extends Model
{
    protected $table = 'pks';

    protected $fillable = [
        'vld',
        'jenis_kerjasama',
        'dunia_usaha_industri',
        'nama_dudi',
        'nomor_pks',
        'judul_pks',
        'tgl_mulai',
        'tgl_selesai',
        'npwp_dudi',
        'nama_bidang_usaha',
        'telp_kantor',
        'fax',
        'contact_person',
        'telepon_cp',
        'jabatan_cp',
    ];

    protected $casts = [
        'tgl_mulai' => 'date',
        'tgl_selesai' => 'date',
    ];
}
