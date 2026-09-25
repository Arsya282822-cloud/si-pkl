<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenilaianPkl extends Model
{
    protected $table = 'penilaian_pkl';

    protected $guarded = [];

    public function penempatan()
    {
        return $this->belongsTo(Penempatan::class);
    }
}
