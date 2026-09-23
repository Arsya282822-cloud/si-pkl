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
