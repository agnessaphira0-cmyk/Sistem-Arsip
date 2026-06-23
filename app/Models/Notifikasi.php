<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $fillable = [
        'admin_id',
        'pesan',
        'status_baca',
        'tgl_notif',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}