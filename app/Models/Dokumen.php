<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    protected $fillable = [
        'no_surat',
        'nama_dokumen',
        'kategori',
        'box_id',
        'tgl_masuk',
        'jenis',
        'file_path',
        'keterangan',
    ];

    public function box()
    {
        return $this->belongsTo(Box::class, 'box_id');
    }

    public function retensi()
    {
        return $this->hasOne(Retensi::class, 'dokumen_id');
    }
}