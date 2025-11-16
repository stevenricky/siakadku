<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Konseling extends Model
{
    protected $table = 'konseling';
    
    protected $fillable = [
        'siswa_id',
        'guru_id',
        'layanan_bk_id',
        'tanggal_konseling',
        'waktu_mulai',
        'waktu_selesai',
        'tempat',
        'permasalahan',
        'tindakan',
        'hasil',
        'status',
        'catatan'
    ];

    protected $casts = [
        'tanggal_konseling' => 'date',
        // Hapus casting untuk waktu_mulai dan waktu_selesai karena tipe data time di MySQL
    ];

    /**
     * Relasi ke siswa
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    /**
     * Relasi ke guru BK
     */
    public function guruBk(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    /**
     * Relasi ke layanan BK
     */
    public function layananBk(): BelongsTo
    {
        return $this->belongsTo(LayananBk::class);
    }

    /**
     * Scope untuk konseling aktif hari ini
     */
    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal_konseling', today());
    }

    /**
     * Scope untuk status tertentu
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}