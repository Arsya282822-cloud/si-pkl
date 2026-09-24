<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';
    protected $fillable = ['user_id', 'kelas_id', 'jurusan_id', 'nis', 'nisn', 'nama', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'no_hp'];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    protected static function booted()
    {
        static::saving(function ($siswa) {
            if (!empty($siswa->nama)) {
                $siswa->nama = mb_strtoupper(trim($siswa->nama));
            }
            if ($siswa->kelas_id && (!$siswa->jurusan_id || $siswa->isDirty('kelas_id'))) {
                $kelas = Kelas::find($siswa->kelas_id);
                if ($kelas && $kelas->jurusan_id) {
                    $siswa->jurusan_id = $kelas->jurusan_id;
                }
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

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function penempatan()
    {
        return $this->hasMany(Penempatan::class);
    }

    public function pengajuan()
    {
        return $this->hasMany(PengajuanPkl::class);
    }
}
