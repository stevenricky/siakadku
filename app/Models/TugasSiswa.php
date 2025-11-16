<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasSiswa extends Model
{
    use HasFactory;

    protected $table = 'tugas_siswa';

    protected $fillable = [
        'tugas_id',
        'siswa_id',
        'jawaban',
        'file_jawaban',
        'nilai',
        'komentar',
        'submitted_at',
        'dinilai_at',
        'status'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'dinilai_at' => 'datetime',
    ];

    // Relasi dengan Tugas
    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }

    // Relasi dengan Siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    // Accessor untuk file jawaban URL
    public function getFileJawabanUrlAttribute()
    {
        return $this->file_jawaban ? asset('storage/tugas_jawaban/' . $this->file_jawaban) : null;
    }

    // Accessor untuk status color
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'belum' => 'gray',
            'dikumpulkan' => 'blue',
            'dinilai' => 'green',
            'terlambat' => 'red',
            default => 'gray'
        };
    }

    // Accessor untuk status text
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'belum' => 'Belum Dikumpulkan',
            'dikumpulkan' => 'Sudah Dikumpulkan',
            'dinilai' => 'Sudah Dinilai',
            'terlambat' => 'Terlambat',
            default => 'Unknown'
        };
    }

    // Method untuk menandai sebagai dikumpulkan
    public function markAsSubmitted()
    {
        $this->update([
            'submitted_at' => now(),
            'status' => now()->gt($this->tugas->deadline) ? 'terlambat' : 'dikumpulkan'
        ]);
    }

    // Method untuk menilai tugas
    public function beriNilai($nilai, $komentar = null)
    {
        $this->update([
            'nilai' => $nilai,
            'komentar' => $komentar,
            'dinilai_at' => now(),
            'status' => 'dinilai'
        ]);
    }
}