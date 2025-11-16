<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alumni extends Model
{
    protected $table = 'alumni';
    
    protected $fillable = [
        'siswa_id',
        'tahun_lulus',
        'no_ijazah',
        'status_setelah_lulus',
        'instansi',
        'jurusan_kuliah',
        'jabatan_pekerjaan',
        'alamat_instansi',
        'no_telepon',
        'email',
        'prestasi_setelah_lulus',
        'status_aktif'
    ];

    protected $casts = [
        'status_aktif' => 'boolean'
    ];

    /**
     * Relasi ke siswa
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    /**
     * Scope untuk alumni aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status_aktif', true);
    }

    /**
     * Scope untuk status tertentu
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status_setelah_lulus', $status);
    }

    /**
     * Scope untuk tahun lulus tertentu
     */
    public function scopeTahunLulus($query, $tahun)
    {
        return $query->where('tahun_lulus', $tahun);
    }
}