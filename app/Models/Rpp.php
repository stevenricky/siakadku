<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rpp extends Model
{
    use HasFactory;

    protected $table = 'rpp';
    
    protected $fillable = [
        'mapel_id',
        'guru_id',
        'judul',
        'kompetensi_dasar',
        'tujuan_pembelajaran',
        'materi',
        'metode',
        'media',
        'langkah_kegiatan',
        'penilaian',
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
}