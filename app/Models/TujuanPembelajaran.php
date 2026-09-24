<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TujuanPembelajaran extends Model
{
    use HasFactory;

    protected $table = 'tujuan_pembelajaran';

    protected $fillable = [
        'jurusan_id',
        'kode_jurusan',
        'konsentrasi_keahlian',
        'capaian_pembelajaran',
        'nomor_urut',
        'tujuan_pembelajaran',
        'tahun',
        'status',
    ];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeByJurusan($query, $kodeJurusan)
    {
        return $query->where('kode_jurusan', $kodeJurusan);
    }
}
