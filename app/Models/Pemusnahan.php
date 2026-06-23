<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pemusnahan extends Model
{
    use HasFactory;

    protected $table = 'pemusnahans';

    protected $fillable = [
        'no_surat',
        'nama_dokumen',
        'kategori',
        'kode_box_lama',
        'kode_rak_lama',
        'tanggal_pemusnahan',
        'eksekutor',
        'alasan'
    ];

    protected $casts = [
        'tanggal_pemusnahan' => 'datetime',
    ];
}