<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelas_id',
        'mapel_id',
        'guru_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'ruangan',
    ];

    // Relationships
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    // Accessor untuk jam pelajaran
    public function getJamPelajaranAttribute()
    {
        return $this->jam_mulai . ' - ' . $this->jam_selesai;
    }

    // Scope untuk jadwal berdasarkan hari
    public function scopeHari($query, $hari)
    {
        return $query->where('hari', $hari);
    }
}