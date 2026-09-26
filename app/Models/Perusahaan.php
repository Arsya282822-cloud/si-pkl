<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    protected $table = 'perusahaan';

    protected $fillable = ['nama_perusahaan', 'alamat', 'latitude', 'longitude', 'radius_meter', 'kota', 'no_telepon', 'email', 'website', 'nama_pimpinan', 'status'];

    public function penempatan()
    {
        return $this->hasMany(Penempatan::class);
    }

    public function pembimbingIndustri()
    {
        return $this->hasMany(PembimbingIndustri::class);
    }

    public function getNamaAttribute()
    {
        return $this->attributes['nama_perusahaan'] ?? ($this->attributes['nama'] ?? '');
    }
}
