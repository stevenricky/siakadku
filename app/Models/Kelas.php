<?php

namespace App\Models;
use App\Models\Traits\ProtectedDataTrait;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kelas',
        'tingkat',
        'jurusan',
        'wali_kelas_id',
        'kapasitas',
    ];

    // Relationships
    public function waliKelas()
    {
        return $this->belongsTo(Guru::class, 'wali_kelas_id');
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class);
    }

    // Accessor untuk nama kelas lengkap
    public function getNamaLengkapAttribute()
    {
        return "Kelas {$this->nama_kelas} - {$this->jurusan}";
    }

    // Scope untuk kelas berdasarkan tingkat
    public function scopeTingkat($query, $tingkat)
    {
        return $query->where('tingkat', $tingkat);
    }

    // Scope untuk kelas berdasarkan jurusan
    public function scopeJurusan($query, $jurusan)
    {
        return $query->where('jurusan', $jurusan);
    }
// app/Models/Kelas.php - Tambah method ini
public function getInfoKelasAttribute()
{
    $waliKelas = $this->waliKelas ? $this->waliKelas->nama_lengkap : 'Belum ada wali kelas';
    $jumlahSiswa = $this->siswa()->count();
    $ketersediaan = $this->kapasitas - $jumlahSiswa;
    
    return "{$this->nama_kelas} (Wali: {$waliKelas}) - {$jumlahSiswa}/{$this->kapasitas} siswa";
}

// Scope untuk kelas yang masih ada kapasitas
public function scopeTersedia($query)
{
    return $query->whereRaw('kapasitas > (SELECT COUNT(*) FROM siswas WHERE siswas.kelas_id = kelas.id)');
}

}
