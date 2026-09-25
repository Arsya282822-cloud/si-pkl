<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalPkl extends Model
{
    use HasFactory;

    protected $table = 'jurnal_pkl';

    protected $guarded = [];

    public function penempatan()
    {
        return $this->belongsTo(Penempatan::class);
    }
}
