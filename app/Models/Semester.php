<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_semester',
        'tahun_ajaran_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_aktif'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_aktif' => 'boolean'
    ];

    // Relasi dengan Tahun Ajaran
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    // Relasi dengan Nilai
    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }

    // Relasi dengan Jadwal
    public function jadwal()
    {
        return $this->hasMany(Jadwal::class);
    }

    // Scope untuk semester aktif
    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }

    // Accessor untuk nama lengkap
    public function getNamaLengkapAttribute()
    {
        return "Semester {$this->nama_semester} - {$this->tahunAjaran->tahun_ajaran}";
    }
}