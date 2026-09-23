<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiPkl extends Model
{
    protected $table = 'absensi_pkl';
    protected $guarded = [];

    public function penempatan()
    {
        return $this->belongsTo(Penempatan::class);
    }

    public function getSiswaAttribute()
    {
        return $this->penempatan?->siswa;
    }
}
