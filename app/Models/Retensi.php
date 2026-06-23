<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Retensi extends Model
{
    protected $fillable = [
        'dokumen_id',
        'tgl_mulai',
        'tgl_kadaluarsa',
        'status',
        'ket_retensi',
    ];

    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class, 'dokumen_id');
    }
}