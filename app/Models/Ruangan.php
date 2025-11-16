<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_ruangan',
        'nama_ruangan',
        'gedung',
        'kapasitas',
        'fasilitas',
        'status'
    ];

    protected $casts = [
        'kapasitas' => 'integer',
        'status' => 'boolean'
    ];

    // Relasi dengan Jadwal
    public function jadwal()
    {
        return $this->hasMany(Jadwal::class);
    }

    // Scope untuk ruangan aktif
    public function scopeAktif($query)
    {
        return $query->where('status', true);
    }

    // Accessor untuk nama lengkap
    public function getNamaLengkapAttribute()
    {
        return "{$this->nama_ruangan} ({$this->gedung})";
    }
}