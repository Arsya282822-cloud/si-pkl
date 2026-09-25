<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $table = 'pengumuman';

    protected $fillable = [
        'author_id',
        'judul',
        'konten',
        'kategori',
        'target_role',
        'is_pinned',
        'file_lampiran',
        'status',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeForRole($query, $roleName)
    {
        return $query->where(function ($q) use ($roleName) {
            $q->where('target_role', 'semua')
                ->orWhere('target_role', $roleName);
        });
    }
}
