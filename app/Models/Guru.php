<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $table = 'guru';

    protected $fillable = ['user_id', 'nip', 'nama', 'jenis_kelamin', 'no_hp', 'alamat'];

    protected static function booted()
    {
        static::saving(function ($guru) {
            if (! empty($guru->nama)) {
                $guru->nama = mb_strtoupper(trim($guru->nama));
            }
        });
    }

    public function getNamaAttribute($value)
    {
        return mb_strtoupper((string) $value);
    }

    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = mb_strtoupper(trim((string) $value));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelasWali()
    {
        return $this->hasMany(Kelas::class, 'wali_kelas_id');
    }

    public function penempatan()
    {
        return $this->hasMany(Penempatan::class);
    }

    public function monitoring()
    {
        return $this->hasMany(Monitoring::class);
    }
}
