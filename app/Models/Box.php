<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    protected $fillable = [
        'kode_box',
        'rak_id',
        'kapasitas',
    ];

    public function rak()
    {
        return $this->belongsTo(Rak::class, 'rak_id');
    }

    public function dokumens()
    {
        return $this->hasMany(Dokumen::class, 'box_id');
    }
}