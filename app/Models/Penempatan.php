<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penempatan extends Model
{
    protected $table = 'penempatan';

    protected $guarded = [];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class);
    }

    public function periodePkl()
    {
        return $this->belongsTo(PeriodePkl::class);
    }

    public function periode()
    {
        return $this->belongsTo(PeriodePkl::class, 'periode_pkl_id');
    }

    public function jurnal()
    {
        return $this->hasMany(JurnalPkl::class);
    }

    public function jurnalPkl()
    {
        return $this->hasMany(JurnalPkl::class);
    }

    public function absensi()
    {
        return $this->hasMany(AbsensiPkl::class);
    }

    public function absensiPkl()
    {
        return $this->hasMany(AbsensiPkl::class);
    }

    public function penilaian()
    {
        return $this->hasOne(PenilaianPkl::class);
    }

    public function penilaianPkl()
    {
        return $this->hasOne(PenilaianPkl::class);
    }
}
