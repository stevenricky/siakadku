<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriInventaris extends Model
{
    protected $table = 'kategori_inventaris';
    
    protected $fillable = [
        'nama_kategori',
        'kode_kategori',
        'deskripsi',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function barang(): HasMany
    {
        return $this->hasMany(BarangInventaris::class, 'kategori_id');
    }
}