<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatatanBk extends Model
{
    protected $table = 'catatan_bk';
    
    protected $fillable = [
        'siswa_id',
        'guru_id',
        // Hapus layanan_bk_id karena tidak ada di database
        'tanggal_catatan',
        'jenis_catatan',
        'deskripsi',
        'tingkat_keparahan',
        'tindak_lanjut',
        'status_selesai'
    ];

    protected $casts = [
        'tanggal_catatan' => 'date',
        'status_selesai' => 'boolean'
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
     * Relasi ke layanan BK - HAPUS karena tidak ada foreign key
     */
    // public function layananBk(): BelongsTo
    // {
    //     return $this->belongsTo(LayananBk::class);
    // }

    /**
     * Scope untuk catatan aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status_selesai', false);
    }

    /**
     * Scope untuk tingkat keparahan tertentu
     */
    public function scopeTingkat($query, $tingkat)
    {
        return $query->where('tingkat_keparahan', $tingkat);
    }
}