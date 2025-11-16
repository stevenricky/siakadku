<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    use HasFactory;

    // Tentukan nama tabel secara eksplisit
    protected $table = 'ujian';

    protected $fillable = [
        'mapel_id',
        'guru_id',
        'judul',
        'deskripsi',
        'waktu_mulai',
        'waktu_selesai',
        'durasi',
        'jumlah_soal',
        'keterangan',
        'is_active',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'durasi' => 'integer',
        'jumlah_soal' => 'integer',
        'is_active' => 'boolean',
    ];

    // Relasi dengan Mata Pelajaran
    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }

    // Relasi dengan Guru
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    // Scope untuk ujian aktif
    public function scopeAktif($query)
    {
        return $query->where('is_active', true)
                    ->where('waktu_mulai', '<=', now())
                    ->where('waktu_selesai', '>=', now());
    }

    // Scope untuk ujian berdasarkan mapel
    public function scopeByMapel($query, $mapelId)
    {
        return $query->where('mapel_id', $mapelId);
    }

    // Scope untuk ujian yang bisa diakses siswa berdasarkan kelas
    public function scopeUntukKelas($query, $kelasId)
    {
        return $query->whereHas('mapel.jadwal', function($q) use ($kelasId) {
            $q->where('kelas_id', $kelasId);
        });
    }
}