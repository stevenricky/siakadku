<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nip',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_telp',
        'status'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelasWali()
    {
        return $this->hasOne(Kelas::class, 'wali_kelas_id');
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }

    // TAMBAHKAN RELASI KE MAPEL (Many-to-Many)
    public function mapel()
    {
        return $this->belongsToMany(Mapel::class, 'guru_mapel', 'guru_id', 'mapel_id')
                    ->withTimestamps();
    }

    // Relasi dengan RPP
    public function rpp()
    {
        return $this->hasMany(Rpp::class, 'guru_id');
    }

    // Accessor untuk menampilkan jenis kelamin lengkap (HANYA untuk display)
    public function getJenisKelaminDisplayAttribute()
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    // Method untuk mengecek apakah guru mengampu mapel tertentu
    public function mengampuMapel($mapelId)
    {
        return $this->mapel()->where('mapel_id', $mapelId)->exists();
    }

    // Scope untuk guru aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}