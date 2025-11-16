<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_mapel',
        'nama_mapel',
        'tingkat',
        'jurusan',
        'kkm',
        'deskripsi',
    ];

    // Relationships
    public function jadwal()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }

    // RELASI KE GURU (Many-to-Many)
    public function guru()
    {
        return $this->belongsToMany(Guru::class, 'guru_mapel', 'mapel_id', 'guru_id')
                    ->withTimestamps();
    }

    // Relasi dengan RPP
    public function rpp()
    {
        return $this->hasMany(Rpp::class, 'mapel_id');
    }

    // PERBAIKI: Relasi dengan Kelas melalui Jadwal (Many-to-Many)
    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'jadwals', 'mapel_id', 'kelas_id')
                    ->withTimestamps();
    }

    // Relasi dengan Tugas
    public function tugas()
    {
        return $this->hasMany(Tugas::class);
    }

    // Relasi dengan Ujian
    public function ujians()
    {
        return $this->hasMany(Ujian::class, 'mapel_id');
    }

    // Relasi dengan Materi
    public function materi()
    {
        return $this->hasMany(Materi::class);
    }

    // Scope untuk mapel berdasarkan jurusan
    public function scopeJurusan($query, $jurusan)
    {
        if ($jurusan) {
            return $query->where('jurusan', $jurusan)->orWhereNull('jurusan');
        }
        return $query;
    }

    // Scope untuk mapel berdasarkan tingkat
    public function scopeTingkat($query, $tingkat)
    {
        return $query->where('tingkat', $tingkat);
    }

    // Scope untuk mapel yang diampu oleh guru tertentu
    public function scopeDiampuOlehGuru($query, $guruId)
    {
        return $query->whereHas('guru', function($q) use ($guruId) {
            $q->where('guru_id', $guruId);
        });
    }

    // Scope untuk mapel berdasarkan kelas
    public function scopeByKelas($query, $kelasId)
    {
        return $query->whereHas('jadwal', function($q) use ($kelasId) {
            $q->where('kelas_id', $kelasId);
        });
    }

    // Accessor untuk nama mapel lengkap
    public function getNamaLengkapAttribute()
    {
        $nama = $this->nama_mapel;
        if ($this->tingkat) {
            $nama .= " (Kelas {$this->tingkat})";
        }
        if ($this->jurusan) {
            $nama .= " - {$this->jurusan}";
        }
        return $nama;
    }
}