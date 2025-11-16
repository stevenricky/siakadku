<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KegiatanEkskul extends Model
{
    protected $table = 'kegiatan_ekskul';
    
    protected $fillable = [
        'ekstrakurikuler_id',
        'nama_kegiatan',
        'deskripsi',
        'tanggal_kegiatan',
        'waktu_mulai',
        'waktu_selesai',
        'tempat',
        'pembina', // Opsional - bisa dari relasi atau manual
        'hasil_kegiatan',
        'dokumentasi',
        'status'
    ];

    protected $casts = [
        'tanggal_kegiatan' => 'date',
        'waktu_mulai' => 'datetime:H:i',
        'waktu_selesai' => 'datetime:H:i',
    ];

    public function ekstrakurikuler(): BelongsTo
    {
        return $this->belongsTo(Ekstrakurikuler::class);
    }

    // Accessor untuk mendapatkan pembina dari relasi jika tidak diisi manual
    public function getPembinaEkskulAttribute()
    {
        return $this->pembina ?: $this->ekstrakurikuler->pembina;
    }

    // Accessor untuk waktu kegiatan
    public function getWaktuKegiatanAttribute()
    {
        return $this->waktu_mulai->format('H:i') . ' - ' . $this->waktu_selesai->format('H:i');
    }

    // Accessor untuk tanggal format Indonesia
    public function getTanggalFormatAttribute()
    {
        return $this->tanggal_kegiatan->translatedFormat('d F Y');
    }

    // Accessor untuk hari
    public function getHariAttribute()
    {
        return $this->tanggal_kegiatan->translatedFormat('l');
    }

    // Accessor untuk status badge
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'terlaksana' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            'dibatalkan' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            'ditunda' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
        };
    }

    // Accessor untuk status text
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'terlaksana' => 'Terlaksana',
            'dibatalkan' => 'Dibatalkan',
            'ditunda' => 'Ditunda',
            default => $this->status
        };
    }
}