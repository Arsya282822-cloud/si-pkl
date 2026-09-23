<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LembarObservasi extends Model
{
    protected $table = 'lembar_observasi';
    protected $guarded = [];

    protected $casts = [
        'data_softskills' => 'array',
        'data_kompetensi_teknis' => 'array',
        'data_kompetensi_baru' => 'array',
        'data_analisis_usaha' => 'array',
        'tgl_observasi_1' => 'date',
        'tgl_observasi_2' => 'date',
        'tgl_observasi_akhir' => 'date',
        'tgl_cetak' => 'date',
    ];

    public function penempatan()
    {
        return $this->belongsTo(Penempatan::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
