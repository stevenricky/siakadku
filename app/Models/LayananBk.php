<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LayananBk extends Model
{
    protected $table = 'layanan_bk';
    
    protected $fillable = [
        'nama_layanan',
        'deskripsi',
        'jenis_layanan',
        'sasaran',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    /**
     * Relasi ke jadwal konseling
     */
    public function konseling(): HasMany
    {
        return $this->hasMany(Konseling::class, 'layanan_bk_id');
    }

    /**
     * Relasi ke catatan BK
     */
    public function catatanBk(): HasMany
    {
        return $this->hasMany(CatatanBk::class, 'layanan_bk_id');
    }
}