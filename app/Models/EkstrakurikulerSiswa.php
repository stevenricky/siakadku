<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class EkstrakurikulerSiswa extends Pivot
{
    protected $table = 'ekstrakurikuler_siswa';
    
    protected $fillable = [
        'ekstrakurikuler_id',
        'siswa_id',
        'status',
        'tanggal_daftar'
    ];
    
    protected $casts = [
        'tanggal_daftar' => 'datetime',
        'status' => 'string'
    ];
}