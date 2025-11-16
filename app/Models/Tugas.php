<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;

    protected $fillable = [
        'mapel_id',
        'guru_id',
        'judul',
        'deskripsi',
        'deadline',
        'tipe',
        'instruksi',
        'file',
        'max_score',
        'is_published',
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'is_published' => 'boolean',
    ];

    // Relasi dengan Mata Pelajaran
    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    // Relasi dengan Guru
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    // Relasi dengan TugasSiswa (Many to Many)
    public function tugasSiswa()
    {
        return $this->hasMany(TugasSiswa::class);
    }

    // Relasi dengan Siswa melalui TugasSiswa
    public function siswa()
    {
        return $this->belongsToMany(Siswa::class, 'tugas_siswa')
                    ->withPivot('jawaban', 'file_jawaban', 'nilai', 'komentar', 'submitted_at', 'status')
                    ->withTimestamps();
    }

    // Accessor untuk file URL
    public function getFileUrlAttribute()
    {
        return $this->file ? asset('storage/tugas/' . $this->file) : null;
    }

    // Accessor untuk status deadline
    public function getStatusAttribute()
    {
        if (now()->gt($this->deadline)) {
            return 'expired';
        } elseif (now()->diffInHours($this->deadline) <= 24) {
            return 'urgent';
        } else {
            return 'active';
        }
    }

    // Accessor untuk badge color berdasarkan status
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'expired' => 'red',
            'urgent' => 'orange',
            'active' => 'green',
            default => 'gray'
        };
    }

    // Accessor untuk badge color berdasarkan tipe
    public function getTipeColorAttribute()
    {
        return match($this->tipe) {
            'tugas' => 'blue',
            'kuis' => 'purple',
            'ulangan' => 'red',
            default => 'gray'
        };
    }

    // Accessor untuk waktu relatif deadline
    public function getDeadlineRelativeAttribute()
    {
        return $this->deadline->diffForHumans();
    }

    // Method untuk cek apakah siswa sudah mengumpulkan
    public function sudahDikumpulkan($siswaId)
    {
        return $this->tugasSiswa()
                    ->where('siswa_id', $siswaId)
                    ->where('status', '!=', 'belum')
                    ->exists();
    }

    // Method untuk mendapatkan pengumpulan siswa tertentu
    public function getPengumpulanSiswa($siswaId)
    {
        return $this->tugasSiswa()
                    ->where('siswa_id', $siswaId)
                    ->first();
    }

    // Scope untuk tugas yang aktif (belum expired)
    public function scopeActive($query)
    {
        return $query->where('deadline', '>', now());
    }

    // Scope untuk tugas yang published
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    // Scope untuk tugas berdasarkan kelas
    public function scopeByKelas($query, $kelasId)
    {
        return $query->whereHas('mapel.jadwal', function($q) use ($kelasId) {
            $q->where('kelas_id', $kelasId);
        });
    }

    // Scope untuk tugas oleh guru tertentu
    public function scopeByGuru($query, $guruId)
    {
        return $query->where('guru_id', $guruId);
    }
}