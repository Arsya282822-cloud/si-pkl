<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodePkl extends Model
{
    protected $table = 'periode_pkl';
    protected $fillable = ['nama_periode', 'tanggal_mulai', 'tanggal_selesai', 'tahun_ajaran', 'status'];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];
    public function penempatan()
    {
        return $this->hasMany(Penempatan::class);
    }
}
