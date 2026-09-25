<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembimbingIndustri extends Model
{
    protected $table = 'pembimbing_industri';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class);
    }
}
